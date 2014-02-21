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
 * Batch edit form tab
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Block_Adminhtml_Batche_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form{	
	/**
	 * prepare the form
	 * @access protected
	 * @return ordersBatches_Batche_Block_Adminhtml_Batche_Edit_Tab_Form
	 * @author Ultimate Module Creator
	 */
	protected function _prepareForm(){
		$form = new Varien_Data_Form();
		$form->setHtmlIdPrefix('batche_');
		$form->setFieldNameSuffix('batche');
		$this->setForm($form);
		$fieldset = $form->addFieldset('batche_form', array('legend'=>Mage::helper('ordersbatches')->__('Batch')));

		$fieldset->addField('batches_name_input', 'text', array(
			'label' => Mage::helper('ordersbatches')->__('Batch Name'),
			'name'  => 'batches_name_input',
			'required'  => true,
			'class' => 'required-entry',

		));
		$fieldset->addField('status', 'select', array(
			'label' => Mage::helper('ordersbatches')->__('Status'),
			'name'  => 'status',
			'values'=> array(
				array(
					'value' => 1,
					'label' => Mage::helper('ordersbatches')->__('Enabled'),
				),
				array(
					'value' => 0,
					'label' => Mage::helper('ordersbatches')->__('Disabled'),
				),
			),
		));
		if (Mage::app()->isSingleStoreMode()){
			$fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_batche')->setStoreId(Mage::app()->getStore(true)->getId());
		}
		if (Mage::getSingleton('adminhtml/session')->getBatcheData()){
			$form->setValues(Mage::getSingleton('adminhtml/session')->getBatcheData());
			Mage::getSingleton('adminhtml/session')->setBatcheData(null);
		}
		elseif (Mage::registry('current_batche')){
			$form->setValues(Mage::registry('current_batche')->getData());
		}
		return parent::_prepareForm();
	}
}