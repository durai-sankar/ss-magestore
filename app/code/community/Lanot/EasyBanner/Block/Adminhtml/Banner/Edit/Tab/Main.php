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
 * Banner admin edit form main tab block
 *
 * @author Lanot
 */
class Lanot_EasyBanner_Block_Adminhtml_Banner_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_idPrefix = 'banner_main_';

    /**
     * @return Lanot_EasyBanner_Model_Banner
     */
    protected function _getItemModel()
    {
        return $this->_getHelper()->getBannerItemInstance();
    }

    /**
     * Prepare form elements for tab
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = $this->_getItemModel();
        $typeId = $model->getTypeId();

        $extensions = $model->getAllowedExtensions();
        $bannerHtml = $bannerHtmlFile = $bannerHtmlCustom = '';

        /**
         * Checking if user have permissions to save information
         */
        if ($this->_getAclHelper()->isActionAllowed($this->_aclSection . '/save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }


        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('banner_main_');
        $form->getFieldsetRenderer()->setTemplate('lanot/easybanner/widget/form/renderer/fieldset.phtml');

        //prepare fieldsets
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => $this->_getHelper()->__('Banner Item Info')
        ));

        $fileFieldset = $form->addFieldset('file_fieldset', array(
            'legend' => $this->_getHelper()->__('Banner File Info'),
            'fieldset_container_id' => 'form_edit_container_file_fieldset',
            'fieldset_container_class' => ($typeId != Lanot_EasyBanner_Model_Banner::TYPE_ID_FILE) ? 'no-display' : '',
        ));

        $customFieldset = $form->addFieldset('custom_fieldset', array(
            'legend' => $this->_getHelper()->__('Banner Custom Code Info'),
            'fieldset_container_id' => 'form_edit_container_custom_fieldset',
            'fieldset_container_class' => ($typeId != Lanot_EasyBanner_Model_Banner::TYPE_ID_CUSTOM) ? 'no-display' : '',
        ));

        //apply additional renderers
        $this->_addElementTypes($fieldset);
        $this->_addElementTypes($fileFieldset);
        $this->_addElementTypes($customFieldset);


        if ($model->getId()) {
            $fieldset->addField('banner_id', 'hidden', array(
                'name' => 'id',
            ));
        }

        //prepare banner HTML
        if ($model->getType()) {
            $bannerHtml = $this->_getHelper()
                ->getBannerRenderer($model->getType())
                ->disableClick()
                ->setBanner($model)
                ->render();
            if (!empty($bannerHtml)) {
                $style = 'width: 100%; overflow: hidden; margin: 15px 0px 0px 0px;';
                $bannerHtml = "<div style='{$style}'>{$bannerHtml}</div>";
            }
        }

        if ($typeId === Lanot_EasyBanner_Model_Banner::TYPE_ID_CUSTOM) {
            $bannerHtmlCustom = $bannerHtml;
        } else {
            $bannerHtmlFile = $bannerHtml;
        }

        //general information
        $fieldset->addField('is_active', 'select', array(
            'name'     => 'is_active',
            'label'    => $this->_getHelper()->__('Is Active'),
            'required' => true,
            'options'  => $model->getAvailableStatuses(),
            'style'    => 'width: 200px',
            'disabled' => $isElementDisabled,
        ));

        $fieldset->addField('type_id', 'select', array(
            'name'     => 'type_id',
            'label'    => $this->_getHelper()->__('Banner Type'),
            'required' => true,
            'options'  => $model->getAvailableTypes(),
            'style'    => 'width: 200px',
            'disabled' => $isElementDisabled,
            'value'    => $typeId,
            'onchange' => 'changeBannerTypeId(this.value)'
        ));

        $fieldset->addField('title', 'text', array(
            'name'     => 'title',
            'label'    => $this->_getHelper()->__('Title'),
            'required' => true,
            'disabled' => $isElementDisabled,
        ));

        $fieldset->addField('description', 'textarea', array(
            'name'     => 'description',
            'label'    => $this->_getHelper()->__('Description'),
            'title'    => $this->_getHelper()->__('Description'),
            'disabled' => $isElementDisabled,
            'style'    => 'height: 60px',
        ));

        //file information
        $fileFieldset->addField('url', 'text', array(
            'name'     => 'url',
            'label'    => $this->_getHelper()->__('Url'),
            'disabled' => $isElementDisabled,
        ));

        $fileFieldset->addField('width', 'text', array(
            'name'     => 'width',
            'label'    => $this->_getHelper()->__('Banner Width (px)'),
            'disabled' => $isElementDisabled,
            'style'    => 'width: 50px',
        ));

        $fileFieldset->addField('height', 'text', array(
            'name'     => 'height',
            'label'    => $this->_getHelper()->__('Banner Height (px)'),
            'disabled' => $isElementDisabled,
            'style'    => 'width: 50px',
        ));

        $fileFieldset->addField('path', 'banner', array(
            'name'     => 'banner_file',
            'label'    => $this->_getHelper()->__('File'),
            'comment'  => $this->_getHelper()->__('Allowed extensions: %s', implode(',', $extensions)),
            'disabled' => $isElementDisabled,
            'style'    => 'width: 200px;',
        ))->setBannerHtml($bannerHtmlFile);

        //custom code information
        $customFieldset->addField('code', 'textarea', array(
            'name'     => 'code',
            'label'    => $this->_getHelper()->__('Custom Code'),
            'disabled' => $isElementDisabled,
        ))->setAfterElementHtml($bannerHtmlCustom);


        Mage::dispatchEvent('adminhtml_easybanner_banner_edit_tab_main_prepare_form', array('form' => $form));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return $this->_getHelper()->__('Banner Info');
    }

    public function getTabTitle()
    {
        return $this->_getHelper()->__('Banner Info');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    /**
     * Retrieve predefined additional element types
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        return array(
            'banner' => Mage::getConfig()->getBlockClassName('lanot_easybanner/adminhtml_renderer_banner')
        );
    }

    /**
     * @return Lanot_EasyBanner_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_easybanner');
    }

    /**
     * @return Lanot_EasyBanner_Helper_Admin
     */
    protected function _getAclHelper()
    {
        return Mage::helper('lanot_easybanner/admin');
    }
}
