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

class Lanot_EasyBanner_Block_Banner_Renderer_Abstract
{
    /** @var Lanot_EasyBanner_Model_Banner */
    protected $_banner = null;

    /** @var bool */
    protected $_disabledClick = false;

    /**
     * @param Lanot_EasyBanner_Model_Banner $banner
     * @return Lanot_EasyBanner_Block_Banner_Renderer_Abstract
     */
    public function setBanner(Lanot_EasyBanner_Model_Banner $banner)
    {
        $this->_banner = $banner;
        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
    }

    /**
     * @return Lanot_EasyBanner_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_easybanner');
    }

    /**
     * @param $data
     * @param null $allowedTags
     * @return mixed
     */
    protected function htmlEscape($data, $allowedTags = null)
    {
        return Mage::helper('core')->escapeHtml($data, $allowedTags);
    }

    /**
     * @param $html
     * @return string
     */
    protected function _wrapHtml($html)
    {
        if ($this->_banner->getUrl() && !$this->_disabledClick) {
            $html = sprintf('<a href="%s" %s>%s</a>', $this->_banner->getUrl(), $this->_getLinkAttributes(), $html);
        }
        return $html;
    }

    /**
     * @return string
     */
    protected function _getLinkAttributes()
    {
        $attrs = array();
        if ($this->_getHelper()->canShowNewWindow()) {
            $attrs[] = 'target="_blank"';
        }

        if ($this->_banner->getTitle()) {
            $attrs[] = 'title="' . $this->htmlEscape($this->_banner->getTitle()) . '"';
        }
        return implode(' ', $attrs);
    }

    /**
     * @return Lanot_EasyBanner_Block_Banner_Renderer_Abstract
     */
    public function disableClick()
    {
        $this->_disabledClick = true;
        return $this;
    }
}