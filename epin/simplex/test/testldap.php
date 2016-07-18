<?php
/*  Author : Chris Gralike
  * Function : Ldap Connection Class
  * Object :
  */
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");
class ldaps{
    private $ldap;
    private $ldapUseSecure;
    private $ldapHost;
    private $ldapPort;
    private $ldapPortSecure;
    private $ldapUser;
    private $ldapPass;
    private $ldapResource;
    private $ldapResultset;
    private $ldapWindows;
    private $ldapDomain;
    // Class constructor
    function __construct(){
        $this->ldapUseSecure = true;
        $this->ldapWindows = true;
        $this->ldapPort = 389;
        $this->ldapPortSecure = 636;
        $this->ldapResource = null;
        $this->ldapResultset = null;
    }
    // Class Destructor
    function __destruct(){
        unset($this->ldap);
        unset($this->ldapUseSecure);
        unset($this->ldapHost);
        unset($this->ldapPort);
        unset($this->ldapPortSecure);
        unset($this->ldapUser);
        unset($this->ldapPass);
        unset($this->ldapResource);
        unset($this->ldapResultset);
        unset($this->ldapWindows);
        unset($this->ldapDomain);
    }
    // Set Ldap Host
    function s_Host($host){
        if(!empty($host)){$this->ldapHost = $host;}
    }
    // Set LdapPort
    function s_Port($port){
        if(!empty($port)){$this->ldapPort = $port;}
    }
    function s_PortSecure($port){
        if(!empty($port)){$this->ldapPortSecure = $port;}
    }
    // Set LdapUser
    function s_User($user){
        if(!empty($user)){
            $this->ldapUser = $user;
        }else{
            return false;
        }
    }
    // Set LdapPasswd
    function s_Pass($pass){
        if(!empty($pass)){
            $this->ldapPass = $pass;
        }else{
            return false;
        }
    }
   
    function s_Windows($win){
        if($win == true || $win == false){$this->ldapWindows = $win;}
    }
   
    function s_Ldapsecure($secure){
        if($secure == true || $secure == false){ $this->ldapUseSecure = $secure; }
    }
   
    function s_Domain($domain){
        if(!empty($domain)){$this->ldapDomain = $domain;}
    }
   
    function g_Host(){
        return $this->ldapHost;
    }
   
    function g_Port(){
        return $this->ldapPort;
    }
   
    function g_PortSecure(){
        return $this->ldapPortSecure;
    }
   
    function g_User(){
        if(!empty($this->ldapUser)){
            return $this->ldapUser;
        }else{
            return false;
        }
    }
   
    function g_Pass(){
        if(!empty($this->ldapPass)){
            return true;
        }else{
            return false;
        }
    }
   
    function g_Windows(){
        return $this->ldapWindows;
    }
   
    function g_Domain(){
        return $this->ldapDomain;
    }
   
    function LdapConn(){
        if($this->ldapUseSecure){
            if($this->ldapResource  = ldap_connect($this->ldapHost, $this->ldapPortSecure)){
                if($this->ldapWindows == true){
                    ldap_set_option($this->ldapResource, LDAP_OPT_PROTOCOL_VERSION, 3);
                    ldap_set_option($this->ldapResource, LDAP_OPT_REFERRALS, 0);
                }
                return true;
            }
        }else{
            if($this->ldapResource  = ldap_connect($this->ldapHost, $this->ldapPort)){
                if($this->ldapWindows == true){
                    ldap_set_option($this->ldapResource, LDAP_OPT_PROTOCOL_VERSION, 3);
                    ldap_set_option($this->ldapResource, LDAP_OPT_REFERRALS, 0);
                }
                return true;
            }
        }
        return false;
    }
   
    function LdapBind(){
        if(!empty($this->ldapUser) && !empty($this->ldapPass)){
            if($this->ldapResultset = @ldap_bind($this->ldapResource, $this->GetldapUPN(), $this->ldapPass)){
                return true;
            }else{
                return false;
            }
        }else{
            if($this->ldapResultset = ldap_bind($this->ldapResource)){
                return true;
            }else{
                return false;
            }
        }
    }
   
    function GetldapUPN(){
        if(!empty($this->ldapDomain) && !empty($this->ldapUser)){
            if(!empty($this->ldapUser)){
                return $this->ldapUser."@".$this->ldapDomain;
            }
        }
        return false;
    }
   
    function GetldapDomain(){
        if(!empty($this->ldapDomain)){
            $DC = explode(".", $this->ldapDomain);
            return "DC=$DC[0],DC=$DC[1]";
        }
        return false;
    }
   
    public function getAttribute($attr){
        /* The relevant object we are looking for to limit the scope */
        $filter = 'samaccountname='.$this->ldapUser;
        /* We want to filter on one object so we dont get "All" information back.... Its Ldap Hell!*/
        $rObjs = array($attr);
        /* Execute the actual search on the directory */
        if($this->ldapResultset = @ldap_search($this->ldapResource, $this->GetldapDomain(), $filter, $rObjs)){
            $info = ldap_get_entries($this->ldapResource, $this->ldapResultset);
        }
        /* Validate we have a result that matters */
        if($info['count'] > 0){
            if(@$info[0][$attr]['count'] > 0){
                return $info[0][$attr][0];
            }else{
                return false;
            }
        }else{
            return false;
        }   
    }
   
    public function getMemberships(){
        /* The relevant object we are looking for to limit the scope */
        $filter = 'samaccountname='.$this->ldapUser;
        /* We want to filter on one object so we dont get "All" information back.... Its Ldap Hell!*/
        $rObjs = array('memberof');
        /* Execute the actual search on the directory */
        if($this->ldapResultset = @ldap_search($this->ldapResource, $this->GetldapDomain(), $filter, $rObjs)){
            $info = ldap_get_entries($this->ldapResource, $this->ldapResultset);
        }
        /* Validate we have a result that matters */
        if($info['count'] > 0){
            foreach($info[0]['memberof'] as $key => $value){
                $memberships[$key]=$value;
            }
            if(is_array($memberships)){
                return $memberships;
            }else{
                return false;
            }
        }else{
            return false;
        }   
    }
}
 
// Set some basic Ldap Details//
$ldap = new ldaps();
$ldap->s_Host('10.150.140.11');  //10.150.140.11'   '10.178.1.48'
$ldap->s_Domain('etisalatng.com');
$ldap->s_LdapSecure(false);
 
if(isset($_POST['validate'])){
    $ldap->s_User($_POST['username']);
    $ldap->s_Pass($_POST['password']);
    // First check we actually have a username and password set inside the ldap object.
    if($ldap->g_User() && $ldap->g_Pass()){
        if($ldap->LdapConn()){
			echo "Connected OK" . ' with username=' . $_POST['username']. '<br>' ;
            if($ldap->LdapBind()){
			
                echo 'Full Name : '. $ldap->getAttribute('cn').'<br>';
                echo 'Email : '. $ldap->getAttribute('mail').'<br>';
                echo 'Title : '. $ldap->getAttribute('title').'<br>';
                echo 'UID : '. $ldap->getAttribute('id').'<br>';
                echo 'Groups : ';
               /*  foreach($ldap->getMemberships() as $key => $value){
                    echo "Group : $value <br>";
                } */
            }else{
                echo "Invalid credentials";
            }
        }else{
            echo "ldap server not available";
        }
    }else{
        echo "blank fields detected";
    }   
}
?>
 
<html>
<head>
</head>
<body>
<table style="width:100%; height:100%;" cellspacing="0" cellpadding="0" align="center" valign="middle">
    <tr>
        <td>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="SSOLOGIN">
            <table style="width:350px; height:150px;" cellspacing="0" cellpadding="0" align="center" valign="middle">
                <tr>
                  <td>Username
                    <input type="text" name="username" /></td></tr>
                <tr>
                  <td>Password  
                    <input type="password" name="password" /></td></tr>
                <tr><td><input type="submit" name="validate" value="Login" /></td></tr>
            </table>
            </form>
        </td>
    </tr>
</table>
</body>
</html>