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

class Lanot_EasyBanner_Block_Widget_Banners 
    extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{
    /** @var Lanot_EasyBanner_Model_Mysql4_Banner_Collection */
    protected $_collection = null;
    
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('lanot/easybanner/banners.phtml');
    }

    /**
     * @return Lanot_EasyBanner_Model_Mysql4_Banner_Collection
     */
    public function getItems()
    {
		if ($this->_collection !== null) {
		    return $this->_collection;
		}
		
        $this->_collection = $this->_getModel()->getCollection()
            ->useActive();///load only active banners        

        //set filter by category id
        if ($this->getCategoryId()) {
			$this->_collection->useCategory((int)$this->getCategoryId());
        }                
        //check use random
        if ($this->getRandom()) {
            $this->_collection->useRandom();
        } else {
            $this->_collection->useLast();
        }        
        //set limit
        if ($this->getLimit()) {
			$this->_collection->setPageSize((int)$this->getLimit());
        } 
        return $this->_collection->load();
    }

    /**
     * @param Lanot_EasyBanner_Model_Banner
     * @return string
     */
    public function render($item)
    {
        return $this->_getHelper()
            ->getBannerRenderer($item->getType())
            ->setBanner($item)
            ->render();
    }   

    /**
     * @return Lanot_EasyBanner_Model_Banner
     */
    protected function _getModel()
    {
        return Mage::getModel('lanot_easybanner/banner');
    }

    /**
     * @return Lanot_EasyBanner_Helpe_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_easybanner');
    }
}