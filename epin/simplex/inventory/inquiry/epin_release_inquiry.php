<?php
/**********************************************************************
    Copyright (C) SIMPLEX
***********************************************************************/
$path_to_root = "../../..";

include($path_to_root . "/includes/db_pager.inc");
include($path_to_root . "/includes/session.inc");
include($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

$page_security = 'SA_SUPPTRANSVIEW';

set_page_security( @$_POST['order_view_mode'],
	array(	'OutstandingOnly' => 'SA_SALESDELIVERY',
			'InvoiceTemplates' => 'SA_SALESINVOICE'),
	array(	'OutstandingOnly' => 'SA_SALESDELIVERY',
			'InvoiceTemplates' => 'SA_SALESINVOICE')
);

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 600);
if ($use_date_picker)
	$js .= get_js_date_picker();

if (get_post('type'))
	$trans_type = $_POST['type'];
elseif (isset($_GET['type']) && $_GET['type'] == ST_SALESQUOTE)
	$trans_type = ST_SALESQUOTE;
else
	$trans_type = ST_SALESORDER;

if ($trans_type == ST_SALESORDER)
{
	if (isset($_GET['OutstandingOnly']) && ($_GET['OutstandingOnly'] == true))
	{
		$_POST['order_view_mode'] = 'OutstandingOnly';
		$_SESSION['page_title'] = _($help_context = "Search Outstanding Sales Orders");
	}
	elseif (isset($_GET['InvoiceTemplates']) && ($_GET['InvoiceTemplates'] == true))
	{
		$_POST['order_view_mode'] = 'InvoiceTemplates';
		$_SESSION['page_title'] = _($help_context = "Search Template for Invoicing");
	}
	elseif (isset($_GET['DeliveryTemplates']) && ($_GET['DeliveryTemplates'] == true))
	{
		$_POST['order_view_mode'] = 'DeliveryTemplates';
		$_SESSION['page_title'] = _($help_context = "Select Template for Delivery");
	}
	elseif (!isset($_POST['order_view_mode']))
	{
		$_POST['order_view_mode'] = false;
		$_SESSION['page_title'] = _($help_context = "Search All Released Sales Orders");
	}
}
else
{
	$_POST['order_view_mode'] = "Quotations";
	$_SESSION['page_title'] = _($help_context = "Search All Sales Quotations");
}
page($_SESSION['page_title'], false, false, "", $js);

if (isset($_GET['selected_customer']))
{
	$selected_customer = $_GET['selected_customer'];
}
elseif (isset($_POST['selected_customer']))
{
	$selected_customer = $_POST['selected_customer'];
}
else
	$selected_customer = -1;

//---------------------------------------------------------------------------------------------

if (isset($_POST['SelectStockFromList']) && ($_POST['SelectStockFromList'] != "") &&
	($_POST['SelectStockFromList'] != ALL_TEXT))
{
 	$selected_stock_item = $_POST['SelectStockFromList'];
}
else
{
	unset($selected_stock_item);
}
//---------------------------------------------------------------------------------------------
//	Query format functions
//
function check_overdue($row)
{
	global $trans_type;
	if ($trans_type == ST_SALESQUOTE)
		return (date1_greater_date2(Today(), sql2date($row['delivery_date'])));
	else
		return ($row['type_'] == 0
			&& date1_greater_date2(Today(), sql2date($row['ord_date']))
			&& ($row['totdelivered'] < $row['totquantity']));
}

function view_link($dummy, $order_no)
{
	global $trans_type;
	return  get_customer_trans_view_str($trans_type, $order_no);
}

function prt_link($row)
{
	global $trans_type;
	return print_document_link($row['order_no'], _("Print"), true, $trans_type, ICON_PRINT);
}

function edit_link($row)
{
	global $trans_type;
	$modify = ($trans_type == ST_SALESORDER ? "ModifyOrderNumber" : "ModifyQuotationNumber");
//Added trans_type for completenss check on next form when determining status of the order
  return pager_link( _("Edit"),
    "/sales/sales_order_entry.php?$modify=" . $row['order_no']."&trans_type=". $trans_type, ICON_EDIT);
}

function dispatch_link($row)
{
	global $trans_type;
	if ($trans_type == ST_SALESORDER)
  		return pager_link( _("Dispatch"),
			"/sales/customer_delivery.php?OrderNumber=" .$row['order_no'], ICON_DOC);
	else
  		return pager_link( _("Sales Order"),
			"/sales/sales_order_entry.php?OrderNumber=" .$row['order_no'], ICON_DOC);
}

function invoice_link($row)
{
	global $trans_type;
	if ($trans_type == ST_SALESORDER)
  		return pager_link( _("Invoice"),
			"/sales/sales_order_entry.php?NewInvoice=" .$row["order_no"], ICON_DOC);
	else
		return '';
}

function delivery_link($row)
{
  return pager_link( _("Delivery"),
	"/sales/sales_order_entry.php?NewDelivery=" .$row['order_no'], ICON_DOC);
}

function order_link($row)
{
  return pager_link( _("Sales Order"),
	"/sales/sales_order_entry.php?NewQuoteToSalesOrder=" .$row['order_no'], ICON_DOC);
}

function tmpl_checkbox($row)
{
	global $trans_type;
	if ($trans_type == ST_SALESQUOTE)
		return '';
	$name = "chgtpl" .$row['order_no'];
	if ( $row['status'] == 'P' )
		$value = 0;
	else if ($row['status'] == 'R' )
		$value = 1;
	
	if ( $row['status'] == 'F' )
		$value = 0;
		//$value = 1;
		
	//$value = $row['status'] ? 1:0;

// save also in hidden field for testing during 'Update'

 return checkbox(null, $name, $value, true,
 	_('Free up the serials Resale'))
	. hidden('last['.$row['order_no'].']', $value, false);
}
//---------------------------------------------------------------------------------------------
// Update db record if respective checkbox value has changed.
//
function change_tpl_flag($id)
{
	global	$Ajax;

  	$sql = "UPDATE ".TB_PREF."pin_mailer_jobs_detail SET status = 'U' WHERE order_no=$id ";//AND line_no=$line

  	db_query($sql, "Can't change sales order type");
	$Ajax->activate('orders_tbl');
}

$id = find_submit('_chgtpl');
if ($id != -1)
	change_tpl_flag($id);

if (isset($_POST['Update']) && isset($_POST['last'])) {
	foreach($_POST['last'] as $id => $value)
		if ($value != check_value('chgtpl'.$id))
			change_tpl_flag($id);
}

//---------------------------------------------------------------------------------------------
//	Order range form
//
if (get_post('_OrderNumber_changed')) // enable/disable selection controls
{
	$disable = get_post('OrderNumber') !== '';

  	if ($_POST['order_view_mode']!='DeliveryTemplates'
		&& $_POST['order_view_mode']!='InvoiceTemplates') {
			$Ajax->addDisable(true, 'OrdersAfterDate', $disable);
			$Ajax->addDisable(true, 'OrdersToDate', $disable);
	}
	$Ajax->addDisable(true, 'StockLocation', $disable);
	$Ajax->addDisable(true, '_SelectStockFromList_edit', $disable);
	$Ajax->addDisable(true, 'SelectStockFromList', $disable);

	if ($disable) {
		$Ajax->addFocus(true, 'OrderNumber');
	} else
		$Ajax->addFocus(true, 'OrdersAfterDate');

	$Ajax->activate('orders_tbl');
}

start_form();

start_table("class='tablestyle_noborder'");
start_row();
ref_cells(_("#:"), 'OrderNumber', '',null, '', true);
if ($_POST['order_view_mode'] != 'DeliveryTemplates' && $_POST['order_view_mode'] != 'InvoiceTemplates')
{
  	date_cells(_("from:"), 'OrdersAfterDate', '', null, -30);
  	date_cells(_("to:"), 'OrdersToDate', '', null, 1);
}
locations_list_cells(_("Location:"), 'StockLocation', null, true);

stock_items_list_cells(_("Item:"), 'SelectStockFromList', null, true);

if ($trans_type == ST_SALESQUOTE)
	check_cells(_("Show All:"), 'show_all');
submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');

hidden('order_view_mode', $_POST['order_view_mode']);
hidden('type', $trans_type);

end_row();

end_table(1);
//---------------------------------------------------------------------------------------------
//	Orders inquiry table
//
//added sorder.ourorder_status in the select clause for order status in summary sorder.ourorder_status
$sql = "SELECT
		sorder.order_no,
		line.id ,
		sorder.reference,
		debtor.name,
		"
		.($_POST['order_view_mode']=='InvoiceTemplates'
		   	|| $_POST['order_view_mode']=='DeliveryTemplates' ?
		 "sorder.comments, " : "sorder.customer_ref, ")
		."sorder.ord_date,
		line.stk_code,
		line.quantity, "
		//Sum(line.unit_price*line.quantity*(1-line.discount_percent)+freight_cost) AS ordervalue, "	//+freight_cost was added inside this may lead to multiple addition, check alter
		."sorder.type_,
		
		sorder.ourorder_status,
		Sum(line.qty_sent) AS totdelivered,
		Sum(line.quantity) AS totquantity,
		detail.status
	FROM ".TB_PREF."sales_orders sorder, "
		.TB_PREF."sales_order_details  line, "
		.TB_PREF."debtors_master debtor, "
		.TB_PREF."cust_branch  branch, "
		.TB_PREF."pin_mailer_jobs_detail detail, "
		.TB_PREF."pin_mailer_jobs jobs
		WHERE sorder.order_no = line.order_no
		AND sorder.trans_type = line.trans_type
		AND sorder.trans_type = $trans_type
		AND sorder.debtor_no = debtor.debtor_no
		AND sorder.branch_code = branch.branch_code
		AND debtor.debtor_no = branch.debtor_no
		AND debtor.debtor_no = branch.debtor_no
		AND sorder.debtor_no = detail.customer_no
		AND jobs.order_no = detail.order_no
		AND jobs.line_no = detail.line_no
		AND line.id = detail.line_no

		AND detail.status = 'R' ";
//		AND jobs.status not in ('Q', 'M')
if (isset($_POST['OrderNumber']) && $_POST['OrderNumber'] != "")
{
	// search orders with number like
	$number_like = "%".$_POST['OrderNumber'];
	$sql .= " AND sorder.order_no LIKE ".db_escape($number_like)
 			." GROUP BY sorder.order_no";
}
else	// ... or select inquiry constraints
{
  	if ($_POST['order_view_mode']!='DeliveryTemplates' && $_POST['order_view_mode']!='InvoiceTemplates')
  	{
		$date_after = date2sql($_POST['OrdersAfterDate']);
		$date_before = date2sql($_POST['OrdersToDate']);

		$sql .=  " AND sorder.ord_date >= '$date_after'"
				." AND sorder.ord_date <= to_date('$date_before', 'yyyy-mm-dd hh24:mi:ss') ";
  	}
  	if ($trans_type == 32 && !check_value('show_all'))
  		$sql .= " AND sorder.delivery_date >= '".date2sql(Today())."'";
	if ($selected_customer != -1)
		$sql .= " AND sorder.debtor_no=".db_escape($selected_customer);

	if (isset($selected_stock_item))
		$sql .= " AND line.stk_code=".db_escape($selected_stock_item);

	if (isset($_POST['StockLocation']) && $_POST['StockLocation'] != ALL_TEXT)
		$sql .= " AND sorder.from_stk_loc = ".db_escape($_POST['StockLocation']);

	if ($_POST['order_view_mode']=='OutstandingOnly')
	//Added status check for delivery  in sales_orders_view.php
		$sql .= " AND line.qty_sent < line.quantity AND ourorder_status= 'Confirmed'";
	elseif ($_POST['order_view_mode']=='InvoiceTemplates' || $_POST['order_view_mode']=='DeliveryTemplates')
		$sql .= " AND sorder.type_=1";

//added sorder.ourorder_status in the group clause for order status in summary
	$sql .= " GROUP BY 		sorder.order_no,
							line.id,
							sorder.reference,
							debtor.name,
							branch.br_name,sorder.customer_ref, sorder.ord_date,
							line.stk_code,
							line.quantity,
							sorder.type_,
							debtor.curr_code,
							sorder.ourorder_status,
							detail.status";
	$sql .= " ORDER BY sorder.order_no desc";
}

if ($trans_type == ST_SALESORDER)
	$cols = array(
		_("Order #") => array('fun'=>'view_link'),
		_("Line #"),
		_("Ref"),
		_("Customer"),
		//_("Branch"),
		_("Cust Order Ref"),
		_("Order Date") => 'date',
//		_("Required By") =>array('type'=>'date', 'ord'=>''),
		_("Stock Id"),
		_("Quantity"),
		//_("Order Total") => array('type'=>'amount', 'ord'=>''),
		'Type' => 'skip',
		//_("Currency") => array('align'=>'center'),
//added order status to the summary display page  _("Order Status")
		_("Order Status")
	);
else
	$cols = array(
		_("Quote #") => array('fun'=>'view_link'),
		_("Ref"),
		_("Customer"),
		//_("Branch"),
		_("Cust Order Ref"),
		_("Quote Date") => 'date',
		_("Valid until") =>array('type'=>'date', 'ord'=>''),
		_("Delivery To"),
		//_("Quote Total") => array('type'=>'amount', 'ord'=>''),
		'Type' => 'skip',
		//_("Currency") => array('align'=>'center'),
//added order status to the summary display page  _("Order Status")
		_("Order Status")
	);

if ($_POST['order_view_mode'] == 'OutstandingOnly') {
	//array_substitute($cols, 3, 1, _("Cust Order Ref"));
	array_append($cols, array(array('insert'=>false, 'fun'=>'dispatch_link')));
    
} elseif ($_POST['order_view_mode'] == 'InvoiceTemplates') {
	array_substitute($cols, 3, 1, _("Description"));
	array_append($cols, array( array('insert'=>true, 'fun'=>'invoice_link')));

} else if ($_POST['order_view_mode'] == 'DeliveryTemplates') {
	array_substitute($cols, 3, 1, _("Description"));
	array_append($cols, array(
			array('insert'=>true, 'fun'=>'delivery_link'))
	);

} elseif ($trans_type == ST_SALESQUOTE) {
	 array_append($cols,array(
					array('insert'=>true, 'fun'=>'edit_link'),
//Added: commented out the conversion of SQ to SO as it is now a separate function 					
					//array('insert'=>true, 'fun'=>'order_link'),
					array('insert'=>true, 'fun'=>'prt_link')));
} elseif ($trans_type == ST_SALESORDER) {
	 /* array_append($cols,array(
			_("Release") => array('insert'=>true, 'fun'=>'tmpl_checkbox'),
					//array('insert'=>true, 'fun'=>'edit_link'),
					array('insert'=>true, 'fun'=>'prt_link'))); */
};


$table =& new_db_pager('orders_tbl', $sql, $cols);
$table->set_marker('check_overdue', _("Marked items are overdue."));

$table->width = "80%";

display_db_pager($table);
submit_center('Update', _("Update"), true, '', null);

end_form();
end_page();
?>