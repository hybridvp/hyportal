<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
//--------------------------------------------------------------------------------------

function add_work_order_issue($woid, $ref, $to_work_order, $items, $location, $workcentre,
	$date_, $memo_)
{
	global $Refs;

	begin_transaction();

	$details = get_work_order($woid);

    if (strlen($details[0]) == 0)
    {
    	echo _("The order number sent is not valid.");
    	cancel_transaction();
    	exit;
    }

	if (work_order_is_closed($woid))
	{
		display_error("UNEXPECTED : Issuing items for a closed Work Order");
		cancel_transaction();
		exit;
	}

     $number = db_insert_id("wo_issues_no_seq");
	// insert the actual issue
	$sql = "INSERT INTO ".TB_PREF."wo_issues (issue_no, workorder_id, reference, issue_date, loc_code, workcentre_id) 
			VALUES (".db_escape($number).", ".db_escape($ref).", ".db_escape($ref).", '" .
		date2sql($date_) . "', ".db_escape($location).", ".db_escape($workcentre).")";
	db_query($sql,"The work order issue could not be added");

	//$number = db_insert_id;
    $item_seq = 0 ;
	foreach ($items as $item)
	{

		if ($to_work_order)
			$item->quantity = -$item->quantity;

		// insert a -ve stock move for each item
		$item_seq++ ;
		add_stock_move(ST_MANUISSUE, $item->stock_id, $number,
			$location, $date_, $memo_, -$item->quantity, 0);

		$sql = "INSERT INTO ".TB_PREF."wo_issue_items (id, issue_id, stock_id, qty_issued)
			VALUES (".db_escape($item_seq).", ".db_escape($number).", ".db_escape($item->stock_id).", "
			.db_escape($item->quantity).")";
		db_query($sql,"A work order issue item could not be added");
	}

	if ($memo_)
		add_comments(ST_MANUISSUE, $number, $date_, $memo_);

	$Refs->save(ST_MANUISSUE, $number, $ref);
	add_audit_trail(ST_MANUISSUE, $number, $date_);

	commit_transaction();
}

//--------------------------------------------------------------------------------------

function get_work_order_issues($woid)
{
	$sql = "SELECT * FROM ".TB_PREF."wo_issues WHERE workorder_id=".db_escape($woid)
	." ORDER BY issue_no";
    return db_query($sql, "The work order issues could not be retrieved");
}

function get_additional_issues($woid)
{
	$sql = "SELECT ".TB_PREF."wo_issues.*, ".TB_PREF."wo_issue_items.*
		FROM ".TB_PREF."wo_issues, ".TB_PREF."wo_issue_items
		WHERE ".TB_PREF."wo_issues.issue_no=".TB_PREF."wo_issue_items.issue_id
		AND ".TB_PREF."wo_issues.workorder_id=".db_escape($woid)
		." ORDER BY ".TB_PREF."wo_issue_items.id";
    return db_query($sql, "The work order issues could not be retrieved");
}
//--------------------------------------------------------------------------------------

function get_work_order_issue($issue_no)
{
	$sql = "SELECT DISTINCT ".TB_PREF."wo_issues.*, ".TB_PREF."workorders.stock_id,
		".TB_PREF."stock_master.description, ".TB_PREF."locations.location_name, "
		.TB_PREF."workcentres.name AS WorkCentreName
		FROM ".TB_PREF."wo_issues, ".TB_PREF."workorders, ".TB_PREF."stock_master, "
		.TB_PREF."locations, ".TB_PREF."workcentres
		WHERE issue_no=".db_escape($issue_no)."
		AND ".TB_PREF."workorders.id = ".TB_PREF."wo_issues.workorder_id
		AND ".TB_PREF."locations.loc_code = ".TB_PREF."wo_issues.loc_code
		AND ".TB_PREF."workcentres.id = ".TB_PREF."wo_issues.workcentre_id
		AND ".TB_PREF."stock_master.stock_id = ".TB_PREF."workorders.stock_id";
    $result = db_query($sql, "A work order issue could not be retrieved");

    return db_fetch($result);
}

//--------------------------------------------------------------------------------------

function get_work_order_issue_details($issue_no)
{
	$sql = "SELECT ".TB_PREF."wo_issue_items.*,"
	.TB_PREF."stock_master.description, ".TB_PREF."stock_master.units
		FROM ".TB_PREF."wo_issue_items, ".TB_PREF."stock_master
		WHERE issue_id=".db_escape($issue_no)."
		AND ".TB_PREF."stock_master.stock_id=".TB_PREF."wo_issue_items.stock_id
		ORDER BY ".TB_PREF."wo_issue_items.id";
    return db_query($sql, "The work order issue items could not be retrieved");
}

//--------------------------------------------------------------------------------------

function exists_work_order_issue($issue_no)
{
	$sql = "SELECT issue_no FROM ".TB_PREF."wo_issues WHERE issue_no=".db_escape($issue_no);
	$result = db_query($sql, "Cannot retreive a wo issue");

    return (db_num_rows($result) > 0);
}

//--------------------------------------------------------------------------------------

function void_work_order_issue($type_no)
{
	begin_transaction();

	// void the actual issue items and their quantities
	$sql = "UPDATE ".TB_PREF."wo_issue_items Set qty_issued = 0 WHERE issue_id="
		.db_escape($type_no);
	db_query($sql,"A work order issue item could not be voided");

	// void all related stock moves
	void_stock_move(ST_MANUISSUE, $type_no);

	// void any related gl trans
	void_gl_trans(ST_MANUISSUE, $type_no, true);

	commit_transaction();
}


//--------------------------------------------------------------------------------------

?>