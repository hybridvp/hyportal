<?php
/**********************************************************************
    Copyright (C) 

***********************************************************************/
$page_security = 'SA_SALESALLOC'; //'SA_ITEMSTRANSVIEW';
$path_to_root = "../..";
include($path_to_root . "/includes/db_pager.inc");
include($path_to_root . "/includes/session.inc");

include($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");
include($path_to_root . "/simplex/includes/nusoap/lib/nusoap.php");

//include($path_to_root . "/includes/db/inventory_db.inc");
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Activate E-PIN File"), false, false, "", $js);

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
	return get_trans_view_str(ST_PURCHORDER, $trans["batch_no"]);
}

//---------------------------------------------------------------------------------------------
function prt_link($row)
{
	return print_document_link($row['batch_no'], _("Print"), true, 18, ICON_PRINT);
}


//---------------------------------------------------------------------------------------------
function activate_link($row) 
{
  //submit_center_first('ApproveOrder', $row, _('Approve Order'), 'default');
	//  	     submit_js_confirm('ApproveOrder', _('You are about to confirm this sales order.\nDo you want to continue?'));
	//submit_row('submit', _("Get"), true, '', '', true);
	 //hidden('hiddenponum'. $row['ponumber'], $row['ponumber'], false);
	 //hidden('hiddenid'. $row['id'], $row['id'], false);
	 $_SESSION['file_number'] = $row['batch_no'];
	submit_cells('Activate', _("Activate"), "colspan=2",  _('Activate this file'), true);
	submit_js_confirm('Activate', _('You are about to Activate this file.\nDo you want to continue?'));
}
function cancel_link($row) 
{
  //submit_center_first('ApproveOrder', $row, _('Approve Order'), 'default');
	//  	     submit_js_confirm('ApproveOrder', _('You are about to confirm this sales order.\nDo you want to continue?'));
	//submit_row('submit', _("Get"), true, '', '', true);
	 //hidden('hiddenponum'. $row['ponumber'], $row['ponumber'], false);
	 //hidden('hiddenid'. $row['id'], $row['id'], false);
	 $_SESSION['file_number'] = $row['batch_no'];
	submit_cells('Cancel', _("Cancel"), "colspan=2",  _('Cancel this file'), true);
	submit_js_confirm('Cancel', _('You are about to Cancel this import.\nDo you want to continue?'));
}
function GetTmpPin($file_number)
{
$sql = "SELECT 
	*	
	FROM "
		.TB_PREF."pin_details pin  WHERE flg_mnt_status = 'U'
	AND pin.batch_no=".db_escape($file_number) ; 
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
function ActivatePIN($pin,$batch_no,$svrname,$proxyhost="",$proxyport="",$proxyusr="",$proxypassword="")
{
		$wsdlfile = "http://emts.ng.com/pinactivationservice/activations.asmx";
		$msg="<?xml version=\"1.0\" encoding=\"utf-8\"?>
			 <soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">
			   <soap:Body>
				 <getStatus xmlns=\"http://webpay.interswitchng.com/webpay/\">
				   <PIN>".$pin."</PIN>
				   <BATCHNO>".$batch_no."</BATCHNO>
				   <SERVERNAME>".$svrname."</SERVERNAME>
				 </getStatus>
			   </soap:Body>
			 </soap:Envelope>";

		$s = new soapclientw($wsdlfile);
		if (empty($proxyhost))
		{
		}else
		{
				$s->setHTTPProxy($proxyhost,$proxyport,$proxyusr,$proxypassword);
		}
		$result = $s->send($msg,'http://emts.ng.com/pinactivationservice/getStatus',60);
		return $s->responseData;
}

function activate_line($file_number)
{
	//$result = GetTmpPin($file_number);
	//begin_transaction();
	$tmpdegrees = TestWebSvc(56);
	/*	
	$sql = "UPDATE ".TB_PREF."pin_details SET active = 1,last_modified_date = sysdate"
	       . " WHERE batch_no= " 
		   . $file_number ." and activated = 0";
			db_query($sql, "The file could not be activated.");
			display_notification ("File  activated");
			$ip = preg_quote($_SERVER['REMOTE_ADDR']);
			
	add_nonfin_audit_trail(0,0,0,0,'DATAFILE IMPORT ACTIVATION','A',$ip,'DATAFILE BATCH NO:' . $file_number . " ACTIVATED ");
	*/
	//Display_error ("Document fully authorised already Ponumber=" . $ponumber . "id=" . $trans_id); 
	display_error("Result = " . $tmpdegrees);
	unset ($_SESSION['file_number']);
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
 if (isset($_POST['Activate']))
  {
  		activate_line($_SESSION['file_number'] ) ;
  }

  
//figure out the sql required from the inputs available //stock.order_no,
//substr(suppliers.supp_name,1,2)
$sql = "SELECT 
	 batch_no, denomination,  
	 decode(status,'N','NEW','S', 'SOLD','D','DELIVERED' ) status,
	 decode(active,0,'INACTIVE',1,'ACTIVE','INACTIVE') activated,
	 trunc(tmp.load_date),sales_order_no, count(*) ,created_by 
	 FROM "
		.TB_PREF."pin_details tmp
	 WHERE tmp.flg_mnt_status= 'A'";

if (isset($order_number) && $order_number != "")
{
	$sql .= " AND tmp.batch_no LIKE ".db_escape('%'. $order_number . '%');
}
else
{
	$data_after = date2sql($_POST['TrsfAfterDate']);
	$data_before = date2sql($_POST['TrsfToDate']);

	$sql .= "  AND trunc(tmp.load_date) >=to_date( '$data_after', 'yyyy-mm-dd') ";
	$sql .= "  AND trunc(tmp.load_date) <= to_date( '$data_before', 'yyyy-mm-dd') ";

	if (isset($selected_unit))
	{
		$sql .= " AND tmp.facevalue=" .db_escape(selected_unit);
	}
} //end not order number selected

$sql .= "group by  batch_no, denomination, status,active, trunc(tmp.load_date),sales_order_no, created_by";
echo $sql;

$result = db_query($sql,"No data was returned");
//echo $sql;
/*show a table of the orders returned by the sql */
$cols = array(
		_("File #") => array('fun'=>'trans_view', 'ord'=>''), 
		_("Denomination"),
		_("Status"), 
		_("Activated"),
		_("Load date"), 
		_("Sales Order #"),
		_("Quantity"),
		_("Created By"),
	
		array('insert'=>true, 'fun'=>'activate_link'),
		//array('insert'=>true, 'fun'=>'cancel_link'),
		array('insert'=>true, 'fun'=>'prt_link')

);

if (get_post('StockLocation') != $all_items) {
	$cols[_("Location")] = 'skip';
}

$table =& new_db_pager('orders_tbl', $sql, $cols);

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
?>