<?php
/**********************************************************************
    Copyright (C) SImplex
***********************************************************************/
$page_security = 'SA_SETUPCOMPANY'; //'SA_ITEMSTRANSVIEW';
$path_to_root = "..";
include($path_to_root . "/includes/db_pager.inc");
include($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Customer Interface"), false, false, "", $js);


if (isset($_GET['order_number']))
{
	$_POST['order_number'] = $_GET['order_number'];
}
//-----------------------------------------------------------------------------------
if (get_post('SearchOrders')) 
{
	$Ajax->activate('orders_tbl');
} elseif (get_post('_order_number_changed')) 
{
	$disable = get_post('order_number') !== '';

	$Ajax->addDisable(true, 'OrdersAfterDate', $disable);
	$Ajax->addDisable(true, 'OrdersToDate', $disable);
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
ref_cells(_("Customer Code:"), 'order_number', '',null, '', true);
date_cells(_("from:"), 'OrdersAfterDate', '', null, -30);
date_cells(_("to:"), 'OrdersToDate');

submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');
end_row();
end_table();
//---------------------------------------------------------------------------------------------
if (isset($_POST['order_number']) && ($_POST['order_number'] != ""))
{
	$file_number = $_POST['order_number'];
}


//------------------------------------------------------------------------------------------------

$myrow = get_company_prefs();

$sql = "SELECT customer_code,name,email, trunc(timestamp_of_insertion), sync_yn,sync_date,txt_error_desc
		FROM "
		.TB_PREF."intf_customer cust WHERE 1=1";
		
if (isset($order_number) && $order_number != "")
{
	$sql .= " AND cust.customer_code LIKE ".db_escape('%'. $order_number. '%');
}
else
{
	$data_after = date2sql($_POST['OrdersAfterDate']);
	$data_before = date2sql($_POST['OrdersToDate']);

	//$sql .= "  AND act.dat_logged >=to_date( '$data_after', 'yyyy-mm-dd hh24:mi:ss') ";
	$sql .= "  AND trunc(cust.timestamp_of_insertion) >=to_date( '$data_after', 'yyyy-mm-dd') ";
	$sql .= "  AND trunc(cust.timestamp_of_insertion) <= to_date('$data_before','yyyy-mm-dd')";

} 
		$sql .= " ORDER BY cust.timestamp_of_insertion desc";

		$result = db_query($sql,"No date was returned");

		if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'CUSTOMER INTERFACE INQUIRY','A',$ip,'INQUIRY DONE ON THIS RECORD');
			}
$cols = array(

		_("Customer #") => array('ord'=>''), 
		_("Customer Name") , 
		_("Email") , 
		_("Entry date") => array('ord'=>'','type' => 'date'), 
		_("Syncronized") , 
		_("Date Syncronized") , 
		_("Status Message") 
);


$table =& new_db_pager('orders_tbl', $sql, $cols);

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
?>