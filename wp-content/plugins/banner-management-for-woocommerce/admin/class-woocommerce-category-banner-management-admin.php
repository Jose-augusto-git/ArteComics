<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Woo_Banner_Management
 * @subpackage Woo_Banner_Management/admin
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Banner_Management
 * @subpackage Woo_Banner_Management/admin
 * @author     Multidots <inquiry@multidots.in>
 */
class woocommerce_category_banner_management_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->wcbm_load_dependencies();
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->banner_methods_obj = new WCBM_banners_display_methods( $plugin_name, $version );
    }
    
    /**
     * Load dependencies
     *
     * @since    2.3.0
     */
    private function wcbm_load_dependencies()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcbm-show-banner-methods.php';
    }
    
    /**
     * Register the stylesheets & JavaScript for admin area.
     *
     * @since 2.0.0
     */
    public function wcbm_enqueue_styles_scripts( $hook )
    {
        global  $typenow ;
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
        
        if ( isset( $hook ) && !empty($hook) && false !== strpos( $hook, '_wcbm' ) || isset( $typenow ) && !empty($typenow) && 'product' === $typenow || 'page' === $typenow ) {
            //stylesheets.
            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'css/datepicker.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'image-upload-category-css',
                plugin_dir_url( __FILE__ ) . 'css/woo-image-upload.css',
                array( 'wp-jquery-ui-dialog' ),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'wbm-jquery-ui-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'wbm-select2-css',
                plugin_dir_url( __FILE__ ) . 'css/select2' . $suffix . '.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'jquery-bxslider-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery.bxslider.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'wbm-font-awesome-css',
                plugin_dir_url( __FILE__ ) . 'css/font-awesome' . $suffix . '.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'wcbm-css',
                plugin_dir_url( __FILE__ ) . 'css/style.css',
                array(),
                $this->version,
                'all'
            );
            
            if ( 'product' !== $typenow && 'page' !== $typenow ) {
                wp_enqueue_style(
                    'wcbm-plugin-new-style',
                    plugin_dir_url( __FILE__ ) . 'css/plugin-new-style.css',
                    array(),
                    $this->version,
                    'all'
                );
                wp_enqueue_style(
                    $this->plugin_name . 'plugin-setup-wizard',
                    plugin_dir_url( __FILE__ ) . 'css/plugin-setup-wizard.css',
                    array(),
                    'all'
                );
            }
            
            if ( !(wcbm_fs()->is__premium_only() && wcbm_fs()->can_use_premium_code()) ) {
                wp_enqueue_style(
                    $this->plugin_name . 'upgrade-dashboard-style',
                    plugin_dir_url( __FILE__ ) . 'css/upgrade-dashboard.css',
                    array(),
                    'all'
                );
            }
            //scripts.
            wp_enqueue_script( 'jquery' );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script(
                'wbm-select2',
                plugin_dir_url( __FILE__ ) . 'js/select2' . $suffix . '.js',
                array(),
                $this->version,
                true
            );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/woocommerce-category-banner-management-admin' . $suffix . '.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                'jquery-bxslider',
                plugin_dir_url( __FILE__ ) . 'js/jquery.bxslider.min.js',
                array( 'jquery' ),
                $this->version
            );
            wp_enqueue_media();
            wp_enqueue_script(
                'wbm-admin',
                plugin_dir_url( __FILE__ ) . 'js/wbm-admin' . $suffix . '.js',
                array(
                'jquery',
                'select2',
                'jquery-ui-slider',
                'jquery-ui-datepicker'
            ),
                $this->version,
                true
            );
            wp_localize_script( 'wbm-admin', 'wbmAdminVars', array(
                'alert'                => __( 'Are you sure you want to delete?', 'banner-management-for-woocommerce' ),
                'placeholder'          => __( 'Enter banner image link', 'banner-management-for-woocommerce' ),
                'click'                => __( 'Click here', 'banner-management-for-woocommerce' ),
                'preview'              => __( 'Preview', 'banner-management-for-woocommerce' ),
                'can_use_premium_code' => wp_json_encode( wcbm_fs()->can_use_premium_code() ),
            ) );
            wp_localize_script( $this->plugin_name, 'wbmConditionalVars', array(
                'update_preview' => __( 'Update Preview', 'banner-management-for-woocommerce' ),
                'hide_preview'   => __( 'Hide Preview', 'banner-management-for-woocommerce' ),
                'show_preview'   => __( 'Show Preview', 'banner-management-for-woocommerce' ),
                'wbm_nonce'      => wp_create_nonce( 'ajax_verification' ),
            ) );
        }
    
    }
    
    /**
     *  Set custom menu in WooCommerce-banner-management plugin
     */
    public function wcbm_menu_page()
    {
        global  $GLOBALS ;
        if ( empty($GLOBALS['admin_page_hooks']['dots_store']) ) {
            add_menu_page(
                'DotStore Plugins',
                __( 'DotStore Plugins', 'banner-management-for-woocommerce' ),
                'null',
                'dots_store',
                array( $this, 'dot_store_menu_page' ),
                'dashicons-marker',
                25
            );
        }
        add_submenu_page(
            'dots_store',
            __( 'Banner Management', 'banner-management-for-woocommerce' ),
            __( 'Banner Management', 'banner-management-for-woocommerce' ),
            'manage_options',
            'wcbm-banner-setting',
            array( $this, 'wcbm_options_page' )
        );
        add_submenu_page(
            'dots_store',
            __( 'Slider Settings', 'banner-management-for-woocommerce' ),
            __( 'Slider Settings', 'banner-management-for-woocommerce' ),
            'manage_options',
            'wcbm-sliders-settings',
            array( $this, 'wcbm_sliders_page' )
        );
        add_submenu_page(
            'dots_store',
            'Get Started',
            'Get Started',
            'manage_options',
            'wcbm-plugin-get-started',
            array( $this, 'wcbm_plugin_get_started' )
        );
        add_submenu_page(
            'dots_store',
            'Introduction',
            'Introduction',
            'manage_options',
            'wcbm-plugin-information',
            array( $this, 'wcbm_plugin_quick_info' )
        );
        add_submenu_page(
            'dots_store',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'wcbm-upgrade-dashboard',
            array( $this, 'wbm_free_user_upgrade_page' )
        );
    }
    
    /**
     * Remove product & category slider menu from left sidebar
     *
     */
    public function wcbm_remove_admin_submenus()
    {
        remove_submenu_page( 'dots_store', 'wcbm-sliders-settings' );
        remove_submenu_page( 'dots_store', 'wcbm-plugin-get-started' );
        remove_submenu_page( 'dots_store', 'wcbm-plugin-information' );
        remove_submenu_page( 'dots_store', 'wcbm-upgrade-dashboard' );
    }
    
    /**
     * WooCommerce Product Attachment Option Page HTML
     *
     */
    public function wcbm_options_page()
    {
        include_once plugin_dir_path( __FILE__ ) . 'partials/header/plugin-header.php';
        wp_enqueue_media();
        self::wcbm_plugin_settings_page();
        //include_once( plugin_dir_path( __FILE__ ) . 'partials/header/plugin-sidebar.php' );
    }
    
    /**
     * Product & Category Sliders List Page
     *
     * @since    1.0.0
     */
    public function wcbm_sliders_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcbm-sliders-start-page.php';
    }
    
    /**
     * include banner settings page.
     */
    public function wcbm_plugin_settings_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcbm-banner-settings-page.php';
    }
    
    /**
     * Premium version info page
     *
     */
    public function wbm_free_user_upgrade_page()
    {
        require_once plugin_dir_path( __FILE__ ) . '/partials/dots-upgrade-dashboard.php';
    }
    
    /*
     * include get started page file.
     */
    public function wcbm_plugin_get_started()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcbm-get-started-page.php';
    }
    
    /**
     * include get information page.
     */
    public function wcbm_plugin_quick_info()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/wcbm-information-page.php';
    }
    
    /**
     * Active menu class add
     * 
     * @since 2.4.3
     */
    public function wcbm_active_plugin_main_menu()
    {
        $menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
        if ( isset( $menu_page ) && !empty($menu_page) && false !== strpos( $menu_page, 'wcbm' ) ) {
            ?>
            <script type="text/javascript">
            // Add currunt menu class in main manu
            jQuery(window).load(function () {
                jQuery('a[href="admin.php?page=wcbm-banner-setting"]').parents().addClass('current wp-has-current-submenu');
                jQuery('a[href="admin.php?page=wcbm-banner-setting"]').addClass('current');
            });
            </script>
            <?php 
        }
    }
    
    /**
     * Set the custom html for category edit field
     *
     */
    public function wcbm_product_cat_taxonomy_custom_fields( $tag )
    {
        $t_id = $tag->term_id;
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
		<tr class="form-field wbm-spacing"><th></th></tr>
		<tr class="form-field wbm-settings-title">
			<th scope="row" valign="top">
				<?php 
        
        if ( $tag->taxonomy === 'product_cat' ) {
            ?>
					<h2><?php 
            esc_html_e( 'Category Based Banner Settings', 'banner-management-for-woocommerce' );
            ?></h2>
				<?php 
        } elseif ( $tag->taxonomy === 'product_tag' ) {
            ?>
					<h2><?php 
            esc_html_e( 'Tag Based Banner Settings', 'banner-management-for-woocommerce' );
            ?></h2>
				<?php 
        }
        
        ?>
			</th>
		</tr>
		<tr class="form-field wbm-spacing"><th></th></tr>
		<tr class="form-field auto_display_banner">
			<th scope="row" valign="top">
				<label for="auto_display_banner"><?php 
        esc_html_e( 'Enable/Disable', 'banner-management-for-woocommerce' );
        ?></label>
			</th>
			<td class="auto_display">
				<fieldset>
					<input id="auto_display_banner" name="term_meta[auto_display_banner]" type="checkbox" value="on"
						class="auto_display_banner" <?php 
        checked( $auto_display_banner, true );
        ?> />
					<label class="auto_display_banner_label" for="auto_display_banner"><em></em></label>
				</fieldset>
			</td>
			<td><?php 
        
        if ( $auto_display_banner ) {
            esc_html_e( 'Preview', 'banner-management-for-woocommerce' );
            ?>:
				<a href="<?php 
            echo  esc_url( get_category_link( $t_id ) ) ;
            ?>"
					target="_blank"><?php 
            esc_html_e( 'Click here', 'banner-management-for-woocommerce' );
            ?></a><?php 
        }
        
        ?>
			</td>
		</tr>
		<?php 
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
		<tr class="111 form-field mdwbm_banner_image_form_field hide_cat_single_banner_image <?php 
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
				<label
					for="display_cate_title_flag"><?php 
        esc_html_e( 'Show category default title?', 'banner-management-for-woocommerce' );
        ?></label>
				<span class="banner-woocommerce-help-tip">
					<div class="alert-desc">
						<?php 
        esc_html_e( 'If selected then it will show the default category title on banner with center position.', 'banner-management-for-woocommerce' );
        ?>
					</div>
				</span>
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
			<th scope="row"><label class="wbm_leble_setting_css"
					for="cat_page_banner_title_color"><?php 
        esc_html_e( 'Select Category title color', 'banner-management-for-woocommerce' );
        ?></label>
			</th>
			<td>
				<input id="cat_page_banner_title_color" name="term_meta[cat_page_banner_title_color]" type="text"
					value="<?php 
        echo  esc_attr( $cat_page_banner_title_color ) ;
        ?>" class="cat_banner_title_color" data-default-color="#effeff" />
			</td>
		</tr>
		<tr id="cat_page_banner_title_size_row"
			class="<?php 
        echo  ( '' === $cat_page_select_image || isset( $cat_page_select_image ) && 'cat-single-image' === $cat_page_select_image ? 'block' : 'hide_me' ) ;
        ?>">
			<th scope="row">
				<label class="wbm_leble_setting_css"
					for="cat_page_banner_title_color"><?php 
        esc_html_e( 'Select Category title size.', 'banner-management-for-woocommerce' );
        ?></label>
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
		</fieldset>
		<?php 
    }
    
    /**
     * Add custom css for dotstore icon in admin area
     *
     * @since  3.9.3
     *
     */
    public function wcbm_dot_store_icon_css()
    {
        echo  '<style>
		   	.toplevel_page_dots_store .dashicons-marker::after{content:"";border:3px solid;position:absolute;top:14px;left:15px;border-radius:50%;opacity: 0.6;}
		    li.toplevel_page_dots_store:hover .dashicons-marker::after,li.toplevel_page_dots_store.current .dashicons-marker::after{opacity: 1;}
		    @media only screen and (max-width: 960px){
		    	.toplevel_page_dots_store .dashicons-marker::after{left:14px;}
		    }
			</style>' ;
    }
    
    /**
     * Save the Woocommerce-Banner-Managment Category Data
     *
     * @param  $term_id
     */
    public function wcbm_product_cat_save_taxonomy_custom_fields( $term_id )
    {
        $args = array(
            'term_meta' => array(
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flags'  => FILTER_REQUIRE_ARRAY,
        ),
        );
        $post_term_meta = filter_input_array( INPUT_POST, $args );
        
        if ( isset( $post_term_meta['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = wcbm_get_category_banner_data( $t_id );
            if ( empty($term_meta) || !is_array( $term_meta ) ) {
                $term_meta = array();
            }
            $posted_term_meta_image = array();
            $posted_term_meta = $post_term_meta['term_meta'];
            if ( !isset( $posted_term_meta['auto_display_banner'] ) ) {
                $posted_term_meta['auto_display_banner'] = 'off';
            }
            if ( !isset( $posted_term_meta['display_cate_title_flag'] ) ) {
                $posted_term_meta['display_cate_title_flag'] = 'off';
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
    
    /**
     * Save WCBM shop page setting
     *
     */
    public function wcbm_save_shop_page_banner_data()
    {
        // Security check
        check_ajax_referer( 'ajax_verification', 'security' );
        // Save global settings
        $shop_page_banner_image_results = filter_input( INPUT_POST, 'shop_page_banner_image_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $shop_page_banner_image_results = ( !empty($shop_page_banner_image_results) ? $shop_page_banner_image_results : '' );
        $shop_page_banner_link_results = filter_input( INPUT_POST, 'shop_page_banner_link_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $shop_page_banner_image_size = filter_input( INPUT_POST, 'shop_page_banner_image_size', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $shop_page_banner_link_results = ( !empty($shop_page_banner_link_results) ? $shop_page_banner_link_results : '' );
        $shop_page_banner_image_size = ( !empty($shop_page_banner_image_size) ? $shop_page_banner_image_size : '' );
        $shop_page_banner_enable_or_not_results = filter_input( INPUT_POST, 'shop_page_banner_enable_or_not_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $shop_page_banner_enable_or_not_results = ( !empty($shop_page_banner_enable_or_not_results) ? $shop_page_banner_enable_or_not_results : '' );
        $shop_page_banner_select_target_results = filter_input( INPUT_POST, 'shop_page_banner_select_target_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $shop_page_banner_select_target_results = ( !empty($shop_page_banner_select_target_results) ? $shop_page_banner_select_target_results : '' );
        $shop_page_banner_select_relation_results = filter_input( INPUT_POST, 'shop_page_banner_select_relation_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $shop_page_banner_select_relation_results = ( !empty($shop_page_banner_select_relation_results) ? $shop_page_banner_select_relation_results : '' );
        $cart_page_banner_image_results = filter_input( INPUT_POST, 'cart_page_banner_image_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $cart_page_banner_image_results = ( !empty($cart_page_banner_image_results) ? $cart_page_banner_image_results : '' );
        $cart_page_banner_link_results = filter_input( INPUT_POST, 'cart_page_banner_link_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $cart_page_banner_link_results = ( !empty($cart_page_banner_link_results) ? $cart_page_banner_link_results : '' );
        $cart_page_banner_enable_or_not_results = filter_input( INPUT_POST, 'cart_page_banner_enable_or_not_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $cart_page_banner_enable_or_not_results = ( !empty($cart_page_banner_enable_or_not_results) ? $cart_page_banner_enable_or_not_results : '' );
        $cart_page_banner_select_target_results = filter_input( INPUT_POST, 'cart_page_banner_select_target_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $cart_page_banner_select_target_results = ( !empty($cart_page_banner_select_target_results) ? $cart_page_banner_select_target_results : '' );
        $cart_page_banner_select_relation_results = filter_input( INPUT_POST, 'cart_page_banner_select_relation_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $cart_page_banner_select_relation_results = ( !empty($cart_page_banner_select_relation_results) ? $cart_page_banner_select_relation_results : '' );
        $checkout_page_banner_image_results = filter_input( INPUT_POST, 'checkout_page_banner_image_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $checkout_page_banner_image_results = ( !empty($checkout_page_banner_image_results) ? $checkout_page_banner_image_results : '' );
        $checkout_page_banner_link_results = filter_input( INPUT_POST, 'checkout_page_banner_link_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $checkout_page_banner_link_results = ( !empty($checkout_page_banner_link_results) ? $checkout_page_banner_link_results : '' );
        $checkout_page_banner_target_results = filter_input( INPUT_POST, 'checkout_page_banner_target_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $checkout_page_banner_target_results = ( !empty($checkout_page_banner_target_results) ? $checkout_page_banner_target_results : '' );
        $checkout_page_banner_relation_results = filter_input( INPUT_POST, 'checkout_page_banner_relation_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $checkout_page_banner_relation_results = ( !empty($checkout_page_banner_relation_results) ? $checkout_page_banner_relation_results : '' );
        $checkout_page_banner_enable_or_not_results = filter_input( INPUT_POST, 'checkout_page_banner_enable_or_not_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $checkout_page_banner_enable_or_not_results = ( !empty($checkout_page_banner_enable_or_not_results) ? $checkout_page_banner_enable_or_not_results : '' );
        $thankyou_page_banner_image_results = filter_input( INPUT_POST, 'thankyou_page_banner_image_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $thankyou_page_banner_image_results = ( !empty($thankyou_page_banner_image_results) ? $thankyou_page_banner_image_results : '' );
        $thankyou_page_banner_target_results = filter_input( INPUT_POST, 'thankyou_page_banner_target_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $thankyou_page_banner_target_results = ( !empty($thankyou_page_banner_target_results) ? $thankyou_page_banner_target_results : '' );
        $thankyou_page_banner_relation_results = filter_input( INPUT_POST, 'thankyou_page_banner_relation_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $thankyou_page_banner_relation_results = ( !empty($thankyou_page_banner_relation_results) ? $thankyou_page_banner_relation_results : '' );
        $thankyou_page_banner_link_results = filter_input( INPUT_POST, 'thankyou_page_banner_link_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $thankyou_page_banner_link_results = ( !empty($thankyou_page_banner_link_results) ? $thankyou_page_banner_link_results : '' );
        $thankyou_page_banner_enable_or_not_results = filter_input( INPUT_POST, 'thankyou_page_banner_enable_or_not_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $thankyou_page_banner_enable_or_not_results = ( !empty($thankyou_page_banner_enable_or_not_results) ? $thankyou_page_banner_enable_or_not_results : '' );
        $banner_detail_page_banner_image_results = filter_input( INPUT_POST, 'banner_detail_page_banner_image_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $banner_detail_page_banner_image_results = ( !empty($banner_detail_page_banner_image_results) ? $banner_detail_page_banner_image_results : '' );
        $banner_detail_page_banner_link_results = filter_input( INPUT_POST, 'banner_detail_page_banner_link_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $banner_detail_page_banner_link_results = ( !empty($banner_detail_page_banner_link_results) ? $banner_detail_page_banner_link_results : '' );
        $banner_detail_page_banner_enable_or_not_results = filter_input( INPUT_POST, 'banner_detail_page_banner_enable_or_not_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $banner_detail_page_banner_enable_or_not_results = ( !empty($banner_detail_page_banner_enable_or_not_results) ? $banner_detail_page_banner_enable_or_not_results : '' );
        $banner_detail_page_section_banner_enable_or_not_results = filter_input( INPUT_POST, 'banner_detail_page_section_banner_enable_or_not_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $banner_detail_page_section_banner_enable_or_not_results = ( !empty($banner_detail_page_section_banner_enable_or_not_results) ? $banner_detail_page_section_banner_enable_or_not_results : '' );
        $other_page_banner_image_results = filter_input( INPUT_POST, 'other_page_banner_image_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $other_page_banner_image_results = ( !empty($other_page_banner_image_results) ? $other_page_banner_image_results : '' );
        $other_page_banner_link_results = filter_input( INPUT_POST, 'other_page_banner_link_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $other_page_banner_image_size = filter_input( INPUT_POST, 'other_page_banner_image_size', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $other_page_banner_link_results = ( !empty($other_page_banner_link_results) ? $other_page_banner_link_results : '' );
        $other_page_banner_image_size = ( !empty($other_page_banner_image_size) ? $other_page_banner_image_size : '' );
        $other_page_banner_enable_or_not_results = filter_input( INPUT_POST, 'other_page_banner_enable_or_not_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $other_page_banner_enable_or_not_results = ( !empty($other_page_banner_enable_or_not_results) ? $other_page_banner_enable_or_not_results : '' );
        $other_page_banner_select_target_results = filter_input( INPUT_POST, 'other_page_banner_select_target_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $other_page_banner_select_target_results = ( !empty($other_page_banner_select_target_results) ? $other_page_banner_select_target_results : '' );
        $other_page_banner_select_relation_results = filter_input( INPUT_POST, 'other_page_banner_select_relation_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $other_page_banner_select_relation_results = ( !empty($other_page_banner_select_relation_results) ? $other_page_banner_select_relation_results : '' );
        $product_slider_settings_on_sale_results = filter_input( INPUT_POST, 'product_slider_settings_on_sale_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $product_slider_settings_on_sale_results = ( !empty($product_slider_settings_on_sale_results) ? $product_slider_settings_on_sale_results : '' );
        $product_slider_price_range_results = filter_input( INPUT_POST, 'product_slider_price_range_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $product_slider_price_range_results = ( !empty($product_slider_price_range_results) ? $product_slider_price_range_results : '' );
        $product_cat_slider_settings_by_title_results = filter_input( INPUT_POST, 'product_cat_slider_settings_by_title_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $product_cat_slider_settings_by_title_results = ( !empty($product_cat_slider_settings_by_title_results) ? $product_cat_slider_settings_by_title_results : '' );
        $product_cat_slider_settings_feature_img_results = filter_input( INPUT_POST, 'product_cat_slider_settings_feature_img_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $product_cat_slider_settings_feature_img_results = ( !empty($product_cat_slider_settings_feature_img_results) ? $product_cat_slider_settings_feature_img_results : '' );
        $product_cat_slider_settings_by_desc_results = filter_input( INPUT_POST, 'product_cat_slider_settings_by_desc_results', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $product_cat_slider_settings_by_desc_results = ( !empty($product_cat_slider_settings_by_desc_results) ? $product_cat_slider_settings_by_desc_results : '' );
        $shop_page_data_stored_array = array(
            'shop_page_banner_image_src'       => $shop_page_banner_image_results,
            'shop_page_banner_link_src'        => $shop_page_banner_link_results,
            'shop_page_banner_image_size'      => $shop_page_banner_image_size,
            'shop_page_banner_enable_status'   => $shop_page_banner_enable_or_not_results,
            'shop_page_banner_select_target'   => $shop_page_banner_select_target_results,
            'shop_page_banner_select_relation' => $shop_page_banner_select_relation_results,
        );
        $cart_page_data_stored_array = array(
            'cart_page_banner_image_src'       => $cart_page_banner_image_results,
            'cart_page_banner_link_src'        => $cart_page_banner_link_results,
            'cart_page_banner_enable_status'   => $cart_page_banner_enable_or_not_results,
            'cart_page_banner_select_target'   => $cart_page_banner_select_target_results,
            'cart_page_banner_select_relation' => $cart_page_banner_select_relation_results,
        );
        $checkout_page_data_stored_array = array(
            'checkout_page_banner_image_src'     => $checkout_page_banner_image_results,
            'checkout_page_banner_link_src'      => $checkout_page_banner_link_results,
            'checkout_page_banner_enable_status' => $checkout_page_banner_enable_or_not_results,
            'checkout_page_banner_target'        => $checkout_page_banner_target_results,
            'checkout_page_banner_relation'      => $checkout_page_banner_relation_results,
        );
        $thankyou_page_data_stored_array = array(
            'thankyou_page_banner_image_src'     => $thankyou_page_banner_image_results,
            'thankyou_page_banner_link_src'      => $thankyou_page_banner_link_results,
            'thankyou_page_banner_enable_status' => $thankyou_page_banner_enable_or_not_results,
            'thankyou_page_banner_target'        => $thankyou_page_banner_target_results,
            'thankyou_page_banner_relation'      => $thankyou_page_banner_relation_results,
        );
        $banner_detail_page_data_stored_array = array(
            'banner_detail_page_banner_image_src'             => $banner_detail_page_banner_image_results,
            'banner_detail_page_banner_link_src'              => $banner_detail_page_banner_link_results,
            'banner_detail_page_banner_enable_status'         => $banner_detail_page_banner_enable_or_not_results,
            'banner_detail_page_section_banner_enable_status' => $banner_detail_page_section_banner_enable_or_not_results,
            'banner_detail_page_banner_target'                => $banner_detail_page_banner_target_dots_results,
            'banner_detail_page_banner_relation'              => $banner_detail_page_banner_relation_dots_results,
        );
        $other_page_data_stored_array = array(
            'other_page_banner_image_src'       => $other_page_banner_image_results,
            'other_page_banner_link_src'        => $other_page_banner_link_results,
            'other_page_banner_image_size'      => $other_page_banner_image_size,
            'other_page_banner_enable_status'   => $other_page_banner_enable_or_not_results,
            'other_page_banner_select_target'   => $other_page_banner_select_target_results,
            'other_page_banner_select_relation' => $other_page_banner_select_relation_results,
        );
        $wbm_prod_slider_data_stored_array = array(
            'product_slider_settings_on_sale'         => $product_slider_settings_on_sale_results,
            'product_slider_settings_featured_prod'   => $product_slider_settings_featured_prod_results,
            'product_slider_price_range'              => $product_slider_price_range_results,
            'product_cat_slider_settings_cat'         => $product_cat_slider_settings_cat_results,
            'product_cat_slider_settings_by_title'    => $product_cat_slider_settings_by_title_results,
            'product_cat_slider_settings_feature_img' => $product_cat_slider_settings_feature_img_results,
            'product_cat_slider_settings_by_desc'     => $product_cat_slider_settings_by_desc_results,
        );
        
        if ( function_exists( 'wcbm_save_page_banner_data' ) ) {
            wcbm_save_page_banner_data( 'shop', $shop_page_data_stored_array );
            wcbm_save_page_banner_data( 'cart', $cart_page_data_stored_array );
            wcbm_save_page_banner_data( 'checkout', $checkout_page_data_stored_array );
            wcbm_save_page_banner_data( 'thankyou', $thankyou_page_data_stored_array );
            wcbm_save_page_banner_data( 'banner_detail', $banner_detail_page_data_stored_array );
            wcbm_save_page_banner_data( 'other_pages', $other_page_data_stored_array );
            wcbm_save_page_banner_data( 'product_sliders', $wbm_prod_slider_data_stored_array );
        }
        
        die;
    }
    
    /**
     * Show Category Banner In Category Page
     *
     */
    public function wcbm_show_category_banner()
    {
        // Make sure this is a product category page
        $this->banner_methods_obj->wcbm_display_category_banner_html();
    }
    
    /**
     * Show Category Banner In Category Page
     *
     */
    public function wcbm_show_product_banner()
    {
        // Make sure this is a product page
        $this->banner_methods_obj->wcbm_display_product_banner_html();
    }
    
    /**
     * Function For display the banner image in shop page
     *
     *
     */
    public function wcbm_show_shop_page_banner()
    {
        $this->banner_methods_obj->wcbm_show_shop_page_banner_method();
    }
    
    /**
     * Function For display the banner image in website other pages
     *
     *
     */
    public function wcbm_show_other_page_banner()
    {
        $this->banner_methods_obj->wcbm_show_other_page_banner_method();
    }
    
    /**
     * Function For display banner image in cart page
     *
     */
    public function wcbm_show_cart_page_banner()
    {
        $this->banner_methods_obj->wcbm_show_cart_page_banner_method();
    }
    
    /**
     * Function For display banner image in check out page
     *
     */
    public function wcbm_show_checkout_page_banner()
    {
        $this->banner_methods_obj->wcbm_show_checkout_page_banner_method();
    }
    
    /**
     * Get dynamic promotional bar of plugin
     *
     * @param   String  $plugin_slug  slug of the plugin added in the site option
     * @since    3.9.3
     * 
     * @return  null
     */
    public function wcbm_get_promotional_bar( $plugin_slug = '' )
    {
        $promotional_bar_upi_url = WCBM_STORE_URL . '/wp-json/dpb-promotional-banner/v2/dpb-promotional-banner?' . wp_rand();
        $promotional_banner_request = wp_remote_get( $promotional_bar_upi_url );
        //phpcs:ignore
        
        if ( empty($promotional_banner_request->errors) ) {
            $promotional_banner_request_body = $promotional_banner_request['body'];
            $promotional_banner_request_body = json_decode( $promotional_banner_request_body, true );
            echo  '<div class="dynamicbar_wrapper">' ;
            if ( !empty($promotional_banner_request_body) && is_array( $promotional_banner_request_body ) ) {
                foreach ( $promotional_banner_request_body as $promotional_banner_request_body_data ) {
                    $promotional_banner_id = $promotional_banner_request_body_data['promotional_banner_id'];
                    $promotional_banner_cookie = $promotional_banner_request_body_data['promotional_banner_cookie'];
                    $promotional_banner_image = $promotional_banner_request_body_data['promotional_banner_image'];
                    $promotional_banner_description = $promotional_banner_request_body_data['promotional_banner_description'];
                    $promotional_banner_button_group = $promotional_banner_request_body_data['promotional_banner_button_group'];
                    $dpb_schedule_campaign_type = $promotional_banner_request_body_data['dpb_schedule_campaign_type'];
                    $promotional_banner_target_audience = $promotional_banner_request_body_data['promotional_banner_target_audience'];
                    
                    if ( !empty($promotional_banner_target_audience) ) {
                        $plugin_keys = array();
                        
                        if ( is_array( $promotional_banner_target_audience ) ) {
                            foreach ( $promotional_banner_target_audience as $list ) {
                                $plugin_keys[] = $list['value'];
                            }
                        } else {
                            $plugin_keys[] = $promotional_banner_target_audience['value'];
                        }
                        
                        $display_banner_flag = false;
                        if ( in_array( 'all_customers', $plugin_keys ) || in_array( $plugin_slug, $plugin_keys ) ) {
                            $display_banner_flag = true;
                        }
                    }
                    
                    if ( true === $display_banner_flag ) {
                        
                        if ( 'default' === $dpb_schedule_campaign_type ) {
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $banner_cookie_visible_once = filter_input( INPUT_COOKIE, 'banner_show_once_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $flag = false;
                            
                            if ( empty($banner_cookie_show) && empty($banner_cookie_visible_once) ) {
                                setcookie( 'banner_show_' . $promotional_banner_cookie, 'yes', time() + 86400 * 7 );
                                //phpcs:ignore
                                setcookie( 'banner_show_once_' . $promotional_banner_cookie, 'yes' );
                                //phpcs:ignore
                                $flag = true;
                            }
                            
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            
                            if ( !empty($banner_cookie_show) || true === $flag ) {
                                $banner_cookie = filter_input( INPUT_COOKIE, 'banner_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                                $banner_cookie = ( isset( $banner_cookie ) ? $banner_cookie : '' );
                                
                                if ( empty($banner_cookie) && 'yes' !== $banner_cookie ) {
                                    ?>
                            	<div class="dpb-popup <?php 
                                    echo  ( isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner' ) ;
                                    ?>">
                                    <?php 
                                    
                                    if ( !empty($promotional_banner_image) ) {
                                        ?>
                                        <img src="<?php 
                                        echo  esc_url( $promotional_banner_image ) ;
                                        ?>"/>
                                        <?php 
                                    }
                                    
                                    ?>
                                    <div class="dpb-popup-meta">
                                        <p>
                                            <?php 
                                    echo  wp_kses_post( str_replace( array( '<p>', '</p>' ), '', $promotional_banner_description ) ) ;
                                    if ( !empty($promotional_banner_button_group) ) {
                                        foreach ( $promotional_banner_button_group as $promotional_banner_button_group_data ) {
                                            ?>
                                                    <a href="<?php 
                                            echo  esc_url( $promotional_banner_button_group_data['promotional_banner_button_link'] ) ;
                                            ?>" target="_blank"><?php 
                                            echo  esc_html( $promotional_banner_button_group_data['promotional_banner_button_text'] ) ;
                                            ?></a>
                                                    <?php 
                                        }
                                    }
                                    ?>
                                    	</p>
                                    </div>
                                    <a href="javascript:void(0);" data-bar-id="<?php 
                                    echo  esc_attr( $promotional_banner_id ) ;
                                    ?>" data-popup-name="<?php 
                                    echo  ( isset( $promotional_banner_cookie ) ? esc_attr( $promotional_banner_cookie ) : 'default-banner' ) ;
                                    ?>" class="dpbpop-close"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><path id="Icon_material-close" data-name="Icon material-close" d="M17.5,8.507,16.493,7.5,12.5,11.493,8.507,7.5,7.5,8.507,11.493,12.5,7.5,16.493,8.507,17.5,12.5,13.507,16.493,17.5,17.5,16.493,13.507,12.5Z" transform="translate(-7.5 -7.5)" fill="#acacac"/></svg></a>
                                </div>
                                <?php 
                                }
                            
                            }
                        
                        } else {
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $banner_cookie_visible_once = filter_input( INPUT_COOKIE, 'banner_show_once_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $flag = false;
                            
                            if ( empty($banner_cookie_show) && empty($banner_cookie_visible_once) ) {
                                setcookie( 'banner_show_' . $promotional_banner_cookie, 'yes' );
                                //phpcs:ignore
                                setcookie( 'banner_show_once_' . $promotional_banner_cookie, 'yes' );
                                //phpcs:ignore
                                $flag = true;
                            }
                            
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            
                            if ( !empty($banner_cookie_show) || true === $flag ) {
                                $banner_cookie = filter_input( INPUT_COOKIE, 'banner_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                                $banner_cookie = ( isset( $banner_cookie ) ? $banner_cookie : '' );
                                
                                if ( empty($banner_cookie) && 'yes' !== $banner_cookie ) {
                                    ?>
                    			<div class="dpb-popup <?php 
                                    echo  ( isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner' ) ;
                                    ?>">
                                    <?php 
                                    
                                    if ( !empty($promotional_banner_image) ) {
                                        ?>
                                            <img src="<?php 
                                        echo  esc_url( $promotional_banner_image ) ;
                                        ?>"/>
                                        <?php 
                                    }
                                    
                                    ?>
                                    <div class="dpb-popup-meta">
                                        <p>
                                            <?php 
                                    echo  wp_kses_post( str_replace( array( '<p>', '</p>' ), '', $promotional_banner_description ) ) ;
                                    if ( !empty($promotional_banner_button_group) ) {
                                        foreach ( $promotional_banner_button_group as $promotional_banner_button_group_data ) {
                                            ?>
                                                    <a href="<?php 
                                            echo  esc_url( $promotional_banner_button_group_data['promotional_banner_button_link'] ) ;
                                            ?>" target="_blank"><?php 
                                            echo  esc_html( $promotional_banner_button_group_data['promotional_banner_button_text'] ) ;
                                            ?></a>
                                                    <?php 
                                        }
                                    }
                                    ?>
                                        </p>
                                    </div>
                                    <a href="javascript:void(0);" data-bar-id="<?php 
                                    echo  esc_attr( $promotional_banner_id ) ;
                                    ?>" data-popup-name="<?php 
                                    echo  ( isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner' ) ;
                                    ?>" class="dpbpop-close"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><path id="Icon_material-close" data-name="Icon material-close" d="M17.5,8.507,16.493,7.5,12.5,11.493,8.507,7.5,7.5,8.507,11.493,12.5,7.5,16.493,8.507,17.5,12.5,13.507,16.493,17.5,17.5,16.493,13.507,12.5Z" transform="translate(-7.5 -7.5)" fill="#acacac"/></svg></a>
                                </div>
                                <?php 
                                }
                            
                            }
                        
                        }
                    
                    }
                }
            }
            echo  '</div>' ;
        }
    
    }
    
    /**
     * Save For Later welcome page
     *
     */
    public function wcbm_welcome_screen_do_activation_redirect()
    {
        // if no activation redirect
        if ( !get_transient( '_welcome_screen_activation_redirect_banner_management' ) ) {
            return;
        }
        // Delete the redirect transient
        delete_transient( '_welcome_screen_activation_redirect_banner_management' );
        // if activating from network, or bulk
        $activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( is_network_admin() || isset( $activate_multi ) && !empty($activate_multi) ) {
            //return;
        }
        // Redirect to save for later welcome  page
        wp_safe_redirect( add_query_arg( array(
            'page' => 'wcbm-plugin-get-started',
        ), admin_url( 'admin.php' ) ) );
        exit;
    }
    
    /**
     * Allowed html tags used for wp_kses function
     *
     * @return array
     * @since     2.3.0
     *
     */
    public function wcbm_allowed_html_tags()
    {
        $allowed_tags = array(
            'a'        => array(
            'href'         => array(),
            'title'        => array(),
            'class'        => array(),
            'target'       => array(),
            'data-tooltip' => array(),
        ),
            'ul'       => array(
            'class' => array(),
        ),
            'li'       => array(
            'class' => array(),
        ),
            'div'      => array(
            'class' => array(),
            'id'    => array(),
        ),
            'select'   => array(
            'rel-id'   => array(),
            'id'       => array(),
            'name'     => array(),
            'class'    => array(),
            'multiple' => array(),
            'style'    => array(),
        ),
            'input'    => array(
            'id'         => array(),
            'value'      => array(),
            'name'       => array(),
            'class'      => array(),
            'type'       => array(),
            'data-index' => array(),
            'style'      => array(),
            'readonly'   => array(),
        ),
            'textarea' => array(
            'id'    => array(),
            'name'  => array(),
            'class' => array(),
        ),
            'option'   => array(
            'id'       => array(),
            'selected' => array(),
            'name'     => array(),
            'value'    => array(),
        ),
            'br'       => array(),
            'p'        => array(),
            'b'        => array(
            'style' => array(),
        ),
            'em'       => array(),
            'strong'   => array(),
            'i'        => array(
            'class' => array(),
        ),
            'span'     => array(
            'class' => array(),
        ),
            'small'    => array(
            'class' => array(),
        ),
            'label'    => array(
            'class' => array(),
            'id'    => array(),
            'for'   => array(),
        ),
        );
        return $allowed_tags;
    }
    
    /**
     * Display message in admin side
     *
     * @param string $message
     * @param string $page
     *
     * @return bool
     * @since 2.3.0
     *
     */
    public function wcbm_updated_message( $message, $page, $validation_msg )
    {
        if ( empty($message) ) {
            return false;
        }
        
        if ( 'wcbm-sliders-settings' === $page ) {
            
            if ( 'created' === $message ) {
                $updated_message = esc_html__( "Slider rule created.", 'banner-management-for-woocommerce' );
            } elseif ( 'saved' === $message ) {
                $updated_message = esc_html__( "Slider rule updated.", 'banner-management-for-woocommerce' );
            } elseif ( 'deleted' === $message ) {
                $updated_message = esc_html__( "Slider rule deleted.", 'banner-management-for-woocommerce' );
            } elseif ( 'duplicated' === $message ) {
                $updated_message = esc_html__( "Slider rule duplicated.", 'banner-management-for-woocommerce' );
            } elseif ( 'disabled' === $message ) {
                $updated_message = esc_html__( "Slider rule disabled.", 'banner-management-for-woocommerce' );
            } elseif ( 'enabled' === $message ) {
                $updated_message = esc_html__( "Slider rule enabled.", 'banner-management-for-woocommerce' );
            }
            
            
            if ( 'failed' === $message ) {
                $failed_messsage = esc_html__( "There was an error with saving data.", 'banner-management-for-woocommerce' );
            } elseif ( 'nonce_check' === $message ) {
                $failed_messsage = esc_html__( "There was an error with security check.", 'banner-management-for-woocommerce' );
            }
            
            if ( 'validated' === $message ) {
                $validated_messsage = esc_html( $validation_msg );
            }
        } else {
            if ( 'saved' === $message ) {
                $updated_message = esc_html__( "Settings saved successfully", 'banner-management-for-woocommerce' );
            }
            if ( 'nonce_check' === $message ) {
                $failed_messsage = esc_html__( "There was an error with security check.", 'banner-management-for-woocommerce' );
            }
            if ( 'validated' === $message ) {
                $validated_messsage = esc_html( $validation_msg );
            }
        }
        
        
        if ( !empty($updated_message) ) {
            echo  sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) ) ;
            return false;
        }
        
        
        if ( !empty($failed_messsage) ) {
            echo  sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $failed_messsage ) ) ;
            return false;
        }
        
        
        if ( !empty($validated_messsage) ) {
            echo  sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $validated_messsage ) ) ;
            return false;
        }
    
    }
    
    /**
     * Show preview of category slider settings
     *
     * @since 2.3.0
     */
    public function wcbm_show_category_slider_settings_preview()
    {
        // Security check
        check_ajax_referer( 'ajax_verification', 'security' );
        // Show category slider preview
        $get_wbm_cat_slider_status = filter_input( INPUT_POST, 'wbm_cat_slider_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_filter_categories = filter_input( INPUT_POST, 'wbm_filter_categories', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_choose_categories = filter_input(
            INPUT_POST,
            'wbm_choose_categories',
            FILTER_SANITIZE_NUMBER_INT,
            FILTER_REQUIRE_ARRAY
        );
        $get_wbm_total_cat_show = filter_input( INPUT_POST, 'wbm_total_cat_show', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_order_by = filter_input( INPUT_POST, 'wbm_cat_order_by', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_order = filter_input( INPUT_POST, 'wbm_cat_order', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_sec_title_status = filter_input( INPUT_POST, 'wbm_cat_sec_title_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_slider_rule_name = filter_input( INPUT_POST, 'wbm_cat_slider_rule_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_title_color = filter_input( INPUT_POST, 'wbm_cat_title_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_title_top_margin = filter_input( INPUT_POST, 'wbm_cat_title_top_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_title_right_margin = filter_input( INPUT_POST, 'wbm_cat_title_right_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_title_bottom_margin = filter_input( INPUT_POST, 'wbm_cat_title_bottom_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_title_left_margin = filter_input( INPUT_POST, 'wbm_cat_title_left_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_title_unit_margin = filter_input( INPUT_POST, 'wbm_cat_title_unit_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_make_card_style = filter_input( INPUT_POST, 'wbm_cat_make_card_style', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_card_top_border = filter_input( INPUT_POST, 'wbm_card_top_border', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_card_right_border = filter_input( INPUT_POST, 'wbm_card_right_border', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_card_bottom_border = filter_input( INPUT_POST, 'wbm_card_bottom_border', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_card_left_border = filter_input( INPUT_POST, 'wbm_card_left_border', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_card_border_style = filter_input( INPUT_POST, 'wbm_card_border_style', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_card_border_color = filter_input( INPUT_POST, 'wbm_card_border_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_card_border_hov_color = filter_input( INPUT_POST, 'wbm_card_border_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_card_bg_color = filter_input( INPUT_POST, 'wbm_card_bg_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_card_bg_hov_color = filter_input( INPUT_POST, 'wbm_card_bg_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_inner_top_padding = filter_input( INPUT_POST, 'wbm_inner_top_padding', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_inner_right_padding = filter_input( INPUT_POST, 'wbm_inner_right_padding', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_inner_bottom_padding = filter_input( INPUT_POST, 'wbm_inner_bottom_padding', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_inner_left_padding = filter_input( INPUT_POST, 'wbm_inner_left_padding', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_inner_unit_padding = filter_input( INPUT_POST, 'wbm_inner_unit_padding', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_name_status = filter_input( INPUT_POST, 'wbm_cat_name_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_name_color = filter_input( INPUT_POST, 'wbm_cat_name_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_name_top_margin = filter_input( INPUT_POST, 'wbm_cat_name_top_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_name_right_margin = filter_input( INPUT_POST, 'wbm_cat_name_right_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_name_bottom_margin = filter_input( INPUT_POST, 'wbm_cat_name_bottom_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_name_left_margin = filter_input( INPUT_POST, 'wbm_cat_name_left_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_name_unit_margin = filter_input( INPUT_POST, 'wbm_cat_name_unit_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_prod_count = filter_input( INPUT_POST, 'wbm_cat_prod_count', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_count_position = filter_input( INPUT_POST, 'wbm_cat_count_position', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_count_before = filter_input( INPUT_POST, 'wbm_prod_count_before', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_count_after = filter_input( INPUT_POST, 'wbm_prod_count_after', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_count_color = filter_input( INPUT_POST, 'wbm_prod_count_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_desc_status = filter_input( INPUT_POST, 'wbm_cat_desc_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_desc_color = filter_input( INPUT_POST, 'wbm_cat_desc_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_desc_top_margin = filter_input( INPUT_POST, 'wbm_cat_desc_top_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_desc_right_margin = filter_input( INPUT_POST, 'wbm_cat_desc_right_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_desc_bottom_margin = filter_input( INPUT_POST, 'wbm_cat_desc_bottom_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_desc_left_margin = filter_input( INPUT_POST, 'wbm_cat_desc_left_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_desc_unit_margin = filter_input( INPUT_POST, 'wbm_cat_desc_unit_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_button = filter_input( INPUT_POST, 'wbm_shop_now_button', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_label = filter_input( INPUT_POST, 'wbm_shop_now_label', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_color = filter_input( INPUT_POST, 'wbm_shop_now_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_hov_color = filter_input( INPUT_POST, 'wbm_shop_now_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_bg_color = filter_input( INPUT_POST, 'wbm_shop_now_bg_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_bg_hov_color = filter_input( INPUT_POST, 'wbm_shop_now_bg_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_all_border = filter_input( INPUT_POST, 'wbm_shop_now_all_border', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_border_style = filter_input( INPUT_POST, 'wbm_shop_now_border_style', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_border_color = filter_input( INPUT_POST, 'wbm_shop_now_border_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_border_hov_color = filter_input( INPUT_POST, 'wbm_shop_now_border_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_top_margin = filter_input( INPUT_POST, 'wbm_shop_now_top_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_right_margin = filter_input( INPUT_POST, 'wbm_shop_now_right_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_bottom_margin = filter_input( INPUT_POST, 'wbm_shop_now_bottom_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_left_margin = filter_input( INPUT_POST, 'wbm_shop_now_left_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_unit_margin = filter_input( INPUT_POST, 'wbm_shop_now_unit_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_shop_now_link_target = filter_input( INPUT_POST, 'wbm_shop_now_link_target', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_thumbnail = filter_input( INPUT_POST, 'wbm_cat_thumbnail', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_thumbnail_size = filter_input( INPUT_POST, 'wbm_cat_thumbnail_size', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_thumb_border = filter_input( INPUT_POST, 'wbm_cat_thumb_border', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_thumb_all_border = filter_input( INPUT_POST, 'wbm_thumb_all_border', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_thumb_border_style = filter_input( INPUT_POST, 'wbm_thumb_border_style', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_thumb_border_color = filter_input( INPUT_POST, 'wbm_thumb_border_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_thumb_border_hov_color = filter_input( INPUT_POST, 'wbm_thumb_border_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_thumb_top_margin = filter_input( INPUT_POST, 'wbm_thumb_top_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_thumb_right_margin = filter_input( INPUT_POST, 'wbm_thumb_right_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_thumb_bottom_margin = filter_input( INPUT_POST, 'wbm_thumb_bottom_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_thumb_left_margin = filter_input( INPUT_POST, 'wbm_thumb_left_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_thumb_unit_margin = filter_input( INPUT_POST, 'wbm_thumb_unit_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_thumb_mode = filter_input( INPUT_POST, 'wbm_cat_thumb_mode', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_autoplay = filter_input( INPUT_POST, 'wbm_cat_autoplay', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_autoplay_speed = filter_input( INPUT_POST, 'wbm_cat_autoplay_speed', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_scroll_speed = filter_input( INPUT_POST, 'wbm_cat_scroll_speed', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_pause_on_hov = filter_input( INPUT_POST, 'wbm_cat_pause_on_hov', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_infinite_loop = filter_input( INPUT_POST, 'wbm_cat_infinite_loop', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_auto_height = filter_input( INPUT_POST, 'wbm_cat_auto_height', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_nav_color = filter_input( INPUT_POST, 'wbm_cat_nav_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_nav_hov_color = filter_input( INPUT_POST, 'wbm_cat_nav_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_nav_bg_color = filter_input( INPUT_POST, 'wbm_cat_nav_bg_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_nav_bg_hov_color = filter_input( INPUT_POST, 'wbm_cat_nav_bg_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_nav_all_border = filter_input( INPUT_POST, 'wbm_cat_nav_all_border', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_nav_border_style = filter_input( INPUT_POST, 'wbm_cat_nav_border_style', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_nav_border_color = filter_input( INPUT_POST, 'wbm_cat_nav_border_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_nav_border_hov_color = filter_input( INPUT_POST, 'wbm_cat_nav_border_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_pager_color = filter_input( INPUT_POST, 'wbm_cat_pager_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_pager_active_color = filter_input( INPUT_POST, 'wbm_cat_pager_active_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_cat_pager_hov_color = filter_input( INPUT_POST, 'wbm_cat_pager_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_dswbm_slider_mode = filter_input( INPUT_POST, 'dswbm_slider_mode', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_cat_thumb_zoom = filter_input( INPUT_POST, 'wbm_cat_thumb_zoom', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_layout_presets = filter_input( INPUT_POST, 'wbm_layout_presets', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_cat_thumbnail_shape = filter_input( INPUT_POST, 'wbm_cat_thumbnail_shape', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_cat_thumb_border_radius = filter_input( INPUT_POST, 'wbm_cat_thumb_border_radius', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_cat_slide_to_scroll_large_desktop = filter_input( INPUT_POST, 'wbm_cat_slide_to_scroll_large_desktop', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_cat_slide_to_scroll_desktop = filter_input( INPUT_POST, 'wbm_cat_slide_to_scroll_desktop', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_cat_slide_to_scroll_laptop = filter_input( INPUT_POST, 'wbm_cat_slide_to_scroll_laptop', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_cat_slide_to_scroll_tablet = filter_input( INPUT_POST, 'wbm_cat_slide_to_scroll_tablet', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_cat_slide_to_scroll_mobile = filter_input( INPUT_POST, 'wbm_cat_slide_to_scroll_mobile', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_cat_touch_swipe = filter_input( INPUT_POST, 'wbm_cat_touch_swipe', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_thumb_shadow_v_offset = '';
        $wbm_thumb_shadow_h_offset = '';
        $wbm_thumb_shadow_blur = '';
        $wbm_thumb_shadow_spread = '';
        $wbm_thumb_shadow_shadow = '';
        $wbm_thumb_shadow_color = '';
        $wbm_thumb_shadow_hover_color = '';
        $wbm_cat_typo_on_off = '';
        $wbm_cat_title_typo_font_family = '';
        $wbm_cat_title_typo_font_style = '';
        $wbm_cat_title_typo_text_align = '';
        $wbm_cat_title_typo_text_transform = '';
        $wbm_cat_title_typo_text_font_size = '';
        $wbm_cat_title_typo_text_line_height = '';
        $wbm_cat_title_typo_text_letter_spacing = '';
        $wbm_cat_typo_shopbtn_on_off = '';
        $wbm_cat_title_typo_shopbtn_font_family = '';
        $wbm_cat_title_typo_shopbtn_font_style = '';
        $wbm_cat_title_typo_shopbtn_text_align = '';
        $wbm_cat_title_typo_shopbtn_text_transform = '';
        $wbm_cat_title_typo_shopbtn_text_font_size = '';
        $wbm_cat_title_typo_shopbtn_text_line_height = '';
        $wbm_cat_title_typo_shopbtn_text_letter_spacing = '';
        $wbm_cat_content_position = '';
        $wbm_exclude_categories = '';
        $dswbm_child_categories = '';
        $wbm_choose_categories_pnc_cat = array();
        $wbm_cat_no_cols_large_desktop = '';
        $wbm_cat_no_cols_desktop = '';
        $wbm_cat_no_cols_laptop = '';
        $wbm_cat_no_cols_tablet = '';
        $wbm_cat_no_cols_mobile = '';
        $wbm_parent_child_display_type = '';
        $wbm_cat_slider_status = ( !empty($get_wbm_cat_slider_status) ? sanitize_text_field( wp_unslash( $get_wbm_cat_slider_status ) ) : '' );
        $wbm_filter_categories = ( !empty($get_wbm_filter_categories) ? sanitize_text_field( wp_unslash( $get_wbm_filter_categories ) ) : '' );
        $wbm_choose_categories = ( isset( $get_wbm_choose_categories ) ? array_map( 'sanitize_text_field', $get_wbm_choose_categories ) : array() );
        $wbm_total_cat_show = ( !empty($get_wbm_total_cat_show) ? sanitize_text_field( wp_unslash( $get_wbm_total_cat_show ) ) : '' );
        $wbm_cat_order_by = ( !empty($get_wbm_cat_order_by) ? sanitize_text_field( wp_unslash( $get_wbm_cat_order_by ) ) : '' );
        $wbm_cat_order = ( !empty($get_wbm_cat_order) ? sanitize_text_field( wp_unslash( $get_wbm_cat_order ) ) : '' );
        $wbm_cat_sec_title_status = ( !empty($get_wbm_cat_sec_title_status) ? sanitize_text_field( wp_unslash( $get_wbm_cat_sec_title_status ) ) : '' );
        $wbm_cat_slider_rule_name = ( !empty($get_wbm_cat_slider_rule_name) ? sanitize_text_field( wp_unslash( $get_wbm_cat_slider_rule_name ) ) : '' );
        $wbm_cat_title_color = ( !empty($get_wbm_cat_title_color) ? sanitize_text_field( wp_unslash( $get_wbm_cat_title_color ) ) : '' );
        $wbm_cat_title_top_margin = ( isset( $get_wbm_cat_title_top_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_cat_title_top_margin ) ) : '' );
        $wbm_cat_title_right_margin = ( isset( $get_wbm_cat_title_right_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_cat_title_right_margin ) ) : '' );
        $wbm_cat_title_bottom_margin = ( isset( $get_wbm_cat_title_bottom_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_cat_title_bottom_margin ) ) : '' );
        $wbm_cat_title_left_margin = ( isset( $get_wbm_cat_title_left_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_cat_title_left_margin ) ) : '' );
        $wbm_cat_title_unit_margin = ( !empty($get_wbm_cat_title_unit_margin) ? sanitize_text_field( wp_unslash( $get_wbm_cat_title_unit_margin ) ) : '' );
        $wbm_cat_make_card_style = ( !empty($get_wbm_cat_make_card_style) ? sanitize_text_field( wp_unslash( $get_wbm_cat_make_card_style ) ) : '' );
        $wbm_card_top_border = ( isset( $get_wbm_card_top_border ) ? sanitize_text_field( wp_unslash( $get_wbm_card_top_border ) ) : '' );
        $wbm_card_right_border = ( isset( $get_wbm_card_right_border ) ? sanitize_text_field( wp_unslash( $get_wbm_card_right_border ) ) : '' );
        $wbm_card_bottom_border = ( isset( $get_wbm_card_bottom_border ) ? sanitize_text_field( wp_unslash( $get_wbm_card_bottom_border ) ) : '' );
        $wbm_card_left_border = ( isset( $get_wbm_card_left_border ) ? sanitize_text_field( wp_unslash( $get_wbm_card_left_border ) ) : '' );
        $wbm_card_border_style = ( isset( $get_wbm_card_border_style ) ? sanitize_text_field( wp_unslash( $get_wbm_card_border_style ) ) : '' );
        $wbm_card_border_color = ( isset( $get_wbm_card_border_color ) ? sanitize_text_field( wp_unslash( $get_wbm_card_border_color ) ) : '' );
        $wbm_card_border_hov_color = ( isset( $get_wbm_card_border_hov_color ) ? sanitize_text_field( wp_unslash( $get_wbm_card_border_hov_color ) ) : '' );
        $wbm_card_bg_color = ( isset( $get_wbm_card_bg_color ) ? sanitize_text_field( wp_unslash( $get_wbm_card_bg_color ) ) : '' );
        $wbm_card_bg_hov_color = ( isset( $get_wbm_card_bg_hov_color ) ? sanitize_text_field( wp_unslash( $get_wbm_card_bg_hov_color ) ) : '' );
        $wbm_inner_top_padding = ( isset( $get_wbm_inner_top_padding ) ? sanitize_text_field( wp_unslash( $get_wbm_inner_top_padding ) ) : '' );
        $wbm_inner_right_padding = ( isset( $get_wbm_inner_right_padding ) ? sanitize_text_field( wp_unslash( $get_wbm_inner_right_padding ) ) : '' );
        $wbm_inner_bottom_padding = ( isset( $get_wbm_inner_bottom_padding ) ? sanitize_text_field( wp_unslash( $get_wbm_inner_bottom_padding ) ) : '' );
        $wbm_inner_left_padding = ( isset( $get_wbm_inner_left_padding ) ? sanitize_text_field( wp_unslash( $get_wbm_inner_left_padding ) ) : '' );
        $wbm_inner_unit_padding = ( !empty($get_wbm_inner_unit_padding) ? sanitize_text_field( wp_unslash( $get_wbm_inner_unit_padding ) ) : '' );
        $wbm_cat_name_status = ( !empty($get_wbm_cat_name_status) ? sanitize_text_field( wp_unslash( $get_wbm_cat_name_status ) ) : '' );
        $wbm_cat_name_color = ( !empty($get_wbm_cat_name_color) ? sanitize_text_field( wp_unslash( $get_wbm_cat_name_color ) ) : '' );
        $wbm_cat_name_top_margin = ( isset( $get_wbm_cat_name_top_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_cat_name_top_margin ) ) : '' );
        $wbm_cat_name_right_margin = ( isset( $get_wbm_cat_name_right_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_cat_name_right_margin ) ) : '' );
        $wbm_cat_name_bottom_margin = ( isset( $get_wbm_cat_name_bottom_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_cat_name_bottom_margin ) ) : '' );
        $wbm_cat_name_left_margin = ( isset( $get_wbm_cat_name_left_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_cat_name_left_margin ) ) : '' );
        $wbm_cat_name_unit_margin = ( !empty($get_wbm_cat_name_unit_margin) ? sanitize_text_field( wp_unslash( $get_wbm_cat_name_unit_margin ) ) : '' );
        $wbm_cat_prod_count = ( !empty($get_wbm_cat_prod_count) ? sanitize_text_field( wp_unslash( $get_wbm_cat_prod_count ) ) : '' );
        $wbm_cat_count_position = ( !empty($get_wbm_cat_count_position) ? sanitize_text_field( wp_unslash( $get_wbm_cat_count_position ) ) : '' );
        $wbm_prod_count_before = ( !empty($get_wbm_prod_count_before) ? sanitize_text_field( wp_unslash( $get_wbm_prod_count_before ) ) : '' );
        $wbm_prod_count_after = ( !empty($get_wbm_prod_count_after) ? sanitize_text_field( wp_unslash( $get_wbm_prod_count_after ) ) : '' );
        $wbm_prod_count_color = ( !empty($get_wbm_prod_count_color) ? sanitize_text_field( wp_unslash( $get_wbm_prod_count_color ) ) : '' );
        $wbm_cat_desc_status = ( !empty($get_wbm_cat_desc_status) ? sanitize_text_field( wp_unslash( $get_wbm_cat_desc_status ) ) : '' );
        $wbm_cat_desc_color = ( !empty($get_wbm_cat_desc_color) ? sanitize_text_field( wp_unslash( $get_wbm_cat_desc_color ) ) : '' );
        $wbm_cat_desc_top_margin = ( isset( $get_wbm_cat_desc_top_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_cat_desc_top_margin ) ) : '' );
        $wbm_cat_desc_right_margin = ( isset( $get_wbm_cat_desc_right_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_cat_desc_right_margin ) ) : '' );
        $wbm_cat_desc_bottom_margin = ( isset( $get_wbm_cat_desc_bottom_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_cat_desc_bottom_margin ) ) : '' );
        $wbm_cat_desc_left_margin = ( isset( $get_wbm_cat_desc_left_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_cat_desc_left_margin ) ) : '' );
        $wbm_cat_desc_unit_margin = ( !empty($get_wbm_cat_desc_unit_margin) ? sanitize_text_field( wp_unslash( $get_wbm_cat_desc_unit_margin ) ) : '' );
        $wbm_shop_now_button = ( !empty($get_wbm_shop_now_button) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_button ) ) : '' );
        $wbm_shop_now_label = ( !empty($get_wbm_shop_now_label) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_label ) ) : '' );
        $wbm_shop_now_color = ( !empty($get_wbm_shop_now_color) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_color ) ) : '' );
        $wbm_shop_now_hov_color = ( !empty($get_wbm_shop_now_hov_color) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_hov_color ) ) : '' );
        $wbm_shop_now_bg_color = ( !empty($get_wbm_shop_now_bg_color) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_bg_color ) ) : '' );
        $wbm_shop_now_bg_hov_color = ( !empty($get_wbm_shop_now_bg_hov_color) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_bg_hov_color ) ) : '' );
        $wbm_shop_now_all_border = ( !empty($get_wbm_shop_now_all_border) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_all_border ) ) : '' );
        $wbm_shop_now_border_style = ( !empty($get_wbm_shop_now_border_style) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_border_style ) ) : '' );
        $wbm_shop_now_border_color = ( !empty($get_wbm_shop_now_border_color) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_border_color ) ) : '' );
        $wbm_shop_now_border_hov_color = ( !empty($get_wbm_shop_now_border_hov_color) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_border_hov_color ) ) : '' );
        $wbm_shop_now_top_margin = ( isset( $get_wbm_shop_now_top_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_top_margin ) ) : '' );
        $wbm_shop_now_right_margin = ( isset( $get_wbm_shop_now_right_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_right_margin ) ) : '' );
        $wbm_shop_now_bottom_margin = ( isset( $get_wbm_shop_now_bottom_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_bottom_margin ) ) : '' );
        $wbm_shop_now_left_margin = ( isset( $get_wbm_shop_now_left_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_left_margin ) ) : '' );
        $wbm_shop_now_unit_margin = ( !empty($get_wbm_shop_now_unit_margin) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_unit_margin ) ) : '' );
        $wbm_shop_now_link_target = ( !empty($get_wbm_shop_now_link_target) ? sanitize_text_field( wp_unslash( $get_wbm_shop_now_link_target ) ) : '' );
        $wbm_cat_thumbnail = ( !empty($get_wbm_cat_thumbnail) ? sanitize_text_field( wp_unslash( $get_wbm_cat_thumbnail ) ) : '' );
        $wbm_cat_thumbnail_size = ( !empty($get_wbm_cat_thumbnail_size) ? sanitize_text_field( wp_unslash( $get_wbm_cat_thumbnail_size ) ) : '' );
        $wbm_cat_thumb_border = ( !empty($get_wbm_cat_thumb_border) ? sanitize_text_field( wp_unslash( $get_wbm_cat_thumb_border ) ) : '' );
        $wbm_thumb_all_border = ( !empty($get_wbm_thumb_all_border) ? sanitize_text_field( wp_unslash( $get_wbm_thumb_all_border ) ) : '' );
        $wbm_thumb_border_style = ( !empty($get_wbm_thumb_border_style) ? sanitize_text_field( wp_unslash( $get_wbm_thumb_border_style ) ) : '' );
        $wbm_thumb_border_color = ( !empty($get_wbm_thumb_border_color) ? sanitize_text_field( wp_unslash( $get_wbm_thumb_border_color ) ) : '' );
        $wbm_thumb_border_hov_color = ( !empty($get_wbm_thumb_border_hov_color) ? sanitize_text_field( wp_unslash( $get_wbm_thumb_border_hov_color ) ) : '' );
        $wbm_thumb_top_margin = ( isset( $get_wbm_thumb_top_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_thumb_top_margin ) ) : '' );
        $wbm_thumb_right_margin = ( isset( $get_wbm_thumb_right_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_thumb_right_margin ) ) : '' );
        $wbm_thumb_bottom_margin = ( isset( $get_wbm_thumb_bottom_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_thumb_bottom_margin ) ) : '' );
        $wbm_thumb_left_margin = ( isset( $get_wbm_thumb_left_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_thumb_left_margin ) ) : '' );
        $wbm_thumb_unit_margin = ( !empty($get_wbm_thumb_unit_margin) ? sanitize_text_field( wp_unslash( $get_wbm_thumb_unit_margin ) ) : '' );
        $wbm_cat_thumb_mode = ( !empty($get_wbm_cat_thumb_mode) ? sanitize_text_field( wp_unslash( $get_wbm_cat_thumb_mode ) ) : '' );
        $wbm_cat_autoplay = ( !empty($get_wbm_cat_autoplay) ? sanitize_text_field( wp_unslash( $get_wbm_cat_autoplay ) ) : '' );
        $wbm_cat_autoplay_speed = ( !empty($get_wbm_cat_autoplay_speed) ? sanitize_text_field( wp_unslash( $get_wbm_cat_autoplay_speed ) ) : '' );
        $wbm_cat_scroll_speed = ( !empty($get_wbm_cat_scroll_speed) ? sanitize_text_field( wp_unslash( $get_wbm_cat_scroll_speed ) ) : '' );
        $wbm_cat_pause_on_hov = ( !empty($get_wbm_cat_pause_on_hov) ? sanitize_text_field( wp_unslash( $get_wbm_cat_pause_on_hov ) ) : '' );
        $wbm_cat_infinite_loop = ( !empty($get_wbm_cat_infinite_loop) ? sanitize_text_field( wp_unslash( $get_wbm_cat_infinite_loop ) ) : '' );
        $wbm_cat_auto_height = ( !empty($get_wbm_cat_auto_height) ? sanitize_text_field( wp_unslash( $get_wbm_cat_auto_height ) ) : '' );
        $wbm_cat_nav_color = ( !empty($get_wbm_cat_nav_color) ? sanitize_text_field( wp_unslash( $get_wbm_cat_nav_color ) ) : '' );
        $wbm_cat_nav_hov_color = ( !empty($get_wbm_cat_nav_hov_color) ? sanitize_text_field( wp_unslash( $get_wbm_cat_nav_hov_color ) ) : '' );
        $wbm_cat_nav_bg_color = ( !empty($get_wbm_cat_nav_bg_color) ? sanitize_text_field( wp_unslash( $get_wbm_cat_nav_bg_color ) ) : '' );
        $wbm_cat_nav_bg_hov_color = ( !empty($get_wbm_cat_nav_bg_hov_color) ? sanitize_text_field( wp_unslash( $get_wbm_cat_nav_bg_hov_color ) ) : '' );
        $wbm_cat_nav_all_border = ( !empty($get_wbm_cat_nav_all_border) ? sanitize_text_field( wp_unslash( $get_wbm_cat_nav_all_border ) ) : '' );
        $wbm_cat_nav_border_style = ( !empty($get_wbm_cat_nav_border_style) ? sanitize_text_field( wp_unslash( $get_wbm_cat_nav_border_style ) ) : '' );
        $wbm_cat_nav_border_color = ( !empty($get_wbm_cat_nav_border_color) ? sanitize_text_field( wp_unslash( $get_wbm_cat_nav_border_color ) ) : '' );
        $wbm_cat_nav_border_hov_color = ( !empty($get_wbm_cat_nav_border_hov_color) ? sanitize_text_field( wp_unslash( $get_wbm_cat_nav_border_hov_color ) ) : '' );
        $wbm_cat_pager_color = ( !empty($get_wbm_cat_pager_color) ? sanitize_text_field( wp_unslash( $get_wbm_cat_pager_color ) ) : '' );
        $wbm_cat_pager_active_color = ( !empty($get_wbm_cat_pager_active_color) ? sanitize_text_field( wp_unslash( $get_wbm_cat_pager_active_color ) ) : '' );
        $wbm_cat_pager_hov_color = ( !empty($get_wbm_cat_pager_hov_color) ? sanitize_text_field( wp_unslash( $get_wbm_cat_pager_hov_color ) ) : '' );
        $get_wbm_dswbm_slider_mode = ( !empty($get_wbm_dswbm_slider_mode) ? sanitize_text_field( wp_unslash( $get_wbm_dswbm_slider_mode ) ) : '' );
        $wbm_cat_thumb_zoom = ( !empty($wbm_cat_thumb_zoom) ? sanitize_text_field( wp_unslash( $wbm_cat_thumb_zoom ) ) : '' );
        $wbm_cat_touch_swipe = ( !empty($wbm_cat_touch_swipe) ? sanitize_text_field( wp_unslash( $wbm_cat_touch_swipe ) ) : '' );
        $wbm_layout_presets = '';
        $wbm_cat_thumbnail_shape = '';
        $wbm_cat_thumb_border_radius = '';
        $wbm_thumb_shadow_v_offset = '';
        $wbm_thumb_shadow_h_offset = '';
        $wbm_thumb_shadow_blur = '';
        $wbm_thumb_shadow_spread = '';
        $wbm_thumb_shadow_shadow = '';
        $wbm_thumb_shadow_color = '';
        $wbm_thumb_shadow_hover_color = '';
        $wbm_cat_typo_on_off = '';
        $wbm_cat_title_typo_font_family = '';
        $wbm_cat_title_typo_font_style = '';
        $wbm_cat_title_typo_text_align = '';
        $wbm_cat_title_typo_text_transform = '';
        $wbm_cat_title_typo_text_font_size = '';
        $wbm_cat_title_typo_text_line_height = '';
        $wbm_cat_title_typo_text_letter_spacing = '';
        $wbm_cat_typo_shopbtn_on_off = '';
        $wbm_cat_title_typo_shopbtn_font_family = '';
        $wbm_cat_title_typo_shopbtn_font_style = '';
        $wbm_cat_title_typo_shopbtn_text_align = '';
        $wbm_cat_title_typo_shopbtn_text_transform = '';
        $wbm_cat_title_typo_shopbtn_text_font_size = '';
        $wbm_cat_title_typo_shopbtn_text_line_height = '';
        $wbm_cat_title_typo_shopbtn_text_letter_spacing = '';
        $wbm_cat_content_position = '';
        $wbm_exclude_categories = '';
        $dswbm_child_categories = '';
        $wbm_choose_categories_pnc_cat = array();
        $wbm_cat_no_cols_large_desktop = '';
        $wbm_cat_no_cols_desktop = '';
        $wbm_cat_no_cols_laptop = '';
        $wbm_cat_no_cols_tablet = '';
        $wbm_cat_no_cols_mobile = '';
        $wbm_parent_child_display_type = '';
        // setup query
        $args = array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
        );
        $final_categories = [];
        
        if ( 'parent' === $dswbm_child_categories ) {
            
            if ( isset( $wbm_filter_categories ) && 'wbm_specific_categories' === $wbm_filter_categories && isset( $wbm_total_cat_show ) ) {
                
                if ( !empty($wbm_choose_categories) ) {
                    $choose_cats = array_reverse( $wbm_choose_categories );
                    $removed_categories = array_splice( $choose_cats, count( $choose_cats ) - $wbm_total_cat_show, $wbm_total_cat_show );
                    $final_categories = array_reverse( $removed_categories );
                }
            
            } else {
                
                if ( isset( $wbm_filter_categories ) && 'wbm_exclude_categories' === $wbm_filter_categories && isset( $wbm_total_cat_show ) ) {
                    if ( !empty($wbm_exclude_categories) ) {
                        $args['exclude'] = $wbm_exclude_categories;
                    }
                } else {
                    $args['number'] = $wbm_total_cat_show;
                }
            
            }
        
        } else {
            
            if ( isset( $wbm_choose_categories_pnc_cat ) && !empty($wbm_choose_categories_pnc_cat) && isset( $wbm_total_cat_show ) ) {
                $choose_pnc_cat = array_reverse( $wbm_choose_categories_pnc_cat );
                $removed_categories = array_splice( $choose_pnc_cat, count( $choose_pnc_cat ) - $wbm_total_cat_show, $wbm_total_cat_show );
                $final_categories = array_reverse( $removed_categories );
            } else {
                $args['number'] = $wbm_total_cat_show;
            }
        
        }
        
        if ( isset( $wbm_cat_order_by ) ) {
            $args['orderby'] = $wbm_cat_order_by;
        }
        if ( isset( $wbm_cat_order ) ) {
            $args['order'] = $wbm_cat_order;
        }
        $terms = get_categories( $args );
        ?>
        <div class="wbm-category-slider-section dswbm-sliders-main <?php 
        echo  esc_attr( $wbm_cat_content_position ) ;
        ?> layout-present-<?php 
        echo  esc_attr( $wbm_layout_presets ) ;
        ?>" auto-play="<?php 
        esc_attr_e( $wbm_cat_autoplay, 'banner-management-for-woocommerce' );
        ?>" auto-play-speed="<?php 
        esc_attr_e( $wbm_cat_autoplay_speed, 'banner-management-for-woocommerce' );
        ?>" scroll-speed="<?php 
        esc_attr_e( $wbm_cat_scroll_speed, 'banner-management-for-woocommerce' );
        ?>" pause-hover="<?php 
        esc_attr_e( $wbm_cat_pause_on_hov, 'banner-management-for-woocommerce' );
        ?>" infinite-loop="<?php 
        esc_attr_e( $wbm_cat_infinite_loop, 'banner-management-for-woocommerce' );
        ?>" auto-height="<?php 
        esc_attr_e( $wbm_cat_auto_height, 'banner-management-for-woocommerce' );
        ?>" slider-mode="<?php 
        esc_attr_e( $get_wbm_dswbm_slider_mode, 'banner-management-for-woocommerce' );
        ?>" 
		slider-touch-status="<?php 
        esc_attr_e( $wbm_cat_touch_swipe, 'banner-management-for-woocommerce' );
        ?>"
		>

			<?php 
        
        if ( isset( $wbm_cat_title_typo_font_family ) && !empty($wbm_cat_title_typo_font_family) && 'none' !== $wbm_cat_title_typo_font_family ) {
            ?>		
				<link href='https://fonts.googleapis.com/css?family=<?php 
            echo  $wbm_cat_title_typo_font_family ;
            ?>' rel='stylesheet' type="text/css"> <?php 
            // phpcs:ignore
            ?>
			<?php 
        }
        
        ?>
			<?php 
        
        if ( isset( $wbm_cat_title_typo_shopbtn_font_family ) && !empty($wbm_cat_title_typo_shopbtn_font_family) && 'none' !== $wbm_cat_title_typo_shopbtn_font_family ) {
            ?>	
				<link href='https://fonts.googleapis.com/css?family=<?php 
            echo  $wbm_cat_title_typo_shopbtn_font_family ;
            ?>' rel='stylesheet' type="text/css"> <?php 
            // phpcs:ignore
            ?>
			<?php 
        }
        
        ?>
			<style>
        		.dswbm-sliders-main .bx-wrapper .bx-controls-direction a i {
        			color: <?php 
        esc_html_e( $wbm_cat_nav_color, 'banner-management-for-woocommerce' );
        ?>;
        			background-color: <?php 
        esc_html_e( $wbm_cat_nav_bg_color, 'banner-management-for-woocommerce' );
        ?>;
        			border-width: <?php 
        esc_html_e( $wbm_cat_nav_all_border . 'px', 'banner-management-for-woocommerce' );
        ?>;
        			border-style: <?php 
        esc_html_e( $wbm_cat_nav_border_style, 'banner-management-for-woocommerce' );
        ?>;
        			border-color: <?php 
        esc_html_e( $wbm_cat_nav_border_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.dswbm-sliders-main .bx-wrapper .bx-controls-direction a i:hover {
        			color: <?php 
        esc_html_e( $wbm_cat_nav_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        			background-color: <?php 
        esc_html_e( $wbm_cat_nav_bg_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        			border-color: <?php 
        esc_html_e( $wbm_cat_nav_border_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.dswbm-sliders-main .bx-wrapper .bx-pager .bx-pager-item a {
        			background-color: <?php 
        esc_html_e( $wbm_cat_pager_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.dswbm-sliders-main .bx-wrapper .bx-pager .bx-pager-item a:hover {
        			background-color: <?php 
        esc_html_e( $wbm_cat_pager_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.dswbm-sliders-main .bx-wrapper .bx-pager .bx-pager-item a.active {
        			background-color: <?php 
        esc_html_e( $wbm_cat_pager_active_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.wbm-category-slider-section .slider-section-title {
        			margin-top: <?php 
        esc_html_e( $wbm_cat_title_top_margin . $wbm_cat_title_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-bottom: <?php 
        esc_html_e( $wbm_cat_title_bottom_margin . $wbm_cat_title_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-right: <?php 
        esc_html_e( $wbm_cat_title_right_margin . $wbm_cat_title_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-left: <?php 
        esc_html_e( $wbm_cat_title_left_margin . $wbm_cat_title_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.wbm-category-slider-section .slider-section-title h3 {
        			color: <?php 
        esc_html_e( $wbm_cat_title_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.wbm-category-slider-section .term-content h3 {
        			margin-top: <?php 
        esc_html_e( $wbm_cat_name_top_margin . $wbm_cat_name_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-bottom: <?php 
        esc_html_e( $wbm_cat_name_bottom_margin . $wbm_cat_name_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-right: <?php 
        esc_html_e( $wbm_cat_name_right_margin . $wbm_cat_name_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-left: <?php 
        esc_html_e( $wbm_cat_name_left_margin . $wbm_cat_name_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.wbm-category-slider-section .term-content h3 a {
        			color: <?php 
        esc_html_e( $wbm_cat_name_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.wbm-category-slider-section .term-content h3 .prod-count {
        			color: <?php 
        esc_html_e( $wbm_prod_count_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.wbm-category-slider-section .term-content .cat-desc {
    				color: <?php 
        esc_html_e( $wbm_cat_desc_color, 'banner-management-for-woocommerce' );
        ?>;
    				margin-top: <?php 
        esc_html_e( $wbm_cat_desc_top_margin . $wbm_cat_desc_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-bottom: <?php 
        esc_html_e( $wbm_cat_desc_bottom_margin . $wbm_cat_desc_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-right: <?php 
        esc_html_e( $wbm_cat_desc_right_margin . $wbm_cat_desc_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-left: <?php 
        esc_html_e( $wbm_cat_desc_left_margin . $wbm_cat_desc_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.wbm-category-slider-section .term-content .shop-now-btn {
        			margin-top: <?php 
        esc_html_e( $wbm_shop_now_top_margin . $wbm_shop_now_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-bottom: <?php 
        esc_html_e( $wbm_shop_now_bottom_margin . $wbm_shop_now_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-right: <?php 
        esc_html_e( $wbm_shop_now_right_margin . $wbm_shop_now_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-left: <?php 
        esc_html_e( $wbm_shop_now_left_margin . $wbm_shop_now_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.wbm-category-slider-section .term-content .shop-now-btn a {
        			color: <?php 
        esc_html_e( $wbm_shop_now_color, 'banner-management-for-woocommerce' );
        ?>;
        			background-color: <?php 
        esc_html_e( $wbm_shop_now_bg_color, 'banner-management-for-woocommerce' );
        ?>;
        			border-width: <?php 
        esc_html_e( $wbm_shop_now_all_border . 'px', 'banner-management-for-woocommerce' );
        ?>;
        			border-style: <?php 
        esc_html_e( $wbm_shop_now_border_style, 'banner-management-for-woocommerce' );
        ?>;
        			border-color: <?php 
        esc_html_e( $wbm_shop_now_border_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.wbm-category-slider-section .term-content .shop-now-btn a:hover {
        			color: <?php 
        esc_html_e( $wbm_shop_now_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        			background-color: <?php 
        esc_html_e( $wbm_shop_now_bg_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        			border-color: <?php 
        esc_html_e( $wbm_shop_now_border_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
				
        		.wbm-category-slider-section .term-list .term-img {
        			margin-top: <?php 
        esc_html_e( $wbm_thumb_top_margin . $wbm_thumb_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-bottom: <?php 
        esc_html_e( $wbm_thumb_bottom_margin . $wbm_thumb_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-right: <?php 
        esc_html_e( $wbm_thumb_right_margin . $wbm_thumb_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-left: <?php 
        esc_html_e( $wbm_thumb_left_margin . $wbm_thumb_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			<?php 
        
        if ( isset( $wbm_cat_thumb_border ) && 'on' === $wbm_cat_thumb_border ) {
            ?>
        				border-width: <?php 
            esc_html_e( $wbm_thumb_all_border . 'px', 'banner-management-for-woocommerce' );
            ?>;
	        			border-style: <?php 
            esc_html_e( $wbm_thumb_border_style, 'banner-management-for-woocommerce' );
            ?>;
	        			border-color: <?php 
            esc_html_e( $wbm_thumb_border_color, 'banner-management-for-woocommerce' );
            ?>;
        				<?php 
        }
        
        ?>
        		}
        		<?php 
        
        if ( isset( $wbm_cat_thumb_border ) && 'on' === $wbm_cat_thumb_border ) {
            ?>
    				.wbm-category-slider-section .term-list .term-img:hover {
	        			border-color: <?php 
            esc_html_e( $wbm_thumb_border_hov_color, 'banner-management-for-woocommerce' );
            ?>;
	        		}
        			<?php 
        }
        
        if ( 'on-hover' === $wbm_cat_thumb_mode ) {
            ?>
    				.wbm-category-slider-section .term-list .term-img img:hover {
        			    filter: grayscale(1);
	        		}
        			<?php 
        }
        if ( 'normal-to-grayscale' === $wbm_cat_thumb_mode ) {
            ?>
    				.wbm-category-slider-section .term-list .term-img img {
        			    filter: grayscale(1);
	        		}
					.wbm-category-slider-section .term-list .term-img img:hover {
        			    filter: none;
	        		}
        			<?php 
        }
        if ( 'always-grayscale' === $wbm_cat_thumb_mode ) {
            ?>
    				.wbm-category-slider-section .term-list .term-img img {
        			    filter: grayscale(1);
	        		}
        			<?php 
        }
        ?>
				<?php 
        ?>

    			<?php 
        
        if ( isset( $wbm_cat_make_card_style ) && 'on' === $wbm_cat_make_card_style ) {
            ?>
    				.wbm-category-slider-section .term-content {
    					border-top-width: <?php 
            esc_html_e( $wbm_card_top_border . 'px', 'banner-management-for-woocommerce' );
            ?>;
        				border-bottom-width: <?php 
            esc_html_e( $wbm_card_bottom_border . 'px', 'banner-management-for-woocommerce' );
            ?>;
        				border-left-width: <?php 
            esc_html_e( $wbm_card_left_border . 'px', 'banner-management-for-woocommerce' );
            ?>;
        				border-right-width: <?php 
            esc_html_e( $wbm_card_right_border . 'px', 'banner-management-for-woocommerce' );
            ?>;
	        			border-style: <?php 
            esc_html_e( $wbm_card_border_style, 'banner-management-for-woocommerce' );
            ?>;
	        			border-color: <?php 
            esc_html_e( $wbm_card_border_color, 'banner-management-for-woocommerce' );
            ?>;
	        			padding-top: <?php 
            esc_html_e( $wbm_inner_top_padding . $wbm_inner_unit_padding, 'banner-management-for-woocommerce' );
            ?>;
	        			padding-bottom: <?php 
            esc_html_e( $wbm_inner_bottom_padding . $wbm_inner_unit_padding, 'banner-management-for-woocommerce' );
            ?>;
	        			padding-right: <?php 
            esc_html_e( $wbm_inner_right_padding . $wbm_inner_unit_padding, 'banner-management-for-woocommerce' );
            ?>;
	        			padding-left: <?php 
            esc_html_e( $wbm_inner_left_padding . $wbm_inner_unit_padding, 'banner-management-for-woocommerce' );
            ?>;
	        			background-color: <?php 
            esc_html_e( $wbm_card_bg_color, 'banner-management-for-woocommerce' );
            ?>;
    				}
    			<?php 
        }
        
        
        if ( isset( $wbm_cat_make_card_style ) && 'on' === $wbm_cat_make_card_style ) {
            ?>
	        		.wbm-category-slider-section .term-content:hover {
	        			border-color: <?php 
            esc_html_e( $wbm_card_border_hov_color, 'banner-management-for-woocommerce' );
            ?>;
	        			background-color: <?php 
            esc_html_e( $wbm_card_bg_hov_color, 'banner-management-for-woocommerce' );
            ?>;
	        		}
	        	<?php 
        }
        
        
        if ( isset( $wbm_cat_thumb_zoom ) && 'zoom-in' === $wbm_cat_thumb_zoom ) {
            
            if ( $wbm_cat_content_position === 'cont-over-thumb' ) {
                ?>
							.wbm-category-slider-section .term-list:hover .term-img img{
								-webkit-transform: scale(1.2);
								-moz-transform: scale(1.2);
								transform: scale(1.2);
							}
						<?php 
            } else {
                ?>
							.wbm-category-slider-section .term-list .term-img:hover img{
								-webkit-transform: scale(1.2);
								-moz-transform: scale(1.2);
								transform: scale(1.2);
							}
						<?php 
            }
        
        } elseif ( isset( $wbm_cat_thumb_zoom ) && 'zoom-out' === $wbm_cat_thumb_zoom ) {
            ?>
					.wbm-category-slider-section .term-list .term-img img{
						-webkit-transform: scale3d(1.2,1.2,1);
						-moz-transform: scale3d(1.2,1.2,1);
						transform: scale3d(1.2,1.2,1);
					}
					<?php 
            
            if ( $wbm_cat_content_position === 'cont-over-thumb' ) {
                ?>
							.wbm-category-slider-section .term-list:hover .term-img img{
								-webkit-transform: none;
								-moz-transform: none;
								transform: none;
							}
						<?php 
            } else {
                ?>
							.wbm-category-slider-section .term-list .term-img:hover img{
								-webkit-transform: none;
								-moz-transform: none;
								transform: none;
							}
						<?php 
            }
        
        }
        
        ?>
				<?php 
        
        if ( 'slider' !== $wbm_layout_presets ) {
            ?>
					@media only screen and (min-width:1280px){
						.wbm-category-slider-section.layout-present-grid ul.wbm-slider li {
							margin: 0 10px;
							width: calc(80% / <?php 
            echo  esc_attr( $wbm_cat_no_cols_large_desktop ) ;
            ?>);
						}
					}
					@media only screen and (min-width:981px) and (max-width:1279px){
						.wbm-category-slider-section.layout-present-grid ul.wbm-slider li {
							margin: 0 10px;
							width: calc(80% / <?php 
            echo  esc_attr( $wbm_cat_no_cols_desktop ) ;
            ?>);
						}
					}
					@media only screen and (min-width:737px) and (max-width:980px){
						.wbm-category-slider-section.layout-present-grid ul.wbm-slider li {
							margin: 0 10px;
							width: calc(80% / <?php 
            echo  esc_attr( $wbm_cat_no_cols_laptop ) ;
            ?>);
						}
					}
					@media only screen and (min-width:481px) and (max-width:737px){
						.wbm-category-slider-section.layout-present-grid ul.wbm-slider li {
							margin: 0 10px;
							width: calc(80% / <?php 
            echo  esc_attr( $wbm_cat_no_cols_tablet ) ;
            ?>);
						}
					}
					@media only screen and (max-width:480px){
						.wbm-category-slider-section.layout-present-grid ul.wbm-slider li {
							margin: 0 10px;
							width: calc(80% / <?php 
            echo  esc_attr( $wbm_cat_no_cols_mobile ) ;
            ?>);
						}
					}
					<?php 
        }
        
        ?>
	    	</style>
        	<?php 
        
        if ( isset( $wbm_cat_slider_status ) && 'on' === $wbm_cat_slider_status ) {
            
            if ( isset( $wbm_cat_sec_title_status ) && 'on' === $wbm_cat_sec_title_status ) {
                ?>
    				<div class="slider-section-title">
						<h3><?php 
                esc_html_e( $wbm_cat_slider_rule_name, 'banner-management-for-woocommerce' );
                ?></h3>
    				</div>
    				<?php 
            }
            
            ?>
        		<ul class="wbm-slider">
	        		<?php 
            
            if ( isset( $wbm_filter_categories ) && 'wbm_specific_categories' === $wbm_filter_categories || 'parent and child' === $dswbm_child_categories ) {
                if ( !empty($final_categories) ) {
                    foreach ( $final_categories as $selected_cat ) {
                        foreach ( $terms as $term ) {
                            
                            if ( intval( $selected_cat ) === $term->term_id ) {
                                $term_link = get_term_link( $term );
                                // If there was an error, continue to the next term.
                                if ( is_wp_error( $term_link ) ) {
                                    continue;
                                }
                                ?>
										<li class="term-list">
											<?php 
                                
                                if ( isset( $wbm_cat_thumbnail ) && 'on' === $wbm_cat_thumbnail ) {
                                    ?>
												<div class="term-img">
													<?php 
                                    $thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                                    
                                    if ( isset( $thumb_id ) && !empty($thumb_id) && intval( $thumb_id ) !== 0 ) {
                                        $thumbnail_url = wp_get_attachment_image_src( $thumb_id, $wbm_cat_thumbnail_size );
                                        $thumbnail_src = ( !empty($thumbnail_url) ? $thumbnail_url[0] : '' );
                                        ?>
														<a href="<?php 
                                        echo  esc_url( $term_link ) ;
                                        ?>">
															<div class="img-overlay"></div>
															<img src="<?php 
                                        echo  esc_url( $thumbnail_src ) ;
                                        ?>" alt="<?php 
                                        echo  esc_attr( $term->name ) ;
                                        ?>">
														</a>
														<?php 
                                    } else {
                                        $placeholder_src = wc_placeholder_img_src( $wbm_cat_thumbnail_size );
                                        
                                        if ( isset( $placeholder_src ) && !empty($placeholder_src) ) {
                                            ?>
															<a href="<?php 
                                            echo  esc_url( $term_link ) ;
                                            ?>">
																<div class="img-overlay"></div>
																<img src="<?php 
                                            echo  esc_url( $placeholder_src ) ;
                                            ?>" alt="<?php 
                                            echo  esc_attr( $term->name ) ;
                                            ?>">
															</a>
															<?php 
                                        }
                                    
                                    }
                                    
                                    ?>
												</div>
												<?php 
                                }
                                
                                ?>
											<div class="term-content">
												<?php 
                                
                                if ( isset( $wbm_cat_name_status ) && 'on' === $wbm_cat_name_status ) {
                                    $wbm_prod_count_data = $wbm_prod_count_before . ' ' . $term->count . ' ' . $wbm_prod_count_after;
                                    ?>
													<h3>
														<a href="<?php 
                                    echo  esc_url( $term_link ) ;
                                    ?>"><?php 
                                    echo  esc_html( $term->name, 'banner-management-for-woocommerce' ) ;
                                    
                                    if ( 'on' === $wbm_cat_prod_count && 'beside_cat' === $wbm_cat_count_position ) {
                                        ?>
															<span class="prod-count"><?php 
                                        echo  esc_html( $wbm_prod_count_data, 'banner-management-for-woocommerce' ) ;
                                        ?></span>
															<?php 
                                    }
                                    
                                    ?>
														</a>
														<?php 
                                    
                                    if ( 'on' === $wbm_cat_prod_count && 'under_cat' === $wbm_cat_count_position ) {
                                        ?>
															<p class="prod-count"><?php 
                                        echo  esc_html( $wbm_prod_count_data, 'banner-management-for-woocommerce' ) ;
                                        ?></p>
															<?php 
                                    }
                                    
                                    ?>
													</h3>
													<?php 
                                }
                                
                                if ( isset( $wbm_parent_child_display_type ) && 'under_parent' === $wbm_parent_child_display_type && 'parent and child' === $dswbm_child_categories ) {
                                    
                                    if ( $term->term_id ) {
                                        $child_arg = array(
                                            'hide_empty' => false,
                                            'parent'     => $term->term_id,
                                        );
                                        $child_categories = get_terms( 'product_cat', $child_arg );
                                        foreach ( $child_categories as $tm ) {
                                            echo  '<p class="cat-desc">' . esc_html( $tm->name, 'banner-management-for-woocommerce' ) . '</p>' ;
                                        }
                                    }
                                
                                }
                                
                                if ( isset( $wbm_cat_desc_status ) && 'on' === $wbm_cat_desc_status ) {
                                    // strip tags to avoid breaking any html
                                    $catDesc = wp_strip_all_tags( $term->description );
                                    
                                    if ( strlen( $catDesc ) > 110 ) {
                                        // truncate string
                                        $catDescCut = substr( $catDesc, 0, 110 );
                                        $endPoint = strrpos( $catDescCut, ' ' );
                                        //if the string doesn't contain any space then it will cut without word basis.
                                        $catDesc = ( $endPoint ? substr( $catDescCut, 0, $endPoint ) : substr( $catDescCut, 0 ) );
                                        $catDesc .= '...';
                                    }
                                    
                                    ?>
													<p class="cat-desc"><?php 
                                    echo  esc_html( $catDesc, 'banner-management-for-woocommerce' ) ;
                                    ?></p>
													<?php 
                                }
                                
                                
                                if ( isset( $wbm_shop_now_button ) && 'on' === $wbm_shop_now_button ) {
                                    ?>
													<div class="shop-now-btn">
														<a href="<?php 
                                    echo  esc_url( $term_link ) ;
                                    ?>" target="<?php 
                                    esc_attr_e( $wbm_shop_now_link_target, 'banner-management-for-woocommerce' );
                                    ?>"><?php 
                                    echo  esc_html( $wbm_shop_now_label, 'banner-management-for-woocommerce' ) ;
                                    ?></a>
													</div>
													<?php 
                                }
                                
                                ?>
											</div>
										</li>
										<?php 
                            }
                        
                        }
                    }
                }
            } elseif ( isset( $terms ) && !empty($terms) ) {
                foreach ( $terms as $term ) {
                    $term_link = get_term_link( $term );
                    // If there was an error, continue to the next term.
                    if ( is_wp_error( $term_link ) ) {
                        continue;
                    }
                    ?>
							<li class="term-list">
								<?php 
                    
                    if ( isset( $wbm_cat_thumbnail ) && 'on' === $wbm_cat_thumbnail ) {
                        ?>
									<div class="term-img">
										<?php 
                        $thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                        
                        if ( isset( $thumb_id ) && !empty($thumb_id) && intval( $thumb_id ) !== 0 ) {
                            $thumbnail_url = wp_get_attachment_image_src( $thumb_id, $wbm_cat_thumbnail_size );
                            $thumbnail_src = ( !empty($thumbnail_url) ? $thumbnail_url[0] : '' );
                            ?>
											<a href="<?php 
                            echo  esc_url( $term_link ) ;
                            ?>">
												<div class="img-overlay"></div>
												<img src="<?php 
                            echo  esc_url( $thumbnail_src ) ;
                            ?>" alt="<?php 
                            echo  esc_attr( $term->name ) ;
                            ?>">
											</a>
										<?php 
                        } else {
                            $placeholder_src = wc_placeholder_img_src( $wbm_cat_thumbnail_size );
                            
                            if ( isset( $placeholder_src ) && !empty($placeholder_src) ) {
                                ?>
												<a href="<?php 
                                echo  esc_url( $term_link ) ;
                                ?>">
													<div class="img-overlay"></div>
													<img src="<?php 
                                echo  esc_url( $placeholder_src ) ;
                                ?>" alt="<?php 
                                echo  esc_attr( $term->name ) ;
                                ?>">
												</a>
												<?php 
                            }
                        
                        }
                        
                        ?>
									</div>
									<?php 
                    }
                    
                    ?>
								<div class="term-content">
									<?php 
                    
                    if ( isset( $wbm_cat_name_status ) && 'on' === $wbm_cat_name_status ) {
                        $wbm_prod_count_data = $wbm_prod_count_before . ' ' . $term->count . ' ' . $wbm_prod_count_after;
                        ?>
										<h3>
											<a href="<?php 
                        echo  esc_url( $term_link ) ;
                        ?>"><?php 
                        echo  esc_html( $term->name, 'banner-management-for-woocommerce' ) ;
                        
                        if ( 'on' === $wbm_cat_prod_count && 'beside_cat' === $wbm_cat_count_position ) {
                            ?>
												<span class="prod-count"><?php 
                            echo  esc_html( $wbm_prod_count_data, 'banner-management-for-woocommerce' ) ;
                            ?></span>
												<?php 
                        }
                        
                        ?>
											</a>
											<?php 
                        
                        if ( 'on' === $wbm_cat_prod_count && 'under_cat' === $wbm_cat_count_position ) {
                            ?>
												<p class="prod-count"><?php 
                            echo  esc_html( $wbm_prod_count_data, 'banner-management-for-woocommerce' ) ;
                            ?></p>
												<?php 
                        }
                        
                        ?>
										</h3>
										<?php 
                    }
                    
                    
                    if ( isset( $wbm_cat_desc_status ) && 'on' === $wbm_cat_desc_status ) {
                        // strip tags to avoid breaking any html
                        $catDesc = wp_strip_all_tags( $term->description );
                        
                        if ( strlen( $catDesc ) > 110 ) {
                            // truncate string
                            $catDescCut = substr( $catDesc, 0, 110 );
                            $endPoint = strrpos( $catDescCut, ' ' );
                            //if the string doesn't contain any space then it will cut without word basis.
                            $catDesc = ( $endPoint ? substr( $catDescCut, 0, $endPoint ) : substr( $catDescCut, 0 ) );
                            $catDesc .= '...';
                        }
                        
                        ?>
										<p class="cat-desc"><?php 
                        echo  esc_html( $catDesc, 'banner-management-for-woocommerce' ) ;
                        ?></p>
										<?php 
                    }
                    
                    
                    if ( isset( $wbm_shop_now_button ) && 'on' === $wbm_shop_now_button ) {
                        ?>
										<div class="shop-now-btn">
											<a href="<?php 
                        echo  esc_url( $term_link ) ;
                        ?>" target="<?php 
                        esc_attr_e( $wbm_shop_now_link_target, 'banner-management-for-woocommerce' );
                        ?>"><?php 
                        echo  esc_html( $wbm_shop_now_label, 'banner-management-for-woocommerce' ) ;
                        ?></a>
										</div>
										<?php 
                    }
                    
                    ?>
								</div>
							</li>
							<?php 
                }
            }
            
            ?>
				</ul>
				<?php 
        } else {
            ?>
	        	<p class="wbm-slider-error"><?php 
            esc_html_e( 'Please enable the slider\'s status.!!', 'banner-management-for-woocommerce' );
            ?></p>
	        	<?php 
        }
        
        ?>
        </div>
        <?php 
        wp_die();
    }
    
    /**
     * Show preview of product slider settings
     *
     * @since 2.3.0
     */
    public function wcbm_show_product_slider_settings_preview()
    {
        // Security check
        check_ajax_referer( 'ajax_verification', 'security' );
        // Show product slider preview
        $get_wbm_prod_slider_status = filter_input( INPUT_POST, 'wbm_prod_slider_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_filter_products = filter_input( INPUT_POST, 'wbm_filter_products', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_choose_products = filter_input(
            INPUT_POST,
            'wbm_choose_products',
            FILTER_SANITIZE_NUMBER_INT,
            FILTER_REQUIRE_ARRAY
        );
        $get_wbm_featured_products = filter_input(
            INPUT_POST,
            'wbm_featured_products',
            FILTER_SANITIZE_NUMBER_INT,
            FILTER_REQUIRE_ARRAY
        );
        $get_wbm_choose_by_cat = filter_input(
            INPUT_POST,
            'wbm_choose_by_cat',
            FILTER_SANITIZE_NUMBER_INT,
            FILTER_REQUIRE_ARRAY
        );
        $get_wbm_total_prod_show = filter_input( INPUT_POST, 'wbm_total_prod_show', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_order_by = filter_input( INPUT_POST, 'wbm_prod_order_by', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_order = filter_input( INPUT_POST, 'wbm_prod_order', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_sec_title_status = filter_input( INPUT_POST, 'wbm_prod_sec_title_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_slider_rule_name = filter_input( INPUT_POST, 'wbm_prod_slider_rule_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_title_color = filter_input( INPUT_POST, 'wbm_prod_title_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_title_top_margin = filter_input( INPUT_POST, 'wbm_prod_title_top_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_title_right_margin = filter_input( INPUT_POST, 'wbm_prod_title_right_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_title_bottom_margin = filter_input( INPUT_POST, 'wbm_prod_title_bottom_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_title_left_margin = filter_input( INPUT_POST, 'wbm_prod_title_left_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_title_unit_margin = filter_input( INPUT_POST, 'wbm_prod_title_unit_margin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_autoplay = filter_input( INPUT_POST, 'wbm_prod_autoplay', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_autoplay_speed = filter_input( INPUT_POST, 'wbm_prod_autoplay_speed', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_scroll_speed = filter_input( INPUT_POST, 'wbm_prod_scroll_speed', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_pause_on_hov = filter_input( INPUT_POST, 'wbm_prod_pause_on_hov', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_infinite_loop = filter_input( INPUT_POST, 'wbm_prod_infinite_loop', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_auto_height = filter_input( INPUT_POST, 'wbm_prod_auto_height', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_nav_color = filter_input( INPUT_POST, 'wbm_prod_nav_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_nav_hov_color = filter_input( INPUT_POST, 'wbm_prod_nav_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_nav_bg_color = filter_input( INPUT_POST, 'wbm_prod_nav_bg_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_nav_bg_hov_color = filter_input( INPUT_POST, 'wbm_prod_nav_bg_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_nav_all_border = filter_input( INPUT_POST, 'wbm_prod_nav_all_border', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_nav_border_style = filter_input( INPUT_POST, 'wbm_prod_nav_border_style', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_nav_border_color = filter_input( INPUT_POST, 'wbm_prod_nav_border_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_nav_border_hov_color = filter_input( INPUT_POST, 'wbm_prod_nav_border_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_pager_color = filter_input( INPUT_POST, 'wbm_prod_pager_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_pager_active_color = filter_input( INPUT_POST, 'wbm_prod_pager_active_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_wbm_prod_pager_hov_color = filter_input( INPUT_POST, 'wbm_prod_pager_hov_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $dswbm_slider_mode = filter_input( INPUT_POST, 'dswbm_slider_mode', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_thumb_zoom = filter_input( INPUT_POST, 'wbm_prod_thumb_zoom', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_slide_large_desktop = filter_input( INPUT_POST, 'wbm_prod_slide_large_desktop', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_slide_desktop = filter_input( INPUT_POST, 'wbm_prod_slide_desktop', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_slide_laptop = filter_input( INPUT_POST, 'wbm_prod_slide_laptop', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_slide_large_tablet = filter_input( INPUT_POST, 'wbm_prod_slide_large_tablet', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_slide_large_mobile = filter_input( INPUT_POST, 'wbm_prod_slide_large_mobile', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_touch_swipe_status = filter_input( INPUT_POST, 'wbm_prod_touch_swipe_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_mousewheel_control_status = filter_input( INPUT_POST, 'wbm_prod_mousewheel_control_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_mouse_draggable_status = filter_input( INPUT_POST, 'wbm_prod_mouse_draggable_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_v_offset = filter_input( INPUT_POST, 'wbm_prod_v_offset', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_h_offset = filter_input( INPUT_POST, 'wbm_prod_h_offset', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_blur = filter_input( INPUT_POST, 'wbm_prod_blur', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_spread = filter_input( INPUT_POST, 'wbm_prod_spread', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_shadow = filter_input( INPUT_POST, 'wbm_prod_shadow', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_color = filter_input( INPUT_POST, 'wbm_prod_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_hover_color = filter_input( INPUT_POST, 'wbm_prod_hover_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $wbm_prod_slider_status = ( !empty($get_wbm_prod_slider_status) ? sanitize_text_field( wp_unslash( $get_wbm_prod_slider_status ) ) : '' );
        $wbm_filter_products = ( !empty($get_wbm_filter_products) ? sanitize_text_field( wp_unslash( $get_wbm_filter_products ) ) : '' );
        $wbm_choose_products = ( isset( $get_wbm_choose_products ) ? array_map( 'sanitize_text_field', $get_wbm_choose_products ) : array() );
        $wbm_featured_products = ( isset( $get_wbm_featured_products ) ? array_map( 'sanitize_text_field', $get_wbm_featured_products ) : array() );
        $wbm_choose_by_cat = ( isset( $get_wbm_choose_by_cat ) ? array_map( 'sanitize_text_field', $get_wbm_choose_by_cat ) : array() );
        $wbm_total_prod_show = ( !empty($get_wbm_total_prod_show) ? sanitize_text_field( wp_unslash( $get_wbm_total_prod_show ) ) : '' );
        $wbm_prod_order_by = ( !empty($get_wbm_prod_order_by) ? sanitize_text_field( wp_unslash( $get_wbm_prod_order_by ) ) : '' );
        $wbm_prod_order = ( !empty($get_wbm_prod_order) ? sanitize_text_field( wp_unslash( $get_wbm_prod_order ) ) : '' );
        $wbm_prod_sec_title_status = ( !empty($get_wbm_prod_sec_title_status) ? sanitize_text_field( wp_unslash( $get_wbm_prod_sec_title_status ) ) : '' );
        $wbm_prod_slider_rule_name = ( !empty($get_wbm_prod_slider_rule_name) ? sanitize_text_field( wp_unslash( $get_wbm_prod_slider_rule_name ) ) : '' );
        $wbm_prod_title_color = ( !empty($get_wbm_prod_title_color) ? sanitize_text_field( wp_unslash( $get_wbm_prod_title_color ) ) : '' );
        $wbm_prod_title_top_margin = ( isset( $get_wbm_prod_title_top_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_prod_title_top_margin ) ) : '' );
        $wbm_prod_title_right_margin = ( isset( $get_wbm_prod_title_right_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_prod_title_right_margin ) ) : '' );
        $wbm_prod_title_bottom_margin = ( isset( $get_wbm_prod_title_bottom_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_prod_title_bottom_margin ) ) : '' );
        $wbm_prod_title_left_margin = ( isset( $get_wbm_prod_title_left_margin ) ? sanitize_text_field( wp_unslash( $get_wbm_prod_title_left_margin ) ) : '' );
        $wbm_prod_title_unit_margin = ( !empty($get_wbm_prod_title_unit_margin) ? sanitize_text_field( wp_unslash( $get_wbm_prod_title_unit_margin ) ) : '' );
        $wbm_prod_autoplay = ( !empty($get_wbm_prod_autoplay) ? sanitize_text_field( wp_unslash( $get_wbm_prod_autoplay ) ) : '' );
        $wbm_prod_autoplay_speed = ( !empty($get_wbm_prod_autoplay_speed) ? sanitize_text_field( wp_unslash( $get_wbm_prod_autoplay_speed ) ) : '' );
        $wbm_prod_scroll_speed = ( !empty($get_wbm_prod_scroll_speed) ? sanitize_text_field( wp_unslash( $get_wbm_prod_scroll_speed ) ) : '' );
        $wbm_prod_pause_on_hov = ( !empty($get_wbm_prod_pause_on_hov) ? sanitize_text_field( wp_unslash( $get_wbm_prod_pause_on_hov ) ) : '' );
        $wbm_prod_infinite_loop = ( !empty($get_wbm_prod_infinite_loop) ? sanitize_text_field( wp_unslash( $get_wbm_prod_infinite_loop ) ) : '' );
        $wbm_prod_auto_height = ( !empty($get_wbm_prod_auto_height) ? sanitize_text_field( wp_unslash( $get_wbm_prod_auto_height ) ) : '' );
        $wbm_prod_nav_color = ( !empty($get_wbm_prod_nav_color) ? sanitize_text_field( wp_unslash( $get_wbm_prod_nav_color ) ) : '' );
        $wbm_prod_nav_hov_color = ( !empty($get_wbm_prod_nav_hov_color) ? sanitize_text_field( wp_unslash( $get_wbm_prod_nav_hov_color ) ) : '' );
        $wbm_prod_nav_bg_color = ( !empty($get_wbm_prod_nav_bg_color) ? sanitize_text_field( wp_unslash( $get_wbm_prod_nav_bg_color ) ) : '' );
        $wbm_prod_nav_bg_hov_color = ( !empty($get_wbm_prod_nav_bg_hov_color) ? sanitize_text_field( wp_unslash( $get_wbm_prod_nav_bg_hov_color ) ) : '' );
        $wbm_prod_nav_all_border = ( !empty($get_wbm_prod_nav_all_border) ? sanitize_text_field( wp_unslash( $get_wbm_prod_nav_all_border ) ) : '' );
        $wbm_prod_nav_border_style = ( !empty($get_wbm_prod_nav_border_style) ? sanitize_text_field( wp_unslash( $get_wbm_prod_nav_border_style ) ) : '' );
        $wbm_prod_nav_border_color = ( !empty($get_wbm_prod_nav_border_color) ? sanitize_text_field( wp_unslash( $get_wbm_prod_nav_border_color ) ) : '' );
        $wbm_prod_nav_border_hov_color = ( !empty($get_wbm_prod_nav_border_hov_color) ? sanitize_text_field( wp_unslash( $get_wbm_prod_nav_border_hov_color ) ) : '' );
        $wbm_prod_pager_color = ( !empty($get_wbm_prod_pager_color) ? sanitize_text_field( wp_unslash( $get_wbm_prod_pager_color ) ) : '' );
        $wbm_prod_pager_active_color = ( !empty($get_wbm_prod_pager_active_color) ? sanitize_text_field( wp_unslash( $get_wbm_prod_pager_active_color ) ) : '' );
        $wbm_prod_pager_hov_color = ( !empty($get_wbm_prod_pager_hov_color) ? sanitize_text_field( wp_unslash( $get_wbm_prod_pager_hov_color ) ) : '' );
        $dswbm_slider_mod = ( !empty($dswbm_slider_mod) ? sanitize_text_field( wp_unslash( $dswbm_slider_mod ) ) : '' );
        $wbm_prod_thumb_zoom = ( !empty($wbm_prod_thumb_zoom) ? sanitize_text_field( wp_unslash( $wbm_prod_thumb_zoom ) ) : '' );
        $wbm_prod_v_offset = ( !empty($wbm_prod_v_offset) ? sanitize_text_field( wp_unslash( $wbm_prod_v_offset ) ) : 0 );
        $wbm_prod_h_offset = ( !empty($wbm_prod_h_offset) ? sanitize_text_field( wp_unslash( $wbm_prod_h_offset ) ) : 0 );
        $wbm_prod_blur = ( !empty($wbm_prod_blur) ? sanitize_text_field( wp_unslash( $wbm_prod_blur ) ) : 0 );
        $wbm_prod_spread = ( !empty($wbm_prod_spread) ? sanitize_text_field( wp_unslash( $wbm_prod_spread ) ) : 0 );
        $wbm_prod_shadow = ( !empty($wbm_prod_shadow) && 'outset' !== $wbm_prod_shadow ? sanitize_text_field( wp_unslash( $wbm_prod_shadow ) ) : '' );
        $wbm_prod_color = ( !empty($wbm_prod_color) ? sanitize_text_field( wp_unslash( $wbm_prod_color ) ) : '' );
        $wbm_prod_hover_color = ( !empty($wbm_prod_hover_color) ? sanitize_text_field( wp_unslash( $wbm_prod_hover_color ) ) : '' );
        $wbm_prod_touch_swipe_status = ( !empty($wbm_prod_touch_swipe_status) ? sanitize_text_field( wp_unslash( $wbm_prod_touch_swipe_status ) ) : '' );
        $wbm_prod_mousewheel_control_status = ( !empty($wbm_prod_mousewheel_control_status) ? sanitize_text_field( wp_unslash( $wbm_prod_mousewheel_control_status ) ) : '' );
        $wbm_prod_mouse_draggable_status = ( !empty($wbm_prod_mouse_draggable_status) ? sanitize_text_field( wp_unslash( $wbm_prod_mouse_draggable_status ) ) : '' );
        // setup query
        $args = array(
            'post_type'   => 'product',
            'post_status' => 'publish',
        );
        if ( isset( $wbm_total_prod_show ) ) {
            $args['posts_per_page'] = $wbm_total_prod_show;
        }
        if ( isset( $wbm_filter_products ) && 'wbm_specific_products' === $wbm_filter_products ) {
            if ( isset( $wbm_choose_products ) ) {
                $args['post__in'] = $wbm_choose_products;
            }
        }
        if ( isset( $wbm_filter_products ) && 'wbm_exclude_product' === $wbm_filter_products ) {
            if ( isset( $get_wbm_exclude_products ) ) {
                $args['post__not_in'] = $get_wbm_exclude_products;
            }
        }
        if ( isset( $wbm_filter_products ) && 'wbm_featured_products' === $wbm_filter_products ) {
            if ( isset( $wbm_choose_by_cat ) ) {
                $args['post__in'] = $wbm_featured_products;
            }
        }
        if ( isset( $wbm_prod_order_by ) ) {
            $args['orderby'] = $wbm_prod_order_by;
        }
        if ( isset( $wbm_prod_order ) ) {
            $args['order'] = $wbm_prod_order;
        }
        $args['fields'] = 'ids';
        // query database
        $products = new WP_Query( $args );
        ?>
		<div class="wbm-product-slider-section dswbm-sliders-main" auto-play="<?php 
        esc_attr_e( $wbm_prod_autoplay, 'banner-management-for-woocommerce' );
        ?>" auto-play-speed="<?php 
        esc_attr_e( $wbm_prod_autoplay_speed, 'banner-management-for-woocommerce' );
        ?>" scroll-speed="<?php 
        esc_attr_e( $wbm_prod_scroll_speed, 'banner-management-for-woocommerce' );
        ?>" pause-hover="<?php 
        esc_attr_e( $wbm_prod_pause_on_hov, 'banner-management-for-woocommerce' );
        ?>" infinite-loop="<?php 
        esc_attr_e( $wbm_prod_infinite_loop, 'banner-management-for-woocommerce' );
        ?>" auto-height="<?php 
        esc_attr_e( $wbm_prod_auto_height, 'banner-management-for-woocommerce' );
        ?>" slider-mode="<?php 
        esc_attr_e( $dswbm_slider_mode, 'banner-management-for-woocommerce' );
        ?>"
		slider-touch-status="<?php 
        esc_attr_e( $wbm_prod_touch_swipe_status, 'banner-management-for-woocommerce' );
        ?>"
		slider-mousewheel-status="<?php 
        esc_attr_e( $wbm_prod_mousewheel_control_status, 'banner-management-for-woocommerce' );
        ?>"
		slider-mouse_draggable-status="<?php 
        esc_attr_e( $wbm_prod_mouse_draggable_status, 'banner-management-for-woocommerce' );
        ?>">
			<?php 
        
        if ( isset( $wbm_product_title_typo_font_family ) && !empty($wbm_product_title_typo_font_family) && 'none' !== $wbm_product_title_typo_font_family ) {
            ?>	
				<link href='https://fonts.googleapis.com/css?family=<?php 
            echo  esc_attr( $wbm_product_title_typo_font_family ) ;
            ?>' rel='stylesheet' type="text/css"> <?php 
            // phpcs:ignore
            ?>
			<?php 
        }
        
        ?>
			<?php 
        
        if ( isset( $wbm_product_title_typo_shopbtn_font_family ) && !empty($wbm_product_title_typo_shopbtn_font_family) && 'none' !== $wbm_product_title_typo_shopbtn_font_family ) {
            ?>
				<link href='https://fonts.googleapis.com/css?family=<?php 
            echo  esc_attr( $wbm_product_title_typo_shopbtn_font_family ) ;
            ?>' rel='stylesheet' type="text/css"> <?php 
            // phpcs:ignore
            ?>
			<?php 
        }
        
        ?>
			<?php 
        
        if ( isset( $wbm_product_title_typo_addtocart_font_family ) && !empty($wbm_product_title_typo_addtocart_font_family) && 'none' !== $wbm_product_title_typo_addtocart_font_family ) {
            ?>
				<link href='https://fonts.googleapis.com/css?family=<?php 
            echo  esc_attr( $wbm_product_title_typo_addtocart_font_family ) ;
            ?>' rel='stylesheet' type="text/css"> <?php 
            // phpcs:ignore
            ?>
			<?php 
        }
        
        ?>
			<?php 
        $current_theme = get_current_theme();
        ?>
			<style>
				<?php 
        if ( 'Neve' === $current_theme ) {
            ?>
					ul.products li.product .button{background:#2f5aae;color:#fff;border-color:#fff;}
					ul.products li.product .button:hover{opacity:0.8;background:#2f5aae;color:#fff;}
					ul.products li.product{text-align:left;}
					ul.products li.product .onsale{position:absolute;top:0;left:0;background:#9463ae;color:#fff;border-radius:0;border-color:#9463ae;padding:0.5em 0.6180469716em;}
					ul.products li.product .woocommerce-loop-product__title{font-size:1.25em;color:#272626;}
					ul.products li.product .price ins{font-weight:700;}
					<?php 
        }
        ?>
				<?php 
        if ( 'Twenty Twenty-Two' === $current_theme ) {
            ?>
					ul.products li.product h2.woocommerce-loop-product__title{font-size:1.2rem;color:#1a4548;}
					ul.products li.product .price span{color:#000;font-size:18px;}
					ul.products li.product .price del span{color:#000;opacity:1;}
					ul.products li.product .button{padding:.8rem 2.7rem;background-color:#1a4548;color:#fff;font-size:16px;line-height:25px;border-radius:0;}
					ul.products li.product .button:hover{opacity:1;background:#1a4548;color:#fff;text-decoration:underline;}
					ul.products li.product .onsale{position:absolute;top:-1rem;right:-1rem;background:#ffe2c7;color:#000;border-radius:2rem;border-color:#ffe2c7;padding:0 0.5rem 0 0.5rem;font-size:.8rem;line-height:2.6rem;text-transform:capitalize;font-weight:300;}
					<?php 
        }
        ?>
				<?php 
        if ( 'Astra' === $current_theme ) {
            ?>
					ul.products li.product{text-align:left;}
					ul.products li.product h2.woocommerce-loop-product__title{font-size:15px;color:#3a3a3a;}
					ul.products li.product .price{margin-bottom:0.9em;}
					ul.products li.product .price span{color:#4b4f58;font-size:13px;font-weight:700;}
					ul.products li.product .price del span{color:#4b4f58;opacity:.5;}
					ul.products li.product .button{padding:15px 30px;background-color:#0370b8;color:#fff;font-size:15px;line-height:19px;}
					ul.products li.product .button:hover{opacity:1;background:#3a3a3a;color:#fff;}
					ul.products li.product .onsale{position:absolute;top:0;right:0;background:#0170b9;color:#fff;border-radius:100%;border-color:#0170b9;padding:0;font-size:1em;line-height:3em;text-transform:capitalize;font-weight:400;margin:-.5em -.5em 0 0;min-width:3em;min-height:3em;text-align:center;}
					<?php 
        }
        ?>
				.wbm-product-slider-section .slider-section-title {
        			margin-top: <?php 
        esc_html_e( $wbm_prod_title_top_margin . $wbm_prod_title_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-bottom: <?php 
        esc_html_e( $wbm_prod_title_bottom_margin . $wbm_prod_title_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-right: <?php 
        esc_html_e( $wbm_prod_title_right_margin . $wbm_prod_title_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        			margin-left: <?php 
        esc_html_e( $wbm_prod_title_left_margin . $wbm_prod_title_unit_margin, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.wbm-product-slider-section .slider-section-title h3 {
        			color: <?php 
        esc_html_e( $wbm_prod_title_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.dswbm-sliders-main .bx-wrapper .bx-controls-direction a i {
        			color: <?php 
        esc_html_e( $wbm_prod_nav_color, 'banner-management-for-woocommerce' );
        ?>;
        			background-color: <?php 
        esc_html_e( $wbm_prod_nav_bg_color, 'banner-management-for-woocommerce' );
        ?>;
        			border-width: <?php 
        esc_html_e( $wbm_prod_nav_all_border . 'px', 'banner-management-for-woocommerce' );
        ?>;
        			border-style: <?php 
        esc_html_e( $wbm_prod_nav_border_style, 'banner-management-for-woocommerce' );
        ?>;
        			border-color: <?php 
        esc_html_e( $wbm_prod_nav_border_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.dswbm-sliders-main .bx-wrapper .bx-controls-direction a i:hover {
        			color: <?php 
        esc_html_e( $wbm_prod_nav_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        			background-color: <?php 
        esc_html_e( $wbm_prod_nav_bg_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        			border-color: <?php 
        esc_html_e( $wbm_prod_nav_border_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.dswbm-sliders-main .bx-wrapper .bx-pager .bx-pager-item a {
        			background-color: <?php 
        esc_html_e( $wbm_prod_pager_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.dswbm-sliders-main .bx-wrapper .bx-pager .bx-pager-item a:hover {
        			background-color: <?php 
        esc_html_e( $wbm_prod_pager_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		.dswbm-sliders-main .bx-wrapper .bx-pager .bx-pager-item a.active {
        			background-color: <?php 
        esc_html_e( $wbm_prod_pager_active_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
				<?php 
        
        if ( isset( $wbm_prod_thumb_zoom ) && 'zoom-in' === $wbm_prod_thumb_zoom ) {
            ?>
					.wbm-product-slider-section .product a img:hover{
						-webkit-transform: scale(1.2);
						-moz-transform: scale(1.2);
						transform: scale(1.2);
					}
				<?php 
        } elseif ( isset( $wbm_prod_thumb_zoom ) && 'zoom-out' === $wbm_prod_thumb_zoom ) {
            ?>
					.wbm-product-slider-section .product a img{
						-webkit-transform: scale3d(1.2,1.2,1);
						-moz-transform: scale3d(1.2,1.2,1);
						transform: scale3d(1.2,1.2,1);
					}
					.wbm-product-slider-section .product a img:hover{
						-webkit-transform: none;
						-moz-transform: none;
						transform: none;
					}
				<?php 
        }
        
        ?>
				
				.wbm-product-slider-section .product{
					-webkit-box-shadow: <?php 
        echo  esc_attr( $wbm_prod_shadow ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_v_offset . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_h_offset . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_blur . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_spread . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_color ) ;
        ?>;
					-moz-box-shadow: <?php 
        echo  esc_attr( $wbm_prod_shadow ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_v_offset . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_h_offset . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_blur . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_spread . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_color ) ;
        ?>;
					box-shadow: <?php 
        echo  esc_attr( $wbm_prod_shadow ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_v_offset . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_h_offset . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_blur . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_spread . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_color ) ;
        ?>;
				}

				.wbm-product-slider-section .product:hover{
					-webkit-box-shadow: <?php 
        echo  esc_attr( $wbm_prod_shadow ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_v_offset . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_h_offset . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_blur . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_spread . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_hover_color ) ;
        ?>;
					-moz-box-shadow: <?php 
        echo  esc_attr( $wbm_prod_shadow ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_v_offset . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_h_offset . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_blur . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_spread . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_hover_color ) ;
        ?>;
					box-shadow: <?php 
        echo  esc_attr( $wbm_prod_shadow ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_v_offset . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_h_offset . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_blur . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_spread . 'px' ) ;
        ?> <?php 
        echo  esc_attr( $wbm_prod_hover_color ) ;
        ?>;
				}
				<?php 
        ?>

			</style>
			<?php 
        
        if ( isset( $wbm_prod_sec_title_status ) && 'on' === $wbm_prod_sec_title_status ) {
            ?>
				<div class="slider-section-title">
					<h3><?php 
            esc_html_e( $wbm_prod_slider_rule_name, 'banner-management-for-woocommerce' );
            ?></h3>
				</div>
				<?php 
        }
        
        
        if ( isset( $wbm_prod_slider_status ) && 'on' === $wbm_prod_slider_status ) {
            
            if ( isset( $products->posts ) && !empty($products->posts) && is_array( $products->posts ) ) {
                ?>
        			<ul class="wbm-slider products">
	        			<?php 
                foreach ( $products->posts as $product_id ) {
                    $product = wc_get_product( $product_id );
                    ?>
							<li <?php 
                    wc_product_class( '', $product );
                    ?>>
								
								<a href="<?php 
                    echo  esc_url( get_permalink( $product->get_id() ), 'banner-management-for-woocommerce' ) ;
                    ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
									<div class="prod-img">
										<?php 
                    $prod_thumb_id = get_post_thumbnail_id( $product_id );
                    
                    if ( isset( $prod_thumb_id ) && !empty($prod_thumb_id) && intval( $prod_thumb_id ) !== 0 ) {
                        $prod_image = wp_get_attachment_image_src( $prod_thumb_id, 'single-post-thumbnail' );
                        ?>
											<img src="<?php 
                        echo  esc_url( $prod_image[0], 'banner-management-for-woocommerce' ) ;
                        ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="<?php 
                        echo  esc_attr( $product->get_name(), 'banner-management-for-woocommerce' ) ;
                        ?>">
											<?php 
                    } else {
                        $placeholder_src = wc_placeholder_img_src();
                        ?>
											<img src="<?php 
                        echo  esc_url( $placeholder_src, 'banner-management-for-woocommerce' ) ;
                        ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="<?php 
                        echo  esc_attr( $product->get_name(), 'banner-management-for-woocommerce' ) ;
                        ?>">
											<?php 
                    }
                    
                    ?>
									</div>
										<h2 class="woocommerce-loop-product__title"><?php 
                    echo  esc_html( $product->get_name(), 'banner-management-for-woocommerce' ) ;
                    ?></h2>
										<?php 
                    
                    if ( $product->is_on_sale() ) {
                        ?>
											<span class="onsale"><?php 
                        esc_html_e( 'Sale!', 'banner-management-for-woocommerce' );
                        ?></span>
											<?php 
                    }
                    
                    ?>
										<span class="price"><?php 
                    echo  wp_kses_post( $product->get_price_html() ) ;
                    ?></span>
									</a>
								<?php 
                    $prod_button_label = '';
                    
                    if ( !$product->is_in_stock() && $product->is_type( 'simple' ) ) {
                        $prod_button_label = esc_html__( 'Read more', 'banner-management-for-woocommerce' );
                    } elseif ( $product->is_type( 'variable' ) ) {
                        $prod_button_label = esc_html__( 'Select options', 'banner-management-for-woocommerce' );
                    } elseif ( $product->is_type( 'simple' ) ) {
                        $prod_button_label = esc_html__( 'Add to cart', 'banner-management-for-woocommerce' );
                    } else {
                        $prod_button_label = esc_html__( 'View products', 'banner-management-for-woocommerce' );
                    }
                    
                    ?>
								<a href="<?php 
                    echo  esc_url( get_permalink( $product->get_id() ), 'banner-management-for-woocommerce' ) ;
                    ?>" data-quantity="1" class="button add_to_cart_button" data-product_id="<?php 
                    echo  esc_attr( $product->get_id(), 'banner-management-for-woocommerce' ) ;
                    ?>" data-product_sku="<?php 
                    echo  esc_attr( $product->get_sku(), 'banner-management-for-woocommerce' ) ;
                    ?>" rel="nofollow"><?php 
                    esc_html_e( $prod_button_label, 'banner-management-for-woocommerce' );
                    ?></a>
							</li>
							<?php 
                }
                ?>
	        		</ul>
        			<?php 
            }
        
        } else {
            ?>
	        	<p class="wbm-slider-error"><?php 
            esc_html_e( 'Please enable the slider\'s status.!!', 'banner-management-for-woocommerce' );
            ?></p>
	        	<?php 
        }
        
        ?>
	    </div>
        <?php 
        wp_die();
    }
    
    /**
     * Get and save plugin setup wizard data
     * 
     * @since    3.9.3
     * 
     */
    public function wcbm_plugin_setup_wizard_submit()
    {
        check_ajax_referer( 'wizard_ajax_nonce', 'nonce' );
        $survey_list = filter_input( INPUT_GET, 'survey_list', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( !empty($survey_list) && 'Select One' !== $survey_list ) {
            update_option( 'wcbm_where_hear_about_us', $survey_list );
        }
        wp_die();
    }
    
    /**
     * Send setup wizard data to sendinblue
     * 
     * @since    3.9.3
     * 
     */
    public function wcbm_send_wizard_data_after_plugin_activation()
    {
        $send_wizard_data = filter_input( INPUT_GET, 'send-wizard-data', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( isset( $send_wizard_data ) && !empty($send_wizard_data) ) {
            
            if ( !get_option( 'wcbm_data_submited_in_sendiblue' ) ) {
                $wcbm_where_hear = get_option( 'wcbm_where_hear_about_us' );
                $get_user = wcbm_fs()->get_user();
                $data_insert_array = array();
                if ( isset( $get_user ) && !empty($get_user) ) {
                    $data_insert_array = array(
                        'user_email'              => $get_user->email,
                        'ACQUISITION_SURVEY_LIST' => $wcbm_where_hear,
                    );
                }
                $feedback_api_url = WCBM_STORE_URL . '/wp-json/dotstore-sendinblue-data/v2/dotstore-sendinblue-data?' . wp_rand();
                $query_url = $feedback_api_url . '&' . http_build_query( $data_insert_array );
                
                if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
                    $response = vip_safe_wp_remote_get(
                        $query_url,
                        3,
                        1,
                        20
                    );
                } else {
                    $response = wp_remote_get( $query_url );
                }
                
                
                if ( !is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
                    update_option( 'wcbm_data_submited_in_sendiblue', '1' );
                    delete_option( 'wcbm_where_hear_about_us' );
                }
            
            }
        
        }
    }

}