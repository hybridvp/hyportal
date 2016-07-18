<?php
/**********************************************************************
    Copyright (C) SIMPLEX
    @author laolu olapegba
***********************************************************************/
$page_security = 'SA_GRN';
$path_to_root = "../..";
include_once($path_to_root . "/purchasing/includes/po_class.inc");
include($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_db.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/simplex/purchasing/includes/ui/ui_funcs.inc");
//include_once($path_to_root . "/simplex/includes/ui/ui_lists.php");
 if (isset($_GET['TransID'])){
	$_POST['TransID'] = $_GET['TransID'];
	} 
	else
	$TransID = $_POST['TransID'];
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Split Items"), false, false, "", $js);

/* if (list_updated('units')) 
	$Ajax->activate('grn_items'); */
//---------------------------------------------------------------------------------------------------------------

if (isset($_GET['AddedID']))
{
	$grn = $_GET['AddedID'];
	$trans_type = ST_SUPPRECEIVE;

	display_notification_centered(_("Items with Purchase Order #" . $_GET['AddedID'] . "Split successfully"));

	//display_note(get_trans_view_str($trans_type, $grn, _("&View this Delivery")));
	
	hyperlink_params("$path_to_root/simplex/purchasing/inquiry/scanned_item_inquiry.php" , _("&View Split Items"), "order_number=". $_GET['AddedID']);

	hyperlink_no_params("$path_to_root/simplex/inventory/inquiry/stock_search.php", _("Select a different &purchase order for pliting"));

	display_footer_exit();
}

//--------------------------------------------------------------------------------------------------

//if ((!isset($_GET['TransID']) || $_GET['TransID'] == 0) )  //&& !isset($_SESSION['PO'])
if ((!isset($_GET['TransID']) || $_GET['TransID'] == 0)  && !isset( $_POST['TransID']))  ///transidhidden
{
	die (_("This page can only be opened if a Transaction # of a received stock has been selected. Please select a Transaction #  first."));
}

function receive_link($param) 
{
  return pager_link( _("Scan remaining items as Bricks"),
	"/simplex/purchasing/po_receive_serialize_2.php?TransID=" . $param, ICON_RECEIVE);
}

function get_item_data($transid)
{
	$sql = "SELECT 	id,stock.stock_id,location_code,qty,box_no,brick_no,card_no,order_no,trans_date,status,sales_order_no,
	smaster.description
	FROM " .TB_PREF."serialized_stock stock, "
		.TB_PREF."locations location, "
		.TB_PREF."stock_master smaster
	WHERE location.loc_code = stock.location_code
	AND smaster.stock_id = stock.stock_id
	AND stock.id=".$transid ;  //AND stock.visible=1
	$sql_a = db_query($sql);
	 $result = db_fetch($sql_a);
	 return $result;
}

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
	
	//if(!isset ($_GET['TransID'])) {
		
	//}
	$TransID = $_POST['TransID']; //transidhidden
	//echo 'trnasid='.$TransID;
	$result = get_item_data($TransID); //
	$sourc_unit = get_source_unit($TransID);
	//echo 'source u='.$sourc_unit . ' -' . 'dest unit=' . $_POST['units'] ;
	$des_unit = $_POST['units'];
	 if ( get_unit_info($des_unit) <= 0)
	 {
	 	die(_("Unit quantity for unit '". get_unit_name ($des_unit) . "' cannot be zero"));
	 }
	if ( get_unit_info($sourc_unit) <= get_unit_info($des_unit) )
	{
		die(_("Cannot split, Source unit cannot be same as destination unit"));
	}
	
	$line_qty = $result["qty"] ;  
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
				label_cell($result["id"]);
				label_cell($result["description"]);
				label_cell($multiplier);
				label_cell($units);
				$remainder = $remainder  - $multiplier;
				text_cells(null, 'srl_no[]',uniqid() , 30, 50);
				label_cell($remainder, '', 'remain');
				end_row();
				$_SESSION['remainder'] = $remainder;
		}
		// if($remainder >0 &&  $_POST['units'] == 'bx.')
		//{
		//submit_cells('ScanBrick', _("Next"), "colspan=2", _('Scan remaining items as Bricks'), true);
		//}	 
				
	}
     end_table();
	div_end();

	/*if($remainder >0 &&  $_POST['units'] == 'bx.')
		{
		//"/$path_to_root .simplex/purchasing/po_receive_serialize_2.php?TransID=".$TransID . "&Rem="
			hyperlink_no_params( "#", _("Scan remaining items as Bricks"));
			//submit_cells('ScanBrick', _("Next"), "colspan=2", _('Scan remaining items as Bricks'), true);
		}	*/
	
}
function process_serialize_po()
{
	global $path_to_root, $Ajax;
	$sessString = $_SESSION['CART'];
	$cart = explode("|", $sessString) ;
	$all_serials = $_SESSION['SRL'];
	//echo 'all_serials:' . explode("|", $all_serials) ;
	//echo 'sessString:' . $sessString . '<br>';
	//echo 'units' .$cart[3];
	$result = get_item_data($cart[0]);
	$location = $cart[1];
	$unit = $cart[2];
	begin_transaction();
	foreach($all_serials as $row)
	{
		$strSQL =  "insert into "
				.TB_PREF."serialized_stock(id,transtype,stock_id,location_code,qty,batch_no ";
				
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
	$strSQL .= ",order_no,trans_date) values (SERIALIZED_STOCKS_ID_SEQ.nextval,null," . db_escape($result['stock_id'] ). ",". db_escape($location) . "," . $cart[3] . ',' .'null' .
				",'" . $row . "',"  . $result['order_no'] . ', sysdate)';
				
				//echo 'sql:' . $strSQL . '<br>';
	db_query($strSQL,"At least one Serial  could not be added for order " . $result['order_no'] );
	}
	
	$strsql2 = "update "
				.TB_PREF ."serialized_stock set status='SPLIT' where id=" . $cart[0];
				db_query($strsql2,"The Serial could not be added");
	
	///move to default location by adding  stock_moves entries for a stock transfer
	$strsql2 = "update "
				.TB_PREF ."stock_moves set loc_code='" .$_POST['Location'] . "',serialized=1 where trans_id=" . $cart[0];
				db_query($strsql2,"The Serial could not be added");
				
	commit_transaction();


	unset($_SESSION['CART']);
	unset($_SESSION['SRL']);

	meta_forward($_SERVER['PHP_SELF'], "AddedID=".$result['order_no']);
}

//--------------------------------------------------------------------------------------------------
function get_unit_info($name)
{
		$sql2 = "SELECT decimals from "
			.TB_PREF."item_units  
			WHERE abbr = '". $name . "'";
			
	$sql_b = db_query($sql2);
	$result2 = db_fetch($sql_b);
	return $result2['decimals'];
}
//--------------------------------------------------------------------------------------------------
function get_unit_name($name)
{
		$sql2 = "SELECT name from "
			.TB_PREF."item_units  
			WHERE abbr = '". $name . "'";
			
	$sql_b = db_query($sql2);
	$result2 = db_fetch($sql_b);
	return $result2['name'];
}
function get_source_unit($transid)
{
$source_unit = "";
	$result = get_item_data($transid ); //$_GET['TransID']
	$box_no = $result["box_no"] ; 
	$brick_no = $result["brick_no"] ;
	if($box_no != 0)
	{
		$source_unit = "bx.";		
	}
	if($brick_no != 0)
	{
		$source_unit = "bk.";	
	}
	return $source_unit;
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
 
	$cart['transid'] = $_POST['TransID']; //transidhidden
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
	header("Location:".$path_to_root. "/simplex/purchasing/po_receive_serialize_2.php?TransID=" . $_POST['TransID']); //transidhidden
}

 //--------------------------------------------------------------------------------------------------

if (isset($_GET['PONumber']) && $_GET['PONumber'] > 0 && !isset($_POST['Update']))
{
	create_new_po();
	/*read in all the selected order into the Items cart  */
	read_po($_GET['PONumber'], $_SESSION['PO']);
}

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
display_srlz_summary($_SESSION['PO'], true);
$source_unit = "";
$trxid = 0;

hidden('TransID', $_GET['TransID']);
if (isset($_GET['TransID']))
{
	$trxid = $_GET['TransID'];
}
else
	$trxid = $_POST['TransID'];

$source_unit = get_source_unit($trxid);
display_heading(_("Items to Split"));
//display_po_serialize_items();

start_table("class='tablestyle_noborder'");
	//start_row();
	echo "Split from ". get_unit_name($source_unit) . " to ";
	echo units_list('units','',false,true);
	//units_list_cells(_(" to "), 'SelectStockFromList', null, true);
	//if (isset($_GET['TransID']) )
	//{
	//	hidden('transidhidden', $_GET['TransID']);
	//}
	//end_row();
	//end_table();

//echo "<center>" . _("Slit from "). "" ; //get_unit_name(;
//echo "<center>" . _("Units to scan:"). " ";
//echo units_list('units','',false,true);

submit_center_first('Start', _("Start"), _("Start scan"), 'default');
echo "<br>";

echo "<hr></center>";

if (isset($_GET['TransID']))
{
	$TransID = $_GET['TransID'];
}
else
	$TransID = $_POST['TransID']; //transidhidden
	
if (isset($_POST['Start']))
	{
	display_po_serialize_items();
} 

submit_center_first('ProcessGoodsReceived', _("Process Items"), _("Serialize and move from arrival"), 'default');
//$table =& new_db_pager() ; //'orders_tbl', '', $cols);
display_db_pager($table);

end_form();

//--------------------------------------------------------------------------------------------------

end_page();
?>

