<?php

/**
 *
 * DwD-CmsMenu - Magento Extension
 *
 * @copyright Copyright (c) 2015 DwDesigner Inc. (http://www.dwdeveloper.com/)
 * @author Damian A. Pastorini - damian.pastorini@dwdeveloper.com
 *
 */

class DwD_CmsMenu_Model_Cmsmenu extends Mage_Core_Model_Abstract
{

    protected $pageObject;

    protected function _construct()
    {
        $this->_init('dwd_cmsmenu/cmsmenu');
    }

    public function loadCmsPageObject()
    {
        $page = Mage::getModel('cms/page');
        $pageId = $this->getCmsPageId();
        if (!is_null($pageId) && $pageId !== $page->getId()) {
            $page->setStoreId(Mage::app()->getStore()->getId());
            if (!$page->load($pageId)) {
                return null;
            }
        }
        if (!$page->getId()) {
            return null;
        }
        $this->pageObject = $page;
    }

    public function getCmsPage()
    {
        return $this->pageObject;
    }

    /**
     * Retrieve page direct URL
     * @return string
     */
    public function getPageUrl()
    {
        if (!$this->pageObject || !$this->pageObject->getId()) {
            return null;
        }
        return Mage::getUrl(null, array('_direct' => $this->pageObject->getIdentifier()));
    }

}
