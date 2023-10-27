<?php
/**
 * Plugin Name: Additional Variation Images Gallery for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/woo-variation-gallery/
 * Description: Allows inserting multiple images for per variation to let visitors see a different images when WooCommerce product variations are switched.
 * Author: Emran Ahmed
 * Version: 1.3.20
 * Domain Path: /languages
 * Requires PHP: 7.4
 * Requires at least: 5.7
 * Tested up to: 6.3
 * WC requires at least: 5.8
 * WC tested up to: 8.0
 * Text Domain: woo-variation-gallery
 * Author URI: https://getwooplugins.com/
 */

defined( 'ABSPATH' ) or die( 'Keep Silent' );

if ( ! defined( 'WOO_VARIATION_GALLERY_PLUGIN_FILE' ) ) {
	define( 'WOO_VARIATION_GALLERY_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'WOO_VARIATION_GALLERY_PLUGIN_VERSION' ) ) {
	define( 'WOO_VARIATION_GALLERY_PLUGIN_VERSION', '1.3.20' );
}

// Include the main class.
if ( ! class_exists( 'Woo_Variation_Gallery', false ) ) {
	require_once dirname( __FILE__ ) . '/includes/class-woo-variation-gallery.php';
}

// Require woocommerce admin message
function woo_variation_gallery_wc_requirement_notice() {

	if ( ! class_exists( 'WooCommerce' ) ) {
		$text    = esc_html__( 'WooCommerce', 'woo-variation-gallery' );
		$link    = esc_url( add_query_arg( array(
			'tab'       => 'plugin-information',
			'plugin'    => 'woocommerce',
			'TB_iframe' => 'true',
			'width'     => '640',
			'height'    => '500',
		), admin_url( 'plugin-install.php' ) ) );
		$message = wp_kses( __( "<strong>Variation Gallery for WooCommerce</strong> is an add-on of ", 'woo-variation-gallery' ), array( 'strong' => array() ) );

		printf( '<div class="%1$s"><p>%2$s <a class="thickbox open-plugin-details-modal" href="%3$s"><strong>%4$s</strong></a></p></div>', 'notice notice-error', $message, $link, $text );
	}
}

add_action( 'admin_notices', 'woo_variation_gallery_wc_requirement_notice' );

/**
 * Returns the main instance.
 */

function woo_variation_gallery() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid

	if ( ! class_exists( 'WooCommerce', false ) ) {
		return false;
	}

	if ( function_exists( 'woo_variation_gallery_pro' ) ) {
		return woo_variation_gallery_pro();
	}

	return Woo_Variation_Gallery::instance();
}

add_action( 'plugins_loaded', 'woo_variation_gallery' );

// Supporting WooCommerce High-Performance Order Storage
function woo_variation_gallery_hpos_compatibility() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}

add_action( 'before_woocommerce_init', 'woo_variation_gallery_hpos_compatibility' );

register_activation_hook( __FILE__, array( 'Woo_Variation_Gallery', 'plugin_activated' ) );
