<?php 
/**********************************************************************
	Copyright (C) Simplex.
***********************************************************************/


function sendmail($type, $body, $authoriser, $order_no, $subject)
{
	global $path_to_root;
	include_once($path_to_root . "/includes/types.inc");
    include_once($path_to_root . "/admin/db/company_db.inc");

	$message = "";
	$recipient = ""; //getrecipients($authoriser);
	
	$from = $_SESSION["wa_current_user"]->loginname;
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	//$headers .= "From: $from" . "\r\n";
	//$headers .= 'Cc: ' . "\r\n";
	
	//$myrow = get_company_prefs();
	//$subject = $myrow["msg_subject"];
	
	// Set up parameters
	switch ($type) {
    case ST_SALESORDER:
		
		$BCC = getCCAddresses(ST_SALESORDER);
		$CC = getauthorisers(ST_SALESORDER);
		$headers .= 'Cc: '. $CC . "\r\n";
		$headers .= 'BCc: '. $BCC . "\r\n";
        $subject = $subject . " Order number " . $order_no ;
		$message = "<p>You have a new Sales Order.</p>
		<p>For your attention.</p> ";
		$message .= "\r\n";
		$message .= $body;
		$body .=  "<br>";
		$body .= msg_contents($order_no);
		
				$strQuery = "select email from users where upper(user_id) in (" . db_escape( strtoupper($_SESSION["wa_current_user"]->loginname) ) 
		. "," . db_escape( strtoupper($authoriser))  .")";
		echo $strQuery;
		$result = db_query($strQuery,"No users were returned");
		
		while ($tmp = db_fetch($result)){
			$recipient =  $tmp['email'] ;
		
			mail($recipient,$subject,$body,$headers);
			echo "recipient = " . $recipient . "<br>";
			echo "headers =" . $headers . "<br>";
		}
							//echo "recipient = " . $recipient . "<br>";
							///echo "subject =" . $subject . "<br>";
							//echo "body =" . $body. "<br>" ;
							//echo "headers =" . $headers . "<br>";
		
        break;
	 
	case ST_AUTORIZATION:
	
		$BCC = getCCAddresses(ST_AUTORIZATION);
		$CC = getauthorisers(ST_AUTORIZATION);
		$headers .= 'Cc: '. $CC . "\r\n";
		$headers .= 'BCc: '. $BCC . "\r\n";
		
        $subject = $subject . " Order number " . $order_no ;
		$message = "<p>Sales Order $order_no has been approved.</p>
		<p>For your attention.</p> ";
		$message .= "\r\n";
		$message .= $body;
		$body .=  "<br>";
		$body .= msg_contents($order_no);
		$requester = get_tranmaker($order_no);
		
		

	
		$strQuery = "select email from users where upper(user_id) in (" . db_escape( strtoupper($_SESSION["wa_current_user"]->loginname) ) 
		. "," . db_escape( strtoupper($requester))  .")";
		//echo $strQuery;
		$result = db_query($strQuery,"No users were returned");
		
		while ($tmp = db_fetch($result)){
			$recipient =  $tmp['email'] ;
		
			mail($recipient,$subject,$body,$headers);
		}
		
					//echo "recipient = " . $recipient . "<br>";
					//echo "subject =" . $subject . "<br>";
					//echo "body =" . $body. "<br>" ;
					//echo "headers =" . $headers . "<br>";
						break;
						
		case ST_CUSTDELIVERY:
		$BCC = getCCAddresses(ST_CUSTDELIVERY);
		$CC = getauthorisers(ST_AUTORIZATION);
		$headers .= 'Cc: '. $CC . "\r\n";
		$headers .= 'BCc: '. $BCC . "\r\n";
		
        $subject = $subject . " Order number " . $order_no ;
		$message = "<p>Sales Order $order_no has been submitted for delivery</p>
		<p>For your attention.</p> ";
		$message .= "\r\n";
		$message .= $body;
		$body .=  "<br>";
		$body .= po_msg_contents($order_no);
		
				$strQuery = "select email from users where upper(user_id) in (" . db_escape( strtoupper($_SESSION["wa_current_user"]->loginname) ) 
		. "," . db_escape( strtoupper($authoriser))  .")";
		echo $strQuery;
		$result = db_query($strQuery,"No users were returned");
		
		while ($tmp = db_fetch($result)){
			$recipient =  $tmp['email'] ;
		
			mail($recipient,$subject,$body,$headers);
		}
		
			break;
		
		case ST_SUPPDELIVERY:
		$BCC = getCCAddresses(ST_CUSTDELIVERY);
		$CC = getauthorisers(ST_AUTORIZATION);
		$headers .= 'Cc: '. $CC . "\r\n";
		$headers .= 'BCc: '. $BCC . "\r\n";
		
        $subject = $subject . " Order number " . $order_no ;
		$message = "<p>Purchase Order $order_no has been submitted for delivery</p>
		<p>For your attention.</p> ";
		$message .= "\r\n";
		$message .= $body;
		$body .=  "<br>";
		$body .= msg_contents($order_no);
		
				$strQuery = "select email from users where upper(user_id) in (" . db_escape( strtoupper($_SESSION["wa_current_user"]->loginname) ) 
		. "," . db_escape( strtoupper($authoriser))  .")";
		echo $strQuery;
		$result = db_query($strQuery,"No users were returned");
		
		while ($tmp = db_fetch($result)){
			$recipient =  $tmp['email'] ;
		
			mail($recipient,$subject,$body,$headers);
		}				//echo "recipient = " . $recipient . "<br>";
							//echo "subject =" . $subject . "<br>";
							//echo "body =" . $body. "<br>" ;
							//echo "headers =" . $headers . "<br>";
		
        break;
	}
	

	
	//echo "recipient = " . $recipient . "<br>";
	//echo "subject =" . $subject . "<br>";
	//echo "body =" . $body. "<br>" ;
	//echo "headers =" . $headers . "<br>";
	

}
function getrecipients($auth)
{
	//$authoriser = getauthoriser($auth);
	$strQuery = "select email from users where upper(user_id) in (" . db_escape( strtoupper($_SESSION["wa_current_user"]->loginname) ) 
	. "," . db_escape( strtoupper($auth))  .")";

echo $strQuery;
	$result = db_query($strQuery,"No users were returned");
	//$tmp = db_fetch($result); 
	$rcpt ="";
	//$row = db_fetch_row($result );

	while ($tmp = db_fetch($result)){
		$rcpt .=  $tmp['email'] . ",";
	}
	return $rcpt;
	//return implode(",", $tmp);
	//return $tmp['email'];
}
function getauthoriser($auth)
{
	$strQuery = "select * from users where upper(user_id) = " . strtoupper(db_escape($auth) );
	$result = db_query($strQuery,"No users were returned");
	echo $strQuery;
	$tmp = db_fetch($result); 
	return $tmp['email'];
}
function getauthorisers($sales_type)
{
	$address = "";
	$strQuery = "select email from users where role_id = ( select approving_role from sales_approval where sales_type=" .$sales_type . ")";
	$result = db_query($strQuery,"No users were returned");
		while ($tmp = db_fetch($result)){
			$recipient =  $tmp['email'] ;
			$address = $address . "," . $recipient;
		}
		return substr($address,1);
}
function get_tranmaker($order_no)
{
	$strQuery = "select * from sales_tran_approval where order_no = " . $order_no;
	$result = db_query($strQuery,"No users were returned");
	//echo $strQuery;
	$tmp = db_fetch($result); 
	return $tmp['requested_by'];
}
function getCCAddresses($type)
{
	$strQuery = "select txt_address from mailing_list where cod_list = (select mail_list_code from sales_approval where sales_type=" . $type . ")";
	$result = db_query($strQuery,"No addresses were returned");
	//echo $strQuery;
	$tmp = db_fetch($result); 
	return $tmp['txt_address'];
}
//----------------------------------------------------------------------------------------
function get_orderdetails($order_no)
{
	$sql = "SELECT so.order_no,so.reference,so.debtor_no,so.deliver_to,line.stk_code,line.quantity, so.ord_date
    		FROM   ".TB_PREF."sales_orders so," 
        	.TB_PREF."sales_order_details line
    		WHERE so.order_no = line.order_no
    		AND so.trans_type = line.trans_type
    		AND so.order_no = ".$order_no;
	
	return db_query($sql, "Could not retrieve orderdetails");
}
//----------------------------------------------------------------------------------------
function get_podetails($order_no)
{
	$sql = "SELECT po.order_no,po.reference,po.supplier_id,line.item_code,line.quantity_ordered, po.ord_date
    		FROM   ".TB_PREF."purch_orders po," 
        	.TB_PREF."purch_order_details line
    		WHERE po.order_no = line.order_no
    		AND po.order_no = ".$order_no;
	
	return db_query($sql, "Could not retrieve po details");
}
//----------------------------------------------------------------------------------------
function get_so_header($order_no)
{
	$sql = "SELECT * FROM ".TB_PREF."sales_orders WHERE order_no=".db_escape($order_no) . " and trans_type=30" ;
	return db_query($sql, "Could not retrieve so_header");
}
function msg_contents($order_no)
{
	$details = get_orderdetails($order_no);
	//$rows = get_so_header($order_no);
	$msg_body = "";		
	$dp_code = "";
	$dp_name= "";
	$invoice_no=0;
	$order_date="";
	
	/*
	while ($row = db_fetch($rows))
	{
		$msg_body .= "Order Number : " . $row['order_no'] . "<br>";
		$msg_body .= "Invoice Number : " . $row['reference'] . "<br>";
		$msg_body .= "DP Code : " . $row['debtor_no'] . "<br>";
		$msg_body .= "DP Name : " . $row['deliver_to'] . "<br>";
	}
	*/
	
	$msg_body .= "ITEMS : " . "<br>";
	while ($line = db_fetch($details))
	{
		$msg_body .= $line['stk_code'] . " : " . $line['quantity'] . "<br>";
		$dp_code = $line['debtor_no'] ;
		$dp_name = $line['deliver_to'];
		$invoice_no = $line['reference'];
		$order_date = $line['ord_date'];
	}
		$msg_body .= "Order Number : " . $order_no. "<br>";
		$msg_body .= "Invoice Number : " . $invoice_no . "<br>";
		$msg_body .= "DP Code : " . $dp_code . "<br>";
		$msg_body .= "DP Name : " . $dp_name . "<br>";
		$msg_body .= "Order Date : " . $order_date . "<br>";
	return $msg_body;
}

function po_msg_contents($order_no)
{
	$details = get_podetails($order_no);
	$msg_body = "";		
	$dp_code = "";
	$dp_name= "";
	$invoice_no=0;
	$order_date="";
	

	$msg_body .= "ITEMS : " . "<br>";
	while ($line = db_fetch($details))
	{
		$msg_body .= $line['item_code'] . " : " . $line['quantity_ordered'] . "<br>";
		$dp_code = $line['supplier_id'] ;
		//$dp_name = $line['deliver_to'];
		$invoice_no = $line['reference'];
		$order_date = $line['ord_date'];
	}
		$msg_body .= "Order Number : " . $order_no. "<br>";
		$msg_body .= "Invoice Number : " . $invoice_no . "<br>";
		$msg_body .= "DP Code : " . $dp_code . "<br>";
		//$msg_body .= "DP Name : " . $dp_name . "<br>";
		$msg_body .= "Order Date : " . $order_date . "<br>";
	return $msg_body;
}
?>