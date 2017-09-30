<?php

/**
 *
 * DwD-CmsMenu - Magento Extension
 *
 * @copyright Copyright (c) 2017 DwDeveloper (http://www.dwdeveloper.com/)
 * @author Damian A. Pastorini - damian.pastorini@dwdeveloper.com
 *
 */

class DwD_CmsMenu_Model_Observer
{

    /**
     * @param $observer
     */
    public function saveCmsMenu($observer)
    {
        $isEnabled = $this->isEnabled();
        if($isEnabled) {
            try {
                $request = $this->getRequest();
                $post = $request->getPost();
                $page = $observer->getObject();
                $pageId = $page->getId();
                $cmsMenu = $this->getModel('dwd_cmsmenu/cmsmenu')->load($pageId, 'cms_page_id');
                $level = $this->getCmsMenuHelper()->getTreeLevel($post['child_of']);
                $cmsMenu->setLevel($level);
                $cmsMenu->setCmsPageId($pageId);
                $cmsMenu->setShowInMenu($post['show_in_menu']);
                $cmsMenu->setChildOf($post['child_of']);
                $cmsMenu->setAddBefore($post['add_before']);
                $itemTitle = $post['menu_item_title'];
                if (!$itemTitle && isset($post['title'])) {
                    $itemTitle = $post['title'];
                }
                $cmsMenu->setMenuItemTitle($itemTitle);
                $observerDataArray = array_merge($observer->getData(), array('cmsmenu' => $cmsMenu, 'post' => $post));
                $this->dispatchEvent('cmsmenu_save_item_before', $observerDataArray);
                $cmsMenu->save();
                $flushCache = $this->getConfig('dwd_cmsmenu/general/cache');
                if($flushCache) {
                    $this->getCacheInstance()->flush();
                }
            } catch (Exception $e) {
                $this->log($e->getMessage());
            }
            $this->dispatchEvent('cmsmenu_save_item_after', $observer->getData());
        }
    }

    /**
     * @param $observer
     * @return mixed
     */
    public function addCmsPageData($observer)
    {
        $isEnabled = $this->isEnabled();
        if($isEnabled) {
            $page = $observer->getObject();
            $pageId = $page->getId();
            $cmsMenu = $this->getModel('dwd_cmsmenu/cmsmenu')->load($pageId, 'cms_page_id');
            $page->setData('show_in_menu', $cmsMenu->getShowInMenu());
            $page->setData('child_of', $cmsMenu->getChildOf());
            $page->setData('add_before', $cmsMenu->getAddBefore());
            $page->setData('menu_item_title', $cmsMenu->getMenuItemTitle());
            $page->setData('level', $cmsMenu->getLevel());
            $observerDataArray = array_merge($observer->getData(), array('page' => $page, 'cmsmenu' => $cmsMenu));
            $this->dispatchEvent('cmsmenu_add_cms_page_data_after', $observerDataArray);
            return $page;
        }
    }

    /**
     * @param $observer
     * @return mixed
     */
    public function deleteCmsMenu($observer)
    {
        $isEnabled = $this->isEnabled();
        if($isEnabled) {
            $page = $observer->getObject();
            $pageId = $page->getId();
            $cmsMenu = $this->getModel('dwd_cmsmenu/cmsmenu')->load($pageId, 'cms_page_id');
            if($cmsMenu->getId()) {
                $cmsMenu->delete();
            }
            $this->dispatchEvent('cmsmenu_delete_item_after', $observer->getData());
            return $page;
        }
    }

    /**
     * @return mixed
     */
    public function isEnabled()
    {
        return $this->getConfig('dwd_cmsmenu/general/enabled');
    }

    /**
     * @param $name
     * @param $data
     */
    private function dispatchEvent($name, $data)
    {
        Mage::dispatchEvent($name, $data);
    }

    /**
     * @param $message
     */
    protected function log($message)
    {
        Mage::log($message, null, 'dwd-cmsmenu-error.log');
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getConfig($path)
    {
        return Mage::getStoreConfig($path);
    }

    /**
     * @return Mage_Core_Controller_Request_Http
     */
    public function getRequest()
    {
        return Mage::app()->getRequest();
    }

    /**
     * @param $name
     * @return false|Mage_Core_Model_Abstract
     */
    public function getModel($name)
    {
        return Mage::getModel($name);
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    public function getCmsMenuHelper()
    {
        return Mage::helper('dwd_cmsmenu');
    }

    /**
     * @return Mage_Core_Model_Cache
     */
    public function getCacheInstance()
    {
        return Mage::app()->getCacheInstance();
    }

}
