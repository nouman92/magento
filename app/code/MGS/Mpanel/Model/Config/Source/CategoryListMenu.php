<?php
/**
 * Magesolution
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magesolution.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magesolution.com/license-agreement/
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Magesolution
 * @package    MGS_Mpanel
 * @copyright  Copyright (c) 2016 Magesolution (http://www.magesolution.com/)
 * @license    http://www.magesolution.com/license-agreement/
 */
namespace MGS\Mpanel\Model\Config\Source;
class CategoryListMenu implements \Magento\Framework\Option\ArrayInterface
{
    static $arr = array();
    static $tmp = array();

	protected $_categoryHelper;
	
    protected $_escaper = null;
    
    protected $_categoryFactory;

    public function __construct(
    	\Magento\Catalog\Model\CategoryFactory $categoryFactory,
		\Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Framework\Escaper $escaper
    	) {
        $this->_escaper = $escaper;
    	$this->_categoryFactory = $categoryFactory;
		$this->_categoryHelper = $categoryHelper;
    }
	
    public function toOptionArray()
    {
		$categories = $this->getStoreCategories(true,false,true);
		$attrs = [['value' => '', 'label' => __('')]];
		foreach($categories as $category){
			if ($category->getIsActive()){
				$attrs[] = ['value'=>$category->getId(), 'label'=>$category->getName()];
			}
		}
		return $attrs;
    }
	
	public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted , $asCollection, $toLoad);
    }
}