<?php 
function update_sales_order($order)
{
	global $loc_notification, $path_to_root, $Refs;

	$del_date = date2sql($order->due_date);
	$ord_date = date2sql($order->document_date);
	$order_no =  key($order->trans_no);
	$version= current($order->trans_no);

	begin_transaction();

	$sql = "UPDATE ".TB_PREF."sales_orders SET type =".db_escape($order->so_type)." ,
		debtor_no = " . db_escape($order->customer_id) . ",
		branch_code = " . db_escape($order->Branch) . ",
		customer_ref = ". db_escape($order->cust_ref) .",
		reference = ". db_escape($order->reference) .",
		comments = ". db_escape($order->Comments) .",
		ord_date = " . db_escape($ord_date) . ",
		order_type = " .db_escape($order->sales_type) . ",
		ship_via = " . db_escape($order->ship_via) .",
		deliver_to = " . db_escape($order->deliver_to) . ",
		delivery_address = " . db_escape($order->delivery_address) . ",
		contact_phone = " .db_escape($order->phone) . ",
		contact_email = " .db_escape($order->email) . ",
		freight_cost = " .db_escape($order->freight_cost) .",
		from_stk_loc = " .db_escape($order->Location) .",
		delivery_date = " .db_escape($del_date). ",
		version = ".($version+1)."
	 WHERE order_no=" . $order_no ."
	 AND trans_type=".$order->trans_type." AND version=".$version;
	db_query($sql, "order Cannot be Updated, this can be concurrent edition conflict");

	$sql = "DELETE FROM ".TB_PREF."sales_order_details WHERE order_no =" . $order_no . " AND trans_type=".$order->trans_type;

	db_query($sql, "Old order Cannot be Deleted");

	if ($loc_notification == 1)
	{
		include_once($path_to_root . "/inventory/includes/inventory_db.inc");
		$st_ids = array();
		$st_names = array();
		$st_num = array();
		$st_reorder = array();
	}
	$item_id = 0 ;
	foreach ($order->line_items as $line)
	{
		if ($loc_notification == 1 && is_inventory_item($line->stock_id))
		{
			$sql = "SELECT ".TB_PREF."loc_stock.*, "
				  .TB_PREF."locations.location_name, "
				  .TB_PREF."locations.email
				FROM ".TB_PREF."loc_stock, "
				  .TB_PREF."locations
				WHERE ".TB_PREF."loc_stock.loc_code=".TB_PREF."locations.loc_code
				 AND ".TB_PREF."loc_stock.stock_id = ".db_escape($line->stock_id)."
				 AND ".TB_PREF."loc_stock.loc_code = ".db_escape($order->Location);
			$res = db_query($sql,"a location could not be retreived");
			$loc = db_fetch($res);
			if ($loc['email'] != "")
			{
				$qoh = get_qoh_on_date($line->stock_id, $order->Location);
				$qoh -= get_demand_qty($line->stock_id, $order->Location);
				$qoh -= get_demand_asm_qty($line->stock_id, $order->Location);
				$qoh -= $line->quantity;
				if ($qoh < $loc['reorder_level'])
				{
					$st_ids[] = $line->stock_id;
					$st_names[] = $line->item_description;
					$st_num[] = $qoh - $loc['reorder_level'];
					$st_reorder[] = $loc['reorder_level'];
				}
			}
		}
		$item_id++ ;
		$sql = "INSERT INTO ".TB_PREF."sales_order_details
		 (id, order_no, trans_type, stk_code,  description, unit_price, quantity,
		  discount_percent, qty_sent)
		 VALUES (";
		$sql .= $item_id.", ".$order_no . ",".$order->trans_type.","
		  .db_escape($line->stock_id) . ","
		  .db_escape($line->item_description) . ", "
		  .db_escape($line->price) . ", "
		  .db_escape($line->quantity) . ", "
		  .db_escape($line->discount_percent) . ", "
		  .db_escape($line->qty_done) ." )";

		db_query($sql, "Old order Cannot be Inserted");

	} /* inserted line items into sales order details */

	add_audit_trail($order->trans_type, $order_no, $order->document_date, _("Updated."));
	$Refs->delete($order->trans_type, $order_no);
	$Refs->save($order->trans_type, $order_no, $order->reference);
	commit_transaction();
	if ($loc_notification == 1 && count($st_ids) > 0)
	{
		require_once($path_to_root . "/reporting/includes/class.mail.inc");
		$company = get_company_prefs();
		$mail = new email($company['coy_name'], $company['email']);
		$from = $company['coy_name'] . " <" . $company['email'] . ">";
		$to = $loc['location_name'] . " <" . $loc['email'] . ">";
		$subject = _("Stocks below Re-Order Level at " . $loc['location_name']);
		$msg = "\n";
		for ($i = 0; $i < count($st_ids); $i++)
			$msg .= $st_ids[$i] . " " . $st_names[$i] . ", "
			  . _("Re-Order Level") . ": " . $st_reorder[$i] . ", "
			  . _("Below") . ": " . $st_num[$i] . "\n";
		$msg .= "\n" . _("Please reorder") . "\n\n";
		$msg .= $company['coy_name'];
		$mail->to($to);
		$mail->subject($subject);
		$mail->text($msg);
		$ret = $mail->send();
	}
}
?>