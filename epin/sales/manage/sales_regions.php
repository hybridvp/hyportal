<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SALESAREA';
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");
global $nonfin_audit_trail;
page(_($help_context = "Sales Regions"));

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
    		$sql = "UPDATE ".TB_PREF."regions SET description=".db_escape($_POST['description'])." WHERE region_code = ".db_escape($selected_id);
			$note = _('Selected sales area has been updated');
			
			if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'SALES REGIONS','M',$ip,'SALES REGION' . $selected_id. " MODIFIED ");
			}
    	} 
    	else 
    	{
    		$sql = "INSERT INTO ".TB_PREF."regions (id,region_code,description) VALUES (region_codes_seq.nextval,".db_escape($_POST['region_code'])."," .db_escape($_POST['description']) . ")";
			$note = _('New sales region has been added');
			
			if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'SALES REGIONS','A',$ip,'SALES REGION' . $_POST['region_code']. " ADDED ");
			}
    	}
    
    	db_query($sql,"The sales region could not be updated or added");
		display_notification($note);    	
		$Mode = 'RESET';
	}
} 

if ($Mode == 'Delete')
{

	$cancel_delete = 0;

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'debtors_master'

	$sql= "SELECT COUNT(*) FROM ".TB_PREF."areas WHERE region_code=".db_escape($selected_id);
	$result = db_query($sql,"check failed");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		$cancel_delete = 1;
		display_error(_("Cannot delete this area because areas have been created using this region."));
	} 
	if ($cancel_delete == 0) 
	{
		$sql="DELETE FROM ".TB_PREF."regions WHERE region_code=".db_escape($selected_id);
		db_query($sql,"could not delete sales region");
		if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'SALES REGIONS','A',$ip,'SALES REGION' . $selected_id. " DELETED ");
			}
		display_notification(_('Selected sales region has been deleted'));
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

$sql = "SELECT * FROM ".TB_PREF."regions";
if (!check_value('show_inactive')) $sql .= " WHERE inactive=0";
$result = db_query($sql,"could not get areas");

start_form();
start_table("$table_style width=30%");

$th = array(_("Region Code"), _("Region Name"), "", "");
inactive_control_column($th);

table_header($th);
$k = 0; 

while ($myrow = db_fetch($result)) 
{
	
	alt_table_row_color($k);
	label_cell($myrow["region_code"]);
		
	label_cell($myrow["description"]);
	
	inactive_control_cell($myrow["region_code"], $myrow["inactive"], 'region_code', 'region_code');

 	edit_button_cell("Edit".$myrow["region_code"], _("Edit"));
 	delete_button_cell("Delete".$myrow["region_code"], _("Delete"));
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
		$sql = "SELECT * FROM ".TB_PREF."regions WHERE region_code=".db_escape($selected_id);

		$result = db_query($sql,"could not get region");
		$myrow = db_fetch($result);
		$_POST['region_code'] = $myrow["region_code"];
		$_POST['description']  = $myrow["description"];		
		label_row(_("Region Code:"),$_POST['region_code']);
		hidden('region_code', $_POST['region_code']);

		text_row_ex(_("Region name:"), 'description', 30); 


	}
	hidden("selected_id", $selected_id);
} 
else {
text_row_ex(_("Region Code:"), 'region_code', 10); 
text_row_ex(_("Region name:"), 'description', 30); 
}
end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();
?>
