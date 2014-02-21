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
 * Batch tab on product edit form
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Block_Adminhtml_Catalog_Product_Edit_Tab_Batche extends Mage_Adminhtml_Block_Widget_Grid{
	/**
	 * Set grid params
	 * @access protected
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function __construct(){
		parent::__construct();
		$this->setId('batche_grid');
		$this->setDefaultSort('position');
		$this->setDefaultDir('ASC');
		$this->setUseAjax(true);
		if ($this->getProduct()->getId()) {
			$this->setDefaultFilter(array('in_batches'=>1));
		}
	}
	/**
	 * prepare the batche collection
	 * @access protected 
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Catalog_Product_Edit_Tab_Batche
	 * @author Ultimate Module Creator
	 */
	protected function _prepareCollection() {
		$collection = Mage::getResourceModel('ordersbatches/batche_collection');
		if ($this->getProduct()->getId()){
			$constraint = 'related.product_id='.$this->getProduct()->getId();
			}
			else{
				$constraint = 'related.product_id=0';
			}
		$collection->getSelect()->joinLeft(
			array('related'=>$collection->getTable('ordersbatches/batche_product')),
			'related.batche_id=main_table.entity_id AND '.$constraint,
			array('position')
		);
		$this->setCollection($collection);
		parent::_prepareCollection();
		return $this;
	}
	/**
	 * prepare mass action grid
	 * @access protected
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Catalog_Product_Edit_Tab_Batche
	 * @author Ultimate Module Creator
	 */ 
	protected function _prepareMassaction(){
		return $this;
	}
	/**
	 * prepare the grid columns
	 * @access protected
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Catalog_Product_Edit_Tab_Batche
	 * @author Ultimate Module Creator
	 */
	protected function _prepareColumns(){
		$this->addColumn('in_batches', array(
			'header_css_class'  => 'a-center',
			'type'  => 'checkbox',
			'name'  => 'in_batches',
			'values'=> $this->_getSelectedBatches(),
			'align' => 'center',
			'index' => 'entity_id'
		));
		$this->addColumn('batches_name_input', array(
			'header'=> Mage::helper('ordersbatches')->__('Batch Name'),
			'align' => 'left',
			'index' => 'batches_name_input',
		));
		$this->addColumn('position', array(
			'header'		=> Mage::helper('ordersbatches')->__('Position'),
			'name'  		=> 'position',
			'width' 		=> 60,
			'type'		=> 'number',
			'validate_class'=> 'validate-number',
			'index' 		=> 'position',
			'editable'  	=> true,
		));
	}
	/**
	 * Retrieve selected batches
	 * @access protected
	 * @return array
	 * @author Ultimate Module Creator
	 */
	protected function _getSelectedBatches(){
		$batches = $this->getProductBatches();
		if (!is_array($batches)) {
			$batches = array_keys($this->getSelectedBatches());
		}
		return $batches;
	}
 	/**
	 * Retrieve selected batches
	 * @access protected
	 * @return array
	 * @author Ultimate Module Creator
	 */
	public function getSelectedBatches() {
		$batches = array();
		//used helper here in order not to override the product model
		$selected = Mage::helper('ordersbatches/product')->getSelectedBatches(Mage::registry('current_product'));
		if (!is_array($selected)){
			$selected = array();
		}
		foreach ($selected as $batche) {
			$batches[$batche->getId()] = array('position' => $batche->getPosition());
		}
		return $batches;
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
		return $this->getUrl('*/*/batchesGrid', array(
			'id'=>$this->getProduct()->getId()
		));
	}
	/**
	 * get the current product
	 * @access public
	 * @return Mage_Catalog_Model_Product
	 * @author Ultimate Module Creator
	 */
	public function getProduct(){
		return Mage::registry('current_product');
	}
	/**
	 * Add filter
	 * @access protected
	 * @param object $column
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Catalog_Product_Edit_Tab_Batche
	 * @author Ultimate Module Creator
	 */
	protected function _addColumnFilterToCollection($column){
		if ($column->getId() == 'in_batches') {
			$batcheIds = $this->_getSelectedBatches();
			if (empty($batcheIds)) {
				$batcheIds = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$batcheIds));
			} 
			else {
				if($batcheIds) {
					$this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$batcheIds));
				}
			}
		} 
		else {
			parent::_addColumnFilterToCollection($column);
		}
		return $this;
	}
}