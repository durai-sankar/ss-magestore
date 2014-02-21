<?php
/**
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2014 Vladimir Popov
 */

class SingPost_WebForms_Block_Adminhtml_Webforms extends Mage_Adminhtml_Block_Widget_Grid_Container{
	public function __construct(){

		$this->_controller = 'adminhtml_webforms';
		$this->_blockGroup = 'webforms';
		$this->_headerText = Mage::helper('webforms')->__('Manage Forms');
		$this->_addButtonLabel = Mage::helper('webforms')->__('Add New Form');
		
		if (SingPost_WebForms_Block_Webforms::VERSION != '2.4.0.0') return;

		parent::__construct();

		$forms = Mage::getModel('webforms/webforms')->getCollection()->count();
		
		if($forms>=300){
			$this->_removeButton('add');
			$this->_addButton('add',array(
				'label' => Mage::helper('webforms')->__('Add New Form'),
				'onclick' => 'alert(\''.Mage::helper('webforms')->__('You have reached Community Edition limit!\nCommunity Edition allows you to manage only 3 forms.\nUpgrade to Professional Edition.').'\')',
			));
		}
	}
}
?>