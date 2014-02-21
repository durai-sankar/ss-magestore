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
 * Banner item resource model
 *
 * @author Lanot
 */
class Lanot_EasyBanner_Model_Mysql4_Banner
extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Initialize connection and define main table and primary key
     */
    protected function _construct()
    {
        $this->_init('lanot_easybanner/banner', 'banner_id');
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $this->_saveCategories($object);

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
    public function getSelectedCategories(Mage_Core_Model_Abstract $object)
    {
        $select = $this->getReadConnection()
            ->select()
            ->from($this->_getLinkTable(), array('category_id'))
            ->where('banner_id=?', $object->getId());

        return $this->getReadConnection()->fetchCol($select);
    }


    /**
     * @param Mage_Core_Model_Abstract $object
     * @return Lanot_EasyBanner_Model_Mysql4_Banner
     */
    protected function _saveCategories(Mage_Core_Model_Abstract $object)
    {
        //there is no any changes
        if (null === $object->getCategories()) {
            return $this;
        }

        $oldCategories = $object->getSelectedCategories($object);
        $newCategories = $object->getCategories();

        $insertCategories = array_diff($newCategories, $oldCategories);
        $deleteCategories = array_diff($oldCategories, $newCategories);

        if (!empty($insertCategories)) {
            $this->_insertCategories($object, $insertCategories);
        }
        if (!empty($deleteCategories)) {
            $this->_deleteCategories($object, $deleteCategories);
        }

        return $this;
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @param array $categoryIds
     * @return Lanot_EasyBanner_Model_Mysql4_Banner
     */
    protected function _insertCategories(Mage_Core_Model_Abstract $object, array $categoryIds)
    {
        $data = array();
        foreach($categoryIds as $categoryId) {
            $data[] = array('banner_id' => $object->getId(), 'category_id' => $categoryId);
        }

        $this->_getWriteAdapter()->insertArray($this->_getLinkTable(), array('banner_id', 'category_id'), $data);

        return $this;
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @param array $categoryIds
     * @return Lanot_EasyBanner_Model_Mysql4_Banner
     */
    protected function _deleteCategories(Mage_Core_Model_Abstract $object, array $categoryIds)
    {
        $where = $this->_getWriteAdapter()->quoteInto('banner_id = ?', $object->getId());
        $where.= $this->_getWriteAdapter()->quoteInto(' AND category_id IN (?)', $categoryIds);
        $this->_getWriteAdapter()->delete($this->_getLinkTable(), $where);

        return $this;
    }
}
    