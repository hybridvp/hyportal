<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_QUICKENTRY';
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");

page(_($help_context = "Quick Entries"));

include($path_to_root . "/gl/includes/gl_db.inc");

include($path_to_root . "/includes/ui.inc");

simple_page_mode(true);
simple_page_mode2(true);

function simple_page_mode2($numeric_id = true)
{
	global $Ajax, $Mode2, $selected_id2;

	$default = $numeric_id ? -1 : '';
	$selected_id2 = get_post('selected_id2', $default);
	foreach (array('ADD_ITEM2', 'UPDATE_ITEM2', 'RESET2') as $m) {
		if (isset($_POST[$m])) {
			$Ajax->activate('_page_body');
			if ($m == 'RESET2') 
				$selected_id2 = $default;
			$Mode2 = $m; return;
		}
	}
	foreach (array('BEd', 'BDel') as $m) {
		foreach ($_POST as $p => $pvar) {
			if (strpos($p, $m) === 0) {
//				$selected_id2 = strtr(substr($p, strlen($m)), array('%2E'=>'.'));
				unset($_POST['_focus']); // focus on first form entry
				$selected_id2 = quoted_printable_decode(substr($p, strlen($m)));
				$Ajax->activate('_page_body');
				$Mode2 = $m;
				return;
			}
		}
	}
	$Mode2 = '';
}

function submit_add_or_update_center2($add=true, $title=false, $async=false)
{
	echo "<center>";
	if ($add)
		submit('ADD_ITEM2', _("Add new"), true, $title, $async);
	else {
		submit('UPDATE_ITEM2', _("Update"), true, $title, $async);
		submit('RESET2', _("Cancel"), true, $title, $async);
	}
	echo "</center>";
}

//-----------------------------------------------------------------------------------

function can_process() 
{

	if (strlen($_POST['description']) == 0) 
	{
		display_error( _("The Quick Entry description cannot be empty."));
		set_focus('description');
		return false;
	}
	if (strlen($_POST['base_desc']) == 0) 
	{
		display_error( _("The base amount description cannot be empty."));
		set_focus('base_desc');
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
			update_quick_entry($selected_id, $_POST['description'], $_POST['type'],
				 input_num('base_amount'), $_POST['base_desc']);
			display_notification(_('Selected quick entry has been updated'));
		} 
		else 
		{
			add_quick_entry($_POST['description'], $_POST['type'], 
				input_num('base_amount'), $_POST['base_desc']);
			display_notification(_('New quick entry has been added'));
		}
		$Mode = 'RESET';
	}
}

if ($Mode2=='ADD_ITEM2' || $Mode2=='UPDATE_ITEM2') 
{
	if ($selected_id2 != -1) 
	{
		update_quick_entry_line($selected_id2, $selected_id, $_POST['actn'], $_POST['dest_id'], input_num('amount', 0), 
			$_POST['dimension_id'], $_POST['dimension2_id']);
		display_notification(_('Selected quick entry line has been updated'));
	} 
	else 
	{
		add_quick_entry_line($selected_id, $_POST['actn'], $_POST['dest_id'], input_num('amount', 0), 
			$_POST['dimension_id'], $_POST['dimension2_id']);
		display_notification(_('New quick entry line has been added'));
	}
	$Mode2 = 'RESET2';
}

//-----------------------------------------------------------------------------------

if ($Mode == 'Delete')
{
	if (!has_quick_entry_lines($selected_id))
	{
		delete_quick_entry($selected_id);
		display_notification(_('Selected quick entry has been deleted'));
		$Mode = 'RESET';
	}
	else
	{
		display_error( _("The Quick Entry has Quick Entry Lines. Cannot be deleted."));
		set_focus('description');
	}
}

if ($Mode2 == 'BDel')
{
	delete_quick_entry_line($selected_id2);
	display_notification(_('Selected quick entry line has been deleted'));
	$Mode2 = 'RESET2';
}
//-----------------------------------------------------------------------------------
if ($Mode == 'RESET')
{
	$selected_id = -1;
	$_POST['description'] = $_POST['type'] = '';
	$_POST['base_desc']= _('Base Amount');
	$_POST['base_amount'] = price_format(0);
}
if ($Mode2 == 'RESET2')
{
	$selected_id2 = -1;
	$_POST['actn'] = $_POST['dest_id'] = $_POST['amount'] = 
		$_POST['dimension_id'] = $_POST['dimension2_id'] = '';
}
//-----------------------------------------------------------------------------------

$result = get_quick_entries();
start_form();
start_table($table_style);
$th = array(_("Description"), _("Type"), "", "");
table_header($th);

$k = 0;
while ($myrow = db_fetch($result)) 
{
	alt_table_row_color($k);
	$type_text = $quick_entry_types[$myrow["type"]];
	label_cell($myrow['description']);
	label_cell($type_text);
	edit_button_cell("Edit".$myrow["id"], _("Edit"));
	delete_button_cell("Delete".$myrow["id"], _("Delete"));
	end_row();
}

end_table(1);
end_form();
//-----------------------------------------------------------------------------------

start_form();

start_table($table_style2);

if ($selected_id != -1) 
{
 	//if ($Mode == 'Edit') 
 	//{
		//editing an existing status code
		$myrow = get_quick_entry($selected_id);

		$_POST['id']  = $myrow["id"];
		$_POST['description']  = $myrow["description"];
		$_POST['type']  = $myrow["type"];
		$_POST['base_desc']  = $myrow["base_desc"];
		$_POST['base_amount']  = price_format($myrow["base_amount"]);
		hidden('selected_id', $selected_id);
 	//}
} 

text_row_ex(_("Description").':', 'description', 50, 60);

quick_entry_types_list_row(_("Entry Type").':', 'type');

text_row_ex(_("Base Amount Description").':', 'base_desc', 50, 60, '',_('Base Amount'));

amount_row(_("Default Base Amount").':', 'base_amount', price_format(0));

end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();


if ($selected_id != -1)
{
	display_heading(_("Quick Entry Lines") . " - " . $_POST['description']);
	$result = get_quick_entry_lines($selected_id);
	start_form();
	start_table($table_style2);
	$dim = get_company_pref('use_dimension');
	if ($dim == 2)
		$th = array(_("Post"), _("Account/Tax Type"), _("Amount"), _("Dimension"), _("Dimension")." 2", "", "");
	else if ($dim == 1)	
		$th = array(_("Post"), _("Account/Tax Type"), _("Amount"), _("Dimension"), "", "");
	else	
		$th = array(_("Post"), _("Account/Tax Type"), _("Amount"), "", "");

	table_header($th);
	$k = 0;
	while ($myrow = db_fetch($result)) 
	{
		alt_table_row_color($k);
		
		label_cell($quick_actions[$myrow['action']]);

		$act_type = strtolower(substr($myrow['action'], 0, 1));

		if ($act_type == 't') 
		{
			label_cells($myrow['tax_name'], '');
		} 
		else 
		{
			label_cell($myrow['dest_id'].' '.$myrow['account_name']);
			if ($act_type == '=') 
				label_cell('');
			elseif ($act_type == '%') 
				label_cell(number_format2($myrow['amount'], user_exrate_dec()), "nowrap align=right ");
			else
				amount_cell($myrow['amount']);
		}		
   		if ($dim >= 1)
			label_cell(get_dimension_string($myrow['dimension_id'], true));
   		if ($dim > 1)
			label_cell(get_dimension_string($myrow['dimension2_id'], true));
		edit_button_cell("BEd".$myrow["id"], _("Edit"));
		delete_button_cell("BDel".$myrow["id"], _("Delete"));
		end_row();
	}
	end_table(1);
	hidden('selected_id', $selected_id);
	hidden('selected_id2', $selected_id2);
	hidden('description', $_POST['description']);
	hidden('type', $_POST['type']);
	end_form();
	start_form();

	div_start('edit_line');
	start_table($table_style2);

	if ($selected_id2 != -1) 
	{
	 	if ($Mode2 == 'BEd') 
	 	{
			//editing an existing status code
			$myrow = get_quick_entry_line($selected_id2);

			$_POST['id']  = $myrow["id"];
			$_POST['dest_id']  = $myrow["dest_id"];
			$_POST['actn']  = $myrow["action"];
			$_POST['amount']  = $myrow["amount"];
			$_POST['dimension_id']  = $myrow["dimension_id"];
			$_POST['dimension2_id']  = $myrow["dimension2_id"];
	 	}
	} 

	quick_actions_list_row(_("Posted").":",'actn', null, true);
	if (list_updated('actn'))
		$Ajax->activate('edit_line');

	$actn = strtolower(substr($_POST['actn'],0,1));

	if ($actn == 't') 
	{
		//item_tax_types_list_row(_("Item Tax Type").":",'dest_id', null);
		tax_types_list_row(_("Tax Type").":", 'dest_id', null);
	} 
	else 
	{
		gl_all_accounts_list_row(_("Account").":", 'dest_id', null, $_POST['type'] == QE_DEPOSIT || $_POST['type'] == QE_PAYMENT);
		if ($actn != '=') 
		{
			if ($actn == '%') 
				small_amount_row(_("Part").":", 'amount', price_format(0), null, "%", user_exrate_dec());
			else
				amount_row(_("Amount").":", 'amount', price_format(0));
		}
	}
	if ($dim >= 1) 
		dimensions_list_row(_("Dimension").":", 'dimension_id', null, true, " ", false, 1);
	if ($dim > 1) 
		dimensions_list_row(_("Dimension")." 2:", 'dimension2_id', null, true, " ", false, 2);
	
	end_table(1);
	if ($dim < 2)
		hidden('dimension2_id', 0);
	if ($dim < 1)
		hidden('dimension_id', 0);
	div_end();

	hidden('selected_id', $selected_id);
	hidden('selected_id2', $selected_id2);
	hidden('description', $_POST['description']);
	hidden('type', $_POST['type']);

	submit_add_or_update_center2($selected_id2 == -1, '', true);

	end_form();
}		
//------------------------------------------------------------------------------------

end_page();

?>