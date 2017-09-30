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

// run query:
$installer->getConnection()
    ->addColumn($installer->getTable('dwd_cmsmenu/cmsmenu'), 'level', array(
        'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable' => false,
        'unsigned' => true,
        'length' => 6,
        'comment' => 'CMS Menu item tree level.'
    ));

// load old cmsmenu items:
$cmsMenuCollection = Mage::getModel('dwd_cmsmenu/cmsmenu')->getCollection();

// loop and fix:
foreach($cmsMenuCollection as $c) {
    $childOf = $c->getChildOf();
    $level = 0;
    if($childOf) {
        if(strpos($childOf, 'c-')!==false) {
            // get the category id:
            $categoryId = substr($childOf, 2);
            // load the category:
            $category = Mage::getModel('catalog/category')->load($categoryId);
            // get the level:
            $categoryLevel = $category->getLevel();
            // calculate the cms page level by removing 1 level to the category:
            $level = $categoryLevel-1; // category level - base category level (2) + 1 that will be the next level
        } else {
            $level++;
            $level = Mage::helper('dwd_cmsmenu')->getTreeLevel($childOf, $level, true);
        }
    }
    $c->setLevel($level);
    $c->save();
}

$installer->endSetup();
