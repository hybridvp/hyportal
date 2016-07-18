<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.

***********************************************************************/
//-----------------------------------------------------------------------------
//
//	Entry/Modify Delivery Note against Sales Order
//
$page_security = 'SA_SALESDELIVERY';
$path_to_root = "../..";

include_once($path_to_root . "/sales/includes/cart_class.inc");
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/includes/manufacturing.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");
include_once($path_to_root . "/taxes/tax_calc.inc");
include_once($path_to_root . "/simplex/sales/includes/sales.inc");

//added confirm order db to check order has been confirmed before delivery in file customer_delivery 
include_once($path_to_root . "/simplex/sales/includes/db/confirm_order_db.inc");


$js = "";
if ($use_popup_windows) {
	$js .= get_js_open_window(900, 500);
}
if ($use_date_picker) {
	$js .= get_js_date_picker();
}

if (isset($_GET['ModifyDelivery'])) {
	$_SESSION['page_title'] = sprintf(_("Modifying Delivery Note # %d."), $_GET['ModifyDelivery']);
	$help_context = "Modifying Delivery Note";
	processing_start();
} elseif (isset($_GET['OrderNumber'])) {
	$_SESSION['page_title'] = _($help_context = "Deliver Items for a Sales Order");
	processing_start();
}

page($_SESSION['page_title'], false, false, "", $js);

if (isset($_GET['AddedID'])) {
	$dispatch_no = $_GET['AddedID'];

	display_notification_centered(sprintf(_("Delivery # %d has been entered."),$dispatch_no));

	display_note(get_customer_trans_view_str(ST_CUSTDELIVERY, $dispatch_no, _("&View This Delivery")), 0, 1);

	display_note(print_document_link($dispatch_no, _("&Print Delivery Note"), true, ST_CUSTDELIVERY));
	display_note(print_document_link($dispatch_no, _("&Email Delivery Note"), true, ST_CUSTDELIVERY, false, "", "", 1), 1, 1);
	display_note(print_document_link($dispatch_no, _("P&rint as Packing Slip"), true, ST_CUSTDELIVERY, false, "", "", 0, 1));
	display_note(print_document_link($dispatch_no, _("E&mail as Packing Slip"), true, ST_CUSTDELIVERY, false, "", "", 1, 1), 1);

	display_note(get_gl_view_str(13, $dispatch_no, _("View the GL Journal Entries for this Dispatch")),1);
	
	hyperlink_params("$path_to_root/simplex/sales/inquiry/customer_epin_inquiry.php", _("View pending Pin files"), "order_number=$dispatch_no");

	hyperlink_params("$path_to_root/sales/inquiry/sales_orders_view.php", _("Select Another Order For Dispatch"), "OutstandingOnly=1");
	
	hyperlink_params("$path_to_root/sales/customer_invoice.php", _("Invoice This Delivery"), "DeliveryNumber=$dispatch_no");

	display_footer_exit();

} elseif (isset($_GET['UpdatedID'])) {

	$delivery_no = $_GET['UpdatedID'];

	display_notification_centered(sprintf(_('Delivery Note # %d has been updated.'),$delivery_no));

	display_note(get_trans_view_str(ST_CUSTDELIVERY, $delivery_no, _("View this delivery")), 0, 1);

	display_note(print_document_link($delivery_no, _("&Print Delivery Note"), true, ST_CUSTDELIVERY));
	display_note(print_document_link($delivery_no, _("&Email Delivery Note"), true, ST_CUSTDELIVERY, false, "", "", 1), 1, 1);
	display_note(print_document_link($delivery_no, _("P&rint as Packing Slip"), true, ST_CUSTDELIVERY, false, "", "", 0, 1));
	display_note(print_document_link($delivery_no, _("E&mail as Packing Slip"), true, ST_CUSTDELIVERY, false, "", "", 1, 1), 1);

	hyperlink_params($path_to_root . "/sales/customer_invoice.php", _("Confirm Delivery and Invoice"), "DeliveryNumber=$delivery_no");

	hyperlink_params($path_to_root . "/sales/inquiry/sales_deliveries_view.php", _("Select A Different Delivery"), "OutstandingOnly=1");

	display_footer_exit();
}
//-----------------------------------------------------------------------------

if (isset($_GET['OrderNumber']) && $_GET['OrderNumber'] > 0) {

	$ord = new Cart(ST_SALESORDER, $_GET['OrderNumber'], true);

	/*read in all the selected order into the Items cart  */

	if ($ord->count_items() == 0) {
		hyperlink_params($path_to_root . "/sales/inquiry/sales_orders_view.php",
			_("Select a different sales order to delivery"), "OutstandingOnly=1");
		die ("<br><b>" . _("This order has no items. There is nothing to delivery.") . "</b>");
	}

	$ord->trans_type = ST_CUSTDELIVERY;
	$ord->src_docs = $ord->trans_no;
	$ord->order_no = key($ord->trans_no);
	$ord->trans_no = 0;
	$ord->reference = $Refs->get_next(ST_CUSTDELIVERY);
	$ord->document_date = new_doc_date();
	$_SESSION['Items'] = $ord;
	copy_from_cart();

} elseif (isset($_GET['ModifyDelivery']) && $_GET['ModifyDelivery'] > 0) {

	$_SESSION['Items'] = new Cart(ST_CUSTDELIVERY,$_GET['ModifyDelivery']);

	if ($_SESSION['Items']->count_items() == 0) {
		hyperlink_params($path_to_root . "/sales/inquiry/sales_orders_view.php",
			_("Select a different delivery"), "OutstandingOnly=1");
		echo "<br><center><b>" . _("This delivery has all items invoiced. There is nothing to modify.") .
			"</center></b>";
		display_footer_exit();
	}

	copy_from_cart();
	
} elseif ( !processing_active() ) {
	/* This page can only be called with an order number for invoicing*/

	display_error(_("This page can only be opened if an order or delivery note has been selected. Please select it first."));

	hyperlink_params("$path_to_root/sales/inquiry/sales_orders_view.php", _("Select a Sales Order to Delivery"), "OutstandingOnly=1");

	end_page();
	exit;

} else {
	check_edit_conflicts();

	if (!check_quantities()) {
		display_error(_("Selected quantity cannot be less than quantity invoiced nor more than quantity	not dispatched on sales order."));

	} elseif(!check_num('ChargeFreightCost', 0)) {
		display_error(_("Freight cost cannot be less than zero"));
		set_focus('ChargeFreightCost');
	}
}

//-----------------------------------------------------------------------------

function check_data()
{
	global $Refs;
//Added checks to see that the order to be delivered has been confirmed in customer_delivery.php to function check_data() 
        if (get_order_state($_SESSION['Items']->order_no) != 'Confirmed') 
	{
        	display_error(_("Order".$_SESSION['Items']->order_no." has not been confirmed: Order could not be delivered!"));
        	return false;//not ok
        }
///////////////////////////////
	if (!isset($_POST['DispatchDate']) || !is_date($_POST['DispatchDate']))	{
		display_error(_("The entered date of delivery is invalid."));
		set_focus('DispatchDate');
		return false;
	}

	if (!is_date_in_fiscalyear($_POST['DispatchDate'])) {
		display_error(_("The entered date of delivery is not in fiscal year."));
		set_focus('DispatchDate');
		return false;
	}

	if (!isset($_POST['due_date']) || !is_date($_POST['due_date']))	{
		display_error(_("The entered dead-line for invoice is invalid."));
		set_focus('due_date');
		return false;
	}

	if ($_SESSION['Items']->trans_no==0) {
		if (!$Refs->is_valid($_POST['ref'])) {
			display_error(_("You must enter a reference."));
			set_focus('ref');
			return false;
		}

		if ($_SESSION['Items']->trans_no==0 && !is_new_reference($_POST['ref'], ST_CUSTDELIVERY)) {
			display_error(_("The entered reference is already in use."));
			set_focus('ref');
			return false;
		}
	}
	if ($_POST['ChargeFreightCost'] == "") {
		$_POST['ChargeFreightCost'] = price_format(0);
	}
	//Laolu
	//check if all line items have been picked
	//if ($_SESSION['Items']->all_items_picked() == 0 ) {
	//	display_error(_("Not all items  have been picked for this delivery note."));
	///	return false;
	//}

	if (!check_num('ChargeFreightCost',0)) {
		display_error(_("The entered shipping value is not numeric."));
		set_focus('ChargeFreightCost');
		return false;
	}

	if ($_SESSION['Items']->has_items_dispatch() == 0 && input_num('ChargeFreightCost') == 0) {
		display_error(_("There are no item quantities on this delivery note."));
		return false;
	}

	if (!check_quantities()) {
		return false;
	}

	return true;
}
//------------------------------------------------------------------------------
function copy_to_cart()
{
	$cart = &$_SESSION['Items'];
	$cart->ship_via = $_POST['ship_via'];
	$cart->freight_cost = input_num('ChargeFreightCost');
	$cart->document_date = $_POST['DispatchDate'];
	$cart->due_date =  $_POST['due_date'];
	$cart->Location = $_POST['Location'];
	$cart->Comments = $_POST['Comments'];
	if ($cart->trans_no == 0)
		$cart->reference = $_POST['ref'];

}
//------------------------------------------------------------------------------

function copy_from_cart()
{
	$cart = &$_SESSION['Items'];
	$_POST['ship_via'] = $cart->ship_via;
	$_POST['ChargeFreightCost'] = price_format($cart->freight_cost);
	$_POST['DispatchDate'] = $cart->document_date;
	$_POST['due_date'] = $cart->due_date;
	$_POST['Location'] = $cart->Location;
	$_POST['Comments'] = $cart->Comments;
	$_POST['cart_id'] = $cart->cart_id;
	$_POST['ref'] = $cart->reference;
}
//------------------------------------------------------------------------------

function check_quantities()
{
	$ok =1;
	// Update cart delivery quantities/descriptions
	foreach ($_SESSION['Items']->line_items as $line=>$itm) {
		if (isset($_POST['Line'.$line])) {
		if($_SESSION['Items']->trans_no) {
			$min = $itm->qty_done;
			$max = $itm->quantity;
		} else {
			$min = 0;
			$max = $itm->quantity - $itm->qty_done;
		}
		
			if (check_num('Line'.$line, $min, $max)) {
				$_SESSION['Items']->line_items[$line]->qty_dispatched =
				  input_num('Line'.$line);
			} else {
				set_focus('Line'.$line);
				$ok = 0;
			}

		}

		if (isset($_POST['Line'.$line.'Desc'])) {
			$line_desc = $_POST['Line'.$line.'Desc'];
			if (strlen($line_desc) > 0) {
				$_SESSION['Items']->line_items[$line]->item_description = $line_desc;
			}
		}
	}
// ...
//	else
//	  $_SESSION['Items']->freight_cost = input_num('ChargeFreightCost');
	return $ok;
}
//------------------------------------------------------------------------------

function check_qoh()
{
	global $SysPrefs;
	if (!$SysPrefs->allow_negative_stock())	{
		foreach ($_SESSION['Items']->line_items as $itm) {

			if ($itm->qty_dispatched && has_stock_holding($itm->mb_flag)) {
				$qoh = get_qoh_on_date($itm->stock_id, $_POST['Location'], $_POST['DispatchDate']);

				if ($itm->qty_dispatched > $qoh) {
					display_error(_("The delivery cannot be processed because there is an insufficient s quantity for item:") .
						" " . $itm->stock_id . " - " .  $itm->item_description . $itm->qty_dispatched);
					return false;
				}
			}
		}
	}
	return true;
}
function get_pin_qty_in_stock($stock_id)
{
	$sql = "SELECT count(pin) FROM ".TB_PREF."pin_details where status = 'N' and flg_mnt_status ='A' and sales_order_no =0 and stock_id= ". db_escape($stock_id) ;
    $result = db_query($sql, "Can not look up stock count");
    $row = db_fetch_row_r($result);
    return $row[0];
}
function GetSOData($transid,$so_number)
{
$sql = "SELECT 	so.order_no,so.branch_code,so.customer_ref,so.ord_date,
				so.ship_via,so.delivery_address,so.contact_phone,so.contact_email,so.deliver_to,so.ourorder_status
	FROM "
		.TB_PREF."sales_orders so, "
		.TB_PREF."sales_order_details line
	WHERE so.order_no = line.order_no
	AND so.trans_type = line.trans_type
	AND so.order_no = ". $so_number .
	" AND line.id=".$transid ; 
	echo $sql; 
	$sql_a = db_query($sql);
	 $result = db_fetch($sql_a);
	 return $result;
}
function check_qty_picked()
{
	foreach ($_SESSION['Items']->line_items as $itm) {
	$qpoh =get_pin_qty_in_stock($itm->stock_id);
		if( $itm->qty_dispatched > $qpoh ){
		display_error(_("The delivery cannot be processed because there are insufficient PINs for item:" ). " " . $itm->stock_id . 					" - " .  $itm->item_description) ;
		return false;
		}
	}
}
//-----------------------------------------------------------------------------
function dispatch_to_cust()
{
		foreach ($_SESSION['Items']->line_items as $line=>$ln_itm) {
			if ($ln_itm->stock_id == 'M100A' )
			{
				//check available pin qty
				$qpoh =get_pin_qty_in_stock($ln_itm->stock_id);
			if( $ln_itm->qty_dispatched > $qpoh ){
				display_error(_("The delivery cannot be processed because there are insufficient PINs for item:") . " " . $ln_itm->stock_id . 					" - " .  $ln_itm->item_description) ;
				return false;
				}
				//log data for processing
				$result = GetSOData($ln_itm->id,$_SESSION['Items']->order_no) ;
					$return = ins_pinmailer_job($_SESSION['Items']->order_no,$_SESSION['Items']->customer_id, $_SESSION['Items']->customer_name,$ln_itm->id,$ln_itm->qty_dispatched,$ln_itm->stock_id,$result['delivery_address'],$result['contact_phone'],$result['contact_email']);
				//allocate PINs
			//$sql2 = "UPDATE " .TB_PREF."pin_details SET status='A' WHERE rownum <=". $ln_itm->qty_dispatched . " AND  stock_id= ". db_escape($ln_itm->stock_id);
			//$res = db_query($sql2, "Could not allocate pin");
			
					if($return) 
						return false;
			}
		}
		return true;
}
function ins_pinmailer_job($order_no, $cust_id, $cust_name, $line_no, $qty, $stock_id, $delivery_address,$cust_phone,$email)
{
	$filename = $cust_id . "_" . $order_no ."_" .$line_no . date('DMY'). ".txt";
	$sql = "INSERT INTO ".TB_PREF."pin_mailer_jobs	(id,order_no,line_no,customer_no,customer_name,quantity,stock_id,
				delivery_address,contact_phone,contact_email,logged_date,logged_by, status,filename,denomination)
				VALUES (PIN_MAILER_ID_SEQ.NEXTVAL,". $order_no. ",". $line_no . "," . db_escape($cust_id). "," . db_escape($cust_name). ",". $qty . "," . db_escape($stock_id) . "," . db_escape($delivery_address) . "," . db_escape($cust_phone) . ",". db_escape($email) . ",SYSDATE," . db_escape($_SESSION["wa_current_user"]->loginname). ",'L',". db_escape($filename). ", 100)";
							
					$res = db_query($sql, "Could not insert into pin_mailer_jobs");
					$err = oci_error($res);  
					return $err;
	//

}
//------------------------------------------------------------------------------

if (isset($_POST['process_delivery']) && check_data() && check_qoh() && dispatch_to_cust()) {

	$dn = &$_SESSION['Items'];

	if ($_POST['bo_policy']) {
		$bo_policy = 0;
	} else {
		$bo_policy = 1;
	}
	
	$newdelivery = ($dn->trans_no == 0);

	copy_to_cart();
	if ($newdelivery) new_doc_date($dn->document_date);
	$delivery_no = $dn->write($bo_policy);
	
	
	
	processing_end();
			if($nonfin_audit_trail)
			{
			$ip = preg_quote($_SERVER['REMOTE_ADDR']);
			add_nonfin_audit_trail(0,0,0,0,'SALES ORDER DELIVERY','A',$ip,'SALES ORDER # %d ' . $delivery_no. " DELIVERED ");
			}
	if ($newdelivery) {
		meta_forward($_SERVER['PHP_SELF'], "AddedID=$delivery_no");
	} else {
		meta_forward($_SERVER['PHP_SELF'], "UpdatedID=$delivery_no");
	}
}

if (isset($_POST['Update']) || isset($_POST['_Location_update'])) {
	$Ajax->activate('Items');
}
//------------------------------------------------------------------------------
start_form();
hidden('cart_id');

start_table("$table_style2 width=80%", 5);
echo "<tr><td>"; // outer table

start_table("$table_style width=100%");
start_row();
label_cells(_("Customer"), $_SESSION['Items']->customer_name, "class='tableheader2'");
label_cells(_("Branch"), get_branch_name($_SESSION['Items']->Branch), "class='tableheader2'");
label_cells(_("Currency"), $_SESSION['Items']->customer_currency, "class='tableheader2'");
end_row();
start_row();

//if (!isset($_POST['ref']))
//	$_POST['ref'] = $Refs->get_next(ST_CUSTDELIVERY);

if ($_SESSION['Items']->trans_no==0) {
	ref_cells(_("Reference"), 'ref', '', null, "class='tableheader2'");
} else {
	label_cells(_("Reference"), $_SESSION['Items']->reference, "class='tableheader2'");
}

label_cells(_("For Sales Order"), get_customer_trans_view_str(ST_SALESORDER, $_SESSION['Items']->order_no), "class='tableheader2'");

label_cells(_("Sales Type"), $_SESSION['Items']->sales_type_name, "class='tableheader2'");
end_row();
start_row();

if (!isset($_POST['Location'])) {
	$_POST['Location'] = $_SESSION['Items']->Location;
}
label_cell(_("Delivery From"), "class='tableheader2'");
locations_list_cells(null, 'Location', null, false, true);

if (!isset($_POST['ship_via'])) {
	$_POST['ship_via'] = $_SESSION['Items']->ship_via;
}
label_cell(_("Shipping Company"), "class='tableheader2'");
shippers_list_cells(null, 'ship_via', $_POST['ship_via']);

// set this up here cuz it's used to calc qoh
if (!isset($_POST['DispatchDate']) || !is_date($_POST['DispatchDate'])) {
	$_POST['DispatchDate'] = new_doc_date();
	if (!is_date_in_fiscalyear($_POST['DispatchDate'])) {
		$_POST['DispatchDate'] = end_fiscalyear();
	}
}
date_cells(_("Date"), 'DispatchDate', '', $_SESSION['Items']->trans_no==0, 0, 0, 0, "class='tableheader2'");
end_row();

end_table();

echo "</td><td>";// outer table

start_table("$table_style width=90%");

if (!isset($_POST['due_date']) || !is_date($_POST['due_date'])) {
	$_POST['due_date'] = get_invoice_duedate($_SESSION['Items']->customer_id, $_POST['DispatchDate']);
}
date_row(_("Invoice Dead-line"), 'due_date', '', null, 0, 0, 0, "class='tableheader2'");
end_table();

echo "</td></tr>";
end_table(1); // outer table

$row = get_customer_to_order($_SESSION['Items']->customer_id);
if ($row['dissallow_invoices'] == 1)
{
	display_error(_("The selected customer account is currently on hold. Please contact the credit control personnel to discuss."));
	end_form();
	end_page();
	exit();
}	
display_heading(_("Delivery Items"));
div_start('Items');
start_table("$table_style width=80%");

$new = $_SESSION['Items']->trans_no==0;
$th = array(_("Item Code"), _("Item Description"), 
	$new ? _("Ordered") : _("Max. delivery"), _("Units"), $new ? _("Delivered") : _("Invoiced"),
	//_("This Delivery"), _("Price"), _("Tax Type"), _("Discount"), _("Total"));
	//Laolu changed
	_("This Delivery"), _("Price"), _("Tax Type"), _("Picked"), _("Discount"), _("Total"), _("Pick Item"));


table_header($th);
$k = 0;
$has_marked = false;

foreach ($_SESSION['Items']->line_items as $line=>$ln_itm) {
	if ($ln_itm->quantity==$ln_itm->qty_done) {
		continue; //this line is fully delivered
	}
	// if it's a non-stock item (eg. service) don't show qoh
	$show_qoh = true;
	if ($SysPrefs->allow_negative_stock() || !has_stock_holding($ln_itm->mb_flag) ||
		$ln_itm->qty_dispatched == 0) {
		$show_qoh = false;
	}

	if ($show_qoh) {
		$qoh = get_qoh_on_date($ln_itm->stock_id, $_POST['Location'], $_POST['DispatchDate']);
	}

	if ($show_qoh && ($ln_itm->qty_dispatched > $qoh)) {
		// oops, we don't have enough of one of the component items
		start_row("class='stockmankobg'");
		$has_marked = true;
	} else {
		alt_table_row_color($k);
	}
	view_stock_status_cell($ln_itm->stock_id);

	text_cells(null, 'Line'.$line.'Desc', $ln_itm->item_description, 30, 50);
	$dec = get_qty_dec($ln_itm->stock_id);
	qty_cell($ln_itm->quantity, false, $dec);
	label_cell($ln_itm->units);
	qty_cell($ln_itm->qty_done, false, $dec);

	small_qty_cells(null, 'Line'.$line, qty_format($ln_itm->qty_dispatched, $ln_itm->stock_id, $dec), null, null, $dec);

	$display_discount_percent = percent_format($ln_itm->discount_percent*100) . "%";

	$line_total = ($ln_itm->qty_dispatched * $ln_itm->price * (1 - $ln_itm->discount_percent));

	amount_cell($ln_itm->price);
	label_cell($ln_itm->tax_type_name);
	qty_cell(get_qty_picked($ln_itm->id),false,0);
	label_cell($display_discount_percent, "nowrap align=right");
	amount_cell($line_total);

	end_row();
}

$_POST['ChargeFreightCost'] =  get_post('ChargeFreightCost', 
	price_format($_SESSION['Items']->freight_cost));

$colspan = 9;

start_row();
label_cell(_("Shipping Cost"), "colspan=$colspan align=right");
small_amount_cells(null, 'ChargeFreightCost', $_SESSION['Items']->freight_cost);
end_row();

$inv_items_total = $_SESSION['Items']->get_items_total_dispatch();

$display_sub_total = price_format($inv_items_total + input_num('ChargeFreightCost'));

label_row(_("Sub-total"), $display_sub_total, "colspan=$colspan align=right","align=right");

$taxes = $_SESSION['Items']->get_taxes(input_num('ChargeFreightCost'));
$tax_total = display_edit_tax_items($taxes, $colspan, $_SESSION['Items']->tax_included);

$display_total = price_format(($inv_items_total + input_num('ChargeFreightCost') + $tax_total));

label_row(_("Amount Total"), $display_total, "colspan=$colspan align=right","align=right");

end_table(1);

if ($has_marked) {
	display_note(_("Marked items have insufficient quantities in stock as on day of delivery."), 0, 1, "class='red'");
}
start_table($table_style2);

policy_list_row(_("Action For Balance"), "bo_policy", null);

textarea_row(_("Memo"), 'Comments', null, 50, 4);

end_table(1);
div_end();
submit_center_first('Update', _("Update"),
  _('Refresh document page'), true);
submit_center_last('process_delivery', _("Process Dispatch"),
  _('Check entered data and save document'), 'default');

end_form();


end_page();

?>