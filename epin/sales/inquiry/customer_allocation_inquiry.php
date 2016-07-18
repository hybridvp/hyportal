<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
//$page_security = 'SA_SALESALLOC';
$page_security = 'SA_CUSTPAYMREP';
$path_to_root = "../..";
include($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Dealer Allocation Inquiry"), false, false, "", $js);

if (isset($_GET['customer_id']))
{
	$_POST['customer_id'] = $_GET['customer_id'];
}

//------------------------------------------------------------------------------------------------

if (!isset($_POST['customer_id']))
	$_POST['customer_id'] = get_global_customer();

start_form();

start_table("class='tablestyle_noborder'");
start_row();

customer_list_cells(_("Select a dealer: "), 'customer_id', $_POST['customer_id'], true);

date_cells(_("from:"), 'TransAfterDate', '', null, -30);
date_cells(_("to:"), 'TransToDate', '', null, 1);

cust_allocations_list_cells(_("Type:"), 'filterType', null);

check_cells(" " . _("show settled:"), 'showSettled', null);

submit_cells('RefreshInquiry', _("Search"),'',_('Refresh Inquiry'), 'default');

set_global_customer($_POST['customer_id']);

end_row();
end_table();
//------------------------------------------------------------------------------------------------
function check_overdue($row)
{
	return ($row['overdue'] == 1 
		&& (abs($row["totalamount"]) - $row["allocated"] != 0));
}

function order_link($row)
{
	return $row['order_']>0 ?
		get_customer_trans_view_str(ST_SALESORDER, $row['order_'])
		: "";
}

function systype_name($dummy, $type)
{
	global $systypes_array;

	return $systypes_array[$type];
}

/* function view_link($trans)
{
	return get_trans_view_str($trans["type"], $trans["trans_no"]);
} */

function view_link($trans)
{
	global $trans_type;
	return  get_customer_trans_view_str(ST_SALESORDER, $trans["trans_no"]);
}
function activate_link($row)
{
	global $trans_type;
	//$modify = ($trans_type == ST_SALESORDER ? "ModifyOrderNumber" : "ModifyQuotationNumber");
 //if ($row['trans_type']==32) return "" ;
 return pager_link( _("Activate EPINS in this Order"),
	"/simplex/sales/pin_activation_request.php?trans_no=" .$row['trans_no']. "&line_no=".$row['line_no'] . "&start_serial=" .$row['start_serial'] . "&end_serial=" . $row['end_serial'] , ICON_ADD);

}
function deactivate_link($row)
{
	global $trans_type;
	//$modify = ($trans_type == ST_SALESORDER ? "ModifyOrderNumber" : "ModifyQuotationNumber");
 //if ($row['trans_type']==32) return "" ;
 return pager_link( _("Deactivate EPINS in this Order"),
	"/simplex/sales/pin_deactivation_request.php?trans_no=" .$row['trans_no']. "&line_no=".$row['line_no'] . "&start_serial=" .$row['start_serial'] . "&end_serial=" . $row['end_serial'] . "&action_mode=2" , ICON_REMOVE);

}
function due_date($row)
{
	return $row["type"] == 10 ? $row["due_date"] : '';
}

function fmt_balance($row)
{
	return $row["totalamount"] - $row["allocated"];
}

function alloc_link($row)
{
	$link = 
	pager_link(_("Allocation"),
		"/sales/allocations/customer_allocate.php?trans_no=" . $row["trans_no"] 
		."&trans_type=" . $row["type"], ICON_MONEY);

	if ($row["type"] == ST_CUSTCREDIT && $row['totalamount'] > 0)
	{
		/*its a credit note which could have an allocation */
		return $link;
	}
	elseif (($row["type"] == ST_CUSTPAYMENT || $row["type"] == ST_BANKDEPOSIT) &&
		($row['totalamount'] - $row['allocated']) > 0)
	{
		/*its a receipt  which could have an allocation*/
		return $link;
	}
	elseif ($row["type"] == ST_CUSTPAYMENT && $row['totalamount'] < 0)
	{
		/*its a negative receipt */
		return '';
	}
}

function fmt_debit($row)
{
	$value =
	    $row['type']==ST_CUSTCREDIT || $row['type']==ST_CUSTPAYMENT || $row['type']==ST_BANKDEPOSIT ?
		-$row["totalamount"] : $row["totalamount"];
	return $value>=0 ? price_format($value) : '';

}

function fmt_credit($row)
{
	$value =
	    !($row['type']==ST_CUSTCREDIT || $row['type']==ST_CUSTPAYMENT || $row['type']==ST_BANKDEPOSIT) ?
		-$row["totalamount"] : $row["totalamount"];
	return $value>0 ? price_format($value) : '';
}
//------------------------------------------------------------------------------------------------

  $data_after = date2sql($_POST['TransAfterDate']);
  $date_to = date2sql($_POST['TransToDate']);

  $sql = "SELECT 
  		13 as type,
		 trans.order_no as trans_no,
		trans.line_no,
		jobs.file_gen_date,
		trans.start_serial,
		trans.end_serial,
		trans.end_serial - trans.start_serial + 1,
		trans.file_name,
		debtor.name,
		debtor.curr_code

    	FROM "
			.TB_PREF."pin_mailer_jobs_detail  trans, "
			.TB_PREF."pin_mailer_jobs  jobs, "
			.TB_PREF."debtors_master debtor
    	WHERE 1=1
		AND debtor.debtor_no = trans.customer_no
		AND jobs.order_no = trans.order_no 
		AND jobs.line_no = trans.line_no";
		
		    		//AND trans.tran_date >=to_date( '$data_after', 'yyyy-mm-dd hh24:mi:ss') 
    		//AND trans.tran_date <= '$date_to'";
			
			//AND (trans.ov_amount + trans.ov_gst + trans.ov_freight 
				//+ trans.ov_freight_tax + trans.ov_discount != 0)


   	if ($_POST['customer_id'] != ALL_TEXT)
   		$sql .= " AND trans.customer_no = ".db_escape($_POST['customer_id']);
		
		$sql .= " ORDER BY trans.order_no desc";
 /*  	if (isset($_POST['filterType']) && $_POST['filterType'] != ALL_TEXT)
   	{
   		if ($_POST['filterType'] == '1' || $_POST['filterType'] == '2')
   		{
   			$sql .= " AND trans.type = ".ST_SALESINVOICE." ";
   		}
   		elseif ($_POST['filterType'] == '3')
   		{
			$sql .= " AND trans.type = " . ST_CUSTPAYMENT;
   		}
   		elseif ($_POST['filterType'] == '4')
   		{
			$sql .= " AND trans.type = ".ST_CUSTCREDIT." ";
   		}

    	if ($_POST['filterType'] == '2')
    	{
    		$today =  date2sql(Today());
    		$sql .= " AND trans.due_date < '$today'
				AND (round(abs(trans.ov_amount + "
				."trans.ov_gst + trans.ov_freight + "
				."trans.ov_freight_tax + trans.ov_discount) - trans.alloc,6) > 0) ";
    	}
   	}else
   	{
	    $sql .= " AND trans.type <> ".ST_CUSTDELIVERY." ";
   	}


   	if (!check_value('showSettled'))
   	{
   		$sql .= " AND (round(abs(trans.ov_amount + trans.ov_gst + "
		."trans.ov_freight + trans.ov_freight_tax + "
		."trans.ov_discount) - trans.alloc,6) != 0) ";
   	}*/
//------------------------------------------------------------------------------------------------

$cols = array(
	_("Type") => array('fun'=>'systype_name'),
	_("Order #") => array('fun'=>'view_link'),
	_("Reference"), 
	_("Date") ,   //=> array('fun'=>'order_link')
	_("Start Serial") ,
	_("End Serial") ,
	_("Total") ,
	_("Filename") ,
	//_("Date") => array('name'=>'tran_date', 'type'=>'date', 'ord'=>'asc'),
	//_("Due Date") => array('type'=>'date', 'fun'=>'due_date'),
	_("Customer"), 
	("Activate") => array('fun'=>'activate_link'),
	("DeActivate") => array('fun'=>'deactivate_link')
	//_("Debit") => array('align'=>'right','fun'=>'fmt_debit'), 
	//_("Credit") => array('align'=>'right','insert'=>true, 'fun'=>'fmt_credit'), 

	//_("Balance") => array('type'=>'amount', 'insert'=>true, 'fun'=>'fmt_balance'),
	//array('insert'=>true, 'fun'=>'alloc_link')
	);

if ($_POST['customer_id'] != ALL_TEXT) {
	$cols[_("Customer")] = 'skip';
	$cols[_("Currency")] = 'skip';
}

$table =& new_db_pager('doc_tbl', $sql, $cols);
//$table->set_marker('check_overdue', _("Marked items are overdue."));

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
?>
