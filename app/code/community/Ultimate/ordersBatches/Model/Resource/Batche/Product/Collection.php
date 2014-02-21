<?php 
/**
 * Ultimate_ordersBatches extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category   	Ultimate
 * @package		Ultimate_ordersBatches
 * @copyright  	Copyright (c) 2014
 * @license		http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Batch - product relation resource model collection
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Model_Resource_Batche_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection{
	/**
	 * remember if fields have been joined
	 * @var bool
	 */
	protected $_joinedFields = false;
	/**
	 * join the link table
	 * @access public
	 * @return Ultimate_ordersBatches_Model_Resource_Batche_Product_Collection
	 * @author Ultimate Module Creator
	 */
	public function joinFields(){
		if (!$this->_joinedFields){
			$this->getSelect()->join(
				array('related' => $this->getTable('ordersbatches/batche_product')),
				'related.product_id = e.entity_id',
				array('position')
			);
			$this->_joinedFields = true;
		}
		return $this;
	}
	/**
	 * add batche filter
	 * @access public
	 * @param Ultimate_ordersBatches_Model_Batche | int $batche
	 * @return Ultimate_ordersBatches_Model_Resource_Batche_Product_Collection
	 * @author Ultimate Module Creator
	 */
	public function addBatcheFilter($batche){
		if ($batche instanceof Ultimate_ordersBatches_Model_Batche){
			$batche = $batche->getId();
		}
		if (!$this->_joinedFields){
			$this->joinFields();
		}
		$this->getSelect()->where('related.batche_id = ?', $batche);
		return $this;
	}
}