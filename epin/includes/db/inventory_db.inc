<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/



function get_qoh_on_date($stock_id, $location=null, $date_=null, $exclude=0)
{
	if ($date_ == null)
		$date_ = Today();

	$date = date2sql($date_);
//select load_start_seq_no- sales_start_seq_no
	$sql = "select qty_in_stock FROM "
	.TB_PREF."denom_sequence_control a,"
	.TB_PREF."stock_master b 
		WHERE 1=1 
		AND a.denomination = b.facevalue 
		AND b.stock_id=".db_escape($stock_id);
	
	$result = db_query($sql, "QOH calulcation failed");

	$myrow = db_fetch_row($result);
	
	return $myrow[0];
}
function get_qoh_on_date_bad($stock_id, $location=null, $date_=null, $exclude=0)
{
	if ($date_ == null)
		$date_ = Today();

	$date = date2sql($date_);

	$sql = "SELECT count(pin) FROM ".TB_PREF."pin_details
		WHERE stock_id=".db_escape($stock_id);
		//."
		//AND to_date(startdate,'yyyymmdd') >= to_date('$date','yyyy-mm-dd')"
		//." AND to_date(enddate,'yyyymmdd') <=to_date('$date','yyyy-mm-dd')";  ///Laolu added

	//if ($location != null)
		$sql .= " AND flg_mnt_status= 'A' AND status = 'N' and location=".db_escape($location);
		
	//echo $sql;
	$result = db_query($sql, "QOH calulcation failed");

	$myrow = db_fetch_row($result);
	if ($exclude > 0)
	{
		$sql = "SELECT count(pin) FROM ".TB_PREF."pin_details
			WHERE stock_id=".db_escape($stock_id)
			//." AND type=".db_escape($exclude)
			
			." AND to_date(startdate,'yyyymmdd') <= to_date('$date','yyyy-mm-dd')"
			." AND to_date(enddate,'yyyymmdd') >=to_date('$date','yyyy-mm-dd')";  ///Laolu added
			$sql .= " AND status = 'N'";

		$result = db_query($sql, "QOH calulcation failed");
		$myrow2 = db_fetch_row($result);
		if ($myrow2 !== false)
			$myrow[0] -= $myrow2[0];
	}
	//get sold pins 
	//$sql_sold = "SELECT sum( quantity ) FROM ".TB_PREF."pin_mailer_jobs
	//	WHERE stock_id=".db_escape($stock_id);
	
	$sql_sold = "SELECT SUM( end_serial - start_serial + 1) as quantity 
				FROM ".TB_PREF."pin_mailer_jobs_detail b, 
		             ".TB_PREF."pin_mailer_jobs a
		        WHERE 1=1 
				AND a.order_no = b.order_no
				AND a.line_no =b.line_no
				AND b.status !='R' 
				AND a.stock_id=".db_escape($stock_id);
		
	$result2 = db_query($sql_sold, "Select from pin_mailer_jobs failed");
	$myrow3 = db_fetch_row($result2);

	$pin_count  = $myrow[0] - $myrow3[0] ;
	if ($pin_count < 0)
		return 0;
	else
		return $pin_count;
		
	//return $myrow[0] - $myrow3[0] ;
}
function get_qoh_on_date_($stock_id, $location=null, $date_=null, $exclude=0)
{
	if ($date_ == null)
		$date_ = Today();

	$date = date2sql($date_);

	$sql = "SELECT SUM(qty) FROM ".TB_PREF."stock_moves
		WHERE stock_id=".db_escape($stock_id)."
		AND tran_date <= '$date'"
		." AND serialized =1";  ///Laolu added

	if ($location != null)
		$sql .= " AND loc_code = ".db_escape($location);

	$result = db_query($sql, "QOH calulcation failed");

	$myrow = db_fetch_row($result);
	if ($exclude > 0)
	{
		$sql = "SELECT SUM(qty) FROM ".TB_PREF."stock_moves
			WHERE stock_id=".db_escape($stock_id)
			." AND type=".db_escape($exclude)
			
			." AND tran_date = '$date'"
			." AND serialized =1";  ///Laolu added

		$result = db_query($sql, "QOH calulcation failed");
		$myrow2 = db_fetch_row($result);
		if ($myrow2 !== false)
			$myrow[0] -= $myrow2[0];
	}

	return $myrow[0];
}

//--------------------------------------------------------------------------------------

function get_item_edit_info($stock_id)
{
	$sql = "SELECT material_cost + labour_cost + overhead_cost AS standard_cost, units, decimals
		FROM ".TB_PREF."stock_master,".TB_PREF."item_units
		WHERE stock_id=".db_escape($stock_id)
		." AND ".TB_PREF."stock_master.units=".TB_PREF."item_units.abbr";
	$result = db_query($sql, "The standard cost cannot be retrieved");

	return db_fetch($result);
}

//--------------------------------------------------------------------------------------

function get_standard_cost($stock_id)
{
	$sql = "SELECT material_cost + labour_cost + overhead_cost AS std_cost
		FROM ".TB_PREF."stock_master WHERE stock_id=".db_escape($stock_id);
	$result = db_query($sql, "The standard cost cannot be retrieved");

	$myrow = db_fetch_row($result);

	return $myrow[0];
}

//--------------------------------------------------------------------------------------

function is_inventory_item($stock_id)
{
	$sql = "SELECT stock_id FROM ".TB_PREF."stock_master
		WHERE stock_id=".db_escape($stock_id)." AND mb_flag <> 'D'";
	$result = db_query($sql, "Cannot query is inventory item or not");

	return db_num_rows($result) > 0;
}

//-------------------------------------------------------------------

Function get_stock_gl_code($stock_id)
{
	/*Gets the GL Codes relevant to the item account  */

	$sql = "SELECT inventory_account, cogs_account,
		adjustment_account, sales_account, assembly_account, dimension_id, dimension2_id FROM
		".TB_PREF."stock_master WHERE stock_id = ".db_escape($stock_id);

	$get = db_query($sql,"retreive stock gl code");
	return db_fetch($get);
}

//--------------------------------------------------------------------------------------

// $date_ - display / non-sql date
// $std_cost - in HOME currency
// $show_or_hide - wil this move be visible in reports, etc
// $price - in $person_id's currency

function add_stock_move($type, $stock_id, $trans_no, $location,
    $date_, $reference, $quantity, $std_cost, $person_id=0, $show_or_hide=1,
    $price=0, $discount_percent=0, $error_msg="", $order_no=0, $serialized=0) //Laolu added order number,serialized
{
	// do not add a stock move if it's a non-inventory item
	if (!is_inventory_item($stock_id))
		return null;

	$date = date2sql($date_);
//Laolu changed sql
 if ($type == 3) //Transit
 {
 	    $id = db_insert_id("STOCK_MOVES_TRANSIT_ID_SEQ");
	$sql = "INSERT INTO ".TB_PREF."stock_moves_transit (trans_id, stock_id, trans_no, type, loc_code,
		tran_date, person_id, reference, qty, standard_cost, visible, price,
		discount_percent, order_no, serialized) VALUES (".db_escape($id).", ".db_escape($stock_id)
		.", ".db_escape($trans_no).", ".db_escape($type)
		.",	".db_escape($location).", '$date', "
		.db_escape($person_id).", ".db_escape($reference).", "
		.db_escape($quantity).", ".db_escape($std_cost).","
		.db_escape($show_or_hide).", ".db_escape($price).", "
		.db_escape($discount_percent) .", "
		.db_escape($order_no) . ", "
		.db_escape($serialized) .")";
 }
 else
 { 
    $id = db_insert_id("stock_moves_id_seq");
	$sql = "INSERT INTO ".TB_PREF."stock_moves (trans_id, stock_id, trans_no, type, loc_code,
		tran_date, person_id, reference, qty, standard_cost, visible, price,
		discount_percent, order_no, serialized) VALUES (".db_escape($id).", ".db_escape($stock_id)
		.", ".db_escape($trans_no).", ".db_escape($type)
		.",	".db_escape($location).", '$date', "
		.db_escape($person_id).", ".db_escape($reference).", "
		.db_escape($quantity).", ".db_escape($std_cost).","
		.db_escape($show_or_hide).", ".db_escape($price).", "
		.db_escape($discount_percent) .", "
		.db_escape($order_no) . ", "
		.db_escape($serialized) .")";
	}
	db_query($sql, $error_msg);
	if ($error_msg == "")
		$error_msg = "The stock movement record cannot be inserted";


	//return db_insert_id;
	return  $id;
}

function update_stock_move_pid($type, $stock_id, $from, $to, $pid, $cost)
{
	$from = date2sql($from);
	$to = date2sql($to);
	 if ($type == 3) //Transit
 	{
			$sql = "UPDATE ".TB_PREF."stock_moves_transit SET standard_cost=".db_escape($cost)
			." WHERE type=".db_escape($type)
			."	AND stock_id=".db_escape($stock_id)
			."  AND tran_date>='$from' AND tran_date<='$to' 
			AND person_id = ".db_escape($pid);
	}
	else
	{
		$sql = "UPDATE ".TB_PREF."stock_moves SET standard_cost=".db_escape($cost)
			." WHERE type=".db_escape($type)
			."	AND stock_id=".db_escape($stock_id)
			."  AND tran_date>='$from' AND tran_date<='$to' 
			AND person_id = ".db_escape($pid);
	}
	db_query($sql, "The stock movement standard_cost cannot be updated");
}

//--------------------------------------------------------------------------------------------------

function get_stock_moves($type, $type_no, $visible=false)
{
	 if ($type == 3) //Transit
 	{
			$sql = "SELECT ".TB_PREF."stock_moves_transit.*, ".TB_PREF."stock_master.description, "
		.TB_PREF."stock_master.units,".TB_PREF."locations.location_name,"
		.TB_PREF."stock_master.material_cost + "
			.TB_PREF."stock_master.labour_cost + "
			.TB_PREF."stock_master.overhead_cost AS FixedStandardCost
		FROM ".TB_PREF."stock_moves,".TB_PREF."locations,".TB_PREF."stock_master
		WHERE ".TB_PREF."stock_moves.stock_id = ".TB_PREF."stock_master.stock_id
		AND ".TB_PREF."locations.loc_code=".TB_PREF."stock_moves.loc_code
		AND type=".db_escape($type)." AND trans_no=".db_escape($type_no)." ORDER BY trans_id";
	if ($visible)
		$sql .= " AND ".TB_PREF."stock_moves.visible=1";
	}
	else
	{
	$sql = "SELECT ".TB_PREF."stock_moves.*, ".TB_PREF."stock_master.description, "
		.TB_PREF."stock_master.units,".TB_PREF."locations.location_name,"
		.TB_PREF."stock_master.material_cost + "
			.TB_PREF."stock_master.labour_cost + "
			.TB_PREF."stock_master.overhead_cost AS FixedStandardCost
		FROM ".TB_PREF."stock_moves,".TB_PREF."locations,".TB_PREF."stock_master
		WHERE ".TB_PREF."stock_moves.stock_id = ".TB_PREF."stock_master.stock_id
		AND ".TB_PREF."locations.loc_code=".TB_PREF."stock_moves.loc_code
		AND type=".db_escape($type)." AND trans_no=".db_escape($type_no)." ORDER BY trans_id";
	if ($visible)
		$sql .= " AND ".TB_PREF."stock_moves.visible=1";
	}

	return db_query($sql, "Could not get stock moves");
}

//--------------------------------------------------------------------------------------------------

function void_stock_move($type, $type_no)
{
	$sql = "UPDATE ".TB_PREF."stock_moves SET qty=0, price=0, discount_percent=0,
		standard_cost=0	WHERE type=".db_escape($type)." AND trans_no=".db_escape($type_no);

	db_query($sql, "Could not void stock moves");
}

//--------------------------------------------------------------------------------------------------

function get_location_name($loc_code)
{
	$sql = "SELECT location_name FROM ".TB_PREF."locations WHERE loc_code="
		.db_escape($loc_code);

	$result = db_query($sql, "could not retreive the location name for $loc_code");

	if (db_num_rows($result) == 1)
	{
		$row = db_fetch_row($result);
		return $row[0];
	}

	display_db_error("could not retreive the location name for $loc_code", $sql, true);
}

//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
function add_stock_move_serials($type, $stock_id, $serial, $location,
    $quantity, $date_,  $unit, $status, $order_no=0) 
{
	// do not add a stock move if it's a non-inventory item
	if (!is_inventory_item($stock_id))
		return null;
    $id = db_insert_id("serialized_stocks_id_seq");
	$date = date2sql($date_);
	$sql = "INSERT INTO ".TB_PREF."serialized_stock (id,transtype, stock_id, location_code,
		qty, batch_no, box_no, brick_no, card_no, order_no, tran_date, status,sales_order_no) VALUES (".db_escape($id).", ".db_escape($type)
		.", ".db_escape($stock_id).", ".db_escape($location)
		.",	".db_escape($quantity).", '', ";
		
		if ( $unit == 'box_no') {
			$sql .= db_escape($serial).",'','',"
		.db_escape($order_no) . ", "
		.db_escape($date_) . ", "
		.db_escape($status) . ", "
		.db_escape($serialized) .", 0)";
		}
		elseif ($unit = 'brick_no') {
			$sql .= "'',". db_escape($serial).",'',"
		.db_escape($order_no) . ", "
		.db_escape($date_) . ", "
		.db_escape($status) . ", "
		.db_escape($serialized) .", 0)";
		}
		//
		elseif ($unit = 'card_no')	 {
			$sql .= "'','',". db_escape($serial).","
		.db_escape($order_no) . ", "
		.db_escape($date_) . ", "
		.db_escape($status) . ", "
		.db_escape($serialized) .", 0)";
		}
		

	if ($error_msg == "")
		$error_msg = "The serialized stock movement record cannot be inserted";

	db_query($sql, $error_msg);

	return $id;
}


?>