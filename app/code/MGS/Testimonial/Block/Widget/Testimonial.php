<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MGS\Testimonial\Block\Widget;

use Magento\Framework\View\Element\Template;

/**
 * Main contact form block
 */
class Testimonial extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
	
    protected $_objectManager;
	
    public function __construct(Template\Context $context, array $data = [], \Magento\Framework\ObjectManagerInterface $objectManager)
    {
        parent::__construct($context, $data);
		$this->_objectManager = $objectManager;
    }
	public function _toHtml(){
		$this->setTemplate('widget/default.phtml');
		return parent::_toHtml();		
	}
	
	public function getModel(){
		return $this->_objectManager->create('MGS\Testimonial\Model\Testimonial');
	}
	
	public function getCollection(){
		$model = $this->getModel();
		$collection = $model->getCollection()
			->addFieldToFilter('status', 1)
			->setOrder('testimonial_id', 'DESC');
		$collection->getSelect()->limit($this->getData('number_of_posts'));
			
		return $collection;
	}
	
	public function getAvatarUrl($fileName){
		if($fileName){
			$path = 'testimonial' . '/' . $fileName;
			$avatarUrl = $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;
			return $avatarUrl;
		}
		return false;
	}
}

