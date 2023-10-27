<?php
defined( 'ABSPATH' ) or die( 'Keep Quit' );

if ( ! class_exists( 'Woo_Variation_Gallery_REST_API', false ) ):
	class Woo_Variation_Gallery_REST_API {

		protected static $_instance = null;

		protected function __construct() {
			$this->includes();
			$this->hooks();
			$this->init();
			do_action( 'woo_variation_gallery_rest_api_loaded', $this );
		}

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function includes() {

		}

		public function hooks() {

			add_action( 'rest_api_init', array( $this, 'register_woo_variation_gallery_images_field' ) );

			/*add_filter( 'woocommerce_rest_prepare_product_variation_object', array(
				$this,
				'rest_api_response'
			), 10, 2 );*/
		}

		public function init() {

		}

		// Start
		public function register_woo_variation_gallery_images_field() {

			register_rest_field( 'product_variation', 'woo_variation_gallery_images', array(
				'get_callback'    => array( $this, 'get_additional_response' ),
				'update_callback' => array( $this, 'update_additional_response' ),
				'schema'          => array( $this, 'additional_response_schema' ),
			) );
		}

		public function get_additional_response( $object, $field_name, $request ) {

			$product_id       = absint( $request->get_param( 'product_id' ) );
			$variation_id     = absint( $object['id'] );
			$variation_images = (array) get_post_meta( $variation_id, 'woo_variation_gallery_images', true );

			$data = array();

			foreach ( $variation_images as $attachment_id ) {

				$image_info = $this->rest_get_image_data( $attachment_id );
				if ( is_array( $image_info ) ) {
					$data[] = $image_info;
				}
			}

			return apply_filters( 'woo_variation_gallery_rest_product_variation_additional_response', $data, $variation_id, $product_id, $variation_images );
		}

		public function update_additional_response( $value, $object, $field_name ) {
			return null;
		}

		public function additional_response_schema() {
			return array(
				'description' => esc_html__( 'Additional Variation Images', 'woo-variation-gallery' ),
				'type'        => 'array',
			);
		}

		public function rest_get_image_data( $attachment_id ) {

			$attachment_post = get_post( $attachment_id );
			if ( is_null( $attachment_post ) ) {
				return false;
			}

			$attachment = wp_get_attachment_image_src( $attachment_id, 'full' );
			if ( ! is_array( $attachment ) ) {
				return false;
			}

			$video_url = sanitize_url( trim( get_post_meta( $attachment_id, 'woo_variation_gallery_media_video', true ) ) );

			$has_video = ! empty( $video_url );

			$attachment_data = array(
				'id'                => absint( $attachment_id ),
				'date_created'      => wc_rest_prepare_date_response( $attachment_post->post_date, false ),
				'date_created_gmt'  => wc_rest_prepare_date_response( strtotime( $attachment_post->post_date_gmt ) ),
				'date_modified'     => wc_rest_prepare_date_response( $attachment_post->post_modified, false ),
				'date_modified_gmt' => wc_rest_prepare_date_response( strtotime( $attachment_post->post_modified_gmt ) ),
				'src'               => current( $attachment ),
				'name'              => sanitize_text_field( get_the_title( $attachment_id ) ),
				'alt'               => sanitize_text_field( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ),
				'has_video'         => $has_video,
				'video_src'         => $has_video ? $video_url : '',
				'embed_url'         => $has_video ? woo_variation_gallery()->get_frontend()->get_embed_url( $video_url ) : ''
			);

			return apply_filters( 'woo_variation_gallery_images_rest_get_image', $attachment_data, $attachment_id );
		}

		// API GET Test
		// OLD WAY
		// curl https://example.com/wp-json/wc/v3/products/<product_id>/variations/<id> -u consumer_key:consumer_secret
		// @TODO: "woocommerce_rest_pre_insert_{$this->post_type}_object"
		public function rest_api_response( $response, $object ) {

			if ( empty( $response->data ) ) {
				return $response;
			}

			$variation_id = $object->get_id();
			$product_id   = $object->get_parent_id();

			$variation_images                               = (array) get_post_meta( $variation_id, 'woo_variation_gallery_images', true );
			$response->data['woo_variation_gallery_images'] = array();
			foreach ( $variation_images as $attachment_id ) {

				$image_info = $this->rest_get_image_data( $attachment_id );
				if ( is_array( $image_info ) ) {
					array_push( $response->data['woo_variation_gallery_images'], $image_info );
				}
			}

			return $response;
		}
	}
endif;