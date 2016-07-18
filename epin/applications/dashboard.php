<?php

	class dashboard_app extends application 
	{
		function dashboard_app() 
		{
			global $installed_modules;
			$this->application("dash",_("&Dashboard"));
			
			$this->add_module(_("Sales"));			
			$this->add_lapp_function(0, _("&HQ Daily Sales"),"dashboard/hqdaily_sales.php?HQ=1");
			$this->add_lapp_function(0, _("H&Q Monthly Sales"),"dashboard/hqmonthly_sales.php?HQ=1");
			
			//$this->add_lapp_function(0, "HQ Sales Against Price","dashboard/sales_price.php?HQ=1");			
			$this->add_lapp_function(0, "All Sales","dashboard/all_sales.php?");
			$this->add_lapp_function(0, "","");
			$this->add_lapp_function(0, _("HQ Outstanding Sales"),"dashboard/hqoutstanding_sales.php?HQ=1");
			$this->add_rapp_function(0, _("&Daily Sales by branch"),"dashboard/daily_sales.php?");
			$this->add_rapp_function(0, _("&Monthly Sales by branch"),"dashboard/monthly_sales.php?");
			//$this->add_rapp_function(0, "All Depot Sales Against Price","dashboard/sales_price.php?");
			$this->add_rapp_function(0, "","");
			$this->add_rapp_function(0, _("All &Outstanding Sales"),"dashboard/outstanding_sales.php?");
			
			$this->add_module(_("Store"));
			$this->add_lapp_function(1, _("Inventory Status"),"dashboard/inventory.php?");
				
			if (count($installed_modules) > 0)
			{
				foreach ($installed_modules as $mod)
				{
					if ($mod["tab"] == "dash")
						$this->add_rapp_function(2, $mod["name"], "modules/".$mod["path"]."/".$mod["filename"]."?");
				}
			}	
		}
	}
	

?>