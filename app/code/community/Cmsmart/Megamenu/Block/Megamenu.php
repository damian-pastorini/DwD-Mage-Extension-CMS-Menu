<?php
/*
* Name Extension: Megamenu
* Version: 0.1.0
* Author: The Cmsmart Development Team 
* Date Created: 16/08/2013
* Websites: http://cmsmart.net
* Technical Support: Forum - http://cmsmart.net/support
* GNU General Public License v3 (http://opensource.org/licenses/GPL-3.0)
* Copyright © 2011-2013 Cmsmart.net. All Rights Reserved.
*/
class Cmsmart_Megamenu_Block_Megamenu extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
        return parent::_prepareLayout();
        
    }
    
     public function getAdmintestimonials()     
     { 
        if (!$this->hasData('megamenu')) {
            $this->setData('megamenu', Mage::registry('megamenu'));
        }
        return $this->getData('megamenu');
        
    }
}