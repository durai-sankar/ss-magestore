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
 * Batch model
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Model_Batche extends Mage_Core_Model_Abstract{
	/**
	 * Entity code.
	 * Can be used as part of method name for entity processing
	 */
	const ENTITY= 'ordersbatches_batche';
	const CACHE_TAG = 'ordersbatches_batche';
	/**
	 * Prefix of model events names
	 * @var string
	 */
	protected $_eventPrefix = 'ordersbatches_batche';
	
	/**
	 * Parameter name in event
	 * @var string
	 */
	protected $_eventObject = 'batche';
	protected $_productInstance = null;
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function _construct(){
		parent::_construct();
		$this->_init('ordersbatches/batche');
	}
	/**
	 * before save batch
	 * @access protected
	 * @return Ultimate_ordersBatches_Model_Batche
	 * @author Ultimate Module Creator
	 */
	protected function _beforeSave(){
		parent::_beforeSave();
		$now = Mage::getSingleton('core/date')->gmtDate();
		if ($this->isObjectNew()){
			$this->setCreatedAt($now);
		}
		$this->setUpdatedAt($now);
		return $this;
	}
	/**
	 * save batche relation
	 * @access public
	 * @return Ultimate_ordersBatches_Model_Batche
	 * @author Ultimate Module Creator
	 */
	protected function _afterSave() {
		$this->getProductInstance()->saveBatcheRelation($this);
		return parent::_afterSave();
	}
	/**
	 * get product relation model
	 * @access public
	 * @return Ultimate_ordersBatches_Model_Batche_Product
	 * @author Ultimate Module Creator
	 */
	public function getProductInstance(){
		if (!$this->_productInstance) {
			$this->_productInstance = Mage::getSingleton('ordersbatches/batche_product');
		}
		return $this->_productInstance;
	}
	/**
	 * get selected products array
	 * @access public
	 * @return array
	 * @author Ultimate Module Creator
	 */
	public function getSelectedProducts(){
		if (!$this->hasSelectedProducts()) {
			$products = array();
			foreach ($this->getSelectedProductsCollection() as $product) {
				$products[] = $product;
			}
			$this->setSelectedProducts($products);
		}
		return $this->getData('selected_products');
	}
	/**
	 * Retrieve collection selected products
	 * @access public
	 * @return Ultimate_ordersBatches_Resource_Batche_Product_Collection
	 * @author Ultimate Module Creator
	 */
	public function getSelectedProductsCollection(){
		$collection = $this->getProductInstance()->getProductCollection($this);
		return $collection;
	}
}