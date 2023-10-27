<?php

defined( 'ABSPATH' ) or die( 'Keep Silent' );

if ( ! class_exists( 'Woo_Variation_Gallery_Backend', false ) ):

	class Woo_Variation_Gallery_Backend {

		protected static $_instance = null;
		protected $admin_menu;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		protected function __construct() {
			$this->includes();
			$this->hooks();
			$this->init();
			do_action( 'woo_variation_gallery_backend_loaded', $this );
		}

		public function includes() {
			require_once dirname( __FILE__ ) . '/getwooplugins/class-getwooplugins-admin-menus.php';

			require_once dirname( __FILE__ ) . '/class-woo-variation-gallery-export-import.php';
			require_once dirname( __FILE__ ) . '/class-woo-variation-gallery-migrate.php';
			require_once dirname( __FILE__ ) . '/class-woo-variation-gallery-rest-api.php';
			require_once dirname( __FILE__ ) . '/class-woo-variation-gallery-deactivate-feedback.php';
		}

		public function hooks() {
			add_filter( 'getwooplugins_get_settings_pages', array( $this, 'init_settings' ) );

			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

			add_filter( 'plugin_action_links_' . plugin_basename( WOO_VARIATION_GALLERY_PLUGIN_FILE ), array(
				$this,
				'plugin_action_links'
			) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_footer', array( $this, 'admin_template_js' ) );

			add_action( 'woocommerce_save_product_variation', array( $this, 'save_product_variation' ), 10, 2 );
			add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'gallery_admin_html' ), 10, 3 );

			add_action( 'after_switch_theme', array( $this, 'remove_option' ), 20 );

			add_action( 'admin_init', array( $this, 'activate_redirect' ) );
		}

		public function init() {
			$this->admin_menu = GetWooPlugins_Admin_Menus::instance();
			Woo_Variation_Gallery_Migrate::instance();
			Woo_Variation_Gallery_REST_API::instance();
			Woo_Variation_Gallery_Export_Import::instance();
			Woo_Variation_Gallery_Deactivate_Feedback::instance();
		}

		public function gallery_admin_html( $loop, $variation_data, $variation ) {
			$variation_id   = absint( $variation->ID );
			$gallery_images = get_post_meta( $variation_id, 'woo_variation_gallery_images', true );
			?>
			<div data-product_variation_id="<?php echo esc_attr( $variation_id ) ?>" class="form-row form-row-full woo-variation-gallery-wrapper">
				<div class="woo-variation-gallery-postbox">
					<div class="postbox-header">
						<h2><?php esc_html_e( 'Variation Product Gallery', 'woo-variation-gallery' ) ?></h2>
						<button type="button" class="handle-div" aria-expanded="true">
							<span class="toggle-indicator" aria-hidden="true"></span>
						</button>
					</div>

					<div class="woo-variation-gallery-inside">
						<div class="woo-variation-gallery-image-container">
							<ul class="woo-variation-gallery-images">
								<?php
								if ( is_array( $gallery_images ) && ! empty( $gallery_images ) ) {
									include dirname( __FILE__ ) . '/admin-template.php';
								}
								?>
							</ul>
						</div>
						<div class="add-woo-variation-gallery-image-wrapper hide-if-no-js">
							<a href="#" data-product_variation_loop="<?php echo absint( $loop ) ?>" data-product_variation_id="<?php echo esc_attr( $variation_id ) ?>" class="button-primary add-woo-variation-gallery-image"><?php esc_html_e( 'Add Variation Gallery Image', 'woo-variation-gallery' ) ?></a>
							<?php if ( ! woo_variation_gallery()->is_pro() ): ?>
								<a target="_blank" href="<?php echo esc_url( woo_variation_gallery()->get_backend()->get_pro_link() ) ?>" style="display: none" class="button woo-variation-gallery-pro-button"><?php esc_html_e( 'Upgrade to pro to add more images and videos', 'woo-variation-gallery' ) ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		public function save_product_variation( $variation_id, $loop ) {

			if ( isset( $_POST['woo_variation_gallery'] ) ) {

				if ( isset( $_POST['woo_variation_gallery'][ $variation_id ] ) ) {

					$gallery_image_ids = array_map( 'absint', $_POST['woo_variation_gallery'][ $variation_id ] );
					update_post_meta( $variation_id, 'woo_variation_gallery_images', $gallery_image_ids );
				} else {
					delete_post_meta( $variation_id, 'woo_variation_gallery_images' );
				}
			} else {
				delete_post_meta( $variation_id, 'woo_variation_gallery_images' );
			}
		}

		public function get_admin_menu() {
			return $this->admin_menu;
		}

		public function load_settings() {
			include_once dirname( __FILE__ ) . '/class-woo-variation-gallery-settings.php';

			return new Woo_Variation_Gallery_Settings();
		}

		public function init_settings( $settings ) {

			$settings[] = $this->load_settings();

			return $settings;
		}

		public function admin_enqueue_scripts() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_media();

			wp_enqueue_style( 'woo-variation-gallery-admin', esc_url( woo_variation_gallery()->assets_url( "/css/admin{$suffix}.css" ) ), array(), woo_variation_gallery()->assets_version( "/css/admin{$suffix}.css" ) );

			wp_enqueue_script( 'woo-variation-gallery-admin', esc_url( woo_variation_gallery()->assets_url( "/js/admin{$suffix}.js" ) ), array(
				'jquery',
				'jquery-ui-sortable',
				'wp-util'
			), woo_variation_gallery()->assets_version( "/js/admin{$suffix}.js" ), true );

			wp_localize_script( 'woo-variation-gallery-admin', 'woo_variation_gallery_admin', array(
				'choose_image' => esc_html__( 'Choose Image', 'woo-variation-gallery' ),
				'add_image'    => esc_html__( 'Add Images', 'woo-variation-gallery' )
			) );

			do_action( 'woo_variation_gallery_admin_enqueue_scripts', $this );
		}

		public function admin_template_js() {
			ob_start();
			require_once dirname( __FILE__ ) . '/admin-template-js.php';
			$data = ob_get_clean();
			echo apply_filters( 'woo_variation_gallery_admin_template_js', $data );
		}

		public function get_pro_link() {

			$affiliate_id = apply_filters( 'gwp_affiliate_id', 0 );

			$link_args = array();

			if ( ! empty( $affiliate_id ) ) {
				$link_args['ref'] = esc_html( $affiliate_id );
			}

			$link_args = apply_filters( 'woo_variation_gallery_get_pro_link_args', $link_args );

			return add_query_arg( $link_args, 'https://getwooplugins.com/plugins/woocommerce-variation-gallery/' );
		}

		public function plugin_row_meta( $links, $file ) {


			if ( woo_variation_gallery()->plugin_basename() !== $file ) {
				return $links;
			}


			$report_url = 'https://getwooplugins.com/tickets/';

			$documentation_url = 'https://getwooplugins.com/documentation/woocommerce-variation-gallery/';

			$row_meta['docs']    = sprintf( '<a target="_blank" href="%1$s" title="%2$s">%2$s</a>', esc_url( $documentation_url ), esc_html__( 'View documentation', 'woo-variation-gallery' ) );
			$row_meta['support'] = sprintf( '<a target="_blank" href="%1$s">%2$s</a>', esc_url( $report_url ), esc_html__( 'Help &amp; Support', 'woo-variation-gallery' ) );

			return array_merge( $links, $row_meta );

		}

		public function plugin_action_links( $links ) {

			$action_links = array(
				'settings' => '<a href="' . esc_url( $this->get_admin_menu()->get_settings_link( 'woo_variation_gallery' ) ) . '" aria-label="' . esc_attr__( 'View Gallery Settings', 'woo-variation-gallery' ) . '">' . esc_html__( 'Settings', 'woo-variation-gallery' ) . '</a>',
			);

			$pro_links = array(
				'gwp-go-pro-action-link' => sprintf( '<a target="_blank" href="%1$s" aria-label="%2$s">%2$s</a>', esc_url( $this->get_pro_link() ), esc_html__( 'Go Pro', 'woo-variation-gallery' ) ),
			);

			if ( woo_variation_gallery()->is_pro() ) {
				$pro_links = array();
			}

			return array_merge( $action_links, $links, $pro_links );

		}

		public function remove_option() {
			$saved_options = woo_variation_gallery()->get_options();

			if ( ! empty( $saved_options ) && is_array( $saved_options ) ) {
				unset( $saved_options['width'], $saved_options['thumbnail_width'] );
				woo_variation_gallery()->update_options( $saved_options );
			}
		}

		public function activate_redirect() {

			if ( wc_string_to_bool( get_option( 'woo_variation_gallery_do_activate_redirect', 'no' ) ) && ! woo_variation_gallery()->is_pro() ) {
				delete_option( 'woo_variation_gallery_do_activate_redirect' );

				wp_redirect( $this->get_admin_menu()->get_settings_link( 'woo_variation_gallery', 'tutorial' ) );
				exit;
			}
		}
	}
endif;