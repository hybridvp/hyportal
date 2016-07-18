<?php
/**********************************************
Author: 
***********************************************/
$page_security = 'SA_PRICEREP' ; //'SA_CSVIMPORT';
$path_to_root="../../..";
global $ws_pinenquiry;
global $ws_voucherrecharge;

require_once($path_to_root . "/simplex/includes/nusoap/lib/nusoap.php");
global $nonfin_audit_trail;
include($path_to_root . "/includes/session.inc");

page(_($help_context = "PIN Inquiry"), @$_REQUEST['popup']);

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/inventory/includes/inventory_db.inc");
add_access_extensions();

//$fullpin = '';

function GetPwd($user_id)
{
	$sql = "SELECT password from users WHERE user_id= ". db_escape($user_id);
	$sql_a = db_query($sql);
	 $result = db_fetch($sql_a);
	 return $result['password'];
}
 
//----------------------------------------------------------------
function objectToArray($d)
{
		if (is_object($d))
		{
			$d = get_object_vars($d);
		}
 
		if (is_array($d))
		{
			return array_map(__FUNCTION__, $d);
		}
		else
		{
			return $d;
		}
}
function ValidatePIN($pin)
{	
	$valid = true;
	for ($i = 0; $i < strlen($pin); $i++)
        {
            if ( !preg_match('[#0-9]',$pin[$i]) )
            {
			
                $valid = false;
				//echo 'pin' . $pin[$i] . '-' . $valid;
				return $valid;
            }
        }
	return $valid;
}
//http://localhost:9952/MyWebService/Service.asmx
/* function TestWebSvc($dfahrenheit,$proxyhost="",$proxyport="",$proxyusr="",$proxypassword="")
{
		$wsdlfile = "http://localhost:9952/MyWebService/Service.asmx?wsdl";
		$msg1="<?xml version=\"1.0\" encoding=\"utf-8\"?>
			 <soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">
			   <soap:Body>
				 <ConvertTemperature xmlns=\"http://localhost:9952/MyWebService/ConvertTemperature/\">
				   <dFahrenheit>".$dfahrenheit."</dFahrenheit>
				 </ConvertTemperature>
			   </soap:Body>
			 </soap:Envelope>";
		$client = new SoapClient($wsdlfile); 
		$parameters = array('dFahrenheit'=>$dfahrenheit);
		
		$response = $client->ConvertTemperature($parameters);
		//print_r(  );
		return $response;
		//display_notification(_(print_r($response, true)));
}
 */
function GetPinDetail($serialNumber, $pin , $url)
{
		$wsdlfile = $url; //"http://epinappsvr/SimplexWebServices/PinEnquiry.svc?wsdl";
		$client = new SoapClient($wsdlfile); 
		$username = $_SESSION["wa_current_user"]->loginname;
		$password = GetPwd($username);
		$parameters = array('username'=>$username, 'password'=>$password, 'serialNumber'=>$serialNumber, 'pinString'=>$pin);
		
		$response = $client->GetPinDetails($parameters);
		return $response;
}
if (isset($_SESSION['fullpin']))
{
	unset ($_SESSION['fullpin']);
}
if (isset($_POST['GetPinDetail']))
{
	$input_error = 0;
/* 	if ( strlen($_POST['username']) == 0) 
			{
				$input_error = 1;
				display_error( _('The username cannot be empty.'));
				set_focus('username');
			}
	elseif (strlen($_POST['upassword']) == 0) 
			{
				$input_error = 1;
				display_error( _('Password cannot be empty'));
				set_focus('upassword');
			} */
	if ( !is_numeric($_POST['srlno']) ) 
			{
				$input_error = 1;
				display_error( _("The serial number must be numeric."));
				set_focus('srlno');
			}	
	elseif ( ValidatePIN($_POST['pin']) )
			{
				/* $pin= $_POST['pin'];
				$valid = true;
				for ($i = 0; $i < strlen($pin); $i++)
				{
					//display_error( _('pin' . $pin[$i] . '-' . $valid)); 
					if ( !preg_match('[#0-9]',$pin[$i]) )
					{
						$input_error = 1;						
					}
				} */
				$input_error = 1;
				display_error( _("Invalid character(s) in PIN"));
				set_focus('pin');
			}	
			//cho "return_code= " . str_replace(";", "", $response_arr["LodgeSMSMessageResult"]);
	if ($input_error != 1)
	{
//		$response_arr = objectToArray( TestWebSvc($_POST['srlno']) );
		//Log the call
		//unset($_SESSION['fullpin'] );
		if($nonfin_audit_trail)
		{
			$ip = preg_quote($_SERVER['REMOTE_ADDR']);
			add_nonfin_audit_trail(0,$_POST['srlno'],0,$_POST['pin'],'PIN INQUIRY','A',$ip,'INQUIRY DONE ON THIS RECORD');
		}
		$response = GetPinDetail($_POST['srlno'],$_POST['pin'], $ws_pinenquiry ) ;
		$response_arr = objectToArray($response );
		$msg = print_r($response, true);
		$response_txt = "";
		
		if(isset($response_arr["GetPinDetailsResult"]['ReponseText']))
		{
			$response_txt = $response_arr["GetPinDetailsResult"]['ReponseText'];
			if($response_arr["GetPinDetailsResult"]['Epin']['Pin'] != '' )
			{
				//$_SESSION['fullpin'] = $response_arr["GetPinDetailsResult"]['Epin']['Pin'];
				$fullpin = $response_arr["GetPinDetailsResult"]['Epin']['Pin'];
				//$_GET['fullpin'] = $response_arr["GetPinDetailsResult"]['Epin']['Pin'];
				//$_POST['fullpin'] = $response_arr["GetPinDetailsResult"]['Epin']['Pin'];
			}
			else
				$fullpin = '';
		}
		$Ajax->activate('_page_body');
		display_notification($response_txt);  
		// . $msg //. _($response_arr["GetPinDetailsResult"]['Epin']['Pin'] ) 
		//. 'PIN =' .$response_arr["GetPinDetailsResult"]['Epin']['Pin']
	}
}


if (isset($_POST['Recharge']))
{
		$input_err = 0;
		if ( strlen($_POST['msisdn']) == 0) 
					{
						$input_error = 1;
						display_error( _('You must enter a valid Subscriber number.'));
						set_focus('msisdn');
					}
		if(!isset($_POST['fullpin']))
		{
			$input_error = 1;
			display_error( _('The PIN must be retrieved first'));
		}
		if ($input_err != 1)
	{
		$soap_options = array(
        'trace'       => 1,     // traces let us look at the actual SOAP messages later
        'exceptions'  => 1 );
		$wsdlreccharge = $ws_voucherrecharge;
		
		if (!class_exists('SoapClient'))
		{
			die ("A required Component is not installed - PHP-Soap module.");
		}
		$webservice = @new SoapClient($wsdlreccharge, $soap_options); 
		try {  
				
								//$x = @new SoapClient("non-existent.wsdl",array("exceptions" => 1));  
								//prefix constructor calls with @ and catch SoapFault exceptions, 20120328163250
								//otherwise you risk having the php interpreter exit/		die on simple network issues. 
								$datestamp = date('ymdHis');
								$successcode = 405000000;
								$s_prefix = "SIMPL";				
								$txn_id =  rand(1, 999999999);								
								//make the request header
								$rqst_hdr = array( 'CommandId'   => (String) "VoucherRecharge", 
												   'Version' => (String) '1' ,
												   'SequenceId'  => (String) '1' ,
												   'SerialNo'  => (String) $s_prefix . 	$datestamp,
												   'RequestType'  => (String) 'Event',
												   'TransactionId'  => (String) $txn_id 
								   );
								//make d request body			
								$rqst_body = array( 'CardPinNumber'   => (String) $_POST['fullpin'], 
												   'SubscriberNo'  => (String) $_POST['msisdn'] 
								   );
								//make the message				
								$rqst_Msg = array( 'RequestHeader'   => $rqst_hdr , 
												   'VoucherRechargeRequest'  => $rqst_body 
								   );
								 if($nonfin_audit_trail)
		{
			$ip = preg_quote($_SERVER['REMOTE_ADDR']);
			add_nonfin_audit_trail(0,$_POST['srlno'], $_POST['msisdn'] ,$_POST['fullpin'],'PIN RECHARGE','A',$ip,'PIN RECHARGE');
		}
								$result = $webservice->VoucherRecharge($rqst_Msg);
								
								$rs = print_r($result, true);
								$response_array = objectToArray($result );
								//$msg = print_r($response, true);
								$response_txt = "";
		/* if($nonfin_audit_trail)
		{
			$ip = preg_quote($_SERVER['REMOTE_ADDR']);
			add_nonfin_audit_trail(0,$_POST['pin'],0,$_POST['msisdn'],'PIN RECHARGE','A',$ip,'PIN RECHARGE. Response:' . $response_array["ResultHeader"]['ResultCode']);
		}	 */			
								//$resp_desc = $response_arr["ResultHeader"]['ResultDesc'];
								if(isset($response_array["ResultHeader"]))
								{
									
									if($response_array["ResultHeader"]['ResultCode'] == $successcode )
									{
										$response_txt = $response_array["ResultHeader"]['ResultDesc'];
									}
									else
									{
										$response_txt = "Recharge Operation not Successfull" ."<br>". $response_array["ResultHeader"]['ResultDesc']."<br>";
										}
								}
								$Ajax->activate('_page_body');
								display_notification($response_txt); 
								
							} 
								catch (SoapFault $E) {  
								$Ajax->activate('_page_body');
								display_notification($E->faultstring);
								//echo $E->faultstring; 
	}  
	}
}

//-------------------------------------------------------------------------------------------- 
start_form(true);

div_start('details');
start_outer_table($table_style2, 5);

table_section(1);

table_section_title(_("EPIN /RCV PIN Inquiry"));

//text_row(_("Username: "), 'username', null, 30, 30);
//label_cell(_("Password: "));
//label_cell("<input type='password' name='upassword' size=22 maxlength=20 value=''>");
text_row(_("Serial Number: "), 'srlno', null, 30, 16);
text_row(_("PIN: "), 'pin', null, 30, 15);
text_row(_("Recharge MSISDN: "), 'msisdn', null, 30, 10);
//text_row(_("FUll PIN: "), 'fullpin', $fullpin, 30, 15);
if(isset($fullpin))
{
	label_cell(_("Complete PIN: ". $fullpin));
	
	hidden('fullpin',$fullpin);
	
//echo "<td>" . "Completes PIN: ". $fullpin. "</td>\n";
}
else
{
	hidden('fullpin','');
	
}

table_section(2);
table_section_title(_("How it works"));
//label_row(_("Transaction #"), $row['trans_no']);
label_row(_(" 1. Enter the Serial number of the card <br> "), '');
label_row(_(" 2. Enter the visible numbers as seen and replace the invisible numbers with '#'"),'' ); 

end_outer_table(1);
div_end();
//unset($_SESSION['fullpin']);
div_start('controls');
//submit_center_first('ConfirmOrder', $ourcorder,	  	     _('Confirm Order'), 'default');
submit_center_first('GetPinDetail', _("Get PIN"), _("Get PIN"), 'default');
//if(isset($_POST['pinvalid']) )
//{
submit_center_last('Recharge', _('Recharge Customer'),
	  	     _('Recharge the specified MSISDN with the PIN amount'), 'Recharge');
		     submit_js_confirm('Recharge', _('You are about to recharge '. $_POST['msisdn'] . '\nDo you want to continue?'));

div_end();

end_form();
end_page();
//------------------------------------------------------------------------------------

?>