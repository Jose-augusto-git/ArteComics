<?php
defined( 'ABSPATH' ) or die( 'Keep Quit' );

if ( ! class_exists( 'Woo_Variation_Gallery_Migrate', false ) ):
	class Woo_Variation_Gallery_Migrate {

		protected static $_instance = null;

		protected function __construct() {
			$this->includes();
			$this->hooks();
			$this->init();
			do_action( 'woo_variation_gallery_migrate_loaded', $this );
		}

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function includes() {
			require_once dirname( __FILE__ ) . '/class-woo-variation-gallery-migration.php';
		}

		public function hooks() {
			add_filter( 'woo_variation_gallery_migration_list', array( $this, 'add_migration_list' ) );
			add_filter( 'woocommerce_debug_tools', array( $this, 'add_migration_list' ) );
			add_filter( 'woo_variation_gallery_migrate_images', array( $this, 'migrate_images' ), 10, 3 );
			add_action( 'init', array( 'Woo_Variation_Gallery_Migration', 'init' ) );
		}

		public function init() {

		}

		public function add_migration_list( $tools = array() ) {

			$tools['woo_variation_gallery_wc_avi_migrate'] = array(
				'name'     => esc_html__( 'Migrate from "WooCommerce Additional Variation Images" plugin', 'woo-variation-gallery' ),
				'button'   => esc_html__( 'Start migration', 'woo-variation-gallery' ),
				'desc'     => esc_html__( 'This will migrate from "WooCommerce Additional Variation Images" to "Additional Variation Images Gallery for WooCommerce".', 'woo-variation-gallery' ),
				'callback' => array( $this, 'wc_avi_migration_queue' )
			);

			$tools['woo_variation_gallery_woothumbs_migrate'] = array(
				'name'     => esc_html__( 'Migrate from "WooThumbs for WooCommerce by Iconic" plugin', 'woo-variation-gallery' ),
				'button'   => esc_html__( 'Start migration', 'woo-variation-gallery' ),
				'desc'     => esc_html__( 'This will migrate from "WooThumbs for WooCommerce by Iconic" to "Additional Variation Images Gallery for WooCommerce".', 'woo-variation-gallery' ),
				'callback' => array( $this, 'woothumbs_migration_queue' )
			);

			$tools['woo_variation_gallery_smart_variations_images_migrate'] = array(
				'name'     => esc_html__( 'Migrate from "Smart Variations Images for WooCommerce" plugin', 'woo-variation-gallery' ),
				'button'   => esc_html__( 'Start migration', 'woo-variation-gallery' ),
				'desc'     => esc_html__( 'This will migrate from "Smart Variations Images for WooCommerce" to "Additional Variation Images Gallery for WooCommerce".', 'woo-variation-gallery' ),
				'callback' => array( $this, 'smart_variations_images_migration_queue' )
			);

			$tools['woo_variation_gallery_avmi_migrate'] = array(
				'name'     => esc_html__( 'Migrate from "Ajaxy Woocommerce Multiple Variation Image" plugin', 'woo-variation-gallery' ),
				'button'   => esc_html__( 'Start migration', 'woo-variation-gallery' ),
				'desc'     => esc_html__( 'This will migrate from "Ajaxy Woocommerce Multiple Variation Image" to "Additional Variation Images Gallery for WooCommerce".', 'woo-variation-gallery' ),
				'callback' => array( $this, 'avmi_migration_queue' )
			);

			$tools['woo_variation_gallery_rtwpvg_migrate'] = array(
				'name'     => esc_html__( 'Migrate from "Variation Images Gallery for WooCommerce by RadiusTheme" plugin', 'woo-variation-gallery' ),
				'button'   => esc_html__( 'Start migration', 'woo-variation-gallery' ),
				'desc'     => esc_html__( 'This will migrate from "Variation Images Gallery for WooCommerce by RadiusTheme" to "Additional Variation Images Gallery for WooCommerce".', 'woo-variation-gallery' ),
				'callback' => array( $this, 'rtwpvg_migration_queue' )
			);

			return apply_filters( 'woo_variation_gallery_add_to_migration_list', $tools, $this );
		}

		public function wc_avi_migration_queue() {
			Woo_Variation_Gallery_Migration::queue_migration( 'woocommerce-additional-variation-images' );

			return esc_html__( 'Variation product migration has been scheduled to run in the background.', 'woo-variation-gallery' );
		}

		public function woothumbs_migration_queue() {
			Woo_Variation_Gallery_Migration::queue_migration( 'woothumbs' );

			return esc_html__( 'Variation product migration has been scheduled to run in the background.', 'woo-variation-gallery' );
		}

		public function smart_variations_images_migration_queue() {
			Woo_Variation_Gallery_Migration::queue_migration( 'smart-variations-images' );

			return esc_html__( 'Variation product migration has been scheduled to run in the background.', 'woo-variation-gallery' );
		}

		public function avmi_migration_queue() {
			Woo_Variation_Gallery_Migration::queue_migration( 'avmi' );

			return esc_html__( 'Variation product migration has been scheduled to run in the background.', 'woo-variation-gallery' );
		}

		public function rtwpvg_migration_queue() {
			Woo_Variation_Gallery_Migration::queue_migration( 'rtwpvg' );

			return esc_html__( 'Variation product migration has been scheduled to run in the background.', 'woo-variation-gallery' );
		}

		public function migrate_images( $images, $migrate_from, $product_id ) {

			if ( 'woocommerce-additional-variation-images' === $migrate_from ) {
				$wc_gallery_images = get_post_meta( $product_id, '_wc_additional_variation_images', true );
				$images            = array_values( array_filter( explode( ',', $wc_gallery_images ) ) );
			}

			if ( 'woothumbs' === $migrate_from ) {
				$wc_gallery_images = get_post_meta( $product_id, 'variation_image_gallery', true );
				$images            = array_values( array_filter( explode( ',', $wc_gallery_images ) ) );
			}

			if ( 'smart-variations-images' === $migrate_from ) {
				$wc_gallery_images = get_post_meta( $product_id, '_product_image_gallery', true );
				$images            = array_values( array_filter( explode( ',', $wc_gallery_images ) ) );
			}

			if ( 'avmi' === $migrate_from ) {
				$wc_gallery_images = get_post_meta( $product_id, 'avmi_image_id', true );
				$images            = array_values( array_filter( explode( ',', $wc_gallery_images ) ) );
			}

			if ( 'rtwpvg' === $migrate_from ) {
				$wc_gallery_images = (array) get_post_meta( $product_id, 'rtwpvg_images', true );
				$images            = array_values( array_filter( $wc_gallery_images ) );
			}

			return apply_filters( 'woo_variation_gallery_migrated_images', $images, $migrate_from, $product_id );
		}
	}
endif;


