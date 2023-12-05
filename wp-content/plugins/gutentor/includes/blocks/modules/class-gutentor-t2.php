<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_T2' ) ) {

	/**
	 * Functions related to Terms
	 *
	 * @package Gutentor
	 * @since 1.0.1
	 */

	class Gutentor_T2 extends Gutentor_Block_Base {

		/**
		 * Name of the block.
		 *
		 * @access protected
		 * @since 1.0.1
		 * @var string
		 */
		protected $block_name = 't2';

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
		 * Returns attributes for this Block
		 *
		 * @static
		 * @access public
		 * @since 1.0.1
		 * @return array
		 */
		protected function get_attrs() {
			$term_attr = array(
				'gID'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'gName'            => array(
					'type'    => 'string',
					'default' => 'gutentor/t2',
				),
				/*Query*/
				't2Temp'           => array(
					'type'    => 'number',
					'default' => 1,
				),
				't2Taxonomy'       => array(
					'type'    => 'string',
					'default' => 'category',
				),
				't2Order'          => array(
					'type'    => 'string',
					'default' => 'desc',
				),
				't2OrderBy'        => array(
					'type'    => 'string',
					'default' => 'date',
				),
				't2IncludeTerms'   => array(
					'type' => 'string',
				),
				't2ExcludeTerms'   => array(
					'type' => 'string',
				),
				't2Number'         => array(
					'type' => 'number',
				),
				't2HideEmpty'      => array(
					'type'    => 'boolean',
					'default' => 'true',
				),
				/*global*/
				't2ContentMargin'  => array(
					'type' => 'object',
				),
				't2ContentPadding' => array(
					'type'    => 'object',
					'default' => array(
						'type'    => 'px',
						'mTop'    => '15',
						'mRight'  => '15',
						'mBottom' => '15',
						'mLeft'   => '15',
					),
				),
				't2BgProps'        => array(
					'type'    => 'object',
					'default' => array(
						'size'       => 'cover',
						'pos'        => 'center',
						'repeat'     => 'no-repeat',
						'attachment' => 'scroll',
					),
				),

			);
			$term_partial_attr = array_merge_recursive( $term_attr, $this->get_module_common_attrs() );
			return array_merge_recursive( $term_partial_attr, $this->get_term_common_attrs() );
		}


		/**
		 * Render Blog Post Data
		 *
		 * @since    1.0.1
		 * @access   public
		 *
		 * @param array  $attributes
		 * @param string $content
		 * @return string
		 */
		public function render_callback( $attributes, $content ) {

			$blockID = isset( $attributes['mID'] ) ? $attributes['mID'] : $attributes['gID'];
			$gID     = isset( $attributes['gID'] ) ? $attributes['gID'] : '';
			$output  = '';

			$default_class = gutentor_block_add_default_classes( 'gutentor-t2', $attributes );

			$tag      = $attributes['mTag'] ? $attributes['mTag'] : 'section';
			$template = $attributes['t2Temp'] ? $attributes['t2Temp'] : '';

			$align                   = isset( $attributes['align'] ) ? 'align' . $attributes['align'] : '';
			$blockComponentAnimation = isset( $attributes['mAnimation'] ) ? $attributes['mAnimation'] : '';

			/*
			Query
			*/
			$taxonomy   = isset( $attributes['t2Taxonomy'] ) ? $attributes['t2Taxonomy'] : 'category';
			$orderby    = isset( $attributes['t2OrderBy'] ) ? $attributes['t2OrderBy'] : 'date';
			$order      = isset( $attributes['t2Order'] ) ? $attributes['t2Order'] : 'desc';
			$hide_empty = isset( $attributes['t2HideEmpty'] ) ? $attributes['t2HideEmpty'] : true;
			$include    = isset( $attributes['t2IncludeTerms'] ) ? $attributes['t2IncludeTerms'] : '';
			$exclude    = isset( $attributes['t2ExcludeTerms'] ) ? $attributes['t2ExcludeTerms'] : '';
			$number     = isset( $attributes['t2Number'] ) ? $attributes['t2Number'] : 5;
			$terms      = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'orderby'    => $orderby,
					'order'      => $order,
					'hide_empty' => $hide_empty,
					'include'    => $include,
					'exclude'    => $exclude,
					'number'     => $number,
				)
			);
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$output .= '<' . $tag . ' id="' . esc_attr( $blockID ) . '" class="' . apply_filters( 'gutentor_term_module_main_wrap_class', gutentor_concat_space( 'section-' . $gID, 'gutentor-module', 'gtf-module', 'gutentor-term-module', 'gutentor-term-module-t2', $align, 'g-loop-' . $number, 'g-template-' . $template, $default_class ), $attributes ) . '" id="' . esc_attr( $blockID ) . '" ' . GutentorAnimationOptionsDataAttr( $blockComponentAnimation ) . '>' . "\n";
				$output .= apply_filters( 'gutentor_term_module_before_container', '', $attributes );
				$output .= "<div class='" . apply_filters( 'gutentor_term_module_container_class', 'grid-container', $attributes ) . "'>";
				$output .= "<div class='" . apply_filters( 'gutentor_term_module_grid_row_class', 'grid-row', $attributes ) . "'>";
				$output .= apply_filters( 'gutentor_term_module_before_block_items', '', $attributes );

				/*term query*/
				$output .= apply_filters( 'gutentor_term_module_t2_query_data', '', $terms, $attributes );

				$output .= apply_filters( 'gutentor_term_module_after_block_items', '', $attributes );
				$output .= '</div>';/*.grid-row*/
				$output .= '</div>';/*.grid-container*/
				$output .= apply_filters( 'gutentor_term_module_after_container', '', $attributes );
				$output .= '</' . $tag . '>';/*.gutentor-blog-term-wrapper*/
			}

			return $output;
		}
	}
}
Gutentor_T2::get_instance()->run();
