<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woo_Variation_Gallery_Compatibility' ) ) :

	class Woo_Variation_Gallery_Compatibility {

		protected static $_instance = null;

		protected function __construct() {
			$this->includes();
			$this->hooks();
			$this->init();
			do_action( 'woo_variation_gallery_compatibility_loaded', $this );
		}

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		protected function includes() {
		}

		protected function hooks() {

			add_action( 'woocommerce_init', array( $this, 'theme_compatibility' ), 20 );
			add_action( 'woocommerce_init', array( $this, 'oxygen_theme_compatibility' ), 9 );
			add_filter( 'woo_variation_gallery_default_width', array( $this, 'set_default_width_based_on_theme' ), 8 );

			add_action( 'woo_variation_duplicator_variation_save', array( $this, 'duplicator_variation_save' ), 10, 2 );
			add_action( 'woo_variation_duplicator_image_saved_to', array( $this, 'duplicator_image_saved_to' ), 10, 2 );
			add_action( 'woo_variation_duplicator_image_saved_from', array(
				$this,
				'duplicator_image_saved_from'
			), 10, 2 );

			add_filter( 'woo_variation_swatches_get_available_preview_variation', array(
				$this,
				'get_available_preview_variation'
			), 10, 3 );

			// Dokan Support
			add_action( 'wp_enqueue_scripts', array( $this, 'dokan_enqueue_scripts' ) );

			add_action( 'wp_footer', array( $this, 'dokan_footer' ) );

			add_action( 'dokan_product_after_variable_attributes', array( $this, 'dokan_variable_attributes' ), 10, 3 );
		}

		protected function init() {
		}

		// Start

		public function dokan_variable_attributes( $loop, $variation_data, $variation ) {
			if ( class_exists( 'WeDevs_Dokan' ) && current_user_can( 'dokan_edit_product' ) ) {
				woo_variation_gallery()->get_backend()->gallery_admin_html( $loop, $variation_data, $variation );
			}
		}

		public function dokan_enqueue_scripts() {
			if ( class_exists( 'WeDevs_Dokan' ) && current_user_can( 'dokan_edit_product' ) ) {
				woo_variation_gallery()->get_backend()->admin_enqueue_scripts();
			}
		}

		public function dokan_footer() {
			if ( class_exists( 'WeDevs_Dokan' ) && current_user_can( 'dokan_edit_product' ) ) {
				woo_variation_gallery()->get_backend()->admin_template_js();
			}
		}

		public function get_available_preview_variation( $available_variation, $variationProductObject, $variation ) {
			return woo_variation_gallery()->get_frontend()->get_available_variation_gallery( $available_variation, $variationProductObject, $variation );
		}

		public function theme_compatibility() {
			$this->kalium_theme_compatibility();
			$this->avada_theme_compatibility();
			$this->oxygen_theme_compatibility();
		}

		public function set_default_width_based_on_theme( $width ) {

			// Twenty twenty three

			$twentythree_theme = wp_get_theme( 'twentytwentythree' );
			if ( $twentythree_theme->exists() ) {
				$width = 100;
			}


			// Avada Theme
			if ( class_exists( 'Avada' ) ) {
				$width = 45;
			}

			// OceanWP Theme
			if ( class_exists( 'OCEANWP_Theme_Class' ) ) {
				$width = 40;
			}

			// Astra Theme
			if ( defined( 'ASTRA_THEME_DIR' ) ) {
				$width = 50;
			}

			// Be Theme
			if ( function_exists( 'mfn_opts_get' ) ) {
				$width = 100;
			}

			// Divi Theme
			if ( function_exists( 'et_setup_theme' ) ) {
				$width = 50;
			}

			// Enfold Theme
			if ( defined( 'AV_FRAMEWORK_VERSION' ) ) {
				$width = 100;
			}

			// Salient Theme
			if ( defined( 'NECTAR_FRAMEWORK_DIRECTORY' ) ) {
				$width = 100;
			}

			// Flatsome Theme
			if ( class_exists( 'Flatsome_Default' ) ) {
				$width = 100;
			}

			// Porto Theme
			if ( defined( 'porto_lib' ) ) {
				$width = 90;
			}

			// Shopisle Theme
			if ( function_exists( 'shopisle_load_sdk' ) ) {
				$width = 45;
			}

			// Zerif Lite Theme
			if ( function_exists( 'zerif_load_sdk' ) ) {
				$width = 50;
			}

			// Hestia Theme
			if ( function_exists( 'hestia_load_sdk' ) ) {
				$width = 45;
			}

			// Storefront Theme
			if ( function_exists( 'storefront_is_woocommerce_activated' ) ) {
				$width = 40;
			}

			// Shopkeeper Theme and The Hanger Theme
			if ( function_exists( 'getbowtied_theme_name' ) ) {
				$width = 100;
			}

			// Shophistic Lite Theme
			if ( class_exists( 'shophistic_lite_Theme' ) ) {
				$width = 100;
			}

			// WR Nitro Theme
			if ( class_exists( 'WR_Nitro' ) ) {
				$width = 100;
			}

			// Sydney Theme
			if ( function_exists( 'sydney_setup' ) ) {
				$width = 50;
			}

			// ColorMag Theme
			if ( function_exists( 'colormag_setup' ) ) {
				$width = 50;
			}

			// GeneratePress Theme
			if ( function_exists( 'generate_setup' ) ) {
				$width = 50;
			}

			// Kalium Theme
			if ( class_exists( 'Kalium' ) ) {
				$width = 100;
			}

			// Kuteshop Theme
			if ( class_exists( 'Kuteshop_Functions' ) ) {
				$width = 40;
			}

			// TwentySixteen Theme
			if ( function_exists( 'twentysixteen_setup' ) ) {
				$width = 45;
			}

			// TwentySeventeen Theme
			if ( function_exists( 'twentyseventeen_setup' ) ) {
				$width = 50;
			}

			// Twenty Nineteen
			if ( function_exists( 'twentynineteen_setup' ) ) {
				$width = 50;
			}

			// Twenty Two
			if ( function_exists( 'twentytwentytwo_styles' ) ) {
				$width = 50;
			}

			// Sober Theme
			if ( function_exists( 'sober_setup' ) ) {
				$width = 40;
			}

			// Stockholm Theme
			if ( defined( 'QODE_FRAMEWORK_ROOT' ) ) {
				$width = 50;
			}

			// X Theme
			if ( function_exists( 'x_boot' ) ) {
				$width = 50;
			}

			// Saha Theme
			if ( function_exists( 'saha_theme_setup' ) ) {
				$width = 100;
			}

			// ROYAL - 8theme WordPress theme
			if ( function_exists( 'etheme_theme_setup' ) ) {
				$width = 100;
			}

			// Customify Theme
			if ( function_exists( 'Customify' ) ) {
				$width = 95;
			}

			// Customizr Theme
			if ( class_exists( 'CZR_BASE' ) ) {
				$width = 50;
			}

			// BASEL Theme
			if ( class_exists( 'BASEL_Theme' ) ) {
				$width = 100;
			}

			// Suave Theme
			if ( function_exists( 'cg_setup' ) ) {
				$width = 100;
			}

			// Oxygen Theme
			if ( function_exists( 'oxygen_woocommerce_use_custom_product_image_gallery_layout' ) ) {
				$width = 50;
			}

			return $width;
		}

		public function kalium_theme_compatibility() {

			if ( function_exists( 'kalium_woocommerce_init' ) ) {
				remove_action( 'kalium_woocommerce_single_product_images', 'kalium_woocommerce_show_product_images_custom_layout', 20 );
				remove_filter( 'woocommerce_available_variation', 'kalium_woocommerce_variation_image_handler', 10 );

				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 10 );
				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

				add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images' );
			}
		}

		public function avada_theme_compatibility() {

			if ( class_exists( 'Avada' ) ) {
				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 10 );
				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

				add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 50 );
			}
		}

		public function oxygen_theme_compatibility() {
			// Remove Oxygen Theme Gallery
			if ( function_exists( 'oxygen_woocommerce_use_custom_product_image_gallery_layout' ) ):
				add_filter( 'oxygen_woocommerce_use_custom_product_image_gallery_layout', '__return_false' );
			endif;
		}

		public function duplicator_variation_save( $new_variation_id, $variation_id ) {
			$images = get_post_meta( $variation_id, 'woo_variation_gallery_images', true );
			if ( $images ) {
				update_post_meta( $new_variation_id, 'woo_variation_gallery_images', $images );
			}
		}

		public function duplicator_image_saved_to( $selected_variation, $current_variation ) {
			$images = get_post_meta( $current_variation->get_id(), 'woo_variation_gallery_images', true );
			if ( $images ) {
				update_post_meta( $selected_variation->get_id(), 'woo_variation_gallery_images', $images );
			}
		}

		public function duplicator_image_saved_from( $current_variation, $selected_variation ) {
			$images = get_post_meta( $selected_variation->get_id(), 'woo_variation_gallery_images', true );

			if ( $images ) {
				update_post_meta( $current_variation->get_id(), 'woo_variation_gallery_images', $images );
			}
		}
	}

endif;