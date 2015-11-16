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
class Cmsmart_Megamenu_Block_Adminhtml_System_Config_Form_Field_Color extends Mage_Adminhtml_Block_System_Config_Form_Field
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
		$jsPath = $this->getJsUrl('cmsmart/megamenu/jquery/jquery-1.7.2.min.js');
		$mcPath = $this->getJsUrl('cmsmart/megamenu/jquery/plugins/mcolorpicker/');
		
		if (Mage::registry('jqueryLoaded') == false)
		{
			$html .= '
			<script type="text/javascript" src="'. $jsPath .'"></script>
			<script type="text/javascript">jQuery.noConflict();</script>
			';
			Mage::register('jqueryLoaded', 1);
        }
		if (Mage::registry('colorPickerLoaded') == false)
		{
			$html .= '
			<script type="text/javascript" src="'. $mcPath .'mcolorpicker.min.js"></script>
			<script type="text/javascript">
				jQuery.fn.mColorPicker.init.replace = false;
				jQuery.fn.mColorPicker.defaults.imageFolder = "'. $mcPath .'images/";
				jQuery.fn.mColorPicker.init.allowTransparency = true;
				jQuery.fn.mColorPicker.init.showLogo = false;
			</script>
            ';
			Mage::register('colorPickerLoaded', 1);
        }
		
		$html .= '
			<script type="text/javascript">
				jQuery(function($){
					$("#'. $element->getHtmlId() .'").attr("data-hex", true).width("250px").mColorPicker();
				});
			</script>
        ';
		
        return $html;
    }
}
