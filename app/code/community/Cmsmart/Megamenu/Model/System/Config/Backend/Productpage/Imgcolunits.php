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
class Cmsmart_ThemeSetting_Model_System_Config_Backend_Productpage_ImgColUnits extends Mage_Core_Model_Config_Data
{	
	public function _afterSave()
    {
		//Get the saved value
		$value = $this->getValue();
		
		//Get the value from config (previous value)
		$oldValue = $this->getOldValue();
		
		if ($value != $oldValue)
		{
			Mage::getSingleton('adminhtml/session')->addNotice(
				Mage::helper('themesetting')->__('"Image Column Width" has changed (previous value: %s). Adjust the "Main Image Width" value in System > Configuration > Zoom > Image Size', $oldValue)
			);
		}
		
        return parent::_afterSave();
    }
}
