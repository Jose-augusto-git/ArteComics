<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Counter_Box' ) ) {

	/**
	 * Functions related to Google Map
	 *
	 * @package Gutentor
	 * @since 1.0.1
	 */
	class Gutentor_Counter_Box extends Gutentor_Block_Base {

		/**
		 * Name of the block.
		 *
		 * @access protected
		 * @since 1.0.1
		 * @var string
		 */
		protected $block_name = 'counter-box';

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @since 1.0.1
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
		 * @since      1.0.1
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 */
		public function register_block_type_args() {
			$this->register_block_type_args = array(
				'view_script_handles' => array( 'countUp' ),
			);
		}

		/**
		 * Just return content.
		 * It is for view_script_handles
		 *
		 * @param array  $attributes Attributes array.
		 * @param string $content Content is always string.
		 * @return string
		 * @since    1.0.1
		 * @access   public
		 */
		public function render_callback( $attributes, $content ) {
			return $content;
		}
	}
}
Gutentor_Counter_Box::get_instance()->run();
