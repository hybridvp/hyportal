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
include($path_to_root . "/simplex/includes/ftp.class.inc");

//$myrow = get_company_prefs();
//echo $myrow;
$rawpin_in_path =  RAWPIN_IN_PATH ; //get_pin_file_in_dir();
$failed_pin_path = get_failed_file_in_dir();
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

page(_($help_context = "Failed File Upload"), false, false, '', '');

function getFilenameWithoutExt($filename){
    $pos = strripos($filename, '.');
    if($pos === false){
        return $filename;
    }else{
        return substr($filename, 0, $pos);
    }
}
//check_paths();
//---------------------------------------------------------------------------------------------
function delete_file($filename)
{
	$ftp_server = DB_HOST;
	$ftp_user = DB_USER;
	$ftp_passwd = DB_PASS;
	
	$failed_pin_path = get_failed_file_in_dir();
	$ftp =& new FTP();
	if ($ftp->connect($ftp_server)) {
		if ($ftp->login($ftp_user,$ftp_passwd)) {
			$ftp->chdir($failed_pin_path);
			$ftp->delete($filename);
		} else {
			//echo "login failed: ";
			//print_r($ftp->error_no);
			//print_r($ftp->error_msg);
			$ftp->disconnect();
			display_error(_("Cannot complete file removal ->login falied " .$ftp->error_no . $ftp->error_msg. " manual removal suggested"));
			
			return false;
		}
		$ftp->disconnect();
		return true;
		//print_r($ftp->lastLines);
	} else {
		//echo "connection failed: ";
		//print_r($ftp->error_no);
		//print_r($ftp->error_msg);
			$ftp->disconnect();
			display_error(_("Cannot complete file removal -> " .$ftp->error_no . $ftp->error_msg. " manual removal suggested"));
			return false;

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
function get_failed_file_in_dir()
{
		$sql2 = "SELECT failed_files_dir from "
			.TB_PREF."company  
			WHERE coy_code=1";
			
	$sql_b = db_query($sql2);
	$result2 = db_fetch($sql_b);
	return $result2['failed_files_dir'];
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

		$sql2 = "SELECT COUNT(*) from "
			.TB_PREF."epin_load_jobs  
			WHERE status ='F' and filename=". db_escape(getFilenameWithoutExt($filename) . ".txt")  ;
			
	$result = db_query($sql2,"check failed");
//	display_error(_("here4:" . $sql2)); 
	$row = db_fetch_row($result);
	return ($row[0] > 0);
		
}

function get_backup_file_combo()
{
	global $path_to_root, $Ajax;
	//$rawpin_in_path =  '/epin_data/epin_io_dir/incoming' ; //RAWPIN_IN_PATH; //get_pin_file_in_dir();
	$ar_files = array();
    default_focus('rawpins');

			$host = DB_HOST;
			$user = DB_USER;
			$pass = DB_PASS;
			$failed_pin_path = get_failed_file_in_dir();
	
			$ftp =& new FTP();
			if ($ftp->connect($host)) {
				if ($ftp->login($user,$pass)) {
					$ftp->chdir($failed_pin_path);
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
									
									/*// open connection
									$conn_id = ftp_connect($host) or die ("Cannot initiate connection to	 host");
									
									// login with username and password
									$login_result = ftp_login($conn_id, $user, $pass);			 
									// check connection and login result
									if ((!$conn_id) || (!$login_result)) {
										   echo "FTP connection has encountered an error!";
										   echo "Attempted to connect to $host for user $user....";
										   exit;
									   } else {
										   echo "Connected to $host, for user $user".".....dir=" .$destDir ;
									   }
									   
										$files = ftp_rawlist($conn_id, $destDir);
										foreach ($files as $file) {
											$str = split(" ", $file);
											$filelist[] = $str[count($str)-1];
										}
										// close the FTP stream
										ftp_close($conn_id);
										
										sort ($filelist);
										$opt_files = "";
										foreach ($filelist as $file) {
										$opt_files .= "<option value='$file'>$file</option>";
									}*/
	
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
	/* if (unlink($rawpin_in_path . get_post('rawpins'))) {
		display_notification(_("File successfully deleted.")." "
				. _("Filename") . ": " . get_post('rawpins'));
		$Ajax->activate('rawpins');
	} */
	if(delete_file(get_post('rawpins'))) {
		display_notification(_("File successfully deleted.")." "
				. _("Filename") . ": " . get_post('rawpins'));
				$Ajax->activate('rawpins');
	}
	else
		display_error(_("Can't delete file."));
};


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
table_section_title(_("Delete Failed Files"));

	start_row();
	echo "<td style='padding-left:20px'align='left'>".get_backup_file_combo()."</td>";
	echo "<td valign='top'>";
	start_table();
	submit_row('view',_("View Raw File"), false, '', '', true);
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
//echo "<td style='padding-left:20px' align='left'><input name='uploadfile' type='file'></td>";
//	submit_cells('upload',_("Upload file"),'', '', true);
end_row();
end_outer_table();

end_form();

end_page();
?>
