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
	
		//echo 'here1';
		$strQuery = "SELECT ".TB_PREF."debtor_trans.*, ".TB_PREF."sys_types.type_id,
		(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + ".TB_PREF."debtor_trans.ov_freight + ".TB_PREF."debtor_trans.ov_discount)
		AS TotalAmount
    	FROM ".TB_PREF."debtor_trans, ".TB_PREF."sys_types, ".TB_PREF."cust_branch, ".TB_PREF."locations
    	WHERE 1=1 " ;
		 //.TB_PREF."debtor_trans.type = 10
    	$strQuery .= " AND ".TB_PREF."debtor_trans.type = ".TB_PREF."sys_types.type_id
		AND ".TB_PREF."debtor_trans.branch_code = ".TB_PREF."cust_branch.branch_code
		AND ".TB_PREF."cust_branch.default_location = ".TB_PREF."locations.loc_code";
		
		

		if ($loc != "")
			$strQuery .= " AND ".TB_PREF."cust_branch.default_location = '$loc'";
			
		$strQuery .= " AND ".TB_PREF."debtor_trans.tran_date <= '$to'
		AND ".TB_PREF."debtor_trans.tran_date >= '$from'
	   	ORDER BY ".TB_PREF."debtor_trans.tran_date";
		//echo $strQuery;
    	$result = db_query($strQuery,"No transactions were returned");
		$damt = 0;
			if ($result) {
				while($ors = db_fetch($result)) {					
					$damt += $ors['totalamount'];
				}
			}
			
			return $damt;
	}		
 							 $month0 = mktime(0,0,0,date('m')+1,0,date('Y'));
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
							 $sql = "SELECT loc_code, location_name FROM ".TB_PREF."locations where inactive !=1";
							 $result = db_query($sql,"No locations were returned");
							 
							 			
							 $strXML = "<chart caption='Sales Comparison' shownames='1' showvalues='0' decimals='0' numberPrefix='NGN'><categories>";
							// echo 'here2';				 
							 if ($result) {
							// echo 'here3';
								while($ors = db_fetch($result)) {
									$lname = $ors['location_name'];									
									$strXML .= "<category label='$lname' />";
							 }
							 
							 $strXML .= "</categories>";
							 $i = 0;
							 $result2 = db_query($sql,"No locations were returned");
							 if ($result2) {
								while($ors = db_fetch($result2)) {	
									$loc = $ors['loc_code'];
									$lname = $ors['location_name'];
									//echo 'location:' .$loc . "<br>";	
									
									if ($i == 0)
										$color = "AFD8F8";
									elseif ($i == 1)
										$color = "F6BD0F";
									elseif ($i == 2)
										$color = "8BBA00";
									elseif ($i == 3)
										$color = "000000";
									elseif ($i == 4)
										$color = "BC9FBC";
									elseif ($i == 5)
										$color = "009900";
									elseif ($i ==6)
										$color = "669999";
									elseif ($i == 7)
										$color = "F6BD0F";
									elseif ($i == 8)
										$color = "FF0000";
											 
	$strXML .= "<dataset seriesName='". date('M', "$month$i") ."' color='". $color. "' showValues='0'>";
	$strXML .= "	<set value='" .getSales(date('Y-m-d',$month11a),date('Y-m-d',$month11),$loc). "' />
					<set value='" .getSales(date('Y-m-d',$month10a),date('Y-m-d',$month10),$loc). "' />
					<set value='" .getSales(date('Y-m-d',$month9a),date('Y-m-d',$month9),$loc). "' />
					<set value='" .getSales(date('Y-m-d',$month8a),date('Y-m-d',$month8),$loc). "' />
					<set value='" .getSales(date('Y-m-d',$month7a),date('Y-m-d',$month7),$loc). "' />
					<set value='" .getSales(date('Y-m-d',$month6a),date('Y-m-d',$month6),$loc). "' />
					<set value='" .getSales(date('Y-m-d',$month5a),date('Y-m-d',$month5),$loc). "' />
					<set value='" .getSales(date('Y-m-d',$month4a),date('Y-m-d',$month4),$loc). "' />
					<set value='" .getSales(date('Y-m-d',$month3a),date('Y-m-d',$month3),$loc). "' />
					<set value='" .getSales(date('Y-m-d',$month2a),date('Y-m-d',$month2),$loc). "' />
					<set value='" .getSales(date('Y-m-d',$month1a),date('Y-m-d',$month1),$loc). "' />
					<set value='" .getSales(date('Y-m-d',$montha),date('Y-m-d',$month0),$loc). "' />";
					$strXML .= "</dataset>";
					
						$i++;
								}
							}
						}	 
							 
					$strXML .= "</chart>";

$js = "";
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_("HQ Outstanding Sales"), false, false, "", $js);


start_form();

start_table($table_style2);

	
 //Create the chart - Column 3D Chart with data from Data/Data.xml
      echo renderChartHTML("Charts/MSColumn3D.swf", "", "$strXML", "Monthly Sales", 900, 450, false); 
	  //echo date('Y-m-d',$month2a);

end_table(1);


end_form();

//--------------------------------------------------------------------------------------------

end_page();

?>