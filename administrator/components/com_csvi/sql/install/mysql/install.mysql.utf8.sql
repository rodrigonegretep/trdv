CREATE TABLE IF NOT EXISTS `#__csvi_available_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `csvi_name` varchar(255) NOT NULL,
  `component_name` varchar(55) NOT NULL,
  `component_table` varchar(55) NOT NULL,
  `component` varchar(55) NOT NULL,
  `isprimary` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `component_name_table` (`component_name`,`component_table`,`component`)
) CHARSET=utf8 COMMENT='Available fields for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_currency` (
  `currency_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `currency_code` varchar(3) DEFAULT NULL,
  `currency_rate` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`currency_id`),
  UNIQUE KEY `currency_code` (`currency_code`)
) CHARSET=utf8 COMMENT='Curriencies and exchange rates for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_icecat_index` (
  `path` varchar(100) DEFAULT NULL,
  `product_id` int(2) DEFAULT NULL,
  `updated` int(14) DEFAULT NULL,
  `quality` varchar(6) DEFAULT NULL,
  `supplier_id` int(1) DEFAULT NULL,
  `prod_id` varchar(16) DEFAULT NULL,
  `catid` int(3) DEFAULT NULL,
  `m_prod_id` varchar(10) DEFAULT NULL,
  `ean_upc` varchar(10) DEFAULT NULL,
  `on_market` int(1) DEFAULT NULL,
  `country_market` varchar(10) DEFAULT NULL,
  `model_name` varchar(26) DEFAULT NULL,
  `product_view` int(5) DEFAULT NULL,
  `high_pic` varchar(51) DEFAULT NULL,
  `high_pic_size` int(5) DEFAULT NULL,
  `high_pic_width` int(3) DEFAULT NULL,
  `high_pic_height` int(3) DEFAULT NULL,
  `m_supplier_id` int(3) DEFAULT NULL,
  `m_supplier_name` varchar(51) DEFAULT NULL,
  KEY `product_mpn` (`prod_id`),
  KEY `manufacturer_name` (`supplier_id`)
) CHARSET=utf8 COMMENT='ICEcat index data for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_icecat_suppliers` (
  `supplier_id` int(11) unsigned NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  UNIQUE KEY `Unique supplier` (`supplier_id`,`supplier_name`),
  KEY `Supplier name` (`supplier_name`)
) CHARSET=utf8 COMMENT='ICEcat supplier data for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `logstamp` datetime NOT NULL,
  `action` varchar(255) NOT NULL,
  `action_type` varchar(255) NOT NULL DEFAULT '',
  `template_name` varchar(255) DEFAULT NULL,
  `records` int(11) NOT NULL,
  `run_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `run_cancelled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Log results for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_log_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_id` int(11) NOT NULL,
  `line` int(11) NOT NULL,
  `description` text NOT NULL,
  `result` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Log details for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_related_products` (
  `product_sku` varchar(64) NOT NULL,
  `related_sku` text NOT NULL
) CHARSET=utf8 COMMENT='Related products import for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_related_categories` (
	`product_sku` varchar(64) NOT NULL,
	`related_cat` text NOT NULL
) CHARSET=utf8 COMMENT='Related categories import for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_replacements` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `findtext` text NOT NULL,
  `replacetext` text NOT NULL,
  `multivalue` enum('0','1') NOT NULL,
  `method` enum('text','regex') NOT NULL DEFAULT 'text',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned DEFAULT '0',
  `checked_out_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Replacement rules for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Configuration values for CSVI';

INSERT IGNORE INTO `#__csvi_settings` VALUES (1, '');
INSERT IGNORE INTO `#__csvi_settings` VALUES (2, '');

CREATE TABLE IF NOT EXISTS `#__csvi_template_fields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the template field',
  `template_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'The template ID',
  `ordering` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'The order of the field',
  `field_name` varchar(255) NOT NULL COMMENT 'Name for the field',
  `file_field_name` varchar(255) NOT NULL COMMENT 'Name for the field from the file',
  `template_field_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Name for the field from the template',
  `column_header` varchar(255) NOT NULL DEFAULT '' COMMENT 'Header for the column',
  `default_value` varchar(255) NOT NULL DEFAULT '' COMMENT 'Default value for the field',
  `process` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'Process the field',
  `combine` ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Combine the field',
  `combine_char` varchar(5) NOT NULL DEFAULT '' COMMENT 'The character(s) to combine the fields',
  `sort` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Sort the field',
  `cdata` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'Use the CDATA tag',
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Holds the fields for a CSVI template';

CREATE TABLE IF NOT EXISTS `#__csvi_template_fields_combine` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the cross reference',
  `field_id` varchar(255) NOT NULL COMMENT 'ID of the field',
  `combine_id` varchar(255) NOT NULL COMMENT 'ID of the combine rule',
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Holds the combine cross reference for a CSVI template field';

CREATE TABLE IF NOT EXISTS `#__csvi_template_fields_replacement` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the cross reference',
  `field_id` varchar(255) NOT NULL COMMENT 'ID of the field',
  `replace_id` varchar(255) NOT NULL COMMENT 'ID of the replacement rule',
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Holds the replacement cross reference for a CSVI template field';

CREATE TABLE IF NOT EXISTS `#__csvi_template_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the saved setting',
  `name` varchar(255) NOT NULL COMMENT 'Name for the saved setting',
  `settings` text NOT NULL COMMENT 'The actual settings',
  `process` enum('import','export') NOT NULL DEFAULT 'import' COMMENT 'The type of template',
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Stores the template settings for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_template_tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `template_type_name` varchar(55) NOT NULL,
  `template_table` varchar(55) NOT NULL,
  `component` varchar(55) NOT NULL,
  `indexed` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`template_type_name`,`template_table`,`component`)
) CHARSET=utf8 COMMENT='Template tables used per template type for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_template_types` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`checked_out` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`template_type_name` VARCHAR(55) NOT NULL,
	`template_type` VARCHAR(55) NOT NULL,
	`component` VARCHAR(55) NOT NULL COMMENT 'Name of the component',
	`url` VARCHAR(100) NULL DEFAULT NULL COMMENT 'The URL of the page the import is for',
	`options` VARCHAR(255) NOT NULL DEFAULT 'fields' COMMENT 'The template pages to show for the template type',
	`published` TINYINT(1) NOT NULL DEFAULT '1',
	`ordering` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`template_type_name`,`template_type`,`component`)
) CHARSET=utf8 COMMENT='Template types for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_maps` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NULL DEFAULT NULL,
	`mapfile` VARCHAR(100) NULL DEFAULT NULL,
	`action` VARCHAR(100) NULL DEFAULT NULL,
	`component` VARCHAR(100) NULL DEFAULT NULL,
	`operation` VARCHAR(100) NULL DEFAULT NULL,
	`checked_out` INT(10) NULL DEFAULT NULL,
	`checked_out_time` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Holds map configurations';

CREATE TABLE IF NOT EXISTS `#__csvi_mapheaders` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`map_id` INT(10) NOT NULL,
	`csvheader` VARCHAR(100) NOT NULL,
	`templateheader` VARCHAR(100) NOT NULL,
	PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Holds map field mapping';
