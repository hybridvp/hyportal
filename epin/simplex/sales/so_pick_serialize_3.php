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
include_once($path_to_root . "/simplex/purchasing/includes/ui/ui_funcs.inc");
include_once($path_to_root . "/simplex/sales/includes/sales.inc");
//include_once($path_to_root . "/simplex/includes/ui/ui_lists.php");


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

	display_notification_centered(_("Sales Order Picking has been processed"));

		hyperlink_params("$path_to_root/simplex/sales/inquiry/picked_item_inquiry.php", _("&View Picked Items"), "order_number=". $_GET['AddedID']);

	hyperlink_no_params("$path_to_root/simplex/sales/inquiry/sales_orders_pick.php", _("Select a different &sales order for picking"));
	

	display_footer_exit();
}

//--------------------------------------------------------------------------------------------------


if (!isset($_SESSION['remainder2']))
{
	die (_("This page can only be opened if a sales order # has been selected. Please select a sales order #  first."));
}
if ((!isset($_GET['OrderNumber']) || $_GET['OrderNumber'] == 0)  && !isset( $_POST['OrderNumber']))
{
	die (_("This page can only be opened if a sales order # has been selected. Please select a sales order #  first."));
}
if(isset($_GET['OrderNumber']) )
{
	if( ispicked($_GET['OrderNumber']))
	{
		hyperlink_back();
		die(_("Items have already been picked for this order."));
	}
}
/*if ($_GET['qty_disp'] > get_qty_serialized($_GET['stock_id'],$_GET['loc_code']))
{
	hyperlink_back();
	die(_("There are not enough serialized items to allocate."));
}*/
function get_unit_name($name)
{
		$sql2 = "SELECT name from "
			.TB_PREF."item_units  
			WHERE abbr = '". $name . "'";
			
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
	$result = get_line_item($OrderNumber);
	$line_qty = $_SESSION['remainder2'] ; //$result["quantity"] ;  //- $result["stock.qty_serialized"]
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
		display_notification_centered(_("Cannot scan as selected Unit , Not enough items"));
	}
	if($count > 0)
	{
		for($i=1; $i<=$count; $i++)
		{
			
				alt_table_row_color($k);
				label_cell($i);
				label_cell($result["id"]);
				label_cell($result["description"]);
				label_cell($multiplier);
				label_cell($units);
				$remainder = $remainder  - $multiplier;
				text_cells(null, 'srl_no[]','' , 30, 50);
				label_cell($remainder, '', 'remain');
				end_row();
				$_SESSION['remainder3'] = $remainder;
		}
				
	}
      end_table();
	div_end();
	
}
function db_serialize($cart,$serials)
{
	$result = get_line_item($cart[0]);
	$location = $cart[1];
	$unit = $cart[2];
	

/* 	$all_serials = $_SESSION['SRL'];
	$location = $_POST['Location'] ;//$cart[1];
	$cart	= array(); */
	foreach($serials as $row)
	{  
		if(!serial_exist($row,$location,$unit))
		{
			hyperlink_back();
			die(_("Item with serial ". $row . " does not exist in the selected location"));
			return ;
		}
	}
	upd_so_serials($serials,$unit,$cart[0]);
}
function process_serialize_so()
{
	global $path_to_root, $Ajax;

	if(isset($_SESSION['CART']))
	{
		$sessString3 = $_SESSION['CART3'];
		$cart3 = explode("|", $sessString3) ;
		$all_serials3 = $_SESSION['SRL3'];
	}
	//for bricks
	if(isset($_SESSION['CART']))
	{
		$sessString2 = $_SESSION['CART2'];
		$cart2 = explode("|", $sessString2) ;
		$all_serials2 = $_SESSION['SRL2'];
	}
		//for boxes
	if(isset($_SESSION['CART']))
	{
		$sessString = $_SESSION['CART'];
		$cart = explode("|", $sessString) ;
		$all_serials = $_SESSION['SRL'];
	}
	
	begin_transaction();
	if(isset($_SESSION['CART']))
	{
		db_serialize($cart3,$all_serials3);
	}
	if(isset($_SESSION['CART']))
	{
		db_serialize($cart2,$all_serials2);
	}
	if(isset($_SESSION['CART']))
	{
		db_serialize($cart,$all_serials);
	}

	//echo 'here now';
	

	
	commit_transaction();

	display_notification(_('success'));

	unset($_SESSION['CART3']);
	unset($_SESSION['SRL3']);
	
	unset($_SESSION['CART2']);
	unset($_SESSION['SRL2']);
	
	unset($_SESSION['CART']);
	unset($_SESSION['SRL']);
	
	meta_forward($_SERVER['PHP_SELF'], "AddedID=".$_POST['OrderNumber']);
	//meta_forward($path_to_root . "/sales/customer_delivery.php", "OrderNumber=".get_sales_orderno($_POST['OrderNumber']));
	
}

//-----------------------------------------------------------------------------------------------------
function setvariables()
{
	$cart	= array();
	$column ;
	$column = get_column($_POST['units']);
	
	$srl_no = $_POST['srl_no'];
	
	$cart['OrderNumber'] = $_POST['OrderNumber'];
	$cart['location_code'] = $_POST['Location'];
	//$cart['srl_no'] = $_POST['srl_no'];
	$cart['units'] = $column;
	$cart['multiplier'] = get_unit_info($_POST['units']);
	$cartstring = implode("|", $cart);
	$_SESSION['CART3'] = $cartstring;
	$_SESSION['SRL3'] = $srl_no;
	
	//$_SESSION['SRL2'] = $_POST['srl_no'];
}

if (isset($_POST['ProcessPick']))
{
	if ($_SESSION['remainder3'] > 0)	{
		display_notification_centered(_("You must Scan all items."));
		echo "<center><p><a href='javascript:goBack();'>Back</a></p></center><br>";
		set_focus('srl_no');
		return false;
	}
}


//--------------------------------------------------------------------------------------------------
if (isset($_POST['ProcessPick']))
{

	setvariables();	

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
echo units_list('units','cd.',false,true);
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
	hidden('StockIdhidden', $_POST['StockIdhidden']);*/
if (isset($_GET['loc_code']))
{
	hidden('FromLocation', $_GET['loc_code']);
}
else
	hidden('FromLocation', $_POST['Location']);
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

