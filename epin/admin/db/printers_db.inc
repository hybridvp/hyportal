<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/

function write_printer_def($id, $name, $descr, $queue, $host, $port, $timeout)
{
	if ($id>0)
		$sql = "UPDATE ".TB_PREF."printers SET description=".db_escape($descr)
		.",name=".db_escape($name).",queue=".db_escape($queue)
		.",host=".db_escape($host).",port=".db_escape($port).",timeout=".db_escape($timeout)
		." WHERE id=".db_escape($id);
	else 
		$sql = "INSERT INTO ".TB_PREF."printers (id,"
			."name,description,queue,host,port,timeout) "
			."VALUES (PRINTERS_ID_SEQ.NEXTVAL,".db_escape($name).",".db_escape($descr).","
			.db_escape($queue).",".db_escape($host).",".db_escape($port).",".db_escape($timeout).")";

	return db_query($sql,"could not write printer definition");
}

function get_all_printers() 
{
	$sql = "SELECT * FROM ".TB_PREF."printers";
	return db_query($sql,"could not get printer definitions");
}

function get_printer($id)
{
		$sql = "SELECT * FROM ".TB_PREF."printers
			WHERE id=".db_escape($id);

		$result = db_query($sql,"could not get printer definition");
		return  db_fetch($result);
}

//============================================================================
// printer profiles functions
//
function update_printer_profile($name, $dest)
{
//			$sql = "REPLACE INTO ".TB_PREF."print_profiles "
	foreach( $dest as $rep => $printer) {
		if ($printer != '' || $rep == '') {
			$sql = "INSERT INTO ".TB_PREF."print_profiles "
			."(id, profile, report, printer) VALUES (print_profiles_id_seq.nextval,"
			.db_escape($name).","
			.db_escape($rep).","
			.db_escape($printer).")";
		} else {
			$sql = "DELETE FROM ".TB_PREF."print_profiles WHERE ("
				."report=" . db_escape($rep)
				." AND profile=".db_escape($name).")";
		}
		$result = db_query($sql,"could not update printing profile");
		if(!$result) {
			return false;
		}
	}
	return true;
}
//
//	Get destination for report defined in given printing profile.
//
function get_report_printer($profile, $report)
{
	$sql = "SELECT printer FROM ".TB_PREF."print_profiles WHERE "
		."profile=".db_escape($profile)." AND report=";

	$result = db_query($sql.db_escape($report), 'report printer lookup failed');

	if (!$result) return false;
	$ret = db_fetch($result);
	if ($ret === false) {
		$result = db_query($sql."''", 'default report printer lookup failed');
		if (!$result) return false;

		$ret = db_fetch($result);
		if (!$ret) return false;
	}
	return get_printer($ret['printer']);
}

function delete_printer_profile($name)
{
	$sql="DELETE FROM ".TB_PREF."print_profiles WHERE profile=".db_escape($name);
	return db_query($sql,"could not delete printing profile");
}
//
// Get all report destinations for given profile.
//
function get_print_profile($name)
{
	$sql = "SELECT	* FROM ".TB_PREF."print_profiles WHERE profile=".db_escape($name);
	return db_query($sql,"could not get printing profile");
}

?>