<?php

defined( 'ABSPATH' ) or die( 'Keep Quit' );

if ( ! class_exists( 'Woo_Variation_Gallery_Settings', false ) ):

	class Woo_Variation_Gallery_Settings extends GetWooPlugins_Settings_Page {

		public function __construct() {
			$this->notices();
			$this->hooks();
			parent::__construct();
		}

		public function get_id() {
			return 'woo_variation_gallery';
		}

		public function get_label() {
			return esc_html__( 'Variation Gallery', 'woo-variation-gallery' );
		}

		public function get_menu_name() {
			return esc_html__( 'Gallery Settings', 'woo-variation-gallery' );
		}

		public function get_title() {
			return esc_html__( 'Variation Gallery for WooCommerce Settings', 'woo-variation-gallery' );
		}

		protected function hooks() {
			add_action( 'getwooplugins_after_delete_options', array( $this, 'delete_old_option_data' ) );
			add_action( 'getwooplugins_sidebar', array( $this, 'sidebar' ) );
			add_filter( 'show_getwooplugins_save_button', array( $this, 'show_save_button' ), 10, 3 );
			add_filter( 'show_getwooplugins_sidebar', array( $this, 'show_sidebar' ), 10, 3 );
		}

		public function show_save_button( $default, $current_tab, $current_section ) {
			if ( $current_tab === $this->get_id() && in_array( $current_section, array( 'tutorial', 'migration' ) ) ) {
				return false;
			}

			return $default;
		}

		public function show_sidebar( $default, $current_tab, $current_section ) {
			if ( $current_tab === $this->get_id() && in_array( $current_section, array( 'tutorial' ) ) ) {
				return false;
			}

			return $default;
		}

		public function sidebar( $current_tab ) {
			if ( $current_tab === $this->get_id() ) {
				include_once dirname( __FILE__ ) . '/html-settings-sidebar.php';
			}
		}

		protected function notices() {
			// phpcs:disable WordPress.Security.NonceVerification.Recommended

			if ( $this->is_current_tab() && isset( $_GET['reset'] ) ) { // WPCS: input var okay, CSRF ok.
				GetWooPlugins_Admin_Settings::add_message( __( 'Gallery Settings reset.', 'woo-variation-gallery' ) );
			}

			// phpcs:enable
		}

		public function delete_old_option_data() {

			delete_option( 'woo_variation_gallery_thumbnails_columns' );
			delete_option( 'woo_variation_gallery_thumbnails_gap' );
			delete_option( 'woo_variation_gallery_width' );
			delete_option( 'woo_variation_gallery_medium_device_width' );
			delete_option( 'woo_variation_gallery_small_device_width' );
			delete_option( 'woo_variation_gallery_small_device_clear_float' );
			delete_option( 'woo_variation_gallery_extra_small_device_width' );
			delete_option( 'woo_variation_gallery_extra_small_device_clear_float' );
			delete_option( 'woo_variation_gallery_margin' );
			delete_option( 'woo_variation_gallery_preloader_disable' );
			delete_option( 'woo_variation_gallery_preload_style' );
			delete_option( 'woo_variation_gallery_slider_autoplay' );
			delete_option( 'woo_variation_gallery_slider_autoplay_speed' );
			delete_option( 'woo_variation_gallery_slide_speed' );
			delete_option( 'woo_variation_gallery_slider_fade' );
			delete_option( 'woo_variation_gallery_slider_arrow' );
			delete_option( 'woo_variation_gallery_zoom' );
			delete_option( 'woo_variation_gallery_lightbox' );
			delete_option( 'woo_variation_gallery_thumbnail_slide' );
			delete_option( 'woo_variation_gallery_thumbnail_arrow' );
			delete_option( 'woo_variation_gallery_zoom_position' );
			delete_option( 'woo_variation_gallery_thumbnail_position' );
			delete_option( 'woo_variation_gallery_remove_featured_image' );
			delete_option( 'woo_variation_gallery_disabled_product_type' );
			delete_option( 'woo_variation_gallery_thumbnail_width' );
			delete_option( 'woo_variation_gallery_reset_on_variation_change' );
			delete_option( 'woo_variation_gallery_image_preload' );
		}

		/**
		 * Output the settings.
		 */
		public function output( $current_tab ) {
			global $current_section;

			if ( $current_tab === $this->get_id() && 'tutorial' === $current_section ) {
				$this->tutorial_section( $current_section );
			} elseif ( $current_tab === $this->get_id() && 'migration' === $current_section ) {
				$this->migration_section( $current_section );
			} else {
				parent::output( $current_tab );
			}
		}

		public function tutorial_section( $current_section ) {
			ob_start();
			$settings = $this->get_settings( $current_section );
			include_once dirname( __FILE__ ) . '/html-tutorials.php';
			echo ob_get_clean();
		}

		public function migration_section( $current_section ) {
			ob_start();
			$settings = $this->get_settings( $current_section );
			include_once dirname( __FILE__ ) . '/html-migrations.php';
			echo ob_get_clean();
		}

		public function get_all_image_sizes() {

			$image_subsizes = wp_get_registered_image_subsizes();

			return apply_filters( 'woo_variation_gallery_get_all_image_sizes', array_reduce( array_keys( $image_subsizes ), function ( $carry, $item ) use ( $image_subsizes ) {

				$title  = ucwords( str_ireplace( array( '-', '_' ), ' ', $item ) );
				$width  = $image_subsizes[ $item ]['width'];
				$height = $image_subsizes[ $item ]['height'];

				$carry[ $item ] = sprintf( '%s (%d &times; %d)', $title, $width, $height );

				return $carry;
			}, array() ) );
		}

		public function plugins_tab( $label ) {
			return sprintf( '<span class="getwooplugins-recommended-plugins-tab dashicons dashicons-admin-plugins"></span> <span>%s</span>', $label );
		}

		protected function get_own_sections() {
			$sections = array(
				''          => esc_html__( 'General', 'woo-variation-gallery' ),
				'configure' => esc_html__( 'Configuration', 'woo-variation-gallery' ),
				'advanced'  => esc_html__( 'Advanced', 'woo-variation-gallery' ),
				'migration' => esc_html__( 'Migration', 'woo-variation-gallery' ),
				'license'   => array(
					'name' => esc_html__( 'License', 'woo-variation-gallery' ),
					'url'  => false
				),
				'tutorial'  => esc_html__( 'Tutorials', 'woo-variation-gallery' )
			);

			if ( current_user_can( 'install_plugins' ) ) {
				$sections['plugins'] = array(
					'name' => $this->plugins_tab( esc_html__( 'Useful Free Plugins', 'woo-variation-gallery' ) ),
					'url'  => self_admin_url( 'plugin-install.php?s=getwooplugins&tab=search&type=author' ),
				);
			}

			return $sections;
		}

		protected function get_settings_for_default_section() {

			$settings = array(

				// Thumbnails Section Start
				array(
					'name' => esc_html__( 'Thumbnail Options', 'woo-variation-gallery' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'thumbnail_options',
				),

				// Thumbnails Item
				array(
					'title'             => esc_html__( 'Thumbnails Item', 'woo-variation-gallery' ),
					'type'              => 'number',
					'default'           => absint( apply_filters( 'woo_variation_gallery_default_thumbnails_columns', 4 ) ),
					'css'               => 'width:50px;',
					'desc_tip'          => esc_html__( 'Product Thumbnails Item Image', 'woo-variation-gallery' ),
					'desc'              => sprintf( esc_html__( 'Product Thumbnails Item Image. Default value is: %d. Limit: 2-8.', 'woo-variation-gallery' ), absint( apply_filters( 'woo_variation_gallery_default_thumbnails_columns', 4 ) ) ),
					'id'                => 'thumbnails_columns',
					'custom_attributes' => array(
						'min'  => 2,
						'max'  => 8,
						'step' => 1,
					),
				),

				// Thumbnails Gap
				array(
					'title'             => esc_html__( 'Thumbnails Gap', 'woo-variation-gallery' ),
					'type'              => 'number',
					'default'           => absint( apply_filters( 'woo_variation_gallery_default_thumbnails_gap', 0 ) ),
					'css'               => 'width:50px;',
					'suffix'            => 'px',
					'desc_tip'          => esc_html__( 'Product Thumbnails Gap In Pixel', 'woo-variation-gallery' ),
					'desc'              => sprintf( esc_html__( 'Product Thumbnails Gap In Pixel. Default value is: %d. Limit: 0-20.', 'woo-variation-gallery' ), apply_filters( 'woo_variation_gallery_default_thumbnails_gap', 0 ) ),
					'id'                => 'thumbnails_gap',
					'custom_attributes' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					),
				),

				// Section End
				array(
					'type' => 'sectionend',
					'id'   => 'thumbnail_options'
				),

				// Gallery Section Start
				array(
					'name' => esc_html__( 'Gallery Options', 'woo-variation-gallery' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'main_options',
				),

				// Default Gallery Width
				array(
					'title'             => esc_html__( 'Gallery Width', 'woo-variation-gallery' ),
					'type'              => 'number',
					'default'           => absint( apply_filters( 'woo_variation_gallery_default_width', 30 ) ),
					'css'               => 'width:60px;',
					'suffix'            => '%',
					'desc_tip'          => esc_html__( 'Slider gallery width in % for large devices.', 'woo-variation-gallery' ),
					'desc'              => sprintf( __( 'Slider Gallery Width in %%. Default value is: %d. Limit: 10-100. Please check this <a target="_blank" href="%s">how to video to configure it.</a>', 'woo-variation-gallery' ), absint( apply_filters( 'woo_variation_gallery_default_width', 30 ) ), 'https://www.youtube.com/watch?v=IPRZnHy3nuQ&list=PLjkiDGg3ul_IX0tgkHNKtTyGhywFhU2J1&index=1' ),
					'id'                => 'width',
					'custom_attributes' => array(
						'min'  => 10,
						'max'  => 100,
						'step' => 1,
					),
				),

				// Medium Devices, Desktop
				array(
					'title'             => esc_html__( 'Gallery Width', 'woo-variation-gallery' ),
					'type'              => 'number',
					'default'           => absint( apply_filters( 'woo_variation_gallery_medium_device_width', 0 ) ),
					'css'               => 'width:60px;',
					'prefix-icon'       => 'dashicons dashicons-desktop',
					'suffix'            => 'px',
					'desc_tip'          => esc_html__( 'Slider gallery width in px for medium devices, small desktop', 'woo-variation-gallery' ),
					'desc'              => esc_html__( 'Slider gallery width in pixel for medium devices, small desktop. Default value is: 0. Limit: 0-1000. Media query (max-width : 992px)', 'woo-variation-gallery' ),
					'id'                => 'medium_device_width',
					'custom_attributes' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
				),

				// Small Devices, Tablets
				array(
					'title'             => esc_html__( 'Gallery Width', 'woo-variation-gallery' ),
					'type'              => 'number',
					'default'           => absint( apply_filters( 'woo_variation_gallery_small_device_width', 720 ) ),
					'css'               => 'width:60px;',
					'prefix-icon'       => 'dashicons dashicons-tablet',
					'suffix'            => 'px',
					'desc_tip'          => esc_html__( 'Slider gallery width in px for small devices, tablets', 'woo-variation-gallery' ),
					'desc'              => esc_html__( 'Slider gallery width in pixel for medium devices, small desktop. Default value is: 720. Limit: 0-1000. Media query (max-width : 768px)', 'woo-variation-gallery' ),
					'id'                => 'small_device_width',
					'custom_attributes' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
				),

				// Clear float for Small Devices, Tablets
				array(
					'title'   => esc_html__( 'Clear float', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => esc_html__( 'Clear float for small devices, tablets.', 'woo-variation-gallery' ),
					'id'      => 'small_device_clear_float'
				),

				// Extra Small Devices, Phones
				array(
					'title'             => esc_html__( 'Gallery Width', 'woo-variation-gallery' ),
					'type'              => 'number',
					'default'           => absint( apply_filters( 'woo_variation_gallery_extra_small_device_width', 320 ) ),
					'css'               => 'width:60px;',
					'prefix-icon'       => 'dashicons dashicons-smartphone',
					'suffix'            => 'px',
					'desc_tip'          => esc_html__( 'Slider gallery width in px for extra small devices, phones', 'woo-variation-gallery' ),
					'desc'              => esc_html__( 'Slider gallery width in pixel for extra small devices, phones. Default value is: 320. Limit: 0-1000. Media query (max-width : 480px)', 'woo-variation-gallery' ),
					'id'                => 'extra_small_device_width',
					'custom_attributes' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
				),

				// Clear float for Extra Small Devices, Phones
				array(
					'title'   => esc_html__( 'Clear float', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => esc_html__( 'Clear float for extra small devices, mobile.', 'woo-variation-gallery' ),
					'id'      => 'extra_small_device_clear_float'
				),

				// Gallery Bottom GAP
				array(
					'title'             => esc_html__( 'Gallery Bottom Gap', 'woo-variation-gallery' ),
					'type'              => 'number',
					'default'           => absint( apply_filters( 'woo_variation_gallery_default_margin', 30 ) ),
					'css'               => 'width:60px;',
					'desc_tip'          => esc_html__( 'Slider gallery bottom margin in pixel', 'woo-variation-gallery' ),
					'suffix'            => 'px',
					'desc'              => sprintf( esc_html__( 'Slider gallery bottom margin in pixel. Default value is: %d. Limit: 10-100.', 'woo-variation-gallery' ), apply_filters( 'woo_variation_gallery_default_margin', 30 ) ),
					'id'                => 'margin',
					'custom_attributes' => array(
						'min'  => 10,
						'max'  => 100,
						'step' => 1,
					),
				),

				// Disable Preloader
				array(
					'title'   => esc_html__( 'Disable Preloader', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => esc_html__( 'Disable preloader on loading variation images', 'woo-variation-gallery' ),
					'id'      => 'preloader_disable'
				),

				// Preload Style
				array(
					'title'   => esc_html__( 'Preload Style', 'woo-variation-gallery' ),
					'type'    => 'select',
					'class'   => 'wc-enhanced-select',
					'default' => 'blur',
					'id'      => 'preload_style',
					'require' => $this->normalize_required_attribute( array( 'preloader_disable' => array( 'type' => 'empty' ) ) ),
					'options' => array(
						'fade' => esc_html__( 'Fade', 'woo-variation-gallery' ),
						'blur' => esc_html__( 'Blur', 'woo-variation-gallery' ),
						'gray' => esc_html__( 'Gray', 'woo-variation-gallery' ),
					)
				),


				// End
				array(
					'type' => 'sectionend',
					'id'   => 'main_options'
				),
			);

			return $settings;
		}

		protected function get_settings_for_configure_section() {

			$settings = array(

				array(
					'name' => esc_html__( 'Gallery Configure', 'woo-variation-gallery' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'configure_settings',
				),

				array(
					'title'   => esc_html__( 'Gallery Auto play', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => esc_html__( 'Gallery Auto Slide / Auto Play', 'woo-variation-gallery' ),
					'id'      => 'slider_autoplay'
				),

				array(
					'title'             => esc_html__( 'Gallery Auto Play Speed', 'woo-variation-gallery' ),
					'type'              => 'number',
					'default'           => 5000,
					'css'               => 'width:70px;',
					'suffix'            => 'milliseconds',
					'desc'              => esc_html__( 'Slider gallery autoplay speed. Default is 5000 means 5 seconds', 'woo-variation-gallery' ),
					'id'                => 'slider_autoplay_speed',
					'require'           => $this->normalize_required_attribute( array( 'slider_autoplay' => array( 'type' => '!empty' ) ) ),
					'custom_attributes' => array(
						'min'  => 500,
						'max'  => 10000,
						'step' => 500,
					),
				),

				array(
					'title'             => esc_html__( 'Gallery Slide / Fade Speed', 'woo-variation-gallery' ),
					'type'              => 'number',
					'default'           => 300,
					'suffix'            => 'milliseconds',
					'css'               => 'width:60px;',
					'desc'              => esc_html__( 'Gallery sliding speed. Default is 300 means 300 milliseconds', 'woo-variation-gallery' ),
					'id'                => 'slide_speed',
					'custom_attributes' => array(
						'min'  => 100,
						'max'  => 1000,
						'step' => 100,
					),
				),

				array(
					'title'   => esc_html__( 'Fade Slide', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => esc_html__( 'Gallery will change by fade not slide', 'woo-variation-gallery' ),
					'id'      => 'slider_fade'
				),

				array(
					'title'   => esc_html__( 'Show Slider Arrow', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'yes',
					'desc'    => esc_html__( 'Show Gallery Slider Arrow', 'woo-variation-gallery' ),
					'id'      => 'slider_arrow'
				),

				array(
					'title'   => esc_html__( 'Enable Image Zoom', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'yes',
					'desc'    => esc_html__( 'Enable Gallery Image Zoom', 'woo-variation-gallery' ),
					'id'      => 'zoom'
				),

				array(
					'title'   => esc_html__( 'Enable Image Popup', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'yes',
					'desc'    => esc_html__( 'Enable Gallery Image Popup', 'woo-variation-gallery' ),
					'id'      => 'lightbox'
				),

				array(
					'title'   => esc_html__( 'Enable Thumbnail Slide', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'yes',
					'desc'    => esc_html__( 'Enable Gallery Thumbnail Slide', 'woo-variation-gallery' ),
					'id'      => 'thumbnail_slide'
				),

				array(
					'title'   => esc_html__( 'Show Thumbnail Arrow', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'yes',
					'desc'    => esc_html__( 'Show Gallery Thumbnail Arrow', 'woo-variation-gallery' ),
					'id'      => 'thumbnail_arrow'
				),

				array(
					'title'    => esc_html__( 'Zoom Icon Display Position', 'woo-variation-gallery' ),
					'id'       => 'zoom_position',
					'default'  => 'top-right',
					//'type'     => 'radio',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'desc_tip' => esc_html__( 'Product Gallery Zoom Icon Display Position', 'woo-variation-gallery' ),
					'options'  => array(
						'top-right'    => esc_html__( 'Top Right', 'woo-variation-gallery' ),
						'top-left'     => esc_html__( 'Top Left', 'woo-variation-gallery' ),
						'bottom-right' => esc_html__( 'Bottom Right', 'woo-variation-gallery' ),
						'bottom-left'  => esc_html__( 'Bottom Left', 'woo-variation-gallery' ),
					),
				),

				array(
					'title'   => esc_html__( 'Thumbnail Display Position', 'woo-variation-gallery' ),
					'id'      => 'thumbnail_position',
					'default' => 'bottom',
					//'type'     => 'radio',
					'type'    => 'select',
					'class'   => 'wc-enhanced-select',
					'desc'    => esc_html__( 'Product Gallery Thumbnail Display Position', 'woo-variation-gallery' ),
					'options' => array(
						'left'   => esc_html__( 'Left', 'woo-variation-gallery' ),
						'right'  => esc_html__( 'Right', 'woo-variation-gallery' ),
						'bottom' => esc_html__( 'Bottom', 'woo-variation-gallery' ),
					),
				),

				array(
					'title'   => esc_html__( 'Small Devices Thumbnail Position', 'woo-variation-gallery' ),
					'id'      => 'thumbnail_position_small_device',
					'default' => 'bottom',
					//'type'     => 'radio',
					'type'    => 'select',
					'class'   => 'wc-enhanced-select',
					'desc'    => esc_html__( 'Product Gallery Thumbnail Display Position for Small Devices. Below 768px', 'woo-variation-gallery' ),
					'options' => array(
						'left'   => esc_html__( 'Left', 'woo-variation-gallery' ),
						'right'  => esc_html__( 'Right', 'woo-variation-gallery' ),
						'bottom' => esc_html__( 'Bottom', 'woo-variation-gallery' ),
					),
				),

				array(
					'type' => 'sectionend',
					'id'   => 'configure_settings'
				),
			);

			return $settings;
		}

		protected function get_settings_for_advanced_section() {
			$settings = array(

				array(
					'name'  => esc_html__( 'Advanced Options', 'woo-variation-gallery' ),
					'type'  => 'title',
					'desc'  => '',
					'class' => 'woo-variation-gallery-options',
					'id'    => 'advanced_options',
				),

				// Hide default featured image
				array(
					'title'   => esc_html__( 'Hide Main Product Image', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => esc_html__( 'Remove main product image from gallery', 'woo-variation-gallery' ),
					'id'      => 'remove_featured_image'
				),

				// Disable on Specific Product type
				array(
					'title'             => esc_html__( 'Disable on Product Type', 'woo-variation-gallery' ),
					'type'              => 'multiselect',
					'options'           => wc_get_product_types(),
					'class'             => 'wc-enhanced-select',
					'default'           => array( 'gift-card', 'bundle' ),
					'desc_tip'          => esc_html__( 'Disable Gallery on Specific Product type like: simple product / variable product / bundle product etc.', 'woo-variation-gallery' ),
					'id'                => 'disabled_product_type',
					'custom_attributes' => array(
						'data-placeholder' => esc_html__( 'Choose specific product type(s).', 'woo-variation-gallery' ),
					)
				),

				// Thumbnails Image Width
				array(
					'title'             => esc_html__( 'Gallery Thumbnails Image Width', 'woo-variation-gallery' ),
					'type'              => 'number',
					'default'           => absint( wc_get_theme_support( 'gallery_thumbnail_image_width', 100 ) ),
					'css'               => 'width:65px;',
					'suffix'            => 'px',
					'desc_tip'          => esc_html__( 'Product Gallery Thumbnails Image Width In Pixel to fix blurry thumbnail image.', 'woo-variation-gallery' ),
					'desc'              => sprintf( esc_html__( 'Product Gallery Thumbnails Image Width In Pixel to fix blurry thumbnail image. Default value is: %1$d. Limit: 80-300. %2$sRecommended: To Regenerate shop thumbnails after change this setting.%3$s', 'woo-variation-gallery' ),
						absint( wc_get_theme_support( 'gallery_thumbnail_image_width', 100 ) ),
						sprintf( '<br /><a target="_blank" href="%s">', esc_url( wp_nonce_url( admin_url( 'admin.php?page=wc-status&tab=tools&action=regenerate_thumbnails' ), 'debug_action' ) ) ),
						'</a>'
					),
					'id'                => 'thumbnail_width',
					'custom_attributes' => array(
						'min'  => 80,
						'max'  => 300,
						'step' => 5,
					),
				),

				// Reset Variation Gallery
				array(
					'title'   => esc_html__( 'Reset Variation Gallery', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'desc'    => esc_html__( 'Always Reset Gallery After Variation Select', 'woo-variation-gallery' ),
					'id'      => 'reset_on_variation_change'
				),

				// Gallery Image Preload
				array(
					'title'   => esc_html__( 'Gallery Image Preload', 'woo-variation-gallery' ),
					'type'    => 'checkbox',
					'default' => 'yes',
					'desc'    => esc_html__( 'Variation Gallery Image Preload', 'woo-variation-gallery' ),
					'id'      => 'image_preload'
				),

				array(
					'type' => 'sectionend',
					'id'   => 'advanced_options'
				),
			);

			return $settings;
		}
	}
endif;