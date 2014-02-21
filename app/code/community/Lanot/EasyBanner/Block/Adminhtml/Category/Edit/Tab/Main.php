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
 * Category admin edit form main tab block
 *
 * @author Lanot
 */
class Lanot_EasyBanner_Block_Adminhtml_Category_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare form elements for tab
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
    	/* @var $model Lanot_EasyBanner_Model_Category */
        $model = $this->_getHelper()->getCategoryItemInstance();

        /**
         * Checking if user have permissions to save information
         */
        if (Mage::helper('lanot_easybanner/admin')->isActionAllowed('manage_category/save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('category_main_');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => $this->_getHelper()->__('Category Item Info')
        ));

        if ($model->getId()) {
            $fieldset->addField('category_id', 'hidden', array(
                'name' => 'id',
            ));
        }

        //Add main elements to the category
        $fieldset->addField('title', 'text', array(
            'name'     => 'title',
            'label'    => $this->_getHelper()->__('Title'),
            'title'    => $this->_getHelper()->__('Title'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('description', 'textarea', array(
        	'name'     => 'description',
        	'label'    => $this->_getHelper()->__('Description'),
        	'title'    => $this->_getHelper()->__('Description'),
        	'required' => false,
        	'disabled' => $isElementDisabled,
        	'style'    => 'height: 100px',	
        ));
        
        $fieldset->addField('is_active', 'select', array(
        	'name'     => 'is_active',
        	'label'    => $this->_getHelper()->__('Is Active'),
        	'title'    => $this->_getHelper()->__('Is Active'),
        	'required' => true,
        	'disabled' => $isElementDisabled,
        	'options'  => $model->getAvailableStatuses(),
        ));

        /*
        //Add layout updates elements to the category
        $lBlock = $this->getLayout()->createBlock('lanot_easybanner/adminhtml_widget_instance_edit_tab_main_layout');
        $fieldset = $form->addFieldset('layout_updates_fieldset', array(
            'legend' => $this->_getHelper()->__('Layout Updates')
        ));

        $fieldset->addField('layout_updates', 'note', array());
        $form->getElement('layout_updates_fieldset')->setRenderer($lBlock);
        */

        $form->setValues($model->getData());
        
        Mage::dispatchEvent('adminhtml_easybanner_category_edit_tab_main_prepare_form', array('form' => $form));

        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return $this->_getHelper()->__('Category Info');
    }

    public function getTabTitle()
    {
        return $this->_getHelper()->__('Category Info');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    protected function _getHelper()
    {
        return Mage::helper('lanot_easybanner');
    }
}
