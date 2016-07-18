<?php
/**********************************************************************
    Copyright (C) FSimplex
***********************************************************************/
class customers_app extends application 
{
	function customers_app() 
	{
		global $installed_extensions;
		$this->application("orders", _($this->help_context = "&Sales & Dealer Management"));
	
		$this->add_module(_("Transactions"));
		//$this->add_lapp_function(0, _("Sales &Quotation Entry"),
		//	"sales/sales_order_entry.php?NewQuotation=Yes", 'SA_SALESQUOTE');
//		$this->add_lapp_function(0, _("Sales &Order Entry"),
//			"sales/sales_order_entry.php?NewOrder=Yes", 'SA_SALESORDER');
//Added the new menu for confirmation of Planned Sales Order
		//$this->add_lapp_function(0, _("Convert Sales Quotation &To Order"),
		//	"simplex/sales/inquiry/sales_orders_confirm_view.php?type=32", 'SA_SQCONFIRM');
			
		//$this->add_lapp_function(0, _("Request for EPIN &Generation"),
		//	"sales/sales_order_entry.php?NewOrder=Yes", 'SA_SALESORDER');


		//$this->add_lapp_function(0, _("Outstanding Requests for EPIN &Generation"),
		//	"sales/sales_order_entry.php?NewOrder=Yes", 'SA_SALESORDER');
			
		//$this->add_lapp_function(0, _("Outstanding Requests for EPIN &Generation"),
		//	"simplex/sales/inquiry/sales_orders_confirm_view.php?type=30", 'SA_SOCONFIRM');

//Laolu added menu
		//$this->add_lapp_function(0, _("&Pick Items from Store"),
		//	"simplex/sales/inquiry/sales_orders_pick.php?OutstandingOnly=1", 'SA_SALESDELIVERY');

//

/*		$this->add_lapp_function(0, _("Direct &Delivery"),
			"sales/sales_order_entry.php?NewDelivery=0", 'SA_SALESDELIVERY');
		$this->add_lapp_function(0, _("Direct &Invoice"),
			"sales/sales_order_entry.php?NewInvoice=0", 'SA_SALESINVOICE');
*/
//Added empty functions to push the menu up 
		$this->add_lapp_function(0, "","");
//		$this->add_lapp_function(0, "","");
//
		$this->add_lapp_function(0, _("Outstanding &Delivery Against Sales Orders"),
			"sales/inquiry/sales_orders_view.php?OutstandingOnly=1&jobflg=0", 'SA_SALESDELIVERY');
		//$this->add_lapp_function(0, _("&Delivery Against Sales Orders"),
		//	"sales/inquiry/sales_orders_view.php?OutstandingOnly=1&jobflg=1", 'SA_SALESDELIVERY');
		//$this->add_lapp_function(0, _("&Delivery Against Sales Orders - Activate and Send"),
		//	"sales/inquiry/sales_orders_view.php?OutstandingOnly=1&jobflg=2", 'SA_SALESDELIVERY');
		//$this->add_lapp_function(0, _("&Invoice Against Sales Delivery"),
		//	"sales/inquiry/sales_deliveries_view.php?OutstandingOnly=1", 'SA_SALESINVOICE');
		$this->add_lapp_function(0, _("&Cancel Sales Orders"),
			"simplex/sales/inquiry/sales_orders_cancel_view.php?type=30", 'SA_SOCONFIRM');

/*		$this->add_rapp_function(0, _("&Template Delivery"),
			"sales/inquiry/sales_orders_view.php?DeliveryTemplates=Yes", 'SA_SALESDELIVERY');
		$this->add_rapp_function(0, _("&Template Invoice"),
			"sales/inquiry/sales_orders_view.php?InvoiceTemplates=Yes", 'SA_SALESINVOICE');
		$this->add_rapp_function(0, _("&Create and Print Recurrent Invoices"),
			"sales/create_recurrent_invoices.php?", 'SA_SALESINVOICE');
		$this->add_rapp_function(0, "","");
*/
//////////////////////////////////////////////////////////////////////////////////////
//Added customer payment advice for capturing customer payment for approval and posting


		//$this->add_rapp_function(0, _("Customer Payments &Advice"),
		//	"simplex/sales/customers_padvice.php?", 'SA_SALESPAYMNT');
		$this->add_lapp_function(0, _("Voucher Resell"),
			"simplex/inventory/epin_sales_inquiry.php?type=30", 'SA_SORESELL');  //SA_INVENTORYADJUSTMENT
		$this->add_rapp_function(0, _("Transfer E-pin to dealer"),
			"simplex/sales/inquiry/customer_epin_inquiry.php?", 'SA_SALESCREDIT');
		$this->add_rapp_function(0, _("E-PIN Resend Request"),
			"simplex/sales/pin_regen_request.php", 'SA_SALESALLOC');
		$this->add_rapp_function(0, _("E-PIN Activation"),
			"simplex/sales/pin_activation_request.php", 'SA_SALESALLOC');
		$this->add_rapp_function(0, _("E-PIN DeActivation"),
			"simplex/sales/pin_deactivation_request.php", 'SA_SALESALLOC');
			
		$this->add_rapp_function(0, _("Activation / Deactivation Status Inquiry"),
			"simplex/sales/inquiry/activation_inq.php", 'SA_SALESALLOC');
			
		//$this->add_rapp_function(0, _("Customer PayAdvice &Confirmation"),
		//	"simplex/sales/manage/approve_padvice.php?", 'SA_SALESPAYMNT');

///////////////////////////////////////////////////////////////////////////////

		//$this->add_rapp_function(0, _("Customer &Payments"),
		//	"sales/customer_payments.php?", 'SA_SALESPAYMNT');


		//$this->add_rapp_function(0, _("Customer &Credit Notes"),
		//	"sales/credit_note_entry.php?NewCredit=Yes", 'SA_SALESCREDIT');
//Added empty functions to push the menu up 
		//$this->add_rapp_function(0, "","");
//
		//$this->add_rapp_function(0, _("&Allocate Customer Payments or Credit Notes"),
		//	"sales/allocations/customer_allocation_main.php?", 'SA_SALESALLOC');

		$this->add_module(_("Inquiries and Reports"));
		//$this->add_lapp_function(1, _("Sales Quotation I&nquiry"),
			//"sales/inquiry/sales_orders_view.php?type=32", 'SA_SALESTRANSVIEW');
		$this->add_lapp_function(1, _("Sales Order &Inquiry"),
			"sales/inquiry/sales_orders_view.php?type=30", 'SA_SALESTRANSVIEW');
		$this->add_lapp_function(1, _("Dealer Transaction &Inquiry"),
			"sales/inquiry/customer_inquiry.php?", 'SA_SALESTRANSVIEW');
		$this->add_lapp_function(1, "","");
		$this->add_lapp_function(1, _("Dealer Allocation &Inquiry"),
			"sales/inquiry/customer_allocation_inquiry.php?", 'SA_CUSTPAYMREP');
			
		//$this->add_lapp_function(1, _("Dealer Allocation &Inquiry -Summary"),
		//	"sales/inquiry/customer_alloc_inquiry_sum.php?", 'SA_CUSTPAYMREP');
			
		$this->add_rapp_function(1, _("Dealer and Sales &Reports"),
			"reporting/reports_main.php?Class=0", 'SA_SALESTRANSVIEW');
			
		$this->add_module(_("Maintenance"));
//
		$this->add_lapp_function(2, _("Manage &Dealers"),
			"sales/manage/customers.php?", 'SA_CUSTOMER');

//Added the new link for Credit limit setting and approval//
//
		//$this->add_lapp_function(2, _("Request &Dealers's Engagement Terms"),
		//	"simplex/sales/manage/engage_customers.php?", 'SA_CUSTOMER');

		//$this->add_lapp_function(2, _("Approve &Dealers's Engagement Terms"),
		//	"simplex/sales/manage/approve_customers_terms.php?", 'SA_CUSTOMER');

//
		$this->add_lapp_function(2, _("Dealer &Branches"),
			"sales/manage/customer_branches.php?", 'SA_CUSTOMER');
		$this->add_lapp_function(2, _("Channel &Types"),
			"sales/manage/sales_groups.php?", 'SA_SALESGROUP');
		//$this->add_lapp_function(2, _("Recurrent &Invoices"),
		//	"sales/manage/recurrent_invoices.php?", 'SA_SRECURRENT');
		$this->add_rapp_function(2, _("Sales T&ypes"),
			"sales/manage/sales_types.php?", 'SA_SALESTYPES');
		$this->add_rapp_function(2, _("Sales &Persons"),
			"sales/manage/sales_people.php?", 'SA_SALESMAN');
		$this->add_rapp_function(2, _("Sales &Territories"),
			"sales/manage/sales_areas.php?", 'SA_SALESAREA');
		$this->add_rapp_function(2, _("Sales &Regions"),
			"sales/manage/sales_regions.php?", 'SA_SALESAREA');
		//$this->add_rapp_function(2, _("Credit &Status Setup"),
		//	"sales/manage/credit_status.php?", 'SA_CRSTATUS');
		if (count($installed_extensions) > 0)
		{
			foreach ($installed_extensions as $mod)
			{
				if (@$mod['active'] && $mod['type'] == 'plugin' && $mod["tab"] == "orders")
					$this->add_rapp_function(2, $mod["title"], 
						"modules/".$mod["path"]."/".$mod["filename"]."?",
						isset($mod["access"]) ? $mod["access"] : 'SA_OPEN' );
			}
		}
	}
}


?>