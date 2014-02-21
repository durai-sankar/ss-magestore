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
 * Categories list admin grid
 *
 * @author Lanot
 */
class Lanot_EasyBanner_Block_Adminhtml_Category_Grid
    extends Lanot_EasyBanner_Block_Adminhtml_Grid_Abstract
{
    protected $_gridId = 'easybanner_categories_list_grid';
    protected $_entityIdField = 'category_id';
    protected $_itemParam = 'category_id';
    protected $_formFieldName = 'category';

    /**
     * @return Lanot_EasyBanner_Model_Category
     */
    protected function _getItemModel()
    {
        return Mage::getSingleton('lanot_easybanner/category');
    }
}
