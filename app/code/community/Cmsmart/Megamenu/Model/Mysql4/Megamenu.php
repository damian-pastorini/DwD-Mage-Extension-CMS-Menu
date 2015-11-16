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
class Cmsmart_Megamenu_Model_Mysql4_Megamenu extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the adminmenutop_id refers to the key field in your database table.
        $this->_init('megamenu/megamenu', 'adminmenutop_id');
    }
}