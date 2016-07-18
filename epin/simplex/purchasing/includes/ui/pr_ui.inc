<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
include_once($path_to_root . "/simplex/purchasing/includes/requisition_db.inc");

include_once($path_to_root . "/simplex/includes/ui/our_ui_lists.inc");

//include_once($path_to_root . "/simplex/includes/simplexreferences.inc");

// ------------------------------------------------------------------------------

function get_supplier_details_to_order(&$order, $supplier_id)
{
	$sql = "SELECT curr_code,supp_name FROM ".TB_PREF."suppliers
		WHERE supplier_id = '$supplier_id'";
	$result = db_query($sql, "The supplier details could not be retreived");

	$myrow = db_fetch($result);
  
	$order->curr_code = $_POST['curr_code'] = $myrow["curr_code"];
	$order->supplier_name = $_POST['supplier_name'] = $myrow["supp_name"];
	$order->supplier_id = $_POST['supplier_id'] = $supplier_id;
}

//---------------------------------------------------------------------------------------------------

function create_new_pr()
{
	if (isset($_SESSION['PO']))
	{
	   
		unset ($_SESSION['PO']->line_items);
		$_SESSION['PO']->lines_on_order = 0;
		unset ($_SESSION['PO']);
	}

	//session_register("PO");

	$_SESSION['PO'] = new purch_order;
	$_POST['OrderDate'] = new_doc_date();
	if (!is_date_in_fiscalyear($_POST['OrderDate']))
		$_POST['OrderDate'] = end_fiscalyear();
	$_SESSION['PO']->orig_order_date = $_POST['OrderDate'];
}

//---------------------------------------------------------------------------------------------------

function display_pr_header(&$order)
{
	global $table_style2, $Ajax, $OurRefs;

	$editable = ($order->order_no == 0);

	start_outer_table("width=80% $table_style2");

	table_section(1);

    if ($editable)
    {
        if (!isset($_POST['supplier_id']) && (get_global_supplier() != ALL_TEXT))

             
			$_POST['supplier_id'] = get_global_supplier();
			supplier_list_row(_("Supplier:"), 'supplier_id', null, false, true, false, true);
			//added this to solve currency error 
			get_supplier_details_to_order($order, $_POST['supplier_id']);
		
	}
	else
	{
		hidden('supplier_id', $order->supplier_id);
		label_row(_("Supplier:"), $order->supplier_name);
    }
	
	if ($order->supplier_id != get_post('supplier_id',-1)) {
//Problem found here but fixed up	    
//		display_notification ("Doing it here and here... or this 2  ".$order->supplier_id);
	
		get_supplier_details_to_order($order, $_POST['supplier_id']);
		// supplier default price update
		foreach ($order->line_items as $line_no=>$item) {
			$line = &$order->line_items[$line_no];
			$line->price =  get_purchase_price ($order->supplier_id, $_POST['stock_id']);
			$line->quantity =  get_purchase_conversion_factor ($order->supplier_id, $_POST['stock_id']);
		}
	    $Ajax->activate('items_table');
	}
	set_global_supplier($_POST['supplier_id']);

	if (!is_company_currency($order->curr_code))
	{
		label_row(_("Supplier Currency:"), $order->curr_code);
		exchange_rate_display($order->curr_code, get_company_currency(),
			$_POST['OrderDate']);
	}

    if ($editable)
    {
    	ref_row(_("RFQ:"), 'ref', '', $OurRefs->get_next_simple_ref(ST_PURCHREQ));
    }
    else
    {
    	hidden('ref', $order->reference);
    	label_row(_("RFQ:"), $order->reference);
    }
	echo "<tr> ";
	label_cell("Authoriser","width=50 colspan=1 align='left'");
	approving_list_cells(null, 'approved_by1', null);
	echo "</tr>";

	table_section(2);

	// check this out?????????
	//if (!isset($_POST['OrderDate']) || $_POST['OrderDate'] == "")
	//	$_POST['OrderDate'] = $order->orig_order_date;
	//if (!isset($_POST['OrderDate']) || $_POST['OrderDate'] == "")
	//{
	//	$_POST['OrderDate'] = Today();
	//	if (!is_date_in_fiscalyear($_POST['OrderDate']))
	//		$_POST['OrderDate'] = end_fiscalyear();
	//}
	date_row(_("Requisition Date:"), 'OrderDate', '', true, 0, 0, 0, null, true);
	if (isset($_POST['_OrderDate_changed'])) {
		$Ajax->activate('_ex_rate');
	}

  	text_row(_("Supplier's Reference:"), 'Requisition', null, 16, 15);

	echo "<tr><td>" . _("Receive Into:") . "</td>";
	echo "<td>";
    echo locations_list('StkLocation', null, false, true);
	echo "</td></tr>";

	table_section(3);

    if (!isset($_POST['StkLocation']) || $_POST['StkLocation'] == "" ||
    	isset($_POST['_StkLocation_update']) || !isset($_POST['delivery_address']) ||
    	$_POST['delivery_address'] == "")
    {
    	/*If this is the first time the form loaded set up defaults */

        //$_POST['StkLocation'] = $_SESSION['UserStockLocation'];
        $sql = "SELECT delivery_address, phone FROM ".TB_PREF."locations WHERE loc_code='" . $_POST['StkLocation'] . "'";
        $result = db_query($sql,"could not get location info");

        if (db_num_rows($result) == 1)
        {
    	  	$loc_row = db_fetch($result);
    	  	$_POST['delivery_address'] = $loc_row["delivery_address"];
			$Ajax->activate('delivery_address');
    	  	$_SESSION['PO']->Location = $_POST['StkLocation'];
    	  	$_SESSION['PO']->delivery_address = $_POST['delivery_address'];

        }
        else
        { /*The default location of the user is crook */
    	  	display_error(_("The default stock location set up for this user is not a currently defined stock location. Your system administrator needs to amend your user record."));
        }
    }

	textarea_row(_("Deliver to:"), 'delivery_address', $_POST['delivery_address'], 35, 4);

	end_outer_table(); // outer table
}

//---------------------------------------------------------------------------------------------------

function display_pr_items(&$order, $editable=true)
{
	global $table_style;

    display_heading(_("Order Items"));

    div_start('items_table');
    start_table("$table_style width=80%");

   	$th = array(_("Item Code"), _("Item Description"), _("Cost Centre"), _("Quantity"),
		_("Received"), _("Unit"),
   		_("Required Delivery Date"), _("Price"), _("Line Total"), "");

	if (count($order->line_items)) $th[] = '';
   	table_header($th);

	$id = find_submit('Edit');
	$total = 0;
	$k = 0;
   	foreach ($order->line_items as $line_no => $po_line)
   	{

		if ($po_line->Deleted == false)
		{
    		$line_total =	round($po_line->quantity * $po_line->price,  user_price_dec());
    		if (!$editable || ($id != $line_no))
			{
    			alt_table_row_color($k);
        		label_cell($po_line->stock_id);
    			label_cell($po_line->item_description);
				//added  cost center
				label_cell($po_line->costcentre);
				//
				qty_cell($po_line->quantity, false, get_qty_dec($po_line->stock_id));
                qty_cell($po_line->qty_received, false, get_qty_dec($po_line->stock_id));
    			label_cell($po_line->units);
                label_cell($po_line->req_del_date);

    			amount_decimal_cell($po_line->price);
                amount_cell($line_total);

                if ($editable)
                {
					edit_button_cell("Edit$line_no", _("Edit"),
					  _('Edit document line'));
					delete_button_cell("Delete$line_no", _("Delete"),
					  _('Remove line from document'));
                }
        		end_row();
			}
			else
			{
				pr_item_controls($order, $po_line->stock_id);
			}
    		$total += $line_total;
		}
    }

	if ($id==-1 && $editable)
	{
		pr_item_controls($order);
    }
    $display_total = price_format($total);
    label_row(_("Total Excluding Shipping/Tax"), $display_total, "colspan=7 align=right",
    	"nowrap align=right", 2);

	end_table(1);
	div_end();
}

//---------------------------------------------------------------------------------------------------

function display_pr_summary(&$po, $is_self=false, $editable=false)
{
	global $table_style2;
    start_table("$table_style2 width=90%");

    start_row();
    label_cells(_("Reference"), $po->reference, "class='tableheader2'");

    label_cells(_("Supplier"), $po->supplier_name, "class='tableheader2'");

    if (!is_company_currency($po->curr_code))
    	label_cells(_("Order Currency"), $po->curr_code, "class='tableheader2'");

    if (!$is_self)
    {
    	label_cells(_("Purchase Order"), get_trans_view_str(ST_PURCHORDER, $po->order_no),
    		"class='tableheader2'");
    }
	end_row();
	start_row();
    label_cells(_("Date"), $po->orig_order_date, "class='tableheader2'");

    if ($editable)
    {
        if (!isset($_POST['Location']))
        	$_POST['Location'] = $po->Location;
        label_cell(_("Deliver Into Location"), "class='tableheader2'");
        locations_list_cells(null, 'Location', $_POST['Location']);
    }
    else
    {
    	label_cells(_("Deliver Into Location"), get_location_name($po->Location),
    		"class='tableheader2'");
    }

    if ($po->requisition_no != "")
    	label_cells(_("Supplier's Reference"), $po->requisition_no, "class='tableheader2'");
    end_row();

    if (!$editable)
     {
   	     if (is_null($po->approved_by2)||$po->approved_by2=="") 
		    label_row(_("Authoriser"), $po->approved_by1, "class='tableheader2'","colspan=9");
 	     else  
		 	label_row(_("Authorisers"), $po->approved_by1."; ".$po->approved_by2."; ".
						$po->approved_by3, "class='tableheader2'","colspan=9");
 
    	label_row(_("Delivery Address"), $po->delivery_address, "class='tableheader2'",
    		"colspan=9");
	}
    if ($po->Comments != "")
    	label_row(_("Order Comments"), $po->Comments, "class='tableheader2'",
    		"colspan=9");
			
	//label_row(_( " here listing  po->1 "), $po->approver_status1) ;
    end_table(1);
///			
//Send this information to determine whether next approval should be selectted or not
//
	hidden('approved_by1',  $po->approved_by1);
	hidden('approved_by2',  $po->approved_by2);
	hidden('approved_by3',  $po->approved_by3);
    
	hidden('approver_status1',  $po->approver_status1);
	hidden('approver_status2',  $po->approver_status2);
	hidden('approver_status3',  $po->approver_status3);
 	
	hidden('no_authoriser',  $po->no_authoriser);
 	
}

//--------------------------------------------------------------------------------

function pr_item_controls(&$order, $stock_id=null)
{
   global $Ajax;
	start_row();
	$dec2 = 0;
	$id = find_submit('Edit');
	if (($id != -1) && $stock_id != null)
	{
		hidden('line_no', $id);

		$_POST['stock_id'] = $order->line_items[$id]->stock_id;
			$dec = get_qty_dec($_POST['stock_id']);
		$_POST['qty'] = qty_format($order->line_items[$id]->quantity, $_POST['stock_id'], $dec);
		//$_POST['price'] = price_format($order->line_items[$id]->price);
		$_POST['price'] = price_decimal_format($order->line_items[$id]->price, $dec2);
		$_POST['req_del_date'] = $order->line_items[$id]->req_del_date;

		$_POST['units'] = $order->line_items[$id]->units;
		//added costcentre post 
		$_POST['costcentre'] = $order->line_items[$id]->costcentre;
		hidden('stock_id', $_POST['stock_id']);
		label_cell($_POST['stock_id']);
		label_cell($order->line_items[$id]->item_description);
	    $Ajax->activate('items_table');
		$qty_rcvd = $order->line_items[$id]->qty_received;
	}
	else
	{
		hidden('line_no', ($_SESSION['PO']->lines_on_order + 1));

		stock_purchasable_items_list_cells(null, 'stock_id', null, false, true, true);
		if (list_updated('stock_id')) {
			    $Ajax->activate('price');
			    $Ajax->activate('units');
			    $Ajax->activate('qty');
			    $Ajax->activate('req_del_date');
				$Ajax->activate('costcentre');
				
			    $Ajax->activate('line_total');
		}
    	$item_info = get_item_edit_info($_POST['stock_id']);
		$_POST['units'] = $item_info["units"];

   		$dec = $item_info["decimals"];
   		$_POST['qty'] =	number_format2(get_purchase_conversion_factor ($order->supplier_id, $_POST['stock_id']), $dec);
		//$_POST['price'] = price_format(get_purchase_price ($order->supplier_id, $_POST['stock_id']));
		$_POST['price'] = price_decimal_format(get_purchase_price ($order->supplier_id, $_POST['stock_id']), $dec2);
		//Deliver is due 10 days after by default
		$_POST['req_del_date'] = add_days(Today(), 10);
	    //Added costcentre post as well 
		$_POST['costcentre'] = '';
		$qty_rcvd = '';
	}
	//Added costcentre
	//usage is text_cells(label, varname,initialval,size);	
	ana_code_list_cells("", 'costcentre',$_POST['costcentre'],_('Select cost centre code'), false);
		
	qty_cells(null, 'qty', null, null, null, $dec);
	qty_cell($qty_rcvd, false, $dec);

	label_cell($_POST['units'], '', 'units');

	date_cells(null, 'req_del_date', '', null, 0, 0, 0);
	
	amount_cells(null, 'price', null, null, null, $dec2);

	//$line_total = $_POST['qty'] * $_POST['price'] * (1 - $_POST['Disc'] / 100);
	$line_total = round(input_num('qty') * input_num('price'),  user_price_dec());
	amount_cell($line_total, false, '','line_total');

	if ($id!=-1)
	{
		button_cell('UpdateLine', _("Update"),
				_('Confirm changes'), ICON_UPDATE);
		button_cell('CancelUpdate', _("Cancel"),
				_('Cancel changes'), ICON_CANCEL);
		//set_focus('qty');
		set_focus('costcentre');
	}
	else
	{
		submit_cells('EnterLine', _("Add Item"), "colspan=2",
		    _('Add new item to document'), true);
	}

	end_row();
}

//---------------------------------------------------------------------------------------------------



?>