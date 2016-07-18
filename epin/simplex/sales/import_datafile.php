<?php
/**********************************************
Author: 
***********************************************/
$page_security = 'SA_SUPPTRANSVIEW' ; //'SA_CSVIMPORT';
$path_to_root="../..";

include($path_to_root . "/includes/session.inc");
add_access_extensions();

include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/inventory/includes/inventory_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_codes_db.inc");
include_once($path_to_root . "/dimensions/includes/dimensions_db.inc");
include_once($path_to_root . "/simplex/includes/cypher.inc"); 
 
function check_stock_id($stock_id) {
    $sql = "SELECT * FROM ".TB_PREF."stock_master where stock_id = $stock_id";
    $result = db_query($sql, "Can not look up stock_id");
    $row = db_fetch_row_r($result);
    if (!$row[0]) return 0;
    return 1;
}
function FileExists($file_numbr)
{
	$sql = "SELECT count(*) FROM ".TB_PREF."pin_details where file_number = $file_numbr";
    $result = db_query($sql, "Can not look up file number");
    $row = db_fetch_row_r($result);
    if (!$row[0]) return 0;
    return 1;
}
function get_nxt_seq($table_name)
{
	 //$nxt_seq = 0;
	 $sql = "SELECT max(sequence_number) FROM ".TB_PREF. $table_name;
	 $result = db_query($sql, "Can not get sequence number");
     $row = db_fetch_row_r($result);
	 if (!$row[0]) return 1;
	 return $row[0] + 1; 
}
function GetTmpPin($file_number)
{
	$sql = "SELECT * FROM "
		.TB_PREF."tmp_pin_details pin 
	WHERE pin.file_number=".db_escape($file_number) ; 
	return db_query($sql);
} 
$action = 'import';
if (isset($_GET['action'])) $action = $_GET['action'];
if (isset($_POST['action'])) $action = $_POST['action'];
 
page("E-Pin file Import");

if (isset($_POST['import'])) {
	if (isset($_FILES['imp']) && $_FILES['imp']['name'] != '') {
		$filename = $_FILES['imp']['tmp_name'];
		$file_numbr = "";
		$sep = $_POST['sep'];
		$ErrorLines = array();
		$Files = array();
		$db_err= "";
		$fp = @fopen($filename, "r");
		if (!$fp)
			die("can not open file $filename");
		global $db;
		//$lines = $b = 0;
		$success = false;
		 
		$fieldLengths=array(20,5,10,20,8,10);

		while ( !feof($fp) ) {  // loop through lines, also look for errors
				$line = fgets($fp);
				$cards2 = array(); 
				$i = 0;
					foreach($fieldLengths as $fieldLength){
						//$t=substr($line, 0, $fieldLength);
						//echo $t . $sep; //field/column separator
						//$line=substr($line,$fieldLength);
							$cards2[] = substr($line, $i, $fieldLength);
							$i += $fieldLength;
					}
					//print_r($cards2);
					//$sql_values = join(",", $cards2); 
					//print $sql_values;
				//if(isset($cards2[0]))  //skip blank lines
  				//{
					$pin = $cards2[0];  
					$denom = $cards2[1];
					$batch_no = $cards2[2]; 
					$pincode = $cards2[3];
					$dat_file_gen = $cards2[4]; 
					$file_num =  $cards2[5];
					$seq_num = get_nxt_seq('tmp_pin_details');
					$sql = "INSERT INTO ".TB_PREF."tmp_pin_details
							(file_number, sequence_number, pin, load_date, file_generation_date,
							   denomination,stock_id, sold_date, status, sales_order_no,
							   customer_no, delivery_date, created_by, last_modified_date,flg_mnt_status,filename)
							VALUES (". db_escape($file_num). ",". $seq_num. "," . db_escape($pin). ",SYSDATE,SYSDATE ,$denom,'M100A',null,'N',0,0,null," . db_escape($_SESSION["wa_current_user"]->loginname). ",SYSDATE,'U',". db_escape($filename) . ")";
							
					$res = db_query($sql, "Could not insert data");
					$err = oci_error($res); 
					if( $err )
					{
						$db_err ="ERROR";
						return;
					}
					
					$file_numbr = $file_num;	
				//}

			echo "\n";
			//echo $sql;
				//$pos1 = stripos($line, "ERROR"); //check for occurence of error in file
				if (preg_match("/error/i", $line)) {
							$ErrorLines[] = $line;
				}
				if (count($ErrorLines)) {
						$Files[$filename] = $ErrorLines;
						$ErrorLines = array();
					}						
						/*if ($success == true )
						{
							db_query($sql, "Could not update supplier data");
						}*/

			}
		@fclose($fp);
		if (isset($Files) ){
			foreach ($Files as $Filename => $Errors) {
				echo "<p><strong>$Filename</strong></p>";
					foreach ($Errors as $Error) {
						 echo "<p>$Error</p>";
					}
			}
		}

	} else display_error("No datafile selected");
	
	if ( empty($db_err) )
	{
		if(FileExists($file_numbr) )
		{
			display_notification( _("Duplicate file number exists, file may have been loaded before"));
			hyperlink_back();
			return;
		}
		
		
		/*$rows = GetTmpPin($file_numbr) ;
		while ($row = db_fetch($rows)) {
		$pin_tab_seq_num = get_nxt_seq('pin_details');
		$sql = "INSERT INTO ".TB_PREF."pin_details
							(file_number, sequence_number, pin, load_date, file_generation_date,
							   denomination,stock_id, status, sales_order_no,
							   customer_no, created_by, last_modified_date,flg_mnt_status) VALUES ( ".
							   db_escape($row['file_number']). ", ". $pin_tab_seq_num. "," . db_escape($row['pin']) . ", to_date("
							   . db_escape($row['load_date']). ",'yyyy-mm-dd hh24:mi:ss')" 
							   . " , to_date(". db_escape($row['file_generation_date']). ",'yyyy-mm-dd hh24:mi:ss'), ". $row['denomination'] 		   . ",'M100A', ". db_escape($row['status']). ", ". $row['sales_order_no'] .", ". $row['customer_no'] 
							   . ", ". db_escape($row['created_by']) . ", to_date(".  db_escape($row['last_modified_date'])
							   . ", 'yyyy-mm-dd hh24:mi:ss'),". db_escape($row['flg_mnt_status']) . ")";
		db_query($sql, "Could not insert datafile" .  $filename);
		}*/
		$sql = 'BEGIN pkg_mnt_epin.ins_pin_details(:file_numbr); END;';
		$result = $stmt = oci_parse($db,$sql);
		//  Bind the input parameter
		oci_bind_by_name($stmt,':file_numbr',$file_numbr,30);
		
		oci_execute($stmt);
		$err = oci_error($result);
		//$ip = preg_quote($_SERVER['REMOTE_ADDR']);
		//add_audit_trail(ST_INVADJUST, $adj_id, $date_);
		display_notification("Items successfully imported");
		hyperlink_no_params($path_to_root."/simplex/sales/inquiry/epin_inquiry.php?file_number=". $file_numbr. "&stat=U", _("View Imported EPINs"));
	}
	//finally , clean up
		$sql = "DELETE ".TB_PREF."tmp_pin_details WHERE filename=". db_escape( $filename) ;
		db_query($sql, "Could not clean up temp file" .  $filename);
}

echo "<br><br>";

if ($action == 'import') {
    start_form(true);
//echo 'files='.$_FILES['imp'];
    start_table("$table_style2 width=40%");


    if (!isset($_POST['sep']))
	$_POST['sep'] = "|";
	//echo pinencrypt("ZTN12375678902346789") . " ||";
	//echo pindecrypt("MRoEAAhpQtN3kXRgp69aToEbds8Zycp9iWXASmfonDQ=");
    table_section_title("E-PIN Import");
    text_row("Field separator:", 'sep', $_POST['sep'], 2, 1);
    label_row("ePIN Import File:", "<input type='file' id='imp' name='imp'>");

    end_table(1);

    submit_center('import', "Import ePIN File");

    end_form();
	end_page();
}
