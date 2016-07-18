<?php
/**********************************************************************
    Copyright Simplex
***********************************************************************/
$page_security = 'SA_SALESTRANSVIEW';
$path_to_root = "../..";
include_once($path_to_root . "/includes/session.inc");
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page(_($help_context = "- Enter Ranges"), false, false, "", $js);
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

	
	hyperlink_params("$path_to_root/simplex/sim/split_range.php" , _("&Split Items in this range"), "id=". $_GET['AddedID']);

	hyperlink_no_params("$path_to_root/simplex/sim/ops_item_search.php", _("View Items"));

	display_footer_exit();
}

//--------------------------------------------------------------------------------------------------

function display_header()
{
	global $table_style;

	//$result = get_bom($selected_parent);
div_start('bom');
	start_table("$table_style width=60%");
	$th = array(_("1. Stage"), _("2. Stage 2"), _("3. Stage 3"),
		_("4. Stage 4"), _("5. Stage 5"));
	table_header($th);
	end_table();
div_end();
}
//--------------------------------------------------------------------------------------------------
function AddRange ($start_no, $end_no )
{
	$id = db_insert_id ("OPS_NUMBERS_ID_SEQ") ;
	$sql = "INSERT INTO ".TB_PREF."OPS_NUMBERS
							(id, start_no, end_no, created_date, created_by )
							VALUES ($id,$start_no, $end_no, sysdate,". db_escape($_SESSION["wa_current_user"]->loginname) . ")";
							db_query($sql, "Could not insert OPS numbers");
	display_notification("Range ". $start_no . " to " . $end_no . " was successfully added");
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

	if (strlen($_POST['start_no']) == 0) 
	{
		$input_error = 1;
		display_error( _('The start number cannot be empty.'));
		set_focus('start_no');
	} 
	elseif (strlen($_POST['end_no']) == 0) 
	{
		$input_error = 1;
		display_error( _('End Number cannot be empty'));
		set_focus('end_no');
	}
	elseif (!is_numeric($_POST['start_no']) ) 
	{
	    $input_error = 1;
	    display_error( _("The start number must be numeric."));
		set_focus('start_no');
	}
	elseif (!is_numeric($_POST['end_no'])) 
	{
	    $input_error = 1;
	    display_error( _("The end number must be numeric."));
		set_focus('end_no');
	}
	elseif ($_POST['end_no'] - $_POST['start_no'] <= 0)  //
	{
	    $input_error = 1;
	    display_error( _("The end number must be greater than the start number."));
		set_focus('start_no');
	}
	
	elseif ($_POST['end_no'] - $_POST['start_no'] < $min_range_value) 
	{
	    $input_error = 1;
	    display_error( _("The difference is less than the minimum range value."));
		set_focus('start_no');
	}
	if ($input_error != 1)
	{
		if ( ValidateRange($_POST['start_no'], $_POST['end_no'] )   )
		{
			$addedid = AddRange($_POST['start_no'], $_POST['end_no'] );
			
			//header("Location:".$path_to_root. "/simplex/sim/split_range.php");
			meta_forward($_SERVER['PHP_SELF'], "AddedID=".$addedid);
		}
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

table_section(1);

table_section_title(_("SIM Ranges"));

		text_row(_("Start Number:"), 'start_no', null, 21, 20);
		text_row(_("End Number:"), 'end_no', null, 21, 20);
submit_cells('addrange', _("Add new Range"), "colspan=2", _(''), false);
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
