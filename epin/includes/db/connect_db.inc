<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
The database functions for MySQL is wrapped in the files /includes/db/connect_db.inc 
and /includes/db/sql_functions.inc (transactions), so there should be no hard-coded mysql-functions.
But we cannot guarantee that all SQL sentenses will work in either PostgreSQL or Oracle. And we do not maintain any support on this topic.

***********************************************************************/

function set_global_connection()
{
	global $db;

   	$db = $_SESSION["wa_current_user"]->get_db_connection();
}

$db_duplicate_error_code = 1062;

//DB wrapper functions to change only once for whole application

function db_query($sql, $err_msg=null)
{
	global $db, $show_sql, $sql_trail, $select_trail, $go_debug, $trans_begin;
	if ($show_sql)
	{
		echo "<font face=arial size=2 color=000099><b>SQL..</b></font>";
		echo "<pre>";
		echo $sql;
		echo "</pre>\n";
	}
	

	//$result = mysql_query($sql, $db);
	//Implement with Oracle Parse and Execute 
	
	$result = oci_parse($db, $sql);
	
	//var_dump ($sql);
	
	//if (!($result)) echo "<br>Error parsing the statement<br>";
	//else echo "<br>parsing successful<br>";
	
	if ($trans_begin)
		$ok = oci_execute($result, OCI_NO_AUTO_COMMIT);
	else
		$ok=oci_execute($result) ;
	
	//if	($ok) echo "<br>execution successful <br>";
    //echo "<br>execution successful ".oci_num_rows($result)."<br>";
	
	$err = oci_error($result);  
	if ($err) 	var_dump ($err['message']." in ".$err['sqltext']) ;  
	  //trigger_error(htmlentities($err['message']), E_USER_ERROR);
	 //{ display_notification ("Error: ".$err['message']." in sql ".$sql); }
	
	if($sql_trail || $err) {
		if ($select_trail || (strstr($sql, 'SELECT') === false || $err)) {
			
			$query = "INSERT INTO ".TB_PREF."sql_trail
				(sql, result, msg, user_, error)
				VALUES(".db_escape($err['sqltext']).",".($result ? 1 : 0).",
				".db_escape($err_msg).",". db_escape($_SESSION["wa_current_user"]->loginname). " , ".db_escape($err['code'].": ".$err['message']).")";
				$t_r = oci_parse($db, $query);
				$ok=oci_execute($t_r) ;				
			//echo 'query ='. $query;	
		}
	}

	if ($err_msg != null || $go_debug) {
		$exit = $err_msg != null;
		if (function_exists('xdebug_call_file'))
			check_db_error('<br>At file '.xdebug_call_file().':'.xdebug_call_line().':<br>'.$err_msg, $sql, $exit);
		else
			check_db_error($err_msg, $sql, $exit);
	}
	return $result;
}

function db_fetch_row ($result)
{

	//return mysql_fetch_row($result);
	
	$result = oci_fetch_row($result) ;
	return array_change_key_case($result, CASE_LOWER);
}
function db_fetch_row_r ($result)
{

	//return mysql_fetch_row($result);
	
	$result = oci_fetch_row($result) ;
	return $result; //array_change_key_case($result, CASE_LOWER);
}

function db_fetch_assoc ($result)
{

	//return mysql_fetch_assoc($result);
	return array_change_key_case(oci_fetch_assoc($result), CASE_LOWER);

}

function db_fetch ($result)
{

//	return mysql_fetch_array($result);

	$result = oci_fetch_array($result);
	return array_change_key_case($result, CASE_LOWER);

}

function db_seek (&$result,$record)
{
	//return mysql_data_seek($result, $record);
	//not used 
	return oci_fetch_row($result) ;	
}

function db_free_result ($result)
{
	if ($result)
		//mysql_free_result($result);
		oci_free_statement($result);
}

function db_num_rows ($result)
{
	//return mysql_num_rows($result);
	$nrows = oci_fetch_all($result, $out); //fetch all rows to count
	$out = '' ; //eraze output
	$ok=oci_execute($result) ; // re-execute the query again to continue
	return $nrows;
}

function db_num_fields ($result)
{
	//return mysql_num_fields($result);
	return oci_num_fields($result);
}

function db_escape($value = "", $nullify = false)
{
	$value = @html_entity_decode($value, ENT_QUOTES, $_SESSION['language']->encoding);
	$value = @htmlspecialchars($value, ENT_QUOTES, $_SESSION['language']->encoding);

  	//reset default if second parameter is skipped
	$nullify = ($nullify === null) ? (false) : ($nullify);

  	//check for null/unset/empty strings
	if ((!isset($value)) || (is_null($value)) || ($value === "")) {
		$value = ($nullify) ? ("NULL") : ("''");
	} else {
		if (is_string($value)) {
      		//value is a string and should be quoted; determine best method based on available extensions
			if (function_exists('mysql_real_escape_string')) {
		  		//$value = "'" . mysql_real_escape_string($value) . "'";
				$value = "'" . ($value) . "'";
			} else {
			  //$value = "'" . mysql_escape_string($value) . "'";
			  $value = "'" . ($value) . "'";
			}
		} else if (!is_numeric($value)) {
			//value is not a string nor numeric
			display_error("ERROR: incorrect data type send to sql query");
			echo '<br><br>';
			exit();
		}
	}
	return $value;
}

function db_error_no ()
{
	global $db;
	//return mysql_errno($db);
	//return error number or code 
	$err = oci_error($db);  
	return $err['code'] ;
}

function db_error_msg($conn)
{
	//return mysql_error($conn);
	//return error message
	$err = oci_error($db);  
	return $err['message'] ;
}

function db_insert_id($seq="999999999")
{
	global $db;
	//return mysql_insert_id($db);
	//No evidence of use, if called just return 99
	if ($seq=='999999999') return 99;
	
	$result = db_query ("select ".$seq.".nextval as order_no from dual" ) ;
	
	$ret = db_fetch($result);
	return $ret[0];
}

function db_num_affected_rows()
{
	global $db;
	//return mysql_affected_rows($db);
	return oci_num_rows($result);
}
function db_parse_proc($sql, $err_msg=null)
{
	global $db, $show_sql, $sql_trail, $select_trail, $go_debug, $trans_begin;
	if ($show_sql)
	{
		echo "<font face=arial size=2 color=000099><b>SQL..</b></font>";
		echo "<pre>";
		echo $sql;
		echo "</pre>\n";
	}

	$result = oci_parse($db, $sql);
	
	$err = oci_error($result);  
	if ($err) 	var_dump ($err['message']." in ".$err['sqltext']) ;  

	return $result;
}
function db_execute_proc($result, $sql, $err_msg=null)
{
global $db, $show_sql, $sql_trail, $select_trail, $go_debug, $trans_begin;
	$result = oci_parse($db, $sql);
	
	//var_dump ($sql);
	
	//if (!($result)) echo "<br>Error parsing the statement<br>";
	//else echo "<br>parsing successful<br>";
	
	if ($trans_begin)
		$ok = oci_execute($result, OCI_NO_AUTO_COMMIT);
	else
		$ok=oci_execute($result) ;
	
	//if	($ok) echo "<br>execution successful <br>";
    //echo "<br>execution successful ".oci_num_rows($result)."<br>";
	
	$err = oci_error($result);  
	if ($err) 	var_dump ($err['message']." in ".$err['sqltext']) ;  
	  //trigger_error(htmlentities($err['message']), E_USER_ERROR);
	 //{ display_notification ("Error: ".$err['message']." in sql ".$sql); }
	
	if($sql_trail || $err) {
		if ($select_trail || (strstr($sql, 'SELECT') === false || $err)) {
			$t_r = oci_parse($db,
			"INSERT INTO ".TB_PREF."sql_trail
				(sql, result, msg, user_, error)
				VALUES(".db_escape($err['sqltext']).",".($result ? 1 : 0).",
				".db_escape($err_msg).", " .$_SESSION["wa_current_user"]->loginname. ", ".db_escape($err['code'].": ".$err['message']).")");
				$ok=oci_execute($t_r) ;
				
		}
	}

	if ($err_msg != null || $go_debug) {
		$exit = $err_msg != null;
		if (function_exists('xdebug_call_file'))
			check_db_error('<br>At file '.xdebug_call_file().':'.xdebug_call_line().':<br>'.$err_msg, $sql, $exit);
		else
			check_db_error($err_msg, $sql, $exit);
	}
	return $result;
}

?>
