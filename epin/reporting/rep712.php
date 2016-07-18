<?php
/**********************************************************************
    Copyright (C) Simplex
***********************************************************************/
//$page_security = $_POST['PARAM_0'] == $_POST['PARAM_1'] ?
//	'SA_SALESTRANSVIEW' : 'SA_SALESBULKREP';

$page_security = 'SA_SALESTRANSVIEW';

// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Laolu Olapegba
// date_:	2010-08-11
// Title:	Print Customer PIN File
// draft version!
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");

$packing_slip = 0;
//----------------------------------------------------------------------------------------------------

print_custpin();

//----------------------------------------------------------------------------------------------------

function print_custpin()
{
	global $path_to_root;

	include_once($path_to_root . "/reporting/includes/cust_pin_send.inc");

	$cust_no = $_POST['PARAM_0'];
	$trn_no = $_POST['PARAM_1'];
	$email = $_POST['PARAM_2'];
	$packing_slip = $_POST['PARAM_3'];
	$comments = $_POST['PARAM_4'];
	$order_no = $_POST['PARAM_5'];
	$filename = $_POST['PARAM_6'];
	$dec = user_price_dec();
	
	$sql = "SELECT * FROM ".TB_PREF."debtors_master WHERE debtor_no = ".db_escape($cust_no);
	$result = db_query($sql,"check for customer details failed");

	$myrow = db_fetch($result);
	$params = array('comments' => $comments);
	$cur = get_company_Pref('curr_default');

	$cur = get_company_Pref('curr_default');

			if ($email == 1)
			{
				$rep = new FrontReport("", "", user_pagesize());
				$rep->currency = $cur;
				$rep->Font();
				$rep->End($email, "Delivery of Order ". $order_no, $myrow, ST_CUSTDELIVERY, $filename);
			}
	
	if ($email == 0)
		$rep->End();
}

?>