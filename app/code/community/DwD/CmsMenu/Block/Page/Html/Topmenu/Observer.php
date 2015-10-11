<?php

/**
 *
 * DwD-CmsMenu - Magento Extension
 *
 * @copyright Copyright (c) 2015 DwDesigner Inc. (http://www.dwdeveloper.com/)
 * @author Damian A. Pastorini - damian.pastorini@dwdeveloper.com
 *
 */

class DwD_CmsMenu_Block_Page_Html_Topmenu_Observer extends Mage_Page_Block_Html_Topmenu
{

    public function addMenuItems($observer)
    {
        $isEnabled = Mage::getStoreConfig('dwd_cmsmenu/general/enabled');
        if($isEnabled) {
            // get all items that should be added at the end of the tree:
            $cmsMenuItems = Mage::getModel('dwd_cmsmenu/cmsmenu')
                ->getCollection()
                ->addActiveFilter()
                ->setChildOfOrder()
                ->setAddBeforeOrder();
            // loop items and add to the menu:
            foreach ($cmsMenuItems as $menuItem) {
                // get item name:
                $itemName = $this->getItemName($menuItem);
                // get item url:
                $itemUrl = Mage::helper('cms/page')->getPageUrl($menuItem->getCmsPageId());
                // check if the item is active:
                $is_active = $this->getItemStatus($itemUrl);
                // create the item data array:
                $itemNodeData = array(
                    'name' => $itemName,
                    'id' => 'cmsmenu-'.$menuItem->getCmsPageId(),
                    'cmsmenu-'.$menuItem->getCmsPageId() => 'cmsmenu-'.$menuItem->getCmsPageId(),
                    'url' => $itemUrl,
                    'is_active' => $is_active,
                    'level' => 0,
                    'is_first' => false, // TODO: fix.
                    'is_last' => false, // TODO: fix.
                    'class' => 'cmsmenu-'.$menuItem->getCmsPageId()
                );
                // child of items:
                if($menuItem->getChildOf()) {
                    // get all child nodes:
                    $allChildNodes = $observer->getMenu()->getAllChildNodes();
                    // look for the parent item:
                    $parentItem = false;
                    // get child of value:
                    $childOf = $this->formatedItemValue($menuItem->getChildOf());
                    // look for cmsmenu items:
                    if(isset($allChildNodes[$childOf])) {
                        $parentItem = $allChildNodes[$childOf];
                    }
                    if($parentItem) {
                        // create new item node:
                        $itemNode = new Varien_Data_Tree_Node($itemNodeData, 'cmsmenu-'.$menuItem->getCmsPageId(), $parentItem->getTree());
                        if(!$menuItem->getAddBefore()) {
                            // add item at the end:
                            $parentItem->addChild($itemNode);
                        } else {
                            // add before case, get parent item childs:
                            $currentChilds = $parentItem->getChildren();
                            if($currentChilds) {
                                // loop and reorder items:
                                foreach($currentChilds as $childIndex => $currentChild) {
                                    $parentItem->removeChild($currentChild);
                                    $addBeforeValue = $this->formatedItemValue($menuItem->getAddBefore());
                                    if($childIndex == $addBeforeValue) {
                                        $parentItem->addChild($itemNode);
                                    }
                                    $parentItem->addChild($currentChild);
                                }
                            } else {
                                // if there are no childs add the item normally:
                                $parentItem->addChild($itemNode);
                            }
                        }
                    }
                } else {
                    // create new node:
                    $itemNode = new Varien_Data_Tree_Node($itemNodeData, 'cmsmenu-' . $menuItem->getCmsPageId(), $observer->getMenu()->getTree());
                    // top level items:
                    if(!$menuItem->getAddBefore()) {
                        // add item at the end:
                        $observer->getMenu()->addChild($itemNode);
                    } else {
                        // get menu items:
                        $currentChilds = $observer->getMenu()->getChildren();
                        if($currentChilds) {
                            // loop and reorder items:
                            foreach($currentChilds as $childIndex => $currentChild) {
                                $observer->getMenu()->removeChild($currentChild);
                                $addBeforeValue = $this->formatedItemValue($menuItem->getAddBefore());
                                if($childIndex == $addBeforeValue) {
                                    $observer->getMenu()->addChild($itemNode);
                                }
                                $observer->getMenu()->addChild($currentChild);
                            }
                        } else {
                            // if there are no items just add the child normally:
                            $observer->getMenu()->addChild($itemNode);
                        }
                    }
                }
            }
        }
        return $observer;
    }

    public function getItemName($menuItem)
    {
        $itemName = $menuItem->getMenuItemTitle();
        if(!$itemName) {
            // if the item title is not specified then get the title from the page:
            $cmsPage = Mage::getModel('cms/page')->load($menuItem->getCmsPageId());
            $itemName = $cmsPage->getTitle();
        }
        return $itemName;
    }

    public function getItemStatus($itemUrl)
    {
        $isActive = false;
        // get current url:
        $currentUrl = rtrim(Mage::helper('core/url')->getCurrentUrl(), '/');
        // get item url:
        $currentPageUrl = rtrim($itemUrl, '/');
        // validate:
        if ($currentPageUrl == $currentUrl) {
            $isActive = true;
        }
        return $isActive;
    }

    public function formatedItemValue($value)
    {
        if(strpos($value, 'c-')!==false) {
            $result = 'category-node-'.str_replace('c-', '', $value);
        } else {
            $result = 'cmsmenu-'.$value;
        }
        return $result;
    }

}
