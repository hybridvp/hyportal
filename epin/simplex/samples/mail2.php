<?php 
				require_once($path_to_root . "/reporting/includes/class.mail.inc");
    			$mail = new email($this->company['coy_name'], $this->company['email']);
				if (!isset($myrow['email']) || $myrow['email'] == '') 
					$myrow['email'] = isset($myrow['contact_email']) ? $myrow['contact_email'] : '';
    			$to = $myrow['DebtorName'] . " <" . $myrow['email'] . ">";
    			$msg = $doc_Dear_Sirs . " " . $myrow['DebtorName'] . ",\n\n" . $doc_AttachedFile . " " . $subject .
    				"\n\n";
				if (isset($myrow['dimension_id']) && $myrow['dimension_id'] > 0 && $doctype == ST_SALESINVOICE) // helper for payment links
				{
					if ($myrow['dimension_id'] == 1)
					{
						$amt = number_format($myrow["ov_freight"] + $myrow["ov_gst"] +	$myrow["ov_amount"], user_price_dec());
						$txt = $doc_Payment_Link . " PayPal: ";
						$nn = urlencode($this->title . " " . $myrow['reference']);
						$url = "https://www.paypal.com/xclick/business=" . $this->company['email'] . "&item_name=" .
							$nn . "&amount=" . $amt . "&currency_code=" . $myrow['curr_code'];
						$msg .= $txt . $url . "\n\n";
					}
				}
    			$msg .= $doc_Kindest_regards . "\n\n";
    			$sender = $this->user . "\n" . $this->company['coy_name'] . "\n" . $this->company['postal_address'] . "\n" . $this->company['email'] . "\n" . $this->company['phone'];
    			$mail->to($to);
    			$mail->subject($subject);
    			$mail->text($msg . $sender);
    			$mail->attachment($fname);
    			$ret = $mail->send();
				if (!$ret)
					display_error(_("Sending document by email failed"));
				else
					display_notification($this->title . " " . $myrow['reference'] . " " 
						. _("has been sent by email."));
				 //Rilwan commented out this line to view the pdf generated as the TestSMTP application could not display it.
				 //this code should be uncommented in production for deletion of Quotes generated
				//unlink($fname);		
			}

?>