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
 * Batch admin controller
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Adminhtml_ordersBatches_BatcheController extends Ultimate_ordersBatches_Controller_Adminhtml_ordersBatches{
	/**
	 * init the batche
	 * @access protected
	 * @return Ultimate_ordersBatches_Model_Batche
	 */
	protected function _initBatche(){
		$batcheId  = (int) $this->getRequest()->getParam('id');
		$batche	= Mage::getModel('ordersbatches/batche');
		if ($batcheId) {
			$batche->load($batcheId);
		}
		Mage::register('current_batche', $batche);
		return $batche;
	}
 	/**
	 * default action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function indexAction() {
		$this->loadLayout();
		$this->_title(Mage::helper('ordersbatches')->__('ordersBatches'))
			 ->_title(Mage::helper('ordersbatches')->__('Batches'));
		$this->renderLayout();
	}
	/**
	 * grid action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function gridAction() {
		$this->loadLayout()->renderLayout();
	}
	/**
	 * edit batch - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function editAction() {
		$batcheId	= $this->getRequest()->getParam('id');
		$batche  	= $this->_initBatche();
		if ($batcheId && !$batche->getId()) {
			$this->_getSession()->addError(Mage::helper('ordersbatches')->__('This batch no longer exists.'));
			$this->_redirect('*/*/');
			return;
		}
		$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		if (!empty($data)) {
			$batche->setData($data);
		}
		Mage::register('batche_data', $batche);
		$this->loadLayout();
		$this->_title(Mage::helper('ordersbatches')->__('ordersBatches'))
			 ->_title(Mage::helper('ordersbatches')->__('Batches'));
		if ($batche->getId()){
			$this->_title($batche->getBatchesNameInput());
		}
		else{
			$this->_title(Mage::helper('ordersbatches')->__('Add batch'));
		}
		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) { 
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true); 
		}
		$this->renderLayout();
	}
	/**
	 * new batch action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function newAction() {
		$this->_forward('edit');
	}
	/**
	 * save batch - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function saveAction() {
		if ($data = $this->getRequest()->getPost('batche')) {
			try {
				$batche = $this->_initBatche();
				$batche->addData($data);
				$products = $this->getRequest()->getPost('products', -1);
				if ($products != -1) {
					$batche->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
				}
				$batche->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ordersbatches')->__('Batch was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $batche->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
			} 
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
			catch (Exception $e) {
				Mage::logException($e);
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ordersbatches')->__('There was a problem saving the batch.'));
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ordersbatches')->__('Unable to find batch to save.'));
		$this->_redirect('*/*/');
	}
	/**
	 * delete batch - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0) {
			try {
				$batche = Mage::getModel('ordersbatches/batche');
				$batche->setId($this->getRequest()->getParam('id'))->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ordersbatches')->__('Batch was successfully deleted.'));
				$this->_redirect('*/*/');
				return; 
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ordersbatches')->__('There was an error deleteing batch.'));
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				Mage::logException($e);
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ordersbatches')->__('Could not find batch to delete.'));
		$this->_redirect('*/*/');
	}
	/**
	 * mass delete batch - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function massDeleteAction() {
		$batcheIds = $this->getRequest()->getParam('batche');
		if(!is_array($batcheIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ordersbatches')->__('Please select batches to delete.'));
		}
		else {
			try {
				foreach ($batcheIds as $batcheId) {
					$batche = Mage::getModel('ordersbatches/batche');
					$batche->setId($batcheId)->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ordersbatches')->__('Total of %d batches were successfully deleted.', count($batcheIds)));
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ordersbatches')->__('There was an error deleteing batches.'));
				Mage::logException($e);
			}
		}
		$this->_redirect('*/*/index');
	}
	/**
	 * mass status change - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function massStatusAction(){
		$batcheIds = $this->getRequest()->getParam('batche');
		if(!is_array($batcheIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ordersbatches')->__('Please select batches.'));
		} 
		else {
			try {
				foreach ($batcheIds as $batcheId) {
				$batche = Mage::getSingleton('ordersbatches/batche')->load($batcheId)
							->setStatus($this->getRequest()->getParam('status'))
							->setIsMassupdate(true)
							->save();
				}
				$this->_getSession()->addSuccess($this->__('Total of %d batches were successfully updated.', count($batcheIds)));
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ordersbatches')->__('There was an error updating batches.'));
				Mage::logException($e);
			}
		}
		$this->_redirect('*/*/index');
	}
	/**
	 * get grid of products action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function productsAction(){
		$this->_initBatche();
		$this->loadLayout();
		$this->getLayout()->getBlock('batche.edit.tab.product')
			->setBatcheProducts($this->getRequest()->getPost('batche_products', null));
		$this->renderLayout();
	}
	/**
	 * get grid of products action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function productsgridAction(){
		$this->_initBatche();
		$this->loadLayout();
		$this->getLayout()->getBlock('batche.edit.tab.product')
			->setBatcheProducts($this->getRequest()->getPost('batche_products', null));
		$this->renderLayout();
	}
	/**
	 * export as csv - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function exportCsvAction(){
		$fileName   = 'batche.csv';
		$content	= $this->getLayout()->createBlock('ordersbatches/adminhtml_batche_grid')->getCsv();
		$this->_prepareDownloadResponse($fileName, $content);
	}
	/**
	 * export as MsExcel - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function exportExcelAction(){
		$fileName   = 'batche.xls';
		$content	= $this->getLayout()->createBlock('ordersbatches/adminhtml_batche_grid')->getExcelFile();
		$this->_prepareDownloadResponse($fileName, $content);
	}
	/**
	 * export as xml - action
	 * @access public
	 * @return void
	 * @author Ultimate Module Creator
	 */
	public function exportXmlAction(){
		$fileName   = 'batche.xml';
		$content	= $this->getLayout()->createBlock('ordersbatches/adminhtml_batche_grid')->getXml();
		$this->_prepareDownloadResponse($fileName, $content);
	}
}