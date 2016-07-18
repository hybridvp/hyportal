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
page(_($help_context = "Search OPS Items"), false, false, "", $js);

if (isset($_GET['id']))
{
	$_POST['id'] = $_GET['id'];
}
//-----------------------------------------------------------------------------------
// Ajax updates
//
if (get_post('SearchOrders')) 
{
	$Ajax->activate('orders_tbl');
} elseif (get_post('_username_changed')) 
{
	$disable = get_post('username') !== '';

	$Ajax->addDisable(true, 'OrdersAfterDate', $disable);
	$Ajax->addDisable(true, 'OrdersToDate', $disable);
	if ($disable) {
		$Ajax->addFocus(true, 'username');
	} else
		$Ajax->addFocus(true, 'OrdersAfterDate');

	$Ajax->activate('orders_tbl');
}


//---------------------------------------------------------------------------------------------

start_form();

start_table("class='tablestyle_noborder'");
start_row();
ref_cells(_("#:"), 'username', '',null, '', true);

date_cells(_("from:"), 'OrdersAfterDate', '', null, -30);
date_cells(_("to:"), 'OrdersToDate');

submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');
end_row();
end_table();
//---------------------------------------------------------------------------------------------
function trans_view($trans)
{
	return get_trans_view_str(ST_PURCHORDER, $trans["id"]);
}

/*function prt_link($row)
{
	return print_document_link($row['trans_id'], _("Print"), true, 18, ICON_PRINT);
}*/

function receive_link($row) 
{
  return pager_link( _("Split"),
	"/simplex/sim/split_range.php?TransID=" . $row["id"],  ICON_RECEIVE);
}

//---------------------------------------------------------------------------------------------
if (isset($_POST['id']) && ($_POST['id'] != ""))
{
	$id = $_POST['id'];
}
if (isset($_POST['username']) && ($_POST['username'] != ""))
{
	$username = $_POST['username'];
}


//figure out the sql required from the inputs available //stock.order_no,
$sql = "SELECT 
	id, start_no,end_no,created_date,created_by
	FROM "
		.TB_PREF."ops_numbers ops ";
	//AND stock.visible=1

if (isset($username) && $username != "")
{
	$sql .= "WHERE stock.created_by LIKE ".db_escape('%'. $username . '%');
}
else if (isset($id) && $id != "")
{
	$sql .= "WHERE stock.id LIKE ".db_escape('%'. $id . '%');
}
else
{
	$data_after = date2sql($_POST['OrdersAfterDate']);
	$data_before = date2sql($_POST['OrdersToDate']);

	$sql .= "  WHERE ops.created_date >=to_date( '$data_after', 'yyyy-mm-dd hh24:mi:ss') ";
	$sql .= "  AND ops.created_date <= '$data_before'";

} //end not order number selected
$sql .= " ORDER BY ops.id desc";
//$sql .= " GROUP BY porder.order_no";

$result = db_query($sql,"No items were returned");
//echo $sql;
/*show a table of the orders returned by the sql */
$cols = array(
		_("#"), 
		_("Start #") => array('ord'=>''), 
		_("End #") , 
		_("Date") => array('ord'=>''), 
		_("Created by") => array('ord'=>''),
		array('insert'=>true, 'fun'=>'receive_link')
);


$table =& new_db_pager('orders_tbl', $sql, $cols);
//$table->set_marker('check_overdue', _("Marked orders have overdue items."));

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
?>
