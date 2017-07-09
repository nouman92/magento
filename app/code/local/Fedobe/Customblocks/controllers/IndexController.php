<?php
class Fedobe_Customblocks_IndexController extends Mage_Core_Controller_Front_Action {        
    public function indexAction() {
        echo Mage::getStoreConfig('customblocks_options/messages/block_message');
		$image=Mage::getStoreConfig('customblocks_options/messages/block_image');
		$url=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		$a='<img src="'.$url.''.$image.'">';
		echo $a;
    }
}
?>