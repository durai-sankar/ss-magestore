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
 * Batch list on product page block
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Block_Catalog_Product_List_Batche extends Mage_Catalog_Block_Product_Abstract{
	/**
	 * get the list of batches
	 * @access protected
	 * @return Ultimate_ordersBatches_Model_Resource_Batche_Collection 
	 * @author Ultimate Module Creator
	 */
	public function getBatcheCollection(){
		if (!$this->hasData('batche_collection')){
			$product = Mage::registry('product');
			$collection = Mage::getResourceSingleton('ordersbatches/batche_collection')
				->addStoreFilter(Mage::app()->getStore())

				->addFilter('status', 1)
				->addProductFilter($product);
			$collection->getSelect()->order('related_product.position', 'ASC');
			$this->setData('batche_collection', $collection);
		}
		return $this->getData('batche_collection');
	}
}