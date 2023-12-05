<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_M5' ) ) {

	/**
	 * Functions related to Progress bar
	 *
	 * @package Gutentor
	 * @since 3.0.6
	 */
	class Gutentor_M5 extends Gutentor_Block_Base {

		/**
		 * Name of the block.
		 *
		 * @access protected
		 * @since 3.0.6
		 * @var string
		 */
		protected $block_name = 'm5';

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @since 3.0.6
		 * @return object
		 */
		public static function get_instance() {

			// Store the instance locally to avoid private static replication.
			static $instance = null;

			// Only run these methods if they haven't been ran previously.
			if ( null === $instance ) {
				$instance = new self();
			}

			// Always return the instance.
			return $instance;

		}

		/**
		 * Set register_block_type_args variable on parent
		 * Used for blog template loading
		 *
		 * @since      3.0.6
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 */
		public function register_block_type_args() {
			$this->register_block_type_args = array(
				'view_script_handles' => array( 'slick' ),
				'style_handles'       => array( 'slick' ),
			);
		}

		/**
		 * Just return content.
		 * It is for view_script_handles
		 *
		 * @param array  $attributes Attributes array.
		 * @param string $content Content is always string.
		 * @return string
		 * @since    3.0.6
		 * @access   public
		 */
		public function render_callback( $attributes, $content ) {
			return $content;
		}
	}
}
Gutentor_M5::get_instance()->run();
