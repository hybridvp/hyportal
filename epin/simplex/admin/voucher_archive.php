<?php
/**********************************************************************
    Copyright Simplex
***********************************************************************/
$page_security = 'SA_FORMSETUP';
$path_to_root = "../..";
include_once($path_to_root . "/includes/session.inc");
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page(_($help_context = "EPIN Archive"), false, false, "", $js);
//page(_($help_context = "Are you booking an existing customer? Search Customers"));

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc");


simple_page_mode(true);
$selected_component = $selected_id;
//--------------------------------------------------------------------------------------------------

if (isset($_GET['AddedID']))
{
	$grn = $_GET['AddedID'];

	
	hyperlink_params("$path_to_root/simplex/admin/archive_job_status.php" , _("&View Archive Job Status"), "id=". $_GET['AddedID']);

	display_footer_exit();
}

//--------------------------------------------------------------------------------------------------

function display_header()
{
	global $table_style;

	//$result = get_bom($selected_parent);
div_start('bom');
	start_table("$table_style width=60%");
	$th = array(_("Voucher Archiving ..."), _(""), _(""),
		_(""), _(""));
	table_header($th);
	end_table();
div_end();
}
//--------------------------------------------------------------------------------------------------
function AddArchive ($start_date )
{
	$id = db_insert_id ("ARCHIVE_JOBS_ID_SEQ") ;
	$sql = "INSERT INTO ".TB_PREF."ARCHIVE_JOBS
							(id, cod_user_id, job_status, dat_logged )
							VALUES ($id, " . db_escape($_SESSION["wa_current_user"]->loginname). " ,'L', to_date( " . db_escape($start_date) . " ,'yyyy-mm-dd') )";
							db_query($sql, "Could not insert ARCHIVE_JOBS ");
	display_notification("Archive job successfully Started");
	return $id;

}
function ValidateRange($start_no, $end_no)
{
	$sql = "SELECT start_no,end_no from OPS_NUMBERS"	 ;  //AND stock.visible=1
	$result = db_query($sql);
	while ($row = db_fetch($result))
	{
		for ($i = $row['start_no']; $i <= $row['end_no']; $i++ )
		{
			for ($j = $start_no; $j <= $end_no; $j++)
			{
				if( $i == $j)
				{
					display_error(_("Some numbers in this range are already used."));
					return false;
				} 
			}
		}
	}
	return true;
	 
	
}
if (isset($_POST['addrange']))
{
	$input_error = 0;
	global	$min_range_value;
	//do validation here
//$_POST['OrdersAfterDate']
$s_date = strtotime(date2sql( $_POST['next_archive']) );
$l_date = strtotime($_POST['last_archive']);
$today = strtotime(date("Y-m-d"));
	if ($l_date >= $s_date || $l_date >= $today  || $s_date < $today) 
	{
		$input_error = 1;
		display_error( _('Invalid start date: '));  //$sdate=' . $s_date . "-today=" .$today 
		set_focus('next_archive');
	} 
/* 	elseif ($_POST['end_no'] - $_POST['start_no'] < $min_range_value) 
	{
	    $input_error = 1;
	    display_error( _("The difference is less than the minimum range value."));
		set_focus('start_no');
	} */
	if ($input_error != 1)
	{
			$addedid = AddArchive( date2sql( $_POST['next_archive']) );
			meta_forward($_SERVER['PHP_SELF'], "AddedID=".$addedid);
	}

}
start_form();

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
$myrow = get_company_prefs();
table_section(1);

table_section_title(_("Start Archiving"));

		label_row(_("Last Archive Date:"), $myrow['last_archive']);
		hidden('last_archive',$myrow['last_archive']);
		start_row();
		//text_row(_("End Sequence Number:"), 'end_no', null, 31, 20);
		//email_row_ex(_("Email Address:"), 'email', 50);
		date_row(_("Start at:"), 'next_archive', '', null, 0);
		end_row();
submit_cells('addrange', _("Start"), "colspan=2", _(''), false);
end_outer_table(1);

div_end();
	//submit_add_or_update_center($selected_id == -1, '', 'both');
	//submit_center_first('addrange', _("Add new Range"),_("xxx"), 'default');
	//submit_cells('addrange', _("Add new Range"), "colspan=2", _(''), true);
//	submit_center_first('AddTrip', _("Go"), _("xxx"), 'default');
end_form();

// ----------------------------------------------------------------------------------

end_page();

?>