<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SUPPTRANSVIEW';

$path_to_root="../..";
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/admin/db/maintenance_db.inc");
include_once($path_to_root ."/admin/db/company_db.inc");

//$myrow = get_company_prefs();
//echo $myrow;
$rawpin_in_path =  RAWPIN_IN_PATH ; //get_pin_file_in_dir();
//echo 'raw p =' . $rawpin_in_path;

if (get_post('view')) {
	$filename = $rawpin_in_path . get_post('rawpins');
	if (in_ajax()) 
		$Ajax->popup( $filename );
	else {
	    header('Content-type: application/octet-stream');
    	header('Content-Length: '.filesize($filename));
		header("Content-Disposition: inline; filename=$filename");
    	readfile($filename);
		exit();
	}
};

if (get_post('download')) {
	download_file($rawpin_in_path . get_post('rawpins'));
	exit;
}

page(_($help_context = "Raw E-PIN Import"), false, false, '', '');

function getFilenameWithoutExt($filename){
    $pos = strripos($filename, '.');
    if($pos === false){
        return $filename;
    }else{
        return substr($filename, 0, $pos);
    }
}
//check_paths();

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
function get_pin_file_in_dir()
{
		$sql2 = "SELECT pin_file_in_dir from "
			.TB_PREF."company  
			WHERE coy_code=1";
			
	$sql_b = db_query($sql2);
	$result2 = db_fetch($sql_b);
	return $result2['pin_file_in_dir'];
}
function updatestatus($filename)
{
		$sql = "UPDATE "
			.TB_PREF."epin_load_jobs  
			SET status = 'L',error_code=0,error_message='',logon_user=" . db_escape(strtoupper($_SESSION["wa_current_user"]->loginname)). "
			 WHERE filename=". db_escape($filename)  ;
			
		db_query($sql, "Cannot update file status for failed file");

}
function is_failedupload($filename)
{

		$sql2 = "SELECT COUNT(1) from "
			.TB_PREF."epin_load_jobs  
			WHERE status ='F' and filename=". db_escape(getFilenameWithoutExt($filename) . ".txt")  ;
			
	$result = db_query($sql2,"check failed");
//	display_error(_("here4:" . $sql2)); 
	$row = db_fetch_row($result);
	return ($row[0] > 0);
/* 		if ($row[0] > 0) 
			return true;
		else
			return false;
 */
		
}
function get_backup_file_combo()
{
	global $path_to_root, $Ajax;
	$rawpin_in_path =  RAWPIN_IN_PATH; //get_pin_file_in_dir();
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

function compress_list_row($label, $name, $value=null)
{
	$ar_comps = array('no'=>_("No"));

    if (function_exists("gzcompress"))
    	$ar_comps['zip'] = "zip";
    if (function_exists("gzopen"))
    	$ar_comps['gzip'] = "gzip";

	echo "<tr><td>$label</td><td>";
	echo array_selector('comp', $value, $ar_comps);
	echo "</td></tr>";
}

function download_file($filename)
{
    if (empty($filename) || !file_exists($filename))
    {
        return false;
    }
    $saveasname = basename($filename);
    header('Content-type: application/octet-stream');
   	header('Content-Length: '.filesize($filename));
   	header('Content-Disposition: attachment; filename="'.$saveasname.'"');
    readfile($filename);

    return true;
}

$db_name = $_SESSION["wa_current_user"]->company;
$conn = $db_connections[$db_name];

if (get_post('creat')) {
	generate_backup($conn, get_post('comp'), get_post('comments'));
	$Ajax->activate('rawpins');
};


if (get_post('deldump')) {
	if (unlink($rawpin_in_path . get_post('rawpins'))) {
		display_notification(_("File successfully deleted.")." "
				. _("Filename") . ": " . get_post('rawpins'));
		$Ajax->activate('rawpins');
	}
	else
		display_error(_("Can't delete EPIN file."));
};

if (get_post('upload'))
{
	$tmpname = $_FILES['uploadfile']['tmp_name'];
	$fname = $_FILES['uploadfile']['name'];
	$filewoutextension = getFilenameWithoutExt($fname) . ".txt";
	
	if (!preg_match("/.unl(.zip|.gz)?$/", $fname))
		display_error(_("You can only upload *.unl raw files"));
	elseif (is_uploaded_file($tmpname)) {
		rename($tmpname, $rawpin_in_path  . "\\".$fname);
		$username = strtoupper($_SESSION["wa_current_user"]->loginname);
		if(isset($fname) && $fname !="")
		{
			
			//if file was previously loaded  and failed
			if (is_failedupload ($fname) ) {
				updatestatus(getFilenameWithoutExt($fname) . ".txt");
				display_notification(_("File was previously loaded with errors"));				
			}
			else{
					//display_error(_("here3")); 
					begin_transaction();
					$sql = 'BEGIN pkg_mnt_epin.ins_pin_upload(:filename,:logon_user); END;';
					$result = $stmt = oci_parse($db,$sql);
					//  Bind the input parameter
					oci_bind_by_name($stmt,':filename',$filewoutextension,255);
					oci_bind_by_name($stmt,':logon_user',$username,30);
					
					oci_execute($stmt, OCI_DEFAULT);
					$err = oci_error($result);
					if( $err ){
								$db_err ="ERROR";
								//oci_rollback($db);
								$pos = strrpos($err['message'],"ORA-00001");
								if( $pos > 0)
								{
									display_error( "Failed uploading to PIN File directory - Duplicate file exists");
								}
								else
									display_error( "Failed uploading to PIN File directory error code:" . $err['code']);
								cancel_transaction();
								return;
								
					}
					else
					    {
						
											/*****  FTP Starts 
							//get FTP access parameters
							$host = DB_HOST;
							$user = DB_USER;
							$pass = DB_PASS;
							$destDir = '/epin_data/epin_io_dir/incoming';
							//$workDir = '../../tmp'; // define this as per local system
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
								   echo "Connected to $host, for user $user".".....";
							   }
							   
							   // upload the file to the path specified
								$upload = ftp_put($conn_id, $destDir."/".$fname, $rawpin_in_path  . "\\".$fname, FTP_BINARY);
								
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
								
								/* if (unlink($rawpin_in_path . $fname)) {
									//display_notification(_("File successfully deleted.")." ". _("Filename") . ": " . get_post('rawpins'));
									//$Ajax->activate('rawpins');
								}
								else
									display_error(_("Cannot delete uploaded file from working directory -- manual deletion recommended")); 
									
							 FTP Ends */
						}
					$ip = preg_quote($_SERVER['REMOTE_ADDR']);
					add_nonfin_audit_trail(0,0,0,0,'DATAFILE IMPORT','A',$ip,'RAW DATAFILE :' . $fname . " WAS UPLOADED FROM " . $fname);
					commit_transaction(); 
			}
			unset($fname);
			unset($tmpname);
		}
		display_notification( "File uploaded to PIN File directory");
		$Ajax->activate('rawpins');
	} else
		display_error(_("File was not uploaded into the system."));
}
//-------------------------------------------------------------------------------
start_form(true, true);
start_outer_table($table_style2);

 table_section(1);
/* table_section_title(_("Create backup"));
	textarea_row(_("Comments:"), 'comments', null, 30, 8);
	compress_list_row(_("Compression:"),'comp');
	vertical_space("height='20px'");
	submit_row('creat',_("Create Backup"), false, "colspan=2 align='center'", '', 'process'); */ 
table_section(2);
table_section_title(_("EPIN File Import"));

	start_row();
	echo "<td style='padding-left:20px'align='left'>".get_backup_file_combo()."</td>";
	echo "<td valign='top'>";
	start_table();
	//submit_row('view',_("View Raw File"), false, '', '', true);
	//submit_row('download',_("Download Backup"), false, '', '', false);
	//submit_row('restore',_("Restore Backup"), false, '','', 'process');
	//submit_js_confirm('restore',_("You are about to restore database from backup file.\nDo you want to continue?"));

	submit_row('deldump', _("Delete Raw File"), false, '','', true);
	// don't use 'delete' name or IE js errors appear
	submit_js_confirm('deldump', sprintf(_("You are about to remove selected EPIN file.\nDo you want to continue ?")));
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
