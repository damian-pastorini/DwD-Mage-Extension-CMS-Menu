<?php
/**
* Name Extension: Megamenu
* Version: 0.1.0
* Author: The Cmsmart Development Team 
* Date Created: 06/09/2013
* Websites: http://cmsmart.net
* Technical Support: Forum - http://cmsmart.net/support
* GNU General Public License v3 (http://opensource.org/licenses/GPL-3.0)
* Copyright © 2011-2013 Cmsmart.net. All Rights Reserved.
*/
class Cmsmart_Megamenu_Block_Catalog_Category_Tabs_Form extends Mage_Adminhtml_Block_Widget_Form
{
	public function getCategory()
    {
        return Mage::registry('current_category');
    }
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
        $this->setForm($form);
	    
     	$fieldset = $form->addFieldset('megamenu_form', array('legend'=>Mage::helper('megamenu')->__('Menu top')));
		
			$this->setTemplate('cmsmart/megamenu/menutop.phtml');
		
		return parent::_prepareForm();
	}
	
	
}
