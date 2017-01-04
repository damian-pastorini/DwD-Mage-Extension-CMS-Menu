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

    /**
     * @param $observer
     * @return mixed
     */
    public function addMenuItems($observer)
    {
        $isEnabled = Mage::getStoreConfig('dwd_cmsmenu/general/enabled');
        if($isEnabled) {
            $topMenu = $observer->getMenu();
            // get all items that should be added at the end of the tree:
            $cmsMenuItems = $this->getCmsMenuItems();
            // loop items and add to the menu:
            foreach ($cmsMenuItems as $menuItem) {
                // load cms page information:
                $menuItem->loadCmsPageObject();
                // check if the cms page is available for the store:
                if(!$menuItem->getCmsPage()) {
                    continue;
                }
                // get item name:
                $itemName = $this->getItemName($menuItem);
                // get item url:
                $itemUrl = $menuItem->getPageUrl();
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
                    'class' => 'cmsmenu-'.$menuItem->getCmsPageId()
                );
                // child of items:
                if($menuItem->getChildOf()) {
                    // get all child nodes:
                    $allChildNodes = $topMenu->getAllChildNodes();
                    // look for the parent item:
                    $parentItem = false;
                    // get child of value:
                    $childOf = $this->formatedItemValue($menuItem->getChildOf());
                    // look for cmsmenu items:
                    if(isset($allChildNodes[$childOf])) {
                        $parentItem = $allChildNodes[$childOf];
                    }
                    if($parentItem) {
                        $this->createAndAssignItemNode($menuItem, $itemNodeData, $parentItem);
                    }
                } else {
                    $this->createAndAssignItemNode($menuItem, $itemNodeData, $topMenu);
                }
            }
        }
        return $observer;
    }

    /**
     * @return mixed
     */
    protected function getCmsMenuItems()
    {
        $collection = Mage::getModel('dwd_cmsmenu/cmsmenu')
            ->getCollection()
            ->addActiveFilter()
            ->setChildOfOrder()
            ->setAddBeforeOrder();
        return $collection;
    }

    /**
     * @param $menuItem
     * @return mixed
     */
    public function getItemName($menuItem)
    {
        $itemName = $menuItem->getMenuItemTitle();
        if(!$itemName) {
            // if the item title is not specified then get the title from the page:
            $cmsPage = $menuItem->getCmsPage();
            if($cmsPage && $cmsPage->getTitle()) {
                $itemName = $cmsPage->getTitle();
            }
        }
        return $itemName;
    }

    /**
     * @param $itemUrl
     * @return bool
     */
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

    /**
     * @param $value
     * @return string
     */
    public function formatedItemValue($value)
    {
        if(strpos($value, 'c-')!==false) {
            $result = 'category-node-'.str_replace('c-', '', $value);
        } else {
            $result = 'cmsmenu-'.$value;
        }
        return $result;
    }

    /**
     * @param $menuItem
     * @param $itemNodeData
     * @param $parentMenu
     */
    public function createAndAssignItemNode($menuItem, $itemNodeData, $parentMenu)
    {
        // create new node:
        $itemNode = new Varien_Data_Tree_Node($itemNodeData, 'cmsmenu-' . $menuItem->getCmsPageId(), $parentMenu->getTree());
        // top level items:
        if(!$menuItem->getAddBefore()) {
            // add item at the end:
            $parentMenu->addChild($itemNode);
        } else {
            // get menu items:
            $currentChilds = $parentMenu->getChildren();
            if($currentChilds) {
                // loop and reorder items:
                foreach($currentChilds as $childIndex => $currentChild) {
                    $parentMenu->removeChild($currentChild);
                    $addBeforeValue = $this->formatedItemValue($menuItem->getAddBefore());
                    if($childIndex == $addBeforeValue) {
                        $parentMenu->addChild($itemNode);
                    }
                    $parentMenu->addChild($currentChild);
                }
            } else {
                // if there are no items just add the child normally:
                $parentMenu->addChild($itemNode);
            }
        }
    }

}
