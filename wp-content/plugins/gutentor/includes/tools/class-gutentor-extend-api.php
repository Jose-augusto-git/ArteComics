<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Gutentor_Extend_Api' ) ) {
	/**
	 * Gutentor_Woo
	 *
	 * @package Gutentor
	 * @since 2.1.9
	 */
	class Gutentor_Extend_Api {

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @since 2.1.9
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
		 * Initialize the class
		 */
		public function run() {
			add_filter( 'gutentor_rest_prepare_data_post', array( $this, 'add_post_comment_data' ), 10, 3 );
			add_filter( 'gutentor_rest_prepare_data_page', array( $this, 'add_post_comment_data' ), 10, 3 );
			add_filter( 'gutentor_rest_prepare_data_product', array( $this, 'add_product_data' ), 10, 3 );
			add_filter( 'gutentor_rest_prepare_data_download', array( $this, 'add_edd_download_data' ), 10, 3 );
			add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'alter_cart_link' ), 10, 3 );

		}

		/**
		 * Add Comment data
		 *
		 * @static
		 * @access public
		 * @since 2.1.9
		 * @return array
		 */
		public function add_post_comment_data( $data, $post, $request ) {

			$comments_count           = wp_count_comments( $post->ID );
			$data['gutentor_comment'] = $comments_count->total_comments;
			return $data;

		}

		/**
		 * Add new badge on product
		 *
		 * @static
		 * @access public
		 * @since 2.1.9
		 * @return string
		 */
		public function new_badge_product( $class, $post, $product ) {

			if ( ! $product ) {
				global $product;
			}
			$newness_days = 30;
			$created      = strtotime( $product->get_date_created() );
			if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
				return apply_filters( 'gutentor_woocommerce_new_badge', '<span class="' . $class . '">' . esc_html__( 'New!', 'gutentor' ) . '</span>', $post, $product );

			}
			return '';
		}

		/**
		 * Add new badge on download
		 *
		 * @static
		 * @access public
		 * @since 2.1.9
		 * @return string
		 */
		public function new_badge_download( $class, $post, $download ) {

			if ( ! $download ) {
				return '';
			}
			$newness_days = 30;
			$created      = strtotime( $download->post_date );
			if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
				return apply_filters( 'gutentor_edd_new_badge', '<span class="' . $class . '">' . esc_html__( 'New!', 'gutentor' ) . '</span>', $post, $download );

			}
			return '';
		}


		/**
		 * Add Review
		 *
		 * @static
		 * @access public
		 * @since 2.1.9
		 * @return string
		 */
		public function gutentor_edd_review( $id ) {
			$output = '';

			// make sure edd reviews is active
			if ( ! function_exists( 'edd_reviews' ) ) {
				return $output;
			}

			$edd_reviews = edd_reviews();
			if ( ! $edd_reviews ) {
				return $output;
			}
			// get the average rating for this download
			$average_rating = $edd_reviews->average_rating( false, $id );
			if ( ! $average_rating ) {
				return $output;
			}
			$rating = round( $average_rating * 2 ) / 2;
			if ( ! $rating ) {
				return $output;
			}

			$output .= '<div class="edd-review-meta-rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';
			for ( $i = 1; $i <= 5;  $i++ ) {
				if ( $i <= $rating ) {
					$output .= '<span class="dashicons dashicons-star-filled"></span>';
				} elseif ( $rating < $i && ( strpos( $rating, '.' ) !== false ) ) {
					$output .= '<span class="dashicons dashicons-star-half"></span>';
					$rating  = absint( $rating );

				} elseif ( $rating < $i ) {
					$output .= '<span class="dashicons dashicons-star-empty"></span>';
				}
			}
			$output         .= '<div style="display:none" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';
			$output         .= '<meta itemprop="worstRating" content="1" />';
			$output         .= '<span itemprop="ratingValue">' . esc_html( $rating ) . '</span>';
					$output .= '<span itemprop="bestRating">5</span>';
			$output         .= '</div>';
			$output         .= '</div>';
			return $output;
		}

		/**
		 * Add product data on gutentor rest data
		 *
		 * @static
		 * @access public
		 * @since 2.1.9
		 * @return array
		 */
		public function add_product_data( $data, $post, $request ) {

			if ( ! gutentor_is_woocommerce_active() ) {
				return $data;
			}
			$product              = wc_get_product( $post->ID );
			$rating               = $product->get_average_rating();
			$count                = $product->get_rating_count();
			$comments_count       = wp_count_comments( $post->ID );
			$author_id            = $post->post_author;
			$product_new_badge    = 'gutentor-wc-new';
			$product_fp_new_badge = 'gutentor-pf-wc-new';

			if ( $product->is_on_sale() ) {
				$data['product_sales_text'] = apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'gutentor' ) . '</span>', $post, $product );
			}
			$data['product_regular_price']   = $product->get_regular_price();
			$data['product_sale_price']      = wc_format_sale_price( wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) ), wc_get_price_to_display( $product ) ) . $product->get_price_suffix();
			$data['product_price']           = ( $product->get_price() ) ? $product->get_price_html() : 'price-empty';
			$data['product_price_empty']     = ( $product->get_price() ) ? 'price-not-empty' : wc_price( '0.00' );
			$data['product_cart_label']      = $product->add_to_cart_text();
			$data['product_rating_html']     = wc_get_rating_html( $rating, $count );
			$data['product_new_badge']       = $this->new_badge_product( $product_new_badge, $post, $product );
			$data['product_fp_new_badge']    = $this->new_badge_product( $product_fp_new_badge, $post, $product );
			$data['product_author_name']     = get_the_author_meta( 'display_name', $author_id );
			$data['product_author_url']      = get_the_author_meta( 'user_url', $author_id );
			$data['product_comment']         = $comments_count->total_comments;
			$data['product_placeholder_url'] = WC()->plugin_url() . '/assets/images/placeholder.png';

			return $data;

		}

		/**
		 * Add download data on gutentor rest data
		 *
		 * @static
		 * @access public
		 * @since 2.1.9
		 * @return array
		 */
		public function add_edd_download_data( $data, $post, $request ) {

			if ( ! gutentor_is_edd_active() ) {
				return $data;
			}
			$download              = edd_get_download( $post->ID );
			$comments_count        = wp_count_comments( $post->ID );
			$author_id             = $post->post_author;
			$download_new_badge    = 'gutentor-edd-new';
			$download_fp_new_badge = 'gutentor-fp-edd-new';

			// add download $options
			$download_args    = array(
				'download_id' => $post->ID,
				'class'       => 'g-edd-wl',
				'shortcode'   => true,
			);
			$fp_download_args = array(
				'download_id' => $post->ID,
				'class'       => 'gutentor-fp-edd-wish-list',
				'shortcode'   => true,
			);
			$output_favourite = $output_fp_favourite = $get_variable_pricing = '';
			if ( gutentor_is_edd_favorites_active() ) {
				ob_start();
				$output_favourite .= edd_favorites_load_link( $post->ID ) . ob_get_clean();
				ob_start();
				$output_fp_favourite .= edd_favorites_load_link( $post->ID ) . ob_get_clean();
			} elseif ( gutentor_is_edd_wishlist_active() ) {
				$output_favourite    .= edd_wl_wish_list_link( $download_args );
				$output_fp_favourite .= edd_wl_wish_list_link( $fp_download_args );
			}
			if ( edd_has_variable_prices( $post->ID ) ) {
				ob_start();
				$get_variable_pricing = edd_purchase_variable_pricing( $post->ID ) . ob_get_clean();
			}
			$data['download_variable_price_html'] = $get_variable_pricing;
			$data['download_has_variable_price']  = edd_has_variable_prices( $post->ID ) ? 'variable-price-true' : '';
			$data['download_cart_label']          = edd_get_option( 'add_to_cart_text', esc_html__( 'Purchase', 'gutentor' ) );
			$data['download_price']               = edd_has_variable_prices( $post->ID ) ? edd_price_range( $post->ID ) : edd_price( $post->ID, false );
			$data['download_price_is_empty']      = gutentor_is_edd_has_price( $post->ID );
			$data['download_rating_html']         = $this->gutentor_edd_review( $post->ID );
			$data['download_wish_list']           = $output_favourite;
			$data['download_fp_wish_list']        = $output_fp_favourite;
			$data['download_new_badge']           = $this->new_badge_download( $download_new_badge, $post, $download );
			$data['download_fp_new_badge']        = $this->new_badge_download( $download_fp_new_badge, $post, $download );
			$data['download_author_name']         = get_the_author_meta( 'display_name', $author_id );
			$data['download_author_url']          = get_the_author_meta( 'user_url', $author_id );
			$data['download_comment']             = $comments_count->total_comments;
			$data['download_placeholder_url']     = GUTENTOR_URL . 'assets/img/default-image.jpg';

			return $data;

		}

		/**
		 * Modify cart html if gutentor-attributes set
		 *
		 * @static
		 * @access public
		 * @since 2.1.9
		 * @return string
		 */
		public function alter_cart_link( $output, $product, $args ) {
			$attributes = isset( $args['gutentor-attributes'] ) ? $args['gutentor-attributes'] : false;
			$buttonType = isset( $args['gutentor-btn-type'] ) ? $args['gutentor-btn-type'] : false;
			if ( ! $attributes ) {
				return $output;
			}
			$icon = '';
			if ( $buttonType === 'featured' ) {
				$btnClass            = isset( $attributes['pFPBtnCName'] ) ? $attributes['pFPBtnCName'] : '';
				$default_class       = gutentor_concat_space( 'gutentor-button', 'gutentor-post-featured-button', $btnClass );
				$icon_options        = ( isset( $attributes['pFPBtnIconOpt'] ) ) ? $attributes['pFPBtnIconOpt'] : '';
				$icon_position_class = GutentorButtonOptionsClasses( $icon_options );
				if ( $icon_position_class == 'gutentor-icon-before' || $icon_position_class == 'gutentor-icon-after' ) {
					$icon = ( isset( $attributes['pFPBtnIcon'] ) && $attributes['pFPBtnIcon']['value'] ) ? '<i class="gutentor-button-icon ' . $attributes['pFPBtnIcon']['value'] . '" ></i>' : '';
				}
			} else {
				$btnClass            = isset( $attributes['pBtnCName'] ) ? $attributes['pBtnCName'] : '';
				$default_class       = gutentor_concat_space( 'gutentor-button', 'gutentor-post-button', $btnClass );
				$icon_options        = ( isset( $attributes['pBtnIconOpt'] ) ) ? $attributes['pBtnIconOpt'] : '';
				$icon_position_class = GutentorButtonOptionsClasses( $icon_options );
				if ( $icon_position_class == 'gutentor-icon-before' || $icon_position_class == 'gutentor-icon-after' ) {
					$icon = ( isset( $attributes['pBtnIcon'] ) && $attributes['pBtnIcon']['value'] ) ? '<i class="gutentor-button-icon ' . $attributes['pBtnIcon']['value'] . '" ></i>' : '';
				}
			}
			$woo_class = esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' );
			$output    = '<a href="' . esc_url( $product->add_to_cart_url() ) . '" data-quantity="' . esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ) . '" 
			class="' . gutentor_concat_space( $default_class, $woo_class, GutentorButtonOptionsClasses( $icon_options ) ) . '" ' . ( isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '' ) . '>
			' . $icon . '<span>' . esc_html( $product->add_to_cart_text() ) . '</span></a>';
			return $output;

		}


	}
}
Gutentor_Extend_Api::get_instance()->run();
