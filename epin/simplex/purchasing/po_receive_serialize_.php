<?php
/**********************************************************************
    Copyright (C) SIMPLEX
    @author laolu olapegba
***********************************************************************/
$page_security = 'SA_GRN';
$path_to_root = "../..";
include_once($path_to_root . "/purchasing/includes/po_class.inc");

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_db.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/simplex/purchasing/includes/ui/ui_funcs.inc");
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
page(_($help_context = "Serialize Received Items"), false, false, "", $js);

/* if (list_updated('units')) 
	$Ajax->activate('grn_items'); */
//---------------------------------------------------------------------------------------------------------------

if (isset($_GET['AddedID']))
{
	$grn = $_GET['AddedID'];
	$trans_type = ST_SUPPRECEIVE;

	display_notification_centered(_("Purchase Order Serialization has been processed"));

	//display_note(get_trans_view_str($trans_type, $grn, _("&View this Delivery")));
			hyperlink_params("$path_to_root/simplex/purchasing/inquiry/scanned_item_inquiry.php", _("&View this scan"));

	hyperlink_params("$path_to_root/simplex/purchasing/inquiry/received_item_search.php", _("Select a different &purchase order for serializing"));



	hyperlink_params("$path_to_root/purchasing/supplier_invoice.php", _("Entry purchase &invoice for this receival"), "New=1");

	hyperlink_no_params("$path_to_root/purchasing/inquiry/po_search.php", _("Select a different &purchase order for receiving items against"));

	display_footer_exit();
}

//--------------------------------------------------------------------------------------------------

//if ((!isset($_GET['TransID']) || $_GET['TransID'] == 0) )  //&& !isset($_SESSION['PO'])
if ((!isset($_GET['TransID']) || $_GET['TransID'] == 0)  && !isset( $_POST['transidhidden']))
{
	die (_("This page can only be opened if a Transaction # of a received stock has been selected. Please select a Transaction #  first."));
}

//--------------------------------------------------------------------------------------------------
function randomPrefix($length) 
{ 
$random= "";
srand((double)microtime()*1000000);

$data = "AbcDE123IJKLMN67QRSTUVWXYZ"; 
$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz"; 
$data .= "0FGH45OP89";

for($i = 0; $i < $length; $i++) 
{ 
$random .= substr($data, (rand()%(strlen($data))), 1); 
}

return $random; 
}
function receive_link($param) 
{
  return pager_link( _("Scan remaining items as Bricks"),
	"/simplex/purchasing/po_receive_serialize_2.php?TransID=" . $param, ICON_RECEIVE);
}

function mygetdata($transid)
{
	$sql = "SELECT 	stock.order_no,	stock.trans_id, 	smaster.description,
	stock.qty , stock.serialized,	location.location_name,	stock.tran_Date,	stock.type, 	
	stock.loc_code,	stock.person_id,	stock.price,	stock.trans_no, 
	stock.reference	,stock.stock_id
	FROM " .TB_PREF."stock_moves stock, "
		.TB_PREF."locations location, "
		.TB_PREF."stock_master smaster
	WHERE location.loc_code = stock.loc_code
	AND smaster.stock_id = stock.stock_id
	AND (location.location_type='ARR') 
	
	AND smaster.serializable=1
	AND stock.trans_id=".$transid ;  //AND stock.visible=1
	$sql_a = mysql_query($sql);
	 $result = mysql_fetch_array($sql_a);
	 return $result;
}
//randomPrefix(10); 
function display_po_serialize_items()
{
	global $table_style;

	div_start('grn_items');
    start_table("colspan=7 $table_style width=90%");
    $th = array(_("Item"), _("Trans #"), _("Description"),_("Qty"), _("Units"),_("Serial #"), _("Unscanned"));
    table_header($th);
	

    /*show the line items on the order with the quantity being received for modification */

    $total = 0;
    $k = 0; //row colour counter
	//$i =1; //row number
	//$i = 0;
	
	if(!isset ($_GET['TransID'])) {
		$TransID = $_POST['transidhidden'];
	}
	
	$result = mygetdata($TransID);
	
	$line_qty = $result["qty"] ;  //- $result["stock.qty_serialized"]
	$remainder  = $line_qty;
	$stock_id = $result["stock_id"];
	$multiplier = get_unit_info($_POST['units']);
	$count = floor($line_qty/$multiplier);
	echo '<strong> Total Quantity: </strong>'. $line_qty;
	//echo '<strong> Total Quantity Unscanned: </strong>'. $multiplier;
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
				text_cells(null, 'srl_no[]',uniqid() , 30, 50);
				label_cell($remainder, '', 'remain');
				end_row();
				$_SESSION['remainder'] = $remainder;
		}
		 if($remainder >0 &&  $_POST['units'] == 'bx.')
		{
		//$path_to_root."simplex/purchasing/po_receive_serialize_2.php?TransID=".$TransID . "&Rem="
			submit_cells('ScanBrick', _("Next"), "colspan=2", _('Scan remaining items as Bricks'), true);
		}	 
				
	}
     end_table();
	div_end();
	if($remainder >0 &&  $_POST['units'] == 'bx.')
		{
		//"/$path_to_root .simplex/purchasing/po_receive_serialize_2.php?TransID=".$TransID . "&Rem="
			hyperlink_no_params( "#", _("Scan remaining items as Bricks"));
			//submit_cells('ScanBrick', _("Next"), "colspan=2", _('Scan remaining items as Bricks'), true);
		}	
	
}
function process_serialize_po()
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
	{
		$strSQL =  "insert into "
				.TB_PREF."serialized_stock(transtype,stock_id,location_code,qty,batch_no ";
				
		switch ($unit) {
		case 'box_no':
			$strSQL .= ", box_no ";
			break;
		case 'brick_no':
			$strSQL .=  ", brick_no ";
			break;
		case 'card_no':
			$strSQL .= ", card_no ";
			break;
		default:
			$strSQL .= ", card_no ";
	}
	$strSQL .= ",order_no,trans_date) values (null," . $result['stock_id'] . ",'". $location . "'," . $cart[3] . ',' .'null' .
				",'" . $row . "',"  . $result['order_no'] . ', sysdate)';
				
				//echo 'sql:' . $strSQL . '<br>';
	db_query($strSQL,"At least one Serial  could not be added for order " . $result['order_no'] );
	}
	
	
	///move to default location by adding  stock_moves entries for a stock transfer
	$strsql2 = "update "
				.TB_PREF ."stock_moves set loc_code='" .$_POST['Location'] . "',serialized=1 where trans_id=" . $cart[0];
				db_query($strsql2,"The Serial could not be added");
	commit_transaction();
	//$transfer_id = get_next_trans_no(ST_LOCTRANSFER);
	//add_stock_transfer_item($transfer_id, $result['stock_id'], 'ARR',
	//		$_POST['Location'], $_POST['DefaultReceivedDate'], 1, $_POST['ref'], $result['qty'], $result['order_no'],1);
	
	display_notification(_('PO '. $result['order_no'] . ' serialized successfully'));

	unset($_SESSION['CART']);
	unset($_SESSION['SRL']);

	meta_forward($_SERVER['PHP_SELF'], "AddedID=$cart[0]");
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
	header("Location:".$path_to_root. "/simplex/purchasing/po_receive_serialize_2.php?TransID=" . $_POST['transidhidden']);
}

/* //--------------------------------------------------------------------------------------------------

if (isset($_GET['TransID']) && $_GET['TransID'] > 0 && !isset($_POST['Update']))
{

	create_new_po();

	///*read in all the selected order into the Items cart  
	read_po($_GET['TransID'], $_SESSION['PO']);
}
 */
 //--------------------------------------------------------------------------------------------------
//Laolu  added create PO object to remove object not found errors
 
/* if (isset($_GET['PONumber']) && $_GET['PONumber'] > 0 && !isset($_POST['Update']))
{

	create_new_po();

	///*read in all the selected order into the Items cart  
	read_po($_GET['PONumber'], $_SESSION['PO']);

} */
if (isset($_POST['ProcessGoodsReceived']))
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
if (isset($_POST['ProcessGoodsReceived']))
{
	setvariables();
	process_serialize_po();
}

//--------------------------------------------------------------------------------------------------

start_form();
 //$_POST['Location'] = 'ARR'; ///laolu reset Location to ARR
display_srlz_summary($_SESSION['PO'], true);
display_heading(_("Items to Serialize"));
//display_po_serialize_items();
echo "<center>" . _("Units to scan:"). " ";
echo units_list('units','',false,true);
hidden('transidhidden', $_GET['TransID']);
submit_center_first('Start', _("Start"), _("Start scan"), 'default');
echo "<br>";

echo "<hr></center>";

$TransID = $_GET['TransID'];
	if(!isset ($_GET['TransID'])) {
		$TransID = $_POST['transidhidden'];
	} //end if
//$_POST['TransID'] = $_GET['TransID'];
//echo 'post tran:' . $_POST['transidhidden'];
//$k = 0;
if (isset($_POST['Start']))
	{
	display_po_serialize_items();
/* 	global $table_style;

	div_start('grn_items');
    start_table("colspan=7 $table_style width=90%");
    $th = array(_("Order no"), _("Trans #"), _("Description"),_("Total Qty"), _("Units"),_("Serial #"));
    table_header($th);
	end_table();
	div_end(); */
} 
//echo 'sessionpo' . $_SESSION['PO'];
//echo "trans id =" . $_GET['TransID'];

submit_center_first('ProcessGoodsReceived', _("Process Items"), _("Serialize and move from arrival"), 'default');
//display_db_pager($table);

end_form();

//--------------------------------------------------------------------------------------------------

end_page();
?>

