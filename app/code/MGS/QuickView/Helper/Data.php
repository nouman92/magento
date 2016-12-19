<?php

namespace MGS\QuickView\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    const XML_PATH_QUICKVIEW_ENABLED = 'mgs_quickview/general/enabled';
    const XML_PATH_QUICKVIEW_BUTTONSTYLE = 'mgs_quickview/general/button_style';

    public function aroundQuickViewHtml(
    \Magento\Catalog\Model\Product $product
    ) {
        $result = '';
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled) {
            $buttonStyle = 'mgs_quickview_button_' . $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_BUTTONSTYLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $productUrl = $this->_urlBuilder->getUrl('mgs_quickview/catalog_product/view', array('id' => $product->getId()));
            return $result . '<a class=" hidden-sm hidden-xs btn btn-view mgs-quickview ' . $buttonStyle . '" data-quickview-url=' . $productUrl . ' href="javascript:void(0);"><span class="pe-7s-search"></span></a>';
        }
        return $result;
    }

}
