<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Term_Modules_Hooks' ) ) {

	/**
	 * Block Specific Hooks Class For Gutentor
	 *
	 * @package Gutentor
	 * @since 2.0.0
	 */
	class Gutentor_Term_Modules_Hooks {

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @return object
		 * @since 2.0.0
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
		 * Add Filter
		 *
		 * @access public
		 * @return void
		 * @since 2.0.0
		 */
		public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
			add_filter( $hook, array( $component, $callback ), $priority, $accepted_args );
		}

		/**
		 * Add Action
		 *
		 * @access public
		 * @return void
		 * @since 2.0.0
		 */
		public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
			add_action( $hook, array( $component, $callback ), $priority, $accepted_args );
		}

		/**
		 * Run Block
		 *
		 * @access public
		 * @return void
		 * @since 2.0.0
		 */
		public function run() {
		    /*Block Specific PHP hooks*/
            $this->add_filter( 'gutentor_term_module_article_class', $this, 'add_item_post_align_class', 10, 2 );

		}

		/**
		 * Adding Align class
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return {array}
		 */
		public function add_item_post_align_class( $output, $attributes ) {

			$gName      = ( isset( $attributes['gName'] ) ) ? $attributes['gName'] : '';
			$block_list = array( 'gutentor/t1','gutentor/t2' );
			$block_list = apply_filters( 'gutentor_term_item_bx_align_class_accessor_block', $block_list );
			if ( ! in_array( $gName, $block_list ) ) {
				return $output;
			}

			if ( ! isset( $attributes['tBxAlign'] ) ) {
				return $output;
			}

			$local_data   = '';
			$align_mobile = ( isset( $attributes['tBxAlign']['mobile'] ) ) ? $attributes['tBxAlign']['mobile'] : false;
			if ( $align_mobile ) {
				$align_m_class = ( $align_mobile ) ? $align_mobile . '-mobile' : '';
				$local_data    = gutentor_concat_space( $local_data, $align_m_class );
			}
			$align_tablet = ( isset( $attributes['tBxAlign']['tablet'] ) ) ? $attributes['tBxAlign']['tablet'] : false;
			if ( $align_tablet ) {

				$align_t_class = ( $align_tablet ) ? $align_tablet . '-tablet' : '';
				$local_data    = gutentor_concat_space( $local_data, $align_t_class );
			}
			$align_desktop = ( isset( $attributes['tBxAlign']['desktop'] ) ) ? $attributes['tBxAlign']['desktop'] : false;
			if ( $align_desktop ) {

				$align_d_class = ( $align_desktop ) ? $align_desktop . '-desktop' : '';
				$local_data    = gutentor_concat_space( $local_data, $align_d_class );
			}
			return gutentor_concat_space( $output, $local_data );
		}

	}
}

/**
 * Return instance of  Gutentor_Term_Modules_Hooks class
 *
 * @since    1.0.0
 */
if ( ! function_exists( 'gutentor_term_modules_hooks' ) ) {

	function gutentor_term_modules_hooks() {
		return Gutentor_Term_Modules_Hooks::get_instance();
	}
}
gutentor_term_modules_hooks()->run();
