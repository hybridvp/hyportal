<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
//----------------------------------------------------------------------------------------

function delete_pr($po)
{
	$sql = "DELETE FROM ".TB_PREF."purch_reqs WHERE pr_no=".db_escape($po);
	db_query($sql, "The requisition header could not be deleted");

	$sql = "DELETE FROM ".TB_PREF."purch_req_details WHERE pr_no =".db_escape($po);
	db_query($sql, "The requisition detail lines could not be deleted");
}

//----------------------------------------------------------------------------------------

function add_pr(&$pr_obj)
{

	global $OurRefs;


	//begin_transaction();

     /*Get the auto increment value of the requisition  number created from the sql above */
	 
     $pr_obj->order_no = db_insert_id("purch_reqs_seq");
     var_dump ($pr_obj) ;

     /*Insert to purchase requisition header record */
     $sql = "INSERT INTO ".TB_PREF."purch_reqs (pr_no, supplier_id, Comments, ord_date, reference, requisition_no, into_stock_location, delivery_address, approved_by1 ) VALUES(";
     $sql .= db_escape($pr_obj->order_no) . "," .db_escape($pr_obj->supplier_id) . "," .
         db_escape($pr_obj->Comments) . ",'" .
         date2sql($pr_obj->orig_order_date) . "', " .
		 db_escape($pr_obj->reference) . ", " .
         db_escape($pr_obj->requisition_no) . ", " .
         db_escape($pr_obj->Location) . ", " .
         db_escape($pr_obj->delivery_address) . ",".
         db_escape($pr_obj->approved_by1) . ")";

	db_query($sql, "The purchase requisition header record could not be inserted");


     //$pr_det_item = 0 ;
     /*Insert the purchase requisition detail records */
     foreach ($pr_obj->line_items as $po_line)
     {
     	if ($po_line->Deleted == false)
     	{
			$pr_det_item =db_insert_id("purch_req_details_item_seq") ;
    		$sql = "INSERT INTO ".TB_PREF."purch_req_details (pr_detail_item, pr_no, item_code, description, costcentre, delivery_date,	unit_price,	quantity_ordered) VALUES (";
    		$sql .= $pr_det_item.", ".$pr_obj->order_no . ", " . db_escape($po_line->stock_id). "," .
    			db_escape($po_line->item_description). "," .
				db_escape($po_line->costcentre). ",'" .
    			date2sql($po_line->req_del_date) . "'," .
    			db_escape($po_line->price) . ", " .
    			db_escape($po_line->quantity). ")";
			db_query($sql, "One of the purchase requisition detail records could not be inserted");
    	}
     }



	$OurRefs->save(ST_PURCHREQ, $pr_obj->order_no, $pr_obj->reference);


	add_comments(ST_PURCHREQ, $pr_obj->order_no, $pr_obj->orig_order_date, $pr_obj->Comments);

	add_audit_trail(ST_PURCHREQ, $pr_obj->order_no, $pr_obj->orig_order_date);

	commit_transaction();
/**Check it is getting here 
//display_notification_centered("back here after a call to handle new orrder");
//display_error(_("back here after a call to handle new orrder."));
	set_focus('ref');
    		return false;
****/

	return $pr_obj->order_no;
}

//----------------------------------------------------------------------------------------

function update_pr(&$pr_obj)
{
	begin_transaction();

    /*Update the purchase requisition header with any changes */
    $sql = "UPDATE ".TB_PREF."purch_reqs SET Comments=" . db_escape($pr_obj->Comments) . ",
		requisition_no= ". db_escape( $pr_obj->requisition_no). ",
		into_stock_location=" . db_escape($pr_obj->Location). ",		
		approved_by1=" . db_escape($pr_obj->approved_by1). ",
		ord_date='" . date2sql($pr_obj->orig_order_date) . "',
		delivery_address=" . db_escape($pr_obj->delivery_address);
    $sql .= " WHERE pr_no = " . $pr_obj->order_no;
	db_query($sql, "The purchase requisition could not be updated");

    /*Now Update the purchase order detail records */
    foreach ($pr_obj->line_items as $po_line)
    {

		if ($po_line->Deleted==True)
		{
			// Sherifoz 21.06.03 Handle deleting existing lines
			if ($po_line->po_detail_rec!='')
			{
				$sql = "DELETE FROM ".TB_PREF."purch_req_details WHERE pr_detail_item=".db_escape($po_line->po_detail_rec);
				db_query($sql, "could not query purch requisition details");
			}
		}
		else if ($po_line->po_detail_rec == '')
		{
			// Sherifoz 21.06.03 Handle adding new lines vs. updating. if no key(po_detail_rec) then it's a new line
			$sql = "INSERT INTO ".TB_PREF."purch_req_details (pr_no, item_code, description, costcentre, delivery_date, unit_price,	quantity_ordered) VALUES (";
			$sql .= $pr_obj->order_no . "," .
				db_escape($po_line->stock_id). "," .
				db_escape($po_line->item_description). "," .
				db_escape($po_line->costcentre). ",'" .
				date2sql($po_line->req_del_date) . "'," .
				db_escape($po_line->price) . ", ".db_escape($po_line->quantity) . ")";
		}
		else
		{
			$sql = "UPDATE ".TB_PREF."purch_req_details SET item_code=".db_escape($po_line->stock_id).",
				description =" . db_escape($po_line->item_description). ",
				costcentre =" . db_escape($po_line->costcentre). ",
				delivery_date ='" . date2sql($po_line->req_del_date) . "',
				unit_price=".db_escape($po_line->price).",
				quantity_ordered=".db_escape($po_line->quantity) . "
				WHERE pr_detail_item=".db_escape($po_line->po_detail_rec);
		}
		db_query($sql, "One of the purchase order requisition records could not be updated");
    }

	add_comments(ST_PURCHREQ, $pr_obj->order_no, $pr_obj->orig_order_date, $pr_obj->Comments);

	commit_transaction();

	return $pr_obj->order_no;
}

//----------------------------------------------------------------------------------------

function read_pr_header($order_no, &$order)
{
   	$sql = "SELECT ".TB_PREF."purch_reqs.*, ".TB_PREF."suppliers.supp_name,
   		".TB_PREF."suppliers.curr_code, ".TB_PREF."locations.location_name
		FROM ".TB_PREF."purch_reqs, ".TB_PREF."suppliers, ".TB_PREF."locations
		WHERE ".TB_PREF."purch_reqs.supplier_id = ".TB_PREF."suppliers.supplier_id
		AND ".TB_PREF."locations.loc_code = into_stock_location
		AND ".TB_PREF."purch_reqs.pr_no = ".db_escape($order_no);
//	echo $sql;
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
     	$order->approved_by1 = $myrow["approved_by1"];
     	$order->approved_by2 = $myrow["approved_by2"];
     	$order->approved_by3 = $myrow["approved_by3"];

     	$order->approver_status1 = $myrow["approver_status1"];
     	$order->approver_status2 = $myrow["approver_status2"];
     	$order->approver_status3 = $myrow["approver_status3"];

     	$order->no_authoriser = $myrow["no_authoriser"];

      	return true;
	}

	display_db_error("FATAL : duplicate purchase order found", "", true);
	return false;
}

//----------------------------------------------------------------------------------------

function read_pr_items($order_no, &$order, $open_items_only=false)
{
	/*now populate the line po array with the purchase order details records */

	$sql = "SELECT ".TB_PREF."purch_req_details.*, units
		FROM ".TB_PREF."purch_req_details
		LEFT JOIN ".TB_PREF."stock_master
		ON ".TB_PREF."purch_req_details.item_code=".TB_PREF."stock_master.stock_id
		WHERE pr_no =".db_escape($order_no);

    if ($open_items_only)
 		$sql .= " AND (".TB_PREF."purch_req_details.quantity_ordered > ".TB_PREF."purch_req_details.quantity_received) ";

	$sql .= " ORDER BY pr_detail_item";

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
					$order->line_items[$order->lines_on_order]->po_detail_rec = $myrow["pr_detail_item"];
					$order->line_items[$order->lines_on_order]->standard_cost = $myrow["std_cost_unit"];  /*Needed for receiving goods and GL interface */
			}
        } /* line po from purchase order details */
    } //end of checks on returned data set
}

//----------------------------------------------------------------------------------------

function read_pr($order_no, &$order, $open_items_only=false)
{
	$result = read_pr_header($order_no, $order);

	if ($result)
		read_pr_items($order_no, $order, $open_items_only);
}

//----------------------------------------------------------------------------------------


?>