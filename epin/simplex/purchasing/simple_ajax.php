<?php
/**********************************************************************
    Copyright (C) SIMPLEX
    @author laolu olapegba
***********************************************************************/
$page_security = 'SA_GRN';
$path_to_root = "../..";
include_once($path_to_root . "/purchasing/includes/po_class.inc");

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_db.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/simplex/purchasing/includes/ui/ui_funcs.php");
include_once($path_to_root . "/simplex/includes/ui/ui_lists.php");

include_once($path_to_root . "/simplex/includes/ui/our_ui_lists.inc");

//include_once($path_to_root . "/includes/ui/ui_lists.inc");

function mysales_items_list($name, $selected_id=null, $all_option=false, 
	$submit_on_change=false, $type='', $opts=array())
{
	global $all_items;
	// all sales codes
	$sql = "SELECT i.item_code, i.description, c.description, decode(sign(count(1)-1), -1, 0, 1) as kit,
			 i.inactive
			FROM
			".TB_PREF."stock_master s,
			".TB_PREF."item_codes i
			LEFT JOIN
			".TB_PREF."stock_category c
			ON i.category_id=c.category_id
			WHERE i.stock_id=s.stock_id";

	
	if ($type == 'local')	{ // exclude foreign codes
		$sql .=	" AND i.is_foreign <>1"; 
	} elseif ($type == 'kits') { // sales kits
		$sql .=	" AND i.is_foreign <>1 AND i.item_code!=i.stock_id";
	}
	$sql .= " AND i.inactive=0 AND s.inactive=0 AND s.no_sale=0";
	$sql .= " GROUP BY i.item_code, i.description, c.description,i.inactive ";

	return combo_input($name, $selected_id, $sql, 'i.item_code', 'c.description',
	array_merge(
	  array(
		'format' => '_format_stock_items',
		'spec_option' => $all_option===true ?  _("All Items") : $all_option,
		'spec_id' => $all_items,
		'search_box' => true,
		'search' => array("i.item_code", "c.description", "i.description"),
		'search_submit' => get_company_pref('no_item_list')!=0,
		'size'=>15,
		'select_submit'=> $submit_on_change,
		'category' => 2,
		'order' => array('c.description','i.item_code')
	  ), $opts) );
}

function mysales_items_list_cells($label, $name, $selected_id=null, $all_option=false, $submit_on_change=false)
{
	if ($label != null)
		echo "<td>$label</td>\n";
	echo mysales_items_list($name, $selected_id, $all_option, $submit_on_change,
		'', array('cells'=>true));
}


$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Serialize Received Items"), false, false, "", $js);

start_form();

echo " here again ";

stock_items_list_cells(_("Item:"), 'SelectStockFromList', null, true);


echo '<br>';

customer_list_cells(_("Customer:"), 'SelectCustomerFromList', null, true);

//customer_list_row(_("Customer:"), 'SelectCustomerFromList', null, true);

end_form();

//--------------------------------------------------------------------------------------------------

end_page();
?>

