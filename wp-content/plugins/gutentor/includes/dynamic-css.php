<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Dynamic_CSS' ) ) :

	/**
	 * Create Dynamic CSS
	 *
	 * @package Gutentor
	 * @since 1.0.0
	 */
	class Gutentor_Dynamic_CSS {

		/**
		 * Rest route namespace.
		 *
		 * @var $namespace
		 */
		public $namespace = 'gutentor-dynamic-css/';

		/**
		 * Rest route version.
		 *
		 * @var $version
		 */
		public $version = 'v1';

		/**
		 * $all_google_fonts
		 *
		 * @var array
		 * @access public
		 * @since 1.0.0
		 */
		public $all_google_fonts = array();

		/**
		 * All blocks on a page
		 *
		 * @var array
		 * @access public
		 * @since 1.0.0
		 */
		public static $unique_blocks = array();

		/**
		 * Main Instance
		 *
		 * Insures that only one instance of Gutentor_Dynamic_CSS exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return object
		 */
		public static function instance() {

			// Store the instance locally to avoid private static replication.
			static $instance = null;

			// Only run these methods if they haven't been ran previously.
			if ( null === $instance ) {
				$instance = new Gutentor_Dynamic_CSS();
			}

			// Always return the instance.
			return $instance;
		}

		/**
		 * Run functionality with hooks
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return void
		 */
		public function run() {
			/*Rest Api to save dynamic CSS and get terms css*/
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );

			add_action( 'render_block', array( $this, 'remove_block_css' ), 10, 2 );
			add_filter( 'wp_head', array( $this, 'dynamic_css' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'dynamic_css_enqueue' ), 9999 );

			add_filter( 'wp_enqueue_scripts', array( $this, 'enqueue_google_fonts' ), 9998 );
			add_filter( 'admin_head', array( $this, 'admin_enqueue_google_fonts' ), 100 );

			/*Dynamic Google Fonts for FSE*/
			add_filter( 'gutentor_block_editor_localize_data', array( $this, 'render_fse_admin_css' ), 100 );
		}

		/**
		 * Render FSE admin css
		 * For google fonts
		 * Probably temporary solution
		 * Should be fixed from JS solution
		 *
		 * TODO : JS
		 *
		 * @since    3.1.9
		 * @access   public
		 *
		 * @return array
		 */
		public function render_fse_admin_css( $localize_data ) {

			if ( gutentor_is_site_editor_screen() ) {
				$block_templates = get_block_templates();

				if ( $block_templates ) {
					$fonts = array();
					foreach ( $block_templates as $template_info ) {
						if ( isset( $template_info->wp_id ) && $template_info->wp_id ) {
							$cssPrefix = $template_info->wp_id;
							$fonts_url = get_post_meta( $cssPrefix, 'gutentor_gfont_url', true );

							if ( $fonts_url ) {
								$fonts_url = apply_filters( 'gutentor_google_fonts', $fonts_url );
								if ( $fonts_url ) {
									$fonts [] = array(
										'id'  => $cssPrefix,
										'url' => esc_url( $fonts_url ),
									);
								}
							}
						}
					}
					$localize_data['fseGfonts'] = $fonts;
				}
			}
			return $localize_data;

		}

		/**
		 * Get core template parts from blocks
		 *
		 * @since    3.2.4
		 * @access   private
		 *
		 * @return array|boolean
		 */
		private function get_core_template_part( $blocks ) {
			$core_template_parts = array();
			foreach ( $blocks as $block ) {
				if ( 'core/template-part' === $block['blockName'] ) {
					$core_template_parts[] = $block;
				}
				if ( isset( $block['innerBlocks'] ) &&
					! empty( $block['innerBlocks'] ) &&
					is_array( $block['innerBlocks'] )
				) {
					if ( $this->get_core_template_part( $block['innerBlocks'] ) ) {
						$core_template_parts = array_merge( $core_template_parts, $this->get_core_template_part( $block['innerBlocks'] ) );
					}
				}
			}
			if ( ! empty( $core_template_parts ) ) {
				return $core_template_parts;
			}
			return false;
		}

		/**
		 * Render FSE public css
		 * TODO : May be file and head support
		 *
		 * @since    3.1.9
		 * @access   public
		 *
		 * @return array
		 */
		public function render_fse_public_css() {
			if ( ! current_theme_supports( 'block-templates' ) ) {
				return;
			}
			global $_wp_current_template_content;

			if ( $_wp_current_template_content ) {
				$blocks = parse_blocks( $_wp_current_template_content );

				require_once GUTENTOR_PATH . 'includes/tools/class-gutentor-tempalte-info.php';

				global $wp_filesystem;
				if ( ! $wp_filesystem ) {
					require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php';
				}
				$upload_dir = wp_upload_dir();

				$template_info = Gutentor_Template_Info::get_instance()->get_template_info();
				if ( isset( $template_info->wp_id ) && $template_info->wp_id ) {
					$cssPrefix = $template_info->wp_id;
					$css_info  = get_post_meta( $cssPrefix, 'gutentor_css_info', true );
					wp_enqueue_style( 'gutentor-dynamic-' . $cssPrefix, trailingslashit( $upload_dir['baseurl'] ) . 'gutentor/p-' . $cssPrefix . '.css', false, isset( $css_info['saved_version'] ) ? $css_info['saved_version'] : '' );

					/*Google Fonts*/
					$fonts_url = get_post_meta( $cssPrefix, 'gutentor_gfont_url', true );
					if ( $fonts_url ) {

						$fonts_url = apply_filters( 'gutentor_google_fonts', $fonts_url );
						if ( $fonts_url ) {
							wp_enqueue_style( 'gutentor-google-fonts-' . $cssPrefix, esc_url( $fonts_url ) );
						}
					}
				}

				$core_template_parts = $this->get_core_template_part( $blocks );
				if ( $core_template_parts ) {

					foreach ( $core_template_parts as $block ) {
						$template_parts_info = Gutentor_Template_Info::get_instance()->get_template_part_info( $block['attrs'] );

						if ( isset( $template_parts_info->ID ) && $template_parts_info->ID ) {
							$cssPrefix = $template_parts_info->ID;
							$css_info  = get_post_meta( $cssPrefix, 'gutentor_css_info', true );
							wp_enqueue_style( 'gutentor-dynamic-' . $cssPrefix, trailingslashit( $upload_dir['baseurl'] ) . 'gutentor/p-' . $cssPrefix . '.css', false, isset( $css_info['saved_version'] ) ? $css_info['saved_version'] : '' );
							/*Google Fonts*/
							$fonts_url = get_post_meta( $cssPrefix, 'gutentor_gfont_url', true );
							if ( $fonts_url ) {
								$fonts_url = apply_filters( 'gutentor_google_fonts', $fonts_url );
								if ( $fonts_url ) {
									wp_enqueue_style( 'gutentor-google-fonts-' . $cssPrefix, esc_url( $fonts_url ) );
								}
							}
						}
					}
				}
			}
		}

		/**
		 * Get unique blocks
		 *
		 * @since    3.0.0
		 * @access   public
		 *
		 * @return array
		 */
		public function get_unique_blocks() {
			return self::$unique_blocks;
		}

		/**
		 * Set unique blocks
		 *
		 * @since    3.0.0
		 * @access   public
		 *
		 * @return void
		 */
		public function set_unique_blocks( $post_id ) {
			$css_info = get_post_meta( $post_id, 'gutentor_css_info', true );
			if ( isset( $css_info['blocks'] ) && is_array( $css_info['blocks'] ) ) {
				self::$unique_blocks = array_unique( array_merge( self::$unique_blocks, $css_info['blocks'] ) );
			}
		}

		/**
		 * Get google font url
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return string
		 */
		public function isGutentorMetaExists() {
			return get_post_meta( get_the_ID(), 'gutentor_dynamic_css', true );
		}

		/**
		 * Get google font url
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return string
		 */

		public function get_google_font_url( $gfonts ) {
			$fonts_url           = '';
			$unique_google_fonts = array();

			if ( ! empty( $gfonts ) ) {
				foreach ( $gfonts as $single_google_font ) {
					$font_family = str_replace( ' ', '+', $single_google_font['family'] );
					if ( isset( $single_google_font['font-weight'] ) ) {
						$unique_google_fonts[ $font_family ]['font-weight'][] = $single_google_font['font-weight'];
					}
				}
			}
			$google_font_family = '';
			if ( ! empty( $unique_google_fonts ) ) {
				foreach ( $unique_google_fonts as $font_family => $unique_google_font ) {
					if ( ! empty( $font_family ) ) {
						if ( $google_font_family ) {
							$google_font_family .= '|';
						}
						$google_font_family .= $font_family;
						if ( isset( $unique_google_font['font-weight'] ) ) {
							$unique_font_weights = array_unique( $unique_google_font['font-weight'] );
							if ( ! empty( $unique_font_weights ) ) {
								$google_font_family .= ':' . join( ',', $unique_font_weights );
							} else {
								$google_font_family .= ':' . 'regular';
							}
						}
					}
				}
			}

			if ( $google_font_family ) {
				$google_font_family = str_replace( 'italic', 'i', $google_font_family );
				$fonts_url          = add_query_arg(
					array(
						'family' => $google_font_family,
					),
					'//fonts.googleapis.com/css'
				);
			}
			return $fonts_url;
		}

		/**
		 * Register REST API route
		 */
		public function register_routes() {
			$namespace = $this->namespace . $this->version;

			register_rest_route(
				$namespace,
				'/save_dynamic_css',
				array(
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'save_dynamic_css' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
						'args'                => array(),
					),
				)
			);
			register_rest_route(
				$namespace,
				'/get_tax_term_css',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_tax_term_css' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
					),
				)
			);
		}

		/**
		 * Function to get Static CSS
		 *
		 * @since 3.0.0
		 * @param string  $block_id
		 * @param boolean $is_file by default return file content, if is_file true return file url.
		 *
		 * @return string
		 */
		public function get_static_css( $block_id, $is_file = false ) {
			$is_rtl   = is_rtl() ? '.rtl' : '';
			$file_url = false;
			switch ( $block_id ) :

				case 'global':
					$file_url = GUTENTOR_URL . 'assets/css/global/global' . $is_rtl . '.css';
					break;
				case 'featured':
					$file_url = GUTENTOR_URL . 'assets/css/module/ft' . $is_rtl . '.css';
					break;

				case 'slick':
					$file_url = GUTENTOR_URL . 'assets/css/global/slick' . $is_rtl . '.css';
					break;

				case 'global-type':
					$file_url = GUTENTOR_URL . 'assets/css/module/pg' . $is_rtl . '.css';
					break;

				case 'global-widget':
					$file_url = GUTENTOR_URL . 'assets/css/widget/widget-global' . $is_rtl . '.css';
					break;

				case 'gutentor/e1':
				case 'gutentor/e2':
				case 'gutentor/e5':
				case 'gutentor/e6':
				case 'gutentor/e7':
				case 'gutentor/e8':
				case 'gutentor/e9':
				case 'gutentor/e10':
				case 'gutentor/e11':
				case 'gutentor/e12':
				case 'gutentor/e13':
				case 'gutentor/e14':
				case 'gutentor/e19':
				case 'gutentor/e21':
					$file     = explode( '/', $block_id )[1];
					$file_url = GUTENTOR_URL . 'assets/css/elements/' . $file . $is_rtl . '.css';
					break;

				case 'gutentor/m0':
				case 'gutentor/m1':
				case 'gutentor/m2':
				case 'gutentor/m4':
				case 'gutentor/m5':
				case 'gutentor/m6':
				case 'gutentor/m7':
				case 'gutentor/m8':
				case 'gutentor/m9':
				case 'gutentor/m10':
				case 'gutentor/m13':
					$file     = explode( '/', $block_id )[1];
					$file_url = GUTENTOR_URL . 'assets/css/module/' . $file . $is_rtl . '.css';
					break;

				case 'gutentor/p1':
				case 'gutentor/p2':
				case 'gutentor/p3':
				case 'gutentor/p4':
				case 'gutentor/p5':
				case 'gutentor/p6':
					$file     = explode( '/', $block_id )[1];
					$file_url = GUTENTOR_URL . 'assets/css/module/' . $file . $is_rtl . '.css';
					break;

				case 'gutentor/t1':
				case 'gutentor/t3':
					$file     = explode( '/', $block_id )[1];
					$file_url = GUTENTOR_URL . 'assets/css/module/' . $file . $is_rtl . '.css';
					break;

				case 'gutentor/about-block':
				case 'gutentor/accordion':
				case 'gutentor/author-profile':
				case 'gutentor/blog-post':
				case 'gutentor/call-to-action':
				case 'gutentor/content-box':
				case 'gutentor/count-down':
				case 'gutentor/divider':
				case 'gutentor/featured-block':
				case 'gutentor/gallery':
				case 'gutentor/google-map':
				case 'gutentor/icon-box':
				case 'gutentor/image-box':
				case 'gutentor/image-slider':
				case 'gutentor/list':
				case 'gutentor/notification':
				case 'gutentor/opening-hours':
				case 'gutentor/pricing':
				case 'gutentor/progress-bar':
				case 'gutentor/restaurant-menu':
				case 'gutentor/show-more':
				case 'gutentor/social':
				case 'gutentor/tabs':
				case 'gutentor/team':
				case 'gutentor/testimonial':
				case 'gutentor/timeline':
				case 'gutentor/video-popup':
					$file     = explode( '/', $block_id )[1];
					$file_url = GUTENTOR_URL . 'assets/css/widget/' . $file . $is_rtl . '.css';
					break;

				default:
					break;

			endswitch;

			/*return file url*/
			if ( $is_file ) {
				return $file_url;
			}

			/*Get/Fetch CSS*/
			if ( $file_url ) {

				$body_args = array(
					/*API version*/
					'api_version' => GUTENTOR_VERSION,
					/*lang*/
					'site_lang'   => get_bloginfo( 'language' ),
				);
				$raw_json  = wp_safe_remote_get(
					$file_url,
					array(
						'timeout' => 100,
						'body'    => $body_args,
					)
				);

				if ( ! is_wp_error( $raw_json ) ) {
					$block_css = wp_remote_retrieve_body( $raw_json );
				} else {
					$block_css = false;
				}
			} else {

				$block_css = false;
			}
			return $block_css;
		}

		private function get_blocks_css( $blocks ) {
			$block_css = '';
			if ( is_array( $blocks ) ) {
				/*global*/
				$block_css .= $this->get_static_css( 'global' );

				/*Slick*/
				$slick = array(
					'gutentor/image-slider',
					'gutentor/m5',
					'gutentor/m0',
					'gutentor/p3',
					'gutentor/t3',
				);
				if ( ! empty( array_intersect( $blocks, $slick ) ) ) {
					$block_css .= $this->get_static_css( 'slick' );
				}

				/*featured*/
				$featured = array(
					'gutentor/t1',
					'gutentor/t2',
					'gutentor/p2',
				);
				if ( ! empty( array_intersect( $blocks, $featured ) ) ) {
					$block_css .= $this->get_static_css( 'featured' );
				}

				/*Post/Tax Type*/
				$types = array(
					'gutentor/p1',
					'gutentor/p2',
					'gutentor/p3',
					'gutentor/p4',
					'gutentor/p5',
					'gutentor/p6',
					'gutentor/t1',
					'gutentor/t2',
					'gutentor/t3',
				);
				if ( ! empty( array_intersect( $blocks, $types ) ) ) {
					$block_css .= $this->get_static_css( 'global-type' );
				}

				/*Widget*/
				$widgets = array(
					'gutentor/about-block',
					'gutentor/accordion',
					'gutentor/author-profile',
					'gutentor/blog-post',
					'gutentor/call-to-action',
					'gutentor/content-box',
					'gutentor/count-down',
					'gutentor/counter-box',
					'gutentor/divider',
					'gutentor/featured-block',
					'gutentor/gallery',
					'gutentor/google-map',
					'gutentor/icon-box',
					'gutentor/image-box',
					'gutentor/image-slider',
					'gutentor/list',
					'gutentor/notification',
					'gutentor/opening-hours',
					'gutentor/pricing',
					'gutentor/progress-bar',
					'gutentor/restaurant-menu',
					'gutentor/show-more',
					'gutentor/social',
					'gutentor/tabs',
					'gutentor/team',
					'gutentor/testimonial',
					'gutentor/timeline',
					'gutentor/video-popup',
				);
				if ( ! empty( array_intersect( $blocks, $widgets ) ) ) {
					$block_css .= $this->get_static_css( 'global-widget' );
				}

				foreach ( $blocks as $block ) {
					$block_css .= $this->get_static_css( $block );
				}
			}
			return $block_css;
		}

		/**
		 * Save Post Dynamic CSS
		 *
		 * @since    3.1.3
		 * @return array
		 */
		public function save_post_dcss( $request ) {
			$message = array();
			$params  = $request->get_params();
			$post_id = absint( $params['post_id'] );

			if ( $post_id ) {
				$message[]   = __( 'Have Post ID ', 'gutentor' );
				$dynamic_css = $params['dynamic_css'];
				$css         = $dynamic_css['css'];
				$gfonts      = $dynamic_css['gfonts'];
				if ( ! empty( $gfonts ) ) {
					$message[]       = __( 'Google fonts is not empty', 'gutentor' );
					$google_font_url = $this->get_google_font_url( $gfonts );
					if ( $google_font_url ) {
						$message[] = __( 'Successfully get google fonts url', 'gutentor' );
						delete_post_meta( $post_id, 'gutentor_gfont_url' );
						update_post_meta( $post_id, 'gutentor_gfont_url', esc_url_raw( $google_font_url ) );
						$message[] = __( 'Successfully saved google fonts url', 'gutentor' );
					} else {
						$fonts_url = get_post_meta( $post_id, 'gutentor_gfont_url', true );
						delete_post_meta( $post_id, 'gutentor_gfont_url', $fonts_url );

						$message[] = __( 'Fail to get google fonts url', 'gutentor' );
					}
				} else {
					$fonts_url = get_post_meta( $post_id, 'gutentor_gfont_url', true );
					delete_post_meta( $post_id, 'gutentor_gfont_url', $fonts_url );
					$message[] = __( 'Google fonts is empty', 'gutentor' );
				}
				// We will probably need to load this file.
				if ( $css ) {
					$message[] = __( 'CSS is not empty', 'gutentor' );

					$minified_css = gutentor_dynamic_css()->minify_css( $css );
					delete_post_meta( $post_id, 'gutentor_dynamic_css' );
					update_post_meta( $post_id, 'gutentor_dynamic_css', $minified_css );
					$prev_css_info = get_post_meta( $post_id, 'gutentor_css_info', true );
					$css_info      = array(
						'version'       => sanitize_text_field( GUTENTOR_VERSION ),
						'saved_version' => isset( $prev_css_info['saved_version'] ) ? absint( $prev_css_info['saved_version'] + 1 ) : 1,
						'is_rtl'        => is_rtl(),
						'blocks'        => array_map( 'sanitize_text_field', $params['blocks'] ),
					);
					delete_post_meta( $post_id, 'gutentor_css_info' );
					update_post_meta( $post_id, 'gutentor_css_info', $css_info );
					$message[] = __( 'Successfully saved gutentor dynamic css', 'gutentor' );

					global $wp_filesystem;
					if ( ! $wp_filesystem ) {
						require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php';
					}
					$upload_dir = wp_upload_dir();
					$dir        = trailingslashit( $upload_dir['basedir'] ) . 'gutentor' . DIRECTORY_SEPARATOR;

					WP_Filesystem();
					if ( ! $wp_filesystem->is_dir( $dir ) ) {
						$message[] = $dir . __( ' not exists', 'gutentor' );
						if ( $wp_filesystem->mkdir( $dir ) ) {
							$message[] = $dir . __( ' created', 'gutentor' );
						} else {
							$message[] = $dir . __( ' create permission issue', 'gutentor' );
						}
					} else {
						$message[] = $dir . __( ' exists', 'gutentor' );

					}
					if ( $wp_filesystem->put_contents( $dir . 'p-' . $post_id . '.css', $minified_css, 0644 ) ) {
						$message[] = __( 'Successfully created css file ', 'gutentor' ) . 'p-' . $post_id . '.css';
					} else {
						$message[] = __( 'Permission denied to create css file ', 'gutentor' ) . 'p-' . $post_id . '.css';
					}
				}
			} else {
				$message[] = __( 'No Post ID ', 'gutentor' );
			}

			return $message;
		}

		/**
		 * Save Widget Dynamic CSS
		 *
		 * @since    3.1.3
		 * @return array
		 */
		public function save_widget_dcss( $request ) {
			$message = array();
			$params  = $request->get_params();
			$widgets = $params['widgets'];

			$g_w_saved_css = $g_w_css = get_option( 'gutentor_widget_dcss' );
			if ( ! is_array( $g_w_saved_css ) ) {
				$g_w_saved_css = $g_w_css = array();
			}
			if ( $widgets && isset( $widgets['theme'] ) ) {
				$g_w_css[ $widgets['theme'] ] = array();
				$message[]                    = __( 'Have Widgets ', 'gutentor' );
				$dynamic_css                  = $params['dynamic_css'];
				$css                          = $dynamic_css['css'];
				$gfonts                       = $dynamic_css['gfonts'];
				if ( ! empty( $gfonts ) ) {
					$message[]                              = __( 'Google fonts is not empty', 'gutentor' );
					$g_w_css[ $widgets['theme'] ]['gfonts'] = $gfonts;

				} else {
					$g_w_css[ $widgets['theme'] ]['gfonts'] = '';
					$message[]                              = __( 'Google fonts is empty', 'gutentor' );
				}
				// We will probably need to load this file.
				if ( $css ) {
					$message[]    = __( 'CSS is not empty', 'gutentor' );
					$minified_css = gutentor_dynamic_css()->minify_css( $css );

					$g_w_css[ $widgets['theme'] ]['css']           = $minified_css;
					$g_w_css[ $widgets['theme'] ]['version']       = GUTENTOR_VERSION;
					$g_w_css[ $widgets['theme'] ]['is_rtl']        = is_rtl();
					$g_w_css[ $widgets['theme'] ]['blocks']        = array_map( 'sanitize_text_field', $params['blocks'] );
					$g_w_css[ $widgets['theme'] ]['saved_version'] = isset( $g_w_saved_css[ $widgets['theme'] ] ) && isset( $g_w_saved_css[ $widgets['theme'] ]['saved_version'] ) ? absint( $g_w_saved_css[ $widgets['theme'] ]['saved_version'] ) + 1 : 1;

					$message[] = __( 'Successfully saved gutentor dynamic css', 'gutentor' );

					global $wp_filesystem;
					if ( ! $wp_filesystem ) {
						require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php';
					}
					$upload_dir = wp_upload_dir();
					$dir        = trailingslashit( $upload_dir['basedir'] ) . 'gutentor' . DIRECTORY_SEPARATOR;

					WP_Filesystem();
					if ( ! $wp_filesystem->is_dir( $dir ) ) {
						$message[] = $dir . __( ' not exists', 'gutentor' );
						if ( $wp_filesystem->mkdir( $dir ) ) {
							$message[] = $dir . __( ' created', 'gutentor' );
						} else {
							$message[] = $dir . __( ' create permission issue', 'gutentor' );
						}
					} else {
						$message[] = $dir . __( ' exists', 'gutentor' );

					}
					update_option( 'gutentor_widget_dcss', $g_w_css );
					if ( $wp_filesystem->put_contents( $dir . 'w-' . $widgets['theme'] . '.css', $minified_css, 0644 ) ) {
						$message[] = __( 'Successfully created css file ', 'gutentor' ) . 'w-' . $widgets['theme'] . '.css';
					} else {
						$message[] = __( 'Permission denied to create css file ', 'gutentor' ) . 'w-' . $widgets['theme'] . '.css';
					}
				}
			} else {
				$message[] = __( 'No Widgets ', 'gutentor' );
			}

			return $message;
		}

		/**
		 * Function to fetch template JSON.
		 *
		 * @return void
		 */
		public function save_dynamic_css( $request ) {
			$message = array();
			$params  = $request->get_params();
			if ( isset( $params['post_id'] ) ) {
				$message = $this->save_post_dcss( $request );
			} elseif ( isset( $params['widgets'] ) ) {
				$message = $this->save_widget_dcss( $request );
			}
			wp_send_json_success( $message );
		}

		/**
		 * Function to fetch template JSON.
		 *
		 * @return string
		 */
		public function get_tax_term_css( $request ) {
			/* get category Color options */
			$text_color       = '#1974d2';
			$bg               = '#ffffff';
			$hover_bg         = '#ffffff';
			$hover_text_color = '#1974d2';
			$tax_terms        = $request->get_params( 'tax_terms' )['tax_terms'];
			$important        = ' !important;';
			$tax_in_color     = gutentor_get_options( 'tax-in-color' );
			/*default category text color */
			$default_cat_txt_color  = gutentor_get_options( 'gc-cat-txt-default' );
			$default_cat_txt_color  = json_decode( $default_cat_txt_color, true );
			$default_cat_txt_color  = isset( $default_cat_txt_color['color'] ) ? $default_cat_txt_color['color'] : false;
			$default_cat_txt_enable = isset( $default_cat_txt_color['enable'] ) ? $default_cat_txt_color['enable'] : false;
			if ( $default_cat_txt_enable && isset( $default_cat_txt_color['normal'] ) && ! empty( $default_cat_txt_color['normal'] ) ) {
				$text_color = $default_cat_txt_color['normal'];
			}
			if ( $default_cat_txt_enable && isset( $default_cat_txt_color['hover'] ) && ! empty( $default_cat_txt_color['hover'] ) ) {
				$hover_text_color = $default_cat_txt_color['hover'];
			}
			/*default category bg color */
			$default_cat_bg_color  = gutentor_get_options( 'gc-cat-bg-default' );
			$default_cat_bg_color  = json_decode( $default_cat_bg_color, true );
			$default_cat_bg_color  = isset( $default_cat_bg_color['color'] ) ? $default_cat_bg_color['color'] : false;
			$default_cat_bg_enable = isset( $default_cat_bg_color['enable'] ) && $default_cat_bg_color['enable'];
			if ( $default_cat_bg_enable && isset( $default_cat_bg_color['normal'] ) && ! empty( $default_cat_bg_color['normal'] ) ) {
				$bg = $default_cat_bg_color['normal'];
			}
			if ( $default_cat_bg_enable && isset( $default_cat_bg_color['hover'] ) && ! empty( $default_cat_bg_color['hover'] ) ) {
				$hover_bg = $default_cat_bg_color['hover'];
			}
			$local_dynamic_css = '';
			foreach ( $tax_terms as $tax => $term_ids ) {
				foreach ( $term_ids as $term_id ) {

					$term = get_term( $term_id, $tax );
					if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
						$gutentor_meta = get_term_meta( $term_id, 'gutentor_meta', true );
						$slug          = $term->slug;

						$cat_color = 'gutentor-cat-' . esc_attr( $term_id );
						if ( is_array( $tax_in_color ) && in_array( $term->taxonomy, $tax_in_color ) && $gutentor_meta ) {
							if ( isset( $gutentor_meta['bg-color'] ) && ! empty( $gutentor_meta['bg-color'] ) ) {
								$bg = $gutentor_meta['bg-color'];
							}
							if ( isset( $gutentor_meta['bg-hover-color'] ) && ! empty( $gutentor_meta['bg-hover-color'] ) ) {
								$hover_bg = $gutentor_meta['bg-hover-color'];
							}
							if ( isset( $gutentor_meta['text-color'] ) && ! empty( $gutentor_meta['text-color'] ) ) {
								$text_color = $gutentor_meta['text-color'];
							}
							if ( isset( $gutentor_meta['text-hover-color'] ) && ! empty( $gutentor_meta['text-hover-color'] ) ) {
								$hover_text_color = $gutentor_meta['text-hover-color'];
							}
						} elseif ( is_array( $tax_in_color ) && in_array( $term->taxonomy, $tax_in_color ) && get_option( $cat_color ) ) {/*backward compatibility*/
							$gutentor_cat_options = get_option( $cat_color );
							$gutentor_cat_options = json_decode( $gutentor_cat_options, true );

							if ( isset( $gutentor_cat_options['background-color'] ) && ! empty( $gutentor_cat_options['background-color'] ) ) {
								$bg = $gutentor_cat_options['background-color'];
							}
							if ( isset( $gutentor_cat_options['background-hover-color'] ) && ! empty( $gutentor_cat_options['background-hover-color'] ) ) {
								$hover_bg = $gutentor_cat_options['background-hover-color'];
							}
							if ( isset( $gutentor_cat_options['text-color'] ) && ! empty( $gutentor_cat_options['text-color'] ) ) {
								$text_color = $gutentor_cat_options['text-color'];
							}
							if ( isset( $gutentor_cat_options['text-hover-color'] ) && ! empty( $gutentor_cat_options['text-hover-color'] ) ) {
								$hover_text_color = $gutentor_cat_options['text-hover-color'];
							}
						}

						/*Cat normal color */
						$cat_color_css = '';
						if ( $text_color ) {
							$cat_color_css .= 'color:' . $text_color . $important;
						}
						/*Cat bg color */
						if ( $bg ) {
							$cat_color_css .= 'background:' . $bg . $important;
						}
						/*Add cat color css */
						if ( ! empty( $cat_color_css ) && ! empty( $slug ) ) {
							$local_dynamic_css .= ".gutentor-categories .gutentor-cat-{$slug}{" . $cat_color_css . '}';
						}

						/* cat hover color */
						$cat_color_hover_css = '';
						if ( $hover_text_color ) {
							$cat_color_hover_css .= 'color:' . $hover_text_color . $important;
						}
						/* cat hover  bg color */
						if ( $hover_bg ) {
							$cat_color_hover_css .= 'background:' . $hover_bg . $important;
						}
						/*add hover css*/
						if ( ! empty( $cat_color_hover_css ) && ! empty( $slug ) ) {
							$local_dynamic_css .= ".gutentor-categories .gutentor-cat-{$slug}:hover{
                        " . $cat_color_hover_css . '
                        }';
						}
					}
				}
			}
			wp_send_json_success( $local_dynamic_css );
		}

		/**
		 * Set all_google_fonts
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return void
		 */
		public function google_block_typography_prep( $block ) {
			if ( ! $this->isGutentorMetaExists() ) {
				if ( is_array( $block ) && isset( $block['attrs'] ) ) {
					$typography_data = array_filter(
						$block['attrs'],
						function ( $key ) {
							return strpos( $key, 'Typography' );
						},
						ARRAY_FILTER_USE_KEY
					);

					foreach ( $typography_data as $key => $typography ) {
						if ( is_array( $typography )
							&& isset( $typography['fontType'] )
							&& 'google' === $typography['fontType']
							&& isset( $typography['googleFont'] )
							&& isset( $typography['fontWeight'] )
						) {
							$this->all_google_fonts[] = array(
								'family'      => $typography['googleFont'],
								'font-weight' => $typography['fontWeight'],
							);

						}
					}
				}
			}
		}

		/**
		 * Prepare $post object for google font url or typography
		 *
		 * @since    1.1.4
		 * @access   public
		 *
		 * @return void
		 */
		public function post_google_typography_prep( $post ) {
			if ( isset( $post->ID ) ) {
				if ( has_blocks( $post->ID ) ) {
					if ( isset( $post->post_content ) ) {
						$blocks = parse_blocks( $post->post_content );
						if ( is_array( $blocks ) && ! empty( $blocks ) ) {
							foreach ( $blocks as $i => $block ) {
								/*google typography*/
								gutentor_dynamic_css()->google_block_typography_prep( $block );
							}
						}
					}
				}
			}
		}

		/**
		 * add google font on admin
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return void|boolean
		 */
		public function admin_enqueue_google_fonts() {
			global $pagenow;
			if ( ! is_admin() ) {
				return false;
			}
			if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {

				if ( ! $this->isGutentorMetaExists() ) {
					global $post;
					$blocks = parse_blocks( $post->post_content );
					if ( is_array( $blocks ) || ! empty( $blocks ) ) {
						foreach ( $blocks as $i => $block ) {
							$this->google_block_typography_prep( $block );
						}
					}
				}

				$this->enqueue_google_fonts( true );
			} elseif ( 'widgets.php' === $pagenow ) {
				$this->enqueue_google_fonts( true );
			}
		}

		/**
		 * Remove style from Gutentor Blocks
		 * 3.2.6 updates
		 * Uses:
		 * 1. Dont remove style tag from blocks while saving
		 * 2. Dont save style on meta
		 * 3. DYNAMIC CSS OPTIONS, head,file,inline
		 * (verson compare check needed)
		 * 3.1. if inline is selected return $block_content as it is(it contain style tag and css)
		 * 3.2. else return $block_content by removing style tag
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @param string $block_content
		 * @param array  $block
		 * @return mixed
		 */
		public function remove_block_css( $block_content, $block ) {
			if ( $this->isGutentorMetaExists() ) {
				return $block_content;
			}

			if ( ! is_admin() && is_array( $block ) && isset( $block['blockName'] ) && strpos( $block['blockName'], 'gutentor' ) !== false ) {
				$block_content = preg_replace( '~<style(.*?)</style>~Usi', '', $block_content );
			}
			return $block_content;

		}

		/**
		 * Add Google Fonts
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @param string $block_content
		 * @param array  $block
		 * @return void|boolean
		 */
		public function enqueue_google_fonts( $head = false ) {
			/*FSE CSS for frontend only*/
			if ( ! is_admin() ) {
				gutentor_dynamic_css()->render_fse_public_css();
			}
			/*Singular*/
			if ( is_singular() || gutentor_is_edit_page() ) {
				$fonts_url = '';
				/*font family wp_enqueue_style*/
				$fonts_url = get_post_meta( get_the_ID(), 'gutentor_gfont_url', true );
				if ( $fonts_url ) {

					$fonts_url = apply_filters( 'gutentor_google_fonts', $fonts_url );
				} else {
					$all_google_fonts = apply_filters( 'gutentor_enqueue_google_fonts', $this->all_google_fonts );

					if ( ! empty( $all_google_fonts ) ) {
						$fonts_url = $this->get_google_font_url( $all_google_fonts );
					}
				}
				if ( $fonts_url ) {
					if ( $head ) {
						echo '<link id="gutentor-google-fonts" href="' . esc_url( $fonts_url ) . '" rel="stylesheet" />';
					} else {
						wp_enqueue_style( 'gutentor-google-fonts', esc_url( $fonts_url ) );
					}
				}
			}

			/*Global and Widgets*/
			$fonts_url        = '';
			$all_google_fonts = array();
			$global_typos     = gutentor_get_global_typography();
			if ( $global_typos && is_array( $global_typos ) && ! empty( $global_typos ) ) {
				foreach ( $global_typos as $global_typo ) {
					if ( $global_typo ) {
						$global_typo = json_decode( $global_typo, true );
						if ( $global_typo && ! empty( $global_typo ) ) {
							if ( isset( $global_typo['fontType'] ) &&
								$global_typo['fontType'] === 'google' &&
								isset( $global_typo['googleFont'] )
							) {
								$all_google_fonts[] = array(
									'family'      => $global_typo['googleFont'],
									'font-weight' => isset( $global_typo['fontWeight'] ) ? $global_typo['fontWeight'] : 'regular',
								);
							}
						}
					}
				}
			}

			if ( current_theme_supports( 'widgets-block-editor' ) ) {
				$g_w_saved_css = get_option( 'gutentor_widget_dcss' );
				if ( is_array( $g_w_saved_css ) &&
					isset( $g_w_saved_css[ get_template() ] ) &&
					isset( $g_w_saved_css[ get_template() ]['gfonts'] ) &&
					is_array( $g_w_saved_css[ get_template() ]['gfonts'] )
				) {
					$all_google_fonts = array_merge( $all_google_fonts, $g_w_saved_css[ get_template() ]['gfonts'] );
				}
			}

			if ( $all_google_fonts ) {
				$fonts_url = $this->get_google_font_url( $all_google_fonts );
			}
			if ( $fonts_url ) {
				if ( $head ) {
					echo '<link id="gutentor-global-google-fonts" href="' . esc_url( $fonts_url ) . '" rel="stylesheet" />';
				} else {
					wp_enqueue_style( 'gutentor-global-google-fonts', esc_url( $fonts_url ) );
				}
			}
		}

		/**
		 * Get CSS without empty selector
		 * Call after minification of CSS
		 *
		 * @since    2.1.0
		 * @access   public
		 *
		 * @param string $minified_css
		 * @return string
		 */
		function get_css_without_empty_selector_after_minify( $minified_css ) {
			$css_explode        = explode( '}', $minified_css );
			$result             = '';
			$double_braces_open = false;
			foreach ( $css_explode as $index => $item ) {
				/*check if double braces*/
				$is_double_braces = substr_count( $item, '{' ) > 1;
				if ( $is_double_braces || ( $item != '' && substr( $item, -1 ) != '{' ) ) {
					if ( $is_double_braces ) {
						$inner_explode = explode( '{', $item );/*max 0,1,2 array,2optional if css property present*/
						$inner_item    = $inner_explode[0] . '{';
						if ( isset( $inner_explode[2] ) && $inner_explode[2] != '' ) {
							$inner_item .= $inner_explode[1] . '{' . $inner_explode[2] . '}';
						}
						$result .= $inner_item;
					} else {
						$result .= $item . '}';
					}

					/*check if double braces*/
					if ( $is_double_braces ) {
						$double_braces_open = true;
					}
				}
				/*close double braces*/
				if ( $double_braces_open && $item == '' ) {
					$result            .= '}';
					$double_braces_open = false;
				}
				/*
				How about more than double braces
				Not needed for now*/
			}
			return $result;
		}


		/**
		 * Minify CSS
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @param string $css
		 * @return string
		 */
		public function minify_css( $css = '' ) {

			// Return if no CSS
			if ( ! $css ) {
				return '';
			}

			// remove comments
			$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );

			// Normalize whitespace
			$css = preg_replace( '/\s+/', ' ', $css );

			// Remove ; before }
			$css = preg_replace( '/;(?=\s*})/', '', $css );

			// Remove space after , : ; { } */ >
			$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );

			// Remove space before , ; { }
			$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );

			// Strips leading 0 on decimal values (converts 0.5px into .5px)
			$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

			// Strips units if value is 0 (converts 0px to 0)
			$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

			// Trim
			$css = trim( $css );

			/*
			get_css_without_empty_selector since 2.1.0
			Double call it to fix media query issue
			*/
			$css = $this->get_css_without_empty_selector_after_minify( $css );
			$css = $this->get_css_without_empty_selector_after_minify( $css );

			// Return minified CSS
			return $css;

		}

		/**
		 * Inner_blocks
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 *
		 * @param array $blocks
		 * @return mixed
		 */
		public function inner_blocks( $blocks ) {
			$get_style = '';

			foreach ( $blocks as $i => $block ) {

				/*google typography*/
				$this->google_block_typography_prep( $block );

				if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
					$get_style .= $this->inner_blocks( $block['innerBlocks'] );
				}
				if ( $block['blockName'] === 'core/block' && ! empty( $block['attrs']['ref'] ) ) {
					$reusable_block = get_post( $block['attrs']['ref'] );

					if ( ! $reusable_block || 'wp_block' !== $reusable_block->post_type ) {
						return '';
					}

					if ( 'publish' !== $reusable_block->post_status || ! empty( $reusable_block->post_password ) ) {
						return '';
					}

					$blocks     = parse_blocks( $reusable_block->post_content );
					$get_style .= $this->inner_blocks( $blocks );
				}

				if ( is_array( $block ) && isset( $block['innerHTML'] ) ) {
					preg_match( "'<style>(.*?)</style>'si", $block['innerHTML'], $match );
					if ( isset( $match[1] ) ) {
						$get_style .= $match[1];
					}
				}
			}
			return $get_style;
		}

		/**
		 * Single Stylesheet
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 *
		 * @param object $this_post
		 * @return mixed
		 */
		public function single_stylesheet( $this_post ) {

			$get_style = '';
			if ( isset( $this_post->ID ) ) {
				if ( has_blocks( $this_post->ID ) ) {
					if ( $this->isGutentorMetaExists() ) {
						$get_style = $this->isGutentorMetaExists();
					} elseif ( isset( $this_post->post_content ) ) {

						$blocks = parse_blocks( $this_post->post_content );
						if ( ! is_array( $blocks ) || empty( $blocks ) ) {
							return false;
						}
						$get_style = $this->inner_blocks( $blocks );
					}

					/*set unique blocks for page,post,archive and search*/
					$this->set_unique_blocks( $this_post->ID );
				}
			}
			return $get_style;
		}

		/**
		 * css prefix
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 *
		 * @return mixed
		 */
		public function css_prefix( $post = false ) {
			if ( ! $post ) {
				global  $post;
			}
			if ( isset( $post ) && isset( $post->ID ) && has_blocks( $post->ID ) ) {
				return $post->ID;
			}
			return false;
		}

		/**
		 * Get dynamic CSS
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @param object $post
		 * @return mixed
		 */
		public function get_singular_dynamic_css( $post = false ) {

			$getCSS = '';
			if ( $post ) {
				$getCSS = $this->single_stylesheet( $post );
			} elseif ( is_singular() ) {
				global $post;
				$getCSS = $this->single_stylesheet( $post );
			} elseif ( is_archive() || is_home() || is_search() ) {
				global $wp_query;
				if ( isset( $wp_query->posts ) ) {
					foreach ( $wp_query->posts as $post ) {
						$getCSS .= $this->single_stylesheet( $post );
					}
				}
			}

			$output = gutentor_dynamic_css()->minify_css( $getCSS );
			return $output;
		}

		/**
		 * For Backward compatible CSS
		 * Add Post Format CSS, Post Format Featured CSS
		 * and Taxonomy Term Color on Head
		 *
		 * Called in dynamic_css functions
		 *
		 * @since    3.0.0
		 * @access   public
		 *
		 * @return void
		 */
		public function backward_dynamic_css() {
			if ( ! is_singular() ) {
				return;
			}
			$css_info = get_post_meta( get_the_ID(), 'gutentor_css_info', true );
			/*
			 * If new version, just return
			 *
			 * Dont load css on new version
			 * */
			if ( $css_info && isset( $css_info['version'] ) ) {
				return;
			}

			if ( is_customize_preview() ) {
				?>
				<style type="text/css">
					<?php
					echo gutentor_post_format_colors( true );
					echo gutentor_post_featured_format_colors( true );
					echo gutentor_pm_post_categories_color( true );
					?>
				</style>
				<?php
			}
			if ( gutentor_has_block( 'gutentor/p1' ) ||
				gutentor_has_block( 'gutentor/p2' ) ||
				gutentor_has_block( 'gutentor/p6' )
			) {
				?>
				<style type="text/css" id="g-dc-p1-p2-p6">
					<?php
					echo gutentor_post_format_colors( true );
					echo gutentor_pm_post_categories_color( true );
					?>
				</style>
				<?php
			}
			if ( gutentor_has_block( 'gutentor/p6' ) ) {
				?>
				<style type="text/css" id="g-dc-p6">
					<?php
					echo gutentor_post_featured_format_colors( true );
					?>
				</style>
				<?php
			}

		}

		/**
		 * Callback function for wp_head
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return void
		 */
		public static function dynamic_css() {
			/*for some backward compatible CSS*/
			gutentor_dynamic_css()->backward_dynamic_css();

			$singularCSS = $combineCSS = '';

			if ( 'file' == apply_filters( 'gutentor_dynamic_style_location', 'head' ) ) {

				global $wp_customize;
				$upload_dir = wp_upload_dir();
				if ( is_singular() ) {
					global  $post;
					$cssPrefix = gutentor_dynamic_css()->css_prefix( $post );
					if ( isset( $wp_customize ) || ! file_exists( $upload_dir['basedir'] . '/gutentor/p-' . $cssPrefix . '.css' ) ) {
						$singularCSS = gutentor_dynamic_css()->get_singular_dynamic_css( $post );
						$combineCSS .= $singularCSS;
					}
				} elseif ( is_archive() || is_home() || is_search() ) {
					global $wp_query;
					if ( isset( $wp_query->posts ) ) {
						foreach ( $wp_query->posts as $post ) {
							$cssPrefix = gutentor_dynamic_css()->css_prefix( $post );
							if ( isset( $wp_customize ) || ! file_exists( $upload_dir['basedir'] . '/gutentor/p-' . $cssPrefix . '.css' ) ) {
								$singularCSS = gutentor_dynamic_css()->get_singular_dynamic_css( $post );
								$combineCSS .= $singularCSS;
							}
						}
					}
				}

				if ( current_theme_supports( 'widgets-block-editor' ) ) {
					$g_w_saved_css = get_option( 'gutentor_widget_dcss' );
					if ( is_array( $g_w_saved_css ) &&
						isset( $g_w_saved_css[ get_template() ] ) &&
						isset( $g_w_saved_css[ get_template() ]['css'] ) &&
						isset( $g_w_saved_css[ get_template() ]['blocks'] )
					) {
						if ( isset( $wp_customize ) || ! file_exists( $upload_dir['basedir'] . '/gutentor/w-' . get_template() . '.css' ) ) {
							$combineCSS .= gutentor_dynamic_css()->minify_css( $g_w_saved_css[ get_template() ]['css'] );
						}
					}
				}

				// Render CSS in the head
				if ( ! empty( $combineCSS ) ) {
					echo "<!-- Gutentor Dynamic CSS -->\n<style type=\"text/css\" id='gutentor-dynamic-css'>\n" . wp_strip_all_tags( $combineCSS ) . "\n</style>";
				}
			} else {
				if ( is_singular() ) {
					global  $post;
					$singularCSS .= gutentor_dynamic_css()->get_singular_dynamic_css( $post );
				} elseif ( is_archive() || is_home() || is_search() ) {
					global $wp_query;
					if ( isset( $wp_query->posts ) ) {
						foreach ( $wp_query->posts as $post ) {
							$singularCSS .= gutentor_dynamic_css()->get_singular_dynamic_css( $post );
						}
					}
				}
				$combineCSS = $singularCSS;

				if ( current_theme_supports( 'widgets-block-editor' ) ) {
					$g_w_saved_css = get_option( 'gutentor_widget_dcss' );
					if ( is_array( $g_w_saved_css ) &&
						isset( $g_w_saved_css[ get_template() ] ) &&
						isset( $g_w_saved_css[ get_template() ]['css'] ) &&
						isset( $g_w_saved_css[ get_template() ]['blocks'] )
					) {

						$combineCSS .= gutentor_dynamic_css()->minify_css( $g_w_saved_css[ get_template() ]['css'] );
					}
				}

				// Render CSS in the head
				if ( ! empty( $combineCSS ) ) {
					echo "<!-- Gutentor Dynamic CSS -->\n<style type=\"text/css\" id='gutentor-dynamic-css'>\n" . wp_strip_all_tags( $combineCSS ) . "\n</style>";
				}
			}
		}


		/**
		 * Fix RTL
		 *
		 * Run only if saved css rtl and site rtl not equal
		 *
		 * @since    3.0.0
		 * @access   public
		 *
		 * @param null
		 * @return void
		 */

		public function fix_rtl( $post_id ) {
			$is_rtl       = is_rtl() ? '.rtl' : '';
			$post_content = get_the_content( $post_id ); // Get the post_content
			preg_match_all( '<!-- /wp:(.*?) -->', $post_content, $blocks ); // Get all matches in between <!-- /wp: --> strings

			if ( is_array( $blocks[1] ) ) {
				/*global CSS*/
				wp_enqueue_style( 'gutentor-global', GUTENTOR_URL . 'assets/css/global/global' . $is_rtl . '.css' );

				/*slick CSS*/
				wp_enqueue_style( 'gutentor-slick', GUTENTOR_URL . 'assets/css/global/slick' . $is_rtl . '.css' );

				/*widget CSS*/
				wp_enqueue_style( 'gutentor-widget', GUTENTOR_URL . 'assets/css/global/widget-global' . $is_rtl . '.css' );

				/*post CSS*/
				wp_enqueue_style( 'gutentor-post', GUTENTOR_URL . 'assets/css/global/pg' . $is_rtl . '.css' );

				foreach ( $blocks[1] as $key => $block_name ) {
					switch ( $block_name ) {
						case 'gutentor/e1':
						case 'gutentor/e2':
						case 'gutentor/e5':
						case 'gutentor/e6':
						case 'gutentor/e7':
						case 'gutentor/e8':
						case 'gutentor/e9':
						case 'gutentor/e10':
						case 'gutentor/e11':
						case 'gutentor/e12':
						case 'gutentor/e13':
							$id   = $block_name;
							$file = explode( '/', $block_name )[1];
							$href = GUTENTOR_URL . 'assets/css/elements/' . $file . $is_rtl . '.css';
							wp_enqueue_style( $id, $href );
							break;

						case 'gutentor/m0':
						case 'gutentor/m1':
						case 'gutentor/m2':
						case 'gutentor/m4':
						case 'gutentor/m5':
						case 'gutentor/m6':
						case 'gutentor/m7':
						case 'gutentor/m8':
						case 'gutentor/m9':
						case 'gutentor/m10':
						case 'gutentor/m13':
						case 'gutentor/p1':
						case 'gutentor/p3':
						case 'gutentor/p2':
						case 'gutentor/p4':
						case 'gutentor/p5':
						case 'gutentor/p6':
						case 'gutentor/t1':
						case 'gutentor/t2':
						case 'gutentor/t3':
							$id   = $block_name;
							$file = explode( '/', $block_name )[1];
							$href = GUTENTOR_URL . 'assets/css/module/' . $file . $is_rtl . '.css';
							wp_enqueue_style( $id, $href );
							break;

						default:
							break;
					}
				}
			}
		}

		/**
		 * Callback function for wp_enqueue_scripts
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return void|boolean
		 */
		public static function dynamic_css_enqueue() {

			// If File is not selected.
			if ( 'file' !== apply_filters( 'gutentor_dynamic_style_location', 'head' ) ) {
				return false;
			}

			global $wp_customize;
			$upload_dir = wp_upload_dir();

			// Render CSS from the custom file
			if ( ! isset( $wp_customize ) ) {

				if ( is_singular() ) {
					global  $post;
					$cssPrefix   = gutentor_dynamic_css()->css_prefix( $post );
					$singularCSS = gutentor_dynamic_css()->get_singular_dynamic_css( $post );
					if ( ! empty( $singularCSS ) && file_exists( $upload_dir['basedir'] . '/gutentor/p-' . $cssPrefix . '.css' ) ) {
						$css_info = get_post_meta( $post->ID, 'gutentor_css_info', true );
						wp_enqueue_style( 'gutentor-dynamic-' . $cssPrefix, trailingslashit( $upload_dir['baseurl'] ) . 'gutentor/p-' . $cssPrefix . '.css', false, isset( $css_info['saved_version'] ) ? $css_info['saved_version'] : '' );
						/*Lets fix RTL If needed*/
						if ( isset( $css_info['is_rtl'] ) && is_rtl() !== $css_info['is_rtl'] ) {
							gutentor_dynamic_css()->fix_rtl( $post->ID );
						}
					}
				} elseif ( is_archive() || is_home() || is_search() ) {
					global $wp_query;
					if ( isset( $wp_query->posts ) ) {
						foreach ( $wp_query->posts as $post ) {
							$cssPrefix   = gutentor_dynamic_css()->css_prefix( $post );
							$singularCSS = gutentor_dynamic_css()->get_singular_dynamic_css( $post );
							if ( ! empty( $singularCSS ) && file_exists( $upload_dir['basedir'] . '/gutentor/p-' . $cssPrefix . '.css' ) ) {
								$css_info = get_post_meta( $post->ID, 'gutentor_css_info', true );
								wp_enqueue_style( 'gutentor-dynamic-' . $cssPrefix, trailingslashit( $upload_dir['baseurl'] ) . 'gutentor/p-' . $cssPrefix . '.css', false, isset( $css_info['saved_version'] ) ? $css_info['saved_version'] : '' );
								$css_info = get_post_meta( $post->ID, 'gutentor_css_info', true );
								/*Lets fix RTL If needed*/
								if ( isset( $css_info['is_rtl'] ) && is_rtl() !== $css_info['is_rtl'] ) {
									gutentor_dynamic_css()->fix_rtl( $post->ID );
								}
							}
						}
					}
				}

				if ( current_theme_supports( 'widgets-block-editor' ) ) {
					$g_w_saved_css = get_option( 'gutentor_widget_dcss' );
					if ( is_array( $g_w_saved_css ) &&
						isset( $g_w_saved_css[ get_template() ] ) &&
						isset( $g_w_saved_css[ get_template() ]['css'] ) &&
						isset( $g_w_saved_css[ get_template() ]['blocks'] )
					) {
						$cssPrefix = get_template();
						if ( file_exists( $upload_dir['basedir'] . '/gutentor/w-' . $cssPrefix . '.css' ) ) {
							wp_enqueue_style(
								'gutentor-dynamic-w-' . $cssPrefix,
								trailingslashit( $upload_dir['baseurl'] ) . 'gutentor/w-' . $cssPrefix . '.css',
								false,
								isset( $g_w_saved_css[ get_template() ]['saved_version'] ) ? $g_w_saved_css[ get_template() ]['saved_version'] : ''
							);
							/*RTL fix not needed TODO*/
						}
					}
				}
			}
		}
	}
endif;

/**
 * Call Gutentor_Dynamic_CSS
 *
 * @since    1.0.0
 * @access   public
 */
if ( ! function_exists( 'gutentor_dynamic_css' ) ) {

	function gutentor_dynamic_css() {

		return Gutentor_Dynamic_CSS::instance();
	}
	gutentor_dynamic_css()->run();
}
