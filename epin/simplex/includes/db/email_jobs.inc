<?php
/**********************************************************************
	Copyright (C) Simplex.
***********************************************************************/

function add_email_job($sender,$recipient,$bcc,$cc,$attach,$status,$url)
{

    //$id = db_insert_id("epin_email_jobs") ;  //.db_escape($id)	.", "
	
				$sql = "INSERT INTO ".TB_PREF."epin_email_jobs"
		. " (id,email_sender,email_recipient,email_bcc,email_cc,email_attachment,status,logged_date,url)
			VALUES( EPIN_EMAIL_JOBS_ID_SEQ.nextval,"    
			.	db_escape($sender)	.", "
			.db_escape($recipient)	.", "
			.	db_escape($bcc)	.", "
			.db_escape($cc)	.", "
			. db_escape($attach). ","
			.db_escape($status)	.", SYSDATE, "
			. db_escape($url) .")";
			echo $sql;
		db_query($sql, "Cannot log email job info");
	

}


?>
