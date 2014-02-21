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

class Lanot_EasyBanner_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    const XML_NODE_RENDERERS = 'lanot_easybanner/renderers/%s';
    const XML_NODE_CONFIG = 'lanot_easybanner/view/%s';

    /**
     * @return Lanot_EasyBanner_Model_Category
     */
    public function getCategoryItemInstance()
	{
		return Mage::registry('easy.banner.category.item');
	}

    /**
     * @return Lanot_EasyBanner_Model_Banner
     */
    public function getBannerItemInstance()
    {
        return Mage::registry('easy.banner.banner.item');
    }

    /**
     * @return bool
     */
    public function isEnabled() {
        return (bool)Mage::getStoreConfig(sprintf(self::XML_NODE_CONFIG, 'enabled'), Mage::app()->getStore()->getId());
    }

    /**
     * @return bool
     */
    public function canShowNewWindow()
    {
        return (bool)Mage::getStoreConfig(sprintf(self::XML_NODE_CONFIG, 'new_window'), Mage::app()->getStore()->getId());
    }

    /**
     * @return string
     */
    public function getBlockCssClass()
    {
        return (string)Mage::getStoreConfig(sprintf(self::XML_NODE_CONFIG, 'block_css_class'), Mage::app()->getStore()->getId());
    }

    /**
     * @return string
     */
    public function getItemCssClass()
    {
        return (string)Mage::getStoreConfig(sprintf(self::XML_NODE_CONFIG, 'item_css_class'), Mage::app()->getStore()->getId());
    }
    
    /**
     * @return string
     */
    public function getTitleCssClass()
    {
        return (string)Mage::getStoreConfig(sprintf(self::XML_NODE_CONFIG, 'title_css_class'), Mage::app()->getStore()->getId());
    }    

    /**
     * @return Lanot_EasyBanner_Block_Banner_Renderer_Abstract
     */
    public function getBannerRenderer($type)
    {
        $path = sprintf(self::XML_NODE_RENDERERS, $type);
        $type = (string) Mage::getConfig()->getNode($path);
        if (!$type) {
            Mage::throwException($this->__('Could not retrieve xml node %s', $path));
        }
        return $this->_getBlock($type);
    }

    /**
     * @param $type
     * @return object
     */
    protected function _getBlock($type)
    {
        return Mage::getBlockSingleton($type);
    }
}
