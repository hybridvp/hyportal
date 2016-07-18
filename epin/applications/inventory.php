<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
class inventory_app extends application 
{
	function inventory_app() 
	{
		global $installed_extensions;
		$this->application("stock", _($this->help_context = "&Voucher Inventory Management"));

		
		$this->add_module(_("Transactions"));
		$this->add_lapp_function(0, _("Inventory Location &Transfers"),
			"inventory/transfers.php?NewTransfer=1", 'SA_LOCATIONTRANSFER');
		$this->add_lapp_function(0, _("Inventory &Adjustments"),
			"inventory/adjustments.php?NewAdjustment=1", 'SA_INVENTORYADJUSTMENT');
		
		$this->add_lapp_function(0, _("Voucher Sale Release / &Adjustment"),
			"simplex/inventory/epin_sales_inquiry.php?", 'SA_INVENTORYADJUSTMENT');
			
		$this->add_lapp_function(0, _("Move to Transit"),
			"simplex/inventory/transfer_to_transit.php?", 'SA_INVENTORYADJUSTMENT');
		$this->add_lapp_function(0, _("Receive from Transit"),
			"simplex/inventory/inquiry/transit_item_search.php?", 'SA_INVENTORYADJUSTMENT');
		/*$this->add_lapp_function(0, _("Stock &Splitting"),
			"simplex/inventory/inquiry/stock_search.php", 'SA_INVENTORYADJUSTMENT');
						
		$this->add_rapp_function(0, _("OPS "),
			"simplex/sim/enter_range.php", 'SA_INVENTORYADJUSTMENT');
		$this->add_rapp_function(0, _("View OPS Items"),
			"simplex/sim/ops_item_search.php", 'SA_ITEMSTRANSVIEW');
			*/
			
		$this->add_module(_("Inquiries and Reports"));
		//$this->add_lapp_function(1, _("Inventory Item &Movements"),
		//	"inventory/inquiry/stock_movements.php?", 'SA_ITEMSTRANSVIEW');
		$this->add_lapp_function(1, _("Inventory Item &Status"),
			"inventory/inquiry/stock_status.php?", 'SA_ITEMSSTATVIEW');
		
		$this->add_lapp_function(1, _("EPIN Release Inquiry"),
			"inventory/inquiry/epin_release_inquiry.php?", 'SA_ITEMSSTATVIEW');
		$this->add_lapp_function(1, _("E-PIN Inquiry"),
			"simplex/sales/inquiry/epin_inquiry.php?", 'SA_SALESTRANSVIEW');
		$this->add_lapp_function(1, _("Inventory &Reports"),
			"reporting/reports_main.php?Class=2", 'SA_ITEMSTRANSVIEW');

			
		$this->add_module(_("Maintenance"));
		$this->add_lapp_function(2, _("&Items"),
			"inventory/manage/items.php?", 'SA_ITEM');
		$this->add_lapp_function(2, _("&Sales Parts"),
			"simplex/sim/sales_parts.php?", 'SA_ITEM');	
		$this->add_lapp_function(2, _("&Foreign Item Codes"),
			"inventory/manage/item_codes.php?", 'SA_FORITEMCODE');
		/*$this->add_lapp_function(2, _("Sales &Parts"),
			"inventory/manage/sales_kits.php?", 'SA_SALESKIT');*/
		$this->add_lapp_function(2, _("Item &Categories"),
			"inventory/manage/item_categories.php?", 'SA_ITEMCATEGORY');
		$this->add_lapp_function(2, _("Inventory &Locations"),
			"inventory/manage/locations.php?", 'SA_INVENTORYLOCATION');
		$this->add_rapp_function(2, _("Inventory &Movement Types"),
			"inventory/manage/movement_types.php?", 'SA_INVENTORYMOVETYPE');
		$this->add_rapp_function(2, _("&Units of Measure"),
			"inventory/manage/item_units.php?", 'SA_UOM');
		$this->add_rapp_function(2, _("&Reorder Levels"),
			"inventory/reorder_level.php?", 'SA_REORDER');

//Laolu added
		//	$this->add_rapp_function(2, _("&Units of Measure Conversion"),
		//	"simplex/unit_conversion/unit_conversion.php?", 'SA_UOM');

		//$this->add_module(_("Pricing and Costs"));
		//$this->add_lapp_function(3, _("Sales &Pricing"),
		//	"inventory/prices.php?", 'SA_SALESPRICE');
		//$this->add_lapp_function(3, _("Purchasing &Pricing"),
		//	"inventory/purchasing_data.php?", 'SA_PURCHASEPRICING');
		//$this->add_rapp_function(3, _("Standard &Costs"),
		//	"inventory/cost_update.php?", 'SA_STANDARDCOST');
		if (count($installed_extensions) > 0)
		{
			foreach ($installed_extensions as $mod)
			{
				if (@$mod['active'] && $mod['type'] == 'plugin' && $mod["tab"] == "stock")
					$this->add_rapp_function(2, $mod["title"], 
						"modules/".$mod["path"]."/".$mod["filename"]."?",
						isset($mod["access"]) ? $mod["access"] : 'SA_OPEN' );
			}
		}
	}
}


?>