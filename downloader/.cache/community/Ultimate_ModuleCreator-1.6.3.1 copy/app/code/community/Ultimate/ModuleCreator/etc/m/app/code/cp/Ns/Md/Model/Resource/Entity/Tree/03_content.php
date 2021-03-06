	/**
	 * Initialize tree
	 * @access public
	 * @return void
	 * {{qwertyuiop}}
	 */
	public function __construct(){
		$resource = Mage::getSingleton('core/resource');
		parent::__construct(
			$resource->getConnection('{{module}}_write'),
			$resource->getTableName('{{module}}/{{entity}}'),
			array(
				Varien_Data_Tree_Dbp::ID_FIELD   => 'entity_id',
				Varien_Data_Tree_Dbp::PATH_FIELD => 'path',
				Varien_Data_Tree_Dbp::ORDER_FIELD=> 'position',
				Varien_Data_Tree_Dbp::LEVEL_FIELD=> 'level',
			)
		);
	}

	/**
	 * Get {{entitiesLabel}} collection
	 * @access public
	 * @param boolean $sorted
	 * @return {{Namespace}}_{{Module}}_Model_Resource_{{Entity}}_Collection
	 * {{qwertyuiop}}
	 */
	public function getCollection($sorted = false){
		if (is_null($this->_collection)) {
			$this->_collection = $this->_getDefaultCollection($sorted);
		}
		return $this->_collection;
	}
	/**
	 * set the collection
	 * @access public
	 * @param {{Namespace}}_{{Module}}_Model_Resource_{{Entity}}_Collection $collection
	 * @return {{Namespace}}_{{Module}}_Model_Resource_{{Entity}}_Tree
	 */
	public function setCollection($collection){
		if (!is_null($this->_collection)) {
			destruct($this->_collection);
		}
		$this->_collection = $collection;
		return $this;
	}
	/**
	 * get the default collection
	 * @access protected
	 * @param boolean $sorted
	 * @return {{Namespace}}_{{Module}}_Model_Resource_{{Entity}}_Collection
	 */
	protected function _getDefaultCollection($sorted = false){
		$collection = Mage::getModel('{{module}}/{{entity}}')->getCollection();
		if ($sorted) {
			if (is_string($sorted)) {
				$collection->setOrder($sorted);
			} 
			else {
				$collection->setOrder('name');
			}
		}
		return $collection;
	}
	/**
	 * Executing parents move method and cleaning cache after it
	 * @access public
	 * @param unknown_type ${{entity}}
	 * @param unknown_type $newParent
	 * @param unknown_type $prevNode
	 * {{qwertyuiop}}
	 */
	public function move(${{entity}}, $newParent, $prevNode = null){
		Mage::getResourceSingleton('{{module}}/{{entity}}')->move(${{entity}}->getId(), $newParent->getId());
		parent::move(${{entity}}, $newParent, $prevNode);
		$this->_afterMove(${{entity}}, $newParent, $prevNode);
	}

	/**
	 * Move tree after
	 * @access protected
	 * @param unknown_type ${{entity}}
	 * @param Varien_Data_Tree_Node $newParent
	 * @param Varien_Data_Tree_Node $prevNode
	 * @return {{Namespace}}_{{Module}}_Model_Resource_{{Entity}}_Tree
	 */
	protected function _afterMove(${{entity}}, $newParent, $prevNode){
		Mage::app()->cleanCache(array({{Namespace}}_{{Module}}_Model_{{Entity}}::CACHE_TAG));
		return $this;
	}
	/**
	 * Load whole {{entityLabel}} tree, that will include specified {{entitiesLabel}} ids.
	 * @access public
	 * @param array $ids
	 * @param bool $addCollectionData
	 * @return {{Namespace}}_{{Module}}_Model_Resource_{{Entity}}_Tree
	 * {{qwertyuiop}}
	 */
	public function loadByIds($ids, $addCollectionData = true){
		$levelField = $this->_conn->quoteIdentifier('level');
		$pathField  = $this->_conn->quoteIdentifier('path');
		// load first two levels, if no ids specified
		if (empty($ids)) {
			$select = $this->_conn->select()
				->from($this->_table, 'entity_id')
				->where($levelField . ' <= 2');
				$ids = $this->_conn->fetchCol($select);
		}
		if (!is_array($ids)) {
			$ids = array($ids);
		}
		foreach ($ids as $key => $id) {
			$ids[$key] = (int)$id;
		}
		// collect paths of specified IDs and prepare to collect all their parents and neighbours
		$select = $this->_conn->select()
			->from($this->_table, array('path', 'level'))
			->where('entity_id IN (?)', $ids);
		$where = array($levelField . '=0' => true);
		
		foreach ($this->_conn->fetchAll($select) as $item) {
			$pathIds  = explode('/', $item['path']);
			$level = (int)$item['level'];
			while ($level > 0) {
				$pathIds[count($pathIds) - 1] = '%';
				$path = implode('/', $pathIds);
				$where["$levelField=$level AND $pathField LIKE '$path'"] = true;
				array_pop($pathIds);
				$level--;
			}
		}
		$where = array_keys($where);
		
		// get all required records
		if ($addCollectionData) {
			$select = $this->_createCollectionDataSelect();
		} 
		else {
			$select = clone $this->_select;
			$select->order($this->_orderField . ' ' . Varien_Db_Select::SQL_ASC);
		}
		$select->where(implode(' OR ', $where));
		
		// get array of records and add them as nodes to the tree
		$arrNodes = $this->_conn->fetchAll($select);
		if (!$arrNodes) {
			return false;
		}
		$childrenItems = array();
		foreach ($arrNodes as $key => $nodeInfo) {
			$pathToParent = explode('/', $nodeInfo[$this->_pathField]);
			array_pop($pathToParent);
			$pathToParent = implode('/', $pathToParent);
			$childrenItems[$pathToParent][] = $nodeInfo;
		}
		$this->addChildNodes($childrenItems, '', null);
		return $this;
	}
	/**
	 * Load array of {{entityLabel}} parents
	 * @access public
	 * @param string $path
	 * @param bool $addCollectionData
	 * @param bool $withRootNode
	 * @return array
	 * {{qwertyuiop}}
	 */
	public function loadBreadcrumbsArray($path, $addCollectionData = true, $withRootNode = false){
		$pathIds = explode('/', $path);
		if (!$withRootNode) {
			array_shift($pathIds);
		}
		$result = array();
		if (!empty($pathIds)) {
			if ($addCollectionData) {
				$select = $this->_createCollectionDataSelect(false);
			} 
			else {
				$select = clone $this->_select;
			}
			$select
				->where('main_table.entity_id IN(?)', $pathIds)
				->order($this->_conn->getLengthSql('main_table.path') . ' ' . Varien_Db_Select::SQL_ASC);
			$result = $this->_conn->fetchAll($select);
		}
		return $result;
	}
	/**
	 * Obtain select for {{entitiesLabel}}
	 * By default everything from entity table is selected
	 * + name
	 * @access public
	 * @param bool $sorted
	 * @param array $optionalAttributes
	 * @return Zend_Db_Select
	 * {{qwertyuiop}}
	 */
	protected function _createCollectionDataSelect($sorted = true){
		$select = $this->_getDefaultCollection($sorted ? $this->_orderField : false)->getSelect();
		${{entities}}Table = Mage::getSingleton('core/resource')->getTableName('{{module}}/{{entity}}');		
		$subConcat = $this->_conn->getConcatSql(array('main_table.path', $this->_conn->quote('/%')));
		$subSelect = $this->_conn->select()
			->from(array('see' => ${{entities}}Table), null)
			->where('see.entity_id = main_table.entity_id')
			->orWhere('see.path LIKE ?', $subConcat);
		return $select;
	}
	/**
	 * Get real existing {{entityLabel}} ids by specified ids
	 * @access public
	 * @param array $ids
	 * @return array
	 * {{qwertyuiop}}
	 */
	public function getExisting{{Entity}}IdsBySpecifiedIds($ids){
		if (empty($ids)) {
			return array();
		}
		if (!is_array($ids)) {
			$ids = array($ids);
		}
		$select = $this->_conn->select()
			->from($this->_table, array('entity_id'))
			->where('entity_id IN (?)', $ids);
		return $this->_conn->fetchCol($select);
	}
