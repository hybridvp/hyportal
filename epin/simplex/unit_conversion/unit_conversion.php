<?php

$page_security = 'SA_UOM';
$path_to_root="../..";
include($path_to_root . "/includes/session.inc");

//page(_("Units of Measure Conversion"));
page(_($help_context = "Units of Measure Conversion"));

include_once($path_to_root . "/includes/ui.inc");

include_once($path_to_root . "/inventory/includes/db/items_units_db.inc");

simple_page_mode(false);
//----------------------------------------------------------------------------------

if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM') 
{

	//initialise no input errors assumed initially before we test
	$input_error = 0;

	if (strlen($_POST['name']) == 0)
	{
		$input_error = 1;
		display_error(_("The unit of measure code cannot be empty."));
		set_focus('name');
	}
	if (strlen($_POST['desc']) == 0)
	{
		$input_error = 1;
		display_error(_("The unit of measure description cannot be empty."));
		set_focus('desc');
	}

	if ($input_error !=1) {
    	write_item_unit_conv(htmlentities($selected_id), $_POST['name'], $_POST['desc'], $_POST['abbr1'], $_POST['abbr2'], $_POST['decimals'] );
		if($selected_id != '')
			display_notification(_('Selected unit conversion has been updated'));
		else
			display_notification(_('New unit conversion has been added'));
		$Mode = 'RESET';
	}
}

//----------------------------------------------------------------------------------

if ($Mode == 'Delete')
{

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'stock_master'

	if (item_unit_used_conv($selected_id))
	{
		display_error(_("Cannot delete this unit of measure conversion because items have been created using this conversion."));

	}
	else
	{
		delete_item_unit_conv($selected_id);
		display_notification(_('Selected unit Conversion has been deleted'));
	}
	$Mode = 'RESET';
}

if ($Mode == 'RESET')
{
	$selected_id = '';
	unset($_POST);
}

//----------------------------------------------------------------------------------

$result = get_all_item_units_conv();
start_form();
start_table("$table_style width=40%");
$th = array(_('Name'), _('Description'), _('Unit 1'), _('Unit 2'), _('Conversion Rate:'), "", "");

table_header($th);
$k = 0; //row colour counter

while ($myrow = db_fetch($result))
{

	alt_table_row_color($k);

	label_cell($myrow["name"]);
	label_cell($myrow["description"]);
	label_cell($myrow["abbr1"]);
	label_cell($myrow["abbr2"]);
	label_cell($myrow["decimals"]);

 	edit_button_cell("Edit".$myrow["name"], _("Edit"));
 	delete_button_cell("Delete".$myrow["name"], _("Delete"));
	end_row();
}

end_table();
end_form();
echo '<br>';

//----------------------------------------------------------------------------------

start_form();

start_table($table_style2);

if ($selected_id != '') 
{
 	if ($Mode == 'Edit') {
		//editing an existing item category

		$myrow = get_item_unit_conv($selected_id);

		$_POST['name'] = $myrow["name"];
		$_POST['desc']  = $myrow["description"];
		$_POST['unit1'] = $myrow["abbr1"];
		$_POST['unit2']  = $myrow["abbr2"];
		$_POST['decimals']  = $myrow["decimals"];
	}
	hidden('selected_id', $selected_id);
}
if ($selected_id != '' && item_unit_conv_used($selected_id)) {
    label_row(_("Unit Conversion Rate:"), $_POST['name']);
    hidden('name', $_POST['name']);
} else
    text_row(_("Unit Conversion Name:"), 'name', null, 20, 20);
	text_row(_("Descriptive:"), 'desc', null, 40, 40);
	conversion_list_row(_("Unit Type 1:"), 'abbr1', '');
	conversion_list_row(_("Unit Type 2:"), 'abbr2', '');
	text_row(_("Conversion Rate:"), 'decimals', null, 40, 40);

//number_list_row(_("Decimal Places:"), 'decimals', null, 0, 6, _("User Quantity Decimals"));

end_table(1);

submit_add_or_update_center($selected_id == '', '', true);

end_form();

end_page();

?>
