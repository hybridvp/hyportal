<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_db.inc");
include_once($path_to_root . "/inventory/includes/inventory_db.inc");
include_once($path_to_root . "/manufacturing/includes/manufacturing_db.inc");

function void_transaction($type, $type_no, $date_, $memo_)
{
	$void_entry = get_voided_entry($type, $type_no);

	if ($void_entry != null)
		return false;

	switch ($type) {
		case ST_JOURNAL : // it's a journal entry
			if (!exists_gl_trans($type, $type_no))
				return false;
			void_journal_trans($type, $type_no);
			break;

		case ST_BANKPAYMENT : // it's a payment
		case ST_BANKDEPOSIT : // it's a deposit
		case ST_BANKTRANSFER : // it's a transfer
			if (!exists_bank_trans($type, $type_no))
				return false;
			void_bank_trans($type, $type_no);
			break;

		case ST_SALESINVOICE : // it's a customer invoice
		case ST_CUSTCREDIT : // it's a customer credit note
		case ST_CUSTPAYMENT : // it's a customer payment
		case ST_CUSTDELIVERY : // it's a customer dispatch
			if (!exists_customer_trans($type, $type_no))
				return false;
			if ($type == 13)	// added 04 Oct 2008 by Joe Hunt. If delivery note has a not voided invoice, then NO.
			{
				$delivery = get_customer_trans($type_no, $type);
				if ($delivery['trans_link'] != 0)
				{
					if (get_voided_entry(10, $delivery['trans_link']) === false)
						return false;
				}
			}	
			post_void_customer_trans($type, $type_no);
			break;

		case ST_LOCTRANSFER : // it's a stock transfer
			if (get_stock_transfer_items($type_no) == null)
				return false;
			void_stock_transfer($type_no);
			break;

		case ST_INVADJUST : // it's a stock adjustment
			if (get_stock_adjustment_items($type_no) == null)
				return false;
			void_stock_adjustment($type_no);
			break;

		case ST_PURCHORDER : // it's a PO
		case ST_SUPPRECEIVE : // it's a GRN
			return false;
		case ST_SUPPINVOICE : // it's a suppler invoice
		case ST_SUPPCREDIT : // it's a supplier credit note
		case ST_SUPPAYMENT : // it's a supplier payment
			if (!exists_supp_trans($type, $type_no))
				return false;
			if (!post_void_supp_trans($type, $type_no))
				return false;
			break;

		case ST_WORKORDER : // it's a work order
			if (!get_work_order($type_no, true))
				return false;
			void_work_order($type_no);
			break;

		case ST_MANUISSUE : // it's a work order issue
			if (!exists_work_order_issue($type_no))
				return false;
			void_work_order_issue($type_no);
			break;

		case ST_MANURECEIVE : // it's a work order production
			if (!exists_work_order_produce($type_no))
				return false;
			void_work_order_produce($type_no);
			break;

		case ST_SALESORDER: // it's a sales order
		case ST_SALESQUOTE: // it's a sales quotation
			return false;

		case ST_COSTUPDATE : // it's a stock cost update
			return false;
			break;
	}

	// only add an entry if it's actually been voided
	add_audit_trail($type, $type_no, $date_, _("Voided.")."\n".$memo_);
	add_voided_entry($type, $type_no, $date_, $memo_);
	return true;
}

//--------------------------------------------------------------------------------------------------

function get_voided_entry($type, $type_no)
{
	$sql = "SELECT * FROM ".TB_PREF."voided WHERE type=".db_escape($type)
		." AND id=".db_escape($type_no);

	$result = db_query($sql, "could not query voided transaction table");

	return db_fetch($result);
}

//--------------------------------------------------------------------------------------------------

function add_voided_entry($type, $type_no, $date_, $memo_)
{
	$date = date2sql($date_);
	$sql = "INSERT INTO ".TB_PREF."voided (type, id, date_, memo_)
		VALUES (".db_escape($type).", ".db_escape($type_no).", "
		.db_escape($date).", ".db_escape($memo_).")";

	db_query($sql, "could not add voided transaction entry");
}

//--------------------------------------------------------------------------------------------------

?>