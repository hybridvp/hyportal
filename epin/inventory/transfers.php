<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_LOCATIONTRANSFER';
$path_to_root = "..";
include_once($path_to_root . "/includes/ui/items_cart.inc");

include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/inventory/includes/stock_transfers_ui.inc");
include_once($path_to_root . "/inventory/includes/inventory_db.inc");
//include_once($path_to_root . "/simplex/inventory/includes/transfers.inc");
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Inventory Location Transfers"), false, false, "", $js);

//-----------------------------------------------------------------------------------------------

check_db_has_costable_items(_("There are no inventory items defined in the system (Purchased or manufactured items)."));

check_db_has_movement_types(_("There are no inventory movement types defined in the system. Please define at least one inventory adjustment type."));

//-----------------------------------------------------------------------------------------------

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

function line_start_focus() {
  global 	$Ajax;

  $Ajax->activate('items_table');
  set_focus('_stock_id_edit');
}
//-----------------------------------------------------------------------------------------------

function handle_new_order()
{
	
	if (isset($_SESSION['transfer_items']))
	{
		$_SESSION['transfer_items']->clear_items();
		unset ($_SESSION['transfer_items']);
	}

    //session_register("transfer_items");

	$_SESSION['transfer_items'] = new items_cart(ST_LOCTRANSFER);
	$_POST['AdjDate'] = new_doc_date();
	if (!is_date_in_fiscalyear($_POST['AdjDate']))
		$_POST['AdjDate'] = end_fiscalyear();
	$_SESSION['transfer_items']->tran_date = $_POST['AdjDate'];	
}
//-----------------------------------------------------------------------------
//Laolu added
function check_data()


{
	global $Refs;

	$tr = &$_SESSION['transfer_items'];
	$input_error = 0;

	if (count($tr->line_items) == 0)	{
		display_error(_("You must enter at least one non empty item line."));
		set_focus('stock_id');
		return false;
	}
	if (!$Refs->is_valid($_POST['ref'])) 
	{
		display_error(_("You must enter a reference."));
		set_focus('ref');
		$input_error = 1;
	} 
	elseif (!is_new_reference($_POST['ref'], ST_LOCTRANSFER)) 
	{
		display_error(_("The entered reference is already in use."));
		set_focus('ref');
		$input_error = 1;
	} 
	elseif (!is_date($_POST['AdjDate'])) 
	{
		display_error(_("The entered date for the adjustment is invalid."));
		set_focus('AdjDate');
		$input_error = 1;
	} 
	elseif (!is_date_in_fiscalyear($_POST['AdjDate'])) 
	{
		display_error(_("The entered date is not in fiscal year."));
		set_focus('AdjDate');
		$input_error = 1;
	} 
	elseif ( !isset($_SESSION['SCANNEDSTOCKS'])  ) //$tr->all_items_picked_s() == 0 ) !isset($_SESSION['SCANNEDSTOCKS']) 
	{
		display_error(_("Not all items  have been picked for this transfer"));
		$input_error = 1;
	}  
	elseif ($_POST['FromStockLocation'] == $_POST['ToStockLocation'])
	{
		display_error(_("The locations to transfer from and to must be different."));
		set_focus('FromStockLocation');
		$input_error = 1;
	} 
	else 
	{
	$msg = $tr->all_items_picked();
	echo 'msg:'.$msg ;
		$failed_item = $tr->check_qoh($_POST['FromStockLocation'], $_POST['AdjDate'], true);
		if ($failed_item >= 0) 
		{
			$line = $tr->line_items[$failed_item];
        	display_error(_("The quantity entered is greater than the available quantity for this item at the source location :") .
        		" " . $line->stock_id . " - " .  $line->item_description);
        	echo "<br>";
			$_POST['Edit'.$failed_item] = 1; // enter edit mode
			$input_error = 1;
		}
	}

	if ($input_error == 1)
		unset($_POST['Process']);
}
//-----------------------------------------------------------------------------------------------

if (isset($_POST['Process']))
{
	check_data();
}

//-------------------------------------------------------------------------------

if (isset($_POST['Process']))
{

	/* $trans_no = add_stock_transfer($_SESSION['transfer_items']->line_items,
		$_POST['FromStockLocation'], $_POST['ToStockLocation'],
		$_POST['AdjDate'], $_POST['type'], $_POST['ref'], $_POST['memo_']);
	new_doc_date($_POST['AdjDate']);  */

	//"/simplex/purchasing/po_receive_serialize.php?TransID=" . $row["trans_id"], ICON_RECEIVE);
   	//meta_forward($_SERVER['PHP_SELF'], "AddedID=$trans_no");

//meta_forward($path_to_root . "/simplex/inventory/transfers_serialize.php", "StockId=$stock_item->stock_id&Qty=$stock_item->quantity");
		
		//$_SESSION['transfer_items']->clear_items();
		//unset($_SESSION['transfer_items']);
unset($_SESSION['SCANNEDSTOCKS']);
}
 /*end of process credit note */

//-----------------------------------------------------------------------------------------------

function check_item_data()
{
	if (!check_num('qty', 0))
	{
		display_error(_("The quantity entered must be a positive number."));
		set_focus('qty');
		return false;
	}
   	return true;
}

//-----------------------------------------------------------------------------------------------

function handle_update_item()
{
    if($_POST['UpdateItem'] != "" && check_item_data())
    {
		$id = $_POST['LineNo'];
    	if (!isset($_POST['std_cost']))
    		$_POST['std_cost'] = $_SESSION['transfer_items']->line_items[$id]->standard_cost;
    	$_SESSION['transfer_items']->update_cart_item($id, input_num('qty'), $_POST['std_cost']);
    }
	line_start_focus();
}

//-----------------------------------------------------------------------------------------------

function handle_delete_item($id)
{
	$_SESSION['transfer_items']->remove_from_cart($id);
	line_start_focus();
}

//-----------------------------------------------------------------------------------------------

function handle_new_item()
{
	if (!check_item_data())
		return;
	if (!isset($_POST['std_cost']))
   		$_POST['std_cost'] = 0;
	add_to_order($_SESSION['transfer_items'], $_POST['stock_id'], input_num('qty'), $_POST['std_cost']);
			//Laolu
		$_SESSION['stocktransferqty'] = input_num('qty');
	line_start_focus();
}

//-----------------------------------------------------------------------------------------------
$id = find_submit('Delete');
if ($id != -1)
	handle_delete_item($id);
	
if (isset($_POST['AddItem']))
	handle_new_item();

if (isset($_POST['UpdateItem']))
	handle_update_item();

if (isset($_POST['CancelItemChanges'])) {
	line_start_focus();
}
//-----------------------------------------------------------------------------------------------

if (isset($_GET['NewTransfer']) || !isset($_SESSION['transfer_items']))
{
	handle_new_order();
}

//-----------------------------------------------------------------------------------------------
start_form();

display_order_header($_SESSION['transfer_items']);

start_table("$table_style width=70%", 10);
start_row();
echo "<td>";
display_transfer_items(_("Items"), $_SESSION['transfer_items']);
transfer_options_controls();
echo "</td>";
end_row();
end_table(1);

submit_center_first('Update', _("Update"), '', null);
submit_center_last('Process', _("Process Transfer"), '',  'default');

end_form();
end_page();

?>