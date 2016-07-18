<?php


//$path_to_root = "../..";


//include($path_to_root . "/includes/session.inc");

//echo " hello there";

//echo 	"hello ".strlen("Planned_From_Quotation#40") ;

echo 	 date("d-m-y h:i:s");

$result = exec('openssl rsa -in C:\simplex\simplexprivkey.p8 -pubout -out C:\simplex\simplexpubkey2.crt') ;
echo $result;

?>
