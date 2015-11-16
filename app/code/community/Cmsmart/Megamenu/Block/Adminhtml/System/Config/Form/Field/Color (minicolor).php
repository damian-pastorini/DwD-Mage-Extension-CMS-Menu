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
class Cmsmart_ThemeSetting_Block_Adminhtml_System_Config_Form_Field_Color extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Add color picker
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return String
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
		$html = $element->getElementHtml(); //Default HTML

        if(Mage::registry('colorPickerFirstUse') == false)
		{
			$html .= '
			<script type="text/javascript" src="'. $this->getJsUrl('cmsmart/jquery/jquery-1.7.2.min.js') .'"></script>
			<script type="text/javascript" src="'. $this->getJsUrl('cmsmart/jquery/plugins/minicolors/jquery.minicolors.min.js') .'"></script>
			<script type="text/javascript">jQuery.noConflict();</script>
            <link type="text/css" rel="stylesheet" href="'. $this->getJsUrl('cmsmart/jquery/plugins/minicolors/jquery.minicolors.css') .'" />
            ';
			
			Mage::register('colorPickerFirstUse', 1);
        }
		
		$html .= '
			<script type="text/javascript">
				jQuery(function($){
					$("#'. $element->getHtmlId() .'").miniColors();
				});
			</script>
        ';
		
        return $html;
    }
}
