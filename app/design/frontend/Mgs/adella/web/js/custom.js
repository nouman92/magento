require([
	'jquery'
], function(jQuery){
	(function($) {
		$.noConflict();
		$(window).load(function(){
			setTimeout(hiddenAlert, 4000);
		});
	
		$(document).ready(function(){
			// Close Newsellter Popup
			$('.modal-popup.newsletter-popup .modal-header .action-close').click(function(){
				$('.modal-popup.newsletter-popup').parent().find('.modals-overlay').hide();
			});
			// Show tooltip remember me
			$('#remember-me-box .tooltip .tooltip.toggle').click(function(){
				$('#remember-me-box > .tooltip .tooltip.content').toggle();
				return false;
			});
			// Hide tooltip remember me (Mouse hover out)
			$( "#remember-me-box" ).mouseleave(function() {
				$('#remember-me-box > .tooltip .tooltip.content').hide();
			});
			// Show search Header 2 
			$('#mini-search-mobile').click(function(){
				$('header .header-v2 .top-search-mini').slideToggle('fast');
			});
			// Show Menu (Responsive)
			$('.menu-pt .btn-responsive-nav').click(function(){
				$('header .menu-pt .nav-main-collapse').toggleClass('show-now');
				$('#mainMenu').enscroll({
					showOnHover: true,
					verticalTrackClass: 'track3',
					verticalHandleClass: 'handle3',
					addPaddingToPane: false
				});
				var menuHeight = $(window).height() - $('.tile-menu-rps').height();
				$('#mainMenu').height(menuHeight);
			});
			// Close Menu (Responsive)
			$('.tile-menu-rps .btn-close-mn').click(function(){
				$('header .menu-pt .nav-main-collapse').removeClass('show-now');
			});
			// Megamenu Dropdown (Static content Full Width)
			$('.static-menu .sub-menu li .toggle-menu > a').click(function(){
				$(this).toggleClass('active');
				$(this).parent().parent().find('.sub-menu').toggleClass('active');
				$(this).parent().parent().find('.sub-menu').slideToggle('fast');
			});
			// Megamenu Dropdown (Static content 1 Column)
			$('.static-menu li.dropdown-submenu .toggle-menu > a').click(function(){
				$(this).toggleClass('active');
				$(this).parent().parent().find('.dropdown-menu').toggleClass('active');
				$(this).parent().parent().find('.dropdown-menu').slideToggle('fast');
			});
			// Sticky Menu
			var height_header = $('.header').height();
			var check_height = $(window).height();
			var body_height = $('body').height() - 250 -  height_header;
			if(body_height > check_height){
				$(window).scroll(function(){
					if($(this).scrollTop() > 200 & $(this).width() > 991){	
						$('.header .sticky-menu').addClass('active-sticky');
					}else {
						$('.header .sticky-menu').removeClass('active-sticky');
					}
				});
			}
			//block filter
			
			if($(window).width() < 991){
				$('.filter .filter-title').click(function(){
					$(this).addClass('hide-filter');
					$(this).parent().find('.filter-content').slideToggle('fast');
				});			
			}
			$('.filter .filter-options .block-title').click(function(){
				$(this).addClass('hide-filter');
				$(this).parent().find('.block-content').slideToggle('fast');
			});
			$('.checkout-extra .block .block-title').click(function(){
				var tab_id = $(this).attr('data-tab');
				$('.checkout-extra .block .block-title').removeClass('active');
				$('.checkout-extra .block .content').removeClass('active');
				$(this).addClass('active');
				$("#"+tab_id).addClass('active');
			});
			//responsive widget slider
		
				var height_blogimage = $('.mgs-blog-lastest-posts .owl-carousel .item article .image img').height();
				var height_blogdesc = height_blogimage + 80;				
				$('.mgs-blog-lastest-posts .owl-carousel .item article .blog-desc').css('height',height_blogdesc + 'px');
				
			//	header-v4
			if($(window).width() > 1199){
				$(window).scroll(function(){
					var side_header_height4 = $("#header-v4 .middle-header-content").innerHeight();				
					var window_height = $(window).height();
					
					if(side_header_height4-window_height < $(this).scrollTop()){
						
						if(!$("#header-v4 .middle-header-content").hasClass("fixed-bottom")){
							$("#header-v4 .middle-header-content").addClass("fixed-bottom");
						}
					}
					if(side_header_height4-window_height >= $(this).scrollTop()){
						
						if( $("#header-v4 .middle-header-content").hasClass("fixed-bottom")){
							$("#header-v4 .middle-header-content").removeClass("fixed-bottom");
						}
					}
				});				
			}
			
		});
		
	})(jQuery);
});
function hiddenAlert() {
    require([
        'jquery'
    ], function (mgsjQuery) {
        (function () {
            mgsjQuery('.cms-index-index .page-main > .page.messages').slideUp();
        })(jQuery);
    });
}
function setLocation(url) {
    require([
        'jquery'
    ], function (jQuery) {
        (function () {
            window.location.href = url;
        })(jQuery);
    });
}