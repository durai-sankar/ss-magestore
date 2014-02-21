<?php
class SingPost_PostalAddress_Model_Streets extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
       	$this->_init('singpost_postaladdress/streets');
    }

}

?>