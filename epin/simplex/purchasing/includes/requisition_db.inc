<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
include_once($path_to_root . "/purchasing/includes/supp_trans_class.inc");

include_once($path_to_root . "/includes/banking.inc");

include_once($path_to_root . "/includes/date_functions.inc");

include_once($path_to_root . "/includes/db/inventory_db.inc");

include_once($path_to_root . "/purchasing/includes/db/supp_trans_db.inc");
include_once($path_to_root . "/simplex/purchasing/includes/db/pr_db.inc");
include_once($path_to_root . "/purchasing/includes/db/grn_db.inc");
include_once($path_to_root . "/purchasing/includes/db/invoice_db.inc");
include_once($path_to_root . "/purchasing/includes/db/suppalloc_db.inc");
include_once($path_to_root . "/purchasing/includes/db/supp_payment_db.inc");
include_once($path_to_root . "/purchasing/includes/db/suppliers_db.inc");
//include_once($path_to_root . "/simplex/purchasing/includes/purchasing_db.inc");

//-------------------------------------------------------------------------------------------------------------

// add a supplier-related gl transaction
// $date_ is display date (non-sql)
// $amount is in SUPPLIERS'S currency


/****These functions may not be required for purchase requisition as same data as purhcase order can be used.	***
*****So, the purchasing db will be included where necessary to call the purchasing db functions 		***
*****commented out here with req in the names									***
function add_req_gl_trans_supplier($type, $type_no, $date_, $account, $dimension, $dimension2,  
	$amount, $supplier_id, $err_msg="", $rate=0, $memo="")
{
	if ($err_msg == "")
		$err_msg = "The supplier GL transaction could not be inserted";	
		
	return add_gl_trans($type, $type_no, $date_, $account, $dimension, $dimension2, $memo, 
		$amount, get_supplier_currency($supplier_id), 
		PT_SUPPLIER, $supplier_id, $err_msg, $rate);
}

//----------------------------------------------------------------------------------------


function get_req_purchase_price($supplier_id, $stock_id)
{
	$sql = "SELECT price, conversion_factor FROM ".TB_PREF."purch_data 
		WHERE supplier_id = ".db_escape($supplier_id) . " 
		AND stock_id = ".db_escape($stock_id);
	$result = db_query($sql, "The supplier pricing details for " . $stock_id . " could not be retrieved");    

	if (db_num_rows($result) == 1)
	{
		$myrow = db_fetch($result);
		return $myrow["price"] / $myrow['conversion_factor'];
	} 
	else 
	{
		return 0;
	}	
}

function get_req_purchase_conversion_factor($supplier_id, $stock_id)
{
	$sql = "SELECT conversion_factor FROM ".TB_PREF."purch_data 
		WHERE supplier_id = ".db_escape($supplier_id)." 
		AND stock_id = ".db_escape($stock_id);
	$result = db_query($sql, "The supplier pricing details for " . $stock_id . " could not be retrieved");    

	if (db_num_rows($result) == 1)
	{
		$myrow = db_fetch($result);
		return $myrow['conversion_factor'];
	} 
	else 
	{
		return 1;
	}	
}
//----------------------------------------------------------------------------------------

function get_req_purchase_data($supplier_id, $stock_id)
{
	$sql = "SELECT * FROM ".TB_PREF."purch_data 
		WHERE supplier_id = ".db_escape($supplier_id) . "
		AND stock_id = ".db_escape($stock_id);
	$result = db_query($sql, "The supplier pricing details for " . $stock_id . " could not be retrieved");    

	return db_fetch($result);
}

function add_or_update_purchase_req_data($supplier_id, $stock_id, $price, $description="", $uom="")
{
	$data = get_purchase_data($supplier_id, $stock_id);
	if ($data === false)
	{
		$sql = "INSERT INTO ".TB_PREF."purch_data (supplier_id, stock_id, price, suppliers_uom,
			conversion_factor, supplier_description) VALUES (".db_escape($supplier_id)
			.", ".db_escape($stock_id).", ".db_escape($price).", "
			.db_escape($uom).", 1, ".db_escape($description).")";
		db_query($sql,"The supplier purchasing details could not be added");
		return;
	}	
	$price = round($price * $data['conversion_factor'], user_price_dec());
	$sql = "UPDATE ".TB_PREF."purch_data SET price=".db_escape($price);
	if ($uom != "")
		$sql .= ",suppliers_uom=".db_escape($uom);
	if ($description != "")	
		$sql .= ",supplier_description=".db_escape($description);
	$sql .= " WHERE stock_id=".db_escape($stock_id)." AND supplier_id=".db_escape($supplier_id);
	db_query($sql,"The supplier purchasing details could not be updated");
	return true;
}
*******/
?>