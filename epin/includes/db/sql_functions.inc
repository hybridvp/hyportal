<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
	The database functions for MySQL is wrapped in the files /includes/db/connect_db.inc 
and /includes/db/sql_functions.inc (transactions), so there should be no hard-coded mysql-functions.
But we cannot guarantee that all SQL sentenses will work in either PostgreSQL or Oracle. And we do not maintain any support on this topic.
***********************************************************************/
//
//	General database functions common for all modules.
//
//-------------------------------------------------------------------	
function begin_transaction()
{
	global $trans_begins ;
	//db_query("BEGIN", "could not start a transaction");
	//Not used in Oracle, used global variable to set the value and execute later as appropriate
	$trans_begins= true ;
}

function commit_transaction()
{
    global $db , $trans_begins;
	
	//db_query("COMMIT", "could not commit a transaction");
	oci_commit($db);
	$trans_begins= false ;
	
}

function cancel_transaction()
{
	//db_query("ROLLBACK", "could not cancel a transaction");	
	oci_rollback($db);
	$trans_begins= false ;

}
//-----------------------------------------------------------------------------
//	Update record activity status.
//
function update_record_status($id, $status, $table, $key) {
	$sql = "UPDATE ".TB_PREF.$table." SET inactive = nvl("
		. db_escape($status).",0) WHERE $key=".db_escape($id);
		
  	db_query($sql, "Can't update record status");
}
?>
