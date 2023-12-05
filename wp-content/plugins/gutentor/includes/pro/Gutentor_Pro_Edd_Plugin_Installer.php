<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Allows plugins to use their own update API.
 *
 * @author Easy Digital Downloads
 * @version 1.9.1
 */
class Gutentor_Pro_Edd_Plugin_Installer {


	private $api_url         = '';
	private $api_data        = array();
	private $plugin_file     = '';
	private $plugin_dir_file = '';
	private $name            = '';
	public $slug             = '';
	private $version         = '';
	private $wp_override     = false;
	private $beta            = false;
	private $failed_request_cache_key;


	/**
	 * Gets an instance of this object.
	 * Prevents duplicate instances which avoid artefacts and improves performance.
	 *
	 * @static
	 * @access public
	 * @since 3.2.1
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
	 * Class constructor.
	 *
	 * @uses plugin_basename()
	 * @uses hook()
	 *
	 * @param string $_api_url     The URL pointing to the custom API endpoint.
	 * @param string $_plugin_file Path to the plugin file.
	 * @param array  $_api_data    Optional data to send with API calls.
	 */
	public function run( $_api_url, $_plugin_file, $_api_data = null ) {

		global $edd_plugin_data;

		$this->api_url                  = trailingslashit( $_api_url );
		$this->api_data                 = $_api_data;
		$this->plugin_file              = $_plugin_file;
		$this->plugin_dir_file          = str_replace( WP_PLUGIN_DIR . '/', '', $_plugin_file );
		$this->name                     = plugin_basename( $_plugin_file );
		$this->slug                     = basename( $_plugin_file, '.php' );
		$this->version                  = $_api_data['version'];
		$this->wp_override              = isset( $_api_data['wp_override'] ) ? (bool) $_api_data['wp_override'] : false;
		$this->beta                     = ! empty( $this->api_data['beta'] ) ? true : false;
		$this->failed_request_cache_key = 'edd_sl_failed_http_' . md5( $this->api_url );

		$edd_plugin_data[ $this->slug ] = $this->api_data;

		/**
		 * Fires after the $edd_plugin_data is setup.
		 *
		 * @since x.x.x
		 *
		 * @param array $edd_plugin_data Array of EDD SL plugin data.
		 */
		do_action( 'post_edd_sl_plugin_updater_setup', $edd_plugin_data );

		// Set up hooks.
		$this->init();
	}

	/**
	 * Set up WordPress filters to hook into WP's update process.
	 *
	 * @uses add_filter()
	 *
	 * @return void
	 */
	public function init() {

		add_filter( 'plugins_api', array( $this, 'plugins_api_filter' ), 10, 3 );
	}

	/**
	 * Show the update notification on multisite subsites.
	 *
	 * @param string $file
	 * @param array  $plugin
	 */
	public function get_install_link() {

		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( $this->plugin_dir_file ) ) {
			return false;
		}

		if ( current_user_can( 'activate_plugins' ) ) {
			if ( file_exists( $this->plugin_file ) ) {
				$url    = wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'activate',
							'plugin' => $this->plugin_dir_file,
							'from'   => $this->slug,
						),
						admin_url( 'plugins.php' )
					),
					'activate-plugin_' . $this->plugin_dir_file
				);
				$action = sprintf(
					'<a href="%1$s" aria-label="%2$s">%2$s</a>',
					esc_url( $url ),
					__( 'Activate' ) . ' ' . $this->api_data['item_name']
				);
			} else {
				if ( is_main_site() ) {
					$url    = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => $this->slug,
								'from'   => $this->slug,
							),
							self_admin_url( 'update.php' )
						),
						'install-plugin_' . $this->slug
					);
					$action = sprintf(
						'<a href="%1$s" class="install-now" data-slug="%2$s" data-name="%2$s" aria-label="%3$s">%3$s</a>',
						esc_url( $url ),
						esc_attr( $this->slug ),
						__( 'Install Now' )
					);
				} else {
					$action = sprintf(
					/* translators: %s: URL to Press This bookmarklet on the main site. */
						__( '%1$s is not installed. Please install from <a href="%2$s">the main site</a>.' ),
						$this->api_data['item_name'],
						get_admin_url( get_current_network_id(), $this->slug . '.php' )
					);
				}
			}
		} else {
			$action = sprintf(
			/* translators: %s: URL to Press This bookmarklet on the main site. */
				__( '%1$s is not available. Please contact your site administrator.' ),
				$this->api_data['item_name']
			);
		}
		return $action;
	}

	/**
	 * Updates information on the "View version x.x details" page with custom data.
	 *
	 * @uses api_request()
	 *
	 * @param mixed  $_data
	 * @param string $_action
	 * @param object $_args
	 * @return object $_data
	 */
	public function plugins_api_filter( $_data, $_action = '', $_args = null ) {

		if ( 'plugin_information' !== $_action ) {
			return $_data;
		}

		if ( ! isset( $_args->slug ) || ( $_args->slug !== $this->slug ) ) {
			return $_data;
		}

		$to_send = array(
			'slug'   => $this->slug,
			'is_ssl' => is_ssl(),
			'fields' => array(
				'banners' => array(),
				'reviews' => false,
				'icons'   => array(),
			),
		);

		// Get the transient where we store the api request for this plugin for 24 hours
		$edd_api_request_transient = $this->get_cached_version_info();

		// If we have no transient-saved value, run the API, set a fresh transient with the API value, and return that value too right now.
		if ( empty( $edd_api_request_transient ) ) {
			$api_response = $this->api_request( 'plugin_information', $to_send );

			// Expires in 3 hours
			$this->set_version_info_cache( $api_response );

			if ( false !== $api_response ) {
				$_data = $api_response;
			}
		} else {
			$_data = $edd_api_request_transient;
		}

		// Convert sections into an associative array, since we're getting an object, but Core expects an array.
		if ( isset( $_data->sections ) && ! is_array( $_data->sections ) ) {
			$_data->sections = $this->convert_object_to_array( $_data->sections );
		}

		// Convert banners into an associative array, since we're getting an object, but Core expects an array.
		if ( isset( $_data->banners ) && ! is_array( $_data->banners ) ) {
			$_data->banners = $this->convert_object_to_array( $_data->banners );
		}

		// Convert icons into an associative array, since we're getting an object, but Core expects an array.
		if ( isset( $_data->icons ) && ! is_array( $_data->icons ) ) {
			$_data->icons = $this->convert_object_to_array( $_data->icons );
		}

		// Convert contributors into an associative array, since we're getting an object, but Core expects an array.
		if ( isset( $_data->contributors ) && ! is_array( $_data->contributors ) ) {
			$_data->contributors = $this->convert_object_to_array( $_data->contributors );
		}

		if ( ! isset( $_data->plugin ) ) {
			$_data->plugin = $this->name;
		}

		if ( ! isset( $_data->version ) ) {
			if ( isset( $_data->new_version ) ) {
				$_data->version = $_data->new_version;
			} elseif ( isset( $_data->stable_version ) ) {
				$_data->version = $_data->stable_version;
			} else {
				$_data->version = '0.0.0.1';
			}
		}

		return $_data;
	}

	/**
	 * Convert some objects to arrays when injecting data into the update API
	 *
	 * Some data like sections, banners, and icons are expected to be an associative array, however due to the JSON
	 * decoding, they are objects. This method allows us to pass in the object and return an associative array.
	 *
	 * @since 3.6.5
	 *
	 * @param stdClass $data
	 *
	 * @return array
	 */
	private function convert_object_to_array( $data ) {
		if ( ! is_array( $data ) && ! is_object( $data ) ) {
			return array();
		}
		$new_data = array();
		foreach ( $data as $key => $value ) {
			$new_data[ $key ] = is_object( $value ) ? $this->convert_object_to_array( $value ) : $value;
		}

		return $new_data;
	}

	/**
	 * Calls the API and, if successfull, returns the object delivered by the API.
	 *
	 * @uses get_bloginfo()
	 * @uses wp_remote_post()
	 * @uses is_wp_error()
	 *
	 * @param string $_action The requested action.
	 * @param array  $_data   Parameters for the API action.
	 * @return false|object|void
	 */
	private function api_request( $_action, $_data ) {
		$data = array_merge( $this->api_data, $_data );

		if ( $data['slug'] !== $this->slug ) {
			return;
		}

		// Don't allow a plugin to ping itself
		if ( trailingslashit( home_url() ) === $this->api_url ) {
			return false;
		}

		if ( $this->request_recently_failed() ) {
			return false;
		}

		return $this->get_version_from_remote();
	}

	/**
	 * Determines if a request has recently failed.
	 *
	 * @since 1.9.1
	 *
	 * @return bool
	 */
	private function request_recently_failed() {
		$failed_request_details = get_option( $this->failed_request_cache_key );

		// Request has never failed.
		if ( empty( $failed_request_details ) || ! is_numeric( $failed_request_details ) ) {
			return false;
		}

		/*
		 * Request previously failed, but the timeout has expired.
		 * This means we're allowed to try again.
		 */
		if ( time() > $failed_request_details ) {
			delete_option( $this->failed_request_cache_key );

			return false;
		}

		return true;
	}

	/**
	 * Logs a failed HTTP request for this API URL.
	 * We set a timestamp for 1 hour from now. This prevents future API requests from being
	 * made to this domain for 1 hour. Once the timestamp is in the past, API requests
	 * will be allowed again. This way if the site is down for some reason we don't bombard
	 * it with failed API requests.
	 *
	 * @see Gutentor_Pro_Edd_Plugin_Installer::request_recently_failed
	 *
	 * @since 1.9.1
	 */
	private function log_failed_request() {
		update_option( $this->failed_request_cache_key, strtotime( '+1 hour' ) );
	}

	/**
	 * Gets the current version information from the remote site.
	 *
	 * @return array|false
	 */
	private function get_version_from_remote() {
		$api_params = array(
			'edd_action'  => 'get_version',
			'license'     => ! empty( $this->api_data['license'] ) ? $this->api_data['license'] : '',
			'item_name'   => isset( $this->api_data['item_name'] ) ? $this->api_data['item_name'] : false,
			'item_id'     => isset( $this->api_data['item_id'] ) ? $this->api_data['item_id'] : false,
			'version'     => isset( $this->api_data['version'] ) ? $this->api_data['version'] : false,
			'slug'        => $this->slug,
			'author'      => $this->api_data['author'],
			'url'         => home_url(),
			'beta'        => $this->beta,
			'php_version' => phpversion(),
			'wp_version'  => get_bloginfo( 'version' ),
		);

		/**
		 * Filters the parameters sent in the API request.
		 *
		 * @param array  $api_params        The array of data sent in the request.
		 * @param array  $this->api_data    The array of data set up in the class constructor.
		 * @param string $this->plugin_file The full path and filename of the file.
		 */
		$api_params = apply_filters( 'edd_sl_plugin_updater_api_params', $api_params, $this->api_data, $this->plugin_file );

		$request = wp_remote_post(
			$this->api_url,
			array(
				'timeout'   => 15,
				'sslverify' => $this->verify_ssl(),
				'body'      => $api_params,
			)
		);

		if ( is_wp_error( $request ) || ( 200 !== wp_remote_retrieve_response_code( $request ) ) ) {
			$this->log_failed_request();

			return false;
		}

		$request = json_decode( wp_remote_retrieve_body( $request ) );

		if ( $request && isset( $request->sections ) ) {
			$request->sections = maybe_unserialize( $request->sections );
		} else {
			$request = false;
		}

		if ( $request && isset( $request->banners ) ) {
			$request->banners = maybe_unserialize( $request->banners );
		}

		if ( $request && isset( $request->icons ) ) {
			$request->icons = maybe_unserialize( $request->icons );
		}

		if ( ! empty( $request->sections ) ) {
			foreach ( $request->sections as $key => $section ) {
				$request->$key = (array) $section;
			}
		}

		return $request;
	}

	/**
	 * Get the version info from the cache, if it exists.
	 *
	 * @param string $cache_key
	 * @return object
	 */
	public function get_cached_version_info( $cache_key = '' ) {

		if ( empty( $cache_key ) ) {
			$cache_key = $this->get_cache_key();
		}

		$cache = get_option( $cache_key );

		// Cache is expired
		if ( empty( $cache['timeout'] ) || time() > $cache['timeout'] ) {
			return false;
		}

		// We need to turn the icons into an array, thanks to WP Core forcing these into an object at some point.
		$cache['value'] = json_decode( $cache['value'] );
		if ( ! empty( $cache['value']->icons ) ) {
			$cache['value']->icons = (array) $cache['value']->icons;
		}

		return $cache['value'];
	}

	/**
	 * Adds the plugin version information to the database.
	 *
	 * @param string $value
	 * @param string $cache_key
	 */
	public function set_version_info_cache( $value = '', $cache_key = '' ) {

		if ( empty( $cache_key ) ) {
			$cache_key = $this->get_cache_key();
		}

		$data = array(
			'timeout' => strtotime( '+3 hours', time() ),
			'value'   => wp_json_encode( $value ),
		);

		update_option( $cache_key, $data, 'no' );

		// Delete the duplicate option
		delete_option( 'edd_api_request_' . md5( serialize( $this->slug . $this->api_data['license'] . $this->beta ) ) );
	}

	/**
	 * Returns if the SSL of the store should be verified.
	 *
	 * @since  1.6.13
	 * @return bool
	 */
	private function verify_ssl() {
		return (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true, $this );
	}

	/**
	 * Gets the unique key (option name) for a plugin.
	 *
	 * @since 1.9.0
	 * @return string
	 */
	private function get_cache_key() {
		$string = $this->slug . $this->api_data['license'] . $this->beta;

		return 'edd_sl_' . md5( serialize( $string ) );
	}

	/**
	 * Activates the license key.
	 *
	 * @return array
	 */
	function activate_license() {
		// retrieve the license from the database
		$license = $this->api_data['license'];

		// data to send in our API request
		$api_params = array(
			'edd_action'  => 'activate_license',
			'license'     => $license,
			'item_id'     => $this->api_data['item_id'],
			'item_name'   => $this->api_data['item_name'],
			'url'         => home_url(),
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);

		// Call the custom API.
		$response = wp_remote_post(
			$this->api_url,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( !$license_data->success ) {
				switch ( $license_data->error ) {
					case 'expired':
						$message = sprintf(
						/* translators: the license key expiration date */
							__( 'Your license key expired on %s.', 'gutentor-pro' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'disabled':
					case 'revoked':
						$message = __( 'Your license key has been disabled.', 'gutentor-pro' );
						break;

					case 'missing':
						$message = __( 'Invalid license.', 'gutentor-pro' );
						break;

					case 'invalid':
					case 'site_inactive':
						$message = __( 'Your license is not active for this URL.', 'gutentor-pro' );
						break;

					case 'item_name_mismatch':
						/* translators: the plugin name */
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'gutentor-pro' ), GUTENTOR_PRO_ITEM_NAME );
						break;

					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'gutentor-pro' );
						break;

					default:
						$message = __( 'An error occurred, please try again.', 'gutentor-pro' );
						break;
				}
			}
		}

		// $license_data->license will be either "valid" or "invalid"
		if ( 'valid' === $license_data->license ) {
			update_option( $this->slug . '_license_key', $license );

			$license_status = array(
				'is_activated' => true,
				'msg'          => __( 'License is valid.', 'gutentor-pro' ),
			);
		} else {
			$license_status = array(
				'is_activated' => false,
				'msg'          => $message,
			);
		}
		update_option( $this->slug . '_license_status', $license_data->license );

		return $license_status;
	}


	/**
	 * Checks if a license key is still valid.
	 * The updater does this for you, so this is only needed if you want
	 * to do somemthing custom.
	 *
	 * @return boolean||void
	 */
	function check_license() {
		$license = trim( get_option( $this->slug . '_license_key' ) );

		if ( ! $license ) {
			return false;
		}

		// data to send in our API request
		$api_params = array(
			'edd_action'  => 'check_license',
			'license'     => $license,
			'item_id'     => $this->api_data['item_id'],
			'item_name'   => $this->api_data['item_name'],
			'url'         => home_url(),
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);

		// Call the custom API.
		$response = wp_remote_post(
			$this->api_url,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		return json_decode( wp_remote_retrieve_body( $response ) );
	}
}


/**
 * Begins execution of the hooks.
 *
 * @since    1.0.0
 */
function gutentor_pro_edd_plugin_installer() {
	return Gutentor_Pro_Edd_Plugin_Installer::get_instance();
}

