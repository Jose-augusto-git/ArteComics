<?php

defined( 'ABSPATH' ) or die( 'Keep Silent' );

if ( ! class_exists( 'Woo_Variation_Gallery' ) ):
	class Woo_Variation_Gallery {

		protected static $_instance = null;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function __construct() {
			$this->includes();
			$this->hooks();
			$this->init();
			do_action( 'woo_variation_gallery_loaded', $this );
		}

		public function includes() {
			require_once dirname( __FILE__ ) . '/class-woo-variation-gallery-frontend.php';
			require_once dirname( __FILE__ ) . '/class-woo-variation-gallery-backend.php';
		}

		public function hooks() {
			add_action( 'init', array( $this, 'language' ), 1 );
		}

		public function init() {

			// instance

			$this->get_frontend();
			$this->get_backend();
		}

		public function version() {
			return esc_attr( WOO_VARIATION_GALLERY_PLUGIN_VERSION );
		}

		public function get_frontend() {
			return Woo_Variation_Gallery_Frontend::instance();
		}

		public function get_backend() {
			return Woo_Variation_Gallery_Backend::instance();
		}

		public function get_inline_style( $styles = array() ) {

			$generated = array();

			foreach ( $styles as $property => $value ) {
				$generated[] = "{$property}: $value";
			}

			return implode( '; ', array_unique( apply_filters( 'woo_variation_gallery_generated_inline_style', $generated ) ) );
		}

		public function get_option( $option, $default = null ) {
			$options = GetWooPlugins_Admin_Settings::get_option( 'woo_variation_gallery' );

			if ( current_theme_supports( 'woo_variation_gallery' ) ) {
				$theme_support = get_theme_support( 'woo_variation_gallery' );
				$default       = isset( $theme_support[0][ $option ] ) ? $theme_support[0][ $option ] : $default;
			}

			return isset( $options[ $option ] ) ? $options[ $option ] : $default;
		}

		public function is_pro() {
			return false;
		}

		public function get_pro_product_id() {
			return 1850;
		}

		public function set_rtl_by_position( $position ) {
			return ! in_array( $position, array( 'left', 'right' ) ) && is_rtl();
		}

		public function get_options() {
			return GetWooPlugins_Admin_Settings::get_option( 'woo_variation_gallery' );
		}

		public function update_options( $settings ) {
			if ( empty( $settings ) || ! is_array( $settings ) ) {
				return false;
			}

			return update_option( 'woo_variation_gallery', $settings );
		}

		public function include_path( $file = '' ) {
			return untrailingslashit( plugin_dir_path( WOO_VARIATION_GALLERY_PLUGIN_FILE ) . 'includes' ) . $file;
		}

		public function template_path( $file = '' ) {
			return untrailingslashit( plugin_dir_path( WOO_VARIATION_GALLERY_PLUGIN_FILE ) . 'templates' ) . $file;
		}

		public function language() {
			load_plugin_textdomain( 'woo-variation-gallery', false, dirname( plugin_basename( WOO_VARIATION_GALLERY_PLUGIN_FILE ) ) . '/languages' );
		}

		public function basename() {
			return basename( dirname( WOO_VARIATION_GALLERY_PLUGIN_FILE ) );
		}

		public function plugin_basename() {
			return plugin_basename( WOO_VARIATION_GALLERY_PLUGIN_FILE );
		}

		public function plugin_dirname() {
			return dirname( plugin_basename( WOO_VARIATION_GALLERY_PLUGIN_FILE ) );
		}

		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( WOO_VARIATION_GALLERY_PLUGIN_FILE ) );
		}

		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', WOO_VARIATION_GALLERY_PLUGIN_FILE ) );
		}

		public function images_url( $file = '' ) {
			return untrailingslashit( plugin_dir_url( WOO_VARIATION_GALLERY_PLUGIN_FILE ) . 'images' ) . $file;
		}

		public function assets_url( $file = '' ) {
			return untrailingslashit( plugin_dir_url( WOO_VARIATION_GALLERY_PLUGIN_FILE ) . 'assets' ) . $file;
		}

		public function assets_path( $file = '' ) {
			return $this->plugin_path() . '/assets' . $file;
		}

		public function assets_version( $file ) {
			return filemtime( $this->assets_path( $file ) );
		}

		public function org_assets_url( $file = '' ) {
			return 'https://ps.w.org/woo-variation-gallery/assets' . $file . '?ver=' . $this->version();
		}

		public static function plugin_activated() {
			update_option( 'woocommerce_show_marketplace_suggestions', 'no' );
			update_option( 'woo_variation_gallery_do_activate_redirect', 'yes' );
		}
	}
endif;