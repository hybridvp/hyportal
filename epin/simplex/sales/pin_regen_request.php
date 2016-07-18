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

page(_($help_context = "E-PIN Regeneration Request"), false, false, "", $js);
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
	display_notification("Request  " . $_GET['AddedID'] ." was successfully Queued");
	//hyperlink_params("$path_to_root/simplex/sales/inquiry/activation_inq.php", _("View Queued Jobs"), "order_number=" . $_GET['AddedID'] );
	//hyperlink_no_params("$path_to_root/sales/inquiry/customer_allocation_inquiry.php?", _("Select another order for Activation") );
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
//-----------------------------------------------
function get_dealer_code($order_no)
{
		$sql2 = "SELECT debtor_no from "
			.TB_PREF."sales_orders  
			WHERE order_no=$order_no";
			
	$sql_b = db_query($sql2);
	$result2 = db_fetch($sql_b);
	return $result2['debtor_no'];
}
//--------------------------------------------------------------------------------------------------
function RemoveFile($filename)
{
	unlink(filename);
}

//--------------------------------------------------------------------------------------------------
function UpdatePinmailer($order_no)
{
		$sql = "UPDATE "
			.TB_PREF."pin_mailer_jobs  
			SET status = 'Q' 
			 WHERE order_no=". db_escape($order_no)  ;
			
		db_query($sql, "Cannot update file status for failed file");

}
//--------------------------------------------------------------------------------------------------
function UpdateQue($order_no,$customer_no )
{
		$sql = "UPDATE "
			.TB_PREF."t_regen_order  
			SET status = 'Q', dat_added = sysdate, dat_last_updated=sysdate
			 WHERE order_no= " . db_escape($order_no) . " and customer_no=". db_escape($customer_no)  ;
			
		db_query($sql, "Cannot t_regen_order  status");

}

//--------------------------------------------------------------------------------------------------
function AddtoQue ($order_no, $customer_no )
{
	//$id = db_insert_id ("ARCHIVE_JOBS_ID_SEQ") ;
	$sql = "INSERT INTO ".TB_PREF."t_regen_order
							(id, order_no, customer_no, status,dat_added, created_by )
							VALUES (seq_t_regen_order.nextval, " . db_escape($order_no) . "," . db_escape($customer_no) . ",'Q',sysdate," .
							 db_escape($_SESSION["wa_current_user"]->loginname) ." )";
							db_query($sql, "Could not insert t_regen_order ");

}
 //-------------------------------------------------------------------------------------------

function CheckQue($order_no, $customer_no)
{
		$sql2 = "SELECT count(1) from "
			.TB_PREF."t_regen_order  
			WHERE order_no= " . db_escape($order_no) . " and customer_no=". db_escape($customer_no)  ;
			
	$sql_b = db_query($sql2, "select from t_regen_order failed");
	$row = db_fetch_row($sql_b);
	return ($row[0] > 0);
}
 //-------------------------------------------------------------------------------------------

function IsValidOrder($order_no)
{
		$sql2 = "SELECT count(1) from "
			.TB_PREF."sales_orders  
			WHERE order_no=" . db_escape($order_no);
			
	$sql_b = db_query($sql2, "select from sales_orders failed");
	$row = db_fetch_row($sql_b);
	return ($row[0] > 0);
}

if (isset($_POST['addrange']))
{
	$input_error = 0;
	global	$min_range_value;
	global $nonfin_audit_trail;
		
			if ( strlen($_POST['order_no']) == 0) 
			{
				$input_error = 1;
				display_error( _('The order number cannot be empty.'));
				set_focus('order_no');
			} 
			elseif (  !is_numeric($_POST['order_no'])) 
			{
				$input_error = 1;
				display_error( _("The order number must be numeric."));
				set_focus('order_no');
			}
			elseif ( !IsValidOrder($_POST['order_no']) )  //
			{
				$input_error = 1;
				display_error( _("Invalid Order number."));
				set_focus('order_no');
			}
	
	if ($input_error != 1)
	{
			//	$addedid = AddRange($_POST['batch_no'],$_POST['start_no'], $_POST['end_no'],104 );
			$addedid = UpdatePinmailer($_POST['order_no']);
			$dealer_code = get_dealer_code($_POST['order_no']);
			if (CheckQue($_POST['order_no'], $dealer_code) )
			{
				echo ("Updating Queue"). "<br/>";
				UpdateQue($_POST['order_no'], $dealer_code );
			}
			else
			{
				echo ("Adding to Queue"). "<br/>";
				AddtoQue ($_POST['order_no'], $dealer_code );
			}
			//
			
			//$dir = "c:/test/\"Test Directory\"";
			//$filename = "C:/Progra~2/Bluech~1/SimplexMail/pinmailer/". strtolower($dealer_code). "/" . strtolower($dealer_code) . ".pem";
			//echo " Removing file :" . $filename . "<br/>";
			//$success = RemoveFile($filename);
			/*if (!unlink($filename))
			  {
			  echo ("Error deleting $filename"). "<br/>";
			  }
			else
			  {
			  echo ("Deleted $filenames"). "<br/>";
			  } 
			  */
			if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'PIN REGENERATION REQUEST','A',$ip,'REQUEST NUMBER ' . $addedid. " SUBMITTED ");
			}
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
	//display_header();
	//--------------------------------------------------------------------------------------
	echo '<br>';

div_start('details');
start_outer_table($table_style2, 5);

table_section(1);

table_section_title(_("PIN Regegeration Request"));
//activation_types_list_row(_("Activation Mode"), 'action_mode',null,true);
//activation_list_cells_ ( _("Deactivation"),'action_mode', null, true);
//if( $activation_mode ==1 )
//{
	text_row(_("Order Number:"), 'order_no', null, 20, 20);
//}
//elseif( $activation_mode ==2 )
//{

	//text_row(_("Start Sequence Number:"), 'start_no', $_POST['start_serial'], 30, 16);
	//text_row(_("End Sequence Number:"), 'end_no', $_POST['end_serial'], 30, 16);
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