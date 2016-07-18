<?php
/**********************************************************************
    Copyright Simplex
***********************************************************************/
$page_security = 'SA_SALESTRANSVIEW';
$path_to_root = "../..";
include_once($path_to_root . "/includes/session.inc");
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page(_($help_context = "- Split Range"), false, false, "", $js);
//page(_($help_context = "Are you booking an existing customer? Search Customers"));

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc");

if (isset($_GET['TransID']))
{
	$_POST['TransID'] = $_GET['TransID'];
}
simple_page_mode(true);
if (!isset($_GET['TransID']) || $_GET['TransID'] == 0)
{
die (_("This page can only be opened if a Transaction # of an OPS item has been selected. Please select a Transaction #  first."));
}


//--------------------------------------------------------------------------------------------------

function display_header()
{
	global $table_style;

	//$result = get_bom($selected_parent);
div_start('bom');
	start_table("$table_style width=60%");
	$th = array(_("1. Stage"), _("2. Stage 2"), _("3. Stage 3"),
		_("4. Stage 4"), _("5. Stage 5"));
	table_header($th);
	end_table();
div_end();
}
function GetOPSData($transid)
{
$sql = "SELECT 
	id, start_no,end_no,created_date,created_by
	FROM "
		.TB_PREF."ops_numbers ops 
	 WHERE ops.id=".$transid ; 
	$sql_a = db_query($sql);
	 $result = db_fetch($sql_a);
	 return $result;
}
//--------------------------------------------------------------------------------------------------
if (isset($_POST['split']))
{
	$input_error = 0;
	//do validation here
	/*if (strlen($_POST['description']) == 0) 
	{
		$input_error = 1;
		display_error( _('The item name must be entered.'));
		set_focus('description');
	} 
	elseif (strlen($_POST['NewStockID']) == 0) 
	{
		$input_error = 1;
		display_error( _('The item code cannot be empty'));
		set_focus('NewStockID');
	}
	*/	

}
start_form();

start_form(false, true);
start_table("class='tablestyle_noborder'");

end_table();
br();

end_form();

	//--------------------------------------------------------------------------------------

start_form();
	display_header();
	//--------------------------------------------------------------------------------------
	echo '<br>';

div_start('details');
start_outer_table($table_style2, 5);

table_section(1);

table_section_title(_("Split Ranges"));

$data = GetOPSData($_POST['TransID']);

		label_cell(_("Start Number: ". $data['start_no']), null) ;
		echo '</br>';
		label_cell(_("  End Number : ". $data['end_no'] ), null);
		//text_row(_("End Number:"), 'end_no', null, 21, 20);
submit_cells('split', _("Split Range"), "colspan=2", _(''), false);
end_outer_table(1);
div_end();
	//submit_add_or_update_center($selected_id == -1, '', 'both');
	//submit_center_first('addrange', _("Add new Range"), _("xxx"), 'default');
//	submit_center_first('AddTrip', _("Go"), _("xxx"), 'default');
end_form();

// ----------------------------------------------------------------------------------

end_page();

?>
