<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Ticker_P5_Templates' ) ) {

	/**
	 * Blog_Post_Templates Class For Gutentor
	 *
	 * @package Gutentor
	 * @since 2.0.0
	 */
	class Gutentor_Ticker_P5_Templates extends Gutentor_Query_Elements {

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @since 2.0.0
		 * @return object
		 */
		public static function get_instance() {

			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new self();
			}

			// Always return the instance
			return $instance;

		}

		/**
		 * Run Block
		 *
		 * @access public
		 * @since 2.0.0
		 * @return void
		 */
		public function run() {
			add_filter( 'gutentor_post_module_p5_query_data', array( $this, 'load_blog_post_template' ), 99999, 3 );
		}

		/**
		 * Load Template 1
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function gutentor_p5_template1( $data, $post, $attributes ) {
			$output  = '';
			$output .= '<a class="gutentor-post-title" href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
			$output .= get_the_title();
			$output .= '</a>';
			return $output;
		}

		/**
		 * Blog Post Templates
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */

		public function load_blog_post_template( $data, $post, $attributes ) {
			$output   = $data;
			$template = ( isset( $attributes['p5Temp'] ) ) ? $attributes['p5Temp'] : '';
			if ( method_exists( $this, $template ) ) {
				$output = $this->$template( $data, $post, $attributes );
			}
			return $output;
		}

	}
}
Gutentor_Ticker_P5_Templates::get_instance()->run();
