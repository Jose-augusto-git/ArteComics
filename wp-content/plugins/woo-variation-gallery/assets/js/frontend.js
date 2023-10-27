/*!
 * Variation Gallery for WooCommerce
 *
 * Author: Emran Ahmed ( emran.bd.08@gmail.com )
 * Date: 9/7/2023, 4:56:22 PM
 * Released under the GPLv3 license.
 */
/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/WooVariationGallery.js":
/***/ (function() {

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

// ================================================================
// WooCommerce Variation Gallery
// ================================================================

/*global wc_add_to_cart_variation_params, woo_variation_gallery_options */
;

(function (window) {
  'use strict';

  var Plugin = function ($) {
    return /*#__PURE__*/function () {
      function _class2(element, options, name) {
        _classCallCheck(this, _class2);

        _defineProperty(this, "defaults", {});

        // Assign
        this.name = name;
        this.element = element; // this._element = $(element)

        this.$element = $(element);
        this.settings = $.extend(true, {}, this.defaults, options); //this.$product             = this.$element.closest('.product');
        // let wrapper               = woo_variation_gallery_options.wrapper || '.product';

        this.$wrapper = this.$element.closest('.product');
        this.$variations_form = this.$wrapper.find('.variations_form');
        this.$attributeFields = this.$variations_form.find('.variations select');
        this.$target = this.$element.parent();
        this.$slider = $('.woo-variation-gallery-slider', this.$element);
        this.$thumbnail = $('.woo-variation-gallery-thumbnail-slider', this.$element);
        this.thumbnail_columns = this.$element.data('thumbnail_columns');
        this.product_id = this.$variations_form.data('product_id');
        this.is_variation_product = this.$variations_form.length > 0;
        this.initial_load = true; // Temp variable

        this.is_vertical = !!woo_variation_gallery_options.is_vertical; // Call

        this.$element.addClass('wvg-loaded');
        this.defaultDimension();
        this.defaultGallery();

        if (!!woo_variation_gallery_options.enable_gallery_preload) {
          this.initVariationImagePreload();
        }

        this.initEvents();
        this.initVariationGallery();

        if (!this.is_variation_product) {
          this.imagesLoaded();
        }

        if (this.is_variation_product) {
          this.initSlick();
          this.initZoom();
          this.initPhotoswipe();
        }

        $(document).trigger('woo_variation_gallery_loaded', [this]);
      }

      _createClass(_class2, [{
        key: "init",
        value: function init() {
          var _this = this;

          return _.debounce(function () {
            _this.initSlick();

            _this.initZoom();

            _this.initPhotoswipe();
          }, 500);
        }
      }, {
        key: "getChosenAttributes",
        value: function getChosenAttributes() {
          var data = {};
          var count = 0;
          var chosen = 0;
          this.$attributeFields.each(function () {
            var attribute_name = $(this).data('attribute_name') || $(this).attr('name');
            var value = $(this).val() || '';

            if (value.length > 0) {
              chosen++;
            }

            count++;
            data[attribute_name] = value;
          });
          return {
            'count': count,
            'chosenCount': chosen,
            'data': data
          };
        }
      }, {
        key: "defaultDimension",
        value: function defaultDimension() {
          var _this2 = this;

          // console.log(this.$element.height(), this.$element.width());
          this.$element.css('min-height', this.$element.height()).css('min-width', this.$element.width());
          $(window).on('resize.wvg', _.debounce(function (event) {
            if (event.originalEvent) {
              _this2.$element.css('min-height', _this2.$element.height()).css('min-width', _this2.$element.width());
            }
          }, 300));
          $(window).on('resize.wvg', _.debounce(function (event) {
            if (event.originalEvent) {
              _this2.$element.css('min-height', '').css('min-width', '');
            }
          }, 100, {
            'leading': true,
            'trailing': false
          }));
        }
      }, {
        key: "dimension",
        value: function dimension() {//this.$element.css('min-height', '0px');
          //this.$element.css('min-width', '0px');
          //return _.debounce(() => {
          //this.$element.css('min-height', this.$slider.height() + 'px');
          //this.$element.css('min-width', this.$slider.width() + 'px');
          //}, 400);
        }
      }, {
        key: "initEvents",
        value: function initEvents() {
          var _this3 = this;

          this.$element.on('woo_variation_gallery_slider_slick_init', function (event, gallery) {
            if (woo_variation_gallery_options.is_vertical) {
              //$(window).off('resize.wvg');
              $(window).on('resize', _this3.enableThumbnailPositionDebounce()); //$(window).on('resize', this.thumbnailHeightDebounce());
              //this.$slider.on('setPosition', this.enableThumbnailPositionDebounce());

              _this3.$slider.on('setPosition', _this3.thumbnailHeightDebounce());

              _this3.$slider.on('afterChange', function () {
                _this3.thumbnailHeight();
              });
            }

            if (woo_variation_gallery_options.enable_thumbnail_slide) {
              var thumbnails = _this3.$thumbnail.find('.wvg-gallery-thumbnail-image').length;

              if (parseInt(woo_variation_gallery_options.gallery_thumbnails_columns) < thumbnails) {
                _this3.$thumbnail.find('.wvg-gallery-thumbnail-image').removeClass('current-thumbnail');

                _this3.initThumbnailSlick();
              } else {
                _this3.$slider.slick('slickSetOption', 'asNavFor', null, false);
              }
            }
          });
          this.$element.on('woo_variation_gallery_slick_destroy', function (event, gallery) {
            if (_this3.$thumbnail.hasClass('slick-initialized')) {
              _this3.$thumbnail.slick('unslick');
            }
          });
          this.$element.on('woo_variation_gallery_image_loaded', this.init());
        }
      }, {
        key: "initSlick",
        value: function initSlick() {
          var _this4 = this;

          if (this.$slider.is('.slick-initialized')) {
            this.$slider.slick('unslick');
          }

          this.$slider.off('init');
          this.$slider.off('beforeChange');
          this.$slider.off('afterChange');
          this.$element.trigger('woo_variation_gallery_before_init', [this]); // Slider

          this.$slider.on('init', function (event) {
            if (_this4.initial_load) {
              _this4.initial_load = false; // this.$element.css('min-height', this.$slider.height() + 'px');
              //_.delay(() => {
              //    this.$slider.slick('setPosition');
              //}, 2000)
            }
          }).on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            // this.stopVideo(this.$slider)
            _this4.$thumbnail.find('.wvg-gallery-thumbnail-image').not('.slick-slide').removeClass('current-thumbnail');

            _this4.$thumbnail.find('.wvg-gallery-thumbnail-image').not('.slick-slide').eq(nextSlide).addClass('current-thumbnail');
          }).on('afterChange', function (event, slick, currentSlide) {
            _this4.stopVideo(_this4.$slider);

            _this4.initZoomForTarget(currentSlide);
          }).slick(); // Thumbnails

          this.$thumbnail.find('.wvg-gallery-thumbnail-image').not('.slick-slide').first().addClass('current-thumbnail');
          this.$thumbnail.find('.wvg-gallery-thumbnail-image').not('.slick-slide').each(function (index, el) {
            $(el).find('div, img').on('click', function (event) {
              event.preventDefault();
              event.stopPropagation();

              _this4.$slider.slick('slickGoTo', index);
            });
          });

          _.delay(function () {
            _this4.$element.trigger('woo_variation_gallery_slider_slick_init', [_this4]);
          }, 1);

          _.delay(function () {
            // console.log(this.$element.height(), this.$element.width());
            //    this.$element.css('min-height', this.$element.height())
            //    this.$element.css('min-width', this.$element.width())
            _this4.removeLoadingClass();
          }, 100);
        }
      }, {
        key: "initZoomForTarget",
        value: function initZoomForTarget(currentSlide) {
          if (!woo_variation_gallery_options.enable_gallery_zoom) {
            return;
          }

          var galleryWidth = parseInt(this.$target.width()),
              zoomEnabled = false,
              zoomTarget = this.$slider.slick('getSlick').$slides.eq(currentSlide);
          $(zoomTarget).each(function (index, target) {
            var image = $(target).find('img');

            if (parseInt(image.data('large_image_width')) > galleryWidth) {
              zoomEnabled = true;
              return false;
            }
          }); // If zoom not included.

          if (!$().zoom) {
            return;
          } // But only zoom if the img is larger than its container.


          if (zoomEnabled) {
            var zoom_options = $.extend({
              touch: false
            }, wc_single_product_params.zoom_options);

            if ('ontouchstart' in document.documentElement) {
              zoom_options.on = 'click';
            }

            zoomTarget.trigger('zoom.destroy');
            zoomTarget.zoom(zoom_options);
          }
        }
      }, {
        key: "initZoom",
        value: function initZoom() {
          var currentSlide = this.$slider.slick('slickCurrentSlide');
          this.initZoomForTarget(currentSlide);
        }
      }, {
        key: "initPhotoswipe",
        value: function initPhotoswipe() {
          var _this5 = this;

          if (!woo_variation_gallery_options.enable_gallery_lightbox) {
            return;
          }

          this.$element.off('click', '.woo-variation-gallery-trigger');
          this.$element.off('click', '.wvg-gallery-image a');
          this.$element.on('click', '.woo-variation-gallery-trigger', function (event) {
            _this5.openPhotoswipe(event);
          });
          this.$element.on('click', '.wvg-gallery-image a', function (event) {
            _this5.openPhotoswipe(event);
          });
        }
      }, {
        key: "openPhotoswipe",
        value: function openPhotoswipe(event) {
          var _this6 = this;

          event.preventDefault();

          if (typeof PhotoSwipe === 'undefined') {
            return false;
          }

          var pswpElement = $('.pswp')[0],
              items = this.getGalleryItems();
          var options = $.extend({
            index: this.$slider.slick('slickCurrentSlide')
          }, wc_single_product_params.photoswipe_options); // Initializes and opens PhotoSwipe.

          var photoswipe = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options); // Gallery starts closing

          photoswipe.listen('close', function () {
            _this6.stopVideo(pswpElement);
          });
          photoswipe.listen('afterChange', function () {
            _this6.stopVideo(pswpElement);
          });
          photoswipe.init();
        }
      }, {
        key: "stopVideo",
        value: function stopVideo(element) {
          $(element).find('iframe, video').each(function () {
            var tag = $(this).prop('tagName').toLowerCase();
            var player = $(this)[0];

            if (tag === 'video') {
              player.pause();
            }

            if (tag === 'iframe') {
              // Safari 16.4 fix
              if (player['contentWindow'] === null) {
                return;
              }

              var src = $(this).attr('src');
              var ytcommand = {
                'event': 'command',
                'func': 'pauseVideo',
                'args': ''
              };
              var vcommand = {
                'method': 'pause',
                'value': 'true'
              }; // https://gist.github.com/nickchauhan/0cf83516379ac7d545698af40b3d6ece

              var url = new URL(src);
              var searchParams = new URLSearchParams(url.search);
              player.contentWindow.postMessage(JSON.stringify(ytcommand), '*'); // Vimeo Video running on background

              if (!searchParams.has('background')) {
                player.contentWindow.postMessage(JSON.stringify(vcommand), '*');
              } //   $(this).attr('src', src);

            }
          });
        }
      }, {
        key: "addLoadingClass",
        value: function addLoadingClass() {
          if (woo_variation_gallery_options.preloader_disable) {
            return true;
          }

          this.$element.addClass('loading-gallery');
        }
      }, {
        key: "removeLoadingClass",
        value: function removeLoadingClass() {
          this.$element.removeClass('loading-gallery');
        }
      }, {
        key: "getGalleryItems",
        value: function getGalleryItems() {
          var $slides = this.$slider.slick('getSlick').$slides,
              items = [];

          if ($slides.length > 0) {
            $slides.each(function (i, el) {
              var img = $(el).find('img, iframe, video');
              var tag = $(img).prop('tagName').toLowerCase();
              var src, item;

              switch (tag) {
                case 'img':
                  var large_image_src = img.attr('data-large_image'),
                      large_image_w = img.attr('data-large_image_width'),
                      large_image_h = img.attr('data-large_image_height');
                  item = {
                    src: large_image_src,
                    w: large_image_w,
                    h: large_image_h,
                    title: img.attr('data-caption') ? img.attr('data-caption') : img.attr('title')
                  };
                  break;

                case 'iframe':
                  src = img.attr('src');
                  item = {
                    html: "<iframe loading=\"lazy\" class=\"wvg-lightbox-iframe\" src=\"".concat(src, "\" style=\"width: 100%; height: 100%; margin: 0;padding: 0; background-color: #000000\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>")
                  };
                  break;

                case 'video':
                  src = img.attr('src');
                  item = {
                    html: "<video preload=\"auto\" class=\"wvg-lightbox-video\" disablePictureInPicture controls controlsList=\"nodownload\" src=\"".concat(src, "\" style=\"width: 100%; height: 100%; margin: 0;padding: 0; background-color: #000000\"></video>")
                  };
                  break;
              }

              items.push(item);
            });
          }

          return items;
        }
      }, {
        key: "destroySlick",
        value: function destroySlick() {
          this.$slider.html('');
          this.$thumbnail.html('');

          if (this.$slider.is('.slick-initialized')) {
            this.$slider.slick('unslick');
          }

          this.$element.trigger('woo_variation_gallery_slick_destroy', [this]);
        }
      }, {
        key: "defaultGallery",
        value: function defaultGallery() {
          var _this7 = this;

          if (this.is_variation_product) {
            if (this.$element.defaultXHR) {
              this.$element.defaultXHR.abort();
            }

            this.$element.defaultXHR = $.ajax({
              global: false,

              /*headers : {
              'Cache-Control' : 'max-age=86400',
              'Pragma'        : 'cache'  //  backwards compatibility with HTTP/1.0 caches
              },
              cache   : true,*/
              url: wc_add_to_cart_variation_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_default_gallery'),
              //type    : 'GET',
              method: 'POST',
              data: {
                product_id: this.product_id
              },
              success: function success(data) {
                if (data) {
                  _this7.$element.data('woo_variation_gallery_default', data);

                  _this7.$element.trigger('woo_variation_default_gallery_loaded', [_this7, data]);
                } else {
                  _this7.$element.data('woo_variation_gallery_default', []);

                  _this7.$element.trigger('woo_variation_default_gallery_loaded', [_this7, []]);

                  console.error("Variation Gallery not available on variation id ".concat(_this7.product_id, "."));
                }
              }
            });
          }
        }
      }, {
        key: "initVariationImagePreload",
        value: function initVariationImagePreload() {
          var _this8 = this;

          //return;
          if (this.is_variation_product) {
            if (this.$element.imagesXHR) {
              this.$element.imagesXHR.abort();
            }

            this.$element.defaultXHR = $.ajax({
              global: false,
              url: wc_add_to_cart_variation_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_variation_gallery'),
              method: 'POST',
              data: {
                product_id: this.product_id
              },
              success: function success(images) {
                if (images) {
                  if (images.length > 1) {
                    _this8.imagePreload(images);
                  }

                  _this8.$element.data('woo_variation_gallery_variation_images', images);

                  _this8.$element.trigger('woo_variation_gallery_variation_images', [_this8, images]);
                } else {
                  _this8.$element.data('woo_variation_gallery_variation_images', []);

                  console.error("Variation Gallery variations images not available on variation id ".concat(_this8.product_id, "."));
                }
              }
            });
          }
        }
      }, {
        key: "imagePreload",
        value: function imagePreload(images) {
          for (var i = 0; i < images.length; i++) {
            try {
              // Note: this won't work when chrome devtool is open and 'disable cache' is enabled within the network panel
              var _img = new Image();

              var _gallery = new Image();

              var _full = new Image();

              var _thumbnail = new Image();

              _img.src = images[i].src;

              if (images[i].srcset) {
                _img.srcset = images[i].srcset;
              }

              _gallery.src = images[i].gallery_thumbnail_src;
              _full.src = images[i].full_src;
              _thumbnail.src = images[i].archive_src;
              var video_link = $.trim(images[i].video_link);

              if (video_link && images[i].video_embed_type === 'video') {
                var req = new XMLHttpRequest();
                req.open('GET', video_link, true);
                req.responseType = 'blob';

                req.onload = function () {
                  // Onload is triggered even on 404
                  // so we need to check the status code
                  if (this.status === 200) {
                    var videoBlob = this.response;
                    var vid = URL.createObjectURL(videoBlob); // IE10+
                    // Video is now downloaded
                    // and we can set it as source on the video element
                    // video.src = vid;
                  }
                };

                req.onerror = function () {// Error
                };

                req.send();
              } // Append Content

              /*let _img_src    = images[i].src;
              let _img_srcset = images[i].srcset;
               let _gallery_src   = images[i].gallery_thumbnail_src;
              let _full_src      = images[i].full_src;
              let _thumbnail_src = images[i].archive_src;
               let template = `<div style="display: none"><img aria-hidden="true" style="display: none" src="${_img_src}" /><img style="display: none" src="${_gallery_src}" /><img style="display: none" src="${_thumbnail_src}" /><img style="display: none" src="${_full_src}" /></div>`;
               if (_img_srcset) {
                  template = `<div style="display: none"><img aria-hidden="true" style="display: none" src="${_img_src}" srcset="${_img_srcset}" /><img style="display: none" src="${_gallery_src}" /><img style="display: none" src="${_thumbnail_src}" /><img style="display: none" src="${_full_src}" /></div>`;
              }
               // let template = `<div style="display: none"><img aria-hidden="true" style="display: none" src="${_img_src}" srcset="${_img_srcset}" /><img style="display: none" src="${_gallery_src}" /><img style="display: none" src="${_thumbnail_src}" /><img style="display: none" src="${_full_src}" /></div>`;
              $('body').append(template)*/

            } catch (e) {
              console.error(e);
            }
          }
        }
      }, {
        key: "showVariationImage",
        value: function showVariationImage(variation) {
          if (variation) {
            this.addLoadingClass();
            this.galleryInit(variation.variation_gallery_images || []);
          }
        }
      }, {
        key: "resetVariationImage",
        value: function resetVariationImage() {
          if (!this.$element.is('.loading-gallery')) {
            this.addLoadingClass();
            this.galleryReset();
          }
        }
      }, {
        key: "initVariationGallery",
        value: function initVariationGallery() {
          var _this9 = this;

          // show_variation, found_variation
          this.$variations_form.off('reset_image.wvg');
          this.$variations_form.off('click.wvg', '.reset_variations');
          this.$variations_form.off('show_variation.wvg');
          this.$variations_form.off('hide_variation.wvg'); // this.$variations_form.off('found_variation.wvg');
          // Show Gallery
          // console.log(this.$variations_form)

          this.$variations_form.on('show_variation.wvg', function (event, variation) {
            _this9.showVariationImage(variation);
          });

          if (woo_variation_gallery_options.gallery_reset_on_variation_change) {
            this.$variations_form.on('hide_variation.wvg', function () {
              _this9.resetVariationImage();
            });
          } else {
            this.$variations_form.on('click.wvg', '.reset_variations', function () {
              _this9.resetVariationImage();
            });
          }
        }
      }, {
        key: "galleryReset",
        value: function galleryReset() {
          var _this10 = this;

          var $default_gallery = this.$element.data('woo_variation_gallery_default');

          if ($default_gallery && $default_gallery.length > 0) {
            this.galleryInit($default_gallery);
          } else {
            _.delay(function () {
              _this10.removeLoadingClass();
            }, 100);
          }
        }
      }, {
        key: "galleryInit",
        value: function galleryInit(images) {
          var _this11 = this;

          var hasGallery = images.length > 1;
          this.$element.trigger('before_woo_variation_gallery_init', [this, images]);
          this.destroySlick();
          var slider_inner_html = images.map(function (image) {
            var template = wp.template('woo-variation-gallery-slider-template');
            return template(image);
          }).join('');
          var thumbnail_inner_html = images.map(function (image) {
            var template = wp.template('woo-variation-gallery-thumbnail-template');
            return template(image);
          }).join('');

          if (hasGallery) {
            this.$target.addClass('woo-variation-gallery-has-product-thumbnail');
            this.$target.removeClass('woo-variation-gallery-no-product-thumbnail');
          } else {
            this.$target.addClass('woo-variation-gallery-no-product-thumbnail');
            this.$target.removeClass('woo-variation-gallery-has-product-thumbnail');
          }

          this.$slider.html(slider_inner_html);

          if (hasGallery) {
            this.$thumbnail.html(thumbnail_inner_html);
          } else {
            this.$thumbnail.html('');
          } //this.$element.trigger('woo_variation_gallery_init', [this, images]);


          _.delay(function () {
            _this11.imagesLoaded();
          }, 1); //this.$element.trigger('after_woo_variation_gallery_init', [this, images]);

        }
      }, {
        key: "imagesLoaded",
        value: function imagesLoaded() {
          var _this12 = this;

          // Some Script Add Custom imagesLoaded Function
          if (!$().imagesLoaded.done) {
            this.$element.trigger('woo_variation_gallery_image_loading', [this]);
            this.$element.trigger('woo_variation_gallery_image_loaded', [this]);
            return;
          }

          this.$element.imagesLoaded().progress(function (instance, image) {
            _this12.$element.trigger('woo_variation_gallery_image_loading', [_this12]);
          }).done(function (instance) {
            _this12.$element.trigger('woo_variation_gallery_image_loaded', [_this12]);
          });
        }
      }, {
        key: "initThumbnailSlick",
        value: function initThumbnailSlick() {
          var _this13 = this;

          if (this.$thumbnail.hasClass('slick-initialized')) {
            this.$thumbnail.slick('unslick');
          }

          this.$thumbnail.off('init');
          this.$thumbnail.on('init', function () {}).slick();

          _.delay(function () {
            _this13.$element.trigger('woo_variation_gallery_thumbnail_slick_init', [_this13]);
          }, 1);
        }
      }, {
        key: "thumbnailHeight",
        value: function thumbnailHeight() {
          //console.log('thumbnailHeight...')
          if (this.is_vertical) {
            if (this.$slider.slick('getSlick').$slides.length > 1) {
              this.$thumbnail.height(this.$slider.height());
            } else {
              this.$thumbnail.height(0);
            }
          } else {
            this.$thumbnail.height('auto');
          }

          if (this.$thumbnail.hasClass('slick-initialized')) {
            this.$thumbnail.slick('setPosition');
          }
        }
      }, {
        key: "thumbnailHeightDebounce",
        value: function thumbnailHeightDebounce(event) {
          var _this14 = this;

          return _.debounce(function () {
            _this14.thumbnailHeight();
          }, 401);
        }
      }, {
        key: "enableThumbnailPosition",
        value: function enableThumbnailPosition() {
          if (!woo_variation_gallery_options.is_mobile) {//    return;
          }

          if (woo_variation_gallery_options.is_vertical) {
            //console.log('enableThumbnailPosition...')
            if (window.matchMedia('(max-width: 768px)').matches || window.matchMedia('(max-width: 480px)').matches) {
              this.is_vertical = false;
              this.$element.removeClass("".concat(woo_variation_gallery_options.thumbnail_position_class_prefix, "left ").concat(woo_variation_gallery_options.thumbnail_position_class_prefix, "right ").concat(woo_variation_gallery_options.thumbnail_position_class_prefix, "bottom"));
              this.$element.addClass("".concat(woo_variation_gallery_options.thumbnail_position_class_prefix, "bottom"));
              this.$slider.slick('setPosition');
            } else {
              this.is_vertical = true;
              this.$element.removeClass("".concat(woo_variation_gallery_options.thumbnail_position_class_prefix, "left ").concat(woo_variation_gallery_options.thumbnail_position_class_prefix, "right ").concat(woo_variation_gallery_options.thumbnail_position_class_prefix, "bottom"));
              this.$element.addClass("".concat(woo_variation_gallery_options.thumbnail_position_class_prefix).concat(woo_variation_gallery_options.thumbnail_position));
              this.$slider.slick('setPosition');
            }
          }
        }
      }, {
        key: "enableThumbnailPositionDebounce",
        value: function enableThumbnailPositionDebounce(event) {
          var _this15 = this;

          return _.debounce(function () {
            _this15.enableThumbnailPosition();
          }, 400);
        }
      }, {
        key: "destroy",
        value: function destroy() {
          this.$element.removeData(this.name);
        }
      }]);

      return _class2;
    }();
  }(jQuery);

  var jQueryPlugin = function ($) {
    return function (PluginName, ClassName) {
      $.fn[PluginName] = function (options) {
        var _this16 = this;

        for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
          args[_key - 1] = arguments[_key];
        }

        return this.each(function (index, element) {
          var $element = $(element);
          var data = $element.data(PluginName);

          if (!data) {
            data = new ClassName($element, $.extend({}, options), PluginName);
            $element.data(PluginName, data);
          }

          if (typeof options === 'string') {
            if (_typeof(data[options]) === 'object') {
              return data[options];
            }

            if (typeof data[options] === 'function') {
              var _data;

              return (_data = data)[options].apply(_data, args);
            }
          }

          return _this16;
        });
      }; // Constructor


      $.fn[PluginName].Constructor = ClassName; // Short Hand

      $[PluginName] = function (options) {
        var _$;

        for (var _len2 = arguments.length, args = new Array(_len2 > 1 ? _len2 - 1 : 0), _key2 = 1; _key2 < _len2; _key2++) {
          args[_key2 - 1] = arguments[_key2];
        }

        return (_$ = $({}))[PluginName].apply(_$, [options].concat(args));
      }; // No Conflict


      $.fn[PluginName].noConflict = function () {
        return $.fn[PluginName];
      };
    };
  }(jQuery);

  jQueryPlugin('WooVariationGallery', Plugin);
})(window);

/***/ }),

/***/ "./src/js/frontend.js":
/***/ (function() {

jQuery(function ($) {
  try {
    $(document).on('woo_variation_gallery_init', function () {
      $('.woo-variation-gallery-wrapper:not(.wvg-loaded)').WooVariationGallery();
    }) // For Single Product
    .trigger('woo_variation_gallery_init');
  } catch (err) {
    // If failed (conflict?) log the error but don't stop other scripts breaking.
    window.console.log(err);
  } // Ajax and Variation Product


  $(document).on('wc_variation_form', '.variations_form', function () {
    $(document).trigger('woo_variation_gallery_init');
  }); // YITH QuickView

  $(document).on('qv_loader_stop', function () {
    $('.woo-variation-gallery-wrapper:not(.woo-variation-gallery-product-type-variable):not(.wvg-loaded)').WooVariationGallery();
  }); // Elementor

  if (window.elementorFrontend && window.elementorFrontend.hooks) {
    elementorFrontend.hooks.addAction('frontend/element_ready/woocommerce-product-images.default', function ($scope) {
      $(document).trigger('woo_variation_gallery_init');
    });
  }
}); // end of jquery main wrapper

/***/ }),

/***/ "./src/scss/frontend.scss":
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/scss/slider.scss":
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/scss/backend.scss":
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	!function() {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = function(result, chunkIds, fn, priority) {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every(function(key) { return __webpack_require__.O[key](chunkIds[j]); })) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	!function() {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/assets/js/frontend": 0,
/******/ 			"assets/css/admin": 0,
/******/ 			"assets/css/slick": 0,
/******/ 			"assets/css/frontend": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = function(chunkId) { return installedChunks[chunkId] === 0; };
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = function(parentChunkLoadingFunction, data) {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some(function(id) { return installedChunks[id] !== 0; })) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkwoo_variation_gallery"] = self["webpackChunkwoo_variation_gallery"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin","assets/css/slick","assets/css/frontend"], function() { return __webpack_require__("./src/js/WooVariationGallery.js"); })
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin","assets/css/slick","assets/css/frontend"], function() { return __webpack_require__("./src/js/frontend.js"); })
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin","assets/css/slick","assets/css/frontend"], function() { return __webpack_require__("./src/scss/frontend.scss"); })
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin","assets/css/slick","assets/css/frontend"], function() { return __webpack_require__("./src/scss/slider.scss"); })
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/css/admin","assets/css/slick","assets/css/frontend"], function() { return __webpack_require__("./src/scss/backend.scss"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;