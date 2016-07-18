<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$path_to_root="..";
$page_security = 'SA_OPEN';
include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/reporting/includes/reports_classes.inc");
$js = "";
if ($use_date_picker)
	$js .= get_js_date_picker();
page(_($help_context = "Reports and Analysis"), false, false, "", $js);

$reports = new BoxReports;

$dim = get_company_pref('use_dimension');

$reports->addReportClass(_('Dealer'));
/* $reports->addReport(_('Dealer'),101,_('Customer &Balances'),
	array(	_('Start Date') => 'DATEBEGIN',
			_('End Date') => 'DATEENDM',
			_('Customer') => 'CUSTOMERS_NO_FILTER',
			_('Currency Filter') => 'CURRENCY',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION')); */
/*$reports->addReport(_('Customer'),102,_('&Aged Customer Analysis'),
	array(	_('End Date') => 'DATE',
			_('Customer') => 'CUSTOMERS_NO_FILTER',
			_('Currency Filter') => 'CURRENCY',
			_('Summary Only') => 'YES_NO',
			_('Graphics') => 'GRAPHIC',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));*/

/* new report 
	added 17 09 15
	by OD Balogun
*/
$reports->addReport(_('Dealer'),723,_('Monthly EPIN Report'),
	array(	_('Year') => 'YEAR',
			_('Month') => 'MONTH',			
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));	
			
$reports->addReport(_('Dealer'),713,_('Customer EPIN Report'),
	array(	_('Username') => 'USERS',
			_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION',
			_('Customer') => 'CUSTOMERS_NO_FILTER'));

$reports->addReport(_('Dealer'),715,_('Daily Sales Report'),
	array(	_('Inventory Category') => 'CATEGORIES',
			_('Location') => 'LOCATIONS',
			_('Summary Only') => 'YES_NO',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));
			
 /*$reports->addReport(_('Dealer'),715,_('Daily Sales Report'),
	array(	_('Username') => 'USERS',
			_('Start Date') => 'DATEBEGINM',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION',
			_('Customer') => 'CUSTOMERS_NO_FILTER')); */
					
$reports->addReport(_('Dealer'),103,_('Dealer &Detail Listing'),
	array(	_('Activity Since') => 'DATEBEGIN',
			_('Sales Areas') => 'AREAS',
			_('Sales Folk') => 'SALESMEN',
			_('Activity Greater Than') => 'TEXT',
			_('Activity Less Than') => 'TEXT',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));
/* $reports->addReport(_('Dealer'),104,_('&Price Listing'),
	array(	_('Currency Filter') => 'CURRENCY',
			_('Inventory Category') => 'CATEGORIES',
			_('Sales Types') => 'SALESTYPES',
			_('Show Pictures') => 'YES_NO',
			_('Show GP %') => 'YES_NO',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION')); */
$reports->addReport(_('Dealer'),105,_('&Order Status Listing'),
	array(	_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Inventory Category') => 'CATEGORIES',
			_('Stock Location') => 'LOCATIONS',
			_('Back Orders Only') => 'YES_NO',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));
$reports->addReport(_('Dealer'),106,_('&Salesman Listing'),
	array(	_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Summary Only') => 'YES_NO',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));
/*$reports->addReport(_('Customer'),107,_('Print &Invoices/Credit Notes'),
	array(	_('From') => 'INVOICE',
			_('To') => 'INVOICE',
			_('Currency Filter') => 'CURRENCY',
			_('email Customers') => 'YES_NO',
			_('Payment Link') => 'PAYMENT_LINK',
			_('Comments') => 'TEXTBOX'));*/
/* $reports->addReport(_('Dealer'),110,_('Print &Deliveries'),
	array(	_('From') => 'DELIVERY',
			_('To') => 'DELIVERY',
			_('email Customers') => 'YES_NO',
			_('Print as Packing Slip') => 'YES_NO',
			_('Comments') => 'TEXTBOX')); */
/* $reports->addReport(_('Dealer'),108,_('Print &Statements'),
	array(	_('Customer') => 'CUSTOMERS_NO_FILTER',
			_('Currency Filter') => 'CURRENCY',
			_('Email Customers') => 'YES_NO',
			_('Comments') => 'TEXTBOX')); */
$reports->addReport(_('Dealer'),109,_('&Print Sales Orders'),
	array(	_('From') => 'ORDERS',
			_('To') => 'ORDERS',
			_('Currency Filter') => 'CURRENCY',
			_('Email Customers') => 'YES_NO',
			_('Print as Quote') => 'YES_NO',
			_('Comments') => 'TEXTBOX'));
/* $reports->addReport(_('Dealer'),111,_('&Print Sales Quotations'),
	array(	_('From') => 'QUOTATIONS',
			_('To') => 'QUOTATIONS',
			_('Currency Filter') => 'CURRENCY',
			_('Email Customers') => 'YES_NO',
			_('Comments') => 'TEXTBOX')); */
/* $reports->addReport(_('Dealer'),111,_('&Print Sales Quotations'),
	array(	_('From') => 'QUOTATIONS',
			_('To') => 'QUOTATIONS',
			_('Currency Filter') => 'CURRENCY',
			_('Email Customers') => 'YES_NO',
			_('Comments') => 'TEXTBOX')); */
/* $reports->addReport(_('Dealer'),112,_('Print Receipts'),
	array(	_('From') => 'RECEIPT',
			_('To') => 'RECEIPT',
			_('Currency Filter') => 'CURRENCY',
			_('Comments') => 'TEXTBOX')); */


$reports->addReportClass(_('Inventory Management'));

$reports->addReport(_('Inventory Management'),301,_('Inventory &Valuation Report'),
	array(	_('Inventory Category') => 'CATEGORIES',
			_('Location') => 'LOCATIONS',
			_('Summary Only') => 'YES_NO',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));
$reports->addReport(_('Inventory Management'),302,_('Inventory &Planning Report'),
	array(	_('Inventory Category') => 'CATEGORIES',
			_('Location') => 'LOCATIONS',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));
$reports->addReport(_('Inventory Management'),303,_('Stock &Check Sheets'),
	array(	_('Inventory Category') => 'CATEGORIES',
			_('Location') => 'LOCATIONS',
			_('Show Pictures') => 'YES_NO',
			_('Inventory Column') => 'YES_NO',
			_('Show Shortage') => 'YES_NO',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));
$reports->addReport(_('Inventory Management'),304,_('Inventory &Sales Report'),
	array(	_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Inventory Category') => 'CATEGORIES',
			_('Location') => 'LOCATIONS',
			_('Customer') => 'CUSTOMERS_NO_FILTER',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));
			
$reports->addReport(_('Inventory Management'),306,_('Inventory &Movement Report'),
	array(	_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));

$reports->addReport(_('Inventory Management'),307,_('Product Sales &Summary'),
	array(	_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));
			
$reports->addReport(_('Inventory Management'),308,_('Detail &Transaction Report'),
	array(	_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));

/* $reports->addReport(_('Inventory Management'),305,_('&GRN Valuation Report'),
	array(	_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION')); */



//$reports->addReport(_('General Ledger'),703,_('GL Account Group Summary'),
//	array(	_('Comments'),'TEXTBOX')));

if ($dim == 2)
{

}
else if ($dim == 1)
{

}
else
{

}
			
$reports->addReportClass(_('Security Management'));
	$reports->addReport(_('Security Management'),711,_('Audit Trail'),
	array(	_('Username') => 'USERS',
			_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));
	
	$reports->addReport(_('Security Management'),716,_('PIN Enquiry Activity Report'),
	array(	_('Username') => 'USERS',
			_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));
			
	$reports->addReport(_('Security Management'),717,_('PIN Recharge Activity Report'),
	array(	_('Username') => 'USERS',
			_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Comments') => 'TEXTBOX',
			_('Destination') => 'DESTINATION'));

add_custom_reports($reports);

echo "<script language='javascript'>
		function onWindowLoad() {
			showClass(" . $_GET['Class'] . ")
		}
	Behaviour.addLoadEvent(onWindowLoad);
	</script>
";
if($nonfin_audit_trail){
			$ip = preg_quote($_SERVER['REMOTE_ADDR']);
			add_nonfin_audit_trail(0,0,0,0,'REPORT PEQUEST','A',$ip,'REPORT '. $_GET['Class'].' REQUESTED ');
}
echo $reports->getDisplay();

end_page();
?>