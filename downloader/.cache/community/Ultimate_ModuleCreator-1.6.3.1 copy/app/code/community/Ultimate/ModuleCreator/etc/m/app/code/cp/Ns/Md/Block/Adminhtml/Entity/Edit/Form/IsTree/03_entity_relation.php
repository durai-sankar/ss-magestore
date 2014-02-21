	/**
	 * get {{siblingsLabel}} in json format
	 * @access public
	 * @return string
	 * {{qwertyuiop}}
	 */
	public function get{{Siblings}}Json(){
		${{siblings}} = $this->get{{Entity}}()->getSelected{{Siblings}}();
		if (!empty(${{siblings}})) {
			$positions = array();
			foreach (${{siblings}} as ${{sibling}}){
				$positions[${{sibling}}->getId()] = ${{sibling}}->getPosition();
			}
			return Mage::helper('core')->jsonEncode($positions);
		}
		return '{}';
	}
