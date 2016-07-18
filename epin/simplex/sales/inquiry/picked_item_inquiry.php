<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_ITEMSTRANSVIEW';
$path_to_root = "../../..";
include($path_to_root . "/includes/db_pager.inc");
include($path_to_root . "/includes/session.inc");

include($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Search Picked Items"), false, false, "", $js);

if (isset($_GET['order_number']))
{
	$_POST['order_number'] = $_GET['order_number'];
}
//-----------------------------------------------------------------------------------
// Ajax updates
//
if (get_post('SearchOrders')) 
{
	$Ajax->activate('orders_tbl');
} elseif (get_post('_order_number_changed')) 
{
	$disable = get_post('order_number') !== '';

	$Ajax->addDisable(true, 'TrsfAfterDate', $disable);
	$Ajax->addDisable(true, 'TrsfToDate', $disable);
	$Ajax->addDisable(true, 'StockLocation', $disable);
	$Ajax->addDisable(true, '_SelectStockFromList_edit', $disable);
	$Ajax->addDisable(true, 'SelectStockFromList', $disable);
	$Ajax->addDisable(true, 'units', $disable);
	
	if ($disable) {
		$Ajax->addFocus(true, 'order_number');
	} else
		$Ajax->addFocus(true, 'TrsfAfterDate');

	$Ajax->activate('orders_tbl');
}


//---------------------------------------------------------------------------------------------

start_form();

start_table("class='tablestyle_noborder'");
start_row();
ref_cells(_("#:"), 'order_number', '',null, '', true);

date_cells(_("from:"), 'TrsfAfterDate', '', null, -30);
date_cells(_("to:"), 'TrsfToDate');

units_list_cells ( _("Unit"),'units', null, true);

locations_list_cells(_("Location:"), 'StockLocation', null, true);

stock_items_list_cells(_("Item:"), 'SelectStockFromList', null, true);

submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');
end_row();
end_table();
//---------------------------------------------------------------------------------------------
function trans_view($trans)
{
	return get_trans_view_str(ST_PURCHORDER, $trans["order_no"]);
}

//---------------------------------------------------------------------------------------------
function prt_link($row)
{
	return print_document_link($row['order_no'], _("Print"), true, 18, ICON_PRINT);
}


//---------------------------------------------------------------------------------------------

if (isset($_POST['order_number']) && ($_POST['order_number'] != ""))
{
	$order_number = $_POST['order_number'];
}

if (isset($_POST['SelectStockFromList']) && ($_POST['SelectStockFromList'] != "") &&
	($_POST['SelectStockFromList'] != $all_items))
{
 	$selected_stock_item = $_POST['SelectStockFromList'];
}
else
{
	unset($selected_stock_item);
}
if (isset($_POST['units']) && ($_POST['units'] != "") &&
	($_POST['units'] != $all_items))
{
 	$selected_unit = $_POST['units'];
}
else
{
	unset($selected_unit);
}
//figure out the sql required from the inputs available //stock.order_no,
$sql = "SELECT 
	serialized.order_no,
	smaster.description,
	serialized.qty,
	serialized.box_no,
	serialized.brick_no,
	serialized.card_no,
	location.location_name,
	serialized.trans_date,
	serialized.stock_id,

	serialized.transtype, 	

	serialized.location_code
	
	
	FROM "
		.TB_PREF."serialized_stock serialized, "
		.TB_PREF."locations location, "
		.TB_PREF."stock_master smaster
	WHERE location.loc_code = serialized.location_code
	AND smaster.stock_id = serialized.stock_id
	AND smaster.serializable=1 AND status = 'PICKED'";
	//AND qty > 0 ";
	//AND serialized.serialized <> 1";
	//	AND stock.visible=1

if (isset($order_number) && $order_number != "")
{
	$sql .= " AND serialized.sales_order_no LIKE ".db_escape('%'. $order_number . '%');
}
else
{
	$data_after = date2sql($_POST['TrsfAfterDate']);
	$data_before = date2sql($_POST['TrsfToDate']);

	$sql .= "  AND serialized.trans_date >=to_date( '$data_after', 'yyyy-mm-dd hh24:mi:ss') ";
	$sql .= "  AND serialized.trans_date <= '$data_before'";

	if (isset($_POST['StockLocation']) && $_POST['StockLocation'] != $all_items)
	{
		$sql .= " AND serialized.location_code = ".db_escape($_POST['StockLocation']);
	}
	if (isset($selected_unit))
	{
		$strunit;
		switch ($_POST['units']) {
			case 'bx.':
				$strunit = "box_no";
				break;
			case 'bx.':
				$strunit =  "brick_no ";
				break;
			case 'cd.':
				$strunit = "card_no ";
				break;
			default:
				$strunit = "card_no ";
				
			} 
		$sql .= " AND serialized." . $strunit ."=" .db_escape($strunit);
	}
	if (isset($selected_stock_item))
	{
		$sql .= " AND serialized.stock_id=".db_escape($selected_stock_item);
	}
} //end not order number selected

/* $sql .= " GROUP BY stock.order_no, stock.trans_id, smaster.description, stock.qty, location.location_name, stock.tran_Date, stock.type, stock.loc_code, stock.person_id, stock.price, stock.trans_no, stock.reference
"; */

$result = db_query($sql,"No serialized stock were returned");
//echo $sql;
/*show a table of the orders returned by the sql */
$cols = array(
		_("Order #") => array('fun'=>'trans_view', 'ord'=>''), 
		_("Item"), 
		_("Quantity"), 
		_("Box no"),
		_("Brick no"),
		_("Card no"),
		_("Location") => array('ord'=>''),
		_("Received Date"),
		//_("Transaction Date") => array('name'=>'ord_date', 'type'=>'date', 'ord'=>'desc'),
		array('insert'=>true, 'fun'=>'prt_link'),

);

if (get_post('StockLocation') != $all_items) {
	$cols[_("Location")] = 'skip';
}

$table =& new_db_pager('orders_tbl', $sql, $cols);
//$table->set_marker('check_overdue', _("Marked orders have overdue items."));

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
?>
