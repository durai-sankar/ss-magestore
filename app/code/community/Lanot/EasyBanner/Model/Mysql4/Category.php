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
 * Banner Category item resource model
 *
 * @author Lanot
 */
class Lanot_EasyBanner_Model_Mysql4_Category
//extends Mage_Core_Model_Resource_Db_Abstract
extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Initialize connection and define main table and primary key
     */
    protected function _construct()
    {
        $this->_init('lanot_easybanner/category', 'category_id');
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $this->_saveBanners($object);

        return $this;
    }
    
    /**
     * @return string
     */
    protected function _getLinkTable()
    {
        return $this->getTable('lanot_easybanner/banner_category');
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return array
     */
    public function getSelectedBanners(Mage_Core_Model_Abstract $object)
    {
        $select = $this->getReadConnection()
            ->select()
            ->from($this->_getLinkTable(), array('banner_id'))
            ->where('category_id=?', $object->getId());

        return $this->getReadConnection()->fetchCol($select);
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return Lanot_EasyBanner_Model_Mysql4_Banner
     */
    protected function _saveBanners(Mage_Core_Model_Abstract $object)
    {
        //there is no any changes
        if (null === $object->getBanners()) {
            return $this;
        }

        $oldBanners = $object->getSelectedBanners($object);
        $newBanners = $object->getBanners();

        $insertBanners = array_diff($newBanners, $oldBanners);
        $deleteBanners = array_diff($oldBanners, $newBanners);

        if (!empty($insertBanners)) {
            $this->_insertBanners($object, $insertBanners);
        }
        if (!empty($deleteBanners)) {
            $this->_deleteBanners($object, $deleteBanners);
        }

        return $this;
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @param array $bannerIds
     * @return Lanot_EasyBanner_Model_Mysql4_Banner
     */
    protected function _insertBanners(Mage_Core_Model_Abstract $object, array $bannerIds)
    {
        $data = array();
        foreach($bannerIds as $bannerId) {
            $data[] = array('banner_id' => $bannerId, 'category_id' => $object->getId());
        }

        $this->_getWriteAdapter()->insertArray($this->_getLinkTable(), array('banner_id', 'category_id'), $data);

        return $this;
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @param array $bannerIds
     * @return Lanot_EasyBanner_Model_Mysql4_Banner
     */
    protected function _deleteBanners(Mage_Core_Model_Abstract $object, array $bannerIds)
    {
        $where = $this->_getWriteAdapter()->quoteInto('category_id = ?', $object->getId());
        $where.= $this->_getWriteAdapter()->quoteInto(' AND banner_id IN (?)', $bannerIds);
        $this->_getWriteAdapter()->delete($this->_getLinkTable(), $where);

        return $this;
    }
    
}
    