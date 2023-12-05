<?php
/**
 * Do things related with Gutentor settings
 *
 * @since 3.0.3
 */

if ( ! class_exists( 'Gutentor_Admin_Settings' ) ) {
	/**
	 * Class Gutentor_Admin_Settings.
	 */
	class Gutentor_Admin_Settings {

		protected static $page_slug = 'gutentor-settings';

		public function __construct() {

			add_action( 'admin_menu', array( __CLASS__, 'admin_pages' ) );
			add_action( 'admin_init', array( $this, 'depreciated_settings' ) );
			add_filter( 'register_post_type_args', array( $this, 'enable_rest_api' ), 20, 2 );
			add_filter( 'use_block_editor_for_post_type', array( $this, 'enable_gutenberg_post_type' ), 999, 2 );

			add_action( 'init', array( $this, 'add_page_templates_in_post_types' ), 999 );

		}


		/**
		 * Admin Page Menu and submenu page
		 *
		 * @since 3.0.3
		 */
		public static function admin_pages() {

			add_submenu_page(
				'gutentor',
				esc_html__( 'Settings', 'gutentor' ),
				esc_html__( 'Settings', 'gutentor' ),
				'manage_options',
				'gutentor-settings',
				array( __CLASS__, 'gutentor_settings_template' )
			);
		}

		/**
		 * Render Settings Template
		 *
		 * @since 3.0.3
		 */
		public static function gutentor_settings_template() {
			require_once GUTENTOR_PATH . 'includes/admin/templates/settings.php';
		}

		/**
		 * All existing Settings
		 * Convert in just one setting
		 *
		 * @since 3.1.2
		 */
		public function depreciated_settings() {
			$g_options = gutentor_get_options();
			if ( isset( $g_options['options_v_0'] ) ) {
				return false;
			}

			$gutentor_get_options = array();

			/*Typography*/
			$global_typography = array(
				'h1',
				'h2',
				'h3',
				'h4',
				'h5',
				'h6',
				'body',
				'button',
			);
			foreach ( $global_typography as $gt ) {
				$key                          = 'gutentor-gt-' . esc_attr( $gt );
				$gutentor_get_options[ $key ] = gutentor_get_options( $key );
			}

			/*Global Color*/
			$global_color = array(
				'btn-txt',
				'btn-bg',
				'heading',
				'body',
				'link',
				'cat-txt-default',
				'cat-bg-default',
			);
			foreach ( $global_color as $gc ) {
				$key                          = 'gc-' . esc_attr( $gc );
				$gutentor_get_options[ $key ] = gutentor_get_options( $key );
			}

			/*Global Width*/
			$global_width = array(
				'mobile',
				'tablet',
				'desktop',
				'large',
			);
			foreach ( $global_width as $gw ) {
				$key                          = 'gutentor-gw-' . esc_attr( $gw );
				$gutentor_get_options[ $key ] = gutentor_get_options( $key );
			}

			/*post format*/
			$post_formats = gutentor_get_post_formats();
			if ( $post_formats && is_array( $post_formats ) ) {
				foreach ( $post_formats as $post_format ) {
					$key                          = 'gutentor-pf-' . esc_attr( $post_format );
					$gutentor_get_options[ $key ] = gutentor_get_options( $key );
				}
			}

			$other_options = array(
				'gutentor_disable_wide_width_editor',
				'gutentor_map_api',
				'gutentor_font_awesome_version',
				'gutentor_tax_term_color',
				'gutentor_tax_term_image',
				'gutentor_dynamic_style_location',
				'gutentor_color_palatte_options',
				'gutentor_color_palatte',
				'gutentor_gt_apply_options',
			);
			foreach ( $other_options as $key ) {
				$gutentor_get_options[ $key ] = gutentor_get_options( $key );
			}
			/*Enabled Disable Blocks*/
			$gutentor_get_options['_GUTENTOR_BLOCKS'] = gutentor_get_options( '_GUTENTOR_BLOCKS' );

			/*Gutentor color palatte*/
			$gutentor_get_options['gutentor_color_palatte'] = gutentor_get_options( 'gutentor_color_palatte' );

			foreach ( $gutentor_get_options as $key => $value ) {
				delete_option( $key );
				if ( 'gutentor_map_api' == $key ) {
					$key = 'map-api';
				} elseif ( 'gutentor_disable_wide_width_editor' == $key ) {
					$key = 'wide-width-editor';
				} elseif ( 'gutentor_tax_term_color' == $key && $gutentor_get_options[ $key ] ) {
					$key   = 'tax-in-color';
					$value = array(
						'category',
						'post_tag',
						'product_cat',
						'product_tag',
						'download_category',
						'download_tag',
					);
				} elseif ( 'gutentor_tax_term_image' == $key && $gutentor_get_options[ $key ] ) {
					$key   = 'tax-in-image';
					$value = array(
						'category',
						'post_tag',
						'product_cat',
						'product_tag',
						'download_category',
						'download_tag',
					);
				} elseif ( 'gutentor_dynamic_style_location' == $key ) {
					$key = 'dynamic-res-location';
				} elseif ( 'gutentor_gt_apply_options' == $key ) {
					$key = 'typo-apply-options';
				} elseif ( 'gutentor_font_awesome_version' == $key ) {
					$key = 'fa-version';
				} elseif ( 'gutentor_color_palatte_options' == $key ) {
					$key = 'color-palette-options';
				} elseif ( 'gutentor_color_palatte' == $key ) {
					$key = 'color-palettes';
				} elseif ( '_GUTENTOR_BLOCKS' == $key ) {
					$key = 'off-blocks';
				} elseif ( strpos( $key, 'gutentor-pf-' ) !== false ) {
					$key = str_replace( 'gutentor-pf-', 'pf-', $key );
				} elseif ( strpos( $key, 'gutentor-gt-' ) !== false ) {
					$key = str_replace( 'gutentor-gt-', 'gt-', $key );
				} elseif ( strpos( $key, 'gutentor-gw-' ) !== false ) {
					$key = str_replace( 'gutentor-gw-', 'gw-', $key );
				} elseif ( strpos( $key, 'gutentor-gc-' ) !== false ) {
					$key = str_replace( 'gutentor-gc-', 'gc-', $key );
				}
				$g_options[ $key ] = $value;
			}

			if ( isset( $g_options['gutentor_enable_editor_in_pt'] ) ) {
				$g_options['editor-in-pt'] = $g_options['gutentor_enable_editor_in_pt'];
				unset( $g_options['gutentor_enable_editor_in_pt'] );
			}
			if ( isset( $g_options['gutentor_enable_page_templates_in_pt'] ) ) {
				$g_options['page-templates-in-pt'] = $g_options['gutentor_enable_page_templates_in_pt'];
				unset( $g_options['gutentor_enable_page_templates_in_pt'] );
			}
			if ( isset( $g_options['gutentor_enable_import_block'] ) ) {
				if ( $g_options['gutentor_enable_import_block'] ) {
					$g_options['enable-import-block'] = $g_options['gutentor_enable_import_block'];
				} else {
					$g_options['enable-import-block'] = true;
				}
				unset( $g_options['gutentor_enable_import_block'] );
			}
			if ( isset( $g_options['gutentor_enable_export_block'] ) ) {
				if ( $g_options['gutentor_enable_export_block'] ) {
					$g_options['enable-export-block'] = $g_options['gutentor_enable_export_block'];
				} else {
					$g_options['enable-export-block'] = true;
				}
				unset( $g_options['gutentor_enable_export_block'] );
			}
			if ( isset( $g_options['resource_load'] ) ) {
				$g_options['resource-load'] = $g_options['resource_load'];
				unset( $g_options['resource_load'] );
			}
			if ( isset( $g_options['gutentor_enable_edd_demo_url'] ) ) {
				$g_options['edd-demo-url'] = $g_options['gutentor_enable_edd_demo_url'];
				unset( $g_options['gutentor_enable_edd_demo_url'] );
			}

			$g_options['options_v_0'] = true;
			update_option( 'gutentor_settings_options', $g_options );
		}

		/**
		 * Enable rest api post types
		 *
		 * @since 3.0.3
		 */
		public static function enable_rest_api( $args, $post_type ) {

			$gutentor_settings = gutentor_get_options();
			if ( isset( $gutentor_settings['editor-in-pt'] ) ) {
				$gutenberg_enable_post_types = $gutentor_settings['editor-in-pt'];
				if ( is_array( $gutenberg_enable_post_types ) &&
					in_array( $post_type, $gutenberg_enable_post_types )
				) {
					$args['show_in_rest'] = true;
				}
			}
			return $args;
		}

		/**
		 * Enable Gutenberg Editor in Post Types
		 *
		 * @since 3.0.3
		 */
		public static function enable_gutenberg_post_type( $can_edit, $post_type ) {

			$gutentor_settings = gutentor_get_options();
			if ( isset( $gutentor_settings['editor-in-pt'] ) ) {
				$gutenberg_enable_post_types = $gutentor_settings['editor-in-pt'];
				if ( is_array( $gutenberg_enable_post_types ) &&
					in_array( $post_type, $gutenberg_enable_post_types ) ) {
					return true;
				}
			}
			return $can_edit;
		}

		/**
		 * How to add Page Templates to Post or Custom Post Types
		 * https://www.gutentor.com/documentation/article/how-to-add-page-templates-to-post-or-custom-post-types/
		 *
		 * @since 3.0.3
		 */
		public function add_page_templates_in_post_types() {
			$pts               = array();
			$pts[]             = 'page';
			$gutentor_settings = gutentor_get_options();
			if ( isset( $gutentor_settings['page-templates-in-pt'] ) ) {
				$pt = maybe_unserialize( $gutentor_settings['page-templates-in-pt'] );
				if ( is_array( $pt ) && ! empty( $pt ) ) {
					foreach ( $pt as $p ) {
						$pts[] = $p;
					}
				}
			}
			if ( ! is_array( $pts ) ) {
				return;
			}
			foreach ( $pts as $p ) {
				add_filter( 'theme_' . $p . '_templates', array( gutentor_hooks(), 'gutentor_add_page_template' ) );
			}
			if ( in_array( 'page', $pts ) ) {
				add_filter( 'page_template', array( gutentor_hooks(), 'gutentor_redirect_page_template' ), 999 );
			}
			if ( count( array_diff( $pts, array( 'page' ) ) ) > 0 ) {
				add_filter( 'single_template', array( gutentor_hooks(), 'gutentor_redirect_page_template' ), 999 );
			}
		}
	}
}
new Gutentor_Admin_Settings();
