<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
function add_acct_ana_type($id, $name, $code, $parent)
{
	$sql = "INSERT INTO ".TB_PREF."analysis_codes (id,name, code, parent, created_by,created_date,last_modified_by,last_modified_date)
		VALUES (ANALYSIS_CODES_ID_SEQ.NEXTVAL,".db_escape($name).", ".db_escape($code).", ".db_escape($parent). "," .db_escape($_SESSION['wa_current_user']->loginname) . ",sysdate,' ',sysdate)";

	return db_query($sql);
}

function update_acct_ana_type($id, $name, $code, $parent)
{
    $sql = "UPDATE ".TB_PREF."analysis_codes SET name=".db_escape($name).",
		code=".db_escape($code).", parent=".db_escape($parent)
		." WHERE id = ".db_escape($id);

	return db_query($sql, "could not update account type");
}

function get_acct_ana_types($all=false)
{
	$sql = "SELECT * FROM ".TB_PREF."analysis_codes";

	if (!$all) $sql .= " WHERE inactive=0";
	$sql .= " ORDER BY code, id";

	return db_query($sql, "could not get account analysis types");
}

function get_acct_ana_type($id)
{
	$sql = "SELECT * FROM ".TB_PREF."analysis_codes WHERE id = ".db_escape($id);

	$result = db_query($sql, "could not get account type");

	return db_fetch($result);
}

function get_acct_ana_type_name($id)
{
	$sql = "SELECT name FROM ".TB_PREF."analysis_codes WHERE id = ".db_escape($id);

	$result = db_query($sql, "could not get account type");

	$row = db_fetch_row($result);
	return $row[0];
}

function delete_acct_ana_type($id)
{
	$sql = "DELETE FROM ".TB_PREF."analysis_codes WHERE id = ".db_escape($id);

	db_query($sql, "could not delete account type");
}


?>