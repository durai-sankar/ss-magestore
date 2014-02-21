<?php
/**
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2014 Vladimir Popov
 */

class SingPost_WebForms_Block_Adminhtml_Webforms_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	protected function _prepareLayout()
	{
		parent::_prepareLayout();
		// add scripts
		$js = $this->getLayout()->createBlock('core/template', 'webforms_js', array(
			'template' => 'webforms/js.phtml',
			'tabs_block' => 'webforms_tabs'
		));
		
		$this->getLayout()->getBlock('content')->append($js);
		
		if ((float)substr(Mage::getVersion() , 0, 3) > 1.3 && substr(Mage::getVersion() , 0, 5) != '1.4.0' || Mage::helper('webforms')->getMageEdition() == 'EE') if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
			$this->_formScripts[] = "
					function toggleEditor() {
						if (tinyMCE.getInstanceById('page_content') == null) {
							tinyMCE.execCommand('mceAddControl', false, 'content');
						} else {
							tinyMCE.execCommand('mceRemoveControl', false, 'content');
						}
					}";
		}
	}
	
	public function __construct()
	{
		parent::__construct();
		$this->_objectId = 'id';
		$this->_blockGroup = 'webforms';
		$this->_controller = 'adminhtml_webforms';
		
		if (Mage::registry('webforms_data') && Mage::registry('webforms_data')->getId()) {
			$this->_addButton('add_fieldset', array(
				'label' => Mage::helper('webforms')->__('Add Field Set') ,
				'class' => 'add',
				'onclick' => 'setLocation(\'' . $this->getAddFieldsetUrl() . '\')',
			));
			
			$click = 'setLocation(\'' . $this->getAddFieldUrl() . '\')';
			$fields = Mage::getModel('webforms/fields')->getCollection()->addFilter('webform_id', Mage::registry('webforms_data')->getId())->count();
			
			if ($fields >= 100) $click = 'alert(\'' . Mage::helper('webforms')->__('You have reached Community Edition limit!\nCommunity Edition allows you to add only 10 fields.\nUpgrade to Professional Edition.') . '\')';
			
			$this->_addButton('add_field', array(
				'label' => Mage::helper('webforms')->__('Add Field') ,
				'class' => 'add',
				'onclick' => $click,
			));
			
			$this->_removeButton('delete');
			
			$this->_addButton('delete', array(
				'label' => Mage::helper('adminhtml')->__('Delete Form') ,
				'class' => 'delete',
				'onclick' => 'deleteConfirm(\'' . Mage::helper('webforms')->__('Are you sure you want to delete the entire form and associated data?') . '\', \'' . $this->getDeleteUrl() . '\')',
			) , -1);
		} else {
			$this->_removeButton('save');
		}
		
		$this->_addButton('saveandcontinue', array(
			'label' => Mage::helper('adminhtml')->__('Save And Continue Edit') ,
			'onclick' => 'saveAndContinueEdit(\'' . $this->getSaveAndContinueUrl() . '\')',
			'class' => 'save',
		) , -100);
	}
	
	public function getSaveAndContinueUrl()
	{
		
		
		return $this->getUrl('*/*/save', array(
			'_current' => true,
			'back' => 'edit',
			'active_tab' => '{{tab_id}}'
		));
	}
	
	public function getDuplicateUrl()
	{
		
		
		return $this->getUrl('*/adminhtml_webforms/duplicate', array(
			'id' => Mage::registry('webforms_data')->getId()
		));
	}
	
	public function getAddFieldUrl()
	{
		
		
		return $this->getUrl('*/adminhtml_fields/edit', array(
			'webform_id' => Mage::registry('webforms_data')->getId()
		));
	}
	
	public function getAddFieldsetUrl()
	{
		
		
		return $this->getUrl('*/adminhtml_fieldsets/edit', array(
			'webform_id' => Mage::registry('webforms_data')->getId()
		));
	}
	
	public function getHeaderText()
	{
		if (Mage::registry('webforms_data') && Mage::registry('webforms_data')->getId()) {
			
			
			return Mage::helper('webforms')->__("Edit '%s' Form", $this->htmlEscape(Mage::registry('webforms_data')->getName()));
		} else {
			
			
			return Mage::helper('webforms')->__('Add Form');
		}
	}
}
?>