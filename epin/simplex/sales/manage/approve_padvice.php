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
	
$_SESSION['page_title'] = _($help_context = "Search Outstanding Customer Payment Advices for Approval");

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

function can_process($DateBanked, $ref, $amount)
{
global $Refs;

	if (!is_date_in_fiscalyear($DateBanked)) {
		display_error(_("The entered date is not in fiscal year, payment cannot be confirmed!"));
		return false;
	}

	if (!$Refs->is_valid($ref)) {
		display_error(_("Generated reference not valid, retry later."));
		return false;
	}

	if (!is_new_reference($ref, ST_CUSTPAYMENT)) {
		display_error(_("The generated reference is already in use. retry the confirmation for new reference number to be generated"));
		return false;
	}

	if ( $amount < 0) {
		display_error(_("Invalid amount found, contact your system support."));
		return false;
	}

return true;
}


function confirm_terms($request_id,$current_status, $version)
{
global $Refs;

   if ($current_status=='Planned')
   { 
   begin_transaction();
  	$sql = "UPDATE ".TB_PREF."pay_advice set request_status = 'ChangingState',"//'Confirmed', Temporarily changed to planned
			 ."	confirmed_by='".$_SESSION['wa_current_user']->loginname."',
				confirmed_date=sysdate
				WHERE order_no = ".$request_id." and request_status = 'Planned' and version=".$version;

  	db_query($sql, "Unable to change state");
 
 	$sql = "select id, debtor_no, bank_act, trans_date, branch_id, amount, order_no, note, request_status, version 
				from ".TB_PREF."pay_advice
				WHERE order_no = ".$request_id;
								
	$result = db_query($sql,"check failed");
	$myrow_chk = db_fetch($result);		
	if ($myrow_chk['version'] == $version && $myrow_chk['request_status']=='Planned') 
	{

//create payment here 
/////////////////////////////////////////////////////////////////////////////////////
///////////////////////Added to do posting for payments////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------------------------------------------------
			$ref = $Refs->get_next(12);
			
			if (can_process(sql2date($myrow_chk['trans_date']), $ref, user_numeric($myrow_chk['amount']))) 
			{
				
				$cust_currency = get_customer_currency($myrow_chk['debtor_no']);
				$bank_currency = get_bank_account_currency($myrow_chk['bank_act']);
				$comp_currency = get_company_currency();
			//	if ($comp_currency != $bank_currency && $bank_currency != $cust_currency)
					$rate = 0;
			///	else
			///		$rate = input_num('_ex_rate');
			
				new_doc_date($myrow_chk['trans_date']);
				$discount = 0 ;
				$charge = 0 ;
			  
				$payment_no = write_customer_payment(0, $myrow_chk['debtor_no'], $myrow_chk['branch_id'],
					$myrow_chk['bank_act'], sql2date($myrow_chk['trans_date']), $ref,
					$myrow_chk['amount'], input_num($discount), $myrow_chk['note'], $rate, $charge);
				
				 Display_notification("Customer paymnent confirmed successfully with payment number ".$payment_no.".");
			  }
			
//----------------------------------------------------------------------------------------------
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
   } //end if version testing 
   else  Display_error("Customer payment has been modificed, approval aborted, review and retry.");
//end else version testing 
//now check if payment has been created and set the status to ConfirmedPosted
//else change it back to planned.
if (isset($payment_no) && $payment_no >0 ) {
  	$sql = "UPDATE ".TB_PREF."pay_advice set request_status = 'ConfirmedPosted'," 
			 ."	confirmed_by='".$_SESSION['wa_current_user']->loginname."',
				confirmed_date=sysdate
				WHERE order_no = ".$request_id." and request_status = 'ChangingState'";

  	db_query($sql, "Unable to change state");
 }
 else //payment was not created
  {
    	$sql = "UPDATE ".TB_PREF."pay_advice set request_status = 'Planned'," 
			 ."	confirmed_by='".$_SESSION['wa_current_user']->loginname."',
				confirmed_date=sysdate
				WHERE order_no = '".$request_id."'";

  	db_query($sql, "Unable to change state");
  }
   commit_transaction();
} //end Planned State testing

   else display_error ("Only payments advice in Planned state can be confirmed/approved");
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
	"/simplex/sales/manage/approve_padvice.php?CustNumber=" .$row['request_id']
		."&ver=".$row['version']."&state=".$row['request_status'], ICON_EDIT);
/*
 return pager_link( _("Confirm Customers terms"),
	"/sales/customer_payments.php?CustNumber=" .$row['request_id']
		."&ver=".$row['version']."&state=".$row['request_status'], ICON_EDIT);
*/
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
	$Ajax->activate('orders_tbl');
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
submit_cells('SearchOrders', _("Search"),'',_('Select Payment'), 'default');

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
        p.order_no request_id,
		p.debtor_no debtor_no ,
		m.name name ,
		p.bank_act bank_act,
		p.ref  ref,
		p.amount amount,
		p.trans_date  ,
		p.note ,
		p.created_by requested_by,
		p.created_date,        
		p.request_status,
		p.version  
		FROM ".TB_PREF."pay_advice p, ".TB_PREF."debtors_master m 
		WHERE request_status = 'Planned'
		and p.debtor_no = m.debtor_no";
/*
id, type, order_no, bank_act, ref, trans_date, amount, dimension_id, dimension2_id, person_type_id, person_id, reconciled, created_by, created_date, note, confirmed_by, confirmed_date, request_status, version
*/

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
		_("Order #") ,
		_("Customer #") ,
		_("Customer Name"),
		_("Bank Act"),
		_("Ref/Slip #"),
		_("Amount")   => array('type'=>'amount'),
		_("Trans Date")=>'date',
		_("Note"),
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
