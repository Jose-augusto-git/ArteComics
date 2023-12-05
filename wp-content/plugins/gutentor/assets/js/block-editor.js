const lodStaticCssOnHead = (staticCss) => {
	if (staticCss.id && staticCss.href) {
		let styleSelector = window.document;
		let iframes = document.getElementsByName('editor-canvas');
		if (iframes.length) {
			styleSelector = iframes[0].contentDocument;
		}
		if (styleSelector.getElementById(staticCss.id) === null) {
			let link = document.createElement('link');
			link.type = 'text/css';
			link.rel = 'stylesheet';
			link.id = staticCss.id;
			if (styleSelector.getElementsByTagName('head').length != 0) {
				styleSelector.getElementsByTagName('head')[0].appendChild(link);
				link.href = staticCss.href;
			} else {
				styleSelector.getElementsByTagName('body')[0].appendChild(link);
				link.href = staticCss.href;
			}
		} else {
			styleSelector.getElementById(staticCss.id).href = staticCss.href;
		}
	}
};

function EditorReady($, iframes = undefined) {
	let gutentorDocument = $(document);

	if (typeof iframes !== 'undefined' && iframes.length) {
		/*https://stackoverflow.com/questions/5992791/get-iframe-contents-with-a-jquery-selector*/
		gutentorDocument = $('iframe[name="editor-canvas"]').contents();
	}

	// bind filter button click
	$('.gutentor-filter-group').on(
		'click',
		'.gutentor-filter-inside',
		function () {
			var filterValue = $(this).attr('data-filter');
			$grid.isotope({ filter: filterValue });
		}
	);

	//close single Item data
	gutentorDocument.on(
		'click',
		'.gutentor-single-item-close-action,.block-editor-block-contextual-toolbar-wrapper',
		function () {
			var this_button = $(this),
				this_column = this_button.closest('.gutentor-column-inside');

			this_column.removeClass('single-item-active');
			this_column
				.closest('.gutentor-section')
				.removeClass('hide-gutentor-controls-actions');
			//$('.edit-post-layout__content').css('overflow-y','');
			$(
				'.block-editor-editor-skeleton__sidebar, .interface-interface-skeleton__sidebar'
			).css({
				'z-index': '',
			});
		}
	);

	gutentorDocument.click(function (e) {
		let popoverContent = gutentorDocument.find(
			'.gutentor-single-item-edit-actions'
		);

		if (
			!$(e.target).closest('.gutentor-single-item-edit-actions').length &&
			popoverContent.is(':visible')
		) {
			if (
				!(
					$(e.target).hasClass('dashicons-admin-generic') &&
					$(e.target).parent().hasClass('gutentor-single-item-action-button')
				) &&
				!(
					$(e.target).hasClass('g-icon-picker-single-btn') ||
					$(e.target).parent().hasClass('g-icon-picker-single-btn')
				)
			) {
				if (popoverContent.find('.g-popover-open ').length) {
					return false;
				}
				$('.gutentor-single-item-close-action').trigger('click');
			}
		}
	});

	//Image Slider single item options show/hide
	gutentorDocument.on(
		'click',
		'.gutentor-single-item-action-button',
		function () {
			var this_button = $(this),
				this_column = this_button.closest('.gutentor-column-inside'),
				popoverContent = this_column.find('.gutentor-single-item-edit-actions'),
				leftPos =
					this_button[0].getBoundingClientRect().left +
					$(window)['scrollLeft']();

			$('.gutentor-column-inside').removeClass('single-item-active');
			$('.gutentor-section').removeClass('hide-gutentor-controls-actions');

			/*Position Fixed*/
			let css = {};
			if (leftPos < 310) {
				css.right = '';
				css.left = 0;
				popoverContent.addClass('g-single-item-popup-left');
			} else {
				popoverContent.removeClass('g-single-item-popup-left');
			}
			popoverContent.css(css);

			this_column.addClass('single-item-active');
			this_column
				.closest('.gutentor-section')
				.addClass('hide-gutentor-controls-actions');
		}
	);

	//social Icon single item options show/hide
	gutentorDocument.on(
		'click',
		'.gutentor-social-single-item-button',
		function () {
			var this_button = $(this),
				this_column = this_button.closest('.gutentor-social-item-inside');

			// hide already opened single item
			$('.gutentor-column-inside').removeClass('single-item-active');
			$('.gutentor-section').removeClass('hide-gutentor-controls-actions');
			$('.gutentor-social-item-inside').removeClass(
				'social-single-item-active'
			);
			this_button
				.closest('.gutentor-column-inside')
				.addClass('social-active-control-action');

			this_column.addClass('social-single-item-active');
			this_column
				.closest('.gutentor-section')
				.addClass('hide-gutentor-controls-actions');
			//$('.edit-post-layout__content').css('overflow-y','visible');
			$(
				'.block-editor-editor-skeleton__sidebar, .interface-interface-skeleton__sidebar'
			).css({
				'z-index': '-1',
			});
		}
	);

	// social Icon close single Item
	gutentorDocument.on(
		'click',
		'.gutentor-social-single-item-close-action',
		function () {
			var this_button = $(this),
				this_column = this_button.closest('.gutentor-social-item-inside');

			this_button
				.closest('.gutentor-column-inside')
				.removeClass('social-active-control-action');

			this_column.removeClass('social-single-item-active');
			this_column
				.closest('.gutentor-section')
				.removeClass('hide-gutentor-controls-actions');
			//$('.edit-post-layout__content').css('overflow-y','');
			$(
				'.block-editor-editor-skeleton__sidebar, .interface-interface-skeleton__sidebar'
			).css({
				'z-index': '',
			});
		}
	);

	// video popup
	gutentorDocument.on(
		'click',
		'.gutentor-video-popup-holder, .g-v-btn, .g-v-fp-btn ',
		function () {
			$(this).magnificPopup({
				disableOn: 700,
				type: 'iframe',
				mainClass: 'mfp-fade',
				removalDelay: 160,
				preloader: false,
				fixedContentPos: false,
			});
		}
	);

	/**
	 * Call Down
	 *
	 * @param {array} sectionID
	 * @return {string}
	 */
	let setCountdownInterval = null;

	function count_down(sectionID) {
		if (setCountdownInterval) {
			clearInterval(setCountdownInterval);
		}

		var gutentor_this = gutentorDocument
			.find('#' + sectionID)
			.find('.gutentor-countdown-wrapper');

		// Set the date we're counting down to
		var gutentor_event_date = gutentor_this.attr('data-eventdate');
		if (gutentor_event_date === undefined || gutentor_event_date === null) {
			if (!gutentor_this.children('.gutentor-count-down-invalid-msg').length) {
				gutentor_this.append(
					"<span class='gutentor-count-down-invalid-msg'>Please set validate Date and time for countdown </span>"
				);
			}
			gutentor_this.children().addClass('hidden');
			gutentor_this
				.children('.gutentor-count-down-invalid-msg')
				.removeClass('hidden');

			return false;
		}
		gutentor_this.children().removeClass('hidden');
		gutentor_this.children('.gutentor-count-down-invalid-msg').remove();
		var expired_text = gutentor_this.attr('data-expiredtext'),
			gutentor_day = gutentor_this.find('.day'),
			gutentor_hour = gutentor_this.find('.hour'),
			gutentor_min = gutentor_this.find('.min'),
			gutentor_sec = gutentor_this.find('.sec'),
			gutentor_date_time = gutentor_event_date.split('T');
		if (gutentor_date_time.length !== 2) {
			return false;
		}
		var date_collection = gutentor_date_time[0],
			time_collection = gutentor_date_time[1],
			date_explode = date_collection.split('-');

		if (date_explode.length !== 3) {
			return false;
		}

		var time_explode = time_collection.split(':');
		if (time_explode.length !== 3) {
			return false;
		}

		var gutentor_year_value = parseInt(date_explode[0]),
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
		setCountdownInterval = setInterval(function () {
			// Get todays date and time
			var now = new Date().getTime();

			// Find the distance between now an the count down date
			var distance = countDownDate - now;

			// Time calculations for days, hours, minutes and seconds
			var days = Math.floor(distance / (1000 * 60 * 60 * 24));
			var hours = Math.floor(
				(distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
			);
			var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			var seconds = Math.floor((distance % (1000 * 60)) / 1000);

			// Display the result in the element
			gutentor_day.html(days);
			gutentor_hour.html(hours);
			gutentor_min.html(minutes);
			gutentor_sec.html(seconds);
			// If the count down is finished, write some text
			if (distance < 0) {
				clearInterval(setCountdownInterval);
				gutentor_this.children().addClass('hidden');
				gutentor_this.append(
					"<span class='gutentor-count-down-expire'>" + expired_text + '</span>'
				);
			} else {
				gutentor_this.children().removeClass('hidden');
				gutentor_this.children('.gutentor-count-down-expire').remove();
			}
		}, 1000);
	}

	// Call count Down
	gutentorDocument.on('click', '.gutentor-countdown-start', function () {
		let sectionID = $(this).attr('data-sectionid');
		count_down(sectionID);
	});

	/*google font load on head*/
	gutentorDocument.on(
		'change',
		'.gutentor-font-type select, .gutentor-google-font select, .gutentor-font-weight select',
		function () {
			let thisTypography = $(this).closest('.gutentor-typography-main-wrap');
			/*give small time to change font weight*/
			setTimeout(function () {
				let gutentorFontTypeWrap = thisTypography.find('.gutentor-font-type'),
					gutentorFontType = gutentorFontTypeWrap.find('select').val();

				if (!thisTypography.attr('id')) {
					thisTypography.uniqueId();
				}
				let uniqueID = thisTypography.attr('id');

				if ('google' === gutentorFontType) {
					let gutentorFontFamilyWrap = thisTypography.find(
							'.gutentor-google-font'
						),
						gutentorIsFontFamily = gutentorFontFamilyWrap.find('select').val()
							? gutentorFontFamilyWrap.find('select').val()
							: 'Acme',
						gutentorFontFamilyVal =
							gutentorIsFontFamily === 'default'
								? 'Acme'
								: gutentorIsFontFamily,
						gutentorFontFamily = gutentorFontFamilyVal.replace(' ', '+'),
						gutentorFontWeightWrap = thisTypography.find(
							'.gutentor-font-weight'
						),
						gutentorFontWeight = gutentorFontWeightWrap.find('select').val();

					gutentorFontFamily = gutentorFontFamily + ':' + gutentorFontWeight;
					gutentorFontFamily = gutentorFontFamily.replace('italic', 'i');
					gutentorFontFamily = gutentorFontFamily.replace('default', 'regular');

					let url = '//fonts.googleapis.com/css';
					url += '?family=' + gutentorFontFamily;

					lodStaticCssOnHead({
						id: '#gutentor-google-' + uniqueID,
						href: url,
					});
				}
			}, 300);
		}
	);

	$('.g-html-preview').each(function () {
		const targetElement = $(this)[0];

		const observer = new MutationObserver(function (mutationsList, observer) {
			mutationsList.forEach(function (mutation) {
				let elem = $(mutation.target);

				if (elem.children().length > 0) {
					let cHeight = elem.children().eq(0).outerHeight();
					let pHeight = elem.outerHeight();
					elem.removeClass('g-child-greater-height g-child-smaller-height');

					if (cHeight > pHeight) {
						elem.addClass('g-child-greater-height');
					} else {
						elem.addClass('g-child-smaller-height');
					}
				}
			});
		});

		const observerConfig = {
			childList: true,
			subtree: true,
		};

		observer.observe(targetElement, observerConfig);
	});

	// filter module
	gutentorDocument.on(
		'click',
		'.g-filter-col-inspectors .css-10nd86i',
		function () {
			$(this)
				.closest('.g-filter-col-inspectors')
				.toggleClass('g-filter-selected');
		}
	);
}

(function ($) {
	function preEditor() {
		if (window.location.href.indexOf('site-editor.php') > -1) {
			let blockLoaded = false;
			let blockLoadedInterval = setInterval(function () {
				let iframes = $('iframe[name="editor-canvas"]');
				if (iframes.length) {
					/*post-title-0 is ID of Post Title Textarea*/
					//Actual functions goes here
					EditorReady($, iframes);

					blockLoaded = true;
				}
				if (blockLoaded) {
					clearInterval(blockLoadedInterval);
				}
			}, 500);
		} else {
			EditorReady($);
		}
	}
	preEditor();

	/*https://stackoverflow.com/questions/47917596/listener-event-for-when-url-parameters-change*/
	(function (history) {
		const pushState = history.pushState;
		history.pushState = function (state) {
			if (typeof history.onpushstate == 'function') {
				history.onpushstate({ state: state });
			}
			// Call your custom function here
			preEditor();
			return pushState.apply(history, arguments);
		};
	})(window.history);
})(jQuery);
