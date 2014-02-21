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
 * @category    Lanot
 * @package     Lanot_EasyBanner
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Lanot_EasyBanner_Block_Banner_Renderer_Flash
    extends Lanot_EasyBanner_Block_Banner_Renderer_Abstract
{
    public function render()
    {
        $attributes = array();
        if ($this->_banner->getWidth()) {
            $attributes[] = 'width="' . $this->_banner->getWidth() . '"';
        }

        if ($this->_banner->getHeight()) {
            $attributes[] = 'height="' . $this->_banner->getHeight() . '"';
        }

        $code = ''.
            '<object type="application/x-shockwave-flash"
             data="' . $this->_banner->getPathUrl() . '"
             classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
             codebase="http://active.macromedia.com/flash4/cabs/swflash.cab#version=4,0,0,0"
            ' . implode(' ', $attributes) . '>
		        <param name="movie" value="' . $this->_banner->getPathUrl() . '">
			    <param name="wmode" value="opaque">
			    <param name="quality" value="high">
			    <embed src="' . $this->_banner->getPathUrl() . '" ' . implode(' ', $attributes) . '
			        wmode="opaque"
	    		    quality="high"
			        type="application/x-shockwave-flash"
                    pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"
                />
		</object>';

        return $this->_wrapHtml($code);
    }
}