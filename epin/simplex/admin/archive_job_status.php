<?php
/**********************************************************************
    Copyright (C) SImplex
***********************************************************************/
$page_security = 'SA_ITEMSTRANSVIEW';
$path_to_root = "../..";
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
page(_($help_context = "Search Archive Jobs"), false, false, "", $js);

if (isset($_GET['file_number']))
{
	$_POST['file_number'] = $_GET['file_number'];
}
//-----------------------------------------------------------------------------------
// Ajax updates
//
if (get_post('SearchOrders')) 
{
	$Ajax->activate('orders_tbl');
} 
elseif (get_post('_file_number_changed')) 
{
	$disable = get_post('file_number') !== '';

	$Ajax->addDisable(true, 'OrdersAfterDate', $disable);
	$Ajax->addDisable(true, 'OrdersToDate', $disable);
	if ($disable) {
		$Ajax->addFocus(true, 'file_number');
	} else
		$Ajax->addFocus(true, 'OrdersAfterDate');

	$Ajax->activate('orders_tbl');
}


//---------------------------------------------------------------------------------------------

start_form();

start_table("class='tablestyle_noborder'");
start_row();
ref_cells(_("Batch #:"), 'file_number', '',null, '', true);
ref_cells(_("Sequence #:"), 'voucher_number', '',null, '', true);
date_cells(_("from:"), 'OrdersAfterDate', '', null, -30);
date_cells(_("to:"), 'OrdersToDate');

submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');
end_row();
end_table();
//---------------------------------------------------------------------------------------------
function trans_view($trans)
{
	return get_trans_view_str(ST_PURCHORDER, $trans["batch_no"]);
}

/*function prt_link($row)
{
	return print_document_link($row['trans_id'], _("Print"), true, 18, ICON_PRINT);
}*/

function receive_link($row) 
{
  return pager_link( _("Split"),
	"#" . $row["batch_no"],  ICON_RECEIVE);
}
function view_pins_link($row) 
{
	$path_to_root = "../../..";
  return pager_link( _("View"),	$path_to_root."/simplex/sales/inquiry/epin_inquiry.php?file_number=" . $row["batch_no"],  ICON_DOC);
}

//---------------------------------------------------------------------------------------------
if (isset($_POST['file_number']) && ($_POST['file_number'] != ""))
{
	$file_number = $_POST['file_number'];
}
if (isset($_POST['voucher_number']) && ($_POST['voucher_number'] != ""))
{
	$voucher_no = $_POST['voucher_number'];
}


//figure out the sql required from the inputs available //stock.order_no,
/* $sql = "SELECT batch_no, sequence_number,  denomination,load_date,  created_by,sales_order_no,customer_no,
			   decode(status,'N','NEW','S', 'SOLD','D','DELIVERED','A', 'ALLOCATED'  ),  decode(flg_mnt_status,'A', 'Authorised', 'U', 'Unauthorised')
		FROM "
		.TB_PREF."PIN_DETAILS epin ";  */
		
$sql = " SELECT id, decode(epin.job_status,'L','LOGGED','C','COMPLETED') status , 
job_start_time, job_end_time,cod_user_id,trunc(dat_logged), c.error_desc FROM " 
 .TB_PREF."archive_jobs epin, "
 .TB_PREF."epin_error_msg c 
WHERE 1=1
AND epin.error_code = c.error_code (+)";
//echo $sql;
if (isset($_GET['stat'])){
	$sql .= " AND status=". db_escape(strtoupper ($_GET['stat']));
}

else if (isset($file_number) && $file_number != "")
{
	$sql .= " AND epin.id LIKE ".db_escape('%'. $file_number/1 . '%');
}

else
{
	$data_after = date2sql($_POST['OrdersAfterDate']);
	$data_before = date2sql($_POST['OrdersToDate']);

	$sql .= "  AND epin.dat_logged >=to_date( '$data_after', 'yyyy-mm-dd hh24:mi:ss') ";
	$sql .= "  AND trunc(epin.dat_logged) <= '$data_before'";

} //end not order number selected
$sql .= " ORDER BY epin.id desc";
//$sql .= " GROUP BY porder.order_no";

$result = db_query($sql,"No items were returned");

if($nonfin_audit_trail)
			{
			$ip = preg_quote($_SERVER['REMOTE_ADDR']);
			add_nonfin_audit_trail(0,0,0,0,'VOUCHER ARCHIVE INQUIRY','I',$ip,'INQUIRY DONE ON THIS RECORD - ');
			}
//echo $sql;
/*show a table of the orders returned by the sql */
$cols = array(
		_("Job #") => array('ord'=>''), 
		_("Status") , 

		_("Start time") ,  //,'ord'=>''
		_("End time") ,  //,'ord'=>''
		_("Logged by") => array('ord'=>''),
		_("Log date") => array('type'=>'date'),
		
		_("") 
);


$table =& new_db_pager('orders_tbl', $sql, $cols);
//$table->set_marker('check_overdue', _("Marked orders have overdue items."));

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
?>