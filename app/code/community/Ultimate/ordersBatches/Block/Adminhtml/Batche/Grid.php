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
 * Batch admin grid block
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Block_Adminhtml_Batche_Grid extends Mage_Adminhtml_Block_Widget_Grid{
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function __construct(){
		parent::__construct();
		$this->setId('batcheGrid');
		$this->setDefaultSort('entity_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
	}
	/**
	 * prepare collection
	 * @access protected
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Batche_Grid
	 * @author Ultimate Module Creator
	 */
	protected function _prepareCollection(){
		$collection = Mage::getModel('ordersbatches/batche')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	/**
	 * prepare grid collection
	 * @access protected
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Batche_Grid
	 * @author Ultimate Module Creator
	 */
	protected function _prepareColumns(){
		$this->addColumn('entity_id', array(
			'header'	=> Mage::helper('ordersbatches')->__('Id'),
			'index'		=> 'entity_id',
			'type'		=> 'number'
		));
		$this->addColumn('batches_name_input', array(
			'header'=> Mage::helper('ordersbatches')->__('Batch Name'),
			'index' => 'batches_name_input',
			'type'	 	=> 'text',

		));
		$this->addColumn('status', array(
			'header'	=> Mage::helper('ordersbatches')->__('Status'),
			'index'		=> 'status',
			'type'		=> 'options',
			'options'	=> array(
				'1' => Mage::helper('ordersbatches')->__('Enabled'),
				'0' => Mage::helper('ordersbatches')->__('Disabled'),
			)
		));
		if (!Mage::app()->isSingleStoreMode()) {
			$this->addColumn('store_id', array(
				'header'=> Mage::helper('ordersbatches')->__('Store Views'),
				'index' => 'store_id',
				'type'  => 'store',
				'store_all' => true,
				'store_view'=> true,
				'sortable'  => false,
				'filter_condition_callback'=> array($this, '_filterStoreCondition'),
			));
		}
		$this->addColumn('created_at', array(
			'header'	=> Mage::helper('ordersbatches')->__('Created at'),
			'index' 	=> 'created_at',
			'width' 	=> '120px',
			'type'  	=> 'datetime',
		));
		$this->addColumn('updated_at', array(
			'header'	=> Mage::helper('ordersbatches')->__('Updated at'),
			'index' 	=> 'updated_at',
			'width' 	=> '120px',
			'type'  	=> 'datetime',
		));
		$this->addColumn('action',
			array(
				'header'=>  Mage::helper('ordersbatches')->__('Action'),
				'width' => '100',
				'type'  => 'action',
				'getter'=> 'getId',
				'actions'   => array(
					array(
						'caption'   => Mage::helper('ordersbatches')->__('Edit'),
						'url'   => array('base'=> '*/*/edit'),
						'field' => 'id'
					)
				),
				'filter'=> false,
				'is_system'	=> true,
				'sortable'  => false,
		));
		$this->addExportType('*/*/exportCsv', Mage::helper('ordersbatches')->__('CSV'));
		$this->addExportType('*/*/exportExcel', Mage::helper('ordersbatches')->__('Excel'));
		$this->addExportType('*/*/exportXml', Mage::helper('ordersbatches')->__('XML'));
		return parent::_prepareColumns();
	}
	/**
	 * prepare mass action
	 * @access protected
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Batche_Grid
	 * @author Ultimate Module Creator
	 */
	protected function _prepareMassaction(){
		$this->setMassactionIdField('entity_id');
		$this->getMassactionBlock()->setFormFieldName('batche');
		$this->getMassactionBlock()->addItem('delete', array(
			'label'=> Mage::helper('ordersbatches')->__('Delete'),
			'url'  => $this->getUrl('*/*/massDelete'),
			'confirm'  => Mage::helper('ordersbatches')->__('Are you sure?')
		));
		$this->getMassactionBlock()->addItem('status', array(
			'label'=> Mage::helper('ordersbatches')->__('Change status'),
			'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
			'additional' => array(
				'status' => array(
						'name' => 'status',
						'type' => 'select',
						'class' => 'required-entry',
						'label' => Mage::helper('ordersbatches')->__('Status'),
						'values' => array(
								'1' => Mage::helper('ordersbatches')->__('Enabled'),
								'0' => Mage::helper('ordersbatches')->__('Disabled'),
						)
				)
			)
		));
		return $this;
	}
	/**
	 * get the row url
	 * @access public
	 * @param Ultimate_ordersBatches_Model_Batche
	 * @return string
	 * @author Ultimate Module Creator
	 */
	public function getRowUrl($row){
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
	/**
	 * get the grid url
	 * @access public
	 * @return string
	 * @author Ultimate Module Creator
	 */
	public function getGridUrl(){
		return $this->getUrl('*/*/grid', array('_current'=>true));
	}
	/**
	 * after collection load
	 * @access protected
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Batche_Grid
	 * @author Ultimate Module Creator
	 */
	protected function _afterLoadCollection(){
		$this->getCollection()->walk('afterLoad');
		parent::_afterLoadCollection();
	}
	/**
	 * filter store column
	 * @access protected
	 * @param Ultimate_ordersBatches_Model_Resource_Batche_Collection $collection
	 * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Batche_Grid
	 * @author Ultimate Module Creator
	 */
	protected function _filterStoreCondition($collection, $column){
		if (!$value = $column->getFilter()->getValue()) {
        	return;
		}
		$collection->addStoreFilter($value);
		return $this;
    }
}