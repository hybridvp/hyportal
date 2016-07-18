<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
function add_account_type($id, $name, $class_id, $parent)
{
	$sql = "INSERT INTO ".TB_PREF."chart_types (id, name, class_id, parent)
		VALUES ($id, ".db_escape($name).", ".db_escape($class_id).", ".db_escape($parent).")";

	return db_query($sql);
}

function update_account_type($id, $name, $class_id, $parent)
{
    $sql = "UPDATE ".TB_PREF."chart_types SET name=".db_escape($name).",
		class_id=".db_escape($class_id).", parent=".db_escape($parent)
		." WHERE id = ".db_escape($id);

	return db_query($sql, "could not update account type");
}

function get_account_types($all=false)
{
	$sql = "SELECT * FROM ".TB_PREF."chart_types";

	if (!$all) $sql .= " WHERE inactive=0";
	$sql .= " ORDER BY class_id, id";

	return db_query($sql, "could not get account types");
}

function get_account_type($id)
{
	$sql = "SELECT * FROM ".TB_PREF."chart_types WHERE id = ".db_escape($id);

	$result = db_query($sql, "could not get account type");

	return db_fetch($result);
}

function get_account_type_name($id)
{
	$sql = "SELECT name FROM ".TB_PREF."chart_types WHERE id = ".db_escape($id);

	$result = db_query($sql, "could not get account type");

	$row = db_fetch_row($result);
	return $row[0];
}

function delete_account_type($id)
{
	$sql = "DELETE FROM ".TB_PREF."chart_types WHERE id = ".db_escape($id);

	db_query($sql, "could not delete account type");
}

function add_account_class($id, $name, $ctype)
{
	$sql = "INSERT INTO ".TB_PREF."chart_class (cid, class_name, ctype)
		VALUES (".db_escape($id).", ".db_escape($name).", ".db_escape($ctype).")";

	return db_query($sql);
}

function update_account_class($id, $name, $ctype)
{
    $sql = "UPDATE ".TB_PREF."chart_class SET class_name=".db_escape($name).",
		ctype=".db_escape($ctype)." WHERE cid = ".db_escape($id);

	return db_query($sql);
}

function get_account_classes($all=false)
{
	$sql = "SELECT * FROM ".TB_PREF."chart_class";
	if (!$all) $sql .= " WHERE inactive=0";
	$sql .= " ORDER BY cid";

	return db_query($sql, "could not get account types");
}

function get_account_class($id)
{
	$sql = "SELECT * FROM ".TB_PREF."chart_class WHERE cid = ".db_escape($id);

	$result = db_query($sql, "could not get account type");

	return db_fetch($result);
}

function get_account_class_name($id)
{
	$sql = "SELECT class_name FROM ".TB_PREF."chart_class WHERE cid =".db_escape($id);

	$result = db_query($sql, "could not get account type");

	$row = db_fetch_row($result);
	return $row[0];
}

function delete_account_class($id)
{
	$sql = "DELETE FROM ".TB_PREF."chart_class WHERE cid = ".db_escape($id);

	db_query($sql, "could not delete account type");
}

?>