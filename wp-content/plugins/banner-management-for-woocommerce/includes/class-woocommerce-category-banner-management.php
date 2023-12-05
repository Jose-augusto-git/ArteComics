<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    woocommerce_category_banner_management
 * @subpackage woocommerce_category_banner_management/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    woocommerce_category_banner_management
 * @subpackage woocommerce_category_banner_management/includes
 * @author     Multidots <inquiry@multidots.in>
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
class woocommerce_category_banner_management
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      woocommerce_category_banner_management_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected  $loader ;
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    public  $plugin_name ;
    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    public  $version ;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->plugin_name = 'banner-management-for-woocommerce';
        $this->version = '2.1';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $prefix = ( is_network_admin() ? 'network_admin_' : '' );
        add_filter(
            "{$prefix}plugin_action_links_" . plugin_basename( dirname( dirname( __FILE__ ) ) . '/woocommerce-category-banner-management.php' ),
            array( $this, 'plugin_action_links' ),
            10,
            4
        );
    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - woocommerce_category_banner_management_Loader. Orchestrates the hooks of the plugin.
     * - woocommerce_category_banner_management_i18n. Defines internationalization functionality.
     * - woocommerce_category_banner_management_Admin. Defines all hooks for the admin area.
     * - woocommerce_category_banner_management_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-category-banner-management-loader.php';
        /**
         * The file contains the general functions of the plugins.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wcbm-update-functions.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-category-banner-management-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-category-banner-management-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-category-banner-management-public.php';
        /**
         * The file contains the shorcodes of the plugins.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/wcbm-banner-public-shortcodes.php';
        $this->loader = new woocommerce_category_banner_management_Loader();
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the woocommerce_category_banner_management_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new woocommerce_category_banner_management_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }
    
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new woocommerce_category_banner_management_Admin( $this->get_plugin_name(), $this->get_version() );
        //enqueue stylesheets & JavaScripts.
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'wcbm_enqueue_styles_scripts' );
        //Add admin menu.
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'wcbm_menu_page' );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'wcbm_remove_admin_submenus' );
        //edit product cat
        $this->loader->add_action(
            'product_cat_edit_form_fields',
            $plugin_admin,
            'wcbm_product_cat_taxonomy_custom_fields',
            10,
            2
        );
        $this->loader->add_action(
            'edited_product_cat',
            $plugin_admin,
            'wcbm_product_cat_save_taxonomy_custom_fields',
            10,
            2
        );
        //edit product tag
        $this->loader->add_action(
            'product_tag_edit_form_fields',
            $plugin_admin,
            'wcbm_product_cat_taxonomy_custom_fields',
            10,
            2
        );
        $this->loader->add_action(
            'edited_product_tag',
            $plugin_admin,
            'wcbm_product_cat_save_taxonomy_custom_fields',
            10,
            2
        );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'wcbm_dot_store_icon_css' );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'wcbm_active_plugin_main_menu' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'wcbm_send_wizard_data_after_plugin_activation' );
        $this->loader->add_action( 'wp_ajax_wcbm_plugin_setup_wizard_submit', $plugin_admin, 'wcbm_plugin_setup_wizard_submit' );
        /** Product banner html code */
        if ( !function_exists( 'product_banner_settings_callback' ) ) {
            function product_banner_settings_callback()
            {
                $t_id = get_the_id();
                $term_meta = ( function_exists( 'wcbm_get_category_banner_data' ) ? wcbm_get_category_banner_data( $t_id ) : '' );
                
                if ( isset( $term_meta['banner_url_id'] ) && '' !== $term_meta['banner_url_id'] ) {
                    $banner_url = $term_meta['banner_url_id'];
                } else {
                    $banner_url = '';
                }
                
                // Get banner link
                
                if ( isset( $term_meta['banner_link'] ) and '' !== $term_meta['banner_link'] ) {
                    $banner_link = $term_meta['banner_link'];
                } else {
                    $banner_link = '';
                }
                
                
                if ( isset( $term_meta['auto_display_banner'] ) && 'on' === $term_meta['auto_display_banner'] || !isset( $term_meta['auto_display_banner'] ) ) {
                    $auto_display_banner = true;
                } else {
                    $auto_display_banner = false;
                }
                
                
                if ( isset( $term_meta['cat_page_select_image'] ) && '' !== $term_meta['cat_page_select_image'] ) {
                    $cat_page_select_image = $term_meta['cat_page_select_image'];
                } else {
                    $cat_page_select_image = 'cat-single-image';
                }
                
                
                if ( isset( $term_meta['cat_page_select_target'] ) && '' !== $term_meta['cat_page_select_target'] ) {
                    $wbm_shop_page_stored_results_serialize_banner_target = $term_meta['cat_page_select_target'];
                } else {
                    $wbm_shop_page_stored_results_serialize_banner_target = 'blank';
                }
                
                
                if ( isset( $term_meta['cat_page_select_relation'] ) && '' !== $term_meta['cat_page_select_relation'] ) {
                    $wbm_shop_page_stored_results_serialize_banner_relation = $term_meta['cat_page_select_relation'];
                } else {
                    $wbm_shop_page_stored_results_serialize_banner_relation = 'follow';
                }
                
                
                if ( isset( $term_meta['cat_page_select_size'] ) && '' !== $term_meta['cat_page_select_size'] ) {
                    $cat_page_select_size = $term_meta['cat_page_select_size'];
                } else {
                    $cat_page_select_size = '';
                }
                
                
                if ( isset( $term_meta['cat_page_banner_title_color'] ) && '' !== $term_meta['cat_page_banner_title_color'] ) {
                    $cat_page_banner_title_color = $term_meta['cat_page_banner_title_color'];
                } else {
                    $cat_page_banner_title_color = '';
                }
                
                
                if ( isset( $term_meta['cat_page_banner_button_text_color'] ) && '' !== $term_meta['cat_page_banner_button_text_color'] ) {
                    $cat_page_banner_button_text_color = $term_meta['cat_page_banner_button_text_color'];
                } else {
                    $cat_page_banner_button_text_color = '';
                }
                
                
                if ( isset( $term_meta['cat_page_banner_button_bg_color'] ) && '' !== $term_meta['cat_page_banner_button_bg_color'] ) {
                    $cat_page_banner_button_bg_color = $term_meta['cat_page_banner_button_bg_color'];
                } else {
                    $cat_page_banner_button_bg_color = '';
                }
                
                
                if ( isset( $term_meta['cat_banner_title_font_size'] ) && '' !== $term_meta['cat_banner_title_font_size'] ) {
                    $cat_banner_title_font_size = $term_meta['cat_banner_title_font_size'];
                } else {
                    $cat_banner_title_font_size = '';
                }
                
                
                if ( isset( $term_meta['display_cate_title_flag'] ) && 'on' === $term_meta['display_cate_title_flag'] ) {
                    $display_cate_title_flag = true;
                } else {
                    $display_cate_title_flag = false;
                }
                
                
                if ( isset( $term_meta['cat_page_banner_button_text'] ) ) {
                    $cat_page_banner_button_text = $term_meta['cat_page_banner_button_text'];
                } else {
                    $cat_page_banner_button_text = '';
                }
                
                
                if ( isset( $term_meta['cat_page_banner_button_link'] ) ) {
                    $cat_page_banner_button_link = $term_meta['cat_page_banner_button_link'];
                } else {
                    $cat_page_banner_button_link = '';
                }
                
                
                if ( isset( $term_meta['cat_page_banner_description'] ) ) {
                    $cat_page_banner_description = $term_meta['cat_page_banner_description'];
                } else {
                    $cat_page_banner_description = '';
                }
                
                ?>
                <table class="product_detail_page_container">
                    <tr class="form-field auto_display_banner">
                        <th scope="row" valign="top">
                            <label for="auto_display_banner"><?php 
                esc_html_e( 'Enable/Disable', 'banner-management-for-woocommerce' );
                ?></label>
                        </th>
                        <td>
                                <input id="auto_display_banner" name="term_meta[auto_display_banner]" type="checkbox" value="on"
                                    class="auto_display_banner" <?php 
                checked( $auto_display_banner, true );
                ?> />
                        </td>
                    </tr>
                    <?php 
                $cat_page_select_image = 'cat-single-image';
                ?>
                    <tr class="form-field mdwbm_banner_url_form_field hide_cat_single_banner_upload <?php 
                echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'none' ) ;
                ?>"
                        id="cat-single-banner-upload">
                        <th scope="row" valign="top">
                            <label
                                for="mdwbm_upload_single_file_button"><?php 
                esc_html_e( 'Banner Image', 'banner-management-for-woocommerce' );
                ?></label>
                        </th>
                        <td>
                            <a class='mdwbm_upload_single_file_button button' id="mdwbm_upload_single_file_button"
                                uploader_title="<?php 
                esc_attr_e( 'Select File', 'banner-management-for-woocommerce' );
                ?>"
                                uploader_button_text="<?php 
                esc_attr_e( 'Include File', 'banner-management-for-woocommerce' );
                ?>"><?php 
                esc_html_e( 'Upload File', 'banner-management-for-woocommerce' );
                ?></a>
                            <a class='mdwbm_remove_file button'
                                id="mdwbm_remove_file_id"><?php 
                esc_html_e( 'Remove File', 'banner-management-for-woocommerce' );
                ?></a>
                        </td>
                    </tr>
                    <?php 
                if ( is_numeric( $banner_url ) ) {
                    $banner_url = wp_get_attachment_url( $banner_url );
                }
                ?>
                    <tr class="222 form-field mdwbm_banner_image_form_field hide_cat_single_banner_image <?php 
                echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image && '' !== $banner_url ? 'block' : 'none' ) ;
                ?>"
                        id="cat-single-banner-image">
                        <th scope="row"></th>
                        <td id="display_image_id">
                            <img class="cat_banner_single_img_admin <?php 
                echo  ( '' === $banner_url ? 'none' : 'block' ) ;
                ?>"
                                src="<?php 
                echo  esc_url( $banner_url ) ;
                ?>" id="cat_banner_single_img_admin_id" />
                            <input type="hidden" class='mdwbm_image' name='term_meta[banner_url_id]'
                                value='<?php 
                echo  esc_attr( $banner_url ) ;
                ?>' id="mdwbm_image_id" />
                        </td>
                    </tr>
                    <tr class="form-field banner_link_form_field hide_banner_link_form_field <?php 
                echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'none' ) ;
                ?>"
                        id="cat-single-image-link">
                        <th scope="row" valign="top">
                            <label for="cat-single-banner-link"><?php 
                esc_html_e( 'Banner image link', 'banner-management-for-woocommerce' );
                ?></label>
                        </th>
                        <td>

                            <input type="url" id="cat-single-banner-link" name='term_meta[banner_link]'
                                value='<?php 
                echo  esc_attr( $banner_link ) ;
                ?>' />
                            <label class="banner_link_label"
                                for="cat-single-banner-link"><em><?php 
                esc_html_e( 'Where users will be directed if they click on the banner.', 'banner-management-for-woocommerce' );
                ?></em></label>
                        </td>
                    </tr>
                    <tr id="select_banner">
                        <th scope="row"><label class="wbm_leble_setting_css"
                                for="cat_select_target_type"><?php 
                esc_html_e( 'Select Link Target', 'banner-management-for-woocommerce' );
                ?></label>
                        </th>
                        <td><select name="term_meta[cat_page_select_target]" class="cat-select-target-type" id="cat_select_target_type">
                                <option value="self"
                                    <?php 
                selected( $wbm_shop_page_stored_results_serialize_banner_target, 'self' );
                ?>>
                                    <?php 
                esc_html_e( 'Self window', 'banner-management-for-woocommerce' );
                ?>
                                </option>
                                <option value="blank"
                                    <?php 
                selected( $wbm_shop_page_stored_results_serialize_banner_target, 'blank' );
                ?>>
                                    <?php 
                esc_html_e( 'New window', 'banner-management-for-woocommerce' );
                ?>
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr id="select_banner">
                        <th scope="row"><label class="wbm_leble_setting_css"
                                for="cat_select_realtion_type"><?php 
                esc_html_e( 'Select Link Relation', 'banner-management-for-woocommerce' );
                ?></label>
                        </th>
                        <td><select name="term_meta[cat_page_select_relation]" class="cat-select-realtion-type" id="cat_select_realtion_type">
                                <option value="follow"
                                    <?php 
                selected( $wbm_shop_page_stored_results_serialize_banner_relation, 'follow' );
                ?>>
                                    <?php 
                esc_html_e( 'Follow', 'banner-management-for-woocommerce' );
                ?>
                                </option>
                                <option value="nofollow"
                                    <?php 
                selected( $wbm_shop_page_stored_results_serialize_banner_relation, 'nofollow' );
                ?>>
                                    <?php 
                esc_html_e( 'No follow', 'banner-management-for-woocommerce' );
                ?>
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr id="cat_select_size_type_row"
                        class="<?php 
                echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
                ?>">
                        <th scope="row"><label class="wbm_leble_setting_css"
                                for="cat_select_size_type"><?php 
                esc_html_e( 'Select Banner Size', 'banner-management-for-woocommerce' );
                ?></label>
                        </th>
                        <td><select name="term_meta[cat_page_select_size]" class="cat-select-size-type" id="cat_select_size_type">
                                <option value="" <?php 
                selected( $cat_page_select_size, '' );
                ?>>
                                    <?php 
                esc_html_e( '-- Select Banner Size --', 'banner-management-for-woocommerce' );
                ?>
                                </option>
                                <option value="25" <?php 
                selected( $cat_page_select_size, '25' );
                ?>>
                                    <?php 
                esc_html_e( '25%', 'banner-management-for-woocommerce' );
                ?>
                                </option>
                                <option value="50" <?php 
                selected( $cat_page_select_size, '50' );
                ?>>
                                    <?php 
                esc_html_e( '50%', 'banner-management-for-woocommerce' );
                ?>
                                </option>
                                <option value="75" <?php 
                selected( $cat_page_select_size, '75' );
                ?>>
                                    <?php 
                esc_html_e( '75%', 'banner-management-for-woocommerce' );
                ?>
                                </option>
                                <option value="100" <?php 
                selected( $cat_page_select_size, '100' );
                ?>>
                                    <?php 
                esc_html_e( '100%', 'banner-management-for-woocommerce' );
                ?>
                                </option>
                                <option value="1080" <?php 
                selected( $cat_page_select_size, '1080' );
                ?>>
                                    <?php 
                esc_html_e( 'Fixed to container(1080px)', 'banner-management-for-woocommerce' );
                ?>
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr id="display_cate_title_flag_row"
                        class="form-field <?php 
                echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
                ?>">
                        <th scope="row" valign="top">
                            <?php 
                if ( function_exists( 'get_current_screen' ) ) {
                    $screen = get_current_screen();
                }
                if ( isset( $screen ) && !empty($screen) ) {
                    
                    if ( $screen->post_type === 'product' ) {
                        ?>
                                    <label
                                    for="display_cate_title_flag"><?php 
                        esc_html_e( 'Show product Name in the Banner', 'banner-management-for-woocommerce' );
                        ?></label>
                                    <span class="banner-woocommerce-help-tip">
                                        <div class="alert-desc">
                                            <?php 
                        esc_html_e( 'If selected then it will show the default product title on banner with center position.', 'banner-management-for-woocommerce' );
                        ?>
                                        </div>
                                    </span>
                            <?php 
                    } elseif ( $screen->post_type === 'page' ) {
                        ?>
                                    <label
                                    for="display_cate_title_flag"><?php 
                        esc_html_e( 'Show Page Name in the Banner', 'banner-management-for-woocommerce' );
                        ?></label>
                                    <span class="banner-woocommerce-help-tip">
                                        <div class="alert-desc">
                                            <?php 
                        esc_html_e( 'If selected then it will show the default page title on banner with center position.', 'banner-management-for-woocommerce' );
                        ?>
                                        </div>
                                    </span>
                            <?php 
                    }
                
                }
                ?>
                        </th>
                        <td class="top_display">
                            <fieldset>
                                <input id="display_cate_title_flag" name="term_meta[display_cate_title_flag]" type="checkbox" value="on"
                                    class="display_cate_title_flag" <?php 
                checked( $display_cate_title_flag, true );
                ?> />
                                <label class="display_cate_title_flag_label" for="display_cate_title_flag"><em></em></label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr id="cat_page_banner_title_color_row"
                        class="<?php 
                echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
                ?>">
                        <th scope="row">
                            <?php 
                if ( isset( $screen ) && !empty($screen) ) {
                    
                    if ( $screen->post_type === 'product' ) {
                        ?>
                                    <label class="wbm_leble_setting_css" for="cat_page_banner_title_color"><?php 
                        esc_html_e( 'Select Product title color', 'banner-management-for-woocommerce' );
                        ?></label>
                                <?php 
                    } elseif ( $screen->post_type === 'page' ) {
                        ?>
                                    <label class="wbm_leble_setting_css" for="cat_page_banner_title_color"><?php 
                        esc_html_e( 'Select Page title color', 'banner-management-for-woocommerce' );
                        ?></label><?php 
                    }
                
                }
                ?>
                        </th>
                        <td>
                            <input id="cat_page_banner_title_color" name="term_meta[cat_page_banner_title_color]" type="text"
                                value="<?php 
                echo  esc_attr( $cat_page_banner_title_color ) ;
                ?>" class="cat_banner_title_color"
                                data-default-color="#effeff" />
                        </td>
                    </tr>
                    <tr id="cat_page_banner_title_size_row"
                        class="<?php 
                echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
                ?>">
                        <th scope="row">
                            <?php 
                if ( isset( $screen ) && !empty($screen) ) {
                    
                    if ( $screen->post_type === 'product' ) {
                        ?>
                                    <label class="wbm_leble_setting_css" for="cat_page_banner_title_color"><?php 
                        esc_html_e( 'Select Product title size.', 'banner-management-for-woocommerce' );
                        ?></label>
                                <?php 
                    } elseif ( $screen->post_type === 'page' ) {
                        ?>
                                    <label class="wbm_leble_setting_css" for="cat_page_banner_title_color"><?php 
                        esc_html_e( 'Select Page title size.', 'banner-management-for-woocommerce' );
                        ?></label><?php 
                    }
                
                }
                ?>
                            <span class="banner-woocommerce-help-tip">
                                <div class="alert-desc">
                                    <?php 
                esc_html_e( 'Configure the font size of default banner title.', 'banner-management-for-woocommerce' );
                ?>
                                </div>
                            </span>
                        </th>
                        <td>
                            <input id="cat_banner_title_font_size" name="term_meta[cat_banner_title_font_size]" type="range" min="0"
                                max="100" step='1' value="<?php 
                echo  esc_attr( $cat_banner_title_font_size ) ;
                ?>">
                            <div class="counter_total"><?php 
                esc_html_e( $cat_banner_title_font_size, 'banner-management-for-woocommerce' );
                ?></div>
                        </td>
                    </tr>
                    <tr id="cat_page_banner_button_text_row"
                        class="<?php 
                echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
                ?>">
                        <th scope="row">
                            <label class="wbm_leble_setting_css"
                                for="cat_page_banner_title_color"><?php 
                esc_html_e( 'Button Text', 'banner-management-for-woocommerce' );
                ?></label>
                        </th>
                        <td>
                            <input id="cat_page_banner_button_text" name="term_meta[cat_page_banner_button_text]" type="text"
                                value="<?php 
                echo  esc_attr( $cat_page_banner_button_text ) ;
                ?>">

                        </td>
                    </tr>
                    <tr id="cat_page_banner_button_link_row"
                        class="form-field <?php 
                echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
                ?>">
                        <th scope="row">
                            <label class="wbm_leble_setting_css"
                                for="cat_page_banner_button_link"><?php 
                esc_html_e( 'Button Link', 'banner-management-for-woocommerce' );
                ?></label>
                        </th>
                        <td>
                            <input id="cat_page_banner_button_link" name="term_meta[cat_page_banner_button_link]" type="url"
                                value="<?php 
                echo  esc_attr( $cat_page_banner_button_link ) ;
                ?>">

                        </td>
                    </tr>
                    <tr id="cat_page_banner_button_text_color_row"
                        class="<?php 
                echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
                ?>">
                        <th scope="row"><label class="wbm_leble_setting_css"
                                for="cat_page_banner_button_text_color"><?php 
                esc_html_e( 'Select button text color', 'banner-management-for-woocommerce' );
                ?></label>
                        </th>
                        <td>
                            <input id="cat_page_banner_button_text_color" name="term_meta[cat_page_banner_button_text_color]" type="text"
                                value="<?php 
                echo  esc_attr( $cat_page_banner_button_text_color ) ;
                ?>" class="cat_page_banner_button_text_color"
                                data-default-color="#effeff" />
                        </td>
                    </tr>
                    <tr id="cat_page_banner_button_bg_color_row"
                        class="<?php 
                echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
                ?>">
                        <th scope="row"><label class="wbm_leble_setting_css"
                                for="cat_page_banner_titlcat_page_banner_button_bg_colore_color"><?php 
                esc_html_e( 'Select button background color', 'banner-management-for-woocommerce' );
                ?></label>
                        </th>
                        <td>
                            <input id="cat_page_banner_button_bg_color" name="term_meta[cat_page_banner_button_bg_color]" type="text"
                                value="<?php 
                echo  esc_attr( $cat_page_banner_button_bg_color ) ;
                ?>" class="cat_page_banner_button_bg_color"
                                data-default-color="#effeff" />
                        </td>
                    </tr>
                    <tr id="cat_page_banner_description_row"
                        class="<?php 
                echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
                ?>">
                        <th scope="row">
                            <label class="wbm_leble_setting_css"
                                for="cat_page_banner_description"><?php 
                esc_html_e( 'Button Description', 'banner-management-for-woocommerce' );
                ?></label>
                        </th>
                        <td>
                            <textarea name="term_meta[cat_page_banner_description]" id="cat_page_banner_description" rows="5" cols="50"
                                class="large-text"><?php 
                esc_html_e( $cat_page_banner_description, 'banner-management-for-woocommerce' );
                ?></textarea>
                        </td>
                    </tr>
                    <?php 
                ?>
                </table>    
                <?php 
            }
        
        }
        /** Define the metabox for product detail page */
        if ( !function_exists( 'add_banner_on_product_page_metabox' ) ) {
            function add_banner_on_product_page_metabox()
            {
                $screens = [ 'product' ];
                foreach ( $screens as $screen ) {
                    add_meta_box(
                        'product_page_banner_meta_box_id',
                        // Unique ID
                        esc_html( 'Product Banner Settings', 'banner-management-for-woocommerce' ),
                        // Box title
                        'product_banner_settings_callback',
                        // Content callback, must be of type callable
                        $screen
                    );
                }
            }
        
        }
        if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
            $wbm_banner_detail_page_stored_results = wcbm_get_page_banner_data( 'banner_detail' );
        }
        /** Added banner settings meta box for product detail page */
        
        if ( isset( $wbm_banner_detail_page_stored_results ) && !empty($wbm_banner_detail_page_stored_results) ) {
            $wbm_banner_detail_page_stored_results_serialize = $wbm_banner_detail_page_stored_results;
            
            if ( !empty($wbm_banner_detail_page_stored_results_serialize) ) {
                $banner_detail_page_section_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status'] : '' );
                if ( isset( $banner_detail_page_section_banner_enable_status ) && 'on' === $banner_detail_page_section_banner_enable_status ) {
                    add_action( 'add_meta_boxes', 'add_banner_on_product_page_metabox' );
                }
            }
        
        }
        
        $this->loader->add_action(
            'woocommerce_before_main_content',
            $plugin_admin,
            'wcbm_show_category_banner',
            5
        );
        $this->loader->add_action(
            'woocommerce_before_main_content',
            $plugin_admin,
            'wcbm_show_shop_page_banner',
            5
        );
        /** Display checkout page banner here */
        $current_theme = wp_get_theme();
        // gets the current theme
        $this->loader->add_action(
            'woocommerce_before_cart',
            $plugin_admin,
            'wcbm_show_cart_page_banner',
            30
        );
        $this->loader->add_action(
            'woocommerce_before_checkout_form',
            $plugin_admin,
            'wcbm_show_checkout_page_banner',
            30
        );
        
        if ( 'Storefront' === $current_theme->name || 'Storefront' === $current_theme->parent_theme ) {
            $this->loader->add_action(
                'storefront_page_before',
                $plugin_admin,
                'wcbm_show_other_page_banner',
                30
            );
        } elseif ( 'Astra' === $current_theme->name || 'Astra' === $current_theme->parent_theme ) {
            $this->loader->add_action(
                'astra_entry_content_before',
                $plugin_admin,
                'wcbm_show_other_page_banner',
                30
            );
        } elseif ( 'OceanWP' === $current_theme->name || 'OceanWP' === $current_theme->parent_theme ) {
            $this->loader->add_action(
                'ocean_before_content',
                $plugin_admin,
                'wcbm_show_other_page_banner',
                30
            );
        } elseif ( 'Neve' === $current_theme->name || 'Neve' === $current_theme->parent_theme ) {
            $this->loader->add_action(
                'neve_before_content',
                $plugin_admin,
                'wcbm_show_other_page_banner',
                30
            );
        } else {
            $this->loader->add_action(
                'wp_head',
                $plugin_admin,
                'wcbm_show_other_page_banner',
                30
            );
        }
        
        $this->loader->add_action( 'wp_ajax_wbm_save_shop_page_banner_data', $plugin_admin, 'wcbm_save_shop_page_banner_data' );
        $this->loader->add_action( 'wp_ajax_nopriv_wbm_save_shop_page_banner_data', $plugin_admin, 'wcbm_save_shop_page_banner_data' );
        $this->loader->add_action( 'wp_ajax_wcbm_show_category_slider_settings_preview', $plugin_admin, 'wcbm_show_category_slider_settings_preview' );
        $this->loader->add_action( 'wp_ajax_wcbm_show_product_slider_settings_preview', $plugin_admin, 'wcbm_show_product_slider_settings_preview' );
        $product_page_banner_filter = apply_filters( 'product_page_banner_filter', 'woocommerce_before_single_product' );
        $this->loader->add_action(
            $product_page_banner_filter,
            $plugin_admin,
            'wcbm_show_product_banner',
            30
        );
        /** Welcome Screen */
        $this->loader->add_action( 'admin_init', $plugin_admin, 'wcbm_welcome_screen_do_activation_redirect' );
    }
    
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new woocommerce_category_banner_management_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'wcbm_enqueue_styles_scripts' );
        $this->loader->add_filter(
            'woocommerce_locate_template',
            $plugin_public,
            'wcbm_woocommerce_locate_template',
            10,
            3
        );
    }
    
    /**
     * Return the plugin action links.  This will only be called if the plugin
     * is active.
     *
     * @since 1.0.0
     * @param array $actions associative array of action names to anchor tags
     * @return array associative array of plugin action links
     */
    public function plugin_action_links( $actions )
    {
        $custom_actions = array(
            'configure' => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wcbm-banner-setting' ), __( 'Settings', 'banner-management-for-woocommerce' ) ),
            'docs'      => sprintf( '<a href="%s" target="_blank">%s</a>', 'https://docs.thedotstore.com/category/255-premium-plugin-settings', __( 'Docs', 'banner-management-for-woocommerce' ) ),
            'support'   => sprintf( '<a href="%s" target="_blank">%s</a>', 'https://www.thedotstore.com/support/', __( 'Support', 'banner-management-for-woocommerce' ) ),
        );
        // add the links to the front of the actions list
        return array_merge( $custom_actions, $actions );
    }
    
    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }
    
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }
    
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    woocommerce_category_banner_management_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }
    
    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}