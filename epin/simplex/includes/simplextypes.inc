<?php
/**********************************************************************
    Copyright (C) BlueChip Tech.
Simplex types for all additional modules created to extend the base ERP
***********************************************************************/
//----------------------------------------------------------------------------------
//	Simplex system transaction types -- addition to the base ERP system
//      Transaction types start from 1001
//      This fiel should be included at the end of the base ERP types.inc

//--
//   Added for Pruchase Requisition in simplextypes.inc to be included in types.inc
//   All addition transaction types defintion should be done here

   define('ST_PURCHREQ', 1001);

   define('ST_SOCONFIRM', 1002);
   
   //define('ST_AUTORIZATION', 90);

//  $systypes_array would have been defined in types.inc, so what we are doing here is to 
//  add the new transaction types that we have defined to the already defined $systypes_array

$systypes_array[ST_PURCHREQ] = (("Purchase Requisition"));
$systypes_array[ST_SOCONFIRM] = (("Confirm Sales Order"));
//
//

?>