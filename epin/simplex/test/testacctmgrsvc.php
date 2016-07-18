<?php
/**********************************************
Author: 
***********************************************/
$page_security = 'SA_SUPPTRANSVIEW' ; //'SA_CSVIMPORT';
$path_to_root="../..";

require_once($path_to_root . "/simplex/includes/nusoap/lib/nusoap.php");
include($path_to_root . "/includes/session.inc");
add_access_extensions();

include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc"); 


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

function GetPinDetail()
{
		$wsdlfile = "http://10.130.0.22:7782/services/CBSInterfaceAccountMgrService?wsdl";
		
		$client = new SoapClient($wsdlfile); 
		$parameters = array('username'=>$username, 'password'=>$password, 'serialNumber'=>$serialNumber, 'pinString'=>$pin);
		
		$response = $client->GetPinDetails($parameters);
		return $response;
}
//http://localhost:9952/MyWebService/Service.asmx
function TestWebSvc($dfahrenheit,$proxyhost="",$proxyport="",$proxyusr="",$proxypassword="")
{
		$response =  GetPinDetail();
		print_r($response);
		$msg = print_r($response, true);
		$response_arr = objectToArray($response  );
		//display_notification($msg . ' The result is : '. _($response_arr["GetPinDetailsResult"]['Epin']['Pin'] ));
		display_notification(' The result is : '. $msg);
		//return $s->responseData;
}
 if (isset($_POST['Activate']))
  {  		
  		$soap_options = array(
        'trace'       => 1,     // traces let us look at the actual SOAP messages later
        'exceptions'  => 1 );
		if (!class_exists('SoapClient'))
		{
			die ("A required Component is not installed - PHP-Soap module.");
		}

		try {  
				$wsdlfile = "http://10.130.0.22:7782/services/CBSInterfaceAccountMgrService?wsdl";
				//$x = @new SoapClient("non-existent.wsdl",array("exceptions" => 1));  
				//prefix constructor calls with @ and catch SoapFault exceptions, 20120328163250
				//otherwise you risk having the php interpreter exit/		die on simple network issues. 
				$datestamp = date('ymdHis');
				$s_prefix = "SIMPL";
				$webservice = @new SoapClient($wsdlfile, $soap_options);  
				$txn_id =  rand(1, 999999999);
				
				//make the request header
				$rqst_hdr = new StdClass();
				$rqst_hdr->RequestHeader = new StdClass();
				$rqst_hdr->RequestHeader->CommandId = "VoucherRecharge";
				$rqst_hdr->RequestHeader->Version = "1";
				$rqst_hdr->RequestHeader->SequenceId = "1";
				$rqst_hdr->RequestHeader->SerialNo = $s_prefix . $datestamp;
				$rqst_hdr->RequestHeader->RequestType = "Event";
				$rqst_hdr->RequestHeader->TransactionId = $txn_id;
				//make d request body
				$rqst_body = new StdClass();
				$rqst_body->VoucherRechargeRequest = new StdClass();
				$rqst_body->VoucherRechargeRequest->CardPinNumber = "651442130909316";
				$rqst_body->VoucherRechargeRequest->SubscriberNo = "08173646201";
				
				//make the message
				$rqst_Msg = new StdClass();
				$rqst_Msg->VoucherRechargeRequestMsg = new StdClass();
				$rqst_Msg->VoucherRechargeRequestMsg->RequestHeader = $rqst_hdr;
				$rqst_Msg->VoucherRechargeRequestMsg->VoucherRechargeRequest = $rqst_body;

				//call d method
				echo 'calling method';
				$result = $webservice->VoucherRecharge($rqst_Msg);
				$rs = print_r($result, true);
				display_notification('Called Voucher recharge : $result= ' .$result);

			} 
				catch (SoapFault $E) {  
				echo $E->faultstring; 
			}  
			//echo "ok\n"; 
  }
page("CBS Acct Mgr Web service testing");

    start_form(true);

    start_table("$table_style2 width=40%"); 
 

    table_section_title("Web service testing");

    text_row("Batch No:", 'batch_no', '', 10, 20);
    //label_row("ePIN Import File:", "<input type='file' id='imp' name='imp'>");

    end_table(1);

    submit_center('Recharge My Card', "Activate");

    end_form();
	end_page();
?>