<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
include_once($path_to_root . "/includes/ui.inc");

//--------------------------------------------------------------------------------------

function display_dimension_balance($id, $from, $to)
{
	global $path_to_root, $table_style;

	$from = date2sql($from);
	$to = date2sql($to);
	$sql = "SELECT account, ".TB_PREF."chart_master.account_name, sum(amount) AS amt FROM
		".TB_PREF."gl_trans,".TB_PREF."chart_master WHERE
		".TB_PREF."gl_trans.account = ".TB_PREF."chart_master.account_code AND
		(dimension_id = $id OR dimension2_id = $id) AND
		tran_date >= '$from' AND tran_date <= '$to' GROUP BY account";
	$result = db_query($sql, "Transactions could not be calculated");

    if (db_num_rows($result) == 0)
    {
    	display_note(_("There are no transactions for this dimension for the selected period."));
    }
    else
    {
		display_heading2(_("Balance for this Dimension"));
		br();
		start_table($table_style);
		$th = array(_("Account"), _("Debit"), _("Credit"));
		table_header($th);

        $total = $k = 0;
        while ($myrow = db_fetch($result))
        {
			alt_table_row_color($k);

			label_cell($myrow["account"]." ".$myrow['account_name']);
			display_debit_or_credit_cells($myrow["amt"]);
			$total += $myrow["amt"];
			end_row();
		}
		start_row();
		label_cell("<b>"._("Balance")."</b>");
		if ($total >= 0)
		{
			amount_cell($total, true);
			label_cell("");
		}
		else
		{
			label_cell("");
			amount_cell(abs($total), true);
		}
		end_row();

		end_table();
    }
}

//--------------------------------------------------------------------------------------

?>