<?php
//$conn = oci_connect('SCOTT','TIGER') or die;

$path_to_root = "../..";


include($path_to_root . "/includes/session.inc");
$sql = 'BEGIN sayHello(:name, :message); END;';

$stmt = db_parse_proc($sql) ;//oci_parse($conn,$sql);

//  Bind the input parameter
oci_bind_by_name($stmt,':name',$name,32);

// Bind the output parameter
oci_bind_by_name($stmt,':message',$message,32);

// Assign a value to the input 
$name = 'Harry';

db_execute_proc ($stmt,$sql );  //oci_execute($stmt);

// $message is now populated with the output value
print "$message\n";
?>
