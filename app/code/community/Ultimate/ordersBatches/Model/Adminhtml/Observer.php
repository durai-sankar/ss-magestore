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
 * Adminhtml observer
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Model_Adminhtml_Observer{
	/**
	 * check if tab can be added
	 * @access protected
	 * @param Mage_Catalog_Model_Product $product
	 * @return bool
	 * @author Ultimate Module Creator
	 */
	protected function _canAddTab($product){
		if ($product->getId()){
			return true;
		}
		if (!$product->getAttributeSetId()){
			return false;
		}
		$request = Mage::app()->getRequest();
		if ($request->getParam('type') == 'configurable'){
			if ($request->getParam('attribtues')){
				return true;
			}
		}
		return false;
	}
	/**
	 * add the batche tab to products
	 * @access public
	 * @param Varien_Event_Observer $observer
	 * @return Ultimate_ordersBatches_Model_Adminhtml_Observer
	 * @author Ultimate Module Creator
	 */
	public function addBatcheBlock($observer){
		$block = $observer->getEvent()->getBlock();
		$product = Mage::registry('product');
		if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)){
			$block->addTab('batches', array(
				'label' => Mage::helper('ordersbatches')->__('Batches'),
				'url'   => Mage::helper('adminhtml')->getUrl('adminhtml/ordersbatches_batche_catalog_product/batches', array('_current' => true)),
				'class' => 'ajax',
			));
		}
		return $this;
	}
	/**
	 * save batche - product relation
	 * @access public
	 * @param Varien_Event_Observer $observer
	 * @return Ultimate_ordersBatches_Model_Adminhtml_Observer
	 * @author Ultimate Module Creator
	 */
	public function saveBatcheData($observer){
		$post = Mage::app()->getRequest()->getPost('batches', -1);
		if ($post != '-1') {
			$post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
			$product = Mage::registry('product');
			$batcheProduct = Mage::getResourceSingleton('ordersbatches/batche_product')->saveProductRelation($product, $post);
		}
		return $this;
	}}