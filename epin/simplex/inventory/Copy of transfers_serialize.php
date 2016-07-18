<?php
/**********************************************************************
    Copyright (C) SIMPLEX
    @author laolu olapegba
***********************************************************************/
$page_security = 'SA_LOCATIONTRANSFER';
$path_to_root = "../..";
include_once($path_to_root . "/purchasing/includes/po_class.inc");

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_db.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/simplex/purchasing/includes/ui/ui_funcs.php");


 include_once($path_to_root . "/includes/ui/items_cart.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/inventory/includes/stock_transfers_ui.inc");
//include_once($path_to_root . "/simplex/transfers/includes/stock_transfers_ui.inc");
include_once($path_to_root . "/inventory/includes/inventory_db.inc"); 
//include_once($path_to_root . "/simplex/includes/ui/ui_lists.php");
 if (isset($_GET['TransID'])){
	$_POST['TransID'] = $_GET['TransID'];
	} 
$TransID = $_GET['TransID'];
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Serialize Transfer Items"), false, false, "", $js);

/* if (list_updated('units')) 
	$Ajax->activate('grn_items'); */
//---------------------------------------------------------------------------------------------------------------

if (isset($_GET['AddedID']))
{
	$trans_no = $_GET['AddedID'];
	$trans_type = ST_LOCTRANSFER;

	display_notification_centered(_("Inventory transfer has been processed"));
	display_note(get_trans_view_str($trans_type, $trans_no, _("&View this transfer")));

	hyperlink_no_params($_SERVER['PHP_SELF'], _("Enter &Another Inventory Transfer"));

	display_footer_exit();
}

//--------------------------------------------------------------------------------------------------

//if ((!isset($_GET['TransID']) || $_GET['TransID'] == 0) )  //&& !isset($_SESSION['PO'])
if ((!isset($_GET['TransID']) || $_GET['TransID'] == 0)  && !isset( $_POST['transidhidden']))
{
	die (_("This page can only be opened if a Transaction # of a received stock has been selected. Please select Items to transfer first."));
}
//--------------------------------------------------------------------------------------------------
function mygetdata($transid)
{
//AND (location.location_type='ARR') 
	$sql = "SELECT 	stock.order_no,	stock.trans_id, 	smaster.description,
	stock.qty , stock.serialized,	location.location_name,	stock.tran_Date,	stock.type, 	
	stock.loc_code,	stock.person_id,	stock.price,	stock.trans_no, 
	stock.reference	,stock.stock_id
	FROM " .TB_PREF."stock_moves stock, "
		.TB_PREF."locations location, "
		.TB_PREF."stock_master smaster
	WHERE location.loc_code = stock.loc_code
	AND smaster.stock_id = stock.stock_id
	
	
	AND smaster.serializable=1
	AND stock.trans_no=".$transid ;  //AND stock.visible=1
	$sql_a = mysql_query($sql);
	 $result = mysql_fetch_array($sql_a);
	 return $result;
}
//randomPrefix(10); 
/* function has_duplicates($arr) {

    var x = {}, len = $arr.length;
    for (var i = 0; i < len; i++) {
        if (x[$arr[i]] === true) {
             return true;
        }
        x[$arr[i]] = true;
    }
    return false;

} */
function display_tsfr_serialize_items()
{
	global $table_style;

	div_start('grn_items');
    start_table("colspan=7 $table_style width=90%");
    $th = array(_("Item"), _("Trans #"), _("Description"),_("Qty"), _("Units"),_("Serial #"), _("Unscanned"));
    table_header($th);
	

    /*show the line items on the order with the quantity being received for modification */

    $total = 0;
    $k = 0; //row colour counter
	if(!isset ($_GET['TransID'])) {
		$TransID = $_POST['transidhidden'];
	}
	
	$result = mygetdata($TransID);
	
	$line_qty = abs($result["qty"] );  
	$remainder  = $line_qty;
	$stock_id = $result["stock_id"];
	$multiplier = get_unit_info($_POST['units']);
	$count = floor($line_qty/$multiplier);
	echo '<strong> Total Quantity: </strong>'. $line_qty;
	$units = $_POST['units'];
	if($line_qty < $multiplier)
	{
		//echo "Cannot scan as , SKU quantity is less than unit count" ;
		display_notification_centered(_("Cannot scan as selected Unit , Not enough items"));
	}
	if($count > 0)
	{
		for($i=1; $i<=$count; $i++)
		{
			
				alt_table_row_color($k);
				label_cell($i);
				label_cell($result["trans_id"]);
				label_cell($result["description"]);
				label_cell($multiplier);
				label_cell($units);
				$remainder = $remainder  - $multiplier;
				text_cells(null, 'srl_no[]','' , 30, 50);
				label_cell($remainder, '', 'remain');
				end_row();
				$_SESSION['remainder'] = $remainder;
		}
		 if($remainder >0 &&  $_POST['units'] == 'bx.')
		{
			submit_cells('ScanBrick', _("Next"), "colspan=2", _('Scan remaining items as Bricks'), true);
		}	 
				
	}
  
    end_table();
	div_end();
	if($remainder >0 &&  $_POST['units'] == 'bx.')
		{
			hyperlink_no_params( "#", _("Scan remaining items as Bricks"));
			//submit_cells('ScanBrick', _("Next"), "colspan=2", _('Scan remaining items as Bricks'), true);
		}	
	
}
function process_serialize_trsf()
{
	global $path_to_root, $Ajax;
	$sessString = $_SESSION['CART'];
	$cart = explode("|", $sessString) ;
	$all_serials = $_SESSION['SRL'];
	//echo 'all_serials:' . explode("|", $all_serials) ;
	echo 'sessString:' . $sessString . '<br>';
	//echo 'units' .$cart[3];
	$result = mygetdata($cart[0]);
	$location = $cart[1];
	$unit = $cart[2];
	begin_transaction();
	foreach($all_serials as $row)
	{  //serial_exist($srl_no,$location,$unit)
		if(!serial_exist($row,$_POST['FromStockLocation'],$unit))
		{
			display_error(_("Item with serial ". $row . " does not exist in the selected location"));
			return ;
		}
	}
	  $trans_no = add_stock_transfer($_SESSION['transfer_items']->line_items,
		$_POST['FromLocation'], $_POST['ToLocation'],
		$_POST['AdjDate_'], $_POST['type_'], $_POST['ref_'], $_POST['memo']);
	new_doc_date($_POST['AdjDate_']);  

   	meta_forward($_SERVER['PHP_SELF'], "AddedID=$trans_no");

		$_SESSION['transfer_items']->clear_items();
		unset($_SESSION['transfer_items']);
		
	unset($_SESSION['CART']);
	unset($_SESSION['SRL']); 

	//meta_forward($_SERVER['PHP_SELF'], "AddedID=$cart[0]");
}

//--------------------------------------------------------------------------------------------------
function serial_exist($srl_no,$location,$unit)
{
echo 'srl_no:' . $srl_no . '<br>';
echo 'location:' . $location . '<br>';
echo 'unit:' . $unit . '<br>';

			$strSQL = "SELECT  "  . $unit . " from ". TB_PREF."serialized_stock  
			WHERE ". $unit ." = '". $srl_no . "'" .
			" AND location_code='" . $location . "'";
	
	if(mysql_num_rows(mysql_query($strSQL))){
		return true;
	}
	else
		return false;
	/* $sql_b = mysql_query($strSQL);
	$result2 = mysql_fetch_array($sql_b);
	return $result2[0]; */
}

//--------------------------------------------------------------------------------------------------
function get_unit_info($name)
{
		$sql2 = "SELECT decimals from "
			.TB_PREF."item_units  
			WHERE abbr = '". $name . "'";
			
	$sql_b = mysql_query($sql2);
	$result2 = mysql_fetch_array($sql_b);
	return $result2['decimals'];
}

//-----------------------------------------------------------------------------------------------------
function setvariables()
{
	$cart	= array();
	$column ;
	switch ($_POST['units']) {
		case "bx.":
			$column = 'box_no';
			break;
		case "bk.":
			$column = 'brick_no';
			break;
		case "cd.":
			$column = 'card_no';
			break;
		case "ea.":
			$column = 'card_no';
			break;
		default:
			$column = 'card_no';
	}
	//create an array of keys to convert, the post value would be referenced simply as $name
	/* foreach ($_POST as $k => $v) {
        $$k = $v;
	} */
	$srl_no = $_POST['srl_no'];
	/* foreach($cart as $key=>$val){
    	$$val=$_POST[$val];
	} */
	
	/* foreach ($srl_no as $value) {
		echo $value . "<br>";
	} */
 
	$cart['transid'] = $_POST['transidhidden'];
	$cart['location_code'] = $_POST['Location'];
	//$cart['srl_no'] = $_POST['srl_no'];
	$cart['units'] = $column; 
	$cart['multiplier'] = get_unit_info($_POST['units']);
	$cartstring = implode("|", $cart);
	$_SESSION['CART'] = $cartstring;
	$_SESSION['SRL'] = $srl_no;
}
//--------------------------------------------------------------------------------------------------
if (isset($_POST['ScanBrick']))
{
	setvariables();
	header("Location:".$path_to_root. "/simplex/inventory/transfers_serialize_2.php?TransID=" . $_POST['transidhidden']);
}
 
if (isset($_POST['ProcessTransfer']))
{


	if ($_SESSION['remainder'] > 0)	{
		display_notification_centered(_("You must Scan all items."));
		echo "<center><p><a href='javascript:goBack();'>Back</a></p></center><br>";
		set_focus('srl_no');
		return false;
	}
	//setvariables();
}


//--------------------------------------------------------------------------------------------------
if (isset($_POST['ProcessTransfer']))
{
	setvariables();
	process_serialize_trsf();
}

//--------------------------------------------------------------------------------------------------

start_form();

//display_srlz_summary($_SESSION['PO'], true);
display_order_header_tsfr($_SESSION['transfer_items'],$_GET['fromloc'],$_GET['toloc']);
display_heading(_("Items to Serialize"));
//display_po_serialize_items();
echo "<center>" . _("Units to scan:"). " ";
echo units_list('units','',false,true);
hidden('transidhidden', $_GET['TransID']);
hidden('FromLocation', $_GET['fromloc']);
hidden('ToLocation', $_GET['toloc']);
hidden('ref_', $_GET['ref_']);
hidden('memo', $_GET['memo']);
hidden('AdjDate_', $_GET['AdjDate_']);
hidden('type_', $_GET['type_']);
//echo 'qtyy:' . $_SESSION['qtyy'] ;
/*
echo 'from:' . $_POST['FromStockLocation'] . "<br>"; 
echo 'to:' . $_POST['ToStockLocation'] . "<br>";
echo 'adjdate: '. $_POST['AdjDate'] . "<br>";
echo 'type:' . $_POST['type'] . "<br>";
echo 'ref:' . $_POST['ref'] . "<br>";
echo 'memo:'  . $_POST['memo_'] . "<br>";
echo "<br>";
echo 'from1:' . $_GET['fromloc'] . "<br>"; 
echo 'to1:' . $_GET['toloc'] . "<br>";
echo 'adjdate1:' . $_GET['AdjDate_'] . "<br>";
echo 'type1:' . $_GET['type_'] . "<br>";
echo 'ref1:' . $_GET['ref_'] . "<br>";
echo 'memo1:'  . $_GET['memo'] . "<br>";
*/

submit_center_first('Start', _("Start"), _("Start scan"), 'default');
echo "<br>";

echo "<hr></center>";

$TransID = $_GET['TransID'];
	if(!isset ($_GET['TransID'])) {
		$TransID = $_POST['transidhidden'];
	} //end if

if (isset($_POST['Start']))
	{
	display_tsfr_serialize_items();
} 
submit_center_first('ProcessTransfer', _("Process Items"), _("Serialize and move from arrival"), 'default');
display_db_pager($table);

end_form();

//--------------------------------------------------------------------------------------------------

end_page();
?>

