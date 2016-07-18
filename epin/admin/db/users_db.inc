<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
include_once($path_to_root . "/simplex/includes/ldaplogin.inc");
include_once($path_to_root . "/admin/db/company_db.inc");
function add_user($seq, $user_id, $real_name, $password, $phone, $email, $role_id, 
	$language, $profile, $rep_popup, $pos, $loc)
{
    //pass sequence number using users_id_seq
	$sql = "INSERT INTO ".TB_PREF."users (id,user_id, real_name, password"
		.", phone, email, role_id, language, pos, print_profile, rep_popup, default_location)
		VALUES (".db_escape($seq).", ".db_escape($user_id).", 
		".db_escape($real_name).", ".db_escape($password) .",nvl(".db_escape($phone)
		.",' '),".db_escape($email).", ".db_escape($role_id).", ".db_escape($language)
		.", ".db_escape($pos).",nvl(".db_escape($profile).", ' '),".db_escape($rep_popup)
		.", ".db_escape($loc)." )";

	db_query($sql, "could not add user for $user_id");
}

//-----------------------------------------------------------------------------------------------

function update_user_password($id, $user_id, $password)
{
	$sql = "UPDATE ".TB_PREF."users SET password=".db_escape($password) . ",
		user_id = ".db_escape($user_id). " WHERE id=".db_escape($id);

	db_query($sql, "could not update user password for $user_id");
}

//-----------------------------------------------------------------------------------------------

function update_user($id, $user_id, $real_name, $phone, $email, $role_id, 
	$language, $profile, $rep_popup, $pos, $loc)
{
	$sql = "UPDATE ".TB_PREF."users SET real_name=".db_escape($real_name).
	", phone=".db_escape($phone).",
		email=".db_escape($email).",
		role_id=".db_escape($role_id).",
		language=".db_escape($language).",
		print_profile=nvl(".db_escape($profile).",' '),
		rep_popup=".db_escape($rep_popup).",
		pos=".db_escape($pos).",
		default_location=".db_escape($loc).",
		user_id = " . db_escape($user_id)
		. " WHERE id=" . db_escape($id);
	db_query($sql, "could not update user for $user_id");
}

//-----------------------------------------------------------------------------------------------

function update_user_display_prefs($id, $price_dec, $qty_dec, $exrate_dec, 
	$percent_dec, $showgl, $showcodes, $date_format, $date_sep, $tho_sep, 
	$dec_sep, $theme, $pagesize, $show_hints, $profile, $rep_popup, $query_size, 
	$graphic_links,	$lang, $stickydate, $startup_tab)
{
	$sql = "UPDATE ".TB_PREF."users SET
		prices_dec=".db_escape($price_dec).",
		qty_dec=".db_escape($qty_dec).",
		rates_dec=".db_escape($exrate_dec).",
		percent_dec=".db_escape($percent_dec).",
		show_gl=".db_escape($showgl).",
		show_codes=".db_escape($showcodes).",
		date_format=".db_escape($date_format).",
		date_sep=".db_escape($date_sep).",
		tho_sep=".db_escape($tho_sep).",
		dec_sep=".db_escape($dec_sep).",
		theme=".db_escape($theme).",
		page_size=".db_escape($pagesize).",
		show_hints=".db_escape($show_hints).",
		print_profile=nvl(".db_escape($profile).",' '),
		rep_popup=".db_escape($rep_popup).",
		query_size=".db_escape($query_size).",
		graphic_links=".db_escape($graphic_links).",
		language=".db_escape($lang).",
		sticky_doc_date=".db_escape($stickydate).",
		startup_tab=".db_escape($startup_tab)."
		WHERE id = ".db_escape($id);

	db_query($sql, "could not update user display prefs for $id");
}

//-----------------------------------------------------------------------------------------------


function get_users($all=false)
{  //Laolu --do not show System Administrators
	$sql = "SELECT u.*, r.role FROM ".TB_PREF."users u, ".TB_PREF."security_roles r
		WHERE u.role_id=r.id AND u.role_id !=2";
	if (!$all) $sql .= " AND u.inactive <>1";
	
	return db_query($sql, "could not get users");
}

//-----------------------------------------------------------------------------------------------

function get_user($id)
{
	$sql = "SELECT * FROM ".TB_PREF."users WHERE id=".db_escape($id);

	$result = db_query($sql, "could not get user $id");

	return db_fetch($result);
}

//-----------------------------------------------------------------------------------------------
//	This function is necessary for admin prefs update after upgrade from 2.1
//
function get_user_by_login($user_id)
{
	$sql = "SELECT * FROM ".TB_PREF."users WHERE user_id=".db_escape($user_id);

	$result = db_query($sql, "could not get user $user_id");

	return db_fetch($result);
}

//-----------------------------------------------------------------------------------------------

function delete_user($id)
{
	$sql="DELETE FROM ".TB_PREF."users WHERE id=".db_escape($id);

	db_query($sql, "could not delete user $id");
}

//-----------------------------------------------------------------------------------------------

function get_user_for_login($user_id, $password, $plain_password)
{
    global $db ;
	set_global_connection();
	global $use_ldap ;
/////////////////////////////////		
//		$result = oci_parse($db, "ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD hh24:mi:ss'");
//		$ok=oci_execute($result) ;
////////////////////////////////	

//echo "<br> in user4login user_id is ". $user_id." and password is ".$password. "<br>" ;
// do not exclude inactive records or you lost access after source upgrade
// on sites using pre 2.2 database
if ($use_ldap)
{
	// Set some basic Ldap Details//
	$ldap = new ldap();
	$com_pref = get_company_prefs();
	
	$ldap->s_Host($com_pref["ldap_servername"]);
	$ldap->s_Domain($com_pref["ldap_domain"]);
	$ldap->s_LdapSecure(false);
	
	$ldap->s_User($user_id);
    $ldap->s_Pass($plain_password);

	 if($ldap->g_User() && $ldap->g_Pass()){
        if($ldap->LdapConn()){
			//echo "servername=" . $com_pref["ldap_servername"] . "<br>";
			//echo "domain=" . $com_pref["ldap_domain"];
			//echo "Connected OK" . ' with username=' . $ldap->g_Pass(). '<br>' ;
            if($ldap->LdapBind()){
			
				//echo "Binding to Server OK" . '<br>' ;
				$_SESSION["LDAP_fullname"] = $ldap->getAttribute('cn');
				if( !isset($_SESSION["LDAP_fullname"] ) || $_SESSION["LDAP_fullname"] =="" )
				{
					$_SESSION['ldap_error']  = "Invalid LDAP credentials";
                	return db_query("select 1 as error_code from dual", "Invalid LDAP credentials for $user_id");
				}
				
				$sql = "SELECT * FROM ".TB_PREF."users WHERE upper(user_id) = upper(".db_escape($user_id) . ")";
				//				." AND"						." password=".db_escape($password);
				echo $sql;
					return db_query($sql, "User $user_id not assigned any role in EPIN");
					
				//$role = "";
				// foreach($ldap->getMemberships() as $key => $value){
                //    $role .= $value . ",";
                //}
				/*$myrow = array( "user_id" => $ldap->getAttribute('id') ,
								"real_name" => $ldap->getAttribute('cn'),
								"email" =>  $ldap->getAttribute('mail'),
								"role_id" => $role,
								"language" => "en-GB",
								"date_format" => 1,
								"date_sep" => 0,
								"tho_sep" => 0,
								"dec_sep" => 0,
								"theme" => "default",
								"page_size" => "Letter",
								"prices_dec" => 2,
								"qty_dec" => 2 ,
								"rates_dec" => 4,
								"percent_dec" => 1,
								"show_gl" => 1,
								"show_codes" => 0,
								"show_hints" => 0,
								"query_size" => 10,
								"graphic_links" => 1,
								"pos" => 1,
								"print_profile" => "",
								"rep_pop" => 1,
								"sticky_doc_date" => 0,
								"startup_tab" => "orders",
								"inactive"=> 0,
								"default_location" => "DEF" 
							);*/

            }else{
				$_SESSION['ldap_error']  = "Invalid LDAP credentials";
                return db_query("select 1 as error_code from dual", "Invalid LDAP credentials for $user_id");
				
            }
        }else{
            //return "ldap server not available";
			$_SESSION['ldap_error']  = "LDAP server not available";
			return db_query("select 2 as error_code from dual", "ldap server not available");
        }
    }else{
        //return "blank fields detected";
		return db_query("select 3 as error_code from dual", "Blank fields detected");
    }   


}
else
	{

		$sql = "SELECT * FROM ".TB_PREF."users WHERE user_id = ".db_escape($user_id)." AND"
		." password=".db_escape($password);

//     echo "<br> in user4login sql is ". $sql. "<br>" ;

	return db_query($sql, "could not get validate user login for $user_id");
	}

}

//-----------------------------------------------------------------------------------------------

function update_user_visitdate($user_id)
{
	$sql = "UPDATE ".TB_PREF."users SET last_visit_date='". date("Y-m-d H:i:s") ."'
		WHERE user_id=".db_escape($user_id);

	db_query($sql, "could not update last visit date for user $user_id");
}

//-----------------------------------------------------------------------------------------------
function check_user_activity($id) 
{
	$sql = "SELECT COUNT(*) FROM ".TB_PREF."audit_trail WHERE audit_trail.user="
		. db_escape($id);
	$result = db_query($sql,"Cant check user activity");
	$ret = db_fetch($result);

	return $ret[0];
}

//-----------------------------------------------------------------------------------------------
function show_users_online()
{
	global $show_users_online;
	
	if (!isset($show_users_online) || $show_users_online == 0 || !defined('TB_PREF') || !isset($_SESSION['get_text']))
		return "";
	$result = db_query("SELECT table_name from user_tables where table_name LIKE '%".TB_PREF."useronline%'");	
	if (db_num_rows($result) == 1)
	{
		$timeoutseconds = 120;

		$timestamp=time();
		$timeout=$timestamp-$timeoutseconds;
		/*
		This will find out if user is from behind proxy server. 
		In that case, the script would count them all as 1 user.
		This function tryes to get real IP address.
		*/
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED'];
		}
		elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_FORWARDED_FOR'];
		}
		elseif (isset($_SERVER['HTTP_FORWARDED'])) {
			$ip = $_SERVER['HTTP_FORWARDED'];
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		// Add user to database
		db_query("INSERT INTO ".TB_PREF."useronline (timestamp, ip, file) VALUES ('". $timestamp ."','". $ip ."','". $_SERVER['PHP_SELF'] ."')");
		//Remove users that were not online within $timeoutseconds.
		db_query("DELETE FROM ".TB_PREF."useronline WHERE timestamp<". $timeout);

		// Select online users
		$result = db_query("SELECT DISTINCT ip FROM ".TB_PREF."useronline");
		$users = db_num_rows($result);
	}
	else
		$users = 1;
	return "$users ".($users == 1 ? _("user online") : _("users online"));

}
?>