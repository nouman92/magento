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
 
class Tcs_Codshippingbooking_Block_Adminhtml_Sales_Order_View_Form extends Mage_Adminhtml_Block_Widget_Form 
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
		$orderId = Mage::registry('bookingOrderId');
		
		/*if order id is set for use*/
		if($orderId){
			
			$order = Mage::getModel('sales/order')->load($orderId);
			$storeId = $order->getStoreId();
			
			$address = $order->getShippingAddress()->getData();
			$productsDetails = '';
			
			foreach($order->getAllItems() as $item){
        $productsDetails .= $item->getName().', '.$item->getSku().', '.$item->getQtyOrdered().', '.strip_tags(Mage::helper('core')->formatPrice($item->getPrice())).' &#13;&#10;';
				$totalQty += $item->getQtyOrdered();
    	}
			
			$data['order_id'] = $order->getId();
			$data['username'] = Mage::getStoreConfig('codshippingbooking/codshippingbooking_settings/codshippingbooking_user_key');
      $data['password'] = Mage::getStoreConfig('codshippingbooking/codshippingbooking_settings/codshippingbooking_user_pass');
			$data['costCenterCode'] = Mage::getStoreConfig('codshippingbooking/codshippingbooking_settings/codshippingbooking_cost_center_id');
			$data['costCenter'] = Mage::getStoreConfig('codshippingbooking/codshippingbooking_settings/codshippingbooking_cost_center_label');
			$data['consigneeName'] = $address['firstname'].' '.$address['middlename'].' '.$address['lastname'];
      $data['consigneeAddress'] = $address['street']; 
      $data['consigneeMobNo'] = $address['telephone']; 
      $data['consigneeEmail'] = isset($address['email'])?$address['email']:''; 
      $data['originCityName'] = '';
      $data['destinationCityName'] = $address['city'];
      $data['pieces'] = $totalQty;
      $data['codAmount'] = $order->getGrandTotal();
      $data['custRefNo'] = $order->getIncrementId(); 
      $data['productDetails'] = $productsDetails;
      $data['fragile'] = 'no';
      $data['service'] = 'O';
      $data['remarks'] = ''; 
      $data['insuranceValue'] = 0;
			 
			if((float)$order->getWeight()<0.5){
				$data['weight'] = 0.5;
			}
			else{
				$data['weight'] = $order->getWeight();
			}
			
			//call function to get cities from the api
			$cities = $this->getCities();
			$origin_cities = $this->getAllOriginCities();
			$data['originCity'] = Mage::getStoreConfig('shipping/origin/city', $storeId);
			
			$destination_cities_option = '<option value="">Select City</option>';
			$origin_cities_option = '<option value="">Select City</option>';
			
			//set options for destination cities
			foreach($cities->Table as $city){
				$selected = '';
				$selectMessage = '';
				if(strtolower($city->CityName)==strtolower($address['city'])){
					$selected = 'selected="selected"';
				}
				else{
					$selectMessage = '<small style="color:red;">The mentioned city '.$address['city'].' in order details did not match with our cities list, please select it manually</small>';
				}
				$destination_cities_option .= '<option value="'.$city->CityName.'" '.$selected.'>'.$city->CityName.'</option>';
			}
			
			$data['select_message'] = $selectMessage;
			
			//set options for origin cities
			foreach($origin_cities->Table as $city){
				$selected = '';
				if(strtolower($city->CityName)==strtolower($data['originCity'])){
					$selected = 'selected="selected"';
				}
				$origin_cities_option .= '<option value="'.$city->AreaName.'" '.$selected.'>'.$city->AreaName.'</option>';
			}
			
			$data['destinationCityOptions'] = $destination_cities_option;
			$data['originCityOptions'] = $origin_cities_option;
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
		return $this->getUrl('admin_codshippingbooking/adminhtml_sales_order_index/preview');
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
