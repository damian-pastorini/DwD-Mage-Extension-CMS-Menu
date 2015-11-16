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
class Cmsmart_Megamenu_Model_System_Config_Source_Nav_ColumnCount
{
    public function toOptionArray()
    {
        return array(
            array('value' => 2, 'label' => Mage::helper('megamenu')->__('2 Columns')),
            array('value' => 3, 'label' => Mage::helper('megamenu')->__('3 Columns')),
			array('value' => 4, 'label' => Mage::helper('megamenu')->__('4 Columns'))            
        );
    }
}