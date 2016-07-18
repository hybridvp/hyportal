<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_INVENTORYLOCATION';
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");

page(_($help_context = "Inventory Locations"));

include_once($path_to_root . "/includes/ui.inc");

include_once($path_to_root . "/inventory/includes/inventory_db.inc");

simple_page_mode(true);

if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM') 
{

	//initialise no input errors assumed initially before we test
	$input_error = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$_POST['loc_code'] = strtoupper($_POST['loc_code']);

	if (strlen(db_escape($_POST['loc_code'])) > 7) //check length after conversion
	{
		$input_error = 1;
		display_error( _("The location code must be five characters or less long (including converted special chars)."));
		set_focus('loc_code');
	} 
	elseif (strlen($_POST['location_name']) == 0) 
	{
		$input_error = 1;
		display_error( _("The location name must be entered."));		
		set_focus('location_name');
	}
	elseif (strlen($_POST['email']) == 0) 
	{
		$input_error = 1;
		display_error( _("The email must be entered."));		
		set_focus('email');
	}
	if ($input_error != 1) 
	{
    	if ($selected_id != -1) 
    	{
    
    		update_item_location($selected_id, $_POST['location_name'], $_POST['delivery_address'],
			$_POST['phone'], $_POST['phone2'], $_POST['fax'], $_POST['email'], $_POST['contact'], 
			$_POST['loc_type'], check_value('activate'));	//Laolu added loc type

			display_notification(_('Selected location has been updated'));
    	} 
    	else 
    	{
    
    	/*selected_id is null cos no item selected on first time round so must be adding a	record must be submitting new entries in the new Location form */
    	
    		add_item_location($_POST['loc_code'], $_POST['location_name'], $_POST['delivery_address'], 
			$_POST['phone'], $_POST['phone2'], $_POST['fax'], $_POST['email'], 
			$_POST['contact'], $_POST['loc_type'], check_value('activate'));

    		//Laolu added loc type
			display_notification(_('New location has been added'));
    	}
		
		$Mode = 'RESET';
	}
} 

function can_delete($selected_id)
{
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."stock_moves WHERE loc_code=".db_escape($selected_id);
	$result = db_query($sql, "could not query stock moves");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this location because item movements have been created using this location."));
		return false;
	}

	$sql= "SELECT COUNT(*) FROM ".TB_PREF."workorders WHERE loc_code=".db_escape($selected_id);
	$result = db_query($sql, "could not query work orders");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this location because it is used by some work orders records."));
		return false;
	}

	$sql= "SELECT COUNT(*) FROM ".TB_PREF."cust_branch WHERE default_location='$selected_id'";
	$result = db_query($sql, "could not query customer branches");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this location because it is used by some branch records as the default location to deliver from."));
		return false;
	}
	
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."bom WHERE loc_code=".db_escape($selected_id);
	$result = db_query($sql, "could not query bom");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this location because it is used by some related records in other tables."));
		return false;
	}
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."grn_batch WHERE loc_code=".db_escape($selected_id);
	$result = db_query($sql, "could not query grn batch");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this location because it is used by some related records in other tables."));
		return false;
	}
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."purch_orders WHERE into_stock_location=".db_escape($selected_id);
	$result = db_query($sql, "could not query purch orders");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this location because it is used by some related records in other tables."));
		return false;
	}
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."sales_orders WHERE from_stk_loc=".db_escape($selected_id);
	$result = db_query($sql, "could not query sales orders");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this location because it is used by some related records in other tables."));
		return false;
	}
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."sales_pos WHERE pos_location=".db_escape($selected_id);
	$result = db_query($sql, "could not query sales pos");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this location because it is used by some related records in other tables."));
		return false;
	}
	//for etisalat - No locations Needed but we must have default location
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."location WHERE loc_code='DEF'";
	$result = db_query($sql, "could not query locations");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this location because you must have at leat one default location."));
		return false;
	}
	return true;
}

//----------------------------------------------------------------------------------

if ($Mode == 'Delete')
{

	if (can_delete($selected_id)) 
	{
		delete_item_location($selected_id);
		display_notification(_('Selected location has been deleted'));
	} //end if Delete Location
	$Mode = 'RESET';
}

if ($Mode == 'RESET')
{
	$selected_id = -1;
	$sav = get_post('show_inactive');
	unset($_POST);
	$_POST['show_inactive'] = $sav;
}

$sql = "SELECT * FROM ".TB_PREF."locations";
if (!check_value('show_inactive')) $sql .= " WHERE inactive=0";
$result = db_query($sql, "could not query locations");;

start_form();
start_table($table_style);
$th = array(_("Location Code"), _("Location Name"), _("Address"), _("Phone"), _("Secondary Phone"), _("Email"),_("Location Type"), "", "");

//$th = array(_("Location Code"), _("Location Name"), _("Address"), _("Phone"), _("Secondary Phone"), "", "");
inactive_control_column($th);
table_header($th);
$k = 0; //row colour counter
while ($myrow = db_fetch($result)) 
{

	alt_table_row_color($k);
	
	label_cell($myrow["loc_code"]);
	label_cell($myrow["location_name"]);
	label_cell($myrow["delivery_address"]);
	label_cell($myrow["phone"]);
	label_cell($myrow["phone2"]);
	label_cell($myrow["email"]);
	label_cell($myrow["location_type"]);
	inactive_control_cell($myrow["loc_code"], $myrow["inactive"], 'locations', 'loc_code');
 	edit_button_cell("Edit".$myrow["loc_code"], _("Edit"));
 	delete_button_cell("Delete".$myrow["loc_code"], _("Delete"));
	end_row();
}
	//END WHILE LIST LOOP
inactive_control_row($th);
end_table();

echo '<br>';

start_table($table_style2);

$_POST['email'] = "";
if ($selected_id != -1) 
{
	//editing an existing Location

 	if ($Mode == 'Edit') {
		$myrow = get_item_location($selected_id);

		$_POST['loc_code'] = $myrow["loc_code"];
		$_POST['location_name']  = $myrow["location_name"];
		$_POST['delivery_address'] = $myrow["delivery_address"];
		$_POST['contact'] = $myrow["contact"];
		$_POST['phone'] = $myrow["phone"];
		$_POST['phone2'] = $myrow["phone2"];
		$_POST['fax'] = $myrow["fax"];
		$_POST['email'] = $myrow["email"];
		$_POST['activate'] = $myrow["activate_on_arrival"];
		$_POST['loc_type'] = $myrow["location_type"];
	}
	hidden("selected_id", $selected_id);
	hidden("loc_code");
	label_row(_("Location Code:"), $_POST['loc_code']);
} 
else 
{ //end of if $selected_id only do the else when a new record is being entered
	text_row(_("Location Code:"), 'loc_code', null, 5, 5);
}

text_row_ex(_("Location Name:"), 'location_name', 50, 50);
text_row_ex(_("Contact for deliveries:"), 'contact', 30, 30);

textarea_row(_("Address:"), 'delivery_address', null, 35, 5);	

text_row_ex(_("Telephone No:"), 'phone', 32, 30);
text_row_ex(_("Secondary Phone Number:"), 'phone2', 32, 30);
text_row_ex(_("Facsimile No:"), 'fax', 32, 30);
email_row_ex(_("E-mail:"), 'email', 30);
locations_type_list_cells('Location Type','loc_type','',false,true);
check_row(_("Activate Items on Arrival:"), 'activate');

//check_row(_("Arrival Location:"), 'arrival');

end_table(1);
submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();

?>
