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
            '1' => array(
                'label'=> 'Pages',
                'value' => array()
            ),
            '2' => array(
                'label'=> 'Categories',
                'value' => array()
            ),
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

}
