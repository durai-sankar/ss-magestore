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
 * Batch admin edit block
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Block_Adminhtml_Batche_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{
	/**
	 * constuctor
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function __construct(){
		parent::__construct();
		$this->_blockGroup = 'ordersbatches';
		$this->_controller = 'adminhtml_batche';
		$this->_updateButton('save', 'label', Mage::helper('ordersbatches')->__('Save Batch'));
		$this->_updateButton('delete', 'label', Mage::helper('ordersbatches')->__('Delete Batch'));
		$this->_addButton('saveandcontinue', array(
			'label'		=> Mage::helper('ordersbatches')->__('Save And Continue Edit'),
			'onclick'	=> 'saveAndContinueEdit()',
			'class'		=> 'save',
		), -100);
		$this->_formScripts[] = "
			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";
	}
	/**
	 * get the edit form header
	 * @access public
	 * @return string
	 * @author Ultimate Module Creator
	 */
	public function getHeaderText(){
		if( Mage::registry('batche_data') && Mage::registry('batche_data')->getId() ) {
			return Mage::helper('ordersbatches')->__("Edit Batch '%s'", $this->htmlEscape(Mage::registry('batche_data')->getBatchesNameInput()));
		} 
		else {
			return Mage::helper('ordersbatches')->__('Add Batch');
		}
	}
}