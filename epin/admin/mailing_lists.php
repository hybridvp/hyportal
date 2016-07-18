<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SECROLES';
$path_to_root = "..";
include($path_to_root . "/includes/session.inc");

page(_($help_context = "Mailing Lists"));

include($path_to_root . "/includes/ui.inc");

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
if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM') 
{

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

	if ($input_error != 1)
	{
    	if ($selected_id != -1) 
    	{
    		$sql = "UPDATE ".TB_PREF."mailing_list SET description=".db_escape($_POST['description'])
			.", txt_address=" . db_escape($_POST['emails']) 
			. " WHERE cod_list = ".db_escape($selected_id);
			$note = _('Selected Mail list has been updated');
    	} 
    	else 
    	{
    		$sql = "INSERT INTO ".TB_PREF."mailing_list (id, cod_list,description,txt_address) VALUES (MAILING_LIST_ID_SEQ.NEXTVAL,"
			.db_escape($_POST['cod_list'])."," 
			.db_escape($_POST['description'])."," 
			.db_escape($_POST['emails']) .")";
			$note = _('New Mail list has been added');
    	}
    
    	db_query($sql,"The Mail list could not be updated or added");
		display_notification($note);    	
		$Mode = 'RESET';
	}
} 

if ($Mode == 'Delete')
{

	$cancel_delete = 0;

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'debtors_master'

	$sql= "SELECT COUNT(*) FROM ".TB_PREF."sales_approval WHERE MAIL_LIST_CODE=".db_escape($selected_id);
	$result = db_query($sql,"check failed");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		$cancel_delete = 1;
		display_error(_("Cannot delete this Mailing list because items have been created using this List."));
	} 
	if ($cancel_delete == 0) 
	{
		$sql="DELETE FROM ".TB_PREF."mailing_list WHERE cod_list=".db_escape($selected_id);
		db_query($sql,"could not delete sales region");

		display_notification(_('Selected Mailing list has been deleted'));
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

$sql = "SELECT * FROM ".TB_PREF."mailing_list";
if (!check_value('show_inactive')) $sql .= " WHERE inactive=0";
$result = db_query($sql,"could not get areas");

start_form();
start_table("$table_style width=30%");

//$th = array(_("Region Code"), _("Region Name"), "", "");
$th = array(_("Name"),_("Description"), _("Email Addresses"),"", "");
inactive_control_column($th);

table_header($th);
$k = 0; 

while ($myrow = db_fetch($result)) 
{
	
	alt_table_row_color($k);
	label_cell($myrow["cod_list"]);
		
	label_cell($myrow["description"]);
	label_cell($myrow["txt_address"]);
	inactive_control_cell($myrow["cod_list"], $myrow["inactive"], 'cod_list', 'cod_list');

 	edit_button_cell("Edit".$myrow["cod_list"], _("Edit"));
 	delete_button_cell("Delete".$myrow["cod_list"], _("Delete"));
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
		$sql = "SELECT * FROM ".TB_PREF."mailing_list WHERE cod_list=".db_escape($selected_id);

		$result = db_query($sql,"could not get mailing_list");
		$myrow = db_fetch($result);
		$_POST['cod_list'] = $myrow["cod_list"];
		$_POST['description']  = $myrow["description"];		
		$_POST['emails'] = $myrow["txt_address"];
		label_row(_("Mail List Name:"),$_POST['cod_list']);
		hidden('cod_list', $_POST['cod_list']);

		text_row_ex(_("Mail List Description:"), 'description', 30,30); 
		textarea_row(_('Email Addresses:'), 'emails', null , 42, 5);

	}
	hidden("selected_id", $selected_id);
} 
else {
//text_row_ex(_("Region Code:"), 'cod_list', 10); 
//text_row_ex(_("Region name:"), 'description', 30); 

text_row_ex(_("Mail List Name:"), 'cod_list',null, 10);  
text_row_ex(_("Mail List Description:"), 'description',null, 30);  
textarea_row(_('Email Addresses:'), 'emails', null , 42,5);
}
end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();
?>