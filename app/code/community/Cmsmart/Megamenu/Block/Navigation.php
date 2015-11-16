
<?php
/*
* Name Extension: Megamenu
* Version: 0.1.0
* Author: The Cmsmart Development Team 
* Date Created: 16/08/2013
* Websites: http://cmsmart.net
* Technical Support: Forum - http://cmsmart.net/support
* GNU General Public License v3 (http://opensource.org/licenses/GPL-3.0)
* Copyright © 2011-2013 Cmsmart.net. All Rights Reserved.
*/

class Cmsmart_Megamenu_Block_Navigation extends Mage_Catalog_Block_Navigation
{
   
   public function _prepareLayout()
    {
                      
        if (!Mage::getStoreConfig('megamenu/mainmenu/enabled')) return;
        $layout = $this->getLayout();
        $this->setTemplate('cmsmart/megamenu/topmenu.phtml');
        $head = $layout->getBlock('head');
        $head->addItem('skin_js', 'js/cmsmart/megamenu/cmsmartmenu.js');
        $head->addItem('skin_css', 'css/cmsmart/megamenu/megamenu.css');
    }
    
   protected function _renderCategoryMenuItemHtml($category, $level = 0, $isLast = false, $isFirst = false,
        $isOutermost = false, $outermostItemClass = '', $childrenWrapClass = '', $noEventAttributes = false)
    {
            
            
    	$showthumbnail = Mage::helper('megamenu')->getShowthumbnail($category->getId());
        
        $width = Mage::getStoreConfig('megamenu/mainmenu/width_thumbnail_category');
        $height= Mage::getStoreConfig('megamenu/mainmenu/height_thumbnail_category');
        if (!$category->getIsActive()) {
            return '';
        }
        $html = array();
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = (array)$category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = ($children && $childrenCount);
        $activeChildren = array();
        
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                $activeChildren[] = $child;
            }
          
        }
        
        $activeChildrenCount = count($activeChildren);
        $hasActiveChildren = ($activeChildrenCount > 0);
        $classes = array();
        $classes[] = 'level' . $level;
        $classes[] = 'nav-' . $this->_getItemPosition($level);
        if ($this->isCategoryActive($category)) {
            $classes[] = 'active';
        }
        $linkClass = '';
        if ($isOutermost && $outermostItemClass) {
            $classes[] = $outermostItemClass;
            $linkClass = ' class="'.$outermostItemClass.'"';
        }
        if ($isFirst) {
            $classes[] = 'first';
        }
        if ($isLast) {
            $classes[] = 'last';
        }
        if ($hasActiveChildren) {
            $classes[] = 'parent';
        }
		if ($this->_isAccordion == FALSE && $level == 1) {
			$classes[] = 'item';
		}
        $column = $this->getLevle($category->getId()) ;
       
       
        $attributes = array();
        if (count($classes) > 0) {
            $attributes['class'] = implode(' ', $classes);
        }
        if ($hasActiveChildren && !$noEventAttributes) {
             $attributes['onmouseover'] = 'toggleMenu(this,0)';
             $attributes['onmouseout'] = 'toggleMenu(this,0)';
        }
        if($showthumbnail != 1){
            $nothumbnail .= ' no-level-thumbnail'; 
            } else {
            $nothumbnail .= ' level-thumbnail';     
            }
        $htmlLi = '<li ';
        
       
        
        foreach ($attributes as $attrName => $attrValue) {                       
            $htmlLi .= ' ' . $attrName .  '="' . str_replace('"', '\"', $attrValue) . ' ' . $nothumbnail . '"';
                  } 
        
        if($level == 1) {
                $idc =  $category->parent_id;
                $widthp = $this->widthp($idc);
                $htmlLi .= 'style="width:'.$widthp.'%;">';    
            
            
        } else {
            $htmlLi .= '>';    
        }
        $html[] = $htmlLi;
		$id = $category->getId();
        $megamenu = Mage::helper('megamenu')->Megamenu($id);
        $cat=Mage::getModel('catalog/category')->load($category->getId());
        $model=$cat->getDisplay_mode();
        $imgaes =  Mage::getModel('catalog/category')->load($category->getId())->getThumbnail();
        if($level == 1){    		
         $html[] = '<a style="background-color:'. $this->backgroundcolor($megamenu).' border-bottom: 2px solid '.$this->fontcolor($megamenu).'" class="catagory-level1" href="'.$this->getCategoryUrl($category).'"'.$linkClass.'>';        
        } else {
         $html[] = '<a  style="background-color:'. $this->backgroundcolor($megamenu).'" href="'.$this->getCategoryUrl($category).'"' .$linkClass.'>';
        }
        if($showthumbnail == 1){
			if ($imgaes) {
				$html[] = '<img src="' . Mage::getBaseUrl('media').'catalog/category/' . $imgaes .'" width="'.$width.'" height="'.$height.'" style="float: left;z-index: 1" />' ;
			}else {
			    $html[] = '<div class="thumbnail"></div>'; 
			} 
		} else {
		  $html[] = '<div class="thumbnail"></div>';
		}
        
        
        if(($childrenCount && $megamenu[0]['category_children']==0) || ($megamenu[0]['active_product']==1) || ($megamenu[0]['active_product']==1) || ($megamenu[0]['active_static_block']==1)){
			    $html[] = '<span style="color:'.$this->fontcolor($megamenu).';">' . $this->escapeHtml($category->getName()).$this->getLabel($megamenu).'</span><span class="spanchildren"></span>';
           	} else{
			     $html[] = '<span style="color:'.$this->fontcolor($megamenu).';  ">' . $this->escapeHtml($category->getName()).$this->getLabel($megamenu).'</span>';    
			}
        $html[] = '</a>';
        if($level == 1) {  $html[] ='<div style="border-top: 1px solid '.$this->fontcolor($megamenu).'; width: 100%; float: left; top: 0px; left: 0px; margin-top: 2px;"></div>'; } 
        if($level == 3) {  $html[] ='<div style="border-top: 1px solid '.$this->fontcolor($megamenu).'; width: 100%; float: left; top: -3px; left: 0px; position: static;"></div>'; }
        // render children
        $htmlChildren = '';
        $j = 0;
        foreach ($activeChildren as $child) {
            $htmlChildren .= $this->_renderCategoryMenuItemHtml(
                $child,
                ($level + 1),
                ($j == $activeChildrenCount - 1),
                ($j == 0),
                false,
                $outermostItemClass,
                $childrenWrapClass,
                $noEventAttributes
            );
            
            $j++;
        }
               
        $standard_menu = Mage::getStoreConfig('megamenu/mainmenu/standard_menu');
        if($standard_menu == 1) {
            $addclass = 'standard_menu';
        } else {
            $addclass = '';
        }
		if ($this->_isAccordion == TRUE)
			$html[] = '<span class="opener">&nbsp;</span>';
	
        if ($childrenWrapClass) {
             if(!$level){
                    $html[] = '<div class="' . $childrenWrapClass  . '">';
                } else {
                    $html[] = '<div class="' . $childrenWrapClass  .'">';
                }
            }
            if($level == 0) {
                $width_menu = Mage::getStoreConfig('megamenu/mainmenu/width_menu') - 20;
                $html[] = '<ul class="level' . $level .' '. $addclass .' column'.$this->getLevle($id).'" style="width:'.$width_menu. 'px;">';   
            } else {
                $html[] = '<ul class="level' . $level .' '. $addclass .' column'.$this->getLevle($id).'">';    
            }
            $html[] = $this->getBlockTop($megamenu);
            
            if($level == 0) {
             if($megamenu[0]['active_product']==1){
                    $numbers = $megamenu[0]['numbers_product'];
                    $showproduct = $this->htmlshownumberproduct($category,$id,$numbers,$level);
                    $html[] = $showproduct; 
                 }
			if($this->getBlockLeft($megamenu)) {
				$classblock =' menu-content';
			}
            $html[] = '<ul class=" level' . $level . $classblock.'">'.$this->getBlockLeft($megamenu);
            $html[] = $this->getBlockRight($megamenu);  
             
              if($megamenu[0]['category_children']==0)   {   
                if($htmlChildren) {
                    if($level == 0){
                         $widthchildren =  $this->width($id);
                        $html[] = '<div class="catagory_children" style="width:'.$widthchildren.'px;">'.$htmlChildren.'</div>'; 
                        
                    } else {
                        $html[] = '<div class="catagory_children column'.$this->getLevle($id).'">'.$htmlChildren.'</div>';    
                    }
                 }
               }
            }  else{
				if($this->getBlockLeft($megamenu)) {
				$classblock =' menu-content';
				}
				$html[] = '<ul class=" level' . $level . $classblock.'">'.$this->getBlockLeft($megamenu);
                if($megamenu[0]['category_children']==0)   {   
                    if($htmlChildren) {
                        $idc =  $category->parent_id;
                        $widthchildren = $this->width($idc);
                        
                        $widthc =  Mage::helper('megamenu')->Megamenu($id);
                        $left = (($widthc[0]['width_block_left'] + 10)/$widthchildren)*100;
                        $w = floor($widthp - $left);
                        $widthchildrenc = ($w * $widthchildren)/100; 
                        
                            if(($widthc[0]['active_static_block'] == 1 ) && ($widthc[0]['active_static_block_left'] == 1 ) && ($widthc[0]['active_static_block_right'] == 1 )) {
                                $classchildren = 'leftrightchildren';
                                $html[] = '<div class="catagory_children '. $classchildren  .' column'.$this->getLevle($id).'" style="width:'.$widthchildrenc.'px">'.$htmlChildren.'</div>';
                                } else if(($widthc[0]['active_static_block'] == 1 ) && ($widthc[0]['active_static_block_left'] == 1 ) && ($widthc[0]['active_static_block_right'] == 0 )){
                                    $classchildren = 'leftchildren';
                                    $html[] = '<div class="catagory_children '. $classchildren  .' column'.$this->getLevle($id).'" style="width:'.$widthchildrenc.'px">'.$htmlChildren.'</div>';
                                } else if(($widthc[0]['active_static_block'] == 1 ) && ($widthc[0]['active_static_block_left'] == 0 ) && ($widthc[0]['active_static_block_right'] == 1 )){
                                    $classchildren = 'rightchildren';
                                    $html[] = '<div class="catagory_children '. $classchildren  .' column'.$this->getLevle($id).'" style="width:'.$widthchildrenc.'px">'.$htmlChildren.'</div>';
                                } else{
                                    $classchildren = '';
                                    $html[] = '<div class="catagory_children '. $classchildren  .' column'.$this->getLevle($id).'">'.$htmlChildren.'</div>';   
                                }
                            
                     }
                   }
                $html[] = $this->getBlockRight($megamenu);   
                if($megamenu[0]['active_product']==1){
                    $numbers = $megamenu[0]['numbers_product'];
                    $showproduct = $this->htmlshownumberproduct($category,$id,$numbers,$level);
                    $html[] = $showproduct; 
                 } 
            }                               
            $html[] = '</ul>'.$this->getBlockBottom($megamenu);
            
            $html[] = '</ul>';
        if ($childrenWrapClass) {
                $html[] = '</div>';
            }
        $html[] = '</li>';
        $html = implode("\n", $html);
        return $html;
    } 
    public function width($id){
        
        $width =  Mage::helper('megamenu')->Megamenu($id);
        $column = $this->getLevle($idc) ;
        $width_menu = Mage::getStoreConfig('megamenu/mainmenu/width_menu');
        if(($width[0]['active_static_block'] == 1 )&& ($width[0]['active_static_block_left'] == 1 ) && ($width[0]['active_static_block_right'] == 1 )) {
              $widthchildren = $width_menu -  $width[0]['width_block_left'] -  $width[0]['width_block_right']-40;
        } else {
              if(($width[0]['active_static_block'] == 1 )&& ($width[0]['active_static_block_left'] == 1 ) && ($width[0]['active_static_block_right'] == 0 )) {
        $widthchildren = $width_menu -  $width[0]['width_block_left']-30;
        } else {
        if(($width[0]['active_static_block'] == 1 ) && ($width[0]['active_static_block_left'] == 0 ) && ($width[0]['active_static_block_right'] == 1 )) {
        $widthchildren = $width_menu -  $width[0]['width_block_right'] - 30;   
        } else{
        $widthchildren = $width_menu;
                    }
                }
        }
       
        return $widthchildren;
    }
    public function getBlockTop($block){
        $active_static_block = $block[0]['active_static_block'];
        $active_static_block_top = $block[0]['active_static_block_top']; 
        $idblock = $block[0]['static_block_top'];
        $showblock = $this->getShowblock($active_static_block_top,$idblock);
        if($active_static_block == 1){
            return $showblock;
        }else {
            return ;
        } 
    }
    
    public function getBlockLeft($block){
        $active_static_block = $block[0]['active_static_block'];
        $active_static_block_left = $block[0]['active_static_block_left'];
        $id =  $block[0]['category_id'];
        $idblock = $block[0]['static_block_left'];
        $showblock = $this->getShowblockleft($active_static_block_left,$idblock,$id);
        if($active_static_block == 1){
            return $showblock;
        }else {
            return ;
        } 
    }
    public function getBlockRight($block){
        $active_static_block = $block[0]['active_static_block'];
        $active_static_block_right = $block[0]['active_static_block_right']; 
        $idblock = $block[0]['static_block_right'];
        $id =  $block[0]['category_id'];
        $showblock = $this->getShowblockright($active_static_block_right,$idblock,$id);
           if($active_static_block == 1){
                return $showblock;
            }else {
                return ;
            } 
    }
    public function getBlockBottom($block){
        $active_static_block = $block[0]['active_static_block'];
        $active_static_block_bottom = $block[0]['active_static_block_bottom']; 
        $idblock = $block[0]['static_block_bottom'];
        $showblock = $this->getShowblock($active_static_block_bottom,$idblock);
        if($active_static_block == 1){
            return $showblock;
        }else {
            return ;
        } 
    }
    public function getShowblockleft($active,$id,$cid) {
        if($active == 1){
            $width =  Mage::helper('megamenu')->Megamenu($cid);
            $auto = $width[0]['auto_width'];
            if($auto == 0) {
                $widthleft=  $width[0]['width_block_left'];
                return '<div class="static-block-left" style="width:'.$widthleft.'px; float: left; margin-top: 5px; margin-right: 10px;">'.$this->getLayout()->createBlock('cms/block')->setBlockId($id)->toHtml().'</div>';
            } else {
                return '<div class="static-block-left">'.$this->getLayout()->createBlock('cms/block')->setBlockId($id)->toHtml().'</div>';
            }
        }   else {
            return ;
        }     
    }
     public function getShowblockright($active,$id,$cid) {
        if($active == 1){
            $width =  Mage::helper('megamenu')->Megamenu($cid);
            $auto = $width[0]['auto_width'];
            if($auto == 0){
                 $widthright=  $width[0]['width_block_right'];
                 return '<div class="static-block-right" style="width:'.$widthright.'px; ">'.$this->getLayout()->createBlock('cms/block')->setBlockId($id)->toHtml().'</div>';
            } else {
                return '<div class="static-block-right">'.$this->getLayout()->createBlock('cms/block')->setBlockId($id)->toHtml().'</div>';    
            }
            
        }   else {
            return ;
        }     
    }
    public function getShowblock($active,$id) {
        if($active == 1){
            return '<ul class="static-block">'.$this->getLayout()->createBlock('cms/block')->setBlockId($id)->toHtml().'</ul>';
        }   else {
            return ;
        }     
    }
    public function widthp($idc){
        $width =  Mage::helper('megamenu')->Megamenu($idc);
        $column = $this->getLevle($idc) ;    
        $width_menu = Mage::getStoreConfig('megamenu/mainmenu/width_menu');
                    
                if(($width[0]['active_static_block'] == 1 )&& ($width[0]['active_static_block_left'] == 1 ) && ($width[0]['active_static_block_right'] == 1 )) {
                        $widthchildrenli = $width_menu -  $width[0]['width_block_left'] -  $width[0]['width_block_right']-30-10;
                    } else {
                           if(($width[0]['active_static_block'] == 1 )&& ($width[0]['active_static_block_left'] == 1 ) && ($width[0]['active_static_block_right'] == 0 )) {
                                $widthchildrenli = $width_menu -  $width[0]['width_block_left']-(30*$column)-30;   
                            } else {
                                if(($width[0]['active_static_block'] == 1 )&& ($width[0]['active_static_block_left'] == 0 ) && ($width[0]['active_static_block_right'] == 1 )) {
                                 $widthchildrenli = $width_menu -  $width[0]['width_block_right']-30;   
                            } else{
                                $widthchildrenli = $width_menu ;
                                }
                        }
                    }
        $cent = ((($column)*30)/$widthchildrenli)*100;
        $widthp = (100 - $cent)/$column;
        return $widthp;       
            
    }
    public function htmlshownumberproduct($category, $id,$number,$level){
        
        $idc =  $category->parent_id;
        $widthp = $this->widthp($idc); 
        
        
        $widthchildren = $this->width($idc);
        
        
        $widthc =  Mage::helper('megamenu')->Megamenu($id);
        if(($widthc[0]['active_static_block'] ==  1) && ($widthc[0]['active_static_block_left'] ==  1)){
            $left = (($widthc[0]['width_block_left'] + 10)/$widthchildren)*100;    
        } else if(($widthc[0]['active_static_block'] ==  1) && ($widthc[0]['active_static_block_right'] ==  1)){
            $left = (($widthc[0]['width_block_right'] + 10)/$widthchildren)*100;  
        }
        
        $w = floor($widthp - $left);
        $widthchildrenc = ($w * $widthchildren)/100;       
        
        $_productCollection = Mage::getResourceModel('catalog/product_collection')  
        ->addAttributeToFilter('status', 1)
        ->addAttributeToFilter('visibility', array('neq' => 1))
        ->addAttributeToSelect('*')  
        ->addCategoryFilter(Mage::getModel('catalog/category')->load($id));
        
         $html = '';
         if((($widthc[0]['active_static_block'] ==  1) && ($widthc[0]['active_static_block_left'] ==  1)) ||(($widthc[0]['active_static_block'] ==  1) && ($widthc[0]['active_static_block_right'] ==  1)) && ($level != 0))
         {
            $html .= '<ul class="level1 menu-product'.$level.' menu-product-one" style="width:'. $widthchildrenc.'px;">';
         } else {
            $html .= '<ul class="level1 menu-product'.$level.'">';
         }
         
         
         $avg = 0; 
            $ratings = array(); 
         foreach ($_productCollection as $_product){
            $productId = $_product->getId();
            $reviews = Mage::getModel('review/review')
                ->getResourceCollection()
                ->addStoreFilter(Mage::app()->getStore()->getId())
                ->addEntityFilter('product', $productId)
                ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
                ->setDateOrder()
                ->addRateVotes();
                if($i < $number){
                    
                if($number < 3 ) { if(($i%2)==0){
                    if($level == 0) {
                         $wproduct = 100/2; 
                    }
                    $levelproduct='levelproducttow fast'; 
                } else {
                    if($level == 0) {
                         $wproduct = 100/2; 
                    }
                    $levelproduct='levelproducttow'; } 
                } else { 
                    if(($i%3)==0){
                        if($level == 0) {
                            $wproduct = 100/3;
                        }
                        $levelproduct='levelproduct fast';
                    } else {
                        if($level == 0) {
                            $wproduct = 100/3;
                        }
                        $levelproduct='levelproduct'; 
                        } 
                    };
                     
                                if($level == 0){
                     $html .= '<li class="level2 '. $levelproduct .'" style="width:'.$wproduct.'%;">';              
                     $html .= '<a href="'. $_product->getProductUrl(). '" title="' . $this->htmlEscape($_product->getName()). '"> 
                          <img src="' . $this->helper('catalog/image')->init($_product, 'small_image')->resize(). '" alt="' . $this->htmlEscape($_product->getName()) . '" title="'. $this->htmlEscape($_product->getName()) .'"/></a>';
                } else {
                    $html .= '<li class="level2 '. $levelproduct .'">';
                    if((($widthc[0]['active_static_block'] ==  1) && ($widthc[0]['active_static_block_left'] ==  1)) ||(($widthc[0]['active_static_block'] ==  1) && ($widthc[0]['active_static_block_right'] ==  1)))
                    {
                        $html .= '<a href="'. $_product->getProductUrl(). '" title="' . $this->htmlEscape($_product->getName()). '"> 
                          <img src="' . $this->helper('catalog/image')->init($_product, 'small_image')->resize(). '" alt="' . $this->htmlEscape($_product->getName()) . '" title="'. $this->htmlEscape($_product->getName()) .'"/></a>';
                    } else{
                     $width = Mage::getStoreConfig('megamenu/mainmenu/width_thumbnail_category');
                     $height = Mage::getStoreConfig('megamenu/mainmenu/height_thumbnail_category');
                     $html .= '<a href="'. $_product->getProductUrl(). '" title="' . $this->htmlEscape($_product->getName()). '"> 
                          <img src="' . $this->helper('catalog/image')->init($_product, 'small_image')->resize(). '" alt="' . $this->htmlEscape($_product->getName()) . '" title="'. $this->htmlEscape($_product->getName()) .'" width="'.$width.'" ; height="'.$height.'"/></a>';
                     }
                    
                }
                $html .= '<a href="'. $_product->getProductUrl(). '" title="' . $this->htmlEscape($_product->getName()). '"> <span class="product-name">'.$_product->getName().'</span></a>';
                if($level == 0){
                    if (count($reviews) > 0) {
                    foreach ($reviews->getItems() as $review) {
                            foreach( $review->getRatingVotes() as $vote ) {
                                $ratings[] = $vote->getPercent();
                            }
                        }
                        $avg = array_sum($ratings)/count($ratings);
                    }
                    $html .='<div class="rating-box"><div class="rating" style="width:'. ceil($avg) .'%;"></div></div>';
                } else {
                    if((($widthc[0]['active_static_block'] ==  1) && ($widthc[0]['active_static_block_left'] ==  1)) ||(($widthc[0]['active_static_block'] ==  1) && ($widthc[0]['active_static_block_right'] ==  1)))
                    {
                        if (count($reviews) > 0) {
                        foreach ($reviews->getItems() as $review) {
                                foreach( $review->getRatingVotes() as $vote ) {
                                    $ratings[] = $vote->getPercent();
                                }
                            }
                            $avg = array_sum($ratings)/count($ratings);
                        }
                        $html .='<div class="rating-box"><div class="rating" style="width:'. ceil($avg) .'%;"></div></div>';
                    }
                }
                // $html .= Mage_Catalog_Block_Product::getPriceHtml($_product, true); 
                if($level==0){
                $html .= '<a href="'.$this->helper('checkout/cart')->getAddUrl($_product).'"><button class="button btn-cart btn-featurecart btn-carthover2" onclick="productAddToCartForm.submit(this)"><span><span>Add to Cart</span></span></button></a>';
                $html .= '<ul class="add-to-links">';
                $html .=  '<li><a href="'.$this->helper('wishlist')->getAddUrl($_product).'" class="link-wishlist">'.$this->__('Add Wishlist').'</a></li>';
                $html .=  '<li><a href="'.$this->getAddToCompareUrl($_product).'" class="link-compare">'.$this->__('Add Compare').'</a></li>';
                $html .= '</ul>';
                } else {
                    if((($widthc[0]['active_static_block'] ==  1) && ($widthc[0]['active_static_block_left'] ==  1)) ||(($widthc[0]['active_static_block'] ==  1) && ($widthc[0]['active_static_block_right'] ==  1)))
                    {
                        $html .= '<ul class="add-to-links">';
                        $html .=  '<li><a href="'.$this->helper('wishlist')->getAddUrl($_product).'" class="link-wishlist">'.$this->__('Wishlist').'</a></li>';
                        $html .=  '<li><a href="'.$this->getAddToCompareUrl($_product).'" class="link-compare">'.$this->__('Compare').'</a></li>';
                        $html .= '</ul>';
                    }
                }
                $html .= '</li>';               
                $i++;            
                }
            }
         $html .= '</ul>';
        return $html;
        
    }
   
    public function getLabel($megamenu){
         if($megamenu[0]['active_label'] == 1){
             return '<span  class="cat-label pin-bottom">'.$megamenu[0]['label'].'</span>';
         } else {
            return ;
         } 
    }
    public function getLevle($id){
        $megamenu = Mage::helper('megamenu')->Megamenu($id);
         if($megamenu[0]['level_column_count'] != 0){
             return $megamenu[0]['level_column_count'];
         } else {
            $level = 1;
            return $level;
         } 
    }
    public function fontcolor($megamenu){
        if( $megamenu[0]['font-color']) {
            return $megamenu[0]['font-color'];
         } else {
            return ;
         }   
    }
    public function backgroundcolor($megamenu){
        if( $megamenu[0]['background-color']) {
            return $megamenu[0]['background-color'];
         } else {
            return ;
         }   
    }
	 public function getCacheKeyInfo()
    {
        return null;
    }
}