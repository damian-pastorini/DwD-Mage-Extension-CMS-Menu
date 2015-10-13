<?php

/**
 *
 * DwD-CmsMenu - Magento Extension
 *
 * @copyright Copyright (c) 2015 DwDesigner Inc. (http://www.dwdeveloper.com/)
 * @author Damian A. Pastorini - damian.pastorini@dwdeveloper.com
 *
 */

class DwD_CmsMenu_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getFathersList()
    {
        // options:
        $options = array(
            '0' => '',
            '1' => array('label'=> 'Pages', 'value' => array()),
            '2' => array('label'=> 'Categories', 'value' => array()),
        );
        // get table name:
        $cmsTable = Mage::getSingleton('core/resource')->getTableName('cmsmenu');
        // get available pages:
        $pagesCollection = Mage::getModel('cms/page')->getCollection();
        $pagesCollection->getSelect()->joinRight(
            array('cmsm' => $cmsTable),
            'main_table.page_id = cmsm.cms_page_id',
            array('cmsmenu_id' => 'cmsm.id')
        );
        foreach ($pagesCollection as $p) {
            $options['1']['value'][] = array ('value'=>$p->getId(), 'label' => $p->getTitle());
        }
        // get available categories:
        $categories = Mage::getModel('catalog/category')
            ->getCollection()
            ->addIsActiveFilter()
            ->addFieldToFilter('level', array('gteq' => 2))
            ->addAttributeToSelect(array('name'));
        foreach ($categories as $c) {
            $options['2']['value'][] = array('value'=>'c-'.$c->getId(), 'label' => $c->getName());
        }
        return $options;
    }

    public function getTreeLevel($childOf, $level = 0, $recursive = false)
    {
        if($childOf) {
            // if is a category child:
            if(strpos($childOf, 'c-')!==false) {
                // get the category id:
                $categoryId = substr($childOf, 2);
                // load the category:
                $category = Mage::getModel('catalog/category')->load($categoryId);
                // get the level:
                $categoryLevel = $category->getLevel();
                if(!$recursive){
                    // calculate the cms page level by removing 1 level to the category:
                    $level = $categoryLevel-1; // category level - base category level (2) + 1 that will be the next level
                } else {
                    $level = $categoryLevel; // if is a recursive request we need to return to category level.
                }
            } else {
                // load the parent item and add 1 to the parent level:
                $cmsMenuItem = Mage::getModel('dwd_cmsmenu/cmsmenu')->load($childOf, 'cms_page_id');
                if(!$recursive) {
                    $level = $cmsMenuItem->getLevel()+1;
                } else {
                    $level++;
                    $level = $this->getTreeLevel($cmsMenuItem->getChildOf(), $level, true);
                }
            }
        }
        return $level;
    }

}
