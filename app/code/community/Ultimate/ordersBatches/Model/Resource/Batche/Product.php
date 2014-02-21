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
 * Batch - product relation model
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Model_Resource_Batche_Product extends Mage_Core_Model_Resource_Db_Abstract{
/**
	 * initialize resource model
	 * @access protected
	 * @return void
	 * @see Mage_Core_Model_Resource_Abstract::_construct()
	 * @author Ultimate Module Creator
	 */
	protected function  _construct(){
		$this->_init('ordersbatches/batche_product', 'rel_id');
	}
	/**
	 * Save batche - product relations
	 * @access public
	 * @param Ultimate_ordersBatches_Model_Batche $batche
	 * @param array $data
	 * @return Ultimate_ordersBatches_Model_Resource_Batche_Product
	 * @author Ultimate Module Creator
	 */
	public function saveBatcheRelation($batche, $data){
		if (!is_array($data)) {
			$data = array();
		}
		$deleteCondition = $this->_getWriteAdapter()->quoteInto('batche_id=?', $batche->getId());
		$this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

		foreach ($data as $productId => $info) {
			$this->_getWriteAdapter()->insert($this->getMainTable(), array(
				'batche_id'  	=> $batche->getId(),
				'product_id' 	=> $productId,
				'position'  	=> @$info['position']
			));
		}
		return $this;
	}
	/**
	 * Save  product - batche relations
	 * @access public
	 * @param Mage_Catalog_Model_Product $prooduct
	 * @param array $data
	 * @return Ultimate_ordersBatches_Model_Resource_Batche_Product
	 * @@author Ultimate Module Creator
	 */
	public function saveProductRelation($product, $data){
		if (!is_array($data)) {
			$data = array();
		}
		$deleteCondition = $this->_getWriteAdapter()->quoteInto('product_id=?', $product->getId());
		$this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);
		
		foreach ($data as $batcheId => $info) {
			$this->_getWriteAdapter()->insert($this->getMainTable(), array(
				'batche_id' => $batcheId,
				'product_id' => $product->getId(),
				'position'   => @$info['position']
			));
		}
		return $this;
	}
}