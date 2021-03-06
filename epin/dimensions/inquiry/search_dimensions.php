<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_DIMTRANSVIEW';
$path_to_root="../..";

include($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

if (isset($_GET['outstanding_only']) && $_GET['outstanding_only'])
{
	$outstanding_only = 1;
	page(_($help_context = "Search Outstanding Dimensions"), false, false, "", $js);
}
else
{
	$outstanding_only = 0;
	page(_($help_context = "Search Dimensions"), false, false, "", $js);
}
//-----------------------------------------------------------------------------------
// Ajax updates
//
if (get_post('SearchOrders'))
{
	$Ajax->activate('dim_table');
} elseif (get_post('_OrderNumber_changed'))
{
	$disable = get_post('OrderNumber') !== '';

	$Ajax->addDisable(true, 'FromDate', $disable);
	$Ajax->addDisable(true, 'ToDate', $disable);
	$Ajax->addDisable(true, 'type_', $disable);
	$Ajax->addDisable(true, 'OverdueOnly', $disable);
	$Ajax->addDisable(true, 'OpenOnly', $disable);

	if ($disable) {
//		$Ajax->addFocus(true, 'OrderNumber');
		set_focus('OrderNumber');
	} else
		set_focus('type_');

	$Ajax->activate('dim_table');
}

//--------------------------------------------------------------------------------------

if (isset($_GET["stock_id"]))
	$_POST['SelectedStockItem'] = $_GET["stock_id"];

//--------------------------------------------------------------------------------------

start_form(false, false, $_SERVER['PHP_SELF'] ."?outstanding_only=$outstanding_only");

start_table("class='tablestyle_noborder'");
start_row();

ref_cells(_("Reference:"), 'OrderNumber', '',null, '', true);

number_list_cells(_("Type"), 'type_', null, 1, 2, _("All"));
date_cells(_("From:"), 'FromDate', '', null, 0, 0, -5);
date_cells(_("To:"), 'ToDate');

check_cells( _("Only Overdue:"), 'OverdueOnly', null);

if (!$outstanding_only)
{
   	check_cells( _("Only Open:"), 'OpenOnly', null);
}
else
	$_POST['OpenOnly'] = 1;

submit_cells('SearchOrders', _("Search"), '', '', 'default');

end_row();
end_table();

$dim = get_company_pref('use_dimension');

function view_link($row) 
{
	return get_dimensions_trans_view_str(ST_DIMENSION, $row["id"]);
}

function is_closed($row)
{
	return $row['closed'] ? _('Yes') : _('No');
}

function sum_dimension($row) 
{
	$sql = "SELECT SUM(amount) FROM ".TB_PREF."gl_trans WHERE tran_date >= '" .
		date2sql($_POST['FromDate']) . "' AND
		tran_date <= '" . date2sql($_POST['ToDate']) . "' AND (dimension_id = " .
		$row['id']." OR dimension2_id = " .$row['id'].")";
	$res = db_query($sql, "Sum of transactions could not be calculated");
	$row = db_fetch_row($res);

	return $row[0];
}

function is_overdue($row)
{
	return date_diff2(Today(), sql2date($row["due_date"]), "d") > 0;
}

function edit_link($row)
{
	//return $row["closed"] ?  '' :
	//	pager_link(_("Edit"),
	//		"/dimensions/dimension_entry.php?trans_no=" . $row["id"], ICON_EDIT);
	return pager_link(_("Edit"),
			"/dimensions/dimension_entry.php?trans_no=" . $row["id"], ICON_EDIT);
}

$sql = "SELECT dim.id,
	dim.reference,
	dim.name,
	dim.type_,
	dim.date_,
	dim.due_date,
	dim.closed
	FROM ".TB_PREF."dimensions as dim WHERE id > 0";

if (isset($_POST['OrderNumber']) && $_POST['OrderNumber'] != "")
{
	$sql .= " AND reference LIKE ".db_escape("%". $_POST['OrderNumber'] . "%");
} else {

	if ($dim == 1)
		$sql .= " AND type_=1";

	if (isset($_POST['OpenOnly']))
	{
   		$sql .= " AND closed=0";
	}

	if (isset($_POST['type_']) && ($_POST['type_'] > 0))
	{
   		$sql .= " AND type_=".db_escape($_POST['type_']);
	}

	if (isset($_POST['OverdueOnly']))
	{
		$today = date2sql(Today());

	   	$sql .= " AND due_date < '$today'";
	}

	$sql .= " AND date_ >= '" . date2sql($_POST['FromDate']) . "'
		AND date_ <= '" . date2sql($_POST['ToDate']) . "'";
}

$cols = array(
	_("#") => array('fun'=>'view_link'), 
	_("Reference"), 
	_("Name"), 
	_("Type"), 
	_("Date") =>'date',
	_("Due Date") => array('name'=>'due_date', 'type'=>'date', 'ord'=>'asc'), 
	_("Closed") => array('fun'=>'is_closed'),
	_("Balance") => array('type'=>'amount', 'insert'=>true, 'fun'=>'sum_dimension'),
	array('insert'=>true, 'fun'=>'edit_link')
);

if ($outstanding_only) {
	$cols[_("Closed")] = 'skip';
}

$table =& new_db_pager('dim_tbl', $sql, $cols);
$table->set_marker('is_overdue', _("Marked dimensions are overdue."));

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();

?>
