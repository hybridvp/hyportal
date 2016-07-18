<?php
$path_to_root = "..";
include_once($path_to_root . "/includes/types.inc");
/**********************************************************************
    Copyright (C) SIMPLEX.
	@Kunle Lateef
This additional list should always come after include($path_to_root . "\includes\ui.inc"); 

As it is an extension of this module.
***********************************************************************/

//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
//--------------------------------------------------------------------------------
//
//Created List for account analysis codes (cost centres)
////////////////////////////////////////////////////////////////////////////////


function ana_code_list($name, $selected_id=null, $spec_option=false, $submit_on_change=false, 
	$show_inactive=false, $editkey = false)
{
	global $all_items;
//Change sub ref to name, it should be concatenated
	$sql = "SELECT code, name, inactive FROM ".TB_PREF."analysis_codes ";

	$mode = 0;

	if ($editkey)
		set_editor('code', $name, $editkey);

	return combo_input($name, $selected_id, $sql, 'code', 'name',
	array(
	    'order' => array('code'),
		'search_box' => true/*$mode!=0*/,
//
		'search' => array("code", "name"),
		'search_submit' => false/*get_company_pref('no_code_list')!=0*/,
//
		'type' => 0,
		'size' => 12,
		'max' =>10,
		'spec_option' => $spec_option === true ? _("All Analysis Codes") : $spec_option,
		'spec_id' => $all_items,
		'select_submit'=> $submit_on_change,
		'async' => false,
		'sel_hint' => $mode ? _('Press Space tab to filter by name fragment; F2 - entry new customer') :
		_('Select Analysis Code'),
		'show_inactive' => $show_inactive
	) );
}

function ana_code_list_cells($label, $name, $selected_id=null, $all_option=false, 
	$submit_on_change=false, $show_inactive=false, $editkey = false)
{
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td nowrap>";
	echo ana_code_list($name, $selected_id, $all_option, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n";
}

function ana_code_list_row($label, $name, $selected_id=null, $all_option = false, 
	$submit_on_change=false, $show_inactive=false, $editkey = false)
{
	echo "<tr><td>$label</td><td nowrap>";
	echo ana_code_list($name, $selected_id, $all_option, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n</tr>\n";
}

//------------------------------------------------------------------------------------------------
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//------------------------------------------------------------------------------------------------

//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
//--------------------------------------------------------------------------------
//
//      Created List for approvals
//
////////////////////////////////////////////////////////////////////////////////

function approving_list($name, $selected_id=null, $spec_option=false, $submit_on_change=false, 
	$show_inactive=false, $editkey = false)
{
	global $all_items;
//Change sub ref to name, it should be concatenated
	$sql = "SELECT approving_officer, (approving_officer|| ': '|| approving_officer_name) name 
	FROM ".TB_PREF."approval_mat ";
	
	$mode = 0;

	//if ($editkey)
	//	set_editor('code', $name, $editkey);

	return combo_input($name, $selected_id, $sql, 'approving_officer', 'name',
	array(
	    'order' => array('approving_officer'),
		'search_box' => true/*$mode!=0*/,
//
		'search' => array("approving_officer", "name"),
		'search_submit' => false/*get_company_pref('no_code_list')!=0*/,
//
		'type' => 0,
		'size' => 10,
		'max' =>12,
		'spec_option' => $spec_option === true ? _("All Approving Officers") : $spec_option,
		'spec_id' => $all_items,
		'select_submit'=> $submit_on_change,
		'async' => false,
		'sel_hint' => $mode ? _('Press Space tab to filter by name fragment') :
		_('Select Approving Officer'),
		'show_inactive' => $show_inactive
	) );
}

function approving_list_cells($label, $name, $selected_id=null, $all_option=false, 
	$submit_on_change=false, $show_inactive=false, $editkey = false)
{
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td nowrap>";
	echo approving_list($name, $selected_id, $all_option, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n";
}

function approving_list_row($label, $name, $selected_id=null, $all_option = false, 
	$submit_on_change=false, $show_inactive=false, $editkey = false)
{
	echo "<tr><td>$label</td><td nowrap>";
	echo approving_list($name, $selected_id, $all_option, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n</tr>\n";
}

//------------------------------------------------------------------------------------------------
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////


function so_approving_list($name, $type,$selected_id=null, $spec_option=false, $submit_on_change=false, 
	$show_inactive=false, $editkey = false)
{
	global $all_items;
//Change sub ref to name, it should be concatenated
	//$sql = "SELECT approving_officer, (approving_officer|| ': '|| approving_officer_name) name 
	//FROM ".TB_PREF."approval_mat ";
	
	$sql = "select user_id approving_officer, (user_id || ' : ' || real_name) name 
	from ". TB_PREF. "users where role_id =
	(
		select id from " .TB_PREF."security_roles where id = (select approving_role from " .TB_PREF."sales_approval where sales_type = ". $type ." )
	)";
	
	$mode = 0;

	//if ($editkey)
	//	set_editor('code', $name, $editkey);

	return combo_input($name, $selected_id, $sql, 'approving_officer', 'name',
	array(
	    'order' => array('approving_officer'),
		'search_box' => true/*$mode!=0*/,
//
		'search' => array("approving_officer", "name"),
		'search_submit' => false/*get_company_pref('no_code_list')!=0*/,
//
		'type' => 0,
		'size' => 10,
		'max' =>12,
		'spec_option' => $spec_option === true ? _("All Approving Officers") : $spec_option,
		'spec_id' => $all_items,
		'select_submit'=> $submit_on_change,
		'async' => false,
		'sel_hint' => $mode ? _('Press Space tab to filter by name fragment') :
		_('Select Approving Officer'),
		'show_inactive' => $show_inactive
	) );
}

function so_approving_list_cells($label, $name,  $selected_id=null, $type,$all_option=false, 
	$submit_on_change=false, $show_inactive=false, $editkey = false)
{
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td nowrap>";
	echo so_approving_list($name, $type, $selected_id, $all_option, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n";
}

function so_approving_list_row($label, $name, $selected_id=null,$type, $all_option = false, 
	$submit_on_change=false, $show_inactive=false, $editkey = false)
{
	echo "<tr><td>$label</td><td nowrap>";
	echo so_approving_list($name, $type,$selected_id, $all_option, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n</tr>\n";
}
//------------------------------------------------------------------------------------------------
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////


function so_mailing_list($name, $type,$selected_id=null, $spec_option=false, $submit_on_change=false, 
	$show_inactive=false, $editkey = false)
{
	global $all_items;
	//Change sub ref to name, it should be concatenated
	$sql = "SELECT cod_list, (cod_list|| ': '|| description) name 
	FROM ".TB_PREF."mailing_list ";
	
//$sql = "select user_id approving_officer, (user_id || ' : ' || real_name) name 	from ". TB_PREF. "users where role_id =	(
//select id from " .TB_PREF."security_roles where id = (select approving_role from " .TB_PREF."sales_approval where sales_type = ". $type ." )	)";
	
	$mode = 0;

	//if ($editkey)
	//	set_editor('code', $name, $editkey);

	return combo_input($name, $selected_id, $sql, 'description', 'name',
	array(
	    'order' => array('description'),
		'search_box' => true/*$mode!=0*/,
//
		'search' => array("description", "name"),
		'search_submit' => false/*get_company_pref('no_code_list')!=0*/,
//
		'type' => 0,
		'size' => 10,
		'max' =>12,
		'spec_option' => $spec_option === true ? _("All Mailing Lists") : $spec_option,
		'spec_id' => $all_items,
		'select_submit'=> $submit_on_change,
		'async' => false,
		'sel_hint' => $mode ? _('Press Space tab to filter by name fragment') :
		_('Select Mailing List'),
		'show_inactive' => $show_inactive
	) );
}

function so_mailing_list_cells($label, $name,  $selected_id=null, $type,$all_option=false, 
	$submit_on_change=false, $show_inactive=false, $editkey = false)
{
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td nowrap>";
	echo so_mailing_list($name, $type, $selected_id, $all_option, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n";
}

function so_mailing_list_row($label, $name, $selected_id=null,$type, $all_option = false, 
	$submit_on_change=false, $show_inactive=false, $editkey = false)
{
	echo "<tr><td>$label</td><td nowrap>";
	echo so_mailing_list($name, $type,$selected_id, $all_option, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n</tr>\n";
}



?>
