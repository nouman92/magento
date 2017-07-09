<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/gpl-license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@refersion.com so we can send you a copy immediately.
 *
 * @category   TCS
 * @package    Tcs_Codshippingbooking
 * @copyright  Copyright (c) 2015 TCS (Pvt) Ltd.
 * @author	   TCS Developer <kamran.haider@tcs-e.com>
 * @license    http://opensource.org/licenses/gpl-license GNU General Public License
 */

/**
 * COD Shipping Booking Form
 *
 * @category   TCS
 * @package    Tcs_Codshippingbooking
 * @author     TCS Developer <kamran.haider@tcs-e.com>
 */
 
class Tcs_Codshippingbooking_Block_Adminhtml_Sales_Order_View_Preview extends Mage_Adminhtml_Block_Widget_Form 
{

	/**
	 * Pass from data to template
	 * 
	 * @param  null
	 * @return array
	 */
	public function getFormData(){
		
		$totalQty = 0;
		$data['consigneeName'] = '';
		$data['consigneeAddress'] = ''; 
		$data['consigneeMobNo'] = ''; 
		$data['consigneeEmail'] = ''; 
		$data['originCityName'] = '';
		$data['destinationCityName'] = '';
		$data['pieces'] = '';
		$data['weight'] = 0; 
		$data['codAmount'] = '';
		$data['custRefNo'] = ''; 
		$data['productDetails'] = '';
		$data['fragile'] = 'no';
		$data['service'] = 'O';
		$data['remarks'] = ''; 
		$data['insuranceValue'] = 0;
		$data['servicesLabel'] = '';
		
		$bookingData = Mage::registry('bookingData');

		/*if order id is set for use*/
		if($bookingData){
			$data['order_id'] 						= $bookingData['order_id'];
			$data['costCenterCode'] 			= Mage::getStoreConfig('codshippingbooking/codshippingbooking_settings/codshippingbooking_cost_center_id');
			$data['consigneeName'] 				= $bookingData['consigneeName'];
      $data['consigneeAddress'] 		= $bookingData['consigneeAddress'];
      $data['consigneeMobNo'] 			= $bookingData['consigneeMobNo']; 
      $data['consigneeEmail'] 			= $bookingData['consigneeEmail']; 
      $data['originCityName'] 			= $bookingData['originCityName'];
      $data['destinationCityName']	= $bookingData['destinationCityName'];
      $data['pieces'] 							= $bookingData['pieces'];
			$data['weight'] 							= $bookingData['weight'];
      $data['codAmount'] 						= $bookingData['codAmount'];
      $data['custRefNo'] 						= $bookingData['custRefNo']; 
      $data['productDetails'] 			= $bookingData['productDetails'];
      $data['fragile'] 							= $bookingData['fragile'];
      $data['services'] 						= $bookingData['services'];
      $data['remarks'] 							= $bookingData['remarks']; 
      $data['insuranceValue'] 			= $bookingData['insuranceValue'];
			
			switch ($bookingData['services']) {
					case 'O':
							$data['servicesLabel'] = "Overnight";
							break;
					case 'D':
							$data['servicesLabel'] = "2nd Day";
							break;
					case 'S':
							$data['servicesLabel'] = "Same day";
							break;
			}
			
		}

		return $data;
	}
	
	/**
	 * Pass from action template
	 * 
	 * @param  null
	 * @return string
	 */
	public function getFormAction(){
		return $this->getUrl('admin_codshippingbooking/adminhtml_sales_order_index/addPost');
	}
	
	/**
	 * Get destination cities from the TCS APi
	 * 
	 * @param  null
	 * @return array
	 */
	public function getCities(){
		$client = new SoapClient('http://webapp.tcscourier.com/CODAPI/Service1.asmx?WSDL');
		$cities = $client->GetAllCities();
		
		$xml = $cities->GetAllCitiesResult->any;
		// Remove namespaces
    $xml    = str_replace(array("diffgr:","msdata:"),'', $xml);
    // Wrap into root element to make it standard XML
    $xml    = "<package>".$xml."</package>";
    // Parse with SimpleXML - probably there're much better ways
    $data   = simplexml_load_string($xml);
		$cities  = $data->diffgram->NewDataSet;

		return $cities;
	}
	
	/**
	 * Get origin cities from the TCS APi
	 * 
	 * @param  null
	 * @return array
	 */
	public function getAllOriginCities(){
		$client = new SoapClient('http://webapp.tcscourier.com/CODAPI/Service1.asmx?WSDL');
		$cities = $client->GetAllOriginCities();
		
		$xml = $cities->GetAllOriginCitiesResult->any;
		// Remove namespaces
    $xml    = str_replace(array("diffgr:","msdata:"),'', $xml);
    // Wrap into root element to make it standard XML
    $xml    = "<package>".$xml."</package>";
    // Parse with SimpleXML - probably there're much better ways
    $data   = simplexml_load_string($xml);
		$cities  = $data->diffgram->NewDataSet;

		return $cities;
	
	}
}
