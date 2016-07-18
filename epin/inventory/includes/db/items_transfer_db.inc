<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
//-------------------------------------------------------------------------------------------------------------

function add_stock_transfer($Items, $location_from, $location_to, $date_, $type, $reference, $memo_)
{
	global $Refs;

	begin_transaction();

	$transfer_id = get_next_trans_no(ST_LOCTRANSFER);

	foreach ($Items as $line_item)
	{
		add_stock_transfer_item($transfer_id, $line_item->stock_id, $location_from,
			$location_to, $date_, $type, $reference, $line_item->quantity);
	}

	add_comments(ST_LOCTRANSFER, $transfer_id, $date_, $memo_);
	
	//Laolu commented out - save not done in this function
	//$Refs->save(ST_LOCTRANSFER, $transfer_id, $reference);
	//add_audit_trail(ST_LOCTRANSFER, $transfer_id, $date_);

	commit_transaction();

	return $transfer_id;
}

//-------------------------------------------------------------------------------------------------------------

// add 2 stock_moves entries for a stock transfer
// $date_ is display date (not sql)
// std_cost is in HOME currency
// it seems the standard_cost field is not used at all
//laolu added serialized,order_no
function add_stock_transfer_item($transfer_id, $stock_id, $location_from, $location_to,
	$date_, $type, $reference, $quantity, $order_no=0, $serialized=0)
{
	add_stock_move(ST_LOCTRANSFER, $stock_id, $transfer_id, $location_from,
       	$date_, $reference, -$quantity, 0, $type, 1, 0, 0, "", $order_no, 0);

	add_stock_move(ST_LOCTRANSFER, $stock_id, $transfer_id, $location_to,
	   	$date_, $reference, $quantity, 0, $type,  1, 0, 0, "", $order_no, $serialized);

}

//-------------------------------------------------------------------------------------------------------------

function get_stock_transfer($trans_no)
{
	$result = get_stock_transfer_items($trans_no);
	if (db_num_rows($result) < 2)
	{
		display_db_error("transfer with less than 2 items : $trans_no", "");
	}

	// this function is very bad that it assumes that 1st record and 2nd record contain the
	// from and to locations - if get_stock_moves uses a different ordering than trans_no then
	// it will bomb
	$move1 = db_fetch($result);
	$move2 = db_fetch($result);

	// return an array of (From, To)
	if ($move1['qty'] < 0)
		return array($move1, $move2);
	else
		return array($move2, $move1);
}

//-------------------------------------------------------------------------------------------------------------

function get_stock_transfer_items($trans_no)
{
	$result = get_stock_moves(ST_LOCTRANSFER, $trans_no);

	if (db_num_rows($result) == 0)
	{
		return null;
	}

	return $result;
}

//-------------------------------------------------------------------------------------------------------------

function void_stock_transfer($type_no)
{
	void_stock_move(ST_LOCTRANSFER, $type_no);
}
//-------------------------------------------------------------------------------------------------------------
//Laolu Added
function add_stock_transfer_srl($all_serials, $stock_id, $location_from, $location_to, $date_, $qty)
{
	global $Refs;

	begin_transaction();

	//$transfer_id = get_next_trans_no(ST_LOCTRANSFER);

	foreach ($all_serials as $serial)
	{
		add_stock_transfer_item_srl($stock_id, $location_from,
			$location_to, $date_, $serial, $qty,0);
	}

	//add_comments(ST_LOCTRANSFER, $transfer_id, $date_, $memo_);

	//$Refs->save(ST_LOCTRANSFER, $transfer_id, $reference);
	//add_audit_trail(ST_LOCTRANSFER, $transfer_id, $date_);

	commit_transaction();

	//return $transfer_id;
}
//--------------------------------------------------------------------------------------------------------------
function add_stock_transfer_item_srl($stock_id, $location_from, $location_to,
	$date_, $serial_no, $quantity, $order_no=0)
{
	add_stock_move_serials(ST_LOCTRANSFER, $stock_id, $serial_no, $location_from,
       	 -$quantity, $date_, $unit, '', $order_no);

	add_stock_move_serials(ST_LOCTRANSFER, $stock_id, $serial_no, $location_to,
	   	$quantity, $date_, $unit,'AVAILABLE', $order_no);

}
?>