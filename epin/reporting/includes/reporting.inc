<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
// Link to printing single document with bulk report template file.
// Ex. label_cell(print_document_link($myrow['order_no'], _("Print")), $type);
// or display_note(print_document_link($order_no, _("Print this order")));
// You only need full parameter list for invoices/credit notes

function print_document_link($doc_no, $link_text, $link=true, $type_no, 
	$icon=false, $class='printlink', $id='', $email=0, $extra=0)
{
	global $path_to_root;
	include_once($path_to_root . "/includes/types.inc");
	include_once($path_to_root . "/simplex/includes/db/email_jobs.inc");

	$url = $path_to_root.'/reporting/prn_redirect.php?';

	switch ($type_no)
	{
		case ST_SALESQUOTE :
			$rep = 111;
			// from, to, currency, bank acc, email, quote, comments
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => '', 
				'PARAM_3' => $email, 
				'PARAM_4' => '');
			break;
		case ST_SALESORDER :
			$rep = 109;
			// from, to, currency, bank acc, email, quote, comments
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => '', 
				'PARAM_3' => $email, 
				'PARAM_4' => 0, 
				'PARAM_5' => '');
			break;
		case ST_CUSTDELIVERY :
			$rep = 110;
			// from, to, email, comments
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => $email,
				'PARAM_3' => $extra);
			break;
		case ST_SALESINVOICE : // Sales Invoice
		case ST_CUSTCREDIT : // Customer Credit Note
			$rep = 107;
			// from, to, currency, bank acc, email, paylink, comments, type
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => '', 
				'PARAM_3' => $email, 
				'PARAM_4' => '',
				'PARAM_5' => '', 
				'PARAM_6' => $type_no);
			break;
		case ST_PURCHORDER :
		case ST_PURCHREQ : 
			$rep = 209;
			// from, to, currency, bank acc, email, comments
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => '', 
				'PARAM_3' => $email, 
				'PARAM_4' => '');
			break;
		case ST_CUSTPAYMENT :
			$rep = 112;
			// from, to, currency, bank acc, email, comments
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => '', 
				'PARAM_4' => '');
			break;
		case ST_SUPPAYMENT :
			$rep = 210;
			// from, to, currency, bank acc, email, comments
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => '', 
				'PARAM_3' => $email, 
				'PARAM_4' => '');
			break;
//		default: $ar = array();
	}
	$url_2 = print_link_urlonly($link_text, $rep, $ar, "", $icon, $class, $id);
	//echo '3mail =' . $email;
	if($email <> 0)
	{
		
		//$sender = "ET";
		//$recipient ="ET";
		//$status='L';
		////add_email_job($sender,$recipient,'','','',$status,$url_2);
		//				$sql = "INSERT INTO ".TB_PREF."epin_email_jobs"
		//. " (id,email_sender,email_recipient,email_bcc,email_cc,email_attachment,status,logged_date,url)
		//	VALUES( EPIN_EMAIL_JOBS_ID_SEQ.nextval,"    
		//	.	db_escape($sender)	.", "
		//	.db_escape($recipient)	.", "
		//	.	db_escape($bcc)	.", "
		//	.db_escape($cc)	.", "
		//	. db_escape($attach). ","
		//	.db_escape($status)	.", SYSDATE, "
		//	. db_escape($url_2) .")";
		//	//echo $sql;
		//db_query($sql, "Cannot log email job info");
	
	}
	//echo 'url=' . $url;
	return print_link($link_text, $rep, $ar, "", $icon, $class, $id);;
}
//
//	Get doucument Link
//
function get_doc_link($doc_no, $link_text, $link=true, $type_no, 
	$icon=false, $class='printlink', $id='', $email=0, $extra=0)
{
	global $path_to_root;
	include_once($path_to_root . "/includes/types.inc");
	include_once($path_to_root . "/simplex/includes/db/email_jobs.inc");

	$path_to_rep = "http://" . $_SERVER['SERVER_NAME'] . "/" . APP_NAME ;
	$url = $path_to_rep.'/reporting/prn_redirect.php?';
	
	
	switch ($type_no)
	{
		case ST_SALESQUOTE :
			$rep = 111;
			// from, to, currency, bank acc, email, quote, comments
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => '', 
				'PARAM_3' => $email, 
				'PARAM_4' => '');
			break;
		case ST_SALESORDER :
			$rep = 109;
			// from, to, currency, bank acc, email, quote, comments
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => '', 
				'PARAM_3' => $email, 
				'PARAM_4' => 0, 
				'PARAM_5' => '');
			break;
		case ST_CUSTDELIVERY :
			$rep = 110;
			// from, to, email, comments
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => $email,
				'PARAM_3' => $extra);
			break;
		case ST_SALESINVOICE : // Sales Invoice
		case ST_CUSTCREDIT : // Customer Credit Note
			$rep = 107;
			// from, to, currency, bank acc, email, paylink, comments, type
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => '', 
				'PARAM_3' => $email, 
				'PARAM_4' => '',
				'PARAM_5' => '', 
				'PARAM_6' => $type_no);
			break;
		case ST_PURCHORDER :
		case ST_PURCHREQ : 
			$rep = 209;
			// from, to, currency, bank acc, email, comments
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => '', 
				'PARAM_3' => $email, 
				'PARAM_4' => '');
			break;
		case ST_CUSTPAYMENT :
			$rep = 112;
			// from, to, currency, bank acc, email, comments
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => '', 
				'PARAM_4' => '');
			break;
		case ST_SUPPAYMENT :
			$rep = 210;
			// from, to, currency, bank acc, email, comments
			$ar = array(
				'PARAM_0' => $doc_no, 
				'PARAM_1' => $doc_no, 
				'PARAM_2' => '', 
				'PARAM_3' => $email, 
				'PARAM_4' => '');
			break;
			//		default: $ar = array();
	}
	$url = print_link_urlonly($link_text, $rep, $ar, "", $icon, $class, $id);
	return  $url;
}
//
//	Universal link to any kind of report.
//
function print_link($link_text, $rep, $pars = array(), $dir = '', 
	$icon=false, $class='printlink', $id='')
{
	global $path_to_root, $pdf_debug;

	$url = $dir == '' ?  $path_to_root.'/reporting/prn_redirect.php?' : $dir;

	$id = default_focus($id);
	foreach($pars as $par => $val) {
		$pars[$par] = "$par=".urlencode($val);
	}
	$pars[] = 'REP_ID='.urlencode($rep);
	$url .= implode ('&', $pars);

	if ($class != '')
		$class = $pdf_debug ? '' : " class='$class'";
	if ($id != '')
		$id = " id='$id'";
	$pars = access_string($link_text);
	if (user_graphic_links() && $icon)
		$pars[0] = set_icon($icon, $pars[0]);
		
	return "<a target='_blank' href='$url'$id$class $pars[1]>$pars[0]</a>";
}
function print_link_urlonly($link_text, $rep, $pars = array(), $dir = '', 
	$icon=false, $class='printlink', $id='')
{
	global $path_to_root, $pdf_debug;
	
	$path_to_rep = "http://" . $_SERVER['SERVER_NAME'] . "/" . APP_NAME ;
	$url = $dir == '' ?  $path_to_rep.'/reporting/prn_redirect.php?' : $dir;

	$id = default_focus($id);
	foreach($pars as $par => $val) {
		$pars[$par] = "$par=".urlencode($val);
	}
	$pars[] = 'REP_ID='.urlencode($rep);
	$url .= implode ('&', $pars);

	if ($class != '')
		$class = $pdf_debug ? '' : " class='$class'";
	if ($id != '')
		$id = " id='$id'";
	$pars = access_string($link_text);
	if (user_graphic_links() && $icon)
		$pars[0] = set_icon($icon, $pars[0]);
		
	return $url;
}

?>