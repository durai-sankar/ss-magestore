<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Lanot
 * @package     Lanot_EasyBanner
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
/**
 * Banner Category admin edit form container
 *
 * @author Lanot
 */
class Lanot_EasyBanner_Block_Adminhtml_Category_Edit
	extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize edit form container
     *
     */
    public function __construct()
    {
        $this->_objectId   = 'id';        
        $this->_blockGroup = 'lanot_easybanner';
        $this->_controller = 'adminhtml_category'; 

        parent::__construct();

        //check permissions
        if (Mage::helper('lanot_easybanner/admin')->isActionAllowed('manage_category/save')) {
            $this->_updateButton('save', 'label', $this->_getHelper()->__('Save Category Item'));
            $this->_addButton('saveandcontinue', array(
                'label'   => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ), -100);
            
            
            $this->_formScripts[] = "            
            	function saveAndContinueEdit(){
            		editForm.submit($('edit_form').action+'back/edit/');
            	}
            ";                        
        } else {
            $this->_removeButton('save');
        }

        if (Mage::helper('lanot_easybanner/admin')->isActionAllowed('manage_category/delete')) {
            $this->_updateButton('delete', 'label', $this->_getHelper()->__('Delete Category Item'));
        } else {
            $this->_removeButton('delete');
        }
    }

    public function getHeaderText()
    {
    	$header = $this->_getHelper()->__('New Category Item');
        $model = $this->_getHelper()->getCategoryItemInstance();
        
        if ($model->getId()) {
        	$title = $this->escapeHtml($model->getTitle());
            $header = $this->_getHelper()->__("Edit Category Item '%s'", $title);
        }        
        return $header;
    }

    protected function _getHelper()
    {
        return Mage::helper('lanot_easybanner');
    }
}
