<?php

//$page_security = 10;
$page_security = 'SA_OPEN';
$path_to_root="..";
include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/manufacturing.inc");
include_once($path_to_root . "/includes/data_checks.inc");
echo "<script language='javascript' src='includes/FusionCharts.js'></script>";
include_once($path_to_root . "/dashboard/includes/FusionCharts.php");

include_once($path_to_root . "/dimensions/includes/dimensions_db.inc");
include_once($path_to_root . "/dimensions/includes/dimensions_ui.inc");

function getSales($from, $to, $loc, $stock_id="", $customer_id="") {


	$strQuery = "SELECT ".TB_PREF."pin_mailer_jobs.stock_id, 
		SUM(".TB_PREF."pin_mailer_jobs.quantity)AS TotalAmount
    	FROM "
		.TB_PREF."debtor_trans, "
		.TB_PREF."cust_branch, "
		.TB_PREF."pin_mailer_jobs, "
		.TB_PREF."groups
    	WHERE 1=1 
		    	
		AND ".TB_PREF."debtor_trans.branch_code = ".TB_PREF."cust_branch.branch_code
		AND ".TB_PREF."cust_branch.group_no = ".TB_PREF."groups.id 
		AND ".TB_PREF."debtor_trans.type = 13 
		AND ".TB_PREF."debtor_trans.debtor_no = ".TB_PREF."pin_mailer_jobs.customer_no 
		AND ".TB_PREF."debtor_trans.order_ = ".TB_PREF."pin_mailer_jobs.order_no ";
		
		if ($loc != "")
			$strQuery .= " AND ".TB_PREF."cust_branch.group_no = '$loc'";
		if ($stock_id != "")
			$strQuery .= " AND ".TB_PREF."pin_mailer_jobs.stock_id = '$stock_id'";
		if ($customer_id != "")
			$strQuery .= " AND ".TB_PREF."pin_mailer_jobs.customer_no = '$customer_id'";

		$strQuery .= " AND ".TB_PREF."debtor_trans.tran_date <= to_date('$to','yyyy-mm-dd')
		AND ".TB_PREF."debtor_trans.tran_date >= to_date( '$from','yyyy-mm-dd')
		group by ".TB_PREF."pin_mailer_jobs.stock_id ";
//	   	ORDER BY ".TB_PREF."debtor_trans.tran_date";

    	$result = db_query($strQuery,"No transactions were returned");
		$damt = 0;
			if ($result) {
				while($ors = db_fetch($result)) {
					$damt += $ors['totalamount'];
				}
			}

			return $damt;
	}
 							 $month = mktime(0,0,0,date('m')+1,0,date('Y'));
							 $montha = mktime(0,0,0,date('m'),1,date('Y'));
							 $month1 = mktime(0,0,0,date('m'),0,date('Y'));
							 $month1a = mktime(0,0,0,date('m')-1,1,date('Y'));
							 $month2 = mktime(0,0,0,date('m')-1,0,date('Y'));
							 $month2a = mktime(0,0,0,date('m')-2,1,date('Y'));
							 $month3 = mktime(0,0,0,date('m')-2,0,date('Y'));
							 $month3a = mktime(0,0,0,date('m')-3,1,date('Y'));
							 $month4 = mktime(0,0,0,date('m')-3,0,date('Y'));
							 $month4a = mktime(0,0,0,date('m')-4,1,date('Y'));
							 $month5 = mktime(0,0,0,date('m')-4,0,date('Y'));
							 $month5a = mktime(0,0,0,date('m')-5,1,date('Y'));
							 $month6 = mktime(0,0,0,date('m')-5,0,date('Y'));
							 $month6a = mktime(0,0,0,date('m')-6,1,date('Y'));
							 $month7 = mktime(0,0,0,date('m')-6,0,date('Y'));
							 $month7a = mktime(0,0,0,date('m')-7,1,date('Y'));
							 $month8 = mktime(0,0,0,date('m')-7,0,date('Y'));
							 $month8a = mktime(0,0,0,date('m')-8,1,date('Y'));
							 $month9 = mktime(0,0,0,date('m')-8,0,date('Y'));
							 $month9a = mktime(0,0,0,date('m')-9,1,date('Y'));
							 $month10 = mktime(0,0,0,date('m')-9,0,date('Y'));
							 $month10a = mktime(0,0,0,date('m')-10,1,date('Y'));
							 $month11 = mktime(0,0,0,date('m')-10,0,date('Y'));
							 $month11a = mktime(0,0,0,date('m')-11,1,date('Y'));

				$loc = "";
				$stock_id = "";
				//if (isset($_GET['HQ']) && $_GET['HQ'] ==1)
				//	$loc = "2";
				//elseif (isset($_POST['group_no']))
				//	$loc = $_POST['group_no'];
					
				$strdate = date('Y');
				if (isset($_POST['stock_id']))
					{
						$stock_id = $_POST['stock_id'];	
						$strXML = "<chart caption='EPIN Monthly Sales for " . $strdate . " for Item ". $stock_id . "' xAxisName='Months' yAxisName='Quantity' showValues='0' numberPrefix='' decimals='0' formatNumberScale='0'>";					
					}
				else
				 {
				 		$strXML = "<chart caption='EPIN Monthly Sales for " . $strdate . "' xAxisName='Months' yAxisName='Quantity' showValues='0' numberPrefix='' decimals='0' formatNumberScale='0'>";
				 }		
				

///$strXML = "<chart caption='EPIN Monthly Sales' xAxisName='Months' yAxisName='Quantity' showValues='0' numberPrefix='' decimals='0' formatNumberScale='0'>";

	$strXML .=
 "  <set label='" .date('M', $month11). "' value='" .getSales(date('Y-m-d',$month11a),date('Y-m-d',$month11),$loc,$stock_id,$customer_id). "' />
	<set label='" .date('M', $month10). "' value='" .getSales(date('Y-m-d', $month10a),date('Y-m-d',$month10),$loc,$stock_id,$customer_id). "' />
	<set label='" .date('M', $month9). "' value='" .getSales(date('Y-m-d',$month9a),date('Y-m-d',$month9),$loc,$stock_id,$customer_id). "' />
	<set label='" .date('M', $month8). "' value='" .getSales(date('Y-m-d',$month8a),date('Y-m-d',$month8),$loc,$stock_id,$customer_id). "' />
	<set label='" .date('M', $month7). "' value='" .getSales(date('Y-m-d',$month7a),date('Y-m-d',$month7),$loc,$stock_id,$customer_id). "' />
	<set label='" .date('M', $month6). "' value='" .getSales(date('Y-m-d',$month6a),date('Y-m-d',$month6),$loc,$stock_id,$customer_id). "' />
	<set label='" .date('M', $month5). "' value='" .getSales(date('Y-m-d',$month5a),date('Y-m-d',$month5),$loc,$stock_id,$customer_id). "' />
	<set label='" .date('M', $month4). "' value='" .getSales(date('Y-m-d',$month4a),date('Y-m-d',$month4),$loc,$stock_id,$customer_id). "' />
	<set label='" .date('M', $month3). "' value='" .getSales(date('Y-m-d',$month3a),date('Y-m-d',$month3),$loc,$stock_id,$customer_id). "' />
	<set label='" .date('M', $month2). "' value='" .getSales(date('Y-m-d',$month2a),date('Y-m-d',$month2),$loc,$stock_id,$customer_id). "' />
	<set label='" .date('M', $month1). "' value='" .getSales(date('Y-m-d',$month1a),date('Y-m-d',$month1),$loc,$stock_id,$customer_id). "' />
	<set label='" .date('M', $month). "' value='" .getSales(date('Y-m-d',$montha),date('Y-m-d',$month),$loc,$stock_id,$customer_id). "' />";

					$strXML .= "</chart>";

$js = "";
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_("HQ Monthly Sales"), false, false, "", $js);


start_form();

start_table($table_style2);

	//sales_groups_list_row(_("Customer Group:"), 'group_no', null, true);
	
	stock_items_list_cells(_("Item:"), 'stock_id', null, true);
	customer_list_cells(_("Customer:"), 'customer_id', null, false, true, false, true);
	submit_cells('submit', _("Submit"),'',_('Get EPIN Sales'), false);

 //Create the chart - Column 3D Chart with data from Data/Data.xml
      echo renderChartHTML("Charts/Column3D.swf", "", "$strXML", "Monthly Sales", 900, 450, false);
	  //echo date('Y-m-d',$month2a);

end_table(1);


end_form();

//--------------------------------------------------------------------------------------------

end_page();

?>
