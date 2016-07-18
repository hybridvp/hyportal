<?PHP
/* 
*******************************************************************************************************
*LDAP Class                                                                                
*Class Created 7-5-06                                                                      
*This class allows for LDAP searches and authentication against AD.                        
*You can use a foreach loop in your code that calls this class to display all entries      
*------------------------------------------------------------------------------------------------------
*Example LDAP Search.                                                                      
*------------------------------------------------------------------------------------------------------
*$ouArray = array('sales','users','accounting','management','purchasing');                                        
*$ldap = new ldap("dc.domain.com","389","dc=domain,dc=com");    
*$ldap->ldapConn();                                                                        
*$bind=$ldap->ldapBind("ldaplookupuser","ldaplookupuserpass");                                          
*if($bind==false)                                                                          
*  echo "Bind Failed<br>";                                                                 
*$description = $ldap->ldapSearch("description","$usrname",$ouArray,"samaccountname");     
*if($description)                                                                          
*{                                                                                         
*  foreach($description as $output)                                                        
*    $company=$output;                                                                     
*}                                                                                         
*/
/*------------------------------------------------------------------------------------------------------
*Example Ldap Authenticate                                                                 
*------------------------------------------------------------------------------------------------------
*/
$usrname=$_POST['username'];                                                               
$pass=$_POST['password'];                                                                     
$ldap = new ldap("etisalatng","389","dc=domain,dc=com");    
$ldap->ldapConn();                                                                        
$auth = $ldap->ldapAuthenticate("$usrname","$pass");                                      
if($auth)                                                                                 
  echo "Successful Authentication<br>";                                                   
else                                                                                      
  echo "Authentication Failed.";                                                          
/*-------------------------------------------------------------------------------------------------------
*Example Multiple LDAP Search
*--------------------------------------------------------------------------------------------------------
*$ouArray = array('sales','users','accounting','management','purchasing');                                        
*$ldap = new ldap("dc.domain.com","389","dc=domain,dc=com");    
*$ldap->ldapConn();                                                                        
*$bind=$ldap->ldapBind("ldaplookupuser","ldaplookupuserpass");                                          
*if($bind==false)                                                                          
*  echo "Bind Failed<br>";                                                                 
*$description = $ldap->multipleLdapSearch("memberof","$usrname",$ouArray,"samaccountname");     
*if($description)                                                                          
*{                                                                                         
*  foreach($description as $output)                                                        
*    echo "$output<br>";                                                                     
*}                                                                        
*--------------------------------------------------------------------------------------------------------
*Modifications                                                                             
*-------------------------------------------------------------------------------------------------------
07-21-06 Added multipleLdapSearch to return all entries stored in LDAP instead of just the primary ones
********************************************************************************************************
*/
class ldap
{
  var $ldapConn; //ldap connection storage variable
  var $ldapBind; //ldap bind storage variable
  var $entries;  //ldap entries variable
  var $ldapLookupUser;
  var $ldapLookupPass;
  var $server;
  var $port;
  var $by;
  var $search;
  var $baseDN;
  //Function to create the ldap object
  function ldap($server,$port,$baseDN)
  {
    $this->server=$server; //sets the dc server
    $this->port=$port; //sets the port to connect to AD
    $this->baseDN=$baseDN; //Sets the base DN for LDAP searches
  }
  //connects to the AD server
  function ldapConn()
  {
    //connects to AD server
    $this->ldapConn = @ldap_connect($this->server,$this->port);
    return $this->ldapConn;
  }
  //Binds to the AD server so you can do lookups against it
  function ldapBind($ldapLookupUser,$ldapLookupPass)
  {
    if(@ldap_bind($this->ldapConn,$ldapLookupUser,$ldapLookupPass))
    {
      $this->ldapBind = @ldap_bind($this->ldapconn,$ldapLookupUser,$ldapLookupPass);
      //returns true if you are able to bind
      return true;
    }
    else
      return false;
  }
  //Authenticates a User against AD
  function ldapAuthenticate($usrname,$password)
  {
    if(@ldap_bind($this->ldapConn,$username,$password))
      return true;
    else
      return false;
  }
  //Searches the ldap schema for the single primary result
  function ldapSearch($by,$search,$ous,$searchby)
  {
    $c=0;
    foreach($ous as $ou) //This foreach loop allows the searching through multiple OU's'
    {
       /*This line searches the AD Schema.  
       It is setup so that you can search for any schema item by any schema item.
       */
      $read=ldap_search($this->ldapConn,"ou=$ou,$this->baseDN", "$searchby=*$search*");
      //This line reads in the entries for output
      $entries = ldap_get_entries($this->ldapConn, $read);
      //Loops through the entries and puts them in the array values
      for ($i=0; $i<$entries["count"]; $i++)
      {
        if($entries[$i][$by][0])
          $values[$c]=$entries[$i][$by][0];
        $c++;
      }
    }
    return $values; //returns the values of the search
  }
  //Searches the LDAP schema and returns all LDAP schema items
  function multipleLdapSearch($by,$search,$ous,$searchby)
  {
    $c=0;
    foreach($ous as $ou) //This foreach loop allows the searching through multiple OU's'
    {
       /*This line searches the AD Schema.  
       It is setup so that you can search for any schema item by any schema item.
       */
      $read=ldap_search($this->ldapConn,"ou=$ou,$this->baseDN", "$searchby=*$search*");
      //This line reads in the entries for output
      $entries = ldap_get_entries($this->ldapConn, $read);
      //Loops through the entries and puts them in the array values
      for ($i=0; $i<$entries["count"]; $i++)
      {
        foreach($entries[$i]["memberof"] as $output)
        {
          $values[$c]=$output;
          $c++;
        }
      }
    }
    return $values; //returns the values of the search
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