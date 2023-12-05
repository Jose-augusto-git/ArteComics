<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_P6' ) ) {

	/**
	 * Functions related to Blog Post
	 *
	 * @package Gutentor
	 * @since 1.0.1
	 */

	class Gutentor_P6 extends Gutentor_Block_Base {

		/**
		 * Name of the block.
		 *
		 * @access protected
		 * @since 1.0.1
		 * @var string
		 */
		protected $block_name = 'p6';

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
		 * Set register_block_type_args variable on parent
		 * Used for blog template loading
		 *
		 * @since      3.0.6
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 */
		public function register_block_type_args() {
			$this->register_block_type_args = array(
				'view_script_handles' => array( 'magnific-popup' ),
				'style_handles'       => array( 'magnific-popup' ),
			);
		}

		/**
		 * Load Dependencies
		 * Used for blog template loading
		 *
		 * @since      1.0.1
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 */
		public function load_dependencies() {

			require_once GUTENTOR_PATH . 'includes/block-templates/duplex/class-duplex-p6-t1.php';
			require_once GUTENTOR_PATH . 'includes/block-templates/duplex/class-duplex-p6-t2.php';
		}

		/**
		 * Returns attributes for this Block
		 *
		 * @static
		 * @access public
		 * @since 1.0.1
		 * @return array
		 */
		public function get_attrs() {
			$blog_post_attr = array(
				'gID'                             => array(
					'type'    => 'string',
					'default' => '',
				),
				/*column*/
				'blockItemsColumn'                => array(
					'type'    => 'object',
					'default' => array(
						'desktop' => 'grid-md-4',
						'tablet'  => 'grid-sm-4',
						'mobile'  => 'grid-xs-12',
					),
				),
				'timestamp'                       => array(
					'type'    => 'number',
					'default' => 0,
				),
				'gName'                           => array(
					'type'    => 'string',
					'default' => 'gutentor/p6',
				),
				'p6Temp'                          => array(
					'type'    => 'string',
					'default' => 'gutentor_p6_template1',
				),
				'gStyle'                          => array(
					'type'    => 'string',
					'default' => 'gutentor-blog-grid',
				),
				'pTaxTerm'                        => array(
					'type'  => 'array',
					'items' => array(
						'type'  => 'object',
						'label' => array(
							'type' => 'string',
						),
						'value' => array(
							'type' => 'number',
						),
					),
				),
				'pTaxType'                        => array(
					'type'    => 'string',
					'default' => 'category',
				),
				'pTaxOperator'                    => array(
					'type'    => 'string',
					'default' => 'IN',
				),
				'pPostType'                       => array(
					'type'    => 'string',
					'default' => 'post',
				),
				'pIncludePosts'                   => array(
					'type' => 'string',
				),
				'pExcludePosts'                   => array(
					'type' => 'string',
				),
				'pOffsetPosts'                    => array(
					'type' => 'number',
				),
				'postsToShow'                     => array(
					'type'    => 'number',
					'default' => 6,
				),
				'order'                           => array(
					'type'    => 'string',
					'default' => 'desc',
				),
				'orderBy'                         => array(
					'type'    => 'string',
					'default' => 'date',
				),
				'categories'                      => array(
					'type'    => 'string',
					'default' => '',
				),
				'gutentorBlogPostImageLink'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pReverseContent'                 => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pOnColInList'                    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pNoFoundTxt'                     => array(
					'type'    => 'string',
					'default' => 'Nothing Found',
				),
				'gutentorBlogPostImageLinkNewTab' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'fpWooOnPrice'                    => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'fpWooOnFreeTxt'                  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'fpWooFreeTxt'                    => array(
					'type'    => 'string',
					'default' => __( 'Free', 'gutentor' ),
				),
				'fpWooOnRating'                   => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnFPTagMeta1'                   => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnFPTagMeta2'                   => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'eFPOnWl'                         => array(
					'type'    => 'boolean',
					'default' => true,
				),
				/*Featured Post avatar*/
				'pFPOnAvatar'                     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pFPAvatarPos'                    => array(
					'type'    => 'string',
					'default' => 'g-avatar-img-fp-t-l',
				),
				'pFPAvatarSize'                   => array(
					'type'    => 'string',
					'default' => '48',
				),
				'pFPAvatarOColor'                 => array(
					'type'    => 'object',
					'default' => array(
						'enable' => false,
						'normal' => '',
						'hover'  => '',
					),
				),
				'pFPOnByAuthor'                   => array(
					'type'    => 'boolean',
					'default' => false,
				),
			);
			$blog_partial_attrs = array_merge_recursive( $blog_post_attr, $this->get_module_common_attrs() );
			$blog_partial_attrs = array_merge_recursive( $blog_partial_attrs, $this->get_module_query_elements_common_attrs() );
			return array_merge_recursive( $blog_partial_attrs, $this->get_module_featured_post_query_elements_common_attrs() );
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

			$blockID = isset( $attributes['pID'] ) ? $attributes['pID'] : $attributes['gID'];
			$gID     = isset( $attributes['gID'] ) ? $attributes['gID'] : '';
			$output  = '';

			$default_class = gutentor_block_add_default_classes( 'gutentor-p6', $attributes );

			$tag                     = $attributes['mTag'] ? $attributes['mTag'] : 'div';
			$template                = $attributes['p6Temp'] ? $attributes['p6Temp'] : '';
			$align                   = isset( $attributes['align'] ) ? 'align' . $attributes['align'] : '';
			$blockComponentAnimation = isset( $attributes['mAnimation'] ) ? $attributes['mAnimation'] : '';
			$nothing_found_text      = isset( $attributes['pNoFoundTxt'] ) ? $attributes['pNoFoundTxt'] : '';

			/*query args*/
			$query_args = array(
				'posts_per_page'      => isset( $attributes['postsToShow'] ) ? $attributes['postsToShow'] : 6,
				'post_type'           => isset( $attributes['pPostType'] ) ? $attributes['pPostType'] : 'post',
				'orderby'             => isset( $attributes['orderBy'] ) ? $attributes['orderBy'] : 'date',
				'order'               => isset( $attributes['order'] ) ? $attributes['order'] : 'desc',
				'paged'               => isset( $attributes['paged'] ) ? $attributes['paged'] : 1,
				'ignore_sticky_posts' => true,
				'post_status'         => 'publish',
			);

			/*Backward compatible*/
			if ( isset( $attributes['categories'] ) && ! empty( $attributes['categories'] ) ) {
				if ( is_array( $attributes['categories'] ) && ! gutentor_is_array_empty( $attributes['categories'] ) ) {
					$query_args['taxonomy'] = 'category';
					$query_args['term']     = $attributes['categories'];
				}
				if ( ! is_array( $attributes['categories'] ) ) {
					$query_args['taxonomy'] = 'category';
					$query_args['term']     = $attributes['categories'];
				}
			}

			if ( isset( $attributes['pTaxType'] ) && ! empty( $attributes['pTaxType'] ) &&
				isset( $attributes['pTaxTerm'] ) && ! empty( $attributes['pTaxTerm'] ) ) {

				$query_args['taxonomy']    = $attributes['pTaxType'];
				$query_args['taxOperator'] = $attributes['pTaxOperator'] ? $attributes['pTaxOperator'] : 'IN';

				if ( is_array( $attributes['pTaxTerm'] ) ) {
					$p1_terms = array();
					foreach ( $attributes['pTaxTerm'] as $p1_term ) {
						$p1_terms [] = $p1_term['value'];
					}
					$query_args['term'] = $p1_terms;
				} elseif ( is_string( $attributes['pTaxTerm'] ) || is_numeric( $attributes['pTaxTerm'] ) ) {
					$query_args['term'] = $attributes['pTaxTerm'];
				}
			}

			if ( isset( $attributes['pAuthor'] ) && ! empty( $attributes['pAuthor'] ) ) {
				if ( is_array( $attributes['pAuthor'] ) ) {
					$author_list = array();
					foreach ( $attributes['pAuthor'] as $data ) {
						$author_list[] = $data['value'];
					}
					$query_args['author__in'] = $author_list;
				}
			}

			if ( isset( $attributes['offset'] ) ) {
				$query_args['offset'] = $attributes['offset'];
			}
			if ( isset( $attributes['pIncludePosts'] ) && ! empty( $attributes['pIncludePosts'] ) ) {
				$query_args['post__in'] = $attributes['pIncludePosts'];
			}
			if ( isset( $attributes['pExcludePosts'] ) && ! empty( $attributes['pExcludePosts'] ) ) {
				$query_args['post__not_in'] = $attributes['pExcludePosts'];
			}
			if ( isset( $attributes['pOffsetPosts'] ) ) {
				$query_args['offset'] = $attributes['pOffsetPosts'];
			}
			/*Search query*/
			if ( isset( $attributes['s'] ) && ! empty( $attributes['s'] ) ) {
				$query_args['s'] = $attributes['s'];
			}

			$the_query         = new WP_Query( gutentor_get_query( $query_args ) );
			$single_post_class = $the_query->post_count === 1 ? 'gutentor-single-post' : '';

			$output .= '<' . $tag . ' class="' . apply_filters( 'gutentor_post_module_main_wrap_class', gutentor_concat_space( 'section-' . $gID, 'gutentor-post-module', 'gutentor-post-module-p6', $single_post_class, $template, $align, $default_class ), $attributes ) . '" id="' . esc_attr( $blockID ) . '" data-gbid="' . esc_attr( $gID ) . '" ' . GutentorAnimationOptionsDataAttr( $blockComponentAnimation ) . '>' . "\n";
			$output .= apply_filters( 'gutentor_post_module_before_container', '', $attributes );
			$output .= "<div class='" . apply_filters( 'gutentor_post_module_container_class', 'grid-container', $attributes ) . "'>";
			$output .= apply_filters( 'gutentor_post_module_before_block_items', '', $attributes );
			$output .= "<div class='" . apply_filters( 'gutentor_post_module_grid_row_class', 'grid-row', $attributes ) . "' " . gutentor_get_html_attr( apply_filters( 'gutentor_post_module_attr', array(), $attributes ) ) . '>';

			if ( $the_query->have_posts() ) :
				$output .= apply_filters( 'gutentor_post_module_p6_query_data', '', $the_query, $attributes, $content );
			else :
				$output .= '<header class="g-n-f-t-1"><h2 class="g-n-f-title">' . esc_html( $nothing_found_text ) . '</h2></header>';
			endif;

			$output .= '</div>';/*.grid-row*/
			$output .= apply_filters( 'gutentor_post_module_after_block_items', '', $attributes );
			$output .= '</div>';/*.grid-container*/
			$output .= apply_filters( 'gutentor_post_module_after_container', '', $attributes );
			$output .= '</' . $tag . '>';/*.gutentor-blog-post-wrapper*/

			// Restore original Post Data
			wp_reset_postdata();
			return $output;
		}
	}
}
Gutentor_P6::get_instance()->run();
