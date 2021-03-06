<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_CUSTPAYMREP';

// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Joe Hunt
// date_:	2005-05-19
// Title:	Customer Balances
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");

//----------------------------------------------------------------------------------------------------

// trial_inquiry_controls();
print_customer_balances();

function get_open_balance($debtorno, $to, $convert)
{
	$to = date2sql($to);

    $sql = "SELECT SUM(IF(".TB_PREF."debtor_trans.type = ".ST_SALESINVOICE.", (".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + 
    	".TB_PREF."debtor_trans.ov_freight + ".TB_PREF."debtor_trans.ov_freight_tax + ".TB_PREF."debtor_trans.ov_discount)";
    if ($convert)
    	$sql .= " * rate";
    $sql .= ", 0)) AS charges,
    	SUM(IF(".TB_PREF."debtor_trans.type <> ".ST_SALESINVOICE.", (".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + 
    	".TB_PREF."debtor_trans.ov_freight + ".TB_PREF."debtor_trans.ov_freight_tax + ".TB_PREF."debtor_trans.ov_discount)";
    if ($convert)
    	$sql .= " * rate";
    $sql .= " * -1, 0)) AS credits,
		SUM(".TB_PREF."debtor_trans.alloc";
	if ($convert)
		$sql .= " * rate";
	$sql .= ") AS Allocated,
		SUM(IF(".TB_PREF."debtor_trans.type = ".ST_SALESINVOICE.", (".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + 
    	".TB_PREF."debtor_trans.ov_freight + ".TB_PREF."debtor_trans.ov_freight_tax + ".TB_PREF."debtor_trans.ov_discount - ".TB_PREF."debtor_trans.alloc)";
    if ($convert)
    	$sql .= " * rate";
    $sql .= ", 
    	((".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + ".TB_PREF."debtor_trans.ov_freight + 
    	".TB_PREF."debtor_trans.ov_freight_tax + ".TB_PREF."debtor_trans.ov_discount) * -1 + ".TB_PREF."debtor_trans.alloc)";
    if ($convert)
    	$sql .= " * rate";
    $sql .= ")) AS OutStanding
		FROM ".TB_PREF."debtor_trans
    	WHERE ".TB_PREF."debtor_trans.tran_date < '$to'
		AND ".TB_PREF."debtor_trans.debtor_no = ".db_escape($debtorno)."
		AND ".TB_PREF."debtor_trans.type <> ".ST_CUSTDELIVERY." GROUP BY debtor_no";

    $result = db_query($sql,"No transactions were returned");
    return db_fetch($result);
}

function get_transactions($debtorno, $from, $to)
{
	$from = date2sql($from);
	$to = date2sql($to);

    $sql = "SELECT ".TB_PREF."debtor_trans.*,
		(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + ".TB_PREF."debtor_trans.ov_freight + 
		".TB_PREF."debtor_trans.ov_freight_tax + ".TB_PREF."debtor_trans.ov_discount)
		AS TotalAmount, ".TB_PREF."debtor_trans.alloc AS Allocated,
		case when ".TB_PREF."debtor_trans.type = ".ST_SALESINVOICE."
		AND ".TB_PREF."debtor_trans.due_date < to_date('$to','yyyy-mm-dd') then 1 else 0 end AS OverDue
    	FROM ".TB_PREF."debtor_trans
    	WHERE ".TB_PREF."debtor_trans.tran_date >= to_date('$from','yyyy-mm-dd') 
		AND ".TB_PREF."debtor_trans.tran_date <= to_date('$to','yyyy-mm-dd')
		AND ".TB_PREF."debtor_trans.debtor_no = ".db_escape($debtorno)."
		AND ".TB_PREF."debtor_trans.type <> ".ST_CUSTDELIVERY."
    	ORDER BY ".TB_PREF."debtor_trans.tran_date";

    return db_query($sql,"No transactions were returned");
}

//----------------------------------------------------------------------------------------------------

function print_customer_balances()
{
    global $path_to_root, $systypes_array;

    $from = $_POST['PARAM_0'];
    $to = $_POST['PARAM_1'];
    $fromcust = $_POST['PARAM_2'];
    $currency = $_POST['PARAM_3'];
    $comments = $_POST['PARAM_4'];
	$destination = $_POST['PARAM_5'];
	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");

	if ($fromcust == ALL_NUMERIC)
		$cust = _('All');
	else
		$cust = get_customer_name($fromcust);
    $dec = user_price_dec();

	if ($currency == ALL_TEXT)
	{
		$convert = true;
		$currency = _('Balances in Home Currency');
	}
	else
		$convert = false;

	$cols = array(0, 100, 130, 190,	250, 320, 385, 450,	515);

	$headers = array(_('Trans Type'), _('#'), _('Date'), _('Due Date'), _('Charges'), _('Credits'),
		_('Allocated'), 	_('Outstanding'));

	$aligns = array('left',	'left',	'left',	'left',	'right', 'right', 'right', 'right');

    $params =   array( 	0 => $comments,
    				    1 => array('text' => _('Period'), 'from' => $from, 		'to' => $to),
    				    2 => array('text' => _('Customer'), 'from' => $cust,   	'to' => ''),
    				    3 => array('text' => _('Currency'), 'from' => $currency, 'to' => ''));

    $rep = new FrontReport(_('Customer Balances'), "CustomerBalances", user_pagesize());

    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->Header();

	$grandtotal = array(0,0,0,0);

	$sql = "SELECT debtor_no, name, curr_code FROM ".TB_PREF."debtors_master ";
	if ($fromcust != ALL_NUMERIC)
		$sql .= "WHERE debtor_no=".db_escape($fromcust);
	$sql .= " ORDER BY name";
	$result = db_query($sql, "The customers could not be retrieved");

	while ($myrow = db_fetch($result))
	{
		if (!$convert && $currency != $myrow['curr_code'])
			continue;
		$rep->fontSize += 2;
		$rep->TextCol(0, 2, $myrow['name']);
		if ($convert)
			$rep->TextCol(2, 3,	$myrow['curr_code']);
		$rep->fontSize -= 2;
		$bal = get_open_balance($myrow['debtor_no'], $from, $convert);
		$init[0] = $init[1] = 0.0;
		$rep->TextCol(3, 4,	_("Open Balance"));
		$init[0] = round2(abs($bal['charges']), $dec);
		$rep->AmountCol(4, 5, $init[0], $dec);
		$init[1] = round2(Abs($bal['credits']), $dec);
		$rep->AmountCol(5, 6, $init[1], $dec);
		$init[2] = round2($bal['Allocated'], $dec);
		$rep->AmountCol(6, 7, $init[2], $dec);
		$init[3] = round2($bal['OutStanding'], $dec);;
		$rep->AmountCol(7, 8, $init[3], $dec);
		$total = array(0,0,0,0);
		for ($i = 0; $i < 4; $i++)
		{
			$total[$i] += $init[$i];
			$grandtotal[$i] += $init[$i];
		}
		$rep->NewLine(1, 2);
		$res = get_transactions($myrow['debtor_no'], $from, $to);
		if (db_num_rows($res)==0)
			continue;
		$rep->Line($rep->row + 4);
		while ($trans = db_fetch($res))
		{
			$rep->NewLine(1, 2);
			$rep->TextCol(0, 1, $systypes_array[$trans['type']]);
			$rep->TextCol(1, 2,	$trans['reference']);
			$rep->DateCol(2, 3,	$trans['tran_date'], true);
			if ($trans['type'] == ST_SALESINVOICE)
				$rep->DateCol(3, 4,	$trans['due_date'], true);
			$item[0] = $item[1] = 0.0;
			if ($convert)
				$rate = $trans['rate'];
			else
				$rate = 1.0;
			if ($trans['type'] == ST_CUSTCREDIT || $trans['type'] == ST_CUSTPAYMENT || $trans['type'] == ST_BANKDEPOSIT)
				$trans['TotalAmount'] *= -1;
			if ($trans['TotalAmount'] > 0.0)
			{
				$item[0] = round2(abs($trans['TotalAmount']) * $rate, $dec);
				$rep->AmountCol(4, 5, $item[0], $dec);
			}
			else
			{
				$item[1] = round2(Abs($trans['TotalAmount']) * $rate, $dec);
				$rep->AmountCol(5, 6, $item[1], $dec);
			}
			$item[2] = round2($trans['Allocated'] * $rate, $dec);
			$rep->AmountCol(6, 7, $item[2], $dec);
			/*
			if ($trans['type'] == 10)
				$item[3] = ($trans['TotalAmount'] - $trans['Allocated']) * $rate;
			else
				$item[3] = ($trans['TotalAmount'] + $trans['Allocated']) * $rate;
			*/
			if ($trans['type'] == ST_SALESINVOICE || $trans['type'] == ST_BANKPAYMENT)
				$item[3] = $item[0] + $item[1] - $item[2];
			else	
				$item[3] = $item[0] - $item[1] + $item[2];
			$rep->AmountCol(7, 8, $item[3], $dec);
			for ($i = 0; $i < 4; $i++)
			{
				$total[$i] += $item[$i];
				$grandtotal[$i] += $item[$i];
			}
		}
		$rep->Line($rep->row - 8);
		$rep->NewLine(2);
		$rep->TextCol(0, 3, _('total'));
		for ($i = 0; $i < 4; $i++)
			$rep->AmountCol($i + 4, $i + 5, $total[$i], $dec);
    	$rep->Line($rep->row  - 4);
    	$rep->NewLine(2);
	}
	$rep->fontSize += 2;
	$rep->TextCol(0, 3, _('Grand Total'));
	$rep->fontSize -= 2;
	for ($i = 0; $i < 4; $i++)
		$rep->AmountCol($i + 4, $i + 5, $grandtotal[$i], $dec);
	$rep->Line($rep->row  - 4);
	$rep->NewLine();
    $rep->End();
}

?>