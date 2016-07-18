<?php

$page_security = 'SA_SOCONFIRM';
$path_to_root = "../../..";
include_once($path_to_root . "/sales/includes/cart_class.inc");

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");

include_once($path_to_root . "/sales/includes/sales_ui.inc");
//include_once($path_to_root . "/sales/includes/sales_db.inc");

include_once($path_to_root . "/simplex/sales/includes/db/confirm_order_db.inc");

//
include_once($path_to_root . "/reporting/includes/reporting.inc");
include_once($path_to_root . "/simplex/includes/email_messaging.inc");
include_once($path_to_root . "/admin/db/company_db.inc");

$order_no = "" ;

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 600);



//form can start here
start_form();
//
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//starting a big change from here. It could be moved to inc later
if (isset($_POST['ConfirmOrder']) || isset($_POST['CancelOrder'])) {

	//display_heading("Confirm Sales Order");
page(_($help_context = "Confirm Sales Order"), true, false, "", $js);

//echo "Hello there".$_REQUEST["trans_no"];
global $nonfin_audit_trail;
  $order_no  = $_REQUEST["trans_no"];
  $ord_no  = $_REQUEST["trans_no"];
  $trans_type = $_REQUEST["trans_type"];
  $amount =  $_REQUEST["items_total"];
  $current_state =  $_REQUEST["ourorder_status"];
  $customer_id = $_REQUEST["customer_id"];
  //display_notification ("Debtor no ".$customer_id);

  if ($current_state != 'Planned')
  {display_error( _("Order #".$order_no. " not in Planned state and thus cannot be Confirmed or Cancelled."));}

  else 
  if (isset($_POST['ConfirmOrder']))
  {
     $credit_blocked = 1 ;
	 $credit_limit = 0;
     $credit_info = get_customer_credit_info($customer_id);
	 $myrow = get_company_prefs();
     if (isset($credit_info)) {     $credit_blocked = $credit_info[0] ;    $credit_limit = $credit_info[1] ; }
     if ( $credit_blocked != 1)//not sales order blocked
	 {	if (confirm_sales_order ($customer_id, $order_no, $trans_type, $amount, $current_state, $credit_limit)) 
		{	
			
			approve_sales_tran_approval($order_no, $_SESSION["wa_current_user"]->loginname, ST_SALESORDER);
			 // Send email
			//$body = get_doc_link($ord_no, "&Email This Order", true, ST_SALESORDER, false, 'menu_option', null, 1, 0);
			
			$body = "Sales Order has been Cancelled";
			$subject = " Sales Order Cancel" ;
			sendmail(90, $body, $_SESSION["wa_current_user"]->loginname,$order_no, $subject);  // sales order approval; = 90
			if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'SALES ORDER CANCELLATION','A',$ip,'SALES ORDER # %d ' . $order_no. " CANCELLED ");
			}
	         display_notification_centered(sprintf( _("Order # %d has been confirmed."),$order_no));
		}
			 
     }
	 else display_error ( _("The selected customer account is currently on hold. 
	 									Please contact the credit control personnel to discuss."));
  }
  else 
		if (cancel_sales_order ($order_no, $trans_type, $amount, $current_state)) 
			{		
			cancel_sales_tran_approval($order_no,$_SESSION["wa_current_user"]->loginname,ST_SALESORDER);
			if($nonfin_audit_trail)
			{
				$ip = preg_quote($_SERVER['REMOTE_ADDR']);
				add_nonfin_audit_trail(0,0,0,0,'SALES ORDER CONFIRMATION','A',$ip,'SALES ORDER # %d ' . $order_no. " CANCELLED ");
			}
	        display_notification_centered(sprintf( _("Order # %d has been cancelled."),$order_no));
			}
    
 
	submenu_option(_("Select Another Sales Order To Confirm &Order"),
		"/simplex/sales/inquiry/sales_orders_confirm_view.php?type=".$trans_type,'prtopt');

	submenu_view(_("&View This Order"), ST_SALESORDER, $order_no);

	submenu_print(_("&Print This Order"), ST_SALESORDER, $order_no,null);
	submenu_print(_("&Email This Order to Customer"), ST_SALESORDER, $order_no, null, 1);

     submenu_option(_("Main Menu"),	"");

	set_focus('prtopt');


	display_footer_exit();

/*	copy_to_cart();
	$modified = ($_SESSION['Items']->trans_no != 0);
	$so_type = $_SESSION['Items']->so_type;
	$_SESSION['Items']->write(1);
	if (count($messages)) { // abort on failure or error messages are lost
		$Ajax->activate('_page_body');
		display_footer_exit();
	}
	$trans_no = key($_SESSION['Items']->trans_no);
	$trans_type = $_SESSION['Items']->trans_type;
	new_doc_date($_SESSION['Items']->document_date);
	processing_end();
	if ($modified) {
		if ($trans_type == ST_SALESQUOTE)
			meta_forward($_SERVER['PHP_SELF'], "UpdatedQU=$trans_no");
		else
			meta_forward($_SERVER['PHP_SELF'], "UpdatedID=$trans_no");
	} elseif ($trans_type == ST_SALESORDER) {
		meta_forward($_SERVER['PHP_SELF'], "AddedID=$trans_no");
	} elseif ($trans_type == ST_SALESQUOTE) {
		meta_forward($_SERVER['PHP_SELF'], "AddedQU=$trans_no");
	} elseif ($trans_type == ST_SALESINVOICE) {
		meta_forward($_SERVER['PHP_SELF'], "AddedDI=$trans_no&Type=$so_type");
	} else {
		meta_forward($_SERVER['PHP_SELF'], "AddedDN=$trans_no&Type=$so_type");
	}
*/


end_page(true,false,true);
}

//-----------------------------------------------------------------------------
//
else
//continue the original view code lines
{

if ($_GET['trans_type'] == ST_SALESQUOTE)
{
	page(_($help_context = "View Sales Quotation"), true, false, "", $js);
	display_heading(sprintf(_("Sales Quotation #%d"),$_GET['trans_no']));
}
else
{
	page(_($help_context = "View Sales Order"), true, false, "", $js);
	display_heading(sprintf(_("Sales Order #%d"),$_GET['trans_no']));
}

if (isset($_SESSION['View']))
{
	unset ($_SESSION['View']);
}

$_SESSION['View'] = new Cart($_GET['trans_type'], $_GET['trans_no'], true);
start_table("$table_style2 width=95%", 5);
echo "<tr valign=top><td>";
display_heading2(_("Order Information"));
/* if ($_GET['trans_type'] != ST_SALESQUOTE)
{
	echo "</td><td>";
	display_heading2(_("Deliveries"));
	echo "</td><td>";
	display_heading2(_("Invoices/Credits"));
} */
echo "</td></tr>";

echo "<tr valign=top><td>";

start_table("$table_style width=95%");
label_row(_("Customer Name"), $_SESSION['View']->customer_name, "class='tableheader2'",
	"colspan=3");
start_row();
label_cells(_("Customer Order Ref."), $_SESSION['View']->cust_ref, "class='tableheader2'");
label_cells(_("Deliver To Branch"), $_SESSION['View']->deliver_to, "class='tableheader2'");
end_row();
start_row();
label_cells(_("Ordered On"), $_SESSION['View']->document_date, "class='tableheader2'");
if ($_GET['trans_type'] == ST_SALESQUOTE)
	label_cells(_("Valid until"), $_SESSION['View']->due_date, "class='tableheader2'");
else
	label_cells(_("Requested Delivery"), $_SESSION['View']->due_date, "class='tableheader2'");
end_row();
start_row();
label_cells(_("Order Currency"), $_SESSION['View']->customer_currency, "class='tableheader2'");
label_cells(_("Deliver From Location"), $_SESSION['View']->location_name, "class='tableheader2'");
end_row();

start_row();
label_cells(_("Invoice Number"),    $_SESSION['View']->reference,       "class='tableheader2'");
label_cells(_("Order Status"), $_SESSION['View']->ourorder_status, "class='tableheader2'");

//label_row(_("Reference"), $_SESSION['View']->reference, "class='tableheader2'", "colspan=3");
end_row();


label_row(_("Delivery Address"), nl2br($_SESSION['View']->delivery_address),
	"class='tableheader2'", "colspan=3");

label_row(_("Telephone"), $_SESSION['View']->phone, "class='tableheader2'", "colspan=3");
label_row(_("E-mail"), "<a href='mailto:" . $_SESSION['View']->email . "'>" . $_SESSION['View']->email . "</a>",
	"class='tableheader2'", "colspan=3");
label_row(_("Comments"), $_SESSION['View']->Comments, "class='tableheader2'", "colspan=3");
end_table();

if ($_GET['trans_type'] != ST_SALESQUOTE)
{
 // remove delivies for etisalat
	/* echo "</td><td valign='top'>";

	start_table($table_style);
	display_heading2(_("Delivery Notes"));


	$th = array(_("#"), _("Ref"), _("Date"), _("Total"));
	table_header($th);

	$sql = "SELECT * FROM ".TB_PREF."debtor_trans WHERE type=".ST_CUSTDELIVERY." AND order_=".db_escape($_GET['trans_no']);
	$result = db_query($sql,"The related delivery notes could not be retreived");

	$delivery_total = 0;
	$k = 0;

	while ($del_row = db_fetch($result))
	{

		alt_table_row_color($k);

		$this_total = $del_row["ov_freight"]+ $del_row["ov_amount"] + $del_row["ov_freight_tax"]  + $del_row["ov_gst"] ;
		$delivery_total += $this_total;

		label_cell(get_customer_trans_view_str($del_row["type"], $del_row["trans_no"]));
		label_cell($del_row["reference"]);
		label_cell(sql2date($del_row["tran_date"]));
		amount_cell($this_total);
		end_row();

	}

	label_row(null, price_format($delivery_total), "", "colspan=4 align=right");

	end_table();
	echo "</td><td valign='top'>";

	start_table($table_style);
	display_heading2(_("Sales Invoices"));

	$th = array(_("#"), _("Ref"), _("Date"), _("Total"));
	table_header($th);

	$sql = "SELECT * FROM ".TB_PREF."debtor_trans WHERE type=".ST_SALESINVOICE." AND order_=".db_escape($_GET['trans_no']);
	$result = db_query($sql,"The related invoices could not be retreived");

	$invoices_total = 0;
	$k = 0;

	while ($inv_row = db_fetch($result))
	{

		alt_table_row_color($k);

		$this_total = $inv_row["ov_freight"] + $inv_row["ov_freight_tax"]  + $inv_row["ov_gst"] + $inv_row["ov_amount"];
		$invoices_total += $this_total;

		label_cell(get_customer_trans_view_str($inv_row["type"], $inv_row["trans_no"]));
		label_cell($inv_row["reference"]);
		label_cell(sql2date($inv_row["tran_date"]));
		amount_cell($this_total);
		end_row();

	}

	label_row(null, price_format($invoices_total), "", "colspan=4 align=right");

	end_table();

	display_heading2(_("Credit Notes"));

	start_table($table_style);
	$th = array(_("#"), _("Ref"), _("Date"), _("Total"));
	table_header($th);

	$sql = "SELECT * FROM ".TB_PREF."debtor_trans WHERE type=".ST_CUSTCREDIT." AND order_=".db_escape($_GET['trans_no']);
	$result = db_query($sql,"The related credit notes could not be retreived");

	$credits_total = 0;
	$k = 0;

	while ($credits_row = db_fetch($result))
	{

		alt_table_row_color($k);

		$this_total = $credits_row["ov_freight"] + $credits_row["ov_freight_tax"]  + $credits_row["ov_gst"] + $credits_row["ov_amount"];
		$credits_total += $this_total;

		label_cell(get_customer_trans_view_str($credits_row["type"], $credits_row["trans_no"]));
		label_cell($credits_row["reference"]);
		label_cell(sql2date($credits_row["tran_date"]));
		amount_cell(-$this_total);
		end_row();

	}

	label_row(null, "<font color=red>" . price_format(-$credits_total) . "</font>",
		"", "colspan=4 align=right");


	end_table();

	echo "</td></tr>";

	end_table(); */
}
echo "<center>";
if ($_SESSION['View']->so_type == 1)
	display_note(_("This Sales Order is used as a Template."), 0, 0, "class='currentfg'");
display_heading2(_("Line Details"));

start_table("colspan=9 width=95% $table_style");
$th = array(_("Item Code"), _("Item Description"), _("Quantity"), _("Unit"),
	 _("Quantity Delivered"));  //_("Price"), _("Discount"), _("Total"),
table_header($th);

$k = 0;  //row colour counter

foreach ($_SESSION['View']->line_items as $stock_item) {

	$line_total = round2($stock_item->quantity * $stock_item->price * (1 - $stock_item->discount_percent),
	   user_price_dec());

	alt_table_row_color($k);

	label_cell($stock_item->stock_id);
	label_cell($stock_item->item_description);
	$dec = get_qty_dec($stock_item->stock_id);
	qty_cell($stock_item->quantity, false, $dec);
	label_cell($stock_item->units);
	//amount_cell($stock_item->price);
	hidden('price',$stock_item->price);
	//amount_cell($stock_item->discount_percent * 100);
	hidden('Disc',$stock_item->discount_percent * 100);
	//	amount_cell($line_total);
	hidden('line_total',$line_total);

	qty_cell($stock_item->qty_done, false, $dec);
	end_row();
}

$items_total = $_SESSION['View']->get_items_total();

$display_total = price_format($items_total + $_SESSION['View']->freight_cost);

//label_row(_("Shipping"), price_format($_SESSION['View']->freight_cost),
//	"align=right colspan=6", "nowrap align=right", 1);
//label_row(_("Total Order Value"), $display_total, "align=right colspan=6",
//	"nowrap align=right", 1);

end_table(2);



	$cancelorder = _("Cancel Order");
	$porder = _("Place Order");
	$corder = _("Commit Order Changes");
	//added $ourcorder to sales_order_entry.php
	$ourcorder = _("Confirm Order");
	//echo "here we go : ".$_GET['trans_no'];
	//echo "here we go : ".$_GET['trans_type'];
	//used this to transfer the transaction number and type for confirmation
    hidden('trans_no', $_GET['trans_no']);
    hidden('trans_type', $_GET['trans_type']);
    hidden('ourorder_status',  $_SESSION['View']->ourorder_status);
	hidden('customer_id', $_SESSION['View']->customer_id);
//$items_total = $_SESSION['View']->get_items_total();
	hidden('items_total',  $_SESSION['View']->get_items_total());
	  //added this to to display Confirm the order

	  	   	//submit_center_first('ConfirmOrder', $ourcorder, _('Confirm Order'), 'default');
	  	    // submit_js_confirm('ConfirmOrder', _('You are about to confirm this sales order.\nDo you want to continue?'));
	  	    submit_center_last('CancelOrder', $cancelorder,
	  	     _('Cancels document entry or removes sales order when editing an old document'), 'cancel');
		     submit_js_confirm('CancelOrder', _('You are about to cancel this sales order.\nDo you want to continue?'));
//end the original view code lines
end_page(true,false,false);
}
end_form();
//and end here


?>
