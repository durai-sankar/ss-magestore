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
 * Category admin edit form main tab block
 *
 * @author Lanot
 */
class Lanot_EasyBanner_Block_Adminhtml_Banner_Edit_Tab_Categories
    extends Lanot_EasyBanner_Block_Adminhtml_Category_Grid
{
    protected $_aclSection = 'manage_banner';
    protected $_isTabGrid = true;
    protected $_columnPrefix = 'categories_';
    protected $_checkboxFieldName = 'categories_in_selected';
    protected $_bannerItemParam = 'banner_id';

    /** @var Lanot_EasyBanner_Model_Banner */
    protected $_bannerItem = null;

    /**
     * Init Grid default properties
     *
     */
    public function __construct()
    {
        parent::__construct();

        if ($this->_getBannerItem()->getId()) {
            $this->setDefaultFilter(array('in_selected' => 1));
        }
    }

    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return !$this->_getAclHelper()->isActionAllowed($this->_aclSection . '/save');
    }

    /**
     * Checks when this block is not available
     *
     * @return boolean
     */
    public function isMassActionAllowed()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/ajaxcategoriesgridonly', array('_current' => true));
    }

    /**
     * Retrieve selected categories
     *
     * @return array
     */
    public function getSelectedLinks()
    {
        return $this->_getBannerItem()->getSelectedCategories();
    }

    /**
     * @return Lanot_EasyBanner_Model_Banner|null
     */
    protected function _getBannerItem()
    {
        if ($this->_bannerItem !== null) {
            return $this->_bannerItem;
        }

        $itemId = $this->getRequest()->getParam($this->_bannerItemParam);
        $this->_bannerItem = $this->_getBannerItemModel();
        if ($itemId) {
            $this->_bannerItem->load($itemId);
        }
        return $this->_bannerItem;
    }

    /**
     * @return Lanot_EasyBanner_Model_Banner
     */
    protected function _getBannerItemModel()
    {
        return Mage::getSingleton('lanot_easybanner/banner');
    }
}
