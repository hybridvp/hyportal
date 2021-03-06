<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_INVENTORYMOVETYPE';
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");

page(_($help_context = "Inventory Movement Types"));

include_once($path_to_root . "/inventory/includes/inventory_db.inc");

include_once($path_to_root . "/includes/ui.inc");

simple_page_mode(true);
//-----------------------------------------------------------------------------------

if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM') 
{

	//initialise no input errors assumed initially before we test
	$input_error = 0;

	if (strlen($_POST['name']) == 0) 
	{
		$input_error = 1;
		display_error(_("The inventory movement type name cannot be empty."));
		set_focus('name');
	}

	if ($input_error != 1) 
	{
    	if ($selected_id != -1) 
    	{
    		update_movement_type($selected_id, $_POST['name']);
			display_notification(_('Selected movement type has been updated'));
    	} 
    	else 
    	{
    		add_movement_type($_POST['name']);
			display_notification(_('New movement type has been added'));
    	}
    	
		$Mode = 'RESET';
	}
} 

//-----------------------------------------------------------------------------------

function can_delete($selected_id)
{
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."stock_moves 
		WHERE type=" . ST_INVADJUST. " AND person_id=".db_escape($selected_id);

	$result = db_query($sql, "could not query stock moves");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this inventory movement type because item transactions have been created referring to it."));
		return false;
	}
	
	return true;
}


//-----------------------------------------------------------------------------------

if ($Mode == 'Delete')
{
	if (can_delete($selected_id))
	{
		delete_movement_type($selected_id);
		display_notification(_('Selected movement type has been deleted'));
	}
	$Mode = 'RESET';
}

if ($Mode == 'RESET')
{
	$selected_id = -1;
	$sav = get_post('show_inactive');
	unset($_POST);
	$_POST['show_inactive'] = $sav;
}
//-----------------------------------------------------------------------------------

$result = get_all_movement_type(check_value('show_inactive'));

start_form();
start_table("$table_style width=30%");

$th = array(_("Description"), "", "");
inactive_control_column($th);
table_header($th);
$k = 0;
while ($myrow = db_fetch($result)) 
{
	
	alt_table_row_color($k);	

	label_cell($myrow["name"]);
	inactive_control_cell($myrow["id"], $myrow["inactive"], 'movement_types', 'id');
 	edit_button_cell("Edit".$myrow['id'], _("Edit"));
 	delete_button_cell("Delete".$myrow['id'], _("Delete"));
	end_row();
}
inactive_control_row($th);
end_table(1);

//-----------------------------------------------------------------------------------

start_table($table_style2);

if ($selected_id != -1) 
{
 	if ($Mode == 'Edit') {
		//editing an existing status code

		$myrow = get_movement_type($selected_id);

		$_POST['name']  = $myrow["name"];
	}
	hidden('selected_id', $selected_id);
} 

text_row(_("Description:"), 'name', null, 50, 50);

end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

//------------------------------------------------------------------------------------

end_page();

?>
