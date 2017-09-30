<?php

/**
 *
 * DwD-CmsMenu - Magento Extension
 *
 * @copyright Copyright (c) 2017 DwDeveloper (http://www.dwdeveloper.com/)
 * @author Damian A. Pastorini - damian.pastorini@dwdeveloper.com
 *
 */

$installer = $this;

$installer->startSetup();

// cmsmenu table:
$table = $this->getTable('dwd_cmsmenu/cmsmenu');

// run query:
$installer->run("CREATE TABLE IF NOT EXISTS {$table} (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`cms_page_id` SMALLINT(6) NOT NULL,
	`show_in_menu` SMALLINT(6) NOT NULL,
	`child_of` VARCHAR(255) NULL DEFAULT NULL,
	`add_before` VARCHAR(255) NULL DEFAULT NULL,
	`menu_item_title` VARCHAR(255) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `cms_page_id` (`cms_page_id`),
	CONSTRAINT `FK_cmsmenu_cms_page` FOREIGN KEY (`cms_page_id`) REFERENCES `cms_page` (`page_id`)
)
ENGINE=InnoDB;");

$installer->endSetup();
