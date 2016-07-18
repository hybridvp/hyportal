<?php
/**********************************************************************
    Copyright (C) SIMPLEX
    @author laolu olapegba
***********************************************************************/
$page_security = 'SA_GRN';
$path_to_root = "../..";
include_once($path_to_root . "/sales/includes/cart_class.inc");
//include_once($path_to_root . "/purchasing/includes/po_class.inc");

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_db.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/simplex/purchasing/includes/ui/ui_funcs.inc");
include_once($path_to_root . "/simplex/sales/includes/sales.inc");
//include_once($path_to_root . "/simplex/includes/ui/ui_lists.php");
 /*if (isset($_GET['OrderNumber'])){
	$_POST['OrderNumber'] = $_GET['OrderNumber'];
	$OrderNumber = $_GET['OrderNumber'];
	} */
 if (isset($_GET['OrderNumber'])){
	$_POST['OrderNumber'] = $_GET['OrderNumber'];
	} 
	else
	$OrderNumber = $_POST['OrderNumber'];
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Pick Delivery Items"), false, false, "", $js);

/* if (list_updated('units')) 
	$Ajax->activate('grn_items'); */
//---------------------------------------------------------------------------------------------------------------

if (isset($_GET['AddedID']))
{
	$grn = $_GET['AddedID'];
	$trans_type = ST_SUPPRECEIVE;

	display_notification_centered(_("Sales Order #" . $_GET['AddedID'] ." has been picked"));

	hyperlink_params("$path_to_root/simplex/sales/inquiry/picked_item_inquiry.php", _("&View Picked Items"), "order_number=". $_GET['AddedID']);

	hyperlink_no_params("$path_to_root/simplex/sales/inquiry/sales_orders_pick.php", _("Select a different &sales order for picking"));

	display_footer_exit();
}

//--------------------------------------------------------------------------------------------------


if ((!isset($_GET['OrderNumber']) || $_GET['OrderNumber'] == 0)  && !isset( $_POST['OrderNumber']))
{
	die (_("This page can only be opened if a sales order # has been selected. Please select a sales order #  first."));
}
if  (isset($_GET['OrderNumber']) )
{
	if(ispicked($_GET['OrderNumber']))
	{
		hyperlink_back();
		die(_("Items have already been picked for this order."));
	}
}
//-----------------------------------------------------------------------------

if (isset($_GET['OrderNumber']) && $_GET['OrderNumber'] > 0) {

	$ord = new Cart(ST_SALESORDER, $_GET['OrderNumber'], true);

	/*read in all the selected order into the Items cart  */

	if ($ord->count_items() == 0) {
		hyperlink_params($path_to_root . "/sales/inquiry/sales_orders_view.php",
			_("Select a different sales order to delivery"), "OutstandingOnly=1");
		die ("<br><b>" . _("This order has no items. There is nothing to pick / deliver.") . "</b>");
	}

	$ord->trans_type = ST_CUSTDELIVERY;
	$ord->src_docs = $ord->trans_no;
	$ord->order_no = key($ord->trans_no);
	$ord->trans_no = 0;
	$ord->reference = $Refs->get_next(ST_CUSTDELIVERY);
	$ord->document_date = new_doc_date();
	$_SESSION['Items'] = $ord;
	copy_from_cart();
	
}
if ( isset( $_GET['qty_disp'] ) &&   isset( $_GET['stock_id'] ) )
{

if ($_GET['qty_disp'] > get_qty_serialized($_GET['stock_id'],$_GET['loc_code']))
{
	// oops, we don't have enough of one of serialized items 
	//display_note(_("There are not enough serialized items to allocate."), 0, 1);
	//echo "<center><p><a href='javascript:goBack();'>Back</a></p></center><br>";
	hyperlink_back();
	die(_("There are not enough serialized items to allocate."));
	
}

}
//--------------------------------------------------------------------------------------------------
function get_unit_name($name)
{
		$sql2 = "SELECT name from "
			.TB_PREF."item_units  
			WHERE abbr = ". db_escape($name);
			
	$sql_b = db_query($sql2);
	$result2 = db_fetch($sql_b);
	return $result2['name'];
}
function display_so_serialize_items()
{
	global $table_style;

	div_start('grn_items');
    start_table("colspan=7 $table_style width=90%");
    $th = array(_("Item"), _("Order #"), _("Description"),_("Qty"), _("Units"),_("Serial #"), _("Unscanned"));
    table_header($th);
	

    /*show the line items on the order with the quantity being received for modification */

    $total = 0;
    $k = 0; //row colour counter

	
	if(!isset ($_GET['OrderNumber'])) {
		$OrderNumber = $_POST['OrderNumber'];
	}
	 $des_unit = $_POST['units'];
	 if ( get_unit_info($des_unit) <= 0)
	 {
	 	die(_("Unit quantity for unit '". get_unit_name ($des_unit) . "' cannot be zero"));
	 }
	
	$line_qty = 0 ;
	$result = get_line_item($OrderNumber);
	$line_qty = $result["quantity"] ;  //- $result["stock.qty_serialized"]
	//display_notification ($line_qty);
	$remainder  = $line_qty;
	$stock_id = $result["stk_code"];
	$multiplier = get_unit_info($_POST['units']);
	$count = floor($line_qty/$multiplier);
	echo '<strong> Total Quantity: </strong>'. $line_qty;
	//echo '<strong> Total Quantity Unscanned: </strong>'. $multiplier;
	$units = $_POST['units'];
	
	if($line_qty < $multiplier)
	{
		//echo "Cannot scan as , SKU quantity is less than unit count" ;
		display_error(_("Cannot scan as selected Unit , Not enough items"));
	}
	if($count > 0)
	{
		for($i=1; $i<=$count; $i++)
		{
			
				alt_table_row_color($k);
				label_cell($i);
				label_cell($result["order_no"]);
				label_cell($result["description"]);
				label_cell($multiplier);
				label_cell($units);
				$remainder = $remainder  - $multiplier;
				text_cells(null, 'srl_no[]','' , 30, 50);
				label_cell($remainder, '', 'remain');
				end_row();
				$_SESSION['remainder'] = $remainder;
		}
		 if($remainder >0 &&  $_POST['units'] == 'bx.')
		{
		//$path_to_root."simplex/purchasing/po_receive_serialize_2.php?TransID=".$TransID . "&Rem="
			submit_cells('ScanBrick', _("Next"), "colspan=2", _('Scan remaining items as Bricks'), true);
		}	
		 else if($remainder >0 &&  $_POST['units'] == 'bk.')
		{
		//$path_to_root."simplex/purchasing/po_receive_serialize_2.php?TransID=".$TransID . "&Rem="
			submit_cells('ScanCard', _("Next"), "colspan=2", _('Scan remaining items as Cards'), true);
		}	 
				
	}
      end_table();
	div_end();
	if($remainder >0 &&  $_POST['units'] == 'bx.')
		{
			hyperlink_no_params( "#", _("Scan remaining items as Bricks"));
		}	
	
}
function copy_from_cart()
{
	$cart = &$_SESSION['Items'];
	$_POST['ref'] = $cart->reference;
	$_POST['Comments'] = $cart->Comments;

	$_POST['OrderDate'] = $cart->document_date;
	$_POST['delivery_date'] = $cart->due_date;
	$_POST['cust_ref'] = $cart->cust_ref;
	$_POST['freight_cost'] = price_format($cart->freight_cost);

	$_POST['deliver_to'] = $cart->deliver_to;
	$_POST['delivery_address'] = $cart->delivery_address;
	$_POST['phone'] = $cart->phone;
	$_POST['Location'] = $cart->Location;
	$_POST['ship_via'] = $cart->ship_via;

	$_POST['customer_id'] = $cart->customer_id;

	$_POST['branch_id'] = $cart->Branch;
	$_POST['sales_type'] = $cart->sales_type;
	// POS
	if ($cart->trans_type == ST_SALESINVOICE)
		$_POST['cash'] = $cart->cash;
	if ($cart->trans_type!=ST_SALESORDER && $cart->trans_type!=ST_SALESQUOTE) { // 2008-11-12 Joe Hunt
		$_POST['dimension_id'] = $cart->dimension_id;
		$_POST['dimension2_id'] = $cart->dimension2_id;
	}
	$_POST['cart_id'] = $cart->cart_id;

}
function process_serialize_so()
{
	global $path_to_root, $Ajax;
	
	$all_serials = $_SESSION['SRL'];
	//$location = $_POST['FromLocation'] ;//$cart[1];
	$unit = get_column($_POST['units']);
	//check_exist($all_serials,$location,$unit);
	$all_serial = $_SESSION['SRL'];
	$loc_code = $_POST['Location'] ;//$cart[1];
	$units = get_column($_POST['units']);
	//$cart	= array();
	foreach($all_serial as $row)
	{  
		if(!serial_exist($row,$loc_code,$units))
		{
			hyperlink_back();
			 //submit_center_first('ConfirmOrder', $ourcorder,   _('Confirm Order'), 'default');
	  	     //submit_js_confirm('ConfirmOrder', _('You are about to confirm this sales order.\nDo you want to continue?'));
			display_error(_( $units ." with serial ". $row . " does not exist in the selected location ".$_REQUEST['FromLocation']));
			return ;
		}
	}
	upd_so_serials($all_serials,$unit,$_POST['OrderNumber']);
	unset($_SESSION['SRL']);
	
	meta_forward($_SERVER['PHP_SELF'], "AddedID=".$_POST['OrderNumber']);
	//meta_forward($path_to_root . "/sales/customer_delivery.php", "OrderNumber=".get_sales_orderno($_POST['OrderNum']));
	
}
//-------------------------------------------------------------------------------------------------
function check_exist()
{

	echo 'here:';
	$all_serial = $_SESSION['SRL'];
	$loc_code = $_POST['FromLocation'] ;//$cart[1];
	$units = get_column($_POST['units']);
	//$cart	= array();
	foreach($all_serial as $row)
	{  
		if(!serial_exist($row,$loc_code,$units))
		{
			hyperlink_back();
			display_error(_("Item with serial ". $row . " does not exist in the selected location"));
			return ;
		}
	}
}
//-----------------------------------------------------------------------------------------------------
function setvariables()
{
	$cart	= array();
	$column ;
	$column = get_column($_POST['units']);
	
	$_SESSION['SRL'] = $_POST['srl_no'];
	
	$cart['OrderNumber'] = $_POST['OrderNumber'];
	$cart['location_code'] = $_POST['Location'];
	//display_notification ( $_POST['Location']) ;
	//$cart['srl_no'] = $_POST['srl_no'];
	$cart['units'] = $column; 
	$cart['multiplier'] = get_unit_info($_POST['units']);
	$cartstring = implode("|", $cart);
	$_SESSION['CART'] = $cartstring;

}
//--------------------------------------------------------------------------------------------------
if (isset($_POST['ScanBrick']))
{	
	setvariables();
	$all_serial = $_SESSION['SRL'];
	$loc_code = $_POST['FromLocation'] ;//$cart[1];
	//display_notification ("checkin posted ".$_REQUEST['FromLocation']) ;
	//echo "checking posted ".$_REQUEST['FromLocation'] ;
	$column = get_column($_POST['units']);
	//$cart	= array();
	foreach($all_serial as $row)
	{  
		if(!serial_exist($row,$loc_code,$column))
		{
			display_error(_("Item with serial ". $row . " does not exist in the selected location ".$loc_code));
			return ;
		}
	}
	header("Location:".$path_to_root. "/simplex/sales/so_pick_serialize_2.php?OrderNumber=" . $_POST['OrderNumber']);
}
if (isset($_POST['ScanCard']))
{	
	setvariables();
	$all_serial = $_SESSION['SRL'];
	$loc_code = $_POST['FromLocation'] ;//$cart[1];
	//display_notification ("checkin posted ".$_REQUEST['FromLocation']) ;
	//echo "checking posted ".$_REQUEST['FromLocation'] ;
	$column = get_column($_POST['units']);
	//$cart	= array();
	foreach($all_serial as $row)
	{  
		if(!serial_exist($row,$loc_code,$column))
		{
			display_error(_("Item with serial ". $row . " does not exist in the selected location ".$loc_code));
			return ;
		}
	}
	header("Location:".$path_to_root. "/simplex/sales/so_pick_serialize_3.php?OrderNumber=" . $_POST['OrderNumber'] ."&cart_id=".$_POST['cart_id']);
}

if (isset($_POST['ProcessPick']))
{


	if ($_SESSION['remainder'] > 0)	{
		display_error(_("You must Scan all items."));
		echo "<center><p><a href='javascript:goBack();'>Back</a></p></center><br>";
		set_focus('srl_no');
		return false;
	}

}


//--------------------------------------------------------------------------------------------------
if (isset($_POST['ProcessPick']))
{
	setvariables();
	//display_notification( $_POST['Location']);
	process_serialize_so();
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
display_heading(_("Pick Delivery Items"));
//display_po_serialize_items();
echo "<center>" . _("Select Units to scan:"). " ";
echo units_list('units','',false,true);


if (isset($_GET['OrderNumber']))
{
	hidden('OrderNumber', $_GET['OrderNumber']);
}
else
	hidden('OrderNumber', $_POST['OrderNumber']);
/*if (isset($_GET['qty_disp']))
{
	hidden('qtyhidden', $_GET['qty_disp']);
}
else
	hidden('qtyhidden', $_POST['qtyhidden']);
if (isset($_GET['stock_id']))
{
	hidden('StockIdhidden', $_GET['stock_id']);
}
else
	hidden('StockIdhidden', $_POST['StockIdhidden']);
	*/
if (isset($_GET['loc_code']))
{
	hidden('FromLocation', $_GET['loc_code']);
}
else
	hidden('FromLocation', $_POST['Location']);
//display_notification ("getting ". $_GET['loc_code']);
//echo "getting ". $_GET['loc_code'] ;


submit_center_first('Start', _("Start"), _("Start scan"), 'default');
echo "<br>";

echo "<hr></center>";

if(isset($_GET['OrderNumber']))
{
	$OrderNumber = $_GET['OrderNumber'];
}
else
	$OrderNumber = $_POST['OrderNumber'];
if (isset($_POST['Start']))
	{
	display_so_serialize_items();
} 

submit_center_first('ProcessPick', _("Process Items"), _("Serialize and Pick"), 'default');
display_db_pager($table);

end_form();

//--------------------------------------------------------------------------------------------------

end_page();
?>

