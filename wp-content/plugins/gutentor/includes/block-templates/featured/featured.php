<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Featured' ) ) {

	/**
	 * Gutentor_Featured Class For Gutentor
	 *
	 * @package Gutentor
	 * @since 2.0.5
	 */
	class Gutentor_Featured extends Gutentor_Query_Elements {

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid interface and improves performance.
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
			add_filter( 'gutentor_post_module_p2_query_data', array( $this, 'post_template' ), 999, 3 );
			add_filter( 'gutentor_term_module_t2_query_data', array( $this, 'term_template' ), 999, 3 );
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
			/*Load file once*/
			static $loaded = false;
			if ( ! $loaded ) {
				$loaded = true;
				/*Template 1*/
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-1/t-1.php';
				/*Template 2*/
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-2/t-1.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-2/t-2.php';
				/*Template 3*/
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-3/t-1.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-3/t-2.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-3/t-3.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-3/t-4.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-3/t-5.php';
				/*Template 4*/
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-4/t-1.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-4/t-2.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-4/t-3.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-4/t-4.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-4/t-5.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-4/t-6.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-4/t-7.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-4/t-8.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-4/t-9.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-4/t-10.php';
				/*Template 5*/
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-5/t-1.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-5/t-2.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-5/t-3.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-5/t-4.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-5/t-5.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-5/t-6.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-5/t-7.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-5/t-8.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-5/t-9.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-5/t-10.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-5/t-11.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-5/t-12.php';
				/*Template 6*/
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-6/t-1.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-6/t-2.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-6/t-3.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-6/t-4.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-6/t-5.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-6/t-6.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-6/t-7.php';
				require_once GUTENTOR_PATH . 'includes/block-templates/featured/n-6/t-8.php';
			}
		}

		/**
		 * Check if P2
		 *
		 * @param {array} output
		 *
		 * @return {boolean}
		 */
		public function isP2( $attributes ) {
			$block_name = ( isset( $attributes['gName'] ) ) ? $attributes['gName'] : '';
			if ( 'gutentor/p2' !== $block_name ) {
				return false;
			}
			$template = ( isset( $attributes['p2Temp'] ) ) ? $attributes['p2Temp'] : '';
			if ( $this->template !== $template ) {
				return false;
			}
			$number = ( isset( $attributes['postsToShow'] ) ) ? $attributes['postsToShow'] : '';
			if ( $this->number !== $number ) {
				return false;
			}
			return true;
		}

		/**
		 * Check if T2
		 *
		 * @param {array} output
		 *
		 * @return {boolean}
		 */
		public function isT2( $attributes ) {
			$block_name = ( isset( $attributes['gName'] ) ) ? $attributes['gName'] : '';
			if ( 'gutentor/t2' !== $block_name ) {
				return false;
			}
			$template = ( isset( $attributes['t2Temp'] ) ) ? $attributes['t2Temp'] : '';
			if ( $this->template !== $template ) {
				return false;
			}
			$number = ( isset( $attributes['t2Number'] ) ) ? $attributes['t2Number'] : '';
			if ( $this->number !== $number ) {
				return false;
			}
			return true;
		}

		/**
		 * Get Single block
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function p2_single_article( $post, $attributes, $index ) {
			$output              = '';
			$enable_post_format  = isset( $attributes['pOnPostFormatOpt'] ) && $attributes['pOnPostFormatOpt'];
			$post_format_pos     = ( isset( $attributes['pPostFormatPos'] ) ) ? $attributes['pPostFormatPos'] : false;
			$cat_pos             = ( isset( $attributes['pPostCatPos'] ) ) ? $attributes['pPostCatPos'] : false;
			$enable_featured_cat = isset( $attributes['pOnFeaturedCat'] ) && $attributes['pOnFeaturedCat'];
			$enable_avatar       = isset( $attributes['pOnAvatar'] ) && $attributes['pOnAvatar'];
			$avatar_pos          = ( isset( $attributes['pAvatarPos'] ) ) ? $attributes['pAvatarPos'] : false;
			$thumb_class         = has_post_thumbnail() ? '' : 'gutentor-post-no-thumb gtf-no-thumb';
			$output             .= "<article class='" . apply_filters( 'gutentor_post_module_article_class', gutentor_concat_space( 'gutentor-post gtf-item-wrap', $thumb_class, 'gutentor-post-item-' . $index, 'gtf-item-' . $index ), $attributes ) . "'>";
            $output             .= "<div class='" . apply_filters( 'gutentor_post_module_post_item', gutentor_concat_space( 'gutentor-post-item', 'gtf-item' ), $attributes ) . "'>";

            if ( has_post_thumbnail( $post->ID ) ) {
				$enable_overlayImage = false;
				$overlayImage        = ( isset( $attributes['pFImgOColor'] ) ) ? $attributes['pFImgOColor'] : false;
				if ( $overlayImage ) {
					$enable_overlayImage = ( isset( $attributes['pFImgOColor']['enable'] ) ) ? $attributes['pFImgOColor']['enable'] : false;
				}
				$url        = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $attributes['pFImgSize'] );
				$background = '';
				if ( isset( $url[0] ) ) {
					$background = 'style="background-image:url(' . esc_url( $url[0] ) . ')"';
				}
				$overlay = $enable_overlayImage ? 'gutentor-overlay g-overlay' : '';/*gutentor-overlay for bc*/
                $output .= "<div class='" . apply_filters( 'gutentor_post_module_post_item_height', gutentor_concat_space( 'gutentor-bg-image gtf-bg-image','gutentor-post-height gtf-item-height',  $overlay ), $attributes ) . "' " . $background . ">";

                if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
					$output .= $this->get_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
					$output .= $this->get_post_format_data( $post, $attributes );
				}
				if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
					$output .= $this->get_post_module_badges_collection( $post, $attributes );
				}
                $output .= apply_filters( 'gutentor_edit_post_module_featured_image_popup_data','', $post, $attributes );
                $output .= '<div class="gutentor-post-content gtf-content">';/*gutentor-post-content for bc*/
				$output .= $this->get_primary_meta( $post, $attributes );
				if ( $enable_avatar && $avatar_pos === 'g-avatar-b-title' ) {
					$output .= $this->get_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {

					$output .= $this->get_post_format_data( $post, $attributes );
				}
				if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-title' ) {

					$output .= $this->get_post_module_badges_collection( $post, $attributes );
				}
				$output .= $this->get_title( $post, $attributes );
				if ( $enable_avatar && $avatar_pos === 'g-avatar-b-content' ) {

					$output .= $this->get_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {

					$output .= $this->get_post_format_data( $post, $attributes );
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

					$output .= $this->get_post_format_data( $post, $attributes );
				}
				if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-button' ) {

					$output .= $this->get_post_module_badges_collection( $post, $attributes );
				}
				$output .= $this->get_button( $post, $attributes );
				$output .= '</div>';/*.gtf-content*/
                $output .= apply_filters( 'gutentor_post_type_featured_after_content','', $post, $attributes );
                $output .= '</div>';/*.gtf-bg-image*/
			} else {
                $output .= "<div class='" . apply_filters( 'gutentor_post_module_post_item_height', gutentor_concat_space( 'gutentor-post-height', 'gtf-item-height' ), $attributes ) . "'>";
				if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
					$output .= $this->get_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
					$output .= $this->get_post_format_data( $post, $attributes );
				}
				if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
					$output .= $this->get_post_module_badges_collection( $post, $attributes );
				}
                $output .= apply_filters( 'gutentor_edit_post_module_featured_image_popup_data','', $post, $attributes );
                $output .= '<div class="gutentor-post-content gtf-content">';/*gutentor-post-content for bc*/
				$output .= $this->get_primary_meta( $post, $attributes );
				if ( $enable_avatar && $avatar_pos === 'g-avatar-b-title' ) {
					$output .= $this->get_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {

					$output .= $this->get_post_format_data( $post, $attributes );
				}
				if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-title' ) {

					$output .= $this->get_post_module_badges_collection( $post, $attributes );
				}
				$output .= $this->get_title( $post, $attributes );
				if ( $enable_avatar && $avatar_pos === 'g-avatar-b-content' ) {

					$output .= $this->get_avatar_data( $post, $attributes );
				}
				if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {

					$output .= $this->get_post_format_data( $post, $attributes );
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

					$output .= $this->get_post_format_data( $post, $attributes );
				}
				if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-button' ) {

					$output .= $this->get_post_module_badges_collection( $post, $attributes );
				}
				$output .= $this->get_button( $post, $attributes );
				$output .= '</div>';/*.gtf-content*/
                $output .= apply_filters( 'gutentor_post_type_featured_after_content','', $post, $attributes );
				$output .= '</div>';/*.gtf-item-height*/
			}
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
		public function p2_woo_single_article( $post, $attributes, $index ) {

			$output = '';
			if ( ! gutentor_is_woocommerce_active() ) {
				return $output;
			}

			$product     = wc_get_product( $post->ID );
			$rating      = $product->get_average_rating();
			$count       = $product->get_rating_count();
			$rating_html = wc_get_rating_html( $rating, $count );

			$enable_post_format  = isset( $attributes['pOnPostFormatOpt'] ) && $attributes['pOnPostFormatOpt'];
			$post_format_pos     = ( isset( $attributes['pPostFormatPos'] ) ) ? $attributes['pPostFormatPos'] : false;
			$cat_pos             = ( isset( $attributes['pPostCatPos'] ) ) ? $attributes['pPostCatPos'] : false;
			$enable_featured_cat = isset( $attributes['pOnFeaturedCat'] ) && $attributes['pOnFeaturedCat'];
			$output             .= "<article class='" . apply_filters( 'gutentor_post_module_article_class', gutentor_concat_space( 'gutentor-post gtf-item-wrap', 'gutentor-post-item-' . $index, 'gtf-item-' . $index ), $attributes ) . "'>";
            $output             .= "<div class='" . apply_filters( 'gutentor_post_module_post_item', gutentor_concat_space( 'gutentor-post-item', 'gtf-item' ), $attributes ) . "'>";

            $enable_overlayImage = false;
			$overlayImage        = isset( $attributes['pFImgOColor'] ) && $attributes['pFImgOColor'];
			if ( $overlayImage ) {
				$enable_overlayImage = ( isset( $attributes['pFImgOColor']['enable'] ) ) ? $attributes['pFImgOColor']['enable'] : false;
			}
			$url         = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $attributes['pFImgSize'] );
			$default_url = WC()->plugin_url() . '/assets/images/placeholder.png';
			$overlay     = $enable_overlayImage ? 'gutentor-overlay g-overlay' : '';/*gutentor-overlay for bc*/
            $output .= "<div class='" . apply_filters( 'gutentor_post_module_post_item_height', gutentor_concat_space( 'gutentor-bg-image gtf-bg-image', 'gutentor-post-height gtf-item-height', $overlay ), $attributes ) . "' style='background-image:url(" . esc_url( is_array( $url ) && ! empty( $url ) ? $url[0] : $default_url ) . ")'>";
            if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
				$output .= $this->new_badge_product( $post, $product );
			}
			if ( $enable_featured_cat && $this->categories_on_image_condition( $cat_pos ) ) {
				$output .= $this->get_woo_badge( $post, $product, $attributes );
			}
            $output .= apply_filters( 'gutentor_edit_post_module_featured_image_popup_data','', $post, $attributes );
            $output .= '<div class="gutentor-post-content gtf-content">';/*gutentor-post-content for bc*/
			$output .= $this->get_primary_meta( $post, $attributes );
			if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {

				$output .= $this->new_badge_product( $post, $product );
			}
			if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-title' ) {

				$output .= $this->get_woo_badge( $post, $product, $attributes );

			}
			$output .= $this->get_title( $post, $attributes );
			$output .= $this->updated_wc_price( $post, $product, $attributes );
			if ( isset( $attributes['wooOnRating'] ) && $attributes['wooOnRating'] ) {
				if ( $rating_html ) {
					$output .= '<div class="gutentor-wc-rating">';
					$output .= $rating_html;
					$output .= '</div>';
				}
			}
			if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {

				$output .= $this->new_badge_product( $post, $product );
			}
			if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-ct-box' ) {

				$output .= $this->get_woo_badge( $post, $product, $attributes );

			}
			$output .= $this->get_description( $post, $attributes );
			$output .= $this->get_secondary_meta( $post, $attributes );
			if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-button' ) {

				$output .= $this->new_badge_product( $post, $product );
			}
			if ( $enable_featured_cat && $cat_pos === 'gutentor-cat-pos-before-button' ) {

				$output .= $this->get_woo_badge( $post, $product, $attributes );

			}
			if ( isset( $attributes['pOnBtn'] ) && $attributes['pOnBtn'] ) {
                $output .= "<div class='" . apply_filters( 'gutentor_post_module_product_button_class', 'gutentor-woo-add-to-cart wc-block-grid__product-add-to-cart',$post, $attributes ) . "'>";
                ob_start();
				woocommerce_template_loop_add_to_cart( array( 'gutentor-attributes' => $attributes ) );
				$output .= ob_get_clean();
				$output .= '</div>';
			}
			$output .= '</div>';/*.gtf-content*/
            $output .= apply_filters( 'gutentor_post_type_featured_after_content','', $post, $attributes );
			$output .= '</div>';/*.gtf-bg-image*/
			$output .= '</div>';/*.gutentor-post-item*/
			$output .= '</article>';/*.article*/
			return $output;
		}


		/**
		 * Get Edd Single block
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function p2_edd_single_article( $post, $attributes, $index ) {
			if ( ! gutentor_is_edd_active() ) {
				return '';
			}
			$download            = edd_get_download( $post->ID );
			$output              = '';
			$enable_avatar       = isset( $attributes['pOnAvatar'] ) && $attributes['pOnAvatar'];
			$avatar_pos          = ( isset( $attributes['pAvatarPos'] ) ) ? $attributes['pAvatarPos'] : false;
			$enable_post_format  = isset( $attributes['pOnPostFormatOpt'] ) && $attributes['pOnPostFormatOpt'];
			$post_format_pos     = ( isset( $attributes['pPostFormatPos'] ) ) ? $attributes['pPostFormatPos'] : false;
			$output             .= "<article class='" . apply_filters( 'gutentor_post_module_article_class', gutentor_concat_space( 'gutentor-post gtf-item-wrap', 'gutentor-post-item-' . $index, 'gtf-item-' . $index ), $attributes ) . "'>";
            $output             .= "<div class='" . apply_filters( 'gutentor_post_module_post_item', gutentor_concat_space( 'gutentor-post-item', 'gtf-item' ), $attributes ) . "'>";
            $enable_overlayImage = false;
			$overlayImage        = isset( $attributes['pFImgOColor'] ) && $attributes['pFImgOColor'];
			if ( $overlayImage ) {
				$enable_overlayImage = ( isset( $attributes['pFImgOColor']['enable'] ) ) ? $attributes['pFImgOColor']['enable'] : false;
			}
			$url         = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $attributes['pFImgSize'] );
			$default_url = GUTENTOR_URL . 'assets/img/default-image.jpg';
			$overlay     = $enable_overlayImage ? 'gutentor-overlay g-overlay' : '';/*gutentor-overlay for bc*/
            $output .= "<div class='" . apply_filters( 'gutentor_post_module_post_item_height', gutentor_concat_space( 'gutentor-bg-image gtf-bg-image', 'gutentor-post-height gtf-item-height', $overlay ), $attributes ) . "' style='background-image:url(" . esc_url( is_array( $url ) && ! empty( $url ) ? $url[0] : $default_url ) . ")'>";

            if ( $enable_avatar && $this->avatar_on_image_condition( $avatar_pos ) ) {
				$output .= $this->get_avatar_data( $post, $attributes );
			}
			if ( $enable_post_format && $this->post_format_on_image_condition( $post_format_pos ) ) {
				$output .= $this->edd_new_badge_product( $post, $download );

			}
            $output .= apply_filters( 'gutentor_edit_post_module_featured_image_popup_data','', $post, $attributes );
            $output .= '<div class="gutentor-post-content gtf-content">';/*gutentor-post-content for bc*/
			$output .= $this->get_primary_meta( $post, $attributes );
			if ( $enable_avatar && $avatar_pos === 'g-avatar-b-title' ) {
				$output .= $this->get_avatar_data( $post, $attributes );
			}
			if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-title' ) {
				$output .= $this->edd_new_badge_product( $post, $download );
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
			if ( $enable_avatar && $avatar_pos === 'g-avatar-b-content' ) {
				$output .= $this->get_avatar_data( $post, $attributes );
			}
			if ( $enable_post_format && $post_format_pos === 'gutentor-pf-pos-before-ct-box' ) {
				$output .= $this->edd_new_badge_product( $post, $download );
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
			$output .= $this->get_edd_button( $post, $attributes );
			$output .= '</div>';/*.gtf-content*/
            $output .= apply_filters( 'gutentor_post_type_featured_after_content','', $post, $attributes );
			$output .= '</div>';/*.gtf-bg-image*/
			$output .= '</div>';/*.gutentor-post-item*/
			$output .= '</article>';/*.article*/
			return $output;
		}

		/**
		 * Featured Post type template
		 *
		 * @param {string} $data
		 * @param {object} $post
		 * @param {array}  $attributes
		 * @return {mix}
		 *
		 * @return {boolean}
		 */
		public function featured_post_type_template( $post, $attributes, $index ) {
			$post_type = ( isset( $attributes['pPostType'] ) ) ? $attributes['pPostType'] : 'post';
			if ( 'product' === $post_type ) {
				return $this->p2_woo_single_article( $post, $attributes, $index );
			} elseif ( 'download' === $post_type ) {
				return $this->p2_edd_single_article( $post, $attributes, $index );

			} else {
				return $this->p2_single_article( $post, $attributes, $index );
			}
		}

		/**
		 * Get T2 Single item
		 *
		 * @param {string} $data
		 * @param {array}  $term
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function t2_single_article( $term, $attributes, $index ) {
			$output         = '';
			$no_thumb       = '';
			$bg_image       = '';
			$url            = '';
			$thumbnail_size = ( isset( $attributes['tFImgSize'] ) ) ? $attributes['tFImgSize'] : 'large';

			if ( $this->has_term_thumbnail( $term ) ) {
				$image_url = wp_get_attachment_image_src( $this->get_term_thumbnail_id( $term ), $thumbnail_size );
				if ( ! $image_url ) {
					$image_url[0] = GUTENTOR_URL . 'assets/img/default-image.jpg';
				}
				$url      = $image_url[0];
				$url      = 'style="background-image:url(' . esc_url( $url ) . ')"';
				$bg_image = 'gtf-bg-image';

			} else {
				$no_thumb = 'gtf-no-thumb';
			}

			$output             .= "<article class='" . apply_filters( 'gutentor_term_module_article_class', gutentor_concat_space( 'gtf-item-wrap', 'gtf-item-' . $index, $no_thumb ), $attributes ) . "'>";
			$output             .= '<div class="gtf-item">';
			$enable_overlayImage = false;
			$overlayImage        = isset( $attributes['tFImgOC'] ) && $attributes['tFImgOC'];
			if ( $overlayImage ) {
				$enable_overlayImage = ( isset( $attributes['tFImgOC']['enable'] ) ) ? $attributes['tFImgOC']['enable'] : false;
			}
			$overlay = $enable_overlayImage ? 'g-overlay' : '';
			$output .= '<div class="' . gutentor_concat_space( $bg_image, 'gtf-item-height', $overlay ) . '" ' . $url . '>';
			$output .= '<div class="gtf-content">';

			$output .= $this->get_term_title_and_count( $term, $attributes );

			$output .= $this->get_term_description( $term, $attributes );
			$output .= $this->get_term_button( $term, $attributes );
			$output .= '</div>';/*.g-term-content*/
			$output .= '</div>';/*.gtf-bg-image*/

			$output .= '</div>';/*.gutentor-term-item*/
			$output .= '</article>';/*.article*/
			return $output;
		}
	}
}

Gutentor_Featured::get_instance()->load_dependencies();
