<?php 
//include_once("conn.inc");
function mygetdata($transid)
{
	$sql = "SELECT *
	FROM " .TB_PREF."epin_mail_jobs 
	WHERE STATUS = 'L'"; 
//	echo $sql;
	$sql_a = db_query($sql);
	 $result = db_fetch($sql_a);
	 return $result;
}
//$result = mygetdata($TransID);
$db_host = "localhost";
$db_user = "simplexaccount";
$db_pass = "simplex";
$db_name = "orcl";

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
	$sql = "SELECT *
	FROM epin_email_jobs 
	WHERE STATUS = 'L'"; 
$s = OCIParse($c, $sql);
   OCIExecute($s, OCI_DEFAULT);
   while (OCIFetch($s)) {
     echo "ID=" . ociresult($s, "ID") .
        ", URL=" . ociresult($s, "URL") . "n";
   }

   // Commit to save changes...
   OCICommit($c);

   // Logoff from Oracle...
   OCILogoff($c);
?>