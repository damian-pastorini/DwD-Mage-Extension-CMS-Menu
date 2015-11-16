<?php
/*
* Name Extension: Cmsmart megamenu
* Author: The Cmsmart Development Team 
* Date Created: 06/09/2013
* Websites: http://cmsmart.net
* Technical Support: Forum - http://cmsmart.net/support
* GNU General Public License v3 (http://opensource.org/licenses/GPL-3.0)
* Copyright © 2011-2013 Cmsmart.net. All Rights Reserved.
*/
$installer = $this;
$installer->startSetup();
$installer->run("
   DROP TABLE IF EXISTS {$this->getTable('admin_menutop')};
   CREATE TABLE {$this->getTable('admin_menutop')}(
  `adminmenutop_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `width_block_left` int(11) NOT NULL DEFAULT '0',
  `width_block_right` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL,
  `numbers_product` varchar(255) NOT NULL DEFAULT '',
  `active_product` int(11) NOT NULL DEFAULT '0',
  `static_block_top` int(11) NOT NULL DEFAULT '0',
  `static_block_bottom` int(11) NOT NULL DEFAULT '0',
  `static_block_left` int(11) NOT NULL DEFAULT '0',
  `static_block_right` int(11) NOT NULL DEFAULT '0',
  `label` varchar(255) NOT NULL DEFAULT '',
  `active_thumbail` int(11) NOT NULL DEFAULT '0',
  `active_static_block` int(11) NOT NULL DEFAULT '0',
  `active_static_block_top` int(11) NOT NULL DEFAULT '0',
  `active_static_block_bottom` int(11) NOT NULL DEFAULT '0',
  `active_static_block_left` int(11) NOT NULL DEFAULT '0',
  `active_static_block_right` int(11) NOT NULL DEFAULT '0',
  `active_label` int(11) NOT NULL DEFAULT '0',
  `level_column_count` int(11) NOT NULL DEFAULT '0',
  `category_children` int(11) NOT NULL DEFAULT '0',
  `font-color` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `background-color` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (adminmenutop_id), 
  UNIQUE KEY (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
$installer->endSetup(); 
