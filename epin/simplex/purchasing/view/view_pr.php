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

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
page(_($help_context = "View Purchase Requisition"), true, false, "", $js);

function costcentre_desc ($costcentre)
{
   	$sql = "SELECT name FROM ".TB_PREF."analysis_codes 
		WHERE code = ".db_escape($costcentre);

   	$result = db_query($sql, "The analysis code description cannot be retrieved");
    
    $myrow = db_fetch_row($result);
    
	return $costcentre.": ".$myrow[0];
}

if (!isset($_GET['trans_no']))
{
	die ("<br>" . _("This page must be called with a purchase requisition number to review."));
}

display_heading(_("Purchase Requisition") . " #" . $_GET['trans_no']);

$purchase_order = new purch_order;

read_pr($_GET['trans_no'], $purchase_order);
//read_pr(13, $purchase_order);

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

if ($overdue_items)
	display_note(_("Marked items are overdue."), 0, 0, "class='overduefg'");

//----------------------------------------------------------------------------------------------------

$k = 0;

$grns_result = get_po_grns($_GET['trans_no']);

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

$invoice_result = get_po_invoices_credits($_GET['trans_no']);

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

end_page(true);

?>
