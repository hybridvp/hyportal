<?php
/**********************************************************************
    Copyright (C) SImplex
***********************************************************************/
$page_security = 'SA_SALESCREDIT'; //'SA_ITEMSTRANSVIEW';
$path_to_root = "../../..";
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
page(_($help_context = "Activation Job Status"), false, false, "", $js);


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
	$Ajax->addDisable(true, 'action_mode', $disable);
	$Ajax->addDisable(true, 'username', $disable);
	
	//$Ajax->addDisable(true, 'StockLocation', $disable);
	//$Ajax->addDisable(true, '_SelectStockFromList_edit', $disable);
	///$Ajax->addDisable(true, 'SelectStockFromList', $disable);
	
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
ref_cells(_("JOB ID:"), 'order_number', '',null, '', true);
activation_mode_list_cells(_("Activation Mode"), 'action_mode',null,true);
item_type_list_cells(_("PIN Type"), 'item_type',null,true);
users_list_cells(_("User ID"), 'username',null,true);
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

if (isset($_POST['action_mode']) && ($_POST['action_mode'] != ""))
{
	$action_mode = $_POST['action_mode'];
}

if (isset($_POST['item_type']) && ($_POST['item_type'] != ""))
{
	$item_type = $_POST['item_type'];
}

if (isset($_POST['username']) &&	($_POST['username'] != "") &&
	($_POST['username'] != ALL_TEXT))
{
 	$username = $_POST['username'];
}
else
{
	unset($username);
}
//------------------------------------------------------------------------------------------------
/* function format($row)
{
	return strtotime($row['uvc_submit_time']);
} */
$myrow = get_company_prefs();
//$retain_days = $myrow["pin_file_retention_days"];
//figure out the sql required from the inputs available //stock.order_no,
$sql = "SELECT id ,batch_no,start_serial,end_serial,dat_logged,cod_user_id,
decode(job_status,'L','IN PROGRESS','C','COMPLETED', 'X','CANCELLED') status,
decode(action_mode,'104','ACTIVATION',105,'DEACTIVATION') status1,  to_date(uvc_submit_time,'YYYYMMDDHH24MISS') uvc_submit_time, to_date(uvc_finish_time,'YYYYMMDDHH24MISS') uvc_finish_time, err.error_desc
		FROM "
		.TB_PREF."activation_jobs act, "
		.TB_PREF."epin_error_msg err
		 WHERE 1=1 
		 AND act.error_code=err.error_code(+)";
		
if (isset($order_number) && $order_number != "")
{
	$sql .= " AND act.jobid LIKE ".db_escape('%'. $order_number. '%');
}
else
{
	$data_after = date2sql($_POST['OrdersAfterDate']);
	$data_before = date2sql($_POST['OrdersToDate']);

	//$sql .= "  AND act.dat_logged >=to_date( '$data_after', 'yyyy-mm-dd hh24:mi:ss') ";
	$sql .= "  AND trunc(act.dat_logged) >=to_date( '$data_after', 'yyyy-mm-dd') ";
	$sql .= "  AND trunc(act.dat_logged) <= to_date('$data_before','yyyy-mm-dd')";
	
	if (isset($username) && $username != "")
	{
		$sql .= " AND act.cod_user_id LIKE ".db_escape('%'. $username . '%');
	}
	  if ( isset($_POST['action_mode'] ))
	  {
   		$sql .= " AND act.action_mode = ".db_escape($_POST['action_mode']);
	  }
	if ( isset($_POST['item_type'] ))
	  {
   		$sql .= " AND act.item_type = ".db_escape($_POST['item_type']);
	  }
	
} 


		
	$sql .= " ORDER BY act.dat_logged desc";
//$sql .= " GROUP BY porder.order_no";

$result = db_query($sql,"No items were returned");

		if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'ACTIVATION STATUS INQUIRY','A',$ip,'INQUIRY DONE ON THIS RECORD');
			}
//echo $sql;
//echo 'date =' . date('YmdHis');
/*show a table of the orders returned by the sql */
//atch_no,start_serial,end_serial,dat_logged,cod_user_id,job_status,action_mode
$cols = array(

		_("Job Id / Order #") => array('ord'=>''), 
		_("Batch NO") , 
		_("Start Sequence") , 
		_("End Sequence") , 
		_("Created date") => array('ord'=>'','type' => 'date'), 
		_("Created by") => array('ord'=>''),
		_("Status") ,
		_("Mode") ,
		_("Job Submission Time"),
		_("UVC Finish Time"), 
		_("Status Message") 
);

/* 		array('insert'=>true, 'fun'=>'download_link'),
		array('insert'=>true, 'fun'=>'email_link')
 */ 
 //array_append($cols,array(
	//		_("Send") => array('insert'=>true, 'fun'=>'tmpl_checkbox'),
	//				array('insert'=>true, 'fun'=>'download_link'),
	//				));
//=> array('fun'=>'trans_view')

$table =& new_db_pager('orders_tbl', $sql, $cols);
//$table->set_marker('check_overdue', _("Marked orders have overdue items."));

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
?>