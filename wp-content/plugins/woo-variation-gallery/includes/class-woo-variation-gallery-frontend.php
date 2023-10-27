<?php

defined( 'ABSPATH' ) or die( 'Keep Silent' );


if ( ! class_exists( 'Woo_Variation_Gallery_Frontend' ) ):

	class Woo_Variation_Gallery_Frontend {

		protected static $_instance = null;

		protected function __construct() {
			$this->includes();
			$this->hooks();
			$this->init();
			do_action( 'woo_variation_gallery_frontend_loaded', $this );
		}

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		protected function includes() {
			require_once dirname( __FILE__ ) . '/class-woo-variation-gallery-compatibility.php';
		}

		protected function hooks() {
			add_filter( 'body_class', array( $this, 'body_class' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_filter( 'woocommerce_post_class', array( $this, 'post_class' ), 25, 2 );
			add_filter( 'woocommerce_available_variation', array( $this, 'get_available_variation_gallery' ), 90, 3 );

			add_action( 'wc_ajax_get_default_gallery', array( $this, 'get_default_gallery' ) );
			add_action( 'wc_ajax_get_variation_gallery', array( $this, 'get_variation_gallery' ) );

			add_filter( 'disable_woo_variation_gallery', array( $this, 'disable_for_specific_product_type' ), 9 );
			add_filter( 'woo_variation_product_gallery_inline_style', array( $this, 'gallery_inline_style' ) );

			add_action( 'after_setup_theme', array( $this, 'enable_theme_support' ), 200 );
			add_action( 'wp_footer', array( $this, 'slider_template_js' ) );

			add_filter( 'wc_get_template', array( $this, 'gallery_template' ), 30, 2 );
			add_filter( 'wc_get_template_part', array( $this, 'gallery_template_part' ), 30, 2 );
		}

		protected function init() {
			Woo_Variation_Gallery_Compatibility::instance();
		}

		// Start

		public function remove_default_template() {
			remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 10 );
			remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

			// remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
			// remove_action( 'woocommerce_before_single_product_summary_product_images', 'woocommerce_show_product_thumbnails', 20 );
		}

		public function get_product_default_attributes( $product_id ) {

			$product = wc_get_product( $product_id );

			if ( ! $product->is_type( 'variable' ) ) {
				return array();
			}

			$variable_product = new WC_Product_Variable( absint( $product_id ) );

			// $selected = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( wp_unslash( $_REQUEST[ $selected_key ] ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] );

			$selected_attributes = array();
			$default_attributes  = $variable_product->get_default_attributes();
			$attributes          = $variable_product->get_attributes();
			foreach ( $attributes as $attribute_name => $attribute_data ) {
				$selected_key = wc_variation_attribute_name( $attribute_name );
				if ( isset( $_REQUEST[ $selected_key ] ) ) {
					$selected_attributes[ sanitize_title( $attribute_name ) ] = wc_clean( wp_unslash( $_REQUEST[ $selected_key ] ) );
				}
			}

			return empty( $selected_attributes ) ? $default_attributes : $selected_attributes;
		}

		public function get_product_default_variation_id( $product, $attributes ) {

			if ( is_numeric( $product ) ) {
				$product = wc_get_product( $product );
			}

			if ( ! $product->is_type( 'variable' ) ) {
				return 0;
			}

			$product_id = $product->get_id();

			foreach ( $attributes as $key => $value ) {
				if ( strpos( $key, 'attribute_' ) === 0 ) {
					continue;
				}

				unset( $attributes[ $key ] );
				$attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
			}

			$data_store = WC_Data_Store::load( 'product' );

			return $data_store->find_matching_product_variation( $product, $attributes );
		}

		public function enable_theme_support() {
			// WooCommerce.
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			$this->gallery_thumbnail_image_width();
		}

		public function gallery_thumbnail_image_width() {
			// Set from gallery settings
			$thumbnail_width = absint( woo_variation_gallery()->get_option( 'thumbnail_width', 100 ) );
			if ( $thumbnail_width > 0 ) {
				add_theme_support( 'woocommerce', array( 'gallery_thumbnail_image_width' => absint( $thumbnail_width ) ) );
			}
		}

		public function post_class( $classes, $product ) {

			$classes[] = 'woo-variation-gallery-product';

			return $classes;
		}

		public function body_class( $classes ) {

			$classes[] = 'woo-variation-gallery';
			$classes[] = sprintf( 'woo-variation-gallery-theme-%s', strtolower( basename( get_template_directory() ) ) );

			if ( is_rtl() ) {
				$classes[] = 'woo-variation-gallery-rtl';
			}

			if ( woo_variation_gallery()->is_pro() ) {
				$classes[] = 'woo-variation-gallery-pro';
			}

			return array_unique( array_values( $classes ) );
		}

		public function enqueue_scripts() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Disable gallery on scripts

			if ( apply_filters( 'disable_woo_variation_gallery', false ) ) {
				return false;
			}


			$single_image_width     = absint( wc_get_theme_support( 'single_image_width', get_option( 'woocommerce_single_image_width', 600 ) ) );
			$gallery_thumbnails_gap = absint( woo_variation_gallery()->get_option( 'thumbnails_gap', apply_filters( 'woo_variation_gallery_default_thumbnails_gap', 0 ), 'woo_variation_gallery_thumbnails_gap' ) );
			$gallery_width          = absint( woo_variation_gallery()->get_option( 'width', apply_filters( 'woo_variation_gallery_default_width', 30 ), 'woo_variation_gallery_width' ) );
			$gallery_margin         = absint( woo_variation_gallery()->get_option( 'margin', apply_filters( 'woo_variation_gallery_default_margin', 30 ), 'woo_variation_gallery_margin' ) );

			$gallery_medium_device_width      = absint( woo_variation_gallery()->get_option( 'medium_device_width', apply_filters( 'woo_variation_gallery_medium_device_width', 0 ), 'woo_variation_gallery_medium_device_width' ) );
			$gallery_small_device_width       = absint( woo_variation_gallery()->get_option( 'small_device_width', apply_filters( 'woo_variation_gallery_small_device_width', 720 ), 'woo_variation_gallery_small_device_width' ) );
			$gallery_extra_small_device_width = absint( woo_variation_gallery()->get_option( 'extra_small_device_width', apply_filters( 'woo_variation_gallery_extra_small_device_width', 320 ), 'woo_variation_gallery_extra_small_device_width' ) );
			$thumbnail_position               = sanitize_text_field( woo_variation_gallery()->get_option( 'position', 'bottom', 'woo_variation_gallery_thumbnail_position' ) );

			$is_defer = is_wp_version_compatible( '6.3' ) ? array( 'strategy' => 'defer' ) : true;

			wp_enqueue_script( 'woo-variation-gallery-slider', esc_url( woo_variation_gallery()->assets_url( "/js/slick{$suffix}.js" ) ), array( 'jquery' ), '1.8.1', $is_defer );

			wp_enqueue_style( 'woo-variation-gallery-slider', esc_url( woo_variation_gallery()->assets_url( "/css/slick{$suffix}.css" ) ), array(), '1.8.1' );

			wp_enqueue_script( 'woo-variation-gallery', esc_url( woo_variation_gallery()->assets_url( "/js/frontend{$suffix}.js" ) ), array(
				'jquery',
				'wp-util',
				'woo-variation-gallery-slider',
				'imagesloaded',
				'wc-add-to-cart-variation'
			), woo_variation_gallery()->assets_version( "/js/frontend{$suffix}.js" ), $is_defer );

			wp_localize_script( 'woo-variation-gallery', 'woo_variation_gallery_options', apply_filters( 'woo_variation_gallery_js_options', array(
				'gallery_reset_on_variation_change' => wc_string_to_bool( woo_variation_gallery()->get_option( 'reset_on_variation_change', 'no', 'woo_variation_gallery_reset_on_variation_change' ) ),
				'enable_gallery_zoom'               => wc_string_to_bool( woo_variation_gallery()->get_option( 'zoom', 'yes', 'woo_variation_gallery_zoom' ) ),
				'enable_gallery_lightbox'           => wc_string_to_bool( woo_variation_gallery()->get_option( 'lightbox', 'yes', 'woo_variation_gallery_lightbox' ) ),
				'enable_gallery_preload'            => wc_string_to_bool( woo_variation_gallery()->get_option( 'image_preload', 'yes', 'woo_variation_gallery_image_preload' ) ),
				'preloader_disable'                 => wc_string_to_bool( woo_variation_gallery()->get_option( 'preloader_disable', 'no', 'woo_variation_gallery_preloader_disable' ) ),
				'enable_thumbnail_slide'            => wc_string_to_bool( woo_variation_gallery()->get_option( 'thumbnail_slide', 'yes', 'woo_variation_gallery_thumbnail_slide' ) ),
				'gallery_thumbnails_columns'        => absint( woo_variation_gallery()->get_option( 'thumbnails_columns', apply_filters( 'woo_variation_gallery_default_thumbnails_columns', 4 ), 'woo_variation_gallery_thumbnails_columns' ) ),
				'is_vertical'                       => in_array( $thumbnail_position, array( 'left', 'right' ) ),
				'thumbnail_position'                => trim( $thumbnail_position ),
				'thumbnail_position_class_prefix'   => 'woo-variation-gallery-thumbnail-position-',
				// 'wrapper'                           => sanitize_text_field( get_option( 'woo_variation_gallery_and_variation_wrapper', apply_filters( 'woo_variation_gallery_and_variation_default_wrapper', '.product' ) ) ),
				'is_mobile'                         => wp_is_mobile(),
				'gallery_default_device_width'      => $gallery_width,
				'gallery_medium_device_width'       => $gallery_medium_device_width,
				'gallery_small_device_width'        => $gallery_small_device_width,
				'gallery_extra_small_device_width'  => $gallery_extra_small_device_width,

			) ) );

			// Stylesheet
			wp_enqueue_style( 'woo-variation-gallery', esc_url( woo_variation_gallery()->assets_url( "/css/frontend{$suffix}.css" ) ), array( 'dashicons' ), woo_variation_gallery()->assets_version( "/css/frontend{$suffix}.css" ) );

			$this->add_inline_style();

			do_action( 'woo_variation_gallery_enqueue_scripts', $this );
		}

		public function add_inline_style() {

			if ( apply_filters( 'disable_woo_variation_gallery', false ) ) {
				return false;
			}

			$single_image_width = absint( wc_get_theme_support( 'single_image_width', get_option( 'woocommerce_single_image_width', 600 ) ) );

			$gallery_thumbnails_columns = absint( woo_variation_gallery()->get_option( 'thumbnails_columns', 4 ) );

			$gallery_thumbnails_gap = absint( woo_variation_gallery()->get_option( 'thumbnails_gap', apply_filters( 'woo_variation_gallery_default_thumbnails_gap', 0 ), 'woo_variation_gallery_thumbnails_gap' ) );
			$gallery_width          = absint( woo_variation_gallery()->get_option( 'width', apply_filters( 'woo_variation_gallery_default_width', 30 ), 'woo_variation_gallery_width' ) );
			$gallery_margin         = absint( woo_variation_gallery()->get_option( 'margin', apply_filters( 'woo_variation_gallery_default_margin', 30 ), 'woo_variation_gallery_margin' ) );

			$gallery_medium_device_width      = absint( woo_variation_gallery()->get_option( 'medium_device_width', apply_filters( 'woo_variation_gallery_medium_device_width', 0 ), 'woo_variation_gallery_medium_device_width' ) );
			$gallery_small_device_width       = absint( woo_variation_gallery()->get_option( 'small_device_width', apply_filters( 'woo_variation_gallery_small_device_width', 720 ), 'woo_variation_gallery_small_device_width' ) );
			$gallery_small_device_clear_float = wc_string_to_bool( woo_variation_gallery()->get_option( 'small_device_clear_float', apply_filters( 'woo_variation_gallery_small_device_clear_float', 'no' ), 'woo_variation_gallery_small_device_clear_float' ) );


			$gallery_extra_small_device_width       = absint( woo_variation_gallery()->get_option( 'extra_small_device_width', apply_filters( 'woo_variation_gallery_extra_small_device_width', 320 ), 'woo_variation_gallery_extra_small_device_width' ) );
			$gallery_extra_small_device_clear_float = wc_string_to_bool( woo_variation_gallery()->get_option( 'extra_small_device_clear_float', apply_filters( 'woo_variation_gallery_extra_small_device_clear_float', 'no' ), 'woo_variation_gallery_extra_small_device_clear_float' ) );


			ob_start();
			include_once dirname( __FILE__ ) . '/stylesheet.php';
			$css = ob_get_clean();
			$css = $this->clean_css( $css );

			$css = apply_filters( 'woo_variation_gallery_inline_style', $css );

			wp_add_inline_style( 'woo-variation-gallery', $css );
		}

		public function clean_css( $inline_css ) {

			$inline_css = str_ireplace( array(
				'<style type="text/css">',
				'<style>',
				'</style>',
				"\r\n",
				"\r",
				"\n",
				"\t"
			), '', $inline_css );
			// Normalize whitespace
			$inline_css = preg_replace( "/\s+/", ' ', $inline_css );

			return trim( $inline_css );
		}

		public function wpml_object_id( $object_id, $type = 'post', $language = null ) {
			$current_language = apply_filters( 'wpml_current_language', $language );

			return apply_filters( 'wpml_object_id', $object_id, $type, true, $current_language );
		}


		public function get_gallery_image_ids( $variation_id ) {

			$images = get_post_meta( $variation_id, 'woo_variation_gallery_images', true );

			if ( empty( $images ) ) {
				return array();
			}

			return array_map( array( $this, 'wpml_object_id' ), (array) $images );
		}

		public function get_available_variation_gallery( $available_variation, $variationProductObject, $variation ) {

			$product_id         = absint( $variation->get_parent_id() );
			$variation_id       = absint( $variation->get_id() );
			$variation_image_id = absint( $variation->get_image_id() );
			$gallery_images     = $this->get_gallery_image_ids( $variation_id );

			// $has_variation_gallery_images = (bool) get_post_meta( $variation_id, 'woo_variation_gallery_images', true );

			$has_variation_gallery_images = count( $gallery_images ) > 0;

			//  $product                      = wc_get_product( $product_id );

			if ( $has_variation_gallery_images ) {
				// $gallery_images = (array) get_post_meta( $variation_id, 'woo_variation_gallery_images', true );
				$gallery_images = (array) $this->get_gallery_image_ids( $variation_id );
			} else {
				// $gallery_images = $product->get_gallery_image_ids();
				$gallery_images = $variationProductObject->get_gallery_image_ids();
			}


			if ( $variation_image_id ) {
				// Add Variation Default Image
				array_unshift( $gallery_images, $variation_image_id );
			} else {
				// Add Product Default Image

				/*if ( has_post_thumbnail( $product_id ) ) {
					array_unshift( $gallery_images, get_post_thumbnail_id( $product_id ) );
				}*/
				$parent_product          = wc_get_product( $product_id );
				$parent_product_image_id = 0;
				if ( $parent_product ) {
					$parent_product_image_id = $parent_product->get_image_id();
				}

				$placeholder_image_id = get_option( 'woocommerce_placeholder_image', 0 );

				if ( ! empty( $parent_product_image_id ) ) {
					array_unshift( $gallery_images, $parent_product_image_id );
				} else {
					array_unshift( $gallery_images, $placeholder_image_id );
				}
			}

			$available_variation['variation_gallery_images'] = array();

			foreach ( $gallery_images as $i => $variation_gallery_image_id ) {
				$available_variation['variation_gallery_images'][ $i ] = $this->get_product_attachment_props( $variation_gallery_image_id );
			}

			return apply_filters( 'woo_variation_gallery_available_variation_gallery', $available_variation, $variation, $product_id );
		}

		//-------------------------------------------------------------------------------
		// Gallery Template
		// Copy of: wc_get_product_attachment_props( $attachment_id = null, $product = false )
		//-------------------------------------------------------------------------------

		public function get_product_attachment_props( $attachment_id, $product_id = false ) {
			$props      = array(
				'image_id'                => '',
				'title'                   => '',
				'caption'                 => '',
				'url'                     => '',
				'alt'                     => '',
				'full_src'                => '',
				'full_src_w'              => '',
				'full_src_h'              => '',
				'full_class'              => '',
				//'full_srcset'              => '',
				//'full_sizes'               => '',
				'gallery_thumbnail_src'   => '',
				'gallery_thumbnail_src_w' => '',
				'gallery_thumbnail_src_h' => '',
				'gallery_thumbnail_class' => '',
				//'gallery_thumbnail_srcset' => '',
				//'gallery_thumbnail_sizes'  => '',
				'archive_src'             => '',
				'archive_src_w'           => '',
				'archive_src_h'           => '',
				'archive_class'           => '',
				//'archive_srcset'           => '',
				//'archive_sizes'            => '',
				'src'                     => '',
				'class'                   => '',
				'src_w'                   => '',
				'src_h'                   => '',
				'srcset'                  => '',
				'sizes'                   => '',
			);
			$attachment = get_post( $attachment_id );

			if ( $attachment ) {

				$props['image_id'] = $attachment_id;
				$props['title']    = _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true );
				$props['caption']  = _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true );
				$props['url']      = wp_get_attachment_url( $attachment_id );

				// Alt text.
				$alt_text = array(
					trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
					$props['caption'],
					wp_strip_all_tags( $attachment->post_title )
				);

				if ( $product_id ) {
					$product    = wc_get_product( $product_id );
					$alt_text[] = wp_strip_all_tags( get_the_title( $product->get_id() ) );
				}

				$alt_text     = array_filter( $alt_text );
				$props['alt'] = $alt_text[0] ?? '';

				// Large version.
				$full_size           = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
				$full_size_src       = wp_get_attachment_image_src( $attachment_id, $full_size );
				$props['full_src']   = esc_url( $full_size_src[0] ?? '' );
				$props['full_src_w'] = esc_attr( $full_size_src[1] ?? '' );
				$props['full_src_h'] = esc_attr( $full_size_src[2] ?? '' );

				$full_size_class = $full_size;
				if ( is_array( $full_size_class ) ) {
					$full_size_class = implode( 'x', $full_size_class );
				}

				$props['full_class'] = "attachment-$full_size_class size-$full_size_class";
				//$props[ 'full_srcset' ] = wp_get_attachment_image_srcset( $attachment_id, $full_size );
				//$props[ 'full_sizes' ]  = wp_get_attachment_image_sizes( $attachment_id, $full_size );


				// Gallery thumbnail.
				$gallery_thumbnail                = wc_get_image_size( 'gallery_thumbnail' );
				$gallery_thumbnail_size           = apply_filters( 'woocommerce_gallery_thumbnail_size', array(
					$gallery_thumbnail['width'],
					$gallery_thumbnail['height']
				) );
				$gallery_thumbnail_src            = wp_get_attachment_image_src( $attachment_id, $gallery_thumbnail_size );
				$props['gallery_thumbnail_src']   = esc_url( $gallery_thumbnail_src[0] ?? '' );
				$props['gallery_thumbnail_src_w'] = esc_attr( $gallery_thumbnail_src[1] ?? '' );
				$props['gallery_thumbnail_src_h'] = esc_attr( $gallery_thumbnail_src[2] ?? '' );

				$gallery_thumbnail_class = $gallery_thumbnail_size;
				if ( is_array( $gallery_thumbnail_class ) ) {
					$gallery_thumbnail_class = implode( 'x', $gallery_thumbnail_class );
				}

				$props['gallery_thumbnail_class'] = "attachment-$gallery_thumbnail_class size-$gallery_thumbnail_class";
				//$props[ 'gallery_thumbnail_srcset' ] = wp_get_attachment_image_srcset( $attachment_id, $gallery_thumbnail );
				//$props[ 'gallery_thumbnail_sizes' ]  = wp_get_attachment_image_sizes( $attachment_id, $gallery_thumbnail );


				// Archive/Shop Page version.
				$thumbnail_size         = apply_filters( 'woocommerce_thumbnail_size', 'woocommerce_thumbnail' );
				$thumbnail_size_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
				$props['archive_src']   = esc_url( $thumbnail_size_src[0] ?? '' );
				$props['archive_src_w'] = esc_attr( $thumbnail_size_src[1] ?? '' );
				$props['archive_src_h'] = esc_attr( $thumbnail_size_src[2] ?? '' );

				$archive_thumbnail_class = $thumbnail_size;
				if ( is_array( $archive_thumbnail_class ) ) {
					$archive_thumbnail_class = implode( 'x', $archive_thumbnail_class );
				}

				$props['archive_class'] = "attachment-$archive_thumbnail_class size-$archive_thumbnail_class";
				//$props[ 'archive_srcset' ] = wp_get_attachment_image_srcset( $attachment_id, $thumbnail_size );
				//$props[ 'archive_sizes' ]  = wp_get_attachment_image_sizes( $attachment_id, $thumbnail_size );


				// Image source.
				$image_size     = apply_filters( 'woocommerce_gallery_image_size', 'woocommerce_single' );
				$src            = wp_get_attachment_image_src( $attachment_id, $image_size );
				$props['src']   = esc_url( $src[0] ?? '' );
				$props['src_w'] = esc_attr( $src[1] ?? '' );
				$props['src_h'] = esc_attr( $src[2] ?? '' );

				$image_size_class = $image_size;
				if ( is_array( $image_size_class ) ) {
					$image_size_class = implode( 'x', $image_size_class );
				}
				$props['class']  = "wp-post-image wvg-post-image attachment-$image_size_class size-$image_size_class ";
				$props['srcset'] = wp_get_attachment_image_srcset( $attachment_id, $image_size );
				$props['sizes']  = wp_get_attachment_image_sizes( $attachment_id, $image_size );

				$props['extra_params'] = wc_implode_html_attributes( apply_filters( 'woo_variation_gallery_image_extra_params', array(), $props, $attachment_id, $product_id ) );

			}

			return apply_filters( 'woo_variation_gallery_get_image_props', $props, $attachment_id, $product_id );
		}

		public function get_video_info( $url ) {

			$videos = array(
				'type'      => false,
				'id'        => 0,
				'url'       => $url,
				'embed_url' => $url
			);

			$youtube_hosts = array( 'www.youtube.com', 'youtube.com', 'youtu.be', 'www.youtu.be' );
			$vimeo_hosts   = array( 'vimeo.com', 'www.vimeo.com', 'player.vimeo.com' );

			$_url  = wp_parse_url( esc_url( $url ) );
			$_args = wp_parse_args( $_url['query'] ?? array() );

			if ( ! $_url || ! isset( $_url['host'] ) ) {
				return $videos;
			}

			// Youtube
			if ( in_array( $_url['host'], $youtube_hosts ) ) {


				$id = str_ireplace( array( '/shorts/', '/embed/', '/' ), '', $_url['path'] );


				// Old Watch Path
				if ( stripos( $_url['path'], 'watch' ) === 1 ) {
					wp_parse_str( $_url['query'], $query );
					$id = $query ? $query['v'] : false;
				}

				$args = wp_parse_args( array(
					'feature'     => 'oembed',
					'enablejsapi' => '1',
					'controls'    => '0'
				), $_args );

				return wp_parse_args( array(
					'type'      => $id ? 'youtube' : false,
					'id'        => $id,
					'embed_url' => add_query_arg( $args, sprintf( 'https://www.youtube.com/embed/%s', $id ) )
				), $videos );


				// Shorts or embed video
				//if ( stripos( $url['path'], 'shorts' ) === 1 || stripos( $url['path'], 'embed' ) === 1 ) {
				//}
			}

			// Vimeo
			if ( in_array( $_url['host'], $vimeo_hosts ) ) {

				$id = str_ireplace( array( '/video/', '/' ), '', $_url['path'] );

				$args = wp_parse_args( array(
					'api' => '1',
					// 'background' => '1'
				), $_args );

				return wp_parse_args( array(
					'type'      => $id ? 'vimeo' : false,
					'id'        => $id,
					'embed_url' => add_query_arg( $args, sprintf( 'https://player.vimeo.com/video/%s', $id ) )
				), $videos );
			}

			return $videos;
		}

		public function get_embed_url( $main_link ) {

			$video_info = $this->get_video_info( $main_link );

			return apply_filters( 'woo_variation_gallery_get_embed_url', $video_info['embed_url'], $video_info );
		}

		public function get_gallery_image_html( $product, $attachment_id, $options = array() ) {

			$defaults = array( 'is_main_thumbnail' => false, 'has_only_thumbnail' => false );
			$options  = wp_parse_args( $options, $defaults );

			$image             = $this->get_product_attachment_props( $attachment_id );
			$post_thumbnail_id = $product->get_image_id();

			$remove_featured_image = wc_string_to_bool( woo_variation_gallery()->get_option( 'remove_featured_image', 'no' ) );

			if ( $remove_featured_image && absint( $attachment_id ) == absint( $post_thumbnail_id ) ) {
				return '';
			}

			$classes = array( 'wvg-gallery-image' );
			if ( isset( $image['video_link'] ) && ! empty( $image['video_link'] ) ) {
				array_push( $classes, 'wvg-gallery-video-slider' );
			}
			$classes = apply_filters( 'woo_variation_gallery_slider_image_html_class', $classes, $attachment_id, $image );


			$template = '<div class="wvg-single-gallery-image-container"><img loading="lazy" width="%d" height="%d" src="%s" class="%s" alt="%s" title="%s" data-caption="%s" data-src="%s" data-large_image="%s" data-large_image_width="%d" data-large_image_height="%d" srcset="%s" sizes="%s" %s /></div>';

			$inner_html = sprintf( $template, esc_attr( $image['src_w'] ), esc_attr( $image['src_h'] ), esc_url( $image['src'] ), esc_attr( $image['class'] ), esc_attr( $image['alt'] ), esc_attr( $image['title'] ), esc_attr( $image['caption'] ), esc_url( $image['full_src'] ), esc_url( $image['full_src'] ), esc_attr( $image['full_src_w'] ), esc_attr( $image['full_src_h'] ), esc_attr( $image['srcset'] ), esc_attr( $image['sizes'] ), $image['extra_params'] );

			if ( ! $options['has_only_thumbnail'] ) {
				if ( isset( $image['video_link'] ) && ! empty( $image['video_link'] ) && $image['video_embed_type'] === 'iframe' ) {
					$template   = '<div class="wvg-single-gallery-iframe-container" style="padding-bottom: %d%%"><iframe src="%s" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
					$inner_html = sprintf( $template, $image['video_ratio'], $image['video_embed_url'] );
				}

				if ( isset( $image['video_link'] ) && ! empty( $image['video_link'] ) && $image['video_embed_type'] === 'video' ) {
					$template   = '<div class="wvg-single-gallery-video-container" style="padding-bottom: %d%%"><video preload="auto" controls controlsList="nodownload" src="%s"></video></div>';
					$inner_html = sprintf( $template, $image['video_ratio'], $image['video_link'] );
				}
			}

			$inner_html = apply_filters( 'woo_variation_gallery_image_inner_html', $inner_html, $image, $template, $attachment_id, $options );

			// If require thumbnail
			if ( ! $options['is_main_thumbnail'] ) {

				$classes = array( 'wvg-gallery-thumbnail-image' );

				if ( isset( $image['video_link'] ) && ! empty( $image['video_link'] ) ) {
					array_push( $classes, 'wvg-gallery-video-thumbnail' );
				}

				$classes = apply_filters( 'woo_variation_gallery_thumbnail_image_html_class', $classes, $attachment_id, $image );

				$template   = '<img width="%d" height="%d" src="%s" class="%s" alt="%s" title="%s" />';
				$inner_html = sprintf( $template, esc_attr( $image['gallery_thumbnail_src_w'] ), esc_attr( $image['gallery_thumbnail_src_h'] ), esc_url( $image['gallery_thumbnail_src'] ), esc_attr( $image['gallery_thumbnail_class'] ), esc_attr( $image['alt'] ), esc_attr( $image['title'] ) );
				$inner_html = apply_filters( 'woo_variation_gallery_thumbnail_image_inner_html', $inner_html, $image, $template, $attachment_id, $options );
			}

			return '<div class="' . esc_attr( implode( ' ', array_map( 'sanitize_html_class', array_unique( $classes ) ) ) ) . '"><div>' . $inner_html . '</div></div>';
		}

		public function get_available_variation( $product_id, $variation_id ) {
			$variable_product = new WC_Product_Variable( $product_id );
			$variation        = $variable_product->get_available_variation( $variation_id );

			return $variation;
		}

		public function get_available_variations( $product ) {

			if ( is_numeric( $product ) ) {
				$product = wc_get_product( absint( $product ) );
			}

			return $product->get_available_variations();
		}

		public function get_default_gallery_images( $product_id ) {

			$product              = wc_get_product( $product_id );
			$product_id           = $product->get_id();
			$attachment_ids       = $product->get_gallery_image_ids( 'edit' );
			$post_thumbnail_id    = $product->get_image_id( 'edit' );
			$has_post_thumbnail   = has_post_thumbnail();
			$images               = array();
			$placeholder_image_id = get_option( 'woocommerce_placeholder_image', 0 );


			/*if ( has_post_thumbnail( $product_id ) ) {
				array_unshift( $gallery_images, get_post_thumbnail_id( $product_id ) );
			}*/

			$post_thumbnail_id = (int) apply_filters( 'woo_variation_gallery_post_thumbnail_id', $post_thumbnail_id, $attachment_ids, $product );
			$attachment_ids    = (array) apply_filters( 'woo_variation_gallery_attachment_ids', $attachment_ids, $post_thumbnail_id, $product );


			$remove_featured_image = wc_string_to_bool( woo_variation_gallery()->get_option( 'remove_featured_image', 'no', 'woo_variation_gallery_remove_featured_image' ) );


			// IF PLACEHOLDER IMAGE HAVE VIDEO IT MAY NOT LOAD.
			if ( ! empty( $post_thumbnail_id ) ) {
				array_unshift( $attachment_ids, $post_thumbnail_id );
			} else {
				array_unshift( $attachment_ids, $placeholder_image_id );
			}

			if ( is_array( $attachment_ids ) && ! empty( $attachment_ids ) ) {

				foreach ( $attachment_ids as $i => $image_id ) {

					if ( $remove_featured_image && absint( $post_thumbnail_id ) == absint( $image_id ) ) {
						continue;
					}

					$images[] = apply_filters( 'woo_variation_gallery_get_default_gallery_image', $this->get_product_attachment_props( $image_id, $product ), $product );
				}
			}

			return apply_filters( 'woo_variation_gallery_get_default_gallery_images', $images, $product );
		}

		public function get_variation_gallery_images( $product_id ) {

			$images               = array();
			$available_variations = $this->get_available_variations( $product_id );

			foreach ( $available_variations as $i => $variation ) {
				array_push( $variation['variation_gallery_images'], $variation['image'] );
			}

			foreach ( $available_variations as $i => $variation ) {
				foreach ( $variation['variation_gallery_images'] as $image ) {
					array_push( $images, $image );
				}
			}

			return apply_filters( 'woo_variation_gallery_get_variation_gallery_images', $images, $product_id );
		}

		public function get_default_gallery() {

			ob_start();

			if ( empty( $_POST ) || empty( $_POST['product_id'] ) ) {
				wp_send_json( false );
			}

			$product_id = absint( $_POST['product_id'] );

			$images = $this->get_default_gallery_images( $product_id );

			wp_send_json( apply_filters( 'woo_variation_gallery_get_default_gallery', $images, $product_id ) );
		}

		public function get_variation_gallery() {

			ob_start();

			if ( empty( $_POST ) || empty( $_POST['product_id'] ) ) {
				wp_send_json( false );
			}

			$product_id = absint( $_POST['product_id'] );

			$images = $this->get_variation_gallery_images( $product_id );

			wp_send_json( apply_filters( 'woo_variation_gallery_get_variation_gallery', $images, $product_id ) );
		}

		public function disable_for_specific_product_type( $default ) {

			if ( function_exists( 'is_product' ) && is_product() ) {
				$product = wc_get_product();

				$product_types         = woo_variation_gallery()->get_option( 'disabled_product_type', array(
					'gift-card',
					'bundle'
				) );
				$disabled_product_type = map_deep( $product_types, 'sanitize_text_field' );

				return is_object( $product ) ? in_array( $product->get_type(), $disabled_product_type ) : $default;
			}

			return $default;
		}

		public function gallery_inline_style( $styles ) {

			$gallery_width = absint( woo_variation_gallery()->get_option( 'width', apply_filters( 'woo_variation_gallery_default_width', 30 ), 'woo_variation_gallery_width' ) );

			if ( $gallery_width > 99 ) {
				$styles['float']   = 'none';
				$styles['display'] = 'block';
			}

			return $styles;
		}

		public function slider_template_js() {
			ob_start();
			require_once dirname( __FILE__ ) . '/slider-template-js.php';
			$data = ob_get_clean();
			echo apply_filters( 'woo_variation_gallery_slider_template_js', $data );
		}

		public function gallery_template( $template, $template_name ) {

			$old_template = $template;

			// Disable gallery on specific product

			if ( apply_filters( 'disable_woo_variation_gallery', false ) ) {
				return $old_template;
			}

			if ( $template_name == 'single-product/product-image.php' ) {
				$template = woo_variation_gallery()->template_path( '/product-images.php' );
			}

			if ( $template_name == 'single-product/product-thumbnails.php' ) {
				$template = woo_variation_gallery()->template_path( '/product-thumbnails.php' );
			}

			return apply_filters( 'woo_variation_gallery_gallery_template_override_location', $template, $template_name, $old_template );
		}

		public function gallery_template_part( $template, $slug ) {

			$old_template = $template;

			// Disable gallery on specific product

			if ( apply_filters( 'disable_woo_variation_gallery', false ) ) {
				return $old_template;
			}

			if ( $slug == 'single-product/product-image' ) {
				$template = woo_variation_gallery()->template_path( '/product-images.php' );
			}

			if ( $slug == 'single-product/product-thumbnails' ) {
				$template = woo_variation_gallery()->template_path( '/product-thumbnails.php' );
			}

			return apply_filters( 'woo_variation_gallery_gallery_template_part_override_location', $template, $slug, $old_template );
		}

	}
endif;