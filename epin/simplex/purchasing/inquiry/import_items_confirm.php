<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_ITEMSTRANSVIEW';
$path_to_root = "../../..";
include($path_to_root . "/includes/db_pager.inc");
include($path_to_root . "/includes/session.inc");

include($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

//include($path_to_root . "/includes/db/inventory_db.inc");
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Authorize Imported Items"), false, false, "", $js);

if (isset($_GET['order_number']))
{
	$_POST['order_number'] = $_GET['order_number'];
}
//-----------------------------------------------------------------------------------
// Ajax updates
//
if (get_post('SearchOrders')) 
{
	$Ajax->activate('orders_tbl');
} elseif (get_post('_order_number_changed')) 
{
	$disable = get_post('order_number') !== '';

	$Ajax->addDisable(true, 'TrsfAfterDate', $disable);
	$Ajax->addDisable(true, 'TrsfToDate', $disable);
	$Ajax->addDisable(true, 'StockLocation', $disable);
	$Ajax->addDisable(true, '_SelectStockFromList_edit', $disable);
	$Ajax->addDisable(true, 'SelectStockFromList', $disable);
	$Ajax->addDisable(true, 'units', $disable);
	
	if ($disable) {
		$Ajax->addFocus(true, 'order_number');
	} else
		$Ajax->addFocus(true, 'TrsfAfterDate');

	$Ajax->activate('orders_tbl');
}


//---------------------------------------------------------------------------------------------

start_form();

start_table("class='tablestyle_noborder'");
start_row();
ref_cells(_("#:"), 'order_number', '',null, '', true);

date_cells(_("from:"), 'TrsfAfterDate', '', null, -30);
date_cells(_("to:"), 'TrsfToDate');

units_list_cells ( _("Unit"),'units', null, true);

locations_list_cells(_("Location:"), 'StockLocation', null, true);

stock_items_list_cells(_("Item:"), 'SelectStockFromList', null, true);

submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');
end_row();
end_table();
//---------------------------------------------------------------------------------------------
function trans_view($trans)
{
	return get_trans_view_str(ST_PURCHORDER, $trans["ponumber"]);
}

//---------------------------------------------------------------------------------------------
function prt_link($row)
{
	return print_document_link($row['ponumber'], _("Print"), true, 18, ICON_PRINT);
}


//---------------------------------------------------------------------------------------------
function receive_link($row) 
{
  return pager_link( _("Confirm"),
	"/simplex/purchasing/view/confirm_po_import.php?TransID=" . $row["id"] . "&PONumber=" . $row['ponumber'],  ICON_RECEIVE);
	//Laolu comments add PONumber to request string to be used in po_receive_serialize
}
function approve_link($row) 
{
  //submit_center_first('ApproveOrder', $row, _('Approve Order'), 'default');
	//  	     submit_js_confirm('ApproveOrder', _('You are about to confirm this sales order.\nDo you want to continue?'));
	//submit_row('submit', _("Get"), true, '', '', true);
	 //hidden('hiddenponum'. $row['ponumber'], $row['ponumber'], false);
	 //hidden('hiddenid'. $row['id'], $row['id'], false);
	 $_SESSION['ponum'] = $row['ponumber'];
	 $_SESSION['trans_id'] = $row['id'];
	submit_cells('Authorise', _("Authorise"), "colspan=2",  _('Authorise this transaction'), true);
	submit_js_confirm('Authorise', _('You are about to Authorise this order.\nDo you want to continue?'));
}
function check_po_number($ponumber) {
    $sql = "SELECT * FROM ".TB_PREF."purch_orders where order_no = $ponumber";
    $result = db_query($sql, "Can not look up ponumber");
    $row = db_fetch_row_r($result);
    if (!$row[0]) return 0;
    return 1;
}
function GetPOData($transid,$po_number)
{
$sql = "SELECT 
	po.ponumber,
	smaster.description,
	po.quantity_ordered,
	suppliers.supp_name,
	location.location_name,
	po.orderdate,
	po.item_code,
	po.into_stock_location,
	id,
	po.reference
	
	FROM "
		.TB_PREF."po_import po, "
		.TB_PREF."locations location, "
		.TB_PREF."stock_master smaster, "
		.TB_PREF."suppliers
	WHERE location.loc_code = po.into_stock_location
	AND smaster.stock_id = po.item_code
	AND suppliers.supplier_id = po.vendor
	AND smaster.serializable=1 AND upper(status) = 'PLANNED'
	AND ponumber = ". $po_number .
	" AND po.id=".$transid ;  //AND stock.visible=1

	$sql_a = db_query($sql);
	 $result = db_fetch($sql_a);
	 return $result;
}
function GetUserId($user_id)
{
$sql = "SELECT id from users WHERE user_id= ". db_escape($user_id);
	$sql_a = db_query($sql);
	 $result = db_fetch($sql_a);
	 return $result['id'];
}
function approve_line($trans_id,$ponumber)
{
	$result = GetPOData($trans_id,$ponumber);
	begin_transaction();
	
	
/*	add_stock_move($type, $stock_id, $trans_no, $location,
    $date_, $reference, $quantity, $std_cost, $person_id=0, $show_or_hide=1,
    $price=0, $discount_percent=0, $error_msg="", $order_no=0, $serialized=0) //Laolu added order number,serialize*/
	
	add_stock_move(100, $result['item_code'], $trans_id, $result['into_stock_location'], date('d/m/Y'), $result['reference'],
            	$result['quantity_ordered'], 0,	GetUserId($_SESSION["wa_current_user"]->loginname), 0, 0,0,"",$ponumber); 
	//commit_transaction();			
	$sql = "UPDATE ".TB_PREF."po_import SET status = 'APPROVED' where id= " . 
			$trans_id ." and ponumber = " .
			$ponumber. " and status != 'APPROVED'";
			$res = db_query($sql, "The order could not be authorised.");
			$err = oci_error($res); 
					if( $err )
					{
						$db_err ="ERROR";
						return;
					}

	if (check_po_number($ponumber) != 1)
	{
	$sql2 = "insert into "
 			.TB_PREF."purch_orders ". 				                     "(order_no,supplier_id,comments,ord_date,reference,requisition_no,into_stock_location,delivery_address,
			created_by,  created_date,last_updated_by,last_updated_date,confirmed_by,confirmed_date,status)
select ponumber,vendor,comments,orderdate,reference,requisition_no,'DEF','Address',created_by,
created_date,last_updated_by,last_updated_date,". db_escape($_SESSION["wa_current_user"]->loginname). ",SYSDATE,status from "
			.TB_PREF."po_import 
			WHERE ponumber=".$ponumber . " AND status= 'APPROVED' AND  rownum=1";
			
			$res = db_query($sql2, "The order could not be authorised.");
			$err = oci_error($res); 
					if( $err )
					{
						$db_err ="ERROR";
						return;
					}
	}
	 $sql3 = "insert into " 
			.TB_PREF."PURCH_ORDER_DETAILS(po_detail_item,order_no,item_code,description,costcentre,delivery_date,qty_invoiced,					                      unit_price,act_price,std_cost_unit,quantity_ordered,quantity_received)
                      select a.id,a.ponumber,a.item_code,b.description,'1000.000.000'                      ,a.deliverydate,0,0,0,0,a.quantity_ordered,0
					  from " 
					  .TB_PREF. "po_import a , stock_master b where a.item_code = b.stock_id
					  and a.status= 'APPROVED' and ponumber=". $ponumber ." AND a.id=".$trans_id ;
					  
					 $res = db_query($sql3, "The order could not be authorised.");
					 $err = oci_error($res); 
					if( $err )
					{
						$db_err ="ERROR";
						return;
					}
					 display_notification ("Document approved");
	//Display_error ("Document fully authorised already Ponumber=" . $ponumber . "id=" . $trans_id); 
	
	if ( empty($db_err) )
	{
		commit_transaction();
	}
	else
		cancel_transaction();
		unset ($_SESSION['trans_id']);
		unset ($_SESSION['ponum']);
}

if (isset($_POST['order_number']) && ($_POST['order_number'] != ""))
{
	$order_number = $_POST['order_number'];
}

if (isset($_POST['SelectStockFromList']) && ($_POST['SelectStockFromList'] != "") &&
	($_POST['SelectStockFromList'] != $all_items))
{
 	$selected_stock_item = $_POST['SelectStockFromList'];
} 

else
{
	unset($selected_stock_item);
}
if (isset($_POST['units']) && ($_POST['units'] != "") &&
	($_POST['units'] != $all_items))
{
 	$selected_unit = $_POST['units'];
}
else
{
	unset($selected_unit);
}
 if (isset($_POST['Authorise']))
  {		
  		if( isset($_SESSION['trans_id']) && isset($_SESSION['ponum'] ) )
		{
  		approve_line($_SESSION['trans_id'], $_SESSION['ponum'] ) ;
		}
  }
  
  
//figure out the sql required from the inputs available //stock.order_no,
//substr(suppliers.supp_name,1,2)
$sql = "SELECT 
	po.ponumber,
	po.id,
	smaster.description,
	po.quantity_ordered,
	suppliers.supp_name,
	location.location_name,
	po.orderdate,
	po.created_by,
	po.item_code,
	po.into_stock_location
	
	FROM "
		.TB_PREF."po_import po, "
		.TB_PREF."locations location, "
		.TB_PREF."stock_master smaster, "
		.TB_PREF."suppliers
	WHERE location.loc_code = po.into_stock_location
	AND smaster.stock_id = po.item_code
	AND suppliers.supplier_id = po.vendor
	AND smaster.serializable=1 AND upper(status) = 'PLANNED'";


if (isset($order_number) && $order_number != "")
{
	$sql .= " AND po.ponumber LIKE ".db_escape('%'. $order_number . '%');
}
else
{
	$data_after = date2sql($_POST['TrsfAfterDate']);
	$data_before = date2sql($_POST['TrsfToDate']);

	$sql .= "  AND po.orderdate >=to_date( '$data_after', 'yyyy-mm-dd') ";
	$sql .= "  AND po.orderdate <= to_date( '$data_before', 'yyyy-mm-dd') ";

	if (isset($_POST['StockLocation']) && $_POST['StockLocation'] != $all_items)
	{
		$sql .= " AND po.into_stock_location = ".db_escape($_POST['StockLocation']);
	}
	if (isset($selected_unit))
	{
		$strunit;
		switch ($_POST['units']) {
			case 'bx.':
				$strunit = "box_no";
				break;
			case 'bx.':
				$strunit =  "brick_no ";
				break;
			case 'cd.':
				$strunit = "card_no ";
				break;
			default:
				$strunit = "card_no ";
				
			} 
		$sql .= " AND po.units=" .db_escape($strunit);
	}
	if (isset($selected_stock_item))
	{
		$sql .= " AND po.item_code=".db_escape($selected_stock_item);
	}
	
} //end not order number selected

//echo $sql;

$result = db_query($sql,"No data was returned");
//echo $sql;
/*show a table of the orders returned by the sql */
$cols = array(
		_("Order #") => array('fun'=>'trans_view', 'ord'=>''), 
		_("Line #"),
		_("Item"), 
		_("Quantity"), 
		_("Supplier"),
		_("Location") => array('ord'=>''),
		_("Order Date"),
		_("Created By"),
		//_("Transaction Date") => array('name'=>'ord_date', 'type'=>'date', 'ord'=>'desc'),
		
		array('insert'=>true, 'fun'=>'approve_link'),
		array('insert'=>true, 'fun'=>'prt_link')
		
		//submit_row('submit', _("Get"), true, '', '', true)
		//submit_cells('EnterLine', _("Add Item"), "colspan=2",
		//    _('Add new item to document'), true)
			
			//submit_center_first('ApproveOrder', $ourcorder,
	  	    // _('Approve Order'), 'default')

);

if (get_post('StockLocation') != $all_items) {
	$cols[_("Location")] = 'skip';
}

$table =& new_db_pager('orders_tbl', $sql, $cols);
//$table->set_marker('check_overdue', _("Marked orders have overdue items."));

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
?>