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
 
/**
 * Image Renderer
 *
 * @author Lanot
 */
class Lanot_EasyBanner_Block_Adminhtml_Renderer_Banner extends Varien_Data_Form_Element_Image
{
    /**
     * Enter description here...
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = '';
        if ($this->getComment()) {
            $html .= sprintf('<div>%s</div>', $this->getComment());
        }

        $url = '';
        if ($this->getValue()) {
            $url = $this->_getUrl();

            if( !preg_match("/^http\:\/\/|https\:\/\//", $url) ) {
                $url = Mage::getBaseUrl('media') . $url;
            }
        }
        $this->setClass('input-file');
        $html.= Varien_Data_Form_Element_Abstract::getElementHtml();
        $html.= $this->_getDeleteCheckbox();
        $html.= $this->getBannerHtml();

        return $html;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    protected function _getDeleteCheckbox()
    {
        $html = '';
        if ($this->getValue()) {
            $label = $this->_getHelper()->__('Delete File');
            $html .= '<span style="display:block;">';
            $html .= '<input type="checkbox" name="'.parent::getName().'[delete]" value="1" class="checkbox" id="'.$this->getHtmlId().'_delete"'.($this->getDisabled() ? ' disabled="disabled"': '').'/>';
            $html .= '<label for="'.$this->getHtmlId().'_delete"'.($this->getDisabled() ? ' class="disabled"' : '').'> '.$label.'</label>';
            $html .= $this->_getHiddenInput();
            $html .= '</span>';
        }

        return $html;
    }

    /**
     * @return Lanot_EasyBanner_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_easybanner');
    }
}