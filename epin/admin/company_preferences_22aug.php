<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SETUPCOMPANY';
global $nonfin_audit_trail;
$path_to_root = "..";
include($path_to_root . "/includes/session.inc");

page(_($help_context = "Application Preference Setup"));

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");

include_once($path_to_root . "/admin/db/company_db.inc");
include_once($path_to_root . "/simplex/includes/ui/our_ui_lists.inc");
//-------------------------------------------------------------------------------------------------

if (isset($_POST['update']) && $_POST['update'] != "")
{

	$input_error = 0;

	if (!check_num('login_tout', 10))
	{
		display_error(_("Login timeout must be positive number not less than 10."));
		set_focus('login_tout');
		$input_error = 1;
	}
	if (strlen($_POST['coy_name'])==0)
	{
		$input_error = 1;
		display_error(_("The company name must be entered."));
		set_focus('coy_name');
	}
	if (strlen($_POST['out_dir'])==0)
	{
		$input_error = 1;
		display_error(_("The output directory must be specified."));
		set_focus('out_dir');
	}
	if (strlen($_POST['in_dir'])==0)
	{
		$input_error = 1;
		display_error(_("The RAW PIN input directory must be specified."));
		set_focus('in_dir');
	}
	if (strlen($_POST['failed_dir'])==0)
	{
		$input_error = 1;
		display_error(_("The Failed EPIN input directory must be specified."));
		set_focus('failed_dir');
	}
	/* if (!strlen($_POST['out_dir'])==0 ) //&& !file_exists($_POST['out_dir'])
	{
		$input_error = 1;
		display_error(_("Specified directory does not exist. Create directory?"));
		
		//if (!file_exists($dir))
		//{
			mkdir ($_POST['out_dir'],0777);
		//}
	}
	if (!strlen($_POST['in_dir'])==0 ) //&& !file_exists($_POST['in_dir'])
	{
		$input_error = 1;
		display_error(_("Specified directory does not exist. Create directory?"));
		
		//if (!file_exists($dir))
		//{
			mkdir ($_POST['in_dir'],0777);
		//}
	} */
	
	if (isset($_FILES['pic']) && $_FILES['pic']['name'] != '')
	{
		$user_comp = user_company();
		$result = $_FILES['pic']['error'];
		$filename = $comp_path . "/$user_comp/images";
		if (!file_exists($filename))
		{
			mkdir($filename);
		}
		$filename .= "/".$_FILES['pic']['name'];

		 //But check for the worst
		if (!in_array((substr(trim($_FILES['pic']['name']),-3)), 
			array('jpg','JPG','png','PNG')))
		{
			display_error(_('Only jpg and png files are supported - a file extension of .jpg or .png is expected'));
			$input_error = 1;
		}
		elseif ( $_FILES['pic']['size'] > ($max_image_size * 1024))
		{ //File Size Check
			display_error(_('The file size is over the maximum allowed. The maximum size allowed in KB is') . ' ' . $max_image_size);
			$input_error = 1;
		}
		elseif ( $_FILES['pic']['type'] == "text/plain" )
		{  //File type Check
			display_error( _('Only graphics files can be uploaded'));
			$input_error = 1;
		}
		elseif (file_exists($filename))
		{
			$result = unlink($filename);
			if (!$result)
			{
				display_error(_('The existing image could not be removed'));
				$input_error = 1;
			}
		}

		if ($input_error != 1)
		{
			$result  =  move_uploaded_file($_FILES['pic']['tmp_name'], $filename);
			$_POST['coy_logo'] = $_FILES['pic']['name'];
			if(!$result) 
				display_error(_('Error uploading logo file'));
		}
	}
	if (check_value('del_coy_logo'))
	{
		$user_comp = user_company();
		$filename = $comp_path . "/$user_comp/images/".$_POST['coy_logo'];
		if (file_exists($filename))
		{
			$result = unlink($filename);
			if (!$result)
			{
				display_error(_('The existing image could not be removed'));
				$input_error = 1;
			}
			else
				$_POST['coy_logo'] = "";
		}
	}
	if ($_POST['add_pct'] == "")
		$_POST['add_pct'] = -1;
	if ($_POST['round_to'] <= 0)
		$_POST['round_to'] = 1;
	if ($input_error != 1)
	{
		update_company_setup($_POST['coy_name'], $_POST['coy_no'], 
			$_POST['gst_no'], $_POST['tax_prd'], $_POST['tax_last'],
			$_POST['postal_address'], $_POST['phone'], $_POST['fax'], 
			$_POST['email'], $_POST['coy_logo'], $_POST['domicile'],
			$_POST['use_dimension'], $_POST['curr_default'], $_POST['f_year'],
			check_value('no_item_list'), check_value('no_customer_list'), 
			check_value('no_supplier_list'), $_POST['base_sales'], 
			check_value('time_zone'), $_POST['add_pct'], $_POST['round_to'],
			$_POST['login_tout'],$_POST['arch_freq'], check_value('dealer_code'), 
			check_value('dealer_name'), check_value('gendate'), $_POST['pin_retention'],$_POST['password'],
			$_POST['out_dir'],$_POST['in_dir'],$_POST['failed_dir'],$_POST['servername'],$_POST['domain'],
			$_POST['emailsubject'] , $_POST['emailbody'], $_POST['confirm_subject'], $_POST['confirm_body'] ,
			$_POST['delivery_subject'], $_POST['delivery_body'] , check_value('alert_reorder_level'),
			 $_POST['alert_repeat'], $_POST['default_delivery_required'], $_POST['maillist']);
		$_SESSION['wa_current_user']->timeout = $_POST['login_tout'];
		if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'APPLICATION PREFERENCES','M',$ip,'APPLICATION SETUP' . $_POST['coy_name']. " MODIFIED ");
			}
		display_notification_centered(_("Company setup has been updated.")); //, $_POST['arch_freq'],
	}
	set_focus('coy_name');
	$Ajax->activate('_page_body');
} /* end of if submit */

//---------------------------------------------------------------------------------------------


start_form(true);
$myrow = get_company_prefs();

$_POST['coy_name'] = $myrow["coy_name"];
$_POST['gst_no'] = $myrow["gst_no"];
$_POST['tax_prd'] = $myrow["tax_prd"];
$_POST['tax_last'] = $myrow["tax_last"];
$_POST['coy_no']  = $myrow["coy_no"];
$_POST['postal_address']  = $myrow["postal_address"];
$_POST['phone']  = $myrow["phone"];
$_POST['fax']  = $myrow["fax"];
$_POST['email']  = $myrow["email"];
$_POST['coy_logo']  = $myrow["coy_logo"];
$_POST['domicile']  = $myrow["domicile"];
$_POST['use_dimension']  = $myrow["use_dimension"];
$_POST['base_sales']  = $myrow["base_sales"];
$_POST['no_item_list']  = $myrow["no_item_list"];
$_POST['no_customer_list']  = $myrow["no_customer_list"];
$_POST['no_supplier_list']  = $myrow["no_supplier_list"];
$_POST['curr_default']  = $myrow["curr_default"];
$_POST['f_year']  = $myrow["f_year"];

$_POST['arch_freq']  = $myrow["archive_frequency"]; $_POST['last_archive'] = $myrow["last_archive"];
$_POST['dealer_code']  = $myrow["filename_fmt_deal_code"];
$_POST['dealer_name']  = $myrow["filename_fmt_deal_name"];
$_POST['gendate']  = $myrow["filename_fmt_gendate"];
$_POST['domain'] = $myrow["ldap_domain"];
$_POST['servername'] = $myrow["ldap_servername"];
$_POST['emailsubject'] = $myrow["msg_subject"];
$_POST['emailbody'] = $myrow["msg_body"];

$_POST['confirm_subject'] = $myrow["confirm_msg_subject"];
$_POST['confirm_body'] = $myrow["confirm_msg_body"];
$_POST['delivery_subject'] = $myrow["delivery_msg_subject"];
$_POST['delivery_body'] = $myrow["delivery_msg_body"];

$_POST['alert_reorder_level']  = $myrow["enable_reorder_alert"];
$_POST['alert_repeat']  = $myrow["alert_repeat"];
$_POST['maillist']  = $myrow["reorder_maillist"];
$_POST['default_delivery_required'] = $myrow['default_delivery_required'];
$_POST['time_zone']  = $myrow["time_zone"];
$_POST['version_id']  = $myrow["version_id"];
$_POST['pin_retention']  = $myrow["pin_file_retention_days"];
$_POST['out_dir'] =  $myrow["pin_file_out_dir"];
$_POST['in_dir'] =  $myrow["pin_file_in_dir"];
$_POST['failed_dir'] =  $myrow["failed_files_dir"];
$_POST['add_pct'] = $myrow['add_pct'];
$_POST['login_tout'] = $myrow['login_tout'];
if ($_POST['add_pct'] == -1)
	$_POST['add_pct'] = "";
$_POST['round_to'] = $myrow['round_to'];	
$_POST['del_coy_logo']  = 0;

start_outer_table($table_style2);

table_section(1);

text_row_ex(_("Name (to appear on reports):"), 'coy_name', 42, 50);
textarea_row(_("Address:"), 'postal_address', $_POST['postal_address'], 35, 6);
text_row_ex(_("Domicile:"), 'domicile', 25, 55);

text_row_ex(_("Phone Number:"), 'phone', 25, 55);
text_row_ex(_("Fax Number:"), 'fax', 25);
email_row_ex(_("Email Address:"), 'email', 25, 55);

text_row_ex(_("Official Company Number:"), 'coy_no', 25);
text_row_ex(_("GSTNo:"), 'gst_no', 25);

currencies_list_row(_("Home Currency:"), 'curr_default', $_POST['curr_default']);
text_row(_("Delivery Required By:"), 'default_delivery_required', $_POST['default_delivery_required'], 6, 6, '', "", _("days"));

//fiscalyears_list_row(_("Fiscal Year:"), 'f_year', $_POST['f_year']);
hidden('f_year','f_year');
archive_freq_list_row(_("Voucher Archiving Frequency:"), 'arch_freq', $_POST['arch_freq']);
label_row(_("Last Archive Date"), $_POST['last_archive']);
table_section_title(_("Filename Template Editor"));
check_row(_("Use Dealer Code:"), 'dealer_code', $_POST['dealer_code']);
check_row(_("Use Dealer Name:"), 'dealer_name', $_POST['dealer_name']);
check_row(_("Use Date of Generation:"), 'gendate', $_POST['gendate']);

table_section_title(_("Messaging preferences"));
text_row_ex(_("New order Email Subject:"), 'emailsubject', 42, 50);
textarea_row(_("New order  Email Body:"), 'emailbody', $_POST['emailbody'], 25, 2);

text_row_ex(_("Order Confirmation Email Subject:"), 'confirm_subject', 42, 50);
textarea_row(_("Order Confirmation  Email Body:"), 'confirm_body', $_POST['confirm_body'], 25, 2);

text_row_ex(_("Order Delivery   Email Subject:"), 'delivery_subject', 42, 50);
textarea_row(_("Order Delivery    Email Body:"), 'delivery_body', $_POST['delivery_body'], 25, 2);

check_row(_("Enable Reorder level Alerts"), 'alert_reorder_level', null);
text_row_ex(_("Reorder Level Alert Repeat:"), 'alert_repeat', 10, 10,  $_POST['alert_repeat']);
so_mailing_list_row('Reorder level Mailing List', 'maillist', $_POST['maillist'], ST_SALESORDER);
table_section(2);

//text_row_ex(_("Tax Periods:"), 'tax_prd', 10, 10, '', null, null, _('Months.'));
hidden('tax_prd','tax_prd');
//text_row_ex(_("Tax Last Period:"), 'tax_last', 10, 10, '', null, null, _('Months back.'));
hidden('tax_last','tax_last');

label_row(_("Company Logo:"), $_POST['coy_logo']);
label_row(_("New Company Logo (.jpg)") . ":", "<input type='file' id='pic' name='pic'>");
check_row(_("Delete Company Logo:"), 'del_coy_logo', $_POST['del_coy_logo']);

//number_list_row(_("Use Dimensions:"), 'use_dimension', null, 0, 2);
hidden('use_dimension','use_dimension');
//sales_types_list_row(_("Base for auto price calculations:"), 'base_sales', $_POST['base_sales'], false,
   // _('No base price list') );
	hidden('base_sales','base_sales');
text_row_ex(_("Add Price from Std Cost:"), 'add_pct', 10, 10, '', null, null, "%");
$curr = get_currency($_POST['curr_default']);
text_row_ex(_("Round to nearest:"), 'round_to', 10, 10, '', null, null, $curr['hundreds_name']);

check_row(_("Search Item List"), 'no_item_list', null);
check_row(_("Search Customer List"), 'no_customer_list', null);
check_row(_("Search Supplier List"), 'no_supplier_list', null);
label_row("", "&nbsp;");
check_row(_("Time Zone on Reports"), 'time_zone', $_POST['time_zone']);
text_row_ex(_("Login Timeout:"), 'login_tout', 10, 10, '', null, null, _('seconds'));
label_row(_("Version Id"), $_POST['version_id']);
text_row_ex(_("Dealer PIN file retention:"), 'pin_retention', 10, 10, '', null, null, _('Days.'));
//text_row_ex(_("PIN Output Directory:"), 'out_dir', 45, 10, '', null, null, "");
$_POST['password'] = "";
label_cell(_("UVC Decrypt Password:"));
label_cell("<input type='password' name='password' size=22 maxlength=20 value='" . $_POST['password'] . "'>");

text_row_ex(_("RAW PIN Input Directory:"), 'in_dir', 42, 50);
text_row_ex(_("PIN Output Directory:"), 'out_dir', 42, 50);
text_row_ex(_("Failed PIN Directory:"), 'failed_dir', 42, 50);
//label_row(_("PIN Output Directory") . ":", "<input type='file' id='out_dir' name='out_dir'>");
table_section_title(_("LDAP Support"));
text_row_ex(_("LDAP Servername:"), 'servername', 30, 50);
text_row_ex(_("Domain name"), 'domain', 30, 50);

end_outer_table(1);

hidden('coy_logo', $_POST['coy_logo']);
submit_center('update', _("Update"), true, '',  'default');

end_form(2);
//-------------------------------------------------------------------------------------------------

end_page();

?>
