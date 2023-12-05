<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Normal_P1_EDD_Templates' ) ) {

	/**
	 * Blog_Post_Templates Class For Gutentor
	 *
	 * @package Gutentor
	 * @since 2.0.0
	 */
	class Gutentor_Normal_P1_EDD_Templates extends Gutentor_Query_Elements {

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
			add_filter( 'gutentor_post_module_p1_query_data', array( $this, 'load_blog_post_template' ), 99999, 3 );
		}

		/**
		 * Load Grid Template 1
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function gutentor_p1_grid_template1( $data, $post, $attributes ) {
			if ( ! gutentor_is_edd_active() ) {
				return $data;
			}
			$download            = edd_get_download( $post->ID );
			$query_sorting       = array_key_exists( 'blockSortableItems', $attributes ) ? $attributes['blockSortableItems'] : false;
			$enable_post_format  = isset( $attributes['pOnPostFormatOpt'] ) && $attributes['pOnPostFormatOpt'];
			$post_format_pos     = ( isset( $attributes['pPostFormatPos'] ) ) ? $attributes['pPostFormatPos'] : false;
			$cat_pos             = ( isset( $attributes['pPostCatPos'] ) ) ? $attributes['pPostCatPos'] : false;
			$enable_featured_cat = isset( $attributes['pOnFeaturedCat'] ) && $attributes['pOnFeaturedCat'];
			$enable_featured_img = isset( $attributes['pOnFImg'] ) && $attributes['pOnFImg'];
			$enable_avatar       = isset( $attributes['pOnAvatar'] ) && $attributes['pOnAvatar'];
			$avatar_pos          = ( isset( $attributes['pAvatarPos'] ) ) ? $attributes['pAvatarPos'] : false;
			$output              = '';
			if ( $query_sorting ) :
				foreach ( $query_sorting as $element ) :
					if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
						return $output;
					}
					switch ( $element['itemValue'] ) {
						case 'featured-image':
							if ( $enable_featured_img ) {
                                $output .= "<div class='" . apply_filters( 'gutentor_post_module_post_image_box', 'gutentor-post-image-box',$post, $attributes ) . "'>";
                                if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
									$output .= $this->get_avatar_data( $post, $attributes );
								}
								$output .= $this->get_edd_thumbnail( $post, $attributes );
								if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
									$output .= $this->edd_new_badge_product( $post, $download );
								}
								if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
									$output .= $this->get_post_module_badges_collection( $post, $attributes );
								}
								$output .= '</div>';
							}
							break;
						case 'title':
							if ( $post_format_pos === 'gutentor-pf-pos-before-title' || $this->avatar_on_title_condition( $avatar_pos ) ) {
								$output .= '<div class="gutentor-post-title-data-wrap">';
								if ( $enable_avatar && $enable_featured_img && $this->avatar_on_title_condition( $avatar_pos ) ) {
									$output .= $this->get_avatar_data( $post, $attributes );
								}
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {

									$output .= $this->edd_new_badge_product( $post, $download );
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
						case 'avatar':
							$output .= $this->get_avatar_data( $post, $attributes );
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
						case 'wishlist':
							$output .= $this->get_edd_wish_list( $post, $attributes );
							break;
						case 'primary-entry-meta':
							$output .= $this->get_primary_meta( $post, $attributes );
							break;

						case 'secondary-entry-meta':
							$output .= $this->get_secondary_meta( $post, $attributes );
							break;
						case 'description':
							if ( $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {
								$output .= '<div class="gutentor-post-desc-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {

									$output .= $this->edd_new_badge_product( $post, $download );
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
							if ( $post_format_pos === 'gutentor-pf-pos-before-button' ) {
								$output .= '<div class="gutentor-post-button-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-button' ) {

									$output .= $this->edd_new_badge_product( $post, $download );
								}
								if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-button' ) {

									$output .= $this->get_post_module_badges_collection( $post, $attributes );
								}

								$output .= $this->get_edd_button( $post, $attributes );
								$output .= '</div>';
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
			return $output;
		}

		/**
		 * Load List Template 1
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function gutentor_p1_list_template1( $data, $post, $attributes ) {

			if ( ! gutentor_is_edd_active() ) {
				return $data;
			}
			$download            = edd_get_download( $post->ID );
			$query_sorting       = array_key_exists( 'blockSortableItems', $attributes ) ? $attributes['blockSortableItems'] : false;
			$enable_post_format  = isset( $attributes['pOnPostFormatOpt'] ) && $attributes['pOnPostFormatOpt'];
			$post_format_pos     = ( isset( $attributes['pPostFormatPos'] ) ) ? $attributes['pPostFormatPos'] : false;
			$cat_pos             = ( isset( $attributes['pPostCatPos'] ) ) ? $attributes['pPostCatPos'] : false;
			$enable_featured_cat = isset( $attributes['pOnFeaturedCat'] ) && $attributes['pOnFeaturedCat'];
			$enable_featured_img = isset( $attributes['pOnFImg'] ) && $attributes['pOnFImg'];
			$enable_avatar       = isset( $attributes['pOnAvatar'] ) && $attributes['pOnAvatar'];
			$avatar_pos          = ( isset( $attributes['pAvatarPos'] ) ) ? $attributes['pAvatarPos'] : false;

			$output = '';
			if ( $enable_featured_img ) {
                $output .= "<div class='" . apply_filters( 'gutentor_post_module_post_image_box', 'gutentor-post-image-box',$post, $attributes ) . "'>";
                if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
					$output .= $this->get_avatar_data( $post, $attributes );
				}
				$output .= $this->get_edd_thumbnail( $post, $attributes );
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
							if ( $post_format_pos === 'gutentor-pf-pos-before-title' || $this->avatar_on_title_condition( $avatar_pos ) ) {
								$output .= '<div class="gutentor-post-title-data-wrap">';
								if ( $enable_avatar && $enable_featured_img && $this->avatar_on_title_condition( $avatar_pos ) ) {
									$output .= $this->get_avatar_data( $post, $attributes );
								}
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {

									$output .= $this->edd_new_badge_product( $post, $download );
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
						case 'avatar':
							$output .= $this->get_avatar_data( $post, $attributes );
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
						case 'wishlist':
							$output .= $this->get_edd_wish_list( $post, $attributes );
							break;
						case 'primary-entry-meta':
							$output .= $this->get_primary_meta( $post, $attributes );
							break;
						case 'secondary-entry-meta':
							$output .= $this->get_secondary_meta( $post, $attributes );
							break;
						case 'description':
							if ( $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {
								$output .= '<div class="gutentor-post-desc-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {

									$output .= $this->edd_new_badge_product( $post, $download );
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
							if ( $post_format_pos === 'gutentor-pf-pos-before-button' ) {
								$output .= '<div class="gutentor-post-button-data-wrap">';
								if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-button' ) {

									$output .= $this->edd_new_badge_product( $post, $download );
								}
								if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-button' ) {

									$output .= $this->get_post_module_badges_collection( $post, $attributes );
								}
								$output .= $this->get_edd_button( $post, $attributes );
								$output .= '</div>';
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
			return $output;

		}

		/**
		 * Load Template 1
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function gutentor_p1_template1( $data, $post, $attributes ) {

			$template_style = isset( $attributes['gStyle'] ) ? $attributes['gStyle'] : false;
			$output         = '';
			if ( $template_style == 'gutentor-blog-grid' ) {
				$output = $this->gutentor_p1_grid_template1( $data, $post, $attributes );
			} elseif ( $template_style == 'gutentor-blog-list' ) {
				$output = $this->gutentor_p1_list_template1( $data, $post, $attributes );
			}
			return $output;
		}

		/**
		 * Load Template 2
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function gutentor_p1_template2( $data, $post, $attributes ) {

			if ( ! gutentor_is_edd_active() ) {
				return $data;
			}
			$download = edd_get_download( $post->ID );

			$enable_post_format  = ( isset( $attributes['pOnPostFormatOpt'] ) ) ? $attributes['pOnPostFormatOpt'] : false;
			$post_format_pos     = ( isset( $attributes['pPostFormatPos'] ) ) ? $attributes['pPostFormatPos'] : false;
			$cat_pos             = ( isset( $attributes['pPostCatPos'] ) ) ? $attributes['pPostCatPos'] : false;
			$enable_featured_cat = ( isset( $attributes['pOnFeaturedCat'] ) ) ? $attributes['pOnFeaturedCat'] : false;
			$enable_featured_img = ( isset( $attributes['pOnFImg'] ) ) ? $attributes['pOnFImg'] : false;
			$enable_avatar       = ( isset( $attributes['pOnAvatar'] ) ) ? $attributes['pOnAvatar'] : false;
			$avatar_pos          = ( isset( $attributes['pAvatarPos'] ) ) ? $attributes['pAvatarPos'] : false;
			$output              = '';
			if ( $enable_featured_img ) {
                $output .= "<div class='" . apply_filters( 'gutentor_post_module_post_image_box', 'gutentor-post-image-box',$post, $attributes ) . "'>";
                if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
					$output .= $this->get_avatar_data( $post, $attributes );
				}
				$output .= $this->get_edd_thumbnail( $post, $attributes );
				if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
					$output .= $this->edd_new_badge_product( $post, $download );
				}
				if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
					$output .= $this->get_post_module_badges_collection( $post, $attributes );
				}
				$output .= $this->get_primary_meta( $post, $attributes );
				$output .= '</div>';/*.gutentor-post-image-box*/
			}
			$output .= '<div class="gutentor-post-content">';
			if ( $enable_avatar && $avatar_pos === 'g-avatar-b-title' ) {

				$output .= $this->get_avatar_data( $post, $attributes );
			}
			if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {

				$output .= $this->edd_new_badge_product( $post, $download );
			}
			if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-title' ) {

				$output .= $this->get_post_module_badges_collection( $post, $attributes );
			}
			$output .= $this->get_title( $post, $attributes );

			$output .= $this->updated_edd_price( $post, $attributes );
			if ( isset( $attributes['wooOnRating'] ) && $attributes['wooOnRating'] ) {
				if ( gutentor_custom_edd_review( $post->ID ) ) {
					$output .= '<div class="gutentor-edd-rating">';
					$output .= gutentor_custom_edd_review( $post->ID );
					$output .= '</div>';
				}
			}
			if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {

				$output .= $this->edd_new_badge_product( $post, $download );
			}
			if ( $enable_avatar && $avatar_pos === 'g-avatar-b-content' ) {

				$output .= $this->get_avatar_data( $post, $attributes );
			}
			if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-ct-box' ) {

				$output .= $this->get_post_module_badges_collection( $post, $attributes );
			}
			$output .= $this->get_description( $post, $attributes );
			$output .= $this->get_secondary_meta( $post, $attributes );
			if ( $enable_avatar && $avatar_pos === 'g-avatar-b-button' ) {

				$output .= $this->get_avatar_data( $post, $attributes );
			}
			if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-button' ) {

				$output .= $this->edd_new_badge_product( $post, $download );
			}
			$output .= $this->get_edd_wish_list( $post, $attributes );
			if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-button' ) {

				$output .= $this->get_post_module_badges_collection( $post, $attributes );
			}
			$output .= $this->get_edd_button( $post, $attributes );
			$output .= '</div>';/*.gutentor-post-content*/
			return $output;
		}

		/**
		 * Load Template 4
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function gutentor_p1_template4( $data, $post, $attributes ) {

			if ( ! gutentor_is_edd_active() ) {
				return $data;
			}
			$download            = edd_get_download( $post->ID );
			$enable_post_format  = isset( $attributes['pOnPostFormatOpt'] ) && $attributes['pOnPostFormatOpt'];
			$post_format_pos     = ( isset( $attributes['pPostFormatPos'] ) ) ? $attributes['pPostFormatPos'] : false;
			$enable_featured_img = isset( $attributes['pOnFImg'] ) && $attributes['pOnFImg'];
			$enable_avatar       = isset( $attributes['pOnAvatar'] ) && $attributes['pOnAvatar'];
			$avatar_pos          = ( isset( $attributes['pAvatarPos'] ) ) ? $attributes['pAvatarPos'] : false;
			$cat_pos             = ( isset( $attributes['pPostCatPos'] ) ) ? $attributes['pPostCatPos'] : false;
			$enable_featured_cat = isset( $attributes['pOnFeaturedCat'] ) && $attributes['pOnFeaturedCat'];
			$output              = '';
			if ( $enable_featured_img ) {
                $output .= "<div class='" . apply_filters( 'gutentor_post_module_post_image_box', 'gutentor-post-image-box',$post, $attributes ) . "'>";
                if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
					$output .= $this->get_avatar_data( $post, $attributes );
				}
				$output .= $this->get_edd_thumbnail( $post, $attributes );
				if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
					$output .= $this->edd_new_badge_product( $post, $download );
				}
				if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
					$output .= $this->get_post_module_badges_collection( $post, $attributes );
				}
				$output .= '</div>';/*.gutentor-post-image-box*/
			}
			$output .= '<div class="gutentor-post-content">';
			if ( $enable_avatar && $avatar_pos === 'g-avatar-b-title' ) {

				$output .= $this->get_avatar_data( $post, $attributes );
			}
			if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {

				$output .= $this->edd_new_badge_product( $post, $download );
			}
			if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
				$output .= $this->get_post_module_badges_collection( $post, $attributes );
			}
			$output .= $this->get_title( $post, $attributes );
			$output .= $this->get_primary_meta( $post, $attributes );
			if ( $enable_avatar && $avatar_pos === 'g-avatar-b-content' ) {

				$output .= $this->get_avatar_data( $post, $attributes );
			}
			if ( isset( $attributes['wooOnRating'] ) && $attributes['wooOnRating'] ) {
				if ( gutentor_custom_edd_review( $post->ID ) ) {
					$output .= '<div class="gutentor-edd-rating">';
					$output .= gutentor_custom_edd_review( $post->ID );
					$output .= '</div>';
				}
			}
			if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {

				$output .= $this->edd_new_badge_product( $post, $download );
			}
			if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
				$output .= $this->get_post_module_badges_collection( $post, $attributes );
			}
			$output .= $this->get_description( $post, $attributes );
			$output .= $this->get_secondary_meta( $post, $attributes );
			if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-button' ) {

				$output .= $this->edd_new_badge_product( $post, $download );
			}
			if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
				$output .= $this->get_post_module_badges_collection( $post, $attributes );
			}
			$output .= '<div class="gutentor-post-f">';
			$output .= $this->updated_edd_price( $post, $attributes );
			$output .= '<div class="gutentor-post-btn-grp">';
			$output .= $this->get_preview_button( $post, $attributes );
			$output .= $this->get_edd_wish_list( $post, $attributes );
			if ( $enable_avatar && $avatar_pos === 'g-avatar-b-button' ) {

				$output .= $this->get_avatar_data( $post, $attributes );
			}
			$output .= $this->get_edd_button( $post, $attributes );
			$output .= '</div>';/*.gutentor-post-btn-grp*/
			$output .= '</div>';/*.gutentor-post-content*/
			$output .= '</div>';/*.gutentor-post-content*/
			return $output;
		}


		/**
		 * add Template 5
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function gutentor_p1_bg_image_template5( $data, $post, $attributes ) {

			if ( ! gutentor_is_edd_active() ) {
				return $data;
			}

			$output              = '';
			$download            = edd_get_download( $post->ID );
			$query_sorting       = array_key_exists( 'blockSortableItems', $attributes ) ? $attributes['blockSortableItems'] : false;
			$enable_post_format  = isset( $attributes['pOnPostFormatOpt'] ) && $attributes['pOnPostFormatOpt'];
			$post_format_pos     = ( isset( $attributes['pPostFormatPos'] ) ) ? $attributes['pPostFormatPos'] : false;
			$cat_pos             = ( isset( $attributes['pPostCatPos'] ) ) ? $attributes['pPostCatPos'] : false;
			$enable_featured_cat = isset( $attributes['pOnFeaturedCat'] ) && $attributes['pOnFeaturedCat'];
			$enable_featured_img = isset( $attributes['pOnFImg'] ) && $attributes['pOnFImg'];
			$enable_avatar       = isset( $attributes['pOnAvatar'] ) && $attributes['pOnAvatar'];
			$avatar_pos          = ( isset( $attributes['pAvatarPos'] ) ) ? $attributes['pAvatarPos'] : false;
			if ( $enable_featured_img && has_post_thumbnail( $post->ID ) ) {
				$enable_overlayImage = false;
				$overlayImage        = isset( $attributes['pFImgOColor'] ) && $attributes['pFImgOColor'];
				if ( $overlayImage ) {
					$enable_overlayImage = ( isset( $attributes['pFImgOColor']['enable'] ) ) ? $attributes['pFImgOColor']['enable'] : false;
				}
				$url     = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $attributes['pFImgSize'] );
				$overlay = $enable_overlayImage ? 'g-overlay' : '';/*gutentor-overlay for bc*/
				$output .= "<div class='" . apply_filters( 'gutentor_post_module_t5_item_height', gutentor_concat_space( 'gptm-bg-image', 'gptm-item-height', $overlay ), $attributes ) . "' style='background-image:url(" . esc_url( is_array( $url ) && ! empty( $url ) ? $url[0] : '' ) . ")'>";
				if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
					$output .= $this->get_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
					$output .= $this->edd_new_badge_product( $post, $download );
				}
				if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
					$output .= $this->get_post_module_badges_collection( $post, $attributes );
				}
                $output .= apply_filters( 'gutentor_edit_post_module_featured_image_popup_data','', $post, $attributes );
                $output .= '<div class="gutentor-post-content">';/*gutentor-post-content for bc*/
				if ( $query_sorting ) :
					foreach ( $query_sorting as $element ) :
						if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
							return $output;
						}
						switch ( $element['itemValue'] ) {
							case 'title':
								if ( $post_format_pos === 'gutentor-pf-pos-before-title' || $this->avatar_on_title_condition( $avatar_pos ) ) {
									$output .= '<div class="gutentor-post-title-data-wrap">';
									if ( $enable_avatar && $enable_featured_img && $this->avatar_on_title_condition( $avatar_pos ) ) {
										$output .= $this->get_avatar_data( $post, $attributes );
									}
									if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {

										$output .= $this->edd_new_badge_product( $post, $download );
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
							case 'wishlist':
								$output .= $this->get_edd_wish_list( $post, $attributes );
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
								if ( $post_format_pos === 'gutentor-pf-pos-before-ct-box' || $avatar_pos === 'g-avatar-b-content' ) {
									$output .= '<div class="gutentor-post-desc-data-wrap">';
									if ( $enable_avatar && $avatar_pos === 'g-avatar-b-content' ) {

										$output .= $this->get_avatar_data( $post, $attributes );
									}
									if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {
										$output .= $this->edd_new_badge_product( $post, $download );
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
								if ( $post_format_pos === 'gutentor-pf-pos-before-button' || $avatar_pos === 'g-avatar-b-button' ) {
									$output .= '<div class="gutentor-post-button-data-wrap">';
									if ( $enable_avatar && $avatar_pos === 'g-avatar-b-button' ) {

										$output .= $this->get_avatar_data( $post, $attributes );
									}
									if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-button' ) {

										$output .= $this->edd_new_badge_product( $post, $download );
									}
									if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-button' ) {

										$output .= $this->get_post_module_badges_collection( $post, $attributes );
									}
									$output .= $this->get_edd_button( $post, $attributes );

									$output .= '</div>';
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
				$output .= '</div>';/*.gptm-bg-image*/
			} else {
				$output .= "<div class='" . apply_filters( 'gutentor_post_module_t5_item_height', 'gptm-item-height', $attributes ) . "'>";
				if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
					$output .= $this->get_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
					$output .= $this->edd_new_badge_product( $post, $download );
				}
				if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
					$output .= $this->get_post_module_badges_collection( $post, $attributes );
				}
                $output .= apply_filters( 'gutentor_edit_post_module_featured_image_popup_data','', $post, $attributes );
                $output .= '<div class="gutentor-post-content">';/*gutentor-post-content for bc*/
				if ( $query_sorting ) :
					foreach ( $query_sorting as $element ) :
						if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
							return $output;
						}
						switch ( $element['itemValue'] ) {
							case 'title':
								if ( $post_format_pos === 'gutentor-pf-pos-before-title' || $avatar_pos === 'g-avatar-b-title' ) {
									$output .= '<div class="gutentor-post-title-data-wrap">';
									if ( $enable_avatar && $avatar_pos === 'g-avatar-b-title' ) {
										$output .= $this->get_avatar_data( $post, $attributes );
									}
									if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {

										$output .= $this->edd_new_badge_product( $post, $download );
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
							case 'wishlist':
								$output .= $this->get_edd_wish_list( $post, $attributes );
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
								if ( $post_format_pos === 'gutentor-pf-pos-before-ct-box' || $avatar_pos === 'g-avatar-b-content' ) {
									$output .= '<div class="gutentor-post-desc-data-wrap">';
									if ( $enable_avatar && $avatar_pos === 'g-avatar-b-content' ) {

										$output .= $this->get_avatar_data( $post, $attributes );
									}
									if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {

										$output .= $this->edd_new_badge_product( $post, $download );
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
								if ( $post_format_pos === 'gutentor-pf-pos-before-button' || $avatar_pos === 'g-avatar-b-button' ) {
									$output .= '<div class="gutentor-post-button-data-wrap">';
									if ( $enable_avatar && $avatar_pos === 'g-avatar-b-button' ) {

										$output .= $this->get_avatar_data( $post, $attributes );
									}
									if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-button' ) {

										$output .= $this->edd_new_badge_product( $post, $download );
									}
									if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-button' ) {

										$output .= $this->get_post_module_badges_collection( $post, $attributes );
									}
									$output .= $this->get_edd_button( $post, $attributes );
									$output .= '</div>';
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
				$output .= '</div>';/*.gptm-item-height*/
			}
			return $output;

		}

        /**
         * add Template 5
         *
         * @param {string} $data
         * @param {array}  $post
         * @param {array}  $attributes
         * @return {mix}
         */
        public function gutentor_p1_normal_image_template5( $data, $post, $attributes ) {

            if ( ! gutentor_is_edd_active() ) {
                return $data;
            }

            $output              = '';
            $download            = edd_get_download( $post->ID );
            $query_sorting       = array_key_exists( 'blockSortableItems', $attributes ) ? $attributes['blockSortableItems'] : false;
            $enable_post_format  = isset( $attributes['pOnPostFormatOpt'] ) && $attributes['pOnPostFormatOpt'];
            $post_format_pos     = ( isset( $attributes['pPostFormatPos'] ) ) ? $attributes['pPostFormatPos'] : false;
            $cat_pos             = ( isset( $attributes['pPostCatPos'] ) ) ? $attributes['pPostCatPos'] : false;
            $enable_featured_cat = isset( $attributes['pOnFeaturedCat'] ) && $attributes['pOnFeaturedCat'];
            $enable_featured_img = isset( $attributes['pOnFImg'] ) && $attributes['pOnFImg'];
            $enable_avatar       = isset( $attributes['pOnAvatar'] ) && $attributes['pOnAvatar'];
            $avatar_pos          = ( isset( $attributes['pAvatarPos'] ) ) ? $attributes['pAvatarPos'] : false;
            if ( $enable_featured_img && has_post_thumbnail( $post->ID ) ) {
                $enable_overlayImage = false;
                $overlayImage        = isset( $attributes['pFImgOColor'] ) && $attributes['pFImgOColor'];
                if ( $overlayImage ) {
                    $enable_overlayImage = ( isset( $attributes['pFImgOColor']['enable'] ) ) ? $attributes['pFImgOColor']['enable'] : false;
                }
                $overlay = $enable_overlayImage ? 'g-overlay' : '';/*gutentor-overlay for bc*/
                $output .= "<div class='" . apply_filters( 'gutentor_post_module_t5_item_height', gutentor_concat_space( 'gptm-bg-image', 'gptm-item-height', $overlay ), $attributes  ) . "'>";
                if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
                    $output .= $this->get_avatar_data( $post, $attributes );
                }
                if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
                    $output .= $this->edd_new_badge_product( $post, $download );
                }
                $output .= $this->get_edd_thumbnail( $post, $attributes );
                if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
                    $output .= $this->get_post_module_badges_collection( $post, $attributes );
                }
                $output .= apply_filters( 'gutentor_edit_post_module_featured_image_popup_data','', $post, $attributes );
                $output .= '<div class="gutentor-post-content">';/*gutentor-post-content for bc*/
                if ( $query_sorting ) :
                    foreach ( $query_sorting as $element ) :
                        if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
                            return $output;
                        }
                        switch ( $element['itemValue'] ) {
                            case 'title':
                                if ( $post_format_pos === 'gutentor-pf-pos-before-title' || $this->avatar_on_title_condition( $avatar_pos ) ) {
                                    $output .= '<div class="gutentor-post-title-data-wrap">';
                                    if ( $enable_avatar && $enable_featured_img && $this->avatar_on_title_condition( $avatar_pos ) ) {
                                        $output .= $this->get_avatar_data( $post, $attributes );
                                    }
                                    if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {

                                        $output .= $this->edd_new_badge_product( $post, $download );
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
                            case 'wishlist':
                                $output .= $this->get_edd_wish_list( $post, $attributes );
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
                                if ( $post_format_pos === 'gutentor-pf-pos-before-ct-box' || $avatar_pos === 'g-avatar-b-content' ) {
                                    $output .= '<div class="gutentor-post-desc-data-wrap">';
                                    if ( $enable_avatar && $avatar_pos === 'g-avatar-b-content' ) {

                                        $output .= $this->get_avatar_data( $post, $attributes );
                                    }
                                    if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {
                                        $output .= $this->edd_new_badge_product( $post, $download );
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
                                if ( $post_format_pos === 'gutentor-pf-pos-before-button' || $avatar_pos === 'g-avatar-b-button' ) {
                                    $output .= '<div class="gutentor-post-button-data-wrap">';
                                    if ( $enable_avatar && $avatar_pos === 'g-avatar-b-button' ) {

                                        $output .= $this->get_avatar_data( $post, $attributes );
                                    }
                                    if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-button' ) {

                                        $output .= $this->edd_new_badge_product( $post, $download );
                                    }
                                    if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-button' ) {

                                        $output .= $this->get_post_module_badges_collection( $post, $attributes );
                                    }
                                    $output .= $this->get_edd_button( $post, $attributes );

                                    $output .= '</div>';
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
                $output .= '</div>';/*.gptm-bg-image*/
            } else {
                $output .= "<div class='" . apply_filters( 'gutentor_post_module_t5_item_height', 'gptm-item-height', $attributes ) . "'>";
                if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
                    $output .= $this->get_avatar_data( $post, $attributes );
                }
                if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
                    $output .= $this->edd_new_badge_product( $post, $download );
                }
                $overlay_obj    = ( isset( $attributes['pFImgOColor'] ) ) ? $attributes['pFImgOColor'] : false;
                $overlay_enable = ( $overlay_obj && array_key_exists( 'enable', $overlay_obj ) ) ? $attributes['pFImgOColor']['enable'] : false;
                $overlay        = ( $overlay_enable ) ? 'gutentor-overlay' : '';
                $output .= '<div class="gutentor-image-thumb">';/*gutentor-post-content for bc*/
                $output .= '<img class="' . gutentor_concat_space( 'normal-image', $overlay ) . '" src="' . GUTENTOR_URL . 'assets/img/default-image.jpg">';
                $output .= '</div>';/*gutentor-post-content for bc*/
                if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
                    $output .= $this->get_post_module_badges_collection( $post, $attributes );
                }
                $output .= apply_filters( 'gutentor_edit_post_module_featured_image_popup_data','', $post, $attributes );
                $output .= '<div class="gutentor-post-content">';/*gutentor-post-content for bc*/
                if ( $query_sorting ) :
                    foreach ( $query_sorting as $element ) :
                        if ( ! ( array_key_exists( 'itemValue', $element ) ) ) {
                            return $output;
                        }
                        switch ( $element['itemValue'] ) {
                            case 'title':
                                if ( $post_format_pos === 'gutentor-pf-pos-before-title' || $avatar_pos === 'g-avatar-b-title' ) {
                                    $output .= '<div class="gutentor-post-title-data-wrap">';
                                    if ( $enable_avatar && $avatar_pos === 'g-avatar-b-title' ) {
                                        $output .= $this->get_avatar_data( $post, $attributes );
                                    }
                                    if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {

                                        $output .= $this->edd_new_badge_product( $post, $download );
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
                            case 'wishlist':
                                $output .= $this->get_edd_wish_list( $post, $attributes );
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
                                if ( $post_format_pos === 'gutentor-pf-pos-before-ct-box' || $avatar_pos === 'g-avatar-b-content' ) {
                                    $output .= '<div class="gutentor-post-desc-data-wrap">';
                                    if ( $enable_avatar && $avatar_pos === 'g-avatar-b-content' ) {

                                        $output .= $this->get_avatar_data( $post, $attributes );
                                    }
                                    if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {

                                        $output .= $this->edd_new_badge_product( $post, $download );
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
                                if ( $post_format_pos === 'gutentor-pf-pos-before-button' || $avatar_pos === 'g-avatar-b-button' ) {
                                    $output .= '<div class="gutentor-post-button-data-wrap">';
                                    if ( $enable_avatar && $avatar_pos === 'g-avatar-b-button' ) {

                                        $output .= $this->get_avatar_data( $post, $attributes );
                                    }
                                    if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-button' ) {

                                        $output .= $this->edd_new_badge_product( $post, $download );
                                    }
                                    if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-button' ) {

                                        $output .= $this->get_post_module_badges_collection( $post, $attributes );
                                    }
                                    $output .= $this->get_edd_button( $post, $attributes );
                                    $output .= '</div>';
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
                $output .= '</div>';/*.gptm-item-height*/
            }
            return $output;

        }

        /**
         * add Template 5
         *
         * @param {string} $data
         * @param {array}  $post
         * @param {array}  $attributes
         * @return {mix}
         */
        public function gutentor_p1_template5( $data, $post, $attributes ) {

            if ( ! gutentor_is_edd_active() ) {
                return $data;
            }
            $output = '';
            $pImgDisplayType          = ( isset( $attributes['pImgDisplayType'] ) ) ? $attributes['pImgDisplayType'] : false;
            if ( $pImgDisplayType === 'normal-image' ) {
                $output .= $this->gutentor_p1_normal_image_template5( $data, $post, $attributes );
            }
            if ( $pImgDisplayType === 'bg-image' ) {
                $output .= $this->gutentor_p1_bg_image_template5( $data, $post, $attributes );
            }
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

			$output    = $data;
			$template  = ( isset( $attributes['p1Temp'] ) ) ? $attributes['p1Temp'] : '';
			$post_type = ( isset( $attributes['pPostType'] ) ) ? $attributes['pPostType'] : 'post';
			if ( $post_type !== 'download' ) {
				return $output;
			}
			if ( method_exists( $this, $template ) ) {
				$output = $this->$template( $data, $post, $attributes );
			} else {
				$output = $this->gutentor_p1_template1( $data, $post, $attributes );
			}
			return $output;
		}
	}
}
Gutentor_Normal_P1_EDD_Templates::get_instance()->run();
