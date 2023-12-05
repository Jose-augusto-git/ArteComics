<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_P5' ) ) {

	/**
	 * Functions related to Blog Post
	 *
	 * @package Gutentor
	 * @since 1.0.1
	 */
	class Gutentor_P5 extends Gutentor_Block_Base {


		/**
		 * Name of the block.
		 *
		 * @access protected
		 * @since 1.0.1
		 * @var string
		 */
		protected $block_name = 'p5';

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @return object
		 * @since 1.0.1
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
				'view_script_handles' => array( 'acmeticker' ),
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
			require_once GUTENTOR_PATH . 'includes/block-templates/ticker/class-ticker-p5-templates.php';
		}

		/**
		 * Returns attributes for this Block
		 *
		 * @static
		 * @access public
		 * @return array
		 * @since 1.0.1
		 */
		public function get_attrs() {
			$blog_post_attr     = array(
				'gID'                             => array(
					'type'    => 'string',
					'default' => '',
				),
				'timestamp'                       => array(
					'type'    => 'number',
					'default' => 0,
				),
				'gName'                           => array(
					'type'    => 'string',
					'default' => 'gutentor/p5',
				),
				'p5Temp'                          => array(
					'type'    => 'string',
					'default' => 'gutentor_p5_template1',
				),
				'pTaxType'                        => array(
					'type'    => 'string',
					'default' => 'category',
				),
				'pTaxOperator'                    => array(
					'type'    => 'string',
					'default' => 'IN',
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
				'gStyle'                          => array(
					'type'    => 'string',
					'default' => 'gutentor-blog-grid',
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
				'gutentorBlogPostImageLinkNewTab' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'p5OnNewsTxt'                     => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'p5Type'                          => array(
					'type'    => 'string',
					'default' => 'marquee',
				),
				'p5Direction'                     => array(
					'type'    => 'string',
					'default' => 'up',
				),
				'p5NewsTxt'                       => array(
					'type'    => 'string',
					'default' => __( 'News' ),
				),
				'p5Speed'                         => array(
					'type'    => 'number',
					'default' => 0.05,
				),
				'p5PauseOnHover'                  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'p5OnControl'                     => array(
					'type'    => 'boolean',
					'default' => true,
				),
			);
			$blog_partial_attrs = array_merge_recursive( $blog_post_attr, $this->get_module_common_attrs() );
			return array_merge_recursive( $blog_partial_attrs, $this->get_module_query_elements_common_attrs() );
		}


		/**
		 * Render Blog Post Data
		 *
		 * @param array  $attributes
		 * @param string $content
		 * @return string
		 * @since    1.0.1
		 * @access   public
		 */
		public function render_callback( $attributes, $content ) {
			$blockID = isset( $attributes['pID'] ) ? $attributes['pID'] : $attributes['gID'];
			$gID     = isset( $attributes['gID'] ) ? $attributes['gID'] : '';
			$output  = '';

			$default_class = gutentor_block_add_default_classes( 'gutentor-p5', $attributes );

			// the query
			$args = array(
				'posts_per_page' => $attributes['postsToShow'],
				'post_type'      => isset( $attributes['pPostType'] ) ? $attributes['pPostType'] : 'post',
				'orderby'        => $attributes['orderBy'],
				'order'          => $attributes['order'],
				'cat'            => $attributes['categories'],
				'paged'          => isset( $attributes['paged'] ) ? $attributes['paged'] : 1,
			);

			if ( isset( $attributes['pTaxType'] ) && ! empty( $attributes['pTaxType'] ) &&
				isset( $attributes['pTaxTerm'] ) && ! empty( $attributes['pTaxTerm'] ) ) {

				$args['taxonomy']    = $attributes['pTaxType'];
				$args['taxOperator'] = $attributes['pTaxOperator'] ? $attributes['pTaxOperator'] : 'IN';
				if ( is_array( $attributes['pTaxTerm'] ) ) {
					$p1_terms = array();
					foreach ( $attributes['pTaxTerm'] as $p1_term ) {
						$p1_terms [] = $p1_term['value'];
					}
					$args['term'] = $p1_terms;
				} elseif ( is_string( $attributes['pTaxTerm'] ) || is_numeric( $attributes['pTaxTerm'] ) ) {
					$args['term'] = $attributes['pTaxTerm'];
				}
			}
			if ( isset( $attributes['pAuthor'] ) && ! empty( $attributes['pAuthor'] ) ) {
				if ( is_array( $attributes['pAuthor'] ) ) {
					$author_list = array();
					foreach ( $attributes['pAuthor'] as $data ) {
						$author_list[] = $data['value'];
					}
					$args['author__in'] = $author_list;
				}
			}

			if ( isset( $attributes['pIncludePosts'] ) && ! empty( $attributes['pIncludePosts'] ) ) {
				$args['post__in'] = $attributes['pIncludePosts'];
			}
			if ( isset( $attributes['pExcludePosts'] ) && ! empty( $attributes['pExcludePosts'] ) ) {
				$args['post__not_in'] = $attributes['pExcludePosts'];
			}
			if ( isset( $attributes['pOffsetPosts'] ) ) {
				$args['offset'] = $attributes['pOffsetPosts'];
			}
			$tag                     = $attributes['mTag'] ? $attributes['mTag'] : 'div';
			$news_ticker_header      = $attributes['p5NewsTxt'] ? $attributes['p5NewsTxt'] : '';
			$template                = $attributes['p5Temp'] ? $attributes['p5Temp'] : '';
			$align                   = isset( $attributes['align'] ) ? 'align' . $attributes['align'] : '';
			$blockComponentAnimation = isset( $attributes['mAnimation'] ) ? $attributes['mAnimation'] : '';

			$the_query = new WP_Query( gutentor_get_query( $args ) );
			if ( $the_query->have_posts() ) :
				$output .= '<' . $tag . ' class="' . apply_filters( 'gutentor_post_module_main_wrap_class', gutentor_concat_space( 'gutentor-post-module', 'gutentor-post-module-p5', 'section-' . $gID, $template, $align, $default_class ), $attributes ) . '" id="' . esc_attr( $blockID ) . '" data-gbid="' . esc_attr( $gID ) . '" ' . GutentorAnimationOptionsDataAttr( $blockComponentAnimation ) . '' . gutentor_get_html_attr( apply_filters( 'gutentor_edit_news_ticker_data_attr', array(), $attributes ) ) . '>' . "\n";
				$output .= apply_filters( 'gutentor_post_module_before_container', '', $attributes );
				$output .= "<div class='" . apply_filters( 'gutentor_post_module_p5_newsticker_wrap_class', 'gutentor-news-ticker', $attributes ) . "'>";
				$output .= apply_filters( 'gutentor_post_module_before_block_items', '', $attributes );
				if ( $attributes['p5OnNewsTxt'] ) {
					$output .= "<div class='gutentor-news-ticker-label'>" . $news_ticker_header . '</div>';/*.ul*/
				}
				$output .= "<div class='gutentor-news-ticker-box'>";
				$output .= "<div class='gutentor-news-ticker-wrap'>";
				$output .= "<ul class='gutentor-news-ticker-data'>";
				while ( $the_query->have_posts() ) :
					$the_query->the_post();
					$output .= '<li>';
					$output .= apply_filters( 'gutentor_post_module_p5_query_data', '', get_post(), $attributes );
					$output .= '</li>';/*.li*/
				endwhile;
				$output .= '</ul>';/*.ul*/
				$output .= '</div>';/*.gutentor-news-ticker-wrap*/
				$output .= '</div>';/*.gutentor-news-ticker-box*/

				if ( 'vertical' === $attributes['p5Type'] ) {
					$hor = ' gutentor-news-ticker-vertical-controls';
				} else {
					$hor = ' gutentor-news-ticker-horizontal-controls';
				}

				if ( $attributes['p5OnControl'] ) {
					$output .= "<div class='gutentor-news-ticker-controls" . $hor . "'>";/*.ul*/
					if ( $attributes['p5Type'] !== 'marquee' ) {
						$output .= '<Button type="button" class="gutentor-news-ticker-arrow gutentor-news-ticker-prev"></Button>';
					}
					$output .= '<Button type="button" class="gutentor-news-ticker-action gutentor-news-ticker-pause"></Button>';
					if ( $attributes['p5Type'] !== 'marquee' ) {
						$output .= '<Button type="button" class="gutentor-news-ticker-arrow gutentor-news-ticker-next"></Button>';
					}
					$output .= '</div>';/*.gutentor-news-ticker-controls*/
				}

				$output .= apply_filters( 'gutentor_post_module_after_block_items', '', $attributes );
				$output .= '</div>';/*.grid-container*/
				$output .= apply_filters( 'gutentor_post_module_after_container', '', $attributes );
				$output .= '</' . $tag . '>';/*.gutentor-blog-post-wrapper*/
			endif;

			// Restore original Post Data
			wp_reset_postdata();
			return $output;
		}
	}
}
Gutentor_P5::get_instance()->run();
