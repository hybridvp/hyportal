<?php 
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/simplex/includes/email_messaging.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

include_once($path_to_root . "/sales/includes/db/sales_types_db.inc");

//add_sales_tran_approval(87,$_SESSION["wa_current_user"]->loginname,'financesuper',30);

//approve_sales_tran_approval(56,$_SESSION["wa_current_user"]->loginname,30);
//$body = get_doc_link($order_no, "&Email This Order", true, ST_SALESORDER, false, 'menu_option', null, 1, 0);
//$body = get_doc_link(54, "&Email This Order", true, ST_SALESORDER, false, 'menu_option', null, 0, 0);
$body = msg_contents(77);
echo 'body=' . $body . "<br>";
echo 'authorisers =' .getauthorisers(30);
sendmail(ST_SALESORDER, $body, '',77, "Sales Order Init");

//sendmail(ST_SALESORDER, $body, $_POST['approved_by1'], $trans_no );//$order_no
//sendmail(30, $body, 'finance',87);
 /*echo 'svr host'. $_SERVER['HTTP_HOST'] . "<br>";
echo 'svr host2'. $_SERVER['SERVER_NAME']  . "<br>"; 

/* foreach($_SERVER as $key_name => $key_value) {
print $key_name . " = " . $key_value . "<br>";
} */

echo 'svr host' . dirname($_SERVER["REQUEST_URI"]) . "<br>";
$approot = substr(dirname(__FILE__),strlen($_SERVER['DOCUMENT_ROOT'])). "<br>";
echo 'approot=' . $approot;

$baseDir = 'http://'.$_SERVER['HTTP_HOST'].(dirname($_SERVER['SCRIPT_NAME']) != '/' ? dirname($_SERVER["SCRIPT_NAME"]).'/' : '/');
echo 'baseDir=' . $baseDir . "<br>";

echo 'http host' .$_SERVER['SERVER_PROTOCOL'].'://'. $_SERVER['HTTP_HOST'] . "<br>";
?>