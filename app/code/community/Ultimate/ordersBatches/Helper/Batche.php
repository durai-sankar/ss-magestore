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
 * Batch helper
 *
 * @category	Ultimate
 * @package		Ultimate_ordersBatches
 * @author Ultimate Module Creator
 */
class Ultimate_ordersBatches_Helper_Batche extends Mage_Core_Helper_Abstract{
	/**
	 * check if breadcrumbs can be used
	 * @access public
	 * @return bool
	 * @author Ultimate Module Creator
	 */
	public function getUseBreadcrumbs(){
		return Mage::getStoreConfigFlag('ordersbatches/batche/breadcrumbs');
	}
}