<?php

/**
 * The Gutentor theme hooks callback functionality of the plugin.
 *
 * @link       https://www.gutentor.com/
 * @since      1.0.0
 *
 * @package    Gutentor
 */

/**
 * The Gutentor theme hooks callback functionality of the plugin.
 *
 * Since Gutentor theme is hooks base theme, this file is main callback to add/remove/edit the functionality of the Gutentor Plugin
 *
 * @package    Gutentor
 * @author     Gutentor <info@gutentor.com>
 */
class Gutentor_Hooks {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Library loaded
	 * Check it Library Loaded
	 *
	 * @since    2.1.2
	 * @access   public
	 * @var      string    $library_loaded
	 */
	public $library_loaded = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {}

	/**
	 * Main Gutentor_Hooks Instance
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @return object $instance Gutentor_Hooks Instance
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null === $instance ) {
			$instance              = new Gutentor_Hooks();
			$instance->plugin_name = GUTENTOR_PLUGIN_NAME;
			$instance->version     = GUTENTOR_VERSION;
		}

		// Always return the instance.
		return $instance;
	}

	/**
	 * Get Thumbnail all sizes.
	 *
	 * @since 2.0.0
	 */
	public static function get_thumbnail_all_sizes() {

		$sizes       = get_intermediate_image_sizes();
		$image_sizes = array();

		$image_sizes[] = array(
			'value' => 'full',
			'label' => esc_html__( 'Full', 'gutentor' ),
		);

		foreach ( $sizes as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
				$image_sizes[] = array(
					'value' => $size,
					'label' => ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
				);
			}
		}
		return $image_sizes;
	}

	/**
	 * Get Thumbnail all sizes.
	 *
	 * @since 2.0.0
	 */
	public static function get_updated_thumbnail_all_sizes() {

		$sizes       = get_intermediate_image_sizes();
		$image_sizes = array();

		$image_sizes[] = array(
			'value' => 'full',
			'label' => esc_html__( 'Full', 'gutentor' ),
		);

		foreach ( $sizes as $size ) {
			$image_sizes[] = array(
				'value' => $size,
				'label' => ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
			);
		}
		return $image_sizes;
	}

	/**
	 * Callback functions for block_categories,
	 * Adding Block Categories
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array $categories
	 * @return array
	 */
	public function add_block_categories( $categories ) {

		return array_merge(
			array(
				array(
					'slug'  => 'gutentor-elements',
					'title' => __( 'Gutentor Elements', 'gutentor' ),
				),
				array(
					'slug'  => 'gutentor-modules',
					'title' => __( 'Gutentor Modules', 'gutentor' ),
				),
				array(
					'slug'  => 'gutentor-posts',
					'title' => __( 'Gutentor Posts', 'gutentor' ),
				),
				array(
					'slug'  => 'gutentor-terms',
					'title' => __( 'Gutentor Terms', 'gutentor' ),
				),
				array(
					'slug'  => 'gutentor',
					'title' => __( 'Gutentor Widgets', 'gutentor' ),
				),
			),
			$categories
		);
	}

	/**
	 * Callback functions for after_setup_theme,
	 * Add Gutentor global color palatte
	 *
	 * @since    3.0.0
	 * @access   public
	 *
	 * @param null
	 * @return void
	 */
	public function add_color_palette() {
		$global_colors = gutentor_get_options( 'color-palettes' );
		if ( ! empty( $global_colors ) ) {

			// Get the current set of colors.
			$colors = get_theme_support( 'editor-color-palette' );
			if ( isset( $colors[0] ) ) {
				$colors = $colors[0];
			}

			// If no colors, create defaults.
			if ( empty( $colors ) ) {
				$colors = array(
					array(
						'name'  => __( 'Black', 'gutentor' ),
						'slug'  => 'black',
						'color' => '#000000',
					),
					array(
						'name'  => __( 'Dark Gray', 'gutentor' ),
						'slug'  => 'dark-gray',
						'color' => '#A9A9A9',
					),
					array(
						'name'  => __( 'Silver', 'gutentor' ),
						'slug'  => 'silver',
						'color' => '#C0C0C0',
					),
					array(
						'name'  => __( 'White Smoke', 'gutentor' ),
						'slug'  => 'white-smoke',
						'color' => '#F5F5F5',
					),
					array(
						'name'  => __( 'White', 'gutentor' ),
						'slug'  => 'white',
						'color' => '#ffffff',
					),
					array(
						'name'  => __( 'Pink', 'gutentor' ),
						'slug'  => 'pink',
						'color' => '#FFC0CB',
					),
					array(
						'name'  => __( 'LightPink', 'gutentor' ),
						'slug'  => 'light-pink',
						'color' => '#FFB6C1',
					),
					array(
						'name'  => __( 'Orchid', 'gutentor' ),
						'slug'  => 'orchid',
						'color' => '#DA70D6',
					),
					array(
						'name'  => __( 'Violet', 'gutentor' ),
						'slug'  => 'violet',
						'color' => '#EE82EE',
					),
					array(
						'name'  => __( 'Sky Blue', 'gutentor' ),
						'slug'  => 'sky-blue',
						'color' => '#87CEEB',
					),
					array(
						'name'  => __( 'Light Cyan', 'gutentor' ),
						'slug'  => 'light-cyan',
						'color' => '#E0FFFF',
					),
					array(
						'name'  => __( 'PaleGreen', 'gutentor' ),
						'slug'  => 'pale-green',
						'color' => '#98FB98',
					),
					array(
						'name'  => __( 'Blue', 'gutentor' ),
						'slug'  => 'blue',
						'color' => '#0000FF',
					),
					array(
						'name'  => __( 'Navy', 'gutentor' ),
						'slug'  => 'navy',
						'color' => '#000080',
					),
					array(
						'name'  => __( 'Orange', 'gutentor' ),
						'slug'  => 'orange',
						'color' => '#FFA500',
					),
					array(
						'name'  => __( 'Dark Orange	', 'gutentor' ),
						'slug'  => 'dark-orange	',
						'color' => '#FF8C00',
					),
					array(
						'name'  => __( 'Tomato	', 'gutentor' ),
						'slug'  => 'tomato',
						'color' => '#FF6347',
					),
					array(
						'name'  => __( 'Maroon	', 'gutentor' ),
						'slug'  => 'maroon',
						'color' => '#800000',
					),

				);
			}

			if ( empty( $global_colors ) ) {
				$global_colors = array();
			}

			// Append our global colors with the theme/default ones.
			if ( is_array( $global_colors ) ) {
				$colors = array_merge( $colors, $global_colors );
			}
			add_theme_support( 'editor-color-palette', $colors );
		}
	}

	/**
	 * Callback functions for init,
	 * Register scripts and styles
	 *
	 * @since    2.1.2
	 * @access   public
	 *
	 * @param null
	 * @return void
	 */
    function register_script_style(){ // phpcs:ignore
		/*Animate CSS*/
		wp_register_style(
			'animate',
			GUTENTOR_URL . 'assets/library/animatecss/animate.min.css',
			array(),
			'3.7.2'
		);

		/*CountUP JS*/
		wp_register_script(
			'countUp', // Handle.
			GUTENTOR_URL . 'assets/library/countUp/countUp.min.js',
			array( 'jquery' ), // Dependencies.
			'1.9.3', // Version.
			true // Enqueue the script in the footer.
		);

		/*flexMenu*/
		wp_register_script(
			'flexMenu', // Handle.
			GUTENTOR_URL . 'assets/library/flexMenu/flexmenu.min.js',
			array( 'jquery' ), // Dependencies
			'1.6.2', // Version: File modification time.
			true // Enqueue the script in the footer.
		);

		/*FontAwesome CSS*/
		if ( 4 == gutentor_get_options( 'fa-version' ) ) {
			wp_register_style(
				'fontawesome', // Handle.
				GUTENTOR_URL . 'assets/library/font-awesome-4.7.0/css/font-awesome.min.css',
				array(),
				'4'
			);
		} else {
			wp_register_style(
				'fontawesome', // Handle.
				GUTENTOR_URL . 'assets/library/fontawesome/css/all.min.css',
				array(),
				'5.12.0'
			);
		}

		/*Isotope Js*/
		wp_register_script(
			'isotope', // Handle.
			GUTENTOR_URL . 'assets/library/isotope/isotope.pkgd.min.js',
			array( 'jquery' ), // Dependencies, defined above.
			'3.0.6', // Version: File modification time.
			true // Enqueue the script in the footer.
		);

		/*jquery-easypiechart Js*/
		wp_register_script(
			'jquery-easypiechart', // Handle.
			GUTENTOR_URL . 'assets/library/jquery-easypiechart/jquery.easypiechart.min.js',
			array( 'jquery' ), // Dependencies, defined above.
			'2.1.7', // Version: File modification time.
			true // Enqueue the script in the footer.
		);

		/*Magnific Popup CSS*/
		wp_register_style(
			'magnific-popup',
			GUTENTOR_URL . 'assets/library/magnific-popup/magnific-popup.min.css',
			array(),
			'1.8.0'
		);
		wp_style_add_data( 'magnific-popup', 'rtl', 'replace' );

		/*Magnific Popup JS*/
		wp_register_script(
			'magnific-popup', // Handle.
			GUTENTOR_URL . 'assets/library/magnific-popup/jquery.magnific-popup.min.js',
			array( 'jquery' ), // Dependencies, defined above.
			'1.1.0', // Version: File modification time.
			true // Enqueue the script in the footer.
		);

		/*Slick CSS*/
		wp_register_style(
			'slick',
			GUTENTOR_URL . 'assets/library/slick/slick.min.css',
			array(),
			'1.8.1'
		);

		/*Slick JS*/
		wp_register_script(
			'slick', // Handle.
			GUTENTOR_URL . 'assets/library/slick/slick.min.js',
			array( 'jquery' ), // Dependencies, defined above.
			'1.8.1', // Version: File modification time.
			true // Enqueue the script in the footer.
		);

		/*sticky sidebar*/
		wp_register_script(
			'theia-sticky-sidebar', // Handle.
			GUTENTOR_URL . 'assets/library/theia-sticky-sidebar/theia-sticky-sidebar.min.js',
			array( 'jquery' ), // Dependencies
			'4.0.1', // Version
			true // Enqueue the script in the footer.
		);

		/*Wow js*/
		wp_register_script(
			'wow', // Handle.
			GUTENTOR_URL . 'assets/library/wow/wow.min.js',
			array( 'jquery' ), // Dependencies
			'1.2.1', // Version: File modification time.
			true // Enqueue the script in the footer.
		);

		/*AcmeTicker*/
		wp_register_script(
			'acmeticker', // Handle.
			GUTENTOR_URL . 'assets/library/acmeticker/acmeticker.min.js',
			array( 'jquery' ), // Dependencies
			'1.0.0', // Version
			true // Enqueue the script in the footer.
		);
		/* Wpness Grid Styles*/
		wp_register_style(
			'wpness-grid',
			GUTENTOR_URL . 'assets/library/wpness-grid/wpness-grid' . GUTENTOR_SCRIPT_PREFIX . '.css',
			array(),
			'1.0.0'
		);
		/*
		Gutentor Specific CSS/JS */
		wp_register_style(
			'gutentor', // Handle.
			GUTENTOR_URL . 'dist/blocks.style.build.css',
			array( 'wp-editor' ), // Dependency to include the CSS after it.
			GUTENTOR_VERSION // Version: File modification time.
		);

		/*Gutentor Woo CSS/JS*/
		wp_register_style(
			'gutentor-woo', // Handle.
			GUTENTOR_URL . 'dist/gutentor-woocommerce.css',
			array( 'wp-editor' ), // Dependency to include the CSS after it.
			GUTENTOR_VERSION // Version: File modification time.
		);

		/*Gutentor Edd CSS/JS*/
		wp_register_style(
			'gutentor-edd', // Handle.
			GUTENTOR_URL . 'dist/gutentor-edd.css',
			array( 'wp-editor' ), // Dependency to include the CSS after it.
			GUTENTOR_VERSION // Version: File modification time.
		);

		/*
			Google Map JS
			Load Frontend only
			Used By:
			gutentor/google-map
			gutentor/e4
			*/
		// Get the API key
		if ( gutentor_get_options( 'map-api' ) ) {
			$apikey = gutentor_get_options( 'map-api' );
		} else {
			$apikey = false;
		}

		// Don't output anything if there is no API key.
		if ( ! ( null === $apikey || empty( $apikey ) ) ) {
			wp_register_script(
				'gutentor-google-maps',
				GUTENTOR_URL . 'assets/js/google-map-loader' . GUTENTOR_SCRIPT_PREFIX . '.js',
				array( 'jquery' ), // Dependencies, defined above.
				'1.0.0',
				true
			);

			wp_register_script(
				'google-maps',
				'https://maps.googleapis.com/maps/api/js?key=' . $apikey . '&libraries=places&callback=initMapScript',
				array( 'gutentor-google-maps' ),
				'1.0.0',
				true
			);
		}

		wp_register_script(
			'gutentor-block', // Handle.
			GUTENTOR_URL . 'assets/js/gutentor' . GUTENTOR_SCRIPT_PREFIX . '.js',
			array( 'jquery' ), // Dependencies, defined above.
			GUTENTOR_VERSION, // Version: File modification time.
			true // Enqueue the script in the footer.
		);
		/*CSS for default/popular themes*/
		$templates        = array( 'twentynineteen', 'twentytwenty', 'generatepress', 'astra' );
		$current_template = get_template();
		if ( in_array( $current_template, $templates ) ) {
			wp_register_style(
				'gutentor-theme-' . esc_attr( $current_template ), // Handle.
				GUTENTOR_URL . 'dist/gutentor-' . esc_attr( $current_template ) . '.css',
				array(), // Dependency to include the CSS after it.
				GUTENTOR_VERSION // Version: File modification time.
			);
			wp_style_add_data( 'gutentor-theme-' . esc_attr( $current_template ), 'rtl', 'replace' );
		}
	}

	/**
	 * Load scripts and styles
	 *
	 * @since    2.1.2
	 * @access   public
	 *
	 * @param null
	 * @return void
	 */
	function load_lib_assets() {

		if ( ! is_admin() ) {

			/*
			fontawesome CSS
			load front end and backend
			Reason: Common for many blocks
			*/
			wp_enqueue_style( 'fontawesome' );
			wp_style_add_data( 'fontawesome', 'rtl', 'replace' );

			/*
			wpness grid Needed for Admin and Frontend.
			Reason: Common for many blocks*/
			wp_enqueue_style( 'wpness-grid' );
			wp_style_add_data( 'wpness-grid', 'rtl', 'replace' );

			/*
			Animate CSS
			load front
			Reason: needed on all blocks since animate option is everywhere
			*/
			wp_enqueue_style( 'animate' );
			wp_style_add_data( 'animate', 'rtl', 'replace' );

			/*
			Wow is needed for Animate CSS
			Reason: needed on all blocks since animate option is everywhere*/
			wp_enqueue_script( 'wow' );

		} else {
			/*
			fontawesome CSS
			load front end and backend
			Reason: Common for many blocks
			*/
			wp_enqueue_style( 'fontawesome' );
			wp_style_add_data( 'fontawesome', 'rtl', 'replace' );

			/*wpness grid Needed for Admin and Frontend*/
			wp_enqueue_style( 'wpness-grid' );
			wp_style_add_data( 'wpness-grid', 'rtl', 'replace' );
		}

		$this->library_loaded = true;
		if ( gutentor_is_edit_page() ) {
			$this->load_last_scripts();
		}

	}
	/**
	 * Callback functions for enqueue_block_assets,
	 * Enqueue Gutenberg block assets for both frontend + backend.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param null
	 * @return void
	 */
	function block_assets() { // phpcs:ignore

		$this->load_lib_assets();
	}

	/**
	 * Load gutentor scripts at last
	 * Because it has lot of dependency
	 *
	 * @since    2.1.2
	 * @access   public
	 *
	 * @param null
	 * @return void|boolean
	 */
	function load_last_scripts() {
		if ( ! $this->library_loaded && ! function_exists( 'gutentor_template' ) ) {
			return false;
		}

        /* Check this TODO */
		wp_enqueue_style( 'gutentor' );
        wp_style_add_data( 'gutentor', 'rtl', 'replace' );

		/*For WooCommerce*/
		if ( gutentor_is_woocommerce_active() ) {
			wp_enqueue_style( 'gutentor-woo' );
			wp_style_add_data( 'gutentor-woo', 'rtl', 'replace' );
		}

		/*For Edd*/
		if ( gutentor_is_edd_active() ) {
			wp_enqueue_style( 'gutentor-edd' );
			wp_style_add_data( 'gutentor-edd', 'rtl', 'replace' );
		}

		/*CSS for default/popular themes*/
		$templates        = array( 'twentynineteen', 'twentytwenty', 'generatepress', 'astra' );
		$current_template = get_template();
		if ( in_array( $current_template, $templates ) ) {
			wp_enqueue_style( 'gutentor-theme-' . esc_attr( $current_template ) );
			wp_style_add_data( 'gutentor-theme-' . esc_attr( $current_template ), 'rtl', 'replace' );
		}

		/*Reusable block fixed*/
		$reusable_blocks = gutentor_get_reusable_block_ids();
		if ( ! isset( $GLOBALS['GUTENTOR_GLOBAL']['reusable_block'] ) ) {
			$GLOBALS['GUTENTOR_GLOBAL']['reusable_block'] = $reusable_blocks;
		} else {
			$reusable_blocks                              = array_merge( $GLOBALS['GUTENTOR_GLOBAL']['reusable_block'], $reusable_blocks );
			$GLOBALS['GUTENTOR_GLOBAL']['reusable_block'] = $reusable_blocks;
		}
		if ( $reusable_blocks ) {
			$upload_dir = wp_upload_dir();
			foreach ( $reusable_blocks as $reusable_block ) {
				if ( file_exists( $upload_dir['basedir'] . '/gutentor/p-' . $reusable_block . '.css' ) ) {
					$css_info = get_post_meta( $reusable_block, 'gutentor_css_info', true );
					wp_enqueue_style( 'gutentor-dynamic-' . $reusable_block, trailingslashit( $upload_dir['baseurl'] ) . 'gutentor/p-' . $reusable_block . '.css', false, isset( $css_info['saved_version'] ) ? $css_info['saved_version'] : '' );
				}
			}
		}

		wp_enqueue_script( 'gutentor-block' );
		wp_localize_script(
			'gutentor-block',
			'gutentorLS',
			apply_filters(
				'gutentor_block_frontend_localize_data',
				array(
					'fontAwesomeVersion' => gutentor_get_options( 'fa-version' ),
					'restNonce'          => wp_create_nonce( 'wp_rest' ),
					'restUrl'            => esc_url_raw( rest_url() ),
				)
			)
		);
	}

	/**
	 * Callback functions for enqueue_block_editor_assets,
	 * Enqueue Gutenberg block assets for backend only.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return void
	 */
	public function block_editor_assets() { // phpcs:ignore

		// edd wishlist scripts loads in backend
		if ( function_exists( 'edd_wl_print_scripts' ) ) {
			edd_wl_print_scripts();
		}
		global $pagenow;

		$dependencies = array( 'jquery', 'lodash', 'wp-api', 'wp-i18n', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-keycodes', 'wp-plugins', 'wp-rich-text', 'wp-viewport' );
		if ( gutentor_is_edit_page() ) {
			array_push( $dependencies, 'wp-editor', 'wp-edit-post' );
		} elseif ( $pagenow === 'widgets.php' ) {
			array_push( $dependencies, 'wp-edit-widgets' );
		}

			// Scripts.
		wp_enqueue_script(
			'gutentor', // Handle.
			GUTENTOR_URL . 'dist/blocks.build.js', // Block.build.js: We register the block here. Built with Webpack.
			$dependencies, // Dependencies, defined above.
			GUTENTOR_VERSION, // Version: File modification time.
			true // Enqueue the script in the footer.
		);

		wp_set_script_translations( 'gutentor', 'gutentor' );
		$localize__data = apply_filters(
			'gutentor_block_editor_localize_data',
			array(
				'currentTheme'                    => get_template(),
				'currentScreen'                   => get_current_screen(),
				'postModuleGlobalCategoriesColor' => gutentor_pm_post_categories_color( true ),
				'thumbnailAllSizes'               => self::get_thumbnail_all_sizes(),
				'updatedThumbnailAllSizes'        => self::get_updated_thumbnail_all_sizes(),
				'mapsAPI'                         => gutentor_get_options( 'map-api' ),
				'dirUrl'                          => GUTENTOR_URL,
				'gutentorLogo'                    => GUTENTOR_URL . 'assets/img/gutentor-logo.png',
				'iconSvg'                         => GUTENTOR_URL . 'assets/img/block-icons/icon.svg',
				'singleColSvg'                    => GUTENTOR_URL . 'assets/img/block-icons/single-column.svg',
				'pricingSvg'                      => GUTENTOR_URL . 'assets/img/block-icons/pricing.svg',
				'simpleTextSvg'                   => GUTENTOR_URL . 'assets/img/block-icons/simple-text.svg',
				'coverSvg'                        => GUTENTOR_URL . 'assets/img/block-icons/cover.svg',
				'carouselSvg'                     => GUTENTOR_URL . 'assets/img/block-icons/carousel.svg',
				'sliderSvg'                       => GUTENTOR_URL . 'assets/img/block-icons/slider.svg',
				'openHoursSvg'                    => GUTENTOR_URL . 'assets/img/block-icons/opening-hours.svg',
				'notificationSvg'                 => GUTENTOR_URL . 'assets/img/block-icons/notification.svg',
				'advancedTextSvg'                 => GUTENTOR_URL . 'assets/img/block-icons/advance-text.svg',
				'featuredSvg'                     => GUTENTOR_URL . 'assets/img/block-icons/featured-block.svg',
				'tabSvg'                          => GUTENTOR_URL . 'assets/img/block-icons/tabs.svg',
				'counterSvg'                      => GUTENTOR_URL . 'assets/img/block-icons/counter.svg',
				'contentBoxSvg'                   => GUTENTOR_URL . 'assets/img/block-icons/content-box.svg',
				'buttonSvg'                       => GUTENTOR_URL . 'assets/img/block-icons/button.svg',
				'buttonGroupSvg'                  => GUTENTOR_URL . 'assets/img/block-icons/button-group.svg',
				'dynamicColSvg'                   => GUTENTOR_URL . 'assets/img/block-icons/dynamic-col.svg',
				'advancedColSvg'                  => GUTENTOR_URL . 'assets/img/block-icons/advanced-col.svg',
				'AdvPostSvg'                      => GUTENTOR_URL . 'assets/img/block-icons/advanced-post-module.svg',
				'newsTickerSvg'                   => GUTENTOR_URL . 'assets/img/block-icons/news-ticker.svg',
				'postFeaturedModuleSvg'           => GUTENTOR_URL . 'assets/img/block-icons/post-feature-module.svg',
				'postModuleSvg'                   => GUTENTOR_URL . 'assets/img/block-icons/post-module.svg',
				'duplexPostModuleSvg'             => GUTENTOR_URL . 'assets/img/block-icons/duplex-post-module.svg',
				/*widget blocks*/
				'aboutSvgW'                       => GUTENTOR_URL . 'assets/img/block-icons/about.svg',
				'authorSvgW'                      => GUTENTOR_URL . 'assets/img/block-icons/author.svg',
				'calltoactionSvgW'                => GUTENTOR_URL . 'assets/img/block-icons/calltoaction.svg',
				'countdownSvgW'                   => GUTENTOR_URL . 'assets/img/block-icons/countdown.svg',
				'dividerSvgW'                     => GUTENTOR_URL . 'assets/img/block-icons/divider.svg',
				'gallerySvgW'                     => GUTENTOR_URL . 'assets/img/block-icons/gallery.svg',
				'imageSvgW'                       => GUTENTOR_URL . 'assets/img/block-icons/image.svg',
				'listSvgW'                        => GUTENTOR_URL . 'assets/img/block-icons/list.svg',
				'mapSvgW'                         => GUTENTOR_URL . 'assets/img/block-icons/map.svg',
				'restaurantmenuSvgW'              => GUTENTOR_URL . 'assets/img/block-icons/restaurantmenu.svg',
				'progressbarSvgW'                 => GUTENTOR_URL . 'assets/img/block-icons/progressbar.svg',
				'ratingSvgW'                      => GUTENTOR_URL . 'assets/img/block-icons/rating.svg',
				'showmoreSvgW'                    => GUTENTOR_URL . 'assets/img/block-icons/showmore.svg',
				'socialSvgW'                      => GUTENTOR_URL . 'assets/img/block-icons/social.svg',
				'teamSvgW'                        => GUTENTOR_URL . 'assets/img/block-icons/team.svg',
				'testimonialsSvgW'                => GUTENTOR_URL . 'assets/img/block-icons/testimonial.svg',
				'timelineSvgW'                    => GUTENTOR_URL . 'assets/img/block-icons/timeline.svg',
				'videoSvgW'                       => GUTENTOR_URL . 'assets/img/block-icons/video.svg',
				'defaultImage'                    => GUTENTOR_URL . 'assets/img/default-image.jpg',
				'previewOnFrontendImg'            => GUTENTOR_URL . 'assets/img/preview-on-frontend.jpg',
				'gutentorSvg'                     => GUTENTOR_URL . 'assets/img/gutentor.svg',
				'gutentorWhiteSvg'                => GUTENTOR_URL . 'assets/img/gutentor-white-logo.svg',
				'gutentorBlackSvg'                => GUTENTOR_URL . 'assets/img/gutentor-black-logo.svg',
				'm6Svg'                           => GUTENTOR_URL . 'assets/img/block-icons/accordion-module.svg',
				'm9Svg'                           => GUTENTOR_URL . 'assets/img/block-icons/shortcode.svg',
				'templateLibrarySvg'              => GUTENTOR_URL . 'assets/img/block-icons/template-library.svg',
				'm12Svg'                          => GUTENTOR_URL . 'assets/img/block-icons/quote.svg',
				'm13Svg'                          => GUTENTOR_URL . 'assets/img/block-icons/toc.svg',
				'fontAwesomeVersion'              => gutentor_get_options( 'fa-version' ),
				'checkPostFormatSupport'          => gutentor_check_post_format_support_enable(),
				'postFormats'                     => gutentor_get_post_formats(),
				'postFormatsIcons'                => gutentor_get_all_post_format_icons(),
				'postFormatsColors'               => gutentor_post_format_colors( true ),
				'postFeaturedFormatsColors'       => gutentor_post_featured_format_colors(),
				'is_woocommerce_active'           => gutentor_is_woocommerce_active(),
				'enableEditorTemplateLibrary'     => gutentor_setting_enable_template_library(),
				'enableExportButton'              => gutentor_setting_enable_export_template_button(),
				'gutentorPro'                     => array(
					'active' => gutentor_pro_active(),
				),
				'edd'                             => array(
					'active'   => gutentor_is_edd_active(),
					'review'   => gutentor_is_edd_review_active(),
					'wishlist' => gutentor_is_edd_wishlist_active(),
				),
				'templateberg'                    => array(
					'active'  => gutentor_is_templateberg_active(),
					'account' => gutentor_templateberg_has_account(),
					'notice'  => gutentor_templateberg()->can_show_notification(),
					'loading' => false,
					'error'   => false,
				),
				'gActiveProTemplates'             => apply_filters(
					'gutentor_is_pro_active',
					array(
						'Gutentor' => false,
					)
				),
				'globalTypography'                => gutentor_get_global_typography(),
				'globalColor'                     => gutentor_get_global_color(),
				'globalContainerWidth'            => gutentor_get_global_container_width(),
				'blockNameShortFullForm'          => gutentor_block_name_short_full_form(),
				'typo-apply-options'              => gutentor_get_options( 'typo-apply-options' ),
				'color-palettes'                  => gutentor_get_options( 'color-palettes' ),
				'isEditPage'                      => gutentor_is_edit_page(),
				'nonce'                           => wp_create_nonce( 'gutentorNonce' ),
			)
		);
		wp_localize_script(
			'gutentor',
			'gutentor',
			$localize__data
		);

		// Scripts.
		wp_enqueue_script(
			'gutentor-editor', // Handle.
			GUTENTOR_URL . 'assets/js/block-editor' . GUTENTOR_SCRIPT_PREFIX . '.js',
			array( 'jquery' ), // Dependencies, defined above.
			GUTENTOR_VERSION, // Version: File modification time.
			true // Enqueue the script in the footer.
		);

		/*Frontend styles*/
		wp_enqueue_style( 'gutentor' );
		wp_style_add_data( 'gutentor', 'rtl', 'replace' );

		// Backend only styles.
		wp_enqueue_style(
			'gutentor-editor', // Handle.
			GUTENTOR_URL . 'dist/blocks.editor.build.css',
			array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
			GUTENTOR_VERSION // Version: File modification time.
		);
		wp_style_add_data( 'gutentor-editor', 'rtl', 'replace' );

	}

	/**
	 * Callback functions for customize_preview_init,
	 *
	 * @since    3.2.3
	 * @access   public
	 *
	 * @return void
	 */
	public function customize_preview_init() {
		wp_enqueue_script( 'gutentor-customizer', GUTENTOR_URL . '/assets/js/customizer.js', array( 'customize-preview', 'customize-selective-refresh', 'wp-data' ), GUTENTOR_VERSION, true );
	}

	/**
	 * Callback functions for body_class,
	 * Adding Body Class.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array $classes array of classes.
	 * @return array
	 */
	function add_body_class( $classes ) {
		$classes[] = 'gutentor-active';
		return $classes;
	}

	/**
	 * Callback functions for body_class,
	 * Adding Admin Body Class.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array $classes array of classes.
	 * @return string
	 */
	function add_admin_body_class( $classes ) {
		// Wrong: No space in the beginning/end.
		$classes                 .= ' gutentor-active ';
		$disable_full_with_editor = gutentor_get_options( 'wide-width-editor' );
		if ( current_theme_supports( 'align-wide' ) && ! $disable_full_with_editor ) {
			$classes = gutentor_concat_space( $classes, ' gutentor-wide-width ' );
		}
		return $classes;
	}

	/**
	 * Create Page Template
	 *
	 * @param {string} $templates
	 * @return string $templates
	 */
	function gutentor_add_page_template( $templates ) {
		$templates['template-gutentor-full-width.php'] = esc_html__( 'Gutentor Full Width', 'gutentor' );
		$templates['template-gutentor-canvas.php']     = esc_html__( 'Gutentor Canvas', 'gutentor' );
		return $templates;
	}

	/**
	 * Redirect Custom Page Template
	 *
	 * @param {string} $templates
	 * @return string $templates
	 */
	function gutentor_redirect_page_template( $template ) {
		$post          = get_post();
		$page_template = '';
		if ( $post->ID ) {
			$page_template = get_post_meta( $post->ID, '_wp_page_template', true );
		}
		$g_template = false;
		if ( 'template-gutentor-full-width.php' == basename( $page_template ) ) {
			$g_template = true;
			$template   = GUTENTOR_PATH . '/page-templates/template-gutentor-full-width.php';
		} elseif ( 'template-gutentor-canvas.php' == basename( $page_template ) ) {
			$g_template = true;
			$template   = GUTENTOR_PATH . '/page-templates/template-gutentor-canvas.php';
		}
		if ( $g_template ) {
			remove_action( 'template_include', array( 'WC_Template_Loader', 'template_loader' ) );
		}
		return $template;
	}

	/**
	 * Allowed style on post save
	 * Since gutentor add internal style per post page
	 *
	 * @param  array $allowedposttags
	 * @return  array
	 */
	public function allow_style_tags( $allowedposttags ) {
		$allowedposttags['style'] = array(
			'type' => true,
		);
		return $allowedposttags;
	}

	/**
	 * By default gutentor use fontawesome 5
	 * Changing default fontawesome to 4
	 * Quick fix for acmethemes
	 *
	 * @param  array $defaults, All default options of gutentor
	 * @return array $defaults, modified version of default
	 */
	function acmethemes_alter_default_options( $defaults ) {
		$current_theme        = wp_get_theme();
		$current_theme_author = $current_theme->get( 'Author' );
		if ( $current_theme_author != 'acmethemes' ) {
			return $defaults;
		}

		$defaults['fa-version'] = 4; /*default is fontawesome 5, we change here 4*/
		return $defaults;
	}

	/**
	 * Register Gutentor_Reusable_Block_Widget
	 */
	function register_gutentor_reusable_block_selector_widget() {
		register_widget( 'Gutentor_WP_Block_Widget' );
	}
}

/**
 * Begins execution of the hooks.
 *
 * @since    1.0.0
 */
function gutentor_hooks() {
	return Gutentor_Hooks::instance();
}
