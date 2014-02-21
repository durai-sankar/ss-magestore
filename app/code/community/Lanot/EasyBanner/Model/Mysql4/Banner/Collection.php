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
 * Banners collection
 *
 * @author Lanot
 */
class Lanot_EasyBanner_Model_Mysql4_Banner_Collection
extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Define collection model
     */
    protected function _construct()
    {
        $this->_init('lanot_easybanner/banner');
    }

    /**
     * @return Lanot_EasyBanner_Model_Mysql4_Banner_Collection
     */
    public function useCategory($categoryId)
    {
        $this->join(array('bc' => 'lanot_easybanner/banner_category'),
            'main_table.banner_id=bc.banner_id AND bc.category_id = ' . (int) $categoryId, array());

        $this->join(array('c' => 'lanot_easybanner/category'),
            'c.category_id = bc.category_id AND c.is_active = ' . 
            (int) Lanot_EasyBanner_Model_Category::STATUS_ENABLED, array());
            
        return $this;
    }

    /**
     * @return Lanot_EasyBanner_Model_Mysql4_Banner_Collection
     */
    public function useRandom()
    {
        $this->getSelect()->order(new Zend_Db_Expr('RAND()'));
        return $this;
    }
    
    /**
     * @return Lanot_EasyBanner_Model_Mysql4_Banner_Collection
     */
    public function useLast()
    {
        $this->setOrder('main_table.banner_id','DESC');
        return $this;
    }    
    
    /**
     * @return Lanot_EasyBanner_Model_Mysql4_Banner_Collection
     */    
    public function useActive()
    {
        $this->addFilter('main_table.is_active', Lanot_EasyBanner_Model_Banner::STATUS_ENABLED);
        return $this;
    }
}
     