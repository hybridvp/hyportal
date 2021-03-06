<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
function add_gl_account($account_code, $account_name, $account_type, $account_code2)
{
	$sql = "INSERT INTO ".TB_PREF."chart_master (account_code, account_code2, account_name, account_type)
		VALUES (".db_escape($account_code).", ".db_escape($account_code2).", "
			.db_escape($account_name).", ".db_escape($account_type).")";

	return db_query($sql);
}

function update_gl_account($account_code, $account_name, $account_type, $account_code2)
{
    $sql = "UPDATE ".TB_PREF."chart_master SET account_name=".db_escape($account_name)
    .",account_type=".db_escape($account_type).", account_code2=".db_escape($account_code2)
		." WHERE account_code = ".db_escape($account_code);

	return db_query($sql);
}

function delete_gl_account($code)
{
	$sql = "DELETE FROM ".TB_PREF."chart_master WHERE account_code=".db_escape($code);

	db_query($sql, "could not delete gl account");
}

function get_gl_accounts($from=null, $to=null)
{
	$sql = "SELECT ".TB_PREF."chart_master.*,".TB_PREF."chart_types.name AS AccountTypeName
		FROM ".TB_PREF."chart_master,".TB_PREF."chart_types
		WHERE ".TB_PREF."chart_master.account_type=".TB_PREF."chart_types.id";
	if ($from != null)
		$sql .= " AND ".TB_PREF."chart_master.account_code >= ".db_escape($from);
	if ($to != null)
		$sql .= " AND ".TB_PREF."chart_master.account_code <= ".db_escape($to);
	$sql .= " ORDER BY account_code";

	return db_query($sql, "could not get gl accounts");
}

function get_gl_accounts_all($balance=-1)
{
	global $use_new_account_types;
	
	if ($balance == 1)
		$where ="WHERE ctype>0 AND ctype<".CL_INCOME;
	elseif ($balance == 0)	
		$where ="WHERE ctype>".CL_EQUITY." OR ctype=0"; // backwards compatibility
 	$sql = "SELECT ".TB_PREF."chart_master.account_code, ".TB_PREF."chart_master.account_name, ".TB_PREF."chart_master.account_code2,
 		".TB_PREF."chart_types.name AS AccountTypeName,".TB_PREF."chart_types.id AS AccountType,
	 	".TB_PREF."chart_types.parent, ".TB_PREF."chart_class.class_name AS AccountClassName, ".TB_PREF."chart_class.cid AS ClassID, 
	 	".TB_PREF."chart_class.ctype AS ClassType
	 	FROM ".TB_PREF."chart_types INNER JOIN ".TB_PREF."chart_class ON ".TB_PREF."chart_types.class_id=".TB_PREF."chart_class.cid
	 	LEFT JOIN ".TB_PREF."chart_master ON ".TB_PREF."chart_master.account_type=".TB_PREF."chart_types.id ";
	if ($balance != -1)
		$sql .= $where; 	 	 		
	if (isset($use_new_account_types) && $use_new_account_types == 1)
	 	$sql .= " ORDER BY ".TB_PREF."chart_class.cid, ".TB_PREF."chart_types.id, parent, ".TB_PREF."chart_master.account_code";
	else
	 	$sql .= " ORDER BY ".TB_PREF."chart_class.cid, case when parent > 0 then parent else ".TB_PREF."chart_types.id end, 
	 		case when parent > 0 then ".TB_PREF."chart_types.id else  parent end, ".TB_PREF."chart_master.account_code";

echo $sql;
	//return db_query($sql, "could not get gl accounts");
	return $sql;
}

function get_gl_account($code)
{
	$sql = "SELECT * FROM ".TB_PREF."chart_master WHERE account_code=".db_escape($code);

	$result = db_query($sql, "could not get gl account");
	return db_fetch($result);
}

function is_account_balancesheet($code)
{
	$sql = "SELECT ".TB_PREF."chart_class.ctype FROM ".TB_PREF."chart_class, "
		.TB_PREF."chart_types, ".TB_PREF."chart_master
		WHERE ".TB_PREF."chart_master.account_type=".TB_PREF."chart_types.id AND
		".TB_PREF."chart_types.class_id=".TB_PREF."chart_class.cid
		AND ".TB_PREF."chart_master.account_code=".db_escape($code);

	$result = db_query($sql,"could not retreive the account class for $code");
	$row = db_fetch_row($result);
	return $row[0] > 0 && $row[0] < CL_INCOME;
}

function get_gl_account_name($code)
{
	$sql = "SELECT account_name from ".TB_PREF."chart_master WHERE account_code=".db_escape($code);

	$result = db_query($sql,"could not retreive the account name for $code");

	if (db_num_rows($result) == 1)
	{
		$row = db_fetch_row($result);
		return $row[0];
	}

	display_db_error("could not retreive the account name for $code", $sql, true);
}


?>