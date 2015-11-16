<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Catalog category controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
 /**
* Name Extension: Megamenu
* Version: 0.1.0
* Author: The Cmsmart Development Team 
* Date Created: 16/08/2013
* Websites: http://cmsmart.net
* Technical Support: Forum - http://cmsmart.net/support
* GNU General Public License v3 (http://opensource.org/licenses/GPL-3.0)
* Copyright © 2011-2013 Cmsmart.net. All Rights Reserved.
*/
include_once(Mage::getModuleDir('controllers','Mage_Adminhtml').DS.'Catalog'.DS.'CategoryController.php');
class Cmsmart_Megamenu_Catalog_CategoryController extends Mage_Adminhtml_Catalog_CategoryController
{
    /**
     * Category save
     */
    public function saveAction()
    {
        
        if (!$category = $this->_initCategory()) {
            return;
        }
		
        $storeId = $this->getRequest()->getParam('store');
        $refreshTree = 'false';
        if ($data = $this->getRequest()->getPost()) {
            $category->addData($data['general']);
            if (!$category->getId()) {
                $parentId = $this->getRequest()->getParam('parent');
                if (!$parentId) {
                    if ($storeId) {
                        $parentId = Mage::app()->getStore($storeId)->getRootCategoryId();
                    }
                    else {
                        $parentId = Mage_Catalog_Model_Category::TREE_ROOT_ID;
                    }
                }
                $parentCategory = Mage::getModel('catalog/category')->load($parentId);
                $category->setPath($parentCategory->getPath());
            }

            /**
             * Process "Use Config Settings" checkboxes
             */
            if ($useConfig = $this->getRequest()->getPost('use_config')) {
                foreach ($useConfig as $attributeCode) {
                    $category->setData($attributeCode, null);
                }
            }

            /**
             * Create Permanent Redirect for old URL key
             */
            if ($category->getId() && isset($data['general']['url_key_create_redirect']))
            // && $category->getOrigData('url_key') != $category->getData('url_key')
            {
                $category->setData('save_rewrites_history', (bool)$data['general']['url_key_create_redirect']);
            }

            $category->setAttributeSetId($category->getDefaultAttributeSetId());

            if (isset($data['category_products']) &&
                !$category->getProductsReadonly()) {
                $products = array();
                parse_str($data['category_products'], $products);
                $category->setPostedProducts($products);
            }

            Mage::dispatchEvent('catalog_category_prepare_save', array(
                'category' => $category,
                'request' => $this->getRequest()
            ));

            /**
             * Proceed with $_POST['use_config']
             * set into category model for proccessing through validation
             */
            $category->setData("use_post_data_config", $this->getRequest()->getPost('use_config'));

            try {
                $validate = $category->validate();
                if ($validate !== true) {
                    foreach ($validate as $code => $error) {
                        if ($error === true) {
                            Mage::throwException(Mage::helper('catalog')->__('Attribute "%s" is required.', $category->getResource()->getAttribute($code)->getFrontend()->getLabel()));
                        }
                        else {
                            Mage::throwException($error);
                        }
                    }
                }

                /**
                 * Check "Use Default Value" checkboxes values
                 */
                if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                    foreach ($useDefaults as $attributeCode) {
                        $category->setData($attributeCode, false);
                    }
                }

                /**
                 * Unset $_POST['use_config'] before save
                 */
                $category->unsetData('use_post_data_config');

                $category->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('catalog')->__('The category has been saved.'));
                $refreshTree = 'true';
            }
            catch (Exception $e){
                $this->_getSession()->addError($e->getMessage())
                    ->setCategoryData($data);
                $refreshTree = 'false';
            }
        }
        
        //Mage::log($category->getId(), null, 'events-custom.log', true);
        // create code at here.
       
        
        $url = $this->getUrl('*/*/edit', array('_current' => true, 'id' => $category->getId()));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("' . $url . '", {}, '.$refreshTree.');</script>'
        );
    }

    
}
