<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_SETUPCOMPANY';
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");

page(_($help_context = "Job Control Setup"));

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");

include_once($path_to_root . "/admin/db/company_db.inc");
include_once($path_to_root . "/simplex/includes/db/simplexreferences_db.inc");
//-------------------------------------------------------------------------------------------------

if (isset($_POST['update']) && $_POST['update'] != "")
{

	$input_error = 0;
	$job = get_jobs();

	if ($input_error != 1)
	{
		begin_transaction();
		//while ($myrow = db_fetch($job))
		//{

			//update_jobctl_setup(strtoupper( 'order_processing' ), $_POST['id' . $myrow["order_processing"]] );
			update_jobctl_setup('ORDER_PROCESSING', check_value('order_processing') );
			update_jobctl_setup('ACTIVATION' , check_value('activation') );
			update_jobctl_setup('SENDER' , check_value('sender') );
			update_jobctl_setup('PIN_LOADING' , check_value('pin_loading') );
			update_jobctl_setup('ARCHIVING', check_value('archiving') );
			
			//echo $myrow["process_name"] . "=" . $_POST[$myrow["run_flag"]];
		commit_transaction();	
		//}
			//$_SESSION['wa_current_user']->timeout = $_POST['login_tout'];
			display_notification_centered(_("Job Control setup has been updated." . check_value('order_processing') )); //, $_POST['arch_freq'],
	}
	//set_focus('coy_name');
				//$Ajax->activate('_page_body');
} /* end of if submit */

//---------------------------------------------------------------------------------------------


start_form(true);
//$order_proc_row = get_jobctl_prefs('ORDER_PROCESSING');
//$activation_row = get_jobctl_prefs('ACTIVATION');
//$senderrow = get_jobctl_prefs('SENDER');

//$_POST['order_proc']  = $order_proc_row["run_flag"];
//$_POST['activation']  = $activation_row["run_flag"];
//$_POST['sender']  = $senderrow["run_flag"];

start_outer_table($table_style2);

table_section(1);

table_section_title(_("EPIN Job Control"));
$th = array(_("Job Type"), _("Enabled"));   //, _("On Order")
		
/* check_row(_("Run Order Processing:"), 'order_proc', $_POST['order_proc']);
check_row(_("Run Activation Processing:"), 'activation', $_POST['activation']);
check_row(_("Run Mail Sender:"), 'sender', $_POST['sender']); */
table_header($th);
$j = 1;
$k = 0; //row colour counter
$jobs = get_jobs();
while ($myrow = db_fetch($jobs))
{

	alt_table_row_color($k);
	//qty_cell($myrow["reorder_level"], false, $dec);
	//check_row(_($myrow["process_name"]), 'process_name', $myrow["process_name"]);
	//label_cell($myrow["process_name"]);
//	check_row(_("Serializable:"), 'serialize');
	check_row(_($myrow["process_name"]), strtolower($myrow["process_name"]), $myrow["run_flag"]);
	//ref_row($myrow["process_name"], 'id' . $myrow["process_name"], '', $myrow["run_flag"]);
    //qty_cell($qoh - $demand_qty, false, $dec);
        //qty_cell($qoo, false, $dec);
    end_row();
	
	$j++;
	If ($j == 12)
	{
		$j = 1;
		table_header($th);
	}
}

table_section(2);

end_outer_table(1);


submit_center('update', _("Update"), true, '',  'default');

end_form(2);
//-------------------------------------------------------------------------------------------------

end_page();

?>
