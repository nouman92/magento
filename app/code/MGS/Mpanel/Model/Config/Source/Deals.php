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
class Deals extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Framework\Option\ArrayInterface
{
	protected $httpContext;
	
	protected $_catalogProductVisibility;
	
	protected $_productCollectionFactory;
	
	protected $_objectManager;
	
	protected $_count;
	
	protected $_date;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\Stdlib\DateTime\DateTime $date,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
		$this->_objectManager = $objectManager;
        $this->httpContext = $httpContext;
		$this->_date = $date;
        parent::__construct(
            $context,
            $data
        );
    }
	
	public function getModel($model){
		return $this->_objectManager->create($model);
	}
	
	public function getProductCollection()
    {
		$now = $this->_date->gmtDate();
		$todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
			->addStoreFilter()
			->addAttributeToFilter(
				'special_from_date',
				[
					'or' => [
						0 => ['date' => true, 'to' => $todayEndOfDayDate],
						1 => ['is' => new \Zend_Db_Expr('not null')],
					]
				],
				'left'
			)->addAttributeToFilter(
				'special_to_date',
				[
					'or' => [
						0 => ['date' => true, 'from' => $todayStartOfDayDate],
						1 => ['is' => new \Zend_Db_Expr('not null')],
					]
				],
				'left'
			)->addAttributeToFilter(
				[
					['attribute' => 'special_from_date', 'is' => new \Zend_Db_Expr('not null')],
					['attribute' => 'special_to_date', 'is' => new \Zend_Db_Expr('not null')],
				]
			)->addAttributeToFilter(
				[
					['attribute' => 'special_from_date', array('lt' => $now)],
				]
			)->addAttributeToFilter(
				[
					['attribute' => 'special_to_date', array('gt' => $now)],
				]
			)->addAttributeToSort(
				'created_at',
				'desc'
			);
		
		$collection->getSelect()->where('price_index.final_price < price_index.price');
		
        return $collection;
    }
	
    public function toOptionArray()
    {	
		$attrs = [['value' => '', 'label' => __('')]];
    	$collection = $this->getProductCollection();
		if(count($collection)){
			foreach($collection as $_product){
				$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
				$productModel = $objectManager->create('Magento\Catalog\Model\Product');
				$_product = $productModel->load($_product->getId());
				$label_prd = '    '.$_product->getSku().'____'. $_product->getName().'    ';
				$attrs[] = ['value'=>$_product->getId(), 'label'=>$label_prd];
			}
		}else{
			$attrs[] = ['value'=>'', 'label'=> __('No deals product, please config special price, special form date and special to date')];
		}
		
        return $attrs;
    }
	
}