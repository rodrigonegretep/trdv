CREATE TABLE IF NOT EXISTS `#__csvi_mapheaders` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`map_id` INT(10) NOT NULL,
	`csvheader` VARCHAR(100) NOT NULL,
	`templateheader` VARCHAR(100) NOT NULL,
	PRIMARY KEY (`id`)
) COMMENT='Contains the mapped fields';

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
) COMMENT='Holds map configurations';

CREATE TABLE IF NOT EXISTS `#__csvi_template_fields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the template field',
  `template_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'The template ID',
  `ordering` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'The order of the field',
  `field_name` varchar(255) NOT NULL COMMENT 'Name for the field',
  `column_header` varchar(255) NOT NULL DEFAULT '' COMMENT 'Header for the column',
  `default_value` varchar(255) NOT NULL DEFAULT '' COMMENT 'Default value for the field',
  `process` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'Process the field',
  `combine` ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Combine the field',
  `sort` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Sort the field',
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Holds the fields for a CSVI template';

CREATE TABLE IF NOT EXISTS `#__csvi_related_categories` (
	`product_sku` VARCHAR(64) NOT NULL,
	`related_cat` TEXT NOT NULL
) COMMENT='Related categories import for CSVI';

ALTER TABLE `#__csvi_replacements`
	ADD COLUMN `ordering` INT(11) NOT NULL DEFAULT '0' AFTER `method`;
	
ALTER TABLE `#__csvi_template_fields`
	ADD COLUMN `file_field_name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Name for the field from the file' AFTER `field_name`;
	
ALTER TABLE `#__csvi_template_fields`
	ADD COLUMN `template_field_name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Name for the field from the template' AFTER `file_field_name`;

ALTER TABLE `#__csvi_template_fields`
	ADD COLUMN `cdata` ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'Use the CDATA tag' AFTER `sort`;
	
ALTER TABLE `#__csvi_template_fields`
	ADD COLUMN `combine_char` VARCHAR(5) NOT NULL DEFAULT '' COMMENT 'The character(s) to combine the fields' AFTER `combine`;

CREATE TABLE IF NOT EXISTS `#__csvi_template_fields_combine` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the cross reference',
	`field_id` VARCHAR(255) NOT NULL COMMENT 'ID of the field',
	`combine_id` VARCHAR(255) NOT NULL COMMENT 'ID of the field to combine',
	PRIMARY KEY (`id`)
) COMMENT='Holds the replacement cross reference for a CSVI template field';

CREATE TABLE IF NOT EXISTS `#__csvi_template_fields_replacement` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the cross reference',
	`field_id` VARCHAR(255) NOT NULL COMMENT 'ID of the field',
	`replace_id` VARCHAR(255) NOT NULL COMMENT 'ID of the replacement rule',
	PRIMARY KEY (`id`)
) COMMENT='Holds the replacement cross reference for a CSVI template field';

CREATE TABLE IF NOT EXISTS `#__csvi_template_settings` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the saved setting',
	`name` VARCHAR(255) NOT NULL COMMENT 'Name for the saved setting',
	`settings` TEXT NOT NULL COMMENT 'The actual settings',
	`process` ENUM('import','export') NOT NULL DEFAULT 'import' COMMENT 'The type of template',
	PRIMARY KEY (`id`)
) COMMENT='Stores the template settings for CSVI';

ALTER TABLE `#__csvi_template_types`
	ADD COLUMN `published` TINYINT(1) NOT NULL DEFAULT '1' AFTER `options`,
	ADD COLUMN `ordering` INT(11) NULL DEFAULT NULL AFTER `published`;

ALTER TABLE `#__csvi_template_settings` 
  ADD COLUMN `process` ENUM('import','export') NOT NULL DEFAULT 'import' COMMENT 'The type of template' AFTER `settings`;