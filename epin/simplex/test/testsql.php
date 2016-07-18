<?php


$path_to_root = "../..";


include($path_to_root . "/includes/session.inc");

$order_no = 35;
$trans_type = 30;
$amount = 450;
	global $systypes_array;
	
	echo 'type 13='. $systypes_array[13];

$sql = "SELECT 1 as Okay FROM ".TB_PREF."sales_order_details
				WHERE ".TB_PREF."sales_order_details.order_no=".$order_no."
				 AND ".TB_PREF."sales_order_details.trans_type=".$trans_type."

				 having sum(".TB_PREF."sales_order_details.quantity*".TB_PREF."sales_order_details.unit_price)=".$amount;


	
echo ($sql);

	
  	$strQuery = "select TAX_GROUPS_ID_SEQ.NEXTVAL from dual" ;//"select code from  ANALYSIS_CODES where id =18";
	$result = db_query($strQuery,"No transactions were returned");
	$tmp = db_fetch($result); //$result['SYSDATE'];
	$id = $tmp[0];
	echo '<br>';
	echo 'id:' .$id . 'end';

?>
