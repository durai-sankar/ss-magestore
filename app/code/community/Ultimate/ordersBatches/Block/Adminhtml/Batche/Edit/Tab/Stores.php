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
 * store selection tab
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Block_Adminhtml_Batche_Edit_Tab_Stores extends Mage_Adminhtml_Block_Widget_Form{
	/**
	 * prepare the form
	 * @access protected
	 * @return Ultimate_ordersBatches_Block_Adminhtml_Batche_Edit_Tab_Stores
	 * @author Ultimate Module Creator
	 */
	protected function _prepareForm(){
		$form = new Varien_Data_Form();
		$form->setFieldNameSuffix('batche');
		$this->setForm($form);
		$fieldset = $form->addFieldset('batche_stores_form', array('legend'=>Mage::helper('ordersbatches')->__('Store views')));
		$field = $fieldset->addField('store_id', 'multiselect', array(
			'name'  => 'stores[]',
			'label' => Mage::helper('ordersbatches')->__('Store Views'),
			'title' => Mage::helper('ordersbatches')->__('Store Views'),
			'required'  => true,
			'values'=> Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
		));
		$renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
		$field->setRenderer($renderer);
  		$form->addValues(Mage::registry('current_batche')->getData());
		return parent::_prepareForm();
	}
}