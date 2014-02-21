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

class Lanot_EasyBanner_Adminhtml_CategoryController
	extends Lanot_EasyBanner_Controller_Adminhtml_AbstractController
{
    protected $_msgTitle = 'Banners';
    protected $_msgHeader = 'Manage Categories';
    protected $_msgItemDoesNotExist = 'The Category item does not exist.';
    protected $_msgItemNotFound = 'Unable to find the category item. #%s';
    protected $_msgItemEdit = 'Edit Category Item';
    protected $_msgItemNew = 'New Category Item';
    protected $_msgItemSaved = 'The Category item has been saved.';
    protected $_msgItemDeleted = 'The Category item has been deleted';
    protected $_msgError = 'An error occurred while edit the Category item. %s';
    protected $_msgErrorItems = 'An error occurred while edit the Category items. %s';
    protected $_msgItems = 'The Category items %s has been';

    protected $_aclSection = 'manage_category';

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _getItemModel()
    {
        return Mage::getModel('lanot_easybanner/category');
    }

    /**
     * @param Mage_Core_Model_Abstract $model
     * @return Lanot_EasyBanner_Controller_Adminhtml_AbstractController
     */
    protected function _registerItem(Mage_Core_Model_Abstract $model)
    {
        Mage::register('easy.banner.category.item', $model);
        return $this;
    }

    /**
     * Grid ajax action
     */
    public function ajaxbannersgridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Grid ajax action
     */
    public function ajaxbannersgridonlyAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}
