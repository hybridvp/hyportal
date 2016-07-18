<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_CUSTOMER';
global $nonfin_audit_trail;
$path_to_root="../../..";
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/admin/db/maintenance_db.inc");
include_once($path_to_root ."/admin/db/company_db.inc");
include($path_to_root . "/simplex/includes/ftp.class.inc");
//$myrow = get_company_prefs();
//echo $myrow;
page(_($help_context = "Dealer PGP Keys"), @$_REQUEST['popup']);
//-----------------------------------------------------------------------------------------------

check_db_has_customers(_("There are no customers defined in the system. Please define a customer to add customer branches."));

check_db_has_sales_people(_("There are no sales people defined in the system. At least one sales person is required before proceeding."));

check_db_has_sales_areas(_("There are no sales areas defined in the system. At least one sales area is required before proceeding."));

check_db_has_shippers(_("There are no shipping companies defined in the system. At least one shipping company is required before proceeding."));

check_db_has_tax_groups(_("There are no tax groups defined in the system. At least one tax group is required before proceeding."));

simple_page_mode(true);
//-----------------------------------------------------------------------------------------------

if (isset($_GET['debtor_no']))
{
	$_POST['customer_id'] = strtoupper($_GET['debtor_no']);
	
}

$dealerkey_path =  DEALERKEY_PATH ;
global $nonfin_audit_trail;

//page(_($help_context = "Raw E-PIN Import"), false, false, '', '');

function getFilenameWithoutExt($filename){
    $pos = strripos($filename, '.');
    if($pos === false){
        return $filename;
    }else{
        return substr($filename, 0, $pos);
    }
}
function check_paths()
{
	if (!file_exists($rawpin_in_path)) {
		display_error (_("Raw File paths have not been set correctly.") 
			._("Please contact System Administrator.")."<br>" 
			. _("cannot find raw file directory") . " - " . + "old pinpath =" .RAWPIN_IN_PATH . "<br>" );
		end_page();
		exit;
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
function updatestatus($filename)
{
		$sql = "UPDATE "
			.TB_PREF."epin_load_jobs  
			SET status = 'L',error_code=0,error_message='',logon_user=" . db_escape(strtoupper($_SESSION["wa_current_user"]->loginname)). "
			 WHERE filename=". db_escape($filename)  ;
			
		db_query($sql, "Cannot update file status for failed file");

}

function get_backup_file_combo()
{
	global $path_to_root, $Ajax;
	$rawpin_in_path =  DEALERKEY_PATH; //get_pin_file_in_dir();
	$ar_files = array();
    default_focus('rawpins');
    $dh = opendir($rawpin_in_path);
	while (($file = readdir($dh)) !== false)
		$ar_files[] = $file;
	closedir($dh);

    rsort($ar_files);
	$opt_files = "";
    foreach ($ar_files as $file)
		if (preg_match("/.unl(.zip|.gz)?$/", $file))
    		$opt_files .= "<option value='$file'>$file</option>";

	$selector = "<select name='rawpins' size=2 style='height:160px;min-width:230px'>$opt_files</select>";

	$Ajax->addUpdate('rawpins', "_rawpins_sel", $selector);
	$selector = "<span id='_rawpins_sel'>".$selector."</span>\n";

	return $selector;
}
function get_backup_file_combo_()
{
	global $path_to_root, $Ajax;
	//$rawpin_in_path =  '/epin_data/epin_io_dir/incoming' ; //RAWPIN_IN_PATH; //get_pin_file_in_dir();
	$ar_files = array();
    default_focus('rawpins');
			$host = DB_HOST;
			$user = DB_USER;
			$pass = DB_PASS;
			$archive_dir = get_dealer_key_dir();
	
			$ftp =& new FTP();
			if ($ftp->connect($host)) {
				if ($ftp->login($user,$pass)) {
					$ftp->chdir($archive_dir);
					$files = $ftp->rawlist() ;
										foreach ($files as $file) {
											$str = split(" ", $file);
											$filelist[] = $str[count($str)-1];
										}
									
										sort ($filelist);
										$opt_files = "";
										foreach ($filelist as $file) {
										$opt_files .= "<option value='$file'>$file</option>";
										}
		
				} else {
					echo "login failed: ";
					print_r($ftp->error_no);
					print_r($ftp->error_msg);
				}
				$ftp->disconnect();
				//print_r($ftp->lastLines);
			} else {
				echo "connection failed: ";
				print_r($ftp->error_no);
				print_r($ftp->error_msg);
			}
				$selector = "<select name='rawpins' size=2 style='height:160px;min-width:230px'>$opt_files</select>";
			
				//$Ajax->addUpdate('rawpins', "_rawpins_sel", $selector);
				$selector = "<span id='_rawpins_sel'>".$selector."</span>\n";
			
				return $selector;
			
}



$db_name = $_SESSION["wa_current_user"]->company;
$conn = $db_connections[$db_name];

if (get_post('creat')) {
	generate_backup($conn, get_post('comp'), get_post('comments'));
	//$Ajax->activate('rawpins');
};


if (get_post('deldump')) {
	if (unlink($dealerkey_path . get_post('rawpins'))) {
		display_notification(_("File successfully deleted.")." "
				. _("Filename") . ": " . get_post('rawpins'));
		//$Ajax->activate('rawpins');
	}
	else
		display_error(_("Can't delete EPIN file."));
};

if (get_post('upload'))
{
	$tmpname = $_FILES['uploadfile']['tmp_name'];
			$fname = $_FILES['uploadfile']['name'];
			//$filewoutextension = getFilenameWithoutExt($fname) . ".txt";
			$pos = strrpos($fname," ");
			if (!preg_match("/.asc(.zip|.gz)?$/", $fname) )
				display_error(_("You can only upload *.asc files ." .$pos));		
				
				
			elseif ( $pos > 0)
				display_error(_("Filename must not contain spaces")); 
			if (!preg_match ("#^[-A-Za-z' .]*$#", $fname)) { 
			  display_error(_("Filename must not contain special characters")); 
			} 
				
			elseif (is_uploaded_file($tmpname)) {
				rename($tmpname, $dealerkey_path  . "\\".$fname);
				$username = strtoupper($_SESSION["wa_current_user"]->loginname);
				if(isset($fname) && $fname !="")
				{
					/*****/
					$host = DB_HOST;
					$user = DB_USER;
					$pass = DB_PASS;
					$destDir = get_dealer_key_dir (); //'/epin_data/epin_io_dir/incoming';
					$conn_id = ftp_connect($host) or die ("Cannot initiate connection to host");
					$login_result = ftp_login($conn_id, $user, $pass);			 
					if ((!$conn_id) || (!$login_result)) {
						   echo "FTP connection has encountered an error!";
						   echo "Attempted to connect to $host for user $user....";
						   exit;
					   } else {
						   echo "Connected to $host";
					   }
						$upload = ftp_put($conn_id, $destDir."/".$fname, $dealerkey_path  . "\\".$fname, FTP_BINARY);
						if (!$upload) {
						  //echo "FTP upload has encountered an error!";
						  display_error(_("FTP upload has encountered an error!"));
						  
						} else {
						
						  echo "Uploaded file with name $fname to $host";
						  	$sql_q = "UPDATE " .TB_PREF."debtors_master SET gpg_filename = " . db_escape($fname)
									  . ",flg_gpg_added='N' WHERE upper(debtor_no) = upper(" . db_escape($_POST['dealer']) . ")" ;
							 db_query($sql_q,"The pgp filename could not be updated");

						$ip = preg_quote($_SERVER['REMOTE_ADDR']);
						add_nonfin_audit_trail(0,0,0,0,'DEALER MAINTENANCE','A',$ip,'GPG DATAFILE :' . $fname . " WAS UPLOADED FROM " . $fname);

						}

						ftp_close($conn_id);
					
						// delete local copy of uploaded file
						//if (unlink($dealerkey_path . $fname)) {
							//display_notification(_("File successfully deleted.")." ". _("Filename") . ": " . get_post('rawpins'));
						//}
						//else
						//{
						//	display_error(_("Cannot delete uploaded file from working directory -- manual deletion recommended")); 
						//}
					/*****/
							 //begin_transaction();
							 
							 
							 /*$sql = 'BEGIN pkg_mnt_epin.add_upd_gpg(:filename,:dealer_no); END;';
							$result = $stmt = oci_parse($db,$sql);
							oci_bind_by_name($stmt,':filename',$fname,255);
							oci_bind_by_name($stmt,':dealer_no',$_POST['dealer'],30);
							
							oci_execute($stmt, OCI_DEFAULT);
							$err = oci_error($result);
							if( $err ){
										$pos = strrpos($err['message'],"ORA-01403");
										if( $pos > 0)
										{
											display_error( "Failed uploading to GPG File directory - no dealer found");
										}
										else
											display_error( "Failed uploading to GPG File directory error code:" . $err['code']);
										cancel_transaction();
										return;
										
							} */
					//commit_transaction(); 
					unset($fname);
					unset($tmpname);
				}
				display_notification( "File successfully uploaded ." . $_POST['dealer'] );
				$Ajax->activate('rawpins');
			} else
				display_error(_("File was not uploaded into the system."));

}
//-------------------------------------------------------------------------------
start_form(true, true);
start_outer_table($table_style2);

 table_section(1);

table_section(2);
table_section_title(_("Dealer PGP Keys"));

	start_row();
	hidden('dealer', $_GET['debtor_no']);
	echo "<td style='padding-left:20px'align='left'>".get_backup_file_combo()."</td>";
	echo "<td valign='top'>";
	start_table();
	submit_row('deldump', _("Delete PGP Key File"), false, '','', true);
	// don't use 'delete' name or IE js errors appear
	submit_js_confirm('deldump', sprintf(_("You are about to remove selected PGP Key file.\nDo you want to continue ?")));
	end_table();
	echo "</td>";
	end_row();
start_row();
echo "<td style='padding-left:20px' align='left'><input name='uploadfile' type='file'></td>";
	submit_cells('upload',_("Upload file"),'', '', true);
end_row();
end_outer_table();

end_form();

end_page();
?>
