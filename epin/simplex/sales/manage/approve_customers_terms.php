<?php

$path_to_root = "../../..";

include($path_to_root . "/includes/db_pager.inc");
include($path_to_root . "/includes/session.inc");
include($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

$page_security = 'SA_SOCONFIRM';
/*
set_page_security( @$_POST['order_view_mode'],
	array(	'OutstandingOnly' => 'SA_SALESDELIVERY',
			'InvoiceTemplates' => 'SA_SALESINVOICE'),
	array(	'OutstandingOnly' => 'SA_SALESDELIVERY',
			'InvoiceTemplates' => 'SA_SALESINVOICE')
);
*/
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
	
$_SESSION['page_title'] = _($help_context = "Search Outstanding Customer Terms ");

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
//	Query format functions
//

function confirm_terms($request_id,$current_status, $version)
{
   if ($current_status=='Planned')
   {
   begin_transaction();
  	$sql = "UPDATE ".TB_PREF."debtors_terms_requests set request_status = 'Confirmed', 
				approved_by='".$_SESSION['wa_current_user']->loginname."',
				approved_date=sysdate
				WHERE request_id = ".$request_id." and request_status = 'Planned' and version=".$version;

  	db_query($sql, "Unable to change state");
 
 	$sql = "select debtor_no,name, credit_status,payment_terms,discount,pymt_discount,
			credit_limit, request_status, version 
				from ".TB_PREF."debtors_terms_requests
				WHERE request_id = ".$request_id;
	$result = db_query($sql,"check failed");
	$myrow_chk = db_fetch($result);		
	if ($myrow_chk['version'] == $version && $myrow_chk['request_status']=="Confirmed") 
	 {
		$sql = "UPDATE ".TB_PREF."debtors_master set 		
			credit_status=".$myrow_chk["credit_status"].",
			payment_terms=".$myrow_chk["payment_terms"].",
			discount=".$myrow_chk["discount"].",
			pymt_discount=".$myrow_chk["pymt_discount"].",
			credit_limit=".$myrow_chk["credit_limit"]."
			WHERE  ".TB_PREF."debtors_master.debtor_no= '".($myrow_chk["debtor_no"])."'";
	     
		 $result = db_query($sql,"Unable to update customer master table: contact your system support.");
		 Display_notification("Customer master for ".($myrow_chk["name"])." updated successfully with the contract terms.");
	}
   else  
   {
   	$sql = "UPDATE ".TB_PREF."debtors_terms_requests set request_status = 'Planned', 
				approved_by='".$_SESSION['wa_current_user']->loginname."',
				approved_date=sysdate
				WHERE request_id = ".$request_id;

  	db_query($sql, "Unable to change state");
   
   	Display_error("Contract terms request for ".($myrow_chk["name"])." has changed, approval aborted, review and retry.");
   }
   commit_transaction();
   }
   
   else display_error ("Only terms in Planned state can be confirmed/approved");
}


function check_overdue($row)
{
	global $trans_type;
         return false ;
		//return (date1_greater_date2(Today(), sql2date($row['created_date'])));
}

function edit_link($row)
{
	global $trans_type;
	$modify = ($trans_type == ST_SALESORDER ? "ModifyOrderNumber" : "ModifyQuotationNumber");
 return pager_link( _("Confirm Customers terms"),
	"/simplex/sales/manage/approve_customers_terms.php?CustNumber=" .$row['request_id']
		."&ver=".$row['version']."&state=".$row['request_status'], ICON_EDIT);
}

//---------------------------------------------------------------------------------------------
// Update db record if respective checkbox value has changed.
//
function change_tpl_flag($id)
{
	global	$Ajax;

  	$sql = "UPDATE ".TB_PREF."sales_orders SET type = !type WHERE order_no=$id";

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
/*
if (get_post('_OrderNumber_changed')) // enable/disable selection controls
{
	$disable = get_post('OrderNumber') !== '';

//		if ($_POST['order_view_mode']!='DeliveryTemplates'
//		&& $_POST['order_view_mode']!='InvoiceTemplates') 
		{
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
*/
	$Ajax->activate('orders_tbl');
//}


start_form();

start_table("class='tablestyle_noborder'");
start_row();
ref_cells(_("Customer #:"), 'CustomerNumber', '',null, '', true);
//if ($_POST['order_view_mode'] != 'DeliveryTemplates' && $_POST['order_view_mode'] != 'InvoiceTemplates')
//{
  	date_cells(_("from:"), 'OrdersAfterDate', '', null, -30);
  	date_cells(_("to:"), 'OrdersToDate', '', null, 1);
//}
//locations_list_cells(_("Location:"), 'StockLocation', null, true);

//stock_items_list_cells(_("Item:"), 'SelectStockFromList', null, true);

//if ($trans_type == ST_SALESQUOTE)
//	check_cells(_("Show All:"), 'show_all');
submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');

//hidden('order_view_mode', $_POST['order_view_mode']);
//hidden('type', $trans_type);

end_row();

if (isset($_GET['CustNumber'])&&isset($_GET['state'])&&isset($_GET['ver'])) 
	{
	confirm_terms($_GET['CustNumber'],$_GET['state'],$_GET['ver'] );
	}
	
end_table(1);
//---------------------------------------------------------------------------------------------

$sql = "SELECT
        dtr.request_id,
		dtr.debtor_no ,
		dtr.name ,
		dtr.address,
		cds.reason_description  credit_status,
        pyt.terms ,
		dtr.discount*100 discount,
		dtr.pymt_discount*100 pymt_discount,
		dtr.credit_limit ,
		dtr.curr_code,
		dtr.requested_by,
		dtr.created_date,        
		dtr.request_status,
		dtr.version  
		FROM ".TB_PREF."debtors_terms_requests  dtr, ".TB_PREF."credit_status cds, ".TB_PREF."payment_terms pyt
		WHERE dtr.request_status = 'Planned' 
		AND dtr.credit_status=cds.id
		AND dtr.payment_terms = pyt.terms_indicator";


		$date_after = date2sql($_POST['OrdersAfterDate']);
		$date_before = date2sql($_POST['OrdersToDate']);

		$sql .=  " AND created_date >= '$date_after'"
				." AND created_date <= to_date('$date_before', 'yyyy-mm-dd hh24:mi:ss') ";
if (isset($_POST['CustomerNumber']) && $_POST['CustomerNumber'] != "")
{
	// search orders with number like
	$number_like = "%".$_POST['CustomerNumber']."%";
	$sql .= " AND debtor_no LIKE ".db_escape($number_like);
	//display_notification ($sql);
}				
//		date_format(created_date, '%d/%m/%Y') as created_date,
//if ($trans_type == ST_SALESORDER)
//		_("Request #") ,

	$cols = array(
		_("Request #") ,
		_("Customer #") ,
		_("Customer Name"),
		_("Address"),
		_("Credit Status"),
		_("Payment Terms"),
		_("Discount %")   => array('type'=>'amount'),
		_("Payment Discount %")   => array('type'=>'amount'),
		_("Credit Limit")  => array('type'=>'amount'),
		_("Currency") => array('align'=>'center'),
		_("Requested By"),
		_("Request Date")=>'date',
		_("Request Status"),
		_("Confirm") => array('insert'=>true, 'fun'=>'edit_link')
	);

$table =& new_db_pager('orders_tbl', $sql, $cols);
$table->set_marker('check_overdue', _("Marked items are overdue."));

$table->width = "80%";

display_db_pager($table);
submit_center('Update', _("Update"), true, '', null);

end_form();
end_page();
?>

