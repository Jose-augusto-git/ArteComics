<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_P1' ) ) {

	/**
	 * Functions related to Blog Post
	 *
	 * @package Gutentor
	 * @since 1.0.1
	 */
	class Gutentor_P1 extends Gutentor_Block_Base {


		/**
		 * Name of the block.
		 *
		 * @access protected
		 * @since 1.0.1
		 * @var string
		 */
		protected $block_name = 'p1';

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
				'view_script_handles' => array( 'magnific-popup', 'masonry' ),
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
			require_once GUTENTOR_PATH . 'includes/block-templates/normal/class-normal-p1-templates.php';
			require_once GUTENTOR_PATH . 'includes/block-templates/normal/class-normal-p1-woo-templates.php';
			require_once GUTENTOR_PATH . 'includes/block-templates/normal/class-normal-p1-edd-templates.php';
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
					'default' => 'gutentor/p1',
				),
				'p1Temp'                          => array(
					'type'    => 'string',
					'default' => 'gutentor_p1_template1',
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
				'pNoFoundTxt'                     => array(
					'type'    => 'string',
					'default' => 'Nothing Found',
				),
				'pEqlHeight'                      => array(
					'type'    => 'boolean',
					'default' => false,
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
				'gutentorBlogPostImageLinkNewTab' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pContentPos'                     => array(
					'type'    => 'object',
					'default' => array(
						'desktop' => 'g-pos-center',
						'tablet'  => 'g-pos-center',
						'mobile'  => 'g-pos-center',
					),
				),
				'pMsyOn'                          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pTimeline'                       => array(
					'type'    => 'object',
					'default' => array(
						'enable'   => false,
						/*icon*/
						'icon'     => array(
							'label' => 'fa-arrow-down',
							'value' => (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-arrow-down' : 'fas fa-arrow-down',
							'code'  => 'f108',
						),
						'iSize'    => array(
							'type'   => 'px',
							'mobile' => 10,
						),
						'iColor'   => array(
							'enable' => true,
							'normal' => '#fff',
						),
						/*node*/
						'nBgColor' => array(
							'enable' => true,
							'normal' => '#d5dee6',
						),
						'nWidth'   => array(
							'enable' => true,
							'type'   => 'px',
							'mobile' => 30,
						),
						'nHeight'  => array(
							'enable' => true,
							'type'   => 'px',
							'mobile' => 30,
						),
						'border'   => array(
							'rTypeN'   => '%',
							'rTopN'    => '50',
							'rRightN'  => '50',
							'rBottomN' => '50',
							'rLeftN'   => '50',
						),
						/*width*/
						'lColor'   => array(
							'enable' => true,
							'normal' => '#d5dee6',
						),
						'lWidth'   => array(
							'enable' => true,
							'type'   => 'px',
							'mobile' => 3,
						),
					),
				),
				'pTypeWpQuery'                    => array(
					'type'    => 'string',
					'default' => 'default',
				),
				'pWpQuery'                        => array(
					'type'    => 'string',
					'default' => '',
				),
				'pImgDisplayType'                 => array(
					'type'    => 'string',
					'default' => 'bg-image',
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

			$blockID      = isset( $attributes['pID'] ) ? $attributes['pID'] : $attributes['gID'];
			$gID          = isset( $attributes['gID'] ) ? $attributes['gID'] : '';
			$pTypeWpQuery = isset( $attributes['pTypeWpQuery'] ) ? $attributes['pTypeWpQuery'] : false;
			$output       = '';

			$default_class = gutentor_block_add_default_classes( 'gutentor-p1', $attributes );

			$tag                     = $attributes['mTag'] ? $attributes['mTag'] : 'div';
			$pFType                  = $attributes['pFType'] ? $attributes['pFType'] : '';
			$template                = $attributes['p1Temp'] ? $attributes['p1Temp'] : '';
			$enable_equal_height     = isset( $attributes['pEqlHeight'] ) && $attributes['pEqlHeight'] ? 'g-equal-height' : '';
			$align                   = isset( $attributes['align'] ) ? 'align' . $attributes['align'] : '';
			$blockComponentAnimation = isset( $attributes['mAnimation'] ) ? $attributes['mAnimation'] : '';
			$post_type               = isset( $attributes['pPostType'] ) ? $attributes['pPostType'] : '';
			$nothing_found_text      = isset( $attributes['pNoFoundTxt'] ) ? $attributes['pNoFoundTxt'] : '';
			$thumbnail_enable        = isset( $attributes['pOnFImg'] ) ? $attributes['pOnFImg'] : false;
			$timeline                = isset( $attributes['pTimeline'] ) ? $attributes['pTimeline'] : false;
			$timelineEnable          = is_array( $timeline ) && array_key_exists( 'enable', $timeline ) ? $timeline['enable'] : false;
			$timelineClass           = '';

			$thumb_class         = '';
			$access_no_thumbnail = array( 'product', 'download' );
			$gptm_class          = $template === 'gutentor_p1_template5' ? 'gptm-item' : '';
			$pImgDisplayType     = ( isset( $attributes['pImgDisplayType'] ) ) ? $attributes['pImgDisplayType'] : false;

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
			/*load custom template search*/
			global $wp_query;
			$gutentor_template = $wp_query->get(
				'gutentor-templates'
			);
			if ( isset( $gutentor_template['type'] ) && $gutentor_template['type'] === 'search' ) {
				$query_args['s'] = $gutentor_template['s'];
			}
			if ( $pTypeWpQuery === 'default' ) {
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

				if ( isset( $attributes['pOffsetPosts'] ) ) {
					$query_args['offset'] = $attributes['pOffsetPosts'];
				}
				if ( isset( $attributes['pIncludePosts'] ) && ! empty( $attributes['pIncludePosts'] ) ) {
					$query_args['post__in'] = $attributes['pIncludePosts'];
				}
				if ( isset( $attributes['pExcludePosts'] ) && ! empty( $attributes['pExcludePosts'] ) ) {
					$query_args['post__not_in'] = $attributes['pExcludePosts'];
				}

				/*Search query*/
				if ( isset( $attributes['s'] ) && ! empty( $attributes['s'] ) ) {
					$query_args['s'] = $attributes['s'];
				}
			}

			if ( $pTypeWpQuery === 'custom' ) {
				$pWpQueryJson = isset( $attributes['pWpQuery'] ) ? $attributes['pWpQuery'] : false;
				$pWpQueryData = json_decode( $pWpQueryJson, true );
				$query_args   = array_merge( $query_args, $pWpQueryData );
			}

			$the_query = new WP_Query( gutentor_get_query( $query_args ) );

			$output         .= '<' . $tag . ' class="' . apply_filters( 'gutentor_post_module_main_wrap_class', gutentor_concat_space( 'gutentor-post-module', 'section-' . $gID, 'gutentor-post-module-p1', $template, $align, $default_class, $enable_equal_height ), $attributes ) . '" id="' . esc_attr( $blockID ) . '" data-gbid="' . esc_attr( $gID ) . '" ' . GutentorAnimationOptionsDataAttr( $blockComponentAnimation ) . '>' . "\n";
			$output         .= apply_filters( 'gutentor_post_module_before_container', '', $attributes );
			$output         .= "<div class='" . apply_filters( 'gutentor_post_module_container_class', 'grid-container', $attributes ) . "'>";
			$output         .= apply_filters( 'gutentor_post_module_before_block_items', '', $attributes );
			$output         .= "<div class='" . apply_filters( 'gutentor_post_module_grid_row_class', 'grid-row', $attributes ) . "' " . gutentor_get_html_attr( apply_filters( 'gutentor_post_module_attr', array(), $attributes ) ) . '>';
			$cat_style       = '';
			$post_categories = array();
			if ( $the_query->have_posts() ) :
				$index = 0;
				while ( $the_query->have_posts() ) :
					$the_query->the_post();
					$post_categories = array_merge( $post_categories, wp_get_post_categories( get_the_ID() ) );
					$video_url       = get_post_meta( get_the_ID(), 'gutentor_meta_video_url', true );
					$video_id        = get_post_meta( get_the_ID(), 'gutentor_meta_video_id', true );
					if ( ! in_array( $post_type, $access_no_thumbnail ) ) {
						$thumb_class = has_post_thumbnail() && $thumbnail_enable ? '' : 'gutentor-post-no-thumb';
						$thumb_class = ( ( $video_url || $video_id ) && gutentor_pro_active() && $pFType === 'video' ) ? '' : $thumb_class;
						$thumb_class = $template === 'gutentor_p1_template5' && $pImgDisplayType === 'normal-image' ? '' : $thumb_class;
					}
					if ( $timelineEnable ) {
						if ( $index % 2 === 0 ) {
							$timelineClass = 'g-timeline-even';
						} else {
							$timelineClass = 'g-timeline-odd';
						}
					}
					$output .= "<article class='" . apply_filters( 'gutentor_post_module_grid_column_class', gutentor_concat_space( 'gutentor-post', $thumb_class, $timelineClass ), $attributes ) . "'>";
					if ( $timelineEnable ) {
						$output .= "<div class='g-timeline-post-wrap'>";
						$output .= "<div class='" . apply_filters( 'gutentor_post_module_post_item', gutentor_concat_space( 'gutentor-post-item', $gptm_class ), $attributes ) . "'>";
						$output .= apply_filters( 'gutentor_post_module_p1_query_data', '', get_post(), $attributes );
						$output .= '</div>';/*.gutentor-post-item*/
						$output .= '</div>';/*.g-timeline-post-wrap*/
						$output .= apply_filters( 'gutentor_post_module_p1_query_data_after', '', get_post(), $attributes );

					} else {
						$output .= "<div class='" . apply_filters( 'gutentor_post_module_post_item', gutentor_concat_space( 'gutentor-post-item', $gptm_class ), $attributes ) . "'>";
						$output .= apply_filters( 'gutentor_post_module_p1_query_data', '', get_post(), $attributes );
						$output .= '</div>';/*.gutentor-post-item*/

					}
					$output .= '</article>';/*.article*/
					$index++;
				endwhile;
			else :
				$output .= '<header class="g-n-f-t-1"><h2 class="g-n-f-title">' . esc_html( $nothing_found_text ) . '</h2></header>';
			endif;
			$output .= '</div>';/*.grid-row*/
			$output .= apply_filters( 'gutentor_post_module_after_block_items', '', $attributes );
			$output .= '</div>';/*.grid-container*/
			$output .= apply_filters( 'gutentor_post_module_after_container', '', $attributes );
			if ( $post_categories && $content === 'ajax' ) {
				$cat_style = gutentor_pm_post_dynamic_categories_color( array_unique( $post_categories ) );
				$output   .= '<style>' . $cat_style . '</style>';
			}
			$output .= '</' . $tag . '>';/*
			.gutentor-blog-post-wrapper*/
			// Restore original Post Data
			wp_reset_postdata();
			return $output;
		}
	}
}
Gutentor_P1::get_instance()->run();
