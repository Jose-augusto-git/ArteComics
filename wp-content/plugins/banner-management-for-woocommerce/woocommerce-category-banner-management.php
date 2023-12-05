<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.multidots.com/
 * @since             1.0.0
 * @package           Woo_Banner_Management
 *
 * @wordpress-plugin
 * Plugin Name: Banner Management For WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/banner-management-for-woocommerce/
 * Description:       With this plugin, You can easily add banner in WooCommerce stores and you can upload the banner  specific for page,category  and welcome page.
 * Version:           2.4.3
 * Author:            theDotstore
 * Author URI:        https://www.thedotstore.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       banner-management-for-woocommerce
 * Domain Path:       /languages
 * WC tested up to:   8.0.3
 * Requires PHP:      7.2
 * Requires at least: 5.0
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( function_exists( 'wcbm_fs' ) ) {
    wcbm_fs()->set_basename( false, __FILE__ );
    return;
}


if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) || function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
    
    if ( !function_exists( 'wcbm_fs' ) ) {
        // Create a helper function for easy SDK access.
        function wcbm_fs()
        {
            global  $wcbm_fs ;
            
            if ( !isset( $wcbm_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_3494_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_3494_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $wcbm_fs = fs_dynamic_init( array(
                    'id'              => '3494',
                    'slug'            => 'woocommerce-category-banner-management',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_7b4f220ab6fb1f1b92d91f6f7f7b9',
                    'is_premium'      => false,
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'trial'           => array(
                    'days'               => 14,
                    'is_require_payment' => true,
                ),
                    'has_affiliation' => 'selected',
                    'menu'            => array(
                    'slug'       => 'wcbm-banner-setting',
                    'first-path' => 'admin.php?page=wcbm-plugin-get-started',
                    'contact'    => false,
                    'support'    => false,
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $wcbm_fs;
        }
        
        // Init Freemius.
        wcbm_fs();
        // Signal that SDK was initiated.
        do_action( 'wcbm_fs_loaded' );
        wcbm_fs()->get_upgrade_url();
        // Not like register_uninstall_hook(), you do NOT have to use a static function.
        wcbm_fs()->add_action( 'after_uninstall', 'wcbm_fs_uninstall_cleanup' );
    }
    
    if ( !defined( 'WCBM_PLUGIN_VERSION' ) ) {
        define( 'WCBM_PLUGIN_VERSION', '2.4.3' );
    }
    if ( !defined( 'WCBM_STORE_URL' ) ) {
        define( 'WCBM_STORE_URL', 'https://www.thedotstore.com' );
    }
    if ( !defined( 'WCBM_PRO_PLUGIN_URL' ) ) {
        define( 'WCBM_PRO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    }
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-woocommerce-category-banner-management-activator.php
     */
    if ( !function_exists( 'activate_woocommerce_category_banner_management' ) ) {
        function activate_woocommerce_category_banner_management()
        {
            require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-category-banner-management-activator.php';
            woocommerce_category_banner_management_Activator::activate();
        }
    
    }
    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-woocommerce-category-banner-management-deactivator.php
     */
    if ( !function_exists( 'deactivate_woocommerce_category_banner_management' ) ) {
        function deactivate_woocommerce_category_banner_management()
        {
            require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-category-banner-management-deactivator.php';
            woocommerce_category_banner_management_Deactivator::deactivate();
        }
    
    }
    register_activation_hook( __FILE__, 'activate_woocommerce_category_banner_management' );
    register_deactivation_hook( __FILE__, 'deactivate_woocommerce_category_banner_management' );
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-category-banner-management.php';
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    if ( !function_exists( 'run_category_banner_management_for_woocommerce' ) ) {
        function run_category_banner_management_for_woocommerce()
        {
            $plugin = new woocommerce_category_banner_management();
            $plugin->run();
        }
    
    }
    /**
     * Hide freemius account tab
     *
     * @since    3.9.3
     */
    
    if ( !function_exists( 'wcbm_hide_account_tab' ) ) {
        function wcbm_hide_account_tab()
        {
            return true;
        }
        
        wcbm_fs()->add_filter( 'hide_account_tabs', 'wcbm_hide_account_tab' );
    }
    
    /**
     * Include plugin header on freemius account page
     *
     * @since    1.0.0
     */
    
    if ( !function_exists( 'wcbm_load_plugin_header_after_account' ) ) {
        function wcbm_load_plugin_header_after_account()
        {
            require_once plugin_dir_path( __FILE__ ) . 'admin/partials/header/plugin-header.php';
        }
        
        wcbm_fs()->add_action( 'after_account_details', 'wcbm_load_plugin_header_after_account' );
    }
    
    /**
     * Hide billing and payments details from freemius account page
     *
     * @since    3.9.3
     */
    
    if ( !function_exists( 'wcbm_hide_billing_and_payments_info' ) ) {
        function wcbm_hide_billing_and_payments_info()
        {
            return true;
        }
        
        wcbm_fs()->add_action( 'hide_billing_and_payments_info', 'wcbm_hide_billing_and_payments_info' );
    }
    
    /**
     * Hide powerd by popup from freemius account page
     *
     * @since    3.9.3
     */
    
    if ( !function_exists( 'wcbm_hide_freemius_powered_by' ) ) {
        function wcbm_hide_freemius_powered_by()
        {
            return true;
        }
        
        wcbm_fs()->add_action( 'hide_freemius_powered_by', 'wcbm_hide_freemius_powered_by' );
    }

}

// Check the plugin dependency.
if ( !function_exists( 'wcbm_validate_admin_init' ) ) {
    function wcbm_validate_admin_init()
    {
        
        if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) && (!function_exists( 'is_plugin_active_for_network' ) || !is_plugin_active_for_network( 'woocommerce/woocommerce.php' )) ) {
            add_action( 'admin_notices', 'wcbm_plugin_admin_notice' );
        } else {
            run_category_banner_management_for_woocommerce();
        }
        
        // Load the language file for translating the plugin strings
        load_plugin_textdomain( 'banner-management-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

}
add_action( 'plugins_loaded', 'wcbm_validate_admin_init' );
/**
 * Show admin notice in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
if ( !function_exists( 'wcbm_plugin_admin_notice' ) ) {
    function wcbm_plugin_admin_notice()
    {
        $vpe_plugin = esc_html__( 'Banner Management For WooCommerce', 'banner-management-for-woocommerce' );
        $wc_plugin = esc_html__( 'WooCommerce', 'banner-management-for-woocommerce' );
        ?>
        <div class="error">
            <p>
                <?php 
        echo  sprintf( esc_html__( '%1$s requires %2$s to be installed & activated!', 'banner-management-for-woocommerce' ), '<strong>' . esc_html( $vpe_plugin ) . '</strong>', '<a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '" target="_blank"><strong>' . esc_html( $wc_plugin ) . '</strong></a>' ) ;
        ?>
            </p>
        </div>
        <?php 
    }

}
/** Save the product banner settings */
if ( !function_exists( 'set_product_banner_information' ) ) {
    function set_product_banner_information( $post_id )
    {
        /** Check if this is admin side */
        if ( !function_exists( 'get_current_screen' ) ) {
            return;
        }
        $screen = get_current_screen();
        
        if ( isset( $screen ) && ('product' === $screen->post_type || 'page' === $screen->post_type) ) {
            $args = array(
                'term_meta' => array(
                'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                'flags'  => FILTER_REQUIRE_ARRAY,
            ),
            );
            $post_term_meta = filter_input_array( INPUT_POST, $args );
            
            if ( isset( $post_term_meta['term_meta'] ) ) {
                $posted_term_meta = $post_term_meta['term_meta'];
                $t_id = $post_id;
                $term_meta = wcbm_get_category_banner_data( $t_id );
                if ( empty($term_meta) || !is_array( $term_meta ) ) {
                    $term_meta = array();
                }
                if ( !isset( $posted_term_meta['auto_display_banner'] ) ) {
                    $posted_term_meta['auto_display_banner'] = 'off';
                }
                if ( !isset( $posted_term_meta['display_cate_title_flag'] ) ) {
                    $posted_term_meta['display_cate_title_flag'] = 'off';
                }
                $banner_image_arr = ( isset( $posted_term_meta['images'] ) ? $posted_term_meta['images'] : array() );
                $posted_term_meta_image = array();
                if ( !empty($banner_image_arr) && is_array( $banner_image_arr ) ) {
                    foreach ( $banner_image_arr as $key => $val ) {
                        if ( '' !== $val['image_url'] ) {
                            $posted_term_meta_image[] = $val;
                        }
                    }
                }
                $posted_term_meta['images'] = $posted_term_meta_image;
                $cat_keys = array_keys( $posted_term_meta );
                if ( !empty($cat_keys) && is_array( $cat_keys ) ) {
                    foreach ( $cat_keys as $key ) {
                        if ( isset( $posted_term_meta[$key] ) ) {
                            $term_meta[$key] = $posted_term_meta[$key];
                        }
                    }
                }
                //save the option array
                if ( function_exists( 'wcbm_save_cat_banner_data' ) ) {
                    wcbm_save_cat_banner_data( $t_id, $term_meta );
                }
            }
        
        }
    
    }

}
add_action( 'save_post', 'set_product_banner_information' );
/**
 * Start plugin setup wizard before license activation screen
 *
 * @since    3.9.3
 */

if ( !function_exists( 'wcbm_load_plugin_setup_wizard_connect_before' ) ) {
    function wcbm_load_plugin_setup_wizard_connect_before()
    {
        require_once plugin_dir_path( __FILE__ ) . 'admin/partials/dots-plugin-setup-wizard.php';
        ?>
        <div class="tab-panel" id="step5">
            <div class="ds-wizard-wrap">
                <div class="ds-wizard-content">
                    <h2 class="cta-title"><?php 
        echo  esc_html__( 'Activate Plugin', 'banner-management-for-woocommerce' ) ;
        ?></h2>
            <input type="hidden" name="wizard_ajax_nonce" id="wizard_ajax_nonce" value="<?php 
        echo  esc_attr( wp_create_nonce( 'wizard_ajax_nonce' ) ) ;
        ?>">
                </div>
        <?php 
    }
    
    wcbm_fs()->add_action( 'connect/before', 'wcbm_load_plugin_setup_wizard_connect_before' );
}

/**
 * End plugin setup wizard after license activation screen
 *
 * @since    3.9.3
 */

if ( !function_exists( 'wcbm_load_plugin_setup_wizard_connect_after' ) ) {
    function wcbm_load_plugin_setup_wizard_connect_after()
    {
        ?>
        </div>
        </div>
        </div>
        </div>
        <?php 
    }
    
    wcbm_fs()->add_action( 'connect/after', 'wcbm_load_plugin_setup_wizard_connect_after' );
}

add_action( 'before_woocommerce_init', function () {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );