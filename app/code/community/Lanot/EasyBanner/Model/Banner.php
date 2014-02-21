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

class Lanot_EasyBanner_Model_Banner
    extends Mage_Core_Model_Abstract
    implements Lanot_Core_Model_Item_Interface
{
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;

    const TYPE_ID_FILE = 'file';
    const TYPE_ID_CUSTOM = 'custom';

    /**
     * @var array
     */
    protected $_extensions = null;

    /**
     * @var string
     */
    protected $_selectedCategories = null;

    protected function _construct()
    {
        $this->_init('lanot_easybanner/banner');
    }

    /**
     * @return Lanot_EasyBanner_Model_Banner
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        $this->_prepareTypeId();
        return $this;
    }

    /**
     * @return Lanot_EasyBanner_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_easybanner');
    }

    /**
     * @return Lanot_EasyBanner_Model_Banner
     */
    protected function _prepareTypeId()
    {
        if (!$this->getData('type_id')) {
            if ($this->getType() == self::TYPE_ID_CUSTOM) {
                $this->setData('type_id', self::TYPE_ID_CUSTOM);
            } else {
                $this->setData('type_id', self::TYPE_ID_FILE);
            }
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getTypeId()
    {
        if (!$this->hasData('type_id')) {
            $this->setData('type_id', self::TYPE_ID_FILE);
        }
        return $this->getData('type_id');
    }

    /**
     * @return array
     */
    public function getAvailableStatuses()
    {
        return array(
            self::STATUS_ENABLED  => $this->_getHelper()->__('Enabled'),
            self::STATUS_DISABLED => $this->_getHelper()->__('Disabled'),
        );
    }

    /**
     * @return int
     */
    public function getStatusDisabled()
    {
        return self::STATUS_DISABLED;
    }

    /**
     * @return int
     */
    public function getStatusEnabled()
    {
        return self::STATUS_ENABLED;
    }

    /**
     * @return array
     */
    public function getAvailableTypes()
    {
        return array(
            self::TYPE_ID_FILE => $this->_getHelper()->__('File (Image, Flash)'),
            self::TYPE_ID_CUSTOM => $this->_getHelper()->__('Custom Code (Html, Embed Code)'),
        );
    }

    /**
     * @return array
     */
    public function getAllowedExtensionsAndTypes()
    {
        $this->prepareExtensions();
        return $this->_extensions;
    }

    /**
     * @return array
     */
    public function getAllowedExtensions()
    {
        $this->prepareExtensions();
        return array_keys($this->_extensions);
    }

    /**
     * @return array
     */
    public function prepareExtensions()
    {
        if ($this->_extensions !== null) {
            return $this->_extensions;
        }

        $this->_extensions = array();
        $node = Mage::getConfig()->getNode('default/lanot_easybanner/extensions/allowed');
        if ($node) {
            foreach($node->asCanonicalArray() as $type => $exts) {
                foreach($exts as $ext => $val) {
                    $this->_extensions[$ext] = $type;
                }
            }
        }
        return $this->_extensions;
    }

    /**
     * Filesystem directory path of banners
     * relatively to media folder
     *
     * @return string
     */
    public function getMediaPath()
    {
        return 'lanot/easybanner';
    }

    /**
     * Filesystem full directory path of banners
     * relatively to media folder
     *
     * @return string
     */
    public function getFullMediaPath()
    {
        return Mage::getBaseDir('media') . '/' . $this->getMediaPath();
    }

    /**
     * @return string
     */
    public function getPathUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $this->getMediaPath() . $this->getPath();
    }
        
    /**
     * @return array
     */
    public function getSelectedCategories()
    {
        if (null === $this->_selectedCategories) {
            $this->_selectedCategories = $this->getResource()->getSelectedCategories($this);

        }
        return $this->_selectedCategories;
    }
    
    /**
     * @return Lanot_EasyBanner_Model_Banner
     */
    protected function _beforeSave()
    {
    	parent::_beforeSave();

        if ($this->isObjectNew()) {
            $this->setData('created_at', Varien_Date::now());
        }
        $this->setData('updated_at', Varien_Date::now());


        if ($this->getTypeId() == self::TYPE_ID_CUSTOM) {
            $this->_prepareCodeInfo();
        } else {
            $this->_prepareFileInfo();
        }

        $this->_prepareCategories();

    	return $this;
    }

    /**
     * @param $key
     * @return null
     */
    protected function _uploadFile($key)
    {
        $file = null;
        if(empty($_FILES[$key]['name'])) {
            return null;
        }
        try {
            //prepare uploader to upload
            $uploader = new Varien_File_Uploader($key);
            $uploader->setAllowedExtensions($this->getAllowedExtensions());
            $uploader->setAllowCreateFolders(true);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            // save the image to path
            $result = $uploader->save($this->getFullMediaPath());
            if (isset($result['file'])) {
                $file = $result['file'];
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::throwException($e);
        }
        return $file;
    }

    /**
     * @param $file
     * @return bool
     */
    protected function _deleteFile($file)
    {
        $filename = $this->getFullMediaPath() . $file;
        $io = new Varien_Io_File();
        if ($io->fileExists($filename)) {
            return $io->rm($filename);
        }
        return false;
    }

    /**
     * @param $path
     * @return mixed
     */
    protected function _getExtension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * @return Lanot_EasyBanner_Model_Banner
     */
    protected function _prepareFileInfo()
    {
        $this->setCode(null);

        //upload file
        $fileData = $this->getBannerFile();
        $isPathDeleted = (is_array($fileData) && !empty($fileData['delete']));
        if ($isPathDeleted) {
            $this->_deleteFile($this->getPath());
            $this->setPath(null);
            $this->setType(null);
        } elseif (!empty($_FILES['banner_file']['name'])){
            $oldPath = $this->getPath();

            $path = $this->_uploadFile('banner_file');
            $this->setPath($path);

            $ext = $this->_getExtension($path);
            $extByType = $this->getAllowedExtensionsAndTypes();
            $type = null;
            if ($ext && !empty($extByType[$ext])) {
                $type = $extByType[$ext];
            }
            $this->setType($type);

            if (null !== $oldPath) {
                $this->_deleteFile($oldPath);
            }
        } elseif($this->getType() == self::TYPE_ID_CUSTOM) {
            $this->setType(null);
        }

        return $this;
    }

    /**
     * @return Lanot_EasyBanner_Model_Banner
     */
    protected function _prepareCodeInfo()
    {
        $this->setPath(null);
        $this->setUrl(null);
        $this->setWidth(null);
        $this->setHeight(null);
        $this->setType(self::TYPE_ID_CUSTOM);

        return $this;
    }


    /**
     * @return Lanot_EasyBanner_Model_Banner
     */
    protected function _prepareCategories()
    {
        $categories = $this->getCategories();
        if (is_string($categories) && !empty($categories)) {
            $this->setCategories(Mage::helper('adminhtml/js')->decodeGridSerializedInput($categories));
        } elseif (null !== $categories) {
            $this->setCategories(array());
        }
        return $this;
    }
}
