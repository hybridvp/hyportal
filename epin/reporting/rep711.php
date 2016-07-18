<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_GLANALYTIC';
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
			add_nonfin_audit_trail(0,0,0,0,'REPORT PEQUEST','A',$ip,'REPORT 711 REQUESTED ');
}
function getTransactions($from, $to, $user)
{
	$fromdate = date2sql($from); // . " 00:00:00";
	$todate = date2sql($to); //. " 23:59.59";
//to_char(dat_txn,'dd/mm/yyyy') ,to_char(dat_txn,'HH24:MM:SS')
	$sql = "select to_char(dat_txn,'dd/mm/yyyy') dat_txn, 			to_char(dat_txn,'HH24:MM:SS') time_txn, cod_user_id,cod_trans_type,txt_txn_desc,cod_term_id,decode(cod_mnt_action,'A' ,'ADD','D','DELETE','I','INQUIRY') action,
cod_key1_value,cod_key2_value
		FROM ".TB_PREF."audit_trail_nonfin a ,"
		      .TB_PREF."users u 
		WHERE 1=1 
		AND upper(a.cod_user_id) = upper(u.user_id ) ";
		
	if ($user != -1)	
		$sql .= " AND u.id='$user'";
	$sql .= "AND a.dat_txn >= to_date('$fromdate','yyyy-mm-dd')
			AND a.dat_txn <= to_date('$todate','yyyy-mm-dd') 
			ORDER BY dat_txn desc";
			
    return db_query($sql,"No audit trail was returned");
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
	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");

    $dec = user_price_dec();

    $cols = array(0, 60, 120, 190, 280, 400 , 460, 520, 580, 640);

    $headers = array(_('Date'), _('Time'), _('User'),
    	_('Trans Type'), _('Description'), _('Terminal ID'), _('Action'), _('Old Value'),  _('New Value'));

    $aligns = array('left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left');

	$usr = get_user($user);
	$user_id = $usr['user_id'];
    $params =   array( 	0 => $comments,
    				    1 => array('text' => _('Period'), 'from' => $from,'to' => $to),
                  //  	2 => array('text' => _('Type'), 'from' => ($systype != -1 ? $systypes_array[$systype] : _('All')), 'to' => ''),
                    	2 => array('text' => _('User'), 'from' => ($user != -1 ? $user_id : _('All')), 'to' => ''));

    $rep = new FrontReport(_('Audit Trail'), "AuditTrail", user_pagesize());

    $rep->Font();
    $rep->Info($params, $cols, $headers, $aligns);
    $rep->Header();

    $trans = getTransactions($from, $to, $user);

    while ($myrow=db_fetch($trans))
    {
        $rep->TextCol(0, 1, $myrow['dat_txn']);
        if (user_date_format() == 0)
        	$rep->TextCol(1, 2, $myrow['time_txn']);
        else	
        	$rep->TextCol(1, 2, $myrow['time_txn']);
        $rep->TextCol(2, 3, $myrow['cod_user_id']);
//        $rep->TextCol(3, 4, $systypes_array($myrow['cod_trans_type']));
		$rep->TextCol(3, 4, $myrow['cod_trans_type']);
        $rep->TextCol(4, 5, $myrow['txt_txn_desc']);
        $rep->TextCol(5, 6, str_replace("\\",".",$myrow['cod_term_id']) );
        //if ($myrow['gl_seq'] == null)
       // 	$action = _('Changed');
       // else
       // 	$action = _('Closed');
        $rep->TextCol(6, 7, $myrow['action']);
		$rep->TextCol(7, 8, $myrow['cod_key1_value']);
		$rep->TextCol(8, 9, $myrow['cod_key2_value']);
        //if ($myrow['amount'] != null)
        //	$rep->AmountCol(7, 8, $myrow['amount'], $dec);
        $rep->NewLine(1, 2);
    }
    $rep->Line($rep->row  + 4);
    $rep->End();
}

?>