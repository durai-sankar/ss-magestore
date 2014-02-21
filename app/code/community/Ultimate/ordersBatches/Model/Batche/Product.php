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
 * Batch product model
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Model_Batche_Product extends Mage_Core_Model_Abstract{
	/**
	 * Initialize resource
	 * @access protected
	 * @return void
	 * @author Ultimate Module Creator
	 */
	protected function _construct(){
		$this->_init('ordersbatches/batche_product');
	}
	/**
	 * Save data for batche-product relation
	 * @access public
	 * @param  Ultimate_ordersBatches_Model_Batche $batche
	 * @return Ultimate_ordersBatches_Model_Batche_Product
	 * @author Ultimate Module Creator
	 */
	public function saveBatcheRelation($batche){
		$data = $batche->getProductsData();
		if (!is_null($data)) {
			$this->_getResource()->saveBatcheRelation($batche, $data);
		}
		return $this;
	}
	/**
	 * get products for batche
	 * @access public
	 * @param Ultimate_ordersBatches_Model_Batche $batche
	 * @return Ultimate_ordersBatches_Model_Resource_Batche_Product_Collection
	 * @author Ultimate Module Creator
	 */
	public function getProductCollection($batche){
		$collection = Mage::getResourceModel('ordersbatches/batche_product_collection')
			->addBatcheFilter($batche);
		return $collection;
	}
}