<?php
/**********************************************************************
    Copyright (C) SIMPLEX
    @author laolu olapegba
***********************************************************************/
//$page_security = 'SA_LOCATIONTRANSFER';
$page_security = 'SA_ITEMSTRANSVIEW';
$path_to_root = "../..";
//include_once($path_to_root . "/purchasing/includes/po_class.inc");

include_once($path_to_root . "/includes/session.inc");
//include_once($path_to_root . "/simplex/purchasing/includes/ui/ui_funcs.php");

include($path_to_root . "/includes/db_pager.inc");
 include_once($path_to_root . "/includes/ui/items_cart.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/inventory/includes/stock_transfers_ui.inc");
//include_once($path_to_root . "/simplex/inventory/includes/transfers.inc");
include_once($path_to_root . "/inventory/includes/inventory_db.inc"); 

 if (isset($_GET['Qty'])){
	$_POST['Qty'] = $_GET['Qty'];
	$Qty = $_GET['Qty'];
	} 

 $js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
/*page(_($help_context = "Serialize Transfer Items"), false, false, "", $js); */
page(_($help_context = "Serialize Transfer Items"),false,false, "", $js);

/* if (list_updated('units')) 
	$Ajax->activate('grn_items'); */
//---------------------------------------------------------------------------------------------------------------

if (isset($_GET['AddedID']))
{
	$trans_no = $_GET['AddedID'];
	$trans_type = ST_LOCTRANSFER;

	display_notification_centered(_("Items  successfully picked"));
	//display_note(get_trans_view_str($trans_type, $trans_no, _("&View this transfer")));

	hyperlink_back();

	display_footer_exit();
}

//--------------------------------------------------------------------------------------------------

//if ((!isset($_GET['TransID']) || $_GET['TransID'] == 0) )  //&& !isset($_SESSION['PO'])
//if ((!isset($_GET['Qty']) || $_GET['Qty'] == 0)  && !isset( $_POST['qtyhidden']))
if ((!isset($_SESSION['stocktransferqty']) || $_SESSION['stocktransferqty'] == 0) )
{
	die (_("This page can only be opened if a stock  code has been selected. Please select Stock to Pick."));
}

if(isset($_SESSION['StockId']))
{
	if(ispickedfortransfer($_GET['StockId']))
	{
		//hyperlink_back();
		echo "<center><p><a href='javascript:window.close();'>Back</a></p></center><br>";
		die(_("Items have already been picked for this order."));
	}
}
//------------------------------------------------------------------------------------------------------
function get_column($unit)
{
	$column ;
	switch ($unit) {
		case "bx.":
			$column = 'box_no';
			break;
		case "bk.":
			$column = 'brick_no';
			break;
		case "cd.":
			$column = 'card_no';
			break;
		default:
			$column = 'card_no';
	}
	return $column;
}
//--------------------------------------------------------------------------------------------------
function ispickedfortransfer($stock_id)
{
	$Stocks = array();
	if( isset($_SESSION['SCANNEDSTOCKS']) )
	{
		$Stocks = $_SESSION['SCANNEDSTOCKS'];
	}
	if(in_array($stock_id,$Stocks))
	{
		return true;
	}
	else
		return false;
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
	$column = get_column($_POST['units']);
	$_SESSION['SRL'] = $_POST['srl_no'];
}

//-----------------------------------------------------------------------------------------------
function upd_tsfr_serials($items,$unit,$sales_order_no=0)
{
	$cartstring = implode("','", $items);
	$sql = "UPDATE ".TB_PREF."serialized_stock SET status='PICKEDFORTRANSFER',sales_order_no=".db_escape($sales_order_no) . 
			" WHERE ".$unit." in ('". $cartstring . "')";
			db_query($sql, "The serials could not be updated");
			
}
//---------------------------------------------------------------------------------------------------
function serial_exist($srl_no,$location,$unit)
{
			$strSQL = "SELECT  "  . $unit . " from ". TB_PREF."serialized_stock  
			WHERE ". $unit ." = '". $srl_no . "'" .
			" AND location_code='" . $location . "' and status <> 'PICKED'";
	
	if(mysql_num_rows(mysql_query($strSQL))){
		return true;
	}
	else
		return false;
}
function display_tsfr_serialize_items()
{
	global $table_style;

	div_start('grn_items');
    start_table("colspan=7 $table_style width=90%");
    $th = array(_("Item"), _("Description"),_("Qty"), _("Units"),_("Serial #"), _("Unscanned"));
    table_header($th);

    /*show the line items on the order with the quantity being received for modification */

    $total = 0;
    $k = 0; //row colour counter
	

		//$result = get_transfer_details($TransID); */
		//echo 'stock qty:' . $_SESSION['stocktransferqty'];  
	$line_qty =  $_SESSION['stocktransferqty'];  
	$remainder  = $line_qty;
	$stock_id = $_POST['StockIdhidden'] ;//  $result["stock_id"];
	
	$multiplier = get_unit_info($_POST['units']);
	//echo 'mul qty:' . $multiplier;
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
				label_cell($stock_id ) ;
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
		elseif ($remainder > 0 &&  $_POST['units'] == 'bk.')
			{
				submit_cells('ScanCard', _("Next"), "colspan=2", _('Scan remaining items as Cards'), true);
			}
		
				
	}
  
    end_table();
	div_end();
	
}

function process_serialize_trsf()
{
	global $path_to_root, $Ajax;
	$all_serials = $_SESSION['SRL'];
	$location = $_POST['FromLocation'] ;//$cart[1];
	$cart	= array();
	$column  = get_column($_POST['units']);
	
	foreach($all_serials as $row)
	{  
		if(!serial_exist($row,$_POST['FromLocation'],$column))
		{
			display_error(_("Item with serial ". $row . " does not exist in the selected location"));
			return ;
		}
	}
	//define an array to hold scanned items(stockid)
	$Stocks = array();
	if(!isset($_SESSION['SCANNEDSTOCKS']))
	{
		$Stocks[] = $_POST['StockIdhidden'];
		$_SESSION['SCANNEDSTOCKS'] = $Stocks;
	}
	else
	{
		$Stocks = $_SESSION['SCANNEDSTOCKS'];
		$Stocks[] = $_POST['StockIdhidden'];
		$_SESSION['SCANNEDSTOCKS'] = $Stocks;
	}
	//update status 
	upd_tsfr_serials($all_serials,$column);
	$stockqty = get_unit_info($column);
	add_stock_transfer_srl($all_serials, $_SESSION['StockIdhidden'], $_SESSION['FromLocation'], $_SESSION['ToLocation'], $_SESSION['AdjDate_'], $stockqty);
	  /*$trans_no = add_stock_transfer($_SESSION['transfer_items']->line_items,
		$_POST['FromLocation'], $_POST['ToLocation'],
		$_POST['AdjDate_'], $_POST['type_'], $_POST['ref_'], $_POST['memo']);
	new_doc_date($_POST['AdjDate_']);  

   	meta_forward($_SERVER['PHP_SELF'], "AddedID=$trans_no");

		$_SESSION['transfer_items']->clear_items();
		unset($_SESSION['transfer_items']);*/
		
	unset($_SESSION['SRL']); 
	meta_forward($_SERVER['PHP_SELF'], "AddedID=$Stocks");
	//unset($_SESSION['SCANNEDSTOCKS']);
	//close  this screen
	//meta_forward($path_to_root . "/simplex/inventory/transfers.php", "OrderNumber=".get_sales_orderno($_POST['OrderNum']));

}

//--------------------------------------------------------------------------------------------------
if (isset($_POST['ScanBrick']))
{
	setvariables();
	href=//'$path_to_root/simplex/inventory/transfers_serialize.php?StockId=$stock_id&Qty=$Qty&fromloc=$fromloc&toloc=$toloc&ref_=$ref&memo=$memo&AdjDate_=$AdjDate&type_=$type'
	header("Location:".$path_to_root. "/simplex/inventory/transfers_serialize_2.php?StockId=" . $_POST['StockId']);
}
 
if (isset($_POST['ProcessTransfer']))
{
	if ($_SESSION['remainder'] > 0)	{
		display_notification_centered(_("You must Scan all items."));
		echo "<center><p><a href='javascript:goBack();'>Back</a></p></center><br>";
		set_focus('srl_no');
		return false;
	}
	if ( !isset($_SESSION['SCANNEDSTOCKS'])  ) //$tr->all_items_picked_s() == 0 ) !isset($_SESSION['SCANNEDSTOCKS']) 
	{
		display_notification_centered(_("Not all items  have been picked for this transfer"));
		return false;
	}  
	
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
if(isset($_GET['fromloc']) && isset($_GET['toloc']) )
{
	display_order_header_tsfr($_SESSION['transfer_items'],$_GET['fromloc'],$_GET['toloc']);
}
display_heading(_("Items to Serialize"));
//display_po_serialize_items();
echo "<center>" . _("Units to scan:"). " ";
echo units_list('units','',false,true);

//hidden('transidhidden', $_GET['TransID']);
//hidden('qtyhidden', $_GET['Qty']);
if(isset($_GET['StockId']) && isset($_GET['fromloc']) && isset($_GET['toloc']) && isset($_GET['ref_']) && isset($_GET['memo']) && isset($_GET['AdjDate_']) && isset($_GET['type_'])     )
{
	hidden('StockIdhidden', $_GET['StockId']);
	hidden('FromLocation', $_GET['fromloc']);
	hidden('ToLocation', $_GET['toloc']);
	hidden('ref_', $_GET['ref_']);
	hidden('memo', $_GET['memo']);
	hidden('AdjDate_', $_GET['AdjDate_']);
	hidden('type_', $_GET['type_']);
	
	hidden('qtyhidden', $_GET['Qty']);
}


submit_center_first('Start', _("Start"), _("Start scan"), 'default');
echo "<br>";

echo "<hr></center>";


	if(isset ($_GET['Qty'])) {
		$Qty = $_GET['Qty'];
	} 
	else
		$Qty = $_POST['qtyhidden'];


if (isset($_POST['Start']))
	{
	display_tsfr_serialize_items();
} 
submit_center_first('ProcessTransfer', _("Process Items"), _("Serialize and move from arrival"), 'default');
//hyperlink_back();
echo "<center><p><a href='javascript:window.close();'>Back</a></p></center><br>";
	$cols = array(
		_("Quote #") => array('fun'=>'view_link'),
		_("Ref"),
		_("Customer"),
		_("Branch"),
		_("Cust Order Ref"),
		_("Quote Date") => 'date',
		_("Valid until") =>array('type'=>'date', 'ord'=>''),
		_("Delivery To"),
		_("Quote Total") => array('type'=>'amount', 'ord'=>''),
		'Type' => 'skip',
		_("Currency") => array('align'=>'center'),
//added order status to the summary display page  _("Order Status")
		_("Order Status")
	);
$sql = "select * "
		. "FROM ".TB_PREF."sales_orders sorder ";
//$table =& new_db_pager('orders_tbl', $sql, $cols);
display_db_pager($table);

end_form();

//--------------------------------------------------------------------------------------------------

end_page();
?>