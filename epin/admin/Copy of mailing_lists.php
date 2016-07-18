<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SECROLES';
$path_to_root = "..";
include($path_to_root . "/includes/session.inc");

page(_($help_context = "Mailing Lists"));

include_once($path_to_root . "/includes/ui.inc");

include_once($path_to_root . "/inventory/includes/inventory_db.inc");
include_once($path_to_root . "/simplex/includes/db/simplexreferences_db.inc");
simple_page_mode(true);

//----------------------------------------------------------------------------------
function IsValidEmail($contactlist)
{

	$contactlist = str_replace("\r\n", "\n", $contactlist);
	$csv_array= explode(",",$contactlist);
	$csvnum=count($csv_array);
	
	for ($n=0;$n<$csvnum;$n++)
	{
		trim($csv_array[$n]);
		$emails = $csv_array[$n];
		if(!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", $emails)){
		//$badMail = "You've got an invalid email: $emails";
		//echo $badMail . "\t";
		return false;
	}
	return true;
	//end mail check loop
}
}
//----------------------------------------------------------------------------------

if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM') 
{

	//initialise no input errors assumed initially before we test
	$input_error = 0;

	if (strlen($_POST['description']) == 0) 
	{
		$input_error = 1;
		display_error(_("The Mail list description cannot be empty."));
		set_focus('description');
	}
	elseif (strlen($_POST['emails']) == 0) 
	{
		$input_error = 1;
		display_error(_("The Email field cannot be empty."));
		set_focus('emails');
	}
	elseif ( !IsValidEmail($_POST['emails'])) 
	{
		$input_error = 1;
		display_error(_("You've got an invalid email : The emails must be separated by comma"));
		set_focus('emails');
	}

	if ($input_error !=1)
	{
    	if ($selected_id != -1) 
    	{
		    update_mailing_list($_POST['cod_list'], $_POST['description'], $_POST['emails']);
			display_notification(_('Selected mailing list has been updated'));
    	} 
    	else 
    	{
		    add_mailing_list($_POST['cod_list'],$_POST['description'], $_POST['emails']);
			display_notification(_('New Mail list has been added'));
    	}
		$Mode = 'RESET';
	}
}

//---------------------------------------------------------------------------------- 

if ($Mode == 'Delete')
{

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'stock_master'
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."sales_approval WHERE MAIL_LIST_CODE=".db_escape($selected_id);
	$result = db_query($sql, "could not query sales_approval");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		display_error(_("Cannot delete this Mailing list because items have been created using this List."));
	} 
	else 
	{
		delete_mailing_list($selected_id);
		display_notification(_('Selected Mailing list has been deleted'));
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
if (list_updated('mb_flag')) {
	$Ajax->activate('details');
}
//----------------------------------------------------------------------------------

$sql = "SELECT * FROM ".TB_PREF."mailing_list 
WHERE 1=1 ";
if (!check_value('show_inactive')) $sql .= " AND inactive<>1";

$result = db_query($sql, "could not get mailing list");

start_form();
start_table("$table_style width=80%");
$th = array(_("Name"),_("Description"), _("Email Addresses"),"", "");

inactive_control_column($th);

table_header($th);
$k = 0; //row colour counter

while ($myrow = db_fetch($result)) 
{
	
	alt_table_row_color($k);
	label_cell($myrow["cod_list"]);
	label_cell($myrow["description"]);
	label_cell($myrow["txt_address"]);
	
	//inactive_control_cell($myrow["cod_list"], $myrow["inactive"], 'cod_list', 'cod_list');
	
 	edit_button_cell("Edit".$myrow["cod_list"], _("Edit"));
 	delete_button_cell("Delete".$myrow["cod_list"], _("Delete"));
	end_row();
}

inactive_control_row($th);
end_table();
echo '<br>';
//----------------------------------------------------------------------------------

div_start('details');
start_table($table_style2);

if ($selected_id != -1) 
{
	
 	if ($Mode == 'Edit') {
		//editing an existing item category
		$myrow = get_mailing_list($selected_id);
		$_POST['cod_list']  = $myrow["cod_list"];
		$_POST['description']  = $myrow["description"];
		$_POST['emails']  = $myrow["txt_address"];
		
		label_row(_("Mail List Name:"),$_POST['cod_list']);
		hidden('cod_list', $_POST['cod_list']);
		
		
		text_row(_("Mail List Description:"), 'description',null, 30, 30);  
		textarea_row(_('Email Addresses:'), 'emails', null , 42, 5);
		
	} 
	hidden('selected_id', $selected_id);
/* 	 else if ($Mode != 'CLONE') {
		$_POST['description'] = '';
		$_POST['emails'] = '';
		$company_record = get_company_prefs();
		hidden('selected_id', $selected_id);
	} */
}
else
{

text_row(_("Mail List Name:"), 'cod_list',null, 10, 10);  
text_row(_("Mail List Description:"), 'description',null, 30, 30);  
textarea_row(_('Email Addresses:'), 'emails', null , 42, 5);

}
end_table(1);
div_end();
submit_add_or_update_center($selected_id == -1, '', 'both'); //, true

end_form();

end_page();

?>
