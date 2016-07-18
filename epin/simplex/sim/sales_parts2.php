<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SALESKIT';
$path_to_root = "../..";
include_once($path_to_root . "/includes/session.inc");

page(_($help_context = "Sales Parts"));

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/includes/manufacturing.inc");

check_db_has_stock_items(_("There are no items defined in the system."));

simple_page_mode(true);
/*
if (isset($_GET['item_code']))
{
	$_POST['item_code'] = $_GET['item_code'];
	$selected_kit =  $_GET['item_code'];
}
*/
//--------------------------------------------------------------------------------------------------
if (list_updated('category_id') || list_updated('mb_flag')) {
	$Ajax->activate('details');
}
function display_kit_items($selected_kit)
{
	global $table_style;

	$result = get_sales_parts($selected_kit);
div_start('bom');
	start_table("$table_style width=60%");
	$th = array(_("Stock Item"), _("Description"), _("Quantity"), _("Units"),
		'','');
	table_header($th);

	$k = 0;
	while ($myrow = db_fetch($result))
	{

		alt_table_row_color($k);

		label_cell($myrow["stock_id"]);
		label_cell($myrow["comp_name"]);
        qty_cell($myrow["quantity"], false, 
			$myrow["units"] == '' ? 0 : get_qty_dec($myrow["comp_name"]));
        label_cell($myrow["units"] == '' ? _('kit') : $myrow["units"]);
 		edit_button_cell("Edit".$myrow['id'], _("Edit"));
 		delete_button_cell("Delete".$myrow['id'], _("Delete"));
		$_POST['inactive'] = $myrow["inactive"];
        end_row();

	} //END WHILE LIST LOOP
	end_table();
div_end();
}

//--------------------------------------------------------------------------------------------------
function add_sales_part($part_code, $stock_id, $description, $category, $qty, $inactive=0)
{
	$sql = "INSERT INTO ".TB_PREF."sales_part_header
			(id,sales_part_code, stock_id, description, category_id, quantity, inactive) 
			VALUES( sales_part_codes_id_seq.nextval,".db_escape($part_code).",".db_escape($stock_id).",
	  		".db_escape($description).",".db_escape($category)
	  		.",".db_escape($qty).",".db_escape($inactive).")";

	db_query($sql,"the part code could not be added");
}
function get_part_props($part_code)
{
	$sql = "SELECT description, category_id FROM ".TB_PREF."sales_part_header "
		. " WHERE sales_part_code=".db_escape($part_code);
	$res = db_query($sql, "kit name query failed");
	return db_fetch($res);
}
function delete_sales_part($id)
{
	$sql="DELETE FROM ".TB_PREF."sales_part_header WHERE id=".db_escape($id);
	db_query($sql,"a sales part could not be deleted");
}

function update_sales_part($id, $sales_part_code, $stock_id, $description, $category, $qty, $inactive=0)
{
	$sql = "UPDATE ".TB_PREF."sales_part_header SET
	 	sales_part_code = ".db_escape($sales_part_code).",
	 	stock_id = ".db_escape($stock_id).",
	 	description = ".db_escape($description).",
	 	category_id = ".db_escape($category).",
	 	quantity = ".db_escape($qty).",
	 	inactive = ".db_escape($inactive)."
        	WHERE ";
			
	if ($id == -1) // update with unknown $id i.e. from items table editor
	 	$sql .= "sales_part_code = ".db_escape($sales_part_code)
		." AND stock_id = ".db_escape($stock_id);
	else
		$sql .= "id = ".db_escape($id);

	db_query($sql,"the sales part code could not be updated");
}

function get_part_code($id)
{
	$sql="SELECT * FROM ".TB_PREF."sales_part_header WHERE id=".db_escape($id);

	$result = db_query($sql,"part code could not be retrieved");

	return db_fetch($result);
}
function get_sales_parts($part_code)
{
	$sql="SELECT DISTINCT part.*, item.units, comp.description as comp_name 
		FROM "
		.TB_PREF."sales_part_header part,"
		.TB_PREF."sales_part_header comp
		LEFT JOIN "
		.TB_PREF."stock_master item
		ON 
			item.stock_id=comp.sales_part_code
		WHERE
			part.stock_id=comp.sales_part_code
			AND part.sales_part_code=".db_escape($part_code);
	//echo $sql;
	$result = db_query($sql,"sales part  could not be retrieved");

	return $result;
}
function update_component($kit_code, $selected_item)
{
	global $Mode, $Ajax, $selected_kit;
	
	if (!check_num('quantity', 0))
	{
		display_error(_("The quantity entered must be numeric and greater than zero."));
		set_focus('quantity');
		return;
	}
   	elseif ($_POST['description'] == '')
   	{
      	display_error( _("Item code description cannot be empty."));
		set_focus('description');
		return;
   	}
	elseif ($selected_item == -1)	// adding new item or new alias/kit
	{
		if (get_post('part_code') == '') { // New kit/alias definition
			$kit = get_sales_parts($_POST['kit_code']);
    		if (db_num_rows($kit)) {
			  	$input_error = 1;
    	  		display_error( _("This item code is already assigned to stock item or sale part."));
				set_focus('kit_code');
				return;
			}
			if (get_post('kit_code') == '') {
	    	  	display_error( _("Part/alias code cannot be empty."));
				set_focus('kit_code');
				return;
			}
		}
   	}

	if (check_item_in_kit($selected_item, $kit_code, $_POST['component'], true)) {
		display_error(_("The selected component contains directly or on any lower level the part under edition. Recursive parts are not allowed."));
		set_focus('component');
		return;
	}

		/*Now check to see that the component is not already in the kit */
	if (check_item_in_kit($selected_item, $kit_code, $_POST['component'])) {
		display_error(_("The selected component is already in this kit. You can modify it's quantity but it cannot appear more than once in the same part."));
		set_focus('component');
		return;
	}
	if ($selected_item == -1) { // new item alias/kit
		if ($_POST['part_code']=='') {
			$kit_code = $_POST['kit_code'];
			$selected_kit = $_POST['part_code'] = $kit_code;
			$msg = _("New alias code has been created.");
		} 
		 else
			$msg =_("New component has been added to selected kit.");

		add_sales_part( $kit_code, get_post('component'), get_post('description'),
			 get_post('category'), input_num('quantity'), 0);
		display_notification($msg);

	} else {
		$props = get_part_props($_POST['part_code']);
		update_sales_part($selected_item, $kit_code, get_post('component'),
			$props['description'], $props['category_id'], input_num('quantity'), 0);
		display_notification(_("Component of selected kit has been updated."));
	}
	$Mode = 'RESET';
	$Ajax->activate('_page_body');
}

//--------------------------------------------------------------------------------------------------

if (get_post('update_name')) {
	update_kit_props(get_post('part_code'), get_post('description'), get_post('category'));
	display_notification(_('Sales part common properties has been updated'));
	$Ajax->activate('_page_body');
}

if ($Mode=='ADD_ITEM' || $Mode=='UPDATE_ITEM')
	update_component($_POST['part_code'], $selected_id);

if ($Mode == 'Delete')
{
	// Before removing last component from selected kit check 
	// if selected kit is not included in any other kit. 
	// 
	$other_kits = get_where_used($_POST['part_code']);
	$num_kits = db_num_rows($other_kits);

	$kit = get_sales_parts($_POST['part_code']);
	if ((db_num_rows($kit) == 1) && $num_kits) {

		$msg = _("This item cannot be deleted because it is the last item in the kit used by following parts")
			.':<br>';

		while($num_kits--) {
			$kit = db_fetch($other_kits);
			$msg .= "'".$kit[0]."'";
			if ($num_kits) $msg .= ',';
		}
		display_error($msg);
	} else {
		delete_sales_part($selected_id);
		display_notification(_("The component item has been deleted from this bom"));
		$Mode = 'RESET';
	}
}

if ($Mode == 'RESET')
{
	$selected_id = -1;
	unset($_POST['quantity']);
	unset($_POST['component']);
}
//--------------------------------------------------------------------------------------------------

start_form();

echo "<center>" . _("Select a sale part:") . "&nbsp;";
echo sales_parts_list2('part_code', null, _('New Part'), true);
echo "</center><br>";
$props = get_part_props($_POST['part_code']);

if (list_updated('part_code')) {
	if (get_post('part_code') == '')
		$_POST['description'] = '';
	$Ajax->activate('_page_body');
}

$selected_kit = $_POST['part_code'];

div_start('details');
start_outer_table($table_style2, 5);


//----------------------------------------------------------------------------------
if (get_post('part_code') == '') {
// New sales kit entry
	//start_table($table_style2);
//table_section(1);
	table_section(1);
	table_section_title(_("Sales Parts"));
	text_row(_("Alias/Part code:"), 'kit_code', null, 20, 21);
} else
{
	 // Kit selected so display bom or edit component
	$_POST['description'] = $props['description'];
	$_POST['category'] = $props['category_id'];
	start_table($table_style2);
	text_row(_("Description:"), 'description', null, 50, 200);
	stock_categories_list_row(_("Category:"), 'category', null);
	submit_row('update_name', _("Update"), false, 'align=center colspan=2', _('Update kit/alias name'), true);
	end_row();
	end_table(1);
	display_kit_items($selected_kit);
	echo '<br>';
	//start_table($table_style2);
		table_section(1);
	table_section_title(_("Sales Parts"));
}

	if ($Mode == 'Edit') {
		$myrow = get_part_code($selected_id);
		$_POST['component'] = $myrow["stock_id"];
		$_POST['quantity'] = number_format2($myrow["quantity"], get_qty_dec($myrow["stock_id"]));
	}
	hidden("selected_id", $selected_id);
	
	sales_local_items_list_row(_("Component:"),'component', null, false, true);

//	if (get_post('description') == '')
//		$_POST['description'] = get_kit_name($_POST['component']);
	if (get_post('part_code') == '') { // new kit/alias
		if ($Mode!='ADD_ITEM' && $Mode!='UPDATE_ITEM') {
			$_POST['description'] = $props['description'];
			$_POST['category'] = $props['category_id'];
		}
		text_row(_("Description:"), 'description', null, 50, 200);
		stock_categories_list_row(_("Category:"), 'category', null);
	}
	$res = get_item_edit_info(get_post('component'));
	$dec =  $res["decimals"] == '' ? 0 : $res["decimals"];
	$units = $res["units"] == '' ? _('kits') : $res["units"];
	if (list_updated('component')) 
	{
		$_POST['quantity'] = number_format2(1, $dec);
		$Ajax->activate('quantity');
		$Ajax->activate('category');
	}
	
	qty_row(_("Quantity:"), 'quantity', number_format2(1, $dec), '', $units, $dec);

	table_section(2);



table_section_title(_("Other"));

record_status_list_row(_("Item status:"), 'inactive');
end_outer_table(1);
div_end();

	//end_table(1);
	div_start('controls');
	submit_add_or_update_center($selected_id == -1, '', 'both');
	div_end();
	end_form();
//----------------------------------------------------------------------------------

end_page();

?>