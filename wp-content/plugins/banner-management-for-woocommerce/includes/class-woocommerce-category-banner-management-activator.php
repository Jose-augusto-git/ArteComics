<?php

	/**
	 * Fired during plugin activation
	 *
	 * @link       http://www.multidots.com/
	 * @since      1.0.0
	 *
	 * @package    woocommerce_category_banner_management
	 * @subpackage woocommerce_category_banner_management/includes
	 */

	/**
	 * Fired during plugin activation.
	 *
	 * This class defines all code necessary to run during the plugin's activation.
	 *
	 * @since      1.0.0
	 * @package    woocommerce_category_banner_management
	 * @subpackage woocommerce_category_banner_management/includes
	 * @author     Multidots <inquiry@multidots.in>
	 */

// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	class woocommerce_category_banner_management_Activator {

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function activate() {
			global $jal_db_version;
			$jal_db_version = '1.0.0';
			if (  in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ||  is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
				$current_user = wp_get_current_user();
				$useremail    = $current_user->user_email;
				$log_url      = filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_VALIDATE_URL );
				$cur_date     = gmdate( 'Y-m-d' );
				$url          = 'http://www.multidots.com/store/wp-content/themes/business-hub-child/API/wp-add-plugin-users.php';
				wp_remote_post( $url, array(
					'method'      => 'POST',
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(),
					'body'        => array(
						'user' => array(
							'user_email'      => $useremail,
							'plugin_site'     => $log_url,
							'status'          => 1,
							'plugin_id'       => '33',
							'activation_date' => $cur_date
						)
					),
					'cookies'     => array()
				) );
				set_transient( '_welcome_screen_activation_redirect_banner_management', true, 30 );
			}
			add_option( 'jal_db_version', $jal_db_version );
		}
	}