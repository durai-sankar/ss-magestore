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
 * Batch - product relation edit block
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Block_Adminhtml_Batche_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid{
	/**
	 * Set grid params
	 * @access protected
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function __construct(){
		parent::__construct();
		$this->setId('product_grid');
		$this->setDefaultSort('position');
		$this->setDefaultDir('ASC');
		$this->setUseAjax(true);
		if ($this->getBatche()->getId()) {
			$this->setDefaultFilter(array('in_products'=>1));
		}
	}
	/**
	 * prepare the product collection
	 * @access protected 
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Batche_Edit_Tab_Product
	 * @author Ultimate Module Creator
	 */
	protected function _prepareCollection() {
		$collection = Mage::getResourceModel('catalog/product_collection');
		$collection->addAttributeToSelect('price');
		$adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
		$collection->joinAttribute('product_name', 'catalog_product/name', 'entity_id', null, 'left', $adminStore);
		if ($this->getBatche()->getId()){
			$constraint = '{{table}}.batche_id='.$this->getBatche()->getId();
		}
		else{
			$constraint = '{{table}}.batche_id=0';
		}
		$collection->joinField('position',
			'ordersbatches/batche_product',
			'position',
			'product_id=entity_id',
			$constraint,
			'left');
		$this->setCollection($collection);
		parent::_prepareCollection();
		return $this;
	}
	/**
	 * prepare mass action grid
	 * @access protected
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Batche_Edit_Tab_Product
	 * @author Ultimate Module Creator
	 */ 
	protected function _prepareMassaction(){
		return $this;
	}
	/**
	 * prepare the grid columns
	 * @access protected
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Batche_Edit_Tab_Product
	 * @author Ultimate Module Creator
	 */
	protected function _prepareColumns(){
		$this->addColumn('in_products', array(
			'header_css_class'  => 'a-center',
			'type'  => 'checkbox',
			'name'  => 'in_products',
			'values'=> $this->_getSelectedProducts(),
			'align' => 'center',
			'index' => 'entity_id'
		));
		$this->addColumn('product_name', array(
			'header'=> Mage::helper('catalog')->__('Name'),
			'align' => 'left',
			'index' => 'product_name',
		));
		$this->addColumn('sku', array(
			'header'=> Mage::helper('catalog')->__('SKU'),
			'align' => 'left',
			'index' => 'sku',
		));
		$this->addColumn('price', array(
			'header'=> Mage::helper('catalog')->__('Price'),
			'type'  => 'currency',
			'width' => '1',
			'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
			'index' => 'price'
		));
		$this->addColumn('position', array(
			'header'=> Mage::helper('catalog')->__('Position'),
			'name'  => 'position',
			'width' => 60,
			'type'  => 'number',
			'validate_class'=> 'validate-number',
			'index' => 'position',
			'editable'  => true,
		));
	}
	/**
	 * Retrieve selected products
	 * @access protected
	 * @return array
	 * @author Ultimate Module Creator
	 */
	protected function _getSelectedProducts(){
		$products = $this->getBatcheProducts();
		if (!is_array($products)) {
			$products = array_keys($this->getSelectedProducts());
		}
		return $products;
	}
 	/**
	 * Retrieve selected products
	 * @access protected
	 * @return array
	 * @author Ultimate Module Creator
	 */
	public function getSelectedProducts() {
		$products = array();
		$selected = Mage::registry('current_batche')->getSelectedProducts();
		if (!is_array($selected)){
			$selected = array();
		}
		foreach ($selected as $product) {
			$products[$product->getId()] = array('position' => $product->getPosition());
		}
		return $products;
	}
	/**
	 * get row url
	 * @access public
	 * @return string
	 * @author Ultimate Module Creator
	 */
	public function getRowUrl($item){
		return '#';
	}
	/**
	 * get grid url
	 * @access public
	 * @return string
	 * @author Ultimate Module Creator
	 */
	public function getGridUrl(){
		return $this->getUrl('*/*/productsGrid', array(
			'id'=>$this->getBatche()->getId()
		));
	}
	/**
	 * get the current batche
	 * @access public
	 * @return Ultimate_ordersBatches_Model_Batche
	 * @author Ultimate Module Creator
	 */
	public function getBatche(){
		return Mage::registry('current_batche');
	}
	/**
	 * Add filter
	 * @access protected
	 * @param object $column
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Batche_Edit_Tab_Product
	 * @author Ultimate Module Creator
	 */
	protected function _addColumnFilterToCollection($column){
		// Set custom filter for in product flag
		if ($column->getId() == 'in_products') {
			$productIds = $this->_getSelectedProducts();
			if (empty($productIds)) {
				$productIds = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
			} 
			else {
				if($productIds) {
					$this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
				}
			}
		} 
		else {
			parent::_addColumnFilterToCollection($column);
		}
		return $this;
	}
}