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
 * Batche product admin controller
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class Ultimate_ordersBatches_Adminhtml_ordersBatches_Batche_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController{
	/**
	 * construct
	 * @access protected
	 * @return void
	 * @author Ultimate Module Creator
	 */
	protected function _construct(){
		// Define module dependent translate
		$this->setUsedModuleName('Ultimate_ordersBatches');
	}
	/**
	 * batches in the catalog page
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function batchesAction(){
		$this->_initProduct();
		$this->loadLayout();
		$this->getLayout()->getBlock('product.edit.tab.batche')
			->setProductBatches($this->getRequest()->getPost('product_batches', null));
		$this->renderLayout();
	}
	/**
	 * batches grid in the catalog page
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function batchesGridAction(){
		$this->_initProduct();
		$this->loadLayout();
		$this->getLayout()->getBlock('product.edit.tab.batche')
			->setProductBatches($this->getRequest()->getPost('product_batches', null));
		$this->renderLayout();
	}
}