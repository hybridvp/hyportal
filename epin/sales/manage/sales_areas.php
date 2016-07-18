<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SALESAREA';
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");

page(_($help_context = "Sales Territories"));

include($path_to_root . "/includes/ui.inc");

simple_page_mode(true);

if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM') 
{

	$input_error = 0;

	if (strlen($_POST['description']) == 0) 
	{
		$input_error = 1;
		display_error(_("The area description cannot be empty."));
		set_focus('description');
	}

	if ($input_error != 1)
	{
    	if ($selected_id != -1) 
    	{
    		$sql = "UPDATE ".TB_PREF."areas SET description=".db_escape($_POST['description']) 
			. ", region_code=". db_escape($_POST['region'])
			." WHERE area_code = ".db_escape($selected_id);
			$note = _('Selected sales area has been updated');
    	} 
    	else 
    	{
    		$sql = "INSERT INTO ".TB_PREF."areas (id,AREA_CODE,description,region_code) VALUES (AREAS_AREA_CODES_SEQ.NEXTVAL,"
			.db_escape($_POST['area_code']) . "," 
			. db_escape($_POST['description']) .","
			. db_escape($_POST['region']). ")";
			$note = _('New sales area has been added');
    	}
    
    	db_query($sql,"The sales area could not be updated or added");
		display_notification($note);    	
		$Mode = 'RESET';
	}
} 

if ($Mode == 'Delete')
{

	$cancel_delete = 0;

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'debtors_master'

	$sql= "SELECT COUNT(*) FROM ".TB_PREF."cust_branch WHERE area=".db_escape($selected_id);
	$result = db_query($sql,"check failed");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		$cancel_delete = 1;
		display_error(_("Cannot delete this area because customer branches have been created using this area."));
	} 
	if ($cancel_delete == 0) 
	{
		$sql="DELETE FROM ".TB_PREF."areas WHERE area_code=".db_escape($selected_id);
		db_query($sql,"could not delete sales area");

		display_notification(_('Selected sales area has been deleted'));
	} //end if Delete area
	$Mode = 'RESET';
} 

if ($Mode == 'RESET')
{
	$selected_id = -1;
	$sav = get_post('show_inactive');
	unset($_POST);
	$_POST['show_inactive'] = $sav;
}

//-------------------------------------------------------------------------------------------------

$sql = "SELECT * FROM ".TB_PREF."areas";
if (!check_value('show_inactive')) $sql .= " WHERE inactive=0";
$result = db_query($sql,"could not get areas");

start_form();
start_table("$table_style width=30%");

$th = array(_("Territory Code"), _("Region"),_("Territory Name"), "", "");
inactive_control_column($th);

table_header($th);
$k = 0; 

while ($myrow = db_fetch($result)) 
{
	
	alt_table_row_color($k);
	label_cell($myrow["area_code"]);
	label_cell($myrow["region_code"]);
	label_cell($myrow["description"]);
	
	inactive_control_cell($myrow["area_code"], $myrow["inactive"], 'areas', 'area_code');

 	edit_button_cell("Edit".$myrow["area_code"], _("Edit"));
 	delete_button_cell("Delete".$myrow["area_code"], _("Delete"));
	end_row();
}
	
inactive_control_row($th);
end_table();
echo '<br>';

//-------------------------------------------------------------------------------------------------

start_table($table_style2);

if ($selected_id != -1) 
{
 	if ($Mode == 'Edit') {
		//editing an existing area
		$sql = "SELECT * FROM ".TB_PREF."areas WHERE area_code=".db_escape($selected_id);

		$result = db_query($sql,"could not get area");
		$myrow = db_fetch($result);
		
		$_POST['area_code'] = $myrow["area_code"];
		$_POST['region'] = $myrow["region_code"];
		label_row(_("Area Code:"),$_POST['area_code']);
		hidden('area_code', $_POST['area_code']);
		//label_row(_("Area Code:"), 'area_code', 10); 
		$_POST['description']  = $myrow["description"];
		
		text_row_ex(_("Territory Name:"), 'description', 30); 
		sales_region_list_row( _("Sales Region:"), 'region', null);
	}
	hidden("selected_id", $selected_id);
} 
else 
{
//if ($Mode=='ADD_ITEM')
//{ 
	text_row_ex(_("Territory Code:"), 'area_code', 10); 
//}
text_row_ex(_("Territory Name:"), 'description', 30); 
sales_region_list_row( _("Sales Region:"), 'region', null);
}
end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();
?>
