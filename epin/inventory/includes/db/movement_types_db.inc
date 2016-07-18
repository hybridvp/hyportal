<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
function add_movement_type($name)
{
	$sql = "INSERT INTO ".TB_PREF."movement_types (id,name)
		VALUES (MOVEMENT_TYPES_ID_SEQ.NEXTVAL,".db_escape($name).")";

	db_query($sql, "could not add item movement type");
}

function update_movement_type($type_id, $name)
{
	$sql = "UPDATE ".TB_PREF."movement_types SET name=".db_escape($name)."
			WHERE id=".db_escape($type_id);

	db_query($sql, "could not update item movement type");
}

function get_all_movement_type($all=false)
{
	$sql = "SELECT * FROM ".TB_PREF."movement_types";
	if (!$all) $sql .= " WHERE inactive=0";

	return db_query($sql, "could not get all item movement type");
}

function get_movement_type($type_id)
{
	$sql = "SELECT * FROM ".TB_PREF."movement_types WHERE id=".db_escape($type_id);

	$result = db_query($sql, "could not get item movement type");

	return db_fetch($result);
}

function delete_movement_type($type_id)
{
	$sql="DELETE FROM ".TB_PREF."movement_types WHERE id=".db_escape($type_id);

	db_query($sql, "could not delete item movement type");
}

?>