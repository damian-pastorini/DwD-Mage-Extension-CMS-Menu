<?php

/**
 *
 * DwD-CmsMenu - Magento Extension
 *
 * @copyright Copyright (c) 2015 DwDesigner Inc. (http://www.dwdeveloper.com/)
 * @author Damian A. Pastorini - damian.pastorini@dwdeveloper.com
 *
 */

class DwD_CmsMenu_Model_Mysql4_Cmsmenu_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    public function _construct()
    {
        $this->_init('dwd_cmsmenu/cmsmenu');
    }

    public function addActiveFilter()
    {
        $this->addFieldToFilter('show_in_menu', array('attribute' => 'show_in_menu', 'eq' => 1));
        return $this;
    }

    public function addChildOfFilter($childOf=false)
    {
        if(!$childOf) {
            $this->addFieldToFilter('child_of', array('attribute' => 'child_of', array('eq'=>'0', 'null'=>true)));
        } else {
            $this->addFieldToFilter('child_of', array('attribute' => 'child_of', 'eq' => $childOf));
        }
        return $this;
    }

    public function addBeforeFilter($notNull = true)
    {
        $this->addFieldToFilter('add_before', array('attribute' => 'add_before', 'notnull' => $notNull));
        return $this;
    }

    public function addLevelFilter($level)
    {
        $this->addFieldToFilter('level', array('attribute' => 'level', 'eq' => $level));
        return $this;
    }

    public function setChildOfOrder()
    {
        $this->getSelect()->order('level', self::SORT_ORDER_ASC);
        $this->getSelect()->order(new Zend_Db_Expr('child_of+0'), self::SORT_ORDER_ASC);
        $this->getSelect()->order('child_of', self::SORT_ORDER_ASC);
        return $this;
    }

    public function setAddBeforeOrder()
    {
        $this->getSelect()->order(new Zend_Db_Expr('add_before+0'), self::SORT_ORDER_ASC);
        $this->getSelect()->order('add_before', self::SORT_ORDER_ASC);
        return $this;
    }

}
