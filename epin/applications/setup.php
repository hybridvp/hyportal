<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
class setup_app extends application
{
	function setup_app()
	{
		global $installed_extensions;
		$this->application("system", _($this->help_context = "A&dministration"));

		$this->add_module(_("Company Setup"));
		$this->add_lapp_function(0, _("&Application Setup"),
			"admin/company_preferences.php?", 'SA_SETUPCOMPANY');
		$this->add_lapp_function(0, _("&User Accounts Setup"),
			"admin/users.php?", 'SA_USERS');
		$this->add_lapp_function(0, _("A&ccess Setup"),
			"admin/security_roles.php?", 'SA_SECROLES');
			
		$this->add_lapp_function(0, _("Authorization Setup"),
			"admin/auth_setup.php?", 'SA_SECROLES');
		$this->add_lapp_function(0, _("Mailing List"),
			"admin/mailing_lists.php?", 'SA_SECROLES');
		$this->add_lapp_function(0, _("&Display Setup"),
			"admin/display_prefs.php?", 'SA_SETUPDISPLAY');
		//$this->add_lapp_function(0, _("&Forms Setup"),
		//	"admin/forms_setup.php?", 'SA_FORMSETUP');
		$this->add_lapp_function(0, _("&Voucher Archiving"),
			"simplex/admin/voucher_archive.php?", 'SA_FORMSETUP');
			

		$this->add_lapp_function(0, _("&Job Control Setup"),
			"simplex/admin/job_ctl.php?", 'SA_SETUPCOMPANY');
		$this->add_lapp_function(0, _("Failed &Uploaded Files"),
			"simplex/admin/failed_rawpinupload.php?", 'SA_SETUPCOMPANY');
		
		//$this->add_lapp_function(0, _("A&ctivation Jobs Status"),
		//	"simplex/admin/activation_status_inq.php?", 'SA_FORMSETUP');
			

			
		//$this->add_rapp_function(0, _("&Taxes"),
		//	"taxes/tax_types.php?", 'SA_TAXRATES');
		//$this->add_rapp_function(0, _("Tax &Groups"),
		//	"taxes/tax_groups.php?", 'SA_TAXGROUPS');
		//$this->add_rapp_function(0, _("Item Ta&x Types"),
		//	"taxes/item_tax_types.php?", 'SA_ITEMTAXTYPE');
		//$this->add_rapp_function(0, _("System and &General GL Setup"),
		//	"admin/gl_setup.php?", 'SA_GLSETUP');
		$this->add_rapp_function(0, _("&Fiscal Years"),
			"admin/fiscalyears.php?", 'SA_FISCALYEARS');
		//$this->add_rapp_function(0, _("View Archived &EPINS"),
		//	"simplex/admin/archive_inquiry.php?", 'SA_SETUPDISPLAY');
		
		$this->add_rapp_function(0, _("View Archived &Files"),
			"simplex/admin/file_archive_inquiry.php?", 'SA_SETUPDISPLAY');
			
		//$this->add_rapp_function(0, _("View Archive &History "),
		//	"simplex/admin/archive_job_status.php?", 'SA_FORMSETUP');
			
		//$this->add_rapp_function(0, _("&Print Profiles"),
		//	"admin/print_profiles.php?", 'SA_PRINTPROFILE');

		$this->add_rapp_function(0, _("User Audit &Trail"),
			"reporting/reports_main.php?Class=2", 'SA_PRINTPROFILE');

		$this->add_module(_("Miscellaneous"));
		//$this->add_lapp_function(1, _("Pa&yment Terms"),
		//	"admin/payment_terms.php?", 'SA_PAYTERMS');
		//$this->add_lapp_function(1, _("Shi&pping Company"),
		//	"admin/shipping_companies.php?", 'SA_SHIPPING');
		//$this->add_rapp_function(1, _("&Points of Sale"),
		//	"sales/manage/sales_points.php?", 'SA_POSSETUP');
		$this->add_rapp_function(1, _("&Printers"),
			"admin/printers.php?", 'SA_PRINTERS');
			
		$this->add_module(_("Maintenance"));
		//$this->add_lapp_function(2, _("&Void a Transaction"),
		//	"admin/void_transaction.php?", 'SA_VOIDTRANSACTION');
		//$this->add_lapp_function(2, _("View or &Print Transactions"),
		//	"admin/view_print_transaction.php?", 'SA_VIEWPRINTTRANSACTION');
		//$this->add_lapp_function(2, _("&Attach Documents"),
		//	"admin/attachments.php?filterType=20", 'SA_ATTACHDOCUMENT');
		$this->add_lapp_function(2, _("System Dia&gnostics"),
			"admin/system_diagnostics.php?", 'SA_OPEN');

		$this->add_lapp_function(2, _("Inventory &Locations"),
			"inventory/manage/locations.php?", 'SA_SETUPCOMPANY'); 
		//$this->add_rapp_function(2, _("&Backup and Restore"),
		//	"admin/backups.php?", 'SA_BACKUP');
		//$this->add_rapp_function(2, _("Create/Update &Companies"),
		//	"admin/create_coy.php?", 'SA_CREATECOMPANY');
		//$this->add_rapp_function(2, _("Install/Update &Languages"),
		//	"admin/inst_lang.php?", 'SA_CREATELANGUAGE');
		///$this->add_rapp_function(2, _("Install/Activate E&xtensions"),
		//	"admin/inst_module.php?", 'SA_CREATEMODULES');
		//$this->add_rapp_function(2, _("Soft&ware Upgrade"),
		//	"admin/inst_upgrade.php?", 'SA_SOFTWAREUPGRADE');
		
		$this->add_module(_("External Interfaces"));
		
		$this->add_lapp_function(3, _("Dealer / Customers Information"),
			"admin/intf_customer.php", 'SA_SETUPCOMPANY');
				$this->add_lapp_function(3, _("EPIN / RCV Item Information"),
			"admin/intf_sales_items.php", 'SA_SETUPCOMPANY');
		$this->add_lapp_function(3, _("Sales Orders"),
			"admin/intf_sales_orders.php", 'SA_SETUPCOMPANY');

		$this->add_lapp_function(3, _("Activation request"),
			"admin/intf_activation.php", 'SA_SETUPCOMPANY');
			
		if (count($installed_extensions) > 0)
		{
			foreach ($installed_extensions as $mod)
			{
				if (@$mod['active'] && $mod['type'] == 'plugin' && $mod["tab"] == "system")
					$this->add_rapp_function(2, $mod["title"], 
						"modules/".$mod["path"]."/".$mod["filename"]."?",
						isset($mod["access"]) ? $mod["access"] : 'SA_OPEN' );
			}
		}
	}
}


?>