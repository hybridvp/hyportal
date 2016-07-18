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

//function validateImport($total_lines, $qty, $start_seq)
page("E-PIN Activation");

    start_form(true);

    start_table("$table_style2 width=40%"); 
 

    table_section_title("E-PIN Activation");

    text_row("Batch No:", 'batch_no', '', 10, 20);
    //label_row("ePIN Import File:", "<input type='file' id='imp' name='imp'>");

    end_table(1);

    submit_center('activate', "Activate");

    end_form();
	end_page();
?>