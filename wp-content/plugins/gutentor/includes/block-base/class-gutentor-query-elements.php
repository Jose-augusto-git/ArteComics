<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Query_Elements' ) ) {

	/**
	 * Base Class For Gutentor for common functions
	 *
	 * @package Gutentor
	 * @since 1.0.1
	 */
	class Gutentor_Query_Elements {

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @return object
		 * @since 1.0.1
		 */
		public static function get_base_instance() {
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
		 * Title
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_title( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnTitle'] ) && $attributes['pOnTitle'] ) {
				$title_tag = $attributes['pTitleTag'];
				$output   .= '<' . $title_tag . ' class="gutentor-post-title">';
				$output   .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
				$output   .= get_the_title();
				$output   .= '</a>';
				$output   .= '</' . $title_tag . '>';
			}
			return $output;

		}

		/**
		 * Title
		 *
		 * @static
		 * @access public
		 * @param {array} $element
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_dynamic_element( $element, $post, $attributes ) {
			$output  = '';
			$on      = ( isset( $element['on'] ) && $element['on'] );
			$itemVal = ( isset( $element['itemValue'] ) && $element['itemValue'] ) ? $element['itemValue'] : false;
			if ( $on && $itemVal ) {
				$terms    = gutentor_get_dynamic_element( $post );
				$selector = str_replace( '/', '-', $itemVal );
				if ( strpos( $itemVal, 'tax/' ) !== false ) {
					$terms = is_array( $terms ) && array_key_exists( $itemVal, $terms ) ? $terms[ $itemVal ] : false;
					if ( $terms && ! is_wp_error( $terms ) ) :
						global $wp_rewrite;
						$rel     = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';
						$i       = 0;
						$output .= '<div class="g-dynamic-el gutentor-categories ' . $selector . '">';
						foreach ( $terms as $term ) {
							if ( 0 <= $i ) {
								$output .= '<a href="' . get_term_link( $term->term_id ) . ' " class="post-category gutentor-cat-' . $term->slug . '" ' . $rel . '>' . $term->name . '</a>';
							}
							$output .= ' ';
							++$i;
						}
						$output .= '</div>';
					endif;
				}
				if ( strpos( $itemVal, 'meta/' ) !== false ) {
					$output .= '<div class="g-dynamic-el ' . $selector . '">';
					$output .= is_array( $terms ) && array_key_exists( $itemVal, $terms ) ? $terms[ $itemVal ] : '';
					$output .= '</div>';
				}
			}
			return $output;

		}


		/**
		 * get_term_dynamic_element
		 *
		 * @static
		 * @access public
		 * @param {array} $element
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_term_dynamic_element( $element, $post, $attributes ) {
			$output   = '';
			$on       = ( isset( $element['on'] ) && $element['on'] );
			$itemVal  = ( isset( $element['itemValue'] ) && $element['itemValue'] ) ? $element['itemValue'] : false;
			$selector = 'g-' . str_replace( '/', '-', $itemVal );
			if ( $on && $itemVal ) {
				$terms = gutentor_get_term_dynamic_element( $post );
				if ( strpos( $itemVal, 'meta/' ) !== false ) {
					$output .= '<div class="g-dynamic-el ' . $selector . '">';
					$output .= is_array( $terms ) && array_key_exists( $itemVal, $terms ) ? $terms[ $itemVal ] : '';
					$output .= '</div>';
				}
			}
			return $output;

		}


		/**
		 * Description
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_description( $post, $attributes ) {
			$output = '';
			if ( ! isset( $attributes['PExcerptLen'] ) || ! isset( $attributes['pOnDesc'] ) ) {
				return $output;
			}
			if ( $attributes['PExcerptLen'] > 0 && $attributes['pOnDesc'] ) {
				$in_words = ( isset( $attributes['pExcerptLenInWords'] ) && $attributes['pExcerptLenInWords'] );
				$output  .= '<div class="gutentor-post-desc">';
				$output  .= gutentor_get_excerpt_by_id( $post->ID, $attributes['PExcerptLen'], $in_words );
				$output  .= '</div>';
			}
			return $output;

		}

		/**
		 * Featured Image
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_featured_image( $post, $attributes ) {
			$output         = '';
			$overlay_obj    = ( isset( $attributes['pFImgOColor'] ) ) ? $attributes['pFImgOColor'] : false;
			$overlay_enable = ( $overlay_obj && array_key_exists( 'enable', $overlay_obj ) ) ? $attributes['pFImgOColor']['enable'] : false;

			$overlay              = ( $overlay_enable ) ? 'gutentor-overlay' : '';
			$enable_image_display = isset( $attributes['pOnImgDisplayOpt'] ) ? $attributes['pOnImgDisplayOpt'] : false;
			$image_display        = isset( $attributes['pImgDisplayOpt'] ) ? $attributes['pImgDisplayOpt'] : false;
			if ( isset( $attributes['pOnFImg'] ) && $attributes['pOnFImg'] ) {
				$image_output = '';
				if ( has_post_thumbnail() ) {
					if ( 'bg-image' == $image_display && $enable_image_display ) {
						$url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $attributes['pFImgSize'] );
						if ( $url ) {
							$image_output .= '<div class="' . gutentor_concat_space( 'gutentor-bg-image', $overlay ) . '" style="background-image:url(' . esc_url( $url[0] ) . ')">';
							$image_output .= '</div>';
						}
					} else {
						$image_output .= '<div class="' . gutentor_concat_space( 'gutentor-image-thumb', $overlay ) . '">';
						$image_output .= get_the_post_thumbnail( $post->ID, $attributes['pFImgSize'], '' );
						$image_output .= '</div>';
					}
					$output .= apply_filters( 'gutentor_save_item_image_display_data', $image_output, get_permalink(), $attributes );
				}
			}
			$output = apply_filters( 'gutentor_save_post_module_featured_image_data', $output, $post, $attributes );
			return $output;

		}

		/**
		 * Avatar Date
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_avatar_data( $post, $attributes ) {
			$output           = '';
			$enable_by_author = ( isset( $attributes['pAccessAvatar'] ) ) ? $attributes['pAccessAvatar'] : false;
			if ( ! $enable_by_author ) {
				return $output;
			}
			$enable_by_author = ( isset( $attributes['pOnByAuthor'] ) ) ? $attributes['pOnByAuthor'] : false;
			$avatar_size      = ( isset( $attributes['pAvatarSize'] ) ) ? $attributes['pAvatarSize'] : false;
			$avatar_url       = $avatar_size ? get_avatar_url( $post->post_author, $avatar_size ) : false;
			$author_name      = get_the_author_meta( 'display_name', $post->post_author );

			if ( isset( $attributes['pOnAvatar'] ) && $attributes['pOnAvatar'] ) {
				$overlay_obj    = ( isset( $attributes['pAvatarOColor'] ) ) ? $attributes['pAvatarOColor'] : false;
				$overlay_enable = ( $overlay_obj && array_key_exists( 'enable', $overlay_obj ) ) ? $attributes['pAvatarOColor']['enable'] : false;
				$overlay_class  = ( $overlay_enable ) ? 'gutentor-overlay' : '';
				if ( $avatar_url ) {
					$output .= '<div class="g-avatar-wrap">';
					$output .= '<div class="' . gutentor_concat_space( 'gutentor-avatar', $overlay_class ) . '">';
					$output .= '<img class="gutentor-avatar-img" src="' . $avatar_url . '"/>';
					$output .= '</div>';
					if ( $enable_by_author && $author_name ) {
						$output .= '<div class="g-avatar-by-author">';
						$output .= esc_html__( 'By', 'gutentor' ) . ' ' . ucwords( $author_name );
						$output .= '</div>';
					}
					$output .= '</div>';
				}
			}
			return $output;
		}

		/**
		 * Avatar Image
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_fp_avatar_data( $post, $attributes ) {
			$output           = '';
			$enable_by_author = ( isset( $attributes['pAccessAvatar'] ) ) ? $attributes['pAccessAvatar'] : false;
			if ( ! $enable_by_author ) {
				return $output;
			}
			$enable_by_author = ( isset( $attributes['pFPOnByAuthor'] ) ) ? $attributes['pFPOnByAuthor'] : false;
			$avatar_size      = ( isset( $attributes['pFPAvatarSize'] ) ) ? $attributes['pFPAvatarSize'] : false;
			$avatar_url       = $avatar_size ? get_avatar_url( $post->post_author, $avatar_size ) : false;
			$author_name      = get_the_author_meta( 'display_name', $post->post_author );

			if ( isset( $attributes['pFPOnAvatar'] ) && $attributes['pFPOnAvatar'] ) {
				$overlay_obj    = ( isset( $attributes['pFPAvatarOColor'] ) ) ? $attributes['pFPAvatarOColor'] : false;
				$overlay_enable = ( $overlay_obj && array_key_exists( 'enable', $overlay_obj ) ) ? $attributes['pFPAvatarOColor']['enable'] : false;
				$overlay_class  = ( $overlay_enable ) ? 'gutentor-overlay' : '';
				if ( $avatar_url ) {
					$output .= '<div class="g-fp-avatar-wrap">';
					$output .= '<div class="' . gutentor_concat_space( 'gutentor-fp-avatar', $overlay_class ) . '">';
					$output .= '<img class="gutentor-fp-avatar-img" src="' . $avatar_url . '"/>';
					$output .= '</div>';
					if ( $enable_by_author && $author_name ) {
						$output .= '<div class="g-fp-avatar-by-author">';
						$output .= esc_html__( 'By', 'gutentor' ) . ucwords( $author_name );
						$output .= '</div>';
					}
					$output .= '</div>';
				}
			}
			return $output;
		}

		/**
		 * Primary Meta Date
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_primary_meta_date_data( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnDateMeta1'] ) && $attributes['pOnDateMeta1'] ) {
				$dateFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-calendar' : 'far fa-calendar-alt';
				$output              .= '<div class="posted-on">';
				if ( isset( $attributes['pOnIconMeta1'] ) && $attributes['pOnIconMeta1'] ) {
					$output .= '<i class="' . $dateFontAwesomeClass . '"></i>';
				}
				$output .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . get_the_date() . '</a>';
				$output .= '</div>';

			}
			return $output;
		}

		/**
		 * Secondary Meta Date
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_secondary_meta_date_data( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnDateMeta2'] ) && $attributes['pOnDateMeta2'] ) {

				$dateFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-calendar' : 'far fa-calendar-alt';
				$output              .= '<div class="posted-on">';
				if ( isset( $attributes['pOnIconMeta2'] ) && $attributes['pOnIconMeta2'] ) {
					$output .= '<i class="' . $dateFontAwesomeClass . '"></i>';
				}
				$output .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . get_the_date() . '</a>';
				$output .= '</div>';

			}
			return $output;

		}

		/**
		 * Primary Meta Comment
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_primary_meta_comment_data( $post, $attributes ) {
			$output = '';
			if ( ! is_object( $post ) ) {
				return $output;
			}
			$comment_data = wp_count_comments( $post->ID );

			if ( ! $comment_data->total_comments ) {
				return $output;
			}
			if ( isset( $attributes['pOnCommentMeta1'] ) && $attributes['pOnCommentMeta1'] ) {
				$commentFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-comment' : 'fas fa-comment';
				$output                 .= '<div class="comments-link">';
				if ( isset( $attributes['pOnIconMeta1'] ) && $attributes['pOnIconMeta1'] ) {
					$output .= '<i class="' . $commentFontAwesomeClass . '"></i>';
				}
				$output .= $comment_data->total_comments;
				$output .= '</div>';

			}
			return $output;
		}

		/**
		 * Secondary Meta Comment
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_secondary_meta_comment_data( $post, $attributes ) {
			$output       = '';
			$comment_data = wp_count_comments( $post->ID );

			if ( ! $comment_data->total_comments ) {
				return $output;
			}
			if ( isset( $attributes['pOnCommentMeta2'] ) && $attributes['pOnCommentMeta2'] ) {
				$commentFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-comment' : 'fas fa-comment';
				$output                 .= '<div class="comments-link">';
				if ( isset( $attributes['pOnIconMeta2'] ) && $attributes['pOnIconMeta2'] ) {
					$output .= '<i class="' . $commentFontAwesomeClass . '"></i>';
				}
				$output .= $comment_data->total_comments;
				$output .= '</div>';

			}
			return $output;
		}

		/**
		 * Primary Meta Author
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_primary_meta_author_data( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnAuthorMeta1'] ) && $attributes['pOnAuthorMeta1'] ) {
				$authorFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-user' : 'far fa-user';
				$output                .= '<div class="author vcard">';
				if ( isset( $attributes['pOnIconMeta1'] ) && $attributes['pOnIconMeta1'] ) {
					$output .= '<i class="' . $authorFontAwesomeClass . '"></i>';
				}
				$output .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . get_the_author() . '</a>';
				$output .= '</div>';
			}
			return $output;

		}

		/**
		 * Secondary Meta Author
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_secondary_meta_author_data( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnAuthorMeta2'] ) && $attributes['pOnAuthorMeta2'] ) {
				$authorFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-user' : 'far fa-user';
				$output                .= '<div class="author vcard">';
				if ( isset( $attributes['pOnIconMeta2'] ) && $attributes['pOnIconMeta2'] ) {
					$output .= '<i class="' . $authorFontAwesomeClass . '"></i>';
				}
				$output .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . get_the_author() . '</a>';
				$output .= '</div>';
			}
			return $output;

		}


		/**
		 * Primary Meta Categories
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_primary_meta_categories_data( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnCatMeta1'] ) && $attributes['pOnCatMeta1'] ) {
				$categories_list = get_the_category_list( esc_html__( ', ', 'gutentor' ) );
				if ( $categories_list ) {
					$catFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-tags' : 'fas fa-tags';
					$output             .= '<div class="gutentor-meta-categories">';
					if ( isset( $attributes['pOnIconMeta1'] ) && $attributes['pOnIconMeta1'] ) {
						$output .= '<i class="' . $catFontAwesomeClass . '"></i>';
					}
					$output .= $categories_list . '</div>';
				}
			}
			return $output;

		}


		/**
		 * Primary Meta Tags
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_primary_meta_tags_data( $post, $attributes ) {

			$output              = '';
			$get_postType        = isset( $attributes['pPostType'] ) ? $attributes['pPostType'] : 'post';
			$catFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-tags' : 'fas fa-tags';
			if ( isset( $attributes['pOnTagMeta1'] ) && $attributes['pOnTagMeta1'] ) {
				if ( $get_postType === 'product' || $get_postType === 'download' ) {
					$terms = '';
					if ( $get_postType === 'product' && gutentor_is_woocommerce_active() ) {
						$terms = wp_get_post_terms( $post->ID, 'product_tag' );
					} elseif ( $get_postType === 'download' && gutentor_is_edd_active() ) {
						$terms = wp_get_post_terms( $post->ID, 'download_tag' );
					}
					if ( is_object( $terms ) || is_array( $terms ) ) {
						if ( count( $terms ) > 0 ) {
							$tag_output = array();
							$output    .= '<div class="gutentor-meta-categories">';
							if ( isset( $attributes['pOnIconMeta1'] ) && $attributes['pOnIconMeta1'] ) {
								$output .= '<i class="' . $catFontAwesomeClass . '"></i>';
							}
							foreach ( $terms as $term ) {
								$tag_output[] = '<a href="' . get_term_link( $term->term_id ) . '" rel="tag">' . $term->name . '</a>';
							}
							$output .= implode( ', ', $tag_output );
							$output .= '</div>';
						}
					}
				} else {
					$tags_list = get_the_tag_list( '', ', ' );
					if ( $tags_list ) {
						$output .= '<div class="gutentor-meta-categories">';
						if ( isset( $attributes['pOnIconMeta1'] ) && $attributes['pOnIconMeta1'] ) {
							$output .= '<i class="' . $catFontAwesomeClass . '"></i>';
						}
						$output .= $tags_list . '</div>';
					}
				}
			}
			return $output;
		}

		/**
		 * Secondary Categories
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_secondary_meta_category_data( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnCatMeta2'] ) && $attributes['pOnCatMeta2'] ) {
				$categories_list = get_the_category_list( esc_html__( ', ', 'gutentor' ) );
				if ( $categories_list ) {
					$catFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-tags' : 'fas fa-tags';
					$output             .= '<div class="gutentor-meta-categories">';
					if ( isset( $attributes['pOnIconMeta2'] ) && $attributes['pOnIconMeta2'] ) {
						$output .= '<i class="' . $catFontAwesomeClass . '"></i>';
					}
					$output .= $categories_list . '</div>';

				}
			}
			return $output;

		}

		/**
		 * Secondary Tags
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_secondary_meta_tag_data( $post, $attributes ) {
			$output              = '';
			$get_postType        = isset( $attributes['pPostType'] ) ? $attributes['pPostType'] : 'post';
			$catFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-tags' : 'fas fa-tags';
			if ( isset( $attributes['pOnTagMeta2'] ) && $attributes['pOnTagMeta2'] ) {
				if ( $get_postType === 'product' || $get_postType === 'download' ) {
					$terms = '';
					if ( $get_postType === 'product' && gutentor_is_woocommerce_active() ) {
						$terms = wp_get_post_terms( $post->ID, 'product_tag' );
					} elseif ( $get_postType === 'download' && gutentor_is_edd_active() ) {
						$terms = wp_get_post_terms( $post->ID, 'download_tag' );
					}
					if ( is_object( $terms ) || is_array( $terms ) ) {
						if ( count( $terms ) > 0 ) {
							$tag_output = array();
							$output    .= '<div class="gutentor-meta-categories">';
							if ( isset( $attributes['pOnIconMeta2'] ) && $attributes['pOnIconMeta2'] ) {
								$output .= '<i class="' . $catFontAwesomeClass . '"></i>';
							}
							foreach ( $terms as $term ) {
								$tag_output[] = '<a href="' . get_term_link( $term->term_id ) . '" rel="tag">' . $term->name . '</a>';
							}
							$output .= implode( ', ', $tag_output );
							$output .= '</div>';
						}
					}
				} else {
					$tags_list = get_the_tag_list( '', ', ' );
					if ( $tags_list ) {
						$output .= '<div class="gutentor-meta-categories">';
						if ( isset( $attributes['pOnIconMeta2'] ) && $attributes['pOnIconMeta2'] ) {
							$output .= '<i class="' . $catFontAwesomeClass . '"></i>';
						}
						$output .= $tags_list . '</div>';
					}
				}
			}
			return $output;

		}

		/**
		 * Primary Entry Meta
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_primary_meta_sorting_data( $post, $attributes ) {
			$meta_sorting = array_key_exists( 'pMeta1Sorting', $attributes ) ? $attributes['pMeta1Sorting'] : false;
			$output       = '';
			if ( ! $meta_sorting ) {
				return $output;
			}
			$output .= '<div class="gutentor-entry-meta gutentor-entry-meta-primary">';
			foreach ( $meta_sorting as $element ) :
				if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
					return $output;
				}
				switch ( $element['itemValue'] ) {
					case 'meta-author':
						$output .= $this->get_primary_meta_author_data( $post, $attributes );
						break;
					case 'meta-date':
						$output .= $this->get_primary_meta_date_data( $post, $attributes );
						break;
					case 'meta-category':
						$output .= $this->get_primary_meta_categories_data( $post, $attributes );
						break;
					case 'meta-tag':
						$output .= $this->get_primary_meta_tags_data( $post, $attributes );
						break;
					case 'meta-comment':
						$output .= $this->get_primary_meta_comment_data( $post, $attributes );
						break;
					default:
						$output .= $this->get_dynamic_element( $element, $post, $attributes );
						break;
				}
			endforeach;
			$output .= '</div>';/*.entry-meta*/
			return $output;

		}

		/**
		 * Primary Entry Meta
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_secondary_meta_sorting_data( $post, $attributes ) {
			$meta_sorting = array_key_exists( 'pMeta2Sorting', $attributes ) ? $attributes['pMeta2Sorting'] : false;
			$output       = '';
			if ( ! $meta_sorting ) {
				return $output;
			}
			$output .= '<div class="gutentor-entry-meta gutentor-entry-meta-secondary">';
			foreach ( $meta_sorting as $element ) :
				if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
					return $output;
				}
				switch ( $element['itemValue'] ) {
					case 'meta-author':
						$output .= $this->get_secondary_meta_author_data( $post, $attributes );
						break;
					case 'meta-date':
						$output .= $this->get_secondary_meta_date_data( $post, $attributes );
						break;
					case 'meta-category':
						$output .= $this->get_secondary_meta_category_data( $post, $attributes );
						break;
					case 'meta-tag':
						$output .= $this->get_secondary_meta_tag_data( $post, $attributes );
						break;
					case 'meta-comment':
						$output .= $this->get_secondary_meta_comment_data( $post, $attributes );
						break;
                    default:
                        $output .= $this->get_dynamic_element( $element, $post, $attributes );
                        break;
				}
			endforeach;
			$output .= '</div>';/*.entry-meta*/
			return $output;

		}

		/**
		 * Primary Meta
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_primary_meta( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnMeta1'] ) && $attributes['pOnMeta1'] ) {
				$output .= $this->get_primary_meta_sorting_data( $post, $attributes );
			}
			return $output;
		}

		/**
		 * Secondary Meta
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_secondary_meta( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnMeta2'] ) && $attributes['pOnMeta2'] ) {
				$output .= $this->get_secondary_meta_sorting_data( $post, $attributes );
			}
			return $output;
		}

		/**
		 * Button
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_button( $post, $attributes ) {
			$output = $icon = '';
			if ( isset( $attributes['pOnBtn'] ) && $attributes['pOnBtn'] ) {
				$default_class       = gutentor_concat_space( 'gutentor-button', 'gutentor-post-button' );
				$icon_options        = ( isset( $attributes['pBtnIconOpt'] ) ) ? $attributes['pBtnIconOpt'] : '';
				$icon_position_class = GutentorButtonOptionsClasses( $icon_options );
				if ( $icon_position_class == 'gutentor-icon-before' || $icon_position_class == 'gutentor-icon-after' ) {
					$icon = ( isset( $attributes['pBtnIcon'] ) && $attributes['pBtnIcon']['value'] ) ? '<i class="gutentor-button-icon ' . $attributes['pBtnIcon']['value'] . '" ></i>' : '';
				}
				$custom_class = ( isset( $attributes['pBtnCName'] ) ) ? $attributes['pBtnCName'] : '';
				$link_options = ( isset( $attributes['pBtnLink'] ) ) ? $attributes['pBtnLink'] : '';
				$output      .= '<a class="' . gutentor_concat_space( $default_class, $custom_class, $icon_position_class ) . '" ' . apply_filters( 'gutentor_save_link_attr', '', esc_url( get_permalink() ), $link_options ) . '>' . $icon . '<span>' . esc_html( $attributes['pBtnText'] ) . '</span></a>';
			}
			$output = apply_filters( 'gutentor_edit_post_module_readmore_button_data', $output, $post, $attributes );
			return $output;

		}

		/**
		 * Preview Button
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_preview_button( $post, $attributes ) {
			$output    = $icon = '';
			$post_type = ( isset( $attributes['pPostType'] ) ) ? $attributes['pPostType'] : 'post';
			$demo_url  = get_permalink( $post->ID );
			if ( $post_type === 'download' ) {
				$demo_url = get_post_meta( $post->ID, 'gutentor_edd_demo_url', true );
				$demo_url = empty( $demo_url ) ? get_permalink( $post->ID ) : $demo_url;
			}
			$demo_url = apply_filters( 'gutentor_get_demo_url', $demo_url, $post, $attributes );
			if ( isset( $attributes['q1OnBtn'] ) && $attributes['q1OnBtn'] ) {
				$default_class       = gutentor_concat_space( 'gutentor-button', 'gutentor-preview-button' );
				$icon_options        = ( isset( $attributes['q1BtnIconOpt'] ) ) ? $attributes['q1BtnIconOpt'] : '';
				$icon_position_class = GutentorButtonOptionsClasses( $icon_options );
				if ( $icon_position_class == 'gutentor-icon-before' || $icon_position_class == 'gutentor-icon-after' ) {
					$icon = ( isset( $attributes['q1BtnIcon'] ) && $attributes['q1BtnIcon']['value'] ) ? '<i class="gutentor-button-icon ' . $attributes['q1BtnIcon']['value'] . '" ></i>' : '';
				}
				$custom_class = ( isset( $attributes['q1BtnCName'] ) ) ? $attributes['q1BtnCName'] : '';
				$link_options = ( isset( $attributes['q1BtnLink'] ) ) ? $attributes['q1BtnLink'] : '';
				$output      .= '<a class="' . gutentor_concat_space( $default_class, $custom_class, $icon_position_class ) . '" ' . apply_filters( 'gutentor_save_link_attr', '', esc_url( $demo_url ), $link_options ) . '>' . $icon . '<span>' . esc_html( $attributes['q1BtnTxt'] ) . '</span></a>';
			}
			return $output;

		}

		/**
		 * Get Featured Post Categories Data
		 *
		 * @param {mix} $post_id
		 * @return string
		 */
		function get_featured_post_module_badge_data( $post_id = false, $badge_type = 'category' ) {

			$cat_list = '';
			$terms    = get_the_terms( $post_id, $badge_type );
			if ( $terms && ! is_wp_error( $terms ) ) :
				global $wp_rewrite;
				$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';
				$i   = 0;
				foreach ( $terms as $badge ) {
					if ( 0 <= $i ) {
						$cat_list .= '<a href="' . get_term_link( $badge->term_id ) . ' " class="post-featured-category gutentor-cat-' . $badge->slug . '" ' . $rel . '>' . $badge->name . '</a>';
					}
					$cat_list .= ' ';
					++$i;
				}
			endif;
			return $cat_list;
		}

		/**
		 * Get Categories Data
		 *
		 * @param {mix} $post_id
		 * @return string
		 */
		function get_post_module_badge_data( $post_id = false, $badge_type = 'category' ) {
			$cat_list = '';
			$terms    = get_the_terms( $post_id, $badge_type );
			if ( $terms && ! is_wp_error( $terms ) ) :
				global $wp_rewrite;
				$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';
				$i   = 0;
				foreach ( $terms as $badge ) {
					if ( 0 <= $i ) {
						$cat_list .= '<a href="' . get_term_link( $badge->term_id ) . ' " class="post-category gutentor-cat-' . $badge->slug . '" ' . $rel . '>' . $badge->name . '</a>';
					}
					$cat_list .= ' ';
					++$i;
				}
			endif;
			return $cat_list;
		}


		/**
		 * Get Product Data
		 *
		 * @param {mix} $post_id
		 * @return string
		 */
		function get_product_module_badge_data( $post_id = false, $badge_type = 'product_cat' ) {

			$cat_list = '';
			$terms    = get_the_terms( $post_id, $badge_type );
			if ( $terms && ! is_wp_error( $terms ) ) :
				global $wp_rewrite;
				$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';
				$i   = 0;
				foreach ( $terms as $badge ) {
					if ( 0 <= $i ) {
						$cat_list .= '<a href="' . get_term_link( $badge->term_id ) . ' " class="g-wc-badge gutentor-cat-' . $badge->slug . '" ' . $rel . '>' . $badge->name . '</a>';
					}
					$cat_list .= ' ';
					++$i;
				}
			endif;
			return $cat_list;
		}

		/**
		 * Get Category Meta
		 *
		 * @param {mix} $post_id
		 * @return string
		 */
		function get_featured_post_module_badges_collection( $post, $attributes ) {

			$badge_type = 'category';
			if ( isset( $attributes['pFPBadgeType'] ) ) {
				$badge_type = $attributes['pFPBadgeType'];
			}
			$output = '';
			if ( $badge_type != -1 && $this->get_featured_post_module_badge_data( $post->ID, $badge_type ) ) {
				$output = '<div class="gutentor-categories gutentor-featured-post-categories">' . $this->get_featured_post_module_badge_data( $post->ID, $badge_type ) . '</div>';
			}
			return $output;
		}

		/**
		 * Get Category Meta
		 *
		 * @param {mix} $post_id
		 * @return string
		 */
		function get_post_module_badges_collection( $post, $attributes ) {

			$badge_type = 'category';
			if ( isset( $attributes['pBadgeType'] ) ) {
				$badge_type = $attributes['pBadgeType'];
			}

			$output = '';

			if ( $badge_type != -1 && $this->get_post_module_badge_data( $post->ID, $badge_type ) ) {
				$output = '<div class="gutentor-categories">' . $this->get_post_module_badge_data( $post->ID, $badge_type ) . '</div>';
			}
			return $output;
		}

		/**
		 * Get post Format Data
		 *
		 * @param {mix} $post_id
		 * @return string
		 */
		function get_post_format_data( $post, $attributes ) {
			$enable_post_format = false;
			$output             = '';
			if ( ! gutentor_check_post_format_support_enable() ) {
				return $output;
			}
			if ( isset( $attributes['pOnPostFormatOpt'] ) && $attributes['pOnPostFormatOpt'] ) {
				$enable_post_format = $attributes['pOnPostFormatOpt'];
			}
			if ( ! $enable_post_format ) {
				return $output;
			}
			$post_format       = get_post_format() ?: 'standard';
			$string_icon       = gutentor_get_post_format_icon( $post_format );
			$decoded_icon      = json_decode( $string_icon );
			$icon              = is_null( $decoded_icon ) ? $string_icon : $decoded_icon;
			$icon              = ( gettype( $icon ) === 'object' ) ? $decoded_icon->icon : $string_icon;
			$icon              = $icon ? $icon : 'fas fa-file-alt';
			$post_format_class = 'gutentor-post-format-' . $post_format;
			$output           .= '<div class="gutentor-post-format-wrap"><span class="' . gutentor_concat_space( 'gutentor-post-format', $post_format_class ) . '"><i class="' . $icon . '"></i></span></div>';
			return $output;
		}

		/**
		 * Categories On Image
		 *
		 * @param {string} condition
		 * @return {boolean}
		 */
		function categories_on_image_condition( $condition ) {
			if ( ! $condition ) {
				return false;
			}
			$match_condition = array(
				'gutentor-cat-pos-img-top-left',
				'gutentor-cat-pos-img-top-right',
				'gutentor-cat-pos-img-bottom-left',
				'gutentor-cat-pos-img-bottom-right',
			);
			if ( in_array( $condition, $match_condition ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Categories On Image
		 *
		 * @param {string} condition
		 * @return {boolean}
		 */
		function featured_post_categories_on_image_condition( $condition ) {
			if ( ! $condition ) {
				return false;
			}
			$match_condition = array(
				'gutentor-fp-cat-pos-img-top-left',
				'gutentor-fp-cat-pos-img-top-right',
				'gutentor-fp-cat-pos-img-bottom-left',
				'gutentor-fp-cat-pos-img-bottom-right',
			);
			if ( in_array( $condition, $match_condition ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Post Format On Image
		 *
		 * @param {string} condition
		 * @return {boolean}
		 */
		function post_format_on_image_condition( $condition ) {
			if ( ! $condition ) {
				return false;
			}
			$match_condition = array(
				'gutentor-pf-pos-img-top-left',
				'gutentor-pf-pos-img-top-right',
				'gutentor-pf-pos-img-bottom-left',
				'gutentor-pf-pos-img-bottom-right',
			);
			if ( in_array( $condition, $match_condition ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Avatar On Image
		 *
		 * @param {string} condition
		 * @return {boolean}
		 */
		function avatar_on_image_condition( $condition ) {
			if ( ! $condition ) {
				return false;
			}
			$match_condition = array(
				'g-avatar-img-t-l',
				'g-avatar-img-t-r',
				'g-avatar-img-b-l',
				'g-avatar-img-b-r',
			);
			if ( in_array( $condition, $match_condition ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Avatar On Image Featured Post
		 *
		 * @param {string} condition
		 * @return {boolean}
		 */
		function avatar_fp_on_image_condition( $condition ) {
			if ( ! $condition ) {
				return false;
			}
			$match_condition = array(
				'g-avatar-img-fp-t-l',
				'g-avatar-img-fp-t-r',
				'g-avatar-img-fp-b-l',
				'g-avatar-img-fp-b-r',
			);
			if ( in_array( $condition, $match_condition ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Avatar On Image
		 *
		 * @param {string} condition
		 * @return {boolean}
		 */
		function avatar_on_title_condition( $condition ) {
			if ( ! $condition ) {
				return false;
			}
			$match_condition = array(
				'g-avatar-b-title-l',
				'g-avatar-b-title-r',
			);
			if ( in_array( $condition, $match_condition ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Post Format On Image
		 *
		 * @param {string} condition
		 * @return {boolean}
		 */
		function featured_post_format_on_image_condition( $condition ) {
			if ( ! $condition ) {
				return false;
			}
			$match_condition = array(
				'gutentor-fp-pf-pos-img-top-left',
				'gutentor-fp-pf-pos-img-top-right',
				'gutentor-fp-pf-pos-img-bottom-left',
				'gutentor-fp-pf-pos-img-bottom-right',
			);
			if ( in_array( $condition, $match_condition ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Title
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_featured_post_title( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnFPTitle'] ) && $attributes['pOnFPTitle'] ) {
				$title_tag = $attributes['pTitleTag'];
				$output   .= '<' . $title_tag . ' class="gutentor-post-featured-title">';
				$output   .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
				$output   .= get_the_title();
				$output   .= '</a>';
				$output   .= '</' . $title_tag . '>';
			}
			return $output;

		}


		/**
		 * Description
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_featured_post_description( $post, $attributes ) {
			$output = '';
			if ( ! isset( $attributes['pFPExcerptLen'] ) || ! isset( $attributes['pOnFPDesc'] ) ) {
				return $output;
			}
			if ( $attributes['pFPExcerptLen'] > 0 && $attributes['pOnFPDesc'] ) {
				$in_words = ( isset( $attributes['pFPExcerptLenInWords'] ) && $attributes['pFPExcerptLenInWords'] );
				$output  .= '<div class="gutentor-post-featured-desc">';
				$output  .= gutentor_get_excerpt_by_id( $post->ID, $attributes['pFPExcerptLen'], $in_words );
				$output  .= '</div>';
			}
			return $output;

		}


		/**
		 * Featured Image
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_featured_post_featured_image( $post, $attributes ) {
			$image_output      = $target = $rel = $link_class = '';
			$overlay_obj       = ( isset( $attributes['pFPFImgOColor'] ) ) ? $attributes['pFPFImgOColor'] : false;
			$overlay_enable    = ( $overlay_obj && array_key_exists( 'enable', $overlay_obj ) ) ? $attributes['pFPFImgOColor']['enable'] : false;
			$image_link_enable = false;
			if ( array_key_exists( 'pFPImgOnLink', $attributes ) ) {
				$image_link_enable = ( isset( $attributes['pFPImgOnLink'] ) ) ? $attributes['pFPImgOnLink'] : false;
			}
			if ( array_key_exists( 'pFPImgOpenNewTab', $attributes ) ) {
				$openNewTab = ( isset( $attributes['pFPImgOpenNewTab'] ) ) ? $attributes['pFPImgOpenNewTab'] : false;
				$target     = ( $openNewTab ) ? 'target="_blank"' : '';
			}
			if ( array_key_exists( 'pFPImgLinkRel', $attributes ) ) {
				$rel = ( $attributes['pFPImgLinkRel'] ) ? 'rel="' . $attributes['pFPImgLinkRel'] . '"' : '';

			}
			if ( array_key_exists( 'pFPImgClass', $attributes ) ) {
				$link_class = ( $attributes['pFPImgClass'] ) ? sanitize_html_class( $attributes['pFPImgClass'] ) : '';
			}
			$overlay = ( $overlay_enable ) ? "<div class='overlay'></div>" : '';
			$overlay = ( $overlay_enable ) ? 'gutentor-overlay' : '';
			if ( isset( $attributes['pOnFPFImg'] ) && $attributes['pOnFPFImg'] ) {
				$image_output = '';
				if ( has_post_thumbnail() ) {
					$image_output .= '<div class="' . gutentor_concat_space( 'gutentor-image-thumb', $overlay ) . '">';
					$image_output .= get_the_post_thumbnail( $post->ID, $attributes['pFPFImgSize'], array( 'class' => 'normal-image' ) );
					$image_output .= $overlay;
					$image_output .= '</div>';
				}
			}

			if ( $image_link_enable ) {
				$image_without_link = $image_output;
				$image_output       = '';
				$image_output      .= '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="gutentor-post-image-link' . gutentor_concat_space( $link_class ) . '" ' . gutentor_concat_space( $target, $rel ) . '>';
				$image_output      .= $image_without_link;
				$image_output      .= '</a>';
			}

			$image_output = apply_filters( 'gutentor_p6_post_module_featured_image_data', $image_output, $post, $attributes );
			return $image_output;

		}

		/**
		 * Primary Meta Date
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_featured_post_primary_meta_date_data( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnFPDateMeta1'] ) && $attributes['pOnFPDateMeta1'] ) {
				$dateFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-calendar' : 'far fa-calendar-alt';
				$output              .= '<div class="posted-on">';
				if ( isset( $attributes['pOnFPIconMeta1'] ) && $attributes['pOnFPIconMeta1'] ) {
					$output .= '<i class="' . $dateFontAwesomeClass . '"></i>';
				}
				$output .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . get_the_date() . '</a>';
				$output .= '</div>';

			}
			return $output;
		}

		/**
		 * Secondary Meta Date
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_featured_post_secondary_meta_date_data( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnFPDateMeta2'] ) && $attributes['pOnFPDateMeta2'] ) {

				$dateFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-calendar' : 'far fa-calendar-alt';
				$output              .= '<div class="posted-on">';
				if ( isset( $attributes['pOnFPIconMeta2'] ) && $attributes['pOnFPIconMeta2'] ) {
					$output .= '<i class="' . $dateFontAwesomeClass . '"></i>';
				}
				$output .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . get_the_date() . '</a>';
				$output .= '</div>';

			}
			return $output;

		}

		/**
		 * Primary Meta Comment
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_featured_post_primary_meta_comment_data( $post, $attributes ) {
			$output = '';
			if ( ! is_object( $post ) ) {
				return $output;
			}
			$comment_data = wp_count_comments( $post->ID );

			if ( ! $comment_data->total_comments ) {
				return $output;
			}
			if ( isset( $attributes['pOnFPCommentMeta1'] ) && $attributes['pOnFPCommentMeta1'] ) {
				$commentFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-comment' : 'fas fa-comment';
				$output                 .= '<div class="comments-link">';
				if ( isset( $attributes['pOnFPIconMeta1'] ) && $attributes['pOnFPIconMeta1'] ) {
					$output .= '<i class="' . $commentFontAwesomeClass . '"></i>';
				}
				$output .= $comment_data->total_comments;
				$output .= '</div>';

			}
			return $output;
		}

		/**
		 * Secondary Meta Comment
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_featured_post_secondary_meta_comment_data( $post, $attributes ) {
			$output       = '';
			$comment_data = wp_count_comments( $post->ID );

			if ( ! $comment_data->total_comments ) {
				return $output;
			}
			if ( isset( $attributes['pOnFPCommentMeta2'] ) && $attributes['pOnFPCommentMeta2'] ) {
				$commentFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-comment' : 'fas fa-comment';
				$output                 .= '<div class="comments-link">';
				if ( isset( $attributes['pOnFPIconMeta2'] ) && $attributes['pOnFPIconMeta2'] ) {
					$output .= '<i class="' . $commentFontAwesomeClass . '"></i>';
				}
				$output .= $comment_data->total_comments;
				$output .= '</div>';

			}
			return $output;
		}

		/**
		 * Primary Meta Author
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_featured_post_primary_meta_author_data( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnFPAuthorMeta1'] ) && $attributes['pOnFPAuthorMeta1'] ) {
				$authorFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-user' : 'far fa-user';
				$output                .= '<div class="author vcard">';
				if ( isset( $attributes['pOnFPIconMeta1'] ) && $attributes['pOnFPIconMeta1'] ) {
					$output .= '<i class="' . $authorFontAwesomeClass . '"></i>';
				}
				$output .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . get_the_author() . '</a>';
				$output .= '</div>';
			}
			return $output;

		}

		/**
		 * Secondary Meta Author
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_featured_post_secondary_meta_author_data( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnFPAuthorMeta2'] ) && $attributes['pOnFPAuthorMeta2'] ) {
				$authorFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-user' : 'far fa-user';
				$output                .= '<div class="author vcard">';
				if ( isset( $attributes['pOnFPIconMeta2'] ) && $attributes['pOnFPIconMeta2'] ) {
					$output .= '<i class="' . $authorFontAwesomeClass . '"></i>';
				}
				$output .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . get_the_author() . '</a>';
				$output .= '</div>';
			}
			return $output;

		}

		/**
		 * Primary Meta Categories
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_featured_post_primary_meta_categories_data( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnFPCatMeta1'] ) && $attributes['pOnFPCatMeta1'] ) {
				$categories_list = get_the_category_list( esc_html__( ', ', 'gutentor' ) );
				if ( $categories_list ) {
					$catFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-tags' : 'fas fa-tags';
					$output             .= '<div class="gutentor-meta-categories">';
					if ( isset( $attributes['pOnFPIconMeta1'] ) && $attributes['pOnFPIconMeta1'] ) {
						$output .= '<i class="' . $catFontAwesomeClass . '"></i>';
					}
					$output .= $categories_list . '</div>';
				}
			}
			return $output;

		}

		/**
		 * Secondary Categories
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function get_featured_post_secondary_meta_category_data( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnFPCatMeta2'] ) && $attributes['pOnFPCatMeta2'] ) {
				$categories_list = get_the_category_list( esc_html__( ', ', 'gutentor' ) );
				if ( $categories_list ) {
					$catFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-tags' : 'fas fa-tags';
					$output             .= '<div class="gutentor-meta-categories">';
					if ( isset( $attributes['pOnFPIconMeta2'] ) && $attributes['pOnFPIconMeta2'] ) {
						$output .= '<i class="' . $catFontAwesomeClass . '"></i>';
					}
					$output .= $categories_list . '</div>';
				}
			}
			return $output;

		}

		/**
		 * Featured Post Primary Meta Tags
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function p6_get_featured_post_primary_meta_tags_data( $post, $attributes ) {
			$output              = '';
			$get_postType        = isset( $attributes['pPostType'] ) ? $attributes['pPostType'] : 'post';
			$catFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-tags' : 'fas fa-tags';
			if ( isset( $attributes['pOnFPTagMeta1'] ) && $attributes['pOnFPTagMeta1'] ) {
				if ( $get_postType === 'product' || $get_postType === 'download' ) {
					$terms = '';
					if ( $get_postType === 'product' && gutentor_is_woocommerce_active() ) {
						$terms = wp_get_post_terms( $post->ID, 'product_tag' );
					} elseif ( $get_postType === 'download' && gutentor_is_edd_active() ) {
						$terms = wp_get_post_terms( $post->ID, 'download_tag' );
					}
					if ( is_object( $terms ) || is_array( $terms ) ) {
						if ( count( $terms ) > 0 ) {
							$tag_output = array();
							$output    .= '<div class="gutentor-meta-categories">';
							if ( isset( $attributes['pOnFPIconMeta1'] ) && $attributes['pOnFPIconMeta1'] ) {
								$output .= '<i class="' . $catFontAwesomeClass . '"></i>';
							}
							foreach ( $terms as $term ) {
								$tag_output[] = '<a href="' . get_term_link( $term->term_id ) . '" rel="tag">' . $term->name . '</a>';
							}
							$output .= implode( ', ', $tag_output );
							$output .= '</div>';
						}
					}
				} else {
					$tags_list = get_the_tag_list( '', ', ' );
					if ( $tags_list ) {
						$output .= '<div class="gutentor-meta-categories">';
						if ( isset( $attributes['pOnFPIconMeta1'] ) && $attributes['pOnFPIconMeta1'] ) {
							$output .= '<i class="' . $catFontAwesomeClass . '"></i>';
						}
						$output .= $tags_list . '</div>';
					}
				}
			}
			return $output;

		}

		/**
		 * Featured Post Secondary Meta Tags
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.5
		 */
		public function p6_get_featured_post_secondary_meta_tags_data( $post, $attributes ) {
			$output              = '';
			$get_postType        = isset( $attributes['pPostType'] ) ? $attributes['pPostType'] : 'post';
			$catFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-tags' : 'fas fa-tags';
			if ( isset( $attributes['pOnFPTagMeta2'] ) && $attributes['pOnFPTagMeta2'] ) {
				if ( $get_postType === 'product' || $get_postType === 'download' ) {
					$terms = '';
					if ( $get_postType === 'product' && gutentor_is_woocommerce_active() ) {
						$terms = wp_get_post_terms( $post->ID, 'product_tag' );
					} elseif ( $get_postType === 'download' && gutentor_is_edd_active() ) {
						$terms = wp_get_post_terms( $post->ID, 'download_tag' );
					}
					if ( is_object( $terms ) || is_array( $terms ) ) {
						if ( count( $terms ) > 0 ) {
							$tag_output = array();
							$output    .= '<div class="gutentor-meta-categories">';
							if ( isset( $attributes['pOnFPIconMeta2'] ) && $attributes['pOnFPIconMeta2'] ) {
								$output .= '<i class="' . $catFontAwesomeClass . '"></i>';
							}
							foreach ( $terms as $term ) {
								$tag_output[] = '<a href="' . get_term_link( $term->term_id ) . '" rel="tag">' . $term->name . '</a>';
							}
							$output .= implode( ', ', $tag_output );
							$output .= '</div>';
						}
					}
				} else {
					$tags_list = get_the_tag_list( '', ', ' );
					if ( $tags_list ) {
						$output .= '<div class="gutentor-meta-categories">';
						if ( isset( $attributes['pOnFPIconMeta2'] ) && $attributes['pOnFPIconMeta2'] ) {
							$output .= '<i class="' . $catFontAwesomeClass . '"></i>';
						}
						$output .= $tags_list . '</div>';
					}
				}
			}
			return $output;

		}

		/**
		 * Primary Entry Meta
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_featured_post_primary_meta_sorting_data( $post, $attributes ) {
			$meta_sorting = array_key_exists( 'pFPMeta1Sorting', $attributes ) ? $attributes['pFPMeta1Sorting'] : false;
			$output       = '';
			if ( ! $meta_sorting ) {
				return $output;
			}
			$output .= '<div class="gutentor-entry-meta gutentor-entry-meta-featured-primary">';
			foreach ( $meta_sorting as $element ) :
				if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
					return $output;
				}
				switch ( $element['itemValue'] ) {
					case 'meta-author':
						$output .= $this->get_featured_post_primary_meta_author_data( $post, $attributes );
						break;
					case 'meta-date':
						$output .= $this->get_featured_post_primary_meta_date_data( $post, $attributes );
						break;
					case 'meta-category':
						$output .= $this->get_featured_post_primary_meta_categories_data( $post, $attributes );
						break;
					case 'meta-comment':
						$output .= $this->get_featured_post_primary_meta_comment_data( $post, $attributes );
						break;
					case 'meta-tag':
						$output .= $this->p6_get_featured_post_primary_meta_tags_data( $post, $attributes );
						break;
					default:
						$output .= '';
						break;
				}
			endforeach;
			$output .= '</div>';/*.entry-meta*/
			return $output;

		}

		/**
		 * Primary Entry Meta
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_featured_post_secondary_meta_sorting_data( $post, $attributes ) {
			$meta_sorting = array_key_exists( 'pFPMeta2Sorting', $attributes ) ? $attributes['pFPMeta2Sorting'] : false;
			$output       = '';
			if ( ! $meta_sorting ) {
				return $output;
			}
			$output .= '<div class="gutentor-entry-meta gutentor-entry-meta-featured-secondary">';
			foreach ( $meta_sorting as $element ) :
				if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
					return $output;
				}
				switch ( $element['itemValue'] ) {
					case 'meta-author':
						$output .= $this->get_featured_post_secondary_meta_author_data( $post, $attributes );
						break;
					case 'meta-date':
						$output .= $this->get_featured_post_secondary_meta_date_data( $post, $attributes );
						break;
					case 'meta-category':
						$output .= $this->get_featured_post_secondary_meta_category_data( $post, $attributes );
						break;
					case 'meta-comment':
						$output .= $this->get_featured_post_secondary_meta_comment_data( $post, $attributes );
						break;
					case 'meta-tag':
						$output .= $this->p6_get_featured_post_secondary_meta_tags_data( $post, $attributes );
						break;
					default:
						$output .= '';
						break;
				}
			endforeach;
			$output .= '</div>';/*.entry-meta*/
			return $output;

		}

		/**
		 * Primary Meta
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_featured_post_primary_meta( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnFPMeta1'] ) && $attributes['pOnFPMeta1'] ) {
				$output .= $this->get_featured_post_primary_meta_sorting_data( $post, $attributes );
			}
			return $output;
		}

		/**
		 * Secondary Meta
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_featured_post_secondary_meta( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnFPMeta2'] ) && $attributes['pOnFPMeta2'] ) {
				$output .= $this->get_featured_post_secondary_meta_sorting_data( $post, $attributes );
			}
			return $output;
		}

		/**
		 * Button
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_featured_post_button( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['pOnFPBtn'] ) && $attributes['pOnFPBtn'] ) {
				$default_class       = gutentor_concat_space( 'gutentor-button', 'gutentor-post-featured-button' );
				$icon_options        = ( isset( $attributes['pFPBtnIconOpt'] ) ) ? $attributes['pFPBtnIconOpt'] : '';
				$link_options        = ( isset( $attributes['pFPBtnLink'] ) ) ? $attributes['pFPBtnLink'] : '';
				$icon_position_class = GutentorButtonOptionsClasses( $icon_options );
				$icon                = '';
				if ( $icon_position_class == 'gutentor-icon-before' || $icon_position_class == 'gutentor-icon-after' ) {
					$icon = ( isset( $attributes['pFPBtnIcon'] ) && $attributes['pFPBtnIcon']['value'] ) ? '<i class="gutentor-button-icon ' . $attributes['pFPBtnIcon']['value'] . '" ></i>' : '';
				}
				$output .= '<a class="' . gutentor_concat_space( $default_class, $icon_position_class ) . '" ' . apply_filters( 'gutentor_save_link_attr', '', esc_url( get_permalink() ), $link_options ) . '>' . $icon . '<span>' . esc_html( $attributes['pFPBtnText'] ) . '</span></a>';
			}
			return $output;

		}

		/**
		 * Get post Format Data
		 *
		 * @param {mix} $post_id
		 * @return string
		 */
		function get_featured_post_format_data( $post, $attributes ) {
			$enable_post_format = false;
			$output             = '';
			if ( ! gutentor_check_post_format_support_enable() ) {
				return $output;
			}
			if ( isset( $attributes['pOnFPPostFormatOpt'] ) && $attributes['pOnFPPostFormatOpt'] ) {
				$enable_post_format = $attributes['pOnFPPostFormatOpt'];
			}
			if ( ! $enable_post_format ) {
				return $output;
			}
			$post_format       = get_post_format() ?: 'standard';
			$string_icon       = gutentor_get_post_format_icon( $post_format );
			$decoded_icon      = json_decode( $string_icon );
			$icon              = is_null( $decoded_icon ) ? $string_icon : $decoded_icon;
			$icon              = ( gettype( $icon ) === 'object' ) ? $decoded_icon->icon : $string_icon;
			$icon              = $icon ? $icon : 'fas fa-file-alt';
			$post_format_class = 'gutentor-post-format-' . $post_format;
			$output           .= '<span class="' . gutentor_concat_space( 'gutentor-post-featured-format', $post_format_class ) . '"><i class="' . $icon . '"></i></span>';
			return $output;
		}

		/**
		 * Get Featured Single item data
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function p6_featured_single_article( $post, $attributes, $index ) {
			$output                = $thumb_class = '';
			$query_sorting         = array_key_exists( 'blockFPSortableItems', $attributes ) ? $attributes['blockFPSortableItems'] : false;
			$enable_featured_image = ( isset( $attributes['pOnFPFImg'] ) ) ? $attributes['pOnFPFImg'] : false;
			$enable_avatar         = ( isset( $attributes['pFPOnAvatar'] ) ) ? $attributes['pFPOnAvatar'] : false;
			$avatar_pos            = ( isset( $attributes['pFPAvatarPos'] ) ) ? $attributes['pFPAvatarPos'] : false;
			$enable_post_format    = ( isset( $attributes['pOnFPPostFormatOpt'] ) ) ? $attributes['pOnFPPostFormatOpt'] : false;
			$post_format_pos       = ( isset( $attributes['pFPPostFormatPos'] ) ) ? $attributes['pFPPostFormatPos'] : false;
			$cat_pos               = ( isset( $attributes['pFPCatPos'] ) ) ? $attributes['pFPCatPos'] : false;
			$enable_featured_cat   = ( isset( $attributes['pOnFPFeaturedCat'] ) ) ? $attributes['pOnFPFeaturedCat'] : false;
			$thumb_class           = has_post_thumbnail() && $enable_featured_image ? '' : 'gutentor-post-no-thumb';
			$output               .= "<article class='" . apply_filters( 'gutentor_post_module_article_class', gutentor_concat_space( 'gutentor-post', 'gutentor-post-featured', $thumb_class, 'gutentor-post-item-' . $index ), $attributes ) . "'>";
			$output               .= "<div class='" . apply_filters( 'gutentor_p6_featured_post_post_item', 'gutentor-post-featured-item', $attributes ) . "'>";
			if ( $enable_featured_image && has_post_thumbnail( $post->ID ) ) {
				$enable_overlayImage = false;
				$overlayImage        = ( isset( $attributes['pFPFImgOColor'] ) ) ? $attributes['pFPFImgOColor'] : false;
				if ( $overlayImage ) {
					$enable_overlayImage = ( isset( $attributes['pFPFImgOColor']['enable'] ) ) ? $attributes['pFPFImgOColor']['enable'] : false;
				}
				$url     = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $attributes['pFPFImgSize'] );
				$overlay = $enable_overlayImage ? 'gutentor-overlay' : '';
				$output .= "<div class='" . apply_filters( 'gutentor_p6_featured_post_post_item_height', gutentor_concat_space( 'gutentor-post-featured-height', 'gutentor-bg-image', $overlay ), $attributes ) . "' style='background-image:url(" . esc_url( is_array( $url ) && ! empty( $url ) ? $url[0] : '' ) . ")'>";
				if ( $enable_avatar && $this->avatar_fp_on_image_condition( $avatar_pos ) ) {
					$output .= $this->get_fp_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $this->featured_post_format_on_image_condition( $post_format_pos ) ) {
					$output .= $this->get_featured_post_format_data( $post, $attributes );
				}
				if ( $enable_featured_cat && $this->featured_post_categories_on_image_condition( $cat_pos ) ) {
					$output .= $this->get_featured_post_module_badges_collection( $post, $attributes );
				}
				$output .= apply_filters( 'gutentor_p6_featured_image_popup_data', '', $post, $attributes );
				$output .= '<div class="gutentor-post-content">';
				if ( $query_sorting ) :
					foreach ( $query_sorting as $element ) :
						if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
							return $output;
						}
						switch ( $element['itemValue'] ) {
							case 'title':
								if ( $cat_pos === 'gutentor-fp-cat-pos-before-title' || $post_format_pos === 'gutentor-fp-pf-pos-before-title' ) {
									$output .= '<div class="gutentor-post-title-data-wrap">';
									if ( $enable_post_format && $post_format_pos === 'gutentor-fp-pf-pos-before-title' ) {

										$output .= $this->get_featured_post_format_data( $post, $attributes );
									}
									if ( $enable_featured_cat && $cat_pos === 'gutentor-fp-cat-pos-before-title' ) {

										$output .= $this->get_featured_post_module_badges_collection( $post, $attributes );
									}
									$output .= $this->get_featured_post_title( $post, $attributes );
									$output .= '</div>';
								} else {

									$output .= $this->get_featured_post_title( $post, $attributes );
								}
								break;
							case 'primary-entry-meta':
								$output .= $this->get_featured_post_primary_meta( $post, $attributes );
								break;
							case 'secondary-entry-meta':
								$output .= $this->get_featured_post_secondary_meta( $post, $attributes );
								break;
							case 'avatar':
								$output .= $this->get_fp_avatar_data( $post, $attributes );
								break;
							case 'description':
								if ( $cat_pos === 'gutentor-fp-cat-pos-before-ct-box' || $post_format_pos === 'gutentor-fp-pf-pos-before-ct-box' ) {
									$output .= '<div class="gutentor-post-desc-data-wrap">';
									if ( $enable_post_format && $post_format_pos === 'gutentor-fp-pf-pos-before-ct-box' ) {

										$output .= $this->get_featured_post_format_data( $post, $attributes );
									}
									if ( $enable_featured_cat && $cat_pos === 'gutentor-fp-cat-pos-before-ct-box' ) {

										$output .= $this->get_featured_post_module_badges_collection( $post, $attributes );
									}
									$output .= $this->get_featured_post_description( $post, $attributes );
									$output .= '</div>';
								} else {

									$output .= $this->get_featured_post_description( $post, $attributes );
								}
								break;
							case 'button':
								if ( $cat_pos === 'gutentor-fp-cat-pos-before-button' || $post_format_pos === 'gutentor-fp-pf-pos-before-button' ) {
									$output .= '<div class="gutentor-post-desc-data-wrap">';
									if ( $enable_post_format && $post_format_pos === 'gutentor-fp-pf-pos-before-button' ) {

										$output .= $this->get_featured_post_format_data( $post, $attributes );
									}
									if ( $enable_featured_cat && $cat_pos === 'gutentor-fp-cat-pos-before-button' ) {

										$output .= $this->get_featured_post_module_badges_collection( $post, $attributes );
									}
									$output .= $this->get_featured_post_button( $post, $attributes );
									$output .= '</div>';
								} else {

									$output .= $this->get_featured_post_button( $post, $attributes );
								}
								break;
							default:
								$output .= '';
								break;
						}
					endforeach;
				endif;
				$output .= '</div>';/*.gutentor-post-content*/
				$output .= '</div>';/*.gutentor-bg-image*/
			} else {
				$output .= '<div class="gutentor-post-featured-height">';
				if ( $enable_avatar && $this->avatar_fp_on_image_condition( $avatar_pos ) ) {
					$output .= $this->get_fp_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $this->featured_post_format_on_image_condition( $post_format_pos ) ) {
					$output .= $this->get_featured_post_format_data( $post, $attributes );
				}
				if ( $enable_featured_cat && $this->featured_post_categories_on_image_condition( $cat_pos ) ) {
					$output .= $this->get_featured_post_module_badges_collection( $post, $attributes );
				}
				$output .= '<div class="gutentor-post-content">';
				if ( $query_sorting ) :
					foreach ( $query_sorting as $element ) :
						if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
							return $output;
						}
						switch ( $element['itemValue'] ) {
							case 'title':
								if ( $cat_pos === 'gutentor-fp-cat-pos-before-title' || $post_format_pos === 'gutentor-fp-pf-pos-before-title' ) {
									$output .= '<div class="gutentor-post-title-data-wrap">';
									if ( $enable_post_format && $post_format_pos === 'gutentor-fp-pf-pos-before-title' ) {

										$output .= $this->get_featured_post_format_data( $post, $attributes );
									}
									if ( $enable_featured_cat && $cat_pos === 'gutentor-fp-cat-pos-before-title' ) {

										$output .= $this->get_featured_post_module_badges_collection( $post, $attributes );
									}
									$output .= $this->get_featured_post_title( $post, $attributes );
									$output .= '</div>';
								} else {

									$output .= $this->get_featured_post_title( $post, $attributes );
								}
								break;
							case 'primary-entry-meta':
								$output .= $this->get_featured_post_primary_meta( $post, $attributes );
								break;
							case 'secondary-entry-meta':
								$output .= $this->get_featured_post_secondary_meta( $post, $attributes );
								break;
							case 'avatar':
								$output .= $this->get_fp_avatar_data( $post, $attributes );
								break;
							case 'description':
								if ( $cat_pos === 'gutentor-fp-cat-pos-before-ct-box' || $post_format_pos === 'gutentor-fp-pf-pos-before-ct-box' ) {
									$output .= '<div class="gutentor-post-desc-data-wrap">';
									if ( $enable_post_format && $post_format_pos === 'gutentor-fp-pf-pos-before-ct-box' ) {

										$output .= $this->get_featured_post_format_data( $post, $attributes );
									}
									if ( $enable_featured_cat && $cat_pos === 'gutentor-fp-cat-pos-before-ct-box' ) {

										$output .= $this->get_featured_post_module_badges_collection( $post, $attributes );
									}
									$output .= $this->get_featured_post_description( $post, $attributes );
									$output .= '</div>';
								} else {

									$output .= $this->get_featured_post_description( $post, $attributes );
								}
								break;
							case 'button':
								if ( $cat_pos === 'gutentor-fp-cat-pos-before-button' || $post_format_pos === 'gutentor-fp-pf-pos-before-button' ) {
									$output .= '<div class="gutentor-post-desc-data-wrap">';
									if ( $enable_post_format && $post_format_pos === 'gutentor-fp-pf-pos-before-button' ) {

										$output .= $this->get_featured_post_format_data( $post, $attributes );
									}
									if ( $enable_featured_cat && $cat_pos === 'gutentor-fp-cat-pos-before-button' ) {

										$output .= $this->get_featured_post_module_badges_collection( $post, $attributes );
									}
									$output .= $this->get_featured_post_button( $post, $attributes );
									$output .= '</div>';
								} else {

									$output .= $this->get_featured_post_button( $post, $attributes );
								}
								break;
							default:
								$output .= '';
								break;
						}
					endforeach;
				endif;
				$output .= '</div>';/*.gutentor-post-content*/
				$output .= '</div>';/*.gutentor-post-height*/
			}
			$output .= '</div>';/*.gutentor-post-featured-item*/
			$output .= '</article>';/*.article*/
			return $output;

		}

		/**
		 * Get Single block
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function p6_single_article( $post, $attributes, $index ) {
			$output                = $thumb_class = '';
			$access_no_thumbnail   = array( 'product', 'download' );
			$video_url             = get_post_meta( $post->ID, 'gutentor_meta_video_url', true );
			$video_id              = get_post_meta( $post->ID, 'gutentor_meta_video_id', true );
			$pFType                = $attributes['pFType'] ? $attributes['pFType'] : '';
			$post_type             = isset( $attributes['pPostType'] ) ? $attributes['pPostType'] : '';
			$enable_avatar         = ( isset( $attributes['pOnAvatar'] ) ) ? $attributes['pOnAvatar'] : false;
			$avatar_pos            = ( isset( $attributes['pAvatarPos'] ) ) ? $attributes['pAvatarPos'] : false;
			$query_sorting         = array_key_exists( 'blockSortableItems', $attributes ) ? $attributes['blockSortableItems'] : false;
			$enable_featured_image = ( isset( $attributes['pOnFImg'] ) ) ? $attributes['pOnFImg'] : false;
			$enable_post_format    = ( isset( $attributes['pOnPostFormatOpt'] ) ) ? $attributes['pOnPostFormatOpt'] : false;
			$post_format_pos       = ( isset( $attributes['pPostFormatPos'] ) ) ? $attributes['pPostFormatPos'] : false;
			$cat_pos               = ( isset( $attributes['pPostCatPos'] ) ) ? $attributes['pPostCatPos'] : false;
			$enable_featured_cat   = ( isset( $attributes['pOnFeaturedCat'] ) ) ? $attributes['pOnFeaturedCat'] : false;
			if ( ! in_array( $post_type, $access_no_thumbnail ) ) {
				$thumb_class = has_post_thumbnail() && $enable_featured_image ? '' : 'gutentor-post-no-thumb';
				$thumb_class = ( ( $video_url || $video_id ) && gutentor_pro_active() && $pFType === 'video' ) ? '' : $thumb_class;
			}
			$output .= "<article class='" . apply_filters( 'gutentor_post_module_article_class', gutentor_concat_space( 'gutentor-post', 'gutentor-post-normal', $thumb_class, 'gutentor-post-item-' . $index ), $attributes ) . "'>";
			$output .= '<div class="gutentor-post-item">';
			if ( $enable_featured_image && gutentor_has_post_featured( $post ) ) {
				$output .= "<div class='" . apply_filters( 'gutentor_post_module_post_image_box', 'gutentor-post-image-box', $post, $attributes ) . "'>";
				$output .= $this->get_featured_image( $post, $attributes );
				if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
					$output .= $this->get_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
					$output .= $this->get_post_format_data( $post, $attributes );
				}
				if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
					$output .= $this->get_post_module_badges_collection( $post, $attributes );
				}
				$output .= '</div>';
			}
			$output .= '<div class="gutentor-post-content">';
			if ( $query_sorting ) :
				foreach ( $query_sorting as $element ) :
					if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
						return $output;
					}
					switch ( $element['itemValue'] ) {
						case 'title':
							if ( $cat_pos === 'gutentor-cat-pos-before-title' || $post_format_pos === 'gutentor-pf-pos-before-title' ) {
								$output .= '<div class="gutentor-post-title-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {

									$output .= $this->get_post_format_data( $post, $attributes );
								}
								if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-title' ) {

									$output .= $this->get_post_module_badges_collection( $post, $attributes );
								}
								$output .= $this->get_title( $post, $attributes );
								$output .= '</div>';
							} else {

								$output .= $this->get_title( $post, $attributes );
							}
							break;
						case 'primary-entry-meta':
							$output .= $this->get_primary_meta( $post, $attributes );
							break;
						case 'secondary-entry-meta':
							$output .= $this->get_secondary_meta( $post, $attributes );
							break;
						case 'avatar':
							$output .= $this->get_avatar_data( $post, $attributes );
							break;
						case 'description':
							if ( $cat_pos === 'gutentor-cat-pos-before-ct-box' || $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {
								$output .= '<div class="gutentor-post-desc-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {

									$output .= $this->get_post_format_data( $post, $attributes );
								}
								if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-ct-box' ) {

									$output .= $this->get_post_module_badges_collection( $post, $attributes );
								}
								$output .= $this->get_description( $post, $attributes );
								$output .= '</div>';
							} else {

								$output .= $this->get_description( $post, $attributes );
							}
							break;
						case 'button':
							if ( $cat_pos === 'gutentor-cat-pos-before-button' || $post_format_pos === 'gutentor-pf-pos-before-button' ) {
								$output .= '<div class="gutentor-post-desc-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-button' ) {

									$output .= $this->get_post_format_data( $post, $attributes );
								}
								if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-button' ) {

									$output .= $this->get_post_module_badges_collection( $post, $attributes );
								}
								$output .= $this->get_button( $post, $attributes );
								$output .= '</div>';
							} else {

								$output .= $this->get_button( $post, $attributes );
							}
							break;
						default:
							$output .= '';
							break;
					}
				endforeach;
			endif;
			$output .= '</div>';/*.gutentor-post-content*/
			$output .= '</div>';/*.gutentor-post-item*/
			$output .= '</article>';/*.article*/
			return $output;

		}

		/**
		 * Get Woo Single block
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function p6_woo_single_article( $post, $attributes, $index ) {
			$output = '';
			if ( ! gutentor_is_woocommerce_active() ) {
				return $output;
			}
			$product               = wc_get_product( $post->ID );
			$rating                = $product->get_average_rating();
			$count                 = $product->get_rating_count();
			$rating_html           = wc_get_rating_html( $rating, $count );
			$enable_avatar         = ( isset( $attributes['pOnAvatar'] ) ) ? $attributes['pOnAvatar'] : false;
			$avatar_pos            = ( isset( $attributes['pAvatarPos'] ) ) ? $attributes['pAvatarPos'] : false;
			$query_sorting         = array_key_exists( 'blockSortableItems', $attributes ) ? $attributes['blockSortableItems'] : false;
			$enable_featured_image = ( isset( $attributes['pOnFImg'] ) ) ? $attributes['pOnFImg'] : false;
			$enable_post_format    = ( isset( $attributes['pOnPostFormatOpt'] ) ) ? $attributes['pOnPostFormatOpt'] : false;
			$post_format_pos       = ( isset( $attributes['pPostFormatPos'] ) ) ? $attributes['pPostFormatPos'] : false;
			$cat_pos               = ( isset( $attributes['pPostCatPos'] ) ) ? $attributes['pPostCatPos'] : false;
			$enable_featured_cat   = ( isset( $attributes['pOnFeaturedCat'] ) ) ? $attributes['pOnFeaturedCat'] : false;

			$output .= "<article class='" . apply_filters( 'gutentor_post_module_article_class', gutentor_concat_space( 'gutentor-post', 'gutentor-post-normal', 'gutentor-post-item-' . $index ), $attributes ) . "'>";
			$output .= '<div class="gutentor-post-item">';
			if ( $enable_featured_image ) {
				$output .= "<div class='" . apply_filters( 'gutentor_post_module_post_image_box', 'gutentor-post-image-box', $post, $attributes ) . "'>";
				$output .= $this->get_woo_product_thumbnail( $post, $product, $attributes );
				if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
					$output .= $this->get_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
					$output .= $this->new_badge_product( $post, $product );
				}
				if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
					$output .= $this->get_woo_badge( $post, $product, $attributes );
				}
				$output .= '</div>';
			}
			$output .= '<div class="gutentor-post-content">';
			if ( $query_sorting ) :
				foreach ( $query_sorting as $element ) :
					if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
						return $output;
					}
					switch ( $element['itemValue'] ) {
						case 'title':
							if ( $cat_pos === 'gutentor-cat-pos-before-title' || $post_format_pos === 'gutentor-pf-pos-before-title' ) {
								$output .= '<div class="gutentor-post-title-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {
									$output .= $this->new_badge_product( $post, $product );
								}
								if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-title' ) {

									$output .= $this->get_woo_badge( $post, $product, $attributes );
								}
								$output .= $this->get_title( $post, $attributes );
								$output .= '</div>';
							} else {
								$output .= $this->get_title( $post, $attributes );
							}
							break;
						case 'primary-entry-meta':
							$output .= $this->get_primary_meta( $post, $attributes );
							break;
						case 'secondary-entry-meta':
							$output .= $this->get_secondary_meta( $post, $attributes );
							break;
						case 'price':
							$output .= $this->updated_wc_price( $post, $product, $attributes );
							break;
						case 'rating':
							if ( isset( $attributes['wooOnRating'] ) && $attributes['wooOnRating'] ) {
								if ( $rating_html ) {
									$output .= '<div class="gutentor-wc-rating">';
									$output .= $rating_html;
									$output .= '</div>';
								}
							}
							break;
						case 'avatar':
							$output .= $this->get_avatar_data( $post, $attributes );
							break;
						case 'description':
							if ( $cat_pos === 'gutentor-cat-pos-before-ct-box' || $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {
								$output .= '<div class="gutentor-post-desc-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {
									$output .= $this->new_badge_product( $post, $product );
								}
								if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-ct-box' ) {

									$output .= $this->get_woo_badge( $post, $product, $attributes );
								}
								$output .= $this->get_description( $post, $attributes );
								$output .= '</div>';
							} else {

								$output .= $this->get_description( $post, $attributes );
							}
							break;
						case 'button':
							if ( $cat_pos === 'gutentor-cat-pos-before-button' || $post_format_pos === 'gutentor-pf-pos-before-button' ) {
								$output .= '<div class="gutentor-post-desc-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-button' ) {

									$output .= $this->new_badge_product( $post, $product );
								}
								if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-button' ) {

									$output .= $this->get_woo_badge( $post, $product, $attributes );
								}
								if ( isset( $attributes['pOnBtn'] ) && $attributes['pOnBtn'] ) {
									$output .= "<div class='" . apply_filters( 'gutentor_post_module_product_button_class', 'gutentor-woo-add-to-cart wc-block-grid__product-add-to-cart', $post, $attributes ) . "'>";
									ob_start();
									woocommerce_template_loop_add_to_cart( array( 'gutentor-attributes' => $attributes ) );
									$output .= ob_get_clean();
									$output .= '</div>';
								}
								$output .= '</div>';
							} else {

								if ( isset( $attributes['pOnBtn'] ) && $attributes['pOnBtn'] ) {
									$output .= "<div class='" . apply_filters( 'gutentor_post_module_product_button_class', 'gutentor-woo-add-to-cart wc-block-grid__product-add-to-cart', $post, $attributes ) . "'>";
									ob_start();
									woocommerce_template_loop_add_to_cart( array( 'gutentor-attributes' => $attributes ) );
									$output .= ob_get_clean();
									$output .= '</div>';
								}
							}
							break;
						default:
							$output .= '';
							break;
					}
				endforeach;
			endif;
			$output .= '</div>';/*.gutentor-post-content*/
			$output .= '</div>';/*.gutentor-post-item*/
			$output .= '</article>';/*.article*/
			return $output;

		}

		/**
		 * Get Woo Single block
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function p6_edd_single_article( $post, $attributes, $index ) {
			if ( ! gutentor_is_edd_active() ) {
				return '';
			}
			$enable_avatar         = ( isset( $attributes['pOnAvatar'] ) ) ? $attributes['pOnAvatar'] : false;
			$avatar_pos            = ( isset( $attributes['pAvatarPos'] ) ) ? $attributes['pAvatarPos'] : false;
			$download              = edd_get_download( $post->ID );
			$query_sorting         = array_key_exists( 'blockSortableItems', $attributes ) ? $attributes['blockSortableItems'] : false;
			$enable_featured_image = ( isset( $attributes['pOnFImg'] ) ) ? $attributes['pOnFImg'] : false;
			$enable_post_format    = ( isset( $attributes['pOnPostFormatOpt'] ) ) ? $attributes['pOnPostFormatOpt'] : false;
			$enable_featured_cat   = ( isset( $attributes['pOnFeaturedCat'] ) ) ? $attributes['pOnFeaturedCat'] : false;
			$cat_pos               = ( isset( $attributes['pPostCatPos'] ) ) ? $attributes['pPostCatPos'] : false;
			$post_format_pos       = ( isset( $attributes['pPostFormatPos'] ) ) ? $attributes['pPostFormatPos'] : false;

			$output  = '';
			$output .= "<article class='" . apply_filters( 'gutentor_post_module_article_class', gutentor_concat_space( 'gutentor-post', 'gutentor-post-normal', 'gutentor-post-item-' . $index ), $attributes ) . "'>";
			$output .= '<div class="gutentor-post-item">';
			if ( $enable_featured_image ) {
				$output .= "<div class='" . apply_filters( 'gutentor_post_module_post_image_box', 'gutentor-post-image-box', $post, $attributes ) . "'>";
				$output .= $this->get_edd_thumbnail( $post, $attributes );
				if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
					$output .= $this->get_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
					$output .= $this->edd_new_badge_product( $post, $download );
				}
				if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
					$output .= $this->get_post_module_badges_collection( $post, $attributes );
				}
				$output .= '</div>';
			}
			$output .= '<div class="gutentor-post-content">';
			if ( $query_sorting ) :
				foreach ( $query_sorting as $element ) :
					if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
						return $output;
					}
					switch ( $element['itemValue'] ) {
						case 'title':
							if ( $post_format_pos === 'gutentor-pf-pos-before-title' ) {
								$output .= '<div class="gutentor-post-title-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {
									$output .= $this->edd_new_badge_product( $post, $download );
								}
								$output .= $this->get_title( $post, $attributes );
								$output .= '</div>';
							} else {
								$output .= $this->get_title( $post, $attributes );
							}
							break;
						case 'primary-entry-meta':
							$output .= $this->get_primary_meta( $post, $attributes );
							break;
						case 'secondary-entry-meta':
							$output .= $this->get_secondary_meta( $post, $attributes );
							break;
						case 'price':
							$output .= $this->updated_edd_price( $post, $attributes );
							break;
						case 'rating':
							if ( isset( $attributes['wooOnRating'] ) && $attributes['wooOnRating'] ) {
								if ( gutentor_custom_edd_review( $post->ID ) ) {
									$output .= '<div class="gutentor-edd-rating">';
									$output .= gutentor_custom_edd_review( $post->ID );
									$output .= '</div>';
								}
							}
							break;
						case 'avatar':
							$output .= $this->get_avatar_data( $post, $attributes );
							break;
						case 'description':
							if ( $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {
								$output .= '<div class="gutentor-post-desc-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {

									$output .= $this->edd_new_badge_product( $post, $download );
								}
								$output .= $this->get_description( $post, $attributes );
								$output .= '</div>';
							} else {

								$output .= $this->get_description( $post, $attributes );
							}
							break;
						case 'wishlist':
							$output .= $this->get_edd_wish_list( $post, $attributes );
							break;
						case 'button':
							if ( $post_format_pos === 'gutentor-pf-pos-before-button' ) {
								$output .= '<div class="gutentor-post-button-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-button' ) {

									$output .= $this->edd_new_badge_product( $post, $download );
								}
								$output .= $this->get_edd_button( $post, $attributes );
							} else {
								$output .= $this->get_edd_button( $post, $attributes );
							}
							break;
						default:
							$output .= '';
							break;
					}
				endforeach;
			endif;
			$output .= '</div>';/*.gutentor-post-content*/
			$output .= '</div>';/*.gutentor-post-item*/
			$output .= '</article>';/*.article*/
			return $output;

		}

		/**
		 * Get get_woo_badge
		 *
		 * @param {array} $post
		 * @param {array} $product
		 * @param {array} $attributes
		 * @return string
		 */
		function get_woo_badge( $post, $product, $attributes ) {
			$output    = '';
			$badgeType = ( isset( $attributes['pBadgeType'] ) ) ? $attributes['pBadgeType'] : false;
			if ( $product->is_on_sale() && $badgeType === 'product-sale' ) {
				$output = '<div class="post-category gutentor-wc-on-sale-wrap">' . apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'gutentor' ) . '</span>', $post, $product ) . '</div>';
			}
			if ( $badgeType === 'product_type' && $this->get_product_module_badge_data( $post->ID, $badgeType ) ) {
				$output = '<div class="gutentor-wc-badge-wrap">' . $this->get_product_module_badge_data( $post->ID, $badgeType ) . '</div>';
			}
			if ( $badgeType !== 'product_type' && $this->get_post_module_badge_data( $post->ID, $badgeType ) ) {
				$output = $this->get_post_module_badge_data( $post->ID, $badgeType );
			}
			if ( $output ) {
				$output = '<div class="gutentor-categories">' . $output . '</div>';
			}
			return $output;
		}

		/**
		 * Get p6 get_woo_badge
		 *
		 * @param {array} $post
		 * @param {array} $product
		 * @param {array} $attributes
		 * @return string
		 */
		function p6_fp_get_woo_badge( $post, $product, $attributes ) {
			$output    = '';
			$badgeType = ( isset( $attributes['pFPBadgeType'] ) ) ? $attributes['pFPBadgeType'] : false;
			if ( $product->is_on_sale() && $badgeType === 'product-sale' ) {
				$output = '<div class="post-featured-category gutentor-wc-on-sale-wrap">' . apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'gutentor' ) . '</span>', $post, $product ) . '</div>';
			}
			if ( $badgeType != -1 && $this->get_product_module_badge_data( $post->ID, $badgeType ) ) {
				$output = '<div class="gutentor-fp-wc-badge-wrap">' . $this->get_product_module_badge_data( $post->ID, $badgeType ) . '</div>';
			}
			if ( $output ) {
				$output = '<div class="gutentor-categories gutentor-featured-post-categories">' . $output . '</div>';
			}
			return $output;
		}

		/**
		 * Get updated Woo price
		 * updated_wc_price
		 *
		 * @param {array} $post
		 * @param {array} $product
		 * @param {array} $attributes
		 * @return string
		 */
		function updated_wc_price( $post, $product, $attributes ) {
			$output = '';
			if ( isset( $attributes['wooOnPrice'] ) && $attributes['wooOnPrice'] ) {
				if ( $product->get_price_html() && $product->get_price() ) {
					$output .= '<div class="gutentor-wc-price">';
					$output .= $product->get_price_html();
					$output .= '</div>';
				} else {
					if ( isset( $attributes['wooOnFreeTxt'] ) && $attributes['wooOnFreeTxt'] ) {
						$output .= '<div class="gutentor-wc-price">';
						$output .= '<span class="woocommerce-Price-currencySymbol">';
						$output .= isset( $attributes['wooFreeTxt'] ) ? $attributes['wooFreeTxt'] : '';
						$output .= '</span>';
						$output .= '</div>';
					} else {
						$output .= '<div class="gutentor-wc-price">';
						$output .= wc_price( '0.00' );
						$output .= '</div>';
					}
				}
			}
			return $output;
		}

		/**
		 * Get Featured Woo price
		 * p6_featured_wc_price
		 *
		 * @param {array} $post
		 * @param {array} $product
		 * @param {array} $attributes
		 * @return string
		 */
		function p6_featured_wc_price( $post, $product, $attributes ) {
			$output = '';
			if ( isset( $attributes['fpWooOnPrice'] ) && $attributes['fpWooOnPrice'] ) {
				if ( $product->get_price_html() && $product->get_price() ) {
					$output .= '<div class="gutentor-fp-wc-price">';
					$output .= $product->get_price_html();
					$output .= '</div>';
				} else {
					if ( isset( $attributes['fpWooOnFreeTxt'] ) && $attributes['fpWooOnFreeTxt'] ) {
						$output .= '<div class="gutentor-fp-wc-price">';
						$output .= '<span class="woocommerce-Price-currencySymbol">';
						$output .= isset( $attributes['fpWooFreeTxt'] ) ? $attributes['fpWooFreeTxt'] : '';
						$output .= '</span>';
						$output .= '</div>';
					} else {
						$output .= '<div class="gutentor-fp-wc-price">';
						$output .= wc_price( '0.00' );
						$output .= '</div>';
					}
				}
			}
			return $output;
		}

		/**
		 * Get new_badge_product Data
		 *
		 * @param {array} $post
		 * @param {array} $product
		 * @param {array} $attributes
		 * @return string
		 */
		function new_badge_product( $post, $product ) {
			if ( ! $product ) {
				global $product;
			}
			$newness_days = 30;
			$created      = strtotime( $product->get_date_created() );
			if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
				return apply_filters( 'gutentor_woocommerce_new_badge', '<div class="gutentor-post-format gutentor-wc-new-wrap"><span class="gutentor-wc-new">' . esc_html__( 'New!', 'gutentor' ) . '</span></div>', $post, $product );

			}
			return '';
		}

		/**
		 * Get Edd new_badge_product Data
		 *
		 * @param {array} $post
		 * @param {array} $product
		 * @param {array} $attributes
		 * @return string
		 */
		function edd_new_badge_product( $post, $download ) {
			if ( ! $download ) {
				return '';
			}
			$newness_days = 30;
			$created      = strtotime( $download->post_date );
			if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
				return apply_filters( 'gutentor_edd_new_badge', '<div class="gutentor-post-format gutentor-edd-new-wrap"><span class="gutentor-edd-new">' . esc_html__( 'New!', 'gutentor' ) . '</span></div>', $post, $download );

			}
			return '';
		}

		/**
		 * Get p6 new_badge_product Data
		 *
		 * @param {array} $post
		 * @param {array} $product
		 * @param {array} $attributes
		 * @return string
		 */
		function p6_fp_new_badge_product( $post, $product ) {
			if ( ! $product ) {
				global $product;
			}
			$newness_days = 30;
			$created      = strtotime( $product->get_date_created() );
			if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
				return apply_filters( 'gutentor_woocommerce_new_badge', '<div class="gutentor-post-featured-format gutentor-pf-wc-new-wrap"><span class="gutentor-pf-wc-new">' . esc_html__( 'New!', 'gutentor' ) . '</span></div>', $post, $product );

			}
			return '';
		}

		/**
		 * Get p6 Edd new_badge_product Data
		 *
		 * @param {array} $post
		 * @param {array} $product
		 * @param {array} $attributes
		 * @return string
		 */
		function p6_fp_edd_new_badge_product( $post, $download ) {
			if ( ! $download ) {
				return '';
			}
			$newness_days = 30;
			$created      = strtotime( $download->post_date );
			if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
				return apply_filters( 'gutentor_fp_edd_new_badge', '<div class="gutentor-post-featured-format gutentor-fp-edd-new-wrap"><span class="gutentor-fp-edd-new">' . esc_html__( 'New!', 'gutentor' ) . '</span></div>', $post, $download );

			}
			return '';
		}

		/**
		 * Featured Image
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $product
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_woo_product_thumbnail( $post, $product, $attributes ) {
			$image_output      = $target = $rel = $link_class = '';
			$image_link_enable = false;
			if ( array_key_exists( 'pImgOnLink', $attributes ) ) {
				$linkInImage       = ( isset( $attributes['pImgOnLink'] ) ) ? $attributes['pImgOnLink'] : false;
				$image_link_enable = ( $linkInImage ) ? $linkInImage : false;
			}
			if ( array_key_exists( 'pImgOpenNewTab', $attributes ) ) {
				$openNewTab = ( isset( $attributes['pImgOpenNewTab'] ) ) ? $attributes['pImgOpenNewTab'] : false;
				$target     = ( $openNewTab ) ? 'target="_blank"' : '';
			}
			if ( array_key_exists( 'pImgLinkRel', $attributes ) ) {
				$rel = ( $attributes['pImgLinkRel'] ) ? 'rel="' . $attributes['pImgLinkRel'] . '"' : '';

			}
			if ( array_key_exists( 'pImgClass', $attributes ) ) {
				$link_class = ( $attributes['pImgClass'] ) ? sanitize_html_class( $attributes['pImgClass'] ) : '';

			}
			$overlay_obj    = ( isset( $attributes['pFImgOColor'] ) ) ? $attributes['pFImgOColor'] : false;
			$thumbnail_size = ( isset( $attributes['pFImgSize'] ) ) ? $attributes['pFImgSize'] : '';
			$overlay_enable = ( $overlay_obj && array_key_exists( 'enable', $overlay_obj ) ) ? $attributes['pFImgOColor']['enable'] : false;
			$overlay        = ( $overlay_enable ) ? 'gutentor-overlay' : '';
			if ( isset( $attributes['pOnFImg'] ) && $attributes['pOnFImg'] ) {
				$image_output  = '';
				$image_output .= '<div class="' . gutentor_concat_space( 'gutentor-image-thumb', $overlay ) . '">';
				$image_output .= woocommerce_get_product_thumbnail( $thumbnail_size );
				$image_output .= '</div>';
			}
			if ( $image_link_enable ) {
				$link               = apply_filters( 'woocommerce_loop_product_link', get_the_permalink( $post->ID ), $product );
				$image_without_link = $image_output;
				$image_output       = '';
				$image_output      .= '<a href="' . esc_url( $link ) . '" class="gutentor-post-image-link woocommerce-LoopProduct-link woocommerce-loop-product__link ' . gutentor_concat_space( $link_class ) . '" ' . gutentor_concat_space( $target, $rel ) . '>';
				$image_output      .= $image_without_link;
				$image_output      .= '</a>';
			}
			$image_output = apply_filters( 'gutentor_save_post_module_featured_image_data', $image_output, $post, $attributes );
			return $image_output;

		}

		/**
		 * Woo Featured Image
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $product
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function p6_fp_get_woo_product_thumbnail( $post, $product, $attributes ) {
			$image_output      = $target = $rel = $link_class = '';
			$image_link_enable = false;
			if ( array_key_exists( 'pFPImgOnLink', $attributes ) ) {
				$image_link_enable = ( isset( $attributes['pFPImgOnLink'] ) ) ? $attributes['pFPImgOnLink'] : false;
			}
			if ( array_key_exists( 'pFPImgOpenNewTab', $attributes ) ) {
				$target = ( isset( $attributes['pFPImgOpenNewTab'] ) ) ? 'target="_blank"' : '';
			}
			if ( array_key_exists( 'pFPImgLinkRel', $attributes ) ) {
				$rel = ( $attributes['pFPImgLinkRel'] ) ? 'rel="' . $attributes['pFPImgLinkRel'] . '"' : '';

			}
			if ( array_key_exists( 'pFPImgClass', $attributes ) ) {
				$link_class = ( $attributes['pFPImgClass'] ) ? sanitize_html_class( $attributes['pFPImgClass'] ) : '';

			}
			$overlay_obj    = ( isset( $attributes['pFPFImgOColor'] ) ) ? $attributes['pFPFImgOColor'] : false;
			$thumbnail_size = ( isset( $attributes['pFPFImgSize'] ) ) ? $attributes['pFPFImgSize'] : '';
			$overlay_enable = ( $overlay_obj && array_key_exists( 'enable', $overlay_obj ) ) ? $attributes['pFPFImgOColor']['enable'] : false;
			$overlay        = ( $overlay_enable ) ? 'gutentor-overlay' : '';
			if ( isset( $attributes['pOnFImg'] ) && $attributes['pOnFImg'] ) {
				$image_output  = '';
				$image_output .= '<div class="' . gutentor_concat_space( 'gutentor-image-thumb', $overlay ) . '">';
				$image_output .= woocommerce_get_product_thumbnail( $thumbnail_size );
				$image_output .= '</div>';
			}
			if ( $image_link_enable ) {
				$link               = apply_filters( 'woocommerce_loop_product_link', get_the_permalink( $post->ID ), $product );
				$image_without_link = $image_output;
				$image_output       = '';
				$image_output      .= '<a href="' . esc_url( $link ) . '" class="gutentor-post-image-link woocommerce-LoopProduct-link woocommerce-loop-product__link ' . gutentor_concat_space( $link_class ) . '" ' . gutentor_concat_space( $target, $rel ) . '>';
				$image_output      .= $image_without_link;
				$image_output      .= '</a>';
			}
			$image_output = apply_filters( 'gutentor_p6_post_module_featured_image_data', $image_output, $post, $attributes );
			return $image_output;

		}

		/**
		 * P6 Edd Featured Image
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function p6_fp_get_edd_thumbnail( $post, $attributes ) {
			$image_output      = $target = $rel = $link_class = '';
			$image_link_enable = false;
			if ( array_key_exists( 'pFPImgOnLink', $attributes ) ) {
				$linkInImage       = ( isset( $attributes['pFPImgOnLink'] ) ) ? $attributes['pFPImgOnLink'] : false;
				$image_link_enable = ( $linkInImage ) ? $linkInImage : false;
			}
			if ( array_key_exists( 'pFPImgOpenNewTab', $attributes ) ) {
				$openNewTab = ( isset( $attributes['pFPImgOpenNewTab'] ) ) ? $attributes['pFPImgOpenNewTab'] : false;
				$target     = ( $openNewTab ) ? 'target="_blank"' : '';
			}
			if ( array_key_exists( 'pFPImgLinkRel', $attributes ) ) {
				$rel = ( $attributes['pFPImgLinkRel'] ) ? 'rel="' . $attributes['pFPImgLinkRel'] . '"' : '';

			}
			if ( array_key_exists( 'pFPImgClass', $attributes ) ) {
				$link_class = ( $attributes['pFPImgClass'] ) ? sanitize_html_class( $attributes['pFPImgClass'] ) : '';

			}
			$overlay_obj    = ( isset( $attributes['pFPFImgOColor'] ) ) ? $attributes['pFPFImgOColor'] : false;
			$thumbnail_size = ( isset( $attributes['pFPFImgSize'] ) ) ? $attributes['pFPFImgSize'] : '';
			$overlay_enable = ( $overlay_obj && array_key_exists( 'enable', $overlay_obj ) ) ? $attributes['pFPFImgOColor']['enable'] : false;
			$overlay        = ( $overlay_enable ) ? 'gutentor-overlay' : '';
			if ( has_post_thumbnail( $post->ID ) ) {
				$get_thumbnail = get_the_post_thumbnail( $post->ID, $thumbnail_size, '' );
			} else {
				$get_thumbnail = '<img src="' . GUTENTOR_URL . 'assets/img/default-image.jpg">';
			}
			if ( isset( $attributes['pOnFPFImg'] ) && $attributes['pOnFPFImg'] ) {
				$image_output  = '';
				$image_output .= '<div class="' . gutentor_concat_space( 'gutentor-image-thumb', $overlay ) . '">';
				$image_output .= $get_thumbnail;
				$image_output .= '</div>';

			}
			if ( $image_link_enable ) {
				$image_without_link = $image_output;
				$image_output       = '';
				$image_output      .= '<a href="' . esc_url( get_the_permalink( $post->ID ) ) . '" class="gutentor-post-image-link ' . gutentor_concat_space( $link_class ) . '" ' . gutentor_concat_space( $target, $rel ) . '>';
				$image_output      .= $image_without_link;
				$image_output      .= '</a>';
			}
			$image_output = apply_filters( 'gutentor_p6_post_module_featured_image_data', $image_output, $post, $attributes );
			return $image_output;

		}

		/*=== Term Module Start =====*/

		/**
		 * Check if term has thumbnail
		 *
		 * @param {object} $term
		 * @return {boolean}
		 */
		public function has_term_thumbnail( $term ) {

			if ( ! $term ) {
				return false;
			}
			$flag = false;
			if ( 'product_cat' === $term->taxonomy ) {
				$flag = ( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
			} else {
				$tax_in_image = gutentor_get_options( 'tax-in-image' );
				if ( is_array( $tax_in_image ) ) {
					if ( in_array( $term->taxonomy, $tax_in_image ) ) {
						switch ( $term->taxonomy ) :
							case 'product_cat':
								$flag = ( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
								break;
							default:
								$gutentor_meta = $term && ! empty( $term->term_id ) ? get_term_meta( $term->term_id, 'gutentor_meta', true ) : false;
								$flag          = ( isset( $gutentor_meta['featured-image'] ) && ( $gutentor_meta['featured-image'] ) );
								break;
						endswitch;
					}
				}
			}
			return $flag;
		}

		/**
		 * Get term thumbnail id
		 *
		 * @param {object} $term
		 * @return {boolean}
		 */
		public function get_term_thumbnail_id( $term ) {
			if ( ! $term ) {
				return false;
			}
			switch ( $term->taxonomy ) :
				case 'product_cat':
					$flag = get_term_meta( $term->term_id, 'thumbnail_id', true );
					break;

				default:
					$gutentor_meta = $term && ! empty( $term->term_id ) ? get_term_meta( $term->term_id, 'gutentor_meta', true ) : false;
					$flag          = isset( $gutentor_meta['featured-image'] ) ? $gutentor_meta['featured-image'] : false;
					break;
			endswitch;

			return $flag;
		}

		/**
		 * Get Term Featured Image
		 *
		 * @access public
		 * @param {object} $term
		 * @param {array}  $attributes
		 *
		 * @return string
		 * @since 3.0.0
		 */
		public function get_term_featured_image( $term, $attributes ) {
			$output         = '';
			$output_image   = '';
			$thumbnail_size = ( isset( $attributes['tFImgSize'] ) ) ? $attributes['tFImgSize'] : 'large';
			$overlay        = ( isset( $attributes['tFImgOC']['enable'] ) ) ? $attributes['tFImgOC']['enable'] : '';
			$overlay        = $overlay ? 'g-overlay' : '';
			if ( $this->has_term_thumbnail( $term ) ) {
				$image_url = wp_get_attachment_image_src( $this->get_term_thumbnail_id( $term ), $thumbnail_size );
				if ( ! $image_url ) {
					$image_url[0] = GUTENTOR_URL . 'assets/img/default-image.jpg';
				}
				$url           = $image_url[0];
				$image_class   = 'gtf-image';
				$output_image .= '<div class="gtf-image-box">';
				$output_image .= '<div class="' . gutentor_concat_space( $image_class, $overlay ) . '">';
				$output_image .= '<img class="normal-image" src="' . esc_url( $url ) . '">';
				$output_image .= '</div>';
				$output_image .= '</div>';
				$output       .= apply_filters( 'gutentor_term_save_item_image_display_data', $output_image, esc_url( get_term_link( $term ) ), $attributes );

			}
			return $output;

		}

		/**
		 * Get Term Title
		 *
		 * @access public
		 * @param {object} $term
		 * @param {array}  $attributes
		 *
		 * @return string
		 * @since 3.0.0
		 */
		public function get_term_title( $term, $attributes ) {
			$output = '';
			if ( isset( $attributes['tOnTitle'] ) && $attributes['tOnTitle'] ) {
				$title_tag = $attributes['tTitleTag'];
				$output   .= '<' . $title_tag . ' class="g-d-title">';
				$output   .= '<a href="' . esc_url( get_term_link( $term ) ) . '" rel="bookmark">';
				$output   .= esc_html( $term->name );
				$output   .= '</a>';
				$output   .= '</' . $title_tag . '>';
			}
			return $output;

		}

		/**
		 * Get Term Count
		 *
		 * @access public
		 * @param {object} $term
		 * @param {array}  $attributes
		 *
		 * @return string
		 * @since 3.0.0
		 */
		public function get_term_count( $term, $attributes ) {
			$output = '';
			if ( isset( $attributes['tOnCount'] ) && $attributes['tOnCount'] ) {
				$termCount = esc_html( $term->count );
				$countOpt  = ( isset( $attributes['tCountOpt'] ) && ! empty( $attributes['tCountOpt'] ) ) ? $attributes['tCountOpt'] : '';
				$prefix    = ( isset( $countOpt['prefix'] ) ) ? $countOpt['prefix'] : '';
				if ( $prefix && is_string( $prefix ) ) {
					$termCount = $prefix . $termCount;
				}
				$suffix = ( isset( $countOpt['suffix'] ) ) ? $countOpt['suffix'] : '';
				if ( $suffix && is_string( $suffix ) ) {
					$termCount = $termCount . $suffix;
				}
				$output .= '<span class="g-d-count">';
				$output .= $termCount;
				$output .= '</span>';
			}
			return $output;

		}

		/**
		 * Get Term Title And Count
		 *
		 * @access public
		 * @param {object} $term
		 * @param {array}  $attributes
		 *
		 * @return string
		 * @since 3.0.0
		 */
		public function get_term_title_and_count( $term, $attributes ) {
			$output       = '';
			$countOpt     = ( isset( $attributes['tCountOpt'] ) && ! empty( $attributes['tCountOpt'] ) ) ? $attributes['tCountOpt'] : '';
			$countDisplay = ( isset( $countOpt['display'] ) ) ? $countOpt['display'] : false;

			if ( ! $countDisplay ) {
				return $this->get_term_title( $term, $attributes );
			}
			switch ( $countDisplay ) {
				case 'top':
					$output .= $this->get_term_count( $term, $attributes );
					$output .= $this->get_term_title( $term, $attributes );
					break;
				case 'front':
					$output .= "<div class='gtf-count-front'>";
					$output .= $this->get_term_count( $term, $attributes );
					$output .= $this->get_term_title( $term, $attributes );
					$output .= '</div>';
					break;
				case 'back':
					$output .= "<div class='gtf-count-back'>";
					$output .= $this->get_term_title( $term, $attributes );
					$output .= $this->get_term_count( $term, $attributes );
					$output .= '</div>';
					break;
				default:
					$output .= $this->get_term_title( $term, $attributes );
					$output .= $this->get_term_count( $term, $attributes );
					break;
			}
			return $output;

		}

		/**
		 * Term Title And Count Updated
		 *
		 * @access public
		 * @param {object} $term
		 * @param {array}  $attributes
		 *
		 * @return string
		 * @since 3.0.0
		 */
		public function get_term_title_and_count_updated( $term, $attributes, $sortingItem ) {
			$output       = '';
			$countOpt     = ( isset( $attributes['tCountOpt'] ) && ! empty( $attributes['tCountOpt'] ) ) ? $attributes['tCountOpt'] : '';
			$countDisplay = ( isset( $countOpt['display'] ) ) ? $countOpt['display'] : false;

			if ( ! $countDisplay ) {
				return $this->get_term_title( $term, $attributes );
			}
			if ( $countDisplay === 'sorting' ) {
				if ( $sortingItem === 'title' ) {
					$output .= $this->get_term_title( $term, $attributes );
				}
				if ( $sortingItem === 'count' ) {
					$output .= $this->get_term_count( $term, $attributes );
				}
			} else {
				switch ( $countDisplay ) {
					case 'top':
						$output .= $this->get_term_count( $term, $attributes );
						$output .= $this->get_term_title( $term, $attributes );
						break;
					case 'front':
						$output .= "<div class='gtf-count-front'>";
						$output .= $this->get_term_count( $term, $attributes );
						$output .= $this->get_term_title( $term, $attributes );
						$output .= '</div>';
						break;
					case 'back':
						$output .= "<div class='gtf-count-back'>";
						$output .= $this->get_term_title( $term, $attributes );
						$output .= $this->get_term_count( $term, $attributes );
						$output .= '</div>';
						break;
					default:
						$output .= $this->get_term_title( $term, $attributes );
						$output .= $this->get_term_count( $term, $attributes );
						break;
				}
			}
			return $output;
		}

		/**
		 * Get Term Description
		 *
		 * @access public
		 * @param {object} $term
		 * @param {array}  $attributes
		 *
		 * @return string
		 * @since 3.0.0
		 */
		public function get_term_description( $term, $attributes ) {
			$output   = '';
			$in_words = ( isset( $attributes['tExcerptLenInWords'] ) && $attributes['tExcerptLenInWords'] );
			if ( ! isset( $attributes['tExcerptLen'] ) ||
				! isset( $attributes['tOnDesc'] ) ||
				! $attributes['tOnDesc'] ||
				! gutentor_get_term_description( $term, $attributes['tExcerptLen'], $in_words ) ||
				$attributes['tExcerptLen'] <= 0 ) {
				return $output;
			}
			$output .= '<div class="g-d-desc">';
			$output .= '<p>' . gutentor_get_term_description( $term, $attributes['tExcerptLen'], $in_words ) . '</p>';
			$output .= '</div>';
			return $output;

		}

		/**
		 * Term Button
		 *
		 * @access public
		 * @param {object} $term
		 * @param {array}  $attributes
		 *
		 * @return string
		 * @since 3.0.0
		 */
		public function get_term_button( $term, $attributes ) {
			$output = $icon = '';
			if ( isset( $attributes['tOnBtn'] ) && $attributes['tOnBtn'] ) {
				$default_class       = gutentor_concat_space( 'gutentor-button', 'g-d-button' );
				$custom_class        = ( isset( $attributes['tBtnCName'] ) ) ? $attributes['tBtnCName'] : '';
				$icon_options        = ( isset( $attributes['tBtnIconOpt'] ) ) ? $attributes['tBtnIconOpt'] : '';
				$icon_position_class = GutentorButtonOptionsClasses( $icon_options );
				if ( $icon_position_class && $icon_position_class !== 'gutentor-icon-hide' ) {
					$icon = ( isset( $attributes['tBtnIcon'] ) && $attributes['tBtnIcon']['value'] ) ? '<i class="gutentor-button-icon ' . $attributes['tBtnIcon']['value'] . '" ></i>' : '';
				}
				$link_options = ( isset( $attributes['tBtnLink'] ) ) ? $attributes['tBtnLink'] : '';
				$output      .= '<a class="' . gutentor_concat_space( $default_class, $custom_class, GutentorButtonOptionsClasses( $icon_options ) ) . '" ' . apply_filters( 'gutentor_save_link_attr', '', esc_url( get_term_link( $term ) ), $link_options ) . '>' . $icon . '<span>' . esc_html( $attributes['tBtnTxt'] ) . '</span></a>';
			}
			return $output;
		}

		/**
		 * Edd Add to cart
		 *
		 * @access public
		 * @param {object} $post
		 * @param {array}  $attributes
		 *
		 * @return string
		 * @since 3.0.0
		 */
		public function get_edd_button( $post, $attributes ) {
			$output = $data_icon_attrs = '';
			if ( isset( $attributes['pOnBtn'] ) && $attributes['pOnBtn'] ) {
				$btnClass            = isset( $attributes['pBtnCName'] ) ? $attributes['pBtnCName'] : '';
				$default_class       = gutentor_concat_space( 'gutentor-button', 'gutentor-post-button', $btnClass );
				$icon_options        = ( isset( $attributes['pBtnIconOpt'] ) ) ? $attributes['pBtnIconOpt'] : '';
				$icon_position_class = GutentorButtonOptionsClasses( $icon_options );
				if ( $icon_position_class == 'gutentor-icon-before' || $icon_position_class == 'gutentor-icon-after' ) {
					$icon            = ( isset( $attributes['pBtnIcon'] ) && $attributes['pBtnIcon']['value'] ) ? $attributes['pBtnIcon']['value'] : '';
					$data_icon_attrs = 'data-icon="' . $icon . '"';
				}
				if ( gutentor_is_edd_wishlist_active() ) {
					remove_action( 'edd_purchase_link_top', 'edd_wl_load_wish_list_link' );
				}
				if ( gutentor_is_edd_favorites_active() ) {
					remove_action( 'edd_purchase_link_top', 'edd_favorites_load_link' );
				}
				$purchase_link = edd_get_purchase_link(
					array(
						'download_id'         => get_the_ID(),
						'is_gutentor'         => true,
						'price'               => false,
						'gutentor-attributes' => $attributes,
						'class'               => gutentor_concat_space( $default_class, GutentorButtonOptionsClasses( $icon_options ) ),
					)
				);

				if ( gutentor_is_edd_wishlist_active() ) {
					add_action( 'edd_purchase_link_top', 'edd_wl_load_wish_list_link' );

				}
				if ( gutentor_is_edd_favorites_active() ) {
					add_action( 'edd_purchase_link_top', 'edd_favorites_load_link' );
				}

				$output .= "<div class='" . apply_filters( 'gutentor_post_module_download_button_class', 'g-edd-cart', $post, $attributes ) . "' " . $data_icon_attrs . '>';
				$output .= $purchase_link;
				$output .= '</div>';

			}
			return $output;
		}

		/**
		 * Edd Wish List
		 *
		 * @access public
		 * @param {object} $post
		 * @param {array}  $attributes
		 *
		 * @return string
		 * @since 3.0.0
		 */
		public function get_edd_wish_list( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['eOnWl'] ) && $attributes['eOnWl'] && gutentor_is_edd_wishlist_active() ) {
				// add download $options
				$download_args = array(
					'download_id' => $post->ID,
					'class'       => 'g-edd-wl',
					'shortcode'   => true,
				);
				$output       .= '<div class="gutentor-edd-wl-wrap">';
				if ( gutentor_is_edd_favorites_active() ) {
					ob_start();
					$output .= edd_favorites_load_link( $post->ID ) . ob_get_clean();
				} elseif ( gutentor_is_edd_wishlist_active() ) {
					$output .= edd_wl_wish_list_link( $download_args );
				}
				$output .= '</div>';
			}
			return $output;
		}

		/**
		 * Featured Post Edd Wish List
		 *
		 * @access public
		 * @param {object} $post
		 * @param {array}  $attributes
		 *
		 * @return string
		 * @since 3.0.0
		 */
		public function get_fp_edd_wish_list( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['eFPOnWl'] ) && $attributes['eFPOnWl'] && gutentor_is_edd_wishlist_active() ) {
				// add download $options
				$download_args = array(
					'download_id' => $post->ID,
					'class'       => 'g-edd-wl',
					'shortcode'   => true,
				);
				$output       .= '<div class="gutentor-fp-edd-wl-wrap">';
				if ( gutentor_is_edd_favorites_active() ) {
					ob_start();
					$output .= edd_favorites_load_link( $post->ID ) . ob_get_clean();
				} elseif ( gutentor_is_edd_wishlist_active() ) {
					$output .= edd_wl_wish_list_link( $download_args );
				}
				$output .= '</div>';
			}
			return $output;
		}

		/**
		 * Featured Edd Add to cart
		 *
		 * @access public
		 * @param {object} $post
		 * @param {array}  $attributes
		 *
		 * @return string
		 * @since 3.0.0
		 */
		public function p6_get_fp_edd_button( $post, $attributes ) {
			$output = $data_icon_attrs = '';
			if ( isset( $attributes['pOnFPBtn'] ) && $attributes['pOnFPBtn'] ) {
				$btnClass            = isset( $attributes['pFPBtnCName'] ) ? $attributes['pFPBtnCName'] : '';
				$default_class       = gutentor_concat_space( 'gutentor-button', 'gutentor-post-featured-button', $btnClass );
				$icon_options        = ( isset( $attributes['pFPBtnIconOpt'] ) ) ? $attributes['pFPBtnIconOpt'] : '';
				$icon_position_class = GutentorButtonOptionsClasses( $icon_options );
				if ( $icon_position_class == 'gutentor-icon-before' || $icon_position_class == 'gutentor-icon-after' ) {
					$icon            = ( isset( $attributes['pFPBtnIcon'] ) && $attributes['pFPBtnIcon']['value'] ) ? $attributes['pFPBtnIcon']['value'] : '';
					$data_icon_attrs = 'data-icon="' . $icon . '"';
				}
				if ( gutentor_is_edd_wishlist_active() ) {
					remove_action( 'edd_purchase_link_top', 'edd_wl_load_wish_list_link' );
				}
				if ( gutentor_is_edd_favorites_active() ) {
					remove_action( 'edd_purchase_link_top', 'edd_favorites_load_link' );
				}
				$purchase_link = edd_get_purchase_link(
					array(
						'download_id'         => get_the_ID(),
						'is_gutentor'         => true,
						'gutentor-attributes' => $attributes,
						'class'               => gutentor_concat_space( $default_class, GutentorButtonOptionsClasses( $icon_options ) ),
					)
				);
				if ( gutentor_is_edd_wishlist_active() ) {
					add_action( 'edd_purchase_link_top', 'edd_wl_load_wish_list_link' );

				}
				if ( gutentor_is_edd_favorites_active() ) {
					add_action( 'edd_purchase_link_top', 'edd_favorites_load_link' );
				}
				$output .= "<div class='" . apply_filters( 'gutentor_post_module_download_button_class', 'g-edd-cart', $post, $attributes ) . "' " . $data_icon_attrs . '>';
				$output .= $purchase_link;
				$output .= '</div>';

			}
			return $output;
		}

		/**
		 * Edd Featured Image
		 *
		 * @static
		 * @access public
		 * @param {array} $post
		 * @param {array} $attributes
		 * @return string
		 * @since 2.0.1
		 */
		public function get_edd_thumbnail( $post, $attributes ) {
			$image_output      = $target = $rel = $link_class = '';
			$image_link_enable = false;
			if ( array_key_exists( 'pImgOnLink', $attributes ) ) {
				$linkInImage       = ( isset( $attributes['pImgOnLink'] ) ) ? $attributes['pImgOnLink'] : false;
				$image_link_enable = ( $linkInImage ) ? $linkInImage : false;
			}
			if ( array_key_exists( 'pImgOpenNewTab', $attributes ) ) {
				$openNewTab = ( isset( $attributes['pImgOpenNewTab'] ) ) ? $attributes['pImgOpenNewTab'] : false;
				$target     = ( $openNewTab ) ? 'target="_blank"' : '';
			}
			if ( array_key_exists( 'pImgLinkRel', $attributes ) ) {
				$rel = ( $attributes['pImgLinkRel'] ) ? 'rel="' . $attributes['pImgLinkRel'] . '"' : '';

			}
			if ( array_key_exists( 'pImgClass', $attributes ) ) {
				$link_class = ( $attributes['pImgClass'] ) ? sanitize_html_class( $attributes['pImgClass'] ) : '';

			}
			$overlay_obj    = ( isset( $attributes['pFImgOColor'] ) ) ? $attributes['pFImgOColor'] : false;
			$thumbnail_size = ( isset( $attributes['pFImgSize'] ) ) ? $attributes['pFImgSize'] : '';
			$overlay_enable = ( $overlay_obj && array_key_exists( 'enable', $overlay_obj ) ) ? $attributes['pFImgOColor']['enable'] : false;
			$overlay        = ( $overlay_enable ) ? 'gutentor-overlay' : '';
			if ( has_post_thumbnail( $post->ID ) ) {
				$get_thumbnail = get_the_post_thumbnail( $post->ID, $thumbnail_size, '' );
			} else {
				$get_thumbnail = '<img src="' . GUTENTOR_URL . 'assets/img/default-image.jpg">';
			}
			if ( isset( $attributes['pOnFImg'] ) && $attributes['pOnFImg'] ) {
				$image_output  = '';
				$image_output .= '<div class="' . gutentor_concat_space( 'gutentor-image-thumb', $overlay ) . '">';
				$image_output .= $get_thumbnail;
				$image_output .= '</div>';
			}
			if ( $image_link_enable ) {
				$image_without_link = $image_output;
				$image_output       = '';
				$image_output      .= '<a href="' . esc_url( get_the_permalink( $post->ID ) ) . '" class="gutentor-post-image-link ' . gutentor_concat_space( $link_class ) . '" ' . gutentor_concat_space( $target, $rel ) . '>';
				$image_output      .= $image_without_link;
				$image_output      .= '</a>';
			}
			$image_output = apply_filters( 'gutentor_save_post_module_featured_image_data', $image_output, $post, $attributes );
			return $image_output;

		}

		/**
		 * Get updated Edd price
		 * updated_wc_price
		 *
		 * @param {array} $post
		 * @param {array} $product
		 * @param {array} $attributes
		 * @return string
		 */
		function updated_edd_price( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['wooOnPrice'] ) && $attributes['wooOnPrice'] ) {
				$output .= '<div class="gutentor-edd-price">';
				if ( edd_has_variable_prices( $post->ID ) ) {
					$output .= edd_price_range( $post->ID );
				} else {
					if ( edd_get_download_price( $post->ID ) == 0 ) {
						if ( isset( $attributes['wooOnFreeTxt'] ) && $attributes['wooOnFreeTxt'] ) {
							$output .= '<div class="edd_price">';
							$output .= isset( $attributes['wooFreeTxt'] ) ? $attributes['wooFreeTxt'] : '';
							$output .= '</div>';
						} else {
							$output .= edd_price( $post->ID, false );
						}
					} else {
						$output .= edd_price( $post->ID, false );
					}
				}
				$output .= '</div>';
			}
			return $output;
		}

		/**
		 * Get Featured Edd price
		 * updated_wc_price
		 *
		 * @param {array} $post
		 * @param {array} $product
		 * @param {array} $attributes
		 * @return string
		 */
		function p6_featured_edd_price( $post, $attributes ) {
			$output = '';
			if ( isset( $attributes['fpWooOnPrice'] ) && $attributes['fpWooOnPrice'] ) {
				$output .= '<div class="gutentor-fp-edd-price">';
				if ( edd_has_variable_prices( $post->ID ) ) {
					$output .= edd_price_range( $post->ID );
				} else {
					if ( edd_get_download_price( $post->ID ) == 0 ) {
						if ( isset( $attributes['fpWooOnFreeTxt'] ) && $attributes['fpWooOnFreeTxt'] ) {
							$output .= '<div class="edd_price">';
							$output .= isset( $attributes['fpWooFreeTxt'] ) ? $attributes['fpWooFreeTxt'] : '';
							$output .= '</div>';
						} else {
							$output .= edd_price( $post->ID, false );
						}
					} else {
						$output .= edd_price( $post->ID, false );
					}
				}
				$output .= '</div>';
			}
			return $output;
		}

	}
}
