<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_BUDGETENTRY';
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");
add_js_file('budget.js');

page(_($help_context = "Budget Entry"));

include($path_to_root . "/includes/ui.inc");

include($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/simplex/includes/ui/our_ui_lists.inc");


check_db_has_gl_account_groups(_("There are no account groups defined. Please define at least one account group before entering accounts."));
///////////////////////////////////////////////////////////////////////////////////
//-------------------------------------------------------------------------------------
function get_budget_master_from_to($from_date, $to_date, $account, $dimension=0, $dimension2=0)
{

	$from = date2sql($from_date);
	$to = date2sql($to_date);

	$sql = "SELECT SUM(amount) FROM ".TB_PREF."budget_master
		WHERE account=".db_escape($account);
	if ($from_date != "")
		$sql .= " AND tran_date >= '$from' ";
	if ($to_date != "")
		$sql .= " AND tran_date <= '$to' ";
	if ($dimension != 0)
  		$sql .= " AND dimension_id = ".($dimension<0?0:db_escape($dimension));
	if ($dimension2 != 0)
  		$sql .= " AND dimension2_id = ".($dimension2<0?0:db_escape($dimension2));
	$result = db_query($sql,"No budget accounts were returned");

	$row = db_fetch_row($result);
	return $row[0];
}

function exists_gl_budget($date_, $account, $costcentre, $dimension, $dimension2)
{
	$sql = "SELECT account FROM ".TB_PREF."budget_master WHERE account=".db_escape($account)
	." AND tran_date='$date_' AND costcentre=".db_escape($costcentre);
	//	dimension_id=".db_escape($dimension)." AND dimension2_id=".db_escape($dimension2);
	$result = db_query($sql, "Cannot retreive a gl transaction");

    return (db_num_rows($result) > 0);
}

function add_update_gl_budget_master($date_, $account, $costcentre, $dimension, $dimension2, $amount)
{
	$date = date2sql($date_);

	if (exists_gl_budget($date, $account,$costcentre, $dimension, $dimension2))
		$sql = "UPDATE ".TB_PREF."budget_master SET amount=".db_escape($amount)
		." WHERE account=".db_escape($account)
		." AND dimension_id=".db_escape($dimension)
		." AND dimension2_id=".db_escape($dimension2)
		." AND tran_date='$date'";
	else
		$sql = "INSERT INTO ".TB_PREF."budget_master (counter,tran_date,
			account, costcentre, dimension_id, dimension2_id, amount, memo_) VALUES (BUDGET_TRANS_COUNTER_SEQ.NEXTVAL,'$date',
			".db_escape($account).", ".db_escape($costcentre).", ".db_escape($dimension).", "
			.db_escape($dimension2).", ".db_escape($amount).", ' ')";

	db_query($sql, "The GL budget transaction could not be saved");
}

function delete_gl_budget_master($date_, $account, $dimension, $dimension2)
{
	$date = date2sql($date_);

	$sql = "DELETE FROM ".TB_PREF."budget_master WHERE account=".db_escape($account)
	." AND dimension_id=".db_escape($dimension)
	." AND dimension2_id=".db_escape($dimension2)
	." AND tran_date='$date'";
	db_query($sql, "The GL budget transaction could not be deleted");
}

function get_only_budget_master_from_to($i, $from_date, $to_date, $account, $dimension=0, $dimension2=0)
{
//$amount, &$code,
if ($i > 0 ) return ; 
	$from = date2sql($from_date);
	$to = date2sql($to_date);

	$sql = "SELECT amount, costcentre FROM ".TB_PREF."budget_master
		WHERE account=".db_escape($account)
		." AND tran_date >= '$from' AND tran_date <= '$to'";
/*
		 AND dimension_id = ".db_escape($dimension)
		 ." AND dimension2_id = ".db_escape($dimension2)
*/
	$result = db_query($sql,"No budget accounts were returned");
    // display_notification ($sql);
	//$row = db_fetch($result);
  $j = 0 ;	
  while ($row = db_fetch($result))
  { //Start While
  	 	//return $row[0];
	$_POST['amount'.$j] = number_format2($row['amount'],0);
	$_POST['costcentre'.$j] = $row['costcentre'];
	$j++;
	//display_notification ($row[1]);
  } //END WHILE LIST LOOP
//set the rest of the variables to 0;
for ($k=$j; $k<20; $k++)
{
	$_POST['amount'.$k] = "";
	$_POST['costcentre'.$k] = "";
}

}

//-------------------------------------------------------------------------------------

if (isset($_POST['add']) || isset($_POST['delete']))
{
	begin_transaction();

 //for ($i = 0, $da = $_POST['begin']; date1_greater_date2($_POST['end'], $da); $i++)
  $da = $_POST['begin'];
  for ($i = 0, $count = 1; $count<=20; $i++)
	{  
	//if (!(is_null($_POST['costcentre'.$i]))  && ($_POST['costcentre'.$i]>='0') ) 
	if (!(is_null($_POST['costcentre'.$i])) && ($_POST['costcentre'.$i]>='0' || $_POST['costcentre'.$i] >= 'a' || $_POST['costcentre'.$i]>='A') ) 
		{if (isset($_POST['add']))
			add_update_gl_budget_master($da, $_POST['account'],$_POST['costcentre'.$i], $_POST['dim1'], $_POST['dim2'], input_num('amount'.$i));
		else
			delete_gl_budget_master($da, $_POST['account'], $_POST['dim1'], $_POST['dim2']);
		}
		//$da = add_months($da, 1);
		$count+=1;
	}
	commit_transaction();

	if (isset($_POST['add']))
		display_notification_centered(_("The Budget has been saved."));
	else
		display_notification_centered(_("The Budget has been deleted."));

	//meta_forward($_SERVER['PHP_SELF']);
	$Ajax->activate('budget_tbl');
}
if (isset($_POST['submit']) || isset($_POST['update']))
	$Ajax->activate('budget_tbl');

//-------------------------------------------------------------------------------------

start_form();

if (db_has_gl_accounts())
{
	$dim = get_company_pref('use_dimension');
	start_table($table_style2);
	fiscalyears_list_row(_("Fiscal Year:"), 'fyear', null);
	gl_all_accounts_list_row(_("Account Code:"), 'account', null);
//////////////////////////
//		ana_code_list_row("", 'costcentre',null,
//		_('Select cost centre code'), true);
//////////////////////////
//fixed dimension to zero. Not used
	$_POST['dim1'] = 0;
	$_POST['dim2'] = 0;
    hidden('dim1', 0);
	hidden('dim2', 0);

/*		
	if (!isset($_POST['dim1']))
		$_POST['dim1'] = 0;
	if (!isset($_POST['dim2']))
		$_POST['dim2'] = 0;
    if ($dim == 2)
    {
		dimensions_list_row(_("Dimension")." 1", 'dim1', $_POST['dim1'], true, null, false, 1);
		dimensions_list_row(_("Dimension")." 2", 'dim2', $_POST['dim2'], true, null, false, 2);
	}
	else if ($dim == 1)
	{
		dimensions_list_row(_("Dimension"), 'dim1', $_POST['dim1'], true, null, false, 1);
		hidden('dim2', 0);
	}
	else
	{
		hidden('dim1', 0);
		hidden('dim2', 0);
	}
*/	
	submit_row('submit', _("Get"), true, '', '', true);
	end_table(1);
	div_start('budget_tbl');
	start_table($table_style2);
	$showdims = (($dim == 1 && $_POST['dim1'] == 0) ||
		($dim == 2 && $_POST['dim1'] == 0 && $_POST['dim2'] == 0));
	if ($showdims)
//		$th = array(_("S/N"), _("Cost Centre"),_("Amount"), _("Dim. incl."), _("Last Year"));
		$th = array(_("S/N"), _("Cost Centre"),_("Amount"));
	else
//		$th = array(_("S/N"), _("Cost Centre"),  _("Amount"), _("Last Year"));
		$th = array(_("S/N"), _("Cost Centre"),  _("Amount"));
	table_header($th);
	$year = $_POST['fyear'];
	if (get_post('update') == '') {
		$sql = "SELECT * FROM ".TB_PREF."fiscal_year WHERE id=".db_escape($year);

		$result = db_query($sql, "could not get current fiscal year");

		$fyear = db_fetch($result);
		$_POST['begin'] = sql2date($fyear['begin']);
		$_POST['end'] = sql2date($fyear['end']);
	}
	hidden('begin');
	hidden('end');
	$total = $btotal = $ltotal = 0;
	//for ($i = 0, $date_ = $_POST['begin']; date1_greater_date2($_POST['end'], $date_); $i++)
    $date_ = $_POST['begin'];
	for ($i = 0, $count = 1; $count<=20; $i++)
	{
		start_row();
		
		//if (get_post('update') == '') 
		if (isset($_POST['submit']))
		    get_only_budget_master_from_to($i, $date_, $date_, $_POST['account'], $_POST['dim1'], $_POST['dim2']);

		label_cell(_($i+1).".");
		ana_code_list_cells("", 'costcentre'.$i,null,
		_('Select cost centre code'), true);
			
		amount_cells(null, 'amount'.$i, 0);
		$total += input_num('amount'.$i);
	    $count+=1;
		end_row();
	}
	start_row();
	label_cell("<b>"._("")."</b>");
	label_cell("<b>"._("Total")."</b>");
	label_cell(number_format2($total, 0), 'align=right style="font-weight:bold"', 'total');
	end_row();
	end_table(1);
	div_end();
	submit_center_first('update', _("Update"), '', null);
	submit('add', _("Save"), true, '', 'default');
	submit_center_last('delete', _("Delete"), '', true);
}
end_form();

end_page();

?>
