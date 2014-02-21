<?php
class FabioSantos_EtiquetaEndereco_Block_Etiqueta extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'etiqueta';
		$this->_blockGroup = 'etiquetaendereco';
		$this->_headerText = 'Shipping Labels';
		parent::__construct();
		$this->removeButton('add');
	}
}
