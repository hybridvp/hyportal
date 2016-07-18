<?php


$page_security = 'SA_OPEN';
//$page_security = 10;
$path_to_root="..";
include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/manufacturing.inc");
include_once($path_to_root . "/includes/data_checks.inc");
echo "<script language='javascript' src='includes/FusionCharts.js'></script>";
include_once($path_to_root . "/dashboard/includes/FusionCharts.php");

include_once($path_to_root . "/dimensions/includes/dimensions_db.inc");
include_once($path_to_root . "/dimensions/includes/dimensions_ui.inc");

function getSales($from, $to, $loc) {


		$strQuery = "SELECT ".TB_PREF."debtor_trans.*, ".TB_PREF."sys_types.type_id as type_name,
		(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + ".TB_PREF."debtor_trans.ov_freight + ".TB_PREF."debtor_trans.ov_discount)
		AS TotalAmount
    	FROM ".TB_PREF."debtor_trans, ".TB_PREF."sys_types, ".TB_PREF."cust_branch, ".TB_PREF."locations
    	WHERE ".TB_PREF."debtor_trans.type = 10
    	AND ".TB_PREF."debtor_trans.type = ".TB_PREF."sys_types.type_id
		AND ".TB_PREF."debtor_trans.branch_code = ".TB_PREF."cust_branch.branch_code
		AND ".TB_PREF."cust_branch.default_location = ".TB_PREF."locations.loc_code";

		if ($loc != "")
			$strQuery .= " AND ".TB_PREF."cust_branch.default_location = '$loc'";

		$strQuery .= " AND ".TB_PREF."debtor_trans.tran_date <= to_date('$to','yyyy-mm-dd')
		AND ".TB_PREF."debtor_trans.tran_date >= to_date('$from','yyyy-mm-dd')
	   	ORDER BY ".TB_PREF."debtor_trans.tran_date";

    	$result = db_query($strQuery,"No transactions were returned");
		$damt = 0 ;
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
							 $month10a = mktime(0,0,0,date('m')-10,0,date('Y'));
							 $month11 = mktime(0,0,0,date('m')-10,0,date('Y'));
							 $month11a = mktime(0,0,0,date('m')-11,1,date('Y'));

				$loc = "";
				if (isset($_GET['HQ']) && $_GET['HQ'] ==1)
					$loc = "DEF";
				elseif (isset($_POST['default_location']))
					$loc = $_POST['default_location'];

							 $strXML = "<chart caption='Monthly Sales' xAxisName='Months' yAxisName='Amount' showValues='0' numberPrefix='N' decimals='0' formatNumberScale='0'>";

							 $strXML .= "	<set label='" .date('M', $month11). "' value='" .getSales(date('Y-m-d',$month11a),date('Y-m-d',$month11),$loc). "' />
					<set label='" .date('M', $month10). "' value='" .getSales(date('Y-m-d', $month10a),date('Y-m-d',$month10),$loc). "' />
					<set label='" .date('M', $month9). "' value='" .getSales(date('Y-m-d',$month9a),date('Y-m-d',$month9),$loc). "' />
					<set label='" .date('M', $month8). "' value='" .getSales(date('Y-m-d',$month8a),date('Y-m-d',$month8),$loc). "' />
					<set label='" .date('M', $month7). "' value='" .getSales(date('Y-m-d',$month7a),date('Y-m-d',$month7),$loc). "' />
					<set label='" .date('M', $month6). "' value='" .getSales(date('Y-m-d',$month6a),date('Y-m-d',$month6),$loc). "' />
					<set label='" .date('M', $month5). "' value='" .getSales(date('Y-m-d',$month5a),date('Y-m-d',$month5),$loc). "' />
					<set label='" .date('M', $month4). "' value='" .getSales(date('Y-m-d',$month4a),date('Y-m-d',$month4),$loc). "' />
					<set label='" .date('M', $month3). "' value='" .getSales(date('Y-m-d',$month3a),date('Y-m-d',$month3),$loc). "' />
					<set label='" .date('M', $month2). "' value='" .getSales(date('Y-m-d',$month2a),date('Y-m-d',$month2),$loc). "' />
					<set label='" .date('M', $month1). "' value='" .getSales(date('Y-m-d',$month1a),date('Y-m-d',$month1),$loc). "' />
					<set label='" .date('M', $month). "' value='" .getSales(date('Y-m-d',$montha),date('Y-m-d',$month),$loc). "' />";

					$strXML .= "</chart>";

$js = "";
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_("HeadQuarter's Monthly Sales"), false, false, "", $js);


start_form();

start_table($table_style2);

	locations_list_row(_("Select Location:"), 'default_location', null);
	submit_cells('submit', _("Submit"),'',_('Get Sales'), false);

 //Create the chart - Column 3D Chart with data from Data/Data.xml
      echo renderChartHTML("Charts/Column3D.swf", "", "$strXML", "Monthly Sales", 900, 450, false);
	  //echo date('Y-m-d',$month2a);

end_table(1);


end_form();

//--------------------------------------------------------------------------------------------

end_page();

?>
