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
        $options = array(
            '0' => '',
            '1' => array(
                'label'=> 'Pages',
                'value' => array()
            ),
            '2' => array(
                'label'=> 'Categories',
                'value' => array()
            ),
        );
        $pages = Mage::getModel('cms/page')->getCollection();
        foreach ($pages as $p) {
            $options['1']['value'][] = array ('value'=>'cmsmenu-'.$p->getId(), 'label' => $p->getTitle());
        }
        // TODO: add categories filters for status and level.
        $categories = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect(array('name'));
        foreach ($categories as $c) {
            $options['2']['value'][] = array('value'=>'category-node-'.$c->getId(), 'label' => $c->getName());
        }
        return $options;
    }

}
