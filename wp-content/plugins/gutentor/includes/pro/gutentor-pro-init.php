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
class Gutentor_Pro_Edd_License_Init {

	public $api_url     = '';
	public $api_data    = array();
	public $plugin_file = '';

	private $name      = '';
	private $slug      = '';
	private $version   = '';
	private $item_id   = '';
	private $item_name = '';

	public $menu_slug   = '';
	public $notice_type = 'upgrade';

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
	 */
	public function run() {

		/*Edit this*/
		$this->api_url     = trailingslashit( 'https://premium.acmeit.org/' );
		$this->plugin_file = WP_PLUGIN_DIR . '/gutentor-pro/gutentor-pro.php';
		$this->slug        = basename( $this->plugin_file, '.php' );
		$this->api_data    = array(
			'version'   => '1.0.1',                    // current version number
			'license'   => $this->get_license_key(),             // license key (used get_option above to retrieve from DB)
			'item_id'   => '251',       // ID of the product
			'item_name' => 'Gutentor Pro',       // ID of the product
			'author'    => 'Gutentor', // author of this plugin
			'beta'      => false,
		);
		/*Edit this end*/

		$this->name      = plugin_basename( $this->plugin_file );
		$this->version   = $this->api_data ['version'];
		$this->item_id   = $this->api_data ['item_id'];
		$this->item_name = $this->api_data ['item_name'];
		$this->menu_slug = $this->slug . '-license';

		if ( ! function_exists( 'gutentor_pro_edd_plugin_installer' ) ) {
			include dirname( __FILE__ ) . '/Gutentor_Pro_Edd_Plugin_Installer.php';
		}
		gutentor_pro_edd_plugin_installer()->run(
			$this->api_url,
			$this->plugin_file,
			$this->api_data
		);

		// Set up hooks.
		$this->init();
	}

	/**
	 * Set up WordPress hooks.
	 *
	 * @return void
	 */
	public function init() {

		add_filter( 'admin_menu', array( $this, 'license_menu' ), 99 );
		add_action( 'admin_init', array( $this, 'register_license_option' ), 10 );
		add_action( 'admin_init', array( $this, 'activate_license' ), 10 );
		add_action( 'admin_init', array( $this, 'deactivate_license' ), 10 );

		add_action( 'admin_init', array( $this, 'upgrade_to_pro_notice' ), 10 );
		add_action( 'admin_notices', array( $this, 'getting_started' ) );
	}

	/**
	 * Get license key
	 *
	 * @return string
	 */
	public function get_license_key() {
		return trim( get_option( $this->slug . '_license_key' ) );
	}
	/**
	 * Adds the plugin license page to the admin menu.
	 *
	 * @return void
	 */
	function license_menu() {
		add_submenu_page(
			'gutentor',
			esc_html__( 'License', 'gutentor' ),
			esc_html__( 'License', 'gutentor' ),
			'manage_options',
			$this->menu_slug,
			array( $this, 'license_page' )
		);
	}

	/**
	 * Callback function from license_page.
	 *
	 * @return void
	 */
	function license_page() {
		add_settings_section(
			$this->slug . '_license',
			esc_html__( 'Gutentor License', 'gutentor' ),
			array( $this, 'license_key_settings_section' ),
			$this->menu_slug
		);
		add_settings_field(
			$this->slug . '_license_key',
			'<label for="' . $this->slug . '_license_key' . '">' . esc_html__( 'License Key', 'gutentor' ) . '</label>',
			array( $this, 'license_key_settings_field' ),
			$this->menu_slug,
			$this->slug . '_license'
		);
		$license = trim( get_option( $this->slug . '_license_key' ) );

		if( isset($_GET['g-message']) && !empty($_GET['g-message'])){
		    ?>
            <div id="message" class="error notice is-dismissible">
                <p><?php echo esc_html($_GET['g-message']) ?></p>
            </div>
                <?php
        }
		?>



        <div class="wrap" id="<?php esc_attr_e( $this->slug ); ?>-license-wrap">
			<h2><?php esc_html_e( 'Gutentor License Options', 'gutentor' ); ?></h2>
			<form method="post" action="options.php" id="<?php echo esc_attr( $this->slug ) . '-options-form'; ?>">
				<?php
				do_settings_sections( $this->menu_slug );
				settings_fields( $this->slug . '_license' );
				if ( $license ) {
					$button_text = esc_html__( 'Delete License', 'gutentor' );
					submit_button( $button_text, 'primary', 'btn-delete-license' );
				} else {
					$button_text = esc_html__( 'Save License', 'gutentor' );
					submit_button( $button_text, 'primary', 'btn-save-license' );
				}
				?>
			</form>
		</div>
		<script type="text/javascript">
			let form = document.getElementById("<?php echo esc_attr( $this->slug ) . '-options-form'; ?>"),
				license_field = document.getElementById("<?php echo esc_attr( $this->slug ) . '_license_key'; ?>");

			if( document.getElementById('btn-delete-license')){
				document.getElementById('btn-delete-license').addEventListener("click", function (evt) {
					evt.preventDefault();
					license_field.value = '';
					form.submit();
				});
			}
		</script>
		<?php
	}


	/**
	 * Adds content to the settings section.
	 *
	 * @return void
	 */
	function license_key_settings_section() {
		$status = trim( get_option( $this->slug . '_license_status' ) );
		if ( 'valid' === $status ) {
			echo '';
		} else {
			printf( esc_html__( '%1$sGet your premium license%2$s for full features, premium Gutenberg templates, premium supports and many more.%3$s', 'gutentor' ), '<h4><a href="https://www.gutentor.com/pricing" target="_blank" rel="nofollow noopener">', '</a>', '</h4>' );
		}
	}

	/**
	 * Outputs the license key settings field.
	 *
	 * @return void
	 */
	function license_key_settings_field() {
		$license = $this->get_license_key();
		$status  = trim( get_option( $this->slug . '_license_status' ) );

		?>
		<h4>
			<?php esc_html_e( 'Enter your license key.', 'gutentor' ); ?>
		</h4>
		<?php
		printf(
			'<input type="text" class="regular-text" id="' . $this->slug . '_license_key' . '" name="' . $this->slug . '_license_key' . '" value="%s" />',
			esc_attr( $license )
		);
		if ( ! $license ) {
			?>
			<p class="description">
				<?php
				esc_html_e( 'You are using a free license.', 'gutentor' );
				?>
				<a href="https://www.gutentor.com/pricing" target="_blank" rel="nofollow noopener noindex">
					<span><?php esc_html_e( 'Upgrade to Pro', 'gutentor' ); ?></span>
				</a>
			</p>
			<?php
		}
		$button = array();
		if ( 'valid' === $status ) {
			$button        = array(
				'name'  => $this->slug . '_license_deactivate',
				'label' => __( 'Deactivate License', 'gutentor' ),
			);
			$check_license = gutentor_pro_edd_plugin_installer()->check_license();
			?>
			<p class="description"><?php echo gutentor_pro_edd_plugin_installer()->get_install_link(); ?></p>
            <?php
            if( isset($check_license->expires)){
                ?>
                <p class="description">
                    <?php
                    printf(
                        __( 'Your license key expires on %s.', 'gutentor' ),
                        date_i18n( get_option( 'date_format' ), strtotime( $check_license->expires, current_time( 'timestamp' ) ) )
                    );
                    ?>
                </p>
                <?php
            }

		} elseif ( ! empty( $license ) ) {
			$button = array(
				'name'  => $this->slug . '_license_activate',
				'label' => __( 'Activate License', 'gutentor' ),
			);
		}
		wp_nonce_field( $this->slug . '_license_nonce', $this->slug . '_license_nonce' );
		if ( $button ) {
			?>
			<input type="submit" class="button-secondary" name="<?php echo esc_attr( $button['name'] ); ?>" value="<?php echo esc_attr( $button['label'] ); ?>"/>
			<?php
		}

	}

	/**
	 * Registers the license key setting in the options table.
	 *
	 * @return void
	 */
	function register_license_option() {
		register_setting( $this->slug . '_license', $this->slug . '_license_key', array( $this, 'sanitize_license' ) );
	}


	/**
	 * Sanitizes the license key.
	 *
	 * @param string $new The license key.
	 * @return string
	 */
	function sanitize_license( $new ) {
		$old = $this->get_license_key();
		if ( $old && $old !== $new ) {
			delete_option( $this->slug . '_license_status' ); // new license has been entered, so must reactivate
		}

		return sanitize_text_field( $new );
	}


	/**
	 * Activates the license key.
	 *
	 * @return void
	 */
	function activate_license() {

		// listen for our activate button to be clicked
		if ( ! isset( $_POST[ $this->slug . '_license_activate' ] ) ) {
			return;
		}

		// run a quick security check
		if ( ! check_admin_referer( $this->slug . '_license_nonce', $this->slug . '_license_nonce' ) ) {
			return; // get out if we didn't click the Activate button
		}

		// retrieve the license from the database
		$license = $this->get_license_key();
		if ( ! $license ) {
			$license = ! empty( $_POST[ $this->slug . '_license_key' ] ) ? sanitize_text_field( $_POST[ $this->slug . '_license_key' ] ) : '';
		}
		if ( ! $license ) {
			return;
		}

		// data to send in our API request
		$api_params = array(
			'edd_action'  => 'activate_license',
			'license'     => $license,
			'item_id'     => $this->item_id,
			'item_name'   => rawurlencode( $this->item_name ), // the name of our product in EDD
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
				$message = __( 'An error occurred, please try again.', 'gutentor' );
			}
		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );



			if ( !$license_data->success ) {

				switch ( $license_data->error ) {

					case 'expired':
						$message = sprintf(
						/* translators: the license key expiration date */
							__( 'Your license key expired on %s.', 'gutentor' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'disabled':
					case 'revoked':
						$message = __( 'Your license key has been disabled.', 'gutentor' );
						break;

					case 'missing':
						$message = __( 'Invalid license.', 'gutentor' );
						break;

					case 'invalid':
					case 'site_inactive':
						$message = __( 'Your license is not active for this URL.', 'gutentor' );
						break;

					case 'item_name_mismatch':
						/* translators: the plugin name */
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'gutentor' ), $this->item_name );
						break;

					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'gutentor' );
						break;

					default:
						$message = __( 'An error occurred, please try again.', 'gutentor' );
						break;
				}
			}
		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$redirect = add_query_arg(
				array(
					'page'          => $this->menu_slug,
					'sl_activation' => 'false',
					'g-message'       => $message,
				),
				admin_url( 'admin.php' )
			);


			wp_redirect( $redirect );
			exit();
		}
		// $license_data->license will be either "valid" or "invalid"
		if ( 'valid' === $license_data->license ) {
			update_option( $this->slug . '_license_key', $license );
		}
		update_option( $this->slug . '_license_status', $license_data->license );
		wp_safe_redirect( admin_url( 'admin.php?page=' . $this->menu_slug ) );
		exit();
	}

	function deactivate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST[ $this->slug . '_license_deactivate' ] ) ) {

			// run a quick security check
			if ( ! check_admin_referer( $this->slug . '_license_nonce', $this->slug . '_license_nonce' ) ) {
				return; // get out if we didn't click the Activate button
			}

			// retrieve the license from the database
			$license = $this->get_license_key();

			// data to send in our API request
			$api_params = array(
				'edd_action'  => 'deactivate_license',
				'license'     => $license,
				'item_id'     => $this->item_id,
				'item_name'   => rawurlencode( $this->item_name ), // the name of our product in EDD
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

				$redirect = add_query_arg(
					array(
						'page'          => $this->menu_slug,
						'sl_activation' => 'false',
                        'g-message'       => $message,
                    ),
					admin_url( 'admin.php' )
				);

				wp_redirect( $redirect );
				exit();
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( 'deactivated' === $license_data->license ) {
				delete_option( $this->slug . '_license_status' );
			}

			wp_safe_redirect( admin_url( 'admin.php?page=' . $this->menu_slug ) );
			exit();

		}
	}

	public function get_installed_time() {
		$helper_options = json_decode( get_option( '__gutentor_helper_options' ), true );
		if ( isset( $helper_options['installed_time'] ) ) {
			return $helper_options['installed_time'];
		}
		$helper_options['installed_time'] = time();

		update_option(
			'__gutentor_helper_options',
			wp_json_encode(
				$helper_options
			)
		);
		return false;

	}

	public function can_show_notice() {
		$license_data = gutentor_pro_edd_plugin_installer()->check_license();
		if ( $license_data && isset( $license_data->license ) && 'valid' === $license_data->license ) {
			 return false;
		}
		global $current_user;
		$user_id                  = $current_user->ID;
		$ignored_notice           = get_user_meta( $user_id, $this->slug . '_upgrade_to_pro_notice', true );
		$ignored_notice_partially = get_user_meta( $user_id, $this->slug . '_upgrade_to_pro_notice_partially', true );

		/**
		 * Return from notice display if:
		 *
		 * 1. The theme installed is less than 15 days ago.
		 * 2. If the user has ignored the message partially for 15 days.
		 * 3. Dismiss always if clicked on 'I Already Did' button.
		 */

		if ( ( $this->get_installed_time() && $this->get_installed_time() > strtotime( '-3 day' ) ) || ( $ignored_notice_partially > strtotime( '-15 day' ) ) || ( $ignored_notice ) ) {
			 return false;
		}
		return true;
	}
	/**
	 * Remove notice permanently
	 *
	 * Active callback of after_setup_theme
	 * return void
	 */
	public function upgrade_to_pro_notice() {

		global $current_user;
		$user_id = $current_user->ID;

		/* If user clicks to ignore the notice, add info to user meta */
		if ( isset( $_GET[ $this->slug . '_upgrade_to_pro_notice' ] ) && '0' == $_GET[ $this->slug . '_upgrade_to_pro_notice' ] ) {
			add_user_meta( $user_id, $this->slug . '_upgrade_to_pro_notice', 'true', true );
		}

		/* If user clicks to ignore the notice, add that to their user meta */
		if ( isset( $_GET[ $this->slug . '_upgrade_to_pro_notice_partially' ] ) && '0' == $_GET[ $this->slug . '_upgrade_to_pro_notice_partially' ] ) {

			update_user_meta( $user_id, $this->slug . '_upgrade_to_pro_notice_partially', time() );
			if ( isset( $_GET['go-license-page'] ) && '1' == $_GET['go-license-page'] ) {
				wp_safe_redirect( admin_url( 'admin.php?page=' . $this->menu_slug ) );
			}
		}
	}

	/**
	 * Get Started Notice
	 * Active callback of admin_notices
	 * return void
	 */
	public function getting_started() {

		if ( ! $this->can_show_notice() ) {
			 return;
		}

		global $current_user;

		?>
		<div class="notice updated <?php echo esc_attr( $this->slug ); ?>-review-notice" style="position:relative;padding:20px">
			<img class="<?php echo esc_attr( $this->slug ); ?>-gsm-screenshot" src="<?php echo esc_url( GUTENTOR_URL . 'assets/img/gutentor-logo.png' ); ?>" alt="<?php esc_attr_e( 'Gutentor', 'gutentor' ); ?>" width="70px" style="margin-right:20px; float: left" />
			<p>
				<?php
				printf(
				/* Translators: %1$s current user display name. */
					esc_html__(
						'Howdy, %1$s! It seems that you have been using Gutentor for a few days. We hope you are happy with everything that the plugin has to offer.  To fully take advantage of the best our plugin can offer, consider upgrading to premium version.',
						'gutentor'
					),
					'<strong>' . esc_html( $current_user->display_name ) . '</strong>'
				);
				?>
			</p>
			<style type="text/css">
				.btn-align-center{
					display: -webkit-inline-box!important;
					display: -ms-inline-flexbox!important;
					display: inline-flex!important;
					-webkit-box-align: center!important;
					-ms-flex-align: center!important;
					align-items: center!important;
				}
			</style>

			<div class="links">
				<a href="https://www.gutentor.com/pricing" class="btn button-primary btn-align-center" target="_blank" rel="nofollow noopener noindex">
					<span class="dashicons dashicons-cart"></span>
					<span><?php esc_html_e( 'Upgrade to Pro', 'gutentor' ); ?></span>
				</a>

				<a href="?<?php echo esc_attr( $this->slug ); ?>_upgrade_to_pro_notice_partially=0" class="btn button-secondary btn-align-center">
					<span class="dashicons dashicons-thumbs-up"></span>
					<span><?php esc_html_e( 'Maybe later', 'gutentor' ); ?></span>
				</a>

				<a href="?<?php echo esc_attr( $this->slug ); ?>_upgrade_to_pro_notice_partially=0&go-license-page=1" class="btn button-secondary btn-align-center" style="background: #218838;border-color: #218838;color: #fff">
					<span class="dashicons dashicons-smiley"></span>
					<span><?php esc_html_e( 'I already did', 'gutentor' ); ?></span>
				</a>

				<a href="?<?php echo esc_attr( $this->slug ); ?>_upgrade_to_pro_notice=0" class="btn-link">
					<span><?php esc_html_e( "Don't show again", 'gutentor' ); ?></span>
				</a>
			</div>

			<a class="notice-dismiss" style="text-decoration:none;" href="?<?php echo esc_attr( $this->slug ); ?>_upgrade_to_pro_notice_partially=0"></a>
		</div>

		<?php
	}

}

/**
 * Begins execution of the license.
 *
 * @since    1.0.0
 */
function gutentor_pro_license_init() {
	return Gutentor_Pro_Edd_License_Init::get_instance();
}

// setup the license
gutentor_pro_license_init()->run();
