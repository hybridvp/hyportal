<?php

function get_unallocated_payments($debtor_no)
{
  $sql = "SELECT 'PAYMENT' type,
  		sum(ov_amount+ov_gst+ov_freight+ov_freight_tax+ov_discount) AS Total,
  		sum(trans.alloc) AS Alloc,
  		case when sum(round(ov_amount+ov_gst+ov_freight+ov_freight_tax+ov_discount-alloc,6))<=0 then 1 else 0 end AS settled
           FROM ".TB_PREF."debtor_trans trans
	   WHERE (type=12 OR type=11 OR type=2)
	   AND trans.ov_amount > 0  AND round(ov_amount+ov_gst+ov_freight+ov_freight_tax-ov_discount-alloc,6) > 0
           AND trans.debtor_no = '".$debtor_no."' ";

  $res = db_query($sql,"Unallocated payment could not be retreived");
  //display_notification ($sql);
  $result = db_fetch($res);
  if (isset($result)) return $result['total'];
  else return 0 ;//no data found
}

function get_outstanding_invoices($debtor_no)
{
  $sql = "SELECT
    'INVOICE'  type,
    sum(ov_amount+ov_gst+ov_freight+ov_freight_tax+ov_discount) AS Total,
    sum(trans.alloc) AS alloc,
    sum(round(ov_amount+ov_gst+ov_freight+ov_freight_tax+ov_discount-alloc,6)) AS Outstanding
    FROM ".TB_PREF."debtor_trans trans
	    WHERE round(ov_amount+ov_gst+ov_freight+ov_freight_tax+ov_discount-alloc,6) > 0
        AND  trans.type <> 12
		AND trans.type <> 2
		AND trans.type <> 11
		AND trans.type <> 13
    	AND trans.debtor_no = '".$debtor_no."' ";
  //display_notification ($sql);
  $res = db_query($sql,"Outstanding invoices could not be retreived");
  $result = db_fetch($res);
  if (isset($result)) return $result['total'];
  else return 0 ;//no data found
}

function get_sales_deliveries($debtor_no)
{
  $sql = "SELECT
	 'DELIVERY' type ,
		 Sum(ov_amount+ov_gst+ov_freight+ov_freight_tax) AS DeliveryValue,
		 Sum(line.quantity-line.qty_done) AS Outstanding,
		 Sum(line.qty_done) AS Done
		 FROM ".TB_PREF."debtor_trans trans, ".TB_PREF."debtor_trans_details line
		WHERE line.debtor_trans_no = trans.trans_no
		AND line.debtor_trans_type = trans.type
		AND line.qty_done < line.quantity
		AND trans.type = 13
		AND trans.debtor_no = '".$debtor_no."' ";
  
  //display_notification ($sql);
  $res = db_query($sql,"Outstanding invoices could not be retreived");
  $result = db_fetch($res);
  if (isset($result)) return $result['deliveryvalue'];
  else return 0 ;//no data found
}


function get_undelivered_sorders($debtor_no, $order_no)
{
  $sql = "SELECT
  'SORDER' as type,
	Sum(line.unit_price*line.quantity*(1-line.discount_percent)+freight_cost) AS ordervalue,
	Sum(line.unit_price*(line.quantity-line.qty_sent)*(1-line.discount_percent)+freight_cost) AS OutstandingValue,
  Sum(line.qty_sent) AS totdelivered,
	Sum(line.quantity) AS totquantity
FROM ".TB_PREF."sales_orders sorder, ".TB_PREF."sales_order_details  line
WHERE sorder.order_no = line.order_no
		AND sorder.trans_type = line.trans_type
		AND sorder.trans_type = 30
		AND sorder.debtor_no = '".$debtor_no."'
		AND line.qty_sent < line.quantity 
		AND 
			(sorder.ourorder_status= 'Confirmed' OR  sorder.order_no = '".$order_no."')";


  //display_notification ($sql);
  $res = db_query($sql,"Outstanding invoices could not be retreived");
  $result = db_fetch($res);
  if (isset($result)) return $result['outstandingvalue'];
  else return 0 ;//no data found
}
function get_customer_credit_info($debtor_no)
{
  $sql = "select c.dissallow_invoices, m.credit_limit from ".TB_PREF."debtors_master m, ".TB_PREF."credit_status c 
  			where m.credit_status = c.id and debtor_no  = '".$debtor_no."' ";

  $res = db_query($sql,"Customer status could not be retreived");
  //display_notification ($sql);
  $result = db_fetch_row($res);  
  return $result;
}


function get_order_state ($order_no, $trans_type=30)
{
	$sql = "SELECT ourorder_status from ".TB_PREF."sales_orders  
		WHERE order_no=" . $order_no ."  
		AND trans_type=".$trans_type ;
		
	$res = db_query($sql,"order status could not be fetched");
	$result = db_fetch($res);
	return $result['ourorder_status'];

}

function change_order_state ($order_no, $trans_type,$current_state, $next_state)
{
    begin_transaction();
	$sql = "UPDATE ".TB_PREF."sales_orders SET ourorder_status ='".$next_state."' 
		WHERE order_no=" . $order_no ."  
		AND trans_type=".$trans_type ; //."  ;
		//AND ourorder_status='".$current_state."'";
 	 db_query($sql, "order state change failed: error change state from ".$current_state." to ".$next_state);
         commit_transaction();

}

function change_porder_state ($order_no,$current_state, $next_state)
{
    begin_transaction();
	$sql = "UPDATE ".TB_PREF."purch_orders SET status ='".$next_state."' 
		WHERE order_no=" . $order_no ."   
		AND status='".$current_state."'";
 	 db_query($sql, "purchase order state change failed: error change state from ".$current_state." to ".$next_state);
         commit_transaction();

}
function start_confirmation ($order_no, $trans_type, $amount, $current_state)
{
//secure sales order for confirmation 
  change_order_state ($order_no, $trans_type,$current_state, 'ChangingState' ) ;

//check sum lines equal order total i.e. no changes have been made between this update and now

  $sql = "SELECT 1 as okay FROM ".TB_PREF."sales_order_details
				WHERE ".TB_PREF."sales_order_details.order_no=".$order_no."
				 AND ".TB_PREF."sales_order_details.trans_type=".$trans_type."
				 having round(sum(".TB_PREF."sales_order_details.quantity*".TB_PREF
				 ."sales_order_details.unit_price*(1-".TB_PREF."sales_order_details.discount_percent)))=round(".$amount.")";

			$res = db_query($sql,"Amount could not be validated");
			$result = db_fetch($res);
//  display_notification ("here ".$amount." o".$order_no);
//    display_notification ($sql);
			if ($result['okay'] == 1)
                           return true ;
                        else
                        {
			//update back to current state
                            change_order_state ($order_no, $trans_type, 'ChangingState' ,$current_state) ;
                            return false ;
                          }

}

function confirm_sales_Order ($customer_id, $order_no, $trans_type, $amount, $current_state, $credit_limit)
{

  if (start_confirmation ($order_no, $trans_type, $amount, $current_state)) 
    {
       //display_notification (get_unallocated_payments($customer_id));
	   //display_notification (get_outstanding_invoices($customer_id));
	   //display_notification (get_sales_deliveries($customer_id));
	   //display_notification (get_undelivered_sorders($customer_id, $order_no));

       $pay_n_credit = ($credit_limit+get_unallocated_payments($customer_id)) ;
	   $debit_items =  (get_outstanding_invoices($customer_id) + get_sales_deliveries($customer_id)
	            + get_undelivered_sorders($customer_id, $order_no));
      if (1==1)  //($debit_items<=$pay_n_credit)
	  {
	  	 	change_order_state ($order_no, $trans_type, 'ChangingState' ,'Confirmed') ;
	  	 	//change_order_state ($order_no, $trans_type, 'ChangingState' ,'Planned') ; 
       		return true ;
	   } else { display_error (_("Customer payment limits and payments not enough for the order being confirmed, 
	   									see your credit officer for more information.")); } 
    }
	else   	display_error( _("Order #".$order_no. " is mutating: could not be confirmed/cancelled now, please review and retry."));
  	change_order_state ($order_no, $trans_type, 'ChangingState' ,'Planned') ; 
	return false;
}

function cancel_sales_Order ($order_no, $trans_type, $amount, $current_state)
{
//return "testing order_no ".$order_no."; trans_type= ".$trans_type."; amount=".$amount."; current state=".$current_state." ;" ; 
   if (start_confirmation ($order_no, $trans_type, $amount, $current_state)
) 
    {
	  change_order_state ($order_no, $trans_type, 'ChangingState' ,'Cancelled') ; 
          return true ;   
    }
  return false;
}
?>