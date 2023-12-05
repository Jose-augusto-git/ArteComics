<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    woocommerce_category_banner_management
 * @subpackage woocommerce_category_banner_management/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Banner_Management
 * @subpackage Woo_Banner_Management/public
 * @author     Multidots <inquiry@multidots.in>
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
class woocommerce_category_banner_management_Public
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
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function wcbm_enqueue_styles_scripts()
    {
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
        $banner_data = array();
        
        if ( function_exists( 'is_product' ) && is_product() || function_exists( 'is_product_category' ) && is_product_category() || function_exists( 'is_product_tag' ) && is_product_tag() || function_exists( 'is_shop' ) && is_shop() || function_exists( 'is_cart' ) && is_cart() || function_exists( 'is_checkout' ) && is_checkout() || function_exists( 'is_page' ) && is_page() ) {
            //enqueue stylesheets.
            wp_enqueue_style(
                'jquery-bxslider-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery.bxslider.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'css/woocommerce-category-banner-management-public.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'fontawesome-css',
                plugin_dir_url( __FILE__ ) . 'css/all.min.css',
                array(),
                $this->version,
                'all'
            );
            //enqueue JavaScripts.
            wp_enqueue_script(
                'jquery-bxslider',
                plugin_dir_url( __FILE__ ) . 'js/jquery.bxslider.min.js',
                array( 'jquery' ),
                $this->version
            );
            wp_enqueue_script(
                $this->plugin_name . '-public-js',
                plugin_dir_url( __FILE__ ) . 'js/woocommerce-category-banner-management-public' . $suffix . '.js',
                array( 'jquery' ),
                $this->version
            );
        }
    
    }
    
    /**
     * Function returns the override woocommerce template path.
     *
     * @param $template
     * @param $template_name
     * @param $template_path
     *
     * @return string
     */
    public function wcbm_woocommerce_locate_template( $template, $template_name, $template_path )
    {
        global  $woocommerce ;
        $_template = $template;
        if ( !$template_path ) {
            $template_path = $woocommerce->template_url;
        }
        $path = untrailingslashit( plugin_dir_path( __FILE__ ) );
        $plugin_path = $path . '/woocommerce/';
        $template = locate_template( array( $template_path . $template_name, $template_name ) );
        // Modification: Get the template from this plugin, if it exists
        if ( file_exists( $plugin_path . $template_name ) ) {
            $template = $plugin_path . $template_name;
        }
        if ( !$template ) {
            $template = $_template;
        }
        // Return what we found
        return $template;
    }

}