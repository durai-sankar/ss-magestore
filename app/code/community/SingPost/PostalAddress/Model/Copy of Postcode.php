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

	public function validPostalCode($postalcode='0', $country = 'SG') {
		
		$postcode_info = Mage::getModel('singpost_postaladdress/postcode')->load($postalcode);
		

		//if( (strlen($postcode_info->getPostalCode()) < 3 && $country != 'SG') || (strlen($postcode_info->getPostalCode()) <= 5 && $country == 'SG') ) { 
		if( strlen($postcode_info->getPostalCode()) <= 4 && $country == 'SG' ) { 
			Mage::getSingleton('core/session')->setPostalCodeError('Invalid Postal Code');
			return FALSE; //no postal code record
		}
	
		$require_unit_number = $this->verify_require_unit_number($postcode_info->getPostalCode());
        $require_service_number = $this->verify_require_service_number($postcode_info->getPostalCode());
        $require_walkup_number = $this->verify_require_walkup_number($postcode_info->getPostalCode());
		
		$apt_level = Mage::getSingleton('core/session')->getAptLevel(); 
		$apt_number = Mage::getSingleton('core/session')->getAptNumber();
		$service_number = Mage::getSingleton('core/session')->getServiceNumber();
		
		Mage::getSingleton('core/session')->setPostalCodeError('');
		
		//check if addtional required address segment is provided
		if($require_unit_number && ($apt_level == 'Required' || $apt_number == 'Required')) { //check if unit no. is required
			Mage::getSingleton('core/session')->setPostalCodeError('Unit Number Required');
			return FALSE;
		} elseif ($require_service_number && empty($service_number)) { //check if service no. is required
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
		

		if( strlen($postcode_info->getPostalCode()) <= 5 && $country == 'SG' ) { 

			
			return "Invalid Postal Code"; //no postal code record
		}
		

		$building_number= (!empty($postcode_info->getBuildingNumber())) ? $postcode_info->getBuildingNumber() : ''; //building no.
		
		$street_key = (!empty($postcode_info->getStreetKey())) ? $postcode_info->getStreetKey() : ''; //street key for db
		$street_name = (!empty($postcode_info->getStreetKey())) ? $this->get_street_name($postcode_info->getStreetKey()) : ''; //street name for GUI
		
		$building_key = (!empty($postcode_info->getBuildingKey()) && ($postcode_info->getPostalCode() != '919191')) ? $postcode_info->getBuildingKey() : ''; //building key for db
		$building_name = (!empty($postcode_info->getBuildingKey()) && ($postcode_info->getPostalCode() != '919191')) ? $this->get_building_name($postcode_info->getBuildingKey()) : ''; //building name for GUI
	
		$StreetAndBuildnigName =   trim($building_name) . ' ' . trim($street_name);
		
		$unoreq = $this->verify_require_unit_number($postcode_info->getPostalCode());
		$snoreq = $this->verify_require_service_number($postcode_info->getPostalCode());
		$wnoreq = $this->verify_require_walkup_number($postcode_info->getPostalCode());
		return $StreetAndBuildnigName . '~' . trim($building_number) . '~' . $unoreq . '~' . $snoreq . '~' . $wnoreq;
		
	}
	
	
	private function get_street_name($street_key) { 
		$infoPostalCode = Mage::getModel('singpost_postaladdress/streets')->load($street_key);
		return $infoPostalCode->street_name;
	}
	
	private function get_building_name($building_key) { 
		$infoPostalCode = Mage::getModel('singpost_postaladdress/building')->load($building_key);
		return $result->building_name;
	}
   
    
	public function verify_require_unit_number($postal_code) {
		$infoPostalCode = Mage::getModel('singpost_postaladdress/postcode')->load($postal_code);
		$adtype = $infoPostalCode->getAddressType();		


			if(
				//$result->address_type == 'S' || //sometimes it's required.
				$adtype =='U' ||
				$adtype == 'P' ||
				$adtype == 'W' ||
				$adtype =='B'
			) {
				Mage::getSingleton('core/session')->setAptLevel('');
				Mage::getSingleton('core/session')->setAptNumber('');
				return FALSE; //unit no. is not needed
			} else {				
				Mage::getSingleton('core/session')->setAptLevel('Required');
				Mage::getSingleton('core/session')->setAptNumber('Required');			
				return  TRUE; //unit no. is needed
			} 

		

		
    }
    
    private function verify_require_service_number($postal_code) {
		$infoPostalCode = Mage::getModel('singpost_postaladdress/postcode')->load($postal_code);
		$adtype = $infoPostalCode->getAddressType();	
		
		
			switch ($adtype) {

				case 'P':
					
					Mage::getSingleton('core/session')->setServiceNumber('Service No Required.');
					return ($this->info->postal_code != '919191') ? $this->lang->line('postal_address_po_box') : $this->lang->line('postal_address_vbox');//service no. is needed
					break;
				case 'W':
					
					Mage::getSingleton('core/session')->setServiceNumber('Service No Required.');
					return $this->lang->line('postal_address_window_delivery'); //service no. is needed
					break;
				case 'B':
					
					Mage::getSingleton('core/session')->setServiceNumber('Service No Required.');
					return $this->lang->line('postal_address_locked_bag'); //service no. is needed
					break;
				default:
					//$this->info->service_number = '';
					Mage::getSingleton('core/session')->setServiceNumber('');
					return FALSE; //service no. is NOT needed
			}
		
		
    }
	
    private function verify_require_walkup_number($postal_code) {
		$infoPostalCode = Mage::getModel('singpost_postaladdress/postcode')->load($postal_code);
		$adtype = $infoPostalCode->getAddressType();	
		
		
			switch ($adtype) {
				case 'U':
					
					Mage::getSingleton('core/session')->setServiceNumber('Service No Required.');
					return $this->lang->line('postal_address_house_number'); //service no. is needed
					break;
				default:
					$this->info->walkup_number = '';
					Mage::getSingleton('core/session')->setServiceNumber('');
					return FALSE; //service no. is NOT needed
			}
		
		
    }
	
	  
}

?>