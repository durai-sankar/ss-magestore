	/**
	 * get grid of products action
	 * @access public
	 * @return void
	 * {{qwertyuiop}}
	 */
	public function productsAction(){
		$this->_init{{Entity}}();
		$this->loadLayout();
		$this->getLayout()->getBlock('{{entity}}.edit.tab.product')
			->set{{Entity}}Products($this->getRequest()->getPost('{{entity}}_products', null));
		$this->renderLayout();
	}
	/**
	 * get grid of products action
	 * @access public
	 * @return void
	 * {{qwertyuiop}}
	 */
	public function productsgridAction(){
		$this->_init{{Entity}}();
		$this->loadLayout();
		$this->getLayout()->getBlock('{{entity}}.edit.tab.product')
			->set{{Entity}}Products($this->getRequest()->getPost('{{entity}}_products', null));
		$this->renderLayout();
	}
