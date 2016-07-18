<?php
/**********************************************************************
    Copyright (C) SImplex
***********************************************************************/
$page_security = 'SA_SUPPTRANSVIEW';
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
page(_($help_context = "Search Outstanding Unserialized"), false, false, "", $js);

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

	$Ajax->addDisable(true, 'OrdersAfterDate', $disable);
	$Ajax->addDisable(true, 'OrdersToDate', $disable);
	$Ajax->addDisable(true, 'StockLocation', $disable);
	$Ajax->addDisable(true, '_SelectStockFromList_edit', $disable);
	$Ajax->addDisable(true, 'SelectStockFromList', $disable);
	
	$Ajax->addDisable(true, 'mode', $disable);
	if ($disable) {
		$Ajax->addFocus(true, 'order_number');
	} else
		$Ajax->addFocus(true, 'OrdersAfterDate');

	$Ajax->activate('orders_tbl');
}


//---------------------------------------------------------------------------------------------

start_form();

start_table("class='tablestyle_noborder'");
start_row();
ref_cells(_("#:"), 'order_number', '',null, '', true);

date_cells(_("from:"), 'OrdersAfterDate', '', null, -30);
date_cells(_("to:"), 'OrdersToDate');

locations_list_cells(_("Location:"), 'StockLocation', null, true);

stock_items_list_cells(_("Item:"), 'SelectStockFromList', null, true);
mode_list_cells(_("Type:"), 'mode', null, true);
submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');
end_row();
end_table();
//---------------------------------------------------------------------------------------------
function trans_view($trans)
{
	return get_trans_view_str(ST_PURCHORDER, $trans["order_no"]);
}

function prt_link($row)
{
	return print_document_link($row['order_no'], _("Print"), true, 18, ICON_PRINT);
}

function receive_link($row) 
{
  return pager_link( _("Serialize"),
	"/simplex/purchasing/po_receive_serialize.php?TransID=" . $row["trans_id"] . "&PONumber=" . $row['order_no'],  ICON_RECEIVE);
	//Laolu comments add PONumber to request string to be used in po_receive_serialize
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
if (isset($_POST['mode']) && ($_POST['mode'] != "") && 
	($_POST['mode'] != $all_types))
{
 	$selected_mode = $_POST['mode'];
}
else
{
	unset($selected_mode);
}

//figure out the sql required from the inputs available //stock.order_no,
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
	AND smaster.serializable=1
	AND (location.location_type='ARR') ";
	//AND stock.visible=1

if (isset($order_number) && $order_number != "")
{
	$sql .= "AND stock.order_no LIKE ".db_escape('%'. $order_number . '%');
}
else
{
	$data_after = date2sql($_POST['OrdersAfterDate']);
	$data_before = date2sql($_POST['OrdersToDate']);

	$sql .= "  AND stock.tran_date >=to_date( '$data_after', 'yyyy-mm-dd hh24:mi:ss') ";
	$sql .= "  AND stock.tran_date <= '$data_before'";

	//if (isset($_POST['StockLocation']) && $_POST['StockLocation'] != $all_items)
	//{
	//	$sql .= " AND porder.into_stock_location = ".db_escape($_POST['StockLocation']);
	//}

	if (isset($selected_stock_item))
	{
		$sql .= " AND stock.stock_id=".db_escape($selected_stock_item);
	}
	
	if (isset($selected_mode))
	{
		$sql .= " AND stock.type=".db_escape($selected_mode); 
	}
} //end not order number selected
$sql .= " ORDER BY stock.order_no desc";
//$sql .= " GROUP BY porder.order_no";

$result = db_query($sql,"No stock were returned");
//echo $sql;
/*show a table of the orders returned by the sql */
$cols = array(
		_("Order #") => array('fun'=>'trans_view', 'ord'=>''), 
		_("Transaction #"), 
		_("Item"), 
		_("Quantity"), 
		_("Location") => array('ord'=>''),
		_("Received Date"),
		//_("Transaction Date") => array('name'=>'ord_date', 'type'=>'date', 'ord'=>'desc'),
		array('insert'=>true, 'fun'=>'prt_link'),
		array('insert'=>true, 'fun'=>'receive_link')
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
