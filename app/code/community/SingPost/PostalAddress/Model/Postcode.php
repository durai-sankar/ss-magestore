<?php
class SingPost_PostalAddress_Model_Postcode extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
       	$this->_init('singpost_postaladdress/postcode');
    }
	
	public function validPostalCodeOLD($postalcode='') 
	{
		$model = Mage::getModel('singpost_postaladdress/postcode')->load($postalcode);
		/*$dbPostalCode=$model->getPostalCode();

		if ($dbPostalCode== '') {
			$ret =  'empty';
		} else {
			$ret =  $dbPostalCode . '-' . $model->getBuildingKey();
		}*/
		
		$sql = "SELECT pc.postal_code, bn.building_name FROM postaladdress_postcode pc inner join postaladdress_building bn on pc.building_key = bn.building_key WHERE pc.postal_code='".$postalcode."'";
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		foreach ($connection->fetchAll($sql) as $arr_row) {
			echo $arr_row['postal_code'].'-'.$arr_row['building_name'];
		}

		return $ret;
	}

	public function validPostalCode($postalcode='0', $country = 'SG', $blockno='', $unitno='', $floorno='') {
		
		$postcode_info = Mage::getModel('singpost_postaladdress/postcode')->load($postalcode);
		

		if( (strlen($postcode_info->getPostalCode()) < 3 && $country != 'SG') || (strlen($postcode_info->getPostalCode()) < 5 && $country == 'SG') ) { 
		
			Mage::getSingleton('core/session')->setPostalCodeError('Invalid Postal Code');
			return FALSE; //no postal code record
		}
		
		Mage::getSingleton('core/session')->setPostalCodeError('');
		
		if ($country == 'SG' && $postalcode != $postcode_info->getPostalCode() && 
				$blockno == $postcode_info->getBlockno() &&
				$unitno == $postcode_info->getUnitno() &&
				$floorno == $postcode_info->getFloorno() ) {
		
				Mage::getSingleton('core/session')->setPostalCodeError('Invalid Postal Code');
				return FALSE;
				
		} elseif ($country == 'SG' && $postalcode == $postcode_info->getPostalCode() && 
				$blockno == $postcode_info->getBlockno() &&
				$unitno == $postcode_info->getUnitno() &&
				$floorno == $postcode_info->getFloorno() ) { 
		
				Mage::getSingleton('core/session')->setPostalCodeError('Service No required');
				return FALSE;
				
		} elseif ($require_walkup_number && empty($service_number)) { //check if service no. is required
			Mage::getSingleton('core/session')->setPostalCodeError('Service No required');
			return FALSE;
		}
		echo 'SUCCESS';
		return TRUE;
	}
	

	public function getStreetAndBuildnigName($postalcode='0', $country = 'SG') {
		
		$postcode_info = Mage::getModel('singpost_postaladdress/postcode')->load($postalcode);
		

		if( strlen($postcode_info->getPostalCode()) <= 4 && $country == 'SG' ) { 

			
			return "Invalid Postal Code"; //no postal code record
		}
		

		$building_number= (!empty($postcode_info->getBuildingNo())) ? $postcode_info->getBuildingNo() : '';
	
		$building_name= (!empty($postcode_info->getBuildingName())) ? $postcode_info->getBuildingName() : '';
		$street_name = (!empty($postcode_info->getStreetName())) ? $postcode_info->getStreetName() : ''; 
		$bno = trim($building_number);

		$StreetAndBuildnigName =  trim( trim($building_name) . ' ' . trim($street_name) );
		
		$unoreq = $this->verify_require_UF_number($postcode_info->getPostalCode(), 'floorno');
		$fnoreq = $this->verify_require_UF_number($postcode_info->getPostalCode(), 'unitno');
		
		return $StreetAndBuildnigName . '~' . $unoreq . '~' . $fnoreq . '~' . $bno ;
		
	}

   
    
	public function verify_require_UF_number($postal_code, $field) {
		$ret = 'TRUE';
		$infoPostalCode = Mage::getModel('singpost_postaladdress/postcode')->load($postal_code);
		
		$sql = 'SELECT postal_code FROM 6dpostcodes WHERE '.$field.' = "" AND postal_code = "'.$postal_code.'"';
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		foreach ($connection->fetchAll($sql) as $arr_row) {
			if ($arr_row['postal_code'] != '') {$ret = 'FALSE';}
		}
		
		return $ret;
	}			

	

	
	  
}

?>