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
function check_stock_id($stock_id) {
    $sql = "SELECT * FROM ".TB_PREF."stock_master where stock_id = $stock_id";
    $result = db_query($sql, "Can not look up stock_id");
    $row = db_fetch_row_r($result);
    if (!$row[0]) return 0;
    return 1;
}
function GetPwd($user_id)
{
	$sql = "SELECT password from users WHERE user_id= ". db_escape($user_id);
	$sql_a = db_query($sql);
	 $result = db_fetch($sql_a);
	 return $result['password'];
}
 
function FileExists($file_numbr) 
{
	$sql = "SELECT count(*) FROM ".TB_PREF."pin_details where batch_no = $file_numbr";
    $result = db_query($sql, "Can not look up file number");
    $row = db_fetch_row_r($result);
    if (!$row[0]) return 0;
    return 1; 
}
function GetPinDetailss($username,$password, $serialNumber, $pin)
{
		$wsdlfile = "http://epinappsvr/SimplexWebServices/PinEnquiry.svc?wsdl";
		$client = new SoapClient($wsdlfile);  //, array('soap_version' => SOAP_1_1)
		$parameters = array('username'=>$username, 'password'=>$password, 'serialNumber'=>$serialNumber, 'pinString'=>$pin);
		
		$response = $client->GetPinDetails($parameters);
		return $response;
}
function GetPinDetail($serialNumber, $pin)
{
		$wsdlfile = "http://epinappsvr/SimplexWebServices/PinEnquiry.svc?wsdl";
		$client = new SoapClient($wsdlfile); 
		$username = $_SESSION["wa_current_user"]->loginname;
		$password = GetPwd($username);
		$parameters = array('username'=>$username, 'password'=>$password, 'serialNumber'=>$serialNumber, 'pinString'=>$pin);
		
		$response = $client->GetPinDetails($parameters);
		return $response;
}
//http://localhost:9952/MyWebService/Service.asmx
function TestWebSvc($dfahrenheit,$proxyhost="",$proxyport="",$proxyusr="",$proxypassword="")
{
		$wsdlfile = "http://localhost:9952/MyWebService/Service.asmx?wsdl";
		$wsdlfile2 = "http://epinappsvr/SimplexWebServices/PinEnquiry.svc?wsdl";
		$msg1="<?xml version=\"1.0\" encoding=\"utf-8\"?>
			 <soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">
			   <soap:Body>
				 <ConvertTemperature xmlns=\"http://localhost:9952/MyWebService/ConvertTemperature/\">
				   <dFahrenheit>".$dfahrenheit."</dFahrenheit>
				 </ConvertTemperature>
			   </soap:Body>
			 </soap:Envelope>";

/* $msg = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
		<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">
		  <soap:Body>
			<ConvertTemperature xmlns=\"http://tempuri.org/\">
			  <dFahrenheit>.$dfahrenheit.</dFahrenheit>
			</ConvertTemperature>
		  </soap:Body>
		</soap:Envelope>" ; */

		//$s = new soapclient($wsdlfile);
		//$client = new SoapClient($wsdlfile); 
		//$client2 = new SoapClient($wsdlfile2); 
		// $err = $client->getError();
		/* if ($err) {

			echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';

		} 
		$client->soap_defencoding = 'UTF-8';
		$client->setHTTPEncoding('deflate, gzip'); */
		
		
		/*if (empty($proxyhost))
		{
			
		}
		else
		{
				$s->setHTTPProxy($proxyhost,$proxyport,$proxyusr,$proxypassword);
		}*/
		//$result = $s->send($msg,'http://localhost:51220/MyWebService/ConvertTemperature',60);
		$username = 'awo';
		$password= 'lanre';
		
		//$parameters = array('dFahrenheit'=>$dfahrenheit); 
		$parameters2 = array('username'=>$username, 'password'=>$password,
		 'serialNumber'=>'9100000000062661', 'pinString'=>'651442130909316');
		//print_r( $client->ConvertTemperature($parameters) );
//		print_r( $client2->GetPinDetails($parameters2) );
		//$response = $client->GetPinDetails($parameters);'financesuper', '5f4dcc3b5aa765d61d8327deb882cf99',
		$response =  GetPinDetail('9100000000062664','548310###385580' );
		print_r($response);
		$msg = print_r($response, true);
		$response_arr = objectToArray($response  );
		//display_notification($msg . ' The result is : '. _($response_arr["GetPinDetailsResult"]['Epin']['Pin'] ));
		display_notification(' The result is : '. _($response_arr["GetPinDetailsResult"]['Epin']['Pin'] ) . $msg);
		//return $s->responseData;
}
 if (isset($_POST['Activate']))
  {
  		
		TestWebSvc(56);
		//echo "temperature: " .$tmpdegrees;
  }
//function validateImport($total_lines, $qty, $start_seq)
page("Web service testing");

    start_form(true);

    start_table("$table_style2 width=40%"); 
 

    table_section_title("Web service testing");

    text_row("Batch No:", 'batch_no', '', 10, 20);
    //label_row("ePIN Import File:", "<input type='file' id='imp' name='imp'>");
	$datestampt = date('ymdHis');
	echo $datestampt;
    end_table(1);

    submit_center('Activate', "Activate");

    end_form();
	end_page();
?>