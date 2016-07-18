<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
//----------------------------------------------------------------------------------------

function delete_po($po)
{
	$sql = "DELETE FROM ".TB_PREF."purch_orders WHERE order_no=".db_escape($po);
	db_query($sql, "The order header could not be deleted");

	$sql = "DELETE FROM ".TB_PREF."purch_order_details WHERE order_no =".db_escape($po);
	db_query($sql, "The order detail lines could not be deleted");
}

//----------------------------------------------------------------------------------------

function add_po(&$po_obj)
{
	global $Refs;

	begin_transaction();

     /*Get the auto increment value of the order number created from the sql above */
     $po_obj->order_no = db_insert_id("purch_orders_no_seq");

     /*Insert to purchase order header record */
     $sql = "INSERT INTO ".TB_PREF."purch_orders (order_no,supplier_id, Comments, ord_date, reference, 
	 requisition_no, into_stock_location, delivery_address, created_by, created_date) VALUES(";
     $sql .= db_escape($po_obj->order_no) . "," .db_escape($po_obj->supplier_id) . "," .
         db_escape($po_obj->Comments) . ",'" .
         date2sql($po_obj->orig_order_date) . "', " .
		 db_escape($po_obj->reference) . ", " .
         db_escape($po_obj->requisition_no) . ", " .
         db_escape($po_obj->Location) . ", " .
         db_escape($po_obj->delivery_address) . ",".
		 db_escape($_SESSION["wa_current_user"]->last_act).",sysdate)";

	db_query($sql, "The purchase order header record could not be inserted");

     //$po_det_item = 0 ;
     /*Insert the purchase order detail records */
     foreach ($po_obj->line_items as $po_line)
     {
     	if ($po_line->Deleted == false)
     	{
			$po_det_item = db_insert_id("purch_order_details_item_seq") ;
    		$sql = "INSERT INTO ".TB_PREF."purch_order_details (po_detail_item, order_no, item_code, description, 
			costcentre, delivery_date, unit_price,	quantity_ordered) VALUES (";
    		$sql .= $po_det_item. ", ".$po_obj->order_no . ", " . db_escape($po_line->stock_id). "," .
    			db_escape($po_line->item_description). "," .
				db_escape($po_line->costcentre). ",'" .
    			date2sql($po_line->req_del_date) . "'," .
    			db_escape($po_line->price) . ", " .
    			db_escape($po_line->quantity). ")";
			db_query($sql, "One of the purchase order detail records could not be inserted");
    	}
     }

	$Refs->save(ST_PURCHORDER, $po_obj->order_no, $po_obj->reference);

	//add_comments(ST_PURCHORDER, $po_obj->order_no, $po_obj->orig_order_date, $po_obj->Comments);

	add_audit_trail(ST_PURCHORDER, $po_obj->order_no, $po_obj->orig_order_date);
	commit_transaction();

	return $po_obj->order_no;
}

//----------------------------------------------------------------------------------------

function update_po(&$po_obj)
{
	begin_transaction();

    /*Update the purchase order header with any changes */
    $sql = "UPDATE ".TB_PREF."purch_orders SET Comments=" . db_escape($po_obj->Comments) . ",
		requisition_no= ". db_escape( $po_obj->requisition_no). ",
		into_stock_location=" . db_escape($po_obj->Location). ",
		ord_date='" . date2sql($po_obj->orig_order_date) . "',
		delivery_address=" . db_escape($po_obj->delivery_address);
    $sql .= " WHERE order_no = " . $po_obj->order_no;
	db_query($sql, "The purchase order could not be updated");

    /*Now Update the purchase order detail records */
    foreach ($po_obj->line_items as $po_line)
    {

		if ($po_line->Deleted==True)
		{
			// Sherifoz 21.06.03 Handle deleting existing lines
			if ($po_line->po_detail_rec!='')
			{
				$sql = "DELETE FROM ".TB_PREF."purch_order_details WHERE po_detail_item=".db_escape($po_line->po_detail_rec);
				db_query($sql, "could not query purch order details");
			}
		}
		else if ($po_line->po_detail_rec == '')
		{
			// Sherifoz 21.06.03 Handle adding new lines vs. updating. if no key(po_detail_rec) then it's a new line
			$sql = "INSERT INTO ".TB_PREF."purch_order_details (order_no, item_code, description, costcentre,  delivery_date, unit_price,	quantity_ordered) VALUES (";
			$sql .= $po_obj->order_no . "," .
				db_escape($po_line->stock_id). "," .
				db_escape($po_line->item_description). "," .
				db_escape($po_line->costcentre). ",'" .
				date2sql($po_line->req_del_date) . "'," .
				db_escape($po_line->price) . ", ".db_escape($po_line->quantity) . ")";
		}
		else
		{
			$sql = "UPDATE ".TB_PREF."purch_order_details SET item_code=".db_escape($po_line->stock_id).",
				description =" . db_escape($po_line->item_description). ",
				costcentre =" . db_escape($po_line->costcentre). ",
				delivery_date ='" . date2sql($po_line->req_del_date) . "',
				unit_price=".db_escape($po_line->price).",
				quantity_ordered=".db_escape($po_line->quantity) . "
				WHERE po_detail_item=".db_escape($po_line->po_detail_rec);
		}
		db_query($sql, "One of the purchase order detail records could not be updated");
    }

	//add_comments(ST_PURCHORDER, $po_obj->order_no, $po_obj->orig_order_date, $po_obj->Comments);

	commit_transaction();

	return $po_obj->order_no;
}

//----------------------------------------------------------------------------------------

function read_po_header($order_no, &$order)
{
   	$sql = "SELECT ".TB_PREF."purch_orders.*, ".TB_PREF."suppliers.supp_name,
   		".TB_PREF."suppliers.curr_code, ".TB_PREF."locations.location_name
		FROM ".TB_PREF."purch_orders, ".TB_PREF."suppliers, ".TB_PREF."locations
		WHERE ".TB_PREF."purch_orders.supplier_id = ".TB_PREF."suppliers.supplier_id
		AND ".TB_PREF."locations.loc_code = 'DEF'
		AND ".TB_PREF."purch_orders.order_no = ".db_escape($order_no);
		

   	$result = db_query($sql, "The order cannot be retrieved");

	if (db_num_rows($result) == 1)
	{

      	$myrow = db_fetch($result);

      	$order->order_no = $order_no;
      	$order->supplier_id = $myrow["supplier_id"];
      	$order->supplier_name = $myrow["supp_name"];
      	$order->curr_code = $myrow["curr_code"];

      	$order->orig_order_date = sql2date($myrow["ord_date"]);
      	$order->Comments = $myrow["comments"];
      	$order->Location = $myrow["into_stock_location"];
      	$order->requisition_no = $myrow["requisition_no"];
      	$order->reference = $myrow["reference"];
      	$order->delivery_address = $myrow["delivery_address"];

      	return true;
	}

	display_db_error("FATAL : duplicate purchase order found", "", true);
	return false;
}

//----------------------------------------------------------------------------------------

function read_po_items($order_no, &$order, $open_items_only=false)
{
	/*now populate the line po array with the purchase order details records */

	$sql = "SELECT ".TB_PREF."purch_order_details.*, units
		FROM ".TB_PREF."purch_order_details
		LEFT JOIN ".TB_PREF."stock_master
		ON ".TB_PREF."purch_order_details.item_code=".TB_PREF."stock_master.stock_id
		WHERE order_no =".db_escape($order_no);

    if ($open_items_only)
 		$sql .= " AND (".TB_PREF."purch_order_details.quantity_ordered > ".TB_PREF."purch_order_details.quantity_received) ";

	$sql .= " ORDER BY po_detail_item";

	$result = db_query($sql, "The lines on the purchase order cannot be retrieved");

    if (db_num_rows($result) > 0)
    {

		while ($myrow = db_fetch($result))
        {

        	$data = get_purchase_data($order->supplier_id, $myrow['item_code']);
        	if ($data !== false)
        	{
        		if ($data['supplier_description'] != "")
        			$myrow['description'] = $data['supplier_description'];
        		//if ($data['suppliers_uom'] != "")
        		//	$myrow['units'] = $data['suppliers_uom'];
        	}		
            if (is_null($myrow["units"]))
            {
		       	$units = "";
            }
            else
            {
                $units = $myrow["units"];
            }

            if ($order->add_to_order($order->lines_on_order+1, $myrow["item_code"],
            	$myrow["quantity_ordered"],$myrow["description"],$myrow["costcentre"],
            	$myrow["unit_price"],$units, sql2date($myrow["delivery_date"]),
            	$myrow["qty_invoiced"], $myrow["quantity_received"])) {
					$order->line_items[$order->lines_on_order]->po_detail_rec = $myrow["po_detail_item"];
					$order->line_items[$order->lines_on_order]->standard_cost = $myrow["std_cost_unit"];  /*Needed for receiving goods and GL interface */
			}
        } /* line po from purchase order details */
    } //end of checks on returned data set
}

//----------------------------------------------------------------------------------------

function read_po($order_no, &$order, $open_items_only=false)
{
	$result = read_po_header($order_no, $order);

	if ($result)
		read_po_items($order_no, $order, $open_items_only);
}

//----------------------------------------------------------------------------------------
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
////---PR---Reader---Start---Here---------------------------------------------------------
///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////
////----------------------------------------------------------------------------------------

function read_prpo_header($order_no, &$order)
{
   	$sql = "SELECT pr.pr_no order_no,  
	    pr.pr_no, pr.supplier_id, pr.comments, pr.ord_date,'' reference, 
		('PR:'||pr.pr_no||'/Ref:'||pr.requisition_no) as requisition_no, 
		pr.into_stock_location, pr.delivery_address, 
		sp.supp_name,
   		sp.curr_code, loc.location_name
		FROM ".TB_PREF."purch_reqs pr, ".TB_PREF."suppliers sp, ".TB_PREF."locations loc 
		WHERE pr.supplier_id = sp.supplier_id
		AND loc.loc_code = into_stock_location
		AND pr.pr_no = ".db_escape($order_no)." 
		AND status='Approved'";
    //display_notification ($sql);
   	$result = db_query($sql, "The order cannot be retrieved");

	if (db_num_rows($result) == 1)
	{

      	$myrow = db_fetch($result);

      	$order->order_no = $order_no;
      	$order->supplier_id = $myrow["supplier_id"];
      	$order->supplier_name = $myrow["supp_name"];
      	$order->curr_code = $myrow["curr_code"];

      	$order->orig_order_date = sql2date($myrow["ord_date"]);
      	$order->Comments = $myrow["comments"];
      	$order->Location = $myrow["into_stock_location"];
      	$order->requisition_no = $myrow["requisition_no"];
      	$order->reference = $myrow["reference"];
      	$order->delivery_address = $myrow["delivery_address"];

      	return true;
	}

	display_db_error("FATAL : no purchase requisistion found", "", false);
	return false;
}

//----------------------------------------------------------------------------------------

function read_prpo_items($order_no, &$order, $open_items_only=false)
{
	/*now populate the line po array with the purchase order details records */

	$sql = "SELECT ".TB_PREF."purch_req_details.pr_detail_item po_detail_item, 
		".TB_PREF."purch_req_details.*, units
		FROM ".TB_PREF."purch_req_details
		LEFT JOIN ".TB_PREF."stock_master
		ON ".TB_PREF."purch_req_details.item_code=".TB_PREF."stock_master.stock_id
		WHERE pr_no =".db_escape($order_no);
/*
    if ($open_items_only)
 		$sql .= " AND (".TB_PREF."purch_order_details.quantity_ordered > ".TB_PREF."purch_order_details.quantity_received) ";
*/
	$sql .= " ORDER BY po_detail_item";

	$result = db_query($sql, "The lines on the purchase order cannot be retrieved");

    if (db_num_rows($result) > 0)
    {

		while ($myrow = db_fetch($result))
        {

        	$data = get_purchase_data($order->supplier_id, $myrow['item_code']);
        	if ($data !== false)
        	{
        		if ($data['supplier_description'] != "")
        			$myrow['description'] = $data['supplier_description'];
        		//if ($data['suppliers_uom'] != "")
        		//	$myrow['units'] = $data['suppliers_uom'];
        	}		
            if (is_null($myrow["units"]))
            {
		       	$units = "";
            }
            else
            {
                $units = $myrow["units"];
            }

            if ($order->add_to_order($order->lines_on_order+1, $myrow["item_code"],
            	$myrow["quantity_ordered"],$myrow["description"],$myrow["costcentre"],
            	$myrow["unit_price"],$units, sql2date($myrow["delivery_date"]),
            	$myrow["qty_invoiced"], $myrow["quantity_received"])) {
					$order->line_items[$order->lines_on_order]->po_detail_rec = $myrow["po_detail_item"];
					$order->line_items[$order->lines_on_order]->standard_cost = $myrow["std_cost_unit"];  /*Needed for receiving goods and GL interface */
			}
        } /* line po from purchase order details */
    } //end of checks on returned data set
}

//----------------------------------------------------------------------------------------

function read_prpo($order_no, &$order, $open_items_only=false)
{
	$result = read_prpo_header($order_no, $order);

	if ($result)
		read_prpo_items($order_no, $order, $open_items_only);
}
////Update converted Pr to OrderedClosed

function update_prpo($pr_no)
{
	begin_transaction();

    /*Update the purchase order header with any changes */
    $sql = "UPDATE ".TB_PREF."purch_reqs SET status='OrderedClosed'
 			WHERE pr_no = " .$pr_no;
	db_query($sql, "The purchase requisition status could not be updated");

	commit_transaction();
}

//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
?>