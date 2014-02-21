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
 * Batch admin edit tabs
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Block_Adminhtml_Batche_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{
	/**
	 * constructor
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function __construct(){
		parent::__construct();
		$this->setId('batche_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('ordersbatches')->__('Batch'));
	}
	/**
	 * before render html
	 * @access protected
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Batche_Edit_Tabs
	 * @author Ultimate Module Creator
	 */
	protected function _beforeToHtml(){
		$this->addTab('form_batche', array(
			'label'		=> Mage::helper('ordersbatches')->__('Batch'),
			'title'		=> Mage::helper('ordersbatches')->__('Batch'),
			'content' 	=> $this->getLayout()->createBlock('ordersbatches/adminhtml_batche_edit_tab_form')->toHtml(),
		));
		if (!Mage::app()->isSingleStoreMode()){
			$this->addTab('form_store_batche', array(
				'label'		=> Mage::helper('ordersbatches')->__('Store views'),
				'title'		=> Mage::helper('ordersbatches')->__('Store views'),
				'content' 	=> $this->getLayout()->createBlock('ordersbatches/adminhtml_batche_edit_tab_stores')->toHtml(),
			));
		}
		$this->addTab('products', array(
			'label' => Mage::helper('ordersbatches')->__('Associated products'),
			'url'   => $this->getUrl('*/*/products', array('_current' => true)),
   			'class'	=> 'ajax'
		));
		return parent::_beforeToHtml();
	}
}