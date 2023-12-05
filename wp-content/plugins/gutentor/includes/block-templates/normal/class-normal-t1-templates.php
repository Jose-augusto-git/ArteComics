<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_T1_Templates' ) ) {

	/**
	 * Blog_Post_Templates Class For Gutentor
	 *
	 * @package Gutentor
	 * @since 2.0.0
	 */
	class Gutentor_T1_Templates extends Gutentor_Query_Elements {

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
			add_filter( 'gutentor_term_module_t1_query_data', array( $this, 'load_blog_post_template' ), 99999, 4 );
		}

		/**
		 * Load Grid Template 1
		 *
		 * @param {string} $data
		 * @param {array}  $term
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function gutentor_t1_grid_template1( $data, $term, $attributes ) {
			$query_sorting       = array_key_exists( 'blockSortableItems', $attributes ) ? $attributes['blockSortableItems'] : false;
			$enable_featured_img = isset( $attributes['tOnFImg'] ) && $attributes['tOnFImg'];
			$output              = '';
			if ( $query_sorting ) :
				foreach ( $query_sorting as $element ) :
					if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
						return $output;
					}
					switch ( $element['itemValue'] ) {
						case 'featured-image':
							if ( $enable_featured_img && $this->has_term_thumbnail( $term ) ) {
								$output .= $this->get_term_featured_image( $term, $attributes );
							}
							break;
						case 'title':
							$output .= $this->get_term_title_and_count_updated( $term, $attributes, 'title' );
							break;
						case 'count':
							$output .= $this->get_term_title_and_count_updated( $term, $attributes, 'count' );
							break;
						case 'description':
								$output .= $this->get_term_description( $term, $attributes );
							break;
						case 'button':
								$output .= $this->get_term_button( $term, $attributes );
							break;
						default:
                            $output .= $this->get_term_dynamic_element( $element,$term, $attributes );
                            break;
					}
				endforeach;
			endif;
			return $output;
		}

		/**
		 * Load List Template 1
		 *
		 * @param {string} $data
		 * @param {array}  $term
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function gutentor_t1_list_template1( $data, $term, $attributes ) {

			$query_sorting       = array_key_exists( 'blockSortableItems', $attributes ) ? $attributes['blockSortableItems'] : false;
			$enable_featured_img = isset( $attributes['tOnFImg'] ) && $attributes['tOnFImg'];

			$output = '';
			if ( $enable_featured_img && $this->has_term_thumbnail( $term ) ) {
				$output .= '<div class="gutentor-term-image-box">';
				$output .= $this->get_term_featured_image( $term, $attributes );
				$output .= '</div>';/*.gutentor-term-image-box*/

			}
			$output .= '<div class="gutentor-term-content">';
			if ( $query_sorting ) :
				foreach ( $query_sorting as $element ) :
					if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
						return $output;
					}
					switch ( $element['itemValue'] ) {
						case 'title':
							$output .= $this->get_term_title_and_count_updated( $term, $attributes, 'title' );
							break;
						case 'count':
							$output .= $this->get_term_title_and_count_updated( $term, $attributes, 'count' );
							break;
						case 'description':
							$output .= $this->get_term_description( $term, $attributes );
							break;
						case 'button':
							$output .= $this->get_term_button( $term, $attributes );
							break;
                        default:
                            $output .= $this->get_term_dynamic_element( $element,$term, $attributes );
                            break;
					}
				endforeach;
			endif;
			$output .= '</div>';/*.gutentor-term-content*/
			return $output;

		}

		/**
		 * Template 2
		 *
		 * @param {string} $data
		 * @param {array}  $term
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function gutentor_t1_template2( $data, $term, $attributes ) {
			$url            = array();
			$bg_image_class = $custom_style = '';
			$thumbnail_size = ( isset( $attributes['tFImgSize'] ) ) ? $attributes['tFImgSize'] : 'large';
			$overlay        = ( isset( $attributes['tFImgOC']['enable'] ) ) ? $attributes['tFImgOC']['enable'] : '';
			$overlay        = $overlay ? 'g-overlay' : '';
			if ( $this->has_term_thumbnail( $term ) ) {
				$url            = wp_get_attachment_image_src( $this->get_term_thumbnail_id( $term ), $thumbnail_size );
				$bg_image_class = 'gtf-bg-image';
				$custom_style   = "style='background-image:url(" . esc_url( is_array( $url ) && ! empty( $url ) ? $url[0] : '' ) . ")'";
			}
			$query_sorting = array_key_exists( 'blockSortableItems', $attributes ) ? $attributes['blockSortableItems'] : false;
			$output        = "<div class='" . apply_filters( 'gutentor_term_module_t1_template2_item_height', gutentor_concat_space( 'gtf-item-height', $bg_image_class, $overlay ), $attributes ) . "' " . $custom_style . '>';
			$output       .= '<div class="gtf-content">';
			if ( $query_sorting ) :
				foreach ( $query_sorting as $element ) :
					if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
						return $output;
					}
					switch ( $element['itemValue'] ) {
						case 'title':
							$output .= $this->get_term_title_and_count_updated( $term, $attributes, 'title' );
							break;
						case 'count':
							$output .= $this->get_term_title_and_count_updated( $term, $attributes, 'count' );
							break;
						case 'description':
							$output .= $this->get_term_description( $term, $attributes );
							break;
						case 'button':
							$output .= $this->get_term_button( $term, $attributes );
							break;
                        default:
                            $output .= $this->get_term_dynamic_element( $element,$term, $attributes );
                            break;
					}
				endforeach;
			endif;
			$output .= '</div>';/*.gtf-content*/
			$output .= '</div>';/*.gtf-item-height*/
			return $output;
		}

		/**
		 * Load Template 1
		 *
		 * @param {string} $data
		 * @param {array}  $term
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function gutentor_t1_template1( $data, $term, $attributes ) {

			$template_style = isset( $attributes['termStyle'] ) ? $attributes['termStyle'] : false;
			$output         = '';
			if ( $template_style === 'gtf-grid' ) {
				$output = $this->gutentor_t1_grid_template1( $data, $term, $attributes );
			} elseif ( $template_style === 'gtf-list' ) {
				$output = $this->gutentor_t1_list_template1( $data, $term, $attributes );
			}
			return $output;
		}

		/**
		 * Blog Post Templates
		 *
		 * @param {string} $data
		 * @param {array}  $term
		 * @param {array}  $attributes
		 * @return {mix}
		 */

		public function load_blog_post_template( $data, $term, $attributes, $index ) {

			$output              = $data;
			$template            = ( isset( $attributes['t1Temp'] ) ) ? $attributes['t1Temp'] : '';
			$enable_featured_img = isset( $attributes['tOnFImg'] ) && $attributes['tOnFImg'];
			if ( $enable_featured_img && $this->has_term_thumbnail( $term ) ) {
				$no_thumb = '';

			} else {
				$no_thumb = 'gtf-no-thumb';
			}
			$output .= "<article class='" . apply_filters( 'gutentor_term_module_article_class', gutentor_concat_space( 'gutentor-term', 'gtf-item-' . $index, $no_thumb ), $attributes ) . "'>";
			$output .= "<div class='gtf-item'>";
			if ( method_exists( $this, $template ) ) {
				$output .= $this->$template( $data, $term, $attributes );
			} else {
				$output .= $this->gutentor_t1_template1( $data, $term, $attributes );
			}
			$output .= '</div>';
			$output .= '</article>';
			return $output;
		}

	}
}
Gutentor_T1_Templates::get_instance()->run();
