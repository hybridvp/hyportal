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
page(_($help_context = "Scan Imported Items"), false, false, "", $js);

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
	return get_trans_view_str(ST_PURCHORDER, $trans["ponumber"]);
}

//---------------------------------------------------------------------------------------------
function prt_link($row)
{
	return print_document_link($row['ponumber'], _("Print"), true, 18, ICON_PRINT);
}


//---------------------------------------------------------------------------------------------
function receive_link($row) 
{
  return pager_link( _("Serialize"),
	"/simplex/purchasing/po_receive_serialize.php?TransID=" . $row["trans_id"] . "&PONumber=" . $row['ponumber'],  ICON_RECEIVE);
	//Laolu comments add PONumber to request string to be used in po_receive_serialize
}

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
//substr(suppliers.supp_name,1,2)
$sql = "SELECT 
	po.ponumber,
	smaster.description,
	po.quantity_ordered,
	suppliers.supp_name,
	location.location_name,
	po.orderdate,
	po.item_code,
	po.into_stock_location,
	id,
	stock.trans_id
	
	FROM "
		.TB_PREF."po_import po, "
		.TB_PREF."locations location, "
		.TB_PREF."stock_master smaster, "
		.TB_PREF."suppliers, "
		.TB_PREF."stock_moves stock
	WHERE location.loc_code = po.into_stock_location
	AND smaster.stock_id = po.item_code
	AND suppliers.supplier_id = po.vendor
	AND stock.serialized != 1
	and stock.order_no =po.ponumber
	AND stock.loc_code = po.into_stock_location
	AND smaster.serializable=1 AND upper(status) = 'APPROVED'";

//--	AND stock.stock_id=po.item_code
if (isset($order_number) && $order_number != "")
{
	$sql .= " AND po.ponumber LIKE ".db_escape('%'. $order_number . '%');
}
else
{
	$data_after = date2sql($_POST['TrsfAfterDate']);
	$data_before = date2sql($_POST['TrsfToDate']);

	$sql .= "  AND po.orderdate >=to_date( '$data_after', 'yyyy-mm-dd') ";
	$sql .= "  AND po.orderdate <= to_date( '$data_before', 'yyyy-mm-dd') ";

	if (isset($_POST['StockLocation']) && $_POST['StockLocation'] != $all_items)
	{
		$sql .= " AND po.into_stock_location = ".db_escape($_POST['StockLocation']);
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
		$sql .= " AND po.units=" .db_escape($strunit);
	}
	if (isset($selected_stock_item))
	{
		$sql .= " AND po.item_code=".db_escape($selected_stock_item);
	}
} //end not order number selected
$sql .= "ORDER BY stock.trans_no";

//echo $sql;

$result = db_query($sql,"No serialized stock were returned");
//echo $sql;
/*show a table of the orders returned by the sql */
$cols = array(
		_("Order #") => array('fun'=>'trans_view', 'ord'=>''), 
		_("Item"), 
		_("Quantity"), 
		_("Supplier"),
		_("Location") => array('ord'=>''),
		_("Order Date"),
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