<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
function update_item($stock_id, $description, $long_description, $category_id, 
	$tax_type_id, $units='', $mb_flag='', $sales_account, $inventory_account, 
	$cogs_account, 	$adjustment_account, $assembly_account, $dimension_id, 
	$dimension2_id, $no_sale, $serialize, $keepas, $facevalue) //Laolu added
{
	$sql = "UPDATE ".TB_PREF."stock_master SET long_description=".db_escape($long_description).",
		description=".db_escape($description).",
		category_id=".db_escape($category_id).",
		sales_account=".db_escape($sales_account).",
		inventory_account=".db_escape($inventory_account).",
		cogs_account=".db_escape($cogs_account).",
		adjustment_account=".db_escape($adjustment_account).",
		assembly_account=".db_escape($assembly_account).",
		dimension_id=".db_escape($dimension_id).",
		dimension2_id=".db_escape($dimension2_id).",
		tax_type_id=".db_escape($tax_type_id).",
		no_sale=".db_escape($no_sale).",
		keep_inactive=".db_escape($keepas).",
		facevalue=".$facevalue.",
		serializable=".db_escape($serialize); //Laolu added

	if ($units != '')
		$sql .= ", units='$units'";

	if ($mb_flag != '')
		$sql .= ", mb_flag='$mb_flag'";

	$sql .= " WHERE stock_id=".db_escape($stock_id);

	db_query($sql, "The item could not be updated");

	update_item_code(-1, $stock_id, $stock_id, $description, $category_id, 1, 0);
}

function add_item($stock_id, $description, $long_description, $category_id, 
	$tax_type_id, $units, $mb_flag,	$sales_account, $inventory_account, 
	$cogs_account, $adjustment_account,	$assembly_account, $dimension_id, 
	$dimension2_id, $no_sale, $serialize, $keepas, $facevalue) //Laolu added serialize
{
	$sql = "INSERT INTO ".TB_PREF."stock_master (stock_id, description, long_description, category_id,
		tax_type_id, units, mb_flag, sales_account, inventory_account, cogs_account,
		adjustment_account, assembly_account, dimension_id, dimension2_id, no_sale, serializable, keep_inactive, facevalue, unitconversion)
		VALUES (".db_escape($stock_id).", ".db_escape($description).", ".db_escape($long_description).",
		".db_escape($category_id).", ".db_escape($tax_type_id).", "
		.db_escape($units).", ".db_escape($mb_flag).",
		".db_escape($sales_account).", ".db_escape($inventory_account)
		.", ".db_escape($cogs_account).",".db_escape($adjustment_account)
		.", ".db_escape($assembly_account).", "
		.db_escape($dimension_id).", ".db_escape($dimension2_id).","
		.db_escape($no_sale).", ".db_escape($serialize). ", ".db_escape($keepas). " ," . $facevalue. ",' ')";

	db_query($sql, "The item could not be added");

	$sql = "INSERT INTO ".TB_PREF."loc_stock (loc_code, stock_id)
		SELECT ".TB_PREF."locations.loc_code, ".db_escape($stock_id)
		." FROM ".TB_PREF."locations";

	db_query($sql, "The item locstock could not be added");
	
	$sql = "INSERT INTO ".TB_PREF."DENOM_SEQUENCE_CONTROL (denomination,load_start_seq_no,sales_start_seq_no)
		VALUES (". $facevalue . ",1,1)";		

	db_query($sql, "The facevalue sequence control could not be added");

	add_item_code($stock_id, $stock_id, $description, $category_id, 1, 0);
}

function delete_item($stock_id)
{
	$sql="DELETE FROM ".TB_PREF."stock_master WHERE stock_id=".db_escape($stock_id);
	db_query($sql, "could not delete stock item");

	/*and cascade deletes in loc_stock */
	$sql ="DELETE FROM ".TB_PREF."loc_stock WHERE stock_id=".db_escape($stock_id);
	db_query($sql, "could not delete stock item loc stock");

	/*and cascade deletes in purch_data */
	$sql ="DELETE FROM ".TB_PREF."purch_data WHERE stock_id=".db_escape($stock_id);
	db_query($sql, "could not delete stock item purch data");

	/*and cascade deletes in prices */
	$sql ="DELETE FROM ".TB_PREF."prices WHERE stock_id=".db_escape($stock_id);
	db_query($sql, "could not delete stock item prices");

	/*and cascade delete the bill of material if any */
	$sql = "DELETE FROM ".TB_PREF."bom WHERE parent=".db_escape($stock_id);
	db_query($sql, "could not delete stock item bom");

	/*and cascade deletes in denom sequence control */
	$sql ="DELETE FROM ".TB_PREF."DENOM_SEQUENCE_CONTROL WHERE denomination= (SELECT facevalue FROM ".TB_PREF."stock_master where stock_id=" .db_escape($stock_id). ")";
	db_query($sql, "could not delete from denom sequence control");
	
	delete_item_kit($stock_id);
}

function get_item($stock_id)
{
	$sql = "SELECT ".TB_PREF."stock_master.*,".TB_PREF."item_tax_types.name AS tax_type_name
		FROM ".TB_PREF."stock_master,".TB_PREF."item_tax_types
		WHERE ".TB_PREF."item_tax_types.id=".TB_PREF."stock_master.tax_type_id
		AND stock_id=".db_escape($stock_id);
	$result = db_query($sql,"an item could not be retreived");

	return db_fetch($result);
}

function get_items()
{
	$sql = "SELECT * FROM ".TB_PREF."stock_master";
	return db_query($sql,"items could not be retreived");
}
//2009-11-09 Added Olapegba femi
function get_all_item_units_conv() {
    $sql = "SELECT * FROM ".TB_PREF."item_units_conversion ORDER BY name";
    return  db_query($sql, "could not get stock conversions");
}

function get_item_unit_conv($unit)
{
	$sql="SELECT * FROM ".TB_PREF."item_units_conversion WHERE name='$unit'";

	$result = db_query($sql,"a unit of measure could not be retrieved");

	return db_fetch($result);
}
function item_unit_conv_used($unit) {
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."stock_master WHERE unitconversion='$unit'";
	$result = db_query($sql, "could not query stock master");
	$myrow = db_fetch_row($result);
	return ($myrow[0] > 0);
}
function get_items_reoders($location)
{
	$sql = "SELECT a.stock_id,b.reorder_level FROM ".TB_PREF."stock_master a,"
			.TB_PREF."loc_stock b WHERE a.stock_id = b.stock_id AND b.loc_code = ". db_escape($location);
	return db_query($sql,"items could not be retreived");
}


?>