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
class Cmsmart_ThemeSetting_Block_Adminhtml_System_Config_Form_Fieldset_Cssgen extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	/**
     * Generate CSS
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return String
     */
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		$elementOriginalData = $element->getOriginalData();
		if (isset($elementOriginalData['process']))
		{
			$name = $elementOriginalData['process'];
		}
		else
		{
			return '<div>Action was not specified</div>';
		}

		$website = Mage::app()->getRequest()->getParam('website');
		$store = Mage::app()->getRequest()->getParam('store');
		$url = $this->getUrl('themesetting/adminhtml_cssgen/' . $name, array('website'=>$website, 'store'=>$store));
		
		$buttonSuffix = '';
		if ($store)
			$buttonSuffix = ' for "' . Mage::app()->getStore($store)->getName() . '" store view';
		elseif ($website)
			$buttonSuffix = ' for "' . Mage::app()->getWebsite($website)->getName() . '" website';
		else
			$buttonSuffix = ' for Default Config';
			
		$html = $this->getLayout()->createBlock('adminhtml/widget_button')
			->setType('button')
			->setClass('generate-css')
			->setLabel('Refresh CSS' . $buttonSuffix)
			->setOnClick("setLocation('$url')")
			->toHtml();
		
        return $html;
    }
}
