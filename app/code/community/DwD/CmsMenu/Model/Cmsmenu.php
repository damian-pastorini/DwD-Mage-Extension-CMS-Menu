<?php

/**
 *
 * DwD-CmsMenu - Magento Extension
 *
 * @copyright Copyright (c) 2017 DwDeveloper (http://www.dwdeveloper.com/)
 * @author Damian A. Pastorini - damian.pastorini@dwdeveloper.com
 *
 */

class DwD_CmsMenu_Model_Cmsmenu extends Mage_Core_Model_Abstract
{

    protected $pageObject;

    /**
     * Constructor.
     */
    protected function _construct()
    {
        $this->_init('dwd_cmsmenu/cmsmenu');
    }

    /**
     * @return null
     */
    public function loadCmsPageObject()
    {
        $page = $this->getPageModel();
        $pageId = $this->getCmsPageId();
        if (!is_null($pageId) && $pageId !== $page->getId()) {
            $page->setStoreId($this->getStoreId());
            if (!$page->load($pageId)) {
                return null;
            }
        }
        if (!$page->getId()) {
            return null;
        }
        $this->pageObject = $page;
    }

    /**
     * @return mixed
     */
    public function getCmsPage()
    {
        return $this->pageObject;
    }

    /**
     *
     * Retrieve page direct URL.
     *
     * @return string
     */
    public function getPageUrl()
    {
        if (!$this->pageObject || !$this->pageObject->getId()) {
            return null;
        }
        return Mage::getUrl(null, array('_direct' => $this->pageObject->getIdentifier()));
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getPageModel()
    {
        return Mage::getModel('cms/page');
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return Mage::app()->getStore()->getId();
    }

}
