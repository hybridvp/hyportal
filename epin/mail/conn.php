<?php 
$db_host = "localhost";
$db_user = "epin";
$db_pass = "epin";
$db_name = "epindb";

/* mysql_connect($db_host, $db_user, $db_pass) or die("Could not connect to database server");
mysql_select_db($db_name) or die ("Could not connect to database name"); */
$c=OCILogon($db_user, $db_pass, $db_name);
   if ( ! $c ) {
   
     $error = "Unable to connect to db server: " . var_dump( OCIError() );
	 echo $error;
	 	$to = "lolapegba@bluechiptech.biz";
		$subject = "Email Job Error";
		$body = $error;
		if (mail($to, $subject, $body)) {
		  //echo("<p>Message successfully sent!</p>");
		 } 
		 die();
	}
$s = OCIParse($c, "select * from tab1");
   OCIExecute($s, OCI_DEFAULT);
   while (OCIFetch($s)) {
     echo "COL1=" . ociresult($s, "COL1") .
        ", COL2=" . ociresult($s, "COL2") . "n";
   }

   // Commit to save changes...
   OCICommit($c);

   // Logoff from Oracle...
   OCILogoff($c);
?>