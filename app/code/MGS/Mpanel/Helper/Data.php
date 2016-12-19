<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MGS\Mpanel\Helper;

/**
 * Contact base helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	protected $_scopeConfig;
	
	protected $_storeManager;
	
	protected $_date;
	
	protected $_url;
	
	protected $_pageLayout;
	
	public function __construct(
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\Stdlib\DateTime\DateTime $date,
		\Magento\Catalog\Model\Category $category,
		\Magento\Framework\Url $url,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
		\Magento\Catalog\Model\Design $catalogDesign

	) {
		$this->_scopeConfig = $scopeConfig;
		$this->_storeManager = $storeManager;
		$this->_date = $date;
		$this->_url = $url;
		$this->_category = $category;
		$this->_objectManager = $objectManager;
		$this->_categoryFactory = $categoryFactory;
		$this->_catalogDesign = $catalogDesign;
	}
	
	public function getStore(){
		return $this->_storeManager->getStore();
	}
	
	public function getStoreConfig($node, $storeId = NULL){
		if($storeId != NULL){
			return $this->_scopeConfig->getValue($node, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
		}
		return $this->_scopeConfig->getValue($node, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->getStore()->getId());
	}
	public function checkLayoutPage($category) {
		$settings = $this->_catalogDesign->getDesignSettings($category);		
		return $settings;
	}
	public function getThemeSettings(){
		return [
			'catalog'=> 
			[
				'per_row' => $this->getStoreConfig('mpanel/catalog/product_per_row'),
				'featured' => $this->getStoreConfig('mpanel/catalog/featured'),
				'hot' => $this->getStoreConfig('mpanel/catalog/hot'),
				'ratio' => $this->getStoreConfig('mpanel/catalog/picture_ratio'),
				'new_label' => $this->getStoreConfig('mpanel/catalog/new_label'),
				'sale_label' => $this->getStoreConfig('mpanel/catalog/sale_label'),
				'preload' => $this->getStoreConfig('mpanel/catalog/preload'),
				'wishlist_button' => $this->getStoreConfig('mpanel/catalog/wishlist_button'),
				'compare_button' => $this->getStoreConfig('mpanel/catalog/compare_button')
			],
			'product_details'=> 
			[
				'sku' => $this->getStoreConfig('mpanel/product_details/sku'),
				'reviews_summary' => $this->getStoreConfig('mpanel/product_details/reviews_summary'),
				'wishlist' => $this->getStoreConfig('mpanel/product_details/wishlist'),
				'compare' => $this->getStoreConfig('mpanel/product_details/compare'),
				'preload' => $this->getStoreConfig('mpanel/product_details/preload'),
				'short_description' => $this->getStoreConfig('mpanel/product_details/short_description'),
				'upsell_products' => $this->getStoreConfig('mpanel/product_details/upsell_products')
			],
			'product_tabs'=> 
			[
				'show_description' => $this->getStoreConfig('mpanel/product_tabs/show_description'),
				'show_additional' => $this->getStoreConfig('mpanel/product_tabs/show_additional'),
				'show_reviews' => $this->getStoreConfig('mpanel/product_tabs/show_reviews'),
				'show_product_tag_list' => $this->getStoreConfig('mpanel/product_tabs/show_product_tag_list')
			],
			'contact_google_map'=> 
			[
				'display_google_map' => $this->getStoreConfig('mpanel/contact_google_map/display_google_map'),
				'address_google_map' => $this->getStoreConfig('mpanel/contact_google_map/address_google_map'),
				'html_google_map' => $this->getStoreConfig('mpanel/contact_google_map/html_google_map'),
				'pin_google_map' => $this->getStoreConfig('mpanel/contact_google_map/pin_google_map'),
				'api_key' => $this->getStoreConfig('mpanel/contact_google_map/api_key')
			],
			'breadcrumb'=> 
			[
				'breadcrumb_tyle' => $this->getStoreConfig('mgstheme/general/breadcrumb'),
			]
		];
	}
	
	/* Get col for responsive */
	public function getColClass($perrow = NULL){
		if(!$perrow){
			$settings = $this->getThemeSettings();
			$perrow = $settings['catalog']['per_row'];
		}
		
		switch($perrow){
			case 2:
				return 'col-lg-6 col-md-6 col-sm-6 col-xs-6';
				break;
			case 3:
				return 'col-lg-4 col-md-4 col-sm-4 col-xs-6';
				break;
			case 4:
				return 'col-lg-3 col-md-3 col-sm-6 col-xs-6';
				break;
			case 6:
				return 'col-lg-2 col-md-2 col-sm-3 col-xs-6';
				break;
		}
		return;
	}
	/* Get class clear left */
	public function getClearClass($perrow = NULL, $nb_item){
		if(!$perrow){
			$settings = $this->getThemeSettings();
			$perrow = $settings['catalog']['per_row'];
		}
		$clearClass = '';
		switch($perrow){
			case 2:
				if($nb_item % 2 == 1){
					$clearClass.= " first-row-item row-sm-first row-xs-first";
				}
				return $clearClass;
				break;
			case 3:
				if($nb_item % 3 == 1){
					$clearClass.= " first-row-item row-sm-first";
				}
				if($nb_item % 2 == 1){
					$clearClass.= " row-xs-first";
				}
				return $clearClass;
				break;
			case 4:
				if($nb_item % 4 == 1){
					$clearClass.= " first-row-item";
				}
				if($nb_item % 2 == 1){
					$clearClass.= " row-sm-first row-xs-first";
				}
				return $clearClass;
				break;
			case 6:
				if($nb_item % 6 == 1){
					$clearClass.= " first-row-item";
				}
				if($nb_item % 4 == 1){
					$clearClass.= " row-sm-first";
				}
				if($nb_item % 2 == 1){
					$clearClass.= " row-xs-first";
				}
				return $clearClass;
				break;
		}
		return $clearClass;
	}
	
	/* Get product image size */
	public function getImageSize(){
		$ratio = $this->getStoreConfig('mpanel/catalog/picture_ratio');
		$maxWidth = $this->getStoreConfig('mpanel/catalog/max_width_image');
		$result = [];
        switch ($ratio) {
            // 1/1 Square
            case 1:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth));
                break;
            // 1/2 Portrait
            case 2:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth*2));
                break;
            // 2/3 Portrait
            case 3:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth * 1.5)));
                break;
            // 3/4 Portrait
            case 4:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth / 3) * 4));
                break;
            // 2/1 Landscape
            case 5:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth/2));
                break;
            // 3/2 Landscape
            case 6:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth*2) / 3));
                break;
            // 4/3 Landscape
            case 7:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth*3) / 4));
                break;
        }

        return $result;
	}
	
	/* Get product image size for product details page*/
	public function getImageSizeForDetails() {
		$ratio = $this->getStoreConfig('mpanel/catalog/picture_ratio');
		$maxWidth = $this->getStoreConfig('mpanel/catalog/max_width_image_detail');
        $result = [];
        switch ($ratio) {
            // 1/1 Square
            case 1:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth));
                break;
            // 1/2 Portrait
            case 2:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth*2));
                break;
            // 2/3 Portrait
            case 3:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth * 1.5)));
                break;
            // 3/4 Portrait
            case 4:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth * 4) / 3));
                break;
            // 2/1 Landscape
            case 5:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth/2));
                break;
            // 3/2 Landscape
            case 6:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth*2) / 3));
                break;
            // 4/3 Landscape
            case 7:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth*3) / 4));
                break;
        }

        return $result;
    }
	
	public function getImageMinSize() {
        $ratio = $this->getStoreConfig('mpanel/catalog/picture_ratio');
        $result = [];
        switch ($ratio) {
            // 1/1 Square
            case 1:
                $result = array('width' => 80, 'height' => 80);
                break;
            // 1/2 Portrait
            case 2:
                $result = array('width' => 80, 'height' => 160);
                break;
            // 2/3 Portrait
            case 3:
                $result = array('width' => 80, 'height' => 120);
                break;
            // 3/4 Portrait
            case 4:
                $result = array('width' => 80, 'height' => 107);
                break;
            // 2/1 Landscape
            case 5:
                $result = array('width' => 80, 'height' => 40);
                break;
            // 3/2 Landscape
            case 6:
                $result = array('width' => 80, 'height' => 53);
                break;
            // 4/3 Landscape
            case 7:
                $result = array('width' => 80, 'height' => 60);
                break;
        }

        return $result;
    }
	
	public function getProductLabel($product){
		$html = '';
		$newLabel = $this->getStoreConfig('mpanel/catalog/new_label');
        $saleLabel = $this->getStoreConfig('mpanel/catalog/sale_label');

		// Sale label
		$price = $product->getPrice();
		$finalPrice = $product->getFinalPrice();
		if(($finalPrice<$price) && ($saleLabel!='')){
			$html .= '<div class="product-label sale-label h6"><span>'.$saleLabel.'</span></div>';
		}
		
		// New label
		$now = $this->_date->gmtDate();
		$dateTimeFormat = \Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT;
		$newFromDate = $product->getNewsFromDate();
        $newFromDate = date($dateTimeFormat, strtotime($newFromDate));
        $newToDate = $product->getNewsToDate();
        $newToDate = date($dateTimeFormat, strtotime($newToDate));
		if ((!(empty($newToDate) && empty($newFromDate)) && ($newFromDate < $now || empty($newFromDate)) && ($newToDate > $now || empty($newToDate)) && ($newLabel != '')) || ((empty($newToDate) && ($newFromDate < $now)) && ($newLabel != ''))) {
			$html.='<div class="product-label new-label h6"><span>'.$newLabel.'</span></div>';
		}
		
		return $html;
	}
	
	public function getUrlBuilder(){
		return $this->_url;
	}
	
	public function getCssUrl(){
		return $this->_url->getUrl('mpanel/index/css',['store'=>$this->getStore()->getId()]);
	}
	
	public function getFonts() {
        return [
            ['css-name' => 'Lato', 'font-name' => __('Lato')],
            ['css-name' => 'Open+Sans', 'font-name' => __('Open Sans')],
            ['css-name' => 'Roboto', 'font-name' => __('Roboto')],
            ['css-name' => 'Roboto Slab', 'font-name' => __('Roboto Slab')],
            ['css-name' => 'Oswald', 'font-name' => __('Oswald')],
            ['css-name' => 'Source+Sans+Pro', 'font-name' => __('Source Sans Pro')],
            ['css-name' => 'PT+Sans', 'font-name' => __('PT Sans')],
            ['css-name' => 'PT+Serif', 'font-name' => __('PT Serif')],
            ['css-name' => 'Droid+Serif', 'font-name' => __('Droid Serif')],
            ['css-name' => 'Josefin+Slab', 'font-name' => __('Josefin Slab')],
            ['css-name' => 'Montserrat', 'font-name' => __('Montserrat')],
            ['css-name' => 'Ubuntu', 'font-name' => __('Ubuntu')],
            ['css-name' => 'Titillium+Web', 'font-name' => __('Titillium Web')],
            ['css-name' => 'Noto+Sans', 'font-name' => __('Noto Sans')],
            ['css-name' => 'Lora', 'font-name' => __('Lora')],
            ['css-name' => 'Playfair+Display', 'font-name' => __('Playfair Display')],
            ['css-name' => 'Bree+Serif', 'font-name' => __('Bree Serif')],
            ['css-name' => 'Vollkorn', 'font-name' => __('Vollkorn')],
            ['css-name' => 'Alegreya', 'font-name' => __('Alegreya')],
            ['css-name' => 'Noto+Serif', 'font-name' => __('Noto Serif')]
        ];
    }
	
	public function getLinksFont() {
        $setting = [
			'default_font' => $this->getStoreConfig('mgstheme/fonts/default_font'),
			'h1' => $this->getStoreConfig('mgstheme/fonts/h1'),
			'h2' => $this->getStoreConfig('mgstheme/fonts/h2'),
			'h3' => $this->getStoreConfig('mgstheme/fonts/h3'),
			'h4' => $this->getStoreConfig('mgstheme/fonts/h4'),
			'h5' => $this->getStoreConfig('mgstheme/fonts/h5'),
			'btn' => $this->getStoreConfig('mgstheme/fonts/btn'),
			'custom_font_fml' => $this->getStoreConfig('mgstheme/fonts/custom_font_fml'),
			'element' => $this->getStoreConfig('mgstheme/fonts/custom_fonts_element'),
			'h6' => $this->getStoreConfig('mgstheme/fonts/h6'),
			'price' => $this->getStoreConfig('mgstheme/fonts/price'),
			'menu' => $this->getStoreConfig('mgstheme/fonts/menu'),
		];
        $fonts = [];
        $fonts[] = $setting['default_font'];

        if (!in_array($setting['h1'], $fonts)) {
            $fonts[] = $setting['h1'];
        }

        if (!in_array($setting['h2'], $fonts)) {
            $fonts[] = $setting['h2'];
        }

        if (!in_array($setting['h3'], $fonts)) {
            $fonts[] = $setting['h3'];
        }

        if (!in_array($setting['price'], $fonts)) {
            $fonts[] = $setting['price'];
        }
		
		if (!in_array($setting['btn'], $fonts)) {
            $fonts[] = $setting['btn'];
        }
		
		if (!in_array($setting['custom_font_fml'], $fonts) && $setting['element'] !== null) {
            $fonts[] = $setting['custom_font_fml'];
        }
		
        if (!in_array($setting['menu'], $fonts)) {
            $fonts[] = $setting['menu'];
        }

        $fonts = array_filter($fonts);
        $links = '';

        foreach ($fonts as $_font) {
			$links .= '<link href="//fonts.googleapis.com/css?family=' . $_font . ':400,300,300italic,400italic,700,700italic,900,900italic" rel="stylesheet" type="text/css"/>';
        }

        return $links;
    }
	
	// get theme color
    public function getThemecolorSetting($storeId) {
        $setting = [
			'a:hover, a:focus, .banner-carousel .box-style3.black-box, .mgs-blog-lastest-posts .owl-carousel .item article .blog-desc .read-more, .block-text .icon span, .rating-result span:after , .product-reviews-summary .reviews-actions a:hover, .product-reviews-summary .reviews-actions.empty a:hover, .price, .price-box .price-wrapper .price, .mgs-product-tab .nav-tabs li a:hover span, .mgs-product-tab .nav-tabs li:hover a span, .mgs-product-tab .nav-tabs li:focus a span, .mgs-product-tab .nav-tabs li.active a span, .toolbar .modes .modes-mode.active, .toolbar .modes .modes-mode.active:hover, .block.filter .filter-options .block-title .block-sub-title:after, .block.filter .filter-actions a, .sidebar .block-wishlist .actions-toolbar .action.details, .sidebar .block-brand .view-all, .sidebar .block-brand .view-all a, .sidebar .related .block-content .action.select, .product-view-info .product-info-price .stock span, .review-add .block-content .review-field-rating .review-control-vote label.before, .sendfriend-product-send #product-sendtofriend-form .actions-toolbar .secondary .action.black, .brand-list.brand-index-index .shop-by-brand .characters .view-all, .checkout-cart-index #shopping-cart-table tbody  tr td.qty .actions-toolbar .gift-options-cart-item .action-gift, .multishipping-checkout-addresses .actions-toolbar .secondary .action.back, .multishipping-checkout-shipping #shipping_method_form .block .block-content .box .box-title .action, .multishipping-checkout-shipping #shipping_method_form .actions-toolbar .action.back, .multishipping-checkout-address-editshipping .form-address-edit .ations-toolbar .secondary .action.black, .multishipping-checkout-billing .actions-toolbar .action.back, .multishipping-checkout-billing .actions-toolbar .action.black, .multishipping-checkout-overview .block .block-content .box .box-title .action .multishipping-checkout-overview .checkout-reivew .actions-toolbar .action.back, .checkout-index-index .authentication-wrapper button.action-close span, .checkout-index-index .authentication-wrapper button.action-auth-toggle span, .checkout-index-index .checkout-payment-method .payments .payment-options .payment-option-title span, .checkout-index-index .opc-sidebar .modal-content .opc-block-shipping-infomation .shipping-infomation .action, .color-theme, .slider_mgs_carousel.owl-theme .owl-dots .owl-dot:hover span, .slider_mgs_carousel.on-load:after, .header-v1 .setting-pt .search-form .form-search .search-select #select-cat-dropdown li > span:hover, .footer-container.footer-v2 .middle-footer a:hover, .footer-container.footer-v3 .middle-footer a:hover, .footer-container.footer-v3 .middle-footer a:focus, .footer-container.footer-v8 .middle-footer a:hover, .breadcrumbs .breadcrumbs-items ul li > a:hover, .post-sharethis .stButton .chicklets:hover:before, .blog-post .post-info a:hover, .blog-post .postContent .post-link .aw-blog-read-more, .blog-post .block-blog-posts .block-content .post-list .li .post-name .date, .portfolio-grid .item .portfolio-bottom-content .category-link a:hover, .portfolio-details  .portfolio-content .action-toolbar .action:hover, .portfolio-details .portfolio-content .portfolio-table tbody tr td a:hover, .customer-account-create .actions-toolbar .action.back, .action .actions-toolbar .action.back, .block-title--myac h3 a, .block-order-details-comments .order-comments .comment .comment-date, .items.order-links  li.current, .items.order-links  li:hover, .header-v3 .search-form .form-search #select-cat-dropdown li:hover   ' => [
				'color' => $this->getStoreConfig('color/general/theme_color', $storeId)	
			],
			'.form-control:focus, .input-text:focus, .btn-default:hover, .btn-default:focus, .btn-default:active, .btn-primary, .btn-secondary:hover, .btn-secondary:focus, .btn-secondary:active, .btn-addlist:hover, .btn-addlist:focus, .btn-addlist:active, .btn-addlist.tocart:hover, .btn-addlist.tocart:focus, .btn-addlist.tocart:active, .banner-carousel .box-style2 a:hover, .newsletter-home .block-newsletter .actions .btn:hover, .banner-newsletter .box-text .block-newsletter .actions .btn:hover, .mgs-product-tab .nav-tabs li a:hover span, .mgs-product-tab .nav-tabs li:hover a span, .mgs-product-tab .nav-tabs li:focus a span, .mgs-product-tab .nav-tabs li.active a span, .brand-list.brand-index-index .shop-by-brand .characters ul.characters-filter li a:hover, .brand-list.brand-index-index .shop-by-brand .characters ul.characters-filter li.active a:hover, .checkout-index-index .authentication-wrapper button.action-login, .checkout-index-index .form-login .actions-toolbar button.login, .checkout-index-index .model-popup .modal-inner-wrap .modal-footer .action.primary, .checkout-index-index .model-popup .modal-inner-wrap .modal-footer .action.secondary:hover, .checkout-index-index .model-popup .modal-inner-wrap .modal-footer .action.secondary:focus, .checkout-index-index .model-popup .modal-inner-wrap .modal-footer .action.secondary:active, .checkout-index-index .methods-shipping .actions-toolbar button, .checkout-index-index .checkout-payment-method .payments .payment-method-content .actions-toolbar .action.checkout, .modal-popup .modal-footer .action-secondary:hover, .modal-popup .modal-footer .action-primary, .blog-post .postContent .post-link .aw-blog-read-more, .blog-post .postContent  blockquote,.tabs_categories_porfolio li .button.is-checked, .tabs_categories_porfolio li .button:hover, .tabs_categories_porfolio li .button:focus , .customer-account-create .actions-toolbar .action.back, .action .actions-toolbar .action.back, .items.order-links  li.current, .items.order-links  li:hover, .footer-container.footer-v9 .social-link a:hover ' => [
				'border-color' => $this->getStoreConfig('color/general/theme_color', $storeId)
			],
			'.btn-default:hover, .btn-default:focus, .btn-default:active, .btn-primary, .btn-secondary:hover, .btn-secondary:focus, .btn-secondary:active, .btn-addlist:hover, .btn-addlist:focus, .btn-addlist:active, .btn-addlist.tocart:hover, .btn-addlist.tocart:focus, .btn-addlist.tocart:active, .owl-theme.owl-carousel .owl-controls .owl-nav [class*=owl-]:hover, .newsletter-home .block-newsletter .actions .btn:hover, .banner-newsletter .box-text .block-newsletter .actions .btn:hover, .product-label.sale-label, .brand-list.brand-index-index .shop-by-brand .characters ul.characters-filter li a:hover, .brand-list.brand-index-index .shop-by-brand .characters ul.characters-filter li.active a:hover, .checkout-cart-index #shopping-cart-table tbody  tr td.qty .action-delete:hover, .checkout-cart-index .checkout-extra .block .block-title:hover h3, .checkout-cart-index .checkout-extra .block .block-title.active h3, .checkout-index-index .authentication-wrapper button.action-login, .checkout-index-index .opc-progress-bar .opc-progress-bar-item._active, .checkout-index-index .opc-progress-bar .opc-progress-bar-item._complete, .checkout-index-index .opc-progress-bar .opc-progress-bar-item._active:before, .checkout-index-index .opc-progress-bar .opc-progress-bar-item._complete:before, .checkout-index-index .form-login .actions-toolbar button.login, .checkout-index-index .model-popup .modal-inner-wrap .modal-footer .action.primary, .checkout-index-index .model-popup .modal-inner-wrap .modal-footer .action.secondary:hover, .checkout-index-index .model-popup .modal-inner-wrap .modal-footer .action.secondary:focus, .checkout-index-index .model-popup .modal-inner-wrap .modal-footer .action.secondary:active, .checkout-index-index .methods-shipping .actions-toolbar button, .checkout-index-index .checkout-payment-method .payments .payment-method-content .actions-toolbar .action.checkout, .modal-popup .modal-footer .action-secondary:hover, .modal-popup .modal-footer .action-primary, .scroll-to-top, .slider_mgs_carousel.owl-theme .owl-dots .owl-dot.active span, .blog-post .postContent ul li:before, .blog-post .block-blog-tags .block=content > span:hover, .tabs_categories_porfolio li .button.is-checked, .tabs_categories_porfolio li .button:hover, .tabs_categories_porfolio li .button:focus, .portfolio-grid .item .portfolio-top-content .link-portfolio > a:hover, .header-v3 .block-cart-header .action.showcart .icon-cart, .header-v3 .header-wl > a span:first-child:hover, .footer-container.footer-v9 .social-link a:hover, .ft-contact li .icon, .footer-container .block-subscribe .block-content .actions .sm-subscribe ' => [
				'background-color' => $this->getStoreConfig('color/general/theme_color', $storeId)
			]
		];
        $setting = array_filter($setting);
        return $setting;
    }
	
	// get header custom color
    public function getHeaderColorSetting($storeId) {
        $setting = [
            /* Header Top Section */
            '.top-header-content' => [
                'background-color' => $this->getStoreConfig('color/header/background_color', $storeId),
                'color' => $this->getStoreConfig('color/header/text_color', $storeId)
            ],
			'.top-header-content a, .top-header-content .dropdown .dropdown-toggle' => [
                'color' => $this->getStoreConfig('color/header/link_color', $storeId)
            ],
			'.top-header-content a:hover' => [
                'color' => $this->getStoreConfig('color/header/link_hover_color', $storeId)
            ],
			'.top-header-content .dropdown > .dropdown-menu' => [
                'background-color' => $this->getStoreConfig('color/header/dropdown_background', $storeId)
            ],
			'.top-header-content .dropdown > .dropdown-menu a' => [
                'color' => $this->getStoreConfig('color/header/dropdown_link_color', $storeId)
            ],
			'.top-header-content .dropdown > .dropdown-menu a:hover' => [
                'color' => $this->getStoreConfig('color/header/dropdown_link_hover_color', $storeId)
            ],
			/* Header Middle Section */
			'.middle-header-content' => [
                'background-color' => $this->getStoreConfig('color/header/middle_background', $storeId)
            ],
			/* Top Search Section */
			'#search_mini_form .input-text' => [
                'background-color' => $this->getStoreConfig('color/header/search_input_background', $storeId),
                'border-color' => $this->getStoreConfig('color/header/search_input_border', $storeId),
                'color' => $this->getStoreConfig('color/header/search_input_text', $storeId),
            ],
			'#search_mini_form .input-text::-webkit-input-placeholder' => [
                'color' => $this->getStoreConfig('color/header/search_input_text', $storeId)
            ],
			'#search_mini_form .input-text:-moz-placeholder' => [
                'color' => $this->getStoreConfig('color/header/search_input_text', $storeId)
            ],
			'#search_mini_form .input-text::-moz-placeholder' => [
                'color' => $this->getStoreConfig('color/header/search_input_text', $storeId)
            ],
			'#search_mini_form .input-text:-ms-input-placeholder' => [
                'color' => $this->getStoreConfig('color/header/search_input_text', $storeId)
            ],
			'.header-v2 .top-search-mini .form-search .button' => [
                'background-color' => $this->getStoreConfig('color/header/search_button_background', $storeId),
                'border-color' => $this->getStoreConfig('color/header/search_button_background', $storeId),
                'color' => $this->getStoreConfig('color/header/search_button_text', $storeId)
            ],
			'.header-v2 .top-search-mini .form-search .button:hover' => [
                'background-color' => $this->getStoreConfig('color/header/search_button_background_hover', $storeId),
                'border-color' => $this->getStoreConfig('color/header/search_button_background_hover', $storeId),
                'color' => $this->getStoreConfig('color/header/search_button_text_hover', $storeId)
            ],
			/* Top Cart Section */
			'.block-cart-header .icon-cart,.header-wl > a,.header-v1 .setting-pt .setting-drd button.dropdown-toggle,.top-search-mini .btn-show-search' => [
                'color' => $this->getStoreConfig('color/header/cart_icon', $storeId)
            ],
			'.header-v2 .block-cart-header .action.showcart .count.qty, .header-v1 .count.qty .counter-number' => [
                'background-color' => $this->getStoreConfig('color/header/cart_number_background', $storeId),
                'color' => $this->getStoreConfig('color/header/cart_number', $storeId)
            ],
			'.minicart-wrapper > .ui-widget-content' => [
                'background-color' => $this->getStoreConfig('color/header/cart_dropdown_background', $storeId),
                'border-color' => $this->getStoreConfig('color/header/cart_dropdown_border', $storeId),
            ],
			'.minicart-wrapper .ui-widget-content .block-content, .minicart-wrapper .ui-widget-content .block-content .subtitle' => [
                'color' => $this->getStoreConfig('color/header/cart_dropdown_text', $storeId)
            ],
			'.minicart-wrapper .ui-widget-content .block-content a' => [
                'color' => $this->getStoreConfig('color/header/cart_dropdown_link', $storeId)
            ],
			'.minicart-wrapper .ui-widget-content .block-content a:hover' => [
                'color' => $this->getStoreConfig('color/header/cart_dropdown_link_hover', $storeId)
            ],
			'.minicart-wrapper .ui-widget-content button, .minicart-wrapper .ui-widget-content .btn' => [
                'background-color' => $this->getStoreConfig('color/header/cart_dropdown_button_background', $storeId),
                'border-color' => $this->getStoreConfig('color/header/cart_dropdown_button_background', $storeId),
                'color' => $this->getStoreConfig('color/header/cart_dropdown_button_text', $storeId),
            ],
			'.minicart-wrapper .ui-widget-content button:hover, .minicart-wrapper .ui-widget-content .btn:hover' => [
                'background-color' => $this->getStoreConfig('color/header/cart_dropdown_button_background_hover', $storeId),
                'border-color' => $this->getStoreConfig('color/header/cart_dropdown_button_background_hover', $storeId),
                'color' => $this->getStoreConfig('color/header/cart_dropdown_button_text_hover', $storeId),
            ],
			/* Top Search Section */
			'#search_mini_form .input-text' => [
                'background-color' => $this->getStoreConfig('color/header/search_input_background', $storeId),
                'border-color' => $this->getStoreConfig('color/header/search_input_border', $storeId),
                'color' => $this->getStoreConfig('color/header/search_input_text', $storeId),
            ],
			'#search_mini_form .input-text::-webkit-input-placeholder' => [
                'color' => $this->getStoreConfig('color/header/search_input_text', $storeId)
            ],
			'#search_mini_form .input-text:-moz-placeholder' => [
                'color' => $this->getStoreConfig('color/header/search_input_text', $storeId)
            ],
			'#search_mini_form .input-text::-moz-placeholder' => [
                'color' => $this->getStoreConfig('color/header/search_input_text', $storeId)
            ],
			'#search_mini_form .input-text:-ms-input-placeholder' => [
                'color' => $this->getStoreConfig('color/header/search_input_text', $storeId)
            ],
			'#search_mini_form .btn-primary' => [
                'background-color' => $this->getStoreConfig('color/header/search_button_background', $storeId),
                'border-color' => $this->getStoreConfig('color/header/search_button_background', $storeId),
                'color' => $this->getStoreConfig('color/header/search_button_text', $storeId)
            ],
			'#search_mini_form .btn-primary:hover' => [
                'background-color' => $this->getStoreConfig('color/header/search_button_background_hover', $storeId),
                'border-color' => $this->getStoreConfig('color/header/search_button_background_hover', $storeId),
                'color' => $this->getStoreConfig('color/header/search_button_text_hover', $storeId)
            ],
			/* Menu Section */
			'#header-v2 .menu-content' => [
                'background-color' => $this->getStoreConfig('color/header/menu_background', $storeId)
            ],
			'#mainMenu.nav-main > li' => [
                'background-color' => $this->getStoreConfig('color/header/lv1_background', $storeId)
            ],
			'#mainMenu.nav-main > li > a.level0' => [
                'color' => $this->getStoreConfig('color/header/lv1_color', $storeId)
            ],
			'#mainMenu.nav-main > li:hover' => [
                'background-color' => $this->getStoreConfig('color/header/lv1_background_hover', $storeId)
            ],
			'#mainMenu.nav-main > li > a.level0:hover' => [
                'color' => $this->getStoreConfig('color/header/lv1_color_hover', $storeId)
            ],
			'#mainMenu.nav-main > li > a.level0::after' => [
                'backgroundcolor' => $this->getStoreConfig('color/header/lv1_color_hover', $storeId)
            ],
			'#mainMenu.nav-main li > ul.dropdown-menu' => [
                'background-color' => $this->getStoreConfig('color/header/menu_dropdown_background', $storeId)
            ],
			'#mainMenu.nav-main li > ul.dropdown-menu li > a' => [
                'color' => $this->getStoreConfig('color/header/menu_dropdown_link_color', $storeId)
            ],
			'#mainMenu.nav-main li > ul.dropdown-menu:hover' => [
                'background-color' => $this->getStoreConfig('color/header/menu_dropdown_background_hover', $storeId)
            ],
			'#mainMenu.nav-main li > ul.dropdown-menu li:hover > a' => [
                'color' => $this->getStoreConfig('color/header/menu_dropdown_link_color_hover', $storeId)
            ],
			/* Sticky Menu */
			'.sticky-menu.active-sticky,.sticky-menu.active-sticky .middle-header-content,.sticky-menu.active-sticky .top-header-content,.sticky-menu.active-sticky .menu-content ' => [
                'background-color' => $this->getStoreConfig('color/header/sticky_background', $storeId)
            ],
			'.sticky-menu.active-sticky #mainMenu.nav-main > li > a.level0' => [
                'color' => $this->getStoreConfig('color/header/sticky_link_pr', $storeId)
            ],
			'.sticky-menu.active-sticky #mainMenu.nav-main > li > a.level0:hover' => [
                'color' => $this->getStoreConfig('color/header/sticky_link_pr_hover', $storeId)
            ],
			'.sticky-menu.active-sticky #mainMenu.nav-main > li > a.level0::after' => [
                'backgroundcolor' => $this->getStoreConfig('color/header/sticky_link_pr_hover', $storeId)
            ],
			'.sticky-menu.active-sticky #mainMenu.nav-main li > ul.dropdown-menu' => [
                'background-color' => $this->getStoreConfig('color/header/sticky_dropdown_bg', $storeId)
            ],
			'.sticky-menu.active-sticky #mainMenu.nav-main li > ul.dropdown-menu li > a' => [
                'color' => $this->getStoreConfig('color/header/sticky_dropdown_link', $storeId)
            ],
			'.sticky-menu.active-sticky #mainMenu.nav-main li > ul.dropdown-menu li:hover > a' => [
                'color' => $this->getStoreConfig('color/header/sticky_dropdown_link_hover', $storeId)
            ],
        ];
        $setting = array_filter($setting);
        return $setting;
    }
	
	// get main content custom color
    public function getMainColorSetting($storeId) {
        $setting = [
            /* Text & Link color */
            '.page-main' => [
                'color' => $this->getStoreConfig('color/main/text_color', $storeId)
            ],
			'.page-main a, a' => [
                'color' => $this->getStoreConfig('color/main/link_color', $storeId)
            ],
			'.page-main a:hover' => [
                'color' => $this->getStoreConfig('color/main/link_color_hover', $storeId)
            ],
			'.page-main .price, .page-main .price-box .price, .price-box .price-wrapper .price, .price-box .special-price .price, .price-box .old-price .price' => [
                'color' => $this->getStoreConfig('color/main/price_color', $storeId)
            ],
			/* Default button color */
            'button, button.btn, button.btn-default, .btn.btn-default' => [
                'color' => $this->getStoreConfig('color/main/button_text', $storeId),
                'background-color' => $this->getStoreConfig('color/main/button_background', $storeId),
                'border-color' => $this->getStoreConfig('color/main/button_border', $storeId)
            ],
			'button:hover, button.btn:hover, button.btn-default:hover, .btn-default:hover, .btn-default:focus, .btn-default:active' => [
                'color' => $this->getStoreConfig('color/main/button_text_hover', $storeId),
                'background-color' => $this->getStoreConfig('color/main/button_background_hover', $storeId),
                'border-color' => $this->getStoreConfig('color/main/button_border_hover', $storeId)
            ],
			/* Primary button color */
            'button.btn-primary, .btn-primary, .btn.btn-primary' => [
                'color' => $this->getStoreConfig('color/main/primary_button_text'),
                'background-color' => $this->getStoreConfig('color/main/primary_button_background', $storeId),
                'border-color' => $this->getStoreConfig('color/main/primary_button_border', $storeId)
            ],
			'button.btn-primary:hover, .btn-primary:hover, .btn-primary:focus, .btn-primary:active' => [
                'color' => $this->getStoreConfig('color/main/primary_button_text_hover', $storeId),
                'background-color' => $this->getStoreConfig('color/main/primary_button_background_hover', $storeId),
                'border-color' => $this->getStoreConfig('color/main/primary_button_border_hover', $storeId)
            ],
			/* Secondary button color */
            'button.btn-secondary, .btn-secondary, .btn.btn-secondary' => [
                'color' => $this->getStoreConfig('color/main/secondary_button_text', $storeId),
                'background-color' => $this->getStoreConfig('color/main/secondary_button_background', $storeId),
                'border-color' => $this->getStoreConfig('color/main/secondary_button_border', $storeId)
            ],
			'button.btn-secondary:hover, .btn-secondary:hover, .btn-secondary:focus, .btn-secondary:active' => [
                'color' => $this->getStoreConfig('color/main/secondary_button_text_hover', $storeId),
                'background-color' => $this->getStoreConfig('color/main/secondary_button_background_hover', $storeId),
                'border-color' => $this->getStoreConfig('color/main/secondary_button_border_hover', $storeId)
            ],
        ];
        $setting = array_filter($setting);
        return $setting;
    }
	
	// get main content custom color
    public function getFooterColorSetting($storeId) {
        $setting = [
            /* Top Footer Section */
            'footer .top-footer' => [
                'background-color' => $this->getStoreConfig('color/footer/top_background_color', $storeId),
                'color' => $this->getStoreConfig('color/footer/top_text_color', $storeId),
                'border-color' => $this->getStoreConfig('color/footer/top_border_color', $storeId)
            ],
			'footer .top-footer label' => [
                'color' => $this->getStoreConfig('color/footer/top_text_color', $storeId)
            ],
			'footer .top-footer h1,footer .top-footer h2,footer .top-footer h3,footer .top-footer h4,footer .top-footer h5,footer .top-footer h6' => [
                'color' => $this->getStoreConfig('color/footer/top_heading_color', $storeId),
            ],
			'footer .top-footer a' => [
                'color' => $this->getStoreConfig('color/footer/top_link_color', $storeId),
            ],
			'footer .top-footer a:hover' => [
                'color' => $this->getStoreConfig('color/footer/top_link_color_hover', $storeId),
            ],
			'footer .top-footer .fa' => [
                'color' => $this->getStoreConfig('color/footer/top_icon_color', $storeId),
            ],
			/* Middle Footer Section */
            'footer .middle-footer' => [
                'background-color' => $this->getStoreConfig('color/footer/middle_background_color', $storeId),
                'color' => $this->getStoreConfig('color/footer/middle_text_color', $storeId),
                'border-color' => $this->getStoreConfig('color/footer/middle_border_color', $storeId)
            ],
			'footer .middle-footer label' => [
                'color' => $this->getStoreConfig('color/footer/middle_text_color', $storeId)
            ],
			'footer .middle-footer h1,footer .middle-footer h2,footer .middle-footer h3,footer .middle-footer h4,footer .middle-footer h5,footer .middle-footer h6' => [
                'color' => $this->getStoreConfig('color/footer/middle_heading_color', $storeId),
            ],
			'footer .middle-footer a' => [
                'color' => $this->getStoreConfig('color/footer/middle_link_color', $storeId),
            ],
			'footer .middle-footer a:hover' => [
                'color' => $this->getStoreConfig('color/footer/middle_link_color_hover', $storeId),
            ],
			'footer .middle-footer .fa' => [
                'color' => $this->getStoreConfig('color/footer/middle_icon_color', $storeId),
            ],
			/* Bottom Footer Section */
            '.footer-container .bottom-footer' => [
                'background-color' => $this->getStoreConfig('color/footer/bottom_background_color', $storeId),
                'color' => $this->getStoreConfig('color/footer/bottom_text_color', $storeId),
                'border-color' => $this->getStoreConfig('color/footer/bottom_border_color', $storeId)
            ],
			'.footer-container .bottom-footer label' => [
                'color' => $this->getStoreConfig('color/footer/bottom_text_color', $storeId)
            ],
			'.footer-container .bottom-footer h1,.footer-container .bottom-footer h2,.footer-container .bottom-footer h3,.footer-container .bottom-footer h4,.footer-container .bottom-footer h5,.footer-container .bottom-footer h6' => [
                'color' => $this->getStoreConfig('color/footer/bottom_heading_color', $storeId),
            ],
			'.footer-container .bottom-footer a' => [
                'color' => $this->getStoreConfig('color/footer/bottom_link_color', $storeId),
            ],
			'.footer-container .bottom-footer a:hover' => [
                'color' => $this->getStoreConfig('color/footer/bottom_link_color_hover', $storeId),
            ],
			'.footer-container .bottom-footer .fa' => [
                'color' => $this->getStoreConfig('color/footer/bottom_icon_color', $storeId),
            ],
			/* Social Footer Section */
			'.footer-container .social-link a' => [
                'color' => $this->getStoreConfig('color/footer/footer_social_color', $storeId),
                'background-color' => $this->getStoreConfig('color/footer/footer_social_background', $storeId),
                'border-color' => $this->getStoreConfig('color/footer/footer_social_border', $storeId),
            ],
			'.footer-container .social-link a:hover' => [
                'color' => $this->getStoreConfig('color/footer/footer_social_color_hover', $storeId),
                'background-color' => $this->getStoreConfig('color/footer/footer_social_background_hover', $storeId),
                'border-color' => $this->getStoreConfig('color/footer/footer_social_border_hover', $storeId),
            ],
			'.footer-container .social-link a .fa' => [
                'color' => $this->getStoreConfig('color/footer/footer_social_color', $storeId),
            ],
			'.footer-container .social-link a:hover .fa' => [
                'color' => $this->getStoreConfig('color/footer/footer_social_color_hover', $storeId),
            ],
			/* Newsletter Footer Section */
			'.footer-container .block-subscribe .block-content .input-box .input-text' => [
                'color' => $this->getStoreConfig('color/footer/footer_newsletter_input_color', $storeId),
                'background-color' => $this->getStoreConfig('color/footer/footer_newsletter_input_background', $storeId),
                'border-color' => $this->getStoreConfig('color/footer/footer_newsletter_input_border', $storeId),
            ],
        ];
        $setting = array_filter($setting);
        return $setting;
    }
	public function getModel($model)
	{
		return $this->_objectManager->create($model);
	}
	public function getCategories()
	{
		$rootCategoryId = $this->_storeManager->getStore()->getRootCategoryId();
		$categoriesArray = $this->_category
			->getCollection()
			->setStoreId($this->_storeManager->getStore()->getId())
			->addAttributeToSelect('*')
			->addAttributeToFilter('is_active', 1)
			->addAttributeToFilter('include_in_menu', 1)
			->addAttributeToFilter('path', array('like' => "1/{$rootCategoryId}/%"))
			->addAttributeToSort('path', 'asc')
			->load()
			->toArray();
		$categories = array();
		if(isset($categoriesArray['items'])){
			foreach ($categoriesArray['items'] as $categoryId => $category) {
				if (isset($category['name'])) {
					$categories[] = array(
						'label' => $category['name'],
						'level' => $category['level'],
						'value' => $category['entity_id']
					);
				}
			}
		}else {
			foreach ($categoriesArray as $categoryId => $category) {
				if (isset($category['name'])) {
					$categories[] = array(
						'label' => $category['name'],
						'level' => $category['level'],
						'value' => $category['entity_id']
					);
				}
			}
		}
		return $categories;
	}
	public function getCurrentlySelectedCategoryId()
	{
		$params = $this->getModel('Magento\Framework\App\Request\Http')->getParams();
		if (isset($params['cat'])) {
			return $params['cat'];
		}
		return '';
	}
	public function getCategoryName($product,$baseName,$categories){
		if($categories != null){
			$_catName = $categories->getName();
		}else{
			$cats = $product->getCategoryIds();
			if(count($cats) > 0){
				$j=0; 
				foreach ($cats as $category_id){
					$j++;
					if($j == 2){
						break;
					}
					$category = $this->_categoryFactory->create();
					$category->load($category_id);
					$_catName = $category->getName();
				}
			}else{
				$_catName = $baseName;
			}
		}
		return $_catName;
	}
	public function getCategoryById($categoryId){
		if($categoryId == null){
			return false;			
		}else{
			$category = $this->_categoryFactory->create();
			$category->load($categoryId);
			return $category;
		}		
	}
}