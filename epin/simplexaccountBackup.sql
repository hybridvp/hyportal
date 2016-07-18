-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 15, 2010 at 02:00 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `simplexaccount`
--

-- --------------------------------------------------------

--
-- Table structure for table `hold_sales`
--

CREATE TABLE IF NOT EXISTS `hold_sales` (
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
  `dimension2_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hold_sales`
--


-- --------------------------------------------------------

--
-- Table structure for table `track_debug`
--

CREATE TABLE IF NOT EXISTS `track_debug` (
  `text_a` varchar(255) DEFAULT NULL,
  `text_b` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `track_debug`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_analysis_codes`
--

CREATE TABLE IF NOT EXISTS `0_analysis_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL DEFAULT '',
  `parent` varchar(64) NOT NULL DEFAULT '00000',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` varchar(64) NOT NULL,
  `created_date` datetime NOT NULL,
  `last_modified_by` varchar(64) NOT NULL,
  `last_modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `0_analysis_codes`
--

INSERT INTO `0_analysis_codes` (`id`, `code`, `name`, `parent`, `inactive`, `created_by`, `created_date`, `last_modified_by`, `last_modified_date`) VALUES
(18, '1000.000.001', 'Staff Cost', '1000', 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(19, '1000.000.000', 'Head Office', '1000', 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(20, '1000.000.002', 'Onitsha Outlet Sundry Expense2', '1000', 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(21, '1000.000.003', 'New Lagos Office Project', '10000', 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `0_approval_mat`
--

CREATE TABLE IF NOT EXISTS `0_approval_mat` (
  `type` varchar(30) NOT NULL DEFAULT 'ALL',
  `username` varchar(30) NOT NULL,
  `approving_officer` varchar(30) NOT NULL,
  `approving_officer_name` varchar(64) DEFAULT NULL,
  `approval_limit` decimal(10,0) NOT NULL DEFAULT '0',
  `description` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`type`,`username`,`approving_officer`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_approval_mat`
--

INSERT INTO `0_approval_mat` (`type`, `username`, `approving_officer`, `approving_officer_name`, `approval_limit`, `description`) VALUES
('ALL', 'admin', 'admin', 'Administrator', '10000000', 'Testing Approval'),
('ALL', 'admin', 'sola', 'Sola Ade', '10000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `0_areas`
--

CREATE TABLE IF NOT EXISTS `0_areas` (
  `area_code` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_code`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `0_areas`
--

INSERT INTO `0_areas` (`area_code`, `description`, `inactive`) VALUES
(1, 'Lagos', 0),
(2, 'Ibadan', 0),
(3, 'Abuja', 0),
(4, 'Port Harcourt', 0),
(5, 'Kano', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_attachments`
--

CREATE TABLE IF NOT EXISTS `0_attachments` (
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

--
-- Dumping data for table `0_attachments`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_audit_trail`
--

CREATE TABLE IF NOT EXISTS `0_audit_trail` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=147 ;

--
-- Dumping data for table `0_audit_trail`
--

INSERT INTO `0_audit_trail` (`id`, `type`, `trans_no`, `user`, `stamp`, `description`, `fiscal_year`, `gl_date`, `gl_seq`) VALUES
(1, 18, 1, 1, '2010-02-22 15:32:34', '', 3, '2010-02-22', 0),
(2, 25, 1, 1, '2010-02-22 15:32:47', '', 3, '2010-02-22', 0),
(3, 20, 1, 1, '2010-02-22 15:33:48', '', 3, '2010-02-22', 0),
(4, 22, 1, 1, '2010-02-22 15:34:19', '', 3, '2010-02-22', 0),
(5, 2, 1, 1, '2010-02-22 15:35:54', '', 3, '2010-02-22', 0),
(6, 16, 1, 1, '2010-02-22 15:38:22', '', 3, '2010-02-22', 0),
(7, 32, 1, 1, '2010-02-22 15:57:49', '', 3, '2010-02-22', 0),
(8, 30, 1, 1, '2010-02-22 15:58:02', '', 3, '2010-02-22', 0),
(9, 12, 1, 1, '2010-02-22 15:59:13', '', 3, '2010-02-22', 0),
(10, 13, 1, 1, '2010-02-22 15:59:53', '', 3, '2010-02-22', 0),
(11, 10, 1, 1, '2010-02-22 15:59:58', '', 3, '2010-02-22', 0),
(12, 32, 2, 1, '2010-02-22 17:57:16', '', 3, '2010-02-22', 0),
(13, 30, 2, 1, '2010-02-22 17:58:02', '', 3, '2010-02-22', NULL),
(14, 30, 2, 1, '2010-02-22 17:58:02', 'Updated.', 3, '2010-02-22', 0),
(15, 12, 2, 1, '2010-02-22 18:01:28', '', 3, '2010-02-22', 0),
(16, 13, 2, 1, '2010-02-22 18:05:28', '', 3, '2010-02-22', NULL),
(17, 13, 2, 1, '2010-02-22 18:10:26', 'Updated.', 3, '2010-02-22', NULL),
(18, 13, 3, 1, '2010-02-22 18:08:26', '', 3, '2010-02-22', 0),
(19, 13, 2, 1, '2010-02-22 18:10:26', 'Updated.', 3, '2010-02-22', 0),
(20, 32, 3, 1, '2010-02-23 04:12:27', '', 3, '2010-02-23', 0),
(21, 30, 3, 1, '2010-02-23 04:12:42', '', 3, '2010-02-23', 0),
(22, 1001, 1, 1, '2010-02-23 14:41:23', '', 3, '2010-02-23', 0),
(23, 1001, 2, 1, '2010-02-23 14:44:45', '', 3, '2010-02-23', 0),
(24, 1001, 3, 1, '2010-02-23 14:47:44', '', 3, '2010-02-23', 0),
(25, 18, 2, 1, '2010-02-23 14:50:18', '', 3, '2010-02-23', 0),
(26, 1001, 4, 1, '2010-02-23 15:11:24', '', 3, '2010-02-23', 0),
(27, 32, 4, 1, '2010-02-23 16:50:44', '', 3, '2010-02-23', 0),
(28, 32, 5, 1, '2010-02-23 17:36:03', '', 3, '2010-02-23', 0),
(29, 30, 4, 1, '2010-02-23 17:37:32', '', 3, '2010-02-23', 0),
(30, 32, 6, 1, '2010-02-23 17:46:22', '', 3, '2010-02-23', 0),
(31, 30, 5, 1, '2010-02-23 17:46:40', '', 3, '2010-02-23', 0),
(32, 13, 4, 1, '2010-02-23 17:52:38', '', 3, '2010-02-23', 0),
(33, 10, 2, 1, '2010-02-23 17:52:58', '', 3, '2010-02-23', 0),
(34, 10, 3, 1, '2010-02-23 17:53:29', '', 3, '2010-02-22', 0),
(35, 12, 3, 1, '2010-02-23 18:08:59', '', 3, '2010-02-23', 0),
(36, 1001, 5, 1, '2010-02-24 06:23:06', '', 3, '2010-02-24', 0),
(37, 1001, 6, 1, '2010-02-24 06:39:42', '', 3, '2010-02-24', 0),
(38, 32, 7, 1, '2010-02-24 06:42:04', '', 3, '2010-02-24', 0),
(39, 1001, 7, 1, '2010-02-24 11:07:13', '', 3, '2010-02-24', 0),
(40, 18, 3, 1, '2010-02-24 11:47:31', '', 3, '2010-02-24', 0),
(41, 18, 4, 1, '2010-02-24 12:06:22', '', 3, '2010-02-24', 0),
(42, 18, 5, 1, '2010-02-24 12:24:07', '', 3, '2010-02-24', 0),
(43, 18, 6, 1, '2010-02-24 12:36:54', '', 3, '2010-02-23', 0),
(44, 18, 7, 1, '2010-02-24 13:26:32', '', 3, '2010-02-23', 0),
(45, 18, 8, 1, '2010-02-24 13:29:07', '', 3, '2010-02-23', 0),
(46, 18, 9, 1, '2010-02-24 13:35:19', '', 3, '2010-02-24', 0),
(47, 18, 10, 1, '2010-02-24 13:45:10', '', 3, '2010-02-24', 0),
(48, 18, 11, 1, '2010-02-24 13:51:33', '', 3, '2010-02-23', 0),
(49, 17, 1, 1, '2010-02-24 18:09:50', '', 3, '2010-02-24', 0),
(50, 32, 8, 1, '2010-02-24 18:12:40', '', 3, '2010-02-24', 0),
(51, 30, 6, 1, '2010-02-24 18:17:03', '', 3, '2010-02-24', 0),
(52, 12, 4, 1, '2010-02-24 18:29:30', '', 3, '2010-02-24', 0),
(53, 12, 5, 1, '2010-02-24 18:29:52', '', 3, '2010-02-24', 0),
(54, 12, 6, 1, '2010-02-24 18:30:33', '', 3, '2010-02-24', 0),
(55, 13, 5, 1, '2010-02-24 18:36:26', '', 3, '2010-02-24', 0),
(56, 10, 4, 1, '2010-02-24 18:38:14', '', 3, '2010-02-24', 0),
(57, 18, 12, 1, '2010-02-25 08:09:36', '', 3, '2010-02-23', 0),
(58, 18, 13, 1, '2010-02-25 08:19:09', '', 3, '2010-02-24', 0),
(59, 18, 14, 1, '2010-02-25 08:19:58', '', 3, '2010-02-24', 0),
(60, 18, 15, 1, '2010-02-25 08:24:09', '', 3, '2010-02-24', 0),
(61, 18, 16, 1, '2010-02-25 08:34:30', '', 3, '2010-02-24', 0),
(62, 32, 9, 1, '2010-02-25 10:20:38', '', 3, '2010-02-25', 0),
(63, 30, 7, 1, '2010-02-25 10:24:39', '', 3, '2010-02-25', 0),
(64, 13, 6, 1, '2010-02-25 10:50:27', '', 3, '2010-02-25', 0),
(65, 10, 5, 1, '2010-02-25 10:52:54', '', 3, '2010-02-25', 0),
(66, 18, 17, 1, '2010-02-25 13:47:23', '', 3, '2010-02-23', 0),
(67, 18, 18, 1, '2010-02-25 13:48:21', '', 3, '2010-02-23', 0),
(68, 18, 19, 1, '2010-02-25 14:26:05', '', 3, '2010-02-23', 0),
(69, 18, 20, 1, '2010-02-25 14:43:52', '', 3, '2010-02-23', 0),
(70, 18, 21, 1, '2010-02-25 14:45:22', '', 3, '2010-02-23', 0),
(71, 30, 8, 1, '2010-02-25 14:53:22', '', 3, '2010-02-25', 0),
(72, 30, 9, 1, '2010-02-25 14:53:34', '', 3, '2010-02-25', 0),
(73, 18, 22, 1, '2010-02-25 17:10:37', '', 3, '2010-02-24', 0),
(74, 18, 23, 1, '2010-02-25 17:15:54', '', 3, '2010-02-24', 0),
(75, 18, 24, 1, '2010-02-27 04:27:01', '', 3, '2010-02-24', 0),
(76, 18, 25, 1, '2010-02-27 04:28:12', '', 3, '2010-02-23', 0),
(77, 1001, 8, 1, '2010-02-27 07:29:49', '', 3, '2010-02-27', 0),
(78, 1001, 10, 1, '2010-03-02 09:05:23', '', 3, '2010-03-02', 0),
(79, 18, 26, 1, '2010-03-02 10:05:24', '', 3, '2010-03-02', 0),
(80, 18, 27, 1, '2010-03-02 10:12:36', '', 3, '2010-03-02', 0),
(81, 18, 28, 1, '2010-03-02 10:17:06', '', 3, '2010-03-02', 0),
(82, 18, 29, 1, '2010-03-02 16:57:32', '', 3, '2010-02-27', 0),
(83, 1001, 11, 1, '2010-03-03 06:25:16', '', 3, '2010-03-03', 0),
(84, 13, 7, 1, '2010-03-03 06:27:45', '', 3, '2010-03-03', 0),
(85, 10, 6, 1, '2010-03-03 06:29:24', '', 3, '2010-03-03', 0),
(86, 1001, 12, 1, '2010-03-04 17:26:16', '', 3, '2010-03-04', 0),
(87, 18, 30, 1, '2010-03-04 22:41:08', '', 3, '2010-03-04', 0),
(88, 18, 31, 1, '2010-03-05 13:09:29', '', 3, '2010-03-04', 0),
(89, 1001, 13, 1, '2010-03-05 13:33:01', '', 3, '2010-03-05', 0),
(90, 1001, 14, 1, '2010-03-09 08:04:28', '', 3, '2010-03-09', 0),
(91, 18, 32, 4, '2010-03-09 12:08:18', '', 3, '2010-03-04', 0),
(92, 18, 33, 1, '2010-03-09 14:20:56', '', 3, '2010-03-04', 0),
(93, 32, 10, 1, '2010-03-09 17:54:49', '', 3, '2010-03-09', 0),
(94, 30, 10, 1, '2010-03-09 17:57:54', '', 3, '2010-03-09', 0),
(95, 13, 8, 1, '2010-03-25 12:26:29', '', 3, '2010-03-25', 0),
(96, 18, 34, 1, '2010-03-30 15:21:58', '', 3, '2010-03-30', 0),
(97, 25, 2, 1, '2010-03-30 15:22:14', '', 3, '2010-03-30', 0),
(98, 18, 35, 1, '2010-03-30 15:23:07', '', 3, '2010-03-30', 0),
(99, 25, 3, 1, '2010-03-30 15:23:22', '', 3, '2010-03-30', 0),
(100, 18, 36, 1, '2010-03-30 15:33:13', '', 3, '2010-03-30', 0),
(101, 25, 4, 1, '2010-03-30 15:33:22', '', 3, '2010-03-30', 0),
(102, 18, 37, 1, '2010-03-30 16:14:33', '', 3, '2010-03-30', 0),
(103, 25, 5, 1, '2010-03-30 16:14:40', '', 3, '2010-03-30', 0),
(104, 18, 38, 1, '2010-03-30 16:15:38', '', 3, '2010-03-30', 0),
(105, 25, 6, 1, '2010-03-30 16:15:47', '', 3, '2010-03-30', 0),
(106, 18, 39, 1, '2010-03-30 16:17:09', '', 3, '2010-03-30', 0),
(107, 25, 7, 1, '2010-03-30 16:17:52', '', 3, '2010-03-30', 0),
(108, 30, 11, 1, '2010-03-31 12:13:19', '', 3, '2010-03-31', 0),
(109, 30, 12, 1, '2010-03-31 12:27:31', '', 3, '2010-03-31', 0),
(110, 13, 9, 1, '2010-03-31 12:28:32', '', 3, '2010-03-31', 0),
(111, 32, 11, 1, '2010-03-31 13:46:25', '', 3, '2010-03-31', 0),
(112, 30, 13, 1, '2010-03-31 13:46:40', '', 3, '2010-03-31', 0),
(113, 18, 40, 1, '2010-04-02 09:16:11', '', 3, '2010-04-02', 0),
(114, 25, 8, 1, '2010-04-02 09:16:36', '', 3, '2010-04-02', 0),
(115, 18, 41, 1, '2010-04-02 09:17:56', '', 3, '2010-04-02', 0),
(116, 25, 9, 1, '2010-04-02 09:19:43', '', 3, '2010-04-02', 0),
(117, 18, 42, 1, '2010-04-02 09:20:37', '', 3, '2010-04-02', 0),
(118, 25, 10, 1, '2010-04-02 09:20:59', '', 3, '2010-04-02', 0),
(119, 1001, 15, 1, '2010-04-07 17:22:34', '', 3, '2010-04-07', 0),
(120, 18, 43, 1, '2010-04-07 17:24:20', '', 3, '2010-04-07', 0),
(121, 18, 44, 1, '2010-04-07 17:25:51', '', 3, '2010-04-07', 0),
(122, 25, 11, 1, '2010-04-07 17:26:33', '', 3, '2010-04-07', 0),
(123, 20, 2, 1, '2010-04-07 17:29:40', '', 3, '2010-04-07', 0),
(124, 18, 45, 1, '2010-04-07 19:06:33', '', 3, '2010-04-07', 0),
(125, 25, 12, 1, '2010-04-07 19:07:06', '', 3, '2010-04-07', 0),
(126, 32, 12, 1, '2010-04-07 20:13:08', '', 3, '2010-04-07', 0),
(127, 30, 14, 1, '2010-04-07 20:13:37', '', 3, '2010-04-07', 0),
(128, 18, 46, 1, '2010-04-08 08:00:35', '', 3, '2010-04-08', 0),
(129, 25, 13, 1, '2010-04-08 08:00:43', '', 3, '2010-04-08', 0),
(130, 32, 13, 1, '2010-04-08 08:30:50', '', 3, '2010-04-08', 0),
(131, 30, 15, 1, '2010-04-08 08:32:42', '', 3, '2010-04-08', 0),
(132, 13, 10, 1, '2010-04-08 10:21:14', '', 3, '2010-04-08', 0),
(133, 32, 14, 1, '2010-04-08 12:02:13', '', 3, '2010-04-08', 0),
(134, 30, 16, 1, '2010-04-08 12:12:10', '', 3, '2010-04-08', 0),
(135, 1001, 16, 1, '2010-04-14 09:08:46', '', 3, '2010-04-14', 0),
(136, 18, 47, 1, '2010-04-14 09:14:54', '', 3, '2010-04-07', 0),
(137, 18, 48, 1, '2010-04-14 09:15:05', '', 3, '2010-04-07', 0),
(138, 25, 14, 1, '2010-04-14 09:16:02', '', 3, '2010-04-14', 0),
(139, 25, 15, 1, '2010-04-14 09:16:12', '', 3, '2010-04-14', 0),
(140, 32, 15, 1, '2010-04-14 09:30:06', '', 3, '2010-04-14', 0),
(141, 30, 17, 1, '2010-04-14 09:30:35', '', 3, '2010-04-14', 0),
(142, 13, 11, 1, '2010-04-14 09:47:48', '', 3, '2010-04-14', 0),
(143, 13, 12, 1, '2010-04-14 10:13:06', '', 3, '2010-04-14', 0),
(144, 32, 16, 1, '2010-04-15 13:19:02', '', 3, '2010-04-15', NULL),
(145, 32, 16, 1, '2010-04-15 13:19:02', 'Updated.', 3, '2010-04-15', 0),
(146, 32, 17, 1, '2010-04-15 13:19:56', '', 3, '2010-04-15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_authority_lmt`
--

CREATE TABLE IF NOT EXISTS `0_authority_lmt` (
  `type` varchar(30) NOT NULL DEFAULT 'ALL',
  `username` varchar(30) NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  `approval_limit` decimal(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`type`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_authority_lmt`
--

INSERT INTO `0_authority_lmt` (`type`, `username`, `description`, `approval_limit`) VALUES
('ALL', 'admin', 'Administrator', '100000000'),
('ALL', 'sola', 'Sola Ade', '20000000');

-- --------------------------------------------------------

--
-- Table structure for table `0_bank_accounts`
--

CREATE TABLE IF NOT EXISTS `0_bank_accounts` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `0_bank_accounts`
--

INSERT INTO `0_bank_accounts` (`account_code`, `account_type`, `bank_account_name`, `bank_account_number`, `bank_name`, `bank_address`, `bank_curr_code`, `dflt_curr_act`, `id`, `last_reconciled_date`, `ending_reconcile_balance`, `inactive`) VALUES
('1060', 0, 'UBA Bank Account', '22222-222--2222', 'UBA Plc', 'Idowu Talyor, VI', 'NGN', 0, 1, '0000-00-00 00:00:00', 0, 0),
('1065', 0, 'Cash', '', 'Office CashBox', 'Office', 'NGN', 1, 2, '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_bank_trans`
--

CREATE TABLE IF NOT EXISTS `0_bank_trans` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `0_bank_trans`
--

INSERT INTO `0_bank_trans` (`id`, `type`, `trans_no`, `bank_act`, `ref`, `trans_date`, `amount`, `dimension_id`, `dimension2_id`, `person_type_id`, `person_id`, `reconciled`) VALUES
(1, 22, 1, '2', '1', '2010-02-22', -44100, 0, 0, 3, 0x31, NULL),
(2, 2, 1, '2', '1', '2010-02-22', 50000, 0, 0, 0, 0x46756e64696e67204163636f756e74, NULL),
(3, 12, 1, '2', '1', '2010-02-22', 630, 0, 0, 2, 0x4c303031, NULL),
(4, 12, 2, '2', '2', '2010-02-22', 450, 0, 0, 2, 0x4c303031, NULL),
(5, 12, 3, '1', '3', '2010-02-23', 1000, 0, 0, 2, 0x4c303031, NULL),
(6, 12, 4, '1', '4', '2010-02-24', 100000, 0, 0, 2, 0x4942303031, NULL),
(7, 12, 5, '1', '5', '2010-02-24', 100000, 0, 0, 2, 0x4942303031, NULL),
(8, 12, 6, '1', '6', '2010-02-24', 100000, 0, 0, 2, 0x4942303031, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `0_bom`
--

CREATE TABLE IF NOT EXISTS `0_bom` (
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

--
-- Dumping data for table `0_bom`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_budget_master`
--

CREATE TABLE IF NOT EXISTS `0_budget_master` (
  `counter` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `type_no` bigint(16) NOT NULL DEFAULT '1',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `account` varchar(11) NOT NULL DEFAULT '',
  `costcentre` varchar(64) NOT NULL DEFAULT '.',
  `memo_` tinytext NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `dimension_id` int(11) DEFAULT '0',
  `dimension2_id` int(11) DEFAULT '0',
  `person_type_id` int(11) DEFAULT NULL,
  `person_id` tinyblob,
  `created_by` varchar(64) NOT NULL,
  `created_date` datetime NOT NULL,
  `approved_by` varchar(64) NOT NULL,
  `approved_dated` datetime NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`counter`),
  KEY `Type_and_Number` (`type`,`type_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=239 ;

--
-- Dumping data for table `0_budget_master`
--

INSERT INTO `0_budget_master` (`counter`, `type`, `type_no`, `tran_date`, `account`, `costcentre`, `memo_`, `amount`, `dimension_id`, `dimension2_id`, `person_type_id`, `person_id`, `created_by`, `created_date`, `approved_by`, `approved_dated`, `status`) VALUES
(225, 0, 1, '2010-01-01', '1540', '1000.000.000', '', 1000, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(226, 0, 1, '2010-01-01', '1510', '1000.000.002', '', 2000000, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(227, 0, 1, '2010-01-01', '1540', '1000.000.002', '', 1000, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(228, 0, 1, '2010-01-01', '1510', '1000.000.001', '', 2000000, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(229, 0, 1, '2010-01-01', '1540', '1000.000.000', '', 12000, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(230, 0, 1, '2010-01-01', '1205', '1000.000.000', '', 12000, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(231, 0, 1, '2010-01-01', '1205', '1000.000.001', '', 324234, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(232, 0, 1, '2010-01-01', '1205', '1000.000.002', '', 3123210, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(233, 0, 1, '2010-01-01', '1205', '1000.000.003', '', 12344, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(234, 0, 1, '2010-01-01', '1520', '1000.000.000', '', 3, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(235, 0, 1, '2010-01-01', '1520', '1000.000.001', '', 3, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(236, 0, 1, '2010-01-01', '1520', '1000.000.002', '', 3, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(237, 0, 1, '2010-01-01', '1520', '1000.000.003', '', 3, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A'),
(238, 0, 1, '2010-02-01', '1520', '1000.000.002', '', 34555, 0, 0, NULL, NULL, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `0_budget_trans`
--

CREATE TABLE IF NOT EXISTS `0_budget_trans` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `0_budget_trans`
--

INSERT INTO `0_budget_trans` (`counter`, `type`, `type_no`, `tran_date`, `account`, `memo_`, `amount`, `dimension_id`, `dimension2_id`, `person_type_id`, `person_id`) VALUES
(1, 0, 1, '2010-01-01', '1200', '', 4323, 0, 0, NULL, NULL),
(2, 0, 1, '2010-02-01', '1200', '', 2342, 0, 0, NULL, NULL),
(3, 0, 1, '2010-03-01', '1200', '', 32132, 0, 0, NULL, NULL),
(4, 0, 1, '2010-04-01', '1200', '', 23423, 0, 0, NULL, NULL),
(5, 0, 1, '2010-05-01', '1200', '', 34535, 0, 0, NULL, NULL),
(6, 0, 1, '2010-06-01', '1200', '', 324234, 0, 0, NULL, NULL),
(7, 0, 1, '2010-07-01', '1200', '', 234523, 0, 0, NULL, NULL),
(8, 0, 1, '2010-08-01', '1200', '', 32423, 0, 0, NULL, NULL),
(9, 0, 1, '2010-09-01', '1200', '', 34534, 0, 0, NULL, NULL),
(10, 0, 1, '2010-10-01', '1200', '', 23423, 0, 0, NULL, NULL),
(11, 0, 1, '2010-11-01', '1200', '', 234234, 0, 0, NULL, NULL),
(12, 0, 1, '2010-12-01', '1200', '', 34534, 0, 0, NULL, NULL),
(13, 0, 1, '2010-01-01', '1550', '', 4323, 0, 0, NULL, NULL),
(14, 0, 1, '2010-02-01', '1550', '', 2342, 0, 0, NULL, NULL),
(15, 0, 1, '2010-03-01', '1550', '', 32132, 0, 0, NULL, NULL),
(16, 0, 1, '2010-04-01', '1550', '', 23423, 0, 0, NULL, NULL),
(17, 0, 1, '2010-05-01', '1550', '', 34535, 0, 0, NULL, NULL),
(18, 0, 1, '2010-06-01', '1550', '', 324234, 0, 0, NULL, NULL),
(19, 0, 1, '2010-07-01', '1550', '', 234523, 0, 0, NULL, NULL),
(20, 0, 1, '2010-08-01', '1550', '', 32423, 0, 0, NULL, NULL),
(21, 0, 1, '2010-09-01', '1550', '', 34534, 0, 0, NULL, NULL),
(22, 0, 1, '2010-10-01', '1550', '', 23423, 0, 0, NULL, NULL),
(23, 0, 1, '2010-11-01', '1550', '', 234234, 0, 0, NULL, NULL),
(24, 0, 1, '2010-12-01', '1550', '', 34534, 0, 0, NULL, NULL),
(25, 0, 1, '2010-01-01', '2680', '', 4323, 0, 0, NULL, NULL),
(26, 0, 1, '2010-02-01', '2680', '', 2342, 0, 0, NULL, NULL),
(27, 0, 1, '2010-03-01', '2680', '', 32132, 0, 0, NULL, NULL),
(28, 0, 1, '2010-04-01', '2680', '', 23423, 0, 0, NULL, NULL),
(29, 0, 1, '2010-05-01', '2680', '', 34535, 0, 0, NULL, NULL),
(30, 0, 1, '2010-06-01', '2680', '', 324234, 0, 0, NULL, NULL),
(31, 0, 1, '2010-07-01', '2680', '', 234523, 0, 0, NULL, NULL),
(32, 0, 1, '2010-08-01', '2680', '', 32423, 0, 0, NULL, NULL),
(33, 0, 1, '2010-09-01', '2680', '', 34534, 0, 0, NULL, NULL),
(34, 0, 1, '2010-10-01', '2680', '', 23423, 0, 0, NULL, NULL),
(35, 0, 1, '2010-11-01', '2680', '', 234234, 0, 0, NULL, NULL),
(36, 0, 1, '2010-12-01', '2680', '', 34534, 0, 0, NULL, NULL),
(37, 0, 1, '2010-01-01', '1530', '', 2000, 0, 0, NULL, NULL),
(38, 0, 1, '2010-02-01', '1530', '', 3342423, 0, 0, NULL, NULL),
(39, 0, 1, '2010-03-01', '1530', '', 0, 0, 0, NULL, NULL),
(40, 0, 1, '2010-04-01', '1530', '', 0, 0, 0, NULL, NULL),
(41, 0, 1, '2010-05-01', '1530', '', 0, 0, 0, NULL, NULL),
(42, 0, 1, '2010-06-01', '1530', '', 0, 0, 0, NULL, NULL),
(43, 0, 1, '2010-07-01', '1530', '', 0, 0, 0, NULL, NULL),
(44, 0, 1, '2010-08-01', '1530', '', 0, 0, 0, NULL, NULL),
(45, 0, 1, '2010-09-01', '1530', '', 0, 0, 0, NULL, NULL),
(46, 0, 1, '2010-10-01', '1530', '', 0, 0, 0, NULL, NULL),
(47, 0, 1, '2010-11-01', '1530', '', 0, 0, 0, NULL, NULL),
(48, 0, 1, '2010-12-01', '1530', '', 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `0_budget_trans_details`
--

CREATE TABLE IF NOT EXISTS `0_budget_trans_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL DEFAULT 'PR',
  `budget_type` varchar(30) NOT NULL DEFAULT 'Internal Commitment',
  `trans_no` bigint(16) NOT NULL DEFAULT '1',
  `action` varchar(30) NOT NULL DEFAULT 'Approval',
  `trans_date` date NOT NULL DEFAULT '0000-00-00',
  `account` varchar(64) NOT NULL DEFAULT '',
  `costcentre` varchar(64) NOT NULL DEFAULT '',
  `item_code` varchar(30) NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `direction` double NOT NULL DEFAULT '1',
  `dimension_id` int(11) DEFAULT '0',
  `dimension2_id` int(11) DEFAULT '0',
  `created_by` varchar(64) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `Type_and_Number` (`type`,`trans_no`,`action`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `0_budget_trans_details`
--

INSERT INTO `0_budget_trans_details` (`id`, `type`, `budget_type`, `trans_no`, `action`, `trans_date`, `account`, `costcentre`, `item_code`, `amount`, `direction`, `dimension_id`, `dimension2_id`, `created_by`, `created_date`, `status`) VALUES
(6, 'PR', 'Internal Commitment', 12, 'Approval', '2010-03-09', '1510', '1000.000.001', 'N100A', 126000, -1, 0, 0, 'system', '2010-03-09 12:31:14', 'A'),
(7, 'PR', 'Internal Commitment', 15, 'Approval', '2010-04-07', '1510', '1000.000.001', 'N100A', 42000, -1, 0, 0, 'system', '2010-04-07 17:23:38', 'A'),
(8, 'PR', 'Internal Commitment', 16, 'Approval', '2010-04-14', '1510', '', 'N100A', 42000, -1, 0, 0, 'system', '2010-04-14 09:09:18', 'A'),
(9, 'PR', 'Internal Commitment', 16, 'Approval', '2010-04-14', '1510', '1000.000.000', 'N100A', 42000, -1, 0, 0, 'system', '2010-04-14 09:11:20', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `0_chart_class`
--

CREATE TABLE IF NOT EXISTS `0_chart_class` (
  `cid` int(11) NOT NULL DEFAULT '0',
  `class_name` varchar(60) NOT NULL DEFAULT '',
  `ctype` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_chart_class`
--

INSERT INTO `0_chart_class` (`cid`, `class_name`, `ctype`, `inactive`) VALUES
(1, 'Assets', 1, 0),
(2, 'Liabilities', 2, 0),
(3, 'Income', 4, 0),
(4, 'Costs', 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_chart_master`
--

CREATE TABLE IF NOT EXISTS `0_chart_master` (
  `account_code` varchar(11) NOT NULL DEFAULT '',
  `account_code2` varchar(11) DEFAULT '',
  `account_name` varchar(60) NOT NULL DEFAULT '',
  `account_type` int(11) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_code`),
  KEY `account_code` (`account_code`),
  KEY `account_name` (`account_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_chart_master`
--

INSERT INTO `0_chart_master` (`account_code`, `account_code2`, `account_name`, `account_type`, `inactive`) VALUES
('1060', '', 'Checking Account', 1, 0),
('1065', '', 'Petty Cash', 1, 0),
('1200', '', 'Accounts Receivables', 1, 0),
('1205', '', 'Allowance for doubtful accounts', 1, 0),
('1510', '', 'Inventory', 2, 0),
('1520', '', 'Stocks of Raw Materials', 2, 0),
('1530', '', 'Stocks of Work In Progress', 2, 0),
('1540', '', 'Stocks of Finsihed Goods', 2, 0),
('1550', '', 'Goods Received Clearing account', 2, 0),
('1820', '', 'Office Furniture &amp; Equipment', 3, 0),
('1825', '', 'Accum. Amort. -Furn. &amp; Equip.', 3, 0),
('1840', '', 'Vehicle', 3, 0),
('1845', '', 'Accum. Amort. -Vehicle', 3, 0),
('2100', '', 'Accounts Payable', 4, 0),
('2110', '', 'Accrued Income Tax - Federal', 4, 0),
('2120', '', 'Accrued Income Tax - State', 4, 0),
('2130', '', 'Accrued Franchise Tax', 4, 0),
('2140', '', 'Accrued Real &amp; Personal Prop Tax', 4, 0),
('2150', '', 'Sales Tax', 4, 0),
('2160', '', 'Accrued Use Tax Payable', 4, 0),
('2210', '', 'Accrued Wages', 4, 0),
('2220', '', 'Accrued Comp Time', 4, 0),
('2230', '', 'Accrued Holiday Pay', 4, 0),
('2240', '', 'Accrued Vacation Pay', 4, 0),
('2310', '', 'Accr. Benefits - 401K', 4, 0),
('2320', '', 'Accr. Benefits - Stock Purchase', 4, 0),
('2330', '', 'Accr. Benefits - Med, Den', 4, 0),
('2340', '', 'Accr. Benefits - Payroll Taxes', 4, 0),
('2350', '', 'Accr. Benefits - Credit Union', 4, 0),
('2360', '', 'Accr. Benefits - Savings Bond', 4, 0),
('2370', '', 'Accr. Benefits - Garnish', 4, 0),
('2380', '', 'Accr. Benefits - Charity Cont.', 4, 0),
('2620', '', 'Bank Loans', 5, 0),
('2680', '', 'Loans from Shareholders', 5, 0),
('3350', '', 'Common Shares', 6, 0),
('3590', '', 'Retained Earnings - prior years', 7, 0),
('4010', '', 'Sales', 8, 0),
('4430', '', 'Shipping &amp; Handling', 9, 0),
('4440', '', 'Interest', 9, 0),
('4450', '', 'Foreign Exchange Gain', 9, 0),
('4500', '', 'Prompt Payment Discounts', 9, 0),
('4510', '', 'Discounts Given', 9, 0),
('5010', '', 'Cost of Goods Sold - Retail', 10, 0),
('5020', '', 'Material Usage Varaiance', 10, 0),
('5030', '', 'Consumable Materials', 10, 0),
('5040', '', 'Purchase price Variance', 10, 0),
('5050', '', 'Purchases of materials', 10, 0),
('5060', '', 'Discounts Received', 10, 0),
('5100', '', 'Freight', 10, 0),
('5410', '', 'Wages &amp; Salaries', 11, 0),
('5420', '', 'Wages - Overtime', 11, 0),
('5430', '', 'Benefits - Comp Time', 11, 0),
('5440', '', 'Benefits - Payroll Taxes', 11, 0),
('5450', '', 'Benefits - Workers Comp', 11, 0),
('5460', '', 'Benefits - Pension', 11, 0),
('5470', '', 'Benefits - General Benefits', 11, 0),
('5510', '', 'Inc Tax Exp - Federal', 11, 0),
('5520', '', 'Inc Tax Exp - State', 11, 0),
('5530', '', 'Taxes - Real Estate', 11, 0),
('5540', '', 'Taxes - Personal Property', 11, 0),
('5550', '', 'Taxes - Franchise', 11, 0),
('5560', '', 'Taxes - Foreign Withholding', 11, 0),
('5610', '', 'Accounting &amp; Legal', 12, 0),
('5615', '', 'Advertising &amp; Promotions', 12, 0),
('5620', '', 'Bad Debts', 12, 0),
('5660', '', 'Amortization Expense', 12, 0),
('5685', '', 'Insurance', 12, 0),
('5690', '', 'Interest &amp; Bank Charges', 12, 0),
('5700', '', 'Office Supplies', 12, 0),
('5760', '', 'Rent', 12, 0),
('5765', '', 'Repair &amp; Maintenance', 12, 0),
('5780', '', 'Telephone', 12, 0),
('5785', '', 'Travel &amp; Entertainment', 12, 0),
('5790', '', 'Utilities', 12, 0),
('5795', '', 'Registrations', 12, 0),
('5800', '', 'Licenses', 12, 0),
('5810', '', 'Foreign Exchange Loss', 12, 0),
('9990', '', 'Year Profit/Loss', 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_chart_types`
--

CREATE TABLE IF NOT EXISTS `0_chart_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `class_id` tinyint(1) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '-1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `0_chart_types`
--

INSERT INTO `0_chart_types` (`id`, `name`, `class_id`, `parent`, `inactive`) VALUES
(1, 'Current Assets', 1, -1, 0),
(2, 'Inventory Assets', 1, -1, 0),
(3, 'Capital Assets', 1, -1, 0),
(5, 'Long Term Liabilities', 2, -1, 0),
(6, 'Share Capital', 2, -1, 0),
(7, 'Retained Earnings', 2, -1, 0),
(8, 'Sales Revenue', 3, -1, 0),
(9, 'Other Revenue', 3, -1, 0),
(10, 'Cost of Goods Sold', 4, -1, 0),
(11, 'Payroll Expenses', 4, -1, 0),
(12, 'General &amp; Administrative expenses', 4, -1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_comments`
--

CREATE TABLE IF NOT EXISTS `0_comments` (
  `type` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `date_` date DEFAULT '0000-00-00',
  `memo_` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_comments`
--

INSERT INTO `0_comments` (`type`, `id`, `date_`, `memo_`) VALUES
(12, 1, '2010-02-22', 'Direct Sales Payment'),
(13, 1, '2010-02-22', 'Sales Quotation # 1'),
(10, 1, '2010-02-22', 'Sales Quotation # 1'),
(12, 2, '2010-02-22', 'tESTING '),
(13, 3, '2010-02-22', 'Sales Quotation # 2'),
(13, 2, '2010-02-22', 'Sales Quotation # 2'),
(13, 4, '2010-02-23', 'Sales Quotation # 5'),
(10, 2, '2010-02-23', 'Sales Quotation # 5'),
(10, 3, '2010-02-22', 'Sales Quotation # 2Sales Quotation # 2'),
(12, 3, '2010-02-23', 'Testing some pay request'),
(13, 5, '2010-02-24', 'Sales Quotation # 8'),
(10, 4, '2010-02-24', 'Sales Quotation # 8'),
(13, 6, '2010-02-25', 'Sales Quotation # 9'),
(10, 5, '2010-02-25', 'Sales Quotation # 9'),
(40, 1, '2010-02-27', 'note'),
(13, 7, '2010-03-03', 'Sales Quotation # 7'),
(10, 6, '2010-03-03', 'Sales Quotation # 7'),
(13, 8, '2010-03-25', 'Sales Quotation # 3'),
(13, 10, '2010-04-08', 'Sales Quotation # 13'),
(13, 11, '2010-04-14', 'Sales Quotation # 15'),
(13, 12, '2010-04-14', 'Sales Quotation # 14');

-- --------------------------------------------------------

--
-- Table structure for table `0_company`
--

CREATE TABLE IF NOT EXISTS `0_company` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_company`
--

INSERT INTO `0_company` (`coy_code`, `coy_name`, `gst_no`, `coy_no`, `tax_prd`, `tax_last`, `postal_address`, `phone`, `fax`, `email`, `coy_logo`, `domicile`, `curr_default`, `debtors_act`, `pyt_discount_act`, `creditors_act`, `bank_charge_act`, `exchange_diff_act`, `profit_loss_year_act`, `retained_earnings_act`, `freight_act`, `default_sales_act`, `default_sales_discount_act`, `default_prompt_payment_act`, `default_inventory_act`, `default_cogs_act`, `default_adj_act`, `default_inv_sales_act`, `default_assembly_act`, `payroll_act`, `allow_negative_stock`, `po_over_receive`, `po_over_charge`, `default_credit_limit`, `default_workorder_required`, `default_dim_required`, `past_due_days`, `use_dimension`, `f_year`, `no_item_list`, `no_customer_list`, `no_supplier_list`, `base_sales`, `foreign_codes`, `accumulate_shipping`, `legal_text`, `default_delivery_required`, `version_id`, `time_zone`, `add_pct`, `round_to`, `login_tout`) VALUES
(1, 'Training Telco Co.', '9876543', '123456789', 1, 1, 'Address 1\r\nAddress 2\r\nAddress 3', '(222) 111.222.333', '', 'delta@delta.com', 'etisalat..jpg', '', 'NGN', '1200', '5060', '2100', '5690', '4450', '9990', '3590', '4430', '4010', '4510', '4500', '1510', '5010', '5040', '4010', '1530', '5000', 0, 10, 10, 0, 20, 20, 30, 1, 3, 0, 0, 0, 1, 0, 0, '', 1, '2.2', 0, -1, 1, 600);

-- --------------------------------------------------------

--
-- Table structure for table `0_credit_status`
--

CREATE TABLE IF NOT EXISTS `0_credit_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason_description` char(100) NOT NULL DEFAULT '',
  `dissallow_invoices` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reason_description` (`reason_description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `0_credit_status`
--

INSERT INTO `0_credit_status` (`id`, `reason_description`, `dissallow_invoices`, `inactive`) VALUES
(1, 'Sales Order Blocked', 1, 0),
(2, 'Cash Customer', 0, 0),
(3, 'Credit Customer', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_currencies`
--

CREATE TABLE IF NOT EXISTS `0_currencies` (
  `currency` varchar(60) NOT NULL DEFAULT '',
  `curr_abrev` char(3) NOT NULL DEFAULT '',
  `curr_symbol` varchar(10) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `hundreds_name` varchar(15) NOT NULL DEFAULT '',
  `auto_update` tinyint(1) NOT NULL DEFAULT '1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`curr_abrev`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_currencies`
--

INSERT INTO `0_currencies` (`currency`, `curr_abrev`, `curr_symbol`, `country`, `hundreds_name`, `auto_update`, `inactive`) VALUES
('US Dollars', 'USD', '$', 'United States', 'Cents', 0, 0),
('Naira', 'NGN', '=N=', 'Nigeria', 'Kobo', 0, 0),
('Euro', 'EUR', '?', 'Europe', 'Cents', 0, 0),
('Pounds', 'GBP', '?', 'England', 'Pence', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_cust_allocations`
--

CREATE TABLE IF NOT EXISTS `0_cust_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amt` double unsigned DEFAULT NULL,
  `date_alloc` date NOT NULL DEFAULT '0000-00-00',
  `trans_no_from` int(11) DEFAULT NULL,
  `trans_type_from` int(11) DEFAULT NULL,
  `trans_no_to` int(11) DEFAULT NULL,
  `trans_type_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `0_cust_allocations`
--

INSERT INTO `0_cust_allocations` (`id`, `amt`, `date_alloc`, `trans_no_from`, `trans_type_from`, `trans_no_to`, `trans_type_to`) VALUES
(1, 630, '2010-02-22', 1, 12, 1, 10),
(2, 180, '2010-02-23', 2, 12, 2, 10),
(3, 270, '2010-02-23', 2, 12, 3, 10),
(4, 1000, '2010-02-23', 3, 12, 2, 10),
(5, 9000, '2010-02-24', 4, 12, 4, 10);

-- --------------------------------------------------------

--
-- Table structure for table `0_cust_branch`
--

CREATE TABLE IF NOT EXISTS `0_cust_branch` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `0_cust_branch`
--

INSERT INTO `0_cust_branch` (`branch_code`, `debtor_no`, `br_name`, `branch_ref`, `br_address`, `area`, `salesman`, `phone`, `phone2`, `fax`, `contact_name`, `email`, `default_location`, `tax_group_id`, `sales_account`, `sales_discount_account`, `receivables_account`, `payment_discount_account`, `default_ship_via`, `disable_trans`, `br_post_address`, `group_no`, `notes`, `inactive`) VALUES
(1, 'IB001', 'Ibadan Customer', 'IbadanCustomer', 'Lagos', 3, 2, '', '', '', 'Main Branch', '', 'IBW', 1, '', '4510', '1200', '4500', 1, 0, 'Lagos', 0, '', 0),
(2, 'L001', 'Lagos Customer Ben', 'Lagos Ben', 'Lagos', 3, 2, '23480999999', '23480999999', '', 'Main Branch', '', 'WH', 1, '4010', '4510', '1200', '4500', 1, 0, 'Lagos', 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_debtors_master`
--

CREATE TABLE IF NOT EXISTS `0_debtors_master` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_debtors_master`
--

INSERT INTO `0_debtors_master` (`debtor_no`, `name`, `debtor_ref`, `address`, `email`, `tax_id`, `curr_code`, `sales_type`, `dimension_id`, `dimension2_id`, `credit_status`, `payment_terms`, `discount`, `pymt_discount`, `credit_limit`, `notes`, `inactive`) VALUES
('IB001', 'Ibadan Customer', 'IbadanCustomer', 'Lagos', '', '', 'NGN', 1, 0, 0, 2, 1, 0, 0, 10000, '', 0),
('L001', 'Rilwan Lateef', 'Lagos Ben', 'Lagos,\r\nLagos Customer Ben', 'rlateef@bluechiptech.biz', '', 'NGN', 1, 0, 0, 2, 1, 0, 0, 2.2e+006, 'Lagos Customer Ben', 0),
('X110', 'Blue Ocean Telecom', 'Blue', 'Lagos', '', '', 'NGN', 1, 0, 0, 2, 1, 0, 0, 1e+006, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_debtors_terms_requests`
--

CREATE TABLE IF NOT EXISTS `0_debtors_terms_requests` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `0_debtors_terms_requests`
--

INSERT INTO `0_debtors_terms_requests` (`debtor_no`, `name`, `debtor_ref`, `address`, `email`, `tax_id`, `curr_code`, `sales_type`, `dimension_id`, `dimension2_id`, `credit_status`, `payment_terms`, `discount`, `pymt_discount`, `credit_limit`, `notes`, `inactive`, `request_id`, `requested_by`, `created_date`, `approved_by`, `approved_date`, `request_status`, `version`, `last_updated_by`, `last_updated_date`) VALUES
('X110', 'Blue Ocean Telecom', 'Blue', 'Lagos', '', '', 'NGN', 1, 0, 0, 2, 1, 0, 0, 1e+006, '', 0, 1, 'admin', '2010-02-23 06:23:50', 'admin', '2010-02-23 06:24:22', 'Confirmed', 0, 'admin', '2010-02-23 06:23:50'),
('L001', 'Lagos Customer Ben', 'Lagos Ben', 'Lagos', '', '', 'NGN', 1, 0, 0, 1, 1, 0, 0, 100000, '', 0, 2, 'admin', '2010-02-23 06:24:55', 'admin', '2010-02-23 06:25:08', 'Confirmed', 0, 'admin', '2010-02-23 06:24:55'),
('L001', 'Lagos Customer Ben', 'Lagos Ben', 'Lagos', '', '', 'NGN', 1, 0, 0, 2, 1, 0, 0, 100000, '', 0, 3, 'admin', '2010-02-23 07:45:14', 'admin', '2010-02-23 07:45:42', 'Confirmed', 0, 'admin', '2010-02-23 07:45:14'),
('L001', 'Lagos Customer Ben', 'Lagos Ben', 'Lagos', '', '', 'NGN', 1, 0, 0, 2, 1, 0, 0, 0, '', 0, 4, 'admin', '2010-02-23 08:27:24', 'admin', '2010-02-23 08:27:35', 'Confirmed', 0, 'admin', '2010-02-23 08:27:24'),
('L001', 'Lagos Customer Ben', 'Lagos Ben', 'Lagos', '', '', 'NGN', 1, 0, 0, 2, 1, 0, 0, 1e+006, '', 0, 5, 'admin', '2010-02-23 08:34:12', 'admin', '2010-02-23 08:34:25', 'Confirmed', 0, 'admin', '2010-02-23 08:34:12'),
('L001', 'Lagos Customer Ben', 'Lagos Ben', 'Lagos', '', '', 'NGN', 1, 0, 0, 2, 1, 0, 0, 2200, '', 0, 6, 'admin', '2010-02-23 17:40:05', 'admin', '2010-02-23 17:41:00', 'Confirmed', 1, 'admin', '2010-02-23 17:40:05'),
('L001', 'Lagos Customer Ben', 'Lagos Ben', 'Lagos', '', '', 'NGN', 1, 0, 0, 1, 1, 0, 0, 2200, '', 0, 7, 'admin', '2010-02-23 17:54:21', 'admin', '2010-02-23 17:54:33', 'Confirmed', 0, 'admin', '2010-02-23 17:54:21'),
('L001', 'Lagos Customer Ben', 'Lagos Ben', 'Lagos', '', '', 'NGN', 1, 0, 0, 2, 1, 0, 0, 2200, '', 0, 8, 'admin', '2010-02-23 17:57:16', 'admin', '2010-02-23 17:57:34', 'Confirmed', 0, 'admin', '2010-02-23 17:57:16'),
('IB001', 'Ibadan Customer', 'IbadanCustomer', 'Lagos', '', '', 'NGN', 1, 0, 0, 2, 1, 0, 0, 10000, '', 0, 9, 'admin', '2010-02-25 07:40:08', 'admin', '2010-03-09 16:16:41', 'Confirmed', 0, 'admin', '2010-02-25 07:40:08'),
('L001', 'Lagos Customer Ben', 'Lagos Ben', 'Lagos', '', '', 'NGN', 1, 0, 0, 2, 1, 0, 0, 2.2e+006, '', 0, 10, 'admin', '2010-03-31 13:47:36', 'admin', '2010-03-31 13:48:16', 'Confirmed', 1, 'admin', '2010-03-31 13:47:36');

-- --------------------------------------------------------

--
-- Table structure for table `0_debtor_trans`
--

CREATE TABLE IF NOT EXISTS `0_debtor_trans` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_debtor_trans`
--

INSERT INTO `0_debtor_trans` (`trans_no`, `type`, `version`, `debtor_no`, `branch_code`, `tran_date`, `due_date`, `reference`, `tpe`, `order_`, `ov_amount`, `ov_gst`, `ov_freight`, `ov_freight_tax`, `ov_discount`, `alloc`, `rate`, `ship_via`, `trans_link`, `dimension_id`, `dimension2_id`) VALUES
(1, 10, 0, 'L001', 2, '2010-02-22', '2010-02-22', '1', 1, 1, 630, 0, 0, 0, 0, 630, 1, 1, 1, 0, 0),
(1, 12, 0, 'L001', 2, '2010-02-22', '0000-00-00', '1', 0, 0, 630, 0, 0, 0, 0, 630, 1, 0, 0, 0, 0),
(1, 13, 1, 'L001', 2, '2010-02-22', '2010-02-22', '1', 1, 1, 630, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0),
(2, 10, 0, 'L001', 2, '2010-02-23', '2010-02-23', '2', 1, 4, 1350, 0, 0, 0, 0, 1180, 1, 1, 4, 0, 0),
(2, 12, 0, 'L001', 2, '2010-02-22', '0000-00-00', '2', 0, 0, 450, 0, 0, 0, 0, 450, 1, 0, 0, 0, 0),
(2, 13, 1, 'L001', 2, '2010-02-22', '2010-02-22', '2', 1, 2, 90, 0, 0, 0, 0, 0, 1, 1, 3, 0, 0),
(3, 10, 0, 'L001', 2, '2010-02-22', '2010-02-22', '3', 1, 2, 270, 0, 0, 0, 0, 270, 1, 1, 0, 0, 0),
(3, 12, 0, 'L001', 2, '2010-02-23', '0000-00-00', '3', 0, 0, 1000, 0, 0, 0, 0, 1000, 1, 0, 0, 0, 0),
(3, 13, 1, 'L001', 2, '2010-02-22', '2010-02-22', '3', 1, 2, 180, 0, 0, 0, 0, 0, 1, 1, 3, 0, 0),
(4, 10, 0, 'IB001', 1, '2010-02-24', '2010-02-24', '4', 1, 6, 9000, 0, 0, 0, 0, 9000, 1, 1, 5, 0, 0),
(4, 12, 0, 'IB001', 1, '2010-02-24', '0000-00-00', '4', 0, 0, 100000, 0, 0, 0, 0, 9000, 1, 0, 0, 0, 0),
(4, 13, 1, 'L001', 2, '2010-02-23', '2010-02-23', '4', 1, 4, 1350, 0, 0, 0, 0, 0, 1, 1, 2, 0, 0),
(5, 10, 0, 'IB001', 1, '2010-02-25', '2010-02-25', '5', 1, 7, 72000, 0, 0, 0, 0, 0, 1, 1, 6, 0, 0),
(5, 12, 0, 'IB001', 1, '2010-02-24', '0000-00-00', '5', 0, 0, 100000, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0),
(5, 13, 1, 'IB001', 1, '2010-02-24', '2010-02-24', '5', 1, 6, 9000, 0, 0, 0, 0, 0, 1, 1, 4, 0, 0),
(6, 10, 0, 'L001', 2, '2010-03-03', '2010-03-03', '6', 1, 8, 90, 0, 0, 0, 0, 0, 1, 1, 7, 0, 0),
(6, 12, 0, 'IB001', 1, '2010-02-24', '0000-00-00', '6', 0, 0, 100000, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0),
(6, 13, 1, 'IB001', 1, '2010-02-25', '2010-02-25', '6', 1, 7, 72000, 0, 0, 0, 0, 0, 1, 1, 5, 0, 0),
(7, 13, 1, 'L001', 2, '2010-03-03', '2010-02-25', '7', 1, 8, 90, 0, 0, 0, 0, 0, 1, 1, 6, 0, 0),
(8, 13, 0, 'L001', 2, '2010-03-25', '2010-02-23', '8', 1, 3, 90, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0),
(9, 13, 0, 'L001', 2, '2010-03-31', '2010-04-01', '9', 1, 12, 45000, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0),
(10, 13, 0, 'L001', 2, '2010-04-08', '2010-04-08', '10', 1, 15, 6000, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0),
(11, 13, 0, 'L001', 2, '2010-04-14', '2010-04-14', '11', 1, 17, 1000, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0),
(12, 13, 0, 'IB001', 1, '2010-04-14', '2010-04-08', '12', 1, 16, 7200, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_debtor_trans_details`
--

CREATE TABLE IF NOT EXISTS `0_debtor_trans_details` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `0_debtor_trans_details`
--

INSERT INTO `0_debtor_trans_details` (`id`, `debtor_trans_no`, `debtor_trans_type`, `stock_id`, `description`, `unit_price`, `unit_tax`, `quantity`, `discount_percent`, `standard_cost`, `qty_done`) VALUES
(1, 1, 13, 'N100A', 'N100 Airtime Card', 90, 4.2857, 7, 0, 84, 7),
(2, 1, 10, 'N100A', 'N100 Airtime Card', 90, 4.2857, 7, 0, 84, 0),
(3, 2, 13, 'N100A', 'N100 Airtime Card', 90, 4.2857, 1, 0, 84, 1),
(4, 3, 13, 'N100A', 'N100 Airtime Card', 90, 4.2857, 2, 0, 84, 2),
(5, 4, 13, 'N100A', 'N100 Airtime Card', 90, 4.2857, 15, 0, 84, 15),
(6, 2, 10, 'N100A', 'N100 Airtime Card', 90, 4.2857, 15, 0, 84, 0),
(7, 3, 10, 'N100A', 'N100 Airtime Card', 90, 4.2857, 1, 0, 84, 0),
(8, 3, 10, 'N100A', 'N100 Airtime Card', 90, 4.2857, 2, 0, 84, 0),
(9, 5, 13, 'N100A', 'N100 Airtime Card', 90, 4.2857, 100, 0, 84, 100),
(10, 4, 10, 'N100A', 'N100 Airtime Card', 90, 4.2857, 100, 0, 84, 0),
(11, 6, 13, 'N100A', 'N100 Airtime Card', 90, 4.2857, 800, 0, 84, 800),
(12, 5, 10, 'N100A', 'N100 Airtime Card', 90, 4.2857, 800, 0, 84, 0),
(13, 7, 13, 'N100A', 'N100 Airtime Card', 90, 4.2857, 1, 0, 84, 1),
(14, 6, 10, 'N100A', 'N100 Airtime Card', 90, 4.2857, 1, 0, 84, 0),
(15, 8, 13, 'N100A', 'N100 Airtime Card', 90, 4.2857, 1, 0, 84, 0),
(16, 9, 13, 'N100A', 'N100 Airtime Card', 90, 4.2857, 500, 0, 84, 0),
(17, 10, 13, 'MOBILEKIT', 'Ready To-Go Mobile Phone &amp; Kits', 1200, 57.1429, 5, 0, 980, 0),
(18, 11, 13, 'N100A', 'N100 Airtime Card', 100, 4.7619, 10, 0, 84, 0),
(19, 12, 13, 'MOBILEKIT', 'Ready To-Go Mobile Phone &amp; Kits', 1200, 57.1429, 6, 0, 980, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_dimensions`
--

CREATE TABLE IF NOT EXISTS `0_dimensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `type_` tinyint(1) NOT NULL DEFAULT '1',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `0_dimensions`
--

INSERT INTO `0_dimensions` (`id`, `reference`, `name`, `type_`, `closed`, `date_`, `due_date`) VALUES
(1, '1', 'Test Dimension', 1, 0, '2010-02-22', '2010-03-14'),
(2, '1000.0000.0000', 'Cost Center for Papa', 1, 0, '2010-02-27', '2010-03-19');

-- --------------------------------------------------------

--
-- Table structure for table `0_exchange_rates`
--

CREATE TABLE IF NOT EXISTS `0_exchange_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curr_code` char(3) NOT NULL DEFAULT '',
  `rate_buy` double NOT NULL DEFAULT '0',
  `rate_sell` double NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `curr_code` (`curr_code`,`date_`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `0_exchange_rates`
--

INSERT INTO `0_exchange_rates` (`id`, `curr_code`, `rate_buy`, `rate_sell`, `date_`) VALUES
(7, 'EUR', 187, 187, '2010-02-22'),
(6, 'GBP', 250, 250, '2010-02-22'),
(5, 'USD', 150, 150, '2010-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `0_fiscal_year`
--

CREATE TABLE IF NOT EXISTS `0_fiscal_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `begin` date DEFAULT '0000-00-00',
  `end` date DEFAULT '0000-00-00',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `0_fiscal_year`
--

INSERT INTO `0_fiscal_year` (`id`, `begin`, `end`, `closed`) VALUES
(1, '2008-01-01', '2008-12-31', 1),
(2, '2009-01-01', '2009-12-31', 0),
(3, '2010-01-01', '2010-12-31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_gl_trans`
--

CREATE TABLE IF NOT EXISTS `0_gl_trans` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=82 ;

--
-- Dumping data for table `0_gl_trans`
--

INSERT INTO `0_gl_trans` (`counter`, `type`, `type_no`, `tran_date`, `account`, `memo_`, `amount`, `dimension_id`, `dimension2_id`, `person_type_id`, `person_id`) VALUES
(1, 20, 1, '2010-02-22', '2100', '', -44100, 0, 0, 3, 0x31),
(2, 20, 1, '2010-02-22', '1510', '', 42000, 1, 0, 3, 0x31),
(3, 20, 1, '2010-02-22', '2150', '', 2100, 0, 0, 3, 0x31),
(4, 22, 1, '2010-02-22', '2100', '', 44100, 0, 0, 3, 0x31),
(5, 22, 1, '2010-02-22', '1065', '', -44100, 0, 0, 3, 0x31),
(6, 2, 1, '2010-02-22', '3350', 'Fund Petty Cash Account', -50000, 0, 0, 0, 0x46756e64696e67204163636f756e74),
(7, 2, 1, '2010-02-22', '1065', '', 50000, 0, 0, 0, 0x46756e64696e67204163636f756e74),
(8, 12, 1, '2010-02-22', '1065', '', 630, 0, 0, 2, 0x4c303031),
(9, 12, 1, '2010-02-22', '1200', '', -630, 0, 0, 2, 0x4c303031),
(10, 13, 1, '2010-02-22', '5010', '', 588, 1, 0, 2, 0x4c303031),
(11, 13, 1, '2010-02-22', '1510', '', -588, 0, 0, 2, 0x4c303031),
(12, 10, 1, '2010-02-22', '4010', '', -600, 1, 0, 2, 0x4c303031),
(13, 10, 1, '2010-02-22', '1200', '', 630, 0, 0, 2, 0x4c303031),
(14, 10, 1, '2010-02-22', '2150', '', -30, 0, 0, 2, 0x4c303031),
(15, 12, 2, '2010-02-22', '1065', '', 450, 0, 0, 2, 0x4c303031),
(16, 12, 2, '2010-02-22', '1200', '', -450, 0, 0, 2, 0x4c303031),
(17, 13, 2, '2010-02-22', '5010', '', 0, 1, 0, 2, 0x4c303031),
(18, 13, 2, '2010-02-22', '1510', '', 0, 0, 0, 2, 0x4c303031),
(19, 13, 2, '2010-02-22', '5010', '', 0, 1, 0, 2, 0x4c303031),
(20, 13, 2, '2010-02-22', '1510', '', 0, 0, 0, 2, 0x4c303031),
(21, 13, 3, '2010-02-22', '5010', '', 168, 1, 0, 2, 0x4c303031),
(22, 13, 3, '2010-02-22', '1510', '', -168, 0, 0, 2, 0x4c303031),
(23, 13, 2, '2010-02-22', '5010', '', 84, 1, 0, 2, 0x4c303031),
(24, 13, 2, '2010-02-22', '1510', '', -84, 0, 0, 2, 0x4c303031),
(25, 13, 4, '2010-02-23', '5010', '', 1260, 1, 0, 2, 0x4c303031),
(26, 13, 4, '2010-02-23', '1510', '', -1260, 0, 0, 2, 0x4c303031),
(27, 10, 2, '2010-02-23', '4010', '', -1285.71, 1, 0, 2, 0x4c303031),
(28, 10, 2, '2010-02-23', '1200', '', 1350, 0, 0, 2, 0x4c303031),
(29, 10, 2, '2010-02-23', '2150', '', -64.29, 0, 0, 2, 0x4c303031),
(30, 10, 3, '2010-02-22', '4010', '', -85.71, 1, 0, 2, 0x4c303031),
(31, 10, 3, '2010-02-22', '4010', '', -171.43, 1, 0, 2, 0x4c303031),
(32, 10, 3, '2010-02-22', '1200', '', 270, 0, 0, 2, 0x4c303031),
(33, 10, 3, '2010-02-22', '2150', '', -12.86, 0, 0, 2, 0x4c303031),
(34, 12, 3, '2010-02-23', '1060', '', 1000, 0, 0, 2, 0x4c303031),
(35, 12, 3, '2010-02-23', '1200', '', -1000, 0, 0, 2, 0x4c303031),
(36, 17, 1, '2010-02-24', '5040', '', -840000, 1, 0, NULL, NULL),
(37, 17, 1, '2010-02-24', '1510', '', 840000, 0, 0, NULL, NULL),
(38, 12, 4, '2010-02-24', '1060', '', 100000, 0, 0, 2, 0x4942303031),
(39, 12, 4, '2010-02-24', '1200', '', -100000, 0, 0, 2, 0x4942303031),
(40, 12, 5, '2010-02-24', '1060', '', 100000, 0, 0, 2, 0x4942303031),
(41, 12, 5, '2010-02-24', '1200', '', -100000, 0, 0, 2, 0x4942303031),
(42, 12, 6, '2010-02-24', '1060', '', 100000, 0, 0, 2, 0x4942303031),
(43, 12, 6, '2010-02-24', '1200', '', -100000, 0, 0, 2, 0x4942303031),
(44, 13, 5, '2010-02-24', '5010', '', 8400, 1, 0, 2, 0x4942303031),
(45, 13, 5, '2010-02-24', '1510', '', -8400, 0, 0, 2, 0x4942303031),
(46, 10, 4, '2010-02-24', '4010', '', -8571.43, 1, 0, 2, 0x4942303031),
(47, 10, 4, '2010-02-24', '1200', '', 9000, 0, 0, 2, 0x4942303031),
(48, 10, 4, '2010-02-24', '2150', '', -428.57, 0, 0, 2, 0x4942303031),
(49, 13, 6, '2010-02-25', '5010', '', 67200, 1, 0, 2, 0x4942303031),
(50, 13, 6, '2010-02-25', '1510', '', -67200, 0, 0, 2, 0x4942303031),
(51, 10, 5, '2010-02-25', '4010', '', -68571.44, 1, 0, 2, 0x4942303031),
(52, 10, 5, '2010-02-25', '1200', '', 72000, 0, 0, 2, 0x4942303031),
(53, 10, 5, '2010-02-25', '2150', '', -3428.57, 0, 0, 2, 0x4942303031),
(54, 10, 5, '2010-02-25', '4450', '', 0.01, 0, 0, 2, 0x4942303031),
(55, 13, 7, '2010-03-03', '5010', '', 84, 1, 0, 2, 0x4c303031),
(56, 13, 7, '2010-03-03', '1510', '', -84, 0, 0, 2, 0x4c303031),
(57, 10, 6, '2010-03-03', '4010', '', -85.71, 1, 0, 2, 0x4c303031),
(58, 10, 6, '2010-03-03', '1200', '', 90, 0, 0, 2, 0x4c303031),
(59, 10, 6, '2010-03-03', '2150', '', -4.29, 0, 0, 2, 0x4c303031),
(60, 13, 8, '2010-03-25', '5010', '', 84, 1, 0, 2, 0x4c303031),
(61, 13, 8, '2010-03-25', '1510', '', -84, 0, 0, 2, 0x4c303031),
(62, 13, 9, '2010-03-31', '5010', '', 42000, 1, 0, 2, 0x4c303031),
(63, 13, 9, '2010-03-31', '1510', '', -42000, 0, 0, 2, 0x4c303031),
(64, 20, 2, '2010-04-07', '2100', '', -926100, 0, 0, 3, 0x31),
(65, 20, 2, '2010-04-07', '1510', '', 42000, 1, 0, 3, 0x31),
(66, 20, 2, '2010-04-07', '1510', '', 420000, 1, 0, 3, 0x31),
(67, 20, 2, '2010-04-07', '1510', '', 84000, 1, 0, 3, 0x31),
(68, 20, 2, '2010-04-07', '1510', '', 84000, 1, 0, 3, 0x31),
(69, 20, 2, '2010-04-07', '1510', '', 42000, 1, 0, 3, 0x31),
(70, 20, 2, '2010-04-07', '1510', '', 42000, 1, 0, 3, 0x31),
(71, 20, 2, '2010-04-07', '1510', '', 42000, 1, 0, 3, 0x31),
(72, 20, 2, '2010-04-07', '1510', '', 42000, 1, 0, 3, 0x31),
(73, 20, 2, '2010-04-07', '1510', '', 42000, 1, 0, 3, 0x31),
(74, 20, 2, '2010-04-07', '1510', '', 42000, 1, 0, 3, 0x31),
(75, 20, 2, '2010-04-07', '2150', '', 44100, 0, 0, 3, 0x31),
(76, 13, 10, '2010-04-08', '5010', '', 4900, 0, 0, 2, 0x4c303031),
(77, 13, 10, '2010-04-08', '1510', '', -4900, 0, 0, 2, 0x4c303031),
(78, 13, 11, '2010-04-14', '5010', '', 840, 1, 0, 2, 0x4c303031),
(79, 13, 11, '2010-04-14', '1510', '', -840, 0, 0, 2, 0x4c303031),
(80, 13, 12, '2010-04-14', '5010', '', 5880, 0, 0, 2, 0x4942303031),
(81, 13, 12, '2010-04-14', '1510', '', -5880, 0, 0, 2, 0x4942303031);

-- --------------------------------------------------------

--
-- Table structure for table `0_grn_batch`
--

CREATE TABLE IF NOT EXISTS `0_grn_batch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `purch_order_no` int(11) DEFAULT NULL,
  `reference` varchar(60) NOT NULL DEFAULT '',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `loc_code` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `0_grn_batch`
--

INSERT INTO `0_grn_batch` (`id`, `supplier_id`, `purch_order_no`, `reference`, `delivery_date`, `loc_code`) VALUES
(1, 1, 1, '1', '2010-02-22', 'ARR'),
(2, 1, 34, '2', '2010-03-30', 'DEF'),
(3, 1, 35, '3', '2010-03-30', 'DEF'),
(4, 1, 36, '4', '2010-03-30', 'DEF'),
(5, 1, 37, '5', '2010-03-30', 'DEF'),
(6, 1, 38, '6', '2010-03-30', 'DEF'),
(7, 1, 39, '7', '2010-03-30', 'DEF'),
(8, 1, 40, '8', '2010-04-02', 'DEF'),
(9, 1, 41, '9', '2010-04-02', 'DEF'),
(10, 1, 42, '10', '2010-04-02', 'DEF'),
(11, 1, 44, '11', '2010-04-07', 'DEF'),
(12, 1, 45, '12', '2010-04-07', 'DEF'),
(13, 1, 46, '13', '2010-04-08', 'DEF'),
(14, 1, 48, '14', '2010-04-14', 'DEF'),
(15, 1, 47, '15', '2010-04-14', 'DEF');

-- --------------------------------------------------------

--
-- Table structure for table `0_grn_items`
--

CREATE TABLE IF NOT EXISTS `0_grn_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grn_batch_id` int(11) DEFAULT NULL,
  `po_detail_item` int(11) NOT NULL DEFAULT '0',
  `item_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `qty_recd` double NOT NULL DEFAULT '0',
  `quantity_inv` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `0_grn_items`
--

INSERT INTO `0_grn_items` (`id`, `grn_batch_id`, `po_detail_item`, `item_code`, `description`, `qty_recd`, `quantity_inv`) VALUES
(1, 1, 1, 'N100A', 'N100A', 500, 500),
(2, 2, 34, 'N100A', 'N100A', 500, 500),
(3, 3, 35, 'N100A', 'N100A', 500, 500),
(4, 4, 36, 'N100A', 'N100A', 500, 500),
(5, 5, 37, 'N100A', 'N100A', 500, 500),
(6, 6, 38, 'N100A', 'N100A', 500, 500),
(7, 7, 39, 'N100A', 'N100A', 500, 500),
(8, 8, 40, 'N100A', 'N100A', 1000, 1000),
(9, 9, 41, 'N100A', 'N100A', 1000, 1000),
(10, 10, 42, 'N100A', 'N100A', 5000, 5000),
(11, 11, 44, 'N100A', 'N100A', 500, 500),
(12, 12, 45, 'N100A', 'N100A', 500, 0),
(13, 13, 46, 'MOBILEKIT', 'Ready To-Go Mobile Phone &amp; Kits', 10, 0),
(14, 14, 48, 'N100A', 'N100A', 500, 0),
(15, 15, 47, 'N100A', 'N100A', 500, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_groups`
--

CREATE TABLE IF NOT EXISTS `0_groups` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `0_groups`
--

INSERT INTO `0_groups` (`id`, `description`, `inactive`) VALUES
(1, 'Small', 0),
(2, 'Medium', 0),
(3, 'Large', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_item_codes`
--

CREATE TABLE IF NOT EXISTS `0_item_codes` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `0_item_codes`
--

INSERT INTO `0_item_codes` (`id`, `item_code`, `stock_id`, `description`, `category_id`, `quantity`, `is_foreign`, `inactive`) VALUES
(1, 'N100A', 'N100A', 'N100 Airtime Card', 1, 1, 0, 0),
(2, 'M100A', 'M100A', 'Manufactured N100 Airtime Card', 1, 1, 0, 0),
(3, 'N100B', 'N100A', 'N 100 Box', 1, 500, 0, 0),
(4, 'MOBILEKIT', 'MOBILEKIT', 'Ready To-Go Mobile Phone &amp; Kits', 2, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_item_tax_types`
--

CREATE TABLE IF NOT EXISTS `0_item_tax_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `exempt` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `0_item_tax_types`
--

INSERT INTO `0_item_tax_types` (`id`, `name`, `exempt`, `inactive`) VALUES
(1, 'VAT Allowed', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_item_tax_type_exemptions`
--

CREATE TABLE IF NOT EXISTS `0_item_tax_type_exemptions` (
  `item_tax_type_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_tax_type_id`,`tax_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_item_tax_type_exemptions`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_item_units`
--

CREATE TABLE IF NOT EXISTS `0_item_units` (
  `abbr` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `decimals` int(2) NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`abbr`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_item_units`
--

INSERT INTO `0_item_units` (`abbr`, `name`, `decimals`, `inactive`) VALUES
('ea.', 'Each', 0, 0),
('hrs', 'Hours', 1, 0),
('bx.', 'Box', 500, 0),
('bk.', 'Brick', 100, 0),
('cd.', 'Sim Card', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_item_units_conversion`
--

CREATE TABLE IF NOT EXISTS `0_item_units_conversion` (
  `name` varchar(15) NOT NULL,
  `description` varchar(40) NOT NULL,
  `abbr1` varchar(20) NOT NULL,
  `abbr2` varchar(20) NOT NULL,
  `decimals` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_item_units_conversion`
--

INSERT INTO `0_item_units_conversion` (`name`, `description`, `abbr1`, `abbr2`, `decimals`) VALUES
('brick', 'card to brick', 'cd.', 'bk', 100),
('box', 'card to box', 'cd.', 'bx.', 500);

-- --------------------------------------------------------

--
-- Table structure for table `0_locations`
--

CREATE TABLE IF NOT EXISTS `0_locations` (
  `loc_code` varchar(5) NOT NULL DEFAULT '',
  `location_name` varchar(60) NOT NULL DEFAULT '',
  `delivery_address` tinytext NOT NULL,
  `phone` varchar(30) NOT NULL DEFAULT '',
  `phone2` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `location_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`loc_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_locations`
--

INSERT INTO `0_locations` (`loc_code`, `location_name`, `delivery_address`, `phone`, `phone2`, `fax`, `email`, `contact`, `inactive`, `location_type`) VALUES
('QC', 'Quality Location', '', '', '', '', '', '', 0, NULL),
('WH', 'Central Warehouse', '', '', '', '', '', '', 0, 'WH'),
('IBW', 'Ibadan Warehouse', '', '', '', '', '', '', 0, 'WH'),
('DEF', 'Default', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '', '', '', '', '', 0, 'ARR');

-- --------------------------------------------------------

--
-- Table structure for table `0_location_type`
--

CREATE TABLE IF NOT EXISTS `0_location_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_type` varchar(10) NOT NULL,
  `Description` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `0_location_type`
--

INSERT INTO `0_location_type` (`id`, `cod_type`, `Description`) VALUES
(1, 'ARR', 'ARRIVAL'),
(2, 'WH', 'WAREHOUSE');

-- --------------------------------------------------------

--
-- Table structure for table `0_loc_stock`
--

CREATE TABLE IF NOT EXISTS `0_loc_stock` (
  `loc_code` char(5) NOT NULL DEFAULT '',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `reorder_level` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loc_code`,`stock_id`),
  KEY `stock_id` (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_loc_stock`
--

INSERT INTO `0_loc_stock` (`loc_code`, `stock_id`, `reorder_level`) VALUES
('ARR', 'M100A', 0),
('ARR', 'N100A', 0),
('DEF', '102', 0),
('DEF', '103', 0),
('DEF', '104', 0),
('DEF', '201', 0),
('DEF', '3400', 0),
('DEF', 'MOBILEKIT', 0),
('DEF', 'N100A', 0),
('IBW', 'M100A', 0),
('IBW', 'MOBILEKIT', 0),
('IBW', 'N100A', 0),
('QC', 'M100A', 0),
('QC', 'MOBILEKIT', 0),
('QC', 'N100A', 0),
('WH', 'M100A', 0),
('WH', 'MOBILEKIT', 0),
('WH', 'N100A', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_movement_types`
--

CREATE TABLE IF NOT EXISTS `0_movement_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `0_movement_types`
--

INSERT INTO `0_movement_types` (`id`, `name`, `inactive`) VALUES
(1, 'Adjustment', 0),
(2, 'Location Change', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_ourrefs`
--

CREATE TABLE IF NOT EXISTS `0_ourrefs` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_ourrefs`
--

INSERT INTO `0_ourrefs` (`id`, `type`, `reference`) VALUES
(1, 1001, 'xyz'),
(2, 1001, '232'),
(3, 1001, '1232'),
(4, 1001, '2323'),
(5, 1001, '232323'),
(6, 1001, 'Test'),
(7, 1001, 'erwe'),
(8, 1001, '432332'),
(10, 1001, '23132'),
(11, 1001, '32232.werew'),
(12, 1001, '232.2342'),
(13, 1001, '123.34.R'),
(14, 1001, '12.12'),
(15, 1001, '32423'),
(16, 1001, '123x');

-- --------------------------------------------------------

--
-- Table structure for table `0_oursys_types`
--

CREATE TABLE IF NOT EXISTS `0_oursys_types` (
  `type_id` smallint(6) NOT NULL DEFAULT '0',
  `type_no` int(11) NOT NULL DEFAULT '1',
  `next_reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_oursys_types`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_params`
--

CREATE TABLE IF NOT EXISTS `0_params` (
  `type` varchar(30) NOT NULL DEFAULT 'SYSTEM',
  `name` varchar(30) NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT '0',
  `num_value` decimal(10,0) DEFAULT NULL,
  `num_value2` decimal(10,0) DEFAULT NULL,
  `num_value3` decimal(10,0) DEFAULT NULL,
  `num_value4` decimal(10,0) DEFAULT NULL,
  `str_value` varchar(128) DEFAULT NULL,
  `str_value2` varchar(128) DEFAULT NULL,
  `str_value3` varchar(128) DEFAULT NULL,
  `str_value4` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_params`
--

INSERT INTO `0_params` (`type`, `name`, `description`, `inactive`, `num_value`, `num_value2`, `num_value3`, `num_value4`, `str_value`, `str_value2`, `str_value3`, `str_value4`) VALUES
('ST_PURCHREQ', 'APPROVAL_MAX_PR', 'Number of approvals for PR: System Max is 3', 0, '3', '0', '0', '0', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `0_payment_terms`
--

CREATE TABLE IF NOT EXISTS `0_payment_terms` (
  `terms_indicator` int(11) NOT NULL AUTO_INCREMENT,
  `terms` char(80) NOT NULL DEFAULT '',
  `days_before_due` smallint(6) NOT NULL DEFAULT '0',
  `day_in_following_month` smallint(6) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`terms_indicator`),
  UNIQUE KEY `terms` (`terms`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `0_payment_terms`
--

INSERT INTO `0_payment_terms` (`terms_indicator`, `terms`, `days_before_due`, `day_in_following_month`, `inactive`) VALUES
(1, 'Due Immediately After Invoice', 0, 0, 0),
(2, 'Due in 14 days', 14, 0, 0),
(3, 'Due in Next Month', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_pay_advice`
--

CREATE TABLE IF NOT EXISTS `0_pay_advice` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `0_pay_advice`
--

INSERT INTO `0_pay_advice` (`id`, `type`, `debtor_no`, `branch_id`, `order_no`, `bank_act`, `bank_branch`, `ref`, `trans_date`, `amount`, `dimension_id`, `dimension2_id`, `person_type_id`, `person_id`, `reconciled`, `created_by`, `created_date`, `note`, `confirmed_by`, `confirmed_date`, `request_status`, `version`) VALUES
(1, 12, 'L001', '2', 1, '2', 'Office', 'Office', '2010-02-22', 630, 0, 0, 0, NULL, NULL, 'admin', '2010-02-22', 'Direct Sales Payment', 'admin', '2010-02-22', 'ConfirmedPosted', 0),
(2, 12, 'L001', '2', 2, '2', 'Office', 'Office', '2010-02-22', 450, 0, 0, 0, NULL, NULL, 'admin', '2010-02-22', 'tESTING ', 'admin', '2010-02-22', 'ConfirmedPosted', 0),
(3, 12, 'L001', '2', 5, '1', 'Yaba', '3432423', '2010-02-23', 1000, 0, 0, 0, NULL, NULL, 'admin', '2010-02-23', 'Testing some pay request', 'admin', '2010-02-23', 'ConfirmedPosted', 0),
(4, 12, 'IB001', '1', 6, '1', 'Ikorodu', '10001223', '2010-02-24', 100000, 0, 0, 0, NULL, NULL, 'admin', '2010-02-24', '', 'admin', '2010-03-09', 'Planned', 0),
(5, 12, 'IB001', '1', 7, '1', 'Lekki', '555', '2010-02-25', 80000, 0, 0, 0, NULL, NULL, 'admin', '2010-02-25', '', 'admin', '2010-03-09', 'Planned', 0),
(6, 12, 'L001', '2', 10, '1', 'Yaba', '343433', '2010-03-09', 42000, 0, 0, 0, NULL, NULL, 'admin', '2010-03-09', '', 'admin', '2010-03-09', 'Planned', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_prices`
--

CREATE TABLE IF NOT EXISTS `0_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `sales_type_id` int(11) NOT NULL DEFAULT '0',
  `curr_abrev` char(3) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `price` (`stock_id`,`sales_type_id`,`curr_abrev`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `0_prices`
--

INSERT INTO `0_prices` (`id`, `stock_id`, `sales_type_id`, `curr_abrev`, `price`) VALUES
(1, 'N100A', 1, 'NGN', 90),
(2, 'N100A', 2, 'NGN', 100),
(3, 'M100A', 1, 'NGN', 100),
(4, 'N100B', 1, 'NGN', 42000),
(5, 'MOBILEKIT', 1, 'NGN', 1200);

-- --------------------------------------------------------

--
-- Table structure for table `0_printers`
--

CREATE TABLE IF NOT EXISTS `0_printers` (
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

--
-- Dumping data for table `0_printers`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_print_profiles`
--

CREATE TABLE IF NOT EXISTS `0_print_profiles` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `profile` varchar(30) NOT NULL,
  `report` varchar(5) DEFAULT NULL,
  `printer` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile` (`profile`,`report`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `0_print_profiles`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_purch_data`
--

CREATE TABLE IF NOT EXISTS `0_purch_data` (
  `supplier_id` varchar(64) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  `suppliers_uom` char(50) NOT NULL DEFAULT '',
  `conversion_factor` double NOT NULL DEFAULT '1',
  `supplier_description` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`supplier_id`,`stock_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_purch_data`
--

INSERT INTO `0_purch_data` (`supplier_id`, `stock_id`, `price`, `suppliers_uom`, `conversion_factor`, `supplier_description`) VALUES
('1', 'N100A', 42000, 'Box', 500, 'N100A'),
('N100A', 'N100A', 42000, 'Box', 500, 'N100A'),
('1', 'MOBILEKIT', 980, 'ea', 1, 'Ready To-Go Mobile Phone &amp; Kits');

-- --------------------------------------------------------

--
-- Table structure for table `0_purch_orders`
--

CREATE TABLE IF NOT EXISTS `0_purch_orders` (
  `order_no` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` varchar(64) NOT NULL DEFAULT '0',
  `comments` tinytext,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` tinytext NOT NULL,
  `requisition_no` tinytext,
  `into_stock_location` varchar(5) NOT NULL DEFAULT '',
  `delivery_address` tinytext NOT NULL,
  `created_by` varchar(64) NOT NULL,
  `created_date` datetime NOT NULL,
  `last_updated_by` varchar(64) NOT NULL,
  `last_updated_date` datetime NOT NULL,
  `confirmed_by` varchar(64) NOT NULL,
  `confirmed_date` datetime NOT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'Planned',
  PRIMARY KEY (`order_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `0_purch_orders`
--

INSERT INTO `0_purch_orders` (`order_no`, `supplier_id`, `comments`, `ord_date`, `reference`, `requisition_no`, `into_stock_location`, `delivery_address`, `created_by`, `created_date`, `last_updated_by`, `last_updated_date`, `confirmed_by`, `confirmed_date`, `status`) VALUES
(1, '1', 'First Order on the system', '2010-02-22', '1', '', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(2, '0', '', '2010-02-23', '2', '', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(3, '0', '', '2010-02-24', '3', 'weqw', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(4, '0', '', '2010-02-24', '4', 'weqw', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(5, 'N100A', '', '2010-02-24', '5', '', 'ARR', 'Lagos', '1267010647', '2010-02-24 12:24:07', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(6, '1', '', '2010-02-23', '6', '', 'ARR', 'Lagos', '1267011414', '2010-02-24 12:36:54', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(7, '1', '', '2010-02-23', '232', '2', 'ARR', 'Lagos', '1267014392', '2010-02-24 13:26:32', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(8, '1', '', '2010-02-23', '2320000', 'PR:2/Ref:', 'ARR', 'Lagos', '1267014546', '2010-02-24 13:29:07', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(9, '1', '', '2010-02-24', '8', '', 'ARR', 'Lagos', '1267014919', '2010-02-24 13:35:19', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(10, '1', '', '2010-02-24', '9', 'testing', 'ARR', 'Lagos', '1267015510', '2010-02-24 13:45:10', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(11, '1', '', '2010-02-23', '10', 'PR:2/Ref:', 'ARR', 'Lagos', '1267015893', '2010-02-24 13:51:33', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(12, 'N100A', '', '2010-02-23', '11', 'PR:4/Ref:34213', 'ARR', 'Lagos', '1267081776', '2010-02-25 08:09:36', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(13, 'N100A', '', '2010-02-24', '12', 'PR:5/Ref:sdfdsdfds', 'ARR', 'Lagos', '1267082349', '2010-02-25 08:19:09', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(14, 'N100A', '', '2010-02-24', '13', 'PR:5/Ref:sdfdsdfds', 'ARR', 'Lagos', '1267082398', '2010-02-25 08:19:58', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(15, 'N100A', '', '2010-02-24', '14', 'PR:5/Ref:sdfdsdfds', 'ARR', 'Lagos', '1267082649', '2010-02-25 08:24:09', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(16, '1', '', '2010-02-24', '15', 'PR:5/Ref:sdfdsdfds', 'ARR', 'Lagos', '1267083269', '2010-02-25 08:34:30', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(17, '1', '', '2010-02-23', '16', 'PR:2/Ref:', 'ARR', 'Lagos', '1267102043', '2010-02-25 13:47:23', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(18, '1', '', '2010-02-23', '17', 'PR:2/Ref:', 'ARR', 'Lagos', '1267102101', '2010-02-25 13:48:21', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(19, '1', '', '2010-02-23', '18', 'PR:2/Ref:', 'ARR', 'Lagos', '1267104365', '2010-02-25 14:26:05', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(20, '1', '', '2010-02-23', '19', 'PR:2/Ref:', 'ARR', 'Lagos', '1267105432', '2010-02-25 14:43:52', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(21, '1', '', '2010-02-23', '20', 'PR:2/Ref:', 'ARR', 'Lagos', '1267105522', '2010-02-25 14:45:22', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(22, 'N100A', '', '2010-02-24', '21', 'PR:5/Ref:sdfdsdfds', 'ARR', 'Lagos', '1267114237', '2010-02-25 17:10:37', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(23, 'N100A', '', '2010-02-24', '22', 'PR:6/Ref:', 'ARR', 'Lagos', '1267114553', '2010-02-25 17:15:54', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(24, 'N100A', '', '2010-02-24', '23', 'PR:7/Ref:weqw', 'ARR', 'Lagos', '1267241221', '2010-02-27 04:27:01', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(25, 'N100A', '', '2010-02-23', '24', 'PR:1/Ref:123', 'ARR', '1232', '1267241292', '2010-02-27 04:28:12', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(26, 'N100A', '', '2010-03-02', '25', '', 'ARR', 'Lagos', '1267520724', '2010-03-02 10:05:24', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(27, 'N100A', '', '2010-03-02', '26', '', 'ARR', 'Lagos', '1267521156', '2010-03-02 10:12:36', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(28, 'N100A', '', '2010-03-02', '27', '', 'ARR', 'w', '1267521426', '2010-03-02 10:17:06', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(29, 'N100A', '', '2010-02-27', '28', 'PR:8/Ref:423', 'ARR', 'Lagos', '1267545452', '2010-03-02 16:57:32', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(30, '1', '', '2010-03-04', '29', 'PR:12/Ref:', 'ARR', 'Lagos', '1267738868', '2010-03-04 22:41:08', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(31, 'N100A', '', '2010-03-04', '30', 'PR:12/Ref:', 'ARR', 'Lagos', '1267790968', '2010-03-05 13:09:29', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(32, 'N100A', '', '2010-03-04', '31', 'PR:12/Ref:', 'ARR', 'Lagos', '1268132898', '2010-03-09 12:08:18', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(33, 'N100A', '', '2010-03-04', '32', 'PR:12/Ref:', 'ARR', 'Lagos', '1268140856', '2010-03-09 14:20:56', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(34, '1', '', '2010-03-30', '33', '', 'DEF', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(35, '1', '', '2010-03-30', '34', '', 'DEF', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '1269958987', '2010-03-30 15:23:07', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(36, '1', '', '2010-03-30', '35', '', 'DEF', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '1269959593', '2010-03-30 15:33:13', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(37, '1', '', '2010-03-30', '36', '', 'DEF', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '1269962073', '2010-03-30 16:14:33', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(38, '1', '', '2010-03-30', '37', '', 'DEF', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '1269962138', '2010-03-30 16:15:38', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(39, '1', '', '2010-03-30', '38', '', 'DEF', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(40, '1', '', '2010-04-02', '39', '', 'DEF', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '1270196171', '2010-04-02 09:16:11', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(41, '1', '', '2010-04-02', '40', '', 'DEF', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '1270196276', '2010-04-02 09:17:56', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(42, '1', '', '2010-04-02', '41', '', 'DEF', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '1270196437', '2010-04-02 09:20:37', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(44, '1', '', '2010-04-07', '43', '', 'DEF', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '1270657551', '2010-04-07 17:25:51', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(45, '1', '', '2010-04-07', '44', '', 'DEF', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '1270663593', '2010-04-07 19:06:33', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(46, '1', '', '2010-04-08', '45', '', 'DEF', 'Delivery 1\r\nDelivery 2\r\nDelivery 3', '1270710035', '2010-04-08 08:00:35', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(47, '1', '', '2010-04-07', '46', 'PR:15/Ref:3432', 'DEF', 'Lagos', '1271232894', '2010-04-14 09:14:54', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned'),
(48, '1', '', '2010-04-07', '47', 'PR:15/Ref:3432', 'DEF', 'Lagos', '1271232905', '2010-04-14 09:15:05', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'Planned');

-- --------------------------------------------------------

--
-- Table structure for table `0_purch_order_details`
--

CREATE TABLE IF NOT EXISTS `0_purch_order_details` (
  `po_detail_item` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL DEFAULT '0',
  `item_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `costcentre` varchar(64) NOT NULL DEFAULT '-simplex',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `qty_invoiced` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `act_price` double NOT NULL DEFAULT '0',
  `std_cost_unit` double NOT NULL DEFAULT '0',
  `quantity_ordered` double NOT NULL DEFAULT '0',
  `quantity_received` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`po_detail_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `0_purch_order_details`
--

INSERT INTO `0_purch_order_details` (`po_detail_item`, `order_no`, `item_code`, `description`, `costcentre`, `delivery_date`, `qty_invoiced`, `unit_price`, `act_price`, `std_cost_unit`, `quantity_ordered`, `quantity_received`) VALUES
(1, 1, 'N100A', 'N100 Airtime Card', '-simplex', '2010-03-04', 500, 84, 84, 84, 500, 500),
(2, 2, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(3, 3, 'N100A', 'N100A', '-simplex', '2010-03-06', 0, 84, 0, 0, 500, 0),
(4, 4, 'N100A', 'N100A', '-simplex', '2010-03-06', 0, 84, 0, 0, 500, 0),
(5, 5, 'N100A', 'N100A', '-simplex', '2010-03-06', 0, 84, 0, 0, 500, 0),
(6, 6, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(7, 7, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(8, 8, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(9, 9, 'N100A', 'N100 Airtime Card', '-simplex', '2010-03-06', 0, 84, 0, 0, 500, 0),
(10, 10, 'N100A', 'N100 Airtime Card', '-simplex', '2010-03-06', 0, 84, 0, 0, 500, 0),
(11, 11, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(12, 12, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(13, 13, 'N100A', 'N100A', '-simplex', '2010-03-06', 0, 0, 0, 0, 200, 0),
(14, 14, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(15, 15, 'N100A', 'N100A', '-simplex', '2010-03-06', 0, 0, 0, 0, 200, 0),
(16, 16, 'N100A', 'N100A', '-simplex', '2010-03-06', 0, 84, 0, 0, 500, 0),
(17, 17, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(18, 18, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(19, 19, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(20, 20, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(21, 21, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(22, 22, 'N100A', 'N100A', '-simplex', '2010-03-06', 0, 0, 0, 0, 200, 0),
(23, 23, 'N100A', 'N100A', '-simplex', '2010-03-06', 0, 84, 0, 0, 500, 0),
(24, 24, 'N100A', 'N100A', '-simplex', '2010-03-06', 0, 84, 0, 0, 500, 0),
(25, 25, 'N100A', 'N100A', '-simplex', '2010-03-05', 0, 0, 0, 0, 500, 0),
(26, 26, 'N100A', 'N100 Airtime Card', '', '2010-03-12', 0, 84, 0, 0, 500, 0),
(27, 27, 'N100A', 'N100 Airtime Card', '1000.000.001', '2010-03-12', 0, 84, 0, 0, 500, 0),
(28, 28, 'N100A', 'N100 Airtime Card', '1000.000.002', '2010-03-12', 0, 84, 0, 0, 500, 0),
(29, 29, 'N100A', 'N100A', '1000.000.001', '2010-03-09', 0, 84, 0, 0, 500, 0),
(30, 30, 'N100A', 'N100A', '1000.000.002', '2010-03-14', 0, 84, 0, 0, 500, 0),
(31, 31, 'N100A', 'N100A', '1000.000.001', '2010-03-14', 0, 84, 0, 0, 1500, 0),
(32, 32, 'N100A', 'N100A', '1000.000.001', '2010-03-14', 0, 84, 0, 0, 1500, 0),
(33, 33, 'N100A', 'N100A', '1000.000.001', '2010-03-14', 0, 84, 0, 0, 1500, 0),
(34, 34, 'N100A', 'N100 Airtime Card', '-simplex', '2010-04-09', 500, 84, 84, 84, 500, 500),
(35, 35, 'N100A', 'N100 Airtime Card', '', '2010-04-09', 500, 84, 84, 84, 500, 500),
(36, 36, 'N100A', 'N100 Airtime Card', '1000.000.001', '2010-04-09', 500, 84, 84, 84, 500, 500),
(37, 37, 'N100A', 'N100 Airtime Card', '', '2010-04-09', 500, 84, 84, 84, 500, 500),
(38, 38, 'N100A', 'N100 Airtime Card', '1000.000.001', '2010-04-09', 500, 84, 84, 84, 500, 500),
(39, 39, 'N100A', 'N100 Airtime Card', '-simplex', '2010-04-09', 500, 84, 84, 84, 500, 500),
(40, 40, 'N100A', 'N100 Airtime Card', '', '2010-04-12', 1000, 84, 84, 84, 1000, 1000),
(41, 41, 'N100A', 'N100 Airtime Card', '', '2010-04-12', 1000, 84, 84, 84, 1000, 1000),
(42, 42, 'N100A', 'N100 Airtime Card', '1000.000.001', '2010-04-12', 5000, 84, 84, 84, 5000, 5000),
(44, 44, 'N100A', 'N100 Airtime Card', '1000.000.000', '2010-04-17', 500, 84, 84, 84, 500, 500),
(45, 45, 'N100A', 'N100 Airtime Card', '1000.000.000', '2010-04-17', 0, 84, 84, 84, 500, 500),
(46, 46, 'MOBILEKIT', 'Ready To-Go Mobile Phone &amp; Kits', '1000.000.000', '2010-04-18', 0, 980, 980, 980, 10, 10),
(47, 47, 'N100A', 'N100A', '1000.000.001', '2010-04-17', 0, 84, 84, 84, 500, 500),
(48, 48, 'N100A', 'N100A', '1000.000.001', '2010-04-17', 0, 84, 84, 84, 500, 500);

-- --------------------------------------------------------

--
-- Table structure for table `0_purch_reqs`
--

CREATE TABLE IF NOT EXISTS `0_purch_reqs` (
  `pr_no` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` varchar(64) NOT NULL DEFAULT '0',
  `comments` tinytext,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` tinytext NOT NULL,
  `requisition_no` tinytext,
  `into_stock_location` varchar(5) NOT NULL DEFAULT '',
  `delivery_address` tinytext NOT NULL,
  `created_by` varchar(64) NOT NULL,
  `created_date` datetime NOT NULL,
  `no_authoriser` int(10) unsigned NOT NULL DEFAULT '3',
  `approved_by1` varchar(64) NOT NULL,
  `approved_date1` datetime NOT NULL,
  `approver_status1` varchar(30) NOT NULL DEFAULT 'Pending',
  `approved_by2` varchar(64) DEFAULT NULL,
  `approved_date2` datetime NOT NULL,
  `approver_status2` varchar(30) NOT NULL DEFAULT 'Pending',
  `approved_by3` varchar(64) DEFAULT NULL,
  `approved_date3` datetime NOT NULL,
  `approver_status3` varchar(30) NOT NULL DEFAULT 'Pending',
  `status` varchar(45) NOT NULL DEFAULT 'Planned',
  `last_updated_by` varchar(64) NOT NULL,
  `last_updated_date` datetime NOT NULL,
  PRIMARY KEY (`pr_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=17 ;

--
-- Dumping data for table `0_purch_reqs`
--

INSERT INTO `0_purch_reqs` (`pr_no`, `supplier_id`, `comments`, `ord_date`, `reference`, `requisition_no`, `into_stock_location`, `delivery_address`, `created_by`, `created_date`, `no_authoriser`, `approved_by1`, `approved_date1`, `approver_status1`, `approved_by2`, `approved_date2`, `approver_status2`, `approved_by3`, `approved_date3`, `approver_status3`, `status`, `last_updated_by`, `last_updated_date`) VALUES
(1, 'N100A', '', '2010-02-23', 'xyz', '123', 'ARR', '1232', '', '0000-00-00 00:00:00', 3, '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', 'OrderedClosed', '', '0000-00-00 00:00:00'),
(2, '1', '', '2010-02-23', '232', '', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', 3, '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', 'OrderedClosed', '', '0000-00-00 00:00:00'),
(3, 'N100A', '', '2010-02-23', '1232', '', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', 3, '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', 'Planned', '', '0000-00-00 00:00:00'),
(4, 'N100A', '', '2010-02-23', '2323', '34213', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', 3, 'admin', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', 'Planned', '', '0000-00-00 00:00:00'),
(5, 'N100A', '', '2010-02-24', '232323', 'sdfdsdfds', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', 3, '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', 'OrderedClosed', '', '0000-00-00 00:00:00'),
(6, 'N100A', '', '2010-02-24', 'Test', '', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', 3, 'admin', '0000-00-00 00:00:00', 'Approved', 'admin', '0000-00-00 00:00:00', 'Approved', 'sola', '0000-00-00 00:00:00', 'Approved', 'Pending', '', '0000-00-00 00:00:00'),
(7, 'N100A', '', '2010-02-24', 'erwe', 'weqw', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', 3, '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', 'OrderedClosed', '', '0000-00-00 00:00:00'),
(8, 'N100A', '', '2010-02-27', '432332', '423', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', 3, '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', 'Pending', '', '0000-00-00 00:00:00'),
(10, 'N100A', '', '2010-03-02', '23132', '', 'ARR', '21312321', '', '0000-00-00 00:00:00', 3, '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', 'Planned', '', '0000-00-00 00:00:00'),
(11, '1', '', '2010-03-03', '32232.werew', '', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', 3, 'admin', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', '', '0000-00-00 00:00:00', 'Pending', 'Planned', '', '0000-00-00 00:00:00'),
(12, 'N100A', '', '2010-03-04', '232.2342', '', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', 3, 'admin', '0000-00-00 00:00:00', 'Approved', 'admin', '0000-00-00 00:00:00', 'Approved', 'admin', '0000-00-00 00:00:00', 'Approved', 'Approved', '', '0000-00-00 00:00:00'),
(13, 'N100A', '', '2010-03-05', '123.34.R', '', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', 3, 'sola', '0000-00-00 00:00:00', 'Approved', 'admin', '0000-00-00 00:00:00', 'Approved', 'sola', '0000-00-00 00:00:00', 'Approved', 'Pending', '', '0000-00-00 00:00:00'),
(14, 'N100A', '', '2010-03-09', '12.12', '', 'ARR', 'Lagos', '', '0000-00-00 00:00:00', 3, 'admin', '0000-00-00 00:00:00', 'Pending', NULL, '0000-00-00 00:00:00', 'Pending', NULL, '0000-00-00 00:00:00', 'Pending', 'Planned', '', '0000-00-00 00:00:00'),
(15, '1', '', '2010-04-07', '32423', '3432', 'WH', 'Lagos', '', '0000-00-00 00:00:00', 3, 'admin', '0000-00-00 00:00:00', 'Approved', 'admin', '0000-00-00 00:00:00', 'Approved', 'admin', '0000-00-00 00:00:00', 'Approved', 'Approved', '', '0000-00-00 00:00:00'),
(16, '1', '', '2010-04-14', '123x', '', 'WH', 'Lagos', '', '0000-00-00 00:00:00', 3, 'admin', '0000-00-00 00:00:00', 'Approved', 'admin', '0000-00-00 00:00:00', 'Approved', 'admin', '0000-00-00 00:00:00', 'Approved', 'Approved', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `0_purch_req_details`
--

CREATE TABLE IF NOT EXISTS `0_purch_req_details` (
  `pr_detail_item` int(11) NOT NULL AUTO_INCREMENT,
  `pr_no` int(11) NOT NULL DEFAULT '0',
  `item_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `costcentre` varchar(64) NOT NULL DEFAULT '-simplex',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `qty_invoiced` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `act_price` double NOT NULL DEFAULT '0',
  `std_cost_unit` double NOT NULL DEFAULT '0',
  `quantity_ordered` double NOT NULL DEFAULT '0',
  `quantity_received` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`pr_detail_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `0_purch_req_details`
--

INSERT INTO `0_purch_req_details` (`pr_detail_item`, `pr_no`, `item_code`, `description`, `costcentre`, `delivery_date`, `qty_invoiced`, `unit_price`, `act_price`, `std_cost_unit`, `quantity_ordered`, `quantity_received`) VALUES
(1, 1, 'N100A', 'N100 Airtime Card', '-simplex', '2010-03-05', 0, 0, 0, 0, 500, 0),
(2, 2, 'N100A', 'N100 Airtime Card', '-simplex', '2010-03-05', 0, 84, 0, 0, 500, 0),
(3, 3, 'N100A', 'N100 Airtime Card', '1000.000.002', '2010-03-05', 0, 0, 0, 0, 500, 0),
(4, 4, 'N100A', 'N100 Airtime Card', '1000.000.002', '2010-03-05', 0, 0, 0, 0, 1, 0),
(5, 5, 'N100A', 'N100 Airtime Card', '1000.000.000', '2010-03-06', 0, 0, 0, 0, 200, 0),
(6, 6, 'N100A', 'N100 Airtime Card', '1000.000.003', '2010-03-06', 0, 84, 0, 0, 500, 0),
(7, 7, 'N100A', 'N100 Airtime Card', '1000.000.002', '2010-03-06', 0, 84, 0, 0, 500, 0),
(8, 8, 'N100A', 'N100 Airtime Card', '1000.000.001', '2010-03-09', 0, 84, 0, 0, 500, 0),
(9, 9, 'N100A', 'N100 Airtime Card', '1000.000.002', '2010-03-12', 0, 84, 0, 0, 500, 0),
(10, 10, 'N100A', 'N100 Airtime Card', '1000.000.002', '2010-03-12', 0, 84, 0, 0, 500, 0),
(11, 11, 'N100A', 'N100A', '1000.000.001', '2010-03-13', 0, 84, 0, 0, 500, 0),
(12, 12, 'N100A', 'N100A', '1000.000.001', '2010-03-14', 0, 84, 0, 0, 1500, 0),
(13, 13, 'N100A', 'N100 Airtime Card', '1000.000.002', '2010-03-15', 0, 84, 0, 0, 500, 0),
(14, 14, 'N100A', 'N100 Airtime Card', '', '2010-03-19', 0, 84, 0, 0, 500, 0),
(15, 15, 'N100A', 'N100 Airtime Card', '1000.000.001', '2010-04-17', 0, 84, 0, 0, 500, 0),
(16, 16, 'N100A', 'N100A', '1000.000.000', '2010-04-24', 0, 84, 0, 0, 500, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_quick_entries`
--

CREATE TABLE IF NOT EXISTS `0_quick_entries` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(60) NOT NULL,
  `base_amount` double NOT NULL DEFAULT '0',
  `base_desc` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `0_quick_entries`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_quick_entry_lines`
--

CREATE TABLE IF NOT EXISTS `0_quick_entry_lines` (
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

--
-- Dumping data for table `0_quick_entry_lines`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_recurrent_invoices`
--

CREATE TABLE IF NOT EXISTS `0_recurrent_invoices` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `order_no` int(11) unsigned NOT NULL,
  `debtor_no` varchar(64) DEFAULT NULL,
  `group_no` smallint(6) unsigned DEFAULT NULL,
  `days` int(11) NOT NULL DEFAULT '0',
  `monthly` int(11) NOT NULL DEFAULT '0',
  `begin` date NOT NULL DEFAULT '0000-00-00',
  `end` date NOT NULL DEFAULT '0000-00-00',
  `last_sent` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Dumping data for table `0_recurrent_invoices`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_refs`
--

CREATE TABLE IF NOT EXISTS `0_refs` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_refs`
--

INSERT INTO `0_refs` (`id`, `type`, `reference`) VALUES
(1, 2, '1'),
(1, 10, '1'),
(1, 12, '1'),
(1, 13, '1'),
(1, 16, '1'),
(1, 17, '1'),
(1, 18, '1'),
(1, 20, '1'),
(1, 22, '1'),
(1, 25, '1'),
(1, 30, '1'),
(1, 32, '1'),
(1, 40, '1'),
(2, 10, '2'),
(2, 12, '2'),
(2, 13, '2'),
(2, 18, '2'),
(2, 20, '2'),
(2, 25, '2'),
(2, 30, '2'),
(2, 32, '2'),
(2, 40, '1000.0000.0000'),
(3, 10, '3'),
(3, 12, '3'),
(3, 13, '3'),
(3, 18, '3'),
(3, 25, '3'),
(3, 30, '3'),
(3, 32, '3'),
(4, 10, '4'),
(4, 12, '4'),
(4, 13, '4'),
(4, 18, '4'),
(4, 25, '4'),
(4, 30, '4'),
(4, 32, '4'),
(5, 10, '5'),
(5, 12, '5'),
(5, 13, '5'),
(5, 18, '5'),
(5, 25, '5'),
(5, 30, '5'),
(5, 32, '5'),
(6, 10, '6'),
(6, 12, '6'),
(6, 13, '6'),
(6, 18, '6'),
(6, 25, '6'),
(6, 30, '6'),
(6, 32, '6'),
(7, 13, '7'),
(7, 18, '7'),
(7, 25, '7'),
(7, 30, '7'),
(7, 32, '7'),
(8, 13, '8'),
(8, 18, '8'),
(8, 25, '8'),
(8, 30, '8'),
(8, 32, '8'),
(9, 13, '9'),
(9, 18, '8'),
(9, 25, '9'),
(9, 30, '9'),
(9, 32, '9'),
(10, 13, '10'),
(10, 18, '9'),
(10, 25, '10'),
(10, 30, '10'),
(10, 32, '10'),
(11, 13, '11'),
(11, 18, '10'),
(11, 25, '11'),
(11, 30, '11'),
(11, 32, '11'),
(12, 13, '12'),
(12, 18, '11'),
(12, 25, '12'),
(12, 30, '12'),
(12, 32, '12'),
(13, 18, '12'),
(13, 25, '13'),
(13, 30, '13'),
(13, 32, '13'),
(14, 18, '13'),
(14, 25, '14'),
(14, 30, '14'),
(14, 32, '14'),
(15, 18, '14'),
(15, 25, '15'),
(15, 30, '15'),
(15, 32, '15'),
(16, 18, '15'),
(16, 30, '16'),
(16, 32, '16'),
(17, 18, '16'),
(17, 30, '17'),
(17, 32, '17'),
(18, 18, '17'),
(19, 18, '18'),
(20, 18, '19'),
(21, 18, '20'),
(22, 18, '21'),
(23, 18, '22'),
(24, 18, '23'),
(25, 18, '24'),
(26, 18, '25'),
(27, 18, '26'),
(28, 18, '27'),
(29, 18, '28'),
(30, 18, '29'),
(31, 18, '30'),
(32, 18, '31'),
(33, 18, '32'),
(34, 18, '33'),
(35, 18, '34'),
(36, 18, '35'),
(37, 18, '36'),
(38, 18, '37'),
(39, 18, '38'),
(40, 18, '39'),
(41, 18, '40'),
(42, 18, '41'),
(43, 18, '42'),
(44, 18, '43'),
(45, 18, '44'),
(46, 18, '45'),
(47, 18, '46'),
(48, 18, '47');

-- --------------------------------------------------------

--
-- Table structure for table `0_salesman`
--

CREATE TABLE IF NOT EXISTS `0_salesman` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `0_salesman`
--

INSERT INTO `0_salesman` (`salesman_code`, `salesman_name`, `salesman_phone`, `salesman_fax`, `salesman_email`, `provision`, `break_pt`, `provision2`, `inactive`) VALUES
(1, 'Muyiwa Ola', '', '', '', 0, 0, 0, 0),
(2, 'Kola Kolapo', '', '', '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sales_orders`
--

CREATE TABLE IF NOT EXISTS `0_sales_orders` (
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
  `direction` int(11) DEFAULT NULL,
  PRIMARY KEY (`trans_type`,`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_sales_orders`
--

INSERT INTO `0_sales_orders` (`order_no`, `trans_type`, `version`, `type`, `debtor_no`, `branch_code`, `reference`, `customer_ref`, `comments`, `ord_date`, `order_type`, `ship_via`, `delivery_address`, `contact_phone`, `contact_email`, `deliver_to`, `freight_cost`, `from_stk_loc`, `delivery_date`, `ourorder_status`, `direction`) VALUES
(1, 30, 1, 0, 'L001', 2, '1', '/SQ1', 'Sales Quotation # 1', '2010-02-22', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-22', 'Confirmed', NULL),
(2, 30, 5, 0, 'L001', 2, '2', '/SQ2', 'Sales Quotation # 2', '2010-02-22', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-22', 'Confirmed', NULL),
(3, 30, 1, 0, 'L001', 2, '3', '/SQ3', 'Sales Quotation # 3', '2010-02-23', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-23', 'Confirmed', NULL),
(4, 30, 1, 0, 'L001', 2, '4', '/SQ5', 'Sales Quotation # 5', '2010-02-23', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-23', 'Confirmed', NULL),
(5, 30, 0, 0, 'L001', 2, '5', '/SQ6', 'Sales Quotation # 6', '2010-02-23', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-23', 'Planned', NULL),
(6, 30, 1, 0, 'IB001', 1, '6', '/SQ8', 'Sales Quotation # 8', '2010-02-24', 1, 1, 'Lagos', '', '', 'Ibadan Customer', 0, 'IBW', '2010-02-24', 'Confirmed', NULL),
(7, 30, 1, 0, 'IB001', 1, '7', '/SQ9', 'Sales Quotation # 9', '2010-02-25', 1, 1, 'Lagos', '', '', 'Ibadan Customer', 0, 'IBW', '2010-02-25', 'Confirmed', NULL),
(8, 30, 1, 0, 'L001', 2, '8', '/SQ7', 'Sales Quotation # 7', '2010-02-25', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-25', 'Confirmed', NULL),
(9, 30, 0, 0, 'L001', 2, '9', '/SQ7', 'Sales Quotation # 7', '2010-02-25', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-25', 'Cancelled', NULL),
(10, 30, 0, 0, 'L001', 2, '10', '/SQ10', 'Sales Quotation # 10', '2010-03-09', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-03-09', 'Cancelled', NULL),
(11, 30, 0, 0, 'L001', 2, '11', '', '', '2010-03-31', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-03-31', 'Cancelled', NULL),
(12, 30, 1, 0, 'L001', 2, '12', '', '', '2010-03-31', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-04-01', 'Confirmed', NULL),
(13, 30, 0, 0, 'L001', 2, '13', '/SQ11', 'Sales Quotation # 11', '2010-03-31', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-03-31', 'Confirmed', NULL),
(14, 30, 0, 0, 'L001', 2, '14', '/SQ12', 'Sales Quotation # 12', '2010-04-07', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-04-07', 'Confirmed', NULL),
(15, 30, 1, 0, 'L001', 2, '15', '/SQ13', 'Sales Quotation # 13', '2010-04-08', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-04-08', 'Confirmed', NULL),
(16, 30, 1, 0, 'IB001', 1, '16', '/SQ14', 'Sales Quotation # 14', '2010-04-08', 1, 1, 'Lagos', '', '', 'Ibadan Customer', 0, 'WH', '2010-04-08', 'Confirmed', NULL),
(17, 30, 1, 0, 'L001', 2, '17', '/SQ15', 'Sales Quotation # 15', '2010-04-14', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-04-14', 'Confirmed', NULL),
(1, 32, 0, 0, 'L001', 2, '1', '', '', '2010-02-22', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-23', 'OrderedClosed', NULL),
(2, 32, 0, 0, 'L001', 2, '2', '', '', '2010-02-22', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-23', 'OrderedClosed', NULL),
(3, 32, 0, 0, 'L001', 2, '3', '', '', '2010-02-23', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-24', 'OrderedClosed', NULL),
(4, 32, 0, 0, 'L001', 2, '4', '', '', '2010-02-23', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-23', 'Planned', NULL),
(5, 32, 0, 0, 'L001', 2, '5', '', '', '2010-02-23', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-23', 'OrderedClosed', NULL),
(6, 32, 0, 0, 'L001', 2, '6', '', '', '2010-02-23', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-24', 'OrderedClosed', NULL),
(7, 32, 0, 0, 'L001', 2, '7', '', '', '2010-02-24', 1, 1, 'Lagos', '', '', 'Lagos Customer Ben', 0, 'WH', '2010-02-25', 'OrderedClosed', NULL),
(8, 32, 0, 0, 'IB001', 1, '8', '', '', '2010-02-24', 1, 1, 'Lagos', '', '', 'Ibadan Customer', 0, 'IBW', '2010-02-25', 'OrderedClosed', NULL),
(9, 32, 0, 0, 'IB001', 1, '9', '', '', '2010-02-25', 1, 1, 'Lagos', '', '', 'Ibadan Customer', 0, 'IBW', '2010-02-26', 'OrderedClosed', NULL),
(10, 32, 0, 0, 'L001', 2, '10', '', '', '2010-03-09', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-03-10', 'OrderedClosed', NULL),
(11, 32, 0, 0, 'L001', 2, '11', '', '', '2010-03-31', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-04-01', 'OrderedClosed', NULL),
(12, 32, 0, 0, 'L001', 2, '12', '', '', '2010-04-07', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-04-08', 'OrderedClosed', NULL),
(13, 32, 0, 0, 'L001', 2, '13', '', '', '2010-04-08', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-04-09', 'OrderedClosed', NULL),
(14, 32, 0, 0, 'IB001', 1, '14', '', '', '2010-04-08', 1, 1, 'Lagos', '', '', 'Ibadan Customer', 0, 'WH', '2010-04-09', 'OrderedClosed', NULL),
(15, 32, 0, 0, 'L001', 2, '15', '', '', '2010-04-14', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-04-15', 'OrderedClosed', NULL),
(16, 32, 1, 0, 'L001', 2, '16', '', '', '2010-04-15', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-04-16', 'Planned', NULL),
(17, 32, 0, 0, 'L001', 2, '17', '', '', '2010-04-15', 1, 1, 'Lagos', '23480999999', '', 'Lagos Customer Ben', 0, 'WH', '2010-04-16', 'Planned', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `0_sales_order_details`
--

CREATE TABLE IF NOT EXISTS `0_sales_order_details` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `0_sales_order_details`
--

INSERT INTO `0_sales_order_details` (`id`, `order_no`, `trans_type`, `stk_code`, `description`, `qty_sent`, `unit_price`, `quantity`, `discount_percent`) VALUES
(1, 1, 32, 'N100A', 'N100 Airtime Card', 0, 90, 7, 0),
(2, 1, 30, 'N100A', 'N100 Airtime Card', 7, 90, 7, 0),
(3, 2, 32, 'N100A', 'N100 Airtime Card', 0, 90, 5, 0),
(5, 2, 30, 'N100A', 'N100 Airtime Card', 3, 90, 5, 0),
(6, 3, 32, 'N100A', 'N100 Airtime Card', 0, 90, 1, 0),
(7, 3, 30, 'N100A', 'N100 Airtime Card', 1, 90, 1, 0),
(8, 4, 32, 'N100A', 'N100 Airtime Card', 0, 90, 5, 0),
(9, 5, 32, 'N100A', 'N100 Airtime Card', 0, 90, 15, 0),
(10, 4, 30, 'N100A', 'N100 Airtime Card', 15, 90, 15, 0),
(11, 6, 32, 'N100A', 'N100 Airtime Card', 0, 90, 90, 0),
(12, 5, 30, 'N100A', 'N100 Airtime Card', 0, 90, 90, 0),
(13, 7, 32, 'N100A', 'N100 Airtime Card', 0, 90, 1, 0),
(14, 8, 32, 'N100A', 'N100 Airtime Card', 0, 90, 100, 0),
(15, 6, 30, 'N100A', 'N100 Airtime Card', 100, 90, 100, 0),
(16, 9, 32, 'N100A', 'N100 Airtime Card', 0, 90, 1000, 0),
(17, 7, 30, 'N100A', 'N100 Airtime Card', 800, 90, 1000, 0),
(18, 8, 30, 'N100A', 'N100 Airtime Card', 1, 90, 1, 0),
(19, 9, 30, 'N100A', 'N100 Airtime Card', 0, 90, 1, 0),
(20, 10, 32, 'N100A', 'N100 Airtime Card', 0, 84, 500, 0),
(21, 10, 30, 'N100A', 'N100 Airtime Card', 0, 84, 500, 0),
(22, 11, 30, 'N100A', 'N100 Airtime Card', 0, 90, 1, 0),
(23, 12, 30, 'N100A', 'N100 Airtime Card', 500, 90, 500, 0),
(24, 11, 32, 'N100A', 'N100 Airtime Card', 0, 90, 500, 0),
(25, 13, 30, 'N100A', 'N100 Airtime Card', 0, 90, 500, 0),
(26, 12, 32, 'N100A', 'N100 Airtime Card', 0, 90, 1, 0),
(27, 14, 30, 'N100A', 'N100 Airtime Card', 0, 90, 1, 0),
(28, 13, 32, 'MOBILEKIT', 'Ready To-Go Mobile Phone &amp; Kits', 0, 1200, 5, 0),
(29, 15, 30, 'MOBILEKIT', 'Ready To-Go Mobile Phone &amp; Kits', 5, 1200, 5, 0),
(30, 14, 32, 'MOBILEKIT', 'Ready To-Go Mobile Phone &amp; Kits', 0, 1200, 6, 0),
(31, 16, 30, 'MOBILEKIT', 'Ready To-Go Mobile Phone &amp; Kits', 6, 1200, 6, 0),
(32, 15, 32, 'N100A', 'N100 Airtime Card', 0, 100, 10, 0),
(33, 17, 30, 'N100A', 'N100 Airtime Card', 10, 100, 10, 0),
(35, 16, 32, 'N100A', 'N100 Airtime Card', 0, 90, 1, 0),
(36, 17, 32, 'N100A', 'N100 Airtime Card', 0, 90, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sales_pos`
--

CREATE TABLE IF NOT EXISTS `0_sales_pos` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pos_name` varchar(30) NOT NULL,
  `cash_sale` tinyint(1) NOT NULL,
  `credit_sale` tinyint(1) NOT NULL,
  `pos_location` varchar(5) NOT NULL,
  `pos_account` smallint(6) unsigned NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_name` (`pos_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `0_sales_pos`
--

INSERT INTO `0_sales_pos` (`id`, `pos_name`, `cash_sale`, `credit_sale`, `pos_location`, `pos_account`, `inactive`) VALUES
(1, 'Default', 1, 1, 'DEF', 4, 0),
(2, 'POS 1', 1, 0, 'DEF', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sales_types`
--

CREATE TABLE IF NOT EXISTS `0_sales_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_type` char(50) NOT NULL DEFAULT '',
  `tax_included` int(1) NOT NULL DEFAULT '0',
  `factor` double NOT NULL DEFAULT '1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_type` (`sales_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `0_sales_types`
--

INSERT INTO `0_sales_types` (`id`, `sales_type`, `tax_included`, `factor`, `inactive`) VALUES
(1, 'Wholesale', 1, 1, 0),
(2, 'Retailsale', 1, 1.1111, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_security_roles`
--

CREATE TABLE IF NOT EXISTS `0_security_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(30) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `sections` text,
  `areas` text,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role` (`role`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `0_security_roles`
--

INSERT INTO `0_security_roles` (`id`, `role`, `description`, `sections`, `areas`, `inactive`) VALUES
(1, 'Inquiries', 'Inquiries', '768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15872;16128', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;524;525;773;774;2822;3073;3075;3076;3077;3329;3330;3331;3332;3333;3334;3335;5377;5633;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8450;8451;10497;10753;11009;11010;11012;13313;13315;15617;15618;15619;15620;15621;15622;15623;15624;15625;15626;15873;15882;16129;16130;16131;16132', 0),
(2, 'System Administrator', 'System Administrator', '256;512;768;2816;3072;3328;5376;5632;5888;7936;8192;8448;10496;10752;11008;13056;13312;15616;15872;16128', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;524;525;769;770;771;772;773;774;2817;2818;2819;2820;2821;2822;2823;3073;3074;3082;3085;3075;3083;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5642;5635;5636;5637;5641;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8195;8196;8197;8449;8450;8451;10497;10753;10754;10755;10756;10757;11009;11010;11012;13057;13313;13314;13315;15617;15618;15619;15620;15621;15622;15623;15624;15625;15626;15627;15873;15874;15875;15876;15877;15878;15879;15880;15883;15881;15882;16129;16130;16131;16132', 0),
(3, 'Salesman', 'Salesman', '768;3072;5632;8192;15872', '773;774;3073;3075;3081;5633;8194;15873', 0),
(4, 'Stock Manager', 'Stock Manager', '2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15872;16128', '2818;2822;3073;3076;3077;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5640;5889;5890;5891;8193;8194;8450;8451;10753;11009;11010;11012;13313;13315;15882;16129;16130;16131;16132', 0),
(5, 'Production Manager', 'Production Manager', '512;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5640;5640;5889;5890;5891;8193;8194;8196;8197;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15876;15877;15880;15882;16129;16130;16131;16132', 0),
(6, 'Purchase Officer', 'Purchase Officer', '512;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5377;5633;5635;5640;5640;5889;5890;5891;8193;8194;8196;8197;8449;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15876;15877;15880;15882;16129;16130;16131;16132', 0),
(7, 'AR Officer', 'AR Officer', '512;768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;771;773;774;2818;2819;2820;2821;2822;2823;3073;3073;3074;3075;3076;3077;3078;3079;3080;3081;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5633;5634;5637;5638;5639;5640;5640;5889;5890;5891;8193;8194;8194;8196;8197;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15873;15876;15877;15878;15880;15882;16129;16130;16131;16132', 0),
(8, 'AP Officer', 'AP Officer', '512;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;769;770;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3082;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5635;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15876;15877;15880;15882;16129;16130;16131;16132', 0),
(9, 'Accountant', 'New Accountant', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13313;13315;15617;15618;15619;15620;15621;15624;15873;15876;15877;15878;15880;15882;16129;16130;16131;16132', 0),
(10, 'Sub Admin', 'Sub Admin', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3082;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15873;15874;15876;15877;15878;15879;15880;15882;16129;16130;16131;16132', 0),
(11, 'Demo', 'New Accountant', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;515;516;519;520;521;522;523;524;771;772;773;774;2817;2818;2819;2820;2821;2822;2823;3073;3074;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15618;15619;15620;15621;15624;15873;15874;15875;15876;15877;15878;15880;15883;15881;15882;16129;16130;16131;16132', 0),
(12, 'POSAgent', 'POS Agent', '5632', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;524;525;769;770;771;772;773;774;2817;2818;2819;2820;2821;2822;2823;3073;3074;3082;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5641;5635;5889;5890;5891;7937;7938;7939;7940;8193;8194;8195;8196;8197;8449;8450;8451;10497;10753;10754;10755;10756;10757;11009;11010;11012;13057;13313;13314;13315;15617;15618;15619;15620;15621;15622;15623;15624;15625;15626;15627;15873;15874;15875;15876;15877;15878;15879;15880;15883;15881;15882;16129;16130;16131;16132', 0),
(13, 'Proc Officer', 'Procurement Officer', '512;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;769;770;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5642;5635;5636;5637;5641;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15876;15877;15880;15882;16129;16130;16131;16132', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_serialized_stock`
--

CREATE TABLE IF NOT EXISTS `0_serialized_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transtype` varchar(20) DEFAULT NULL,
  `stock_id` char(20) NOT NULL,
  `location_code` char(5) NOT NULL,
  `qty` bigint(20) NOT NULL,
  `batch_no` varchar(20) DEFAULT NULL,
  `box_no` varchar(20) NOT NULL DEFAULT '0',
  `brick_no` varchar(20) NOT NULL DEFAULT '0',
  `card_no` varchar(20) NOT NULL DEFAULT '0',
  `order_no` int(11) NOT NULL,
  `trans_date` datetime NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'AVAILABLE',
  `sales_order_no` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2072 ;

--
-- Dumping data for table `0_serialized_stock`
--

INSERT INTO `0_serialized_stock` (`id`, `transtype`, `stock_id`, `location_code`, `qty`, `batch_no`, `box_no`, `brick_no`, `card_no`, `order_no`, `trans_date`, `status`, `sales_order_no`) VALUES
(51, NULL, 'N100A', 'WH', 500, NULL, '4bb2fee73e006', '0', '0', 34, '2010-03-31 09:12:20', 'PICKED', 23),
(52, NULL, 'N100A', 'WH', 500, NULL, '4bb31f0dd6716', '0', '0', 39, '2010-03-31 11:08:16', 'AVAILABLE', 0),
(53, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330602', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(54, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330656', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(55, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3306d2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(56, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330711', 44, '2010-04-07 17:28:46', 'PICKED', 16),
(57, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33074a', 44, '2010-04-07 17:28:46', 'PICKED', 16),
(58, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330782', 44, '2010-04-07 17:28:46', 'PICKED', 16),
(59, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3307ba', 44, '2010-04-07 17:28:46', 'PICKED', 16),
(60, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33084a', 44, '2010-04-07 17:28:46', 'PICKED', 16),
(61, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330883', 44, '2010-04-07 17:28:46', 'PICKED', 16),
(62, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3308bb', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(63, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3308f3', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(64, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33092b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(65, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330990', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(66, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3309d2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(67, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330a0b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(68, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330a42', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(69, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330a79', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(70, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330abd', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(71, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330af5', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(72, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330b2b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(73, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330b62', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(74, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330ba5', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(75, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330bdd', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(76, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330c13', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(77, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330c4a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(78, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330c80', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(79, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330cb6', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(80, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330cee', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(81, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330d24', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(82, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330d5a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(83, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330d90', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(84, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330dc8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(85, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330dff', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(86, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330e36', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(87, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330e8d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(88, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330ec8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(89, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330eff', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(90, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330f36', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(91, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330f6c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(92, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330faa', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(93, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a330fe1', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(94, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331018', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(95, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33104f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(96, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331085', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(97, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3310bc', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(98, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331107', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(99, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33113f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(100, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331176', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(101, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3311ad', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(102, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3311e4', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(103, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33121b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(104, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331255', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(105, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33128c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(106, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3312c2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(107, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3312f9', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(108, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331330', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(109, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331366', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(110, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3313a5', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(111, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3313df', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(112, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331415', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(113, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33144d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(114, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331483', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(115, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3314bb', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(116, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3314f2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(117, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331529', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(118, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331560', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(119, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331598', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(120, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3315d0', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(121, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331609', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(122, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33165d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(123, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331696', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(124, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3316cc', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(125, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331704', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(126, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33173d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(127, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331773', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(128, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3317b5', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(129, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3317ee', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(130, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331825', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(131, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33185d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(132, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331896', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(133, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3318cd', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(134, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331904', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(135, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33193c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(136, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331974', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(137, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3319ad', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(138, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3319e5', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(139, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331a1e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(140, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331a64', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(141, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331a9c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(142, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331ad4', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(143, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331b0c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(144, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331c0b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(145, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331c51', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(146, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331c9a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(147, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331cd8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(148, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331d51', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(149, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331d8c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(150, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331dc3', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(151, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331dfa', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(152, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331e33', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(153, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331e6a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(154, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331ea1', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(155, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331ed8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(156, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331f0f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(157, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331f47', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(158, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331f7e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(159, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331fb4', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(160, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a331fea', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(161, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332023', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(162, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332059', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(163, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332091', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(164, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3320cf', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(165, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332106', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(166, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33213e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(167, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332175', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(168, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3321ac', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(169, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3321e4', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(170, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33222e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(171, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33226d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(172, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3322a4', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(173, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3322db', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(174, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332312', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(175, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332349', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(176, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332380', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(177, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3323b7', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(178, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3323ee', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(179, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332425', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(180, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33245b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(181, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332492', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(182, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3324cf', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(183, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332508', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(184, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33253e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(185, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332575', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(186, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3325ab', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(187, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3325e3', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(188, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33261b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(189, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332651', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(190, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332688', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(191, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3326cf', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(192, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332706', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(193, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33273e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(194, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332775', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(195, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3327ab', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(196, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3327e2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(197, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33281a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(198, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332850', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(199, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332887', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(200, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3328be', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(201, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3328fb', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(202, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332932', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(203, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332968', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(204, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33299f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(205, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3329f8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(206, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332a39', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(207, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332a72', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(208, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332aaa', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(209, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332ae2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(210, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332b1a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(211, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332b51', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(212, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332b89', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(213, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332bc0', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(214, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332bf8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(215, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332c30', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(216, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332c67', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(217, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332c9f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(218, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332cd6', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(219, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332d0d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(220, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332d45', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(221, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332d7d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(222, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332dda', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(223, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332e18', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(224, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332e68', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(225, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332ea2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(226, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332eda', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(227, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332f11', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(228, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332f49', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(229, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332f80', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(230, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332fb7', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(231, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a332fee', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(232, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333030', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(233, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333068', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(234, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3330a0', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(235, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3330d8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(236, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33310f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(237, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33314c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(238, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333184', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(239, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3331c3', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(240, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3331fc', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(241, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333232', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(242, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33326a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(243, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3332a0', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(244, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3332d8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(245, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33330f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(246, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333345', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(247, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33337c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(248, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3333b4', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(249, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3333ea', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(250, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333422', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(251, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333459', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(252, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333490', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(253, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3334c7', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(254, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3334fe', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(255, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33353c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(256, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333573', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(257, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3335cc', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(258, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333603', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(259, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33363b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(260, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333673', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(261, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3336aa', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(262, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3336e2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(263, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333719', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(264, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333751', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(265, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333788', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(266, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3337bf', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(267, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3337f5', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(268, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33382c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(269, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333863', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(270, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33389a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(271, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3338d1', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(272, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333909', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(273, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333940', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(274, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33397d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(275, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3339fb', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(276, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333a35', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(277, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333a6e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(278, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333ab2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(279, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333aeb', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(280, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333b23', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(281, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333b5a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(282, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333b92', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(283, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333bcb', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(284, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333c03', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(285, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333c3b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(286, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333c72', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(287, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333caa', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(288, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333ce1', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(289, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333d18', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(290, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333d55', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(291, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333d97', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(292, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333dcf', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(293, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333e06', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(294, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333e3d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(295, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333e74', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(296, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333eab', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(297, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333ee2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(298, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333f19', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(299, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333f51', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(300, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333f88', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(301, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333fbf', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(302, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a333ff5', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(303, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33402b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(304, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334064', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(305, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33409b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(306, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3340d2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(307, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334108', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(308, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334145', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(309, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33417e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(310, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3341b5', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(311, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3341ec', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(312, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334223', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(313, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33425a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(314, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334291', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(315, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3342c8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(316, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3342fe', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(317, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334337', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(318, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33436e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(319, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3343a7', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(320, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3343de', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(321, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334414', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(322, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33444c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(323, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33448b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(324, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3344c1', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(325, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3344f8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(326, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33453b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(327, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334579', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(328, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3345b2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(329, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3345e9', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(330, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334620', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(331, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334657', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(332, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33468f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(333, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3346c7', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(334, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3346fe', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(335, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334735', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(336, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33476c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(337, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3347a3', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(338, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3347da', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(339, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334811', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(340, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334848', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(341, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33487f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(342, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3348b6', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(343, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3348fc', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(344, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334948', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(345, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334986', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(346, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3349be', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(347, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3349f6', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(348, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334a2c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(349, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334a63', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(350, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334a9b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(351, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334ad2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(352, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334b09', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(353, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334b42', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(354, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334b79', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(355, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334bb0', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(356, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334be7', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(357, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334c1e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(358, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334c54', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(359, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334c8b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(360, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334cc1', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(361, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334cfc', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(362, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334d34', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(363, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334d6b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(364, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334da2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(365, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334dd8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(366, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334e0e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(367, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334e45', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(368, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334e82', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(369, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334eba', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(370, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334ef1', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(371, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334f27', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(372, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334f5e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(373, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334f94', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(374, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a334fcc', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(375, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335002', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(376, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335039', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(377, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335070', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(378, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3350a7', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(379, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3350e5', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(380, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33511d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(381, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335159', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(382, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335191', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(383, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3351c9', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(384, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335200', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(385, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335236', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(386, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33526e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(387, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3352a5', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(388, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3352dc', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(389, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335313', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(390, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33534b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(391, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335383', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(392, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3353b9', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(393, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3353f0', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(394, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335427', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(395, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33545e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(396, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335495', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(397, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335508', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(398, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33554b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(399, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335584', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(400, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3355bc', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(401, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3355f3', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(402, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33562a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(403, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335660', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(404, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335698', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(405, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3356ce', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(406, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335705', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(407, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33573d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(408, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335783', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(409, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3357bb', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(410, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3357f3', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(411, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33582a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(412, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335862', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(413, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3358a3', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(414, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335909', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(415, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335943', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(416, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33597b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(417, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3359b1', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(418, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3359f0', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(419, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335a28', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(420, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335a60', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(421, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335a97', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(422, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335ace', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(423, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335b04', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(424, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335b3b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(425, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335b72', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(426, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335ba9', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(427, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335be0', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(428, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335c17', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(429, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335c4d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(430, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335c84', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(431, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335cec', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(432, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335d25', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(433, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335d5c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(434, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335d94', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(435, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335dcb', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(436, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335e08', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(437, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335e3f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(438, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335e76', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(439, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335ead', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(440, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335ee4', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(441, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335f1b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(442, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335f52', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(443, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335f89', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(444, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335fc0', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(445, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a335ff7', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(446, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33602e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(447, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336065', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(448, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3360a3', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(449, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3360db', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(450, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336111', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(451, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336148', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(452, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336180', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(453, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3361b6', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(454, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3361ed', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(455, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336224', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(456, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33625b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(457, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336292', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(458, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3362d0', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(459, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33630a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(460, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336341', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(461, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336379', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(462, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3363b0', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(463, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3363e7', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(464, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33641e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(465, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336455', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(466, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336494', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(467, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3364cc', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(468, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336503', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(469, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33653a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(470, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336572', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(471, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3365a9', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(472, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3365e7', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(473, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33661e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(474, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336655', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(475, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33668b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(476, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3366c2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(477, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3366f8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(478, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33672e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(479, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336765', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(480, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33679c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(481, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3367d3', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(482, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33680a', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(483, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336852', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(484, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336892', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(485, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3368ca', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(486, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336900', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(487, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336937', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(488, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33696e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(489, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3369a6', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(490, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3369db', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(491, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336a12', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(492, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336a49', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(493, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336a80', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(494, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336ab7', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(495, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336aed', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(496, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336b24', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(497, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336b5b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(498, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336b93', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(499, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336bc9', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(500, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336c00', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(501, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336c3b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(502, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336c73', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(503, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336caa', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(504, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336ce9', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(505, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336d20', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(506, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336d56', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(507, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336d8d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(508, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336dc8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(509, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336e00', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(510, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336e36', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(511, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336e6d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(512, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336ea4', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(513, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336eda', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(514, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336f11', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(515, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336f47', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(516, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336f7e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(517, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336fb4', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(518, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a336feb', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(519, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337027', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(520, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33705f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(521, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337095', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(522, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3370dc', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(523, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337114', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(524, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33714b', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0);
INSERT INTO `0_serialized_stock` (`id`, `transtype`, `stock_id`, `location_code`, `qty`, `batch_no`, `box_no`, `brick_no`, `card_no`, `order_no`, `trans_date`, `status`, `sales_order_no`) VALUES
(525, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337181', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(526, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3371be', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(527, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3371f6', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(528, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33722d', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(529, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337264', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(530, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33729c', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(531, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3372d2', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(532, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337309', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(533, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33733f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(534, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337376', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(535, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3373ad', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(536, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3373e4', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(537, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33750e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(538, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a33759f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(539, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337942', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(540, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337993', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(541, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a3379ce', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(542, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337a08', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(543, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337a3f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(544, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337a77', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(545, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337aaf', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(546, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337ae7', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(547, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337b1f', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(548, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337b57', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(549, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337b99', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(550, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337be8', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(551, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337c25', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(552, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbcb2a337c5e', 44, '2010-04-07 17:28:46', 'AVAILABLE', 0),
(558, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c838c3', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(557, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83874', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(556, NULL, 'N100A', 'WH', 500, NULL, '4bbccd2417730', '0', '0', 45, '2010-04-07 19:21:32', 'AVAILABLE', 0),
(559, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83902', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(560, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8393b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(561, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83973', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(562, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c839ac', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(563, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c839e4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(564, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83a1b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(565, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83a51', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(566, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83a88', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(567, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83ac0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(568, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83afa', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(569, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83b31', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(570, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83b68', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(571, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83b9f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(572, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83bd6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(573, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83c0c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(574, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83c44', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(575, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83c7b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(576, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83cb3', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(577, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83ce9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(578, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83d2d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(579, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83d64', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(580, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83d9c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(581, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83dd3', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(582, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83e0c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(583, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83e43', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(584, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83e79', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(585, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83eb1', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(586, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83ee9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(587, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83f36', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(588, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83f6f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(589, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83fa6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(590, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c83fdd', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(591, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84014', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(592, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8404b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(593, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84082', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(594, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c840b9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(595, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c840f0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(596, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8412b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(597, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84162', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(598, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84198', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(599, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c841cf', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(600, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84206', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(601, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8423c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(602, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84274', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(603, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c842ab', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(604, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c842e4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(605, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8431a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(606, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84377', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(607, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c843c2', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(608, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8440a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(609, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84444', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(610, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8447c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(611, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c844b5', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(612, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c844ec', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(613, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84523', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(614, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84562', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(615, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84598', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(616, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c845d0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(617, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84608', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(618, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84640', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(619, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84678', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(620, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c846b7', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(621, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c846ef', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(622, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84727', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(623, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8475f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(624, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84797', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(625, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c847cf', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(626, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84806', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(627, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8483d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(628, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84875', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(629, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c848ac', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(630, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c848e3', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(631, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8491a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(632, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84956', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(633, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8498d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(634, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c849c4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(635, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c849fa', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(636, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84a31', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(637, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84a68', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(638, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84aa4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(639, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84adb', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(640, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84b12', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(641, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84b49', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(642, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84b80', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(643, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84bb7', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(644, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84bee', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(645, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84c25', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(646, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84c5c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(647, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84c93', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(648, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84cca', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(649, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84d01', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(650, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84d3c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(651, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84d74', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(652, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84dac', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(653, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84de3', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(654, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84e1a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(655, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84e50', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(656, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84e8d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(657, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84ec6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(658, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84f2d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(659, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84f66', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(660, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84f9e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(661, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c84fd4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(662, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8500c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(663, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85044', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(664, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8507c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(665, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c850b4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(666, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c850ea', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(667, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85122', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(668, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8515e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(669, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85196', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(670, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c851cc', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(671, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85202', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(672, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8523a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(673, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c852a6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(674, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c852e0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(675, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85317', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(676, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8534e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(677, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85387', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(678, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c853be', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(679, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c853f6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(680, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8542e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(681, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85465', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(682, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8549c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(683, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c854d5', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(684, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8550d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(685, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85544', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(686, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85580', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(687, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c855b7', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(688, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c855ef', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(689, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85626', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(690, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85663', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(691, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8569b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(692, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c856d2', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(693, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85709', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(694, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85740', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(695, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85778', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(696, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c857b0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(697, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c857e7', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(698, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85820', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(699, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85857', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(700, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85890', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(701, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c858c7', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(702, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c858fe', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(703, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85936', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(704, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8596e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(705, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c859a9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(706, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c859e0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(707, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85a17', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(708, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85a70', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(709, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85aa9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(710, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85ae1', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(711, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85b17', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(712, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85b4e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(713, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85b85', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(714, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85bbd', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(715, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85bf5', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(716, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85c2c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(717, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85c64', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(718, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85c9b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(719, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85cd3', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(720, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85d0b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(721, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85d42', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(722, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85d79', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(723, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85db1', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(724, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85de8', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(725, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85e26', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(726, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85e5e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(727, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85ea6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(728, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85ede', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(729, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85f15', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(730, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85f4c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(731, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85f84', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(732, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85fbb', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(733, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c85ff3', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(734, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8602b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(735, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86063', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(736, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8609f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(737, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c860d8', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(738, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8610f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(739, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86146', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(740, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8617f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(741, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c861b6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(742, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c861ed', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(743, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8622a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(744, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86261', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(745, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8629a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(746, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c862d0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(747, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86307', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(748, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8633f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(749, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86376', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(750, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c863ad', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(751, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c863e5', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(752, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8641d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(753, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86455', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(754, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8648c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(755, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c864c3', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(756, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c864fb', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(757, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86531', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(758, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86569', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(759, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c865a4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(760, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c865db', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(761, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86618', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(762, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86650', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(763, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86687', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(764, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c866bf', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(765, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c866f7', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(766, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8672e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(767, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86767', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(768, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8679e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(769, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c867d5', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(770, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8680c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(771, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86844', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(772, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8687b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(773, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c868b2', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(774, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c868e9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(775, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86920', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(776, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86957', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(777, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8698f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(778, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c869c7', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(779, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86a03', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(780, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86a3a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(781, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86a72', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(782, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86aaf', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(783, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86af5', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(784, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86b2e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(785, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86b66', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(786, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86b9d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(787, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86bd4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(788, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86c0c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(789, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86c43', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(790, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86c7a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(791, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86cb1', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(792, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86ce8', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(793, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86d1f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(794, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86d56', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(795, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86d91', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(796, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86def', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(797, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86e29', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(798, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86e61', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(799, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86e99', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(800, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86ed0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(801, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86f07', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(802, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86f3f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(803, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86f75', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(804, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86fab', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(805, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c86fe3', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(806, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8701b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(807, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87052', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(808, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87089', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(809, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c870c1', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(810, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c870f8', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(811, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8712f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(812, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87167', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(813, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c871a2', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(814, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c871da', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(815, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87211', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(816, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87248', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(817, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87280', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(818, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c872b7', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(819, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c872ef', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(820, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87326', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(821, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8735d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(822, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87394', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(823, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c873cb', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(824, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87403', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(825, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8743a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(826, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87471', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(827, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c874ae', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(828, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c874e6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(829, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8751c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(830, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87553', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(831, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87592', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(832, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c875cb', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(833, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87627', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(834, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87661', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(835, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87699', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(836, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c876d1', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(837, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87708', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(838, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8773f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(839, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87775', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(840, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c877ac', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(841, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c877e4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(842, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8781b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(843, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87852', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(844, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87889', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(845, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c878c1', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(846, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c878f7', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(847, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8792f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(848, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87965', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(849, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c879a9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(850, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c879e2', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(851, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87a1b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(852, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87a53', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(853, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87a8c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(854, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87ac4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(855, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87afd', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(856, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87b36', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(857, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87b6e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(858, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87ba6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(859, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87bdd', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(860, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87c14', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(861, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87c4b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(862, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87c82', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(863, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87cba', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(864, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87cf1', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(865, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87d28', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(866, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87d65', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(867, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87d9f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(868, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87dd6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(869, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87e0d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(870, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87e44', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(871, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87e7b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(872, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87eb8', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(873, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87ef0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(874, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87f27', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(875, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87f5f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(876, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87f97', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(877, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c87fce', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(878, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88006', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(879, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8803e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(880, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88075', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(881, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c880ac', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(882, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c880e3', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(883, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8811b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(884, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88171', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(885, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c881b0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(886, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c881e8', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(887, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8821f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(888, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88256', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(889, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8828d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(890, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c882c5', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(891, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c882fd', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(892, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88335', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(893, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8836c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(894, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c883a2', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(895, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c883d9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(896, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8840f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(897, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88447', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(898, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8847f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(899, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c884b6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(900, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c884ed', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(901, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8852b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(902, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88565', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(903, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8859e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(904, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c885d6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(905, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8860d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(906, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88643', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(907, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8867a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(908, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c886b2', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(909, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c886e9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(910, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88720', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(911, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88757', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(912, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8878e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(913, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c887c5', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(914, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c887fc', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(915, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88833', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(916, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8886a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(917, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c888a6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(918, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c888de', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(919, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8891a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(920, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88952', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(921, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88989', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(922, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c889c4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(923, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c889fc', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(924, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88a33', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(925, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88a6a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(926, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88aa1', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(927, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88ad8', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(928, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88b10', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(929, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88b47', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(930, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88b7f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(931, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88bb6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(932, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88bed', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(933, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88c23', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(934, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88c5b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(935, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88c93', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(936, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88cc9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(937, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88d04', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(938, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88d3d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(939, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88d75', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(940, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88db0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(941, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88de8', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(942, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88e2d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(943, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88e64', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(944, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88e9b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(945, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88ed4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(946, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88f0a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(947, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88f41', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(948, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88f79', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(949, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88fb0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(950, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c88fe8', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(951, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8901f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(952, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89055', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(953, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8908d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(954, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c890c5', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(955, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89102', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(956, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8913a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(957, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89171', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(958, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c891a9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(959, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c891e0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(960, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89217', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(961, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8924f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(962, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8928a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(963, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c892c3', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(964, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c892fa', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(965, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89331', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(966, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89376', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(967, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c893ad', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(968, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c893e4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(969, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8941b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(970, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89453', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(971, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8948a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(972, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c894c7', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(973, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89501', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(974, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89539', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(975, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89570', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(976, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c895ac', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(977, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c895e4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(978, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8961b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(979, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89652', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(980, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8968a', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(981, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c896c1', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(982, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c896f9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(983, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89730', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(984, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89767', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(985, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8979f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(986, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c897d6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(987, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8980d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(988, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89844', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(989, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8987c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(990, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c898b8', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(991, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c898f0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(992, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89928', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(993, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8995f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(994, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89995', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(995, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c899cc', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(996, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89a04', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(997, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89a3b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(998, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89a73', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(999, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89aaa', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1000, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89ae2', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1001, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89b19', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0);
INSERT INTO `0_serialized_stock` (`id`, `transtype`, `stock_id`, `location_code`, `qty`, `batch_no`, `box_no`, `brick_no`, `card_no`, `order_no`, `trans_date`, `status`, `sales_order_no`) VALUES
(1002, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89b52', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1003, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89b88', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1004, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89bbf', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1005, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89bf6', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1006, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89c2e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1007, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89c65', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1008, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89ca5', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1009, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89cde', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1010, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89d15', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1011, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89d4e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1012, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89d88', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1013, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89dc0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1014, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89df7', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1015, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89e2f', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1016, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89e66', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1017, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89e9d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1018, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89ed4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1019, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89f0b', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1020, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89f42', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1021, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89f79', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1022, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89fb0', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1023, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c89fe7', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1024, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a01e', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1025, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a056', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1026, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a092', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1027, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a0ca', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1028, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a101', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1029, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a138', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1030, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a174', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1031, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a1ac', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1032, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a1e3', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1033, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a229', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1034, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a260', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1035, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a297', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1036, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a2ce', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1037, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a306', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1038, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a33d', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1039, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a375', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1040, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a3ad', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1041, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a3e4', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1042, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a41c', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1043, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a454', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1044, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a490', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1045, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a4c9', 41, '2010-04-07 19:22:19', 'AVAILABLE', 0),
(1046, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a501', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1047, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a538', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1048, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a571', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1049, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a5aa', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1050, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a5e2', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1051, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a61a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1052, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a651', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1053, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a690', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1054, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a6c8', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1055, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a6ff', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1056, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a737', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1057, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a76f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1058, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a7a6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1059, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a7de', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1060, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a815', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1061, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a869', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1062, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a8a6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1063, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a8de', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1064, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a916', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1065, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a94e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1066, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a98c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1067, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8a9c6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1068, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8aa09', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1069, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8aa44', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1070, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8aa7c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1071, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8aab5', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1072, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8aaf0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1073, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ab32', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1074, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ab6e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1075, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8aba6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1076, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8abdf', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1077, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ac1a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1078, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ad1a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1079, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ad57', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1080, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ad90', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1081, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8adc7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1082, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8adff', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1083, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ae39', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1084, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ae71', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1085, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8aea9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1086, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8aee0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1087, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8af17', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1088, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8af50', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1089, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8af87', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1090, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8afbf', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1091, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8aff6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1092, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b037', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1093, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b071', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1094, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b0a9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1095, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b0e1', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1096, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b118', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1097, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b14f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1098, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b190', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1099, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b1c9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1100, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b201', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1101, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b239', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1102, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b2af', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1103, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b2ea', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1104, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b320', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1105, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b357', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1106, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b390', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1107, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b3c7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1108, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b3fe', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1109, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b43c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1110, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b473', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1111, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b4ab', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1112, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b4e3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1113, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b51b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1114, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b552', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1115, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b589', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1116, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b5d6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1117, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b60d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1118, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b645', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1119, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b67d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1120, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b6b7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1121, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b6f1', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1122, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b728', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1123, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b760', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1124, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b797', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1125, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b7cf', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1126, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b80c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1127, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b845', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1128, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b87d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1129, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b8b3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1130, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b8eb', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1131, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b922', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1132, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b95a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1133, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b991', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1134, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b9c8', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1135, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8b9ff', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1136, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ba37', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1137, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ba6e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1138, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8baa5', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1139, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bade', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1140, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bb15', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1141, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bb4c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1142, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bb83', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1143, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bbbe', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1144, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bbfe', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1145, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bc36', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1146, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bc6d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1147, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bca4', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1148, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bcdb', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1149, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bd12', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1150, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bd49', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1151, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bd80', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1152, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bdb7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1153, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bdee', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1154, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8be25', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1155, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8be5c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1156, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8be93', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1157, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8becf', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1158, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bf06', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1159, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bf3e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1160, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bf74', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1161, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bfab', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1162, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8bfe9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1163, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c020', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1164, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c057', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1165, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c08e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1166, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c0c5', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1167, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c0fd', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1168, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c133', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1169, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c16a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1170, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c1a1', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1171, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c1d8', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1172, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c20f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1173, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c256', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1174, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c28d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1175, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c2c5', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1176, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c2fc', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1177, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c334', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1178, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c36c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1179, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c3a7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1180, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c3e1', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1181, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c418', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1182, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c44f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1183, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c485', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1184, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c4bc', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1185, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c4f3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1186, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c52b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1187, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c563', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1188, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c59a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1189, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c5d6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1190, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c60d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1191, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c644', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1192, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c67b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1193, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c6b6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1194, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c6ee', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1195, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c725', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1196, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c75d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1197, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c799', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1198, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c7d1', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1199, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c809', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1200, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c840', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1201, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c878', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1202, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c8af', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1203, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c8e7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1204, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c91e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1205, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c955', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1206, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c98c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1207, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c9c3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1208, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8c9fa', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1209, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ca32', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1210, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ca6a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1211, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8caa4', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1212, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cadc', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1213, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cb13', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1214, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cb4a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1215, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cb86', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1216, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cbbe', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1217, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cbf5', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1218, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cc2d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1219, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cc63', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1220, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cc9b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1221, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ccd1', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1222, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cd08', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1223, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cd3f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1224, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cd76', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1225, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cdad', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1226, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cdf1', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1227, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ce29', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1228, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ce60', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1229, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ce98', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1230, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cece', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1231, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cf05', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1232, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cf3d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1233, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cf93', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1234, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8cfd0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1235, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d007', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1236, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d03e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1237, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d076', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1238, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d0ad', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1239, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d0e5', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1240, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d11c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1241, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d154', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1242, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d18b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1243, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d1c3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1244, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d1fc', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1245, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d233', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1246, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d26c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1247, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d2aa', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1248, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d2e3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1249, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d3a2', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1250, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d3de', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1251, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d417', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1252, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d450', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1253, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d48a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1254, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d4c3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1255, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d4fc', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1256, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d535', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1257, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d56d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1258, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d5a5', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1259, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d5dd', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1260, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d616', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1261, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d650', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1262, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d687', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1263, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d6c0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1264, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d6f8', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1265, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d784', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1266, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d7c0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1267, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d7f9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1268, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d830', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1269, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d868', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1270, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d8a0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1271, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d8d7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1272, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d90e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1273, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d946', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1274, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d97d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1275, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d9b4', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1276, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8d9eb', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1277, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8da22', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1278, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8da5b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1279, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8da9d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1280, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8dad5', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1281, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8db0e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1282, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8db4d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1283, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8db88', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1284, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8dbc0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1285, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8dbf8', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1286, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8dc2f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1287, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8dc67', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1288, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8dc9e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1289, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8dcd6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1290, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8dd0e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1291, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8dd46', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1292, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8dd7d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1293, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ddb4', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1294, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ddeb', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1295, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8de23', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1296, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8de5b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1297, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8de92', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1298, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8deca', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1299, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8df07', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1300, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e0e7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1301, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e12d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1302, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e167', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1303, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e1a0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1304, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e1d9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1305, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e211', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1306, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e249', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1307, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e281', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1308, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e2b9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1309, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e315', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1310, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e354', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1311, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e38c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1312, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e3c4', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1313, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e3fd', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1314, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e436', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1315, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e46e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1316, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e4a7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1317, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e4e0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1318, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e518', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1319, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e550', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1320, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e589', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1321, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e5c0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1322, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e5f8', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1323, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e62f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1324, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e66e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1325, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e6a7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1326, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e6e5', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1327, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e71d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1328, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e754', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1329, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e78c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1330, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e7c3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1331, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e7fa', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1332, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e832', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1333, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e869', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1334, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e8a0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1335, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e8d7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1336, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e90e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1337, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e949', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1338, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e981', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1339, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e9b8', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1340, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8e9f0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1341, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ea27', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1342, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ea5f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1343, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ea96', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1344, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ead3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1345, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8eb0c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1346, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8eb43', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1347, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8eb7a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1348, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ebb1', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1349, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ebe9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1350, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ec20', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1351, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ec57', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1352, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ec8e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1353, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ecc6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1354, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ecfd', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1355, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ed34', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1356, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ed6c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1357, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8eda3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1358, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8edda', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1359, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ee12', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1360, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ee49', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1361, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ee81', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1362, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8eebb', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1363, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8eef2', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1364, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ef2a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1365, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ef62', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1366, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ef9a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1367, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8efd0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1368, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f007', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1369, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f042', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1370, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f07a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1371, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f0b0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1372, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f0e6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1373, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f11e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1374, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f15a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1375, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f192', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1376, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f1ca', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1377, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f200', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1378, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f238', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1379, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f26f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1380, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f2bb', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1381, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f2f4', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1382, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f32b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1383, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f362', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1384, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f399', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1385, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f3d0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1386, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f407', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1387, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f43e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1388, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f475', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1389, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f4ad', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1390, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f4e3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1391, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f51b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1392, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f559', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1393, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f590', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1394, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f5c8', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1395, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f5ff', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1396, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f637', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1397, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f68c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1398, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f6c7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1399, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f6ff', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1400, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f736', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1401, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f76f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1402, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f7a7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1403, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f7de', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1404, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f815', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1405, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f84d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1406, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f884', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1407, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f8bb', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1408, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f8f3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1409, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f92b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1410, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f963', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1411, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f99a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1412, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8f9d2', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1413, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fa09', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1414, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fa41', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1415, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fb78', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1416, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fbb3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1417, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fbea', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1418, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fc21', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1419, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fc59', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1420, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fc90', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1421, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fcc7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1422, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fcff', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1423, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fd3b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1424, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fd71', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1425, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fda9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1426, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fde0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1427, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fe17', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1428, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fe55', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1429, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fe8d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1430, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fec4', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1431, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8fefc', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1432, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ff34', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1433, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ff6a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1434, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ffa1', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1435, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c8ffe8', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1436, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90020', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1437, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90057', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1438, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9008e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1439, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c900c7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1440, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c900fe', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1441, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9013a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1442, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90172', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1443, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c901aa', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1444, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c901e2', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1445, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90219', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1446, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90256', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1447, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9028d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1448, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c902c4', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1449, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c902fb', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1450, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90332', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1451, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9036a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1452, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c903a1', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1453, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c903d9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1454, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90410', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1455, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90448', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1456, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9047f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1457, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c904b6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1458, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c904ed', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1459, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90525', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1460, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90561', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1461, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90598', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1462, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c905d0', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1463, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90607', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1464, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90645', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1465, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9067d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1466, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c906b5', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1467, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c906ec', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1468, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90723', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1469, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9075a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1470, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90790', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0);
INSERT INTO `0_serialized_stock` (`id`, `transtype`, `stock_id`, `location_code`, `qty`, `batch_no`, `box_no`, `brick_no`, `card_no`, `order_no`, `trans_date`, `status`, `sales_order_no`) VALUES
(1471, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c907c8', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1472, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c907ff', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1473, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90836', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1474, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9086d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1475, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c908a4', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1476, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c908db', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1477, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90913', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1478, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9094e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1479, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90985', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1480, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c909bc', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1481, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c909f9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1482, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90a33', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1483, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90a6b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1484, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90aa2', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1485, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90ad9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1486, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90b0f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1487, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90b47', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1488, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90b7e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1489, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90bb5', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1490, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90bec', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1491, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90c24', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1492, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90c5b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1493, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90cb9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1494, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90cf3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1495, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90d2c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1496, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90d64', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1497, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90d9c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1498, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90dd3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1499, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90e10', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1500, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90e48', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1501, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90e7f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1502, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90eb7', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1503, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90eee', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1504, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90f24', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1505, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90f5f', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1506, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90f98', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1507, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c90fcf', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1508, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91006', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1509, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9103d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1510, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91074', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1511, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c910ab', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1512, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c910e2', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1513, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91119', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1514, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91154', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1515, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9118b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1516, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c911c2', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1517, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91200', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1518, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91237', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1519, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9126e', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1520, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c912a3', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1521, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c912da', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1522, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91312', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1523, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91349', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1524, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91381', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1525, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c913b9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1526, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c913f2', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1527, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91429', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1528, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91461', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1529, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91498', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1530, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c914ce', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1531, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91505', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1532, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91543', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1533, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9157b', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1534, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c915b6', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1535, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c915ee', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1536, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91625', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1537, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9165d', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1538, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91694', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1539, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c916cb', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1540, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91702', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1541, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91748', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1542, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91781', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1543, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c917b8', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1544, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c917ef', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1545, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91825', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1546, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9185c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1547, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91892', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1548, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c918c9', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1549, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91902', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1550, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c9193a', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1551, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91976', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1552, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c919b4', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1553, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c919ec', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1554, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91a24', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1555, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91a5c', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1556, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bbccd4c91a93', 41, '2010-04-07 19:22:20', 'AVAILABLE', 0),
(1557, NULL, 'MOBILEKIT', 'WH', 1, NULL, '0', '0', '4bbd83ff9bf64', 46, '2010-04-08 08:21:39', 'PICKED', 15),
(1558, NULL, 'MOBILEKIT', 'WH', 1, NULL, '0', '0', '4bbd83ff9bfb1', 46, '2010-04-08 08:21:39', 'PICKED', 15),
(1559, NULL, 'MOBILEKIT', 'WH', 1, NULL, '0', '0', '4bbd83ff9bff2', 46, '2010-04-08 08:21:39', 'PICKED', 15),
(1560, NULL, 'MOBILEKIT', 'WH', 1, NULL, '0', '0', '4bbd83ff9c02b', 46, '2010-04-08 08:21:39', 'PICKED', 15),
(1561, NULL, 'MOBILEKIT', 'WH', 1, NULL, '0', '0', '4bbd83ff9c063', 46, '2010-04-08 08:21:39', 'PICKED', 15),
(1562, NULL, 'MOBILEKIT', 'WH', 1, NULL, '0', '0', '4bbd83ff9c0b8', 46, '2010-04-08 08:21:39', 'AVAILABLE', 0),
(1563, NULL, 'MOBILEKIT', 'WH', 1, NULL, '0', '0', '4bbd83ff9c0f3', 46, '2010-04-08 08:21:39', 'AVAILABLE', 0),
(1564, NULL, 'MOBILEKIT', 'WH', 1, NULL, '0', '0', '4bbd83ff9c12b', 46, '2010-04-08 08:21:39', 'AVAILABLE', 0),
(1565, NULL, 'MOBILEKIT', 'WH', 1, NULL, '0', '0', '4bbd83ff9c162', 46, '2010-04-08 08:21:39', 'AVAILABLE', 0),
(1566, NULL, 'MOBILEKIT', 'WH', 1, NULL, '0', '0', '4bbd83ff9c19a', 46, '2010-04-08 08:21:39', 'AVAILABLE', 0),
(1567, NULL, 'N100A', 'WH', 100, NULL, '0', '4bc579df326ff', '0', 47, '2010-04-14 09:16:34', 'AVAILABLE', 0),
(1568, NULL, 'N100A', 'WH', 100, NULL, '0', '4bc579df3274d', '0', 47, '2010-04-14 09:16:34', 'AVAILABLE', 0),
(1569, NULL, 'N100A', 'WH', 100, NULL, '0', '4bc579df3278e', '0', 47, '2010-04-14 09:16:34', 'AVAILABLE', 0),
(1570, NULL, 'N100A', 'WH', 100, NULL, '0', '4bc579df327c6', '0', 47, '2010-04-14 09:16:34', 'AVAILABLE', 0),
(1571, NULL, 'N100A', 'WH', 100, NULL, '0', '4bc579df327fe', '0', 47, '2010-04-14 09:16:34', 'AVAILABLE', 0),
(1572, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0b5b', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1573, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0baa', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1574, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0bea', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1575, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0c23', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1576, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0c5a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1577, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0c95', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1578, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0ccd', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1579, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0d04', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1580, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0d3b', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1581, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0d72', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1582, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0daa', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1583, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0de0', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1584, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0e17', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1585, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0e4e', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1586, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0e86', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1587, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0ebe', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1588, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0ef4', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1589, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0f2b', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1590, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0f62', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1591, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0f98', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1592, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a0fcf', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1593, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a100a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1594, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1041', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1595, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1078', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1596, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a10af', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1597, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a10e7', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1598, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a111d', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1599, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1155', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1600, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a118b', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1601, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a11c2', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1602, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a11f9', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1603, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a122f', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1604, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1265', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1605, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a129d', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1606, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a12d3', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1607, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a130a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1608, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1341', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1609, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1378', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1610, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a13af', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1611, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a13e9', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1612, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1421', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1613, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1459', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1614, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1490', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1615, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a14c7', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1616, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a14fe', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1617, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1535', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1618, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a156c', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1619, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a15a2', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1620, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a15da', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1621, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1611', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1622, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1648', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1623, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a167f', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1624, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a16b6', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1625, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a16ed', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1626, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1723', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1627, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a175a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1628, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1791', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1629, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a17ca', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1630, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1802', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1631, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1851', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1632, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1889', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1633, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a18c0', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1634, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a18f8', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1635, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a192e', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1636, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1965', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1637, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a199d', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1638, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a19d3', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1639, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1a0a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1640, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1a41', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1641, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1a78', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1642, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1aaf', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1643, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1ae6', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1644, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1b1d', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1645, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1b55', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1646, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1b8b', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1647, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1bc8', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1648, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1bff', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1649, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1c55', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1650, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1c92', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1651, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1cc9', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1652, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1cff', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1653, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1d37', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1654, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1d6d', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1655, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1da4', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1656, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1dda', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1657, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1e10', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1658, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1e47', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1659, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1e7e', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1660, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1eb4', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1661, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1eeb', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1662, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1f22', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1663, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1f5a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1664, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1f91', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1665, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a1fcc', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1666, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2004', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1667, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a203c', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1668, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2073', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1669, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a20aa', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1670, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a20e1', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1671, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2119', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1672, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2150', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1673, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2188', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1674, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a21be', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1675, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a21f6', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1676, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a222d', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1677, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2264', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1678, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a229b', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1679, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a22d3', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1680, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a230a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1681, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2341', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1682, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2378', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1683, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a23b2', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1684, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a23e8', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1685, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2431', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1686, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2468', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1687, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a249f', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1688, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a24d5', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1689, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a250c', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1690, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2543', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1691, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a257a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1692, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a25b1', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1693, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a25e8', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1694, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a261f', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1695, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2655', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1696, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a268c', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1697, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a26c3', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1698, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a26fa', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1699, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2732', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1700, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2769', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1701, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a27a3', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1702, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a27da', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1703, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2822', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1704, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a285a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1705, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2891', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1706, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a28c8', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1707, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a28fe', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1708, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2936', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1709, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a296d', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1710, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a29a4', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1711, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a29dc', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1712, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2a13', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1713, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2a4a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1714, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2a81', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1715, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2ab8', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1716, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2aef', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1717, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2b26', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1718, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2b5e', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1719, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2b95', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1720, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2bd2', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1721, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2c0a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1722, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2c41', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1723, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2c79', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1724, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2caf', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1725, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2ce6', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1726, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2d1c', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1727, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2d54', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1728, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2d8b', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1729, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2dc2', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1730, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2df9', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1731, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2e30', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1732, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2e74', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1733, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2eab', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1734, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2ee2', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1735, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2f19', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1736, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2f50', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1737, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2f87', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1738, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2fc0', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1739, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a2ff7', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1740, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a302e', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1741, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3065', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1742, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a309b', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1743, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a30d2', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1744, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3109', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1745, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a313f', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1746, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3176', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1747, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a31ac', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1748, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a31e2', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1749, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3219', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1750, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a324f', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1751, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a328a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1752, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a32c1', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1753, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a32f8', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1754, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a332f', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1755, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3366', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1756, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a33f7', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1757, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3438', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1758, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3471', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1759, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a34a9', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1760, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a34e1', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1761, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3518', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1762, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a354f', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1763, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3588', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1764, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a35bf', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1765, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a35f6', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1766, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a362d', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1767, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3665', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1768, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a369c', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1769, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a36d3', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1770, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3709', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1771, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3740', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1772, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3776', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1773, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a37af', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1774, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a37ed', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1775, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3824', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1776, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a385b', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1777, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3893', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1778, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a38ca', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1779, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3901', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1780, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3938', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1781, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a396f', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1782, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a39a5', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1783, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a39dc', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1784, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3a14', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1785, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3a4a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1786, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3a81', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1787, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3ab9', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1788, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3af0', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1789, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3b27', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1790, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3b5e', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1791, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3b97', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1792, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3bce', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1793, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3c05', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1794, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3c3c', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1795, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3c73', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1796, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3cab', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1797, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3ce8', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1798, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3d20', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1799, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3d57', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1800, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3d8e', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1801, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3dc5', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1802, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3dfc', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1803, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3e33', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1804, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3e69', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1805, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3ea0', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1806, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3ed7', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1807, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3f0e', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1808, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3f45', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1809, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3f7b', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1810, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3fb6', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1811, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a3fee', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1812, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4024', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1813, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a405c', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1814, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4093', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1815, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a40ca', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1816, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4101', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1817, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4138', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1818, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a416e', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1819, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a41a4', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1820, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a41db', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1821, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4212', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1822, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4249', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1823, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4280', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1824, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a42b7', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1825, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a42ee', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1826, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4327', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1827, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a436f', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1828, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a43a7', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1829, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a43de', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1830, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4415', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1831, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a444c', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1832, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4483', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1833, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a44ba', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1834, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a44f1', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1835, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4527', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1836, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a455e', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1837, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4595', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1838, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a45cb', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1839, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4603', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1840, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a463a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1841, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4671', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1842, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a46ac', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1843, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a46e3', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1844, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a471a', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1845, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4751', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1846, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a478c', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1847, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a47c2', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1848, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a47f9', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1849, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4841', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1850, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4879', 48, '2010-04-14 09:17:15', 'AVAILABLE', 0),
(1851, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a48b0', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1852, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a48e6', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1853, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a491c', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1854, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4952', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1855, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4989', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1856, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a49c1', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1857, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a49f7', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1858, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4a2e', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1859, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4a66', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1860, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4a9d', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1861, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4ad4', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1862, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4b0d', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1863, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4b44', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1864, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4b7e', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1865, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4bb7', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1866, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4bed', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1867, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4c24', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1868, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4c5b', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1869, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4c92', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1870, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4cc8', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1871, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4d00', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1872, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4d37', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1873, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4d6e', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1874, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4da4', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1875, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4ddb', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1876, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4e12', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1877, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4e49', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1878, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4e80', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1879, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4eb7', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1880, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4eed', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1881, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4f26', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1882, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4f5d', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1883, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4f93', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1884, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a4fc9', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1885, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5000', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1886, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5037', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1887, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5072', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1888, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a50a9', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1889, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a50e0', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1890, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5116', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1891, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a514e', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1892, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5184', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1893, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a51e3', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1894, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a521d', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1895, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5254', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1896, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a528b', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1897, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a52c2', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1898, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a52fc', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1899, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5334', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1900, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a536f', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1901, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a53a6', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1902, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a53de', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1903, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5415', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1904, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a544c', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1905, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5483', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1906, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a54ba', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1907, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a54f1', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1908, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5528', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1909, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5560', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1910, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5597', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1911, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a55ce', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1912, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5604', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1913, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a563c', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1914, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5672', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1915, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a56a9', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1916, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a56e0', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1917, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5718', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1918, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a574f', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1919, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5787', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1920, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a57be', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1921, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a57f5', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1922, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a582c', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1923, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5863', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1924, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a589b', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1925, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a58d2', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1926, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5918', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1927, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5950', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1928, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5987', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1929, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a59be', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1930, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a59f5', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1931, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5a2c', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1932, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5a67', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1933, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5a9f', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1934, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5af9', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1935, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5b36', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1936, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5b6e', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1937, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5bac', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1938, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5be4', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1939, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5c1c', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0);
INSERT INTO `0_serialized_stock` (`id`, `transtype`, `stock_id`, `location_code`, `qty`, `batch_no`, `box_no`, `brick_no`, `card_no`, `order_no`, `trans_date`, `status`, `sales_order_no`) VALUES
(1940, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5c53', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1941, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5c8c', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1942, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5cc3', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1943, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5cfa', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1944, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5d33', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1945, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5d6a', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1946, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5da1', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1947, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5dd8', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1948, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5e0f', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1949, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5e47', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1950, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5e7e', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1951, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5eb8', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1952, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5eef', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1953, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5f26', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1954, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5f5d', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1955, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5f98', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1956, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a5fd0', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1957, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6008', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1958, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a603f', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1959, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6076', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1960, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a60ad', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1961, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a60e4', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1962, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a612a', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1963, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6161', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1964, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6198', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1965, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a61cf', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1966, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6206', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1967, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a623e', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1968, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6279', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1969, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a62b1', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1970, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a62e8', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1971, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a631f', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1972, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6356', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1973, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a638d', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1974, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a63c4', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1975, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a63fb', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1976, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6432', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1977, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a646c', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1978, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a64a4', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1979, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a64dc', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1980, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6513', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1981, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a654a', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1982, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6580', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1983, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a65b8', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1984, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a65ef', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1985, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6626', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1986, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a665f', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1987, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6696', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1988, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a66ce', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1989, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6705', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1990, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6750', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1991, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6792', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1992, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a67c8', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1993, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a67ff', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1994, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6836', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1995, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a686e', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1996, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a68a5', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1997, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a68dc', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1998, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6913', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(1999, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6949', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2000, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a697f', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2001, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a69b6', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2002, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a69ee', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2003, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6a24', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2004, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6a6b', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2005, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6aa3', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2006, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6ada', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2007, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6b11', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2008, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6b47', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2009, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6b7e', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2010, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6bb4', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2011, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6bea', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2012, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6c21', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2013, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6c58', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2014, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6c8e', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2015, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6cc4', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2016, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6cfc', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2017, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6d33', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2018, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6d6a', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2019, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6da1', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2020, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6dd8', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2021, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6e0f', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2022, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6e47', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2023, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6e82', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2024, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6eb9', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2025, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6ef1', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2026, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6f28', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2027, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6f62', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2028, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6f99', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2029, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a6fd0', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2030, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7007', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2031, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a703e', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2032, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7075', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2033, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a70ac', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2034, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a70e3', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2035, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7119', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2036, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7150', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2037, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7187', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2038, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a71be', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2039, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a71f5', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2040, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a72bb', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2041, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a72fe', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2042, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7337', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2043, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a736f', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2044, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a73b4', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2045, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a73f2', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2046, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a742d', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2047, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7469', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2048, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a74a3', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2049, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a74e7', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2050, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7520', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2051, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7559', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2052, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7592', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2053, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a75cb', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2054, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7629', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2055, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a766a', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2056, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a76a2', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2057, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a771e', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2058, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7759', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2059, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7791', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2060, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a77c9', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2061, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a783a', 48, '2010-04-14 09:17:16', 'AVAILABLE', 0),
(2062, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7872', 48, '2010-04-14 09:17:16', 'PICKED', 17),
(2063, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a78aa', 48, '2010-04-14 09:17:16', 'PICKED', 17),
(2064, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a78e1', 48, '2010-04-14 09:17:16', 'PICKED', 17),
(2065, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7919', 48, '2010-04-14 09:17:16', 'PICKED', 17),
(2066, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7950', 48, '2010-04-14 09:17:16', 'PICKED', 17),
(2067, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7987', 48, '2010-04-14 09:17:16', 'PICKED', 17),
(2068, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a79c5', 48, '2010-04-14 09:17:16', 'PICKED', 17),
(2069, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7a00', 48, '2010-04-14 09:17:16', 'PICKED', 17),
(2070, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7a37', 48, '2010-04-14 09:17:16', 'PICKED', 17),
(2071, NULL, 'N100A', 'WH', 1, NULL, '0', '0', '4bc57a02a7a6e', 48, '2010-04-14 09:17:16', 'PICKED', 17);

-- --------------------------------------------------------

--
-- Table structure for table `0_shippers`
--

CREATE TABLE IF NOT EXISTS `0_shippers` (
  `shipper_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipper_name` varchar(60) NOT NULL DEFAULT '',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `phone2` varchar(30) NOT NULL DEFAULT '',
  `contact` tinytext NOT NULL,
  `address` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shipper_id`),
  UNIQUE KEY `name` (`shipper_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `0_shippers`
--

INSERT INTO `0_shippers` (`shipper_id`, `shipper_name`, `phone`, `phone2`, `contact`, `address`, `inactive`) VALUES
(1, 'Self', '', '', 'Self', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sql_trail`
--

CREATE TABLE IF NOT EXISTS `0_sql_trail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sql` text NOT NULL,
  `result` tinyint(1) NOT NULL,
  `msg` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `0_sql_trail`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_stock_category`
--

CREATE TABLE IF NOT EXISTS `0_stock_category` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `0_stock_category`
--

INSERT INTO `0_stock_category` (`category_id`, `description`, `dflt_tax_type`, `dflt_units`, `dflt_mb_flag`, `dflt_sales_act`, `dflt_cogs_act`, `dflt_inventory_act`, `dflt_adjustment_act`, `dflt_assembly_act`, `dflt_dim1`, `dflt_dim2`, `inactive`, `dflt_no_sale`) VALUES
(1, 'Airtime Cards', 1, 'ea.', 'B', '4010', '5010', '1510', '5040', '1530', 1, 0, 0, 0),
(2, 'Phone', 1, 'ea.', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_stock_master`
--

CREATE TABLE IF NOT EXISTS `0_stock_master` (
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
  `serializable` tinyint(3) unsigned DEFAULT NULL COMMENT 'can be serialized to inventory',
  `unitconversion` tinytext NOT NULL COMMENT 'added for laolu',
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_stock_master`
--

INSERT INTO `0_stock_master` (`stock_id`, `category_id`, `tax_type_id`, `description`, `long_description`, `units`, `mb_flag`, `sales_account`, `cogs_account`, `inventory_account`, `adjustment_account`, `assembly_account`, `dimension_id`, `dimension2_id`, `actual_cost`, `last_cost`, `material_cost`, `labour_cost`, `overhead_cost`, `inactive`, `no_sale`, `serializable`, `unitconversion`) VALUES
('M100A', 1, 1, 'Manufactured N100 Airtime Card', 'N100 Airtime Card', 'ea.', 'M', '4010', '1530', '1510', '5040', '1530', 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, ''),
('MOBILEKIT', 2, 1, 'Ready To-Go Mobile Phone &amp; Kits', 'Ready To-Go Mobile Phone &amp; Kits', 'ea.', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 980, 0, 0, 0, 0, 1, ''),
('N100A', 1, 1, 'N100 Airtime Card', 'N100 Airtime Card', 'ea.', 'B', '4010', '5010', '1510', '5040', '1530', 1, 0, 0, 0, 84, 0, 0, 0, 0, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `0_stock_moves`
--

CREATE TABLE IF NOT EXISTS `0_stock_moves` (
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
  `order_no` int(11) DEFAULT NULL,
  `serialized` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`trans_id`),
  KEY `type` (`type`,`trans_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `0_stock_moves`
--

INSERT INTO `0_stock_moves` (`trans_id`, `trans_no`, `stock_id`, `type`, `loc_code`, `tran_date`, `person_id`, `price`, `reference`, `qty`, `discount_percent`, `standard_cost`, `visible`, `order_no`, `serialized`) VALUES
(1, 1, 'N100A', 25, 'ARR', '2010-02-22', 1, 84, '', 500, 0, 84, 1, NULL, NULL),
(2, 1, 'N100A', 16, 'ARR', '2010-02-22', 2, 0, '1', -100, 0, 0, 1, NULL, NULL),
(3, 1, 'N100A', 16, 'WH', '2010-02-22', 2, 0, '1', 100, 0, 0, 1, NULL, NULL),
(4, 1, 'N100A', 13, 'WH', '2010-02-22', 0, 90, '1', -7, 0, 84, 1, NULL, NULL),
(5, 2, 'N100A', 13, 'WH', '2010-02-22', 0, 0, '2', 0, 0, 0, 1, NULL, NULL),
(6, 2, 'N100A', 13, 'WH', '2010-02-22', 0, 0, '2', 0, 0, 0, 1, NULL, NULL),
(7, 3, 'N100A', 13, 'WH', '2010-02-22', 0, 90, '3', -2, 0, 84, 1, NULL, NULL),
(8, 2, 'N100A', 13, 'WH', '2010-02-22', 0, 90, '2', -1, 0, 84, 1, NULL, NULL),
(9, 4, 'N100A', 13, 'WH', '2010-02-23', 0, 90, '4', -15, 0, 84, 1, NULL, NULL),
(10, 1, 'N100A', 17, 'IBW', '2010-02-24', 1, 0, '1', 10000, 0, 84, 1, NULL, NULL),
(11, 5, 'N100A', 13, 'IBW', '2010-02-24', 0, 90, '5', -100, 0, 84, 1, NULL, NULL),
(12, 6, 'N100A', 13, 'IBW', '2010-02-25', 0, 90, '6', -800, 0, 84, 1, NULL, NULL),
(13, 7, 'N100A', 13, 'WH', '2010-03-03', 0, 90, '7', -1, 0, 84, 1, NULL, NULL),
(14, 8, 'N100A', 13, 'WH', '2010-03-25', 0, 90, '8', -1, 0, 84, 1, NULL, NULL),
(15, 2, 'N100A', 25, 'WH', '2010-03-30', 1, 84, '', 500, 0, 84, 0, 34, 1),
(16, 3, 'N100A', 25, 'WH', '2010-03-30', 1, 84, '', 500, 0, 84, 0, NULL, 1),
(17, 4, 'N100A', 25, 'WH', '2010-03-30', 1, 84, '', 500, 0, 84, 0, NULL, 1),
(18, 5, 'N100A', 25, 'WH', '2010-03-30', 1, 84, '', 500, 0, 84, 0, NULL, 1),
(19, 6, 'N100A', 25, 'WH', '2010-03-30', 1, 84, '', 500, 0, 84, 0, NULL, 1),
(20, 7, 'N100A', 25, 'WH', '2010-03-30', 1, 84, '', 500, 0, 84, 0, 39, 1),
(21, 9, 'N100A', 13, 'WH', '2010-03-31', 0, 90, '9', -500, 0, 84, 1, 0, 1),
(22, 8, 'N100A', 25, 'WH', '2010-04-02', 1, 84, '', 1000, 0, 84, 0, NULL, 1),
(23, 9, 'N100A', 25, 'WH', '2010-04-02', 1, 84, '', 1000, 0, 84, 0, 41, 1),
(24, 10, 'N100A', 25, 'WH', '2010-04-02', 1, 84, '', 5000, 0, 84, 0, NULL, 1),
(25, 11, 'N100A', 25, 'WH', '2010-04-07', 1, 84, '', 500, 0, 84, 0, 44, 1),
(26, 12, 'N100A', 25, 'WH', '2010-04-07', 1, 84, '', 500, 0, 84, 0, 45, 1),
(27, 13, 'MOBILEKIT', 25, 'WH', '2010-04-08', 1, 980, '', 10, 0, 980, 0, 46, 1),
(28, 10, 'MOBILEKIT', 13, 'WH', '2010-04-08', 0, 1200, '10', -5, 0, 980, 1, 0, 0),
(29, 14, 'N100A', 25, 'WH', '2010-04-14', 1, 84, '', 500, 0, 84, 0, 48, 1),
(30, 15, 'N100A', 25, 'WH', '2010-04-14', 1, 84, '', 500, 0, 84, 0, 47, 1),
(31, 11, 'N100A', 13, 'WH', '2010-04-14', 0, 100, '11', -10, 0, 84, 1, 0, 0),
(32, 12, 'MOBILEKIT', 13, 'WH', '2010-04-14', 0, 1200, '12', -6, 0, 980, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_suppliers`
--

CREATE TABLE IF NOT EXISTS `0_suppliers` (
  `supplier_id` varchar(64) NOT NULL DEFAULT '0',
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_suppliers`
--

INSERT INTO `0_suppliers` (`supplier_id`, `supp_name`, `supp_ref`, `address`, `supp_address`, `phone`, `phone2`, `fax`, `gst_no`, `contact`, `supp_account_no`, `email`, `website`, `bank_account`, `curr_code`, `payment_terms`, `dimension_id`, `dimension2_id`, `tax_group_id`, `credit_limit`, `purchase_account`, `payable_account`, `payment_discount_account`, `notes`, `inactive`) VALUES
('1', 'One Phone Teleco', 'OnePhone', '5, Taylor Street,', '', '234', '', '', '', 'OnePhone', '', '', '', '', 'NGN', 1, 1, 0, 1, 0, '5010', '2100', '5060', 'Test', 0),
('N100A', 'Test Character Suppliers', 'Test', '', '', '+2348099444300', '', '', '', 'Testing Team', '', '', '', '', 'NGN', 1, 0, 0, 1, 0, '5010', '2100', '5060', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_supp_allocations`
--

CREATE TABLE IF NOT EXISTS `0_supp_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amt` double unsigned DEFAULT NULL,
  `date_alloc` date NOT NULL DEFAULT '0000-00-00',
  `trans_no_from` int(11) DEFAULT NULL,
  `trans_type_from` int(11) DEFAULT NULL,
  `trans_no_to` int(11) DEFAULT NULL,
  `trans_type_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `0_supp_allocations`
--

INSERT INTO `0_supp_allocations` (`id`, `amt`, `date_alloc`, `trans_no_from`, `trans_type_from`, `trans_no_to`, `trans_type_to`) VALUES
(1, 44100, '2010-02-22', 1, 22, 1, 20);

-- --------------------------------------------------------

--
-- Table structure for table `0_supp_invoice_items`
--

CREATE TABLE IF NOT EXISTS `0_supp_invoice_items` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `0_supp_invoice_items`
--

INSERT INTO `0_supp_invoice_items` (`id`, `supp_trans_no`, `supp_trans_type`, `gl_code`, `grn_item_id`, `po_detail_item_id`, `stock_id`, `description`, `quantity`, `unit_price`, `unit_tax`, `memo_`) VALUES
(1, 1, 20, '0', 1, 1, 'N100A', 'N100A', 500, 84, 4.2, ''),
(2, 2, 20, '0', 11, 44, 'N100A', 'N100A', 500, 84, 4.2, ''),
(3, 2, 20, '0', 10, 42, 'N100A', 'N100A', 5000, 84, 4.2, ''),
(4, 2, 20, '0', 9, 41, 'N100A', 'N100A', 1000, 84, 4.2, ''),
(5, 2, 20, '0', 8, 40, 'N100A', 'N100A', 1000, 84, 4.2, ''),
(6, 2, 20, '0', 7, 39, 'N100A', 'N100A', 500, 84, 4.2, ''),
(7, 2, 20, '0', 6, 38, 'N100A', 'N100A', 500, 84, 4.2, ''),
(8, 2, 20, '0', 5, 37, 'N100A', 'N100A', 500, 84, 4.2, ''),
(9, 2, 20, '0', 4, 36, 'N100A', 'N100A', 500, 84, 4.2, ''),
(10, 2, 20, '0', 3, 35, 'N100A', 'N100A', 500, 84, 4.2, ''),
(11, 2, 20, '0', 2, 34, 'N100A', 'N100A', 500, 84, 4.2, '');

-- --------------------------------------------------------

--
-- Table structure for table `0_supp_trans`
--

CREATE TABLE IF NOT EXISTS `0_supp_trans` (
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `supplier_id` varchar(64) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `0_supp_trans`
--

INSERT INTO `0_supp_trans` (`trans_no`, `type`, `supplier_id`, `reference`, `supp_reference`, `tran_date`, `due_date`, `ov_amount`, `ov_discount`, `ov_gst`, `rate`, `alloc`) VALUES
(1, 20, '1', '1', '3456', '2010-02-22', '2010-02-28', 42000, 0, 2100, 1, 44100),
(1, 22, '1', '1', '', '2010-02-22', '2010-02-22', -44100, 0, 0, 1, 44100),
(2, 20, '1', '2', 'xy123', '2010-04-07', '2010-04-30', 882000, 0, 44100, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sys_types`
--

CREATE TABLE IF NOT EXISTS `0_sys_types` (
  `type_id` smallint(6) NOT NULL DEFAULT '0',
  `type_no` int(11) NOT NULL DEFAULT '1',
  `next_reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_sys_types`
--

INSERT INTO `0_sys_types` (`type_id`, `type_no`, `next_reference`) VALUES
(0, 19, '1'),
(1, 8, '1'),
(2, 5, '2'),
(4, 3, '1'),
(10, 19, '7'),
(11, 3, '1'),
(12, 6, '7'),
(13, 5, '13'),
(16, 2, '2'),
(17, 2, '2'),
(18, 1, '48'),
(20, 8, '3'),
(21, 1, '1'),
(22, 4, '2'),
(25, 1, '16'),
(26, 1, '1'),
(28, 1, '1'),
(29, 1, '1'),
(30, 5, '18'),
(32, 0, '18'),
(35, 1, '1'),
(40, 1, '1001.0000.0000'),
(1001, 1001, '1');

-- --------------------------------------------------------

--
-- Table structure for table `0_tags`
--

CREATE TABLE IF NOT EXISTS `0_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `0_tags`
--

INSERT INTO `0_tags` (`id`, `type`, `name`, `description`, `inactive`) VALUES
(1, 2, '1', 'Cost Center', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_tag_associations`
--

CREATE TABLE IF NOT EXISTS `0_tag_associations` (
  `record_id` varchar(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  UNIQUE KEY `record_id` (`record_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_tag_associations`
--

INSERT INTO `0_tag_associations` (`record_id`, `tag_id`) VALUES
('2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `0_tax_groups`
--

CREATE TABLE IF NOT EXISTS `0_tax_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `tax_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `0_tax_groups`
--

INSERT INTO `0_tax_groups` (`id`, `name`, `tax_shipping`, `inactive`) VALUES
(1, 'Tax', 0, 0),
(2, 'Tax Exempt', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_tax_group_items`
--

CREATE TABLE IF NOT EXISTS `0_tax_group_items` (
  `tax_group_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`tax_group_id`,`tax_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_tax_group_items`
--

INSERT INTO `0_tax_group_items` (`tax_group_id`, `tax_type_id`, `rate`) VALUES
(1, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `0_tax_types`
--

CREATE TABLE IF NOT EXISTS `0_tax_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate` double NOT NULL DEFAULT '0',
  `sales_gl_code` varchar(11) NOT NULL DEFAULT '',
  `purchasing_gl_code` varchar(11) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `0_tax_types`
--

INSERT INTO `0_tax_types` (`id`, `rate`, `sales_gl_code`, `purchasing_gl_code`, `name`, `inactive`) VALUES
(1, 5, '2150', '2150', 'VAT', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_trans_approval_mat`
--

CREATE TABLE IF NOT EXISTS `0_trans_approval_mat` (
  `type` varchar(30) NOT NULL DEFAULT 'ALL',
  `for_type` varchar(30) NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`type`,`for_type`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_trans_approval_mat`
--

INSERT INTO `0_trans_approval_mat` (`type`, `for_type`, `description`) VALUES
('ST_PURCHREQ', 'ST_PURCHREQ', NULL),
('ALL', 'ST_PURCHREQ', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `0_trans_tax_details`
--

CREATE TABLE IF NOT EXISTS `0_trans_tax_details` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `0_trans_tax_details`
--

INSERT INTO `0_trans_tax_details` (`id`, `trans_type`, `trans_no`, `tran_date`, `tax_type_id`, `rate`, `ex_rate`, `included_in_price`, `net_amount`, `amount`, `memo`) VALUES
(1, 20, 1, '2010-02-22', 1, 5, 1, 0, 42000, 2100, '3456'),
(2, 13, 1, '2010-02-22', 1, 5, 1, 1, 600, 30, '1'),
(3, 10, 1, '2010-02-22', 1, 5, 1, 1, 600, 30, '1'),
(4, 13, 2, '2010-02-22', 1, 5, 1, 1, 0, 0, '2'),
(5, 13, 2, '2010-02-22', 1, 5, 1, 1, 0, 0, '2'),
(6, 13, 3, '2010-02-22', 1, 5, 1, 1, 171.42857142857, 8.5714285714286, '3'),
(7, 13, 2, '2010-02-22', 1, 5, 1, 1, 85.714285714286, 4.2857142857143, '2'),
(8, 13, 4, '2010-02-23', 1, 5, 1, 1, 1285.7142857143, 64.285714285714, '4'),
(9, 10, 2, '2010-02-23', 1, 5, 1, 1, 1285.7142857143, 64.285714285714, '2'),
(10, 10, 3, '2010-02-22', 1, 5, 1, 1, 257.14285714286, 12.857142857143, '3'),
(11, 13, 5, '2010-02-24', 1, 5, 1, 1, 8571.4285714286, 428.57142857143, '5'),
(12, 10, 4, '2010-02-24', 1, 5, 1, 1, 8571.4285714286, 428.57142857143, '4'),
(13, 13, 6, '2010-02-25', 1, 5, 1, 1, 68571.428571429, 3428.5714285714, '6'),
(14, 10, 5, '2010-02-25', 1, 5, 1, 1, 68571.428571429, 3428.5714285714, '5'),
(15, 13, 7, '2010-03-03', 1, 5, 1, 1, 85.714285714286, 4.2857142857143, '7'),
(16, 10, 6, '2010-03-03', 1, 5, 1, 1, 85.714285714286, 4.2857142857143, '6'),
(17, 13, 8, '2010-03-25', 1, 5, 1, 1, 85.714285714286, 4.2857142857143, '8'),
(18, 13, 9, '2010-03-31', 1, 5, 1, 1, 42857.142857143, 2142.8571428571, '9'),
(19, 20, 2, '2010-04-07', 1, 5, 1, 0, 882000, 44100, 'xy123'),
(20, 13, 10, '2010-04-08', 1, 5, 1, 1, 5714.2857142857, 285.71428571429, '10'),
(21, 13, 11, '2010-04-14', 1, 5, 1, 1, 952.38095238095, 47.619047619048, '11'),
(22, 13, 12, '2010-04-14', 1, 5, 1, 1, 6857.1428571429, 342.85714285714, '12');

-- --------------------------------------------------------

--
-- Table structure for table `0_useronline`
--

CREATE TABLE IF NOT EXISTS `0_useronline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(15) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL DEFAULT '',
  `file` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `0_useronline`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_users`
--

CREATE TABLE IF NOT EXISTS `0_users` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `0_users`
--

INSERT INTO `0_users` (`id`, `user_id`, `password`, `real_name`, `role_id`, `phone`, `email`, `language`, `date_format`, `date_sep`, `tho_sep`, `dec_sep`, `theme`, `page_size`, `prices_dec`, `qty_dec`, `rates_dec`, `percent_dec`, `show_gl`, `show_codes`, `show_hints`, `last_visit_date`, `query_size`, `graphic_links`, `pos`, `print_profile`, `rep_popup`, `sticky_doc_date`, `startup_tab`, `inactive`) VALUES
(1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'Administrator', 2, '', 'sysmon999@gmail.com', 'en_GB', 1, 0, 0, 0, 'default', 'Letter', 2, 2, 4, 1, 1, 0, 0, '2010-04-15 12:17:50', 10, 1, 1, '', 1, 0, 'orders', 0),
(2, 'demouser', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Demo User', 11, '999-999-999', 'demo@demo.nu', 'en_GB', 0, 0, 0, 0, 'default', 'Letter', 2, 2, 3, 1, 1, 0, 0, '2008-02-06 19:02:35', 10, 1, 1, '', 1, 0, 'orders', 0),
(3, 'POSAgent1', '50382a6dd992239d450f5d6a828c5b4e', 'POS Agent 1', 12, '', '', 'en_GB', 0, 0, 0, 0, 'default', 'Letter', 2, 2, 4, 1, 1, 0, 0, '2010-02-14 00:50:44', 10, 1, 1, '', 1, 0, 'orders', 0),
(4, 'sola', '5f4dcc3b5aa765d61d8327deb882cf99', 'Sola Ade', 13, '', '', 'en_GB', 1, 0, 0, 0, 'default', 'Letter', 2, 2, 4, 1, 1, 0, 0, '2010-03-09 11:47:53', 10, 1, 1, '', 1, 0, 'orders', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_voided`
--

CREATE TABLE IF NOT EXISTS `0_voided` (
  `type` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `memo_` tinytext NOT NULL,
  UNIQUE KEY `id` (`type`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `0_voided`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_workcentres`
--

CREATE TABLE IF NOT EXISTS `0_workcentres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL DEFAULT '',
  `description` char(50) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `0_workcentres`
--

INSERT INTO `0_workcentres` (`id`, `name`, `description`, `inactive`) VALUES
(1, 'Assembly Line 1', 'Assembly Line 1 for Production', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_workorders`
--

CREATE TABLE IF NOT EXISTS `0_workorders` (
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

--
-- Dumping data for table `0_workorders`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_wo_issues`
--

CREATE TABLE IF NOT EXISTS `0_wo_issues` (
  `issue_no` int(11) NOT NULL AUTO_INCREMENT,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `loc_code` varchar(5) DEFAULT NULL,
  `workcentre_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`issue_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `0_wo_issues`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_wo_issue_items`
--

CREATE TABLE IF NOT EXISTS `0_wo_issue_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(40) DEFAULT NULL,
  `issue_id` int(11) DEFAULT NULL,
  `qty_issued` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `0_wo_issue_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_wo_manufacture`
--

CREATE TABLE IF NOT EXISTS `0_wo_manufacture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(100) DEFAULT NULL,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `0_wo_manufacture`
--


-- --------------------------------------------------------

--
-- Table structure for table `0_wo_requirements`
--

CREATE TABLE IF NOT EXISTS `0_wo_requirements` (
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

--
-- Dumping data for table `0_wo_requirements`
--

