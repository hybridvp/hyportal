<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SALESANALYTIC';
// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Joe Hunt
// date_:	2005-05-19
// Title:	Inventory Sales Report
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/includes/banking.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_category_db.inc");

//----------------------------------------------------------------------------------------------------

print_inventory_sales();

function getTransactions($category, $location, $fromcust, $from, $to)
{
//(".TB_PREF."stock_master.material_cost + ".TB_PREF."stock_master.labour_cost + ".TB_PREF."stock_master.overhead_cost)) AS cost
	$from = date2sql($from);
	$to = date2sql($to);
	$sql = "SELECT ".TB_PREF."stock_master.category_id,
			".TB_PREF."stock_category.description AS cat_description,
			".TB_PREF."stock_master.stock_id,
			".TB_PREF."stock_master.description,
			".TB_PREF."pin_mailer_jobs.sold_from_loc,
			".TB_PREF."debtor_trans.debtor_no,
			".TB_PREF."debtors_master.name AS debtor_name,
			trunc(".TB_PREF."pin_mailer_jobs.logged_date) logged_date,
			SUM(".TB_PREF."pin_mailer_jobs.quantity) AS qty,
			SUM(".TB_PREF."pin_mailer_jobs.quantity*".TB_PREF."pin_mailer_jobs.denomination) AS amt,
			SUM(".TB_PREF."pin_mailer_jobs.quantity * 0) AS cost
		FROM ".TB_PREF."stock_master,
			".TB_PREF."stock_category,
			".TB_PREF."debtor_trans,
			".TB_PREF."debtors_master,
			".TB_PREF."pin_mailer_jobs
		WHERE ".TB_PREF."stock_master.stock_id=".TB_PREF."pin_mailer_jobs.stock_id
		AND ".TB_PREF."stock_master.category_id=".TB_PREF."stock_category.category_id
		AND ".TB_PREF."debtor_trans.debtor_no=".TB_PREF."debtors_master.debtor_no ";
		//AND ".TB_PREF."pin_mailer_jobs.type=".TB_PREF."debtor_trans.type
		$sql.=" AND ".TB_PREF."pin_mailer_jobs.order_no=".TB_PREF."debtor_trans.order_
		AND ".TB_PREF."pin_mailer_jobs.logged_date>= to_date('$from','yyyy-mm-dd')
		AND ".TB_PREF."pin_mailer_jobs.logged_date<= to_date('$to','yyyy-mm-dd')

		AND (".TB_PREF."stock_master.mb_flag='B' OR ".TB_PREF."stock_master.mb_flag='M')";
		if ($category != 0)
			$sql .= " AND ".TB_PREF."stock_master.category_id = ".db_escape($category);
		if ($location != 'all')
			$sql .= " AND ".TB_PREF."pin_mailer_jobs.sold_from_loc = ".db_escape($location);
		if ($fromcust != -1)
			$sql .= " AND ".TB_PREF."debtors_master.debtor_no = ".db_escape($fromcust);
		$sql .= " GROUP BY "
		.TB_PREF."stock_master.category_id, "
		.TB_PREF."stock_category.description, "
		.TB_PREF."stock_master.stock_id, "
		.TB_PREF."stock_master.description, "
		.TB_PREF."pin_mailer_jobs.sold_from_loc, "
		.TB_PREF."debtor_trans.debtor_no, "
		.TB_PREF."debtors_master.name ,
		trunc( ".TB_PREF."pin_mailer_jobs.logged_date )
		ORDER BY ".TB_PREF."stock_master.category_id,
			".TB_PREF."stock_master.stock_id, ".TB_PREF."debtors_master.name";
    return db_query($sql,"No transactions were returned");
//
//AND ((".TB_PREF."debtor_trans.type=".ST_CUSTDELIVERY." AND ".TB_PREF."debtor_trans.version=1) OR ".TB_PREF."stock_moves.type=".ST_CUSTCREDIT.")

}

//----------------------------------------------------------------------------------------------------

function print_inventory_sales()
{
    global $path_to_root;

	$from = $_POST['PARAM_0'];
	$to = $_POST['PARAM_1'];
    $category = $_POST['PARAM_2'];
    $location = $_POST['PARAM_3'];
    $fromcust = $_POST['PARAM_4'];
	$comments = $_POST['PARAM_5'];
	$destination = $_POST['PARAM_6'];
	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");

    $dec = user_price_dec();

	if ($category == ALL_NUMERIC)
		$category = 0;
	if ($category == 0)
		$cat = _('All');
	else
		$cat = get_category_name($category);

	if ($location == ALL_TEXT)
		$location = 'all';
	if ($location == 'all')
		$loc = _('All');
	else
		$loc = get_location_name($location);

	if ($fromcust == ALL_NUMERIC)
		$fromc = _('All');
	else
		$fromc = get_customer_name($fromcust);

	$cols = array(0, 75, 175, 250, 300, 375, 450,	515);

	$headers = array(_('Category'), _('Description'), _('Customer'), _('Qty'), _('Sales'), _('Cost'), _('-'));
	if ($fromcust != ALL_NUMERIC)
		$headers[2] = '';	

	$aligns = array('left',	'left',	'left', 'right', 'right', 'right', 'right');

    $params =   array( 	0 => $comments,
    				    1 => array('text' => _('Period'),'from' => $from, 'to' => $to),
    				    2 => array('text' => _('Category'), 'from' => $cat, 'to' => ''),
    				    3 => array('text' => _('Location'), 'from' => $loc, 'to' => ''),
    				    4 => array('text' => _('Customer'), 'from' => $fromc, 'to' => ''));

    $rep = new FrontReport(_('Inventory Sales Report'), "InventorySalesReport", user_pagesize());

    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->Header();

	$res = getTransactions($category, $location, $fromcust, $from, $to);
	$total = $grandtotal = 0.0;
	$total1 = $grandtotal1 = 0.0;
	$total2 = $grandtotal2 = 0.0;
	$catt = '';
	while ($trans=db_fetch($res))
	{
		if ($catt != $trans['cat_description'])
		{
			if ($catt != '')
			{
				$rep->NewLine(2, 3);
				$rep->TextCol(0, 4, _('total'));
				$rep->AmountCol(4, 5, $total, $dec);
				$rep->AmountCol(5, 6, $total1, $dec);
				$rep->AmountCol(6, 7, $total2, $dec);
				$rep->Line($rep->row - 2);
				$rep->NewLine();
				$rep->NewLine();
				$total = $total1 = $total2 = 0.0;
			}
			$rep->TextCol(0, 1, $trans['category_id']);
			$rep->TextCol(1, 6, $trans['cat_description']);
			$catt = $trans['cat_description'];
			$rep->NewLine();
		}

		$curr = get_customer_currency($trans['debtor_no']);
		$rate = get_exchange_rate_from_home_currency($curr, sql2date($trans['logged_date']));
		$trans['amt'] *= $rate;
		$cb = $trans['amt'] - $trans['cost'];
		$rep->NewLine();
		$rep->fontsize -= 2;
		$rep->TextCol(0, 1, $trans['stock_id']);
		if ($fromcust == ALL_NUMERIC)
		{
			$rep->TextCol(1, 2, $trans['description']);
			$rep->TextCol(2, 3, $trans['debtor_name']);
		}
		else
			$rep->TextCol(1, 3, $trans['description']);
		$rep->AmountCol(3, 4, $trans['qty'], get_qty_dec($trans['stock_id']));
		$rep->AmountCol(4, 5, $trans['amt'], $dec);
		$rep->AmountCol(5, 6, $trans['cost'], $dec);
		$rep->AmountCol(6, 7, $cb, $dec);
		$rep->fontsize += 2;
		$total += $trans['amt'];
		$total1 += $trans['cost'];
		$total2 += $cb;
		$grandtotal += $trans['amt'];
		$grandtotal1 += $trans['cost'];
		$grandtotal2 += $cb;
	}
	$rep->NewLine(2, 3);
	$rep->TextCol(0, 4, _('total'));
	$rep->AmountCol(4, 5, $total, $dec);
	$rep->AmountCol(5, 6, $total1, $dec);
	$rep->AmountCol(6, 7, $total2, $dec);
	$rep->Line($rep->row - 2);
	$rep->NewLine();
	$rep->NewLine(2, 1);
	$rep->TextCol(0, 4, _('Grand Total'));
	$rep->AmountCol(4, 5, $grandtotal, $dec);
	$rep->AmountCol(5, 6, $grandtotal1, $dec);
	$rep->AmountCol(6, 7, $grandtotal2, $dec);

	$rep->Line($rep->row  - 4);
	$rep->NewLine();
    $rep->End();
}

?>