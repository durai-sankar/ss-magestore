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

class Lanot_EasyBanner_Adminhtml_BannerController
    extends Lanot_EasyBanner_Controller_Adminhtml_AbstractController
{
    protected $_msgTitle = 'Banners';
    protected $_msgHeader = 'Manage Banners';
    protected $_msgItemDoesNotExist = 'The Banner item does not exist.';
    protected $_msgItemNotFound = 'Unable to find the category item. #%s';
    protected $_msgItemEdit = 'Edit Banner Item';
    protected $_msgItemNew = 'New Banner Item';
    protected $_msgItemSaved = 'The Banner item has been saved.';
    protected $_msgItemDeleted = 'The Banner item has been deleted';
    protected $_msgError = 'An error occurred while edit the Banner item. %s';
    protected $_msgErrorItems = 'An error occurred while edit the Banner items. %s';
    protected $_msgItems = 'The Banner items %s has been';

    protected $_aclSection = 'manage_banner';

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _getItemModel()
    {
        return Mage::getModel('lanot_easybanner/banner');
    }

    /**
     * @param Mage_Core_Model_Abstract $model
     * @return Lanot_EasyBanner_Adminhtml_BannerController
     */
    protected function _registerItem(Mage_Core_Model_Abstract $model)
    {
        Mage::register('easy.banner.banner.item', $model);
        return $this;
    }

    /**
     * Grid ajax action
     */
    public function ajaxcategoriesgridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Grid ajax action
     */
    public function ajaxcategoriesgridonlyAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }    
}
