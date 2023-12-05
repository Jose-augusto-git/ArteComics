(function ($) {

    var bxSlider = jQuery.fn.bxSlider;
    var $window = $(window);
    jQuery.fn.bxSlider = function () {
        var slider = bxSlider.apply(this, arguments);
        if (!this.length || !arguments[0].mouseDrag) {
            return slider;
        }
        var posX;
        var $viewport = this.parents('.bx-viewport');
        $viewport
            .on('dragstart', dragHandler)
            .on('mousedown', mouseDownHandler);
        function dragHandler(e) {
            e.preventDefault();
        }
        function mouseDownHandler(e) {
            posX = e.pageX;
            $window.on('mousemove.bxSlider', mouseMoveHandler);
        }
        function mouseMoveHandler(e) {
            if (posX < e.pageX) {
                slider.goToPrevSlide();
            } else {
                slider.goToNextSlide();
            }
            $window.off('mousemove.bxSlider');
        }
        return slider;
    };

    $(window).load(function () {
        var windowWidth = $(window).width();
        if (jQuery('.wbm-product-bxslider').length !== 0) {
            var numberOfVisibleSlides;
            if (windowWidth <= 480) {
                numberOfVisibleSlides = 1;
            } else if (windowWidth <= 1024) {
                numberOfVisibleSlides = 2;
            } else {
                numberOfVisibleSlides = 3;
            }
            var sliderNextText = jQuery('<i></i>').addClass('fas fa-chevron-right').css({'color': '#fff', 'background-color': 'rgb(0 0 0 / 20%)'});
            var sliderPrevText = jQuery('<i></i>').addClass('fas fa-chevron-left').css({'color': '#fff', 'background-color': 'rgb(0 0 0 / 20%)'});
            jQuery('.wbm-product-bxslider > ul').bxSlider({
                onSliderLoad: function () {
                    jQuery('.bx-wrapper').css('visibility', 'visible');
                    jQuery('.bx-wrapper').parent('.wbm_banner_random_image').fadeIn('medium');
                },
                minSlides: numberOfVisibleSlides,
                maxSlides: numberOfVisibleSlides,
                moveSlides: 1,
                controls: true,
                pager: true,
                auto: true,
                adaptiveHeight: true,
                responsive: true,
                slideWidth: 2200,
                slideMargin: 30,
                nextText: sliderNextText.get(0).outerHTML,
                prevText: sliderPrevText.get(0).outerHTML
            });
        }

        if ($('.dswbm-sliders-main').length !== 0) {
            var sliderNextIcon = $('<i></i>').addClass('fa fa-angle-right');
            var sliderPrevIcon = $('<i></i>').addClass('fa fa-angle-left');

            $('.dswbm-sliders-main').each(function () {
                let sliderWrap = $(this).find('ul');
                let sliderMain = $(this);
                let sliderNumberLargeDesk = sliderMain.attr('slider-large-desk');
                let sliderNumberDesk = sliderMain.attr('slider-desk');
                let sliderNumberLaptop = sliderMain.attr('slider-laptop');
                let sliderNumberTablet = sliderMain.attr('slider-tablet');
                let sliderNumberMobile = sliderMain.attr('slider-mobile');

                let sliderScrollLargeDesktop = sliderMain.attr('slider-to-large-desktop');
                let sliderToScrollDesktop = sliderMain.attr('slider-to-desktop');
                let sliderToScrollLaptop = sliderMain.attr('slider-to-laptop');
                let sliderToScrollTablet = sliderMain.attr('slider-to-tablet');
                let sliderToScrollMobile = sliderMain.attr('slider-to-mobile');

                var numberOfVisibleSlides;
                if (windowWidth <= 480) {
                    numberOfVisibleSlides = sliderNumberMobile;
                } else if (windowWidth <= 736) {
                    numberOfVisibleSlides = sliderNumberTablet;
                } else if (windowWidth <= 980) {
                    numberOfVisibleSlides = sliderNumberLaptop;
                } else if (windowWidth <= 1280) {
                    numberOfVisibleSlides = sliderNumberDesk;
                } else {
                    numberOfVisibleSlides = sliderNumberLargeDesk;
                }

                var sliderScrolltodevice;
                if (windowWidth <= 480) {
                    sliderScrolltodevice = sliderToScrollMobile;
                } else if (windowWidth <= 736) {
                    sliderScrolltodevice = sliderToScrollTablet;
                } else if (windowWidth <= 980) {
                    sliderScrolltodevice = sliderToScrollLaptop;
                } else if (windowWidth <= 1280) {
                    sliderScrolltodevice = sliderToScrollDesktop;
                } else {
                    sliderScrolltodevice = sliderScrollLargeDesktop;
                }

                let autoPlay = sliderMain.attr('auto-play');
                let autoPlaySpeed = sliderMain.attr('auto-play-speed');
                let scrollSpeed = sliderMain.attr('scroll-speed');
                let pauseOnHover = sliderMain.attr('pause-hover');
                let inFiniteLoop = sliderMain.attr('infinite-loop');
                let autoHeight = sliderMain.attr('auto-height');
                let sliderNav = sliderMain.attr('show-controls');
                let sliderPager = sliderMain.attr('show-pager');
                let slideSpace = sliderMain.attr('slide-space');
                let slidermode = sliderMain.attr('slider-mode');
                let sliderLayoutPreset = sliderMain.attr('slider-layout-preset');
                let sliderTouchStatus = sliderMain.attr('slider-touch-status');
                let sliderMousewheelStatus = sliderMain.attr('slider-mousewheel-status');
                let sliderMouseDraggableStatus = sliderMain.attr('slider-mouse_draggable-status');

                var slidermodestatus;
                if ( 'standard' === slidermode || '' === slidermode ) {
                    slidermodestatus = false;
                } else {
                    slidermodestatus = true;
                    autoPlay         = 'false';
                }

                if( 'false-mobile' === sliderNav &&  windowWidth <= 480 ){
                    sliderNav = 'false';
                }
                if( 'false-mobile' === sliderNav &&  windowWidth > 481 ){
                    sliderNav = 'true';
                }

                
                if( 'false-mobile' === sliderPager && windowWidth <= 480 ){
                    sliderPager = 'false';
                }
                if( 'false-mobile' === sliderPager && windowWidth > 481 ){
                    sliderPager = 'true';
                }

                if( '' === sliderLayoutPreset || undefined === sliderLayoutPreset ) {
                    sliderLayoutPreset = 'slider';
                }

                if( 'slider' === sliderLayoutPreset ){
                    sliderWrap.find('li.last').removeClass('last');
                    sliderWrap.bxSlider({
                        minSlides: numberOfVisibleSlides,
                        maxSlides: numberOfVisibleSlides,
                        moveSlides: sliderScrolltodevice,
                        controls: sliderNav === 'true' ? true : false,
                        pager: sliderPager === 'true' ? true : false,
                        auto: autoPlay === 'true' ? true : false,
                        pause: parseInt(autoPlaySpeed),
                        speed: parseInt(scrollSpeed),
                        ticker: slidermodestatus,
                        tickerHover: slidermodestatus,
                        useCSS: false,
                        zoomType: "inner",
                        imageCrossfade: true,
                        adaptiveHeight: autoHeight === 'true' ? true : false,
                        autoHover: pauseOnHover === 'true' ? true : false,
                        infiniteLoop: inFiniteLoop === 'true' ? true : false,
                        responsive: true,
                        mouseDrag: sliderMouseDraggableStatus === 'true' ? true : false,
                        touchEnabled: sliderTouchStatus === 'true' ? true : false,
                        slideWidth: 2200,
                        slideMargin: parseInt(slideSpace),
                        nextText: sliderNextIcon.get(0).outerHTML,
                        prevText: sliderPrevIcon.get(0).outerHTML,
                    });
                    if( 'true' === sliderMousewheelStatus ){
                        sliderWrap.on('mousewheel', function(e){
                            e.preventDefault();
                      
                            if( !sliderWrap.hasClass('dontMove') ) {
                      
                              if(e.originalEvent.wheelDelta > 0) {
                                sliderWrap.goToPrevSlide();
                              } else {            
                                sliderWrap.goToNextSlide();
                              }
                      
                            }
                      
                        })
                    }
                }
            });
        }
    });
})(jQuery);
