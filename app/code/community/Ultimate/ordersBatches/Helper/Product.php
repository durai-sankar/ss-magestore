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
 * Product helper
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Helper_Product extends Ultimate_ordersBatches_Helper_Data{
	/**
	 * get the selected batches for a product
	 * @access public
	 * @param Mage_Catalog_Model_Product $product
	 * @return array()
	 * @author Ultimate Module Creator
	 */
	public function getSelectedBatches(Mage_Catalog_Model_Product $product){
		if (!$product->hasSelectedBatches()) {
			$batches = array();
			foreach ($this->getSelectedBatchesCollection($product) as $batche) {
				$batches[] = $batche;
			}
			$product->setSelectedBatches($batches);
		}
		return $product->getData('selected_batches');
	}
	/**
	 * get batche collection for a product
	 * @access public
	 * @param Mage_Catalog_Model_Product $product
	 * @return Ultimate_ordersBatches_Model_Resource_Batche_Collection
	 */
	public function getSelectedBatchesCollection(Mage_Catalog_Model_Product $product){
		$collection = Mage::getResourceSingleton('ordersbatches/batche_collection')
			->addProductFilter($product);
		return $collection;
	}
}