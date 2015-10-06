<?php

/**
 *
 * DwD-CmsMenu - Magento Extension
 *
 * @copyright Copyright (c) 2015 DwDesigner Inc. (http://www.dwdeveloper.com/)
 * @author Damian A. Pastorini - damian.pastorini@dwdeveloper.com
 *
 */

class DwD_CmsMenu_Block_Adminhtml_Cms_Page_Edit_Tab_Content extends Mage_Adminhtml_Block_Widget_Form
{

    public function prepareForm($observer)
    {
        $isEnabled = Mage::getStoreConfig('dwd_cmsmenu/general/enabled');
        if($isEnabled) {
            $form = $observer->getForm();
            $menuFieldset = $form->addFieldset('menu_fieldset', array('legend' => Mage::helper('cms')->__('Top Menu')));
            $yesnoSource = Mage::getModel('adminhtml/system_config_source_yesno')->toArray();
            $menuFieldset->addField('show_in_menu', 'select', array(
                'label' => Mage::helper('cms')->__('Show in Menu'),
                'title' => Mage::helper('cms')->__('Show in Menu'),
                'name' => 'show_in_menu',
                'options' => $yesnoSource,
            ));
            $menuFieldset->addField('menu_item_title', 'text', array(
                'label' => Mage::helper('cms')->__('Menu Title'),
                'name' => 'menu_item_title',
                'note' => Mage::helper('cms')->__('If empty the item name will be the page title.'),
            ));
            $fathersList = Mage::helper('dwd_cmsmenu')->getFathersList();
            $menuFieldset->addField('child_of', 'select', array(
                'label' => Mage::helper('cms')->__('Show as child of'),
                'title' => Mage::helper('cms')->__('Show as child of'),
                'name' => 'child_of',
                'values' => $fathersList,
                'note' => Mage::helper('cms')->__('If empty the item will be displayed in the top level.'),
            ));
            $menuFieldset->addField('add_before', 'select', array(
                'label' => Mage::helper('cms')->__('Add before'),
                'title' => Mage::helper('cms')->__('Add before'),
                'name' => 'add_before',
                'values' => $fathersList,
                'note' => Mage::helper('cms')->__('If empty the item will be added as last. If you have multiple items without this value those will be added at the end ordered by the identifier.'),
            ));
        }
    }

}
