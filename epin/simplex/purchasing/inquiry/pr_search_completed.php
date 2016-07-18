<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SUPPTRANSVIEW';
$path_to_root="../../..";
include($path_to_root . "/includes/db_pager.inc");
include($path_to_root . "/includes/session.inc");

include($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Search Purchase Requisition"), false, false, "", $js);

if (isset($_GET['order_number']))
{
	$order_number = $_GET['order_number'];
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

locations_list_cells(_("into location:"), 'StockLocation', null, true);

stock_items_list_cells(_("for item:"), 'SelectStockFromList', null, true);

submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');
end_row();
end_table();
//---------------------------------------------------------------------------------------------
if (isset($_POST['order_number']))
{
	$order_number = $_POST['order_number'];
}

if (isset($_POST['SelectStockFromList']) &&	($_POST['SelectStockFromList'] != "") &&
	($_POST['SelectStockFromList'] != ALL_TEXT))
{
 	$selected_stock_item = $_POST['SelectStockFromList'];
}
else
{
	unset($selected_stock_item);
}

//---------------------------------------------------------------------------------------------
function trans_view($trans)
{
	return get_trans_view_str(ST_PURCHREQ, $trans["pr_no"]);
}

function fwd_link($row) 
{
  return pager_link( _("Forward to authoriser"),
	"/simplex/purchasing/pr_entry_items.php?" . SID 
	. "ForwardOrderNumber=" . $row["pr_no"], ICON_DOC);
}

function edit_link($row) 
{
  return pager_link( _("Edit"),
	"/simplex/purchasing/pr_entry_items.php?" . SID 
	. "ModifyOrderNumber=" . $row["pr_no"], ICON_EDIT);
}

function prt_link($row)
{
	return print_document_link($row['pr_no'], _("Print"), true, 18, ICON_PRINT);
}

//---------------------------------------------------------------------------------------------

$sql = "SELECT 
	porder.pr_no, 
	porder.reference, 
	supplier.supp_name, 
	location.location_name,
	porder.requisition_no, 
	porder.ord_date, 
	supplier.curr_code, 
	Sum(line.unit_price*line.quantity_ordered) AS ordervalue,
	porder.status,
	porder.into_stock_location
	FROM ".TB_PREF."purch_reqs as porder, "
		.TB_PREF."purch_req_details as line, "
		.TB_PREF."suppliers supplier, "
		.TB_PREF."locations location
	WHERE porder.pr_no = line.pr_no
	AND porder.supplier_id = supplier.supplier_id
	AND location.loc_code = porder.into_stock_location ";

if (isset($order_number) && $order_number != "")
{
	$sql .= "AND porder.reference LIKE ".db_escape('%'. $order_number . '%');
}
else
{

	$data_after = date2sql($_POST['OrdersAfterDate']);
	$date_before = date2sql($_POST['OrdersToDate']);

	$sql .= " AND porder.ord_date >=to_date( '$data_after', 'yyyy-mm-dd hh24:mi:ss') ";
	$sql .= " AND porder.ord_date <= to_date('$date_before', 'yyyy-mm-dd hh24:mi:ss') ";

	if (isset($_POST['StockLocation']) && $_POST['StockLocation'] != ALL_TEXT)
	{
		$sql .= " AND porder.into_stock_location = ".db_escape($_POST['StockLocation']);
	}
	if (isset($selected_stock_item))
	{
		$sql .= " AND line.item_code=".db_escape($selected_stock_item);
	}

} //end not order number selected

$sql .= " GROUP BY 	porder.pr_no, 
	porder.reference, 
	supplier.supp_name, 
	location.location_name,
	porder.requisition_no, 
	porder.ord_date, 
	supplier.curr_code, 
	porder.status,
	porder.into_stock_location";

$cols = array(
		_("#") => array('fun'=>'trans_view', 'ord'=>''), 
		_("Reference"), 
		_("Supplier") => array('ord'=>''),
		_("Location"),
		_("Supplier's Reference"), 
		_("Req Date") => array('name'=>'ord_date', 'type'=>'date', 'ord'=>'desc'),
		_("Currency") => array('align'=>'center'), 
		_("Order Total") => 'amount',
		_("Order Status") ,
		array('insert'=>true, 'fun'=>'edit_link'),
		array('insert'=>true, 'fun'=>'prt_link'),		
		array('insert'=>true, 'fun'=>'fwd_link'),
);

if (get_post('StockLocation') != $all_items) {
	$cols[_("Location")] = 'skip';
}
//---------------------------------------------------------------------------------------------------

$table =& new_db_pager('orders_tbl', $sql, $cols);

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
?>
