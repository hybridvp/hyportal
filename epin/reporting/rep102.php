<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_CUSTPAYMREP';
// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Joe Hunt
// date_:	2005-05-19
// Title:	Aged Customer balances
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");

//----------------------------------------------------------------------------------------------------

print_aged_customer_analysis();

function get_invoices($customer_id, $to)
{
	$todate = date2sql($to);
	$PastdueDays1 = get_company_pref('past_due_days');
	$PastdueDays2 = 2 * $PastdueDays1;

	// Revomed allocated from sql
    $value = "(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + "
		.TB_PREF."debtor_trans.ov_freight + ".TB_PREF."debtor_trans.ov_freight_tax + "
		.TB_PREF."debtor_trans.ov_discount)";
	$due = "decode (".TB_PREF."debtor_trans.type,".ST_SALESINVOICE.",".TB_PREF."debtor_trans.due_date,".TB_PREF."debtor_trans.tran_date)";
	$sql = "SELECT ".TB_PREF."debtor_trans.type, ".TB_PREF."debtor_trans.reference,
		".TB_PREF."debtor_trans.tran_date,
		$value as balance,
		decode (sign('$todate' - $due), 1,$value,0) AS due,
		decode (sign($PastdueDays1 - ('$todate' - $due)), -1,$value,0) AS overdue1,
		decode (sign($PastdueDays2 - ('$todate' - $due)), -1,$value,0) AS overdue2

		FROM ".TB_PREF."debtors_master,
			".TB_PREF."payment_terms,
			".TB_PREF."debtor_trans

		WHERE ".TB_PREF."debtor_trans.type <> ".ST_CUSTDELIVERY."
			AND ".TB_PREF."debtors_master.payment_terms = ".TB_PREF."payment_terms.terms_indicator
			AND ".TB_PREF."debtors_master.debtor_no = ".TB_PREF."debtor_trans.debtor_no
			AND ".TB_PREF."debtor_trans.debtor_no = ".db_escape($customer_id)."
			AND ".TB_PREF."debtor_trans.tran_date <= '$todate'
			AND ABS(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst 
					+ ".TB_PREF."debtor_trans.ov_freight + ".TB_PREF."debtor_trans.ov_freight_tax 
					+ ".TB_PREF."debtor_trans.ov_discount) > 0.004
			ORDER BY ".TB_PREF."debtor_trans.tran_date";

	return db_query($sql, "The customer details could not be retrieved");
}

//----------------------------------------------------------------------------------------------------

function print_aged_customer_analysis()
{
    global $comp_path, $path_to_root, $systypes_array;

    $to = $_POST['PARAM_0'];
    $fromcust = $_POST['PARAM_1'];
    $currency = $_POST['PARAM_2'];
	$summaryOnly = $_POST['PARAM_3'];
    $graphics = $_POST['PARAM_4'];
    $comments = $_POST['PARAM_5'];
	$destination = $_POST['PARAM_6'];
	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");
	if ($graphics)
	{
		include_once($path_to_root . "/reporting/includes/class.graphic.inc");
		$pg = new graph();
	}

	if ($fromcust == ALL_NUMERIC)
		$from = _('All');
	else
		$from = get_customer_name($fromcust);
    $dec = user_price_dec();

	if ($summaryOnly == 1)
		$summary = _('Summary Only');
	else
		$summary = _('Detailed Report');
	if ($currency == ALL_TEXT)
	{
		$convert = true;
		$currency = _('Balances in Home Currency');
	}
	else
		$convert = false;

	$PastdueDays1 = get_company_pref('past_due_days');
	$PastdueDays2 = 2 * $PastdueDays1;
	$nowdue = "1-" . $PastdueDays1 . " " . _('Days');
	$pastdue1 = $PastdueDays1 + 1 . "-" . $PastdueDays2 . " " . _('Days');
	$pastdue2 = _('Over') . " " . $PastdueDays2 . " " . _('Days');

	$cols = array(0, 100, 130, 190,	250, 320, 385, 450,	515);
	$headers = array(_('Customer'),	'',	'',	_('Current'), $nowdue, $pastdue1, $pastdue2,
		_('Total Balance'));

	$aligns = array('left',	'left',	'left',	'right', 'right', 'right', 'right',	'right');

    $params =   array( 	0 => $comments,
    					1 => array('text' => _('End Date'), 'from' => $to, 'to' => ''),
    				    2 => array('text' => _('Customer'),	'from' => $from, 'to' => ''),
    				    3 => array('text' => _('Currency'), 'from' => $currency, 'to' => ''),
                    	4 => array('text' => _('Type'),		'from' => $summary,'to' => ''));

	if ($convert)
		$headers[2] = _('Currency');
    $rep = new FrontReport(_('Aged Customer Analysis'), "AgedCustomerAnalysis", user_pagesize());

    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->Header();

	$total = array(0,0,0,0, 0);

	$sql = "SELECT debtor_no, name, curr_code FROM ".TB_PREF."debtors_master";
	if ($fromcust != ALL_NUMERIC)
		$sql .= " WHERE debtor_no=".db_escape($fromcust);
	$sql .= " ORDER BY name";
	$result = db_query($sql, "The customers could not be retrieved");

	while ($myrow=db_fetch($result))
	{
		if (!$convert && $currency != $myrow['curr_code'])
			continue;
		$rep->fontSize += 2;
		$rep->TextCol(0, 2, $myrow['name']);
		if ($convert)
		{
			$rate = get_exchange_rate_from_home_currency($myrow['curr_code'], $to);
			$rep->TextCol(2, 3,	$myrow['curr_code']);
		}
		else
			$rate = 1.0;
		$rep->fontSize -= 2;
		$custrec = get_customer_details($myrow['debtor_no'], $to);
		foreach ($custrec as $i => $value)
			$custrec[$i] *= $rate;
		$total[0] += ($custrec["balance"] - $custrec["due"]);
		$total[1] += ($custrec["due"]-$custrec["overdue1"]);
		$total[2] += ($custrec["overdue1"]-$custrec["overdue2"]);
		$total[3] += $custrec["overdue2"];
		$total[4] += $custrec["balance"];
		$str = array($custrec["balance"] - $custrec["due"],
			$custrec["due"]-$custrec["overdue1"],
			$custrec["overdue1"]-$custrec["overdue2"],
			$custrec["overdue2"],
			$custrec["balance"]);
		for ($i = 0; $i < count($str); $i++)
			$rep->AmountCol($i + 3, $i + 4, $str[$i], $dec);
		$rep->NewLine(1, 2);
		if (!$summaryOnly)
		{
			$res = get_invoices($myrow['debtor_no'], $to);
    		if (db_num_rows($res)==0)
				continue;
    		$rep->Line($rep->row + 4);
			while ($trans=db_fetch($res))
			{
				$rep->NewLine(1, 2);
        		$rep->TextCol(0, 1, $systypes_array[$trans['type']], -2);
				$rep->TextCol(1, 2,	$trans['reference'], -2);
				$rep->DateCol(2, 3, $trans['tran_date'], true, -2);
				if ($trans['type'] == ST_CUSTCREDIT || $trans['type'] == ST_CUSTPAYMENT || $trans['type'] == ST_BANKDEPOSIT)
				{
					$trans['balance'] *= -1;
					$trans['due'] *= -1;
					$trans['overdue1'] *= -1;
					$trans['overdue2'] *= -1;
				}
				foreach ($trans as $i => $value)
					$trans[$i] *= $rate;
				$str = array($trans["balance"] - $trans["due"],
					$trans["due"]-$trans["overdue1"],
					$trans["overdue1"]-$trans["overdue2"],
					$trans["overdue2"],
					$trans["balance"]);
				for ($i = 0; $i < count($str); $i++)
					$rep->AmountCol($i + 3, $i + 4, $str[$i], $dec);
			}
			$rep->Line($rep->row - 8);
			$rep->NewLine(2);
		}
	}
	if ($summaryOnly)
	{
    	$rep->Line($rep->row  + 4);
    	$rep->NewLine();
	}
	$rep->fontSize += 2;
	$rep->TextCol(0, 3, _('Grand Total'));
	$rep->fontSize -= 2;
	for ($i = 0; $i < count($total); $i++)
	{
		$rep->AmountCol($i + 3, $i + 4, $total[$i], $dec);
		if ($graphics && $i < count($total) - 1)
		{
			$pg->y[$i] = abs($total[$i]);
		}
	}
   	$rep->Line($rep->row - 8);
   	if ($graphics)
   	{
   		global $decseps, $graph_skin;
		$pg->x = array(_('Current'), $nowdue, $pastdue1, $pastdue2);
		$pg->title     = $rep->title;
		$pg->axis_x    = _("Days");
		$pg->axis_y    = _("Amount");
		$pg->graphic_1 = $to;
		$pg->type      = $graphics;
		$pg->skin      = $graph_skin;
		$pg->built_in  = false;
		$pg->fontfile  = $path_to_root . "/reporting/fonts/Vera.ttf";
		$pg->latin_notation = ($decseps[$_SESSION["wa_current_user"]->prefs->dec_sep()] != ".");
		$filename = $comp_path .'/'. user_company(). "/images/test.png";
		$pg->display($filename, true);
		$w = $pg->width / 1.5;
		$h = $pg->height / 1.5;
		$x = ($rep->pageWidth - $w) / 2;
		$rep->NewLine(2);
		if ($rep->row - $h < $rep->bottomMargin)
			$rep->Header();
		$rep->AddImage($filename, $x, $rep->row - $h, $w, $h);
	}
	$rep->NewLine();
    $rep->End();
}

?>