<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Gutentor_Templateberg' ) ) {
	/**
	 * Advanced Import
	 *
	 * @package Gutentor
	 * @since 1.0.1
	 */
	class Gutentor_Templateberg extends WP_Rest_Controller {

		/**
		 * Rest route namespace.
		 *
		 * @var Gutentor_Templateberg
		 */
		public $namespace = 'gutentor-advanced-import/';

		/**
		 * Rest route version.
		 *
		 * @var Gutentor_Templateberg
		 */
		public $version = 'v1';

		/**
		 * Initialize the class
		 */
		public function run() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		/**
		 * Register REST API route
		 */
		public function register_routes() {
			$namespace = $this->namespace . $this->version;

			register_rest_route(
				$namespace,
				'/tb_sure_do_condition',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'tb_sure_do_condition' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
						'args'                => array(
							'reset' => array(
								'type'        => 'boolean',
								'required'    => false,
								'description' => __( 'Reset True or False', 'gutentor' ),
							),
						),

					),
				)
			);
		}

		/**
		 * Install Gutentor
		 * return void
		 */
		public function install_templateberg() {

			$slug   = 'templateberg';
			$plugin = 'templateberg/templateberg.php';

			$status = array(
				'install' => 'plugin',
				'slug'    => sanitize_key( wp_unslash( $slug ) ),
			);

			/*prevent gutentor to redirect*/
			update_option( '__templateberg_do_redirect', false );
			require_once ABSPATH . 'wp-admin/includes/file.php';
			include_once ABSPATH . 'wp-admin/includes/plugin.php';

			if ( is_plugin_active_for_network( $plugin ) || is_plugin_active( $plugin ) ) {
				// Plugin is activated
				return $status;
			}

			if ( ! current_user_can( 'install_plugins' ) ) {
				$status['errorMessage'] = __( 'Sorry, you are not allowed to install plugins on this site.', 'templateberg' );
				return $status;
			}

			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

			// Looks like a plugin is installed, but not active.
			if ( file_exists( WP_PLUGIN_DIR . '/' . $slug ) ) {
				$plugin_data          = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
				$status['plugin']     = $plugin;
				$status['pluginName'] = $plugin_data['Name'];

				if ( current_user_can( 'activate_plugin', $plugin ) && is_plugin_inactive( $plugin ) ) {
					$result = activate_plugin( $plugin );

					if ( is_wp_error( $result ) ) {
						$status['errorCode']    = $result->get_error_code();
						$status['errorMessage'] = $result->get_error_message();
						return $status;
					}
					return $status;
				}
			}

			$api = plugins_api(
				'plugin_information',
				array(
					'slug'   => sanitize_key( wp_unslash( $slug ) ),
					'fields' => array(
						'sections' => false,
					),
				)
			);

			if ( is_wp_error( $api ) ) {
				$status['errorMessage'] = $api->get_error_message();
				return $status;
			}

			$status['pluginName'] = $api->name;

			$skin     = new WP_Ajax_Upgrader_Skin();
			$upgrader = new Plugin_Upgrader( $skin );
			$result   = $upgrader->install( $api->download_link );

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				$status['debug'] = $skin->get_upgrade_messages();
			}

			if ( is_wp_error( $result ) ) {
				$status['errorCode']    = $result->get_error_code();
				$status['errorMessage'] = $result->get_error_message();
				return $status;
			} elseif ( is_wp_error( $skin->result ) ) {
				$status['errorCode']    = $skin->result->get_error_code();
				$status['errorMessage'] = $skin->result->get_error_message();
				return $status;
			} elseif ( $skin->get_errors()->get_error_code() ) {
				$status['errorMessage'] = $skin->get_error_messages();
				return $status;
			} elseif ( is_null( $result ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem();
				global $wp_filesystem;

				$status['errorCode']    = 'unable_to_connect_to_filesystem';
				$status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'templateberg' );

				// Pass through the error from WP_Filesystem if one was raised.
				if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
					$status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
				}
				return $status;
			}

			$install_status = install_plugin_install_status( $api );

			if ( current_user_can( 'activate_plugin', $install_status['file'] ) && is_plugin_inactive( $install_status['file'] ) ) {
				$result = activate_plugin( $install_status['file'] );

				if ( is_wp_error( $result ) ) {
					$status['errorCode']    = $result->get_error_code();
					$status['errorMessage'] = $result->get_error_message();
					return $status;
				}
			}
			return $status;
		}

		/**
		 * Function to get notification date
		 *
		 * @since 3.0.8
		 * @return string Date Time.
		 */
		public function get_notification_calendar() {
			return get_user_meta( get_current_user_id(), 'gutentor_templateberg_notice_calendar', true );
		}

		/**
		 * Check if we can show notification
		 *
		 * @since 3.0.8
		 * @return boolean condition
		 */
		public function can_show_notification() {
			if ( gutentor_is_templateberg_active() && gutentor_templateberg_has_account() && ! gutentor_setting_enable_template_library() ) {
				return false;
			}
			$icalendar = $this->get_notification_calendar();

			/**
			 * Return from notice display if:
			 * 5 days
			 * 1. If the user has ignored the message partially for 15 days.
			 */
			if ( $icalendar > strtotime( '-5 day' ) ) {
				return false;
			}
			return true;
		}

		/**
		 * Function to set Purchase info
		 *
		 * @since 1.0.0
		 * @param WP_REST_Request $request Full details about the request.
		 * @return bool|WP_REST_Response.
		 */
		public function tb_sure_do_condition( \WP_REST_Request $request ) {
			$output = false;
			if ( ! current_user_can( 'edit_posts' ) ) {
				return false;
			}
			if ( $request->get_param( 'condition' ) ) {
				$condition = sanitize_text_field( $request->get_param( 'condition' ) );
				switch ( $condition ) {
					case 'maybe':
						$output = update_user_meta( get_current_user_id(), 'gutentor_templateberg_notice_calendar', time() );
						break;

					case 'active':
						$output = $this->install_templateberg();
						break;

					case 'account':
						$output = admin_url( 'admin.php?page=templateberg' );
						break;

					case 'gutentor':
					case 'refresh':
						if ( gutentor_is_templateberg_active() && gutentor_templateberg_has_account() ) {
							$options                        = gutentor_get_options();
							$options['enable-import-block'] = false;
							update_option( 'gutentor_settings_options', $options );
						}
						$output = gutentor_get_options();
						break;
				}
			}
			return rest_ensure_response( $output );
		}

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @since 1.0.1
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
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'gutentor' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'gutentor' ), '1.0.0' );
		}
	}
}
/**
 * Begins execution of the hooks.
 *
 * @since    1.0.0
 */
function gutentor_templateberg() {
	return Gutentor_Templateberg::get_instance();
}

gutentor_templateberg()->run();
