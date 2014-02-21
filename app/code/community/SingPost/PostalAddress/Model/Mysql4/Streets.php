<?php
 
class SingPost_PostalAddress_Model_Mysql4_Streets extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('singpost_postaladdress/streets', 'street_key');
    }
}
?>