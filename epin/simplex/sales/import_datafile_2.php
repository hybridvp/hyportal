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
	$sql = "SELECT count(*) FROM ".TB_PREF."pin_details where batch_no = $file_numbr";
    $result = db_query($sql, "Can not look up file number");
    $row = db_fetch_row_r($result);
    if (!$row[0]) return 0;
    return 1; 
}
function StockExists($facevalue) 
{
    $sql = "SELECT * FROM ".TB_PREF."stock_master where facevalue = $facevalue";
    $result = db_query($sql, "Can not look up facevalue");
    $row = db_fetch_row_r($result);
    if (!$row[0]) return 0;
    return 1;
}
function getStockId($facevalue) 
{
    $sql = "SELECT stock_id FROM ".TB_PREF."stock_master where facevalue = $facevalue";
    $result = db_query($sql, "Can not look up facevalue");
    $row = db_fetch_row_r($result);
    if (!$row[0]) return 0;
    return $row[0];
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
	WHERE pin.batch_no=".db_escape($file_number) ; 
	return db_query($sql);
} 
//check if item is kept as active or inactive
function GetActiveStatus($stock_id)
{	
    $sql = "SELECT keep_inactive FROM ".TB_PREF."stock_master where stock_id = $stock_id";
    $result = db_query($sql, "Can not look up stock_id active status");
    $row = db_fetch_row_r($result);
    if (!$row[0]) 
		return 0;
	else 
	{
		if( $row[0] ==1 )
			return 0;
			else return 1;
	}
} 
//function validateImport($total_lines, $qty, $start_seq)
function stock_items_list_row($label, $name, $selected_id=null, $all_option=false, $submit_on_change=false)
{
	echo "<tr>\n";
	stock_items_list_cells($label, $name, $selected_id, $all_option, $submit_on_change);
	echo "</tr>\n";
}
$action = 'import'; 
if (isset($_GET['action'])) $action = $_GET['action'];
if (isset($_POST['action'])) $action = $_POST['action'];
 
page("E-Pin file Import - 2");

if (isset($_POST['import'])) {
	if (isset($_FILES['imp']) && $_FILES['imp']['name'] != '') {
		$filename = $_FILES['imp']['tmp_name'];
		//$file_numbr = "";
		$sep = $_POST['sep'];
		
		$total_pin_lines = 0;
		$batch_no = 0;
		$stock_id = "";
		
		$comment = "#";
		$group = "NONE";
		//$ErrorLines = array();		//$Files = array();
		$db_err= "";
		$fp = @fopen($filename, "r");
		if (!$fp)
			die("can not open file $filename"); 
		global $db;
		//$lines = $b = 0;
		$success = false; 
		$file_numbr;
		$faceval;
		 
		$fieldLengths=array(16,1,15);
		$line_no = 0;
		
		//if(!isset($_POST['stock_id']))
		//{
		//	display_error("No item selected");
		//	return;
		//}	

		//echo 'cfg values: batch:' . $config_values['FaceValue'];
			  //$active = GetActiveStatus($_POST['stock_id']);
		while ( !feof($fp) ) {  // loop through lines, also look for errors
			  $line = trim(fgets($fp)); 
			// while (trim($line) != "") {
		  if ($line && !ereg("^$comment", $line)) {
				$pieces = explode(":", $line);
				$option = trim($pieces[0]);
				$value = trim($pieces[1]);
				$config_values[$option] = $value; 
			  }
			  		
					
					if( $line_no > 5) {
					$file_numbr = $config_values['Batch'];		
					$faceval = $config_values['FaceValue'];	
						if(FileExists($file_numbr) )
						{
							display_notification( _("Duplicate file number exists, file may have been loaded before"));
							hyperlink_back();
							return;
						}  			 
						if(StockExists($faceval) )
						{	
							$stock_id = getStockId($faceval);
						} 
						else
						{	
							//echo 'cfg values: batch:' . $config_values['FaceValue'];
							display_notification( _("No stock item value matches with the file face value"));
							hyperlink_back();
							return;
						}
					}
				$cards2 = array(); 
				$i = 0;
					
					if($line_no >= 39 && strrpos($line,"[") === false && trim($line) != ""){  //if line 40 and above start
						foreach($fieldLengths as $fieldLength){
								$cards2[] = substr($line, $i, $fieldLength);
								$i += $fieldLength;
						}
					$total_pin_lines++ ;
					$seq_num = $cards2[0];  
					$space = $cards2[1];
					$pin = $cards2[2];
					
					//$batch_no =	$config_values['Batch'];
					$start_seq = $config_values['Start_Sequence'];
					$batch_quantity = $config_values['Quantity'];

	
			  //echo 'cfg values: batch:' . $config_values['Batch'];
			  //echo 'cfg values: q:' . $config_values['Quantity'];
			  //echo 'cfg values: f:' . $config_values['FaceValue'];
					
					$id = get_nxt_seq('tmp_pin_details');

					$sql = "INSERT INTO ".TB_PREF."tmp_pin_details			(batch_no,sequence_number,pin,load_date,file_generation_date,denomination,stock_id,sold_date,status,sales_order_no,customer_no,delivery_date,created_by,last_modified_date,flg_mnt_status,filename,batch_qty,cardprefix,facevalue,startdate,enddate,currency,resid1,resleft1,resactivedays1,resid2,resleft2,resactivedays2,resid3,resleft3,resactivedays3,resid4,resleft4,resactivedays4,resid5,resleft5,resactivedays5,resid6,resleft6,resactivedays6,resid7,resleft7,resactivedays7,resid8,resleft8,resactivedays8,resid9,resleft9,resactivedays9,resid10,resleft10,resactivedays10,start_sequence,location
)

VALUES (". $config_values['Batch']. ",". $seq_num. "," . db_escape($pin). ",SYSDATE,". " to_date(".$config_values['Batch'].",'yyyymmdd') " . " ,". $config_values['FaceValue'] ."," . db_escape($stock_id) . ",null,'N',0,0,null," . db_escape($_SESSION["wa_current_user"]->loginname). ",SYSDATE,'U',". db_escape($filename) .",". $config_values['Quantity'] .",". db_escape($config_values['CardPrefix']) .",". $config_values['FaceValue'] .",". $config_values['StartDate'] .",". $config_values['StopDate'] .",". db_escape($config_values['Currency']) .",". $config_values['ResID1'] .",". $config_values['Resleft1'] .",". $config_values['ResActiveDays1'] .",".

 $config_values['ResID2'] .",". $config_values['Resleft2'] .",". $config_values['ResActiveDays2'] .",".
 $config_values['ResID3'] .",". $config_values['Resleft3'] .",". $config_values['ResActiveDays3'] .",".
 $config_values['ResID4'] .",". $config_values['Resleft4'] .",". $config_values['ResActiveDays4'] .",".
 $config_values['ResID5'] .",". $config_values['Resleft5'] .",". $config_values['ResActiveDays5'] .",".
 $config_values['ResID6'] .",". $config_values['Resleft6'] .",". $config_values['ResActiveDays6'] .",".
 $config_values['ResID7'] .",". $config_values['Resleft7'] .",". $config_values['ResActiveDays7'] .",".
 $config_values['ResID8'] .",". $config_values['Resleft8'] .",". $config_values['ResActiveDays8'] .",".
 $config_values['ResID9'] .",". $config_values['Resleft9'] .",". $config_values['ResActiveDays9'] .",".
 $config_values['ResID10'] .",". $config_values['Resleft10'] .",". $config_values['ResActiveDays10'] .",".
 $config_values['Start_Sequence'] .	",'DEF')";
							
						begin_transaction();
						//$result = oci_parse($db, $sql); 
						//$ok = oci_execute($result, OCI_NO_AUTO_COMMIT);		
						$result = db_query($sql, "Could not insert data");
						$err = oci_error($result); 
						if( $err )
						{
							$db_err ="ERROR";
							//oci_rollback($db);
							cancel_transaction();
							return;
						}
						
						
					} ///end if line 40
			echo "\n";
			//echo 'filenumber :' .$file_numbr;
			//echo 'db error:'.$db_err;
			$line_no ++;					
			}
		@fclose($fp);
	

	} else display_error("No datafile selected");
	/*$val_ret = validateImport();
	if ($val_ret != true)
	{
		return;
	} */
		
		/*$sql = 'BEGIN pkg_mnt_epin.validate_epin_file(:total_lines, :quantity, :start_seq, :batch_number, :var_err_msg); END;';
		$result = $stmt = oci_parse($db,$sql);
		//  Bind the input parameter
		oci_bind_by_name($stmt,':total_lines',$total_pin_lines,10);
		oci_bind_by_name($stmt,':quantity',$batch_quantity,10);
		oci_bind_by_name($stmt,':start_seq',$start_seq,10);
		oci_bind_by_name($stmt,':batch_number',$batch_no,30);
		
		// Bind the output parameter
		oci_bind_by_name($stmt,':var_err_msg',$message,200);

		oci_execute($stmt, OCI_DEFAULT);
		$err = oci_error($result);
		if( $err ){
							$db_err ="ERROR";
							//oci_rollback($db);
							return;
							cancel_transaction();
		}
		if ($message != "")
		{
			display_notification( _("Invalid File, " . $message));
					hyperlink_back();
					return;
		}*/
	
	if ( empty($db_err) )
	{
		$sql = 'BEGIN pkg_mnt_epin.ins_pin_details(:file_numbr); END;';
		$result = $stmt = oci_parse($db,$sql);
		//  Bind the input parameter
		oci_bind_by_name($stmt,':file_numbr',$file_numbr,30);
		
		oci_execute($stmt, OCI_DEFAULT);
		$err = oci_error($result);
		if( $err ){
					$db_err ="ERROR";
					//oci_rollback($db);
					return;
					cancel_transaction();
		}
		$ip = preg_quote($_SERVER['REMOTE_ADDR']);
		add_nonfin_audit_trail(0,0,0,0,'DATAFILE IMPORT','A',$ip,'DATAFILE BATCH NO:' . $file_numbr . " WAS IMPORTED FROM " . $filename);

	if ( empty($db_err) )
	{
		// Everything OK so commit
		//oci_commit($db);
		
		//($cod_key1,$cod_key1_value,$cod_key2,$cod_key2_value,$trans_type,$mnt_action,$term_id, $txn_descr='')
		commit_transaction();
		display_notification("Items successfully imported. Total :" . $total_pin_lines);
		hyperlink_no_params($path_to_root."/simplex/sales/inquiry/epin_inquiry.php?file_number=". $file_numbr. "&stat=U", _("View Imported EPINs =".  $total_pin_lines));
	}
	else
		{ 
			//oci_rollback($db); 
			cancel_transaction();
		}

	}
	//finally , clean up
	//cancel_transaction();
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
	//stock_items_list_row("", 'stock_id');
	//stock_items_list_sales_cells('Select an Item','stock_id');
		//echo "<tr>\n";
	//stock_items_list("Select an item:", 'stock_id');
	//echo "</tr>\n";
    text_row("Field separator:", 'sep', $_POST['sep'], 2, 1);
    label_row("ePIN Import File:", "<input type='file' id='imp' name='imp'>");

    end_table(1);

    submit_center('import', "Import ePIN File");

    end_form();
	end_page();
}