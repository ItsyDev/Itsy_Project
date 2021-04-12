CREATE TABLE IF NOT EXISTS `list_division` (
  `division_id` int(11) NOT NULL AUTO_INCREMENT,
  `division_name` varchar(50) NOT NULL,
  PRIMARY KEY (`division_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `list_access_control` (
  `access_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_level` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `level_name` varchar(50) NOT NULL,
  PRIMARY KEY (`access_id`),
  KEY `FK_list_access_control_list_division` (`division_id`),
  CONSTRAINT `FK_list_access_control_list_division` FOREIGN KEY (`division_id`) REFERENCES `list_division` (`division_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `list_user_status` (
  `user_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_status` varchar(50) NOT NULL,
  PRIMARY KEY (`user_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `list_category_toko` (
  `category_toko_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_toko` varchar(100) NOT NULL,
  PRIMARY KEY (`category_toko_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `list_toko` (
  `toko_id` int(11) NOT NULL AUTO_INCREMENT,
  `toko_name` varchar(150) NOT NULL,
  `toko_address` text NOT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 0,
  `category_toko_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`toko_id`),
  UNIQUE KEY `toko_name` (`toko_name`),
  KEY `FK_list_toko_list_toko_category` (`category_toko_id`),
  CONSTRAINT `FK_list_toko_list_toko_category` FOREIGN KEY (`category_toko_id`) REFERENCES `list_category_toko` (`category_toko_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `list_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_fullname` varchar(200) NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `user_address` text DEFAULT NULL,
  `user_status_id` int(11) NOT NULL,
  `access_id` int(11) NOT NULL,
  `last_active` datetime DEFAULT NULL,
  `user_photo` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`),
  KEY `FK_list_user_list_access_control` (`access_id`),
  KEY `FK_list_user_list_user_status` (`user_status_id`),
  CONSTRAINT `FK_list_user_list_access_control` FOREIGN KEY (`access_id`) REFERENCES `list_access_control` (`access_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_list_user_list_user_status` FOREIGN KEY (`user_status_id`) REFERENCES `list_user_status` (`user_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `rel_user_toko` (
  `rel_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `toko_id` int(11) NOT NULL,
  `is_owner` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`rel_id`),
  KEY `FK__list_toko` (`toko_id`),
  KEY `FK_rel_user_toko_list_user` (`user_id`),
  CONSTRAINT `FK__list_toko` FOREIGN KEY (`toko_id`) REFERENCES `list_toko` (`toko_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_rel_user_toko_list_user` FOREIGN KEY (`user_id`) REFERENCES `list_user` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `list_product_category` (
  `product_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `toko_id` int(11) NOT NULL,
  `product_category` varchar(150) NOT NULL,
  PRIMARY KEY (`product_category_id`),
  KEY `FK_list_product_category_list_toko` (`toko_id`),
  CONSTRAINT `FK_list_product_category_list_toko` FOREIGN KEY (`toko_id`) REFERENCES `list_toko` (`toko_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Added date 14 March 2021
INSERT INTO `list_category_toko` (`category_toko_id`, `category_toko`) VALUES
	(1, 'Toko Fashion'),
	(2, 'Toko Makanan'),
	(3, 'Toko Barang');

INSERT INTO `list_user_status` (`user_status_id`, `user_status`) VALUES
	(1, 'Active'),
	(2, 'Non Active'),
	(3, 'Banned');

INSERT INTO `list_division` (`division_id`, `division_name`) VALUES
	(1, 'Super Admin'),
	(2, 'Owner Business'),
	(3, 'Staff'),
	(4, 'Agen');

INSERT INTO `list_access_control` (`access_id`, `admin_level`, `division_id`, `level_name`) VALUES
	(1, 100, 1, 'Super Admin'),
	(2, 90, 2, 'Owner'),
	(3, 80, 3, 'Staff'),
	(4, 50, 4, 'Reseller');

-- Added date 20 March 2021
-- CREATE TABLE IF NOT EXISTS `list_menu` (
--   `menu_id` int(11) NOT NULL AUTO_INCREMENT,
--   `menu_name` varchar(50) NOT NULL,
--   `menu_icon` varchar(20) NOT NULL,
--   `menu_link` varchar(50) NOT NULL,
--   `is_dropdown` tinyint(4) NOT NULL DEFAULT 0,
--   PRIMARY KEY (`menu_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- CREATE TABLE IF NOT EXISTS `list_sub_menu` (
--   `sub_menu_id` int(11) NOT NULL AUTO_INCREMENT,
--   `menu_id` int(11) NOT NULL,
--   `sub_name` varchar(50) NOT NULL,
--   `sub_link` varchar(50) NOT NULL,
--   PRIMARY KEY (`sub_menu_id`),
--   KEY `FK__list_menu` (`menu_id`),
--   CONSTRAINT `FK__list_menu` FOREIGN KEY (`menu_id`) REFERENCES `list_menu` (`menu_id`) ON UPDATE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- CREATE TABLE IF NOT EXISTS `list_module` (
--   `module_id` int(11) NOT NULL AUTO_INCREMENT,
--   `menu_id` int(11) DEFAULT NULL,
--   `module` varchar(50) NOT NULL,
--   PRIMARY KEY (`module_id`),
--   KEY `FK_list_module_list_menu` (`menu_id`),
--   CONSTRAINT `FK_list_module_list_menu` FOREIGN KEY (`menu_id`) REFERENCES `list_menu` (`menu_id`) ON UPDATE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- CREATE TABLE IF NOT EXISTS `list_log` (
--   `log_id` int(11) NOT NULL AUTO_INCREMENT,
--   `user_id` int(11) NOT NULL,
--   `module_id` int(11) NOT NULL,
--   `description` text DEFAULT NULL,
--   `data_ref` mediumtext DEFAULT NULL,
--   `action_date` datetime NOT NULL,
--   `operating_system` varchar(150) NOT NULL,
--   `ip_address` varchar(50) NOT NULL,
--   `browser` varchar(100) NOT NULL,
--   PRIMARY KEY (`log_id`),
--   KEY `user_id` (`user_id`),
--   KEY `module_id` (`module_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- CREATE TABLE IF NOT EXISTS `rel_user_module` (
--   `rel_id` int(11) NOT NULL AUTO_INCREMENT,
--   `user_id` int(11) NOT NULL,
--   `module_id` int(11) NOT NULL,
--   `is_allow` tinyint(4) NOT NULL DEFAULT 1,
--   PRIMARY KEY (`rel_id`),
--   KEY `FK__list_user` (`user_id`),
--   KEY `FK__list_module` (`module_id`),
--   CONSTRAINT `FK__list_module` FOREIGN KEY (`module_id`) REFERENCES `list_module` (`module_id`) ON UPDATE CASCADE,
--   CONSTRAINT `FK__list_user` FOREIGN KEY (`user_id`) REFERENCES `list_user` (`user_id`) ON UPDATE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Added date 29 March 2021
CREATE TABLE IF NOT EXISTS `list_supplier` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `toko_id` int(11) NOT NULL,
  `supplier_name` varchar(200) NOT NULL,
  `supplier_phone` varchar(200) NOT NULL,
  `supplier_address` text NOT NULL,
  `supplier_note` text NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`supplier_id`),
  KEY `toko_id` (`toko_id`),
  CONSTRAINT `list_supplier_ibfk_1` FOREIGN KEY (`toko_id`) REFERENCES `list_toko` (`toko_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- Added date 7 April 2021
CREATE TABLE IF NOT EXISTS `list_customer` (
  `customer_id` int(10) NOT NULL AUTO_INCREMENT,
  `toko_id` int(11) NOT NULL,
  `access_id` int(11) DEFAULT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_note` text NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`customer_id`),
  KEY `FK_list_customer_list_toko` (`toko_id`),
  KEY `FK_list_customer_list_access_control` (`access_id`),
  CONSTRAINT `FK_list_customer_list_access_control` FOREIGN KEY (`access_id`) REFERENCES `list_access_control` (`access_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_list_customer_list_toko` FOREIGN KEY (`toko_id`) REFERENCES `list_toko` (`toko_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Added date 8 April 2021
CREATE TABLE IF NOT EXISTS `list_customer_address_type` (
  `address_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `address_type_` varchar(50) NOT NULL,
  PRIMARY KEY (`address_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `list_customer_address_type` (`address_type_id`, `address_type_`) VALUES
	(1, 'Alamat Pengiriman'),
	(2, 'Alamat Penagihan');

CREATE TABLE IF NOT EXISTS `list_customer_address` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `address_type_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `province_name` varchar(100) NOT NULL,
  `district_id` int(11) NOT NULL,
  `district_name` varchar(200) NOT NULL,
  `subdistrict_id` int(11) NOT NULL,
  `subdistrict_name` varchar(200) NOT NULL,
  `full_address` text NOT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`address_id`),
  KEY `FK_list_customer_address_list_customer` (`customer_id`),
  KEY `FK_list_customer_address_list_customer_address_type` (`address_type_id`),
  CONSTRAINT `FK_list_customer_address_list_customer` FOREIGN KEY (`customer_id`) REFERENCES `list_customer` (`customer_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_list_customer_address_list_customer_address_type` FOREIGN KEY (`address_type_id`) REFERENCES `list_customer_address_type` (`address_type_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Added date 10 April 2021
CREATE TABLE IF NOT EXISTS `list_warehouse` (
  `warehouse_id` int(11) NOT NULL AUTO_INCREMENT,
  `toko_id` int(11) NOT NULL,
  `warehouse_name` varchar(250) NOT NULL,
  `warehouse_phone` varchar(25) NOT NULL,
  `pic_name` varchar(150) NOT NULL,
  `warehouse_note` text NOT NULL,
  `province_id` int(11) NOT NULL,
  `province_name` varchar(200) NOT NULL,
  `district_id` int(11) NOT NULL,
  `district_name` varchar(200) NOT NULL,
  `subdistrict_id` int(11) NOT NULL,
  `subdistrict_name` varchar(200) NOT NULL,
  `full_address` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_create` int(11) NOT NULL,
  `user_update` int(11) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`warehouse_id`),
  KEY `user_create` (`user_create`),
  KEY `user_update` (`user_update`),
  KEY `FK_list_warehouse_list_toko` (`toko_id`),
  CONSTRAINT `FK_list_warehouse_list_toko` FOREIGN KEY (`toko_id`) REFERENCES `list_toko` (`toko_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;