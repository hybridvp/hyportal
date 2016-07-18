<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SALESTRANSVIEW';
// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Joe Hunt
// date_:	2005-05-19
// Title:	Audit Trail
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
			add_nonfin_audit_trail(0,0,0,0,'REPORT PEQUEST','A',$ip,'REPORT 713 REQUESTED ');
}
function getTransactions($from, $to, $user, $customer_no)
{
	$fromdate = date2sql($from); // . " 00:00:00";
	$todate = date2sql($to); //. " 23:59.59";
//to_char(dat_txn,'dd/mm/yyyy') ,to_char(dat_txn,'HH24:MM:SS')
/* 	$sql = "select to_char(dat_txn,'dd/mm/yyyy') dat_txn, 			to_char(dat_txn,'HH24:MM:SS') time_txn, cod_user_id,cod_trans_type,txt_txn_desc,cod_term_id,decode(cod_mnt_action,'A' ,'ADD','D','DELETE','I','INQUIRY') action,
cod_key1_value,cod_key2_value
		FROM ".TB_PREF."audit_trail_nonfin a "; */
	$sql = "select a.order_no,a.customer_no,customer_name,quantity,logged_date,logged_by,denomination ,b.file_name
			FROM ".TB_PREF."pin_mailer_jobs a, "
				.TB_PREF."pin_mailer_jobs_detail b 
			where 1=1
			AND a.order_no = b.order_no
			AND a.line_no = b.line_no
			AND a.status='C' ";
		
	if ($user != -1)	
		$sql .= " AND a.logged_by='$user' ";
	if ($customer_no != -1)	
		$sql .= " AND a.customer_no='$customer_no' ";
	$sql .= "AND a.logged_date >= to_date('$fromdate','yyyy-mm-dd')
			AND a.logged_date <= to_date('$todate','yyyy-mm-dd') 
			ORDER BY logged_date desc";
    return db_query($sql,"No result was returned");
}
//----------------------------------------------------------------------------------------------------

function print_audit_trail()
{
    global $path_to_root, $systypes_array;

    $from = $_POST['PARAM_1'];
    $to = $_POST['PARAM_2'];
    $user = $_POST['PARAM_0'];
    $comments = $_POST['PARAM_3'];
	$destination = $_POST['PARAM_4'];
	$customer_no = $_POST['PARAM_5'];
	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");

    $dec = user_price_dec();

    $cols = array(0, 60, 130, 220, 260, 320 , 390, 430, 530);

    $headers = array( _('Order #'), 
    	 _('Customer name'), _('Quantity'), _('Request Date'), _('Requested By'),  _('Value'), _('File'));

    $aligns = array('left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left');

	$usr = get_user($user);
	$user_id = $usr['user_id'];
    $params =   array( 	0 => $comments,
    				    1 => array('text' => _('Period'), 'from' => $from,'to' => $to),
                  //  	2 => array('text' => _('Type'), 'from' => ($systype != -1 ? $systypes_array[$systype] : _('All')), 'to' => ''),
                    	2 => array('text' => _('User'), 'from' => ($user != -1 ? $user_id : _('All')), 'to' => ''));

    $rep = new FrontReport(_('Customer EPIN '), "Customer EPIN", user_pagesize());

    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->Header();

    $trans = getTransactions($from, $to, $user, $customer_no);

    while ($myrow=db_fetch($trans))
    {
        $rep->TextCol(0, 1, $myrow['order_no']);
		$rep->TextCol(1, 2, $myrow['customer_name']);
		$rep->TextCol(2, 3, $myrow['quantity']);
        if (user_date_format() == 0)
        	$rep->TextCol(3,4, $myrow['logged_date']);
        else	
        	$rep->TextCol(3, 4, $myrow['logged_date']);
			
		$rep->TextCol(4, 5, $myrow['logged_by']);
		
		$rep->TextCol(5, 6, $myrow['denomination']);
//        $rep->TextCol(3, 4, $systypes_array($myrow['cod_trans_type']));
		
        //$rep->TextCol(4, 5, $myrow['customer_name']);
        $rep->TextCol(6, 7, $myrow['file_name']);
        //if ($myrow['gl_seq'] == null)
       // 	$action = _('Changed');
       // else
       // 	$action = _('Closed');
        
		
		
        //if ($myrow['amount'] != null)
        //	$rep->AmountCol(7, 8, $myrow['amount'], $dec);
        $rep->NewLine(1, 2);
    }
    $rep->Line($rep->row  + 4);
    $rep->End();
}

?>