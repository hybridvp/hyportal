<?php

  $path_to_root = "../..";
  include($path_to_root . "/simplex/includes/nusoap/lib/nusoap.php");
     //   require_once('nusoap/lib/nusoap.php');
function ActivatePIN($pin,$batch_no,$svrname,$proxyhost="",$proxyport="",$proxyusr="",$proxypassword="")
{
		$wsdlfile = "http://emts.ng.com/pinactivationservice/activations.asmx";
		$msg="<?xml version=\"1.0\" encoding=\"utf-8\"?>
			 <soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">
			   <soap:Body>
				 <getStatus xmlns=\"http://webpay.interswitchng.com/webpay/\">
				   <PIN>".$pin."</PIN>
				   <BATCHNO>".$batch_no."</BATCHNO>
				   <SERVERNAME>".$svrname."</SERVERNAME>
				 </getStatus>
			   </soap:Body>
			 </soap:Envelope>";

		$s = new soapclientw($wsdlfile);
		if (empty($proxyhost))
		{
		}else
		{
				$s->setHTTPProxy($proxyhost,$proxyport,$proxyusr,$proxypassword);
		}
		$result = $s->send($msg,'http://emts.ng.com/pinactivationservice/getStatus',60);
		return $s->responseData;
}
?>