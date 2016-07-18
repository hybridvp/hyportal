<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_ITEMSVALREP';
// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Joe Hunt
// date_:	2005-05-19
// Title:	Inventory Valuation
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_category_db.inc");

//----------------------------------------------------------------------------------------------------

print_inventory_valuation_report();

function getTransactions($category, $location)
{
//SUM(denomination/denomination)


//".TB_PREF."stock_master.material_cost + ".TB_PREF."stock_master.labour_cost + ".TB_PREF."stock_master.overhead_cost AS unitcost,
	$sql = "SELECT ".TB_PREF."stock_master.category_id,
			".TB_PREF."stock_categry.description AS cat_description,
			".TB_PREF."stock_master.stock_id,
			".TB_PREF."stock_master.description,
	  		".TB_PREF."pin_details.location),
			SUM(denomination/denomination) AS QtyOnHand,
			".TB_PREF."stock_master.facevalue AS unitcost,
			SUM(denomination/denomination) * (".TB_PREF."stock_master.facevalue) As ItemTotal
		FROM ".TB_PREF."stock_master,
			".TB_PREF."stock_category,
			".TB_PREF."pin_details
		WHERE ".TB_PREF."stock_master.stock_id=".TB_PREF."pin_details.stock_id
		AND ".TB_PREF."stock_master.category_id=".TB_PREF."stock_category.category_id
		GROUP BY ".TB_PREF."stock_master.category_id,
			".TB_PREF."stock_category.description, ";
		if ($location != 'all')
			$sql .= .TB_PREF."pin_details.location), ";
		$sql .= "stock_master.facevalue,
			".TB_PREF."stock_master.stock_id,
			".TB_PREF."stock_master.description,
			".TB_PREF."pin_details.denomination
		HAVING SUM(".TB_PREF."denomination/denomination) != 0";
		if ($category != 0)
			$sql .= " AND ".TB_PREF."stock_master.category_id = ".db_escape($category);
		if ($location != 'all')
			$sql .= " AND ".TB_PREF."pin_details.location = ".db_escape($location);
		$sql .= " ORDER BY ".TB_PREF."stock_master.category_id,
			".TB_PREF."stock_master.stock_id";
//echo $sql;
    return db_query($sql,"No transactions were returned");
	
}
/* function getTransactions_($category, $location)
{
	$sql = "SELECT ".TB_PREF."stock_master.category_id,
			".TB_PREF."stock_category.description AS cat_description,
			".TB_PREF."stock_master.stock_id,
			".TB_PREF."stock_master.description,
			".TB_PREF."stock_moves.loc_code,
			SUM(".TB_PREF."stock_moves.qty) AS QtyOnHand,
			".TB_PREF."stock_master.material_cost + ".TB_PREF."stock_master.labour_cost + ".TB_PREF."stock_master.overhead_cost AS unitcost,
			SUM(".TB_PREF."stock_moves.qty) *(".TB_PREF."stock_master.material_cost + ".TB_PREF."stock_master.labour_cost + ".TB_PREF."stock_master.overhead_cost) AS ItemTotal
		FROM ".TB_PREF."stock_master,
			".TB_PREF."stock_category,
			".TB_PREF."stock_moves
		WHERE ".TB_PREF."stock_master.stock_id=".TB_PREF."stock_moves.stock_id
		AND ".TB_PREF."stock_master.category_id=".TB_PREF."stock_category.category_id
		GROUP BY ".TB_PREF."stock_master.category_id,
			".TB_PREF."stock_category.description, ";
		if ($location != 'all')
			$sql .= TB_PREF."stock_moves.loc_code, ";
		$sql .= "UnitCost,
			".TB_PREF."stock_master.stock_id,
			".TB_PREF."stock_master.description
		HAVING SUM(".TB_PREF."stock_moves.qty) != 0";
		if ($category != 0)
			$sql .= " AND ".TB_PREF."stock_master.category_id = ".db_escape($category);
		if ($location != 'all')
			$sql .= " AND ".TB_PREF."stock_moves.loc_code = ".db_escape($location);
		$sql .= " ORDER BY ".TB_PREF."stock_master.category_id,
			".TB_PREF."stock_master.stock_id";

    return db_query($sql,"No transactions were returned");
} */

//----------------------------------------------------------------------------------------------------

function print_inventory_valuation_report()
{
    global $path_to_root;

    $category = $_POST['PARAM_0'];
    $location = $_POST['PARAM_1'];
    $detail = $_POST['PARAM_2'];
    $comments = $_POST['PARAM_3'];
	$destination = $_POST['PARAM_4'];
	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");
	$detail = !$detail;
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
		$loc = $location;

	$cols = array(0, 100, 250, 350, 450,	515);

	$headers = array(_('Category'), '', _('Quantity'), _('Unit Cost'), _('Value'));

	$aligns = array('left',	'left',	'right', 'right', 'right');

    $params =   array( 	0 => $comments,
    				    1 => array('text' => _('Category'), 'from' => $cat, 'to' => ''),
    				    2 => array('text' => _('Location'), 'from' => $loc, 'to' => ''));

    $rep = new FrontReport(_('Inventory Valuation Report'), "InventoryValReport", user_pagesize());

    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->Header();

	$res = getTransactions($category, $location);
	$total = $grandtotal = 0.0;
	$catt = '';
	while ($trans=db_fetch($res))
	{
		if ($catt != $trans['cat_description'])
		{
			if ($catt != '')
			{
				if ($detail)
				{
					$rep->NewLine(2, 3);
					$rep->TextCol(0, 4, _('total'));
				}
				$rep->AmountCol(4, 5, $total, $dec);
				if ($detail)
				{
					$rep->Line($rep->row - 2);
					$rep->NewLine();
				}
				$rep->NewLine();
				$total = 0.0;
			}
			$rep->TextCol(0, 1, $trans['category_id']);
			$rep->TextCol(1, 2, $trans['cat_description']);
			$catt = $trans['cat_description'];
			if ($detail)
				$rep->NewLine();
		}
		if ($detail)
		{
			$rep->NewLine();
			$rep->fontsize -= 2;
			$rep->TextCol(0, 1, $trans['stock_id']);
			$rep->TextCol(1, 2, $trans['description']);
			$rep->AmountCol(2, 3, $trans['qtyonhand'], get_qty_dec($trans['stock_id']));
			$rep->AmountCol(3, 4, $trans['unitcost'], $dec);
			$rep->AmountCol(4, 5, $trans['itemtotal'], $dec);
			$rep->fontsize += 2;
		}
		$total += $trans['itemtotal'];
		$grandtotal += $trans['itemtotal'];
	}
	if ($detail)
	{
		$rep->NewLine(2, 3);
		$rep->TextCol(0, 4, _('total'));
	}
	$rep->Amountcol(4, 5, $total, $dec);
	if ($detail)
	{
		$rep->Line($rep->row - 2);
		$rep->NewLine();
	}
	$rep->NewLine(2, 1);
	$rep->TextCol(0, 4, _('Grand Total'));
	$rep->AmountCol(4, 5, $grandtotal, $dec);
	$rep->Line($rep->row  - 4);
	$rep->NewLine();
    $rep->End();
}

?>