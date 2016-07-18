<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_GRN';
$path_to_root = "..";
include_once($path_to_root . "/purchasing/includes/po_class.inc");

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_db.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/simplex/purchasing/includes/ui/ui_funcs.inc");
include_once($path_to_root . "/simplex/includes/email_messaging.inc");
include_once($path_to_root . "/simplex/sales/includes/db/confirm_order_db.inc");
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Confirm Purchase Order Items"), false, false, "", $js);
global $nonfin_audit_trail;
//---------------------------------------------------------------------------------------------------------------

if (isset($_GET['AddedID']))
{
	$grn = $_GET['AddedID'];
	$trans_type = ST_SUPPRECEIVE;

	display_notification_centered(_("Purchase Order has been processed"));

	display_note(get_trans_view_str($trans_type, $grn, _("&View this Delivery")));

	//hyperlink_params("$path_to_root/purchasing/supplier_invoice.php", _("Entry purchase &invoice for this receival"), "New=1");

	hyperlink_no_params("$path_to_root/purchasing/po_search.php", _("Select a different &purchase order for receiving items against"));

	display_footer_exit();
}

//--------------------------------------------------------------------------------------------------

if ((!isset($_GET['PONumber']) || $_GET['PONumber'] == 0) && !isset($_SESSION['PO']))
{
	die (_("This page can only be opened if a purchase order has been selected. Please select a purchase order first."));
}

//--------------------------------------------------------------------------------------------------
function get_facevalue($stock_id)
{
//and sales_order_no =0
	//$sql = "SELECT count(pin) FROM ".TB_PREF."pin_details where status = 'N' and flg_mnt_status ='A'  and stock_id= ". db_escape($stock_id) ;
	$sql = "SELECT facevalue FROM ".TB_PREF."stock_master where stock_id =". db_escape($stock_id) ;
	//echo $sql;
    $result = db_query($sql, "Can not look up stock id");
    $row = db_fetch_row_r($result);
    return $row[0];
}
//--------------------------------------------------------------------------------------------------

function display_po_receive_items()
{
	global $table_style;

	div_start('grn_items');
    start_table("colspan=7 $table_style width=90%");
    $th = array(_("Item Code"), _("Description"), _("Ordered"), _("Units"), _("Received"),
    	_("Outstanding"));  //, _("Price"), _("Total")
    table_header($th);

    /*show the line items on the order with the quantity being received for modification */

    $total = 0;
    $k = 0; //row colour counter

    if (count($_SESSION['PO']->line_items)> 0 )
    {
       	foreach ($_SESSION['PO']->line_items as $ln_itm)
       	{

			alt_table_row_color($k);

    		$qty_outstanding = $ln_itm->quantity - $ln_itm->qty_received;

 			if (!isset($_POST['Update']) && !isset($_POST['ProcessGoodsReceived']) && $ln_itm->receive_qty == 0)
    	  	{   //If no quantites yet input default the balance to be received
    	    	$ln_itm->receive_qty = $qty_outstanding;
    		}

    		$line_total = ($ln_itm->receive_qty * $ln_itm->price);
    		$total += $line_total;

			label_cell($ln_itm->stock_id);
			if ($qty_outstanding > 0)
				text_cells(null, $ln_itm->stock_id . "Desc", $ln_itm->item_description, 30, 50);
			else
				label_cell($ln_itm->item_description);
			$dec = get_qty_dec($ln_itm->stock_id);
			qty_cell($ln_itm->quantity, false, $dec);
			label_cell($ln_itm->units);
			qty_cell($ln_itm->qty_received, false, $dec);
			qty_cell($qty_outstanding, false, $dec);

			if ($qty_outstanding > 0)
			{
				//label_cells(null, $ln_itm->line_no, number_format2($ln_itm->receive_qty, $dec), "align=right", null, $dec);
				hidden($ln_itm->line_no,$ln_itm->receive_qty);
			}
//				qty_cells(null, $ln_itm->line_no, number_format2($ln_itm->receive_qty, $dec), "align=right", null, $dec);
				
			//else
			//	label_cell(number_format2($ln_itm->receive_qty, $dec), "align=right");

			//amount_decimal_cell($ln_itm->price);
			//amount_cell($line_total);
			end_row();
       	}
    }

    $display_total = number_format2($total,user_price_dec());
   // label_row(_("Total value of items received"), $display_total, "colspan=8 align=right",    	"nowrap align=right");
    end_table();
	div_end();
}

//--------------------------------------------------------------------------------------------------
function GetPOData($transid,$po_number)
{
$sql = "SELECT 	po.order_no,po.reference,po.ord_date,
				po.delivery_address,po.status
	FROM "
		.TB_PREF."purch_orders po, "
		.TB_PREF."purch_order_details line
	WHERE po.order_no = line.order_no
	AND po.order_no = ". $po_number .
	" AND line.po_detail_item=".$transid ; 
	echo $sql; 
	$sql_a = db_query($sql);
	 $result = db_fetch($sql_a);
	 return $result;
}
//--------------------------------------------------------------------------------------------------

function check_po_changed()
{
	/*Now need to check that the order details are the same as they were when they were read into the Items array. If they've changed then someone else must have altered them */
	// Sherifoz 22.06.03 Compare against COMPLETED items only !!
	// Otherwise if you try to fullfill item quantities separately will give error.
	$sql = "SELECT item_code, quantity_ordered, quantity_received, qty_invoiced
		FROM ".TB_PREF."purch_order_details
		WHERE order_no=".db_escape($_SESSION['PO']->order_no)
		." ORDER BY po_detail_item";

	$result = db_query($sql, "could not query purch order details");
    check_db_error("Could not check that the details of the purchase order had not been changed by another user ", $sql);

	$line_no = 1;
	while ($myrow = db_fetch($result))
	{
		$ln_item = $_SESSION['PO']->line_items[$line_no];
		// only compare against items that are outstanding
		$qty_outstanding = $ln_item->quantity - $ln_item->qty_received;
		if ($qty_outstanding > 0)
		{
    		if ($ln_item->qty_inv != $myrow["qty_invoiced"]	||
    			$ln_item->stock_id != $myrow["item_code"] ||
    			$ln_item->quantity != $myrow["quantity_ordered"] ||
    			$ln_item->qty_received != $myrow["quantity_received"])
    		{
    			return true;
    		}
		}
	 	$line_no++;
	} /*loop through all line items of the order to ensure none have been invoiced */

	return false;
}

function ins_rcvmailer_job($order_no, $cust_id, $cust_name, $line_no, $qty, $stock_id, $delivery_address,$cust_phone,$email)
{
	$myrow = get_company_prefs();
//	if(isset ($_POST['jobflg'] )
	$jobflag = 1; //$_POST['jobflg'];
//	if($jobflag
	$filename = "";//$cust_id . "_" . $order_no ."_" .$line_no . date('DMY'). ".txt";
	if($myrow["filename_fmt_deal_code"]  != "0")
		$filename .= $cust_id. "_";
	if($myrow["filename_fmt_deal_name"] != "0")
		$filename .= $cust_name. "_";
	
	if($myrow["filename_fmt_gendate"]  != "0")
		$filename .= date('DMY'). "_" ;
		
		$filename .= $order_no ."_" .$line_no ;
		$filename .= ".txt";
		$facevalue = get_facevalue($stock_id);
	$sql = "INSERT INTO ".TB_PREF."rcv_mailer_jobs	(id,order_no,line_no,supplier_no,supplier_name,quantity,stock_id,
				delivery_address,contact_phone,contact_email,logged_date,logged_by, status,filename,denomination,sold_from_loc,job_flag)
				VALUES (PIN_MAILER_ID_SEQ.NEXTVAL,". $order_no. ",". $line_no . "," . db_escape($cust_id). "," . db_escape($cust_name). ",". $qty . "," . db_escape($stock_id) . "," . db_escape($delivery_address) . "," . db_escape($cust_phone) . ",". db_escape($email) .          ",SYSDATE," . db_escape($_SESSION["wa_current_user"]->loginname). ",'L',". db_escape($filename). 
				", ". $facevalue. "," .  db_escape($_POST['Location']) . ",". db_escape($jobflag).")";  //$_POST['jobflg']
							
					$res = db_query($sql, "Could not insert into rcv_mailer_jobs");
					$err = oci_error($res);  
					return $err;
	//

}
//------------------------------------------------------------------------------------
function get_rcv_qty_in_stock($stock_id)
{
//and sales_order_no =0

	//$sql = "SELECT count(pin) FROM ".TB_PREF."pin_details where status = 'N' and flg_mnt_status ='A'  and stock_id= ". db_escape($stock_id) ;
$sql = "SELECT qty_in_stock FROM ".TB_PREF."denom_sequence_control where item_type = 'RCV' and denomination= (select facevalue from stock_master where stock_id= ". db_escape($stock_id) . ")" ;
	//$sql = "SELECT qty_in_stock FROM ".TB_PREF."denom_sequence_control where stock_id= ". db_escape($stock_id) ;
	//echo $sql;
    $result = db_query($sql, "Can not look up stock count");
    $row = db_fetch_row_r($result);
    return $row[0];
}
//--------------------------------------------------------------------------------------------------

function can_process()
{
	global $SysPrefs, $Refs;
	
	if (count($_SESSION['PO']->line_items) <= 0)
	{
        display_error(_("There is nothing to process. Please enter valid quantities greater than zero."));
    	return false;
	}

	if (!is_date($_POST['DefaultReceivedDate']))
	{
		display_error(_("The entered date is invalid."));
		set_focus('DefaultReceivedDate');
		return false;
	}

    if (!$Refs->is_valid($_POST['ref']))
    {
		display_error(_("You must enter a reference."));
		set_focus('ref');
		return false;
	}

	if (!is_new_reference($_POST['ref'], ST_SUPPRECEIVE))
	{
		display_error(_("The entered reference is already in use."));
		set_focus('ref');
		return false;
	}

	$something_received = 0;
	foreach ($_SESSION['PO']->line_items as $order_line)
	{
	  	if ($order_line->receive_qty > 0)
	  	{
			$something_received = 1;
			break;
	  	}
	}

    // Check whether trying to deliver more items than are recorded on the actual purchase order (+ overreceive allowance)
    $delivery_qty_too_large = 0;
	foreach ($_SESSION['PO']->line_items as $order_line)
	{
	  	if ($order_line->receive_qty+$order_line->qty_received >
	  		$order_line->quantity * (1+ ($SysPrefs->over_receive_allowance() / 100)))
	  	{
			$delivery_qty_too_large = 1;
			break;
	  	}
	}

    if ($something_received == 0)
    { 	/*Then dont bother proceeding cos nothing to do ! */
        display_error(_("There is nothing to process. Please enter valid quantities greater than zero."));
    	return false;
    }
    elseif ($delivery_qty_too_large == 1)
    {
    	display_error(_("Entered quantities cannot be greater than the quantity entered on the purchase order including the allowed over-receive percentage") . " (" . $SysPrefs->over_receive_allowance() ."%)."
    		. "<br>" .
    	 	_("Modify the ordered items on the purchase order if you wish to increase the quantities."));
    	return false;
    }

	return true;
}
//-----------------------------------------------------------------------------
function dispatch_to_supplier()
{
		
		foreach ($_SESSION['PO']->line_items as $ln_itm) {
		
				//$ln_itm = line[$line_no];
				$qpoh =get_rcv_qty_in_stock($ln_itm->stock_id);
			if( $ln_itm->quantity > $qpoh ){
				display_error(_("The delivery cannot be processed because there are insufficient RCVs for item:") . " " . $ln_itm->stock_id . 								                    " - " .  $ln_itm->item_description . 'qpoh =' . $qpoh) ;
				    return false;
				}
				//print_r($ln_itm );
				//log data for processing
				$result = GetPOData($ln_itm->po_detail_rec,$_SESSION['PO']->order_no) ;
				$return = ins_rcvmailer_job($_SESSION['PO']->order_no,$_SESSION['PO']->supplier_id, $_SESSION['PO']->supplier_name,$ln_itm->	po_detail_rec,$ln_itm->quantity,$ln_itm->stock_id,$result['delivery_address'],'.','.');
				//allocate PINs

			$body = get_company_pref("delivery_msg_body");
			$subject = get_company_pref("delivery_msg_subject");
			
			// sales order approval; = 90
			sendmail(ST_SUPPDELIVERY, $body, $_SESSION["wa_current_user"]->loginname,$_SESSION['PO']->order_no, $subject);  
					if($return) 
						return false;
			//}
		}
	dispatch_sales_tran_approval($_SESSION['PO']->order_no,$_SESSION["wa_current_user"]->loginname,$_SESSION["wa_current_user"]->loginname,		ST_CUSTDELIVERY);
		return true;
}
//--------------------------------------------------------------------------------------------------

function process_receive_po()
{
	global $path_to_root, $Ajax , $nonfin_audit_trail;

	if (!can_process() )
		return;
	if (check_po_changed())
	{
		display_error(_("This order has been changed or invoiced since this delivery was started to be actioned. Processing halted. To enter a delivery against this purchase order, it must be re-selected and re-read again to update the changes made by the other user."));
		hyperlink_no_params("$path_to_root/purchasing/inquiry/po_search.php",
		 _("Select a different purchase order for receiving goods against"));
		hyperlink_params("$path_to_root/purchasing/po_receive_items.php", 
			 _("Re-Read the updated purchase order for receiving goods against"),
			 "PONumber=" . $_SESSION['PO']->order_no);
		unset($_SESSION['PO']->line_items);
		unset($_SESSION['PO']);
		unset($_POST['ProcessGoodsReceived']);
		$Ajax->activate('_page_body');
		display_footer_exit();
	}
	
	if ( !dispatch_to_supplier() )
		return;
	
	$grn = add_grn($_SESSION['PO'], $_POST['DefaultReceivedDate'],
		$_POST['ref'], $_POST['Location']);
	
	change_porder_state ($_SESSION['PO']->order_no, 'APPROVED' ,'Confirmed') ;  //$_SESSION['Items']->order_no
	if($nonfin_audit_trail)
	{
		$ip = preg_quote($_SERVER['REMOTE_ADDR']);
		add_nonfin_audit_trail(0,0,0,0,'PURCHASE ORDER CONFIRMATION','A',$ip,'PO # ' . $delivery_no. " CONFIRMED ");
	}
			
	new_doc_date($_POST['DefaultReceivedDate']);
	unset($_SESSION['PO']->line_items);
	unset($_SESSION['PO']);

	meta_forward($_SERVER['PHP_SELF'], "AddedID=$grn");
}

//--------------------------------------------------------------------------------------------------

if (isset($_GET['PONumber']) && $_GET['PONumber'] > 0 && !isset($_POST['Update']))
{

	create_new_po();

	/*read in all the selected order into the Items cart  */
	read_po($_GET['PONumber'], $_SESSION['PO']);
}

//--------------------------------------------------------------------------------------------------

if (isset($_POST['Update']) || isset($_POST['ProcessGoodsReceived']))
{

	/* if update quantities button is hit page has been called and ${$line->line_no} would have be
 	set from the post to the quantity to be received in this receival*/
	foreach ($_SESSION['PO']->line_items as $line)
	{
	 if( ($line->quantity - $line->qty_received)>0) {
		$_POST[$line->line_no] = max($_POST[$line->line_no], 0);
		if (!check_num($line->line_no))
			$_POST[$line->line_no] = number_format2(0, get_qty_dec($line->stock_id));

		if (!isset($_POST['DefaultReceivedDate']) || $_POST['DefaultReceivedDate'] == "")
			$_POST['DefaultReceivedDate'] = new_doc_date();

		$_SESSION['PO']->line_items[$line->line_no]->receive_qty = input_num($line->line_no);

		if (isset($_POST[$line->stock_id . "Desc"]) && strlen($_POST[$line->stock_id . "Desc"]) > 0)
		{
			$_SESSION['PO']->line_items[$line->line_no]->item_description = $_POST[$line->stock_id . "Desc"];
		}
	 }
	}
	$Ajax->activate('grn_items');
}

//--------------------------------------------------------------------------------------------------

if (isset($_POST['ProcessGoodsReceived']))
{
	process_receive_po();
}

//--------------------------------------------------------------------------------------------------

start_form();

//display_grn_summary($_SESSION['PO'], true);
$_POST['Location'] = 'ARR'; ///laolu reset default Location to WIP
display_grn_summary_2($_SESSION['PO'], true);
display_heading(_("Items to Confirm"));
display_po_receive_items();

echo '<br>';
//submit_center_first('Update', _("Update"), '', true);
submit_center('ProcessGoodsReceived', _("Confirm"), _("Clear all GL entry fields"), 'default');

end_form();

//--------------------------------------------------------------------------------------------------

end_page();
?>

