<?php 
		
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/ui/ui_msgs.inc");


				require_once($path_to_root . "/reporting/includes/class.mail.inc");
    			//$mail = new email('Company Name Here', 'simplex@bluechiptech.biz');
				$mail = new email('Laolu Olapegba', 'laolu.olapegba@techrunch.net');
				$subject = "Testing Email Sending" ;
				$myrow['email'] = 'laolu.olapegba@techrunch.net' ;
    			$to = "laolu.olapegba@techrunch.net";
    			$msg = "Dear Mr. Lateef,\n\n How are you" ;// $doc_AttachedFile . " " . $subject ."\n\n";
    			$sender = "Rilwan\n Company Name\nLagos Island\n" ;
    			$mail->to($to);
    			$mail->subject($subject);
    			$mail->text($msg . $sender);
    			//$mail->attachment($fname);
    			$ret = $mail->send();
				if (!$ret)
					display_error("Sending document by email failed");
				else
					display_notification("Email sent");
				 //Rilwan commented out this line to view the pdf generated as the TestSMTP application could not display it.
				 //this code should be uncommented in production for deletion of Quotes generated
				//unlink($fname);		
	

?>