<?php
/**********************************************************************
    Copyright Simplex
***********************************************************************/
$page_security = 'SA_SALESALLOC';
$path_to_root = "../..";
include_once($path_to_root . "/includes/session.inc");
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page(_($help_context = "E-PIN De-Activation Request"), false, false, "", $js);
//page(_($help_context = "Are you booking an existing customer? Search Customers"));

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc");


//simple_page_mode(true);
//$selected_component = $selected_id;
if (isset($_GET['action_mode'])) 
{
	$_POST['action_mode'] = $_GET['action_mode'] ; 
}
$selected = (!isset($_POST['action_mode']) || $_POST['action_mode'] == ""); 
if (isset($_POST['action_mode'])) 
{
	$activation_mode = $_POST['action_mode']; 
}
if (isset($_GET['start_serial'])) 
{
	$_POST['start_serial'] = $_GET['start_serial'] ; 
}

if (isset($_GET['end_serial'])) 
{
	$_POST['end_serial'] = $_GET['end_serial'] ; 
}
//--------------------------------------------------------------------------------------------------

if (isset($_GET['AddedID']))
{
	$grn = $_GET['AddedID'];
	
	//hyperlink_params("$path_to_root/simplex/sim/split_range.php" , _("&Split Items in this range"), "id=". $_GET['AddedID']);
	display_notification("De-Activation job " . $_GET['AddedID'] ." was successfully Queued");
	hyperlink_params("$path_to_root/simplex/sales/inquiry/activation_inq.php", _("View Queued Jobs"), "order_number=" . $_GET['AddedID'] );
	hyperlink_no_params("$path_to_root/sales/inquiry/customer_allocation_inquiry.php?", _("Select another order for Deactivation") );
	display_footer_exit(); 
}

//--------------------------------------------------------------------------------------------------

function display_header()
{
	global $table_style;

	//$result = get_bom($selected_parent);
div_start('bom');
	start_table("$table_style width=60%");
	$th = array(_(" "), _(""), _(""),
		_(""), _(""));
	table_header($th);
	end_table();
div_end();
}
//--------------------------------------------------------------------------------------------------
function AddRange ($batch_no="",$start_no="", $end_no="", $action_mode)
{
	global $db;
	begin_transaction();
	$id = db_insert_id ("ACTIVATION_JOBS_ID_SEQ") ;
	$job_id = date('YmdHis');
	$sql = "INSERT INTO ".TB_PREF."ACTIVATION_JOBS
			(id, batch_no, start_serial, end_serial, dat_logged, job_status,action_mode,cod_user_id,jobid, item_type ,error_code)
			VALUES ($id,". db_escape($batch_no) . "," .db_escape($start_no) . "," .db_escape($end_no). ", sysdate,'L',". db_escape($action_mode). ",". db_escape($_SESSION["wa_current_user"]->loginname) . ", " . db_escape($job_id) .",'EPIN', 2)";  //to_char(sysdate,'YYYYMMDDHH24MISS') "
							db_query($sql, "Could not insert deactivation job");
					
						
					$sql = 'BEGIN proc_active_deactive(:TIME_GN,:BATCH_NO,:START_SERIAL,:END_SERIAL,:ACTIVATION_TYPE); END;';
					$result = $stmt = oci_parse($db,$sql);
					//  Bind the input parameter
					oci_bind_by_name($stmt,':TIME_GN',$job_id,20);
					oci_bind_by_name($stmt,':BATCH_NO',$batch_no,8);
					oci_bind_by_name($stmt,':START_SERIAL',$start_no,16);
					oci_bind_by_name($stmt,':END_SERIAL',$end_no,16);
					oci_bind_by_name($stmt,':ACTIVATION_TYPE',$action_mode,5);
					
					oci_execute($stmt, OCI_DEFAULT);
					$err = oci_error($result);
					if( $err ){
								$db_err ="ERROR";
								//oci_rollback($db);
								$pos = strrpos($err['message'],"ORA-00001");
								if( $pos > 0)
								{
									display_error( "Failed while Logging activation request");
								}
								else
									display_error( "Failed while Logging activation request:" . $err['code']);
								cancel_transaction();
								return;
								
					}
					else
					 {
					 	commit_transaction();
					 	display_notification("Activation was successfully Queued");
					 }	
	
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
 //-------------------------------------------------------------------------------------------
function IsValidBatch($batch_no)
{
		$sql2 = "SELECT count(1) from "
			.TB_PREF."pin_master  
			WHERE batch_no=" . db_escape($batch_no);
			
	$sql_b = db_query($sql2, "select from pin_master failed");
	$row = db_fetch_row($sql_b);
	return ($row[0] > 0);
}
function IsValidSequence($sequence_no)
{
		$sql2 = "SELECT count(1) from "
			.TB_PREF."pin_details  
			WHERE sequence_number=" . db_escape($sequence_no);
			
	$sql_b = db_query($sql2, "select from pin_mdetails failed");
	$row = db_fetch_row($sql_b);
	return ($row[0] > 0);
}
if (isset($_POST['addrange']))
{
	$input_error = 0;
	global	$min_range_value;
	global $nonfin_audit_trail;
	//echo 'mode=' .$activation_mode;
	//do validation here
	//activation_mode !=2 || $activation_mode!=1 
	/* if( $activation_mode !=2 || $activation_mode!=1 )
	{
			$input_error = 1;
			display_error( _('You must Select the activation mode.'));
			set_focus('action_mode');
	} */
	if	($activation_mode ==2) {
		
			if ( strlen($_POST['start_no']) == 0) 
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
			elseif (!strlen($_POST['batch_no']) == 0) 
			{
				$input_error = 1;
				display_error( _('Batch number must be empty to de-activate in this mode'));
				set_focus('end_no');
			}
			elseif ( !is_numeric($_POST['start_no']) ) 
			{
				$input_error = 1;
				display_error( _("The start number must be numeric."));
				set_focus('start_no');
			}
			elseif (  !is_numeric($_POST['end_no'])) 
			{
				$input_error = 1;
				display_error( _("The end number must be numeric."));
				set_focus('end_no');
			}
			/* elseif ( ($_POST['end_no'] - $_POST['start_no'] <= 0) )  //
			{
				$input_error = 1;
				display_error( _("The end number must be greater than the start number."));
				set_focus('start_no');
			} 
			*/
			elseif ( !IsValidSequence($_POST['start_no'])  )  
			{
				$input_error = 1;
				display_error( _("Invalid Sequence number."));
				set_focus('start_no');
			}
			elseif ( !IsValidSequence($_POST['end_no']) )  
			{
				$input_error = 1;
				display_error( _("Invalid Sequence number."));
				set_focus('end_no');
			}
			
			/* elseif ( $_POST['end_no'] - $_POST['start_no'] < $min_range_value) 
			{
				$input_error = 1;
				display_error( _("The difference is less than the minimum range value."));
				set_focus('start_no');
			} */
	}
	elseif (  ($activation_mode ==1) )
	{
		if ( strlen($_POST['batch_no']) == 0) 
		{
			$input_error = 1;
			display_error( _('The batch number cannot be empty  when de-activating in batch mode.'));
			set_focus('batch_no');
		} 
		elseif ( !strlen($_POST['start_no'] == 0) ||  !strlen($_POST['end_no']) ==0 ) 
		{
				$input_error = 1;
				display_error( _("Start Sequence and end sequence must be null to de-activate in batch mode."));
				set_focus('start_no');
		}
		elseif ( !IsValidBatch($_POST['batch_no'])) 
		{
			$input_error = 1;
			display_error( _('Invalid batch number.'));
			set_focus('batch_no');
		} 
	}
	if ($input_error != 1)
	{
		//if ( ValidateRange($_POST['start_no'], $_POST['end_no'] )   )
		//{
			$addedid = AddRange($_POST['batch_no'],$_POST['start_no'], $_POST['end_no'],105 );
			
			if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'PIN DEACTIVATION REQUEST','A',$ip,'REQUEST NUMBER ' . $addedid. " SUBMITTED ");
			}
			//header("Location:".$path_to_root. "/simplex/sim/split_range.php");
			meta_forward($_SERVER['PHP_SELF'], "AddedID=".$addedid);
			//display_notification("Activation job" . $addedid ." was successfully Queued");
		//}
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
	//display_header();
	//--------------------------------------------------------------------------------------
	echo '<br>';

div_start('details');
start_outer_table($table_style2, 5);

table_section(1);

table_section_title(_("De-Activation Request"));
activation_types_list_row(_("Deactivation Mode"), 'action_mode', $_POST['action_mode'] ,true);
//activation_list_cells_ ( _("Deactivation"),'action_mode', null, true);
//if( $activation_mode ==1 )
//{
	text_row(_("Batch Number:"), 'batch_no', null, 20, 8);
//}
//elseif( $activation_mode ==2 )
//{

	text_row(_("Start Sequence Number:"), 'start_no', $_POST['start_serial'], 30, 16);
	text_row(_("End Sequence Number:"), 'end_no', $_POST['end_serial'], 30, 16);
//}
submit_cells('addrange', _("Send Request"), "colspan=2", _(''), false);
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