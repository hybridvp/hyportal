<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
class manufacturing_app extends application
{
	function manufacturing_app()
	{
		global $installed_extensions;
		$this->application("manuf", _($this->help_context = "&PO & RCV Management"));

		$this->add_module(_("Transactions"));
		
		$this->add_lapp_function(0, _("Purchase &Order Entry"),
			"purchasing/po_entry_items.php?NewOrder=Yes", 'SA_PURCHASEORDER');
			
		$this->add_lapp_function(0, _("&Outstanding Purchase Orders Maintenance"),
			"purchasing/po_search.php?", 'SA_GRN');
			
			
		$this->add_rapp_function(0, _("Transfer RCV to Manufacturer"),
			"simplex/purchasing/inquiry/supplier_rcv_inquiry.php?", 'SA_SALESCREDIT');
			
		$this->add_rapp_function(0, _("RCV Activation"),
			"simplex/purchasing/rcv_activation_request.php", 'SA_SALESALLOC');
		$this->add_rapp_function(0, _("RCV DeActivation"),
			"simplex/purchasing/rcv_deactivation_request.php", 'SA_SALESALLOC');
			
		$this->add_rapp_function(0, _("Activation / Deactivation Status Inquiry"),
			"simplex/sales/inquiry/activation_inq.php", 'SA_SALESALLOC');
		//$this->add_lapp_function(0, _("Purchase Orders &Confirmation"),"purchasing/po_confirm.php?", 'SA_GRN');
/*		$this->add_lapp_function(0, _("Work &Order Entry"),
			"manufacturing/work_order_entry.php?", 'SA_WORKORDERENTRY');
		$this->add_lapp_function(0, _("&Outstanding Work Orders"),
			"manufacturing/search_work_orders.php?outstanding_only=1", 'SA_MANUFTRANSVIEW');*/

		$this->add_module(_("Inquiries and Reports"));
		$this->add_lapp_function(1, _("Purchase Orders &Inquiry"),
			"purchasing/po_search_completed.php?", 'SA_SUPPTRANSVIEW');
		$this->add_lapp_function(1, _("Supplier Transaction &Inquiry"),
			"purchasing/supplier_inquiry.php?", 'SA_SUPPTRANSVIEW');
		$this->add_lapp_function(1, "","");
		$this->add_lapp_function(1, _("Supplier Allocation &Inquiry"),
			"purchasing/supplier_allocation_inquiry.php?", 'SA_SUPPLIERALLOC');
			
		$this->add_rapp_function(1, _("RCV File Load Status"),
			"simplex/purchasing/inquiry/rcv_file_inquiry.php?", 'SA_ITEMSTRANSVIEW');
		/*
		$this->add_lapp_function(1, _("Costed Bill Of Material Inquiry"),
			"manufacturing/inquiry/bom_cost_inquiry.php?", 'SA_WORKORDERCOST');
		$this->add_lapp_function(1, _("Inventory Item Where Used &Inquiry"),
			"manufacturing/inquiry/where_used_inquiry.php?", 'SA_WORKORDERANALYTIC');
		$this->add_lapp_function(1, _("Work Order &Inquiry"),
			"manufacturing/search_work_orders.php?", 'SA_MANUFTRANSVIEW');*/
		$this->add_rapp_function(1, _("Manufacturing &Reports"),
			"reporting/reports_main.php?Class=3", 'SA_MANUFTRANSVIEW');

//		$this->add_rapp_function(1, "","");
		


		$this->add_module(_("Maintenance"));
				$this->add_lapp_function(2, _("&Manufacturer"),
			"purchasing/manage/suppliers.php?", 'SA_SUPPLIER');
/*		$this->add_lapp_function(2, _("&Bills Of Material"),
			"manufacturing/manage/bom_edit.php?", 'SA_BOM');
		$this->add_lapp_function(2, _("&Work Centres"),
			"manufacturing/manage/work_centres.php?", 'SA_WORKCENTRES');
			*/
		if (count($installed_extensions) > 0)
		{
			foreach ($installed_extensions as $mod)
			{
				if (@$mod['active'] && $mod['type'] == 'plugin' && $mod["tab"] == "manuf")
					$this->add_rapp_function(2, $mod["title"], 
						"modules/".$mod["path"]."/".$mod["filename"]."?",
						isset($mod["access"]) ? $mod["access"] : 'SA_OPEN' );
			}
		}
	}
}


?>