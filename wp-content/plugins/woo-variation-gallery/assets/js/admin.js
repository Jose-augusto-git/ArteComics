/*!
 * Variation Gallery for WooCommerce
 *
 * Author: Emran Ahmed ( emran.bd.08@gmail.com )
 * Date: 9/7/2023, 4:56:22 PM
 * Released under the GPLv3 license.
 */
/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/WooVariationGalleryAdmin.js":
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "WooVariationGalleryAdmin": function() { return /* binding */ WooVariationGalleryAdmin; }
/* harmony export */ });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

/*global woo_variation_gallery_admin */
var WooVariationGalleryAdmin = function ($) {
  var WooVariationGalleryAdmin = /*#__PURE__*/function () {
    function WooVariationGalleryAdmin() {
      _classCallCheck(this, WooVariationGalleryAdmin);
    }

    _createClass(WooVariationGalleryAdmin, null, [{
      key: "GWPAdmin",
      value: function GWPAdmin() {
        if ($().gwp_deactivate_popup) {
          $().gwp_deactivate_popup('woo-variation-gallery');
        }
      }
    }, {
      key: "HandleDiv",
      value: function HandleDiv() {
        // Meta-Boxes - Open/close
        $(document.body).on('click', '.woo-variation-gallery-wrapper .handle-div', function () {
          $(this).closest('.woo-variation-gallery-postbox').toggleClass('closed');
          var ariaExpandedValue = !$(this).closest('.woo-variation-gallery-postbox').hasClass('closed');
          $(this).attr('aria-expanded', ariaExpandedValue);
        });
      }
    }, {
      key: "ImageUploader",
      value: function ImageUploader() {
        $(document).off('click', '.add-woo-variation-gallery-image');
        $(document).off('click', '.remove-woo-variation-gallery-image');
        $(document).on('click', '.add-woo-variation-gallery-image', this.AddImage);
        $(document).on('click', '.remove-woo-variation-gallery-image', this.RemoveImage);
        $('.woocommerce_variation').each(function () {
          var optionsWrapper = $(this).find('.options:first');
          var galleryWrapper = $(this).find('.woo-variation-gallery-wrapper');
          galleryWrapper.insertBefore(optionsWrapper);
        });
        $(document).trigger('woo_variation_gallery_admin_image_uploader_attached', this);
      }
    }, {
      key: "AddImage",
      value: function AddImage(event) {
        var _this = this;

        event.preventDefault();
        event.stopPropagation();
        var frame;
        var product_variation_id = $(this).data('product_variation_id');
        var loop = $(this).data('product_variation_loop');

        if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
          // If the media frame already exists, reopen it.
          if (frame) {
            frame.open();
            return;
          } // Create the media frame.


          frame = wp.media({
            title: woo_variation_gallery_admin.choose_image,
            button: {
              text: woo_variation_gallery_admin.add_image
            },

            /*states : [
                new wp.media.controller.Library({
                    title      : woo_variation_gallery_admin.choose_image,
                    filterable : 'all',
                    multiple   : 'add'
                })
            ],*/
            library: {
              type: ['image'] // [ 'video', 'image' ]

            } // multiple : true
            // multiple : 'add'

          }); // When an image is selected, run a callback.

          frame.on('select', function () {
            var images = frame.state().get('selection').toJSON();
            var html = images.map(function (image) {
              if (image.type === 'image') {
                var id = image.id,
                    _image$sizes = image.sizes;
                _image$sizes = _image$sizes === void 0 ? {} : _image$sizes;
                var thumbnail = _image$sizes.thumbnail,
                    full = _image$sizes.full;
                var url = thumbnail ? thumbnail.url : full.url;
                var template = wp.template('woo-variation-gallery-image');
                return template({
                  id: id,
                  url: url,
                  product_variation_id: product_variation_id,
                  loop: loop
                });
              }
            }).join('');
            $(_this).parent().prev().find('.woo-variation-gallery-images').append(html); // Variation Changed

            WooVariationGalleryAdmin.Sortable();
            WooVariationGalleryAdmin.VariationChanged(_this);

            _.delay(function () {
              WooVariationGalleryAdmin.ProNotice(_this);
            }, 5);
          }); // Finally, open the modal.

          frame.open();
        }
      }
    }, {
      key: "VariationChanged",
      value: function VariationChanged($el) {
        $($el).closest('.woocommerce_variation').addClass('variation-needs-update');
        $('button.cancel-variation-changes, button.save-variation-changes').removeAttr('disabled');
        $('#variable_product_options').trigger('woocommerce_variations_input_changed'); // Dokan Support

        $($el).closest('.dokan-product-variation-itmes').addClass('variation-needs-update');
        $('.dokan-product-variation-wrapper').trigger('dokan_variations_input_changed');
        $(document).trigger('woo_variation_gallery_admin_variation_changed', this);
      }
    }, {
      key: "ProNotice",
      value: function ProNotice($el) {
        var total = $($el).closest('.woo-variation-gallery-wrapper').find('.woo-variation-gallery-images > li').length;
        $($el).closest('.woo-variation-gallery-wrapper').find('.woo-variation-gallery-images > li').each(function (i, el) {
          if (i >= 2) {
            $(el).remove();
            $($el).closest('.woo-variation-gallery-wrapper').find('.woo-variation-gallery-pro-button').show();
          } else {
            $($el).closest('.woo-variation-gallery-wrapper').find('.woo-variation-gallery-pro-button').hide();
          }
        });
      }
    }, {
      key: "RemoveImage",
      value: function RemoveImage(event) {
        var _this2 = this;

        event.preventDefault();
        event.stopPropagation(); // Variation Changed

        WooVariationGalleryAdmin.VariationChanged(this);

        _.delay(function () {
          WooVariationGalleryAdmin.ProNotice(_this2);
          $(_this2).parent().remove();
        }, 1);
      }
    }, {
      key: "Sortable",
      value: function Sortable() {
        $('.woo-variation-gallery-images').sortable({
          items: 'li.image',
          cursor: 'move',
          scrollSensitivity: 40,
          forcePlaceholderSize: true,
          forceHelperSize: false,
          helper: 'clone',
          opacity: 0.65,
          placeholder: 'woo-variation-gallery-sortable-placeholder',
          start: function start(event, ui) {
            ui.item.css('background-color', '#F6F6F6');
          },
          stop: function stop(event, ui) {
            ui.item.removeAttr('style');
          },
          update: function update() {
            // Variation Changed
            WooVariationGalleryAdmin.VariationChanged(this);
          }
        });
      }
    }]);

    return WooVariationGalleryAdmin;
  }();

  return WooVariationGalleryAdmin;
}(jQuery);



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
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
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
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

function _getRequireWildcardCache(nodeInterop) { if (typeof WeakMap !== "function") return null; var cacheBabelInterop = new WeakMap(); var cacheNodeInterop = new WeakMap(); return (_getRequireWildcardCache = function _getRequireWildcardCache(nodeInterop) { return nodeInterop ? cacheNodeInterop : cacheBabelInterop; })(nodeInterop); }

function _interopRequireWildcard(obj, nodeInterop) { if (!nodeInterop && obj && obj.__esModule) { return obj; } if (obj === null || _typeof(obj) !== "object" && typeof obj !== "function") { return { "default": obj }; } var cache = _getRequireWildcardCache(nodeInterop); if (cache && cache.has(obj)) { return cache.get(obj); } var newObj = {}; var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor; for (var key in obj) { if (key !== "default" && Object.prototype.hasOwnProperty.call(obj, key)) { var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null; if (desc && (desc.get || desc.set)) { Object.defineProperty(newObj, key, desc); } else { newObj[key] = obj[key]; } } } newObj["default"] = obj; if (cache) { cache.set(obj, newObj); } return newObj; }

jQuery(function ($) {
  Promise.resolve().then(function () {
    return _interopRequireWildcard(__webpack_require__("./src/js/WooVariationGalleryAdmin.js"));
  }).then(function (_ref) {
    var WooVariationGalleryAdmin = _ref.WooVariationGalleryAdmin;
    // WooVariationGalleryAdmin.ImageUploader();
    // WooVariationGalleryAdmin.Sortable();
    // WooVariationGalleryAdmin.GWPAdmin();
    WooVariationGalleryAdmin.HandleDiv();
    WooVariationGalleryAdmin.ImageUploader();
    $('#woocommerce-product-data').on('woocommerce_variations_loaded', function () {
      WooVariationGalleryAdmin.ImageUploader();
      WooVariationGalleryAdmin.Sortable();
    });
    $('#variable_product_options').on('woocommerce_variations_added', function () {
      WooVariationGalleryAdmin.ImageUploader();
      WooVariationGalleryAdmin.Sortable(); // WooVariationGalleryAdmin.HandleDiv();
    }); // Dokan Pro Support

    $('.dokan-product-variation-wrapper').on('dokan_variations_loaded dokan_variations_added', function () {
      WooVariationGalleryAdmin.ImageUploader();
      WooVariationGalleryAdmin.Sortable(); //WooVariationGalleryAdmin.HandleDiv();
    });
    $(document).trigger('woo_variation_gallery_admin_loaded');
  });
}); // end of jquery main wrapper
}();
/******/ })()
;