<?php
/**********************************************
Author: 
***********************************************/
$page_security = 'SA_SUPPTRANSVIEW' ; //'SA_CSVIMPORT';
$path_to_root="../..";

include($path_to_root . "/includes/session.inc");
add_access_extensions();

include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/inventory/includes/inventory_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_codes_db.inc");
include_once($path_to_root . "/dimensions/includes/dimensions_db.inc");
 
function check_stock_id($stock_id) {
    $sql = "SELECT * FROM ".TB_PREF."stock_master where stock_id = $stock_id";
    $result = db_query($sql, "Can not look up stock_id");
    $row = db_fetch_row_r($result);
    if (!$row[0]) return 0;
    return 1;
}
function check_supplier_id($vendor_id) {
    $sql = "SELECT * FROM ".TB_PREF."suppliers where supplier_id = $vendor_id";
    $result = db_query($sql, "Can not look up vendor id");
    $row = db_fetch_row_r($result);
    if (!$row[0]) return 0;
    return 1;
}
function check_location_id($location_id) {
    $sql = "SELECT * FROM ".TB_PREF."locations where loc_code = $location_id";
    $result = db_query($sql, "Can not look up Location id");
    $row = db_fetch_row_r($result);
    if (!$row[0]) return 0; 
    return 1;
}
function get_supplier_id($supplier) {
    $sql = "SELECT supplier_id FROM ".TB_PREF."suppliers where supp_name = $supplier";
    $result = db_query($sql, "Can not look up supplier");
    $row = db_fetch_row($result);
    if (!$row[0]) return 0;
    return $row[0];
}
function check_po_number($ponumber) {
    $sql = "SELECT * FROM ".TB_PREF."purch_orders where order_no = $ponumber";
    $result = db_query($sql, "Can not look up ponumber");
    $row = db_fetch_row_r($result);
    if (!$row[0]) return 0;
    return 1;
}

$action = 'import';
if (isset($_GET['action'])) $action = $_GET['action'];
if (isset($_POST['action'])) $action = $_POST['action'];

page("Import of CSV formatted Items");

if (isset($_POST['import'])) {
	if (isset($_FILES['imp']) && $_FILES['imp']['name'] != '') {
		$filename = $_FILES['imp']['tmp_name'];
		$sep = $_POST['sep']; 

		$fp = @fopen($filename, "r");
		if (!$fp)
			die("can not open file $filename");

		$lines = $b = 0;
				$prev_ponumber = 0;
				$prev_itemcode = '';
				$success = false;
		// type, item_code, stock_id, description, category, units, qty, mb_flag, currency, price
		while ($data = fgetcsv($fp, 4096, $sep)) {
			if ($lines++ == 0) continue;
			list($ponumber,$line_no, $orderdate,$itemcode, $quantity, $unit,  $vendor,  $deliverydate, $location, $ref, $description, $comments) = $data;
			//$type = strtoupper($type);
			//$mb_flag = strtoupper($mb_flag);


			//if ($type == 'BUY') { 
				$itemcode = db_escape($itemcode);
				$supplier_id = db_escape($vendor);
				$loc_code = db_escape($location);
				if( check_po_number($ponumber) )
				{
					display_notification("PO $ponumber already exists");
					hyperlink_back();
					return; 

				}
				if (check_stock_id($itemcode)) {
					
					if (check_supplier_id($supplier_id) ){
						$success = true;
						if (check_location_id($loc_code)) { 
						$success = true;
					if ($prev_ponumber == $ponumber && $prev_itemcode == $itemcode) 
							{
								display_notification("Item $itemcode already exists in PO $ponumber");
								//echo "<center><p><a href='javascript:goBack();'>Back</a></p></center><br>";
								//return; 
								$success = false;
							}
					else if (!isset($line_no) or $line_no ==0)
						display_notification("Invalid Item Line number ");
					
					else {
						//$sql = "SELECT vendor_id from ".TB_PREF."purch_data where supplier_id=$supplier_id AND stock_id=$itemcode";
						//$result = db_query($sql, "Could not lookup supplier purchasing data");
						//$row = db_fetch_row($result);
						$ponumber = $ponumber;
						$itemcode = $itemcode;
						$descr = db_escape($description);
						$qty = (int)$quantity;
						$vendor = db_escape($vendor);
						$orderdt = db_escape($orderdate);
						$deliverydt = db_escape($deliverydate);
						$ref = db_escape($ref);
						$comm = db_escape($comments);
//						$unit = (int)$unit;
						$unit = db_escape($unit);
						//$loc_code = db_escape(location);
						if ($unit <= 0) $unit = 1;
						$sql = "INSERT INTO ".TB_PREF."po_import
							(id, ponumber, item_code, description, unit,
							   quantity_ordered, vendor, orderdate, deliverydate,
							   reference, requisition_no, comments, into_stock_location,
							   created_by, created_date, last_updated_by,
							   last_updated_date,status)
							VALUES (tmp_po_import_id_seq.nextval,$ponumber, $itemcode, $descr,$unit, $qty,
							$vendor,to_date($orderdt,'dd/mm/yyyy'),to_date($deliverydt,'dd/mm/yyyy'),$ref,null,
							$comm,$loc_code,". db_escape($_SESSION["wa_current_user"]->loginname). ",sysdate,". 
							db_escape($_SESSION["wa_current_user"]->last_act). ",sysdate,'PLANNED')";
						
						if ($success == true )
						{
							db_query($sql, "Could not update supplier data");
						}

					}
				$prev_ponumber = $ponumber;
				$prev_itemcode = $itemcode;
					//$p++;
					} else display_notification("Location $location not found");
					} else display_notification("Supplier $vendor not found");
				} else display_notification("Stock Code $itemcode does not exist");

			}
		//}
		@fclose($fp);
	} else display_error("No CSV file selected");
	if ($success == true)
	{
											display_notification("Items successfully imported");
	hyperlink_no_params($path_to_root."/simplex/purchasing/inquiry/view_import.php", _("View Imported Items"));
	}
}

echo "<br><br>";

if ($action == 'import') {
    start_form(true);
//echo 'files='.$_FILES['imp'];
    start_table("$table_style2 width=40%");

   /* $company_record = get_company_prefs();

    if (!isset($_POST['inventory_account']) || $_POST['inventory_account'] == "")
   	$_POST['inventory_account'] = $company_record["default_inventory_act"];

    if (!isset($_POST['cogs_account']) || $_POST['cogs_account'] == "")
   	$_POST['cogs_account'] = $company_record["default_cogs_act"];

    if (!isset($_POST['sales_account']) || $_POST['sales_account'] == "")
	$_POST['sales_account'] = $company_record["default_inv_sales_act"];

    if (!isset($_POST['adjustment_account']) || $_POST['adjustment_account'] == "")
	$_POST['adjustment_account'] = $company_record["default_adj_act"];

    if (!isset($_POST['assembly_account']) || $_POST['assembly_account'] == "")
	$_POST['assembly_account'] = $company_record["default_assembly_act"];*/
    if (!isset($_POST['sep']))
	$_POST['sep'] = ",";
	
	if (!isset($_POST['encl_char']))
	$_POST['encl_char'] = htmlspecialchars("\"");
	
	if (!isset($_POST['esc_char']))
	$_POST['esc_char'] = '\\';
	if (!isset($_POST['use_header']))
	$_POST['use_header'] = 1;
    table_section_title("PO Import");
    text_row("Field separator:", 'sep', $_POST['sep'], 2, 1);
	text_row("Enclose Character:", 'encl_char', $_POST['encl_char'], 2, 1);
	text_row("Escape Character:", 'esc_char', $_POST['esc_char'], 2, 1);
	check_row(_("Use CSV Header:"), 'use_header');
    locations_list_row("To Location:", 'location', null);
    label_row("CSV Import File:", "<input type='file' id='imp' name='imp'>");

    end_table(1);

    submit_center('import', "Import CSV File");

    end_form();
	end_page();
}
