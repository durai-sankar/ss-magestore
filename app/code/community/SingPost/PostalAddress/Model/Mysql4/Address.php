<?php
 
class SingPost_PostalAddress_Model_Mysql4_Address extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('singpost_postaladdress/address', 'user_id');
    }
}
?>