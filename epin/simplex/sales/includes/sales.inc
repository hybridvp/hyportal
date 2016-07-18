<?php 
/**********************************************************************
    Copyright (C) Simplex
***********************************************************************/
function get_qty_serialized($stock_id, $location)
{
	$sql = "SELECT 	sum(qty)
	FROM " .TB_PREF."serialized_stock as stock 
	WHERE stock.location_code = ".db_escape($location).
	" AND stock.stock_id =".db_escape($stock_id). 
	" AND stock.status = 'AVAILABLE'";
	$result = db_query($sql,"qty serialized could not be retrieved");
	
	return db_fetch($result);
	/* $sql_a = mysql_query($sql);
	 $result = mysql_fetch_array($sql_a);
	 return $result; */
}


//--------------------------------------------------------------------------------------------------
function get_unit_info($name)
{
		$sql2 = "SELECT decimals from "
			.TB_PREF."item_units  
			WHERE abbr = '". $name . "'";
			
	$sql_b = db_query($sql2);
	$result2 = db_fetch($sql_b);  //mysql_fetch_array
	return $result2['decimals'];
}
//---------------------------------------------------------------------------------------------------
function get_sales_orderno($line_no)
{
		$strSQL = "SELECT order_no from "
			.TB_PREF."sales_order_details  
			WHERE id = ". db_escape($line_no);
			
		$result = db_query($strSQL," sales_orderno could not be retreived");
		$retval = db_fetch($result);
		return $retval['order_no'];
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
//--------------------
function get_line_item($orderno)
{
	$sql = "SELECT 	sorder.*
	FROM " .TB_PREF."sales_order_details sorder, "
		   .TB_PREF."stock_master smaster
	WHERE smaster.stock_id = sorder.stk_code
	AND sorder.order_no = ".db_escape($orderno);
	//echo $sql;
	$result = db_query($sql,"sales order line item could not be retrieved");
	
	return db_fetch($result);
	/* $sql_a = mysql_query($sql);
	 $result = mysql_fetch_array($sql_a);
	 return $result; */
}
//--------------------------------------------------------------------------------------------------
function ispicked($order_no)
{

 	$retval = 0;
	$strSQL = "SELECT status from ". TB_PREF."serialized_stock  
	WHERE sales_order_no= ". db_escape($order_no) . 
	" AND status='PICKED'";
	//display_notification($strSQL)	 ;
	 if(db_num_rows(db_query($strSQL))){
		return true;
	}
	else
		return false;
		return true;	
	
	
	
	/* $result = db_query($strSQL,"an item could not be retreived");
	$retval = db_fetch($result);
	return $retval['qty'];*/
	
 
}
//--------------------------------------------------------------------------------------------------
function get_qty_picked($order_no)
{
 	$retval = 0;
	$strSQL = "SELECT sum(qty) qty from ". TB_PREF."serialized_stock  
	WHERE sales_order_no= ". db_escape($order_no) . 
	" AND status='PICKED'";
	$result = db_query($strSQL,"an item could not be retreived");
	$retval = db_fetch($result);
	return $retval['qty'];
/* 	if(db_num_rows(db_query($strSQL))){
		return true;
	}
	else
		return false; */
}
//--------------------------------------------------------------------------------------------------
function serial_exist($srl_no,$location,$unit)
{
			$strSQL = "SELECT  "  . $unit . " from ". TB_PREF."serialized_stock  
			WHERE ". $unit ." = '". $srl_no . "'" .
			" AND location_code='" . $location . "' and status <> 'PICKED'";
	//Display_notification ($strSQL) ;
	if(db_num_rows(db_query($strSQL))){
		return true;
	}
	else
		return false;
}
//--------------------------------------------------------------------------------------------------
function serial_loaded($srl_no,$location,$unit,$stock_id)
{
	$strQ = "select a.stock_id,b.description from "
	. TB_PREF."stock_master a, stock_category b
	where a.category_id = b.category_id and a.stock_id=" . db_escape($stock_id);
	$sql_a = db_query($strQ);
	 $result = db_fetch($sql_a);
	if ( $result['description'] = 'Airtime Cards')
	{
		if ($unit = 'box_no' ) 
			$unit = 'box_number';
		elseif ($unit = 'brick_no')
			$unit = 'brick_number';
		elseif ($unit = 'card_no')
			$unit = 'serial_no';
		$strSQL = "SELECT  serial_no from ". TB_PREF."rcv_output  
		WHERE ". $unit ." = ". db_escape($srl_no)  ;
		
		if(db_num_rows(db_query($strSQL))){
			return true;
		}
		else
			return false;
	}
	elseif ( $result['description'] = 'Phone' )
	{
		if ($unit = 'box_no' ) 
			$unit = 'box_number';
		elseif ($unit = 'brick_no')
			$unit = 'brick_number';
		elseif ($unit = 'card_no')
			$unit = 'serial_no';
		$strSQL = "SELECT  serial_no from ". TB_PREF."sim_details  
		WHERE ". $unit ." = ". db_escape($srl_no)  ;
		
		if(db_num_rows(db_query($strSQL))){
			return true;
		}
		else
			return false;
	}
}
//-----------------------------------------------------------------------------------------------
function upd_so_serials($items,$unit,$sales_order_no)
{
	$cartstring = implode("','", $items);
	$sql = "UPDATE ".TB_PREF."serialized_stock SET status='PICKED',sales_order_no=".db_escape($sales_order_no) . 
			" WHERE ".$unit." in ('". $cartstring . "')";
	//display_notification ($sql) ;
			db_query($sql, "The serials could not be updated");
			
}
/* function get_item($stock_id)
{
	$sql = "SELECT ".TB_PREF."stock_master.*,".TB_PREF."item_tax_types.name AS tax_type_name
		FROM ".TB_PREF."stock_master,".TB_PREF."item_tax_types
		WHERE ".TB_PREF."item_tax_types.id=".TB_PREF."stock_master.tax_type_id
		AND stock_id=".db_escape($stock_id);
	$result = db_query($sql,"an item could not be retreived");

	return db_fetch($result);
}
 */
?>