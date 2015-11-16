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
class Cmsmart_Megamenu_Block_Adminhtml_System_Config_Form_Field_Tex extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	/**
     * Add texture preview
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return String
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
		$html = $element->getElementHtml(); //Default HTML
		$jsPath = $this->getJsUrl('cmsmart/jquery/jquery-1.7.2.min.js');
		//$texPath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'nbimages/cmsmart/themesetting/patterns/default/';
		$texPath = Mage::helper('themesetting')->getPatternsUrl();
		
		//Recreate ID of the background color picker which is related with this pattern
		$bgcPickerId = str_replace('_tex', '_bg_color', $element->getHtmlId());
		
		//Create ID of the pattern preview box
		$previewId = $element->getHtmlId() . '-tex-preview';
		
		if (Mage::registry('jqueryLoaded') == false)
		{
			$html .= '
			<script type="text/javascript" src="'. $jsPath .'"></script>
			<script type="text/javascript">jQuery.noConflict();</script>
			';
			Mage::register('jqueryLoaded', 1);
        }

	    $html .= '
		<br/><div id="'. $previewId .'" style="width:280px; height:160px; margin:10px 0; background-color:transparent;"></div>
		<script type="text/javascript">
			jQuery(function($){
				var tex		= $("#'. $element->getHtmlId()	.'");
				var bgc		= $("#'. $bgcPickerId			.'");
				var preview	= $("#'. $previewId				.'");
				
				preview.css("background-color", bgc.attr("value"));
				
				tex.change(function() {
					preview.css({
						"background-color": bgc.css("background-color"),
						"background-image": "url('. $texPath .'" + tex.val() + ".png)"
					});
				})
				.change();
			});
		</script>
		';
		
        return $html;
    }
}
