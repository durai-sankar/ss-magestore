	/**
	 * get the {{entity}} {{attributeLabel}}
	 * @access public
	 * @return string
	 * {{qwertyuiop}}
	 */
	public function get{{AttributeMagicCode}}(){
		${{attributeCode}} = $this->getData('{{attributeCode}}');
		$helper = Mage::helper('cms');
		$processor = $helper->getBlockTemplateProcessor();
		$html = $processor->filter(${{attributeCode}});
		return $html;
	}
