<?php
/**********************************************************************

***********************************************************************/
$page_security = 'SA_SALESTRANSVIEW';
// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Joe Hunt
// date_:	2005-05-19

// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/includes/ui/ui_view.inc");

//----------------------------------------------------------------------------------------------------

print_audit_trail();
if($nonfin_audit_trail)
			{
			$ip = preg_quote($_SERVER['REMOTE_ADDR']);
			add_nonfin_audit_trail(0,0,0,0,'REPORT PEQUEST','A',$ip,'REPORT 716 REQUESTED ');
}
function getTransactions($from, $to)
{
	$fromdate = date2sql($from); // . " 00:00:00";
	$todate = date2sql($to); //. " 23:59.59";
	//to_char(dat_txn,'dd/mm/yyyy') ,to_char(dat_txn,'HH24:MM:SS')
	/* 	$sql = "select to_char(dat_txn,'dd/mm/yyyy') dat_txn, 			to_char(dat_txn,'HH24:MM:SS') time_txn, 			cod_user_id,cod_trans_type,txt_txn_desc,cod_term_id,decode(cod_mnt_action,'A' ,'ADD','D','DELETE','I','INQUIRY') action,
cod_key1_value,cod_key2_value
		FROM ".TB_PREF."audit_trail_nonfin a "; */
	$sql = 'BEGIN pkg_inventory_movement.prc_inventory_statement(:vstart_date,:vend_date); END;';
							
							$result = $stmt = oci_parse($db,$sql);
							oci_bind_by_name($stmt,':vstart_date',$fromdate,30);
							oci_bind_by_name($stmt,':vend_date',$todate,30);
							
							// Create a new cursor resource
							$sales_inventory = oci_new_cursor($db);
							
							// Bind the cursor resource to the Oracle argument
							oci_bind_by_name($stmt,":sales_inventory",$sales_inventory,-1,OCI_B_CURSOR);							
							// Execute the statement
							oci_execute($stmt);
							
							// Execute the cursor
							oci_execute($sales_inventory);

							$err = oci_error($result);
							if( $err ){
										$db_err ="ERROR";
										return;
							}
/* 	$sql = "select b.file_name,a.order_no,a.line_no,customer_name,quantity,b.start_serial || '-' start_serial,b.end_serial,denomination,logged_by 
			FROM ".TB_PREF."pin_mailer_jobs a,  "
			.TB_PREF."pin_mailer_jobs_detail b 
			where 1=1
			AND a.order_no = b.order_no
			AND a.line_no = b.line_no ";
			//AND a.status !='' ";
		
	if ($user != -1)	
		$sql .= " AND a.logged_by='$user' ";
	if ($customer_no != -1)	
		$sql .= " AND a.customer_no='$customer_no' ";
	$sql .= "AND a.logged_date >= to_date('$fromdate','yyyy-mm-dd')
			AND a.logged_date <= to_date('$todate','yyyy-mm-dd') 
			ORDER BY logged_date desc"; */
			//echo $sql;
    return db_query($sql,"No result was returned");
}
//----------------------------------------------------------------------------------------------------

function print_audit_trail()
{
    global $path_to_root, $systypes_array, $db;
		
    $from = $_POST['PARAM_0'];
    $to = $_POST['PARAM_1'];
    $comments = $_POST['PARAM_2'];
	$destination = $_POST['PARAM_3'];

	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");

    $dec = user_price_dec();

    $cols = array(0, 150, 300); //array(0, 60, 120, 180, 300, 120 , 120, 60, 120, 120);

    $headers = array(_('Product (Denomination)'), _('Quantity Sold'));

    $aligns = array('left', 'left', 'left');
	///$usr = get_user($user);
	//$user_id = $usr['user_id'];
    $params =   array( 	0 => $comments,
    				    1 => array('text' => _('Period'), 'from' => $from,'to' => $to));
                  //  	2 => array('text' => _('Type'), 'from' => ($systype != -1 ? $systypes_array[$systype] : _('All')), 'to' => ''),
                    	//2 => array('text' => _('User'), 'from' => ($user != -1 ? $user_id : _('All')), 'to' => ''));

    $rep = new FrontReport(_('Product Sales Summary'), "ProductSalesSummary", user_pagesize());
    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->Header();
//    $trans = getTransactions($from, $to);
	$sql = 'BEGIN pkg_inventory_movement.prc_sales_summary(:vstart_date,:vend_date,:sales_summary); END;';
							
							$result = $stmt = oci_parse($db,$sql);
							oci_bind_by_name($stmt,':vstart_date',$from,30);
							oci_bind_by_name($stmt,':vend_date',$to,30);
							
							// Create a new cursor resource
							$sales_summary = oci_new_cursor($db);
							
							// Bind the cursor resource to the Oracle argument
							oci_bind_by_name($stmt,":sales_summary",$sales_summary,-1,OCI_B_CURSOR);							
							// Execute the statement
							oci_execute($stmt);
							
							// Execute the cursor
							oci_execute($sales_summary);

    while ($myrow=db_fetch($sales_summary))
    {
        $rep->TextCol(0, 1, $myrow['facevalue']);
        //if (user_date_format() == 0)
        //	$rep->TextCol(1, 2, $myrow['logged_date']);
        //else	
        //	$rep->TextCol(1, 2, $myrow['logged_date']);
		$rep->TextCol(1, 2, $myrow['quantity_sold']);

        //if ($myrow['gl_seq'] == null)
       // 	$action = _('Changed');
       // else
       // 	$action = _('Closed');
        //$rep->TextCol(6, 7, $myrow['end_serial']);
		//$rep->TextCol(7, 8, $myrow['denomination']);
		//$rep->TextCol(8, 9, $myrow['logged_by']);
        //if ($myrow['amount'] != null)
        //	$rep->AmountCol(7, 8, $myrow['amount'], $dec);
        $rep->NewLine(1, 2);
    }
    $rep->Line($rep->row  + 4);
    $rep->End();
}

?>