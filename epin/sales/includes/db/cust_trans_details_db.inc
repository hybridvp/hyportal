<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
//----------------------------------------------------------------------------------------

function get_customer_trans_details($debtor_trans_type, $debtor_trans_no)
{
if (!is_array($debtor_trans_no))
	$debtor_trans_no = array( 0=>$debtor_trans_no );

	$sql = "SELECT ".TB_PREF."debtor_trans_details.*,
		".TB_PREF."debtor_trans_details.unit_price+".TB_PREF."debtor_trans_details.unit_tax AS fullunitprice,
		".TB_PREF."debtor_trans_details.description As StockDescription,
		".TB_PREF."stock_master.units
		FROM ".TB_PREF."debtor_trans_details,".TB_PREF."stock_master
		WHERE (";

	$tr=array();
	foreach ($debtor_trans_no as $trans_no)
		$tr[] = 'debtor_trans_no='.$trans_no;

	$sql .= implode(' OR ', $tr);


	$sql.=	") AND debtor_trans_type=".db_escape($debtor_trans_type)."
		AND ".TB_PREF."stock_master.stock_id=".TB_PREF."debtor_trans_details.stock_id
		ORDER BY id";
	return db_query($sql, "The debtor transaction detail could not be queried");
}

//----------------------------------------------------------------------------------------

function void_customer_trans_details($type, $type_no)
{
	$sql = "UPDATE ".TB_PREF."debtor_trans_details SET quantity=0, unit_price=0,
		unit_tax=0, discount_percent=0, standard_cost=0
		WHERE debtor_trans_no=".db_escape($type_no)."
		AND debtor_trans_type=".db_escape($type);

	db_query($sql, "The debtor transaction details could not be voided");

	// clear the stock move items
	void_stock_move($type, $type_no);
}
//----------------------------------------------------------------------------------------

function write_customer_trans_detail_item($debtor_trans_type, $debtor_trans_no, $stock_id, $description,
	$quantity, $unit_price, $unit_tax, $discount_percent, $std_cost, $line_id=0)
{
	if ($line_id!=0)
		$sql = "UPDATE ".TB_PREF."debtor_trans_details SET
			stock_id=".db_escape($stock_id).",
			description=".db_escape($description).",
			quantity=$quantity,
			unit_price=$unit_price,
			unit_tax=$unit_tax,
			discount_percent=$discount_percent,
			standard_cost=$std_cost WHERE
			id=".db_escape($line_id);
	else
			$sql = "INSERT INTO ".TB_PREF."debtor_trans_details (id,debtor_trans_no,
				debtor_trans_type, stock_id, description, quantity, unit_price,
				unit_tax, discount_percent, standard_cost)
			VALUES (DEBTOR_TRANS_DETAILS_ID_SEQ.NEXTVAL,".db_escape($debtor_trans_no).", ".db_escape($debtor_trans_type).", ".db_escape($stock_id).
			", ".db_escape($description).",
				$quantity, $unit_price, $unit_tax, $discount_percent, $std_cost)";

	db_query($sql, "The debtor transaction detail could not be written");
}

?>