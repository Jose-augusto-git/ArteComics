(function ($) {
	let gDocument = $(document),
		gProgressBarE = $('.gutentor-element-progressbar'),
		counter_element = $('.gutentor-element-counter'),
		gWindow = $(window),
		gBody = $('body'),
		gWindowWidth = gWindow.width(),
		gM11Init = false,
		gFlexInit = false;

	/*Gutentor gBooleanVal*/
	function gBooleanVal(val) {
		if (typeof val === 'boolean') {
			return val;
		}
		return val === 'true';
	}

	function gSlick(gThis) {
		let gss = {}; /*Slick Setting*/
		if (gThis[0].hasAttribute('data-dots')) {
			gss.dots = gBooleanVal(gThis.data('dots'));
		}
		if (gThis[0].hasAttribute('data-arrows')) {
			gss.arrows = gBooleanVal(gThis.data('arrows'));
		}
		if (gThis[0].hasAttribute('data-infinite')) {
			gss.infinite = gBooleanVal(gThis.data('infinite'));
		}
		if (gThis[0].hasAttribute('data-speed')) {
			gss.speed = parseInt(gThis.data('speed'));
		}
		if (gThis[0].hasAttribute('data-slideitemdesktop')) {
			gss.slidesToShow = parseInt(gThis.data('slideitemdesktop'));
		}
		if (gThis[0].hasAttribute('data-slidescroll-desktop')) {
			gss.slidesToScroll = parseInt(gThis.data('slidescroll-desktop'));
		}
		if (gThis[0].hasAttribute('data-nextarrow')) {
			gss.nextArrow =
				'<span class="slick-next"><i class="' +
				gThis.data('nextarrow') +
				'"></i></span>';
		} else {
			gss.nextArrow =
				'<span class="slick-next"><i class="fas fa-angle-right"></i></span>';
		}
		if (gThis[0].hasAttribute('data-prevarrow')) {
			gss.prevArrow =
				'<span class="slick-prev"><i class="' +
				gThis.data('prevarrow') +
				'"></i></span>';
		} else {
			gss.prevArrow =
				'<span class="slick-prev"><i class="fas fa-angle-left"></i></span>';
		}
		if (gThis[0].hasAttribute('data-autoplay')) {
			gss.autoplay = gBooleanVal(gThis.data('autoplay'));
			if (gThis[0].hasAttribute('data-autoplayspeed')) {
				gss.autoplaySpeed = parseInt(gThis.data('autoplayspeed'));
			}
			if (gThis[0].hasAttribute('data-pauseonfocus')) {
				gss.pauseOnFocus = gBooleanVal(gThis.data('pauseonfocus'));
			}
			if (gThis[0].hasAttribute('data-pauseonhover')) {
				gss.pauseOnHover = gBooleanVal(gThis.data('pauseonhover'));
			}
		}
		if (gThis[0].hasAttribute('data-draggable')) {
			gss.draggable = gBooleanVal(gThis.data('draggable'));
		}
		if (gThis[0].hasAttribute('data-fade')) {
			gss.fade = gBooleanVal(gThis.data('fade'));
		}
		if (gThis[0].hasAttribute('data-blockimagesliderfade')) {
			gss.fade = gBooleanVal(gThis.data('blockimagesliderfade'));
		}
		if (gThis[0].hasAttribute('data-cmondesktop')) {
			gss.centerMode = gBooleanVal(gThis.data('cmondesktop'));
			if (gBooleanVal(gThis.data('cmondesktop'))) {
				if (gThis[0].hasAttribute('data-cmpaddingdesktop')) {
					gss.centerPadding = gThis.data('cmpaddingdesktop');
				}
			}
		}

		/*Responsive Setting*/
		let rTgss = {},
			rMgss = {};
		if (gThis[0].hasAttribute('data-slideitemtablet')) {
			rTgss.slidesToShow = parseInt(gThis.data('slideitemtablet'));
		}
		if (gThis[0].hasAttribute('data-slidescroll-tablet')) {
			rTgss.slidesToScroll = parseInt(gThis.data('slidescroll-tablet'));
		}
		if (gThis[0].hasAttribute('data-dotstablet')) {
			rTgss.dots = gBooleanVal(gThis.data('dotstablet'));
		}
		if (gThis[0].hasAttribute('data-arrowstablet')) {
			rTgss.arrows = gBooleanVal(gThis.data('arrowstablet'));
		}
		if (gThis[0].hasAttribute('data-cmontablet')) {
			rTgss.centerMode = gBooleanVal(gThis.data('cmontablet'));
			if (gBooleanVal(gThis.data('cmontablet'))) {
				if (gThis[0].hasAttribute('data-cmpaddingtablet')) {
					rTgss.centerPadding = gThis.data('cmpaddingtablet');
				}
			}
		}

		/*Responsive mobile*/
		if (gThis[0].hasAttribute('data-slideitemmobile')) {
			rMgss.slidesToShow = parseInt(gThis.data('slideitemmobile'));
		}
		if (gThis[0].hasAttribute('data-slidescroll-mobile')) {
			rMgss.slidesToScroll = parseInt(gThis.data('slidescroll-mobile'));
		}
		if (gThis[0].hasAttribute('data-dotsmobile')) {
			rMgss.dots = gBooleanVal(gThis.data('dotsmobile'));
		}
		if (gThis[0].hasAttribute('data-arrowsmobile')) {
			rMgss.arrows = gBooleanVal(gThis.data('arrowsmobile'));
		}
		if (gThis[0].hasAttribute('data-cmonmobile')) {
			rMgss.centerMode = gBooleanVal(gThis.data('cmonmobile'));
			if (gBooleanVal(gThis.data('cmonmobile'))) {
				if (gThis[0].hasAttribute('data-cmpaddingmobile')) {
					rMgss.centerPadding = gThis.data('cmpaddingmobile');
				}
			}
		}
		let rTSettings = {
				breakpoint: 1024,
				settings: rTgss,
			},
			rMSettings = {
				breakpoint: 480,
				settings: rMgss,
			};

		gss.responsive = [];
		gss.responsive.push(rTSettings);
		gss.responsive.push(rMSettings);

		/*Arrow Position*/
		if (
			gThis[0].hasAttribute('data-arrowspositiondesktop') &&
			'gutentor-slick-a-default-desktop' !== gThis.data('arrowspositiondesktop')
		) {
			gss.appendArrows = gThis.siblings('.gutentor-slick-arrows');
		}
		/*RTL*/
		if (gBody.hasClass('rtl')) {
			gss.rtl = true;
		}
		/*Finally call Slick*/
		if (gThis.hasClass('slick-initialized')) {
			gThis.slick('refresh');
		} else {
			gThis.slick(gss);
		}
	}

	/*Magnific Popup
	 * gThis
	 * isI => is image
	 * isG => is gallery
	 * */
	function gMagnificPopup(gThis, isI = false, isG = false) {
		let gma = {};
		if (isI) {
			gma = {
				type: 'image',
				closeBtnInside: false,
				fixedContentPos: false,
			};
		} else {
			gma = {
				type: 'iframe',
				mainClass: 'mfp-fade',
				removalDelay: 160,
				preloader: false,
				fixedContentPos: false,
			};
		}
		if (isG) {
			gma.gallery = {
				enabled: true,
				navigateByImgClick: true,
				preload: [0, 1], // Will preload 0 - before current, and 1 after the current image
			};
			gma.callbacks = {
				elementParse: function (item) {
					if (item.el[0].getAttribute('data-media-type') === 'g-popup-video') {
						item.type = 'iframe';
					} else {
						item.type = 'image';
					}
				},
			};
		}
		gThis.magnificPopup(gma);
	}

	/*easyPieChart*/
	function gEasyPieChart(gThis) {
		let gea = {
			barColor: gThis.data('barcolor'),
			trackColor: gThis.data('trackcolor'),
			scaleColor: gThis.data('scalecolor'),
			size: gThis.data('size'),
			lineCap: gThis.data('linecap'),
			animate: gThis.data('animate'),
			lineWidth: gThis.data('linewidth'),
		};
		if (typeof $.fn.easyPieChart === 'function') {
			gThis.easyPieChart(gea);
		}
	}

	/*CountUP*/
	function gCountUp(gThis) {
		let startValue = parseInt(gThis.data('start')),
			endValue = parseInt(gThis.data('end')),
			duration = parseInt(gThis.data('duration'));

		if (typeof CountUp === 'function') {
			let nCountUp = new CountUp(gThis[0], startValue, endValue, 0, duration);
			nCountUp.start();
		}
	}

	function gIsEleInView(elem) {
		let docViewTop = $(window).scrollTop(),
			docViewBottom = docViewTop + $(window).height(),
			elemTop = $(elem).offset().top,
			elemBottom = elemTop + $(elem).height();

		return elemBottom <= docViewBottom && elemTop >= docViewTop;
	}

	function gTriggerCountUp(gThis) {
		if (gIsEleInView(gThis)) {
			if (!gThis.hasClass('g-c-loaded')) {
				gThis.addClass('g-c-loaded');
				gCountUp(gThis);
			}
		}
	}

	function gTriggerPieChart(gThis) {
		if (gIsEleInView(gThis)) {
			if (!gThis.hasClass('g-c-loaded')) {
				gThis.addClass('g-c-loaded');
				gEasyPieChart(gThis);
			}
		}
	}

	function gAddLoaded(gThis) {
		if (gIsEleInView(gThis)) {
			if (!gThis.hasClass('g-c-loaded')) {
				gThis.addClass('g-c-loaded');
				gThis.css('width', gThis.attr('data-width') + '%');
			}
		}
	}

	function gCountAndBar($wrap = null) {
		if ($wrap) {
			/*CountUP Trigger*/
			$wrap
				.find('.gutentor-single-item-number,.gutentor-counter-number-main')
				.each(function () {
					gTriggerCountUp($(this));
				});

			/*easyPieChart Trigger*/
			$wrap
				.find(
					'.gutentor-progressbar-circular,.gutentor-element-progressbar-circular'
				)
				.each(function () {
					gTriggerPieChart($(this));
				});

			/*easyPieChart Trigger*/
			$wrap
				.find(
					'.gutentor-porgress-bar-item .progressbar,.gutentor-element-progressbar-box .gutentor-element-progressbar-horizontal'
				)
				.each(function () {
					gAddLoaded($(this));
				});
		} else {
			/*CountUP Trigger*/
			$('.gutentor-single-item-number,.gutentor-counter-number-main').each(
				function () {
					gTriggerCountUp($(this));
				}
			);

			/*easyPieChart Trigger*/
			$(
				'.gutentor-progressbar-circular,.gutentor-element-progressbar-circular'
			).each(function () {
				gTriggerPieChart($(this));
			});

			/*easyPieChart Trigger*/
			$(
				'.gutentor-porgress-bar-item .progressbar,.gutentor-element-progressbar-box .gutentor-element-progressbar-horizontal'
			).each(function () {
				gAddLoaded($(this));
			});
		}
	}
	gCountAndBar();
	$(window).scroll(function () {
		gCountAndBar();
	});
	/*Count Down*/
	function gCountDown(gThis) {
		// Set the date we're counting down to
		let gutentor_event_date = gThis.data('eventdate');
		if (gutentor_event_date === undefined || gutentor_event_date === null) {
			gThis.html(
				'<span>Please set validate Date and time for countdown </span>'
			);
			return false;
		}
		let expired_text = gThis.data('expiredtext'),
			gutentor_day = gThis.find('.day'),
			gutentor_hour = gThis.find('.hour'),
			gutentor_min = gThis.find('.min'),
			gutentor_sec = gThis.find('.sec'),
			gutentor_date_time = gutentor_event_date.split('T');
		if (gutentor_date_time.length !== 2) {
			return false;
		}
		let date_collection = gutentor_date_time[0],
			time_collection = gutentor_date_time[1],
			date_explode = date_collection.split('-');

		if (date_explode.length !== 3) {
			return false;
		}

		let time_explode = time_collection.split(':');
		if (time_explode.length !== 3) {
			return false;
		}

		let gutentor_year_value = parseInt(date_explode[0]),
			gutentor_month_value = parseInt(date_explode[1]) - 1,
			gutentor_day_value = parseInt(date_explode[2]),
			gutentor_hour_value = parseInt(time_explode[0]),
			gutentor_minutes_value = parseInt(time_explode[1]),
			gutentor_second_value = parseInt(time_explode[2]),
			countDownDate = new Date(
				gutentor_year_value,
				gutentor_month_value,
				gutentor_day_value,
				gutentor_hour_value,
				gutentor_minutes_value,
				gutentor_second_value,
				0
			).getTime();

		// Update the count down every 1 second
		let x = setInterval(function () {
			// Get todays date and time
			let now = new Date().getTime();

			// Find the distance between now an the count down date
			let distance = countDownDate - now;

			// Time calculations for days, hours, minutes and seconds
			let days = Math.floor(distance / (1000 * 60 * 60 * 24));
			let hours = Math.floor(
				(distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
			);
			let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			let seconds = Math.floor((distance % (1000 * 60)) / 1000);

			// Display the result in the element
			gutentor_day.html(days);
			gutentor_hour.html(hours);
			gutentor_min.html(minutes);
			gutentor_sec.html(seconds);
			// If the count down is finished, write some text
			if (distance < 0) {
				clearInterval(x);
				gThis.html('<span>' + expired_text + '</span>');
			}
		}, 1000);
	}

	/*Tabs*/
	function gTabs() {
		gDocument.on('click', '.gutentor-tabs-list', function () {
			let thisTabInside = $(this),
				gutentorSingleItemIndex = thisTabInside.data('index'),
				gTabsC = thisTabInside.closest('.gutentor-tabs'),
				gTabsContentWrap = gTabsC.next('.gutentor-tabs-content-wrap'),
				gTabsSingleContent = gTabsContentWrap.find(
					'.' + gutentorSingleItemIndex
				);

			gTabsSingleContent.siblings().removeClass('gutentor-tab-content-active');
			thisTabInside.siblings().removeClass('gutentor-tab-active');

			gTabsSingleContent.addClass('gutentor-tab-content-active');
			thisTabInside.addClass('gutentor-tab-active');
		});
	}

	/*show more block*/
	function gShowMoreBlock(className) {
		gDocument.on('click', className, function (e) {
			e.preventDefault();
			if (className === '.gutentor-show-more-button') {
				$(this)
					.closest('.gutentor-single-item-content')
					.addClass('show-more-content');
			} else if (className === '.gutentor-show-less-action-button') {
				$(this)
					.closest('.gutentor-single-item-content')
					.removeClass('show-more-content');
			} else if (className === '.gutentor-adv-s-m-btn') {
				$(this)
					.closest('.g-m-advance-show-more')
					.addClass('g-show-more-content');
			} else if (className === '.gutentor-adv-s-l-btn') {
				$(this)
					.closest('.g-m-advance-show-more')
					.removeClass('g-show-more-content');
			}
		});
	}

	/*API*/
	function gP4GetLoader(type) {
		let gP4Loader;
		switch (type) {
			case 'gp4-animation-1':
				gP4Loader = '<div class="gutentor-loading-wrap"></div>';
				break;
			case 'gp4-animation-2':
				gP4Loader =
					'<div class="gutentor-loading-wrap"><div class="gutentor-loading-2"><div></div><div></div><div></div></div></div>';
				break;
			case 'gp4-animation-3':
				gP4Loader =
					'<div class="gutentor-loading-wrap"><div class="gutentor-loading-3"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>';
				break;
			case 'gp4-animation-4':
				gP4Loader = '<div class="gutentor-loading-wrap"></div>';
				break;
			case 'gp4-animation-5':
				gP4Loader =
					'<div class="gutentor-loading-wrap"><div class="gutentor-loading-5"></div></div>';
				break;
			default:
				gP4Loader = '';
				break;
		}
		return gP4Loader;
	}

	function gApi(gAB, gParam, gAppend = false) {
		gParam.innerBlockType = gAB.data('i-b');
		gParam.blockId = gAB.find('.gutentor-post-module').data('gbid');
		gParam.postId = gAB.data('gpid');

		/*Globally Add Tax and Term Data*/
		if (gAB.find('.gutentor-filter-navigation').length) {
			gParam.gTax = gAB.find('.gutentor-filter-navigation').data('gtax');
			gParam.gTerm = gAB
				.find('.gutentor-filter-item-active')
				.children()
				.attr('data-gterm');
			if ('gAll' === gParam.gTerm) {
				gParam.allOpt = gAB
					.find('.gutentor-filter-item-active')
					.children()
					.attr('data-all-opt');
			}
		} else {
			gParam.gTax = 'default';
			gParam.gTerm = 'default';
		}
		if (!gParam.paged) {
			gParam.paged = 1;
		}
		/*Search Field*/
		if (gAB.find('.g-fm-search').length) {
			gParam.s = gAB.find('.g-fm-search').val();
		}

		$.ajax({
			type: 'GET',
			url: gutentorLS.restUrl + 'gutentor-self-api/v1/gadvancedb',
			data: gParam,
			beforeSend: function (xhr) {
				gAB.addClass(gAB.data('l-ani'));
				xhr.setRequestHeader('X-WP-Nonce', gutentorLS.restNonce);
				gAB.removeClass('gutentor-loaded');
				gAB
					.find('.gutentor-post-module .grid-container')
					.append(gP4GetLoader(gAB.data('l-ani')));
			},
		})
			.done(function (data) {
				if (!gAppend) {
					gAB.find('.gutentor-post-module').replaceWith(data.pBlog);
				} else {
					gAB
						.find('.gutentor-post-module .grid-container .grid-row')
						.append($(data.pBlog).find('.grid-container .grid-row').html());
				}

				gAB
					.find('.gutentor-pagination')
					.children() //Select all the children of the parent
					.not(':first-child') //Unselect the first child
					.not(':last-child') //Unselect the last child
					.remove();

				let paged = parseInt(gParam.paged),
					max_num_pages = parseInt(data.max_num_pages);

				gAB
					.find('.gutentor-pagination')
					.children('.gutentor-pagination-prev')
					.after(data.pagination)
					.children()
					.attr('data-gpage', paged > 1 ? paged - 1 : 1);
				gAB.attr('data-maxnumpages', max_num_pages);
				gAB
					.find('.gutentor-pagination')
					.children('.gutentor-pagination-next')
					.children()
					.attr(
						'data-gpage',
						max_num_pages > paged ? paged + 1 : max_num_pages
					);

				/*disabled class*/
				if (paged <= 1) {
					gAB
						.find('.gutentor-pagination')
						.children('.gutentor-pagination-prev')
						.children()
						.addClass('gutentor-disabled');
					gAB
						.find('.gutentor-navigation')
						.find('.g-nav-prev')
						.addClass('gutentor-disabled');
				} else {
					gAB
						.find('.gutentor-pagination')
						.children('.gutentor-pagination-prev')
						.children()
						.removeClass('gutentor-disabled');
					gAB
						.find('.gutentor-navigation')
						.find('.g-nav-prev')
						.removeClass('gutentor-disabled');
				}
				if (max_num_pages <= paged) {
					gAB
						.find('.gutentor-pagination')
						.children('.gutentor-pagination-next')
						.children()
						.addClass('gutentor-disabled');
					gAB
						.find('.gutentor-navigation')
						.find('.g-nav-next')
						.addClass('gutentor-disabled');

					/*for load more disabled class*/
					gAB
						.find(
							'.gutentor-post-footer.g-ap-load-more-template a.gutentor-button'
						)
						.addClass('gutentor-disabled');
				} else {
					gAB
						.find('.gutentor-pagination')
						.children('.gutentor-pagination-next')
						.children()
						.removeClass('gutentor-disabled');
					gAB
						.find('.gutentor-navigation')
						.find('.g-nav-next')
						.removeClass('gutentor-disabled');

					/*for load more disabled class*/
					gAB
						.find(
							'.gutentor-post-footer.g-ap-load-more-template a.gutentor-button'
						)
						.removeClass('gutentor-disabled');
				}
				gAB.find('.gutentor-post-module').each(function () {
					gReloadMasonry($(this));
				});
			})
			.fail(function (jqXHR, textStatus, errorThrown) {
				console.log(jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown);
			})
			.always(function () {
				$('.g-edd-cart .edd-no-js').hide();
				$('.g-edd-cart .edd-add-to-cart').addClass('edd-has-js');
				gAB.removeClass(gAB.data('l-ani'));
				gAB.addClass('gutentor-loaded');
				gAB
					.find('.gutentor-post-module .grid-container')
					.find('.gutentor-loading-wrap')
					.remove();
			});
	}
	/*Filter Cats*/
	gDocument.on(
		'click',
		'.gutentor-filter-navigation .gutentor-filter-item>a',
		function (e) {
			e.preventDefault();

			let gThis = $(this),
				gList = gThis.closest('.gutentor-filter-list'),
				gAB = gThis.closest('.gutentor-advanced-post-module');

			if (gThis.parent().hasClass('gutentor-filter-item-active')) {
				return false;
			}
			gList
				.find('.gutentor-filter-item')
				.removeClass('gutentor-filter-item-active');
			gThis.parent().addClass('gutentor-filter-item-active');

			/*for load more reset pagination */
			gAB
				.find('.gutentor-post-footer.g-ap-load-more-template a.gutentor-button')
				.removeAttr('data-gpage');

			/*Search reset*/
			if (gAB.find('.g-fm-search').length) {
				gAB.find('.g-fm-search').val('');
			}

			gApi(gAB, {});
		}
	);
	/*Numeric Pagination*/
	gDocument.on('click', '.gutentor-pagination a', function (e) {
		e.preventDefault();

		let gThis = $(this),
			gAB = gThis.closest('.gutentor-advanced-post-module');

		if (gThis.hasClass('gutentor-disabled')) {
			return false;
		}
		if (gThis.parent().hasClass('gutentor-pagination-active')) {
			return false;
		}
		let currentPage = gThis
			.parent()
			.siblings('.gutentor-pagination-active')
			.children()
			.attr('data-gpage');

		if (currentPage == gThis.attr('data-gpage')) {
			return false;
		}
		let gParam = {
			paged: gThis.attr('data-gpage'),
		};
		if (parseInt(gAB.attr('data-maxnumpages')) < parseInt(gParam.paged)) {
			return false;
		}
		gApi(gAB, gParam);
	});
	/*Navigation*/
	gDocument.on('click', '.gutentor-navigation a', function (e) {
		e.preventDefault();

		let gThis = $(this),
			gNav = gThis.closest('.gutentor-navigation'),
			gAB = gThis.closest('.gutentor-advanced-post-module');

		if (gThis.hasClass('gutentor-disabled')) {
			return false;
		}
		let current_page = parseInt(gNav.attr('data-gpage')),
			nextPage;
		if (gThis.hasClass('g-nav-prev')) {
			nextPage = current_page - 1;
		} else {
			nextPage = current_page + 1;
		}
		let gParam = {
			paged: nextPage,
		};

		gNav.attr('data-gpage', nextPage);
		gApi(gAB, gParam);
	});
	/*Load More*/
	gDocument.on(
		'click',
		'.gutentor-post-footer.g-ap-load-more-template a.gutentor-button',
		function (e) {
			e.preventDefault();

			let gThis = $(this),
				gAB = gThis.closest('.gutentor-advanced-post-module');

			if (gThis.hasClass('gutentor-disabled')) {
				return false;
			}

			if (!gThis.attr('data-gpage')) {
				gThis.attr('data-gpage', 2);
			}
			let gParam = {
				paged: gThis.attr('data-gpage'),
			};
			gThis.attr('data-gpage', parseInt(gThis.attr('data-gpage')) + 1);
			gApi(gAB, gParam, true);
		}
	);
	/*Search Icon*/
	gDocument.on('click', '.g-search-icon', function (e) {
		e.preventDefault();

		let gThis = $(this),
			gAB = gThis.closest('.gutentor-advanced-post-module');

		/*commented on request of a user*/
		/*https://wordpress.org/support/topic/post-module-header-search-keywords/#post-15077989*/
		/* if( !gAB.find('.g-fm-search').val()){
            return false;
        }*/
		gApi(gAB, {});
	});

	/*Search Enter*/
	$(document).on(
		'keydown',
		'.gutentor-advanced-post-module .g-fm-search',
		function (e) {
			let key = e.which;
			if (key === 13) {
				e.preventDefault();

				let gThis = $(this),
					gAB = gThis.closest('.gutentor-advanced-post-module');
				/*commented on request of a user*/
				/*https://wordpress.org/support/topic/post-module-header-search-keywords/#post-15077989*/
				/*  if( !gAB.find('.g-fm-search').val()){
                    return false;
                }*/

				gApi(gAB, {});
			}
		}
	);

	/*Advanced popup*/
	function gAdvPopupOpen(gma, gThis) {
		if (gThis.attr('devices')) {
			let devices = JSON.parse(gThis.attr('devices'));
			if (Array.isArray(devices)) {
				if (devices.includes('desktop') && window.screen.width > 991) {
					$.magnificPopup.open(gma);
				} else if (devices.includes('mobile') && window.screen.width < 768) {
					$.magnificPopup.open(gma);
				} else if (
					devices.includes('tablet') &&
					window.screen.width >= 767 &&
					window.screen.width <= 991
				) {
					$.magnificPopup.open(gma);
				}
			}
		} else {
			$.magnificPopup.open(gma);
		}
	}

	let gIdleTime;
	function gSetIdleTime(gma, gThis) {
		window.onload = resetTimer;
		window.onmousemove = resetTimer;
		window.onmousedown = resetTimer; // catches touchscreen presses as well
		window.ontouchstart = resetTimer; // catches touchscreen swipes as well
		window.ontouchmove = resetTimer; // required by some devices
		window.onclick = resetTimer; // catches touchpad clicks as well
		window.onkeydown = resetTimer;
		window.addEventListener('scroll', resetTimer, true); // improved; see comments

		function resetTimer() {
			clearTimeout(gIdleTime);
			if (gThis.attr('timing')) {
				gIdleTime = setTimeout(function () {
					if (!gThis.attr('g-adv-popup-open')) {
						gAdvPopupOpen(gma, gThis);
						gThis.attr('g-adv-popup-open', true);
					}
				}, parseFloat(gThis.attr('timing')) * 1000);
			}
		}
	}

	function gInitAdvPopup() {
		function gIsTrue(val) {
			if (val && val == '1') {
				return true;
			}
			return false;
		}

		let gma = {},
			gIndex = {},
			gCss = {},
			sideCss = '';
		$('.g-adv-popup').each(function (i) {
			let gThis = $(this),
				gMainClass = 'g-adv-popup-mfp ';
			gIndex[i] = {};
			gma[i] = {};
			gCss[i] = '.gadvpopupwow{visibility:hidden;}';
			if (gThis.attr('closeBtnCol')) {
				gCss[i] +=
					'.' +
					gThis.attr('id') +
					'-mfp' +
					' .mfp-close{color:' +
					gThis.attr('closeBtnCol') +
					';}';
			}
			if (gThis.attr('bgCol') || gThis.attr('bgGradient')) {
				let bgType = gThis.attr('bgType') ? gThis.attr('bgType') : '',
					bgcolor =
						bgType === 'solid' && gThis.attr('bgCol')
							? gThis.attr('bgCol')
							: '',
					bgGradient =
						bgType === 'gradient' && gThis.attr('bgGradient')
							? gThis.attr('bgGradient')
							: '',
					bgGradient2 = bgType === 'solid' && bgcolor ? 'none' : '',
					bgcolor2 =
						bgType === 'gradient' && gThis.attr('bgGradient')
							? 'transparent'
							: '';
				gCss[i] +=
					'.gutentor-active .mfp-bg{' +
					GutentorGenerateCSS('background-color', bgcolor) +
					GutentorGenerateCSS('background-image', bgGradient2) +
					GutentorGenerateCSS('background-image', bgGradient) +
					GutentorGenerateCSS('background-color', bgcolor2) +
					'}';
			}
			gMainClass += gThis.attr('id') + '-mfp ';
			gma[i] = {
				type: 'inline',
				items: {
					src: '#' + gThis.attr('id'),
				},
			};
			if (gThis.attr('type')) {
				gMainClass += 'g-adv-popup-' + gThis.attr('type') + ' ';
				if ('modal' === gThis.attr('wval')) {
					gma[i].modal = true;
				}
				if ('modal' === gThis.attr('type')) {
					if (gThis.attr('wval') && gThis.attr('wunit')) {
						gCss[i] +=
							'.' +
							gThis.attr('id') +
							'-mfp' +
							' .mfp-container .mfp-content{width:' +
							gThis.attr('wval') +
							gThis.attr('wunit') +
							';}';
					}
				}

				if ('side' === gThis.attr('type') && gThis.attr('side')) {
					if ((gThis.attr('x') || gThis.attr('y')) && gThis.attr('sideunit')) {
						if ('top-left' === gThis.attr('side')) {
							sideCss +=
								'left:' +
								gThis.attr('x') +
								gThis.attr('sideunit') +
								' !important;';
							sideCss +=
								'top:' +
								gThis.attr('y') +
								gThis.attr('sideunit') +
								' !important;';
						} else if ('top-right' === gThis.attr('side')) {
							sideCss +=
								'right:' +
								gThis.attr('x') +
								gThis.attr('sideunit') +
								' !important;';
							sideCss +=
								'top:' +
								gThis.attr('y') +
								gThis.attr('sideunit') +
								' !important;';
						} else if ('bottom-left' === gThis.attr('side')) {
							sideCss +=
								'left:' +
								gThis.attr('x') +
								gThis.attr('sideunit') +
								' !important;';
							sideCss +=
								'bottom:' +
								gThis.attr('y') +
								gThis.attr('sideunit') +
								' !important;';
						} else if ('bottom-right' === gThis.attr('side')) {
							sideCss +=
								'right:' +
								gThis.attr('x') +
								gThis.attr('sideunit') +
								' !important;';
							sideCss +=
								'bottom:' +
								gThis.attr('y') +
								gThis.attr('sideunit') +
								' !important;';
						}
					}
					if (gThis.attr('wval') && gThis.attr('wunit')) {
						sideCss +=
							'width:' + gThis.attr('wval') + gThis.attr('wunit') + ';';
					}
					gCss[i] += '.' + gThis.attr('id') + '-mfp' + ' {' + sideCss + '}';
					gMainClass += 'g-adv-popup-side-' + gThis.attr('side') + ' ';
				}
			}
			if (gMainClass) {
				gma[i].mainClass = gMainClass;
			}
			gma[i].closeOnBgClick = gIsTrue(gThis.attr('closeonbgclick'));
			gma[i].closeBtnInside = gIsTrue(gThis.attr('closebtninside'));
			gma[i].showCloseBtn = gIsTrue(gThis.attr('showclosebtn'));
			gma[i].enableEscapeKey = gIsTrue(gThis.attr('enableescapekey'));
			gma[i].fixedContentPos = gIsTrue(gThis.attr('fixedcontentpos'));
			gma[i].fixedBgPos = gIsTrue(gThis.attr('fixedbgpos'));

			if (!gma[i].closeBtnInside && $('body').hasClass('admin-bar')) {
				gCss[i] += '.' + gThis.attr('id') + '-mfp' + ' {top:32px !important;}';
			}
			gma[i].callbacks = {
				beforeOpen: function () {
					if (gCss[i]) {
						$('head').append(
							'<style id="gutentor-adv-popup-style">' + gCss[i] + '</style>'
						);
					}
					if (gThis.attr('animation')) {
						this.st.mainClass =
							this.st.mainClass + ' animated ' + gThis.attr('animation');
					}
				},
				open: function () {
					gRefreshJS($('.mfp-container'));
					/*WOW*/
					if (typeof WOW !== 'undefined') {
						let wow = new WOW({
							boxClass: 'gadvpopupwow',
						});
						wow.init();
					}
					$('.mfp-bg').removeClass(this.st.mainClass);

					if (
						'top' === gThis.attr('type') ||
						'bottom' === gThis.attr('type') ||
						'side' === gThis.attr('type')
					) {
						$('.mfp-bg').remove();
					}
				},
				close: function () {
					$('#gutentor-adv-popup-style').remove();
				},
			};

			if (gThis.attr('trigger')) {
				if ('load' === gThis.attr('trigger')) {
					if (gThis.attr('timing')) {
						setTimeout(function () {
							gAdvPopupOpen(gma[i], gThis);
						}, parseFloat(gThis.attr('timing')) * 1000);
					} else {
						gAdvPopupOpen(gma[i], gThis);
					}
				} else if ('click' === gThis.attr('trigger')) {
					gDocument.on('click', '.' + gThis.attr('clickclass'), function (e) {
						e.preventDefault();
						gAdvPopupOpen(gma[i], gThis);
					});
				} else if ('idle' === gThis.attr('trigger')) {
					gSetIdleTime(gma[i], gThis);
				} else if ('scroll' === gThis.attr('trigger')) {
					gIndex[i].lastScrollTop = 0;
					gIndex[i].open = false;
					gIndex[i].lastDirection = '';
					gIndex[i].scrollDistance = $(window).scrollTop();
					gIndex[i].triggerDistance = 0;
					if (gThis.attr('sval')) {
						gIndex[i].triggerDistance = parseInt(gThis.attr('sval'));
						if (gThis.attr('sunit') && '%' === gThis.attr('sunit')) {
							gIndex[i].triggerDistance =
								($(window).height() * gIndex[i].triggerDistance) / 100;
						}
						$(window).scroll(function (event) {
							if (!gIndex[i].open) {
								let st = $(this).scrollTop();
								if (st > gIndex[i].lastScrollTop) {
									if ('downscroll' !== gIndex[i].lastDirection) {
										gIndex[i].scrollDistance =
											gIndex[i].scrollDistance - (st - gIndex[i].lastScrollTop);
									} else {
										gIndex[i].scrollDistance =
											gIndex[i].scrollDistance + (st - gIndex[i].lastScrollTop);
									}
									gIndex[i].lastDirection = 'downscroll';
									if ('top' === gThis.attr('direction')) {
										if (gIndex[i].scrollDistance > gIndex[i].triggerDistance) {
											gAdvPopupOpen(gma[i], gThis);
											gIndex[i].open = true;
										}
									}
								} else {
									if ('upscroll' !== gIndex[i].lastDirection) {
										gIndex[i].scrollDistance =
											gIndex[i].scrollDistance - (st - gIndex[i].lastScrollTop);
									} else {
										gIndex[i].scrollDistance =
											gIndex[i].scrollDistance + (gIndex[i].lastScrollTop - st);
									}
									gIndex[i].lastDirection = 'upscroll';
									if ('bottom' === gThis.attr('direction')) {
										if (gIndex[i].scrollDistance > gIndex[i].triggerDistance) {
											gAdvPopupOpen(gma[i], gThis);
											gIndex[i].open = true;
										}
									}
								}
								gIndex[i].lastScrollTop = st;
							}
						});
					}
				}
			}
		});
	}
	function gAdvPopupData() {
		let gAllScripts = [],
			gAllStyles = [];

		function getAllScripts() {
			let scripts = window.document.getElementsByTagName('script');
			$(scripts).each(function () {
				if ($(this).attr('src')) {
					gAllScripts.push($(this).attr('src'));
				}
			});
		}
		getAllScripts();

		function getAllStyles() {
			let links = window.document.getElementsByTagName('link');
			$(links).each(function () {
				if ($(this).attr('href')) {
					gAllStyles.push($(this).attr('href'));
				}
			});
		}
		getAllStyles();

		function filterContent(filterContent) {
			filterContent.find('script').each(function () {
				if (!gAllScripts.includes($(this).attr('src'))) {
					if ($(this).attr('src')) {
						$('head').append(
							"<script type='text/javascript' src='" +
								$(this).attr('src') +
								"' />"
						);
					}
				}
				$(this).remove();
			});
			filterContent.find('link').each(function () {
				if (!gAllStyles.includes($(this).attr('href'))) {
					if (
						$(this).attr('href') &&
						$(this).attr('rel') &&
						'stylesheet' === $(this).attr('rel')
					) {
						$('head').append(
							"<link rel='stylesheet' href='" + $(this).attr('href') + "' />"
						);
					}
				}
				$(this).remove();
			});
			filterContent.find('.wow').addClass('gadvpopupwow').removeClass('wow');

			gBody.append(filterContent);
			gInitAdvPopup();
		}

		if ('ajax' != gutentorLS.adv_popup_load) {
			filterContent($('.g-adv-popup'));
		} else {
			let gParam = {
				condition: gutentorLS.condition,
			};
			$.ajax({
				type: 'GET',
				url: gutentorLS.restUrl + 'gutentor-self-api/v1/popup',
				data: gParam,
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', gutentorLS.restNonce);
				},
			})
				.done(function (data) {
					data &&
						data.map((item, i) => {
							let dataContent = $(item);
							filterContent(dataContent);
						});
				})
				.fail(function (jqXHR, textStatus, errorThrown) {
					console.log(jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown);
				})
				.always(function () {});
		}
	}

	function GutentorGenerateCSS(prop, value) {
		if (value) {
			return `${prop}: ${value};`;
		}
		return '';
	}

	/*Page Disabled for Pagination*/
	function gPageDisabled(gThis) {
		let gAB = gThis.closest('.gutentor-advanced-post-module');
		if (
			gAB.attr('data-maxnumpages') &&
			parseInt(gAB.attr('data-maxnumpages')) < 2
		) {
			gThis.addClass('gutentor-disabled');
		} else {
			gThis.removeClass('gutentor-disabled');
		}
	}

	/*Popover Block*/
	/*show popover content block*/
	function gPopOver() {
		gDocument.on(
			'mouseenter',
			'.g-m-popover.g-po-e-hover .g-m-popover-normal',
			function (e) {
				$(this).next('.g-m-popover-content').removeClass('d-none');
			}
		);
		gDocument.on(
			'mouseleave',
			'.g-m-popover.g-po-e-hover .g-m-popover-normal',
			function (e) {
				$(this).next('.g-m-popover-content').addClass('d-none');
			}
		);
		gDocument.on(
			'click',
			'.g-m-popover.g-po-e-hover .g-m-popover-normal',
			function (e) {
				e.preventDefault();
			}
		);
		gDocument.on(
			'click',
			'.g-m-popover.g-po-e-click .g-m-popover-normal',
			function (e) {
				$(this).next('.g-m-popover-content').toggleClass('d-none');
				e.preventDefault();
			}
		);
		$('.g-m-popover.g-m-popover-c-on')
			.find('.g-m-popover-content')
			.removeClass('d-none');
	}

	/*Acme Ticker*/
	function gAcmeTicker(gThis) {
		let news_ticker_data = gThis.find('.gutentor-news-ticker-data'),
			news_ticker_Pause = gThis
				.find('.gutentor-news-ticker-controls')
				.find('.gutentor-news-ticker-pause'),
			news_ticker_up = gThis
				.find('.gutentor-news-ticker-controls')
				.find('.gutentor-news-ticker-prev'),
			news_ticker_down = gThis
				.find('.gutentor-news-ticker-controls')
				.find('.gutentor-news-ticker-next');
		let options = {
			type: 'horizontal',
			direction: 'right',
			speed: 600,
			controls: {
				toggle:
					news_ticker_Pause /*Can be used for vertical/horizontal/marquee/typewriter*/,
			},
		};
		if (gThis.attr('data-type')) {
			options.type = gThis.attr('data-type');
			if (gThis.attr('data-type') !== 'marquee') {
				options.controls.prev = news_ticker_up;
				options.controls.next = news_ticker_down;
			}
		}
		if (gThis.attr('data-direction')) {
			options.direction = gThis.attr('data-direction');
		}
		if (gThis.attr('data-speed')) {
			options.speed = Number(gThis.attr('data-speed'));
		}
		if (gThis.attr('data-pauseOnHover')) {
			options.pauseOnHover = '1' === gThis.attr('data-pauseOnHover');
		}
		news_ticker_data.AcmeTicker(options);
	}

	function gEddCartIcon(gThis) {
		let icon = gThis.attr('data-icon'),
			button_class = gThis.find('.gutentor-button');
		if (button_class.hasClass('gutentor-icon-before')) {
			button_class.prepend(
				'<i class="gutentor-button-icon ' + icon + '" ></i>'
			);
		}
		if (button_class.hasClass('gutentor-icon-after')) {
			button_class.append('<i class="gutentor-button-icon ' + icon + '" ></i>');
		}
	}

	function gFlexMenu(gThis) {
		/*FlexMenu ( Responsive Menu)*/
		if (typeof $.fn.flexMenu !== 'undefined') {
			if (gThis.length) {
				gThis.flexMenu({
					threshold: 0,
					cutoff: 0,
					linkText: '<span class="screen-reader-text">More</span>',
					linkTextAll: '<span class="screen-reader-text">More</span>',
					linkTitle: '',
					linkTitleAll: '',
					showOnHover: gWindowWidth > 991 ? true : false,
				});
				if (!gFlexInit) {
					gDocument.click(function (e) {
						let flexMenuPopup = $('ul.flexMenu-popup');
						if (
							!(
								$(e.target).closest('li.flexMenu-viewMore ').length > 0 ||
								!flexMenuPopup.length ||
								!flexMenuPopup.is(':visible')
							)
						) {
							$('li.flexMenu-viewMore > a').trigger('click');
						}
					});
				}
				gFlexInit = true;
			}
		}
	}

	function gRefreshJS($wrap) {
		/*Counter and Bar*/
		gCountAndBar($wrap);

		/*Initilized disabled*/
		$wrap
			.find('.gutentor-post-footer.g-ap-load-more-template a.gutentor-button')
			.each(function () {
				gPageDisabled($(this));
			});

		/*Slick*/
		if (typeof $.fn.slick !== 'undefined') {
			$wrap.find('.gutentor-slider-wrapper').each(function () {
				gSlick($(this));
			});
			$wrap.find('.gutentor-module-slider-row').each(function () {
				gSlick($(this));
			});
			$wrap.find('.gutentor-carousel-row').each(function () {
				gSlick($(this));
			});
			$wrap.find('.gutentor-image-carousel-row').each(function () {
				gSlick($(this));
			});
			$wrap.find('.gutentor-module-carousel-row').each(function () {
				gSlick($(this));
			});
		}

		// Gutentor Countdown
		$wrap.find('.gutentor-countdown-wrapper').each(function () {
			gCountDown($(this));
		});

		// gutentor popover blocks
		gPopOver();

		/*Acme Ticker*/
		if (typeof $.fn.AcmeTicker !== 'undefined') {
			$wrap.find('.gutentor-post-module-p5').each(function () {
				gAcmeTicker($(this));
			});
		}

		/*edd add to cart*/
		$wrap.find('.g-edd-cart').each(function () {
			gEddCartIcon($(this));
		});

		/*Flex*/
		gFlexMenu($wrap.find('.g-responsive-menu'));

		/*Filter*/
		gFilter();

		/*sticky sidebar*/
		if (typeof $.fn.theiaStickySidebar !== 'undefined') {
			$wrap.find('.gutentor-enable-sticky-column').each(function () {
				gTheiaStickySidebar($(this));
			});
		}

		/*Video Autoplay*/
		gVideoAutoplay();

		//Gutentor Gallery Box
		if (typeof $.fn.imagesLoaded !== 'undefined') {
			let galleryWrapper = $wrap.find('.gutentor-gallery-wrapper');
			galleryWrapper.each(function () {
				gGalleryMasonry($(this));
			});
			let postModule = $('.gutentor-post-module-p1');
			postModule.each(function () {
				gPostModuleMasonry($(this));
			});
		}

		/*Isotop Refresh*/
		if (!gM11Init) {
			if (typeof $.fn.isotope === 'function') {
				gM11();
			}
		}

		if (typeof $.fn.isotope !== 'undefined') {
			if ($wrap.find('.g-gm-item-wrap').hasClass('isotope')) {
				$wrap.find('.g-gm-item-wrap').isotope('reloadItems').isotope();
			}
			if ($wrap.find('.gutentor-filter-item-wrap').hasClass('isotope')) {
				$wrap
					.find('.gutentor-filter-item-wrap')
					.isotope('reloadItems')
					.isotope();
			}
		}
		/*masonry Refresh*/
		$wrap.find('.gutentor-gallery-wrapper').each(function () {
			let masonryBoxes = $(this);
			if (masonryBoxes.hasClass('enable-masonry')) {
				let container = masonryBoxes.find('.full-width-row');
				container.masonry({
					itemSelector: '.gutentor-gallery-item',
				});
			}
		});
		$wrap.find('.gutentor-post-module-p1').each(function () {
			let masonryBoxes = $(this);
			if (masonryBoxes.hasClass('g-masonry')) {
				let container = masonryBoxes.find('.grid-row');
				container.masonry({
					itemSelector: '.gutentor-post',
				});
			}
		});
		$wrap.find('.g-gm-module').each(function (i, item) {
			let thisFilterWrap = $(this);
			if (thisFilterWrap.hasClass('g-on-masonry')) {
				let container = thisFilterWrap.find('.g-gm-item-wrap');
				container.masonry({
					itemSelector: '.g-gm-col ',
				});
			}
		});
	}

	/*Document ready function*/
	gDocument.ready(function () {
		/*Gutentor Advanced Popup*/
		if (
			'is_admin' in gutentorLS &&
			'is_on_adv_popup' in gutentorLS &&
			'adv_popup_load' in gutentorLS &&
			gutentorLS.is_on_adv_popup &&
			!gutentorLS.is_admin
		) {
			gAdvPopupData();
		}

		/*Magnific Popup close*/
		gDocument.on('click', '.g-adv-popup-close', function (e) {
			e.preventDefault();
			$.magnificPopup.close();
		});

		/*WOW*/
		if (typeof WOW !== 'undefined') {
			new WOW().init();
		}
		/*Magnific Popup*/
		/* video popup and button link popup */
		$('.gutentor-video-popup-holder').each(function () {
			gMagnificPopup($(this));
		});
		$('.g-v-btn').each(function () {
			gMagnificPopup($(this));
		});
		$('.g-v-fp-btn').each(function () {
			gMagnificPopup($(this));
		});
		$('.gutentor-element-button-link-popup').each(function () {
			gMagnificPopup($(this));
		});

		/*Initilized disabled*/
		$('.gutentor-post-footer.g-ap-load-more-template a.gutentor-button').each(
			function () {
				gPageDisabled($(this));
			}
		);

		/*Slick*/
		if (typeof $.fn.slick !== 'undefined') {
			$('.gutentor-slider-wrapper').each(function () {
				gSlick($(this));
			});
			$('.gutentor-module-slider-row').each(function () {
				gSlick($(this));
			});
			$('.gutentor-carousel-row').each(function () {
				gSlick($(this));
			});
			$('.gutentor-image-carousel-row').each(function () {
				gSlick($(this));
			});
			$('.gutentor-module-carousel-row').each(function () {
				gSlick($(this));
			});
		}

		/*Accordion*/
		gDocument.on('click', '.gutentor-accordion-heading', function (e) {
			var gThis = $(this),
				accordion_content = gThis.closest('.gutentor-accordion-wrap'),
				accordion_item = gThis.closest('.gutentor-single-item'),
				accordion_details = accordion_item.find('.gutentor-accordion-body'),
				accordion_all_items = accordion_content.siblings(
					'.gutentor-accordion-wrap'
				);

			accordion_all_items.each(function () {
				$(this).find('.gutentor-accordion-body').slideUp();
				$(this).find('.gutentor-accordion-heading').removeClass('active');
			});

			if (accordion_details.is(':visible')) {
				accordion_details.slideUp().removeClass('gutentor-active-body');
				gThis.removeClass('active');
			} else {
				accordion_details.slideDown().addClass('gutentor-active-body');
				gThis.addClass('active');
			}
			e.preventDefault();
		});

		/* Module Accordion*/
		function moduleAccordion(gThis) {
			let a_g_p = gThis.closest('.gutentor-module-accordion'),
				a_item = gThis.closest('.gutentor-module-accordion-item'),
				a_details = a_item.find('.gutentor-module-accordion-body'),
				a_o_items = a_item.siblings('.gutentor-module-accordion-item'),
				a_i_wrap = gThis.find('.gutentor-module-accordion-icon');

			if (a_g_p.hasClass('gutentor-module-accordion-enable-toggle')) {
				a_o_items.removeClass('gutentor-module-accordion-active');

				a_o_items.each(function () {
					$(this).find('.gutentor-module-accordion-body').slideUp();
					$(this)
						.find('.gutentor-module-accordion-icon')
						.removeClass('gutentor-module-accordion-icon-active');
				});
			}
			if (a_details.is(':visible')) {
				a_details.slideUp();
				a_i_wrap.removeClass('gutentor-module-accordion-icon-active');
				a_details.removeClass('gutentor-module-accordion-icon-active');
				a_item.removeClass('gutentor-module-accordion-active');
			} else {
				a_details.slideDown();
				a_details.addClass('gutentor-module-accordion-icon-active');
				a_i_wrap.addClass('gutentor-module-accordion-icon-active');
				a_item.addClass('gutentor-module-accordion-active');

				gRefreshJS(a_details);
			}
		}
		gDocument.on(
			'click',
			'.gutentor-module-accordion-item-heading',
			function (e) {
				moduleAccordion($(this));
				e.preventDefault();
			}
		);
		gDocument.on(
			'keypress',
			'.gutentor-module-accordion-item-heading',
			function (e) {
				if (e.keyCode == 13) {
					moduleAccordion($(this));
					e.preventDefault();
				}
			}
		);

		/* Table of content Accordion*/
		gDocument.on('click', '.g-toc-header', function (e) {
			let gThis = $(this),
				toc_parent = gThis.closest('.gutentor-module-table-of-contents'),
				toc_body = toc_parent.find('.g-toc-body');
			if (toc_parent.hasClass('g-enable-collapsible')) {
				if (toc_body.is(':visible')) {
					toc_body.slideUp();
					gThis.removeClass('g-collapsible-active');
				} else {
					gThis.addClass('g-collapsible-active');
					toc_body.slideDown();
				}
			}
			e.preventDefault();
		});

		/* Module Tab*/
		function moduleTabs(gThis) {
			let gThisIndex = gThis.index(),
				gThisWrap = gThis.closest('.gutentor-module-tabs-wrap'),
				gThisWrapID = gThisWrap.data('id'),
				gThisContentID = '.gm-tc-' + gThisWrapID;
			if (gThis.hasClass('gutentor-tabs-nav-active')) {
				return;
			}
			gThis.addClass('gutentor-tabs-nav-active');
			gThis.siblings().removeClass('gutentor-tabs-nav-active');
			gThisWrap
				.find(gThisContentID)
				.eq(gThisIndex)
				.siblings()
				.removeClass('gutentor-tabs-content-active');
			gThisWrap
				.find(gThisContentID)
				.eq(gThisIndex)
				.addClass('gutentor-tabs-content-active');

			/*Slick Fixed*/
			gRefreshJS(gThisWrap.find(gThisContentID).eq(gThisIndex));
		}
		gDocument.on('click', '.gutentor-module-tabs-item', function (e) {
			moduleTabs($(this));
			e.preventDefault();
		});
		gDocument.on('keypress', '.gutentor-module-tabs-item', function (e) {
			if (e.keyCode == 13) {
				moduleTabs($(this));
				e.preventDefault();
			}
		});

		/*Counter*/
		gDocument.on('click', '.gutentor-countup-wrap', function () {
			$(this).addClass('gutentor-countup-open');
		});
		gDocument.on('click', '.gutentor-countup-box-close', function () {
			$('.gutentor-countup-box').addClass('hide-input');
			$(this).hide();
		});
		gDocument.on('click', '.gutentor-countup', function () {
			$('.gutentor-countup-box').removeClass('hide-input');
		});

		// Gutentor Countdown
		$('.gutentor-countdown-wrapper').each(function () {
			gCountDown($(this));
		});

		// gutentor popover blocks
		gPopOver();

		/*Flex Menu*/
		gFlexMenu($('.g-responsive-menu'));

		/*Show more Block*/
		gShowMoreBlock('.gutentor-show-more-button');
		gShowMoreBlock('.gutentor-show-less-action-button');
		gShowMoreBlock('.gutentor-adv-s-m-btn');
		gShowMoreBlock('.gutentor-adv-s-l-btn');

		/*Acme Ticker*/
		if (typeof $.fn.AcmeTicker !== 'undefined') {
			$('.gutentor-post-module-p5').each(function () {
				gAcmeTicker($(this));
			});
			/*Pause fixed*/
			$(document).on('acmeTickerToggle', function (e, thisTicker) {
				$(thisTicker)
					.closest('.gutentor-news-ticker')
					.toggleClass('gutentor-ticker-pause');
			});
		}
		/*Tabs*/
		gTabs();

		/*edd add to cart*/
		$('.g-edd-cart').each(function () {
			gEddCartIcon($(this));
		});
	});

	/*Filter Module*/
	function gM11() {
		let buttonM11Filters = {},
			buttonM11Filter = {},
			m11Regex = {},
			m11FilWrap = $('.g-fm-module'),
			currentFilter;
		if (m11FilWrap.length) {
			m11FilWrap.find('.g-gm-item-wrap').isotope({
				itemSelector: '.g-gm-col',
				layoutMode: 'fitRows',
				filter: function () {
					let gThis = $(this),
						searchResult =
							currentFilter && m11Regex[currentFilter]
								? gThis.text().match(m11Regex[currentFilter])
								: true,
						buttonResult =
							currentFilter && buttonM11Filter[currentFilter]
								? gThis.is(buttonM11Filter[currentFilter])
								: true;

					return searchResult && buttonResult;
				},
			});
		}
		gDocument.on('click', '.g-fm-fi', function () {
			$(this).siblings().removeClass('g-fm-active');
			$(this).addClass('g-fm-active');

			let masonryBoxes = $(this).closest('.g-fm-module');
			currentFilter = masonryBoxes.attr('data-filter-number');
			let gThis = $(this);
			// get group key
			let btnGroup = gThis.parents('.g-fm-fis'),
				filterGroup = btnGroup.attr('data-filter-group');

			// set filter for group
			if (buttonM11Filters[currentFilter] === undefined) {
				buttonM11Filters[currentFilter] = {};
			}
			buttonM11Filters[currentFilter][filterGroup] = gThis.attr('data-filter');
			// combine filters
			if (buttonM11Filter[currentFilter] === undefined) {
				buttonM11Filter[currentFilter] = {};
			}
			buttonM11Filter[currentFilter] = gConcatValues(
				buttonM11Filters[currentFilter]
			);
			// Isotope arrange
			let this_grid_wrapper = gThis
				.closest('.g-fm-module')
				.find('.g-gm-item-wrap');
			this_grid_wrapper.isotope();
		});

		$('.g-gm-module')
			.find('.grid-container')
			.each(function () {
				let str = $('.g-nothing-found-text').text();
				str = str ? str : 'Nothing Found!!!!';
				let html = '<div class="g-nothing-found">' + str + '</div>';
				$(this).append(html);
				$('.g-nothing-found').hide();
			});

		//filter search
		function filterSearchEvent(thisData) {
			let masonryBoxes = thisData.closest('.g-fm-module');
			currentFilter = masonryBoxes.attr('data-filter-number');
			m11Regex[currentFilter] = new RegExp(thisData.val(), 'gi');
			let this_grid_wrapper = thisData
				.closest('.g-fm-module')
				.find('.g-gm-item-wrap');

			this_grid_wrapper.isotope();
			// display message box if no filtered items
			if (!this_grid_wrapper.data('isotope').filteredItems.length) {
				$('.g-nothing-found').show();
			} else {
				$('.g-nothing-found').hide();
			}
		}

		/* use value of search field to filter*/
		$('.g-fm-module .g-fm-search').keyup(
			gDebounce(function () {
				filterSearchEvent($(this));
			})
		);
		$('.g-fm-module .g-fm-search').on('search', function () {
			filterSearchEvent($(this));
		});

		// flatten object by concatting values
		function gConcatValues(obj) {
			let value = '';
			for (let prop in obj) {
				value += obj[prop];
			}
			return value;
		}

		// gDebounce so filtering doesn't happen every millisecond
		function gDebounce(fn, threshold) {
			let timeout;
			threshold = threshold || 100;
			return function gDebounced() {
				clearTimeout(timeout);
				let args = arguments;
				let _this = this;

				function delayed() {
					fn.apply(_this, args);
				}
				timeout = setTimeout(delayed, threshold);
			};
		}

		gDocument.find('.g-fm-module').each(function (i, item) {
			let thisFilterWrap = $(this);
			thisFilterWrap.attr('data-filter-number', i);
		});
		gDocument.find('.g-gm-module').each(function (i, item) {
			let thisFilterWrap = $(this);
			if (thisFilterWrap.hasClass('g-on-masonry')) {
				let container = thisFilterWrap.find('.g-gm-item-wrap');
				container.isotope({ layoutMode: 'masonry' });
			}
			let thisFilterWrapPopup = $(this);
			if (thisFilterWrapPopup.hasClass('g-on-popup')) {
				let container = thisFilterWrapPopup.find('.g-gm-item-wrap');
				if (typeof $.fn.imagesLoaded !== 'undefined') {
					container.imagesLoaded(function () {
						thisFilterWrapPopup.fadeIn('slow');
						container.masonry({
							itemSelector: '.g-gm-col ',
						});
					});
				}
			}
			let popupIcon = thisFilterWrapPopup.find('.g-gm-popup'),
				popupMediaType = popupIcon.attr('data-media-type');
			if (popupMediaType === 'g-popup-img') {
				gMagnificPopup(popupIcon, true, true);
			} else {
				gMagnificPopup(popupIcon, false, true);
			}
		});

		gM11Init = true;
	}

	/*Gallery Gutenberg Module Popup Link
	 * gMagnificPopup initilize above */
	gDocument.on('click', '.g-gm-popup', function (e) {
		e.preventDefault();
		if (typeof magnificPopup !== 'undefined') {
			$(this).magnificPopup.open();
		}
	});

	/*Gallery Wrapper Masonry*/
	function gGalleryMasonry(masonryBoxes) {
		if (masonryBoxes.hasClass('enable-masonry')) {
			let container = masonryBoxes.find('.full-width-row');

			container.imagesLoaded(function () {
				masonryBoxes.fadeIn('slow');
				container.masonry({
					itemSelector: '.gutentor-gallery-item',
				});
			});
		}
		gMagnificPopup(masonryBoxes.find('.image-gallery'), true, true);
	}

	function gPostModuleMasonry(masonryBoxes) {
		if (masonryBoxes.hasClass('g-masonry')) {
			let container = masonryBoxes.find('.grid-row');
			container.imagesLoaded(function () {
				masonryBoxes.fadeIn('slow');
				container.masonry({
					itemSelector: '.gutentor-post',
				});
			});
		}
	}

	function gReloadMasonry(masonryBoxes) {
		if (masonryBoxes.hasClass('g-masonry')) {
			let container = masonryBoxes.find('.grid-row');
			container.imagesLoaded(function () {
				container.masonry('destroy');
			});
		}
		gPostModuleMasonry(masonryBoxes);
	}

	/*Video Autoplay */
	function gVideoAutoplay() {
		let videos = document.getElementsByClassName('gutentor-bg-video'),
			i;
		for (i = 0; i < videos.length; i++) {
			if (videos[i].hasAttribute('autoplay')) {
				if (!videos[i].playing) {
					if (!videos[i].hasAttribute('muted')) {
						videos[i].muted = true;
					}
					videos[i].play();
				}
			}
		}
	}

	/*Filter*/
	function gFilter() {
		//Gutentor filter Box
		let buttonFilters = {},
			buttonFilter = {},
			qsRegex = {},
			filter_wrap = $('.gutentor-filter-item-wrap'),
			currentFilter;
		if (filter_wrap.length) {
			filter_wrap.isotope({
				itemSelector: '.gutentor-gallery-item',
				layoutMode: 'fitRows',
				filter: function () {
					let gThis = $(this);
					let searchResult =
						currentFilter && qsRegex[currentFilter]
							? gThis.text().match(qsRegex[currentFilter])
							: true;
					let buttonResult =
						currentFilter && buttonFilter[currentFilter]
							? gThis.is(buttonFilter[currentFilter])
							: true;
					return searchResult && buttonResult;
				},
			});
		}
		gDocument.on('click', '.gutentor-filter-btn', function () {
			$(this).siblings().removeClass('gutentor-filter-btn-active');
			$(this).addClass('gutentor-filter-btn-active');

			let masonryBoxes = $(this).closest('.gutentor-filter-wrapper');
			currentFilter = masonryBoxes.attr('data-filter-number');
			let gThis = $(this);
			// get group key
			let $buttonGroup = gThis.parents('.gutentor-filter-group'),
				filterGroup = $buttonGroup.attr('data-filter-group');

			// set filter for group
			if (buttonFilters[currentFilter] === undefined) {
				buttonFilters[currentFilter] = {};
			}
			buttonFilters[currentFilter][filterGroup] = gThis.attr('data-filter');
			// combine filters
			if (buttonFilter[currentFilter] === undefined) {
				buttonFilter[currentFilter] = {};
			}
			buttonFilter[currentFilter] = concatValues(buttonFilters[currentFilter]);
			// Isotope arrange
			let this_grid_wrapper = $(this)
				.closest('.gutentor-filter-container')
				.next('.gutentor-filter-item-wrap');
			this_grid_wrapper.isotope();
		});
		// use value of search field to filter
		$('.gutentor-search-filter').keyup(
			debounce(function () {
				let masonryBoxes = $(this).closest('.gutentor-filter-wrapper');
				currentFilter = masonryBoxes.attr('data-filter-number');
				qsRegex[currentFilter] = new RegExp($(this).val(), 'gi');
				let this_grid_wrapper = $(this)
					.closest('.gutentor-filter-container')
					.next('.gutentor-filter-item-wrap');

				this_grid_wrapper.isotope();
			})
		);

		// flatten object by concatting values
		function concatValues(obj) {
			let value = '';
			for (let prop in obj) {
				value += obj[prop];
			}
			return value;
		}

		// debounce so filtering doesn't happen every millisecond
		function debounce(fn, threshold) {
			let timeout;
			threshold = threshold || 100;
			return function debounced() {
				clearTimeout(timeout);
				let args = arguments;
				let _this = this;

				function delayed() {
					fn.apply(_this, args);
				}
				timeout = setTimeout(delayed, threshold);
			};
		}

		gDocument.find('.gutentor-filter-wrapper').each(function (i, item) {
			let thisFilterWrap = $(this);
			thisFilterWrap.attr('data-filter-number', i);
			gMagnificPopup(thisFilterWrap.find('.image-gallery'), true, true);

			let container = thisFilterWrap.find('.gutentor-filter-item-wrap');

			if (thisFilterWrap.hasClass('enable-masonry')) {
				container.isotope({ layoutMode: 'masonry' });
			}
		});
	}

	/*Sticky Sidebar*/
	function gTheiaStickySidebar(gThis) {
		let stickyChildren = gThis
				.find('.grid-row:first')
				.children('.gutentor-single-column'),
			mTop = gThis.attr('data-top'),
			mBottom = gThis.attr('data-bottom');

		stickyChildren.theiaStickySidebar({
			// Settings
			additionalMarginTop: parseInt(mTop),
			additionalMarginBottom: parseInt(mBottom),
		});
	}
	/*Window Load*/
	gWindow.on('load', function () {
		//Gutentor Gallery Box
		if (typeof $.fn.imagesLoaded !== 'undefined') {
			let galleryWrapper = $('.gutentor-gallery-wrapper');
			galleryWrapper.each(function () {
				gGalleryMasonry($(this));
			});
			let postModule = $('.gutentor-post-module-p1');
			postModule.each(function () {
				gPostModuleMasonry($(this));
			});
		}

		/*Filter*/
		gFilter();

		/*sticky sidebar*/
		if (typeof $.fn.theiaStickySidebar !== 'undefined') {
			$('.gutentor-enable-sticky-column').each(function () {
				gTheiaStickySidebar($(this));
			});
		}

		/*WooCommerce added cart*/

		$(document.body).on('added_to_cart', function (e, button) {
			setTimeout(function () {
				$('.gutentor-woo-add-to-cart .added_to_cart.wc-forward').addClass(
					'gutentor-button button gutentor-post-button'
				);
			}, 1);
		});

		if (typeof $.fn.isotope !== 'undefined') {
			/*m11 filter*/
			gM11();
		}

		/*Video Autoplay*/
		gVideoAutoplay();
	});
})(jQuery);
