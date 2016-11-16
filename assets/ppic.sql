-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2016 at 10:07 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ppic`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `DummyData`(IN `size` INT(1) UNSIGNED)
BEGIN
    SET @x = 0;
    REPEAT				
            INSERT INTO t_process VALUES (NULL ,  16,  300, 35000, '300', 1, 1, NULL);
            SET @x=@x+1;
    UNTIL @x >= size END REPEAT;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `GenerateProdCardNew`(`item_id` INT(1) UNSIGNED, `prod_qty` DECIMAL(10,0) UNSIGNED, `prod_lot` VARCHAR(10)) RETURNS tinyint(1) unsigned
BEGIN
DECLARE qty_box, qty_sub_lot, no_sub_lot, no_card, qty_remain DECIMAL(7,0);
DECLARE output tinyint(1);
SELECT m_item_qty_box INTO qty_box FROM m_item WHERE m_item_id = item_id;
IF qty_box IS NULL OR qty_box<1 THEN
    SET output=0;
ELSE
    SET qty_sub_lot=qty_box*6;
    SET no_sub_lot=1;
	SET no_card=1;
	SET qty_remain=prod_qty;
	REPEAT
		IF qty_remain > qty_sub_lot THEN
			REPEAT				
				INSERT INTO t_prod VALUES (NULL ,  prod_lot,  no_sub_lot, no_card, qty_box);
				SET no_card=no_card+1;
			UNTIL no_card >= 7 END REPEAT;
			SET no_card=1;
			SET no_sub_lot=no_sub_lot+1;
			SET qty_remain = qty_remain-qty_sub_lot;
		ELSEIF qty_remain < qty_sub_lot AND qty_remain > qty_box THEN
			REPEAT
				INSERT INTO t_prod VALUES (NULL ,  prod_lot,  no_sub_lot, no_card, qty_box);
				SET no_card=no_card+1;
				SET qty_remain = qty_remain-qty_box;
			UNTIL qty_remain <= qty_box END REPEAT;
		ELSE
			INSERT INTO t_prod VALUES (NULL ,  prod_lot,  no_sub_lot, no_card, qty_remain);
			SET qty_remain = qty_remain-qty_remain;
		END IF;
	UNTIL qty_remain=0 END REPEAT;	
    SET output=1;
END IF;
RETURN output;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `a_group`
--

CREATE TABLE IF NOT EXISTS `a_group` (
  `a_group_id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `a_group_level` int(1) unsigned NOT NULL,
  `a_group_menu` int(1) unsigned NOT NULL,
  `a_group_status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`a_group_id`),
  KEY `a_group_level` (`a_group_level`),
  KEY `a_group_menu` (`a_group_menu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=105 ;

--
-- Dumping data for table `a_group`
--

INSERT INTO `a_group` (`a_group_id`, `a_group_level`, `a_group_menu`, `a_group_status`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 1, 5, 1),
(6, 1, 6, 1),
(7, 1, 7, 1),
(8, 1, 8, 1),
(9, 1, 9, 1),
(10, 1, 10, 1),
(11, 1, 28, 1),
(12, 1, 35, 1),
(13, 1, 36, 1),
(14, 1, 37, 1),
(15, 1, 38, 1),
(16, 1, 39, 1),
(17, 1, 40, 1),
(18, 1, 41, 1),
(19, 1, 42, 1),
(43, 6, 1, 1),
(44, 6, 2, 1),
(45, 6, 3, 1),
(46, 6, 4, 1),
(47, 6, 5, 1),
(48, 6, 6, 1),
(49, 6, 7, 0),
(50, 6, 8, 1),
(51, 6, 9, 1),
(52, 6, 10, 1),
(53, 6, 28, 1),
(54, 6, 35, 1),
(55, 6, 36, 1),
(56, 6, 37, 1),
(57, 6, 38, 0),
(58, 6, 39, 1),
(59, 6, 40, 1),
(60, 6, 41, 1),
(61, 6, 42, 1),
(62, 7, 1, 0),
(63, 7, 2, 1),
(64, 7, 3, 1),
(65, 7, 4, 0),
(66, 7, 5, 0),
(67, 7, 6, 0),
(68, 7, 7, 0),
(69, 7, 8, 0),
(70, 7, 9, 0),
(71, 7, 10, 0),
(72, 7, 28, 0),
(73, 7, 35, 0),
(74, 7, 36, 0),
(75, 7, 37, 1),
(76, 7, 38, 0),
(77, 7, 39, 1),
(78, 7, 40, 0),
(79, 7, 41, 0),
(80, 7, 42, 0),
(96, 1, 51, 1),
(97, 6, 51, 1),
(98, 7, 51, 0),
(99, 1, 52, 1),
(100, 6, 52, 1),
(101, 7, 52, 0),
(102, 1, 53, 1),
(103, 6, 53, 0),
(104, 7, 53, 0);

-- --------------------------------------------------------

--
-- Table structure for table `a_level`
--

CREATE TABLE IF NOT EXISTS `a_level` (
  `a_level_id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `a_level_name` varchar(100) NOT NULL,
  PRIMARY KEY (`a_level_id`),
  UNIQUE KEY `a_level_name` (`a_level_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `a_level`
--

INSERT INTO `a_level` (`a_level_id`, `a_level_name`) VALUES
(1, 'Administrator'),
(6, 'Supervisor'),
(7, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `a_menu`
--

CREATE TABLE IF NOT EXISTS `a_menu` (
  `a_menu_id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `a_menu_name` varchar(255) NOT NULL,
  `a_menu_parentId` int(1) unsigned NOT NULL,
  `a_menu_uri` varchar(255) NOT NULL,
  `a_menu_iconCls` varchar(255) NOT NULL,
  `a_menu_type` enum('dialog','messager','tabs','window') NOT NULL,
  PRIMARY KEY (`a_menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Admin Menu' AUTO_INCREMENT=54 ;

--
-- Dumping data for table `a_menu`
--

INSERT INTO `a_menu` (`a_menu_id`, `a_menu_name`, `a_menu_parentId`, `a_menu_uri`, `a_menu_iconCls`, `a_menu_type`) VALUES
(1, 'Master', 0, '', 'icon-master', ''),
(2, 'Transaksi', 0, '', 'icon-transaksi', ''),
(3, 'Report', 0, '', 'icon-print', ''),
(4, 'Admin', 0, '', 'icon-admin', ''),
(5, 'Setting', 0, '', 'icon-setup', ''),
(6, 'Admin User', 4, 'admin/user', 'icon-man', 'tabs'),
(7, 'Admin Menu', 4, 'admin/menu', 'icon-menu', 'tabs'),
(8, 'General', 5, '', 'icon-general', 'dialog'),
(9, 'Master Customer', 1, 'master/customer', 'icon-master', 'tabs'),
(10, 'Master Item', 1, 'master/item', 'icon-master', 'tabs'),
(28, 'Master Vendor', 1, 'master/vendor', 'icon-master', 'tabs'),
(35, 'Master Kategori Proses', 1, 'master/proccat', 'icon-master', 'tabs'),
(36, 'Master Wire, Tools & Washer', 1, 'master/wtw', 'icon-master', 'tabs'),
(37, 'Transaksi PO', 2, 'transaksi/po', 'icon-transaksi', 'tabs'),
(38, 'Admin Group', 4, 'admin/group', 'icon-user', 'tabs'),
(39, 'Report Hasil Produksi Excel', 3, 'report/prodexcel', 'icon-print', 'dialog'),
(40, 'Master Computer', 1, 'master/computer', 'icon-master', 'tabs'),
(41, 'Master Machine', 1, 'master/machine', 'icon-master', 'tabs'),
(42, 'Master Marking', 1, 'master/marking', 'icon-master', 'tabs'),
(51, 'Inquiry', 0, '', 'icon-inquiry', ''),
(52, 'Inquiry Mutasi WIP', 51, 'inquiry/mutasi', 'icon-search-2', 'dialog'),
(53, 'Master Operator', 1, 'master/operator', 'icon-master', 'tabs');

-- --------------------------------------------------------

--
-- Table structure for table `a_user`
--

CREATE TABLE IF NOT EXISTS `a_user` (
  `a_user_id` int(1) NOT NULL AUTO_INCREMENT,
  `a_user_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `a_user_username` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `a_user_password` varchar(100) NOT NULL,
  `a_user_level` int(1) unsigned NOT NULL,
  PRIMARY KEY (`a_user_id`),
  UNIQUE KEY `username` (`a_user_username`),
  KEY `a_user_level` (`a_user_level`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Master User' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `a_user`
--

INSERT INTO `a_user` (`a_user_id`, `a_user_name`, `a_user_username`, `a_user_password`, `a_user_level`) VALUES
(1, 'Agus Setiawan', 'agus', 'c4ca4238a0b923820dcc509a6f75849b', 1),
(2, 'Karyadi', 'kkyadi', 'c4ca4238a0b923820dcc509a6f75849b', 7),
(3, 'Operator', 'operator', 'c4ca4238a0b923820dcc509a6f75849b', 7),
(4, 'Feri Panzer', 'feri', 'c4ca4238a0b923820dcc509a6f75849b', 6);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `a_group`
--
ALTER TABLE `a_group`
  ADD CONSTRAINT `a_group_ibfk_3` FOREIGN KEY (`a_group_menu`) REFERENCES `a_menu` (`a_menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `a_group_ibfk_4` FOREIGN KEY (`a_group_level`) REFERENCES `a_level` (`a_level_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `a_user`
--
ALTER TABLE `a_user`
  ADD CONSTRAINT `a_user_ibfk_1` FOREIGN KEY (`a_user_level`) REFERENCES `a_level` (`a_level_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
