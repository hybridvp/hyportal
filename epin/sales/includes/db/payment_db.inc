<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
/*
  Write/update customer payment.
*/
function write_customer_payment($trans_no, $customer_id, $branch_id, $bank_account,
	$date_, $ref, $amount, $discount, $memo_, $rate=0, $charge=0)
{
	global $Refs;

	begin_transaction();

	$company_record = get_company_prefs();

	$payment_no = write_customer_trans(ST_CUSTPAYMENT, $trans_no, $customer_id, $branch_id, 
		$date_, $ref, $amount, $discount, 0, 0, 0, 0, 0, 0, 0, "", 0, $rate);

	$bank_gl_account = get_bank_gl_account($bank_account);

	if ($trans_no != 0) {
	  delete_comments(ST_CUSTPAYMENT, $trans_no);
	  void_bank_trans(ST_CUSTPAYMENT, $trans_no, true);
	  void_gl_trans(ST_CUSTPAYMENT, $trans_no, true);
	  void_cust_allocations(ST_CUSTPAYMENT, $trans_no, $date_);
	}
	$total = 0;
	/* Bank account entry first */
	$total += add_gl_trans_customer(ST_CUSTPAYMENT, $payment_no, $date_,
		$bank_gl_account, 0, 0, $amount - $charge,  $customer_id,
		"Cannot insert a GL transaction for the bank account debit", $rate);

	if ($branch_id != ANY_NUMERIC) {

		$branch_data = get_branch_accounts($branch_id);

		$debtors_account = $branch_data["receivables_account"];
		$discount_account = $branch_data["payment_discount_account"];

	} else {
		$debtors_account = $company_record["debtors_act"];
		$discount_account = $company_record["default_prompt_payment_act"];
	}

	if (($discount + $amount) != 0)	{
	/* Now Credit Debtors account with receipts + discounts */
	$total += add_gl_trans_customer(ST_CUSTPAYMENT, $payment_no, $date_,
		$debtors_account, 0, 0, -($discount + $amount), $customer_id,
		"Cannot insert a GL transaction for the debtors account credit", $rate);
	}
	if ($discount != 0)	{
		/* Now Debit discount account with discounts allowed*/
		$total += add_gl_trans_customer(ST_CUSTPAYMENT, $payment_no, $date_,
			$discount_account, 0, 0, $discount, $customer_id,
			"Cannot insert a GL transaction for the payment discount debit", $rate);
	}

	if ($charge != 0)	{
		/* Now Debit bank charge account with charges */
		$charge_act = get_company_pref('bank_charge_act');
		$total += add_gl_trans_customer(ST_CUSTPAYMENT, $payment_no, $date_,
			$charge_act, 0, 0, $charge, $customer_id,
			"Cannot insert a GL transaction for the payment bank charge debit", $rate);
	}
	/*Post a balance post if $total != 0 */
	add_gl_balance(ST_CUSTPAYMENT, $payment_no, $date_, -$total, PT_CUSTOMER, $customer_id);	

	/*now enter the bank_trans entry */
	add_bank_trans(ST_CUSTPAYMENT, $payment_no, $bank_account, $ref,
		$date_, $amount - $charge, PT_CUSTOMER, $customer_id,
		get_customer_currency($customer_id), "", $rate);

	add_comments(ST_CUSTPAYMENT, $payment_no, $date_, $memo_);

	$Refs->save(ST_CUSTPAYMENT, $payment_no, $ref);

	commit_transaction();

	return $payment_no;
}

//-------------------------------------------------------------------------------------------------

function void_customer_payment($type, $type_no)
{
	begin_transaction();

	void_bank_trans($type, $type_no, true);
	void_gl_trans($type, $type_no, true);
	void_cust_allocations($type, $type_no);
	void_customer_trans($type, $type_no);

	commit_transaction();
}


?>