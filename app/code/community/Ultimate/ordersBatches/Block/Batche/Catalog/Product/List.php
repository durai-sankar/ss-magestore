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
 * Batch product list
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Block_Batche_Catalog_Product_List extends Mage_Core_Block_Template{
	/**
	 * get the list of products
	 * @access public
	 * @return Mage_Catalog_Model_Resource_Product_Collection
	 * @author Ultimate Module Creator
	 */
	public function getProductCollection(){
		$collection = $this->getBatche()->getSelectedProductsCollection();
		$collection->addAttributeToSelect('name');
		$collection->addUrlRewrite();
		$collection->getSelect()->order('related.position');
		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
		Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
		return $collection;
	}
	/**
	 * get current batche
	 * @access public
	 * @return Ultimate_ordersBatches_Model_Batche
	 * @author Ultimate Module Creator
	 */
	public function getBatche(){
		return Mage::registry('current_ordersbatches_batche');
	}
}