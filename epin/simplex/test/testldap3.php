<?php 
include("class.AuthLdap.php");
//function AuthLdap ($sLdapServer, $sBaseDN, $sServerType, $sDomain = "", $searchUser = "", $searchPassword = "") {
$ldap = new AuthLdap("10.150.140","dc=etisalatng,dc=com","ActiveDirectory","etisalatng");
$server[0] = "10.150.140.11"; // Primary LDAP server
//$server[1] = "10.1.1.2"; // Replica LDAP server
$ldap->server = $server;
$ldap->dn = "dc=etisalatng,dc=com"; // Base DN of our organisation

if ( $ldap->connect() ) {
	echo "Connected OK" ;
} 
else {

}
?>