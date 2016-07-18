<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
//--------------------------------------------------------------------------------------------------

function get_security_role($id)
{
	$sql = "SELECT * FROM ".TB_PREF."security_roles WHERE id='$id'";
	//echo $sql;
	$ret = db_query($sql, "could not retrieve security roles");
	$row = db_fetch($ret);
	if ($row != false) {
		$row['areas'] = explode(';', $row['areas']);
		$row['sections'] = explode(';', $row['sections']);
	}
	return $row;
}

//--------------------------------------------------------------------------------------------------

function add_security_role($name, $description, $sections, $areas)
{
	$sql = "INSERT INTO ".TB_PREF."security_roles (id,role, description, sections, areas)
	VALUES (SECURITY_ROLES_ID_SEQ.NEXTVAL,"
	.db_escape($name).","
	.db_escape($description).","
	.db_escape(implode(';', $sections)).","
	.db_escape(implode(';', $areas)).")";

	db_query($sql, "could not add new security role");
}

//--------------------------------------------------------------------------------------------------

function update_security_role($id, $name, $description, $sections, $areas)
{
    	$sql = "UPDATE ".TB_PREF."security_roles SET role=".db_escape($name)
    	.",description=".db_escape($description)
    	.",sections=".db_escape(implode(';', $sections))
    	.",areas=".db_escape(implode(';', $areas))
    	." WHERE id=$id";
    	db_query($sql, "could not update role");
}
//--------------------------------------------------------------------------------------------------

function delete_security_role($id)
{
	$sql = "DELETE FROM ".TB_PREF."security_roles WHERE id=$id";

	db_query($sql, "could not delete role");
}
//--------------------------------------------------------------------------------------------------

function check_role_used($id) {
	$sql = "SELECT count(*) FROM ".TB_PREF."users WHERE role_id=$id";
	$ret = db_query($sql, 'cannot check role usage');
	$row = db_fetch($ret);
	return $row[0];
}
//----------------------------------------------------------------------------------------------------------
function get_all_sales_approvals()
{
	$sql = "SELECT * FROM ".TB_PREF."sales_approval";

	return db_query($sql, "could not get all sales_approval");
}
//--------------------------------------------------------------------------------------------------

function delete_sales_approval($id)
{
	$sql = "DELETE FROM ".TB_PREF."sales_approval WHERE id=$id";

	db_query($sql, "could not delete role");
}
//--------------------------------------------------------------------------------------------------
function get_sales_approval($id)
{
	$sql = "SELECT * FROM ".TB_PREF."sales_approval WHERE id=$id";

	db_query($sql, "could not fetch sales approval");
}
function add_sales_approval($name, $maker, $checker, $type, $maillist)
{
	$sql = "INSERT INTO ".TB_PREF."sales_approval (id,sales_type, sales_role, approving_role, description,cod_maker_id, dat_last_mnt, mail_list_code)
		VALUES (SALES_APPROVAL_ID_SEQ.NEXTVAL,"
		.db_escape($type).", "
		.db_escape($maker).", "
		.db_escape($checker).", "
		.db_escape($name).","
		.db_escape($_SESSION["wa_current_user"]->loginname) . " ,sysdate," . $maillist ." )";

	db_query($sql, "could not add tax type");
}

function update_sales_approval($id, $name, $maker, $checker, $type, $maillist)
{
	$sql = "UPDATE ".TB_PREF."sales_approval SET description=".db_escape($name).",
		sales_role=".db_escape($maker).",
		approving_role=".db_escape($checker).",
		sales_type=$type " . ",
		mail_list_code=$maillist " . "
		
		WHERE id=".db_escape($id);

	db_query($sql, "could not update sales approval");
}

?>