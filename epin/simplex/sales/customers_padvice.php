<?php
/**********************************************************************
Simplex Extention 
***********************************************************************/
$page_security = 'SA_CUSTOMER';
$path_to_root = "../..";

include_once($path_to_root . "/includes/session.inc");
page(_($help_context = "Customers Payment Advice"), @$_REQUEST['popup']); 

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/banking.inc");
include_once($path_to_root . "/includes/ui.inc");


$js = '';

if ($use_popup_windows) {
	$js .= get_js_open_window(900, 500);
}

$new_padvice = true ;
//----------------------------------------------------------------------------------------------
//
//Modified this function in ui_list.inc to show code in addition to name for customer
//
function padvice_list($name, $selected_id=null, $spec_option=false, $submit_on_change=false, 
	$show_inactive=false, $editkey = false)
{
	global $all_items;
//Change sub ref to name, it should be concatenated
	$sql = "SELECT order_no, (order_no|| '->Customer:'|| s.debtor_no||'->'|| name|| ' | Ref: '||customer_ref  ) as debtor_desc, 
			curr_code, inactive FROM ".TB_PREF."sales_orders s, ".TB_PREF."debtors_master m 
			where s.debtor_no=m.debtor_no
			and s.trans_type = 30
			and s.ourorder_status='Planned'";


	$mode = get_company_pref('no_customer_list');

	if ($editkey)
		set_editor('customer', $name, $editkey);

	return combo_input($name, $selected_id, $sql, 'order_no', 'debtor_desc',
	array(
	    'format' => '_format_add_curr',
	    'order' => array('order_no'),
		'search_box' => true/*$mode!=0*/,
//
		'search' => array("order_no", "debtor_desc"),
		'search_submit' => false/*get_company_pref('no_item_list')!=0*/,
//
		'type' => 0,
		'size' => 20,
		'spec_option' => $spec_option === false ? _("All Customers") : $spec_option,
		'spec_id' => $all_items,
		'select_submit'=> $submit_on_change,
		'async' => false,
		'sel_hint' => $mode ? _('Press Space tab to filter by name fragment; F2 - entry new customer') :
		_('Select Sales Order'),
		'show_inactive' => $show_inactive
	) );
}

function padvice_list_cells($label, $name, $selected_id=null, $all_option=false, 
	$submit_on_change=false, $show_inactive=false, $editkey = false)
{
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td nowrap>";
	echo padvice_list($name, $selected_id, $all_option, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n";
}

function padvice_list_row($label, $name, $selected_id=null, $all_option = false, 
	$submit_on_change=false, $show_inactive=false, $editkey = false)
{
	echo "<tr><td>$label</td><td nowrap>";
	echo padvice_list($name, $selected_id, $all_option, $submit_on_change,
		$show_inactive, $editkey);
	echo "</td>\n</tr>\n";
}


function can_process()
{
	if (strlen($_POST['order_no']) == 0) 
	{
		display_error(_("The customer name cannot be empty."));
		set_focus('order_no');
		return false;
	} 

	if (strlen($_POST['ref']) == 0) 
	{
		display_error(_("The payment reference/slip number cannot be empty."));
		set_focus('ref');
		return false;
	} 
		
	if (strlen($_POST['bank_account']) == 0) 
	{
		display_error(_("The payment reference/slip number cannot be empty."));
		set_focus('ref');
		return false;
	} 
	
	if (!check_num('amount', 0)) 
	{
		display_error(_("The paid amount must be numeric and greater than or equal to 0."));
		set_focus('amount');
		return false;		
	} 
	if (strlen($_POST['bank_branch']) == 0) 
	{
		display_error(_("The bank branch cannot be empty."));
		set_focus('bank_branch');
		return false;		
	} 
	return true;
}

//--------------------------------------------------------------------------------------------

function handle_submit()
{
	global $path_to_root, $new_customer, $Ajax;

	if (!can_process())
		return;
		
{
////////////////////////////////////////////////////////////////////////////
/////First check if the request is already logged, then update
//////////////////////////////////////////////////////////////////////////// 
    $sql= "SELECT 1 as okay FROM ".TB_PREF."pay_advice WHERE order_no = "
		.db_escape($_POST['order_no'])." and request_status='Planned'";
	$result = db_query($sql,"check failed");
	$myrow_chk = db_fetch_row($result);
 
 	begin_transaction();
	if (!($myrow_chk[0]==1))    
	{ 	//it is a new customer request 
		$sql = "INSERT INTO ".TB_PREF."pay_advice (id, type, debtor_no, branch_id, order_no, bank_act, bank_branch, ref, 
		trans_date, amount, note, created_by, created_date) 
			VALUES (PAY_ADVICE_ID_SEQ.nextval,12,".db_escape($_POST['debtor_no']) .",".db_escape($_POST['branch_id']) .",".db_escape($_POST['order_no']) .","
			.db_escape($_POST['bank_account']) .", nvl(".db_escape($_POST['bank_branch']) .",' '), ".db_escape($_POST['ref']) .", " 
			.db_escape(date2sql($_POST['Datebanked'])) .", "
			.input_num('amount') . ", " . db_escape($_POST['note']) . ","
			.db_escape($_SESSION['wa_current_user']->loginname ) . ",sysdate)";
		db_query($sql,"The customer request could not be completed");

		display_notification(_("A new customer payment advice has been added."));
		$Ajax->activate('_page_body');
	}
   else 
   {
///////////////////////////////////////////////////////////////////////////

		$sql = "UPDATE ".TB_PREF."pay_advice SET " .
           "bank_act=".db_escape($_POST['bank_account']) . ", 
            ref=".db_escape($_POST['ref']) . ", 
            trans_date=" .db_escape(date2sql($_POST['Datebanked'])). ",			
            amount=" . input_num('amount'). ", 
			bank_branch= nvl(".db_escape($_POST['bank_branch']). ", ' '),	
            note=".db_escape($_POST['note']) . ",
			version=version+1		
            WHERE order_no = ".db_escape($_POST['order_no']).
			"and request_status='Planned'";
		 //display_notification ($sql);
		 db_query($sql,"The customer could not be updated");

//		update_record_status($_POST['order_id'], $_POST['inactive'],
//			'debtors_master', 'debtor_no');
		$Ajax->activate('order_id1'); // in case of status change
		display_notification(_("Customer payment advice has been updated."));
	}
	} 
		commit_transaction();				
		
}
//--------------------------------------------------------------------------------------------

if (isset($_POST['submit'])) 
{
	handle_submit();
		unset($_POST['order_id']);
		$new_padvice = false;
		$Ajax->activate('_page_body');

}
else $new_padvice = true;
//-------------------------------------------------------------------------------------------- 

if (isset($_POST['delete'])) 
{

	//the link to delete a selected record was clicked instead of the submit button

	$cancel_delete = 0;

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'debtor_trans'
	$sel_id = db_escape($_POST['order_id']);
	$sql= "SELECT COUNT(*) FROM ".TB_PREF."debtor_trans WHERE debtor_no=$sel_id";
	$result = db_query($sql,"check failed");
	$myrow = db_fetch_row($result);
	if ($myrow[0] > 0) 
	{
		$cancel_delete = 1;
		display_error(_("This customer cannot be deleted because there are transactions that refer to it."));
	} 
	else 
	{
		$sql= "SELECT COUNT(*) FROM ".TB_PREF."sales_orders WHERE debtor_no=$sel_id";
		$result = db_query($sql,"check failed");
		$myrow = db_fetch_row($result);
		if ($myrow[0] > 0) 
		{
			$cancel_delete = 1;
			display_error(_("Cannot delete the customer record because orders have been created against it."));
		} 
		else 
		{
			$sql = "SELECT COUNT(*) FROM ".TB_PREF."cust_branch WHERE debtor_no=$sel_id";
			$result = db_query($sql,"check failed");
			$myrow = db_fetch_row($result);
			if ($myrow[0] > 0) 
			{
				$cancel_delete = 1;
				display_error(_("Cannot delete this customer because there are branch records set up against it."));
				//echo "<br> There are " . $myrow[0] . " branch records relating to this customer";
			}
		}
	}
	
	if ($cancel_delete == 0) 
	{ 	//ie not cancelled the delete as a result of above tests
		$sql = "DELETE FROM ".TB_PREF."debtors_master WHERE debtor_no=$sel_id";
		db_query($sql,"cannot delete customer");

		display_notification(_("Selected customer has been deleted."));
		unset($_POST['order_id']);
		$new_customer = true;
		$Ajax->activate('_page_body');
	} //end if Delete Customer
}

check_db_has_sales_types(_("There are no sales types defined. Please define at least one sales type before adding a customer."));
 
start_form();
if ($new_padvice) 
{ 

////////////////////////
	start_table("class = 'tablestyle_noborder'");
	start_row();
	padvice_list_cells(_("Select a Sales Order: "), 'order_id1', null,
		_('Select Sales Order'), true, check_value('show_inactive'));
	check_cells(_("Show inactive:"), 'show_inactive', null, true);
	end_row();	
	label_row("<br>", "");
	end_table();
    if ($_POST['order_id1'] > 0)
	submenu_view(_("&View This Order"), ST_SALESORDER, $_POST['order_id1']);
    
    if (get_post('_show_inactive_update')) 
	{
		$Ajax->activate('order_id1');
		//set_focus('order_id');
	}
//////////////////////////////////////////////////////////////////

	$sql = "SELECT order_no, debtor_no, branch_code branch_id, deliver_to, customer_ref, delivery_date, ourorder_status
		FROM  ".TB_PREF."sales_orders
			where trans_type = 30
			and ourorder_status='Planned'
			and order_no=".db_escape($_POST['order_id1']);

	$result = db_query($sql,"Sales Order information could not be retrieved.");
	$myrow = db_fetch($result);

start_outer_table($table_style2, 5);
table_section(1);

table_section_title(_("______________Order Summary______________"));
label_row("<br>","");

////text_row(_("Customer Code:"), 'custcode', $_POST['custcode'], 10, 30);
//Added: Changed this form text_row to text_cells
label_row(_("Order #:"), $myrow['order_no'], "class='tableheader2'");
		
hidden('order_no', $myrow['order_no']);

label_row(_("Customer #:"), $myrow['debtor_no'], "class='tableheader2'");
		
hidden('debtor_no', $myrow['debtor_no']);


//text_row(_("Customer Name:"), 'CustName', $_POST['CustName'], 40, 80);
label_row(_("Deliver To:"), $myrow['deliver_to'] , "class='tableheader2'");
hidden('deliver_to', $myrow['deliver_to'] );

hidden('branch_id', $myrow['branch_id']);


label_row(_('Customer Ref:'),$myrow['customer_ref'], "class='tableheader2'");
//hidden('customer_ref', $_POST['customer_ref']);

//textarea_row(_("Address:"), 'address', $_POST['address'], 35, 5);

label_row(_('Delivery Date:'), sql2date($myrow['delivery_date']), "class='tableheader2'");
//hidden('delivery_date', $_POST['delivery_date']);

label_row(_("Order Status:"), $myrow['ourorder_status'] , "class='tableheader2'");
//hidden('ourorder_status', $_POST['ourorder_status']);

table_section(2);

table_section_title(_("Customer Payment Advice"));
label_row("<br>","");

amount_row(_("Amount Paid:"), 'amount');

bank_accounts_list_row(_("Into Bank Account:"), 'bank_account', null, true);

//text_row(_("Reference:"), 'ref', null, 20, 40);
		
text_row(_("Bank Branch:"), 'bank_branch', null,20,40);

text_row(_("Ref/Slip #:"), 'ref', null,20,40);

date_row(_("Date of Deposit:"), 'Datebanked', '', true, 0, 0, 0, null, true);

textarea_row(_("Note:"), 'note', null, 22, 4);
		
label_row(_("Raised by:"), $_SESSION['wa_current_user']->loginname );

set_focus('amount');

//percent_row(_("Payment Discount:"), 'pymt_discount', $_POST['order_no']);

//payment_terms_list_row(_("Payment Terms:"), 'payment_terms', $_POST['order_no']);

//
//Added: Change credit status to view only text item
//This was changed back, and now will be removed from update 
//credit_status_list_row(_("Credit Status:"), 'credit_status', $_POST['credit_status']); 
//label_row(_("Credit Status:"),$_POST['credit_status']);
///
/*$dim = get_company_pref('use_dimension');
if ($dim >= 1)
	dimensions_list_row(_("Dimension")." 1:", 'dimension_id', $_POST['dimension_id'], true, " ", false, 1);
if ($dim > 1)
	dimensions_list_row(_("Dimension")." 2:", 'dimension2_id', $_POST['dimension2_id'], true, " ", false, 2);
if ($dim < 1)
	hidden('dimension_id', 0);
if ($dim < 2)
	hidden('dimension2_id', 0);
textarea_row(_("General Notes:"), 'notes', null, 35, 5);
record_status_list_row(_("Customer status:"), 'inactive');
*/
end_outer_table(1);

}

div_start('controls');
{
if ($new_padvice)
{	submit_center('submit', _("Save Payment Advice"), 
	  _('Request Contract Terms Approval'), @$_REQUEST['popup'] ? true : 'default');
		$new_padvice = false;
}
	submit_return('select', get_post('order_id'), _("Select this customer and return to document entry."));

}
div_end();
hidden('popup', @$_REQUEST['popup']);
end_form();
end_page();

?>
