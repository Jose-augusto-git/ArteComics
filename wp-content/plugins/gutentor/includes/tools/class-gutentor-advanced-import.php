<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Gutentor_Advanced_Import' ) ) {
	/**
	 * Advanced Import
	 *
	 * @package Gutentor
	 * @since 1.0.1
	 */
	class Gutentor_Advanced_Import extends WP_Rest_Controller {

		/**
		 * Rest route namespace.
		 *
		 * @var Gutentor_Advanced_Import
		 */
		public $namespace = 'gutentor-advanced-import/';

		/**
		 * Rest route version.
		 *
		 * @var Gutentor_Advanced_Import
		 */
		public $version = 'v1';

		/**
		 * Initialize the class
		 */
		public function run() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
			add_action( 'gutentor_get_template_library', array( $this, 'add_dynamic_library' ) );
		}

		/**
		 * Register REST API route
		 */
		public function register_routes() {
			$namespace = $this->namespace . $this->version;

			register_rest_route(
				$namespace,
				'/fetch_templates',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'fetch_templates' ),
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

			register_rest_route(
				$namespace,
				'/import_template',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'import_template' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
						'args'                => array(
							'url' => array(
								'type'        => 'string',
								'required'    => true,
								'description' => __( 'URL of the JSON file.', 'gutentor' ),
							),
						),
					),
				)
			);

		}

		/**
		 * Function to delete templates and bock json transient
		 *
		 * @since 2.0.9
		 * @return void
		 */
		public function delete_transient() {
			/*Delete Template Library Transient*/
			delete_transient( 'gutentor_get_template_library' );

			/*Delete Block Json Transient*/
			global $wpdb;
			$transients = $wpdb->get_col( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient_gutentor_get_block_json_%'" );

			if ( $transients ) {
				foreach ( $transients as $transient ) {
					$transient = preg_replace( '/^_transient_/i', '', $transient );
					delete_transient( $transient );
				}
			}
		}

		/**
		 * Function to fetch templates.
		 *
		 * @return array|bool|\WP_Error
		 */
		public function fetch_templates( \WP_REST_Request $request ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				return false;
			}
			if ( $request->get_param( 'reset' ) ) {
				$this->delete_transient();
			}

			$templates_list = get_transient( 'gutentor_get_template_library' );

			/*Get/Fetch templates*/
			if ( empty( $templates_list ) ) {
				if ( ! function_exists( 'run_gutentor_template_library' ) ) {
					/*
					if gutentor template library is not installed
					fetch template library data from live*/
					$url       = 'https://www.demo.gutentor.com/wp-json/gutentor-tlapi/v1/fetch_templates/';
					$body_args = array(
						/*API version*/
						'api_version' => wp_get_theme()['Version'],
						/*lang*/
						'site_lang'   => get_bloginfo( 'language' ),
					);
					$raw_json  = wp_safe_remote_get(
						$url,
						array(
							'timeout' => 100,
							'body'    => $body_args,
						)
					);

					if ( ! is_wp_error( $raw_json ) ) {
						$demo_server = json_decode( wp_remote_retrieve_body( $raw_json ), true );
						if ( json_last_error() === JSON_ERROR_NONE ) {
							if ( is_array( $demo_server ) ) {
								$templates_list = $demo_server;
							}
						}
					} else {
						return rest_ensure_response( $raw_json->get_error_message() );
					}
				} else {
					/*
					if gutentor template library is installed
					fetch template library data from the plugin gutentor-template-library
					special hooks for gutentor-template-library plugin*/
					$templates_list = apply_filters( 'gutentor_advanced_import_gutentor_template_library', array() );
				}

				/*Store on transient*/
				$templates_list = apply_filters( 'gutentor_get_template_library', $templates_list );

				set_transient( 'gutentor_get_template_library', $templates_list, DAY_IN_SECONDS );
			}

			$templates = apply_filters( 'gutentor_advanced_import_templates', $templates_list );

			return rest_ensure_response( $templates );
		}

		/**
		 * Function to fetch template JSON.
		 *
		 * @return array|bool|\WP_Error
		 */
		public function import_template( $request ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				return false;
			}

			$url = $request->get_param( 'url' );

			$url_array  = explode( '/', $url );
			$block_id   = $url_array[ count( $url_array ) - 5 ] . '-' . $url_array[ count( $url_array ) - 2 ];
			$block_json = get_transient( 'gutentor_get_block_json_' . $block_id );
			/*Get/Fetch templates*/
			if ( empty( $block_json ) ) {
				if ( $url ) {
					$body_args = array(
						/*API version*/
						'api_version' => GUTENTOR_VERSION,
						/*lang*/
						'site_lang'   => get_bloginfo( 'language' ),
					);
					$raw_json  = wp_safe_remote_get(
						$url,
						array(
							'timeout' => 100,
							'body'    => $body_args,
						)
					);

					if ( ! is_wp_error( $raw_json ) ) {
						$block_json = json_decode( wp_remote_retrieve_body( $raw_json ) );
						/*Store on transient*/
						ob_start();
						set_transient( 'gutentor_get_block_json_' . $block_id, $block_json, DAY_IN_SECONDS );
						ob_get_clean();
					}
				}
			}
			if ( $block_json ) {
				return rest_ensure_response( $block_json );
			}
			return false;
		}

		/**
		 * Add Dynamic template
		 * Reusable blocks
		 *
		 * @access public
		 * @since 2.1.9
		 * @return array
		 */
		public function add_dynamic_library( $templates_list ) {

			$d_list = array();

			/*Reusable*/
			$args      = array(
				'post_type'      => 'wp_block',
				'posts_per_page' => 100,
			);
			$the_query = new WP_Query( gutentor_get_query( $args ) );
			if ( $the_query->have_posts() ) :

				while ( $the_query->have_posts() ) :
					$the_query->the_post();

					$q_list                   = array();
					$q_list['title']          = get_the_title();
					$q_list['post_content']   = get_the_content();
					$q_list['type']           = 'reusable';
					$q_list['keywords']       = explode( ' ', get_the_title() );
					$q_list['categories']     = array( 'reusable' );
					$q_list['template_url']   = '';
					$q_list['screenshot_url'] = '';
					$q_list['demo_url']       = esc_url( get_edit_post_link() );

					$d_list[] = $q_list;

				endwhile;
			endif;
			wp_reset_postdata();

			/*Patterns*/
			if ( class_exists( 'WP_Block_Patterns_Registry' ) ) {
				$p_lists = WP_Block_Patterns_Registry::get_instance()->get_all_registered();
				if ( $p_lists && is_array( $p_lists ) ) {
					foreach ( $p_lists as $p_list ) {
						$q_list                   = array();
						$q_list['title']          = $p_list['title'] ? $p_list['title'] : __( 'Untitled', 'gutentor' );
						$q_list['post_content']   = $p_list['content'] ? $p_list['content'] : '';
						$q_list['type']           = 'pattern';
						$q_list['keywords']       = explode( ' ', $q_list['title'] );
						$q_list['categories']     = $p_list['categories'] ? $p_list['categories'] : array();
						$q_list['template_url']   = '';
						$q_list['screenshot_url'] = '';
						$q_list['demo_url']       = '';

						$d_list[] = $q_list;
					}
				}
			}

			return array_merge_recursive( $templates_list, $d_list );

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
Gutentor_Advanced_Import::get_instance()->run();
