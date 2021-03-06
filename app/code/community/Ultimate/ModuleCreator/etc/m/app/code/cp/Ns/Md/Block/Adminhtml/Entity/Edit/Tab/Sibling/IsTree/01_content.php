<?php
{{License}}
/**
 * {{entity}} - {{sibling}} relation edit block
 *
 * @category	{{Namespace}}
 * @package		{{Namespace}}_{{Module}}
 * {{qwertyuiop}}
 */
class {{Namespace}}_{{Module}}_Block_Adminhtml_{{Entity}}_Edit_Tab_{{Sibling}} extends {{Namespace}}_{{Module}}_Block_Adminhtml_{{Sibling}}_Tree{
	protected $_{{sibling}}Ids = null;
	protected $_selectedNodes = null;

	/**
	 * constructor
	 * Specify template to use
	 * @access public
	 * @return void
	 * {{qwertyuiop}}
	 */
	public function __construct(){
		parent::__construct();
		$this->setTemplate('{{namespace}}_{{module}}/{{entity}}/edit/tab/{{sibling}}.phtml');
	}
	/**
	 * Retrieve currently edited {{entityLabel}}
	 * @access public
	 * @return {{Namespace}}_{{Module}}_Model_{{Entity}}
	 * {{qwertyuiop}}
	 */
	public function get{{Entity}}(){
		return Mage::registry('current_{{entity}}');
	}
	/**
	 * Return array with {{siblingsLabel}} IDs which the {{entityLabel}} is linked to
	 * @access public
	 * @return array
	 * {{qwertyuiop}}
	 */
	public function get{{Sibling}}Ids(){
		if (is_null($this->_{{sibling}}Ids)){
			${{siblings}} = $this->get{{Entity}}()->getSelected{{Siblings}}();
				$ids = array();
				foreach (${{siblings}} as ${{sibling}}){
					$ids[] = ${{sibling}}->getId();
				}
				$this->_{{sibling}}Ids = $ids;
		}
			return $this->_{{sibling}}Ids;
	}
	/**
	 * Forms string out of get{{Sibling}}Ids()
	 * @access public
	 * @return string
	 * {{qwertyuiop}}
	 */
	public function getIdsString(){
		return implode(',', $this->get{{Sibling}}Ids());
	}
	/**
	 * Returns root node and sets 'checked' flag (if necessary)
	 * @access public
	 * @return Varien_Data_Tree_Node
	 * {{qwertyuiop}}
	 */
	public function getRootNode(){
		$root = $this->getRoot();
		if ($root && in_array($root->getId(), $this->get{{Sibling}}Ids())) {
			$root->setChecked(true);
		}
		return $root;
	}

	/**
	 * Returns root node
	 *
	 * @param {{Namespace}}_{{Module}}_Model_{{Sibling}}|null $parentNode{{Sibling}}
	 * @param int  $recursionLevel
	 * @return Varien_Data_Tree_Node
	 * {{qwertyuiop}}
	 */
	public function getRoot($parentNode{{Sibling}} = null, $recursionLevel = 3){
		if (!is_null($parentNode{{Sibling}}) && $parentNode{{Sibling}}->getId()) {
			return $this->getNode($parentNode{{Sibling}}, $recursionLevel);
		}
		$root = Mage::registry('{{sibling}}_root');
		if (is_null($root)) {
			$rootId = Mage::helper('{{module}}/{{sibling}}')->getRoot{{Sibling}}Id();
			$ids = $this->getSelected{{Sibling}}PathIds($rootId);
			$tree = Mage::getResourceSingleton('{{module}}/{{sibling}}_tree')
				->loadByIds($ids, false, false);
			if ($this->get{{Sibling}}()) {
				$tree->loadEnsuredNodes($this->get{{Sibling}}(), $tree->getNodeById($rootId));
			}	
			$tree->addCollectionData($this->get{{Sibling}}Collection());
			$root = $tree->getNodeById($rootId);
			Mage::register('{{sibling}}_root', $root);
		}
		return $root;
	}
	/**
	 * Returns array with configuration of current node
	 * @access public
	 * @param Varien_Data_Tree_Node $node
	 * @param int $level How deep is the node in the tree
	 * @return array
	 * {{qwertyuiop}}
	 */
	protected function _getNodeJson($node, $level = 1){
		$item = parent::_getNodeJson($node, $level);
		if ($this->_isParentSelected{{Sibling}}($node)) {
			$item['expanded'] = true;
		}
		if (in_array($node->getId(), $this->get{{Sibling}}Ids())) {
			$item['checked'] = true;
		}
		return $item;
	}
	/**
	 * Returns whether $node is a parent (not exactly direct) of a selected node
	 * @access public
	 * @param Varien_Data_Tree_Node $node
	 * @return bool
	 * {{qwertyuiop}}
	 */
	protected function _isParentSelected{{Sibling}}($node){
		$result = false;
		// Contains string with all {{siblingLabel}} IDs of children (not exactly direct) of the node
		$allChildren = $node->getAllChildren();
		if ($allChildren) {
			$selected{{Sibling}}Ids = $this->get{{Sibling}}Ids();
			$allChildrenArr = explode(',', $allChildren);
			for ($i = 0, $cnt = count($selected{{Sibling}}Ids); $i < $cnt; $i++) {
				$isSelf = $node->getId() == $selected{{Sibling}}Ids[$i];
				if (!$isSelf && in_array($selected{{Sibling}}Ids[$i], $allChildrenArr)) {
					$result = true;
					break;
				}
			}
		}
		return $result;
	}
	/**
	 * Returns array with nodes those are selected (contain current {{entityLabel}})
	 *
	 * @return array
	 */
	protected function _getSelectedNodes(){
		if ($this->_selectedNodes === null) {
			$this->_selectedNodes = array();
			$root = $this->getRoot();
			foreach ($this->get{{Sibling}}Ids() as ${{sibling}}Id) {
				if ($root) {
					$this->_selectedNodes[] = $root->getTree()->getNodeById(${{sibling}}Id);
				}
			}
		}
		return $this->_selectedNodes;
	}

	/**
	 * Returns JSON-encoded array of {{siblingsLabel}} children
	 * @access public
	 * @param int ${{sibling}}Id
	 * @return string
	 * {{qwertyuiop}}
	 */
	public function get{{Sibling}}ChildrenJson(${{sibling}}Id){
		${{sibling}} = Mage::getModel('{{module}}/{{sibling}}')->load(${{sibling}}Id);
		$node = $this->getRoot(${{sibling}}, 1)->getTree()->getNodeById(${{sibling}}Id);
		if (!$node || !$node->hasChildren()) {
			return '[]';
		}
		$children = array();
		foreach ($node->getChildren() as $child) {
			$children[] = $this->_getNodeJson($child);
		}
		return Mage::helper('core')->jsonEncode($children);
	}
	/**
	 * Returns URL for loading tree
	 * @access public
	 * @param null $expanded
	 * @return string
	 * {{qwertyuiop}}
	 */
	public function getLoadTreeUrl($expanded = null){
		return $this->getUrl('*/*/{{siblings}}Json', array('_current' => true));
	}

	/**
	 * Return distinct path ids of selected {{siblingsLabel}}
	 * @access public
	 * @param mixed $rootId Root {{siblingLabel}} Id for context
	 * @return array
	 * {{qwertyuiop}}
	 */
	public function getSelected{{Sibling}}PathIds($rootId = false){
		$ids = array();
		${{sibling}}Ids = $this->get{{Sibling}}Ids();
		if (empty(${{sibling}}Ids)) {
			return array();
		}
		$collection = Mage::getResourceModel('{{module}}/{{sibling}}_collection');
		if ($rootId) {
			$collection->addFieldToFilter('parent_id', $rootId);
		} 
		else {
			$collection->addFieldToFilter('entity_id', array('in'=>${{sibling}}Ids));
		}
	
		foreach ($collection as $item) {
			if ($rootId && !in_array($rootId, $item->getPathIds())) {
				continue;
			}
			foreach ($item->getPathIds() as $id) {
				if (!in_array($id, $ids)) {
					$ids[] = $id;
				}
			}
		}
		return $ids;
	}
}
