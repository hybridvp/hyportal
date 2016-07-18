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

function getSales($from, $to, $loc) {


		$strQuery = "SELECT ".TB_PREF."debtor_trans.*, ".TB_PREF."sys_types.type_id as type_name,
		(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + ".TB_PREF."debtor_trans.ov_freight + ".TB_PREF."debtor_trans.ov_discount)
		AS TotalAmount
    	FROM ".TB_PREF."debtor_trans, ".TB_PREF."sys_types, ".TB_PREF."cust_branch, ".TB_PREF."groups
    	WHERE ".TB_PREF."debtor_trans.type = 10
    	AND ".TB_PREF."debtor_trans.type = ".TB_PREF."sys_types.type_id
		AND ".TB_PREF."debtor_trans.branch_code = ".TB_PREF."cust_branch.branch_code
		AND ".TB_PREF."cust_branch.group_no = ".TB_PREF."groups.id";

		if ($loc != "")
			$strQuery .= " AND ".TB_PREF."cust_branch.group_no = '$loc'";

		$strQuery .= " AND ".TB_PREF."debtor_trans.tran_date <= '$to'
		AND ".TB_PREF."debtor_trans.tran_date >= '$from'
	   	ORDER BY ".TB_PREF."debtor_trans.tran_date";

    	$result = db_query($strQuery,"No transactions were returned");
		$damt = 0;
			if ($result) {
				while($ors = db_fetch($result)) {
					$damt += $ors['totalamount'];
				}
			}

			return $damt;
	}
							 $month[] = mktime(0,0,0,date('m')-11,1,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-10,0,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-10,1,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-9,0,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-9,1,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-8,0,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-8,1,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-7,0,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-7,1,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-6,0,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-6,1,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-5,0,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-5,1,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-4,0,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-4,1,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-3,0,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-3,1,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-2,0,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-2,1,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-1,0,date('Y'));
							 $month[] = mktime(0,0,0,date('m')-1,1,date('Y'));
							 $month[] = mktime(0,0,0,date('m'),0,date('Y'));
							 $month[] = mktime(0,0,0,date('m'),1,date('Y'));
							 $month[] = mktime(0,0,0,date('m')+1,0,date('Y'));

							 $sql = "SELECT id, description FROM ".TB_PREF."groups ORDER BY id";
							 $result = db_query($sql,"No locations were returned");

	$strXML = "<chart caption='Sales Comparison' shownames='1' showvalues='0' decimals='0' numberPrefix='N'><categories>";


							 if ($result) {
									while($ors = db_fetch($result)) {
										$lcode[] = $ors['id'];
										$lname = $ors['description'];
										$strXML .= "<category label='$lname' />";

								 }
							 }

							 $strXML .= "</categories>";
							 $i = 0;
							 $k = 1;
							 $l = 0;
				for ($j=0; $j<count($month)/2; $j++) {

									if ($i == 0)
										$color = "AFD8F8";
									elseif ($i == 1)
										$color = "F6BD0F";
									elseif ($i == 2)
										$color = "8BBA00";
									elseif ($i == 3)
										$color = "99FF66";
									elseif ($i == 4)
										$color = "BC9FBC";
									elseif ($i == 5)
										$color = "009900";
									elseif ($i == 6)
										$color = "669999";
									elseif ($i == 7)
										$color = "996600";
									elseif ($i == 8)
										$color = "FF0000";
									elseif ($i == 9)
										$color = "000066";
									elseif ($i == 10)
										$color = "FF66666";
									elseif ($i == 11)
										$color = "00FFFFF";
									$i++;


					$strXML .= "<dataset seriesName='". date('M', $month[$k]) ."' color='". $color ."' showValues='0'>";

					foreach($lcode as $loc)
						$strXML .= "	<set value='" .getSales(date('Y-m-d',$month[$l]),date('Y-m-d',$month[$k]),$loc). "' />";

					$strXML .= "</dataset>";
					$k = $k + 2;
					$l = $l + 2;
				}


					$strXML .= "</chart>";

$js = "";
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_("All Sales"), false, false, "", $js);


start_form();

start_table($table_style2);


 //Create the chart - Column 3D Chart with data from Data/Data.xml
      echo renderChartHTML("Charts/MSColumn3D.swf", "", "$strXML", "All Monthly Sales", 900, 450, false);

end_table(1);


end_form();

//--------------------------------------------------------------------------------------------

end_page();

?>