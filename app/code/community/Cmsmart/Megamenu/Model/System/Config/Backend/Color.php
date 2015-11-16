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
class Cmsmart_ThemeSetting_Model_System_Config_Backend_Color extends Mage_Core_Model_Config_Data
{
	public function save()
	{
		$v = $this->getValue();
		if ($v == 'rgba(0, 0, 0, 0)')
		{
			$this->setValue('transparent');
		}
		return parent::save();
	}
}
