<?php

/**
 *
 * DwD-CmsMenu - Magento Extension
 *
 * @copyright Copyright (c) 2015 DwDesigner Inc. (http://www.dwdeveloper.com/)
 * @author Damian A. Pastorini - damian.pastorini@dwdeveloper.com
 *
 */

$installer = $this;

$installer->startSetup();

// cmsmenu table:
$table = $this->getTable('dwd_cmsmenu/cmsmenu');
// cms pages table:
$tableCmsPages = $this->getTable('cms/page');

// run query:
$installer->run("ALTER TABLE {$table} DROP FOREIGN KEY `FK_cmsmenu_cms_page`;");
$installer->run("ALTER TABLE {$table} ADD CONSTRAINT `FK_cmsmenu_cms_page` FOREIGN KEY (`cms_page_id`) REFERENCES {$tableCmsPages} (`page_id`);");

$installer->endSetup();
