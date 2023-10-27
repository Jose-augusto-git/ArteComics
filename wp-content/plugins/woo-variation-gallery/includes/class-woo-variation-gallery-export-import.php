<?php

defined( 'ABSPATH' ) or die( 'Keep Quit' );

if ( ! class_exists( 'Woo_Variation_Gallery_Export_Import', false ) ):

	class Woo_Variation_Gallery_Export_Import {

		private $export_type = 'product';
		private $column_id = 'woo_variation_gallery_images';

		protected static $_instance = null;

		protected function __construct() {
			$this->includes();
			$this->hooks();
			$this->init();
			do_action( 'woo_variation_gallery_export_import_loaded', $this );
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
			// EXPORT
			// "woocommerce_{$this->export_type}_export_column_names"
			// add_filter( 'woocommerce_product_export_column_names', 'add_woo_variation_gallery_export_column' );

			add_filter( "woocommerce_product_export_{$this->export_type}_default_columns", array(
				$this,
				'export_column_name'
			) );
			add_filter( "woocommerce_product_export_{$this->export_type}_column_{$this->column_id}", array(
				$this,
				'export_column_data'
			), 10, 3 );

			// IMPORT
			add_filter( 'woocommerce_csv_product_import_mapping_options', array( $this, 'import_column_name' ) );
			add_filter( 'woocommerce_csv_product_import_mapping_default_columns', array(
				$this,
				'default_import_column_name'
			) );
			add_action( 'woocommerce_product_import_inserted_product_object', array(
				$this,
				'process_wc_import'
			), 10, 2 );
		}

		public function init() {

		}


		// Export
		public function export_column_name( $columns ) {

			// column slug => column name
			$columns[ $this->column_id ] = esc_html__( 'Woo Variation Gallery Images', 'woo-variation-gallery' );

			return $columns;
		}

		public function export_column_data( $value, $product, $column_id ) {
			$product_id     = $product->get_id();
			$gallery_images = get_post_meta( $product_id, 'woo_variation_gallery_images', true );

			if ( empty( $gallery_images ) ) {
				return '';
			}

			if ( is_array( $gallery_images ) ) {
				$gallery_images = (array) apply_filters( 'woo_variation_gallery_raw_exported_images', $gallery_images, $product );
				$gallery_images = array_values( array_filter( $gallery_images ) );
			}

			$images = array();

			foreach ( $gallery_images as $image_id ) {
				$image = wp_get_attachment_image_src( $image_id, 'full' );

				if ( $image ) {
					$images[] = $image[0];
				}
			}

			$images = apply_filters( 'woo_variation_gallery_exported_images', $images, $product_id );

			return implode( ',', array_values( array_filter( $images ) ) );
		}

		// Import
		public function import_column_name( $columns ) {
			// column slug => column name
			$columns[ $this->column_id ] = esc_html__( 'Woo Variation Gallery Images', 'woo-variation-gallery' );

			return $columns;
		}

		public function default_import_column_name( $columns ) {
			// potential column name => column slug
			$columns[ esc_html__( 'Woo Variation Gallery Images', 'woo-variation-gallery' ) ] = $this->column_id;

			return $columns;
		}

		public function process_wc_import( $product, $data ) {

			$product_id = $product->get_id();

			if ( isset( $data[ $this->column_id ] ) && ! empty( $data[ $this->column_id ] ) ) {


				$woo_variation_images = array();
				$raw_gallery_images   = (array) explode( ',', $data[ $this->column_id ] );
				$raw_gallery_images   = array_values( array_filter( $raw_gallery_images ) );

				$raw_gallery_images = (array) apply_filters( 'woo_variation_gallery_raw_imported_images', $raw_gallery_images, $product_id, $data, $this->column_id );

				foreach ( $raw_gallery_images as $url ) {
					$woo_variation_images[] = $this->get_attachment_id_from_url( trim( $url ), $product_id );
				}

				$woo_variation_images = apply_filters( 'woo_variation_gallery_imported_images', $woo_variation_images, $raw_gallery_images, $product_id );

				update_post_meta( $product_id, 'woo_variation_gallery_images', array_values( array_filter( $woo_variation_images ) ) );
			}
		}

		public function get_attachment_id_from_url( $url, $product_id ) {
			if ( empty( $url ) ) {
				return 0;
			}

			$id         = 0;
			$upload_dir = wp_upload_dir( null, false );
			$base_url   = $upload_dir['baseurl'] . '/';

			// Check first if attachment is inside the WordPress uploads directory, or we're given a filename only.
			if ( false !== strpos( $url, $base_url ) || false === strpos( $url, '://' ) ) {
				// Search for yyyy/mm/slug.extension or slug.extension - remove the base URL.
				$file = str_replace( $base_url, '', $url );
				$args = array(
					'post_type'   => 'attachment',
					'post_status' => 'any',
					'fields'      => 'ids',
					'meta_query'  => array( // @codingStandardsIgnoreLine.
						'relation' => 'OR',
						array(
							'key'     => '_wp_attached_file',
							'value'   => '^' . $file,
							'compare' => 'REGEXP',
						),
						array(
							'key'     => '_wp_attached_file',
							'value'   => '/' . $file,
							'compare' => 'LIKE',
						),
						array(
							'key'     => '_wc_attachment_source',
							'value'   => '/' . $file,
							'compare' => 'LIKE',
						),
					),
				);
			} else {
				// This is an external URL, so compare to source.
				$args = array(
					'post_type'   => 'attachment',
					'post_status' => 'any',
					'fields'      => 'ids',
					'meta_query'  => array( // @codingStandardsIgnoreLine.
						array(
							'value' => $url,
							'key'   => '_wc_attachment_source',
						),
					),
				);
			}

			$ids = get_posts( $args ); // @codingStandardsIgnoreLine.

			if ( $ids ) {
				$id = current( $ids );
			}

			// Upload if attachment does not exists.
			if ( ! $id && stristr( $url, '://' ) ) {
				$upload = wc_rest_upload_image_from_url( $url );

				if ( is_wp_error( $upload ) ) {
					throw new Exception( $upload->get_error_message(), 400 );
				}

				$id = wc_rest_set_uploaded_image_as_attachment( $upload, $product_id );

				if ( ! wp_attachment_is_image( $id ) ) {
					/* translators: %s: image URL */
					throw new Exception( sprintf( __( 'Not able to attach "%s".', 'woocommerce' ), $url ), 400 );
				}

				// Save attachment source for future reference.
				update_post_meta( $id, '_wc_attachment_source', $url );
			}

			if ( ! $id ) {
				/* translators: %s: image URL */
				throw new Exception( sprintf( __( 'Unable to use image "%s".', 'woocommerce' ), $url ), 400 );
			}

			return $id;
		}
	}

endif;