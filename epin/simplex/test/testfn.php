<?php


$path_to_root = "../..";


include($path_to_root . "/includes/session.inc");
global $db;
//echo " hello there";

//echo 	"hello ".strlen("Planned_From_Quotation#40") ;

//echo 	"hello ".length("Planned_From_Quotation#40") ;
///echo (substr("Planned_From_Quotation#40",23,strlen("Planned_From_Quotation#40")-23 ));

$sql = 'BEGIN pkg_mnt_epin.validate_epin_file(:total_lines, :quantity, :start_seq, :batch_number, :var_err_msg); END;';
		$result = $stmt = oci_parse($db,$sql);
		//  Bind the input parameter
		oci_bind_by_name($stmt,':total_lines',$total_lines, 10);
		oci_bind_by_name($stmt,':quantity',$qty,10);
		oci_bind_by_name($stmt,':start_seq',$start_seq,30);
		oci_bind_by_name($stmt,':batch_number',$batch_no,30);
		
		// Bind the output parameter
		oci_bind_by_name($stmt,':var_err_msg',$message,200);
		
		$total_lines = 200;
		$qty = 200;
		$start_seq = 2009092200000001;
		$batch_no = 20090927;
		oci_execute($stmt, OCI_DEFAULT);
		$err = oci_error($result);
		if( $err ){
							$db_err ="ERROR";
							echo $err['message'];
		}
		print "$message\n";


  

?>
