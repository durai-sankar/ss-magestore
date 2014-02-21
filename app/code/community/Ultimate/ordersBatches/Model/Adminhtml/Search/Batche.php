<?php 
/**
 * Ultimate_ordersBatches extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category   	Ultimate
 * @package		Ultimate_ordersBatches
 * @copyright  	Copyright (c) 2014
 * @license		http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Admin search model
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Model_Adminhtml_Search_Batche extends Varien_Object{
	/**
	 * Load search results
	 * @access public
	 * @return Ultimate_ordersBatches_Model_Adminhtml_Search_Batche
	 * @author Ultimate Module Creator
	 */
	public function load(){
		$arr = array();
		if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
			$this->setResults($arr);
			return $this;
		}
		$collection = Mage::getResourceModel('ordersbatches/batche_collection')
			->addFieldToFilter('batches_name_input', array('like' => $this->getQuery().'%'))
			->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
			->load();
		foreach ($collection->getItems() as $batche) {
			$arr[] = array(
				'id'=> 'batche/1/'.$batche->getId(),
				'type'  => Mage::helper('ordersbatches')->__('Batch'),
				'name'  => $batche->getBatchesNameInput(),
				'description'   => $batche->getBatchesNameInput(),
				'url' => Mage::helper('adminhtml')->getUrl('*/ordersbatches_batche/edit', array('id'=>$batche->getId())),
			);
		}
		$this->setResults($arr);
		return $this;
	}
}