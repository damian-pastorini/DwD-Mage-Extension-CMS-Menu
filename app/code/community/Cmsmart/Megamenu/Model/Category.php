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
class Cmsmart_Megamenu_Model_Category extends Varien_Object
{
    static public function toOptionArray()
    {
		$category = Mage::getModel('catalog/category'); 
		$tree = $category->getTreeModel(); 
		$tree->load();
		$ids = $tree->getCollection()->getAllIds(); 
		$arr = array();
		$arr['-1']='----Please Select----';
		if ($ids)
		{
			foreach ($ids as $id)
			{ 
				$cat = Mage::getModel('catalog/category'); 
				$cat->load($id);
				$arr[$id] = $cat->getName();
			} 
		}
		return $arr;
;
    }
}
