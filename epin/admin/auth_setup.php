<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SECROLES';
$path_to_root = "..";

include($path_to_root . "/includes/session.inc");
page(_($help_context = "Authorization Setup"));

include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/taxes/db/tax_types_db.inc");
include_once($path_to_root . "/simplex/includes/ui/our_ui_lists.inc");

simple_page_mode(true);
//-----------------------------------------------------------------------------------

function can_process()
{
	global $selected_id;
	
	if (strlen($_POST['name']) == 0)
	{
		display_error(_("The Approval description cannot be empty."));
		set_focus('name');
		return false;
	}
 	elseif (get_post('init_role') == get_post('approv_role'))
	{
		display_error( _("The initiating role and the approving role cannot be the same."));
		set_focus('init_role');
		return false;
	} 

/* 	if (!is_tax_gl_unique(get_post('sales_gl_code'), get_post('purchasing_gl_code'), $selected_id)) {
		display_error( _("Selected GL Accounts cannot be used by another tax type."));
		set_focus('sales_gl_code');
		return false;
	} */
	return true;
}

//-----------------------------------------------------------------------------------

if ($Mode=='ADD_ITEM' && can_process())
{
//($name, $maker, $checker, $type, $maillist)
	add_sales_approval($_POST['name'], $_POST['init_role'],	$_POST['approv_role'], 21,$_POST['maillist']);
	display_notification(_('New item has been added'));
	$Mode = 'RESET';
}

//-----------------------------------------------------------------------------------

if ($Mode=='UPDATE_ITEM' && can_process())
{
//($id, $name, $maker, $checker, $type, $maillist)

	update_sales_approval($selected_id, $_POST['name'],
    	$_POST['init_role'], $_POST['approv_role'], 21, $_POST['maillist']);
	display_notification(_('Selected Item has been updated'));
	$Mode = 'RESET';
}

//-----------------------------------------------------------------------------------

function can_delete($selected_id)
{
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."sales_tran_approval	WHERE trans_type=".db_escape($selected_id);
	$result = db_query($sql, "could not sales_tran_approval");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0)
	{
		display_error(_("Cannot delete this approval type because some transactions are referring to it."));

		return false;
	}

	return true;
}


//-----------------------------------------------------------------------------------

if ($Mode == 'Delete')
{

	if (can_delete($selected_id))
	{
		delete_sales_approval($selected_id);
		display_notification(_('Selected item has been deleted'));
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

$result = get_all_sales_approvals();

start_form();

//display_note(_("To avoid problems with manual journal entry all tax types should have unique Sales/Purchasing GL accounts."));
start_table($table_style);

$th = array(_("Description"),_("Transaction Type"), _("Initiating Role"),_("Approving Role"), "", "");
inactive_control_column($th);
table_header($th);

$k = 0;
while ($myrow = db_fetch($result))
{

	alt_table_row_color($k);

	label_cell($myrow["description"]);
	//label_cell(percent_format($myrow["rate"]), "align=right");
	label_cell( $systypes_array[$myrow["sales_type"]]);
	//label_cell($myrow["purchasing_gl_code"] . "&nbsp;" . $myrow["PurchasingAccountName"]);

	inactive_control_cell($myrow["id"], $myrow["inactive"], 'auth_type', 'id');
	label_cell($myrow["sales_role"]);
	label_cell($myrow["approving_role"]);
 	edit_button_cell("Edit".$myrow["id"], _("Edit"));
 	delete_button_cell("Delete".$myrow["id"], _("Delete"));

	end_row();
}

inactive_control_row($th);
end_table(1);
//-----------------------------------------------------------------------------------

start_table($table_style2);
if (!isset($_POST['filterType']))
	$_POST['filterType'] = 0;
if ($selected_id != -1) 
{
 	if ($Mode == 'Edit') {
		//editing an existing status code

		$myrow = get_sales_approval($selected_id);

		$_POST['name']  = $myrow["description"];
		//$_POST['rate']  = percent_format($myrow["rate"]);
		$_POST['init_role']  = $myrow["sales_role"];
		$_POST['approv_role']  = $myrow["approving_role"];
	}
	hidden('selected_id', $selected_id);
}
text_row_ex(_("Description:"), 'name', 50);
//small_amount_row(_("Default Rate:"), 'rate', '', "", "%", user_percent_dec());

//gl_all_accounts_list_row(_("Sales GL Account:"), 'sales_gl_code', null);
//gl_all_accounts_list_row(_("Purchasing GL Account:"), 'purchasing_gl_code', null);
cust_allocations_list_cells_3("Transaction Type", 'filterType', $_POST['filterType'], false);
security_roles_list_row(_("Initiating Role:"). "&nbsp;", 'init_role', null, true, true, check_value('show_inactive'));
security_roles_list_row(_("Approving Role:"). "&nbsp;", 'approv_role', null, true, true, check_value('show_inactive'));

so_mailing_list_row('Notification Mailing List', 'maillist', null, ST_SALESORDER);
end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();

?>
