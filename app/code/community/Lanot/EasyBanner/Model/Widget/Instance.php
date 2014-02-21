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
* Magento core model rewrite
*
* @author Lanot
*/
class Lanot_EasyBanner_Model_Widget_Instance extends Mage_Widget_Model_Widget_Instance
{
    protected $_eventPrefix      = 'widget_instance';
    protected $_eventObject      = 'widget_instance';

    /**
     * Generate layout update xml
     *
     * @param string $blockReference
     * @param string $position
     * @return string
     */
    public function generateLayoutUpdateXml($blockReference, $templatePath = '')
    {
        $xml = parent::generateLayoutUpdateXml($blockReference, $templatePath);

        if ($xml && ($this->getSortOrder() == 0) && (strpos($xml, 'before=') === false)) {
            $xml = str_replace('<block ', '<block before="-" ', $xml);
        }

        return $xml;
    }
}
