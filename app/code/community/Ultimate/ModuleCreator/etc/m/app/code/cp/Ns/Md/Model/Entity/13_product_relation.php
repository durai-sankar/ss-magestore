	/**
	 * get product relation model
	 * @access public
	 * @return {{Namespace}}_{{Module}}_Model_{{Entity}}_Product
	 * {{qwertyuiop}}
	 */
	public function getProductInstance(){
		if (!$this->_productInstance) {
			$this->_productInstance = Mage::getSingleton('{{module}}/{{entity}}_product');
		}
		return $this->_productInstance;
	}
	/**
	 * get selected products array
	 * @access public
	 * @return array
	 * {{qwertyuiop}}
	 */
	public function getSelectedProducts(){
		if (!$this->hasSelectedProducts()) {
			$products = array();
			foreach ($this->getSelectedProductsCollection() as $product) {
				$products[] = $product;
			}
			$this->setSelectedProducts($products);
		}
		return $this->getData('selected_products');
	}
	/**
	 * Retrieve collection selected products
	 * @access public
	 * @return {{Namespace}}_{{Module}}_Resource_{{Entity}}_Product_Collection
	 * {{qwertyuiop}}
	 */
	public function getSelectedProductsCollection(){
		$collection = $this->getProductInstance()->getProductCollection($this);
		return $collection;
	}
