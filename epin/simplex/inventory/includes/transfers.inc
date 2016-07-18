<?php

//**********************************************************************
    Copyright (C) Simplex
***********************************************************************
//------------------------------------------------------------------------------------------------
function get_transfer_details($transid)
{
		$sql = "SELECT 	stock.order_no,	stock.trans_id, 	smaster.description,
	stock.qty , stock.serialized,	location.location_name,	stock.tran_Date,	stock.type, 	
	stock.loc_code,	stock.person_id,	stock.price,	stock.trans_no, 
	stock.reference	,stock.stock_id
	FROM " .TB_PREF."stock_moves stock, "
		.TB_PREF."locations location, "
		.TB_PREF."stock_master smaster
	WHERE location.loc_code = stock.loc_code
	AND smaster.stock_id = stock.stock_id
	
	
	AND smaster.serializable=1
	AND stock.trans_no=".$transid ;  //AND stock.visible=1
	$sql_a = mysql_query($sql);
	 $result = mysql_fetch_array($sql_a);
	 return $result;
}
//--------------------------------------------------------------------------------------------------
function get_unit_info($name)
{
		$sql2 = "SELECT decimals from "
			.TB_PREF."item_units  
			WHERE abbr = '". $name . "'";
		$sql_b = mysql_query($sql2);
		$result2 = mysql_fetch_array($sql_b);
		return $result2['decimals'];
}
//--------------------------------------------------------------------------------------------------
function ispickedfortransfer($stock_id)
{
	$Stocks = array();
	$Stocks = $_SESSION['SCANNEDSTOCKS'];
	if(in_array($stock_id,$Stocks))
	{
		return true;
	}
	else
		return false;
}
//-----------------------------------------------------------------------------------------------
function upd_tsfr_serials($items,$unit,$sales_order_no=0)
{
	$cartstring = implode("','", $items);
	$sql = "UPDATE ".TB_PREF."serialized_stock SET status='PICKEDFORTRANSFER',sales_order_no=".db_escape($sales_order_no) . 
			" WHERE ".$unit." in ('". $cartstring . "')";
			db_query($sql, "The serials could not be updated");
			
}
//---------------------------------------------------------------------------------------------------
function serial_exist($srl_no,$location,$unit)
{
			$strSQL = "SELECT  "  . $unit . " from ". TB_PREF."serialized_stock  
			WHERE ". $unit ." = '". $srl_no . "'" .
			" AND location_code='" . $location . "' and status <> 'PICKED'";
	
	if(mysql_num_rows(mysql_query($strSQL))){
		return true;
	}
	else
		return false;
}
//------------------------------------------------------------------------------------------------------
function get_column($unit)
{
	$column ;
	switch ($unit) {
		case "bx.":
			$column = 'box_no';
			break;
		case "bk.":
			$column = 'brick_no';
			break;
		case "cd.":
			$column = 'card_no';
			break;
		default:
			$column = 'card_no';
	}
	return $column;
}

?>