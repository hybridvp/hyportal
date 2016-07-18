<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/

function add_item_location($loc_code, $location_name, $delivery_address, $phone, $phone2, $fax, $email, $contact,$location_type)//Laolu added $location_type,$activate
{
	$sql = "INSERT INTO ".TB_PREF."locations (loc_code, location_name, delivery_address, phone, phone2, fax, email, contact,location_type, activate_on_arrival)
		VALUES (".db_escape($loc_code).", ".db_escape($location_name).", ".db_escape($delivery_address).", "
			.db_escape($phone).", ".db_escape($phone2).", ".db_escape($fax).", nvl(".db_escape($email).",' '), "
			.db_escape($contact). ", ".db_escape($location_type) . ", ".db_escape($activate). ")" ;

	db_query($sql,"a location could not be added");

	/* Also need to add loc_stock records for all existing items */
	$sql = "INSERT INTO ".TB_PREF."loc_stock (loc_code, stock_id, reorder_level)
		SELECT ".db_escape($loc_code).", ".TB_PREF."stock_master.stock_id, 0 FROM ".TB_PREF."stock_master";

	db_query($sql,"a location could not be added");
}

//------------------------------------------------------------------------------------

function update_item_location($loc_code, $location_name, $delivery_address, $phone, $phone2, $fax, $email, 
$contact,$location_type, $activate)

{
    $sql = "UPDATE ".TB_PREF."locations SET location_name=".db_escape($location_name).",
    	delivery_address=".db_escape($delivery_address).",
    	phone=".db_escape($phone).", phone2=".db_escape($phone2).", fax=".db_escape($fax).",
    	email=".db_escape($email).", contact=".db_escape($contact).", 
		location_type=" . db_escape($location_type). " , activate_on_arrival=".db_escape($activate)." 
    	WHERE loc_code = ".db_escape($loc_code);

	db_query($sql,"a location could not be updated");
}


//------------------------------------------------------------------------------------

function delete_item_location($item_location)
{
	$sql="DELETE FROM ".TB_PREF."locations WHERE loc_code=".db_escape($item_location);
	db_query($sql,"a location could not be deleted");

	$sql = "DELETE FROM ".TB_PREF."loc_stock WHERE loc_code =".db_escape($item_location);
	db_query($sql,"a location could not be deleted");
}

//------------------------------------------------------------------------------------

function get_item_location($item_location)
{
	$sql="SELECT * FROM ".TB_PREF."locations WHERE loc_code=".db_escape($item_location);

	$result = db_query($sql,"a location could not be retrieved");

	return db_fetch($result);
}

//------------------------------------------------------------------------------------

function set_reorder_level($stock_id, $loc_code, $reorder_level)
{
	$sql = "UPDATE ".TB_PREF."loc_stock SET reorder_level = $reorder_level
		WHERE stock_id = ".db_escape($stock_id)." AND loc_code = ".db_escape($loc_code);

	db_query($sql,"an item reorder could not be set");
}

//------------------------------------------------------------------------------------

function get_loc_details($stock_id)
{
	$sql = "SELECT ".TB_PREF."loc_stock.*, ".TB_PREF."locations.location_name
		FROM ".TB_PREF."loc_stock, ".TB_PREF."locations
		WHERE ".TB_PREF."loc_stock.loc_code=".TB_PREF."locations.loc_code
		AND ".TB_PREF."loc_stock.stock_id = ".db_escape($stock_id) 
		. " AND ".TB_PREF."locations.inactive = 0"
		." ORDER BY ".TB_PREF."loc_stock.loc_code";
	return db_query($sql,"an item reorder could not be retreived");
}

//------------------------------------------------------------------------------------

?>