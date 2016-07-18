<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
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
	/* 	$sql = "select to_char(dat_txn,'dd/mm/yyyy') dat_txn, 			to_char(dat_txn,'HH24:MM:SS') time_txn, 			cod_user_id,cod_trans_type,txt_txn_desc,cod_term_id,decode(cod_mnt_action,'A' ,'ADD','D','DELETE','I','INQUIRY') action,
cod_key1_value,cod_key2_value
		FROM ".TB_PREF."audit_trail_nonfin a "; */
	$sql = "select  file_name,order_no,line_no,customer_name,quantity,start_serial,end_serial,stock_id,denomination,logged_by,reference,logged_date from (
	select b.file_name,a.order_no,a.line_no,customer_name,quantity,b.start_serial || '-' start_serial,b.end_serial, a.Stock_id,denomination,logged_by, c.reference,a.logged_date
			FROM pin_mailer_jobs a, pin_mailer_jobs_detail b ,sales_orders c where 1=1 
			AND a.order_no = c.order_no AND b.order_no = c.order_no
			AND a.order_no = b.order_no AND a.line_no = b.line_no ";
	$sql .= "AND a.logged_date >= to_date('$fromdate','yyyy-mm-dd')
			AND a.logged_date <= to_date('$todate','yyyy-mm-dd') 
			";
	$sql .= " union 
			select y.file_name,x.order_no,x.line_no,customer_name,quantity,
			y.start_serial || start_serial,y.end_serial,x.stock_id,denomination,logged_by, z.reference ,x.logged_date
			FROM data_pin_mailer_jobs x, data_pin_mailer_jobs_detail y ,sales_orders z where 1=1 AND x.order_no = z.order_no
			AND y.order_no = z.order_no AND x.order_no = y.order_no 
			AND x.line_no = y.line_no ";
			
			//AND a.status !='' ";
		
	if ($user != -1)	
		$sql .= " AND a.logged_by='$user' ";
	if ($customer_no != -1)	
		$sql .= " AND a.customer_no='$customer_no' ";
	$sql .= "AND x.logged_date >= to_date('$fromdate','yyyy-mm-dd')
			AND x.logged_date <= to_date('$todate','yyyy-mm-dd') 
			) ORDER BY logged_date desc";
			echo $sql;
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
		//130, 30, 30,70,30,80,80,4040,70
    $cols = array(0, 50,180, 220, 260, 330 , 410, 470, 300, 510, 550, 580, 650); //array(0, 60, 120, 180, 300, 120 , 120, 60, 120, 120);

    $headers = array(_('Invoice #'), _('File'), _('Order #'), _('Item #'),
    	 _('Customer name'), _('quantity'), _('Start Serial'), _('End Serial'),  _('Stock ID') ,  _('FaceValue'), _('User'), _('Date') );

    $aligns = array('left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'right', 'right' , 'left', 'left');
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
		$rep->TextCol(0, 1, $myrow['reference']);
        $rep->TextCol(1, 2, $myrow['file_name']);
        //if (user_date_format() == 0)
        //	$rep->TextCol(1, 2, $myrow['logged_date']);
        //else	
        //	$rep->TextCol(1, 2, $myrow['logged_date']);
		$rep->TextCol(2, 3, $myrow['order_no']);
        $rep->TextCol(3, 4, $myrow['line_no']);
		$rep->TextCol(4, 5, $myrow['customer_name']);
        $rep->TextCol(5, 6, $myrow['quantity']);
        $rep->TextCol(6, 7, $myrow['start_serial']);
        //if ($myrow['gl_seq'] == null)
       // 	$action = _('Changed');
       // else
       // 	$action = _('Closed');
        $rep->TextCol(7, 8, $myrow['end_serial']);
		$rep->TextCol(8, 9, $myrow['stock_id']);
		$rep->TextCol(9, 10, $myrow['denomination']);
		$rep->TextCol(10, 11, $myrow['logged_by']);
		
        //if ($myrow['amount'] != null)
        //	$rep->AmountCol(7, 8, $myrow['amount'], $dec);
        $rep->NewLine(1, 2);
    }
    $rep->Line($rep->row  + 4);
    $rep->End();
}

?>