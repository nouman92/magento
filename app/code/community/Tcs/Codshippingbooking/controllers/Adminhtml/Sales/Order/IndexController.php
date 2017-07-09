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
 * COD Shipping Booking Action Controller
 *
 * @category   TCS
 * @package    Tcs_Codshippingbooking
 * @author     TCS Developer <kamran.haider@tcs-e.com>
 */

class Tcs_Codshippingbooking_Adminhtml_Sales_Order_IndexController extends Mage_Adminhtml_Controller_Action
{
	const MAX_DECIMAL_VALUE = 99999999.9999;	
	/**
	 * Action to show the add data form
	 * 
	 * @param  null
	 * @return null
	 */	
	public function addAction(){
		$order_id = $this->getRequest()->getParam('order_id');
		Mage::Register('bookingOrderId', $order_id);
		$this->loadLayout();
		$this->renderLayout();
	}
	
	/**
	 * Action to show the add data form
	 * 
	 * @param  null
	 * @return null
	 */	
	public function previewAction(){
		$post = $this->getRequest()->getParams();
		$postData = $post['booking'];
				
		try {
			$error = false;
			$errorField = ''; 
			//Validation for mandatory data
			if (!Zend_Validate::is($postData['consigneeName'], 'NotEmpty')) {
				$errorField .= "Consignee Name is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['consigneeAddress'], 'NotEmpty')) {
				$errorField .= "Consignee Address is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['consigneeMobNo'], 'NotEmpty')) {
				$errorField .= "Consignee Mobile Number is Required.<br>";
				$error = true;
			}
			if(isset($postData['consigneeMobNo']) && !empty($postData['consigneeMobNo'])){
				if (!Zend_Validate::is($postData['consigneeMobNo'], 'Digits')) {
           $errorField .= 'Only Digits allowed For Mobile Number.<br>';
        }
			}
			if (!Zend_Validate::is($postData['destinationCityName'], 'NotEmpty')) {
				$errorField = "Destination City Name is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['weight'], 'NotEmpty')) {
				$errorField .= "Weight is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['codAmount'], 'NotEmpty')) {
				$errorField .= "COD Amount is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['fragile'], 'NotEmpty')) {
				$errorField .= "Fragile is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['services'], 'NotEmpty')) {
				$errorField .= "Service is Required.<br>";
				$error = true;
			}
			if(isset($postData['consigneeEmail']) && !empty($postData['consigneeEmail'])){
				if (!Zend_Validate::is($postData['consigneeEmail'], 'EmailAddress')) {
           $errorField .= 'Invalid email address.<br>';
        }
			}
			if (isset($postData['weight']) && !empty($postData['weight']) && $postData['weight'] > 0
					&& !Zend_Validate::is($postData['weight'], 'Between', array(0, self::MAX_DECIMAL_VALUE))) {
					$errorField .= 'The "weight" value is not within the specified range.<br>';
			}
			
			if(isset($postData['codAmount']) && !empty($postData['codAmount'])){
				if (!Zend_Validate::is($postData['codAmount'], 'Float')) {
           $errorField .= 'Only numbers with/without decimal allowed for Cod Amount.<br>';
        }
			}
			if(isset($postData['custRefNo']) && !empty($postData['custRefNo'])){
				if (!Zend_Validate::is($postData['custRefNo'], 'Digits')) {
           $errorField .= 'Only Digits allowed For Mobile Number.<br>';
        }
			}
			if(isset($postData['pieces']) && !empty($postData['pieces'])){
				if (!Zend_Validate::is($postData['pieces'], 'GreaterThan',array('min' => 1))) {
           $errorField .= 'Numbers greater than zero are allowed.<br>';
        }
			}
			if ($error) {
			throw new Exception();
			}

			Mage::Register('bookingData', $postData);
			//Send the data to the api
			$this->loadLayout();
			$this->renderLayout();
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('codshippingbooking')->__('Unable to submit your request due to following errors:<br>'.$errorField));
			$this->_redirect('*/*/add',array('order_id'=>$postData['order_id']));
			return;
		}	
		
		
	}
	
	/**
	 * Action to gether the from data and post the data to api
	 * 
	 * @param  null
	 * @return null
	 */		
	public function addPostAction(){
		
		$post = $this->getRequest()->getParams();
		$postData = $post['booking'];		
		try {
			$error = false;
			$errorField = ''; 
			//Validation for mandatory data
			if (!Zend_Validate::is($postData['consigneeName'], 'NotEmpty')) {
				$errorField .= "Consignee Name is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['consigneeAddress'], 'NotEmpty')) {
				$errorField .= "Consignee Address is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['consigneeMobNo'], 'NotEmpty')) {
				$errorField .= "Consignee Mobile Number is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['destinationCityName'], 'NotEmpty')) {
				$errorField = "Destination City Name is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['weight'], 'NotEmpty')) {
				$errorField .= "Weight is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['codAmount'], 'NotEmpty')) {
				$errorField .= "COD Amount is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['fragile'], 'NotEmpty')) {
				$errorField .= "Fragile is Required.<br>";
				$error = true;
			}
			if (!Zend_Validate::is($postData['services'], 'NotEmpty')) {
				$errorField .= "Service is Required.<br>";
				$error = true;
			}
			if ($error) {
			throw new Exception();
			}
			//Send the data to the api
			$val = $this->sendAwbRequest($postData);
			if($val){
					$errorField .= "Api Error-".$val.".<br>";
					$error = true;
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('codshippingbooking')->__('Unable to submit your request due to following errors:<br>'.$errorField));
					$this->_redirect('*/*/add',array('order_id'=>$postData['order_id']));
					return;
			}
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('codshippingbooking')->__('Unable to submit your request due to following errors:<br>'.$errorField));
			$this->_redirect('*/*/add',array('order_id'=>$postData['order_id']));
			return;
		}	
	}
	
	/**
	 * Function to post the data to api
	 * 
	 * @param  array
	 * @return null
	 */	
	public function sendAwbRequest($data){
		
		//get user, paas and output type from the cofig data
		$data['userName'] = Mage::getStoreConfig('codshippingbooking/codshippingbooking_settings/codshippingbooking_user_key');
    $data['password'] = Mage::getStoreConfig('codshippingbooking/codshippingbooking_settings/codshippingbooking_user_pass');
		$outputType = Mage::getStoreConfig('codshippingbooking/codshippingbooking_settings/codshippingbooking_print');

		$client = new SoapClient('http://webapp.tcscourier.com/CODAPI/Service1.asmx?WSDL');
		$response = $client->InsertData($data);
		
		if($this->bigintval($response->InsertDataResult)){
			
			
			
			$order = Mage::getModel('sales/order')->load($data['order_id']);
			 
			// Add the comment and save the order (last parameter will determine if comment will be sent to customer)
			$order->addStatusHistoryComment('Cn Number - '.$response->InsertDataResult);
			$order->save();
			
			$result = file_get_contents('http://webapp.tcscourier.com/CODAPI/cnprn.aspx?cn='.$response->InsertDataResult);
			
			//From config check which type of output is selected	
			if($outputType=='pdf'){
				//get pdf output
				$this->getPdfOutput($result,$response->InsertDataResult);
			}
			else{
				//get html output
				$this->getHtmlOutput($result,$response->InsertDataResult);
			}
		}
		else{
			return $response->InsertDataResult;
		}
	}
	
	/**
	 * Function to post the data to api
	 * 
	 * @param  array
	 * @return null
	 */	
	public function getHtmlOutput($result,$cnNumber){
		//For pdf we need TCPDF
		/*require_once(Mage::getBaseDir('lib') . '/Codshippingbooking/tcpdf_barcodes_1d.php');
		require_once(Mage::getBaseDir('lib') . '/Codshippingbooking/tcpdf_include.php');
		
		$barcodeobj = new TCPDFBarcode('http://www.tcpdf.org', 'C128B');

		// output the barcode as HTML object
		$barcodeHtml = $barcodeobj->getBarcodeHTML(.5, 20, 'black');
		$barcodeHtml1 = $barcodeobj->getBarcodeHTML(.5, 20, 'black');
		$barcodeHtml2 = $barcodeobj->getBarcodeHTML(.5, 20, 'black');
		
		// some adjustments in the html received	
		$patterns[0] = '/src=\"images\/cnlogo.png\"/';
		$patterns[1] = '/<div id="barcodec">/';
		$patterns[2] = '/<div id="barcode">/';
		$patterns[3] = '/<div id="barcodea">/';
		
		$replacements[0] = 'src="'.Mage::getDesign()->getSkinUrl('images/codshippingbooking/cnlogo.png').'"';
		$replacements[1] = '<div id="barcodec">'.$barcodeHtml.'<span>'.$cnNumber.'</span>';
		$replacements[2] = '<div id="barcode">'.$barcodeHtml1.'<span>'.$cnNumber.'</span>';
		$replacements[3] = '<div id="barcodea">'.$barcodeHtml2.'<span>'.$cnNumber.'</span>';
				
		$html = preg_replace($patterns, $replacements, $result);
		$print = '<button style="" onclick="javascript:self.print();" class="scalable " type="button" title="Cancel"><span><span><span>Print</span></span></span></button>';*/
		
		//echo $print.$html;
		$message = '<ul class="messages"><li class="success-msg"><ul><li><span>Final output will be open in a new tab/window, so kindly make sure to allow popups.</span></li><li>Please close this window.</li></ul></li></ul>';
		$html = '<script>window.onload = function() {document.body.innerHTML=\''.$message.'\';};function OpenInNewTab(url) {var win = window.open(url, "_blank");win.focus();}OpenInNewTab("http://webapp.tcscourier.com/CODAPI/cnprn.aspx?cn='.$cnNumber.'"); closeAll();</script><style>.messages,.messages ul { font-style:arial; font-size:14px; list-style:none !important; margin:0 !important; padding:0 !important; }.messages { width:100%; overflow:hidden; }.messages li { margin:0 0 10px !important; }.messages li li { margin:0 0 3px !important; }.success-msg{ border-style:solid !important; border-width:1px !important; background-position:10px 9px !important; background-repeat:no-repeat !important; min-height:24px !important; padding:8px 8px 8px 32px !important; font-size:11px !important; font-weight:bold !important; }.success-msg { border-color:#446423; background-color:#eff5ea; background-image:url('.Mage::getDesign()->getSkinUrl('images/success_msg_icon.gif').'); color:#3d6611; }</style>';
		echo $html;
	}
	
	public function getPdfOutput($result, $cnNumber){
		
		//For pdf we need TCPDF
		require_once(Mage::getBaseDir('lib') . '/Codshippingbooking/tcpdf_barcodes_1d.php');
		require_once(Mage::getBaseDir('lib') . '/Codshippingbooking/tcpdf_include.php');
		
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		//We need to generate borcode to apply in odf
		$barcodeHtml = $pdf->serializeTCPDFtagParameters(array($cnNumber, 'C128B', 40, '', 24, 6, 0.2, array('position'=>'E', 'border'=>false, 'padding'=>0, 'fgcolor'=>array(0,0,0), 'bgcolor'=>'', 'text'=>false, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>2), 'N')); 

		//get html content between the form tag 
		$pattern = '/<table class="tab" cellpadding="0" cellspacing="0" style="font-size: 10px; font-family: arial;">(.*?)<\/table>/is';
		preg_match_all($pattern, $result, $matches, PREG_PATTERN_ORDER);
		
		$html =  '<table cellpadding="3">';
		foreach ($matches[0] as $match){
			$html .= '<tr><td>'.$match.'</td></tr>';
			$html .= '<tr><td align="center">--------------------------------------------------------------------------------------</td></tr>';
		}
		$html .=  '</table>';

		// some adjustments in the html received	
		$patterns[0] = '/<form(.*?)>/';
		$patterns[1] = '/<\/form>/';
		$patterns[2] = '/<input(.*?)>/';
		$patterns[3] = '/font-size: small/';
		$patterns[4] = '/src=\"images\/cnlogo.png\"/';
		$patterns[5] = '/<div id="barcodec">/';
		$patterns[6] = '/<div id="barcode">/';
		$patterns[7] = '/<div id="barcodea">/';
		$patterns[8] = '/<td rowspan="3" valign="bottom" width="85" class="tdl" align="center">/';
		$patterns[9] = '/<td rowspan="3" class="tdl" width="85">/';
		$patterns[10] = '/<td colspan="3" class="tdl" width="340">/';
		$patterns[11] = '/font-size: 10px;/';
		$patterns[12] = '/cellpadding="0"/';


		

		
		$replacements[0] = '';
		$replacements[1] = '';
	  $replacements[2] = '';
		$replacements[3] = 'font-size: 11px';
		$replacements[4] = 'src="'.Mage::getDesign()->getSkinUrl('images/codshippingbooking/cnlogo.png').'"';
		$replacements[5] = '<div id="barcodec"><tcpdf method="write1DBarcode" params="'.$barcodeHtml.'" /><span>'.$cnNumber.'</span>';
		$replacements[6] = '<div id="barcode"><tcpdf method="write1DBarcode" params="'.$barcodeHtml.'" /><span>'.$cnNumber.'</span>';
		$replacements[7] = '<div id="barcodea"><tcpdf method="write1DBarcode" params="'.$barcodeHtml.'" /><span>'.$cnNumber.'</span>';
		$replacements[8] = '<td rowspan="3" valign="bottom" width="135" class="tdl" align="center">';
		$replacements[9] = '<td rowspan="3" class="tdl" width="95" align="center">';
		$replacements[10] = '<td colspan="3" class="tdl" width="316">';
		$replacements[11] = '';
		$replacements[12] = 'cellpadding="3"';



				
		$html = preg_replace($patterns, $replacements, $html);

		$html = '<style> .tdl{border:1px solid #000;}.tdl {border-color: #000000;border-style: solid;border-width: 1px 1px 1px 1px;margin: 0;padding: 4px; font-size:10px;} .tdl img{margin-top:3px;}</style>'.$html;

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		
		// ---------------------------------------------------------
		
		// set font
		$pdf->SetFont('helvetica', 'R', 10);
		
		// add a page
		$pdf->AddPage();
		

		
		// print html using writeHTML()

		$pdf->writeHTML($html, true, false, true, false, '');
		

		// ---------------------------------------------------------
		
		//Close and output PDF document
		$pdf->Output($cnNumber.'.pdf', 'I');  
	
	}
	
	function bigintval($value) {
		$value = trim($value);
		if (ctype_digit($value)) {
			return $value;
		}
		$value = preg_replace("/[^0-9](.*)$/", '', $value);
		if (ctype_digit($value)) {
			return $value;
		}
		return 0;
	}
	
}
