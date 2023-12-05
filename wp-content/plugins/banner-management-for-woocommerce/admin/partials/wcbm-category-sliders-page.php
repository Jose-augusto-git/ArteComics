<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * wbm_banner_management_category_slider_Page class.
 */
if ( !class_exists( 'wbm_banner_management_category_slider_Page' ) ) {
    class wbm_banner_management_category_slider_Page
    {
        /**
         * Output the Admin UI
         *
         * @since 1.0.0
         */
        const  post_type = 'wcbm_category_slider' ;
        private static  $admin_object = null ;
        /**
         * Register post type
         *
         * @since 1.0.0
         */
        public static function wcbm_register_post_type()
        {
            register_post_type( self::post_type, array(
                'labels'          => array(
                'name'          => __( 'Category Sliders', 'banner-management-for-woocommerce' ),
                'singular_name' => __( 'Category Slider', 'banner-management-for-woocommerce' ),
            ),
                'rewrite'         => false,
                'query_var'       => false,
                'public'          => false,
                'capability_type' => 'page',
                'capabilities'    => array(
                'edit_post'          => 'edit_category_sliders',
                'read_post'          => 'read_category_sliders',
                'delete_post'        => 'delete_category_sliders',
                'edit_posts'         => 'edit_category_sliders',
                'edit_others_posts'  => 'edit_category_sliders',
                'publish_posts'      => 'edit_category_sliders',
                'read_private_posts' => 'edit_category_sliders',
            ),
            ) );
        }
        
        /**
         * Display output
         *
         * @since 1.0.0
         *
         * @uses woocommerce_category_banner_management_Admin
         * @uses wcbm_save_category_slider_settings
         * @uses wcbm_add_category_sliders_form
         * @uses wcbm_category_slider_edit_screen
         * @uses wcbm_category_slider_delete
         * @uses wcbm_category_sliders_list_screen
         * @uses woocommerce_category_banner_management_Admin::wcbm_updated_message()
         *
         * @access   public
         */
        public static function wcbm_category_sliders_output()
        {
            self::$admin_object = new woocommerce_category_banner_management_Admin( '', '' );
            $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $post_id_request = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
            $cust_nonce = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_dswbm_add = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $message = filter_input( INPUT_GET, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            
            if ( isset( $action ) && !empty($action) ) {
                
                if ( 'add' === $action ) {
                    self::wcbm_save_category_slider_settings();
                    self::wcbm_add_category_sliders_form();
                } elseif ( 'edit' === $action ) {
                    
                    if ( isset( $cust_nonce ) && !empty($cust_nonce) ) {
                        $getnonce = wp_verify_nonce( $cust_nonce, 'edit_' . $post_id_request );
                        
                        if ( isset( $getnonce ) && 1 === $getnonce ) {
                            self::wcbm_category_slider_edit_screen( $post_id_request );
                        } else {
                            wp_safe_redirect( add_query_arg( array(
                                'page' => 'wcbm-sliders-settings',
                                'tab'  => 'wcbm-category-sliders',
                            ), admin_url( 'admin.php' ) ) );
                            exit;
                        }
                    
                    } elseif ( isset( $get_dswbm_add ) && !empty($get_dswbm_add) ) {
                        
                        if ( !wp_verify_nonce( $get_dswbm_add, 'dswbm_add' ) ) {
                            $message = 'nonce_check';
                        } else {
                            self::wcbm_category_slider_edit_screen( $post_id_request );
                        }
                    
                    }
                
                } elseif ( 'delete' === $action ) {
                    self::wcbm_category_slider_delete( $post_id_request );
                } else {
                    self::wcbm_category_sliders_list_screen();
                }
            
            } else {
                self::wcbm_category_sliders_list_screen();
            }
            
            if ( isset( $message ) && !empty($message) ) {
                self::$admin_object->wcbm_updated_message( $message, $get_page, "" );
            }
        }
        
        /**
         * Delete category sliders
         *
         * @param int $id
         *
         * @access   public
         * @uses woocommerce_category_banner_management_Admin::wcbm_updated_message()
         *
         * @since    1.0.0
         *
         */
        public static function wcbm_category_slider_delete( $id )
        {
            $cust_nonce = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $getnonce = wp_verify_nonce( $cust_nonce, 'del_' . $id );
            
            if ( isset( $getnonce ) && 1 === $getnonce ) {
                wp_delete_post( $id );
                wp_safe_redirect( add_query_arg( array(
                    'page'    => 'wcbm-sliders-settings',
                    'tab'     => 'wcbm-category-sliders',
                    'message' => 'deleted',
                ), admin_url( 'admin.php' ) ) );
                exit;
            } else {
                self::$admin_object->wcbm_updated_message( 'nonce_check', $get_page, "" );
            }
        
        }
        
        /**
         * Count total sliders
         *
         * @return int $wbm_sliders_list
         * @since    1.0.0
         *
         */
        public static function wcbm_count_category_sliders()
        {
            $wbm_sliders_args = array(
                'post_type'      => self::post_type,
                'post_status'    => array( 'publish', 'draft' ),
                'posts_per_page' => -1,
                'orderby'        => 'ID',
                'order'          => 'DESC',
            );
            $sliders_post_query = new WP_Query( $wbm_sliders_args );
            $wbm_sliders_list = $sliders_post_query->posts;
            return count( $wbm_sliders_list );
        }
        
        /**
         * Save category sliders when add or edit
         *
         * @param int $method_id
         *
         * @return bool false when nonce is not verified
         * @uses wcbm_count_category_sliders()
         *
         * @since    1.0.0
         *
         * @uses woocommerce_category_banner_management_Admin::wcbm_updated_message()
         */
        private static function wcbm_save_category_slider_settings( $method_id = 0 )
        {
            global  $sitepress ;
            $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $dswbm_slider_save = filter_input( INPUT_POST, 'wbm_category_sliders_save', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $wbm_category_sliders_nonce = filter_input( INPUT_POST, 'wbm_category_sliders_save_method_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( isset( $action ) && !empty($action) ) {
                
                if ( isset( $dswbm_slider_save ) ) {
                    
                    if ( empty($wbm_category_sliders_nonce) || !wp_verify_nonce( sanitize_text_field( $wbm_category_sliders_nonce ), 'woocommerce_save_method' ) ) {
                        self::$admin_object->wcbm_updated_message( 'nonce_check', $get_page, '' );
                        wp_die();
                    }
                    
                    $dswbm_slider_status = filter_input( INPUT_POST, 'dswbm_cat_slider_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                    $dswbm_slider_rule_name = filter_input( INPUT_POST, 'dswbm_cat_slider_rule_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                    $conditional_payments_count = self::wcbm_count_category_sliders();
                    settype( $method_id, 'integer' );
                    
                    if ( isset( $dswbm_slider_status ) ) {
                        $post_status = 'publish';
                    } else {
                        $post_status = 'draft';
                    }
                    
                    
                    if ( '' !== $method_id && 0 !== $method_id ) {
                        $dscpw_cp_post = array(
                            'ID'          => $method_id,
                            'post_title'  => sanitize_text_field( $dswbm_slider_rule_name ),
                            'post_status' => $post_status,
                            'menu_order'  => $conditional_payments_count + 1,
                            'post_type'   => self::post_type,
                        );
                        $method_id = wp_update_post( $dscpw_cp_post );
                    } else {
                        $dscpw_cp_post = array(
                            'post_title'  => sanitize_text_field( $dswbm_slider_rule_name ),
                            'post_status' => $post_status,
                            'menu_order'  => $conditional_payments_count + 1,
                            'post_type'   => self::post_type,
                        );
                        $method_id = wp_insert_post( $dscpw_cp_post );
                    }
                    
                    
                    if ( '' !== $method_id && 0 !== $method_id ) {
                        
                        if ( $method_id > 0 ) {
                            $wbm_general_settings = filter_input(
                                INPUT_POST,
                                'wbm_category_slider_general',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $dswbm_slider_mode = ( isset( $wbm_general_settings['dswbm_slider_mode'] ) && !empty($wbm_general_settings['dswbm_slider_mode']) ? $wbm_general_settings['dswbm_slider_mode'] : '' );
                            $wbm_layout_presets = ( isset( $wbm_general_settings['wbm_layout_presets'] ) && !empty($wbm_general_settings['wbm_layout_presets']) ? $wbm_general_settings['wbm_layout_presets'] : '' );
                            $wbm_category_type = ( isset( $wbm_general_settings['wbm_category_type'] ) && !empty($wbm_general_settings['wbm_category_type']) ? $wbm_general_settings['wbm_category_type'] : '' );
                            $wbm_filter_categories = ( isset( $wbm_general_settings['wbm_filter_categories'] ) && !empty($wbm_general_settings['wbm_filter_categories']) ? $wbm_general_settings['wbm_filter_categories'] : '' );
                            $wbm_choose_categories = ( isset( $wbm_general_settings['wbm_choose_categories'] ) && !empty($wbm_general_settings['wbm_choose_categories']) ? $wbm_general_settings['wbm_choose_categories'] : array() );
                            $wbm_exclude_categories = ( isset( $wbm_general_settings['wbm_exclude_categories'] ) && !empty($wbm_general_settings['wbm_exclude_categories']) ? $wbm_general_settings['wbm_exclude_categories'] : array() );
                            $wbm_total_cat_show = ( isset( $wbm_general_settings['wbm_total_cat_show'] ) && !empty($wbm_general_settings['wbm_total_cat_show']) ? $wbm_general_settings['wbm_total_cat_show'] : '' );
                            $wbm_cat_order_by = ( isset( $wbm_general_settings['wbm_cat_order_by'] ) && !empty($wbm_general_settings['wbm_cat_order_by']) ? $wbm_general_settings['wbm_cat_order_by'] : '' );
                            $wbm_cat_order = ( isset( $wbm_general_settings['wbm_cat_order'] ) && !empty($wbm_general_settings['wbm_cat_order']) ? $wbm_general_settings['wbm_cat_order'] : '' );
                            $no_cols_large_desktop = ( isset( $wbm_general_settings['wbm_cat_no_of_cols']['large_desktop'] ) && !empty($wbm_general_settings['wbm_cat_no_of_cols']['large_desktop']) ? $wbm_general_settings['wbm_cat_no_of_cols']['large_desktop'] : '' );
                            $no_cols_desktop = ( isset( $wbm_general_settings['wbm_cat_no_of_cols']['desktop'] ) && !empty($wbm_general_settings['wbm_cat_no_of_cols']['desktop']) ? $wbm_general_settings['wbm_cat_no_of_cols']['desktop'] : '' );
                            $no_cols_laptop = ( isset( $wbm_general_settings['wbm_cat_no_of_cols']['laptop'] ) && !empty($wbm_general_settings['wbm_cat_no_of_cols']['laptop']) ? $wbm_general_settings['wbm_cat_no_of_cols']['laptop'] : '' );
                            $no_cols_tablet = ( isset( $wbm_general_settings['wbm_cat_no_of_cols']['tablet'] ) && !empty($wbm_general_settings['wbm_cat_no_of_cols']['tablet']) ? $wbm_general_settings['wbm_cat_no_of_cols']['tablet'] : '' );
                            $no_cols_mobile = ( isset( $wbm_general_settings['wbm_cat_no_of_cols']['mobile'] ) && !empty($wbm_general_settings['wbm_cat_no_of_cols']['mobile']) ? $wbm_general_settings['wbm_cat_no_of_cols']['mobile'] : '' );
                            $dswbm_child_categories = ( isset( $wbm_general_settings['dswbm_child_categories'] ) && !empty($wbm_general_settings['dswbm_child_categories']) ? $wbm_general_settings['dswbm_child_categories'] : '' );
                            $wbm_choose_categories_pnc_cat = ( isset( $wbm_general_settings['wbm_choose_categories_pnc_cat'] ) && !empty($wbm_general_settings['wbm_choose_categories_pnc_cat']) ? $wbm_general_settings['wbm_choose_categories_pnc_cat'] : array() );
                            $wbm_parent_child_display_type = ( isset( $wbm_general_settings['wbm_parent_child_display_type'] ) && !empty($wbm_general_settings['wbm_parent_child_display_type']) ? $wbm_general_settings['wbm_parent_child_display_type'] : '' );
                            $wbm_cat_no_of_cols_arr = array(
                                'large_desktop' => $no_cols_large_desktop,
                                'desktop'       => $no_cols_desktop,
                                'laptop'        => $no_cols_laptop,
                                'tablet'        => $no_cols_tablet,
                                'mobile'        => $no_cols_mobile,
                            );
                            $wbm_general_settings_arr[] = array(
                                'dswbm_slider_mode'             => $dswbm_slider_mode,
                                'wbm_layout_presets'            => $wbm_layout_presets,
                                'wbm_category_type'             => $wbm_category_type,
                                'wbm_filter_categories'         => $wbm_filter_categories,
                                'wbm_choose_categories'         => $wbm_choose_categories,
                                'wbm_exclude_categories'        => $wbm_exclude_categories,
                                'wbm_total_cat_show'            => $wbm_total_cat_show,
                                'wbm_cat_order_by'              => $wbm_cat_order_by,
                                'wbm_cat_order'                 => $wbm_cat_order,
                                'wbm_cat_no_of_cols'            => $wbm_cat_no_of_cols_arr,
                                'dswbm_child_categories'        => $dswbm_child_categories,
                                'wbm_choose_categories_pnc_cat' => $wbm_choose_categories_pnc_cat,
                                'wbm_parent_child_display_type' => $wbm_parent_child_display_type,
                            );
                            update_post_meta( $method_id, 'wbm_category_slider_general_meta', $wbm_general_settings_arr );
                            $wbm_display_settings = filter_input(
                                INPUT_POST,
                                'wbm_category_slider_display',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $wbm_cat_title = ( isset( $wbm_display_settings['wbm_cat_title'] ) && !empty($wbm_display_settings['wbm_cat_title']) ? $wbm_display_settings['wbm_cat_title'] : '' );
                            $wbm_cat_title_color = ( isset( $wbm_display_settings['wbm_cat_title_color'] ) && !empty($wbm_display_settings['wbm_cat_title_color']) ? $wbm_display_settings['wbm_cat_title_color'] : '' );
                            $sec_title_top_margin = ( isset( $wbm_display_settings['wbm_cat_title_margin']['top'] ) ? $wbm_display_settings['wbm_cat_title_margin']['top'] : '' );
                            $sec_title_right_margin = ( isset( $wbm_display_settings['wbm_cat_title_margin']['right'] ) ? $wbm_display_settings['wbm_cat_title_margin']['right'] : '' );
                            $sec_title_bottom_margin = ( isset( $wbm_display_settings['wbm_cat_title_margin']['bottom'] ) ? $wbm_display_settings['wbm_cat_title_margin']['bottom'] : '' );
                            $sec_title_left_margin = ( isset( $wbm_display_settings['wbm_cat_title_margin']['left'] ) ? $wbm_display_settings['wbm_cat_title_margin']['left'] : '' );
                            $sec_title_margin_unit = ( isset( $wbm_display_settings['wbm_cat_title_margin']['unit'] ) && !empty($wbm_display_settings['wbm_cat_title_margin']['unit']) ? $wbm_display_settings['wbm_cat_title_margin']['unit'] : '' );
                            $wbm_cat_space_between = ( isset( $wbm_display_settings['wbm_cat_space_between'] ) ? $wbm_display_settings['wbm_cat_space_between'] : '' );
                            $wbm_cat_make_card_style = ( isset( $wbm_display_settings['wbm_cat_make_card_style'] ) && !empty($wbm_display_settings['wbm_cat_make_card_style']) ? $wbm_display_settings['wbm_cat_make_card_style'] : '' );
                            $wbm_cat_card_top_border = ( isset( $wbm_display_settings['wbm_cat_card_border']['top'] ) ? $wbm_display_settings['wbm_cat_card_border']['top'] : '' );
                            $wbm_cat_card_right_border = ( isset( $wbm_display_settings['wbm_cat_card_border']['right'] ) ? $wbm_display_settings['wbm_cat_card_border']['right'] : '' );
                            $wbm_cat_card_bottom_border = ( isset( $wbm_display_settings['wbm_cat_card_border']['bottom'] ) ? $wbm_display_settings['wbm_cat_card_border']['bottom'] : '' );
                            $wbm_cat_card_left_border = ( isset( $wbm_display_settings['wbm_cat_card_border']['left'] ) ? $wbm_display_settings['wbm_cat_card_border']['left'] : '' );
                            $wbm_cat_card_border_style = ( isset( $wbm_display_settings['wbm_cat_card_border']['style'] ) && !empty($wbm_display_settings['wbm_cat_card_border']['style']) ? $wbm_display_settings['wbm_cat_card_border']['style'] : '' );
                            $wbm_cat_card_border_color = ( isset( $wbm_display_settings['wbm_cat_card_border']['color'] ) && !empty($wbm_display_settings['wbm_cat_card_border']['color']) ? $wbm_display_settings['wbm_cat_card_border']['color'] : '' );
                            $wbm_cat_card_border_hov_color = ( isset( $wbm_display_settings['wbm_cat_card_border']['hover_color'] ) && !empty($wbm_display_settings['wbm_cat_card_border']['hover_color']) ? $wbm_display_settings['wbm_cat_card_border']['hover_color'] : '' );
                            $wbm_cat_card_bg_color = ( isset( $wbm_display_settings['wbm_cat_card_background']['bg_color'] ) && !empty($wbm_display_settings['wbm_cat_card_background']['bg_color']) ? $wbm_display_settings['wbm_cat_card_background']['bg_color'] : '' );
                            $wbm_cat_card_bg_hov_color = ( isset( $wbm_display_settings['wbm_cat_card_background']['bg_hover_color'] ) && !empty($wbm_display_settings['wbm_cat_card_background']['bg_hover_color']) ? $wbm_display_settings['wbm_cat_card_background']['bg_hover_color'] : '' );
                            $wbm_cat_inner_top_pedding = ( isset( $wbm_display_settings['wbm_cat_inner_padding']['top'] ) ? $wbm_display_settings['wbm_cat_inner_padding']['top'] : '' );
                            $wbm_cat_inner_right_pedding = ( isset( $wbm_display_settings['wbm_cat_inner_padding']['right'] ) ? $wbm_display_settings['wbm_cat_inner_padding']['right'] : '' );
                            $wbm_cat_inner_bottom_pedding = ( isset( $wbm_display_settings['wbm_cat_inner_padding']['bottom'] ) ? $wbm_display_settings['wbm_cat_inner_padding']['bottom'] : '' );
                            $wbm_cat_inner_left_pedding = ( isset( $wbm_display_settings['wbm_cat_inner_padding']['left'] ) ? $wbm_display_settings['wbm_cat_inner_padding']['left'] : '' );
                            $wbm_cat_inner_padding_unit = ( isset( $wbm_display_settings['wbm_cat_inner_padding']['unit'] ) && !empty($wbm_display_settings['wbm_cat_inner_padding']['unit']) ? $wbm_display_settings['wbm_cat_inner_padding']['unit'] : '' );
                            $wbm_cat_name_status = ( isset( $wbm_display_settings['wbm_cat_name_status'] ) && !empty($wbm_display_settings['wbm_cat_name_status']) ? $wbm_display_settings['wbm_cat_name_status'] : '' );
                            $wbm_cat_name_color = ( isset( $wbm_display_settings['wbm_cat_name_color'] ) && !empty($wbm_display_settings['wbm_cat_name_color']) ? $wbm_display_settings['wbm_cat_name_color'] : '' );
                            $wbm_cat_name_top_margin = ( isset( $wbm_display_settings['wbm_cat_name_margin']['top'] ) ? $wbm_display_settings['wbm_cat_name_margin']['top'] : '' );
                            $wbm_cat_name_right_margin = ( isset( $wbm_display_settings['wbm_cat_name_margin']['right'] ) ? $wbm_display_settings['wbm_cat_name_margin']['right'] : '' );
                            $wbm_cat_name_bottom_margin = ( isset( $wbm_display_settings['wbm_cat_name_margin']['bottom'] ) ? $wbm_display_settings['wbm_cat_name_margin']['bottom'] : '' );
                            $wbm_cat_name_left_margin = ( isset( $wbm_display_settings['wbm_cat_name_margin']['left'] ) ? $wbm_display_settings['wbm_cat_name_margin']['left'] : '' );
                            $wbm_cat_name_margin_unit = ( isset( $wbm_display_settings['wbm_cat_name_margin']['unit'] ) && !empty($wbm_display_settings['wbm_cat_name_margin']['unit']) ? $wbm_display_settings['wbm_cat_name_margin']['unit'] : '' );
                            $wbm_cat_prod_count = ( isset( $wbm_display_settings['wbm_cat_prod_count'] ) && !empty($wbm_display_settings['wbm_cat_prod_count']) ? $wbm_display_settings['wbm_cat_prod_count'] : '' );
                            $wbm_cat_count_position = ( isset( $wbm_display_settings['wbm_cat_count_position'] ) && !empty($wbm_display_settings['wbm_cat_count_position']) ? $wbm_display_settings['wbm_cat_count_position'] : '' );
                            $wbm_prod_count_before = ( isset( $wbm_display_settings['wbm_prod_count_before'] ) && !empty($wbm_display_settings['wbm_prod_count_before']) ? $wbm_display_settings['wbm_prod_count_before'] : '' );
                            $wbm_prod_count_after = ( isset( $wbm_display_settings['wbm_prod_count_after'] ) && !empty($wbm_display_settings['wbm_prod_count_after']) ? $wbm_display_settings['wbm_prod_count_after'] : '' );
                            $wbm_prod_count_color = ( isset( $wbm_display_settings['wbm_prod_count_color'] ) && !empty($wbm_display_settings['wbm_prod_count_color']) ? $wbm_display_settings['wbm_prod_count_color'] : '' );
                            $wbm_cat_desc_status = ( isset( $wbm_display_settings['wbm_cat_desc_status'] ) && !empty($wbm_display_settings['wbm_cat_desc_status']) ? $wbm_display_settings['wbm_cat_desc_status'] : '' );
                            $wbm_cat_desc_color = ( isset( $wbm_display_settings['wbm_cat_desc_color'] ) && !empty($wbm_display_settings['wbm_cat_desc_color']) ? $wbm_display_settings['wbm_cat_desc_color'] : '' );
                            $wbm_cat_desc_top_margin = ( isset( $wbm_display_settings['wbm_cat_desc_margin']['top'] ) ? $wbm_display_settings['wbm_cat_desc_margin']['top'] : '' );
                            $wbm_cat_desc_right_margin = ( isset( $wbm_display_settings['wbm_cat_desc_margin']['right'] ) ? $wbm_display_settings['wbm_cat_desc_margin']['right'] : '' );
                            $wbm_cat_desc_bottom_margin = ( isset( $wbm_display_settings['wbm_cat_desc_margin']['bottom'] ) ? $wbm_display_settings['wbm_cat_desc_margin']['bottom'] : '' );
                            $wbm_cat_desc_left_margin = ( isset( $wbm_display_settings['wbm_cat_desc_margin']['left'] ) ? $wbm_display_settings['wbm_cat_desc_margin']['left'] : '' );
                            $wbm_cat_desc_margin_unit = ( isset( $wbm_display_settings['wbm_cat_desc_margin']['unit'] ) && !empty($wbm_display_settings['wbm_cat_desc_margin']['unit']) ? $wbm_display_settings['wbm_cat_desc_margin']['unit'] : '' );
                            $wbm_shop_now_button = ( isset( $wbm_display_settings['wbm_shop_now_button'] ) && !empty($wbm_display_settings['wbm_shop_now_button']) ? $wbm_display_settings['wbm_shop_now_button'] : '' );
                            $wbm_shop_now_label = ( isset( $wbm_display_settings['wbm_shop_now_label'] ) && !empty($wbm_display_settings['wbm_shop_now_label']) ? $wbm_display_settings['wbm_shop_now_label'] : '' );
                            $wbm_shop_now_color = ( isset( $wbm_display_settings['wbm_shop_now_color']['color'] ) && !empty($wbm_display_settings['wbm_shop_now_color']['color']) ? $wbm_display_settings['wbm_shop_now_color']['color'] : '' );
                            $wbm_shop_now_hov_color = ( isset( $wbm_display_settings['wbm_shop_now_color']['hover_color'] ) && !empty($wbm_display_settings['wbm_shop_now_color']['hover_color']) ? $wbm_display_settings['wbm_shop_now_color']['hover_color'] : '' );
                            $wbm_shop_now_bg_color = ( isset( $wbm_display_settings['wbm_shop_now_color']['bg_color'] ) && !empty($wbm_display_settings['wbm_shop_now_color']['bg_color']) ? $wbm_display_settings['wbm_shop_now_color']['bg_color'] : '' );
                            $wbm_shop_now_hov_bg_color = ( isset( $wbm_display_settings['wbm_shop_now_color']['bg_hover_color'] ) && !empty($wbm_display_settings['wbm_shop_now_color']['bg_hover_color']) ? $wbm_display_settings['wbm_shop_now_color']['bg_hover_color'] : '' );
                            $wbm_shop_now_all_border = ( isset( $wbm_display_settings['wbm_shop_now_border']['all'] ) && !empty($wbm_display_settings['wbm_shop_now_border']['all']) ? $wbm_display_settings['wbm_shop_now_border']['all'] : '' );
                            $wbm_shop_now_border_style = ( isset( $wbm_display_settings['wbm_shop_now_border']['style'] ) && !empty($wbm_display_settings['wbm_shop_now_border']['style']) ? $wbm_display_settings['wbm_shop_now_border']['style'] : '' );
                            $wbm_shop_now_border_color = ( isset( $wbm_display_settings['wbm_shop_now_border']['color'] ) && !empty($wbm_display_settings['wbm_shop_now_border']['color']) ? $wbm_display_settings['wbm_shop_now_border']['color'] : '' );
                            $wbm_shop_now_border_hov_color = ( isset( $wbm_display_settings['wbm_shop_now_border']['hover_color'] ) && !empty($wbm_display_settings['wbm_shop_now_border']['hover_color']) ? $wbm_display_settings['wbm_shop_now_border']['hover_color'] : '' );
                            $wbm_shop_now_top_margin = ( isset( $wbm_display_settings['wbm_shop_now_margin']['top'] ) ? $wbm_display_settings['wbm_shop_now_margin']['top'] : '' );
                            $wbm_shop_now_right_margin = ( isset( $wbm_display_settings['wbm_shop_now_margin']['right'] ) ? $wbm_display_settings['wbm_shop_now_margin']['right'] : '' );
                            $wbm_shop_now_bottom_margin = ( isset( $wbm_display_settings['wbm_shop_now_margin']['bottom'] ) ? $wbm_display_settings['wbm_shop_now_margin']['bottom'] : '' );
                            $wbm_shop_now_left_margin = ( isset( $wbm_display_settings['wbm_shop_now_margin']['left'] ) ? $wbm_display_settings['wbm_shop_now_margin']['left'] : '' );
                            $wbm_shop_now_margin_unit = ( isset( $wbm_display_settings['wbm_shop_now_margin']['unit'] ) && !empty($wbm_display_settings['wbm_shop_now_margin']['unit']) ? $wbm_display_settings['wbm_shop_now_margin']['unit'] : '' );
                            $wbm_shop_now_link_target = ( isset( $wbm_display_settings['wbm_shop_now_link_target'] ) && !empty($wbm_display_settings['wbm_shop_now_link_target']) ? $wbm_display_settings['wbm_shop_now_link_target'] : '' );
                            $wbm_cat_content_position = '';
                            $wbm_cat_title_margin_arr = array(
                                'top'    => $sec_title_top_margin,
                                'right'  => $sec_title_right_margin,
                                'bottom' => $sec_title_bottom_margin,
                                'left'   => $sec_title_left_margin,
                                'unit'   => $sec_title_margin_unit,
                            );
                            $wbm_cat_card_border_arr = array(
                                'top'         => $wbm_cat_card_top_border,
                                'right'       => $wbm_cat_card_right_border,
                                'bottom'      => $wbm_cat_card_bottom_border,
                                'left'        => $wbm_cat_card_left_border,
                                'style'       => $wbm_cat_card_border_style,
                                'color'       => $wbm_cat_card_border_color,
                                'hover_color' => $wbm_cat_card_border_hov_color,
                            );
                            $wbm_cat_card_background_arr = array(
                                'bg_color'       => $wbm_cat_card_bg_color,
                                'bg_hover_color' => $wbm_cat_card_bg_hov_color,
                            );
                            $wbm_cat_inner_padding_arr = array(
                                'top'    => $wbm_cat_inner_top_pedding,
                                'right'  => $wbm_cat_inner_right_pedding,
                                'bottom' => $wbm_cat_inner_bottom_pedding,
                                'left'   => $wbm_cat_inner_left_pedding,
                                'unit'   => $wbm_cat_inner_padding_unit,
                            );
                            $wbm_cat_name_margin_arr = array(
                                'top'    => $wbm_cat_name_top_margin,
                                'right'  => $wbm_cat_name_right_margin,
                                'bottom' => $wbm_cat_name_bottom_margin,
                                'left'   => $wbm_cat_name_left_margin,
                                'unit'   => $wbm_cat_name_margin_unit,
                            );
                            $wbm_cat_desc_margin_arr = array(
                                'top'    => $wbm_cat_desc_top_margin,
                                'right'  => $wbm_cat_desc_right_margin,
                                'bottom' => $wbm_cat_desc_bottom_margin,
                                'left'   => $wbm_cat_desc_left_margin,
                                'unit'   => $wbm_cat_desc_margin_unit,
                            );
                            $wbm_shop_now_color_arr = array(
                                'color'          => $wbm_shop_now_color,
                                'hover_color'    => $wbm_shop_now_hov_color,
                                'bg_color'       => $wbm_shop_now_bg_color,
                                'bg_hover_color' => $wbm_shop_now_hov_bg_color,
                            );
                            $wbm_shop_now_border_arr = array(
                                'all'         => $wbm_shop_now_all_border,
                                'style'       => $wbm_shop_now_border_style,
                                'color'       => $wbm_shop_now_border_color,
                                'hover_color' => $wbm_shop_now_border_hov_color,
                            );
                            $wbm_shop_now_margin_arr = array(
                                'top'    => $wbm_shop_now_top_margin,
                                'right'  => $wbm_shop_now_right_margin,
                                'bottom' => $wbm_shop_now_bottom_margin,
                                'left'   => $wbm_shop_now_left_margin,
                                'unit'   => $wbm_shop_now_margin_unit,
                            );
                            $wbm_display_settings_arr[] = array(
                                'wbm_cat_title'            => $wbm_cat_title,
                                'wbm_cat_title_color'      => $wbm_cat_title_color,
                                'wbm_cat_title_margin'     => $wbm_cat_title_margin_arr,
                                'wbm_cat_space_between'    => $wbm_cat_space_between,
                                'wbm_cat_make_card_style'  => $wbm_cat_make_card_style,
                                'wbm_cat_card_border'      => $wbm_cat_card_border_arr,
                                'wbm_cat_card_background'  => $wbm_cat_card_background_arr,
                                'wbm_cat_inner_padding'    => $wbm_cat_inner_padding_arr,
                                'wbm_cat_name_status'      => $wbm_cat_name_status,
                                'wbm_cat_name_color'       => $wbm_cat_name_color,
                                'wbm_cat_name_margin'      => $wbm_cat_name_margin_arr,
                                'wbm_cat_prod_count'       => $wbm_cat_prod_count,
                                'wbm_cat_count_position'   => $wbm_cat_count_position,
                                'wbm_prod_count_before'    => $wbm_prod_count_before,
                                'wbm_prod_count_after'     => $wbm_prod_count_after,
                                'wbm_prod_count_color'     => $wbm_prod_count_color,
                                'wbm_cat_desc_status'      => $wbm_cat_desc_status,
                                'wbm_cat_desc_color'       => $wbm_cat_desc_color,
                                'wbm_cat_desc_margin'      => $wbm_cat_desc_margin_arr,
                                'wbm_shop_now_button'      => $wbm_shop_now_button,
                                'wbm_shop_now_label'       => $wbm_shop_now_label,
                                'wbm_shop_now_color'       => $wbm_shop_now_color_arr,
                                'wbm_shop_now_border'      => $wbm_shop_now_border_arr,
                                'wbm_shop_now_margin'      => $wbm_shop_now_margin_arr,
                                'wbm_shop_now_link_target' => $wbm_shop_now_link_target,
                                'wbm_cat_content_position' => $wbm_cat_content_position,
                            );
                            update_post_meta( $method_id, 'wbm_category_slider_display_meta', $wbm_display_settings_arr );
                            $wbm_thumbnail_settings = filter_input(
                                INPUT_POST,
                                'wbm_category_slider_thumbnail',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $wbm_cat_thumbnail = ( isset( $wbm_thumbnail_settings['wbm_cat_thumbnail'] ) && !empty($wbm_thumbnail_settings['wbm_cat_thumbnail']) ? $wbm_thumbnail_settings['wbm_cat_thumbnail'] : '' );
                            $wbm_cat_thumbnail_size = ( isset( $wbm_thumbnail_settings['wbm_cat_thumbnail_size'] ) && !empty($wbm_thumbnail_settings['wbm_cat_thumbnail_size']) ? $wbm_thumbnail_settings['wbm_cat_thumbnail_size'] : '' );
                            $wbm_cat_thumb_style = ( isset( $wbm_thumbnail_settings['wbm_cat_thumb_style'] ) && !empty($wbm_thumbnail_settings['wbm_cat_thumb_style']) ? $wbm_thumbnail_settings['wbm_cat_thumb_style'] : '' );
                            $wbm_thumb_all_border = ( isset( $wbm_thumbnail_settings['wbm_thumb_border']['all'] ) && !empty($wbm_thumbnail_settings['wbm_thumb_border']['all']) ? $wbm_thumbnail_settings['wbm_thumb_border']['all'] : '' );
                            $wbm_thumb_border_style = ( isset( $wbm_thumbnail_settings['wbm_thumb_border']['style'] ) && !empty($wbm_thumbnail_settings['wbm_thumb_border']['style']) ? $wbm_thumbnail_settings['wbm_thumb_border']['style'] : '' );
                            $wbm_thumb_border_color = ( isset( $wbm_thumbnail_settings['wbm_thumb_border']['color'] ) && !empty($wbm_thumbnail_settings['wbm_thumb_border']['color']) ? $wbm_thumbnail_settings['wbm_thumb_border']['color'] : '' );
                            $wbm_thumb_border_hov_color = ( isset( $wbm_thumbnail_settings['wbm_thumb_border']['hover_color'] ) && !empty($wbm_thumbnail_settings['wbm_thumb_border']['hover_color']) ? $wbm_thumbnail_settings['wbm_thumb_border']['hover_color'] : '' );
                            $wbm_thumb_top_margin = ( isset( $wbm_thumbnail_settings['wbm_thumb_margin']['top'] ) ? $wbm_thumbnail_settings['wbm_thumb_margin']['top'] : '' );
                            $wbm_thumb_right_margin = ( isset( $wbm_thumbnail_settings['wbm_thumb_margin']['right'] ) ? $wbm_thumbnail_settings['wbm_thumb_margin']['right'] : '' );
                            $wbm_thumb_bottom_margin = ( isset( $wbm_thumbnail_settings['wbm_thumb_margin']['bottom'] ) ? $wbm_thumbnail_settings['wbm_thumb_margin']['bottom'] : '' );
                            $wbm_thumb_left_margin = ( isset( $wbm_thumbnail_settings['wbm_thumb_margin']['left'] ) ? $wbm_thumbnail_settings['wbm_thumb_margin']['left'] : '' );
                            $wbm_thumb_margin_unit = ( isset( $wbm_thumbnail_settings['wbm_thumb_margin']['unit'] ) && !empty($wbm_thumbnail_settings['wbm_thumb_margin']['unit']) ? $wbm_thumbnail_settings['wbm_thumb_margin']['unit'] : '' );
                            $wbm_cat_thumb_mode = ( isset( $wbm_thumbnail_settings['wbm_cat_thumb_mode'] ) && !empty($wbm_thumbnail_settings['wbm_cat_thumb_mode']) ? $wbm_thumbnail_settings['wbm_cat_thumb_mode'] : '' );
                            $wbm_cat_thumb_zoom = ( isset( $wbm_thumbnail_settings['wbm_cat_thumb_zoom'] ) && !empty($wbm_thumbnail_settings['wbm_cat_thumb_zoom']) ? $wbm_thumbnail_settings['wbm_cat_thumb_zoom'] : '' );
                            $wbm_cat_thumbnail_shape = ( isset( $wbm_thumbnail_settings['wbm_cat_thumbnail_shape'] ) && !empty($wbm_thumbnail_settings['wbm_cat_thumbnail_shape']) ? $wbm_thumbnail_settings['wbm_cat_thumbnail_shape'] : 'square' );
                            $wbm_cat_thumb_border_radius = ( isset( $wbm_thumbnail_settings['wbm_cat_thumb_border_radius'] ) && !empty($wbm_thumbnail_settings['wbm_cat_thumb_border_radius']) ? $wbm_thumbnail_settings['wbm_cat_thumb_border_radius'] : 0 );
                            $wbm_thumb_shadow_v_offset = ( isset( $wbm_thumbnail_settings['wbm_thumb_shadow']['v-offset'] ) && !empty($wbm_thumbnail_settings['wbm_thumb_shadow']['v-offset']) ? $wbm_thumbnail_settings['wbm_thumb_shadow']['v-offset'] : 0 );
                            $wbm_thumb_shadow_h_offset = ( isset( $wbm_thumbnail_settings['wbm_thumb_shadow']['h-offset'] ) && !empty($wbm_thumbnail_settings['wbm_thumb_shadow']['h-offset']) ? $wbm_thumbnail_settings['wbm_thumb_shadow']['h-offset'] : 0 );
                            $wbm_thumb_shadow_blur = ( isset( $wbm_thumbnail_settings['wbm_thumb_shadow']['blur'] ) && !empty($wbm_thumbnail_settings['wbm_thumb_shadow']['blur']) ? $wbm_thumbnail_settings['wbm_thumb_shadow']['blur'] : 0 );
                            $wbm_thumb_shadow_spread = ( isset( $wbm_thumbnail_settings['wbm_thumb_shadow']['spread'] ) && !empty($wbm_thumbnail_settings['wbm_thumb_shadow']['spread']) ? $wbm_thumbnail_settings['wbm_thumb_shadow']['spread'] : 0 );
                            $wbm_thumb_shadow_style = ( isset( $wbm_thumbnail_settings['wbm_thumb_shadow']['shadow'] ) && !empty($wbm_thumbnail_settings['wbm_thumb_shadow']['shadow']) ? $wbm_thumbnail_settings['wbm_thumb_shadow']['shadow'] : 'inset' );
                            $wbm_thumb_shadow_color = ( isset( $wbm_thumbnail_settings['wbm_thumb_shadow']['color'] ) && !empty($wbm_thumbnail_settings['wbm_thumb_shadow']['color']) ? $wbm_thumbnail_settings['wbm_thumb_shadow']['color'] : '' );
                            $wbm_thumb_shadow_hov_color = ( isset( $wbm_thumbnail_settings['wbm_thumb_shadow']['hover-color'] ) && !empty($wbm_thumbnail_settings['wbm_thumb_shadow']['hover-color']) ? $wbm_thumbnail_settings['wbm_thumb_shadow']['hover-color'] : '' );
                            $wbm_thumb_border_arr = array(
                                'all'         => $wbm_thumb_all_border,
                                'style'       => $wbm_thumb_border_style,
                                'color'       => $wbm_thumb_border_color,
                                'hover_color' => $wbm_thumb_border_hov_color,
                            );
                            $wbm_thumb_margin_arr = array(
                                'top'    => $wbm_thumb_top_margin,
                                'right'  => $wbm_thumb_right_margin,
                                'bottom' => $wbm_thumb_bottom_margin,
                                'left'   => $wbm_thumb_left_margin,
                                'unit'   => $wbm_thumb_margin_unit,
                            );
                            $wbm_thumb_shadow = array(
                                'v-offset'    => $wbm_thumb_shadow_v_offset,
                                'h-offset'    => $wbm_thumb_shadow_h_offset,
                                'blur'        => $wbm_thumb_shadow_blur,
                                'spread'      => $wbm_thumb_shadow_spread,
                                'shadow'      => $wbm_thumb_shadow_style,
                                'color'       => $wbm_thumb_shadow_color,
                                'hover-color' => $wbm_thumb_shadow_hov_color,
                            );
                            $wbm_thumbnail_settings_arr[] = array(
                                'wbm_cat_thumbnail'           => $wbm_cat_thumbnail,
                                'wbm_cat_thumbnail_size'      => $wbm_cat_thumbnail_size,
                                'wbm_cat_thumb_style'         => $wbm_cat_thumb_style,
                                'wbm_thumb_border'            => $wbm_thumb_border_arr,
                                'wbm_thumb_margin'            => $wbm_thumb_margin_arr,
                                'wbm_cat_thumb_mode'          => $wbm_cat_thumb_mode,
                                'wbm_cat_thumb_zoom'          => $wbm_cat_thumb_zoom,
                                'wbm_cat_thumbnail_shape'     => $wbm_cat_thumbnail_shape,
                                'wbm_cat_thumb_border_radius' => $wbm_cat_thumb_border_radius,
                                'wbm_thumb_shadow'            => $wbm_thumb_shadow,
                            );
                            update_post_meta( $method_id, 'wbm_category_slider_thumbnail_meta', $wbm_thumbnail_settings_arr );
                            $wbm_sliders_settings = filter_input(
                                INPUT_POST,
                                'wbm_category_slider_sliders',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $wbm_cat_autoplay = ( isset( $wbm_sliders_settings['wbm_cat_autoplay'] ) && !empty($wbm_sliders_settings['wbm_cat_autoplay']) ? $wbm_sliders_settings['wbm_cat_autoplay'] : '' );
                            $wbm_cat_autoplay_speed = ( isset( $wbm_sliders_settings['wbm_cat_autoplay_speed'] ) && !empty($wbm_sliders_settings['wbm_cat_autoplay_speed']) ? $wbm_sliders_settings['wbm_cat_autoplay_speed'] : '' );
                            $wbm_cat_scroll_speed = ( isset( $wbm_sliders_settings['wbm_cat_scroll_speed'] ) && !empty($wbm_sliders_settings['wbm_cat_scroll_speed']) ? $wbm_sliders_settings['wbm_cat_scroll_speed'] : '' );
                            $wbm_cat_pause_on_hov = ( isset( $wbm_sliders_settings['wbm_cat_pause_on_hov'] ) && !empty($wbm_sliders_settings['wbm_cat_pause_on_hov']) ? $wbm_sliders_settings['wbm_cat_pause_on_hov'] : '' );
                            $wbm_cat_infinite_loop = ( isset( $wbm_sliders_settings['wbm_cat_infinite_loop'] ) && !empty($wbm_sliders_settings['wbm_cat_infinite_loop']) ? $wbm_sliders_settings['wbm_cat_infinite_loop'] : '' );
                            $wbm_cat_auto_height = ( isset( $wbm_sliders_settings['wbm_cat_auto_height'] ) && !empty($wbm_sliders_settings['wbm_cat_auto_height']) ? $wbm_sliders_settings['wbm_cat_auto_height'] : '' );
                            $wbm_cat_nav_status = ( isset( $wbm_sliders_settings['wbm_cat_nav_status'] ) && !empty($wbm_sliders_settings['wbm_cat_nav_status']) ? $wbm_sliders_settings['wbm_cat_nav_status'] : '' );
                            $wbm_cat_nav_color = ( isset( $wbm_sliders_settings['wbm_cat_nav_color']['color'] ) && !empty($wbm_sliders_settings['wbm_cat_nav_color']['color']) ? $wbm_sliders_settings['wbm_cat_nav_color']['color'] : '' );
                            $wbm_cat_nav_hov_color = ( isset( $wbm_sliders_settings['wbm_cat_nav_color']['hover_color'] ) && !empty($wbm_sliders_settings['wbm_cat_nav_color']['hover_color']) ? $wbm_sliders_settings['wbm_cat_nav_color']['hover_color'] : '' );
                            $wbm_cat_nav_bg_color = ( isset( $wbm_sliders_settings['wbm_cat_nav_color']['bg_color'] ) && !empty($wbm_sliders_settings['wbm_cat_nav_color']['bg_color']) ? $wbm_sliders_settings['wbm_cat_nav_color']['bg_color'] : '' );
                            $wbm_cat_nav_hov_bg_color = ( isset( $wbm_sliders_settings['wbm_cat_nav_color']['bg_hover_color'] ) && !empty($wbm_sliders_settings['wbm_cat_nav_color']['bg_hover_color']) ? $wbm_sliders_settings['wbm_cat_nav_color']['bg_hover_color'] : '' );
                            $wbm_cat_nav_all_border = ( isset( $wbm_sliders_settings['wbm_cat_nav_border']['all'] ) && !empty($wbm_sliders_settings['wbm_cat_nav_border']['all']) ? $wbm_sliders_settings['wbm_cat_nav_border']['all'] : '' );
                            $wbm_cat_nav_border_style = ( isset( $wbm_sliders_settings['wbm_cat_nav_border']['style'] ) && !empty($wbm_sliders_settings['wbm_cat_nav_border']['style']) ? $wbm_sliders_settings['wbm_cat_nav_border']['style'] : '' );
                            $wbm_cat_nav_border_color = ( isset( $wbm_sliders_settings['wbm_cat_nav_border']['color'] ) && !empty($wbm_sliders_settings['wbm_cat_nav_border']['color']) ? $wbm_sliders_settings['wbm_cat_nav_border']['color'] : '' );
                            $wbm_cat_nav_border_hov_color = ( isset( $wbm_sliders_settings['wbm_cat_nav_border']['hover_color'] ) && !empty($wbm_sliders_settings['wbm_cat_nav_border']['hover_color']) ? $wbm_sliders_settings['wbm_cat_nav_border']['hover_color'] : '' );
                            $wbm_cat_pager_status = ( isset( $wbm_sliders_settings['wbm_cat_pager_status'] ) && !empty($wbm_sliders_settings['wbm_cat_pager_status']) ? $wbm_sliders_settings['wbm_cat_pager_status'] : '' );
                            $wbm_cat_pager_color = ( isset( $wbm_sliders_settings['wbm_cat_pager_color']['color'] ) && !empty($wbm_sliders_settings['wbm_cat_pager_color']['color']) ? $wbm_sliders_settings['wbm_cat_pager_color']['color'] : '' );
                            $wbm_cat_pager_active_color = ( isset( $wbm_sliders_settings['wbm_cat_pager_color']['active_color'] ) && !empty($wbm_sliders_settings['wbm_cat_pager_color']['active_color']) ? $wbm_sliders_settings['wbm_cat_pager_color']['active_color'] : '' );
                            $wbm_cat_pager_hov_color = ( isset( $wbm_sliders_settings['wbm_cat_pager_color']['hover_color'] ) && !empty($wbm_sliders_settings['wbm_cat_pager_color']['hover_color']) ? $wbm_sliders_settings['wbm_cat_pager_color']['hover_color'] : '' );
                            $wbm_cat_slide_to_scroll_large_desktop = ( isset( $wbm_sliders_settings['wbm_cat_slide_to_scroll']['large_desktop'] ) && !empty($wbm_sliders_settings['wbm_cat_slide_to_scroll']['large_desktop']) ? $wbm_sliders_settings['wbm_cat_slide_to_scroll']['large_desktop'] : '' );
                            $wbm_cat_slide_to_scroll_desktop = ( isset( $wbm_sliders_settings['wbm_cat_slide_to_scroll']['desktop'] ) && !empty($wbm_sliders_settings['wbm_cat_slide_to_scroll']['desktop']) ? $wbm_sliders_settings['wbm_cat_slide_to_scroll']['desktop'] : '' );
                            $wbm_cat_slide_to_scroll_laptop = ( isset( $wbm_sliders_settings['wbm_cat_slide_to_scroll']['laptop'] ) && !empty($wbm_sliders_settings['wbm_cat_slide_to_scroll']['laptop']) ? $wbm_sliders_settings['wbm_cat_slide_to_scroll']['laptop'] : '' );
                            $wbm_cat_slide_to_scroll_tablet = ( isset( $wbm_sliders_settings['wbm_cat_slide_to_scroll']['tablet'] ) && !empty($wbm_sliders_settings['wbm_cat_slide_to_scroll']['tablet']) ? $wbm_sliders_settings['wbm_cat_slide_to_scroll']['tablet'] : '' );
                            $wbm_cat_slide_to_scroll_mobile = ( isset( $wbm_sliders_settings['wbm_cat_slide_to_scroll']['mobile'] ) && !empty($wbm_sliders_settings['wbm_cat_slide_to_scroll']['mobile']) ? $wbm_sliders_settings['wbm_cat_slide_to_scroll']['mobile'] : '' );
                            $wbm_cat_touch_swipe = ( isset( $wbm_sliders_settings['wbm_cat_touch_swipe'] ) && !empty($wbm_sliders_settings['wbm_cat_touch_swipe']) ? $wbm_sliders_settings['wbm_cat_touch_swipe'] : 'false' );
                            $wbm_cat_mousewheel = ( isset( $wbm_sliders_settings['wbm_cat_mousewheel'] ) && !empty($wbm_sliders_settings['wbm_cat_mousewheel']) ? $wbm_sliders_settings['wbm_cat_mousewheel'] : 'false' );
                            $wbm_cat_mouse_draggable = ( isset( $wbm_sliders_settings['wbm_cat_mouse_draggable'] ) && !empty($wbm_sliders_settings['wbm_cat_mouse_draggable']) ? $wbm_sliders_settings['wbm_cat_mouse_draggable'] : 'false' );
                            $wbm_cat_slide_to_scroll = array(
                                'large_desktop' => $wbm_cat_slide_to_scroll_large_desktop,
                                'desktop'       => $wbm_cat_slide_to_scroll_desktop,
                                'laptop'        => $wbm_cat_slide_to_scroll_laptop,
                                'tablet'        => $wbm_cat_slide_to_scroll_tablet,
                                'mobile'        => $wbm_cat_slide_to_scroll_mobile,
                            );
                            $wbm_cat_nav_color_arr = array(
                                'color'          => $wbm_cat_nav_color,
                                'hover_color'    => $wbm_cat_nav_hov_color,
                                'bg_color'       => $wbm_cat_nav_bg_color,
                                'bg_hover_color' => $wbm_cat_nav_hov_bg_color,
                            );
                            $wbm_cat_nav_border_arr = array(
                                'all'         => $wbm_cat_nav_all_border,
                                'style'       => $wbm_cat_nav_border_style,
                                'color'       => $wbm_cat_nav_border_color,
                                'hover_color' => $wbm_cat_nav_border_hov_color,
                            );
                            $wbm_cat_pager_color_arr = array(
                                'color'        => $wbm_cat_pager_color,
                                'active_color' => $wbm_cat_pager_active_color,
                                'hover_color'  => $wbm_cat_pager_hov_color,
                            );
                            $wbm_sliders_settings_arr[] = array(
                                'wbm_cat_autoplay'        => $wbm_cat_autoplay,
                                'wbm_cat_autoplay_speed'  => $wbm_cat_autoplay_speed,
                                'wbm_cat_scroll_speed'    => $wbm_cat_scroll_speed,
                                'wbm_cat_pause_on_hov'    => $wbm_cat_pause_on_hov,
                                'wbm_cat_infinite_loop'   => $wbm_cat_infinite_loop,
                                'wbm_cat_auto_height'     => $wbm_cat_auto_height,
                                'wbm_cat_nav_status'      => $wbm_cat_nav_status,
                                'wbm_cat_nav_color'       => $wbm_cat_nav_color_arr,
                                'wbm_cat_nav_border'      => $wbm_cat_nav_border_arr,
                                'wbm_cat_pager_status'    => $wbm_cat_pager_status,
                                'wbm_cat_pager_color'     => $wbm_cat_pager_color_arr,
                                'wbm_cat_slide_to_scroll' => $wbm_cat_slide_to_scroll,
                                'wbm_cat_touch_swipe'     => $wbm_cat_touch_swipe,
                                'wbm_cat_mousewheel'      => $wbm_cat_mousewheel,
                                'wbm_cat_mouse_draggable' => $wbm_cat_mouse_draggable,
                            );
                            update_post_meta( $method_id, 'wbm_category_slider_sliders_meta', $wbm_sliders_settings_arr );
                            $wbm_category_slider_typo = filter_input(
                                INPUT_POST,
                                'wbm_category_slider_typo',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $wbm_cat_typo_on_off = ( isset( $wbm_category_slider_typo['wbm_cat_typo_on_off'] ) && !empty($wbm_category_slider_typo['wbm_cat_typo_on_off']) ? $wbm_category_slider_typo['wbm_cat_typo_on_off'] : 'off' );
                            $wbm_cat_title_typo_font_family = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_font_family'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_font_family']) ? $wbm_category_slider_typo['wbm_cat_title_typo_font_family'] : 'none' );
                            $wbm_cat_title_typo_font_style = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_font_style'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_font_style']) ? $wbm_category_slider_typo['wbm_cat_title_typo_font_style'] : '' );
                            $wbm_cat_title_typo_text_align = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_text_align'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_text_align']) ? $wbm_category_slider_typo['wbm_cat_title_typo_text_align'] : '' );
                            $wbm_cat_title_typo_text_transform = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_text_transform'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_text_transform']) ? $wbm_category_slider_typo['wbm_cat_title_typo_text_transform'] : '' );
                            $wbm_cat_title_typo_text_font_size = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_text_font_size'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_text_font_size']) ? $wbm_category_slider_typo['wbm_cat_title_typo_text_font_size'] : 0 );
                            $wbm_cat_title_typo_text_line_height = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_text_line_height'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_text_line_height']) ? $wbm_category_slider_typo['wbm_cat_title_typo_text_line_height'] : 0 );
                            $wbm_cat_title_typo_text_letter_spacing = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_text_letter_spacing'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_text_letter_spacing']) ? $wbm_category_slider_typo['wbm_cat_title_typo_text_letter_spacing'] : 0 );
                            $wbm_cat_typo_shopbtn_on_off = ( isset( $wbm_category_slider_typo['wbm_cat_typo_shopbtn_on_off'] ) && !empty($wbm_category_slider_typo['wbm_cat_typo_shopbtn_on_off']) ? $wbm_category_slider_typo['wbm_cat_typo_shopbtn_on_off'] : 'off' );
                            $wbm_cat_title_typo_shopbtn_font_family = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_font_family'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_font_family']) ? $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_font_family'] : 'none' );
                            $wbm_cat_title_typo_shopbtn_font_style = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_font_style'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_font_style']) ? $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_font_style'] : '' );
                            $wbm_cat_title_typo_shopbtn_text_align = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_align'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_align']) ? $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_align'] : '' );
                            $wbm_cat_title_typo_shopbtn_text_transform = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_transform'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_transform']) ? $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_transform'] : '' );
                            $wbm_cat_title_typo_shopbtn_text_font_size = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_font_size'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_font_size']) ? $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_font_size'] : 0 );
                            $wbm_cat_title_typo_shopbtn_text_line_height = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_line_height'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_line_height']) ? $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_line_height'] : 0 );
                            $wbm_cat_title_typo_shopbtn_text_letter_spacing = ( isset( $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_letter_spacing'] ) && !empty($wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_letter_spacing']) ? $wbm_category_slider_typo['wbm_cat_title_typo_shopbtn_text_letter_spacing'] : 0 );
                            $wbm_category_slider_typo_arr[] = array(
                                'wbm_cat_typo_on_off'                            => $wbm_cat_typo_on_off,
                                'wbm_cat_title_typo_font_family'                 => $wbm_cat_title_typo_font_family,
                                'wbm_cat_title_typo_font_style'                  => $wbm_cat_title_typo_font_style,
                                'wbm_cat_title_typo_text_align'                  => $wbm_cat_title_typo_text_align,
                                'wbm_cat_title_typo_text_transform'              => $wbm_cat_title_typo_text_transform,
                                'wbm_cat_title_typo_text_font_size'              => $wbm_cat_title_typo_text_font_size,
                                'wbm_cat_title_typo_text_line_height'            => $wbm_cat_title_typo_text_line_height,
                                'wbm_cat_title_typo_text_letter_spacing'         => $wbm_cat_title_typo_text_letter_spacing,
                                'wbm_cat_typo_shopbtn_on_off'                    => $wbm_cat_typo_shopbtn_on_off,
                                'wbm_cat_title_typo_shopbtn_font_family'         => $wbm_cat_title_typo_shopbtn_font_family,
                                'wbm_cat_title_typo_shopbtn_font_style'          => $wbm_cat_title_typo_shopbtn_font_style,
                                'wbm_cat_title_typo_shopbtn_text_align'          => $wbm_cat_title_typo_shopbtn_text_align,
                                'wbm_cat_title_typo_shopbtn_text_transform'      => $wbm_cat_title_typo_shopbtn_text_transform,
                                'wbm_cat_title_typo_shopbtn_text_font_size'      => $wbm_cat_title_typo_shopbtn_text_font_size,
                                'wbm_cat_title_typo_shopbtn_text_line_height'    => $wbm_cat_title_typo_shopbtn_text_line_height,
                                'wbm_cat_title_typo_shopbtn_text_letter_spacing' => $wbm_cat_title_typo_shopbtn_text_letter_spacing,
                            );
                            update_post_meta( $method_id, 'wbm_category_slider_typo_meta', $wbm_category_slider_typo_arr );
                            if ( !empty($sitepress) ) {
                                do_action(
                                    'wpml_register_single_string',
                                    'banner-management-for-woocommerce',
                                    sanitize_text_field( $dswbm_slider_rule_name ),
                                    sanitize_text_field( $dswbm_slider_rule_name )
                                );
                            }
                        }
                    
                    } else {
                        echo  '<div class="updated error"><p>' . esc_html__( 'Error saving category slider.', 'banner-management-for-woocommerce' ) . '</p></div>' ;
                        return false;
                    }
                    
                    $dswbm_add = wp_create_nonce( 'dswbm_add' );
                    
                    if ( 'add' === $action ) {
                        wp_safe_redirect( add_query_arg( array(
                            'page'     => 'wcbm-sliders-settings',
                            'tab'      => 'wcbm-category-sliders',
                            'action'   => 'edit',
                            'post'     => $method_id,
                            '_wpnonce' => esc_attr( $dswbm_add ),
                            'message'  => 'created',
                        ), admin_url( 'admin.php' ) ) );
                        exit;
                    }
                    
                    
                    if ( 'edit' === $action ) {
                        wp_safe_redirect( add_query_arg( array(
                            'page'     => 'wcbm-sliders-settings',
                            'tab'      => 'wcbm-category-sliders',
                            'action'   => 'edit',
                            'post'     => $method_id,
                            '_wpnonce' => esc_attr( $dswbm_add ),
                            'message'  => 'saved',
                        ), admin_url( 'admin.php' ) ) );
                        exit;
                    }
                
                }
            
            }
        }
        
        /**
         * Edit category slider method screen
         *
         * @param string $id
         *
         * @uses wcbm_save_category_slider_settings()
         * @uses wcbm_category_slider_settings()
         *
         * @since    1.0.0
         *
         */
        public static function wcbm_category_slider_edit_screen( $id )
        {
            self::wcbm_save_category_slider_settings( $id );
            self::wcbm_category_slider_settings();
        }
        
        /**
         * Edit category slider method
         *
         * @since    1.0.0
         */
        private static function wcbm_category_slider_settings()
        {
            include plugin_dir_path( __FILE__ ) . 'wcbm-category-sliders-form.php';
        }
        
        /**
         * List conditional payment methods function.
         *
         * @since    1.0.0
         *
         * @uses wcbm_banner_management_category_slider_Table class
         * @uses wcbm_banner_management_category_slider_Table::process_bulk_action()
         * @uses wcbm_banner_management_category_slider_Table::prepare_items()
         * @uses wcbm_banner_management_category_slider_Table::search_box()
         * @uses wcbm_banner_management_category_slider_Table::display()
         *
         * @access public
         *
         */
        public static function wcbm_category_sliders_list_screen()
        {
            if ( !class_exists( 'wcbm_banner_management_category_slider_Table' ) ) {
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'list-tables/class-wbm-category-slider-table.php';
            }
            $link = add_query_arg( array(
                'page'   => 'wcbm-sliders-settings',
                'tab'    => 'wcbm-category-sliders',
                'action' => 'add',
            ), admin_url( 'admin.php' ) );
            ?>
            <div class="wcbm-slider-section">
                <h1 class="wp-heading-inline">
                    <?php 
            echo  esc_html( __( 'All Category Sliders', 'banner-management-for-woocommerce' ) ) ;
            ?>
                </h1>
                <a href="<?php 
            echo  esc_url( $link ) ;
            ?>"
                   class="page-title-action dots-btn-with-brand-color"><?php 
            echo  esc_html__( 'Add New', 'banner-management-for-woocommerce' ) ;
            ?></a>
                <?php 
            $request_s = filter_input( INPUT_POST, 's', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( isset( $request_s ) && !empty($request_s) ) {
                echo  sprintf( '<span class="subtitle">' . wp_kses( __( 'Search results for: <strong>%s</strong>', 'banner-management-for-woocommerce' ), array(
                    'strong' => array(),
                ) ) . '</span>', esc_html( $request_s ) ) ;
            }
            ?>
                <?php 
            $wbm_sliders_Table_Class = new wcbm_banner_management_category_slider_Table();
            $wbm_sliders_Table_Class->process_bulk_action();
            $wbm_sliders_Table_Class->prepare_items();
            $wbm_sliders_Table_Class->search_box( esc_html__( 'Search', 'banner-management-for-woocommerce' ), 'dswbm-sliders' );
            $wbm_sliders_Table_Class->display();
            ?>
            </div>

            <?php 
        }
        
        /**
         * Add category slider methods form function.
         *
         * @since    1.0.0
         */
        public static function wcbm_add_category_sliders_form()
        {
            include plugin_dir_path( __FILE__ ) . 'wcbm-category-sliders-form.php';
        }
    
    }
}