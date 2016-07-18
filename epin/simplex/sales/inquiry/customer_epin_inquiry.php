<?php
/**********************************************************************
    Copyright (C) SImplex
***********************************************************************/
$page_security = 'SA_SALESCREDIT'; //'SA_ITEMSTRANSVIEW';
$path_to_root = "../../..";
global $nonfin_audit_trail;
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
page(_($help_context = "Search EPIN files"), false, false, "", $js);

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
ref_cells(_("Sales order #:"), 'order_number', '',null, '', true);

date_cells(_("from:"), 'OrdersAfterDate', '', null, -30);
date_cells(_("to:"), 'OrdersToDate');

submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');
end_row();
end_table();
//---------------------------------------------------------------------------------------------
//function transs_view($row)
//{
//	return get_trans_view_str(ST_PURCHORDER, $row["filename"]);
//}

/*function prt_link($row)
{
	return print_document_link($row['trans_id'], _("Print"), true, 18, ICON_PRINT);
}*/
//------------------------------------------------------------------------------------------------
function tmpl_checkbox($row)
{
	global $trans_type;
	if ($trans_type == ST_SALESQUOTE)
		return '';
	$name = "chgtpl" .$row['order_no'];
	
	if ($row['queued'] =='Q' )
		$value = 1;
	else
		$value = 0;
//	$value = $row['status'] ? 1:0;

// save also in hidden field for testing during 'Update'

 return checkbox(null, $name, $value, true,
 	_('Queue this order for delivery to Dealer'))
	. hidden('last['.$row['order_no'].']', $value, false);
}
//---------------------------------------------------------------------------------------------
// Update db record if respective checkbox value has changed.
//
function change_tpl_flag($id)
{
	global	$Ajax;
	$pin_status = get_trans_stat ($id) ;
  	$sql = "UPDATE ".TB_PREF."pin_mailer_jobs SET status='Q' WHERE order_no =$id and '" . $pin_status . "'= 'A'";

  	db_query($sql, "Can't queue item for delivery");
	
			if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'EPIN DELIEVERY','A',$ip,'ORDER #' . $id. " QUEUED FOR DELIVERY ");
			}
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

//----------------------------------------------------------------------------------------
function get_trans_stat($order_no)
{
	 //$nxt_seq = 0;
	 $sql = "SELECT ap_get_pin_status(" . $order_no . ") FROM dual";
	 $result = db_query($sql, "Can not get pin status");
     $row = db_fetch_row_r($result);
	 return $row[0];
}
//----------------------------------------------------------------------------------------
function get_trans_no($order_no)
{
	 //$nxt_seq = 0;
	 $sql = "SELECT trans_no FROM ".TB_PREF. "debtor_trans where order_ = $order_no";
	 $result = db_query($sql, "Can not get transaction number");
     $row = db_fetch_row_r($result);
	 return $row[0];
}
function download_link($row) 
{
	//return viewer_link(_("Download"), );
  return pager_link( _("Download"),
	"/simplex/sales/view/download_view.php?filename=". $row["filename"] . "&customer_no=" .$row["customer_no"],  ICON_DOWN);
}
function activation_link($row) 
{
	//return viewer_link(_("Download"), );
  return pager_link( _("View Activation Status"),
	"/simplex/sales/view/view_activation_stat.php?order_number=". $row["order_no"] . "&line_no=" . $row["line_no"]. "&jobid=" . $row["jobid"],  ICON_VIEW);
}
function email_link($row) 
{
$trans_no = get_trans_no($row["order_no"]) ;
	//http://localhost:8080/etisalat/reporting/prn_redirect.php?PARAM_0=33&PARAM_1=33&PARAM_2=1&PARAM_3=0&REP_ID=110
	//return viewer_link(_("Download"), );
  return pager_link( _("Send to Dealer"),
	"/reporting/prn_redirect.php?PARAM_0=". $row["customer_no"] . "&PARAM_1=". $trans_no."&PARAM_2=1&PARAM_3=0&REP_ID=712&PARAM_5=".$row["order_no"] . "&PARAM_6=" .$row["filename"],  ICON_MAIL);
}
//---------------------------------------------------------------------------------------------
if (isset($_POST['order_number']) && ($_POST['order_number'] != ""))
{
	$file_number = $_POST['order_number'];
}
if (isset($_POST['username']) && ($_POST['username'] != ""))
{
	$username = $_POST['username'];
}

$myrow = get_company_prefs();
$retain_days = $myrow["pin_file_retention_days"];
//figure out the sql required from the inputs available //stock.order_no,
$sql = "SELECT epin.order_no, epin.line_no, detail.pin_qty, denomination,file_gen_date,  logged_by,epin.customer_no,decode(epin.status,'L','IN PROGRESS','S', 'STOPPED','C','ACTIVATING','M','DELIVERED', 'Q','QUEUED','A','ACTIVATED', 'F', 'FAILED') status, detail.file_name ||'.pgp.gz' as filename	,  epin.status as  queued	,jobid   
		FROM "
		.TB_PREF."pin_mailer_jobs epin ,"
		.TB_PREF."pin_mailer_jobs_detail detail 
		 WHERE detail.order_no = epin.order_no
		 AND detail.line_no = epin.line_no 
		 AND detail.status = 'P' ";
		 //AND epin.status ='A'	 
		
if (isset($username) && $username != "")
{
	$sql .= " AND epin.created_by LIKE ".db_escape('%'. $username . '%');
}
else if (isset($order_number) && $order_number != "")
{
	$sql .= " AND epin.order_no LIKE ".db_escape('%'. $order_number. '%');
}
else
{
	$data_after = date2sql($_POST['OrdersAfterDate']);
	$data_before = date2sql($_POST['OrdersToDate']);

	$sql .= "  AND epin.logged_date >=to_date( '$data_after', 'yyyy-mm-dd hh24:mi:ss') ";
	$sql .= "  AND trunc(epin.logged_date) <= to_date('$data_before','yyyy-mm-dd')";
	//$sql .= " AND file_gen_date >= SYSDATE - $retain_days";
} //end not order number selected
	$sql .= " ORDER BY epin.order_no,epin.line_no desc";
//$sql .= " GROUP BY porder.order_no";

$result = db_query($sql,"No items were returned");

		if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'CUSTOMER EPIN INQUIRY','A',$ip,'INQUIRY DONE ON THIS RECORD');
			}
//echo $sql;
/*show a table of the orders returned by the sql */
$cols = array(
		_("Order #") => array('ord'=>''), 
		_("Line Item") , 
		_("Quantity") , 
		_("Denomination") , 
		_("Created date") => array('ord'=>''),   //,'type' => 'date'
		_("Created by") => array('ord'=>''),
		_("Customer #") ,
		_("Status") ,
		_("Filename") 
);

/* 		array('insert'=>true, 'fun'=>'download_link'),
		array('insert'=>true, 'fun'=>'email_link')
 */ array_append($cols,array(
			_("Send") => array('insert'=>true, 'fun'=>'tmpl_checkbox'),
					array('insert'=>true, 'fun'=>'activation_link'),
					));
//=> array('fun'=>'trans_view')

$table =& new_db_pager('orders_tbl', $sql, $cols);
//$table->set_marker('check_overdue', _("Marked orders have overdue items."));

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
?>