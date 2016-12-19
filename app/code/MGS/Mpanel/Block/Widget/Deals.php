<?php
namespace MGS\Mpanel\Block\Widget;
 
class Deals extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Widget\Block\BlockInterface
{
	protected $urlHelper;
	
	protected $_storeManager;
	
	protected $httpContext;
	
	protected $_catalogProductVisibility;
	
	protected $_productCollectionFactory;
	
	protected $_objectManager;
	
	protected $_count;
	
	protected $_date;
	
	public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
		\Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\Stdlib\DateTime\DateTime $date,
        array $data = []
    ) {
		$this->urlHelper = $urlHelper;
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
	
	public function _toHtml()
    {
		$this->setTemplate('widget/deals.phtml');
		return parent::_toHtml();
    }
	
	public function getProductToShow()
    {
		$result = [];
		$productsIds = $this->getData('product_id');
		$productArray = explode(',',$productsIds);
		$idDeals = $this->getProductCollection();
		if(count($productArray)>0){
			foreach($productArray as $productId){
				if (in_array($productId, $idDeals)) {
					$result[] = $productId;
				}
			}
		}
		return $result;
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
		
		$result = [];
		
		if(count($collection)>0){
			foreach($collection as $_product){
				$result[] = $_product->getId();
			}
		}
		
        return $result;
    }
	
	public function getSliderControl()
    {
        return $this->getData('slider_control');
    }
	
	public function getSliderDots(){
		return $this->getData('slider_dots');		
	}
	
	public function getSlider()
    {
        return $this->getData('slider_mode');
    }
	public function getTitle()
    {
		if($this->hasData('title')){
			return $this->getData('title');
		}
		return;
    }
	
	public function getEncodedUrl($url)
    {
        return $this->urlHelper->getEncodedUrl($url);
    }
	
}