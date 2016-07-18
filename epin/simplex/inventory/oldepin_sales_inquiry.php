<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_ITEMSTRANSVIEW';
$path_to_root = "../..";
include($path_to_root . "/includes/db_pager.inc");
include($path_to_root . "/includes/session.inc");

include($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

//include($path_to_root . "/includes/db/inventory_db.inc");
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Release EPIN Sales"), false, false, "", $js);

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
	}
	else
		$Ajax->addFocus(true, 'TrsfAfterDate');

	$Ajax->activate('orders_tbl');
}


//---------------------------------------------------------------------------------------------

start_form();

start_table("class='tablestyle_noborder'");
start_row();
ref_cells(_("File #:"), 'order_number', '',null, '', true);

date_cells(_("from:"), 'TrsfAfterDate', '', null, -30);
date_cells(_("to:"), 'TrsfToDate');

denom_list_cells ( _("Unit"),'units', null, true);

submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');
end_row();
end_table();
//---------------------------------------------------------------------------------------------
function trans_view($trans)
{
	return get_trans_view_str(ST_PURCHORDER, $trans["sales_order_no"]);
}

//---------------------------------------------------------------------------------------------
function prt_link($row)
{
	return print_document_link($row['sales_order_no'], _("Print"), true, 18, ICON_PRINT);
}


//---------------------------------------------------------------------------------------------
function release_link($row) 
{
  //submit_center_first('ApproveOrder', $row, _('Approve Order'), 'default');
	//  	     submit_js_confirm('ApproveOrder', _('You are about to confirm this sales order.\nDo you want to continue?'));
	//submit_row('submit', _("Get"), true, '', '', true);
	 //hidden('hiddenponum'. $row['ponumber'], $row['ponumber'], false);
	 //hidden('hiddenid'. $row['id'], $row['id'], false);
	 $_SESSION['sales_order_no'] = $row['sales_order_no'];
	submit_cells('Release', _("Release"), "colspan=2",  _('Void transaction'), true);
	submit_js_confirm('Void Transaction', _('You are about to void this sale.\nDo you want to continue?'));
}
function systype_name($dummy, $type)
{
	global $systypes_array;

	return $systypes_array[$type];
}
function GetTmpPin($file_number)
{
$sql = "SELECT 
	*	
	FROM "
		.TB_PREF."pin_details pin  WHERE flg_mnt_status = 'U'
	AND pin.sales_order_no=".db_escape($file_number) ; 
	$sql_a = db_query($sql);
	 $result = db_fetch($sql_a);
	 return $result;
}
function GetUserId($user_id)
{
$sql = "SELECT id from users WHERE user_id= ". db_escape($user_id);
	$sql_a = db_query($sql);
	 $result = db_fetch($sql_a);
	 return $result['id'];
}
function release_line($order_number)
{
	//$result = GetTmpPin($file_number);
	//begin_transaction();
			
	$sql = "UPDATE ".TB_PREF."pin_details 
	SET authorised_by = " .db_escape($_SESSION["wa_current_user"]->loginname) . ",
	status = 'N', 
	last_modified_date = SYSDATE,
	sales_order_no= 0 
	WHERE sales_order_no= " . $order_number ." 
	AND flg_mnt_status = 'A'
	AND STATUS='S'";
	db_query($sql, "The order could not be voided.");
	display_notification ("Document approved");
	//Display_error ("Document fully authorised already Ponumber=" . $ponumber . "id=" . $trans_id); 
		$ip = preg_quote($_SERVER['REMOTE_ADDR']);
	add_nonfin_audit_trail(0,0,0,0,'EPIN SALES RELEASE','U',$ip,'EPIN SALES ORDER #:' . $order_number . " VOIDED ");
	unset ($_SESSION['sales_order_no']);
}

if (isset($_POST['order_number']) && ($_POST['order_number'] != ""))
{
	$order_number = $_POST['order_number'];
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
 if (isset($_POST['Release']))
  {
  		if(isset($_SESSION['sales_order_no']))
		{
  			release_line($_SESSION['sales_order_no'] ) ;
		}
  }	

  
//figure out the sql required from the inputs available //stock.order_no,
//substr(suppliers.supp_name,1,2)
//trans.type,
//	 sales_order_no, denomination,  decode(status,'N','NEW','S', 'SOLD','D','DELIVERED' ) status, debtor.name, trunc(tmp.sold_date) sold_date, 
$sql = "SELECT 
	 sales_order_no, denomination,   status, debtor.name, trunc(tmp.sold_date) sold_date, count(*) ,created_by 
	FROM "
		.TB_PREF."pin_details tmp ," 
		.TB_PREF."debtors_master debtor ,"
		.TB_PREF."debtor_trans  trans ,"
		.TB_PREF."pin_mailer_jobs_detail detail
		
	WHERE 1=1
	AND debtor.debtor_no = tmp.customer_no 
	AND debtor.debtor_no = trans.debtor_no 
	AND trans.order_ = tmp.sales_order_no
	AND debtor.debtor_no = detail.customer_no
	AND trans.order_ = detail.order_no
	AND tmp.flg_mnt_status= 'A' AND detail.status = 'P'";

if (isset($order_number) && $order_number != "")
{
	$sql .= " AND tmp.batch_no LIKE ".db_escape('%'. $order_number . '%');
}
else
{
	$data_after = date2sql($_POST['TrsfAfterDate']);
	$data_before = date2sql($_POST['TrsfToDate']);

	$sql .= "  AND trunc(tmp.sold_date) >=to_date( '$data_after', 'yyyy-mm-dd') ";
	$sql .= "  AND trunc(tmp.sold_date) <= to_date( '$data_before', 'yyyy-mm-dd') ";

	if (isset($selected_unit))
	{
		$sql .= " AND tmp.denomination=" .db_escape(selected_unit);
	}
} //end not order number selected

$sql .= "group by trans.type, sales_order_no, denomination, status, debtor.name, trunc(tmp.sold_date), created_by";
//echo $sql;

$result = db_query($sql,"No data was returned");
//echo $sql;
/*show a table of the orders returned by the sql */
$cols = array(

		_("Sales Order #") => array('fun'=>'trans_view', 'ord'=>''), 
		_("Denomination"),
		_("Status"), 
		_("Customer #"),
		_("Delivery date"), 

		_("Quantity"),
		_("Created By"),
	
		array('insert'=>true, 'fun'=>'release_link'),
		//array('insert'=>true, 'fun'=>'cancel_link'),
		array('insert'=>true, 'fun'=>'prt_link')

);
//		_("Type") => array('fun'=>'systype_name', 'ord'=>''),
if (get_post('StockLocation') != $all_items) {
	$cols[_("Location")] = 'skip';
}

$table =& new_db_pager('orders_tbl', $sql, $cols);

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
?>