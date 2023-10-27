<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woo_Variation_Gallery_Deactivate_Feedback', false ) ):

	require_once dirname( __FILE__ ) . '/getwooplugins/class-getwooplugins-plugin-deactivate-feedback.php';

	class Woo_Variation_Gallery_Deactivate_Feedback extends GetWooPlugins_Plugin_Deactivate_Feedback {

		protected static $_instance = null;

		public function __construct() {
			parent::__construct();
		}

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		protected function includes() {
		}

		public function slug() {
			return woo_variation_gallery()->basename();
		}

		public function version() {
			return woo_variation_gallery()->version();
		}

		public function options() {
			return woo_variation_gallery()->get_options();
		}

		public function reasons() {

			$current_user = wp_get_current_user();

			return array(
				'temporary_deactivation' => array(
					'title'             => esc_html__( 'It\'s a temporary deactivation.', 'woo-variation-gallery' ),
					'input_placeholder' => '',
				),

				'dont_know_about' => array(
					'title'             => esc_html__( 'I couldn\'t understand how to make it work.', 'woo-variation-gallery' ),
					'input_placeholder' => '',
					'alert'             => __( 'It converts single variation image to multiple variation image gallery. <br><a target="_blank" href="http://bit.ly/demo-dea-dilogue">Please check live demo</a>.', 'woo-variation-gallery' ),
				),

				'gallery_too_small' => array(
					'title'             => __( 'My Gallery looks <strong>too small</strong>.', 'woo-variation-gallery' ),
					'input_placeholder' => '',
					'alert'             => __( '<a target="_blank" href="http://bit.ly/video-tuts-for-deactivate-dialogue">Please check this video to configure it.</a>.', 'woo-variation-gallery' ),
				),

				'no_longer_needed' => array(
					'title'             => esc_html__( 'I no longer need the plugin', 'woo-variation-gallery' ),
					'input_placeholder' => '',
				),

				'found_a_better_plugin' => array(
					'title'             => esc_html__( 'I found a better plugin', 'woo-variation-gallery' ),
					'input_placeholder' => esc_html__( 'Please share which plugin', 'woo-variation-gallery' ),
				),

				'broke_site_layout' => array(
					'title'             => __( 'The plugin <strong>broke my layout</strong> or some functionality.', 'woo-variation-gallery' ),
					'input_placeholder' => '',
					'alert'             => __( '<a target="_blank" href="https://getwooplugins.com/tickets/">Please open a support ticket</a>, we will fix it immediately.', 'woo-variation-gallery' ),
				),

				'plugin_setup_help' => array(
					'title'             => __( 'I need someone to <strong>setup this plugin.</strong>', 'woo-variation-gallery' ),
					'input_placeholder' => esc_html__( 'Your email address.', 'woo-variation-gallery' ),
					'input_value'       => sanitize_email( $current_user->user_email ),
					'alert'             => __( 'Please provide your email address to contact with you <br>and help you to setup and configure this plugin.', 'woo-variation-gallery' ),
				),

				'plugin_config_too_complicated' => array(
					'title'             => __( 'The plugin is <strong>too complicated to configure.</strong>', 'woo-variation-gallery' ),
					'input_placeholder' => '',
					'alert'             => __( '<a target="_blank" href="https://getwooplugins.com/documentation/woocommerce-variation-gallery/">Have you checked our documentation?</a>.', 'woo-variation-gallery' ),
				),

				'need_specific_feature' => array(
					'title'             => esc_html__( 'I need specific feature that you don\'t support.', 'woo-variation-gallery' ),
					'input_placeholder' => esc_html__( 'Please share with us.', 'woo-variation-gallery' ),
					//'alert'             => __( '<a target="_blank" href="https://getwooplugins.com/tickets/">Please open a ticket</a>, we will try to fix it immediately.', 'woo-variation-gallery' ),
				),

				'other' => array(
					'title'             => esc_html__( 'Other', 'woo-variation-gallery' ),
					'input_placeholder' => esc_html__( 'Please share the reason', 'woo-variation-gallery' ),
				)
			);
		}
	}
endif;