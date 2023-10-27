<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woo_Variation_Gallery_Migration', false ) ):

	class Woo_Variation_Gallery_Migration {
		/**
		 * Background process to regenerate all images
		 *
		 * @var Woo_Variation_Gallery_Migrate_Request
		 */
		protected static $background_process;

		public static function init() {

			// Not required when Jetpack Photon is in use.
			// class_exists( 'Jetpack' ) & method_exists( 'Jetpack', 'get_active_modules' ) & in_array( 'photon', Jetpack::get_active_modules() )
			if ( class_exists( 'Jetpack' ) && method_exists( 'Jetpack', 'is_module_active' ) && Jetpack::is_module_active( 'photon' ) ) {
				return;
			}

			if ( apply_filters( 'woo_variation_gallery_migrate', true ) ) {

				include_once dirname( __FILE__ ) . '/class-woo-variation-gallery-migrate-request.php';

				self::$background_process = new Woo_Variation_Gallery_Migrate_Request();

				add_action( 'admin_init', array( __CLASS__, 'migrate_notice' ) );

				// From WC_Admin_Notices::add_custom_notice( 'woo_variation_gallery_migrate')...
				// do_action( 'woocommerce_hide_' . $hide_notice . '_notice' );
				add_action( 'woocommerce_hide_woo_variation_gallery_migrate_notice', array(
					__CLASS__,
					'dismiss_notice'
				) );

			}
		}

		/**
		 * Dismiss notice and cancel jobs.
		 */
		public static function dismiss_notice() {
			if ( self::$background_process ) {
				self::$background_process->kill_process();

				$log = wc_get_logger();
				$log->info( esc_html__( 'Cancelled migration job.', 'woo-variation-gallery' ), array( 'source' => 'woo-variation-gallery' ) );
			}
			WC_Admin_Notices::remove_notice( 'woo_variation_gallery_migrate' );
		}

		public static function notice_markup() {
			ob_start();
			?>
			<div class="updated woocommerce-message">
			<a class="woocommerce-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wc-hide-notice', 'woo_variation_gallery_migrate' ), 'woocommerce_hide_notices_nonce', '_wc_notice_nonce' ) ); ?>"><?php
				esc_html_e( 'Cancel migration', 'woo-variation-gallery' ); ?></a>
			<p><?php
				esc_html_e( 'Variation Gallery Migration is running in the background. Depending on the amount of variation product in your store this may take a while.', 'woo-variation-gallery' ); ?></p>
			</div><?php
			return ob_get_clean();
		}

		/**
		 * Show notice when job is running in background.
		 */
		public static function migrate_notice() {
			if ( ! self::$background_process->is_running() ) {
				// WC_Admin_Notices::add_custom_notice( 'woo_variation_gallery_migrate', self::notice_markup() );
				WC_Admin_Notices::add_custom_notice( 'woo_variation_gallery_migrate', esc_html__( 'Variation Gallery Migration is running in the background. Depending on the amount of variation product in your store this may take a while.', 'woo-variation-gallery' ) );
			} else {
				WC_Admin_Notices::remove_notice( 'woo_variation_gallery_migrate' );
			}
		}

		/**
		 * Get list of variation product and queue them for migrate
		 *
		 * @param string $migrate_from
		 *
		 * @return void
		 */

		public static function queue_migration( $migrate_from = false ) {
			global $wpdb;
			// First lets cancel existing running queue to avoid running it more than once.
			self::$background_process->kill_process();

			// Now lets find all product image attachments IDs and pop them onto the queue.
			$variations = $wpdb->get_results( // @codingStandardsIgnoreLine
				"SELECT ID
			FROM $wpdb->posts
			WHERE post_type = 'product_variation'
			ORDER BY ID DESC" );
			foreach ( $variations as $variation ) {
				self::$background_process->push_to_queue( array(
					'variation_id' => absint( $variation->ID ),
					'migrate_from' => sanitize_text_field( $migrate_from ),
				) );
			}

			// Lets dispatch the queue to start processing.
			self::$background_process->save()->dispatch();
		}
	}

endif;
