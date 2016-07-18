<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
include_once($path_to_root . "/simplex/includes/db/simplexreferences_db.inc");
//include_once( "/../simplex/includes/db/simplexreferences_db.inc");


//include_once( "c:/faccount/www/account/simplex/includes/db/simplexreferences_db.inc");


//include_once( "./db/simplexreferences_db.inc");


class simple_references 
{
	
	function save($type, $id, $reference) 
	{
		add_simple_reference($type, $id, $reference);
		if ($reference != 'auto')
			$this->save_last($reference, $type);
	}
	
	function get($type, $id) 
	{
		return get_simple_reference($type, $id);
	}	
	
	function delete($type, $id) 
	{
		delete_simple_reference($type, $id);
	}	
	
	function update($type, $id, $reference) 
	{
	    update_simple_reference($type, $id, $reference);
		if ($reference != 'auto')
			$this->save_last($reference, $type);
	}
	
	function exists($type, $reference) 
	{
		return (find_simple_reference($type, $reference) != null);
	}
	
	function save_last($reference, $type) 
	{
		$next = $this->increment($reference);
		save_next_simple_reference($type, $next);
	}
	
	function get_next_simple_ref($type) 
	{
		return get_next_simple_reference($type);
	}
	
	//------------------------------------

	function is_valid($reference) 
	{
		return strlen(trim($reference)) > 0;
	}
	
	function increment($reference) 
	{
		// New method done by Pete. So f.i. WA036 will increment to WA037 and so on.
       	// If $reference contains at least one group of digits,
        // extract first didgits group and add 1, then put all together.
        // NB. preg_match returns 1 if the regex matches completely 
        // also $result[0] holds entire string, 1 the first captured, 2 the 2nd etc.
        //
        if (preg_match('/^(\D*?)(\d+)(.*)/', $reference, $result) == 1) 
        {
			list($all, $prefix, $number, $postfix) = $result;
			$dig_count = strlen($number); // How many digits? eg. 0003 = 4
			$fmt = '%0' . $dig_count . 'd'; // Make a format string - leading zeroes
			$nextval =  sprintf($fmt, intval($number + 1)); // Add one on, and put prefix back on

			return $prefix.$nextval.$postfix;
        }
        else 
            return $reference;
	}
	
	//------------------------------------
}

//----------------------------------------------------------------------------

function is_new($ref, $type)
{
	$db_info = get_systype_db_info($type);
	$db_name = $db_info[0];
	$db_type = $db_info[1];
	$db_ref = $db_info[3];
	
	if ($db_ref != null) 
	{
		$sql = "SELECT $db_ref FROM $db_name WHERE $db_ref='$ref'";
		if ($db_type != null)
			$sql .= " AND $db_type=$type";
			 
		$result = db_query($sql, "could not test for unique reference");
		
		return (db_num_rows($result) == 0);
	}
	
	// it's a type that doesn't use references - shouldn't be calling here, but say yes anyways
	return true;
}

//create the new class $OurRefs as well
$OurRefs = new simple_references();

?>