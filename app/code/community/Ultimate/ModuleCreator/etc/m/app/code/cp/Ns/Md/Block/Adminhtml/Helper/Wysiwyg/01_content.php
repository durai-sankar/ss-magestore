<?php
{{License}}
/**
 * {{Module}} textarea attribute WYSIWYG button
 * @category   {{Namespace}}
 * @package	{{Namespace}}_{{Module}}
 * {{qwertyuiop}}
 */
class {{Namespace}}_{{Module}}_Block_Adminhtml_Helper_Wysiwyg extends Varien_Data_Form_Element_Textarea
{
	/**
	 * Retrieve additional html and put it at the end of element html
	 * @access public
	 * @return string
	 * {{qwertyuiop}}
	 */
	public function getAfterElementHtml(){
		$html = parent::getAfterElementHtml();
		$disabled = ($this->getDisabled() || $this->getReadonly());
		$html .= Mage::getSingleton('core/layout')
			->createBlock('adminhtml/widget_button', '', array(
				'label'   => Mage::helper('catalog')->__('WYSIWYG Editor'),
				'type'=> 'button',
				'disabled' => $disabled,
				'class' => ($disabled) ? 'disabled btn-wysiwyg' : 'btn-wysiwyg',
				'onclick' => 'catalogWysiwygEditor.open(\''.Mage::helper('adminhtml')->getUrl('*/*/wysiwyg').'\', \''.$this->getHtmlId().'\')'
			))->toHtml();
		return $html;
	}
}