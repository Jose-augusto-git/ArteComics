<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woo_Variation_Gallery_Migrate_Request', false ) ):

	if ( ! class_exists( 'WC_Background_Process', false ) ) {
		include_once WC()->plugin_path() . '/includes/abstracts/class-wc-background-process.php';
	}

	/**
	 * Class that extends WC_Background_Process to migrate variation images in the background.
	 */
	class Woo_Variation_Gallery_Migrate_Request extends WC_Background_Process {

		/**
		 * Stores the product ID being processed.
		 *
		 * @var integer
		 */
		protected $product_id = 0;

		/**
		 * Initiate new background process.
		 */
		public function __construct() {
			// Uses unique prefix per blog so each blog has separate queue.
			$this->prefix = 'wp_' . get_current_blog_id();
			$this->action = 'woo_variation_gallery_wc_migrate';
			parent::__construct();
		}

		/**
		 * Is job running?
		 *
		 * @return boolean
		 */
		public function is_running() {
			return $this->is_queue_empty();
		}

		/**
		 * Limit each task ran per batch to 1 for image regen.
		 *
		 * @return bool
		 */
		protected function batch_limit_exceeded() {
			return true;
		}

		protected function is_migrateable( $product ) {
			return true;
		}

		/**
		 * Code to execute for each item in the queue
		 *
		 * @param mixed $item Queue item to iterate over.
		 *
		 * @return bool
		 */
		protected function task( $item ) {

			if ( ! is_array( $item ) && ! isset( $item['variation_id'] ) ) {
				return false;
			}

			if ( ! $item['migrate_from'] ) {
				return false;
			}

			$this->product_id = absint( $item['variation_id'] );
			$product          = wc_get_product( $this->product_id );
			$migrate_from     = sanitize_text_field( $item['migrate_from'] );

			if ( ! $product ) {
				return false;
			}

			// $wc_gallery_images       = get_post_meta( $this->product_id, '_wc_additional_variation_images', true );
			// $wc_gallery_images_array = explode( ',', $wc_gallery_images );

			$wc_gallery_images_array = apply_filters( 'woo_variation_gallery_migrate_images', array(), $migrate_from, $this->product_id, $product );

			if ( empty( $wc_gallery_images_array ) ) {
				return false;
			}

			$log = wc_get_logger();
			$log->info( sprintf( esc_html__( 'Migration for variation product ID: %s. From: %s', 'woo-variation-gallery' ), $this->product_id, $migrate_from ), array( 'source' => 'woo-variation-gallery' ) );

			// Update the meta data
			update_post_meta( $this->product_id, 'woo_variation_gallery_images', array_values( array_filter( $wc_gallery_images_array ) ) );

			// We made it till the end, now lets remove the item from the queue.
			return false;
		}

		/**
		 * This runs once the job has completed all items on the queue.
		 *
		 * @return void
		 */
		protected function complete() {
			parent::complete();
			$log = wc_get_logger();
			$log->info( esc_html__( 'Migration completed of all variation product image', 'woo-variation-gallery' ), array( 'source' => 'woo-variation-gallery' ) );
		}
	}

endif;
	
