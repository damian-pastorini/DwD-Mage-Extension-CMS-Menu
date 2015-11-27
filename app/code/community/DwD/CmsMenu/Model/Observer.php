<?php

/**
 *
 * DwD-CmsMenu - Magento Extension
 *
 * @copyright Copyright (c) 2015 DwDesigner Inc. (http://www.dwdeveloper.com/)
 * @author Damian A. Pastorini - damian.pastorini@dwdeveloper.com
 *
 */

class DwD_CmsMenu_Model_Observer
{

    public function saveCmsMenu($observer)
    {
        $isEnabled = Mage::getStoreConfig('dwd_cmsmenu/general/enabled');
        if($isEnabled) {
            try {
                $request = Mage::app()->getRequest();
                $post = $request->getPost();
                $page = $observer->getObject();
                $pageId = $page->getId();
                $cmsMenu = Mage::getModel('dwd_cmsmenu/cmsmenu')->load($pageId, 'cms_page_id');
                if (!$cmsMenu || ($cmsMenu && !$cmsMenu->getId())) {
                    $cmsMenu = Mage::getModel('dwd_cmsmenu/cmsmenu');
                }
                $level = Mage::helper('dwd_cmsmenu')->getTreeLevel($post['child_of']);
                $cmsMenu->setLevel($level);
                $cmsMenu->setCmsPageId($pageId);
                $cmsMenu->setShowInMenu($post['show_in_menu']);
                $cmsMenu->setChildOf($post['child_of']);
                $cmsMenu->setAddBefore($post['add_before']);
                $itemTitle = $post['menu_item_title'];
                if (!$itemTitle) {
                    $itemTitle = $post['title'];
                }
                $cmsMenu->setMenuItemTitle($itemTitle);
                $observerDataArray = array_merge($observer->getData(), array('cmsmenu'=>$cmsMenu, 'post'=>$post));
                Mage::dispatchEvent('cmsmenu_save_item_before', $observerDataArray);
                $cmsMenu->save();
                $flushCache = Mage::getStoreConfig('dwd_cmsmenu/general/cache');
                if($flushCache) {
                    Mage::app()->getCacheInstance()->flush();
                }
            } catch (Exception $e) {
                Mage::log($e->getMessage(), null, 'dwd-cmsmenu-error.log');
            }
            Mage::dispatchEvent('cmsmenu_save_item_after', $observer->getData());
        }
    }

    public function addCmsPageData($observer)
    {
        $isEnabled = Mage::getStoreConfig('dwd_cmsmenu/general/enabled');
        if($isEnabled) {
            $page = $observer->getObject();
            $pageId = $page->getId();
            $cmsMenu = Mage::getModel('dwd_cmsmenu/cmsmenu')->load($pageId, 'cms_page_id');
            $page->setData('show_in_menu', $cmsMenu->getShowInMenu());
            $page->setData('child_of', $cmsMenu->getChildOf());
            $page->setData('add_before', $cmsMenu->getAddBefore());
            $page->setData('menu_item_title', $cmsMenu->getMenuItemTitle());
            $page->setData('level', $cmsMenu->getLevel());
            $observerDataArray = array_merge($observer->getData(), array('page'=>$page, 'cmsmenu'=>$cmsMenu));
            Mage::dispatchEvent('cmsmenu_add_cms_page_data_after', $observerDataArray);
            return $page;
        }
    }

}
