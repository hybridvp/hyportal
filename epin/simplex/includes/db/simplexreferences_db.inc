<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
//--------------------------------------------------------------------------------------------------

function get_simple_reference($type, $id)
{
	$sql = "SELECT * FROM ".TB_PREF."ourrefs WHERE type=".db_escape($type)." AND id=".db_escape($id);

	$result = db_query($sql, "could not query reference table");
    $row = db_fetch($result);
    return $row['reference'];
}

//--------------------------------------------------------------------------------------------------

function add_simple_reference($type, $id, $reference)
{
	$sql = "INSERT INTO ".TB_PREF."ourrefs (type, id, reference)
		VALUES (".db_escape($type).", ".db_escape($id).", "
			. db_escape(trim($reference)) . ")";

	db_query($sql, "could not add reference entry");
}

//--------------------------------------------------------------------------------------------------
 
function update_simple_reference($type, $id, $reference)
{
    $sql = "UPDATE ".TB_PREF."ourrefs SET reference=".db_escape($reference)
			." WHERE type=".db_escape($type)." AND id=".db_escape($id);
    db_query($sql, "could not update reference entry");
}

//--------------------------------------------------------------------------------------------------

function delete_simple_reference($type, $id)
{
	$sql = "DELETE FROM ".TB_PREF."ourrefs WHERE type=$type AND id=".db_escape($id);

	return db_query($sql, "could not delete from reference table");
}

//--------------------------------------------------------------------------------------------------

function find_simple_reference($type, $reference)
{
	$sql = "SELECT id FROM ".TB_PREF."ourrefs WHERE type=".db_escape($type)
		." AND reference=".db_escape($reference);

	$result = db_query($sql, "could not query reference table");

    return (db_num_rows($result) > 0);
}

//--------------------------------------------------------------------------------------------------

function save_next_simple_reference($type, $reference)
{
    $sql = "UPDATE ".TB_PREF."oursys_types SET next_reference=" . db_escape(trim($reference)) 
		. " WHERE type_id = ".db_escape($type);

	db_query($sql, "The next transaction ref for $type could not be updated");
}

//--------------------------------------------------------------------------------------------------

function get_next_simple_reference($type)
{
    $sql = "SELECT next_reference FROM ".TB_PREF."oursys_types WHERE type_id = ".db_escape($type);

    $result = db_query($sql,"The last transaction ref for $type could not be retreived");

    $row = db_fetch_row($result);
    return $row[0];
}

function ora_date($dat)
{
    return "to_date('$dat','yyyy-mm-dd')";
}

function get_jobctl_prefs($processname,$tbpref = TB_PREF)
{
	$sql = "SELECT * FROM ".TB_PREF."job_control WHERE process_name=".db_escape($processname) ;
	//echo $sql;
	$result = db_query($sql, "The job control preferences could not be retrieved");
	
	/*if (db_num_rows($result) == 0)
		display_db_error("FATAL : Could not find job control prefs", $sql);
	*/
	return db_fetch($result);
}
function get_jobs()
{
	$sql = "SELECT * FROM ".TB_PREF."job_control " ;
	//echo $sql;
	return db_query($sql,"The job control table could not be retrieved");
	//$result = db_query($sql, "The job control table could not be retrieved");
	
	/*if (db_num_rows($result) == 0)
		display_db_error("FATAL : Could not find job control prefs", $sql);
	*/
	//return db_fetch($result);
}
function update_jobctl_setup($process_name, $run_flag)
{
	$sql = "UPDATE ".TB_PREF."job_control SET run_flag=".db_escape($run_flag)."
		WHERE upper(process_name)=". db_escape($process_name) ;

	db_query($sql, "The job cotrol setup could not be updated ");
}

function check_dealer_gpg($debtor_no)
{
    $sql = "SELECT flg_gpg_added FROM ".TB_PREF."debtors_master WHERE debtor_no = ".db_escape($debtor_no);

    $result = db_query($sql,"The pgp_filename for  $debtor_no could not be retreived");

    $row = db_fetch_row($result);
    //return $row[0];
	if( $row[0] =='Y' )
		return true;
	else 
		return false;
	
}
//----------------------------------------------------------------------------------------------------------
function get_all_mailing_lists()
{
	$sql = "SELECT * FROM ".TB_PREF."mailing_list";

	return db_query($sql, "could not get all mailing_list");
}
//--------------------------------------------------------------------------------------------------

function delete_mailing_list($id)
{
	$sql = "DELETE FROM ".TB_PREF."mailing_list WHERE id=$id";

	db_query($sql, "could not delete mailing_list");
}
//--------------------------------------------------------------------------------------------------
function get_mailing_list($cod_list)
{
	$sql = "SELECT * FROM ".TB_PREF."mailing_list WHERE cod_list= " . db_escape($cod_list);

	db_query($sql, "could not fetch mailing_list");
}
function add_mailing_list($cod_list, $desc, $address)
{
	$sql = "INSERT INTO ".TB_PREF."mailing_list (id, cod_list,description,txt_address)
		VALUES (MAILING_LIST_ID_SEQ.NEXTVAL,"
		.db_escape($cod_list) . ", "
		.db_escape($desc).", "
		.db_escape($address). " )";

	db_query($sql, "could not add MAILING_LIST");
}

function update_mailing_list($cod_list, $desc, $email_address)
{
	$sql = "UPDATE ".TB_PREF."mailing_list SET description=".db_escape($desc).",
		email_address=".db_escape($email_address). "		
		WHERE cod_list=".db_escape($cod_list);

	db_query($sql, "could not update mailing list");
}
?>