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


function getSales($date,$loc="",$stock_id= "") {
	
		/// Laolu changed sys_types.type_name to sys_types.type_no
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
		AND ".TB_PREF." debtor_trans.type = 13 
		AND ".TB_PREF."debtor_trans.debtor_no = ".TB_PREF."pin_mailer_jobs.customer_no 
		AND ".TB_PREF."debtor_trans.order_ = ".TB_PREF."pin_mailer_jobs.order_no ";
		
		if ($loc != "")
			$strQuery .= " AND ".TB_PREF."cust_branch.group_no = '$loc'";
		if ($stock_id != "")
			$strQuery .= " AND ".TB_PREF."pin_mailer_jobs.stock_id = '$stock_id'";
		$strQuery .= " AND ".TB_PREF."debtor_trans.tran_date = to_date('$date','yyyy-mm-dd')
		
		group by ".TB_PREF."pin_mailer_jobs.stock_id
	   	ORDER BY ".TB_PREF."debtor_trans.tran_date";
		
    	$result = db_query($strQuery,"No transactions were returned");
		//echo $strQuery;
		$damt =0;
			if ($result) {
				while($ors = db_fetch($result)) {					
					$damt += $ors['totalamount'];
				}
			}
			
			return $damt;
	}		
 							 $month31 = mktime(0,0,0,date('m')+1,0,date('Y'));
							 $month1 = mktime(0,0,0,date('m'),1,date('Y'));
							 $month2 = mktime(0,0,0,date('m'),2,date('Y'));
							 $month3 = mktime(0,0,0,date('m'),3,date('Y'));
							 $month4 = mktime(0,0,0,date('m'),4,date('Y'));
							 $month5 = mktime(0,0,0,date('m'),5,date('Y'));
							 $month6 = mktime(0,0,0,date('m'),6,date('Y'));
							 $month7 = mktime(0,0,0,date('m'),7,date('Y'));
							 $month8 = mktime(0,0,0,date('m'),8,date('Y'));
							 $month9 = mktime(0,0,0,date('m'),9,date('Y'));
							 $month10 = mktime(0,0,0,date('m'),10,date('Y'));
							 $month11 = mktime(0,0,0,date('m'),11,date('Y'));
							 $month12 = mktime(0,0,0,date('m'),12,date('Y'));
							 $month13 = mktime(0,0,0,date('m'),13,date('Y'));
							 $month14 = mktime(0,0,0,date('m'),14,date('Y'));
							 $month15 = mktime(0,0,0,date('m'),15,date('Y'));
							 $month16 = mktime(0,0,0,date('m'),16,date('Y'));
							 $month17 = mktime(0,0,0,date('m'),17,date('Y'));
							 $month18 = mktime(0,0,0,date('m'),18,date('Y'));
							 $month19 = mktime(0,0,0,date('m'),19,date('Y'));
							 $month20 = mktime(0,0,0,date('m'),20,date('Y'));
							 $month21 = mktime(0,0,0,date('m'),21,date('Y'));
							 $month22 = mktime(0,0,0,date('m'),22,date('Y'));
							 $month23 = mktime(0,0,0,date('m'),23,date('Y'));
							 $month24 = mktime(0,0,0,date('m'),24,date('Y'));
							 $month25 = mktime(0,0,0,date('m'),25,date('Y'));
							 $month26 = mktime(0,0,0,date('m'),26,date('Y'));
							 $month27 = mktime(0,0,0,date('m'),27,date('Y'));
							 $month28 = mktime(0,0,0,date('m'),28,date('Y'));
							 $month29 = mktime(0,0,0,date('m'),29,date('Y'));
							 $month30 = mktime(0,0,0,date('m'),30,date('Y'));
							 
					$loc = "";		
					$stock_id = ""; 
				//if (isset($_GET['HQ']) && $_GET['HQ'] ==1)
				//	$loc = "2";
				//elseif (isset($_POST['group_no']))
				//	$loc = $_POST['group_no'];
				
				$strdate = date('F-Y');
				if (isset($_POST['stock_id']))
					{
						$stock_id = $_POST['stock_id'];	
													 $strXML = "<chart caption='EPIN Daily Sales for " . $strdate . " for Item ". $stock_id . "' xAxisName='Days of the Month' yAxisName='Total Sales Quantity' showValues='0' numberPrefix='' decimals='0' formatNumberScale='0'>";					
					}
				else
				 {
				 								 $strXML = "<chart caption='EPIN Daily Sales for " . $strdate . "' xAxisName='Days of the Month' yAxisName='Total Sales Quantity' showValues='0' numberPrefix='' decimals='0' formatNumberScale='0'>";
				 }		
				

					

							 
		$strXML .= "<set label='" .date('d', $month1). "' value='" .getSales(date('Y-m-d',$month1),$loc,$stock_id). "' />
					<set label='" .date('d', $month2). "' value='" .getSales(date('Y-m-d',$month2),$loc,$stock_id). "' />
					<set label='" .date('d', $month3). "' value='" .getSales(date('Y-m-d',$month3),$loc,$stock_id). "' />
					<set label='" .date('d', $month4). "' value='" .getSales(date('Y-m-d',$month4),$loc,$stock_id). "' />
					<set label='" .date('d', $month5). "' value='" .getSales(date('Y-m-d',$month5),$loc,$stock_id). "' />
					<set label='" .date('d', $month6). "' value='" .getSales(date('Y-m-d',$month6),$loc,$stock_id). "' />
					<set label='" .date('d', $month7). "' value='" .getSales(date('Y-m-d',$month7),$loc,$stock_id). "' />
					<set label='" .date('d', $month8). "' value='" .getSales(date('Y-m-d',$month8),$loc,$stock_id). "' />
					<set label='" .date('d', $month9). "' value='" .getSales(date('Y-m-d',$month9),$loc,$stock_id). "' />
					<set label='" .date('d', $month10). "' value='" .getSales(date('Y-m-d',$month10),$loc,$stock_id). "' />
					<set label='" .date('d', $month11). "' value='" .getSales(date('Y-m-d',$month11),$loc,$stock_id). "' />
					<set label='" .date('d', $month12). "' value='" .getSales(date('Y-m-d',$month12),$loc,$stock_id). "' />
					<set label='" .date('d', $month13). "' value='" .getSales(date('Y-m-d',$month13),$loc,$stock_id). "' />
					<set label='" .date('d', $month14). "' value='" .getSales(date('Y-m-d',$month14),$loc,$stock_id). "' />
					<set label='" .date('d', $month15). "' value='" .getSales(date('Y-m-d',$month15),$loc,$stock_id). "' />
					<set label='" .date('d', $month16). "' value='" .getSales(date('Y-m-d',$month16),$loc,$stock_id). "' />
					<set label='" .date('d', $month17). "' value='" .getSales(date('Y-m-d',$month17),$loc,$stock_id). "' />
					<set label='" .date('d', $month18). "' value='" .getSales(date('Y-m-d',$month18),$loc,$stock_id). "' />
					<set label='" .date('d', $month19). "' value='" .getSales(date('Y-m-d',$month19),$loc,$stock_id). "' />
					<set label='" .date('d', $month20). "' value='" .getSales(date('Y-m-d',$month20),$loc,$stock_id). "' />
					<set label='" .date('d', $month21). "' value='" .getSales(date('Y-m-d',$month21),$loc,$stock_id). "' />
					<set label='" .date('d', $month22). "' value='" .getSales(date('Y-m-d',$month22),$loc,$stock_id). "' />
					<set label='" .date('d', $month23). "' value='" .getSales(date('Y-m-d',$month23),$loc,$stock_id). "' />
					<set label='" .date('d', $month24). "' value='" .getSales(date('Y-m-d',$month24),$loc,$stock_id). "' />
					<set label='" .date('d', $month25). "' value='" .getSales(date('Y-m-d',$month25),$loc,$stock_id). "' />
					<set label='" .date('d', $month26). "' value='" .getSales(date('Y-m-d',$month26),$loc,$stock_id). "' />
					<set label='" .date('d', $month27). "' value='" .getSales(date('Y-m-d',$month27),$loc,$stock_id). "' />
					<set label='" .date('d', $month28). "' value='" .getSales(date('Y-m-d',$month28),$loc,$stock_id). "' />
					<set label='" .date('d', $month29). "' value='" .getSales(date('Y-m-d',$month29),$loc,$stock_id). "' />
					<set label='" .date('d', $month30). "' value='" .getSales(date('Y-m-d',$month30),$loc,$stock_id). "' />					
					<set label='" .date('d', $month31). "' value='" .getSales(date('Y-m-d',$month31),$loc,$stock_id). "' />";
										
					$strXML .= "</chart>";

$js = "";
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_("Daily Sales by branch"), false, false, "", $js);


start_form();

start_table($table_style2);

		//stock_items_list_row(_("Item :"), 'stock_id', null, true);
		stock_items_list_cells(_("Item:"), 'stock_id', null, true);
	//sales_groups_list_row(_("Customer Group:"), 'group_no', null, true);

	submit_cells('submit', _("Submit"),'',_('Get EPIN Sales'), false);

 //Create the chart - Column 3D Chart with data from Data/Data.xml
      echo renderChartHTML("Charts/Column3D.swf", "", "$strXML", "Daily Sales", 900, 450, false); 
	  //echo date('Y-m-d',$month2a);

end_table(1);


end_form();

//--------------------------------------------------------------------------------------------

end_page();

?>
