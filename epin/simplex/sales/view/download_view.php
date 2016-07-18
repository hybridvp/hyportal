<?php
/**********************************************************************
    Copyright Simplex
***********************************************************************/
$page_security = 'SA_SALESTRANSVIEW';
$path_to_root = "../../..";
include_once($path_to_root . "/includes/session.inc");
//include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/admin/db/maintenance_db.inc");
$js = "";
			//if ($use_popup_windows)
				//$js .= get_js_open_window(900, 500);
			//if ($use_date_picker)
			//	$js .= get_js_date_picker();

//page(_($help_context = "Download File"), false, false, "", $js);
		//page(_($help_context = "Are you booking an existing customer? Search Customers"));

		//include_once($path_to_root . "/includes/date_functions.inc");
//include_once($path_to_root . "/includes/data_checks.inc");


			//simple_page_mode(true);
			//$selected_component = $selected_id;
//check_paths();
function get_pin_file_out_dir()
{
		$sql2 = "SELECT pin_file_out_dir from "
			.TB_PREF."company  
			WHERE coy_code=1";
			
	$sql_b = db_query($sql2);
	$result2 = db_fetch($sql_b);
	return $result2['pin_file_out_dir'];
}

function check_paths()
{
$pin_out_dir = get_pin_file_out_dir();
	if (!file_exists($pin_out_dir)) {   //PIN_PATH
		display_error (_("EPIN paths have not been set correctly.") 
			._("Please contact System Administrator.")."<br>" 
			. _("cannot find PIN directory") . " - " . $pin_out_dir . "<br>");
		end_page();
		exit;
	}
}
function download_file($filename)
{
    if (empty($filename) || !file_exists($filename))
    {
		display_error (_("File is not ready for download.". $filename )) ;
		echo "<center><p><a href='javascript:goBack();'>Back</a></p></center><br>";
		
        return false;
    }
    $saveasname = basename($filename);
    header('Content-type: application/octet-stream');
   	header('Content-Length: '.filesize($filename));
   	header('Content-Disposition: attachment; filename="'.$saveasname.'"');
    readfile($filename);

    return true;
}
//--------------------------------------------------------------------------------------------------

/*if (isset($_GET['AddedID']))
{
	$grn = $_GET['AddedID'];

	
	hyperlink_params("$path_to_root/simplex/sim/split_range.php" , _("&Split Items in this range"), "id=". $_GET['AddedID']);

	hyperlink_no_params("$path_to_root/simplex/sim/ops_item_search.php", _("View Items"));

	display_footer_exit();
}*/
if (isset($_GET["filename"])){

	$host = DB_HOST;
	$user = DB_USER;
	$filename = $_GET["filename"];
	$customer_no = $_GET["customer_no"];
	$pin_out_dir = get_pin_file_out_dir();
	//ftp://user@ftpserver/url-path
	$download = download_file("ftp://". $user ."@". $host .$pin_out_dir . "/". $customer_no . "/". $filename);
			if($nonfin_audit_trail && $download == true)
			{
			$ip = preg_quote($_SERVER['REMOTE_ADDR']);
			add_nonfin_audit_trail(0,0,0,0,'CUSTOMER EPIN DOWNLOAD','A',$ip,'CUSTOMER EPIN ' . $filename. " DOWNLOADED ");
			}
	exit;

}
//--------------------------------------------------------------------------------------------------

/*function display_header()
{
	global $table_style;

	//$result = get_bom($selected_parent);
div_start('bom');
	//start_table("$table_style width=60%");
	//$th = array(_("1. Stage"), _("2. Stage 2"), _("3. Stage 3"),
	//	_("4. Stage 4"), _("5. Stage 5"));
	//table_header($th);
	//end_table();
div_end();
}
//--------------------------------------------------------------------------------------------------

/*start_form();

start_form(false, true);
start_table("class='tablestyle_noborder'");

end_table();
br();

end_form();

	//--------------------------------------------------------------------------------------

start_form();
	display_header();
	//--------------------------------------------------------------------------------------
	echo '<br>';

div_start('details');
start_outer_table($table_style2, 5);

table_section(1);

table_section_title(_("File Download"));

end_outer_table(1);

div_end();
	//submit_add_or_update_center($selected_id == -1, '', 'both');
	//submit_center_first('addrange', _("Add new Range"),_("xxx"), 'default');
	//submit_cells('addrange', _("Add new Range"), "colspan=2", _(''), true);
//	submit_center_first('AddTrip', _("Go"), _("xxx"), 'default');
end_form();

// ----------------------------------------------------------------------------------

end_page();
*/

?>
