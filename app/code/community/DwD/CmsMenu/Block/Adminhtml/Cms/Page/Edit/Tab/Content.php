<?php

/**
 *
 * DwD-CmsMenu - Magento Extension
 *
 * @copyright Copyright (c) 2017 DwDeveloper (http://www.dwdeveloper.com/)
 * @author Damian A. Pastorini - damian.pastorini@dwdeveloper.com
 *
 */

class DwD_CmsMenu_Block_Adminhtml_Cms_Page_Edit_Tab_Content extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * @param $observer
     */
    public function prepareForm($observer)
    {
        $isEnabled = $this->getConfig('dwd_cmsmenu/general/enabled');
        if($isEnabled) {
            $form = $observer->getForm();
            $menuFieldset = $form->addFieldset('menu_fieldset', array('legend' => $this->__('Top Menu')));
            $yesnoSource = $this->getYesNoOptions();
            $menuFieldset->addField('show_in_menu', 'select', array(
                'label' => $this->__('Show in Menu'),
                'title' => $this->__('Show in Menu'),
                'name' => 'show_in_menu',
                'options' => $yesnoSource,
            ));
            $menuFieldset->addField('menu_item_title', 'text', array(
                'label' => $this->__('Menu Title'),
                'name' => 'menu_item_title',
                'note' => $this->__('If empty the item name will be the page title.'),
            ));
            $fathersList = $this->getCmsMenuHelper()->getFathersList();
            $menuFieldset->addField('child_of', 'select', array(
                'label' => $this->__('Show as child of'),
                'title' => $this->__('Show as child of'),
                'name' => 'child_of',
                'values' => $fathersList,
                'note' => $this->__('If empty the item will be displayed in the top level.'),
            ));
            $menuFieldset->addField('add_before', 'select', array(
                'label' => $this->__('Add before'),
                'title' => $this->__('Add before'),
                'name' => 'add_before',
                'values' => $fathersList,
                'note' => $this->__('If empty the item will be added as last. If you have multiple items without this'.
                    ' value those will be added at the end ordered by the identifier.'),
            ));
            $this->dispatchEvent('cmsmenu_add_form_items_after', $observer->getData());
        }
    }

    /**
     * @param $name
     * @param $data
     */
    private function dispatchEvent($name, $data)
    {
        Mage::dispatchEvent($name, $data);
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    public function getCmsMenuHelper()
    {
        return Mage::helper('dwd_cmsmenu');
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getConfig($path)
    {
        return Mage::getStoreConfig($path);
    }

    /**
     * @return array
     */
    public function getYesNoOptions()
    {
        return Mage::getModel('adminhtml/system_config_source_yesno')->toArray();
    }

}
