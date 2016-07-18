<?php
/**********************************************************************
	Copyright (C) Simplex.
***********************************************************************/

function add_nonfin_audit_trail($cod_key1,$cod_key1_value,$cod_key2,$cod_key2_value,$trans_type,$mnt_action,$term_id, $txn_descr='')
{

    $id = db_insert_id("audit_trail_id_seq") ;
	$sql = "INSERT INTO ".TB_PREF."audit_trail_nonfin"
		. " (id,cod_key1,cod_key1_value,cod_key2,cod_key2_value,cod_user_id,cod_trans_type,
txt_txn_desc,cod_mnt_action,cod_term_id,dat_txn)
			VALUES(".db_escape($id)	.", "
			.	$cod_key1	.", "
			.db_escape($cod_key1_value)	.", "
			.	$cod_key2	.", "
			.db_escape($cod_key2_value)	.", "
			. db_escape(strtoupper($_SESSION["wa_current_user"]->loginname)). ","
			.db_escape($trans_type)	.", "
			.db_escape($txn_descr)	.", "
			.db_escape($mnt_action) .","
			. db_escape($term_id) .",sysdate)";


	db_query($sql, "Cannot add nonfin audit info");
	
	// all audit records beside latest one should have gl_seq set to NULL
	// to avoid need for subqueries (not existing in MySQL 3) all over the code
	///$sql = "UPDATE ".TB_PREF."audit_trail SET gl_seq = NULL"
	//	. " WHERE type=".db_escape($trans_type)." AND trans_no="
	//	.db_escape($trans_no)." AND id!=".$id;

	//db_query($sql, "Cannot update audit gl_seq");
}

function get_nonfin_audit_trail_all()
{
	$sql = "SELECT * FROM ".TB_PREF."audit_trail_nonfin" ;
//		." WHERE type=".db_escape($trans_type)." AND trans_no="
	//	.db_escape($trans_no);

	return db_query($sql, "Cannot get all audit info for transaction");
}

function get_audit_trail_last_($trans_type, $trans_no)
{
	$sql = "SELECT * FROM ".TB_PREF."audit_trail"
		." WHERE type=".db_escape($trans_type).
			" AND trans_no=".db_escape($trans_no)." AND NOT ISNULL(gl_seq)";

	$res = db_query($sql, "Cannot get last audit info for transaction");
	if ($res)
		$row = db_fetch($res);

	return $row;
}

/*
	Confirm and close for edition all transactions up to date $todate, 
	and reindex	journal.
*/
function close_transactions_($todate) {
	$errors = 0;
	$sql = "SELECT DISTINCT a.id, a.gl_date, a.fiscal_year"
		." FROM ".TB_PREF."gl_trans gl"
		." LEFT JOIN ". TB_PREF."audit_trail a ON 
			(gl.type=a.type AND gl.type_no=a.trans_no)"
		. " WHERE gl_date<='". date2sql($todate) ."'"
		. " AND NOT ISNULL(gl_seq)"
		. " ORDER BY a.fiscal_year, a.gl_date, a.id";

	$result = db_query($sql, "Cannot select transactions for closing");

	if (db_num_rows($result)) {
		$last_year = 0;

		while ($row = db_fetch($result)) {
			if ($row['fiscal_year'] == null) {
				$errors = 1; continue;
			}
			if ($last_year != $row['fiscal_year']) {
				$last_year = $row['fiscal_year'];
				$counter = 1; // reset counter on fiscal year change
			} else
				$counter++;
			$sql2 = "UPDATE ".TB_PREF."audit_trail SET"
				. " gl_seq=$counter"
				. " WHERE id=".$row['id'];
											
			db_query($sql2, "Cannot reindex journal");
		}
	}
	
	if ($errors) 
		display_warning(_("Some transactions journal GL postings were not indexed due to lack of audit trail record."));
}

/*
	Reopen all transactions for edition up from date $fromdate
*/
function open_transactions_($fromdate) {

	$sql = "SELECT a.id, a.gl_date, a.fiscal_year"
		." FROM ".TB_PREF."gl_trans gl"
		." LEFT JOIN ". TB_PREF."audit_trail a ON 
			(gl.type=a.type AND gl.type_no=a.trans_no)"
		. " WHERE gl_date>='". date2sql($fromdate) ."'"
		. " AND !ISNULL(gl_seq)"
		. " ORDER BY a.fiscal_year, a.gl_date, a.id";

	$result = db_query($sql, "Cannot select transactions for openning");

	if (db_num_rows($result)) {
		$last_year = 0;

		while ($row = db_fetch($result)) {
			if ($row['fiscal_year'] == null) {
				continue;
			}
			$sql2 = "UPDATE ".TB_PREF."audit_trail SET"
				. " gl_seq=0"
				. " WHERE id=".$row['id'];
											
			db_query($sql2, "Cannot clear journal order");
		}
	}
}
/*
	Closed transactions have gl_seq number assigned.
*/
function is_closed_trans_($type, $trans_no) {
	$sql = "SELECT	gl_seq  FROM ".TB_PREF."audit_trail"
		. " WHERE type=".db_escape($type)
		." AND trans_no=".db_escape($trans_no)
		." AND gl_seq>0";

	$res = db_query($sql, "Cannot check transaction");

	return db_num_rows($res);
}


function add_sales_tran_approval($order_no, $request_by, $approved_by, $type)
{

    $id = db_insert_id("sales_tran_approval_id_seq") ;
	$sql = "INSERT INTO ".TB_PREF."sales_tran_approval"
		. " (id,order_no,requested_by,approved_by,approval_date, trans_type)
			VALUES(".db_escape($id)	.", "
			.	$order_no	.", "
			.db_escape($request_by)	.", " . db_escape($approved_by)	. " ,null, " . $type . ")";


	db_query($sql, "Cannot add sales_tran_approval");
	//db_query($sql, "Cannot update audit gl_seq");
}
function approve_sales_tran_approval($order_no, $approver, $type)
{

	$sql = "UPDATE ".TB_PREF."sales_tran_approval set flg_approved='Y' , approved_by="
		
			.db_escape($approver)	.", approval_date = sysdate where order_no=" . $order_no	. " and trans_type= " . $type ;

	db_query($sql, "Cannot add sales_tran_approval");
	//db_query($sql, "Cannot update audit gl_seq");
}
function dispatch_sales_tran_approval($order_no, $request_by, $approved_by, $type)
{

    $id = db_insert_id("sales_tran_approval_id_seq") ;
	$sql = "INSERT INTO ".TB_PREF."sales_tran_approval"
		. " (id,order_no,requested_by,approved_by,approval_date, trans_type)
			VALUES(".db_escape($id)	.", "
			.	$order_no	.", "
			.db_escape($request_by)	.", " . db_escape($approved_by)	. " ,null, " . $type . ")";


	db_query($sql, "Cannot complete dispatch_sales_tran_approval");
	//db_query($sql, "Cannot update audit gl_seq");
}
function cancel_sales_tran_approval($order_no, $approver, $type)
{

	$sql = "UPDATE ".TB_PREF."sales_tran_approval set flg_approved='C' , approved_by="
		
			.db_escape($approver)	.", approval_date = sysdate where order_no=" . $order_no	. " and trans_type= " . $type ;

	db_query($sql, "Cannot add sales_tran_approval");
	//db_query($sql, "Cannot update audit gl_seq");
}
?>
