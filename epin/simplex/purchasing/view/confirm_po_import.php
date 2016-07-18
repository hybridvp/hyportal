<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/

//$page_security = 'SA_SUPPTRANSVIEW';
//changed to 

$page_security = 'SA_PURCHREQ'; //for the time being
$path_to_root = "../../..";
include($path_to_root . "/simplex/purchasing/includes/pr_class.inc");

include($path_to_root . "/includes/session.inc");
include($path_to_root . "/simplex/purchasing/includes/requisition_ui.inc");
include_once($path_to_root . "/simplex/includes/ui/our_ui_lists.inc");


$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
page(_($help_context = "Forward/Approve Imported Order"), true, false, "", $js);

function can_authorise($approved_by, $amount, $msg=null)
{
   	$sql = "SELECT approval_limit FROM ".TB_PREF."authority_lmt 
		WHERE username = ".db_escape($approved_by);

   	$result = db_query($sql, "The authoriser information could not be retrieved.");
    
    $myrow = db_fetch_row($result);
    
	if ($amount <=$myrow[0]) return true;

    if (is_null($msg))
		Display_notification ("You do not have the authority to approve this document, please forward to a higher level.");
	else 
		Display_notification ($msg);

   return  false;
}


function user_can_authorise($param, $amount, $approved_by1,$approved_by2,  $approved_by3, 
									$approver_status1, $approver_status2, $approver_status3) 
{
if ($approver_status1=='Pending' 
			&& $approved_by1==$_SESSION['wa_current_user']->loginname 
					&& can_authorise( $approved_by1, $amount)) { return true ;}
else
{ 	if ($param > 1 && $approver_status2=='Pending' 
			&& $approved_by2==$_SESSION['wa_current_user']->loginname 
					&& can_authorise( $approved_by2, $amount)) { return true ;}
	else 
		if ($param > 2 && $approver_status3=='Pending' 
			&& $approved_by3==$_SESSION['wa_current_user']->loginname 
					&& can_authorise( $approved_by3, $amount)) { return true ;}
}					
return false ;
}

function display_next_authoriser($param, $approver1, $approver2, $approver3)
{
   
	if ( ($param==2 && $approver1!='Approved') || ($param==3 && $approver2!='Approved'))
        return true ;
     return false ;		
}

function costcentre_desc ($costcentre)
{
   	$sql = "SELECT name FROM ".TB_PREF."analysis_codes 
		WHERE code = ".db_escape($costcentre);

   	$result = db_query($sql, "The analysis code description cannot be retrieved");
    
    $myrow = db_fetch_row($result);
    
	return $costcentre.": ".$myrow[0];
}


function book_budget_entry($pr_no, $amount) 
{

return true ;
}


function budget_entry_exists($pr_no, $account, $costcentre)
{
   	$sql = "SELECT 1 okay FROM ".TB_PREF."budget_trans_details
		WHERE trans_no =".db_escape($pr_no)." and  type ='PR' and direction=-1 and action = 'Approval'
		and account =".db_escape($account)." and  costcentre =".db_escape($costcentre);


   	$result = db_query($sql, "Budget information could not be retrieved");
    
    $myrow = db_fetch_row($result);
    
	if ($myrow[0]==1) return true;
 return false;
}

function get_budgeted_amount($account, $costcentre, $cost, $trandate = null)
{
//get budgeted amount 
   $sql = "SELECT  SUM(amount) budget FROM ".TB_PREF."budget_master bm 
			WHERE bm.account = ".db_escape($account)."
			and bm.costcentre = ".db_escape($costcentre)."
			and status = 'A'
			"//and bm.tran_date =".date2sql($tran_date)
			;
//    display_notification ($sql) ;
   	$result = db_query($sql, "Budget could not be retrieved");  
    $myrow = db_fetch_row($result);
	return $myrow[0] ;
}

function get_allocated_spent_budget($account, $costcentre, $cost, $trandate=null)
{
//get allocated and spent budget
   $sql = "SELECT  SUM(amount*direction) spent FROM ".TB_PREF."budget_trans_details btd 
			WHERE btd.account = ".db_escape($account)."
			and btd.costcentre = ".db_escape($costcentre)."
			and btd.status = 'A'
			"//and btd.trans_date =".date2sql($tran_date)
			;
 //  	display_notification ($sql) ;
	
	$result = db_query($sql, "Spent budget could not be retrieved");
    
    $myrow = db_fetch_row($result);
    
	return $myrow[0] ;
   
}

function budget_available($account, $costcentre, $cost, $trandate = null)
{
//get budgeted amount 

	$budgeted_amount = get_budgeted_amount($account, $costcentre, $cost, $trandate);
	$allocated_spent_budget = get_allocated_spent_budget($account, $costcentre, $cost, $trandate);
    if (($budgeted_amount+$allocated_spent_budget-$cost) < 0 ) 
	    display_error ("Budgeted amount exceeded by ".($budgeted_amount+($allocated_spent_budget-$cost)).
			" : Purchase requisition could not be approved") ;
	return true ;
}

function budget_cover($pr_no, $amount)
{

//First update all entries relating to this pr to U=Unused.
		$sql = "UPDATE ".TB_PREF."budget_trans_details SET status='U'
		WHERE trans_no =".db_escape($pr_no)." and  type ='PR' and direction=-1 and action = 'Approval'";

		db_query($sql, "The budget detail transactions could not be updated.");

//Loop through the PR and insert or update the budget transactions 
//laolu added NVL for costcentre
$sql = "SELECT  pr_no, stkm.inventory_account account, prd.item_code, nvl(prd.costcentre,0) costcentre,
			sum(prd.quantity_ordered*prd.unit_price) cost
			FROM ".TB_PREF."purch_req_details prd, ".TB_PREF."stock_master stkm 
			WHERE prd.item_code = stkm.stock_id and pr_no = ".db_escape($pr_no)."
			group by pr_no, inventory_account, item_code, costcentre ";

$result = db_query($sql,"Unable to fetch budget information");

$marked_reverse = false ;
while ($myrow = db_fetch($result)) 
{
  if (budget_available($myrow['account'], $myrow['costcentre'], $myrow['cost'], ''))
  {   if (budget_entry_exists($myrow['pr_no'], $myrow['account'], $myrow['costcentre']))
		$sql = "UPDATE ".TB_PREF."budget_trans_details SET status='T', amount = ".$myrow['cost']."
		WHERE trans_no =".db_escape($pr_no)." and  type ='PR' and direction=-1 and action = 'Approval' 
		and account=".db_escape($myrow['account'])." and costcentre=".db_escape($myrow['costcentre']) ;
	 else
		$sql = "INSERT INTO ".TB_PREF."budget_trans_details(id,trans_no, action, trans_date, 
				account, costcentre, item_code, amount, direction, 
				created_by, created_date, status) VALUES (BUDGET_TRANS_DETAILS_ID.NEXTVAL,".db_escape($myrow['pr_no']).", 'Approval',sysdate,".
				db_escape($myrow['account']).",".db_escape($myrow['costcentre']).",".db_escape($myrow['item_code']).",".
				$myrow['cost'].", -1, 'system',sysdate,'T')";
				
      db_query($sql, "The budget detail transactions could not be processed.");
   }
   else $marked_reverse = true ;
} //END WHILE LIST LOOP
//if any of the lines has no budget, then make all the budget reserved for this Pr up to now available, otherwise make permanent reservation.
		if ($marked_reverse )
				$sql = "UPDATE ".TB_PREF."budget_trans_details SET status='U'
				WHERE trans_no =".db_escape($pr_no)." and  type ='PR' and direction=-1 and action = 'Approval'";
		else 
				$sql = "UPDATE ".TB_PREF."budget_trans_details SET status='A'
				WHERE trans_no =".db_escape($pr_no)." and  type ='PR' and direction=-1 and action = 'Approval'";

		db_query($sql, "The budget detail transactions could not be updated.");

    return true ;
   	
	$sql = "SELECT amount already FROM ".TB_PREF."budget_trans_details  
		WHERE type = 'PR' 
		and action = 'Approval'
		and trans_no=".db_escape($pr_no);

   	$result = db_query($sql, "The budget transaction details could not be retrieved!");
    
    $myrow = db_fetch_row($result);
    
	if ($amount == $myrow[0]) return true;
	else
	{
	   return book_budget_entry($pr_no, $amount) ;
	}
}

function current_approver($approver1, $approver2, $approver3, $status1, $status2, $status3)
{

}
function  approve_pr ( $no_param, $pr_no, $next_approver,  $approver_status1,  
					$approver_status2,  $approver_status3, $items_total)
{
        //display_notification($no_param);
		$initsql = "UPDATE ".TB_PREF."purch_reqs SET ";
		$sql = $initsql;
		if ($approver_status1 != 'Approved') 
		  { 
		  	$sql .= "approver_status1= 'Approved' ";
			if ($no_param==1) 
				$sql .= ", status = 'Approved' WHERE approver_status1 != 'Approved' " ; 
				else {$sql .= ", status = 'Pending', approved_by2= ".
							db_escape($next_approver). "  WHERE approver_status1 != 'Approved' " ;}
		  }
		else 
			if ($approver_status2 != 'Approved') 
			{
			   $sql .= "approver_status2= 'Approved'" ; 
				if ($no_param==2) $sql .= ", status = 'Approved'  WHERE approver_status2 != 'Approved' "  ; 
				else {$sql .= ", status = 'Pending', approved_by3= ".
							db_escape($next_approver)."  WHERE approver_status2 != 'Approved' " ;}		   
			}
			else 
				if ($approver_status3 != 'Approved') 
				{   $sql .= "approver_status3= 'Approved',  status = 'Approved' WHERE approver_status3 != 'Approved' "  ;	
				 
				 }  	
				else 
				{
				   Display_error ("Document fully authorised already");
				   return true;
				}
		//display_notification ($sql) ; 		
		if ($initsql!=$sql)		
		{	$sql .= "  and status != 'Approved' and pr_no =".db_escape($pr_no)  ;
			db_query($sql, "The purchase requisition could not be approved.");
			display_notification ("Document approved");
		}
return true;
}

function  set_next_approver ($trans_no, $next_approver, $approver_status1, $approver_status2, $approver_status3)
{
			$initsql  = "UPDATE ".TB_PREF."purch_reqs SET ";
			$sql = $initsql ;
		    if ($approver_status1!='Approved')
				$sql .= "approved_by1 = ".db_escape($_POST['next_approver'])." , approver_status1 = 'Pending' " ;
			else if ($approver_status2!='Approved')
				$sql .= "approved_by2 = ".db_escape($_POST['next_approver'])." , approver_status2 = 'Pending' " ;
			else if ($approver_status3!='Approved')
				$sql .= "approved_by3 = ".db_escape($_POST['next_approver'])." , approver_status3 = 'Pending'" ;
		
		if ($initsql==$sql)
		    display_notification ("Purhcase requisition could not forwarded to ".$next_approver." contact your system administrator");
		else
		{
			$sql .= " where pr_no = ".db_escape ($_POST['trans_no']);	
			db_query($sql, "The next approver information could not be updated.");			
  			display_notification ("Purhcase requisition forwarded to ".$next_approver);
		}
}

/*
function can_approve ($pr_no, $approved_by, $amount)
{
   if (!can_authorise($approved_by, $amount,null)) 
   {
   	return false;
   }
   if (!budget_cover($pr_no, $amount)) return false;
   return true ;
}
*/
 if (isset($_POST['ForwardOrder']))
  {
  if (can_authorise($_POST['next_approver'], $_POST['items_total'],
  		 "The selected authoriser ".$_POST['next_approver']." does not have the authiority to approve this document") )
        set_next_approver ($_POST['trans_no'], $_POST['next_approver'], $_POST['approved_by1'], $_POST['approved_by2'], $_POST['approved_by3']);
 

   }	
   
  if (isset($_POST['ApproveOrder']))
  {
  
 //Test if the current authoriser 

	
    if (can_authorise($_POST['approved_by1'], $_POST['items_total'], null) && budget_cover($_POST['trans_no'], $_POST['items_total']))
		 {   approve_pr ( $_POST['no_authoriser'],  $_POST['trans_no'], $_POST['next_approver'],  $_POST['approver_status1'],  
					$_POST['approver_status2'],  $_POST['approver_status3'],$_POST['items_total']);
					    //display_notification( "Authorising Purchase Req ".$_REQUEST['approver_status1']) ;
		 }
   }	 
if (!isset($_REQUEST['trans_no']))
{
     Display_error("Invalid access! module called without the right parameters");
     end_page(true);
	die ("<br>" . _("This page must be called with a purchase requisition number to review."));

}

//form can start here
start_form();

display_heading(_("Purchase Requisition") . " #" . $_REQUEST['trans_no']);

$purchase_order = new purch_order;

read_pr($_REQUEST['trans_no'], $purchase_order);
//read_pr(13, $purchase_order);

//	display_notification ( " here listing  po->1 ". $purchase_order->approver_status1."  po->2". 
//	$purchase_order->approver_status2."  po->3 ". $purchase_order->approver_status3) ;

echo "<br>";
display_pr_summary($purchase_order, true);



start_table("$table_style width=90%", 6);
echo "<tr><td valign=top>"; // outer table

display_heading2(_("Line Details"));

start_table("colspan=9 $table_style width=100%");

$th = array(_("Item Code"), _("Item Description"), _("Cost Centre"), _("Quantity"), _("Unit"), _("Price"),
	_("Line Total"), _("Requested By"), _("Quantity Received"), _("Quantity Invoiced"));
table_header($th);
$total = $k = 0;
$overdue_items = false;

foreach ($purchase_order->line_items as $stock_item)
{

	$line_total = $stock_item->quantity * $stock_item->price;

	// if overdue and outstanding quantities, then highlight as so
	if (($stock_item->quantity - $stock_item->qty_received > 0)	&&
		date1_greater_date2(Today(), $stock_item->req_del_date))
	{
    	start_row("class='overduebg'");
    	$overdue_items = true;
	}
	else
	{
		alt_table_row_color($k);
	}

	label_cell($stock_item->stock_id);
	label_cell($stock_item->item_description);
	label_cell(costcentre_desc($stock_item->costcentre));
	$dec = get_qty_dec($stock_item->stock_id);
	qty_cell($stock_item->quantity, false, $dec);
	label_cell($stock_item->units);
	amount_decimal_cell($stock_item->price);
	amount_cell($line_total);
	label_cell($stock_item->req_del_date);
	qty_cell($stock_item->qty_received, false, $dec);
	qty_cell($stock_item->qty_inv, false, $dec);
	end_row();

	$total += $line_total;
}

$display_total = number_format2($total,user_price_dec());
label_row(_("Total Excluding Tax/Shipping"), $display_total,
	"align=right colspan=5", "nowrap align=right", 3);
end_table();

hidden('items_total', $total );
hidden('no_authoriser', $purchase_order->no_authoriser);

//first check if it is necessary to display it to the next authoriser
 $next_auth_displayed = false ;
if (display_next_authoriser($purchase_order->no_authoriser, $purchase_order->approver_status1, 
								$purchase_order->approver_status2, $purchase_order->approver_status3)) 
	{ 
	    $next_auth_displayed = true ;
		start_table("colspan=4 $table_style width=100%");
		echo "<tr> ";
		//$th = array(_("Item Code"), _("Item Description"), _("Cost Centre"), _("Quantity"), _("Unit"), _("Price"),
		//	_("Line Total"), _("Requested By"), _("Quantity Received"), _("Quantity Invoiced"));
		//table_header($th);
		label_cell("Forward to next Authoriser","width=160 colspan=1 align='left'");
		approving_list_cells(null, 'next_approver', null);
		echo "</tr>";
		end_table();
	}
else hidden ('next_approver', 'END');
if ($overdue_items)
	display_note(_("Marked items are overdue."), 0, 0, "class='overduefg'");

//----------------------------------------------------------------------------------------------------

$k = 0;

$grns_result = get_po_grns($_REQUEST['trans_no']);

if (db_num_rows($grns_result) > 0)
{

    echo "</td><td valign=top>"; // outer table

    display_heading2(_("Deliveries"));
    start_table($table_style);
    $th = array(_("#"), _("Reference"), _("Delivered On"));
    table_header($th);
    while ($myrow = db_fetch($grns_result))
    {
		alt_table_row_color($k);

    	label_cell(get_trans_view_str(ST_SUPPRECEIVE,$myrow["id"]));
    	label_cell($myrow["reference"]);
    	label_cell(sql2date($myrow["delivery_date"]));
    	end_row();
    }
    end_table();;
}

$invoice_result = get_po_invoices_credits($_REQUEST['trans_no']);

$k = 0;

if (db_num_rows($invoice_result) > 0)
{

    echo "</td><td valign=top>"; // outer table

    display_heading2(_("Invoices/Credits"));
    start_table($table_style);
    $th = array(_("#"), _("Date"), _("Total"));
    table_header($th);
    while ($myrow = db_fetch($invoice_result))
    {
    	alt_table_row_color($k);

    	label_cell(get_trans_view_str($myrow["type"],$myrow["trans_no"]));
    	label_cell(sql2date($myrow["tran_date"]));
    	amount_cell($myrow["total"]);
    	end_row();
    }
    end_table();
}

echo "</td></tr>";

end_table(1); // outer table


//----------------------------------------------------------------------------------------------------
//Check if current users is an approving authoriser or it he supposed to just forward the pr
 

	$cancelorder = _("Cancel Order");
	$porder = _("Place Order");
	$corder = _("Commit Order Changes");
	//added $ourcorder to sales_order_entry.php
	$ourcorder = _("Approve Order");

	//used this to transfer the transaction number and type for confirmation
    hidden('trans_no', $_REQUEST['trans_no']);
    //hidden('trans_type', $_REQUEST['trans_type']);
    //hidden('ourorder_status',  $_SESSION['View']->ourorder_status);
	//hidden('customer_id', $_SESSION['View']->customer_id);
	//$items_total = $_SESSION['View']->get_items_total();
	//	hidden('items_total',  $_SESSION['View']->get_items_total());
	//added this to to display Confirm the order

if (user_can_authorise($purchase_order->no_authoriser, $total ,$purchase_order->approved_by1,  
								$purchase_order->approved_by2,  $purchase_order->approved_by3, 
								$purchase_order->approver_status1, $purchase_order->approver_status2, 
								$purchase_order->approver_status3)) 
{
	  	   	submit_center_first('ApproveOrder', $ourcorder,
	  	     _('Approve Order'), 'default');
	  	     submit_js_confirm('ApproveOrder', _('You are about to confirm this sales order.\nDo you want to continue?'));
	  	    submit_center_last('CancelOrder', $cancelorder,
	  	     _('Cancels document entry or removes sales order when editing an old document'), 'cancel');
		     submit_js_confirm('CancelOrder', _('You are about to cancel this sales order.\nDo you want to continue?'));

}								
else if ($next_auth_displayed)
{
//you can only forward

	$ourcorder = _("Foward Requisition Order");
   	submit_center_first('ForwardOrder', $ourcorder,
	  	     _('Forward Requisition'), 'default');

}
//
end_page(true);
end_form();
?>