<?php
/**********************************************************************
    Copyright (C) SIMPLEX
    @author laolu olapegba
***********************************************************************/
$page_security = 'SA_GRN';
$path_to_root = "../..";
include_once($path_to_root . "/purchasing/includes/po_class.inc");

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_db.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/simplex/purchasing/includes/ui/ui_funcs.php");
//include_once($path_to_root . "/simplex/includes/ui/ui_lists.php");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Serialize Received Items"), false, false, "", $js);

//---------------------------------------------------------------------------------------------------------------

if (isset($_GET['AddedID']))
{
	$grn = $_GET['AddedID'];
	$trans_type = ST_SUPPRECEIVE;

	display_notification_centered(_("Purchase Order Delivery has been processed"));

	display_note(get_trans_view_str($trans_type, $grn, _("&View this Delivery")));

	hyperlink_params("$path_to_root/purchasing/supplier_invoice.php", _("Entry purchase &invoice for this receival"), "New=1");

	hyperlink_no_params("$path_to_root/purchasing/inquiry/po_search.php", _("Select a different &purchase order for receiving items against"));

	display_footer_exit();
}

//--------------------------------------------------------------------------------------------------

if ((!isset($_GET['TransID']) || $_GET['TransID'] == 0) && !isset($_SESSION['PO']))
{
	die (_("This page can only be opened if a Transaction # of a received stock has been selected. Please select a Transaction #  first."));
}

//--------------------------------------------------------------------------------------------------
function randomPrefix($length) 
{ 
$random= "";
srand((double)microtime()*1000000);

$data = "AbcDE123IJKLMN67QRSTUVWXYZ"; 
$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz"; 
$data .= "0FGH45OP89";

for($i = 0; $i < $length; $i++) 
{ 
$random .= substr($data, (rand()%(strlen($data))), 1); 
}

return $random; 
}

//randomPrefix(10); 
function display_po_serialize_items()
{
	global $table_style;

	div_start('grn_items');
    start_table("colspan=7 $table_style width=90%");
    $th = array(_("Order no"), _("Trans #"), _("Description"),_("Total Qty"), _("Units"),_("Serial #"),_("Remainder"));
    table_header($th);
	

    /*show the line items on the order with the quantity being received for modification */

    $total = 0;
    $k = 0; //row colour counter
	$i =1; //row number
	//$i = 0;
	$count = $_GET['TransID'];
	
	//$line = array($result);
	//mysql_fetch_array
	$sql = "SELECT 
	stock.order_no,
	stock.trans_id, 
	smaster.description,
	stock.qty,
	location.location_name,
	stock.tran_Date,
	stock.type, 	
	stock.loc_code,
	stock.person_id,
	stock.price,
	stock.trans_no, 
	stock.reference
	
	FROM "
		.TB_PREF."stock_moves stock, "
		.TB_PREF."locations location, "
		.TB_PREF."stock_master smaster
	WHERE location.loc_code = stock.loc_code
	AND smaster.stock_id = stock.stock_id
	AND (location.location_type='ARR') 
	AND stock.visible=1
	AND smaster.serializable=1
	AND stock.trans_id=".$_GET['TransID'];
	$sql_a = mysql_query($sql);
	$result = mysql_fetch_array($sql_a);
	$line_qty = $result["qty"];
	//$stock_units = $result["units"];
/* 	if ($item_count  > 0 )
	{
		
	} */

			
	//$result = mysql_fetch_array($sql);
	
    //if ($count > 0 )
    //{

	//conversion_list_row(_("Unit Type 1:"), 'abbr1', '');
	//conversion_list_row(_("Unit Type 2:"), 'abbr2', '');
		
		//echo "here now:" .$count;
		//for ($i = 1; $i <= $item_count; $i++)
		//{
		
			alt_table_row_color($k);
			//label_cell($i);
			label_cell($result["order_no"]);
			label_cell($result["trans_id"]);
			label_cell($result["description"]);
			
			//conversion_list_cells('Units','list_units', '');
			//_("Unit Type 1:")
			label_cell($line_qty);

			units_list_cells(null, 'units','',false,true);
 			if (list_updated('units')) {
			    //$Ajax->activate('price');
			    //$Ajax->activate('units');
			    //$Ajax->activate('qty');
				$Ajax->activate('remain');
			    //$Ajax->activate('req_del_date');
			    //$Ajax->activate('line_total');
			} 
			//label_cell("bricks");
			//echo 'scaca1:' . ($_POST['units']);
			$multiplier = get_unit_info($_POST['units']);
			//echo 'scaca:' . get_unit_info($_POST['units']);
			//echo 'multiplier:' . $multiplier;
			$remainder = $line_qty  - $multiplier;
			//round($line_qty / $multiplier);
			//randomPrefix(10)
			text_cells(null, 'srl_no_'.$i,uniqid() , 30, 50);
			//text_cells(null, 'remain'); //label_cell('');
			label_cell($remainder, '', 'remain');
			submit_cells('EnterLine', _("Scan Item"), "colspan=2",
		    _('Add new item to document'), true);
			end_row();
			
			
		//}
   // }


    //$display_total = number_format2($total,user_price_dec());
    //label_row(_("Total value of items received"), $display_total, "colspan=8 align=right","nowrap align=right");
    end_table();
	div_end();
}

//--------------------------------------------------------------------------------------------------
function get_unit_info($name)
{
		$sql2 = "SELECT decimals from "
			.TB_PREF."item_units_conversion  
			WHERE abbr2 = '". $name . "'";
			
	$sql_b = mysql_query($sql2);
	$result2 = mysql_fetch_array($sql_b);
	return $result2['decimals'];
}
function process_receive_po()
{
	global $path_to_root, $Ajax;

	if (!can_process())
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

	$grn = add_grn($_SESSION['PO'], $_POST['DefaultReceivedDate'],
		$_POST['ref'], 'ARR');   /// Laolu ARR

	new_doc_date($_POST['DefaultReceivedDate']);
	unset($_SESSION['PO']->line_items);
	unset($_SESSION['PO']);

	meta_forward($_SERVER['PHP_SELF'], "AddedID=$grn");
}
//---------------------------------------------------------------------------------------------------

function handle_add_new_item()
{
	$allow_update = check_data();
	
	if ($allow_update == true)
	{ 
		if (count($_SESSION['PO']->line_items) > 0)
		{
		    foreach ($_SESSION['PO']->line_items as $order_item) 
		    {

    			/* do a loop round the items on the order to see that the item
    			is not already on this order */
   			    if (($order_item->stock_id == $_POST['stock_id']) && 
   			    	($order_item->Deleted == false)) 
   			    {
				  	$allow_update = false;
				  	display_error(_("The selected item is already on this order."));
			    }
		    } /* end of the foreach loop to look for pre-existing items of the same code */
		}

		if ($allow_update == true)
		{
		   	$sql = "SELECT description, units, mb_flag
				FROM ".TB_PREF."stock_master WHERE stock_id = ".db_escape($_POST['stock_id']);

		    $result = db_query($sql,"The stock details for " . $_POST['stock_id'] . " could not be retrieved");

		    if (db_num_rows($result) == 0)
		    {
				$allow_update = false;
		    }		    

			if ($allow_update)
		   	{
				$myrow = db_fetch($result);
				$_SESSION['PO']->add_to_order ($_POST['line_no'], $_POST['stock_id'], input_num('qty'), 
					$myrow["description"], input_num('price'), $myrow["units"],
					$_POST['req_del_date'], 0, 0);

				unset_form_variables();
				$_POST['stock_id']	= "";
	   		} 
	   		else 
	   		{
			     display_error(_("The selected item does not exist or it is a kit part and therefore cannot be purchased."));
		   	}

		} /* end of if not already on the order and allow input was true*/
    }
	line_start_focus();
}


/* //--------------------------------------------------------------------------------------------------

if (isset($_GET['TransID']) && $_GET['TransID'] > 0 && !isset($_POST['Update']))
{

	create_new_po();

	///*read in all the selected order into the Items cart  
	read_po($_GET['TransID'], $_SESSION['PO']);
}
 */

//--------------------------------------------------------------------------------------------------

/* if (isset($_POST['ProcessGoodsReceived']))
{

	/* if update quantities button is hit page has been called and ${$line->line_no} would have be
 	set from the post to the quantity to be received in this receival
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
*/

//--------------------------------------------------------------------------------------------------
if (isset($_POST['EnterLine']))
	handle_add_new_item();
if (isset($_POST['ProcessGoodsReceived']))
{
	process_receive_po();
}

//--------------------------------------------------------------------------------------------------

start_form();
 //$_POST['Location'] = 'ARR'; ///laolu reset Location to ARR
display_srlz_summary($_SESSION['PO'], true);
display_heading(_("Items to Serialize"));
display_po_serialize_items();
	
echo '<br>';
//echo 'sessionpo' . $_SESSION['PO'];
//echo "trans id =" . $_GET['TransID'];
echo $sql;
echo '<br>';
//	echo "Location=" . $_POST['Location'];
////submit_center_first('Update', _("Update"), '', true);
submit_center_first('ProcessGoodsReceived', _("Process Items"), _("Clear all GL entry fields"), 'default');
display_db_pager($table);
end_form();

//--------------------------------------------------------------------------------------------------

end_page();
?>

