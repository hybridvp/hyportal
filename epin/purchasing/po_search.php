<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SUPPTRANSVIEW';
$path_to_root = "..";
include($path_to_root . "/includes/db_pager.inc");
include($path_to_root . "/includes/session.inc");

include($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Search Outstanding Purchase Orders"), false, false, "", $js);

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

submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');
end_row();
end_table();
//---------------------------------------------------------------------------------------------
function trans_view($trans)
{
	return get_trans_view_str(ST_PURCHORDER, $trans["order_no"]);
}

function edit_link($row) 
{
  return pager_link( _("Edit"),
	"/purchasing/po_entry_items.php?ModifyOrderNumber=" . $row["order_no"], ICON_EDIT);
}

function prt_link($row)
{
	return print_document_link($row['order_no'], _("Print"), true, 18, ICON_PRINT);
}

function receive_link($row) 
{
  return pager_link( _("Confirm PO"),
	"/purchasing/po_confirm.php?PONumber=" . $row["order_no"], ICON_RECEIVE);
}

function check_overdue($row)
{
	return $row['overdue']==1;
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

//figure out the sql required from the inputs available
$sql = "SELECT 
	porder.order_no, 
	porder.reference,
	supplier.supp_name, 
	porder.requisition_no, 
	porder.ord_date,
	supplier.curr_code,
	Sum(line.unit_price*line.quantity_ordered) AS ordervalue,
	decode(sign(line.delivery_date-sysdate), -1, decode(sign(line.quantity_ordered - line.quantity_received), 1, 1,0),0) As OverDue
	FROM "
		.TB_PREF."purch_orders porder, "
		.TB_PREF."purch_order_details line, "
		.TB_PREF."suppliers supplier 
	WHERE porder.order_no = line.order_no
	AND porder.supplier_id = supplier.supplier_id
	AND (line.quantity_ordered > line.quantity_received) ";

if (isset($order_number) && $order_number != "")
{
	$sql .= "AND porder.reference LIKE ".db_escape('%'. $order_number . '%');
}
else
{
	$data_after = date2sql($_POST['OrdersAfterDate']);
	$data_before = date2sql($_POST['OrdersToDate']);

	$sql .= "  AND trunc(porder.ord_date) >= to_date('$data_after' ,'yyyy-mm-dd')";
	$sql .= "  AND trunc(porder.ord_date) <= to_date('$data_before' ,'yyyy-mm-dd')";

	/* if (isset($_POST['StockLocation']) && $_POST['StockLocation'] != $all_items)
	{
		$sql .= " AND porder.into_stock_location = ".db_escape($_POST['StockLocation']);
	} */

	if (isset($selected_stock_item))
	{
		$sql .= " AND line.item_code=".db_escape($selected_stock_item);
	}
} //end not order number selected

$sql .= " GROUP BY 	porder.order_no, 
	porder.reference,
	supplier.supp_name, 
	porder.requisition_no, 
	porder.ord_date,
	supplier.curr_code,
	decode(sign(line.delivery_date-sysdate); -1; decode(sign(line.quantity_ordered - line.quantity_received); 1; 1;0);0)";
//when using display_db_pager, the group by should not have any function, if there is then the function separator should be semicolon
//
//$result = db_query($sql,"No orders were returned");
//echo $sql;
/*show a table of the orders returned by the sql */
$cols = array(
		_("PO #") => array('fun'=>'trans_view', 'ord'=>''), 
		_("Reference"), 
		_("Supplier") => array('ord'=>''),
		_("Supplier's Reference"), 
		_("Order Date") => array('name'=>'ord_date', 'type'=>'date', 'ord'=>'desc'),
		_("Currency") => array('align'=>'center'), 
		_("Order Total") => 'amount',
		array('insert'=>true, 'fun'=>'edit_link'),
		array('insert'=>true, 'fun'=>'prt_link'),
		array('insert'=>true, 'fun'=>'receive_link')
);

if (get_post('StockLocation') != $all_items) {
	$cols[_("Location")] = 'skip';
}

$table =& new_db_pager('orders_tbl', $sql, $cols);
$table->set_marker('check_overdue', _("Marked orders have overdue items."));

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
?>
