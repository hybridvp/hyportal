<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_CUSTOMER';
$path_to_root = "../..";

include_once($path_to_root . "/includes/session.inc");
page(_($help_context = "Dealer Management"), @$_REQUEST['popup']); 

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/banking.inc");
include_once($path_to_root . "/includes/ui.inc");
include($path_to_root . "/simplex/includes/ftp.class.inc");

if (isset($_GET['debtor_no'])) 
{
	$_POST['customer_id'] = $_GET['debtor_no'];
}
$new_customer = (!isset($_POST['customer_id']) || $_POST['customer_id'] == ""); 
//$dealerkey_path =  DEALERKEY_PATH ;
//--------------------------------------------------------------------------------------------
global $nonfin_audit_trail;
function can_process()
{
	if (strlen($_POST['CustName']) == 0) 
	{
		display_error(_("The dealer name cannot be empty."));
		set_focus('CustName');
		return false;
	} 
	if ( !ctype_alpha( substr($_POST['custcode'],1,1) ) ) 
	{
		display_error(_("The dealer code must start with a letter." . substr($_POST['custcode'],1,1)));
		set_focus('custcode');
		return false;
	} 
	if (strlen($_POST['email']) == 0) 
	{
		display_error(_("The customer email is compulsory."));
		set_focus('email');
		return false;
	} 
	
	if (!isValidEmail($_POST['email']))
	{
		display_error( _("You must enter a valid email address ."));
		set_focus('email');
		return false;
	}
	if (strlen($_POST['cust_ref']) == 0) 
	{
		display_error(_("The dealer short name cannot be empty."));
		set_focus('cust_ref');
		return false;
	} 
	
	if (!check_num('credit_limit', 0))
	{
		display_error(_("The credit limit must be numeric and not less than zero."));
		set_focus('credit_limit');
		return false;		
	} 
	
	if (!check_num('pymt_discount', 0, 100)) 
	{
		display_error(_("The payment discount must be numeric and is expected to be less than 100% and greater than or equal to 0."));
		set_focus('pymt_discount');
		return false;		
	} 
	
	if (!check_num('discount', 0, 100)) 
	{
		display_error(_("The discount percentage must be numeric and is expected to be less than 100% and greater than or equal to 0."));
		set_focus('discount');
		return false;		
	} 

	return true;
}
function getFilenameWithoutExt($filename){
    $pos = strripos($filename, '.');
    if($pos === false){
        return $filename;
    }else{
        return substr($filename, 0, $pos);
    }
}
//-------------------------------------------------------------------------------------------------

function isValidEmail($email){ 
      $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$"; 
      
      if (eregi($pattern, $email)){ 
         return true; 
      } 
      else { 
         return false; 
      }    
 } 
 //-------------------------------------------------------------------------------------------
 function get_dealer_key_dir()
{
		$sql2 = "SELECT dealer_keys_dir from "
			.TB_PREF."company  
			WHERE coy_code=1";
			
	$sql_b = db_query($sql2);
	$result2 = db_fetch($sql_b);
	return $result2['dealer_keys_dir'];
}
//--------------------------------------------------------------------------------------------
function get_pin_file_out_dir()
{
		$sql2 = "SELECT pin_file_out_dir from "
			.TB_PREF."company  
			WHERE coy_code=1";
			
	$sql_b = db_query($sql2);
	$result2 = db_fetch($sql_b);
	return $result2['pin_file_out_dir'];
}
//---------------------------------------------------------------------------------------------
function create_cust_logical_dir($cust_code)
{
		$pin_file_out_dir = get_pin_file_out_dir();	
		$sql2 = "create or replace directory " .$cust_code . " as '" .$pin_file_out_dir ."/" .strtolower($cust_code) . "'";
		$sql_b = db_query($sql2,"The dealer logical directory could not be created");
}
//---------------------------------------------------------------------------------------------
function create_cust_pindir($cust_code)
{
	$ftp_server = DB_HOST;
	$ftp_user = DB_USER;
	$ftp_passwd = DB_PASS;
	
	$pin_file_out_dir = get_pin_file_out_dir();	
	$ftp =& new FTP();
	if ($ftp->connect($ftp_server)) {
		if ($ftp->login($ftp_user,$ftp_passwd)) {
			$ftp->chdir($pin_file_out_dir);
			$ftp->mkdir(strtolower($cust_code));
			$ftp->chmod(775,$cust_code);
		} else {
			//echo "login failed: ";
			//print_r($ftp->error_no);
			//print_r($ftp->error_msg);
			$ftp->disconnect();
			display_error(_("Cannot complete dealer creation ->login falied " .$ftp->error_no . $ftp->error_msg. " manual directory creation suggested"));
		}
		$ftp->disconnect();
		//print_r($ftp->lastLines);
	} else {
		//echo "connection failed: ";
		//print_r($ftp->error_no);
		//print_r($ftp->error_msg);
		$ftp->disconnect();
			display_error(_("Cannot complete dealer creation -> " .$ftp->error_no . $ftp->error_msg. " manual directory creation suggested"));

	}
	
}
//--------------------------------------------------------------------------------------------

function handle_submit()
{
	global $path_to_root, $new_customer, $Ajax;
	global $nonfin_audit_trail;
	if (!can_process())
		return;
		
	if ($new_customer == false) 
	{

		$sql = "UPDATE ".TB_PREF."debtors_master SET name=" . db_escape($_POST['CustName']) . ", 
			debtor_ref=" . db_escape($_POST['cust_ref']) . ",
			address=".db_escape($_POST['address']) . ", 
			tax_id=".db_escape($_POST['tax_id']) . ", 
			curr_code=".db_escape($_POST['curr_code']) . ", 
			email=".db_escape($_POST['email']) . ", 
			dimension_id=".db_escape($_POST['dimension_id']) . ", 
			dimension2_id=".db_escape($_POST['dimension2_id']) . ", ".
			"credit_status=".db_escape($_POST['credit_status']) . ", ".
/////////////////////////////////////////////////////////////////////////////////////			
//Added: Credit status and payment terms are no longer updated from this customer screen. 
//A separate screen is now to be used for the credit status setting
/*            "credit_status=".db_escape($_POST['credit_status']) . ", ".
            "payment_terms=".db_escape($_POST['payment_terms']) . ", 
            credit_limit=" . input_num('credit_limit') . ",			
            discount=" . input_num('discount') / 100 . ", 
            pymt_discount=" . input_num('pymt_discount') / 100 . ",  */			
/////////////////////////////////////////////////////////////////////////////////////////			

           "sales_type = ".db_escape($_POST['sales_type']) . ", 
            notes=".db_escape($_POST['notes']) . "
            WHERE debtor_no = ".db_escape($_POST['customer_id']);

		db_query($sql,"The dealer could not be updated");

		update_record_status($_POST['customer_id'], $_POST['inactive'],
			'debtors_master', 'debtor_no');
			if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'DEALER MAINTENANCE','M',$ip,'DEALER CODE' . $_POST['customer_id']. "MODIFIED");
			}
		/*   *********  */
		//if (isset($_FILES['uploadfile']) && $_FILES['uploadfile']['name'] != '')
		//{
						//}
		/*              */

		$Ajax->activate('customer_id'); // in case of status change
		display_notification(_("Dealer has been updated."));
	} 
	else 
	{ 	//it is a new customer

/* 		begin_transaction();

		$sql = "INSERT INTO ".TB_PREF."debtors_master (debtor_no, name, debtor_ref, address, tax_id, email, dimension_id, dimension2_id,  
			curr_code, credit_status, payment_terms, discount, pymt_discount,credit_limit,  
			sales_type, notes) VALUES (".trim(strtoupper( db_escape($_POST['custcode']) )).", ".db_escape($_POST['CustName']) .", " .db_escape($_POST['cust_ref']) .", "
			.db_escape($_POST['address']) . ", " . db_escape('1') . ","
			.db_escape($_POST['email']) . ", ".db_escape($_POST['dimension_id']) . ", " 
			.db_escape($_POST['dimension2_id']) . ", ".db_escape($_POST['curr_code']) . ", 
			" . db_escape($_POST['credit_status']) . ", ".db_escape($_POST['payment_terms']) . ", " . input_num('discount')/100 . ", 
			" . input_num('pymt_discount')/100 . ", " . input_num('credit_limit') 
			 .", ".db_escape($_POST['sales_type']).", nvl(".db_escape($_POST['notes']) . ",' '))";

		db_query($sql,"The dealer could not be added");

		$_POST['customer_id'] = $_POST['custcode']; //db_insert_id;
		$new_customer = false;
		
			if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'DEALER MAINTENANCE','A',$ip,'DEALER CODE' . $_POST['custcode']. " ADDED ");
			}
		commit_transaction();	
		//create pin out dir for this customer
		//$pin_file_out_dir = get_pin_file_out_dir();	
		
		if (!strlen($_POST['custcode'])==0 ) //&& !file_exists($_POST['custcode']))
		{
			//mkdir ($pin_file_out_dir . "//" .$_POST['custcode'],0777);
			create_cust_pindir(strtolower($_POST['custcode']));
			create_cust_logical_dir($_POST['custcode']);
			
		} */
		display_notification(_("New dealer cannot be added from this channel."));

		$Ajax->activate('_page_body');
	}
}
//--------------------------------------------------------------------------------------------

if (get_post('upload'))
{

	if ($new_customer == false)  
	{
		//display_notification(_("Customer has been updated."));
			$tmpname = $_FILES['uploadfile']['tmp_name'];
			$fname = $_FILES['uploadfile']['name'];
			//$path_parts = pathinfo($_FILES['File']['name'], PATHINFO_FILENAME); 
			//$FileName = $path_parts['filename']; 
			$filewoutextension = getFilenameWithoutExt($fname) . ".txt";
			
			if (!preg_match("/.gpg(.zip|.gz)?$/", $fname))
				display_error(_("You can only upload *.gpg files"));
			elseif (is_uploaded_file($tmpname)) {
				rename($tmpname, $dealerkey_path  . "\\".$fname);
				$username = strtoupper($_SESSION["wa_current_user"]->loginname);
				if(isset($fname) && $fname !="")
				{
					/******/
					// get FTP access parameters
					$host = DB_HOST;
					$user = DB_USER;
					$pass = DB_PASS;
					$destDir = get_dealer_key_dir () ; //'/epin_data/epin_io_dir/incoming';
					$workDir = '../../tmp'; // define this as per local system
					// open connection
					$conn_id = ftp_connect($host) or die ("Cannot initiate connection to	 host");
					
					// login with username and password
					$login_result = ftp_login($conn_id, $user, $pass);			 
					// check connection and login result
					if ((!$conn_id) || (!$login_result)) {
						   echo "FTP connection has encountered an error!";
						   echo "Attempted to connect to $host for user $user....";
						   exit;
					   } else {
						   echo "Connected to $host";
					   }
					   
					   // upload the file to the path specified
		
						$upload = ftp_put($conn_id, $destDir."/".$fname, $dealerkey_path  . "\\".$fname, FTP_BINARY);
						
						// check upload status
						// display message
						if (!$upload) {
						  echo "FTP upload has encountered an error!";
						} else {
						  echo "Uploaded file with name $fname to $host";
						}
						
						// close the FTP stream
						ftp_close($conn_id);
					
					// delete local copy of uploaded file			
					//unlink($rawpin_in_path .$tmpname) or die("Cannot delete uploaded file from working directory -- manual deletion recommended");
						 if (unlink($dealerkey_path . $fname)) {
							//display_notification(_("File successfully deleted.")." ". _("Filename") . ": " . get_post('rawpins'));
							//$Ajax->activate('rawpins');
						}
						else
							display_error(_("Cannot delete uploaded file from working directory -- manual deletion recommended")); 
					/******/
					//display_error(_("here3")); 
							 begin_transaction();
							 
							 $sql_q = "UPDATE " .TB_PREF."debtors_master SET gpg_filename = " . db_escape($fname )
									  . " WHERE debtor_no = " . db_escape($_POST['customer_id']) ;
							 db_query($sql_q,"The gpg_filename could not be updated");
							 
							/* $sql = 'BEGIN pkg_mnt_epin.ins_pin_upload(:filename,:logon_user); END;';
							$result = $stmt = oci_parse($db,$sql);
							oci_bind_by_name($stmt,':filename',$filewoutextension,255);
							oci_bind_by_name($stmt,':logon_user',$username,30);
							
							oci_execute($stmt, OCI_DEFAULT);
							$err = oci_error($result);
							if( $err ){
										$db_err ="ERROR";
										//oci_rollback($db);
										cancel_transaction();
										return;
										
							} */
							$ip = preg_quote($_SERVER['REMOTE_ADDR']);
							add_nonfin_audit_trail(0,0,0,0,'DEALER MAINTENANCE','A',$ip,'GPG DATAFILE :' . $fname . " WAS UPLOADED FROM " . $fname);
							commit_transaction(); 
					unset($fname);
					unset($tmpname);
				}
				display_notification( "File uploaded to GPG directory");
				//$Ajax->activate('rawpins');
			} else
				display_error(_("File was not uploaded into the system."));

	}
}
//--------------------------------------------------------------------------------------------

if (isset($_POST['submit'])) 
{
	handle_submit();
}
//-------------------------------------------------------------------------------------------- 

if (isset($_POST['delete'])) 
{

	//the link to delete a selected record was clicked instead of the submit button

	$cancel_delete = 0;

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'debtor_trans'
	$sel_id = db_escape($_POST['customer_id']);
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."debtor_trans WHERE debtor_no=$sel_id";
	$result = db_query($sql,"check failed");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		$cancel_delete = 1;
		display_error(_("This dealer cannot be deleted because there are transactions that refer to it."));
	} 
	else 
	{
		$sql= "SELECT COUNT(*) FROM ".TB_PREF."sales_orders WHERE debtor_no=$sel_id";
		$result = db_query($sql,"check failed");
		$myrow = db_fetch_row($result);
		if ($myrow[0] > 0) 
		{
			$cancel_delete = 1;
			display_error(_("Cannot delete the dealer record because orders have been created against it."));
		} 
		else 
		{
			$sql = "SELECT COUNT(*) FROM ".TB_PREF."cust_branch WHERE debtor_no=$sel_id";
			$result = db_query($sql,"check failed");
			$myrow = db_fetch_row($result);
			if ($myrow[0] > 0) 
			{
				$cancel_delete = 1;
				display_error(_("Cannot delete this dealer because there are branch records set up against it."));
				//echo "<br> There are " . $myrow[0] . " branch records relating to this customer";
			}
		}
	}
	
	if ($cancel_delete == 0) 
	{ 	//ie not cancelled the delete as a result of above tests
		$sql = "DELETE FROM ".TB_PREF."debtors_master WHERE debtor_no=$sel_id";
		db_query($sql,"cannot delete dealer");
		if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'DEALER MAINTENANCE','D',$ip,'DEALER CODE' . $sel_id. "DELETED");
			}
		display_notification(_("Selected dealer has been deleted."));
		unset($_POST['customer_id']);
		$new_customer = true;
		$Ajax->activate('_page_body');
	} //end if Delete Customer
}

check_db_has_sales_types(_("There are no sales types defined. Please define at least one sales type before adding a dealer."));
 
start_form();

if (db_has_customers()) 
{
	start_table("class = 'tablestyle_noborder'");
	start_row();
	customer_list_cells(_("Select a Dealer: "), 'customer_id', null,
		_('New dealer'), true, check_value('show_inactive'));
	check_cells(_("Show inactive:"), 'show_inactive', null, true);
	end_row();
	end_table();
	if (get_post('_show_inactive_update')) {
		$Ajax->activate('customer_id');
		set_focus('customer_id');
	}
} 
else 
{
	hidden('customer_id');
}

if ($new_customer) 
{
	$_POST['custcode'] = '';
	$_POST['CustName'] = $_POST['cust_ref'] = $_POST['address'] = $_POST['tax_id']  = '';
	$_POST['dimension_id'] = 0;
	$_POST['dimension2_id'] = 0;
	$_POST['sales_type'] = -1;
	$_POST['email'] = '';
	$_POST['curr_code']  = get_company_currency();
	$_POST['credit_status']  = 2;
	$_POST['payment_terms']  = $_POST['notes']  = '';

	$_POST['discount']  = $_POST['pymt_discount'] = percent_format(0);
	$_POST['credit_limit']	= price_format($SysPrefs->default_credit_limit());
	$_POST['inactive'] = 0;
	$_POST['sales_channel'] = -1;
	//$_POST['key_file'] = '';
} 
else 
{

	$sql = "SELECT * FROM ".TB_PREF."debtors_master WHERE debtor_no = ".db_escape($_POST['customer_id']);
	$result = db_query($sql,"check failed");

	$myrow = db_fetch($result);

	$_POST['custcode'] = $myrow["debtor_no"];
	$_POST['CustName'] = $myrow["name"];
	$_POST['cust_ref'] = $myrow["debtor_ref"];
	$_POST['address']  = $myrow["address"];
	$_POST['tax_id']  = $myrow["tax_id"];
	$_POST['email']  = $myrow["email"];
	$_POST['dimension_id']  = $myrow["dimension_id"];
	$_POST['dimension2_id']  = $myrow["dimension2_id"];
	$_POST['sales_type'] = $myrow["sales_type"];
	$_POST['curr_code']  = $myrow["curr_code"];
	$_POST['credit_status']  = $myrow["credit_status"];
	$_POST['payment_terms']  = $myrow["payment_terms"];
	$_POST['discount']  = percent_format($myrow["discount"] * 100);
	$_POST['pymt_discount']  = percent_format($myrow["pymt_discount"] * 100);
	$_POST['credit_limit']	= price_format($myrow["credit_limit"]);
	$_POST['notes']  = $myrow["notes"];
	$_POST['inactive'] = $myrow["inactive"];
	$_POST['key_file'] = $myrow["gpg_filename"];
	$_POST['sales_channel'] = $myrow["sales_channel"];;
}

start_outer_table($table_style2, 5);
table_section(1);
table_section_title(_("Name and Address"));
//Added: Changed this form text_row to text_cells
//label_row(_("Customer Code:"),$_POST['custcode']);
if ($new_customer)
{
	text_row(_("Dealer Code:"), 'custcode', $_POST['custcode'], 12, 10);
}
else
{
	label_row(_("Dealer Code:"), $_POST['custcode']);
	hidden('custcode', $_POST['custcode']);	
}
text_row(_("Dealer Name:"), 'CustName', $_POST['CustName'], 40, 80);
text_row(_("Dealer Short Name:"), 'cust_ref', null, 30, 30);
textarea_row(_("Address:"), 'address', $_POST['address'], 35, 5);

email_row(_("E-mail:"), 'email', null, 60, 60);
//text_row(_("GSTNo:"), 'tax_id', null, 40, 40);
hidden('tax_id', $_POST['tax_id']);	


if ($new_customer) 
{
	currencies_list_row(_("Dealer's Currency:"), 'curr_code', $_POST['curr_code']);
} 
else 
{
	label_row(_("Dealer's Currency:"), $_POST['curr_code']);
	hidden('curr_code', $_POST['curr_code']);				
}	
sales_types_list_row(_("Sales Type:"), 'sales_type', $_POST['sales_type']);
sales_groups_list_row(_("Channel Type:"), 'sales_channel', $_POST['sales_channel']);
//hidden('sales_type',1);

table_section(2);

table_section_title(_("Sales"));

percent_row(_("Discount (Not Updatable):"), 'discount', $_POST['discount']);
percent_row(_("Payment Discount (Not Updatable):"), 'pymt_discount', $_POST['pymt_discount']);
amount_row(_("Credit Limit (Not Updatable):"), 'credit_limit', $_POST['credit_limit']);

payment_terms_list_row(_("Payment Terms (Not Updatable):"), 'payment_terms', $_POST['payment_terms']);
//
//Added: Change credit status to view only text item
//This was changed back, and now will be removed from update 
credit_status_list_row(_("Credit Status (Not Updatable):"), 'credit_status', $_POST['credit_status']); 
//label_row(_("Credit Status:"),$_POST['credit_status']);
///
$dim = get_company_pref('use_dimension');
if ($dim >= 1)
	//dimensions_list_row(_("Dimension")." 1:", 'dimension_id', $_POST['dimension_id'], true, " ", false, 1);
	hidden('dimension_id',1);
if ($dim > 1)
	//dimensions_list_row(_("Dimension")." 2:", 'dimension2_id', $_POST['dimension2_id'], true, " ", false, 2);
	hidden('dimension2_id',1);
if ($dim < 1)
	hidden('dimension_id', 0);
if ($dim < 2)
	hidden('dimension2_id', 0);
///////////////////	
//This is trying to get the login userid for logging created by and updated by
//label_row(_("Crated by:"),$_SESSION["wa_current_user"]->username." (". $_SESSION["wa_current_user"]->username.")");
///////////////////

if (!$new_customer)  {
	start_row();
	echo '<td>'._('Dealer branches').':</td>';
  	hyperlink_params_td($path_to_root . "/sales/manage/customer_branches.php",
		'<b>'. (@$_REQUEST['popup'] ?  _("Select or &Add") : _("&Add or Edit ")).'</b>', 
		"debtor_no=".$_POST['customer_id'].(@$_REQUEST['popup'] ? '&popup=1':''));
	end_row();
	start_row();
	echo '<td>'._('Dealer PGP Keys').':</td>';
  	hyperlink_params_td($path_to_root . "/simplex/sales/manage/manage_dealer_key.php",
		'<b>'. (@$_REQUEST['popup'] ?  _("Select or &Add") : _("&Add or Edit ")).'</b>', 
		"debtor_no=".$_POST['customer_id'].(@$_REQUEST['popup'] ? '&popup=1':''));
	end_row();
}

textarea_row(_("General Notes:"), 'notes', null, 35, 5);
record_status_list_row(_("Dealer status:"), 'inactive');
//table_section_title(_("Dealer GPG key"));
//start_row();
//label_row(_("GPG Key file:"), $_POST['key_file']);
//label_row(_("New Key File (.gpg)") . ":", "<input type='file' id='pic' name='pic'>");
//hidden('key_file', $_POST['key_file']);
//echo "<td style='padding-left:20px' align='left'><input name='uploadfile' type='file'></td>";
//label_row(_("New Key File (.gpg)") . ":", "<input type='file' id='uploadfile' name='uploadfile'>");
//submit_cells('upload',_("Upload file"),'', '', true);
//end_row();
end_outer_table(1);

div_start('controls');
if ($new_customer)
{
	submit_center('submit', _("Add New Dealer"), true, '', 'default');
} 
else 
{
	submit_center_first('submit', _("Update Dealer"), 
	  _('Update customer data'), @$_REQUEST['popup'] ? true : 'default');
	submit_return('select', get_post('customer_id'), _("Select this dealer and return to document entry."));
	submit_center_last('delete', _("Delete Customer"), 	  _('Delete dealer data if have been never used'), true);
	  //submit_row('upload',_("Upload file"),'', '', true);
}
div_end();
hidden('popup', @$_REQUEST['popup']);
end_form();
end_page();

?>
