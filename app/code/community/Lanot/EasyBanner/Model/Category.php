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

class Lanot_EasyBanner_Model_Category
    extends Mage_Core_Model_Abstract
    implements Lanot_Core_Model_Item_Interface
{    
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;

    /**
     * @var string
     */
    protected $_selectedBanners = null;
    
    /**
     *
     */
    protected function _construct()
    {
        $this->_init('lanot_easybanner/category');
    }

    /**
     * @return Lanot_EasyBanner_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_easybanner');
    }

    /**
     * @return array
     */
    public function getAvailableStatuses()
    {
        return array(
            self::STATUS_ENABLED  => $this->_getHelper()->__('Enabled'),
            self::STATUS_DISABLED => $this->_getHelper()->__('Disabled'),
        );
    }

    /**
     * @return int
     */
    public function getStatusDisabled()
    {
        return self::STATUS_DISABLED;
    }

    /**
     * @return int
     */
    public function getStatusEnabled()
    {
        return self::STATUS_ENABLED;
    }

    /**
     * @param bool $addEmpty
     * @return array
     */
    public function toOptionArray($addEmpty = true)
    {
        /** @var $collection Lanot_EasyBanner_Model_Mysql4_Category_Collection */
        $collection = $this->getCollection();
        $options = array();
        if ($addEmpty) {
            $options[] = array(
                'label' => $this->_getHelper()->__('-- Please Select a Category --'),
                'value' => ''
            );
        }
        foreach ($collection as $category) {
            $options[] = array(
                'label' => $category->getTitle(),
                'value' => $category->getId()
            );
        }

        return $options;
    }

    /**
     * @return Lanot_EasyBanner_Model_Category
     */
    protected function _beforeSave()
    {
    	parent::_beforeSave();

    	if ($this->isObjectNew()) {
    		$this->setData('created_at', Varien_Date::now());
    	}
        $this->setData('updated_at', Varien_Date::now());
        $this->_prepareBanners();

    	return $this;
    }

    /**
     * @return array
     */
    public function getSelectedBanners()
    {
        if (null === $this->_selectedBanners) {
            $this->_selectedBanners = $this->getResource()->getSelectedBanners($this);

        }
        return $this->_selectedBanners;
    }

    /**
     * @return Lanot_EasyBanner_Model_Category
     */
    protected function _prepareBanners()
    {
        $banners = $this->getBanners();
        if (is_string($banners) && !empty($banners)) {
            $this->setBanners(Mage::helper('adminhtml/js')->decodeGridSerializedInput($banners));
        } elseif (null !== $banners) {
            $this->setBanners(array());
        }
        return $this;
    }
}

