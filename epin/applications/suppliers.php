<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
class suppliers_app extends application 
{
	function suppliers_app() 
	{
		global $installed_extensions;
		$this->application("AP", _($this->help_context = "&E-PIN Management"));

		$this->add_module(_("Transactions"));
		
		//$this->add_lapp_function(0, "","");
		/* $this->add_lapp_function(0, _("Import E-PIN file"),
			"simplex/sales/import_datafile_3.php?", 'SA_SUPPTRANSVIEW'); */
		
		$this->add_lapp_function(0, _("Import Raw E-PIN file"),
			"simplex/sales/import_datafile_3.php?", 'SA_PURCHASEORDER');   //SA_SUPPTRANSVIEW
		//$this->add_lapp_function(0, _("Authorize E-PIN file"),
			//"simplex/sales/import_datafile_auth.php?", 'SA_SUPPLIERCREDIT');  ////SA_SUPPTRANSVIEW
			
		$this->add_lapp_function(0, _("Voucher Sale Release / &Adjustment"),
			"simplex/inventory/epin_sales_release.php?type=30", 'SA_GRNDELETE');  //SA_INVENTORYADJUSTMENT

//Added empty functions to push the menu up 
		$this->add_rapp_function(0, "","");
//
		//$this->add_rapp_function(0, _("Batch Activation"),
		//	"simplex/sales/pin_activation.php", 'SA_SALESCREDIT');
			
		//$this->add_rapp_function(0, _("EPIN Blacklist"),
		//	"simplex/sales/pin_activation.php", 'SA_SALESCREDIT');

		$this->add_module(_("Inquiries and Reports"));

		$this->add_rapp_function(1, _("EPIN Inquiry"),
			"simplex/sales/inquiry/epin_inquiry.php?", 'SA_ITEMSTRANSVIEW');
			
		$this->add_lapp_function(1, _("EPIN Release Inquiry"),
			"simplex/inventory/inquiry/epin_release_inquiry.php?", 'SA_ITEMSSTATVIEW');
		//$this->add_rapp_function(1, _("Customer PIN file Inquiry"),
		//	"simplex/sales/inquiry/customer_epin_inquiry.php?", 'SA_SALESTRANSVIEW');
		$this->add_rapp_function(1, _("Overscratched EPIN / RCV Inquiry"),
			"simplex/sales/inquiry/pininquirysvc.php?", 'SA_PRICEREP');
			
		$this->add_lapp_function(1, _("Inventory &Reports"),
			"reporting/reports_main.php?Class=1", 'SA_ITEMSTRANSVIEW');
		$this->add_lapp_function(1, _("Inventory Item &Status"),
			"inventory/inquiry/stock_status.php?", 'SA_ITEMSSTATVIEW');
			
		$this->add_rapp_function(1, _("EPIN File Load Status"),
			"simplex/sales/inquiry/epin_file_inquiry.php?", 'SA_ITEMSTRANSVIEW');
			
		$this->add_module(_("Maintenance"));
		
		
		$this->add_lapp_function(2, _("&Items"),
			"inventory/manage/items.php?", 'SA_ITEM');
		$this->add_lapp_function(2, _("&Sales Parts"),
			"simplex/sim/sales_parts.php?", 'SA_ITEM');	
		$this->add_lapp_function(2, _("Item &Categories"),
			"inventory/manage/item_categories.php?", 'SA_ITEMCATEGORY');
		//$this->add_lapp_function(2, _("Inventory &Locations"),
		//	"inventory/manage/locations.php?", 'SA_INVENTORYLOCATION');
		//$this->add_lapp_function(2, _("&UVC file format specs"),
			//"purchasing/manage/suppliers.php?", 'SA_SUPPLIER');
			
			
		$this->add_rapp_function(2, _("&Units of Measure"),
			"inventory/manage/item_units.php?", 'SA_UOM');
		$this->add_rapp_function(2, _("&Reorder Levels"),
			"inventory/reorder_level.php?", 'SA_REORDER');
		if (count($installed_extensions) > 0)
		{
			foreach ($installed_extensions as $mod)
			{
				if (@$mod['active'] && $mod['type'] == 'plugin' && $mod["tab"] == "AP")
					$this->add_rapp_function(2, $mod["title"], 
						"modules/".$mod["path"]."/".$mod["filename"]."?",
						isset($mod["access"]) ? $mod["access"] : 'SA_OPEN' );
			}
		}
	}
}


?>