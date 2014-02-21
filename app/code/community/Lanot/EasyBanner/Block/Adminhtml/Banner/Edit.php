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
 * Banner Banner admin edit form container
 *
 * @author Lanot
 */
class Lanot_EasyBanner_Block_Adminhtml_Banner_Edit
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
        $this->_controller = 'adminhtml_banner';

        parent::__construct();

        //check permissions
        if (Mage::helper('lanot_easybanner/admin')->isActionAllowed('manage_banner/save')) {
            $this->_updateButton('save', 'label', $this->_getHelper()->__('Save Banner Item'));
            $this->_addButton('saveandcontinue', array(
                'label'   => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ), -100);

            $this->_formScripts[] = "
            	function saveAndContinueEdit(){
            		editForm.submit($('edit_form').action+'back/edit/');
            	}

            	function changeBannerTypeId(value) {
            	    document.getElementById('form_edit_container_file_fieldset').addClassName('no-display');
            	    document.getElementById('form_edit_container_custom_fieldset').addClassName('no-display');
            	    document.getElementById('form_edit_container_' + value + '_fieldset').removeClassName('no-display');
            	}
            ";
        } else {
            $this->_removeButton('save');
        }

        if (Mage::helper('lanot_easybanner/admin')->isActionAllowed('manage_banner/delete')) {
            $this->_updateButton('delete', 'label', $this->_getHelper()->__('Delete Banner Item'));
        } else {
            $this->_removeButton('delete');
        }
    }

    public function getHeaderText()
    {
    	$header = $this->_getHelper()->__('New Banner Item');
        $model = $this->_getHelper()->getBannerItemInstance();
        
        if ($model->getId()) {
        	$title = $this->escapeHtml($model->getTitle());
            $header = $this->_getHelper()->__("Edit Banner Item '%s'", $title);
        }        
        return $header;
    }

    protected function _getHelper()
    {
        return Mage::helper('lanot_easybanner');
    }
}
