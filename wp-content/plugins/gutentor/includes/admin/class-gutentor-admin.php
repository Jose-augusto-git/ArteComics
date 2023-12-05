<?php
/**
 * Do things related with admin settings
 *
 * @since 1.0.0
 */

if ( ! class_exists( 'Gutentor_Admin' ) ) {
	/**
	 * Class Gutentor_Admin.
	 */
	class Gutentor_Admin extends Gutentor_Helper {

		protected static $page_slug = 'gutentor';

		public $tax_in_color = array();
		public $tax_in_image = array();
		public $taxonomies   = array();

		public function __construct() {

			add_action( 'admin_menu', array( __CLASS__, 'admin_pages' ) );
			add_action( 'admin_init', array( __CLASS__, 'redirect' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_js' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'admin_editor_scripts' ), '99' );

			/*Category/Taxonomy Term Meta*/
			$this->tax_in_color = gutentor_get_options( 'tax-in-color' );
			$this->tax_in_image = gutentor_get_options( 'tax-in-image' );

			if ( is_array( $this->tax_in_color ) ) {
				$this->taxonomies = $this->tax_in_color;
			}
			if ( is_array( $this->tax_in_image ) ) {
				$this->taxonomies = array_unique( array_merge( $this->taxonomies, $this->tax_in_image ) );
			}
			if ( ! empty( $this->taxonomies ) ) {
				foreach ( $this->taxonomies as $tax ) {
					add_action( $tax . '_add_form_fields', array( $this, 'taxonomy_edit_meta_field' ) );
					add_action( $tax . '_edit_form_fields', array( $this, 'taxonomy_edit_meta_field' ) );
					add_action( 'edited_' . $tax, array( $this, 'save_taxonomy_custom_meta' ) );
					add_action( 'create_' . $tax, array( $this, 'save_taxonomy_custom_meta' ) );

					add_filter( 'register_taxonomy_args', array( $this, 'add_taxonomy_args' ), 10, 2 );
				}
			}

			self::initialize_ajax();
		}

		/**
		 * Redirect to plugin page when plugin activated
		 *
		 * @since 1.0.0
		 */
		public static function redirect() {
			if ( get_option( '__gutentor_do_redirect' ) ) {
				update_option( '__gutentor_do_redirect', false );
				if ( ! is_multisite() ) {
					exit( wp_redirect( admin_url( 'admin.php?page=' . self::$page_slug ) ) );
				}
			}
		}

		/**
		 * Admin Page Menu and submenu page
		 *
		 * @since 1.0.0
		 */
		public static function admin_pages() {

			add_menu_page(
				'gutentor',
				esc_html__( 'Gutentor', 'gutentor' ),
				'manage_options',
				self::$page_slug,
				array( __CLASS__, 'getting_started_template' ),
				'data:image/svg+xml;base64,' . base64_encode(
					'<svg version="1.1" id="gray-logo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="44px" height="47.042px" viewBox="0 0 44 47.042" enable-background="new 0 0 44 47.042" xml:space="preserve">
<path fill="#9FA4A9" d="M42.397,13.536l-16.293-9.45c-0.732-0.424-1.935-0.427-2.667-0.006l-0.44,0.252
	c0.045,0.085,0.071,0.182,0.071,0.284v5.565c0,0.341-0.275,0.616-0.617,0.616h-5.565c-0.341,0-0.619-0.275-0.619-0.616V8.2
	l-1.199,0.69v6.151c0,0.382-0.31,0.69-0.689,0.69h-6.21c-0.38,0-0.688-0.309-0.688-0.69V13.25l-0.376,0.216
	c-0.734,0.422-1.338,1.461-1.339,2.309L5.76,18.553h3.42c0.327,0,0.593,0.265,0.593,0.594v5.348c0,0.329-0.266,0.596-0.593,0.596
	H5.748l-0.019,9.521c-0.001,0.849,0.597,1.89,1.33,2.313l16.295,9.452c0.732,0.424,1.935,0.428,2.669,0.005l16.332-9.388
	c0.732-0.421,1.336-1.461,1.337-2.311l0.039-18.834C43.732,15.003,43.134,13.961,42.397,13.536z M34.535,30.71l-9.631,5.758
	L15.086,31l-0.175-11.235l9.643-5.771l9.1,5.067l-1.393,2.499l-7.657-4.263l-6.807,4.074l0.122,7.935l6.933,3.86l6.82-4.08v-1.083
	l-6.103-0.122l0.059-2.861l8.906,0.179V30.71z"/>
<path fill="#9FA4A9" d="M5.866,11.788c0,0.111-0.09,0.203-0.201,0.203H3.849c-0.11,0-0.202-0.092-0.202-0.203V9.974
	c0-0.113,0.091-0.203,0.202-0.203h1.816c0.111,0,0.201,0.09,0.201,0.203V11.788z"/>
<path fill="#9FA4A9" d="M4.048,17.141c0,0.187-0.15,0.336-0.336,0.336H0.691c-0.187,0-0.336-0.149-0.336-0.336v-3.022
	c0-0.184,0.149-0.334,0.336-0.334h3.021c0.186,0,0.336,0.15,0.336,0.334V17.141z"/>
<rect x="8.79" y="9.445" fill="#9FA4A9" width="5.202" height="5.202"/>
<rect x="17.342" y="5.137" fill="#9FA4A9" width="4.82" height="4.819"/>
<path fill="#9FA4A9" d="M9.261,7.286c0,0.179-0.145,0.324-0.324,0.324H6.025C5.847,7.61,5.7,7.465,5.7,7.286V4.375
	c0-0.178,0.146-0.325,0.325-0.325h2.912c0.179,0,0.324,0.146,0.324,0.325V7.286z"/>
<path fill="#9FA4A9" d="M15.298,6.035c0,0.178-0.145,0.323-0.323,0.323h-2.913c-0.179,0-0.324-0.145-0.324-0.323V3.121
	c0-0.177,0.146-0.323,0.324-0.323h2.913c0.178,0,0.323,0.146,0.323,0.323V6.035z"/>
<path fill="#9FA4A9" d="M19.745,2.948c0,0.149-0.122,0.271-0.272,0.271h-2.455c-0.152,0-0.274-0.123-0.274-0.271V0.491
	c0-0.149,0.122-0.272,0.274-0.272h2.455c0.15,0,0.272,0.124,0.272,0.272V2.948z"/>
<rect x="4.154" y="19.683" fill="#9FA4A9" width="4.546" height="4.546"/>
</svg>'
				),
				110
			);

			add_submenu_page(
				'gutentor',
				esc_html__( 'Getting Started Page', 'gutentor' ),
				esc_html__( 'Getting Started', 'gutentor' ),
				'manage_options',
				self::$page_slug,
				array( __CLASS__, 'getting_started_template' )
			);

			add_submenu_page(
				'gutentor',
				esc_html__( 'Blocks', 'gutentor' ),
				esc_html__( 'Blocks', 'gutentor' ),
				'manage_options',
				'gutentor-blocks',
				array( __CLASS__, 'gutentor_blocks_template' )
			);
		}


		/**
		 * Enqueue styles & scripts for frontend & backend
		 *
		 * @access public
		 * @uses wp_enqueue_style
		 * @return void
		 * @since Gutentor 1.0.0
		 */
		public static function admin_scripts() {
			$screen              = get_current_screen();
			$admin_scripts_bases = array( 'toplevel_page_gutentor', 'gutentor_page_gutentor-blocks', 'gutentor_page_gutentor-settings', 'term', 'edit-tags' );
			if ( ! ( isset( $screen->base ) &&
				in_array( $screen->base, $admin_scripts_bases ) )
			) {
				return;
			}
			/*
			---------------------------------------------*
			* Register Style for Admin Page               *
			*---------------------------------------------*/
			$scripts = array(
				array(
					'handler'  => 'jquery-ui',
					'absolute' => true,
					'style'    => GUTENTOR_URL . 'assets/library/jquery-ui/jquery-ui' . '.min' . '.css',
				),
				array(
					'handler'    => 'gutentor-admin-build',
					'absolute'   => true,
					'script'     => GUTENTOR_URL . 'dist/admin.build.js',
					'dependency' => array( 'jquery', 'lodash', 'wp-api', 'wp-i18n', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-data', 'wp-editor', 'wp-edit-post', 'wp-element', 'wp-keycodes', 'wp-plugins', 'wp-rich-text', 'wp-viewport' ), // Dependencies, defined above,
				),
			);

			/*FontAwesome CSS*/
			if ( 4 == gutentor_get_options( 'fa-version' ) ) {
				wp_enqueue_style(
					'fontawesome', // Handle.
					GUTENTOR_URL . 'assets/library/font-awesome-4.7.0/css/font-awesome.min.css',
					array(),
					'4'
				);
			} else {
				wp_enqueue_style(
					'fontawesome', // Handle.
					GUTENTOR_URL . 'assets/library/fontawesome/css/all.min.css',
					array(),
					'5.12.0'
				);
			}

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_media();

			self::enqueue( $scripts );

			$localize = array(
				'edd'                => array(
					'active'   => gutentor_is_edd_active(),
					'review'   => gutentor_is_edd_review_active(),
					'wishlist' => gutentor_is_edd_wishlist_active(),
				),
				'fontAwesomeVersion' => gutentor_get_options( 'fa-version' ),
				'postFormats'        => gutentor_get_post_formats(),
				'gutentorPro'        => array(
					'active' => gutentor_pro_active(),
				),
			);
			wp_localize_script( 'gutentor-admin-build', 'gutentor', $localize );

		}


		/**
		 * Enqueue styles & scripts for frontend & backend
		 *
		 * @access public
		 * @uses wp_enqueue_style
		 * @return void
		 * @since Gutentor 1.0.0
		 */
		public static function admin_scripts_js() {
			$screen              = get_current_screen();
			$admin_scripts_bases = array( 'toplevel_page_gutentor', 'gutentor_page_gutentor-blocks', 'gutentor_page_gutentor-settings', 'gutentor_page_gutentor-pro-license', 'term', 'edit-tags' );
			$admin_scripts_bases = apply_filters( 'gutentor_admin_scripts_current_screen', $admin_scripts_bases );
			if ( ! ( isset( $screen->base ) &&
				(
						( in_array( $screen->base, $admin_scripts_bases ) ) ||
						( in_array( get_post_type(), array( 'gutentor-fonts', 'gutentor-icons' ) ) )
				)
			)
			) {
				return;
			}
			/*
			---------------------------------------------*
			* Register Style for Admin Page               *
			*---------------------------------------------*/
			$scripts = array(
				array(
					'handler'  => 'magnific-popup',
					'absolute' => true,
					'style'    => GUTENTOR_URL . 'assets/library/magnific-popup/magnific-popup' . '.min' . '.css',
				),
				array(
					'handler'    => 'gutentor-admin',
					'absolute'   => true,
					'style'      => GUTENTOR_URL . 'dist/blocks.admin.build.css',
					'dependency' => array( 'wp-edit-blocks' ),
				),
				array(
					'handler'    => 'gutentor-admin',
					'absolute'   => true,
					'script'     => GUTENTOR_URL . 'assets/js/admin-script' . GUTENTOR_SCRIPT_PREFIX . '.js',
					'dependency' => array( 'wp-color-picker' ),
				),
				array(
					'handler'  => 'magnific-popup',
					'absolute' => true,
					'script'   => GUTENTOR_URL . 'assets/library/magnific-popup/jquery.magnific-popup' . '.min' . '.js',
				),
			);

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_media();

			self::enqueue( $scripts );

			$localize = array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'gutentor-block-nonce' ),
			);
			wp_localize_script( 'gutentor-admin', 'GUTENTOR_SETTINGS', $localize );
		}

		/**
		 * Enqueue styles & scripts for frontend & backend
		 *
		 * @access public
		 * @uses wp_enqueue_style
		 * @return void
		 * @since Gutentor 1.0.0
		 */
		public static function admin_editor_scripts() {

			/*
			---------------------------------------------*
			 * Register Style for Admin Page               *
			 *---------------------------------------------*/
			$scripts = array(
				array(
					'handler'    => 'gutentor-block-deactivate',
					'absolute'   => true,
					'script'     => GUTENTOR_URL . 'assets/js/blocks-deactivate' . GUTENTOR_SCRIPT_PREFIX . '.js',
					'dependency' => array( 'wp-blocks' ),
				),
			);

			self::enqueue( $scripts );
			$localize = array(
				'status' => self::block_action(),
			);
			wp_localize_script( 'gutentor-block-deactivate', 'GUTENTOR_BLOCKS', $localize );

		}

		/**
		 * Render Getting Started Template
		 *
		 * @return void
		 */
		public static function getting_started_template() {
			require_once GUTENTOR_PATH . 'includes/admin/templates/getting-started-page.php';
		}

		/**
		 * Render Blocks Template
		 *
		 * @since 1.0.0
		 */
		public static function gutentor_blocks_template() {
			require_once GUTENTOR_PATH . 'includes/admin/templates/admin-page.php';
		}

		/**
		 * Initialize Ajax
		 */
		public static function initialize_ajax() {
			// Ajax requests.
			add_action( 'wp_ajax_gutentor_activate_block', array( __CLASS__, 'activate_block' ) );
			add_action( 'wp_ajax_gutentor_deactivate_block', array( __CLASS__, 'deactivate_block' ) );

			add_action( 'wp_ajax_gutentor_bulk_activate_blocks', array( __CLASS__, 'bulk_activate_blocks' ) );
			add_action( 'wp_ajax_gutentor_bulk_deactivate_blocks', array( __CLASS__, 'bulk_deactivate_blocks' ) );
		}

		public static function block_action( $action = 'get', $blocks = array() ) {

			$key = 'off-blocks';

			switch ( $action ) {
				case 'get':
					return self::get_option( $key, array() );
				case 'update':
					self::update_option( $key, $blocks );
					break;

			}
		}

		public static function is_block_active( $block_id ) {
			$blocks = self::block_action();
			if ( ! isset( $blocks[ $block_id ] ) || $blocks[ $block_id ] == $block_id ) {
				return true;
			} else {
				return false;
			}
		}

		public static function is_all_block_active() {
			$blocks = self::block_action();
			if ( is_array( $blocks ) && ! empty( $blocks ) ) {
				foreach ( $blocks as $b ) {
					if ( $b == 'disabled' ) {
						return false;
					}
				}
			}

			return true;
		}

		/**
		 * Activate block
		 */
		public static function activate_block() {

			check_ajax_referer( 'gutentor-block-nonce', 'nonce' );

			$block_id = sanitize_text_field( $_POST['block_id'] );
			$blocks   = self::block_action();
			if ( ! is_array( $blocks ) ) {
				$blocks = array();
			}
			$blocks[ $block_id ] = $block_id;
			$blocks              = array_map( 'esc_attr', $blocks );
			/*Update blocks.*/
			self::update_option( 'off-blocks', $blocks );

			die();
		}

		/**
		 * Deactivate block
		 */
		public static function deactivate_block() {

			check_ajax_referer( 'gutentor-block-nonce', 'nonce' );

			$block_id = sanitize_text_field( $_POST['block_id'] );
			$blocks   = self::block_action();
			if ( ! is_array( $blocks ) ) {
				$blocks = array();
			}
			$blocks[ $block_id ] = 'disabled';

			$blocks = array_map( 'esc_attr', $blocks );

			// Update blocks.
			self::block_action( 'update', $blocks );

			echo $block_id;

			die();
		}

		/**
		 * Activate all module
		 */
		public static function bulk_activate_blocks() {

			check_ajax_referer( 'gutentor-block-nonce', 'nonce' );

			// Get all blocks.
			$all_blocks = self::$block_list;
			$new_blocks = array();

			// Set all extension to enabled.
			foreach ( $all_blocks as $slug => $value ) {
				$_slug                = str_replace( 'gutentor/', '', $slug );
				$new_blocks[ $_slug ] = $_slug;
			}

			// Escape attrs.
			$new_blocks = array_map( 'esc_attr', $new_blocks );

			// Update new_extensions.
			self::block_action( 'update', $new_blocks );

			echo 'success';

			die();
		}

		/**
		 * Deactivate all module
		 */
		public static function bulk_deactivate_blocks() {

			check_ajax_referer( 'gutentor-block-nonce', 'nonce' );

			// Get all extensions.
			$old_blocks = self::$block_list;
			$new_blocks = array();

			// Set all extension to enabled.
			foreach ( $old_blocks as $slug => $value ) {
				$_slug                = str_replace( 'gutentor/', '', $slug );
				$new_blocks[ $_slug ] = 'disabled';
			}

			// Escape attrs.
			$new_blocks = array_map( 'esc_attr', $new_blocks );

			// Update new_extensions.

			self::update_option( 'off-blocks', $new_blocks );
			echo 'success';

			die();
		}

		public static function elements() {

			$gutentor_elements = array(
				'e1'      => array(
					'title'       => esc_html__( 'Advanced Text', 'gutentor' ),
					'description' => esc_html__( 'Insert text with advanced options ', 'gutentor' ),
				),
				'e2'      => array(
					'title'       => esc_html__( 'Button', 'gutentor' ),
					'description' => esc_html__( 'Prompt visitors to take action with attractive buttons.', 'gutentor' ),
				),
				'e3'      => array(
					'title'       => esc_html__( 'Counter', 'gutentor' ),
					'description' => esc_html__( ' Insert an animated number to display the counter number.', 'gutentor' ),
				),
				'divider' => array(
					'title'       => esc_html__( 'Divider', 'gutentor' ),
					'description' => esc_html__( 'Divider differentiate sections and elements with shape.', 'gutentor' ),
				),
				'e4'      => array(
					'title'       => esc_html__( 'Google Map', 'gutentor' ),
					'description' => esc_html__( 'Display a Google Map on your website with Google Map API.', 'gutentor' ),
				),
				'e5'      => array(
					'title'       => esc_html__( 'Icon', 'gutentor' ),
					'description' => esc_html__( 'Insert an icon to symbolize the text', 'gutentor' ),
				),
				'e6'      => array(
					'title'       => esc_html__( 'Image', 'gutentor' ),
					'description' => esc_html__( 'Insert an image to create extra value on the content.', 'gutentor' ),
				),
				'e7'      => array(
					'title'       => esc_html__( 'List', 'gutentor' ),
					'description' => esc_html__( 'Represent the paragraphs in tabbed styles', 'gutentor' ),
				),
				'e8'      => array(
					'title'       => esc_html__( 'Pricing', 'gutentor' ),
					'description' => esc_html__( 'Insert the pricing to showcase the price of the product.', 'gutentor' ),
				),
				'e9'      => array(
					'title'       => esc_html__( 'Progress Bar', 'gutentor' ),
					'description' => esc_html__( 'Showcase the progress of the work in an animated form.', 'gutentor' ),
				),
				'e10'     => array(
					'title'       => esc_html__( 'Rating', 'gutentor' ),
					'description' => esc_html__( 'Insert the rating element to represent the rating from 1-5 star.', 'gutentor' ),
				),
				'e0'      => array(
					'title'       => esc_html__( 'Simple Text', 'gutentor' ),
					'description' => esc_html__( 'Insert text with minimal options ', 'gutentor' ),
				),
				'e11'     => array(
					'title'       => esc_html__( 'Video Popup', 'gutentor' ),
					'description' => esc_html__( 'Insert video in your website.', 'gutentor' ),
				),

			);

			return apply_filters( 'gutentor_elements_in_admin_page', $gutentor_elements );
		}


		public static function modules() {

			$gutentor_modules = array(

				'm6'  => array(
					'title'       => esc_html__( 'Accordion ', 'gutentor' ),
					'description' => esc_html__( 'Design collapsable items and pin any Gutentor Elements in Accordion Body.', 'gutentor' ),
				),
				'm4'  => array(
					'title'       => esc_html__( 'Advanced Columns', 'gutentor' ),
					'description' => esc_html__( 'Insert advanced columns to create customizable columns within the page. ', 'gutentor' ),
				),
				'm1'  => array(
					'title'       => esc_html__( 'Button Group', 'gutentor' ),
					'description' => esc_html__( ' Insert button group and it allows to added unlimited buttons.', 'gutentor' ),
				),
				'm0'  => array(
					'title'       => esc_html__( 'Carousel', 'gutentor' ),
					'description' => esc_html__( 'Insert carousel column and add element inside the columns. ', 'gutentor' ),
				),
				'm3'  => array(
					'title'       => esc_html__( 'Container/Cover Block', 'gutentor' ),
					'description' => esc_html__( 'Insert container and add any block, it facilitates as like wrapper.', 'gutentor' ),
				),
				'm2'  => array(
					'title'       => esc_html__( 'Dynamic Columns', 'gutentor' ),
					'description' => esc_html__( 'Insert dynamic columns to insert unlimited predefined columns ', 'gutentor' ),
				),
				'm9'  => array(
					'title'       => esc_html__( 'Form Wrapper', 'gutentor' ),
					'description' => esc_html__( 'Use (contact form) shortcode and design form input field, text area and button according to your need.', 'gutentor' ),
				),
				'm11' => array(
					'title'       => esc_html__( 'Filter', 'gutentor' ),
					'description' => esc_html__( 'Filter block has module gallery all features with added primary/secondary filters items and searches filter. Filter block uses Module Gallery block as an inner block. ', 'gutentor' ),
				),
				'm10' => array(
					'title'       => esc_html__( 'Gallery', 'gutentor' ),
					'description' => esc_html__( 'Advanced gallery block which let you add any Gutentor Elements as gallery content. The Module Gallery Block also allows customizing the popup content as image and video.', 'gutentor' ),
				),
				'm8'  => array(
					'title'       => esc_html__( 'Icon Group ', 'gutentor' ),
					'description' => esc_html__( 'Insert multiple icons in the Icon Group and create beautiful social profile links and icon designs.', 'gutentor' ),
				),
				'm12' => array(
					'title'       => esc_html__( 'Quote', 'gutentor' ),
					'description' => esc_html__( 'Quote is the collection of element blocks which create a adorable quote design.', 'gutentor' ),
				),
				'm5'  => array(
					'title'       => esc_html__( 'Slider ', 'gutentor' ),
					'description' => esc_html__( 'Insert slider container and add elements within the container.', 'gutentor' ),
				),
				'm7'  => array(
					'title'       => esc_html__( 'Tabs ', 'gutentor' ),
					'description' => esc_html__( 'Add tab items with tab title and content, allowed to add any gutentor element inside it.', 'gutentor' ),
				),
				'm13' => array(
					'title'       => esc_html__( 'Table of Contents ', 'gutentor' ),
					'description' => esc_html__( 'Table of Contents facilitates to access large contents of post/page through the heading of the contents.', 'gutentor' ),
				),

			);

			if ( gutentor_pro_active() ) {
				$gutentor_modules['m14'] = array(
					'title'       => esc_html__( 'Advance Show More', 'gutentor' ),
					'description' => esc_html__( 'Advance Show More is extended version of show more widget with innerblocks.', 'gutentor' ),
				);
				$gutentor_modules['m15'] = array(
					'title'       => esc_html__( 'PopOver', 'gutentor' ),
					'description' => esc_html__( 'PopOver is extended version of show more widget with innerblocks.', 'gutentor' ),
				);
				$gutentor_modules['m16'] = array(
					'title'       => esc_html__( 'Wrapper', 'gutentor' ),
					'description' => esc_html__( 'Use it as wrapper block for gutentor elements, and other blocks, control width and design beautiful block section.', 'gutentor' ),
				);

			}

			return apply_filters( 'gutentor_modules_in_admin_page', $gutentor_modules );
		}

		public static function posts() {

			$gutentor_posts = array(

				'p4' => array(
					'title'       => esc_html__( 'Advanced Post (Type)', 'gutentor' ),
					'description' => esc_html__( 'Combination of multiple blocks like Post Module, Post Header, Post Footer.', 'gutentor' ),
				),
				'p6' => array(
					'title'       => esc_html__( 'Duplex Post (Type)', 'gutentor' ),
					'description' => esc_html__( 'Design post in two different ways – Feature Post and Normal Post, post design will be more beautiful.', 'gutentor' ),
				),
				'p1' => array(
					'title'       => esc_html__( 'Post (Type)', 'gutentor' ),
					'description' => esc_html__( 'Display Blog post with list and grid view from post module.', 'gutentor' ),
				),
				'p3' => array(
					'title'       => esc_html__( 'Post (Type) Carousel', 'gutentor' ),
					'description' => esc_html__( 'Display post or post type in carousel mode.', 'gutentor' ),
				),
				'p2' => array(
					'title'       => esc_html__( 'Post (Type) Feature', 'gutentor' ),
					'description' => esc_html__( 'Display post with news and magazine style.', 'gutentor' ),
				),
				'p5' => array(
					'title'       => esc_html__( 'Post (Type) News Ticker', 'gutentor' ),
					'description' => esc_html__( 'Display Marquee, Vertical, Horizontal or Typewriter News Ticker from post or post type.', 'gutentor' ),
				),
			);
			return apply_filters( 'gutentor_posts_in_admin_page', $gutentor_posts );
		}

		public static function terms() {

			$gutentor_terms = array(
				't1' => array(
					'title'       => esc_html__( 'Term (Category)', 'gutentor' ),
					'description' => esc_html__( 'Similar like Post (Type) block but in Term (Category) you can customize any term with beautiful design.', 'gutentor' ),
				),
				't2' => array(
					'title'       => esc_html__( 'Term (Category) Feature', 'gutentor' ),
					'description' => esc_html__( '38 unique and lovely templates to showcase your Category and Term similar to Post (Type) Feature. Very useful to Blog, Magazine and E-commerce sites.', 'gutentor' ),
				),
				't3' => array(
					'title'       => esc_html__( 'Term Category Carousel', 'gutentor' ),
					'description' => esc_html__( 'Term (Category) Carousel Block use Term (Category) as an inner block. You will find almost every options to customize your Term (Category) Carousel.', 'gutentor' ),
				),
			);
			return apply_filters( 'gutentor_terms_in_admin_page', $gutentor_terms );
		}

		public static function content() {

			$gutentor_block_collection = array(
				'about-block'     => array(
					'title'       => esc_html__( 'About Widget', 'gutentor' ),
					'description' => esc_html__( 'The About Widget gives short description related to product, person or any items with a large section of a photo, title, description, and button with customise setting and different templates.', 'gutentor' ),
				),
				'accordion'       => array(
					'title'       => esc_html__( 'Accordion Widget', 'gutentor' ),
					'description' => esc_html__( 'The Accordion Widget helps you to display information in collapsible rows with title ,description and button. Generally this block can be helpful for display FAQ and other informative message. ', 'gutentor' ),
				),
				'list'            => array(
					'title'       => esc_html__( 'Advanced List Widget', 'gutentor' ),
					'description' => esc_html__( 'Not just a regular list but create an awesome list by adding image and icon on the advanced list widget.', 'gutentor' ),
				),
				'author-profile'  => array(
					'title'       => esc_html__( 'Author Widget', 'gutentor' ),
					'description' => esc_html__( 'The Author Widget allows user to display information related to author with title, description and button', 'gutentor' ),
				),
				'blog-post'       => array(
					'title'       => esc_html__( 'Post Widget', 'gutentor' ),
					'description' => esc_html__( 'The Post Widget block display collection of posts with different setting related to post items and many more templates.', 'gutentor' ),
				),
				'call-to-action'  => array(
					'title'       => esc_html__( 'Call to Action Widget', 'gutentor' ),
					'description' => esc_html__( 'The Call to Action Widget helps user to trigger certain action with collection of button.It is usable to link learn more, download, buy etc.', 'gutentor' ),
				),
				'content-box'     => array(
					'title'       => esc_html__( 'Content Widget', 'gutentor' ),
					'description' => esc_html__( 'Content Widget block is useful to preset plain title, content and button without image and icon.', 'gutentor' ),
				),
				'count-down'      => array(
					'title'       => esc_html__( 'Countdown Widget', 'gutentor' ),
					'description' => esc_html__( 'This Widget is useful for setting a countdown feature on your website. It is extremely helpful for any event show related website.', 'gutentor' ),
				),
				'counter-box'     => array(
					'title'       => esc_html__( 'Counter Widget', 'gutentor' ),
					'description' => esc_html__( 'Counter Widget represents the facts and figure related to any product, item or any product with cool animation , features and many more fascinated templates.', 'gutentor' ),
				),
				'featured-block'  => array(
					'title'       => esc_html__( 'Featured Widget', 'gutentor' ),
					'description' => esc_html__( 'The Featured Widget displays large section with a photo, title, description, and button with awesome template. Generally, it is useful to header part of the site.', 'gutentor' ),
				),
				'gallery'         => array(
					'title'       => esc_html__( 'Gallery Widget', 'gutentor' ),
					'description' => esc_html__( 'The Gallery Widget allows user to create mesmerizing gallery of image with caption which is perfect showcase of image of portfolio, services or product.', 'gutentor' ),
				),
				'google-map'      => array(
					'title'       => esc_html__( 'Google Map Widget', 'gutentor' ),
					'description' => esc_html__( 'Google Map Widget facilitates user to display the location of organization, company or any place with advanced features of google map. ', 'gutentor' ),
				),
				'icon-box'        => array(
					'title'       => esc_html__( 'Icon Widget', 'gutentor' ),
					'description' => esc_html__( 'Icon Widget facilitates to show off a short brief about the users services with Font Awesome icons with cool templates and features.', 'gutentor' ),
				),
				'image-box'       => array(
					'title'       => esc_html__( 'Image Widget', 'gutentor' ),
					'description' => esc_html__( 'The Image Widget display information with image, title, description and button which modify by available features and cool templates.', 'gutentor' ),
				),
				'image-slider'    => array(
					'title'       => esc_html__( 'Image Slider Widget', 'gutentor' ),
					'description' => esc_html__( 'The Image Slider Widget display adorable slider with image, title, description and button which modify by available features and cool templates.', 'gutentor' ),
				),
				'notification'    => array(
					'title'       => esc_html__( 'Notification Widget', 'gutentor' ),
					'description' => esc_html__( 'The Notification Widget facilitates to show different type of information(Warning,Error,Success).', 'gutentor' ),
				),
				'opening-hours'   => array(
					'title'       => esc_html__( 'Opening Hours Widget', 'gutentor' ),
					'description' => esc_html__( 'The Opening Hours Widget depicts the information related to opening schedule of any organization.', 'gutentor' ),
				),
				'pricing'         => array(
					'title'       => esc_html__( 'Pricing Widget', 'gutentor' ),
					'description' => esc_html__( 'The Pricing Widget represents the pricing details of any commodity with number of customize features.', 'gutentor' ),
				),
				'progress-bar'    => array(
					'title'       => esc_html__( 'Progress Bar Widget', 'gutentor' ),
					'description' => esc_html__( 'The progress bar Widget facilitates user create a customizable bar and/or circle progress counter to represent percentage values.', 'gutentor' ),
				),
				'restaurant-menu' => array(
					'title'       => esc_html__( 'Restaurant Menu Widget', 'gutentor' ),
					'description' => esc_html__( 'The Restaurant Menu Widget represents the information items and recipes available in restaurant with different features and cool templates.', 'gutentor' ),
				),
				'show-more'       => array(
					'title'       => esc_html__( 'Show More Widget', 'gutentor' ),
					'description' => esc_html__( 'Add small text to show details text with show more button.', 'gutentor' ),
				),
				'social'          => array(
					'title'       => esc_html__( 'Social links Widget', 'gutentor' ),
					'description' => esc_html__( 'The Social links Widget displays the social networks page on website with different templates and a number of features.', 'gutentor' ),
				),
				'tabs'            => array(
					'title'       => esc_html__( 'Tabs Widget', 'gutentor' ),
					'description' => esc_html__( 'The Tab Widget facilitates user to display content in a fully tabbed UX which contains title, description and buttons with number of templates.', 'gutentor' ),
				),
				'team'            => array(
					'title'       => esc_html__( 'Team Widget', 'gutentor' ),
					'description' => esc_html__( 'With the team Widget users can create an attractive and sophisticated team section where they can represent the team members of their company in a professional way.', 'gutentor' ),
				),
				'testimonial'     => array(
					'title'       => esc_html__( 'Testimonial Widget', 'gutentor' ),
					'description' => esc_html__( 'The Testimonial Widget display the feedback or quotation given by your user which helps site visitor to trust on your product, services.', 'gutentor' ),
				),
				'timeline'        => array(
					'title'       => esc_html__( 'Timeline Widget', 'gutentor' ),
					'description' => esc_html__( 'The Timeline Widget has ability to represents the user information or events in chronological order with different styles.  ', 'gutentor' ),
				),
				'video-popup'     => array(
					'title'       => esc_html__( 'Video Popup Widget', 'gutentor' ),
					'description' => esc_html__( 'The Video Popup Widget display video from youtube link or custom uploaded video in popup mode with number of styles ,video control.', 'gutentor' ),
				),
			);

			return apply_filters( 'gutentor_block_in_admin_page', $gutentor_block_collection );
		}

		/*Taxonomy Fields*/
		public function taxonomy_edit_meta_field( $term ) {

			// Retrieve the existing value(s) for this meta field.
			$gutentor_meta    = $term && ! empty( $term->term_id ) ? get_term_meta( $term->term_id, 'gutentor_meta', true ) : false;
			$bg               = isset( $gutentor_meta['bg-color'] ) ? $gutentor_meta['bg-color'] : '';
			$hover_bg         = isset( $gutentor_meta['bg-hover-color'] ) ? $gutentor_meta['bg-hover-color'] : '';
			$text_color       = isset( $gutentor_meta['text-color'] ) ? $gutentor_meta['text-color'] : '';
			$hover_text_color = isset( $gutentor_meta['text-hover-color'] ) ? $gutentor_meta['text-hover-color'] : '';
			$f_image          = isset( $gutentor_meta['featured-image'] ) ? $gutentor_meta['featured-image'] : '';

			/*backward compatibility*/
			if ( isset( $term->term_id ) && get_option( 'gutentor-cat-' . $term->term_id ) ) {
				$gutentor_prev_options = get_option( 'gutentor-cat-' . $term->term_id );
				$gutentor_prev_options = json_decode( $gutentor_prev_options, true );
				if ( isset( $gutentor_prev_options['background-color'] ) && ! empty( $gutentor_prev_options['background-color'] ) ) {
					$bg = $gutentor_prev_options['background-color'];
				}
				if ( isset( $gutentor_prev_options['background-hover-color'] ) && ! empty( $gutentor_prev_options['background-hover-color'] ) ) {
					$hover_bg = $gutentor_prev_options['background-hover-color'];
				}
				if ( isset( $gutentor_prev_options['text-color'] ) && ! empty( $gutentor_prev_options['text-color'] ) ) {
					$text_color = $gutentor_prev_options['text-color'];
				}
				if ( isset( $gutentor_prev_options['text-hover-color'] ) && ! empty( $gutentor_prev_options['text-hover-color'] ) ) {
					$hover_text_color = $gutentor_prev_options['text-hover-color'];
				}
			}

			if (
				(
					$term &&
					! empty( $term->term_id ) &&
					is_array( $this->tax_in_color ) && in_array( $term->taxonomy, $this->tax_in_color )
				)
				||
				(
					isset( $_GET['taxonomy'] ) &&
					is_array( $this->tax_in_color ) && in_array( $_GET['taxonomy'], $this->tax_in_color )
				)
			) {
				?>
				<tr class="gutentor-fields">
					<td><h4><?php esc_html_e( 'Background Color', 'gutentor' ); ?></h4></td>
					<td class="form-field gutentor-fields">
						<label for="gutentor_meta[bg-color]"><?php esc_html_e( 'Normal', 'gutentor' ); ?></label>
						<input type="text" value="<?php echo esc_attr( $bg ); ?>" id="gutentor_meta[bg-color]" name="gutentor_meta[bg-color]" class="gutentor-color-picker" data-rgba="1"/>

					</td>
				</tr>
				<tr class="form-field gutentor-fields">
					<td></td>
					<td>
						<label for="gutentor_meta[bg-hover-color]"><?php esc_html_e( 'Hover', 'gutentor' ); ?></label>
						<input type="text" value="<?php echo esc_attr( $hover_bg ); ?>" id="gutentor_meta[bg-hover-color]" name="gutentor_meta[bg-hover-color]" class="gutentor-color-picker" data-rgba="1" />
					</td>
				</tr>
				<tr>
					<td><h4><?php esc_html_e( 'Text Color', 'gutentor' ); ?></h4></td>
					<td class="form-field gutentor-fields">
						<label for="gutentor_meta[text-color]"><?php esc_html_e( 'Normal', 'gutentor' ); ?></label>
						<input type="text" value="<?php echo esc_attr( $text_color ); ?>" id="gutentor_meta[text-color]" name="gutentor_meta[text-color]" class="gutentor-color-picker" data-rgba="1"/>

					</td>
				</tr>
				<tr class="form-field gutentor-fields">
					<td></td>
					<td>
						<label for="gutentor_meta[text-hover-color]"><?php esc_html_e( 'Hover', 'gutentor' ); ?></label>
						<input type="text" value="<?php echo esc_attr( $hover_text_color ); ?>" id="gutentor_meta[text-hover-color]" name="gutentor_meta[text-hover-color]" class="gutentor-color-picker" data-rgba="1" />
					</td>
				</tr>
				<?php
			}

			if (
				(
					$term &&
					(
							! empty( $term->term_id ) &&
							(
									$term->taxonomy !== 'product_cat' &&
									(
											is_array( $this->tax_in_image ) && in_array( $term->taxonomy, $this->tax_in_image )
									)
						)
					)
				)
				||
				(
					isset( $_GET['taxonomy'] ) &&
					is_array( $this->tax_in_image ) && in_array( $_GET['taxonomy'], $this->tax_in_image )
				)
			) {
				?>
				<tr>
					<td>
						<label for="gutentor_meta[featured-image]"><h4><?php esc_html_e( 'Featured Image', 'gutentor' ); ?></h4></label>
					</td>
					<td>
						<div class="form-field gutentor-fields">
							<?php
							$button_text  = __( 'Select Image', 'gutentor' );
							$upload_title = __( 'Add Image', 'gutentor' );
							$preview      = '';
							if ( ! empty( $f_image ) ) {
								$attachment   = wp_get_attachment_image_src( $f_image, 'thumbnail' );
								$preview      = $attachment[0];
								$upload_title = __( 'Change Image', 'gutentor' );
							}
							$hidden  = ( empty( $f_image ) ) ? ' hidden' : '';
							$output  = "<div class='gutentor-img-preview" . esc_attr( $hidden ) . "'><div class='gutentor-img-wrap'><i class='dashicons dashicons-no gutentor-clear-img'></i><a data-button-text='" . esc_attr( $button_text ) . "' data-title='" . esc_attr( $upload_title ) . "' href='#' class='gutentor-img-uploader-open'><img src='" . esc_url( $preview ) . "' /></a></div></div>";
							$output .= "<a href='#' class='button button-primary gutentor-img-uploader-open' data-button-text='" . esc_attr( $button_text ) . "' data-title='" . esc_attr( $upload_title ) . "'>" . esc_html( $upload_title ) . '</a>';

							$output .= '<input type="hidden" value="' . esc_attr( $f_image ) . '" id="gutentor_meta[featured-image]" name="gutentor_meta[featured-image]" />';
							echo $output;
							?>
						</div>
					</td>
				</tr>
				<?php
			}
			?>
			<?php wp_nonce_field( 'gutentor_update_term_meta', 'gutentor_term_meta_nonce' ); ?>
			<?php
		}

		public function save_taxonomy_custom_meta( $term_id ) {

			if (
				isset( $_POST['gutentor_meta'] ) &&
				is_array( $_POST['gutentor_meta'] ) &&
				! empty( $_POST['gutentor_term_meta_nonce'] ) &&
				wp_verify_nonce( $_POST['gutentor_term_meta_nonce'], 'gutentor_update_term_meta' )
			) {

				$m_value = array();
				foreach ( $_POST['gutentor_meta'] as $key => $value ) {
					$key = sanitize_key( $key );
					switch ( $key ) {
						case 'bg-color':
						case 'bg-hover-color':
						case 'text-color':
						case 'text-hover-color':
							$m_value[ $key ] = gutentor_sanitize_color( $value );
							break;
						case 'featured-image':
							$m_value[ $key ] = absint( $value );
							break;
						default:
							$m_value[ $key ] = sanitize_text_field( $value );
							break;
					}
				}

				update_term_meta( $term_id, 'gutentor_meta', $m_value );
				/*backward compatibility*/
				if ( get_option( 'gutentor-cat-' . $term_id ) ) {
					delete_option( 'gutentor-cat-' . $term_id );
				}
			}
		}


		public function add_taxonomy_args( $args, $taxonomy_name ) {

			if ( is_array( $this->taxonomies ) &&
				in_array( $taxonomy_name, $this->taxonomies )
			) {
				$args['show_in_rest'] = true;
			}
			return $args;
		}
	}
}
new Gutentor_Admin();
