# MySQL dump of database 'simplexaccount' on host 'localhost'
# Backup Date and Time: 2010-02-23 05:50
# Built by SimplexAccounting 2.2.5
# http://Simplexaccounting.net
# Company: Training Telco Co.
# User: Administrator



### Structure of table `0_areas` ###

DROP TABLE IF EXISTS `0_areas`;

CREATE TABLE `0_areas` (
  `area_code` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_code`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;


### Data of table `0_areas` ###

INSERT INTO `0_areas` VALUES ('1', 'Lagos', '0');
INSERT INTO `0_areas` VALUES ('2', 'Ibadan', '0');
INSERT INTO `0_areas` VALUES ('3', 'Abuja', '0');
INSERT INTO `0_areas` VALUES ('4', 'Port Harcourt', '0');
INSERT INTO `0_areas` VALUES ('5', 'Kano', '0');


### Structure of table `0_attachments` ###

DROP TABLE IF EXISTS `0_attachments`;

CREATE TABLE `0_attachments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `type_no` int(11) NOT NULL DEFAULT '0',
  `trans_no` int(11) NOT NULL DEFAULT '0',
  `unique_name` varchar(60) NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `filename` varchar(60) NOT NULL DEFAULT '',
  `filesize` int(11) NOT NULL DEFAULT '0',
  `filetype` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `type_no` (`type_no`,`trans_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_attachments` ###



### Structure of table `0_audit_trail` ###

DROP TABLE IF EXISTS `0_audit_trail`;

CREATE TABLE `0_audit_trail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `user` smallint(6) unsigned NOT NULL DEFAULT '0',
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `description` varchar(60) DEFAULT NULL,
  `fiscal_year` int(11) NOT NULL,
  `gl_date` date NOT NULL DEFAULT '0000-00-00',
  `gl_seq` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fiscal_year` (`fiscal_year`,`gl_seq`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;


### Data of table `0_audit_trail` ###

INSERT INTO `0_audit_trail` VALUES ('1', '18', '1', '1', '2010-02-22 15:32:34', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('2', '25', '1', '1', '2010-02-22 15:32:47', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('3', '20', '1', '1', '2010-02-22 15:33:48', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('4', '22', '1', '1', '2010-02-22 15:34:19', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('5', '2', '1', '1', '2010-02-22 15:35:54', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('6', '16', '1', '1', '2010-02-22 15:38:22', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('7', '32', '1', '1', '2010-02-22 15:57:49', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('8', '30', '1', '1', '2010-02-22 15:58:02', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('9', '12', '1', '1', '2010-02-22 15:59:13', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('10', '13', '1', '1', '2010-02-22 15:59:53', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('11', '10', '1', '1', '2010-02-22 15:59:58', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('12', '32', '2', '1', '2010-02-22 17:57:16', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('13', '30', '2', '1', '2010-02-22 17:58:02', NULL, '3', '2010-02-22', NULL);
INSERT INTO `0_audit_trail` VALUES ('14', '30', '2', '1', '2010-02-22 17:58:02', 'Updated.', '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('15', '12', '2', '1', '2010-02-22 18:01:28', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('16', '13', '2', '1', '2010-02-22 18:05:28', NULL, '3', '2010-02-22', NULL);
INSERT INTO `0_audit_trail` VALUES ('17', '13', '2', '1', '2010-02-22 18:10:26', 'Updated.', '3', '2010-02-22', NULL);
INSERT INTO `0_audit_trail` VALUES ('18', '13', '3', '1', '2010-02-22 18:08:26', NULL, '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('19', '13', '2', '1', '2010-02-22 18:10:26', 'Updated.', '3', '2010-02-22', '0');
INSERT INTO `0_audit_trail` VALUES ('20', '32', '3', '1', '2010-02-23 04:12:27', NULL, '3', '2010-02-23', '0');
INSERT INTO `0_audit_trail` VALUES ('21', '30', '3', '1', '2010-02-23 04:12:42', NULL, '3', '2010-02-23', '0');


### Structure of table `0_bank_accounts` ###

DROP TABLE IF EXISTS `0_bank_accounts`;

CREATE TABLE `0_bank_accounts` (
  `account_code` varchar(11) NOT NULL DEFAULT '',
  `account_type` smallint(6) NOT NULL DEFAULT '0',
  `bank_account_name` varchar(60) NOT NULL DEFAULT '',
  `bank_account_number` varchar(100) NOT NULL DEFAULT '',
  `bank_name` varchar(60) NOT NULL DEFAULT '',
  `bank_address` tinytext,
  `bank_curr_code` char(3) NOT NULL DEFAULT '',
  `dflt_curr_act` tinyint(1) NOT NULL DEFAULT '0',
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `last_reconciled_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ending_reconcile_balance` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bank_account_name` (`bank_account_name`),
  KEY `bank_account_number` (`bank_account_number`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


### Data of table `0_bank_accounts` ###

INSERT INTO `0_bank_accounts` VALUES ('1060', '0', 'Our Teleco Company Account', '22222-222--2222', 'UBA Plc', 'Idowu Talyor, VI', 'NGN', '0', '1', '0000-00-00 00:00:00', '0', '0');
INSERT INTO `0_bank_accounts` VALUES ('1065', '0', 'Cash', '', 'Office CashBox', 'Office', 'NGN', '1', '2', '0000-00-00 00:00:00', '0', '0');


### Structure of table `0_bank_trans` ###

DROP TABLE IF EXISTS `0_bank_trans`;

CREATE TABLE `0_bank_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) DEFAULT NULL,
  `trans_no` int(11) DEFAULT NULL,
  `bank_act` varchar(11) DEFAULT NULL,
  `ref` varchar(40) DEFAULT NULL,
  `trans_date` date NOT NULL DEFAULT '0000-00-00',
  `amount` double DEFAULT NULL,
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `person_type_id` int(11) NOT NULL DEFAULT '0',
  `person_id` tinyblob,
  `reconciled` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_act` (`bank_act`,`ref`),
  KEY `type` (`type`,`trans_no`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


### Data of table `0_bank_trans` ###

INSERT INTO `0_bank_trans` VALUES ('1', '22', '1', '2', '1', '2010-02-22', '-44100', '0', '0', '3', '1', NULL);
INSERT INTO `0_bank_trans` VALUES ('2', '2', '1', '2', '1', '2010-02-22', '50000', '0', '0', '0', 'Funding Account', NULL);
INSERT INTO `0_bank_trans` VALUES ('3', '12', '1', '2', '1', '2010-02-22', '630', '0', '0', '2', 'L001', NULL);
INSERT INTO `0_bank_trans` VALUES ('4', '12', '2', '2', '2', '2010-02-22', '450', '0', '0', '2', 'L001', NULL);


### Structure of table `0_bom` ###

DROP TABLE IF EXISTS `0_bom`;

CREATE TABLE `0_bom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` char(20) NOT NULL DEFAULT '',
  `component` char(20) NOT NULL DEFAULT '',
  `workcentre_added` int(11) NOT NULL DEFAULT '0',
  `loc_code` char(5) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`parent`,`component`,`workcentre_added`,`loc_code`),
  KEY `component` (`component`),
  KEY `id` (`id`),
  KEY `loc_code` (`loc_code`),
  KEY `parent` (`parent`,`loc_code`),
  KEY `Parent_2` (`parent`),
  KEY `workcentre_added` (`workcentre_added`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_bom` ###



### Structure of table `0_budget_trans` ###

DROP TABLE IF EXISTS `0_budget_trans`;

CREATE TABLE `0_budget_trans` (
  `counter` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `type_no` bigint(16) NOT NULL DEFAULT '1',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `account` varchar(11) NOT NULL DEFAULT '',
  `memo_` tinytext NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `dimension_id` int(11) DEFAULT '0',
  `dimension2_id` int(11) DEFAULT '0',
  `person_type_id` int(11) DEFAULT NULL,
  `person_id` tinyblob,
  PRIMARY KEY (`counter`),
  KEY `Type_and_Number` (`type`,`type_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_budget_trans` ###



### Structure of table `0_chart_class` ###

DROP TABLE IF EXISTS `0_chart_class`;

CREATE TABLE `0_chart_class` (
  `cid` int(11) NOT NULL DEFAULT '0',
  `class_name` varchar(60) NOT NULL DEFAULT '',
  `ctype` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;


### Data of table `0_chart_class` ###

INSERT INTO `0_chart_class` VALUES ('1', 'Assets', '1', '0');
INSERT INTO `0_chart_class` VALUES ('2', 'Liabilities', '2', '0');
INSERT INTO `0_chart_class` VALUES ('3', 'Income', '4', '0');
INSERT INTO `0_chart_class` VALUES ('4', 'Costs', '6', '0');


### Structure of table `0_chart_master` ###

DROP TABLE IF EXISTS `0_chart_master`;

CREATE TABLE `0_chart_master` (
  `account_code` varchar(11) NOT NULL DEFAULT '',
  `account_code2` varchar(11) DEFAULT '',
  `account_name` varchar(60) NOT NULL DEFAULT '',
  `account_type` int(11) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_code`),
  KEY `account_code` (`account_code`),
  KEY `account_name` (`account_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;


### Data of table `0_chart_master` ###

INSERT INTO `0_chart_master` VALUES ('1060', '', 'Checking Account', '1', '0');
INSERT INTO `0_chart_master` VALUES ('1065', '', 'Petty Cash', '1', '0');
INSERT INTO `0_chart_master` VALUES ('1200', '', 'Accounts Receivables', '1', '0');
INSERT INTO `0_chart_master` VALUES ('1205', '', 'Allowance for doubtful accounts', '1', '0');
INSERT INTO `0_chart_master` VALUES ('1510', '', 'Inventory', '2', '0');
INSERT INTO `0_chart_master` VALUES ('1520', '', 'Stocks of Raw Materials', '2', '0');
INSERT INTO `0_chart_master` VALUES ('1530', '', 'Stocks of Work In Progress', '2', '0');
INSERT INTO `0_chart_master` VALUES ('1540', '', 'Stocks of Finsihed Goods', '2', '0');
INSERT INTO `0_chart_master` VALUES ('1550', '', 'Goods Received Clearing account', '2', '0');
INSERT INTO `0_chart_master` VALUES ('1820', '', 'Office Furniture &amp; Equipment', '3', '0');
INSERT INTO `0_chart_master` VALUES ('1825', '', 'Accum. Amort. -Furn. &amp; Equip.', '3', '0');
INSERT INTO `0_chart_master` VALUES ('1840', '', 'Vehicle', '3', '0');
INSERT INTO `0_chart_master` VALUES ('1845', '', 'Accum. Amort. -Vehicle', '3', '0');
INSERT INTO `0_chart_master` VALUES ('2100', '', 'Accounts Payable', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2110', '', 'Accrued Income Tax - Federal', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2120', '', 'Accrued Income Tax - State', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2130', '', 'Accrued Franchise Tax', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2140', '', 'Accrued Real &amp; Personal Prop Tax', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2150', '', 'Sales Tax', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2160', '', 'Accrued Use Tax Payable', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2210', '', 'Accrued Wages', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2220', '', 'Accrued Comp Time', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2230', '', 'Accrued Holiday Pay', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2240', '', 'Accrued Vacation Pay', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2310', '', 'Accr. Benefits - 401K', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2320', '', 'Accr. Benefits - Stock Purchase', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2330', '', 'Accr. Benefits - Med, Den', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2340', '', 'Accr. Benefits - Payroll Taxes', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2350', '', 'Accr. Benefits - Credit Union', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2360', '', 'Accr. Benefits - Savings Bond', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2370', '', 'Accr. Benefits - Garnish', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2380', '', 'Accr. Benefits - Charity Cont.', '4', '0');
INSERT INTO `0_chart_master` VALUES ('2620', '', 'Bank Loans', '5', '0');
INSERT INTO `0_chart_master` VALUES ('2680', '', 'Loans from Shareholders', '5', '0');
INSERT INTO `0_chart_master` VALUES ('3350', '', 'Common Shares', '6', '0');
INSERT INTO `0_chart_master` VALUES ('3590', '', 'Retained Earnings - prior years', '7', '0');
INSERT INTO `0_chart_master` VALUES ('4010', '', 'Sales', '8', '0');
INSERT INTO `0_chart_master` VALUES ('4430', '', 'Shipping &amp; Handling', '9', '0');
INSERT INTO `0_chart_master` VALUES ('4440', '', 'Interest', '9', '0');
INSERT INTO `0_chart_master` VALUES ('4450', '', 'Foreign Exchange Gain', '9', '0');
INSERT INTO `0_chart_master` VALUES ('4500', '', 'Prompt Payment Discounts', '9', '0');
INSERT INTO `0_chart_master` VALUES ('4510', '', 'Discounts Given', '9', '0');
INSERT INTO `0_chart_master` VALUES ('5010', '', 'Cost of Goods Sold - Retail', '10', '0');
INSERT INTO `0_chart_master` VALUES ('5020', '', 'Material Usage Varaiance', '10', '0');
INSERT INTO `0_chart_master` VALUES ('5030', '', 'Consumable Materials', '10', '0');
INSERT INTO `0_chart_master` VALUES ('5040', '', 'Purchase price Variance', '10', '0');
INSERT INTO `0_chart_master` VALUES ('5050', '', 'Purchases of materials', '10', '0');
INSERT INTO `0_chart_master` VALUES ('5060', '', 'Discounts Received', '10', '0');
INSERT INTO `0_chart_master` VALUES ('5100', '', 'Freight', '10', '0');
INSERT INTO `0_chart_master` VALUES ('5410', '', 'Wages &amp; Salaries', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5420', '', 'Wages - Overtime', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5430', '', 'Benefits - Comp Time', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5440', '', 'Benefits - Payroll Taxes', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5450', '', 'Benefits - Workers Comp', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5460', '', 'Benefits - Pension', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5470', '', 'Benefits - General Benefits', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5510', '', 'Inc Tax Exp - Federal', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5520', '', 'Inc Tax Exp - State', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5530', '', 'Taxes - Real Estate', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5540', '', 'Taxes - Personal Property', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5550', '', 'Taxes - Franchise', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5560', '', 'Taxes - Foreign Withholding', '11', '0');
INSERT INTO `0_chart_master` VALUES ('5610', '', 'Accounting &amp; Legal', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5615', '', 'Advertising &amp; Promotions', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5620', '', 'Bad Debts', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5660', '', 'Amortization Expense', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5685', '', 'Insurance', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5690', '', 'Interest &amp; Bank Charges', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5700', '', 'Office Supplies', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5760', '', 'Rent', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5765', '', 'Repair &amp; Maintenance', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5780', '', 'Telephone', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5785', '', 'Travel &amp; Entertainment', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5790', '', 'Utilities', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5795', '', 'Registrations', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5800', '', 'Licenses', '12', '0');
INSERT INTO `0_chart_master` VALUES ('5810', '', 'Foreign Exchange Loss', '12', '0');
INSERT INTO `0_chart_master` VALUES ('9990', '', 'Year Profit/Loss', '12', '0');


### Structure of table `0_chart_types` ###

DROP TABLE IF EXISTS `0_chart_types`;

CREATE TABLE `0_chart_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `class_id` tinyint(1) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '-1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;


### Data of table `0_chart_types` ###

INSERT INTO `0_chart_types` VALUES ('1', 'Current Assets', '1', '-1', '0');
INSERT INTO `0_chart_types` VALUES ('2', 'Inventory Assets', '1', '-1', '0');
INSERT INTO `0_chart_types` VALUES ('3', 'Capital Assets', '1', '-1', '0');
INSERT INTO `0_chart_types` VALUES ('4', 'Current Liabilities', '2', '-1', '0');
INSERT INTO `0_chart_types` VALUES ('5', 'Long Term Liabilities', '2', '-1', '0');
INSERT INTO `0_chart_types` VALUES ('6', 'Share Capital', '2', '-1', '0');
INSERT INTO `0_chart_types` VALUES ('7', 'Retained Earnings', '2', '-1', '0');
INSERT INTO `0_chart_types` VALUES ('8', 'Sales Revenue', '3', '-1', '0');
INSERT INTO `0_chart_types` VALUES ('9', 'Other Revenue', '3', '-1', '0');
INSERT INTO `0_chart_types` VALUES ('10', 'Cost of Goods Sold', '4', '-1', '0');
INSERT INTO `0_chart_types` VALUES ('11', 'Payroll Expenses', '4', '-1', '0');
INSERT INTO `0_chart_types` VALUES ('12', 'General &amp; Administrative expenses', '4', '-1', '0');


### Structure of table `0_comments` ###

DROP TABLE IF EXISTS `0_comments`;

CREATE TABLE `0_comments` (
  `type` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `date_` date DEFAULT '0000-00-00',
  `memo_` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_comments` ###

INSERT INTO `0_comments` VALUES ('40', '1', '2010-02-22', 'note');
INSERT INTO `0_comments` VALUES ('12', '1', '2010-02-22', 'Direct Sales Payment');
INSERT INTO `0_comments` VALUES ('13', '1', '2010-02-22', 'Sales Quotation # 1');
INSERT INTO `0_comments` VALUES ('10', '1', '2010-02-22', 'Sales Quotation # 1');
INSERT INTO `0_comments` VALUES ('12', '2', '2010-02-22', 'tESTING ');
INSERT INTO `0_comments` VALUES ('13', '3', '2010-02-22', 'Sales Quotation # 2');
INSERT INTO `0_comments` VALUES ('13', '2', '2010-02-22', 'Sales Quotation # 2');


### Structure of table `0_company` ###

DROP TABLE IF EXISTS `0_company`;

CREATE TABLE `0_company` (
  `coy_code` int(11) NOT NULL DEFAULT '1',
  `coy_name` varchar(60) NOT NULL DEFAULT '',
  `gst_no` varchar(25) NOT NULL DEFAULT '',
  `coy_no` varchar(25) NOT NULL DEFAULT '0',
  `tax_prd` int(11) NOT NULL DEFAULT '1',
  `tax_last` int(11) NOT NULL DEFAULT '1',
  `postal_address` tinytext NOT NULL,
  `phone` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `coy_logo` varchar(100) NOT NULL DEFAULT '',
  `domicile` varchar(55) NOT NULL DEFAULT '',
  `curr_default` char(3) NOT NULL DEFAULT '',
  `debtors_act` varchar(11) NOT NULL DEFAULT '',
  `pyt_discount_act` varchar(11) NOT NULL DEFAULT '',
  `creditors_act` varchar(11) NOT NULL DEFAULT '',
  `bank_charge_act` varchar(11) NOT NULL DEFAULT '',
  `exchange_diff_act` varchar(11) NOT NULL DEFAULT '',
  `profit_loss_year_act` varchar(11) NOT NULL DEFAULT '',
  `retained_earnings_act` varchar(11) NOT NULL DEFAULT '',
  `freight_act` varchar(11) NOT NULL DEFAULT '',
  `default_sales_act` varchar(11) NOT NULL DEFAULT '',
  `default_sales_discount_act` varchar(11) NOT NULL DEFAULT '',
  `default_prompt_payment_act` varchar(11) NOT NULL DEFAULT '',
  `default_inventory_act` varchar(11) NOT NULL DEFAULT '',
  `default_cogs_act` varchar(11) NOT NULL DEFAULT '',
  `default_adj_act` varchar(11) NOT NULL DEFAULT '',
  `default_inv_sales_act` varchar(11) NOT NULL DEFAULT '',
  `default_assembly_act` varchar(11) NOT NULL DEFAULT '',
  `payroll_act` varchar(11) NOT NULL DEFAULT '',
  `allow_negative_stock` tinyint(1) NOT NULL DEFAULT '0',
  `po_over_receive` int(11) NOT NULL DEFAULT '10',
  `po_over_charge` int(11) NOT NULL DEFAULT '10',
  `default_credit_limit` int(11) NOT NULL DEFAULT '1000',
  `default_workorder_required` int(11) NOT NULL DEFAULT '20',
  `default_dim_required` int(11) NOT NULL DEFAULT '20',
  `past_due_days` int(11) NOT NULL DEFAULT '30',
  `use_dimension` tinyint(1) DEFAULT '0',
  `f_year` int(11) NOT NULL DEFAULT '1',
  `no_item_list` tinyint(1) NOT NULL DEFAULT '0',
  `no_customer_list` tinyint(1) NOT NULL DEFAULT '0',
  `no_supplier_list` tinyint(1) NOT NULL DEFAULT '0',
  `base_sales` int(11) NOT NULL DEFAULT '-1',
  `foreign_codes` tinyint(1) NOT NULL DEFAULT '0',
  `accumulate_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `legal_text` tinytext NOT NULL,
  `default_delivery_required` smallint(6) NOT NULL DEFAULT '1',
  `version_id` varchar(11) NOT NULL DEFAULT '',
  `time_zone` tinyint(1) NOT NULL DEFAULT '0',
  `add_pct` int(5) NOT NULL DEFAULT '-1',
  `round_to` int(5) NOT NULL DEFAULT '1',
  `login_tout` smallint(6) NOT NULL DEFAULT '600',
  PRIMARY KEY (`coy_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;


### Data of table `0_company` ###

INSERT INTO `0_company` VALUES ('1', 'Training Telco Co.', '9876543', '123456789', '1', '1', 'Address 1\r\nAddress 2\r\nAddress 3', '(222) 111.222.333', '', 'delta@delta.com', 'etisalat..jpg', '', 'NGN', '1200', '5060', '2100', '5690', '4450', '9990', '3590', '4430', '4010', '4510', '4500', '1510', '5010', '5040', '4010', '1530', '5000', '0', '10', '10', '0', '20', '20', '30', '1', '3', '0', '1', '0', '1', '0', '0', '', '1', '2.2', '0', '-1', '1', '600');


### Structure of table `0_credit_status` ###

DROP TABLE IF EXISTS `0_credit_status`;

CREATE TABLE `0_credit_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason_description` char(100) NOT NULL DEFAULT '',
  `dissallow_invoices` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reason_description` (`reason_description`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;


### Data of table `0_credit_status` ###

INSERT INTO `0_credit_status` VALUES ('1', 'Sales Order Blocked', '1', '0');
INSERT INTO `0_credit_status` VALUES ('2', 'Cash Customer', '0', '0');
INSERT INTO `0_credit_status` VALUES ('3', 'Credit Customer', '0', '0');


### Structure of table `0_currencies` ###

DROP TABLE IF EXISTS `0_currencies`;

CREATE TABLE `0_currencies` (
  `currency` varchar(60) NOT NULL DEFAULT '',
  `curr_abrev` char(3) NOT NULL DEFAULT '',
  `curr_symbol` varchar(10) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `hundreds_name` varchar(15) NOT NULL DEFAULT '',
  `auto_update` tinyint(1) NOT NULL DEFAULT '1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`curr_abrev`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;


### Data of table `0_currencies` ###

INSERT INTO `0_currencies` VALUES ('US Dollars', 'USD', '$', 'United States', 'Cents', '0', '0');
INSERT INTO `0_currencies` VALUES ('Naira', 'NGN', '=N=', 'Nigeria', 'Kobo', '0', '0');
INSERT INTO `0_currencies` VALUES ('Euro', 'EUR', '?', 'Europe', 'Cents', '0', '0');
INSERT INTO `0_currencies` VALUES ('Pounds', 'GBP', '?', 'England', 'Pence', '0', '0');


### Structure of table `0_cust_allocations` ###

DROP TABLE IF EXISTS `0_cust_allocations`;

CREATE TABLE `0_cust_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amt` double unsigned DEFAULT NULL,
  `date_alloc` date NOT NULL DEFAULT '0000-00-00',
  `trans_no_from` int(11) DEFAULT NULL,
  `trans_type_from` int(11) DEFAULT NULL,
  `trans_no_to` int(11) DEFAULT NULL,
  `trans_type_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_cust_allocations` ###

INSERT INTO `0_cust_allocations` VALUES ('1', '630', '2010-02-22', '1', '12', '1', '10');


### Structure of table `0_cust_branch` ###

DROP TABLE IF EXISTS `0_cust_branch`;

CREATE TABLE `0_cust_branch` (
  `branch_code` int(11) NOT NULL AUTO_INCREMENT,
  `debtor_no` varchar(64) NOT NULL DEFAULT '0',
  `br_name` varchar(60) NOT NULL DEFAULT '',
  `branch_ref` varchar(30) NOT NULL DEFAULT '',
  `br_address` tinytext NOT NULL,
  `area` int(11) DEFAULT NULL,
  `salesman` int(11) NOT NULL DEFAULT '0',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `phone2` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `contact_name` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `default_location` varchar(5) NOT NULL DEFAULT '',
  `tax_group_id` int(11) DEFAULT NULL,
  `sales_account` varchar(11) DEFAULT NULL,
  `sales_discount_account` varchar(11) DEFAULT NULL,
  `receivables_account` varchar(11) DEFAULT NULL,
  `payment_discount_account` varchar(11) DEFAULT NULL,
  `default_ship_via` int(11) NOT NULL DEFAULT '1',
  `disable_trans` tinyint(4) NOT NULL DEFAULT '0',
  `br_post_address` tinytext NOT NULL,
  `group_no` int(11) NOT NULL DEFAULT '0',
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`branch_code`,`debtor_no`) USING BTREE,
  KEY `branch_code` (`branch_code`),
  KEY `br_name` (`br_name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


### Data of table `0_cust_branch` ###

INSERT INTO `0_cust_branch` VALUES ('1', 'IB001', 'Ibadan Customer', 'IbadanCustomer', 'Lagos', '3', '2', '', '', '', 'Main Branch', '', 'IBW', '1', NULL, '4510', '1200', '4500', '1', '0', 'Lagos', '0', '', '0');
INSERT INTO `0_cust_branch` VALUES ('2', 'L001', 'Lagos Customer Ben', 'Lagos Ben', 'Lagos', '3', '2', '', '', '', 'Main Branch', '', 'WH', '1', NULL, '4510', '1200', '4500', '1', '0', 'Lagos', '0', '', '0');


### Structure of table `0_debtor_trans` ###

DROP TABLE IF EXISTS `0_debtor_trans`;

CREATE TABLE `0_debtor_trans` (
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `version` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `debtor_no` varchar(128) DEFAULT NULL,
  `branch_code` int(11) NOT NULL DEFAULT '-1',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` varchar(60) NOT NULL DEFAULT '',
  `tpe` int(11) NOT NULL DEFAULT '0',
  `order_` int(11) NOT NULL DEFAULT '0',
  `ov_amount` double NOT NULL DEFAULT '0',
  `ov_gst` double NOT NULL DEFAULT '0',
  `ov_freight` double NOT NULL DEFAULT '0',
  `ov_freight_tax` double NOT NULL DEFAULT '0',
  `ov_discount` double NOT NULL DEFAULT '0',
  `alloc` double NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '1',
  `ship_via` int(11) DEFAULT NULL,
  `trans_link` int(11) NOT NULL DEFAULT '0',
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_no`,`type`),
  KEY `debtor_no` (`debtor_no`,`branch_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_debtor_trans` ###

INSERT INTO `0_debtor_trans` VALUES ('1', '10', '0', 'L001', '2', '2010-02-22', '2010-02-22', '1', '1', '1', '630', '0', '0', '0', '0', '630', '1', '1', '1', '0', '0');
INSERT INTO `0_debtor_trans` VALUES ('1', '12', '0', 'L001', '2', '2010-02-22', '0000-00-00', '1', '0', '0', '630', '0', '0', '0', '0', '630', '1', '0', '0', '0', '0');
INSERT INTO `0_debtor_trans` VALUES ('1', '13', '1', 'L001', '2', '2010-02-22', '2010-02-22', '1', '1', '1', '630', '0', '0', '0', '0', '0', '1', '1', '1', '0', '0');
INSERT INTO `0_debtor_trans` VALUES ('2', '12', '0', 'L001', '2', '2010-02-22', '0000-00-00', '2', '0', '0', '450', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0');
INSERT INTO `0_debtor_trans` VALUES ('2', '13', '0', 'L001', '2', '2010-02-22', '2010-02-22', '2', '1', '2', '90', '0', '0', '0', '0', '0', '1', '1', '0', '0', '0');
INSERT INTO `0_debtor_trans` VALUES ('3', '13', '0', 'L001', '2', '2010-02-22', '2010-02-22', '3', '1', '2', '180', '0', '0', '0', '0', '0', '1', '1', '0', '0', '0');


### Structure of table `0_debtor_trans_details` ###

DROP TABLE IF EXISTS `0_debtor_trans_details`;

CREATE TABLE `0_debtor_trans_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `debtor_trans_no` int(11) DEFAULT NULL,
  `debtor_trans_type` int(11) DEFAULT NULL,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `unit_price` double NOT NULL DEFAULT '0',
  `unit_tax` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `discount_percent` double NOT NULL DEFAULT '0',
  `standard_cost` double NOT NULL DEFAULT '0',
  `qty_done` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


### Data of table `0_debtor_trans_details` ###

INSERT INTO `0_debtor_trans_details` VALUES ('1', '1', '13', 'N100A', 'N100 Airtime Card', '90', '4.2857', '7', '0', '84', '7');
INSERT INTO `0_debtor_trans_details` VALUES ('2', '1', '10', 'N100A', 'N100 Airtime Card', '90', '4.2857', '7', '0', '84', '0');
INSERT INTO `0_debtor_trans_details` VALUES ('3', '2', '13', 'N100A', 'N100 Airtime Card', '90', '4.2857', '1', '0', '84', '0');
INSERT INTO `0_debtor_trans_details` VALUES ('4', '3', '13', 'N100A', 'N100 Airtime Card', '90', '4.2857', '2', '0', '84', '0');


### Structure of table `0_debtors_master` ###

DROP TABLE IF EXISTS `0_debtors_master`;

CREATE TABLE `0_debtors_master` (
  `debtor_no` varchar(40) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `debtor_ref` varchar(30) NOT NULL,
  `address` tinytext,
  `email` varchar(100) NOT NULL DEFAULT '',
  `tax_id` varchar(55) NOT NULL DEFAULT '',
  `curr_code` char(3) NOT NULL DEFAULT '',
  `sales_type` int(11) NOT NULL DEFAULT '1',
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `credit_status` int(11) NOT NULL DEFAULT '0',
  `payment_terms` int(11) DEFAULT NULL,
  `discount` double NOT NULL DEFAULT '0',
  `pymt_discount` double NOT NULL DEFAULT '0',
  `credit_limit` float NOT NULL DEFAULT '1000',
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`debtor_no`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;


### Data of table `0_debtors_master` ###

INSERT INTO `0_debtors_master` VALUES ('IB001', 'Ibadan Customer', 'IbadanCustomer', 'Lagos', '', '', 'NGN', '1', '0', '0', '2', '1', '0', '0', '0', '', '0');
INSERT INTO `0_debtors_master` VALUES ('L001', 'Lagos Customer Ben', 'Lagos Ben', 'Lagos', '', '', 'NGN', '1', '0', '0', '1', '1', '0', '0', '100000', '', '0');
INSERT INTO `0_debtors_master` VALUES ('X110', 'Blue Ocean Telecom', 'Blue', 'Lagos', '', '', 'NGN', '1', '0', '0', '2', '1', '0', '0', '1e+006', '', '0');


### Structure of table `0_debtors_terms_requests` ###

DROP TABLE IF EXISTS `0_debtors_terms_requests`;

CREATE TABLE `0_debtors_terms_requests` (
  `debtor_no` varchar(40) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `debtor_ref` varchar(30) NOT NULL,
  `address` tinytext,
  `email` varchar(100) NOT NULL DEFAULT '',
  `tax_id` varchar(55) NOT NULL DEFAULT '',
  `curr_code` char(3) NOT NULL DEFAULT '',
  `sales_type` int(10) DEFAULT NULL,
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `credit_status` int(11) NOT NULL DEFAULT '0',
  `payment_terms` int(11) DEFAULT NULL,
  `discount` double NOT NULL DEFAULT '0',
  `pymt_discount` double NOT NULL DEFAULT '0',
  `credit_limit` float NOT NULL DEFAULT '1000',
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `requested_by` varchar(128) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `approved_by` varchar(128) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `request_status` varchar(45) NOT NULL DEFAULT 'Planned',
  `version` int(10) unsigned NOT NULL DEFAULT '0',
  `last_updated_by` varchar(128) NOT NULL,
  `last_updated_date` datetime NOT NULL,
  PRIMARY KEY (`request_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


### Data of table `0_debtors_terms_requests` ###

INSERT INTO `0_debtors_terms_requests` VALUES ('X110', 'Blue Ocean Telecom', 'Blue', 'Lagos', '', '', 'NGN', '1', '0', '0', '2', '1', '0', '0', '1e+006', '', '0', '1', 'admin', '2010-02-23 06:23:50', 'admin', '2010-02-23 06:24:22', 'Confirmed', '0', 'admin', '2010-02-23 06:23:50');
INSERT INTO `0_debtors_terms_requests` VALUES ('L001', 'Lagos Customer Ben', 'Lagos Ben', 'Lagos', '', '', 'NGN', '1', '0', '0', '1', '1', '0', '0', '100000', '', '0', '2', 'admin', '2010-02-23 06:24:55', 'admin', '2010-02-23 06:25:08', 'Confirmed', '0', 'admin', '2010-02-23 06:24:55');


### Structure of table `0_dimensions` ###

DROP TABLE IF EXISTS `0_dimensions`;

CREATE TABLE `0_dimensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `type_` tinyint(1) NOT NULL DEFAULT '1',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_dimensions` ###

INSERT INTO `0_dimensions` VALUES ('1', '1', 'Test Dimension', '1', '0', '2010-02-22', '2010-03-14');


### Structure of table `0_exchange_rates` ###

DROP TABLE IF EXISTS `0_exchange_rates`;

CREATE TABLE `0_exchange_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curr_code` char(3) NOT NULL DEFAULT '',
  `rate_buy` double NOT NULL DEFAULT '0',
  `rate_sell` double NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `curr_code` (`curr_code`,`date_`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;


### Data of table `0_exchange_rates` ###

INSERT INTO `0_exchange_rates` VALUES ('7', 'EUR', '187', '187', '2010-02-22');
INSERT INTO `0_exchange_rates` VALUES ('6', 'GBP', '250', '250', '2010-02-22');
INSERT INTO `0_exchange_rates` VALUES ('5', 'USD', '150', '150', '2010-02-22');


### Structure of table `0_fiscal_year` ###

DROP TABLE IF EXISTS `0_fiscal_year`;

CREATE TABLE `0_fiscal_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `begin` date DEFAULT '0000-00-00',
  `end` date DEFAULT '0000-00-00',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;


### Data of table `0_fiscal_year` ###

INSERT INTO `0_fiscal_year` VALUES ('1', '2008-01-01', '2008-12-31', '0');
INSERT INTO `0_fiscal_year` VALUES ('2', '2009-01-01', '2009-12-31', '0');
INSERT INTO `0_fiscal_year` VALUES ('3', '2010-01-01', '2010-12-31', '0');


### Structure of table `0_gl_trans` ###

DROP TABLE IF EXISTS `0_gl_trans`;

CREATE TABLE `0_gl_trans` (
  `counter` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `type_no` bigint(16) NOT NULL DEFAULT '1',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `account` varchar(11) NOT NULL DEFAULT '',
  `memo_` tinytext NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `person_type_id` int(11) DEFAULT NULL,
  `person_id` tinyblob,
  PRIMARY KEY (`counter`),
  KEY `Type_and_Number` (`type`,`type_no`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;


### Data of table `0_gl_trans` ###

INSERT INTO `0_gl_trans` VALUES ('1', '20', '1', '2010-02-22', '2100', '', '-44100', '0', '0', '3', '1');
INSERT INTO `0_gl_trans` VALUES ('2', '20', '1', '2010-02-22', '1510', '', '42000', '1', '0', '3', '1');
INSERT INTO `0_gl_trans` VALUES ('3', '20', '1', '2010-02-22', '2150', '', '2100', '0', '0', '3', '1');
INSERT INTO `0_gl_trans` VALUES ('4', '22', '1', '2010-02-22', '2100', '', '44100', '0', '0', '3', '1');
INSERT INTO `0_gl_trans` VALUES ('5', '22', '1', '2010-02-22', '1065', '', '-44100', '0', '0', '3', '1');
INSERT INTO `0_gl_trans` VALUES ('6', '2', '1', '2010-02-22', '3350', 'Fund Petty Cash Account', '-50000', '0', '0', '0', 'Funding Account');
INSERT INTO `0_gl_trans` VALUES ('7', '2', '1', '2010-02-22', '1065', '', '50000', '0', '0', '0', 'Funding Account');
INSERT INTO `0_gl_trans` VALUES ('8', '12', '1', '2010-02-22', '1065', '', '630', '0', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('9', '12', '1', '2010-02-22', '1200', '', '-630', '0', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('10', '13', '1', '2010-02-22', '5010', '', '588', '1', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('11', '13', '1', '2010-02-22', '1510', '', '-588', '0', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('12', '10', '1', '2010-02-22', '4010', '', '-600', '1', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('13', '10', '1', '2010-02-22', '1200', '', '630', '0', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('14', '10', '1', '2010-02-22', '2150', '', '-30', '0', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('15', '12', '2', '2010-02-22', '1065', '', '450', '0', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('16', '12', '2', '2010-02-22', '1200', '', '-450', '0', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('17', '13', '2', '2010-02-22', '5010', '', '0', '1', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('18', '13', '2', '2010-02-22', '1510', '', '0', '0', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('19', '13', '2', '2010-02-22', '5010', '', '0', '1', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('20', '13', '2', '2010-02-22', '1510', '', '0', '0', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('21', '13', '3', '2010-02-22', '5010', '', '168', '1', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('22', '13', '3', '2010-02-22', '1510', '', '-168', '0', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('23', '13', '2', '2010-02-22', '5010', '', '84', '1', '0', '2', 'L001');
INSERT INTO `0_gl_trans` VALUES ('24', '13', '2', '2010-02-22', '1510', '', '-84', '0', '0', '2', 'L001');


### Structure of table `0_grn_batch` ###

DROP TABLE IF EXISTS `0_grn_batch`;

CREATE TABLE `0_grn_batch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `purch_order_no` int(11) DEFAULT NULL,
  `reference` varchar(60) NOT NULL DEFAULT '',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `loc_code` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_grn_batch` ###

INSERT INTO `0_grn_batch` VALUES ('1', '1', '1', '1', '2010-02-22', 'ARR');


### Structure of table `0_grn_items` ###

DROP TABLE IF EXISTS `0_grn_items`;

CREATE TABLE `0_grn_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grn_batch_id` int(11) DEFAULT NULL,
  `po_detail_item` int(11) NOT NULL DEFAULT '0',
  `item_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `qty_recd` double NOT NULL DEFAULT '0',
  `quantity_inv` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_grn_items` ###

INSERT INTO `0_grn_items` VALUES ('1', '1', '1', 'N100A', 'N100A', '500', '500');


### Structure of table `0_groups` ###

DROP TABLE IF EXISTS `0_groups`;

CREATE TABLE `0_groups` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;


### Data of table `0_groups` ###

INSERT INTO `0_groups` VALUES ('1', 'Small', '0');
INSERT INTO `0_groups` VALUES ('2', 'Medium', '0');
INSERT INTO `0_groups` VALUES ('3', 'Large', '0');


### Structure of table `0_item_codes` ###

DROP TABLE IF EXISTS `0_item_codes`;

CREATE TABLE `0_item_codes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_code` varchar(20) NOT NULL,
  `stock_id` varchar(20) NOT NULL,
  `description` varchar(200) NOT NULL DEFAULT '',
  `category_id` smallint(6) unsigned NOT NULL,
  `quantity` double NOT NULL DEFAULT '1',
  `is_foreign` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_id` (`stock_id`,`item_code`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_item_codes` ###

INSERT INTO `0_item_codes` VALUES ('1', 'N100A', 'N100A', 'N100 Airtime Card', '1', '1', '0', '0');


### Structure of table `0_item_tax_type_exemptions` ###

DROP TABLE IF EXISTS `0_item_tax_type_exemptions`;

CREATE TABLE `0_item_tax_type_exemptions` (
  `item_tax_type_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_tax_type_id`,`tax_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_item_tax_type_exemptions` ###



### Structure of table `0_item_tax_types` ###

DROP TABLE IF EXISTS `0_item_tax_types`;

CREATE TABLE `0_item_tax_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `exempt` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_item_tax_types` ###

INSERT INTO `0_item_tax_types` VALUES ('1', 'VAT Allowed', '0', '0');


### Structure of table `0_item_units` ###

DROP TABLE IF EXISTS `0_item_units`;

CREATE TABLE `0_item_units` (
  `abbr` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `decimals` tinyint(2) NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`abbr`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;


### Data of table `0_item_units` ###

INSERT INTO `0_item_units` VALUES ('ea.', 'Each', '0', '0');
INSERT INTO `0_item_units` VALUES ('hrs', 'Hours', '1', '0');


### Structure of table `0_loc_stock` ###

DROP TABLE IF EXISTS `0_loc_stock`;

CREATE TABLE `0_loc_stock` (
  `loc_code` char(5) NOT NULL DEFAULT '',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `reorder_level` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loc_code`,`stock_id`),
  KEY `stock_id` (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_loc_stock` ###

INSERT INTO `0_loc_stock` VALUES ('ARR', 'N100A', '0');
INSERT INTO `0_loc_stock` VALUES ('DEF', '102', '0');
INSERT INTO `0_loc_stock` VALUES ('DEF', '103', '0');
INSERT INTO `0_loc_stock` VALUES ('DEF', '104', '0');
INSERT INTO `0_loc_stock` VALUES ('DEF', '201', '0');
INSERT INTO `0_loc_stock` VALUES ('DEF', '3400', '0');
INSERT INTO `0_loc_stock` VALUES ('DEF', 'N100A', '0');
INSERT INTO `0_loc_stock` VALUES ('IBW', 'N100A', '0');
INSERT INTO `0_loc_stock` VALUES ('QC', 'N100A', '0');
INSERT INTO `0_loc_stock` VALUES ('WH', 'N100A', '0');


### Structure of table `0_locations` ###

DROP TABLE IF EXISTS `0_locations`;

CREATE TABLE `0_locations` (
  `loc_code` varchar(5) NOT NULL DEFAULT '',
  `location_name` varchar(60) NOT NULL DEFAULT '',
  `delivery_address` tinytext NOT NULL,
  `phone` varchar(30) NOT NULL DEFAULT '',
  `phone2` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loc_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;


### Data of table `0_locations` ###

INSERT INTO `0_locations` VALUES ('ARR', 'Arrival Location', '', '', '', '', '', 'Purchasing Officer 1', '0');
INSERT INTO `0_locations` VALUES ('QC', 'Quality Location', '', '', '', '', '', '', '0');
INSERT INTO `0_locations` VALUES ('WH', 'Central Warehouse', '', '', '', '', '', '', '0');
INSERT INTO `0_locations` VALUES ('IBW', 'Ibadan Warehouse', '', '', '', '', '', '', '0');


### Structure of table `0_movement_types` ###

DROP TABLE IF EXISTS `0_movement_types`;

CREATE TABLE `0_movement_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


### Data of table `0_movement_types` ###

INSERT INTO `0_movement_types` VALUES ('1', 'Adjustment', '0');
INSERT INTO `0_movement_types` VALUES ('2', 'Location Change', '0');


### Structure of table `0_ourrefs` ###

DROP TABLE IF EXISTS `0_ourrefs`;

CREATE TABLE `0_ourrefs` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_ourrefs` ###



### Structure of table `0_oursys_types` ###

DROP TABLE IF EXISTS `0_oursys_types`;

CREATE TABLE `0_oursys_types` (
  `type_id` smallint(6) NOT NULL DEFAULT '0',
  `type_no` int(11) NOT NULL DEFAULT '1',
  `next_reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_oursys_types` ###



### Structure of table `0_pay_advice` ###

DROP TABLE IF EXISTS `0_pay_advice`;

CREATE TABLE `0_pay_advice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) DEFAULT '12',
  `debtor_no` varchar(64) NOT NULL,
  `branch_id` varchar(45) NOT NULL DEFAULT '0',
  `order_no` int(11) NOT NULL DEFAULT '0',
  `bank_act` varchar(64) DEFAULT NULL,
  `bank_branch` varchar(45) NOT NULL DEFAULT '0',
  `ref` varchar(40) DEFAULT NULL,
  `trans_date` date NOT NULL DEFAULT '0000-00-00',
  `amount` double DEFAULT NULL,
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `person_type_id` int(11) NOT NULL DEFAULT '0',
  `person_id` varchar(64) DEFAULT NULL,
  `reconciled` date DEFAULT NULL,
  `created_by` varchar(64) DEFAULT NULL,
  `created_date` date NOT NULL DEFAULT '0000-00-00',
  `note` varchar(128) DEFAULT NULL,
  `confirmed_by` varchar(64) DEFAULT NULL,
  `confirmed_date` date NOT NULL DEFAULT '0000-00-00',
  `request_status` varchar(20) NOT NULL DEFAULT 'Planned',
  `version` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bank_act` (`bank_act`,`ref`),
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


### Data of table `0_pay_advice` ###

INSERT INTO `0_pay_advice` VALUES ('1', '12', 'L001', '2', '1', '2', 'Office', 'Office', '2010-02-22', '630', '0', '0', '0', NULL, NULL, 'admin', '2010-02-22', 'Direct Sales Payment', 'admin', '2010-02-22', 'ConfirmedPosted', '0');
INSERT INTO `0_pay_advice` VALUES ('2', '12', 'L001', '2', '2', '2', 'Office', 'Office', '2010-02-22', '450', '0', '0', '0', NULL, NULL, 'admin', '2010-02-22', 'tESTING ', 'admin', '2010-02-22', 'ConfirmedPosted', '0');


### Structure of table `0_payment_terms` ###

DROP TABLE IF EXISTS `0_payment_terms`;

CREATE TABLE `0_payment_terms` (
  `terms_indicator` int(11) NOT NULL AUTO_INCREMENT,
  `terms` char(80) NOT NULL DEFAULT '',
  `days_before_due` smallint(6) NOT NULL DEFAULT '0',
  `day_in_following_month` smallint(6) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`terms_indicator`),
  UNIQUE KEY `terms` (`terms`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;


### Data of table `0_payment_terms` ###

INSERT INTO `0_payment_terms` VALUES ('1', 'Due Immediately After Invoice', '0', '0', '0');
INSERT INTO `0_payment_terms` VALUES ('2', 'Due in 14 days', '14', '0', '0');
INSERT INTO `0_payment_terms` VALUES ('3', 'Due in Next Month', '0', '1', '0');


### Structure of table `0_prices` ###

DROP TABLE IF EXISTS `0_prices`;

CREATE TABLE `0_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `sales_type_id` int(11) NOT NULL DEFAULT '0',
  `curr_abrev` char(3) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `price` (`stock_id`,`sales_type_id`,`curr_abrev`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


### Data of table `0_prices` ###

INSERT INTO `0_prices` VALUES ('1', 'N100A', '1', 'NGN', '90');
INSERT INTO `0_prices` VALUES ('2', 'N100A', '2', 'NGN', '100');


### Structure of table `0_print_profiles` ###

DROP TABLE IF EXISTS `0_print_profiles`;

CREATE TABLE `0_print_profiles` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `profile` varchar(30) NOT NULL,
  `report` varchar(5) DEFAULT NULL,
  `printer` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile` (`profile`,`report`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_print_profiles` ###



### Structure of table `0_printers` ###

DROP TABLE IF EXISTS `0_printers`;

CREATE TABLE `0_printers` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(60) NOT NULL,
  `queue` varchar(20) NOT NULL,
  `host` varchar(40) NOT NULL,
  `port` smallint(11) unsigned NOT NULL,
  `timeout` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_printers` ###



### Structure of table `0_purch_data` ###

DROP TABLE IF EXISTS `0_purch_data`;

CREATE TABLE `0_purch_data` (
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  `suppliers_uom` char(50) NOT NULL DEFAULT '',
  `conversion_factor` double NOT NULL DEFAULT '1',
  `supplier_description` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`supplier_id`,`stock_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;


### Data of table `0_purch_data` ###

INSERT INTO `0_purch_data` VALUES ('1', 'N100A', '42000', 'Box', '500', 'N100A');


### Structure of table `0_purch_order_details` ###

DROP TABLE IF EXISTS `0_purch_order_details`;

CREATE TABLE `0_purch_order_details` (
  `po_detail_item` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL DEFAULT '0',
  `item_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `qty_invoiced` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `act_price` double NOT NULL DEFAULT '0',
  `std_cost_unit` double NOT NULL DEFAULT '0',
  `quantity_ordered` double NOT NULL DEFAULT '0',
  `quantity_received` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`po_detail_item`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_purch_order_details` ###

INSERT INTO `0_purch_order_details` VALUES ('1', '1', 'N100A', 'N100 Airtime Card', '2010-03-04', '500', '84', '84', '84', '500', '500');


### Structure of table `0_purch_orders` ###

DROP TABLE IF EXISTS `0_purch_orders`;

CREATE TABLE `0_purch_orders` (
  `order_no` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `comments` tinytext,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` tinytext NOT NULL,
  `requisition_no` tinytext,
  `into_stock_location` varchar(5) NOT NULL DEFAULT '',
  `delivery_address` tinytext NOT NULL,
  PRIMARY KEY (`order_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_purch_orders` ###

INSERT INTO `0_purch_orders` VALUES ('1', '1', 'First Order on the system', '2010-02-22', '1', NULL, 'ARR', 'Lagos');


### Structure of table `0_purch_req_details` ###

DROP TABLE IF EXISTS `0_purch_req_details`;

CREATE TABLE `0_purch_req_details` (
  `pr_detail_item` int(11) NOT NULL AUTO_INCREMENT,
  `pr_no` int(11) NOT NULL DEFAULT '0',
  `item_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `qty_invoiced` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `act_price` double NOT NULL DEFAULT '0',
  `std_cost_unit` double NOT NULL DEFAULT '0',
  `quantity_ordered` double NOT NULL DEFAULT '0',
  `quantity_received` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`pr_detail_item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_purch_req_details` ###



### Structure of table `0_purch_reqs` ###

DROP TABLE IF EXISTS `0_purch_reqs`;

CREATE TABLE `0_purch_reqs` (
  `pr_no` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `comments` tinytext,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` tinytext NOT NULL,
  `requisition_no` tinytext,
  `into_stock_location` varchar(5) NOT NULL DEFAULT '',
  `delivery_address` tinytext NOT NULL,
  PRIMARY KEY (`pr_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_purch_reqs` ###



### Structure of table `0_quick_entries` ###

DROP TABLE IF EXISTS `0_quick_entries`;

CREATE TABLE `0_quick_entries` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(60) NOT NULL,
  `base_amount` double NOT NULL DEFAULT '0',
  `base_desc` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_quick_entries` ###



### Structure of table `0_quick_entry_lines` ###

DROP TABLE IF EXISTS `0_quick_entry_lines`;

CREATE TABLE `0_quick_entry_lines` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `qid` smallint(6) unsigned NOT NULL,
  `amount` double DEFAULT '0',
  `action` varchar(2) NOT NULL,
  `dest_id` varchar(11) NOT NULL,
  `dimension_id` smallint(6) unsigned DEFAULT NULL,
  `dimension2_id` smallint(6) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `qid` (`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_quick_entry_lines` ###



### Structure of table `0_recurrent_invoices` ###

DROP TABLE IF EXISTS `0_recurrent_invoices`;

CREATE TABLE `0_recurrent_invoices` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `order_no` int(11) unsigned NOT NULL,
  `debtor_no` int(11) unsigned DEFAULT NULL,
  `group_no` smallint(6) unsigned DEFAULT NULL,
  `days` int(11) NOT NULL DEFAULT '0',
  `monthly` int(11) NOT NULL DEFAULT '0',
  `begin` date NOT NULL DEFAULT '0000-00-00',
  `end` date NOT NULL DEFAULT '0000-00-00',
  `last_sent` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_recurrent_invoices` ###



### Structure of table `0_refs` ###

DROP TABLE IF EXISTS `0_refs`;

CREATE TABLE `0_refs` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_refs` ###

INSERT INTO `0_refs` VALUES ('1', '2', '1');
INSERT INTO `0_refs` VALUES ('1', '10', '1');
INSERT INTO `0_refs` VALUES ('1', '12', '1');
INSERT INTO `0_refs` VALUES ('1', '13', '1');
INSERT INTO `0_refs` VALUES ('1', '16', '1');
INSERT INTO `0_refs` VALUES ('1', '18', '1');
INSERT INTO `0_refs` VALUES ('1', '20', '1');
INSERT INTO `0_refs` VALUES ('1', '22', '1');
INSERT INTO `0_refs` VALUES ('1', '25', '1');
INSERT INTO `0_refs` VALUES ('1', '30', '1');
INSERT INTO `0_refs` VALUES ('1', '32', '1');
INSERT INTO `0_refs` VALUES ('1', '40', '1');
INSERT INTO `0_refs` VALUES ('2', '12', '2');
INSERT INTO `0_refs` VALUES ('2', '13', '2');
INSERT INTO `0_refs` VALUES ('2', '30', '2');
INSERT INTO `0_refs` VALUES ('2', '32', '2');
INSERT INTO `0_refs` VALUES ('3', '13', '3');
INSERT INTO `0_refs` VALUES ('3', '30', '3');
INSERT INTO `0_refs` VALUES ('3', '32', '3');


### Structure of table `0_sales_order_details` ###

DROP TABLE IF EXISTS `0_sales_order_details`;

CREATE TABLE `0_sales_order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL DEFAULT '0',
  `trans_type` smallint(6) NOT NULL DEFAULT '30',
  `stk_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `qty_sent` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `discount_percent` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;


### Data of table `0_sales_order_details` ###

INSERT INTO `0_sales_order_details` VALUES ('1', '1', '32', 'N100A', 'N100 Airtime Card', '0', '90', '7', '0');
INSERT INTO `0_sales_order_details` VALUES ('2', '1', '30', 'N100A', 'N100 Airtime Card', '7', '90', '7', '0');
INSERT INTO `0_sales_order_details` VALUES ('3', '2', '32', 'N100A', 'N100 Airtime Card', '0', '90', '5', '0');
INSERT INTO `0_sales_order_details` VALUES ('5', '2', '30', 'N100A', 'N100 Airtime Card', '3', '90', '5', '0');
INSERT INTO `0_sales_order_details` VALUES ('6', '3', '32', 'N100A', 'N100 Airtime Card', '0', '90', '1', '0');
INSERT INTO `0_sales_order_details` VALUES ('7', '3', '30', 'N100A', 'N100 Airtime Card', '0', '90', '1', '0');


### Structure of table `0_sales_orders` ###

DROP TABLE IF EXISTS `0_sales_orders`;

CREATE TABLE `0_sales_orders` (
  `order_no` int(11) NOT NULL,
  `trans_type` smallint(6) NOT NULL DEFAULT '30',
  `version` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `debtor_no` varchar(32) DEFAULT NULL,
  `branch_code` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  `customer_ref` tinytext NOT NULL,
  `comments` tinytext,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `order_type` int(11) NOT NULL DEFAULT '0',
  `ship_via` int(11) NOT NULL DEFAULT '0',
  `delivery_address` tinytext NOT NULL,
  `contact_phone` varchar(30) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `deliver_to` tinytext NOT NULL,
  `freight_cost` double NOT NULL DEFAULT '0',
  `from_stk_loc` varchar(5) NOT NULL DEFAULT '',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `ourorder_status` varchar(45) NOT NULL DEFAULT 'Planned',
  PRIMARY KEY (`trans_type`,`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_sales_orders` ###

INSERT INTO `0_sales_orders` VALUES ('1', '30', '1', '0', 'L001', '2', '1', '/SQ1', 'Sales Quotation # 1', '2010-02-22', '1', '1', 'Lagos', NULL, NULL, 'Lagos Customer Ben', '0', 'WH', '2010-02-22', 'Confirmed');
INSERT INTO `0_sales_orders` VALUES ('2', '30', '5', '0', 'L001', '2', '2', '/SQ2', 'Sales Quotation # 2', '2010-02-22', '1', '1', 'Lagos', NULL, NULL, 'Lagos Customer Ben', '0', 'WH', '2010-02-22', 'Confirmed');
INSERT INTO `0_sales_orders` VALUES ('3', '30', '0', '0', 'L001', '2', '3', '/SQ3', 'Sales Quotation # 3', '2010-02-23', '1', '1', 'Lagos', NULL, NULL, 'Lagos Customer Ben', '0', 'WH', '2010-02-23', 'Planned');
INSERT INTO `0_sales_orders` VALUES ('1', '32', '0', '0', 'L001', '2', '1', '', NULL, '2010-02-22', '1', '1', 'Lagos', NULL, NULL, 'Lagos Customer Ben', '0', 'WH', '2010-02-23', 'OrderedClosed');
INSERT INTO `0_sales_orders` VALUES ('2', '32', '0', '0', 'L001', '2', '2', '', NULL, '2010-02-22', '1', '1', 'Lagos', NULL, NULL, 'Lagos Customer Ben', '0', 'WH', '2010-02-23', 'OrderedClosed');
INSERT INTO `0_sales_orders` VALUES ('3', '32', '0', '0', 'L001', '2', '3', '', NULL, '2010-02-23', '1', '1', 'Lagos', NULL, NULL, 'Lagos Customer Ben', '0', 'WH', '2010-02-24', 'OrderedClosed');


### Structure of table `0_sales_pos` ###

DROP TABLE IF EXISTS `0_sales_pos`;

CREATE TABLE `0_sales_pos` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pos_name` varchar(30) NOT NULL,
  `cash_sale` tinyint(1) NOT NULL,
  `credit_sale` tinyint(1) NOT NULL,
  `pos_location` varchar(5) NOT NULL,
  `pos_account` smallint(6) unsigned NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_name` (`pos_name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


### Data of table `0_sales_pos` ###

INSERT INTO `0_sales_pos` VALUES ('1', 'Default', '1', '1', 'DEF', '4', '0');
INSERT INTO `0_sales_pos` VALUES ('2', 'POS 1', '1', '0', 'DEF', '4', '0');


### Structure of table `0_sales_types` ###

DROP TABLE IF EXISTS `0_sales_types`;

CREATE TABLE `0_sales_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_type` char(50) NOT NULL DEFAULT '',
  `tax_included` int(1) NOT NULL DEFAULT '0',
  `factor` double NOT NULL DEFAULT '1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_type` (`sales_type`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


### Data of table `0_sales_types` ###

INSERT INTO `0_sales_types` VALUES ('1', 'Wholesale', '1', '1', '0');
INSERT INTO `0_sales_types` VALUES ('2', 'Retailsale', '1', '1.1111', '0');


### Structure of table `0_salesman` ###

DROP TABLE IF EXISTS `0_salesman`;

CREATE TABLE `0_salesman` (
  `salesman_code` int(11) NOT NULL AUTO_INCREMENT,
  `salesman_name` char(60) NOT NULL DEFAULT '',
  `salesman_phone` char(30) NOT NULL DEFAULT '',
  `salesman_fax` char(30) NOT NULL DEFAULT '',
  `salesman_email` varchar(100) NOT NULL DEFAULT '',
  `provision` double NOT NULL DEFAULT '0',
  `break_pt` double NOT NULL DEFAULT '0',
  `provision2` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`salesman_code`),
  UNIQUE KEY `salesman_name` (`salesman_name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


### Data of table `0_salesman` ###

INSERT INTO `0_salesman` VALUES ('1', 'Muyiwa Ola', '', '', '', '0', '0', '0', '0');
INSERT INTO `0_salesman` VALUES ('2', 'Kola Kolapo', '', '', '', '0', '0', '0', '0');


### Structure of table `0_security_roles` ###

DROP TABLE IF EXISTS `0_security_roles`;

CREATE TABLE `0_security_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(30) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `sections` text,
  `areas` text,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role` (`role`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;


### Data of table `0_security_roles` ###

INSERT INTO `0_security_roles` VALUES ('1', 'Inquiries', 'Inquiries', '768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15872;16128', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;524;525;773;774;2822;3073;3075;3076;3077;3329;3330;3331;3332;3333;3334;3335;5377;5633;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8450;8451;10497;10753;11009;11010;11012;13313;13315;15617;15618;15619;15620;15621;15622;15623;15624;15625;15626;15873;15882;16129;16130;16131;16132', '0');
INSERT INTO `0_security_roles` VALUES ('2', 'System Administrator', 'System Administrator', '256;512;768;2816;3072;3328;5376;5632;5888;7936;8192;8448;10496;10752;11008;13056;13312;15616;15872;16128', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;524;525;769;770;771;772;773;774;2817;2818;2819;2820;2821;2822;2823;3073;3074;3082;3085;3075;3083;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5642;5635;5636;5637;5641;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8195;8196;8197;8449;8450;8451;10497;10753;10754;10755;10756;10757;11009;11010;11012;13057;13313;13314;13315;15617;15618;15619;15620;15621;15622;15623;15624;15625;15626;15627;15873;15874;15875;15876;15877;15878;15879;15880;15883;15881;15882;16129;16130;16131;16132', '0');
INSERT INTO `0_security_roles` VALUES ('3', 'Salesman', 'Salesman', '768;3072;5632;8192;15872', '773;774;3073;3075;3081;5633;8194;15873', '0');
INSERT INTO `0_security_roles` VALUES ('4', 'Stock Manager', 'Stock Manager', '2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15872;16128', '2818;2822;3073;3076;3077;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5640;5889;5890;5891;8193;8194;8450;8451;10753;11009;11010;11012;13313;13315;15882;16129;16130;16131;16132', '0');
INSERT INTO `0_security_roles` VALUES ('5', 'Production Manager', 'Production Manager', '512;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5640;5640;5889;5890;5891;8193;8194;8196;8197;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15876;15877;15880;15882;16129;16130;16131;16132', '0');
INSERT INTO `0_security_roles` VALUES ('6', 'Purchase Officer', 'Purchase Officer', '512;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5377;5633;5635;5640;5640;5889;5890;5891;8193;8194;8196;8197;8449;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15876;15877;15880;15882;16129;16130;16131;16132', '0');
INSERT INTO `0_security_roles` VALUES ('7', 'AR Officer', 'AR Officer', '512;768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;771;773;774;2818;2819;2820;2821;2822;2823;3073;3073;3074;3075;3076;3077;3078;3079;3080;3081;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5633;5634;5637;5638;5639;5640;5640;5889;5890;5891;8193;8194;8194;8196;8197;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15873;15876;15877;15878;15880;15882;16129;16130;16131;16132', '0');
INSERT INTO `0_security_roles` VALUES ('8', 'AP Officer', 'AP Officer', '512;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;769;770;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3082;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5635;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15876;15877;15880;15882;16129;16130;16131;16132', '0');
INSERT INTO `0_security_roles` VALUES ('9', 'Accountant', 'New Accountant', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13313;13315;15617;15618;15619;15620;15621;15624;15873;15876;15877;15878;15880;15882;16129;16130;16131;16132', '0');
INSERT INTO `0_security_roles` VALUES ('10', 'Sub Admin', 'Sub Admin', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3082;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15873;15874;15876;15877;15878;15879;15880;15882;16129;16130;16131;16132', '0');
INSERT INTO `0_security_roles` VALUES ('11', 'Demo', 'New Accountant', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;515;516;519;520;521;522;523;524;771;772;773;774;2817;2818;2819;2820;2821;2822;2823;3073;3074;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15618;15619;15620;15621;15624;15873;15874;15875;15876;15877;15878;15880;15883;15881;15882;16129;16130;16131;16132', '0');
INSERT INTO `0_security_roles` VALUES ('12', 'POSAgent', 'POS Agent', '5632', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;524;525;769;770;771;772;773;774;2817;2818;2819;2820;2821;2822;2823;3073;3074;3082;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5641;5635;5889;5890;5891;7937;7938;7939;7940;8193;8194;8195;8196;8197;8449;8450;8451;10497;10753;10754;10755;10756;10757;11009;11010;11012;13057;13313;13314;13315;15617;15618;15619;15620;15621;15622;15623;15624;15625;15626;15627;15873;15874;15875;15876;15877;15878;15879;15880;15883;15881;15882;16129;16130;16131;16132', '0');


### Structure of table `0_shippers` ###

DROP TABLE IF EXISTS `0_shippers`;

CREATE TABLE `0_shippers` (
  `shipper_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipper_name` varchar(60) NOT NULL DEFAULT '',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `phone2` varchar(30) NOT NULL DEFAULT '',
  `contact` tinytext NOT NULL,
  `address` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shipper_id`),
  UNIQUE KEY `name` (`shipper_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_shippers` ###

INSERT INTO `0_shippers` VALUES ('1', 'Self', '', '', 'Self', '', '0');


### Structure of table `0_sql_trail` ###

DROP TABLE IF EXISTS `0_sql_trail`;

CREATE TABLE `0_sql_trail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sql` text NOT NULL,
  `result` tinyint(1) NOT NULL,
  `msg` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_sql_trail` ###



### Structure of table `0_stock_category` ###

DROP TABLE IF EXISTS `0_stock_category`;

CREATE TABLE `0_stock_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `dflt_tax_type` int(11) NOT NULL DEFAULT '1',
  `dflt_units` varchar(20) NOT NULL DEFAULT 'each',
  `dflt_mb_flag` char(1) NOT NULL DEFAULT 'B',
  `dflt_sales_act` varchar(11) NOT NULL DEFAULT '',
  `dflt_cogs_act` varchar(11) NOT NULL DEFAULT '',
  `dflt_inventory_act` varchar(11) NOT NULL DEFAULT '',
  `dflt_adjustment_act` varchar(11) NOT NULL DEFAULT '',
  `dflt_assembly_act` varchar(11) NOT NULL DEFAULT '',
  `dflt_dim1` int(11) DEFAULT NULL,
  `dflt_dim2` int(11) DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `dflt_no_sale` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_stock_category` ###

INSERT INTO `0_stock_category` VALUES ('1', 'Airtime Cards', '1', 'ea.', 'B', '4010', '5010', '1510', '5040', '1530', '1', '0', '0', '0');


### Structure of table `0_stock_master` ###

DROP TABLE IF EXISTS `0_stock_master`;

CREATE TABLE `0_stock_master` (
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(200) NOT NULL DEFAULT '',
  `long_description` tinytext NOT NULL,
  `units` varchar(20) NOT NULL DEFAULT 'each',
  `mb_flag` char(1) NOT NULL DEFAULT 'B',
  `sales_account` varchar(11) NOT NULL DEFAULT '',
  `cogs_account` varchar(11) NOT NULL DEFAULT '',
  `inventory_account` varchar(11) NOT NULL DEFAULT '',
  `adjustment_account` varchar(11) NOT NULL DEFAULT '',
  `assembly_account` varchar(11) NOT NULL DEFAULT '',
  `dimension_id` int(11) DEFAULT NULL,
  `dimension2_id` int(11) DEFAULT NULL,
  `actual_cost` double NOT NULL DEFAULT '0',
  `last_cost` double NOT NULL DEFAULT '0',
  `material_cost` double NOT NULL DEFAULT '0',
  `labour_cost` double NOT NULL DEFAULT '0',
  `overhead_cost` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `no_sale` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_stock_master` ###

INSERT INTO `0_stock_master` VALUES ('N100A', '1', '1', 'N100 Airtime Card', 'N100 Airtime Card', 'ea.', 'B', '4010', '5010', '1510', '5040', '1530', '1', '0', '0', '0', '84', '0', '0', '0', '0');


### Structure of table `0_stock_moves` ###

DROP TABLE IF EXISTS `0_stock_moves`;

CREATE TABLE `0_stock_moves` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_no` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `loc_code` char(5) NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `person_id` int(11) DEFAULT NULL,
  `price` double NOT NULL DEFAULT '0',
  `reference` char(40) NOT NULL DEFAULT '',
  `qty` double NOT NULL DEFAULT '1',
  `discount_percent` double NOT NULL DEFAULT '0',
  `standard_cost` double NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`trans_id`),
  KEY `type` (`type`,`trans_no`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;


### Data of table `0_stock_moves` ###

INSERT INTO `0_stock_moves` VALUES ('1', '1', 'N100A', '25', 'ARR', '2010-02-22', '1', '84', '', '500', '0', '84', '1');
INSERT INTO `0_stock_moves` VALUES ('2', '1', 'N100A', '16', 'ARR', '2010-02-22', '2', '0', '1', '-100', '0', '0', '1');
INSERT INTO `0_stock_moves` VALUES ('3', '1', 'N100A', '16', 'WH', '2010-02-22', '2', '0', '1', '100', '0', '0', '1');
INSERT INTO `0_stock_moves` VALUES ('4', '1', 'N100A', '13', 'WH', '2010-02-22', '0', '90', '1', '-7', '0', '84', '1');
INSERT INTO `0_stock_moves` VALUES ('5', '2', 'N100A', '13', 'WH', '2010-02-22', '0', '0', '2', '0', '0', '0', '1');
INSERT INTO `0_stock_moves` VALUES ('6', '2', 'N100A', '13', 'WH', '2010-02-22', '0', '0', '2', '0', '0', '0', '1');
INSERT INTO `0_stock_moves` VALUES ('7', '3', 'N100A', '13', 'WH', '2010-02-22', '0', '90', '3', '-2', '0', '84', '1');
INSERT INTO `0_stock_moves` VALUES ('8', '2', 'N100A', '13', 'WH', '2010-02-22', '0', '90', '2', '-1', '0', '84', '1');


### Structure of table `0_supp_allocations` ###

DROP TABLE IF EXISTS `0_supp_allocations`;

CREATE TABLE `0_supp_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amt` double unsigned DEFAULT NULL,
  `date_alloc` date NOT NULL DEFAULT '0000-00-00',
  `trans_no_from` int(11) DEFAULT NULL,
  `trans_type_from` int(11) DEFAULT NULL,
  `trans_no_to` int(11) DEFAULT NULL,
  `trans_type_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_supp_allocations` ###

INSERT INTO `0_supp_allocations` VALUES ('1', '44100', '2010-02-22', '1', '22', '1', '20');


### Structure of table `0_supp_invoice_items` ###

DROP TABLE IF EXISTS `0_supp_invoice_items`;

CREATE TABLE `0_supp_invoice_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supp_trans_no` int(11) DEFAULT NULL,
  `supp_trans_type` int(11) DEFAULT NULL,
  `gl_code` varchar(11) NOT NULL DEFAULT '0',
  `grn_item_id` int(11) DEFAULT NULL,
  `po_detail_item_id` int(11) DEFAULT NULL,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `quantity` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `unit_tax` double NOT NULL DEFAULT '0',
  `memo_` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_supp_invoice_items` ###

INSERT INTO `0_supp_invoice_items` VALUES ('1', '1', '20', '0', '1', '1', 'N100A', 'N100A', '500', '84', '4.2', NULL);


### Structure of table `0_supp_trans` ###

DROP TABLE IF EXISTS `0_supp_trans`;

CREATE TABLE `0_supp_trans` (
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `supplier_id` int(11) unsigned DEFAULT NULL,
  `reference` tinytext NOT NULL,
  `supp_reference` varchar(60) NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `ov_amount` double NOT NULL DEFAULT '0',
  `ov_discount` double NOT NULL DEFAULT '0',
  `ov_gst` double NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '1',
  `alloc` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_no`,`type`),
  KEY `supplier_id` (`supplier_id`),
  KEY `SupplierID_2` (`supplier_id`,`supp_reference`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_supp_trans` ###

INSERT INTO `0_supp_trans` VALUES ('1', '20', '1', '1', '3456', '2010-02-22', '2010-02-28', '42000', '0', '2100', '1', '44100');
INSERT INTO `0_supp_trans` VALUES ('1', '22', '1', '1', '', '2010-02-22', '2010-02-22', '-44100', '0', '0', '1', '44100');


### Structure of table `0_suppliers` ###

DROP TABLE IF EXISTS `0_suppliers`;

CREATE TABLE `0_suppliers` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `supp_name` varchar(60) NOT NULL DEFAULT '',
  `supp_ref` varchar(30) NOT NULL DEFAULT '',
  `address` tinytext NOT NULL,
  `supp_address` tinytext NOT NULL,
  `phone` varchar(30) NOT NULL DEFAULT '',
  `phone2` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `gst_no` varchar(25) NOT NULL DEFAULT '',
  `contact` varchar(60) NOT NULL DEFAULT '',
  `supp_account_no` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `website` varchar(100) NOT NULL DEFAULT '',
  `bank_account` varchar(60) NOT NULL DEFAULT '',
  `curr_code` char(3) DEFAULT NULL,
  `payment_terms` int(11) DEFAULT NULL,
  `dimension_id` int(11) DEFAULT '0',
  `dimension2_id` int(11) DEFAULT '0',
  `tax_group_id` int(11) DEFAULT NULL,
  `credit_limit` double NOT NULL DEFAULT '0',
  `purchase_account` varchar(11) DEFAULT NULL,
  `payable_account` varchar(11) DEFAULT NULL,
  `payment_discount_account` varchar(11) DEFAULT NULL,
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`supplier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_suppliers` ###

INSERT INTO `0_suppliers` VALUES ('1', 'One Phone Teleco', 'OnePhone', '5, Taylor Street,', '', '234', '', '', '', 'OnePhone', '', '', '', '', 'NGN', '1', '1', '0', '1', '0', '5010', '2100', '5060', 'Test', '0');


### Structure of table `0_sys_types` ###

DROP TABLE IF EXISTS `0_sys_types`;

CREATE TABLE `0_sys_types` (
  `type_id` smallint(6) NOT NULL DEFAULT '0',
  `type_no` int(11) NOT NULL DEFAULT '1',
  `next_reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_sys_types` ###

INSERT INTO `0_sys_types` VALUES ('0', '19', '1');
INSERT INTO `0_sys_types` VALUES ('1', '8', '1');
INSERT INTO `0_sys_types` VALUES ('2', '5', '2');
INSERT INTO `0_sys_types` VALUES ('4', '3', '1');
INSERT INTO `0_sys_types` VALUES ('10', '19', '2');
INSERT INTO `0_sys_types` VALUES ('11', '3', '1');
INSERT INTO `0_sys_types` VALUES ('12', '6', '3');
INSERT INTO `0_sys_types` VALUES ('13', '5', '4');
INSERT INTO `0_sys_types` VALUES ('16', '2', '2');
INSERT INTO `0_sys_types` VALUES ('17', '2', '1');
INSERT INTO `0_sys_types` VALUES ('18', '1', '2');
INSERT INTO `0_sys_types` VALUES ('20', '8', '2');
INSERT INTO `0_sys_types` VALUES ('21', '1', '1');
INSERT INTO `0_sys_types` VALUES ('22', '4', '2');
INSERT INTO `0_sys_types` VALUES ('25', '1', '2');
INSERT INTO `0_sys_types` VALUES ('26', '1', '1');
INSERT INTO `0_sys_types` VALUES ('28', '1', '1');
INSERT INTO `0_sys_types` VALUES ('29', '1', '1');
INSERT INTO `0_sys_types` VALUES ('30', '5', '4');
INSERT INTO `0_sys_types` VALUES ('32', '0', '4');
INSERT INTO `0_sys_types` VALUES ('35', '1', '1');
INSERT INTO `0_sys_types` VALUES ('40', '1', '2');
INSERT INTO `0_sys_types` VALUES ('1001', '1001', '1');


### Structure of table `0_tag_associations` ###

DROP TABLE IF EXISTS `0_tag_associations`;

CREATE TABLE `0_tag_associations` (
  `record_id` varchar(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  UNIQUE KEY `record_id` (`record_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;


### Data of table `0_tag_associations` ###



### Structure of table `0_tags` ###

DROP TABLE IF EXISTS `0_tags`;

CREATE TABLE `0_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_tags` ###

INSERT INTO `0_tags` VALUES ('1', '2', '1', 'First Dimension', '0');


### Structure of table `0_tax_group_items` ###

DROP TABLE IF EXISTS `0_tax_group_items`;

CREATE TABLE `0_tax_group_items` (
  `tax_group_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`tax_group_id`,`tax_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_tax_group_items` ###

INSERT INTO `0_tax_group_items` VALUES ('1', '1', '5');


### Structure of table `0_tax_groups` ###

DROP TABLE IF EXISTS `0_tax_groups`;

CREATE TABLE `0_tax_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `tax_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


### Data of table `0_tax_groups` ###

INSERT INTO `0_tax_groups` VALUES ('1', 'Tax', '0', '0');
INSERT INTO `0_tax_groups` VALUES ('2', 'Tax Exempt', '0', '0');


### Structure of table `0_tax_types` ###

DROP TABLE IF EXISTS `0_tax_types`;

CREATE TABLE `0_tax_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate` double NOT NULL DEFAULT '0',
  `sales_gl_code` varchar(11) NOT NULL DEFAULT '',
  `purchasing_gl_code` varchar(11) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


### Data of table `0_tax_types` ###

INSERT INTO `0_tax_types` VALUES ('1', '5', '2150', '2150', 'VAT', '0');


### Structure of table `0_trans_tax_details` ###

DROP TABLE IF EXISTS `0_trans_tax_details`;

CREATE TABLE `0_trans_tax_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_type` smallint(6) DEFAULT NULL,
  `trans_no` int(11) DEFAULT NULL,
  `tran_date` date NOT NULL,
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  `ex_rate` double NOT NULL DEFAULT '1',
  `included_in_price` tinyint(1) NOT NULL DEFAULT '0',
  `net_amount` double NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `memo` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;


### Data of table `0_trans_tax_details` ###

INSERT INTO `0_trans_tax_details` VALUES ('1', '20', '1', '2010-02-22', '1', '5', '1', '0', '42000', '2100', '3456');
INSERT INTO `0_trans_tax_details` VALUES ('2', '13', '1', '2010-02-22', '1', '5', '1', '1', '600', '30', '1');
INSERT INTO `0_trans_tax_details` VALUES ('3', '10', '1', '2010-02-22', '1', '5', '1', '1', '600', '30', '1');
INSERT INTO `0_trans_tax_details` VALUES ('4', '13', '2', '2010-02-22', '1', '5', '1', '1', '0', '0', '2');
INSERT INTO `0_trans_tax_details` VALUES ('5', '13', '2', '2010-02-22', '1', '5', '1', '1', '0', '0', '2');
INSERT INTO `0_trans_tax_details` VALUES ('6', '13', '3', '2010-02-22', '1', '5', '1', '1', '171.42857142857', '8.5714285714286', '3');
INSERT INTO `0_trans_tax_details` VALUES ('7', '13', '2', '2010-02-22', '1', '5', '1', '1', '85.714285714286', '4.2857142857143', '2');


### Structure of table `0_useronline` ###

DROP TABLE IF EXISTS `0_useronline`;

CREATE TABLE `0_useronline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(15) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL DEFAULT '',
  `file` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_useronline` ###



### Structure of table `0_users` ###

DROP TABLE IF EXISTS `0_users`;

CREATE TABLE `0_users` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `real_name` varchar(100) NOT NULL DEFAULT '',
  `role_id` int(11) NOT NULL DEFAULT '1',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) DEFAULT NULL,
  `language` varchar(20) DEFAULT NULL,
  `date_format` tinyint(1) NOT NULL DEFAULT '0',
  `date_sep` tinyint(1) NOT NULL DEFAULT '0',
  `tho_sep` tinyint(1) NOT NULL DEFAULT '0',
  `dec_sep` tinyint(1) NOT NULL DEFAULT '0',
  `theme` varchar(20) NOT NULL DEFAULT 'default',
  `page_size` varchar(20) NOT NULL DEFAULT 'A4',
  `prices_dec` smallint(6) NOT NULL DEFAULT '2',
  `qty_dec` smallint(6) NOT NULL DEFAULT '2',
  `rates_dec` smallint(6) NOT NULL DEFAULT '4',
  `percent_dec` smallint(6) NOT NULL DEFAULT '1',
  `show_gl` tinyint(1) NOT NULL DEFAULT '1',
  `show_codes` tinyint(1) NOT NULL DEFAULT '0',
  `show_hints` tinyint(1) NOT NULL DEFAULT '0',
  `last_visit_date` datetime DEFAULT NULL,
  `query_size` tinyint(1) DEFAULT '10',
  `graphic_links` tinyint(1) DEFAULT '1',
  `pos` smallint(6) DEFAULT '1',
  `print_profile` varchar(30) NOT NULL DEFAULT '1',
  `rep_popup` tinyint(1) DEFAULT '1',
  `sticky_doc_date` tinyint(1) DEFAULT '0',
  `startup_tab` varchar(20) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;


### Data of table `0_users` ###

INSERT INTO `0_users` VALUES ('1', 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'Administrator', '2', '', 'rilwanlateef@yahoo.co.uk', 'en_GB', '1', '0', '0', '0', 'default', 'Letter', '2', '2', '4', '1', '1', '0', '0', '2010-02-23 05:48:44', '10', '1', '1', '', '1', '0', 'orders', '0');
INSERT INTO `0_users` VALUES ('2', 'demouser', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Demo User', '11', '999-999-999', 'demo@demo.nu', 'en_GB', '0', '0', '0', '0', 'default', 'Letter', '2', '2', '3', '1', '1', '0', '0', '2008-02-06 19:02:35', '10', '1', '1', '', '1', '0', 'orders', '0');
INSERT INTO `0_users` VALUES ('3', 'POSAgent1', '50382a6dd992239d450f5d6a828c5b4e', 'POS Agent 1', '12', '', NULL, 'en_GB', '0', '0', '0', '0', 'default', 'Letter', '2', '2', '4', '1', '1', '0', '0', '2010-02-14 00:50:44', '10', '1', '1', '', '1', '0', 'orders', '0');


### Structure of table `0_voided` ###

DROP TABLE IF EXISTS `0_voided`;

CREATE TABLE `0_voided` (
  `type` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `memo_` tinytext NOT NULL,
  UNIQUE KEY `id` (`type`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;


### Data of table `0_voided` ###



### Structure of table `0_wo_issue_items` ###

DROP TABLE IF EXISTS `0_wo_issue_items`;

CREATE TABLE `0_wo_issue_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(40) DEFAULT NULL,
  `issue_id` int(11) DEFAULT NULL,
  `qty_issued` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_wo_issue_items` ###



### Structure of table `0_wo_issues` ###

DROP TABLE IF EXISTS `0_wo_issues`;

CREATE TABLE `0_wo_issues` (
  `issue_no` int(11) NOT NULL AUTO_INCREMENT,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `loc_code` varchar(5) DEFAULT NULL,
  `workcentre_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`issue_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_wo_issues` ###



### Structure of table `0_wo_manufacture` ###

DROP TABLE IF EXISTS `0_wo_manufacture`;

CREATE TABLE `0_wo_manufacture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(100) DEFAULT NULL,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_wo_manufacture` ###



### Structure of table `0_wo_requirements` ###

DROP TABLE IF EXISTS `0_wo_requirements`;

CREATE TABLE `0_wo_requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `workcentre` int(11) NOT NULL DEFAULT '0',
  `units_req` double NOT NULL DEFAULT '1',
  `std_cost` double NOT NULL DEFAULT '0',
  `loc_code` char(5) NOT NULL DEFAULT '',
  `units_issued` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_wo_requirements` ###



### Structure of table `0_workcentres` ###

DROP TABLE IF EXISTS `0_workcentres`;

CREATE TABLE `0_workcentres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL DEFAULT '',
  `description` char(50) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_workcentres` ###



### Structure of table `0_workorders` ###

DROP TABLE IF EXISTS `0_workorders`;

CREATE TABLE `0_workorders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wo_ref` varchar(60) NOT NULL DEFAULT '',
  `loc_code` varchar(5) NOT NULL DEFAULT '',
  `units_reqd` double NOT NULL DEFAULT '1',
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `required_by` date NOT NULL DEFAULT '0000-00-00',
  `released_date` date NOT NULL DEFAULT '0000-00-00',
  `units_issued` double NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `released` tinyint(1) NOT NULL DEFAULT '0',
  `additional_costs` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `wo_ref` (`wo_ref`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


### Data of table `0_workorders` ###

