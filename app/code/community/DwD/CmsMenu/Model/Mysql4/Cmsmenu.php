<?php

/**
 *
 * DwD-CmsMenu - Magento Extension
 *
 * @copyright Copyright (c) 2017 DwDeveloper (http://www.dwdeveloper.com/)
 * @author Damian A. Pastorini - damian.pastorini@dwdeveloper.com
 *
 */

class DwD_CmsMenu_Model_Mysql4_Cmsmenu extends Mage_Core_Model_Mysql4_Abstract
{

    /**
     * Constructor.
     */
    protected function _construct()
    {
        $this->_init('dwd_cmsmenu/cmsmenu', 'id');
    }

}
