<?php  
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_GLACCOUNTGROUP';
$path_to_root = "../../..";
include($path_to_root . "/includes/session.inc");

page(_($help_context = "GL Account Analysis Groups"));

include($path_to_root . "/simplex/gl/includes/db/gl_db_acct_ana_types.inc");

include($path_to_root . "/includes/ui.inc");

simple_page_mode(true);
//-----------------------------------------------------------------------------------

function can_process() 
{
	global $selected_id;

/*	if (!input_num('id'))
	{
	    display_error( _("The account id must be an integer and cannot be empty."));
	    set_focus('id');
	    return false;
	}
*/
	if (strlen($_POST['name']) == 0) 
	{
		display_error( _("The account group name cannot be empty."));
		set_focus('name');
		return false;
	}

	if (isset($selected_id) && ($selected_id == $_POST['parent'])) 
	{
		display_error(_("You cannot set an account group to be a subgroup of itself."));
		return false;
	}

	return true;
}

//-----------------------------------------------------------------------------------

if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM') 
{

	if (can_process()) 
	{

    	if ($selected_id != -1) 
    	{
    		if (update_acct_ana_type($selected_id, $_POST['name'], $_POST['code'], $_POST['parent']))
				display_notification(_('Selected account analysis type has been updated'));
    	} 
    	else 
    	{
    		if (add_acct_ana_type($_POST['id'], $_POST['name'], $_POST['code'], $_POST['parent'])) {
				display_notification(_('New account analysis type has been added'));
				$Mode = 'RESET';
			}
    	}
	}
}

//-----------------------------------------------------------------------------------

function can_delete($selected_id)
{
	if ($selected_id == -1)
		return false;

	$type = db_escape($selected_id);
/*	$sql= "SELECT COUNT(*) FROM ".TB_PREF."chart_master
		WHERE account_type=$type";
	$result = db_query($sql, "could not query chart master");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this account group because GL accounts have been created referring to it."));
		return false;
	}
*/
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."analysis_codes
		WHERE parent=$type";
	$result = db_query($sql, "could not query chart types");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this account analysis group because GL account analysis groups have been created referring to it."));
		return false;
	}

	return true;
}


//-----------------------------------------------------------------------------------

if ($Mode == 'Delete')
{

	if (can_delete($selected_id))
	{
		delete_acct_ana_type($selected_id);
		display_notification(_('Selected account group has been deleted'));
	}
	$Mode = 'RESET';
}
if ($Mode == 'RESET')
{
 	$selected_id = -1;
	$_POST['id']  = $_POST['name']  = '';
	unset($_POST['parent']);
	unset($_POST['code']);
}
//-----------------------------------------------------------------------------------

$result = get_acct_ana_types(check_value('show_inactive'));

start_form();
start_table($table_style);
$th = array(_("ID"), _("Analysis Code"), _("Subgroup Of"), _("Description"), "", "");
inactive_control_column($th);
table_header($th);

$k = 0;
while ($myrow = db_fetch($result)) 
{

	alt_table_row_color($k);

	label_cell($myrow["id"]);
	label_cell($myrow["code"]);
	label_cell($myrow["parent"]);	
	label_cell($myrow["name"]);
	inactive_control_cell($myrow["id"], $myrow["inactive"], 'analysis_codes', 'id');
	edit_button_cell("Edit".$myrow["id"], _("Edit"));
	delete_button_cell("Delete".$myrow["id"], _("Delete"));
	end_row();
}

inactive_control_row($th);
end_table(1);
//-----------------------------------------------------------------------------------

start_table($table_style2);

if ($selected_id != -1)
{
	if ($Mode == 'Edit') 
	{
		//editing an existing status code
		$myrow = get_acct_ana_type($selected_id);
	
		$_POST['id']  = $myrow["id"];
		$_POST['name']  = $myrow["name"];
		$_POST['parent']  = $myrow["parent"];
		$_POST['code']  = $myrow["code"];
		hidden('selected_id', $selected_id);
	
 		hidden('id',$_POST['id']);
		label_row(_("ID:"), $_POST['id']);
	
		label_row(_("Acct Analysis Code:"), $_POST['code']);
 		hidden('code',$_POST['code']);
 	}
}
else

label_row(_("ID:"), '', 10);
hidden("id", '');
if ($Mode != 'Edit')  text_row_ex(_("Acct Analysis Code:"), 'code', 30);
text_row_ex(_("Description:"), 'name', 50);
text_row_ex(_("Subgroup Of:"), 'parent', 30);

end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

hyperlink_no_params($_SERVER['PHP_SELF'], _("Enter &A New Analysis"));
//------------------------------------------------------------------------------------

end_page();

?>
