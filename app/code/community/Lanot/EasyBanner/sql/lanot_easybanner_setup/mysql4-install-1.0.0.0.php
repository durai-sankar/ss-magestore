<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Lanot
 * @package     Lanot_EasyBanner
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;

$installer->startSetup();

//tables definition
$bannerTable         = $installer->getTable('lanot_easybanner/banner');
$categoryTable       = $installer->getTable('lanot_easybanner/category');
$bannerCategoryTable = $installer->getTable('lanot_easybanner/banner_category');

//create table for banner
$installer->run("
	DROP TABLE IF EXISTS `{$bannerTable}`;
	CREATE TABLE `{$bannerTable}` (
	    `banner_id`  int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Banner ID',
	    `is_active`   tinyint DEFAULT 0,
	    `title`       varchar(255) NOT NULL,
	    `description` mediumtext DEFAULT NULL,
		`path`        varchar(255) DEFAULT NULL,
		`url`         varchar(255) DEFAULT NULL,
		`type`        varchar(8)  DEFAULT NULL,
		`width`       smallint  DEFAULT NULL,
		`height`      smallint  DEFAULT NULL,
		`created_at` timestamp NULL DEFAULT NULL  COMMENT 'Creation Time',
  		`updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update Time',		
		PRIMARY KEY (`banner_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Banners Entity Table';
");


//create table for banner category
$installer->run("
	DROP TABLE IF EXISTS `{$categoryTable}`;
	CREATE TABLE `{$categoryTable}` (
		`category_id`   int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Category ID',
		`is_active`     tinyint DEFAULT 0,
		`title`         varchar(255) DEFAULT NULL,
		`description`   tinytext,		
		`created_at` timestamp NULL DEFAULT NULL COMMENT 'Creation Time',
  		`updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update Time',		
		PRIMARY KEY (`category_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Banner Category Table';
");


//create table for banners to categories
$installer->run("
	DROP TABLE IF EXISTS `{$bannerCategoryTable}`;
	CREATE TABLE `{$bannerCategoryTable}` (
		`banner_id`      int(10) unsigned NOT NULL COMMENT 'Banner Attachment ID',
		`category_id`   int(10) unsigned NOT NULL COMMENT 'Banner Category ID',
		PRIMARY KEY (`banner_id`, `category_id`),
		KEY `IDX_EASYBANER_BANER_ID`    (`banner_id`),
		KEY `IDX_EASYBANER_CATEGORY_ID` (`category_id`),
  		CONSTRAINT `FK_EASYBANNER_CATEGORY_BANER_ID` FOREIGN KEY (`banner_id`) REFERENCES `{$bannerTable}` (`banner_id`) ON DELETE CASCADE ON UPDATE CASCADE,
 		CONSTRAINT `FK_EASYBANNER_CATEGORY_CATEGORY_ID` FOREIGN KEY (`category_id`) REFERENCES `{$categoryTable}` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Banners To Categories Table';
");

$installer->endSetup();
