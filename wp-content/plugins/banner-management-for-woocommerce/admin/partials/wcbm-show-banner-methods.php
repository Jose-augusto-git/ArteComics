<?php

/**
 * This file is contains display methods of all banners.
 *
 * @link       http://www.multidots.com/
 * @since      2.3.0
 *
 * @package    woocommerce_category_banner_management
 * @subpackage woocommerce_category_banner_management/admin/partials
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
* WCBM_banners_display_methods class.
*/
if ( !class_exists( 'WCBM_banners_display_methods' ) ) {
    class WCBM_banners_display_methods
    {
        /**
         * The ID of this plugin.
         *
         * @since    2.3.0
         * @access   private
         * @var      string $plugin_name The ID of this plugin.
         */
        private  $plugin_name ;
        /**
         * The version of this plugin.
         *
         * @since    2.3.0
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
            $this->plugin_name = $plugin_name;
            $this->version = $version;
        }
        
        /**
         * Function For display the banner image in shop page
         *
         *
         */
        public function wcbm_show_shop_page_banner_method()
        {
            $wbm_shop_page_stored_results_serialize_banner_src = '';
            $wbm_shop_page_stored_results_serialize_banner_link = '';
            $wbm_shop_page_stored_results_serialize_banner_enable_status = '';
            $alt_tag_value = '';
            $wbm_shop_page_stored_results_serialize_banner_target = '';
            $wbm_shop_page_stored_results_serialize_banner_relation = '';
            $wbm_shop_page_stored_results = ( function_exists( 'wcbm_get_page_banner_data' ) ? wcbm_get_page_banner_data( 'shop' ) : '' );
            
            if ( isset( $wbm_shop_page_stored_results ) && !empty($wbm_shop_page_stored_results) ) {
                $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
                
                if ( !empty($wbm_shop_page_stored_results_serialize) ) {
                    $wbm_shop_page_stored_results_serialize_banner_src = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_src']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_src'] : '' );
                    $wbm_shop_page_stored_results_serialize_banner_link = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_link_src']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_link_src'] : '' );
                    $wbm_shop_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_enable_status']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_enable_status'] : '' );
                    $wbm_shop_page_stored_results_serialize_banner_target = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_select_target']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_select_target'] : '' );
                    $wbm_shop_page_stored_results_serialize_banner_relation = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_select_relation']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_select_relation'] : '' );
                }
            
            }
            
            if ( is_shop() ) {
                
                if ( !empty($wbm_shop_page_stored_results_serialize_banner_enable_status) && $wbm_shop_page_stored_results_serialize_banner_enable_status === 'on' ) {
                    $shop_page_select_size = ( !empty($wbm_shop_page_stored_results['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results['shop_page_banner_image_size'] : '' );
                    $shop_page_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $shop_page_select_size ) : '' );
                    ?>
                        <div class="wbm_banner_image <?php 
                    echo  esc_attr( $shop_page_select_size_class ) ;
                    ?>">
                            <?php 
                    
                    if ( '' === $wbm_shop_page_stored_results_serialize_banner_link ) {
                        $alt_tag_css_shop_page_fornt = '';
                    } else {
                        
                        if ( !preg_match( "~^(?:f|ht)tps?://~i", $wbm_shop_page_stored_results_serialize_banner_link ) ) {
                            $image_link = "http://" . $wbm_shop_page_stored_results_serialize_banner_link;
                        } else {
                            $image_link = $wbm_shop_page_stored_results_serialize_banner_link;
                        }
                        
                        
                        if ( 'self' === $wbm_shop_page_stored_results_serialize_banner_target ) {
                            $target_attr = "_self";
                        } else {
                            $target_attr = "_blank";
                        }
                        
                        
                        if ( 'nofollow' === $wbm_shop_page_stored_results_serialize_banner_relation ) {
                            $rel_attr = "noopener noreferrer nofollow";
                        } else {
                            $rel_attr = "noopener noreferrer  follow";
                        }
                        
                        $alt_tag_css_shop_page_fornt = 'href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '"';
                    }
                    
                    ?>
                            <a <?php 
                    echo  wp_kses_post( $alt_tag_css_shop_page_fornt ) ;
                    ?>>
                                <p>
                                    <img src="<?php 
                    echo  esc_url( $wbm_shop_page_stored_results_serialize_banner_src ) ;
                    ?>"
                                        class="category_banner_image" title="<?php 
                    echo  esc_attr( $alt_tag_value ) ;
                    ?>"
                                        alt="<?php 
                    echo  esc_attr( $alt_tag_value ) ;
                    ?>">
                                </p>
                            </a>
                        </div>
                    <?php 
                }
            
            }
        }
        
        /**
         * Function For display banner image in cart page
         *
         */
        public function wcbm_show_cart_page_banner_method()
        {
            $wbm_cart_page_stored_results_serialize_banner_src = '';
            $wbm_cart_page_stored_results_serialize_banner_link = '';
            $wbm_cart_page_stored_results_serialize_banner_enable_status = '';
            $alt_tag_value = '';
            $wbm_cart_page_stored_results_serialize_banner_target = '';
            $wbm_cart_page_stored_results_serialize_banner_relation = '';
            $wbm_cart_page_stored_results = ( function_exists( 'wcbm_get_page_banner_data' ) ? wcbm_get_page_banner_data( 'cart' ) : '' );
            if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
                $wbm_shop_page_stored_results = wcbm_get_page_banner_data( 'shop' );
            }
            $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
            $wbm_shop_page_stored_results_serialize_banner_image_size = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_size'] : '' );
            $banner_global_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $wbm_shop_page_stored_results_serialize_banner_image_size ) : '' );
            
            if ( isset( $wbm_cart_page_stored_results ) && !empty($wbm_cart_page_stored_results) ) {
                $wbm_cart_page_stored_results_serialize = $wbm_cart_page_stored_results;
                
                if ( !empty($wbm_cart_page_stored_results_serialize) ) {
                    $wbm_cart_page_stored_results_serialize_banner_src = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_image_src']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_image_src'] : '' );
                    $wbm_cart_page_stored_results_serialize_banner_link = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_link_src']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_link_src'] : '' );
                    $wbm_cart_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_enable_status']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_enable_status'] : '' );
                    $wbm_cart_page_stored_results_serialize_banner_target = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_select_target']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_select_target'] : '' );
                    $wbm_cart_page_stored_results_serialize_banner_relation = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_select_relation']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_select_relation'] : '' );
                }
            
            }
            
            
            if ( !empty($wbm_cart_page_stored_results_serialize_banner_enable_status) && $wbm_cart_page_stored_results_serialize_banner_enable_status === 'on' ) {
                ?>
                        <div class="wbm_banner_image <?php 
                echo  esc_attr( $banner_global_select_size_class ) ;
                ?>">
                            <?php 
                
                if ( $wbm_cart_page_stored_results_serialize_banner_link === '' ) {
                    $alt_tag_css_cart_page_fornt = '';
                } else {
                    
                    if ( !preg_match( "~^(?:f|ht)tps?://~i", $wbm_cart_page_stored_results_serialize_banner_link ) ) {
                        $image_link = "http://" . $wbm_cart_page_stored_results_serialize_banner_link;
                    } else {
                        $image_link = $wbm_cart_page_stored_results_serialize_banner_link;
                    }
                    
                    
                    if ( 'self' === $wbm_cart_page_stored_results_serialize_banner_target ) {
                        $target_attr = "_self";
                    } else {
                        $target_attr = "_blank";
                    }
                    
                    
                    if ( 'nofollow' === $wbm_cart_page_stored_results_serialize_banner_relation ) {
                        $rel_attr = "noopener noreferrer nofollow";
                    } else {
                        $rel_attr = "noopener noreferrer  follow";
                    }
                    
                    $alt_tag_css_cart_page_fornt = 'href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '">';
                }
                
                ?>
                        <a <?php 
                echo  wp_kses_post( $alt_tag_css_cart_page_fornt ) ;
                ?>>
                            <p>
                                <img src="<?php 
                echo  esc_url( $wbm_cart_page_stored_results_serialize_banner_src ) ;
                ?>"
                                    class="category_banner_image" title="<?php 
                echo  esc_attr( $alt_tag_value ) ;
                ?> <?php 
                echo  esc_attr( $banner_global_select_size_class ) ;
                ?>"
                                    alt="<?php 
                echo  esc_attr( $alt_tag_value ) ;
                ?>">
                            </p>
                        </a>
                    </div>
                    <?php 
            }
        
        }
        
        /**
         * Function For display banner image in check out page
         *
         */
        public function wcbm_show_checkout_page_banner_method()
        {
            $wbm_checkout_page_stored_results_serialize_banner_src = '';
            $wbm_checkout_page_stored_results_serialize_banner_link = '';
            $wbm_checkout_page_stored_results_serialize_banner_enable_status = '';
            $alt_tag_value = '';
            $wbm_checkout_page_stored_results_serialize_banner_target = '';
            $wbm_checkout_page_stored_results_serialize_banner_relation = '';
            $wbm_checkout_page_stored_results = ( function_exists( 'wcbm_get_page_banner_data' ) ? wcbm_get_page_banner_data( 'checkout' ) : '' );
            if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
                $wbm_shop_page_stored_results = wcbm_get_page_banner_data( 'shop' );
            }
            $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
            $wbm_shop_page_stored_results_serialize_banner_image_size = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_size'] : '' );
            $banner_global_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $wbm_shop_page_stored_results_serialize_banner_image_size ) : '' );
            
            if ( isset( $wbm_checkout_page_stored_results ) && !empty($wbm_checkout_page_stored_results) ) {
                $wbm_checkout_page_stored_results_serialize = $wbm_checkout_page_stored_results;
                
                if ( !empty($wbm_checkout_page_stored_results_serialize) ) {
                    $wbm_checkout_page_stored_results_serialize_banner_src = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_image_src']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_image_src'] : '' );
                    $wbm_checkout_page_stored_results_serialize_banner_link = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_link_src']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_link_src'] : '' );
                    $wbm_checkout_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_enable_status']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_enable_status'] : '' );
                    $wbm_checkout_page_stored_results_serialize_banner_target = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_target']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_target'] : '' );
                    $wbm_checkout_page_stored_results_serialize_banner_relation = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_relation']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_relation'] : '' );
                }
            
            }
            
            
            if ( !empty($wbm_checkout_page_stored_results_serialize_banner_enable_status) && $wbm_checkout_page_stored_results_serialize_banner_enable_status === 'on' ) {
                ?>
                    <div class="wbm_banner_image <?php 
                echo  esc_attr( $banner_global_select_size_class ) ;
                ?>">
                        <?php 
                
                if ( $wbm_checkout_page_stored_results_serialize_banner_link === '' ) {
                    $alt_tag_css_checkout_page_fornt = '';
                } else {
                    
                    if ( !preg_match( "~^(?:f|ht)tps?://~i", $wbm_checkout_page_stored_results_serialize_banner_link ) ) {
                        $image_link = "http://" . $wbm_checkout_page_stored_results_serialize_banner_link;
                    } else {
                        $image_link = $wbm_checkout_page_stored_results_serialize_banner_link;
                    }
                    
                    
                    if ( 'self' === $wbm_checkout_page_stored_results_serialize_banner_target ) {
                        $target_attr = "_self";
                    } else {
                        $target_attr = "_blank";
                    }
                    
                    
                    if ( 'nofollow' === $wbm_checkout_page_stored_results_serialize_banner_relation ) {
                        $rel_attr = "noopener noreferrer nofollow";
                    } else {
                        $rel_attr = "noopener noreferrer  follow";
                    }
                    
                    $alt_tag_css_checkout_page_fornt = 'href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '"';
                }
                
                ?>
                        <a <?php 
                echo  wp_kses_post( $alt_tag_css_checkout_page_fornt ) ;
                ?>>
                            <p>
                                <img src="<?php 
                echo  esc_url( $wbm_checkout_page_stored_results_serialize_banner_src ) ;
                ?>"
                                    class="category_banner_image" title="<?php 
                echo  esc_attr( $alt_tag_value ) ;
                ?>"
                                    alt="<?php 
                echo  esc_attr( $alt_tag_value ) ;
                ?>">
                            </p>
                        </a>
                    </div>
                    <?php 
            }
        
        }
        
        /**
         * Function For display product page banner
         *
         */
        public function wcbm_display_product_banner_html()
        {
            $wbm_banner_detail_page_stored_results_serialize_banner_enable_status = '';
            $banner_detail_page_section_banner_enable_status = '';
            if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
                $wbm_banner_detail_page_stored_results = wcbm_get_page_banner_data( 'banner_detail' );
            }
            
            if ( isset( $wbm_banner_detail_page_stored_results ) && !empty($wbm_banner_detail_page_stored_results) ) {
                $wbm_banner_detail_page_stored_results_serialize = $wbm_banner_detail_page_stored_results;
                
                if ( !empty($wbm_banner_detail_page_stored_results_serialize) ) {
                    $wbm_banner_detail_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status'] : '' );
                    $banner_detail_page_section_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status'] : '' );
                }
            
            }
            
            
            if ( is_product() && 'on' !== $wbm_banner_detail_page_stored_results_serialize_banner_enable_status && 'on' === $banner_detail_page_section_banner_enable_status ) {
                $product_id = get_the_id();
                $heading_text = get_the_title( $product_id );
                $term_options = ( function_exists( 'wcbm_get_category_banner_data' ) ? wcbm_get_category_banner_data( $product_id ) : '' );
                $alt_tag_value = '';
                $cat_page_select_size = ( !empty($term_options['cat_page_select_size']) ? $term_options['cat_page_select_size'] : '' );
                $cat_page_banner_button_text = ( !empty($term_options['cat_page_banner_button_text']) ? $term_options['cat_page_banner_button_text'] : '' );
                $cat_page_banner_button_link = ( !empty($term_options['cat_page_banner_button_link']) ? $term_options['cat_page_banner_button_link'] : '' );
                $cat_page_banner_description = ( !empty($term_options['cat_page_banner_description']) ? $term_options['cat_page_banner_description'] : '' );
                $cat_page_select_target = ( !empty($term_options['cat_page_select_target']) ? $term_options['cat_page_select_target'] : 'blank' );
                $cat_page_select_relation = ( !empty($term_options['cat_page_select_relation']) ? $term_options['cat_page_select_relation'] : 'follow' );
                $cat_page_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $cat_page_select_size ) : '' );
                
                if ( isset( $term_options['auto_display_banner'] ) && 'on' === $term_options['auto_display_banner'] || !isset( $term_options['auto_display_banner'] ) ) {
                    ?>
                    <div class="wbm_banner_image <?php 
                    echo  esc_attr( $cat_page_select_size_class ) ;
                    ?>">
                        <?php 
                    if ( isset( $term_options['banner_url_id'] ) && '' !== $term_options['banner_url_id'] ) {
                        $url = $term_options['banner_url_id'];
                    }
                    // Exit if the image url doesn't exist
                    if ( !isset( $url ) or false === $url ) {
                        return;
                    }
                    // Get the banner link if it exists
                    if ( '' !== $term_options['banner_link'] ) {
                        $link = $term_options['banner_link'];
                    }
                    // Print Output
                    if ( isset( $link ) ) {
                        
                        if ( '' === $link ) {
                            echo  "<a>" ;
                        } else {
                            
                            if ( !preg_match( "~^(?:f|ht)tps?://~i", $link ) ) {
                                $image_link = "http://" . $link;
                            } else {
                                $image_link = $link;
                            }
                            
                            
                            if ( 'self' === $cat_page_select_target ) {
                                $target_attr = "_self";
                            } else {
                                $target_attr = "_blank";
                            }
                            
                            
                            if ( 'nofollow' === $cat_page_select_relation ) {
                                $rel_attr = "noopener noreferrer nofollow";
                            } else {
                                $rel_attr = "noopener noreferrer  follow";
                            }
                            
                            echo  '<a href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '">' ;
                        }
                    
                    }
                    if ( is_numeric( $url ) ) {
                        $url = wp_get_attachment_url( $url );
                    }
                    if ( false !== $url ) {
                        echo  "<img src='" . esc_url( $url ) . "' class='category_banner_image' />" ;
                    }
                    if ( isset( $link ) ) {
                        echo  "</a>" ;
                    }
                    
                    if ( isset( $term_options['display_cate_title_flag'] ) && 'on' === $term_options['display_cate_title_flag'] ) {
                        $title_color = ( isset( $term_options['cat_page_banner_title_color'] ) ? $term_options['cat_page_banner_title_color'] : '' );
                        $title_font = ( isset( $term_options['cat_banner_title_font_size'] ) ? $term_options['cat_banner_title_font_size'] : '' );
                        echo  '<div class="banner_default_title_row">' ;
                        echo  '<h2 class="banner_default_title" style="color:' . esc_attr( $title_color ) . '; font-size: ' . esc_attr( $title_font ) . 'px;">' ;
                        echo  esc_html( $heading_text, 'banner-management-for-woocommerce' ) ;
                        echo  '</h2>' ;
                        echo  '</div>' ;
                        $no_main_title = '';
                    } else {
                        $no_main_title = 'no_main_title';
                    }
                    
                    
                    if ( isset( $cat_page_banner_button_text ) || isset( $cat_page_banner_description ) ) {
                        $cat_page_banner_button_bg_color = ( isset( $term_options['cat_page_banner_button_bg_color'] ) ? $term_options['cat_page_banner_button_bg_color'] : '' );
                        $cat_page_banner_button_text_color = ( isset( $term_options['cat_page_banner_button_text_color'] ) ? $term_options['cat_page_banner_button_text_color'] : '' );
                        echo  '<div class="banner_button_container ' . esc_attr( $no_main_title ) . '">' ;
                        if ( isset( $cat_page_banner_description ) && '' !== $cat_page_banner_description ) {
                            echo  '<p class="banner_button_desciption" style="color:' . esc_attr( $cat_page_banner_button_text_color ) . ';">' . esc_html( $cat_page_banner_description, 'banner-management-for-woocommerce' ) . '</p>' ;
                        }
                        if ( isset( $cat_page_banner_button_text ) && '' !== $cat_page_banner_button_text ) {
                            echo  '<a href="' . esc_url( $cat_page_banner_button_link ) . '" class="button_link" style="background-color:' . esc_attr( $cat_page_banner_button_bg_color ) . ';color:' . esc_attr( $cat_page_banner_button_text_color ) . ';" target="_blank">' . esc_html( $cat_page_banner_button_text, 'banner-management-for-woocommerce' ) . '</a>' ;
                        }
                        echo  '</div>' ;
                    }
                    
                    echo  '</div>' ;
                }
            
            } else {
                
                if ( is_product() && 'on' === $wbm_banner_detail_page_stored_results_serialize_banner_enable_status ) {
                    $wbm_banner_detail_page_stored_results_serialize_benner_src = '';
                    $wbm_banner_detail_page_stored_results_serialize_benner_link = '';
                    $wbm_banner_detail_page_stored_results_serialize_benner_enable_status = '';
                    $alt_tag_value = '';
                    $wbm_banner_detail_page_stored_results = ( function_exists( 'wcbm_get_page_banner_data' ) ? wcbm_get_page_banner_data( 'banner_detail' ) : '' );
                    if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
                        $wbm_shop_page_stored_results = wcbm_get_page_banner_data( 'shop' );
                    }
                    $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
                    $wbm_shop_page_stored_results_serialize_banner_image_size = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_size'] : '' );
                    $banner_global_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $wbm_shop_page_stored_results_serialize_banner_image_size ) : '' );
                    
                    if ( isset( $wbm_banner_detail_page_stored_results ) && !empty($wbm_banner_detail_page_stored_results) ) {
                        $wbm_banner_detail_page_stored_results_serialize = $wbm_banner_detail_page_stored_results;
                        
                        if ( !empty($wbm_banner_detail_page_stored_results_serialize) ) {
                            $wbm_banner_detail_page_stored_results_serialize_benner_src = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_image_src']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_image_src'] : '' );
                            $wbm_banner_detail_page_stored_results_serialize_benner_link = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_link_src']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_link_src'] : '' );
                            $wbm_banner_detail_page_stored_results_serialize_benner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status'] : '' );
                            $wbm_banner_global_page_stored_results_serialize_banner_target = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_target']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_target'] : '' );
                            $wbm_banner_global_page_stored_results_serialize_banner_relation = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_relation']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_relation'] : '' );
                        }
                    
                    }
                    
                    
                    if ( !empty($wbm_banner_detail_page_stored_results_serialize_benner_enable_status) && $wbm_banner_detail_page_stored_results_serialize_benner_enable_status === 'on' ) {
                        ?>
                        <div class="wbm_banner_image <?php 
                        echo  esc_attr( $banner_global_select_size_class ) ;
                        ?>">
                            <?php 
                        
                        if ( '' === $wbm_banner_detail_page_stored_results_serialize_benner_link ) {
                            $alt_tag_css_banner_detail_page_front = 'class="no-link"';
                        } else {
                            
                            if ( !preg_match( "~^(?:f|ht)tps?://~i", $wbm_banner_detail_page_stored_results_serialize_benner_link ) ) {
                                $image_link = "http://" . $wbm_banner_detail_page_stored_results_serialize_benner_link;
                            } else {
                                $image_link = $wbm_banner_detail_page_stored_results_serialize_benner_link;
                            }
                            
                            
                            if ( 'self' === $wbm_banner_global_page_stored_results_serialize_banner_target ) {
                                $target_attr = "_self";
                            } else {
                                $target_attr = "_blank";
                            }
                            
                            
                            if ( 'nofollow' === $wbm_banner_global_page_stored_results_serialize_banner_relation ) {
                                $rel_attr = "noopener noreferrer nofollow";
                            } else {
                                $rel_attr = "noopener noreferrer follow";
                            }
                            
                            $alt_tag_css_banner_detail_page_front = 'class="has-link" href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '"';
                        }
                        
                        ?>
                            <a <?php 
                        echo  wp_kses_post( $alt_tag_css_banner_detail_page_front ) ;
                        ?>>
                                <p>
                                    <img src="<?php 
                        echo  esc_url( $wbm_banner_detail_page_stored_results_serialize_benner_src ) ;
                        ?>"
                                            class="category_banner_image" alt="<?php 
                        echo  esc_attr( $alt_tag_value ) ;
                        ?>">
                                </p>
                            </a>
                        </div>
                        <?php 
                    }
                
                }
            
            }
        
        }
        
        /**
         * Function For display category page banner
         *
         */
        public function wcbm_display_category_banner_html()
        {
            $wbm_banner_detail_page_stored_results_serialize_banner_enable_status = '';
            $banner_detail_page_section_banner_enable_status = '';
            if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
                $wbm_banner_detail_page_stored_results = wcbm_get_page_banner_data( 'banner_detail' );
            }
            
            if ( isset( $wbm_banner_detail_page_stored_results ) && !empty($wbm_banner_detail_page_stored_results) ) {
                $wbm_banner_detail_page_stored_results_serialize = $wbm_banner_detail_page_stored_results;
                
                if ( !empty($wbm_banner_detail_page_stored_results_serialize) ) {
                    $wbm_banner_detail_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status'] : '' );
                    $banner_detail_page_section_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status'] : '' );
                }
            
            }
            
            
            if ( is_product_category() || is_product_tag() ) {
                $category = get_queried_object();
                $cat_id = $category->term_id;
                $heading_text = $category->name;
                $term_options = ( function_exists( 'wcbm_get_category_banner_data' ) ? wcbm_get_category_banner_data( $cat_id ) : '' );
                $alt_tag_value = '';
                $cat_page_select_size = ( !empty($term_options['cat_page_select_size']) ? $term_options['cat_page_select_size'] : '' );
                $cat_page_banner_button_text = ( !empty($term_options['cat_page_banner_button_text']) ? $term_options['cat_page_banner_button_text'] : '' );
                $cat_page_banner_button_link = ( !empty($term_options['cat_page_banner_button_link']) ? $term_options['cat_page_banner_button_link'] : '' );
                $cat_page_banner_description = ( !empty($term_options['cat_page_banner_description']) ? $term_options['cat_page_banner_description'] : '' );
                $cat_page_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $cat_page_select_size ) : '' );
                
                if ( isset( $term_options['cat_page_select_target'] ) && '' !== $term_options['cat_page_select_target'] ) {
                    $wbm_shop_page_stored_results_serialize_banner_target = $term_options['cat_page_select_target'];
                } else {
                    $wbm_shop_page_stored_results_serialize_banner_target = 'blank';
                }
                
                
                if ( isset( $term_options['cat_page_select_relation'] ) && '' !== $term_options['cat_page_select_relation'] ) {
                    $wbm_shop_page_stored_results_serialize_banner_relation = $term_options['cat_page_select_relation'];
                } else {
                    $wbm_shop_page_stored_results_serialize_banner_relation = 'follow';
                }
                
                
                if ( isset( $term_options['auto_display_banner'] ) && 'on' === $term_options['auto_display_banner'] || !isset( $term_options['auto_display_banner'] ) ) {
                    ?>
                    <div class="wbm_banner_image <?php 
                    echo  esc_attr( $cat_page_select_size_class ) ;
                    ?>">
                        <?php 
                    if ( isset( $term_options['banner_url_id'] ) && '' !== $term_options['banner_url_id'] ) {
                        $url = $term_options['banner_url_id'];
                    }
                    // Exit if the image url doesn't exist
                    if ( !isset( $url ) or false === $url ) {
                        return;
                    }
                    // Get the banner link if it exists
                    if ( '' !== $term_options['banner_link'] ) {
                        $link = $term_options['banner_link'];
                    }
                    // Print Output
                    if ( isset( $link ) ) {
                        
                        if ( '' === $link ) {
                            echo  "<a>" ;
                        } else {
                            
                            if ( !preg_match( "~^(?:f|ht)tps?://~i", $link ) ) {
                                $image_link = "http://" . $link;
                            } else {
                                $image_link = $link;
                            }
                            
                            
                            if ( 'self' === $wbm_shop_page_stored_results_serialize_banner_target ) {
                                $target_attr = "_self";
                            } else {
                                $target_attr = "_blank";
                            }
                            
                            
                            if ( 'nofollow' === $wbm_shop_page_stored_results_serialize_banner_relation ) {
                                $rel_attr = "noopener noreferrer nofollow";
                            } else {
                                $rel_attr = "noopener noreferrer  follow";
                            }
                            
                            echo  '<a class="has-link" href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '">' ;
                        }
                    
                    }
                    if ( is_numeric( $url ) ) {
                        $url = wp_get_attachment_url( $url );
                    }
                    if ( false !== $url ) {
                        echo  "<img src='" . esc_url( $url ) . "' class='category_banner_image' />" ;
                    }
                    if ( isset( $link ) ) {
                        echo  "</a>" ;
                    }
                    
                    if ( isset( $term_options['display_cate_title_flag'] ) && 'on' === $term_options['display_cate_title_flag'] ) {
                        $title_color = ( isset( $term_options['cat_page_banner_title_color'] ) ? $term_options['cat_page_banner_title_color'] : '' );
                        $title_font = ( isset( $term_options['cat_banner_title_font_size'] ) ? $term_options['cat_banner_title_font_size'] : '' );
                        echo  '<div class="banner_default_title_row">' ;
                        echo  '<h2 class="banner_default_title" style="color:' . esc_attr( $title_color ) . '; font-size: ' . esc_attr( $title_font ) . 'px;">' ;
                        echo  esc_html( $heading_text, 'banner-management-for-woocommerce' ) ;
                        echo  '</h2>' ;
                        echo  '</div>' ;
                        $no_main_title = '';
                    } else {
                        $no_main_title = 'no_main_title';
                    }
                    
                    
                    if ( isset( $cat_page_banner_button_text ) || isset( $cat_page_banner_description ) ) {
                        $cat_page_banner_button_bg_color = ( isset( $term_options['cat_page_banner_button_bg_color'] ) ? $term_options['cat_page_banner_button_bg_color'] : '' );
                        $cat_page_banner_button_text_color = ( isset( $term_options['cat_page_banner_button_text_color'] ) ? $term_options['cat_page_banner_button_text_color'] : '' );
                        echo  '<div class="banner_button_container ' . esc_attr( $no_main_title ) . '">' ;
                        if ( isset( $cat_page_banner_description ) && '' !== $cat_page_banner_description ) {
                            echo  '<p class="banner_button_desciption" style="color:' . esc_attr( $cat_page_banner_button_text_color ) . ';">' . esc_html( $cat_page_banner_description, 'banner-management-for-woocommerce' ) . '</p>' ;
                        }
                        if ( isset( $cat_page_banner_button_text ) && '' !== $cat_page_banner_button_text ) {
                            echo  '<a href="' . esc_url( $cat_page_banner_button_link ) . '" class="button_link" style="background-color:' . esc_attr( $cat_page_banner_button_bg_color ) . ';color:' . esc_attr( $cat_page_banner_button_text_color ) . ';" target="_blank">' . esc_html( $cat_page_banner_button_text, 'banner-management-for-woocommerce' ) . '</a>' ;
                        }
                        echo  '</div>' ;
                    }
                    
                    echo  '</div>' ;
                }
            
            }
        
        }
        
        /**
         * Function For display the banner image in website other pages
         *
         *
         */
        public function wcbm_show_other_page_banner_method()
        {
            $wbm_other_page_stored_results_serialize_banner_enable_status = '';
            if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
                $wbm_other_page_stored_results = wcbm_get_page_banner_data( 'other_pages' );
            }
            
            if ( isset( $wbm_other_page_stored_results ) && !empty($wbm_other_page_stored_results) ) {
                $wbm_other_page_stored_results_serialize = $wbm_other_page_stored_results;
                $wbm_other_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_enable_status']) ? $wbm_other_page_stored_results_serialize['other_page_banner_enable_status'] : '' );
            }
            
            
            if ( is_page() && 'on' !== $wbm_other_page_stored_results_serialize_banner_enable_status && isset( $wbm_banner_other_page_section_stored_results_serialize_banner_enable_status ) && 'on' === $wbm_banner_other_page_section_stored_results_serialize_banner_enable_status ) {
                $page_id = get_the_id();
                $heading_text = get_the_title( $page_id );
                $term_options = ( function_exists( 'wcbm_get_category_banner_data' ) ? wcbm_get_category_banner_data( $page_id ) : '' );
                $alt_tag_value = '';
                $cat_page_select_size = ( !empty($term_options['cat_page_select_size']) ? $term_options['cat_page_select_size'] : '' );
                $cat_page_banner_button_text = ( !empty($term_options['cat_page_banner_button_text']) ? $term_options['cat_page_banner_button_text'] : '' );
                $cat_page_banner_button_link = ( !empty($term_options['cat_page_banner_button_link']) ? $term_options['cat_page_banner_button_link'] : '' );
                $cat_page_banner_description = ( !empty($term_options['cat_page_banner_description']) ? $term_options['cat_page_banner_description'] : '' );
                $cat_page_select_target = ( !empty($term_options['cat_page_select_target']) ? $term_options['cat_page_select_target'] : 'blank' );
                $cat_page_select_relation = ( !empty($term_options['cat_page_select_relation']) ? $term_options['cat_page_select_relation'] : 'follow' );
                $cat_page_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $cat_page_select_size ) : '' );
                
                if ( isset( $term_options['auto_display_banner'] ) && 'on' === $term_options['auto_display_banner'] || !isset( $term_options['auto_display_banner'] ) ) {
                    ?>
                    <div class="wbm_banner_image <?php 
                    echo  esc_attr( $cat_page_select_size_class ) ;
                    ?>">
                        <?php 
                    if ( isset( $term_options['banner_url_id'] ) && '' !== $term_options['banner_url_id'] ) {
                        $url = $term_options['banner_url_id'];
                    }
                    // Exit if the image url doesn't exist
                    if ( !isset( $url ) or false === $url ) {
                        return;
                    }
                    // Get the banner link if it exists
                    if ( '' !== $term_options['banner_link'] ) {
                        $link = $term_options['banner_link'];
                    }
                    // Print Output
                    if ( isset( $link ) ) {
                        
                        if ( '' === $link ) {
                            echo  "<a>" ;
                        } else {
                            
                            if ( !preg_match( "~^(?:f|ht)tps?://~i", $link ) ) {
                                $image_link = "http://" . $link;
                            } else {
                                $image_link = $link;
                            }
                            
                            
                            if ( 'self' === $cat_page_select_target ) {
                                $target_attr = "_self";
                            } else {
                                $target_attr = "_blank";
                            }
                            
                            
                            if ( 'nofollow' === $cat_page_select_relation ) {
                                $rel_attr = "noopener noreferrer nofollow";
                            } else {
                                $rel_attr = "noopener noreferrer  follow";
                            }
                            
                            echo  '<a href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '">' ;
                        }
                    
                    }
                    if ( is_numeric( $url ) ) {
                        $url = wp_get_attachment_url( $url );
                    }
                    if ( false !== $url ) {
                        echo  "<img src='" . esc_url( $url ) . "' class='category_banner_image' />" ;
                    }
                    if ( isset( $link ) ) {
                        echo  "</a>" ;
                    }
                    
                    if ( isset( $term_options['display_cate_title_flag'] ) && 'on' === $term_options['display_cate_title_flag'] ) {
                        $title_color = ( isset( $term_options['cat_page_banner_title_color'] ) ? $term_options['cat_page_banner_title_color'] : '' );
                        $title_font = ( isset( $term_options['cat_banner_title_font_size'] ) ? $term_options['cat_banner_title_font_size'] : '' );
                        echo  '<div class="banner_default_title_row">' ;
                        echo  '<h2 class="banner_default_title" style="color:' . esc_attr( $title_color ) . '; font-size: ' . esc_attr( $title_font ) . 'px;">' ;
                        echo  esc_html( $heading_text, 'banner-management-for-woocommerce' ) ;
                        echo  '</h2>' ;
                        echo  '</div>' ;
                        $no_main_title = '';
                    } else {
                        $no_main_title = 'no_main_title';
                    }
                    
                    
                    if ( isset( $cat_page_banner_button_text ) || isset( $cat_page_banner_description ) ) {
                        $cat_page_banner_button_bg_color = ( isset( $term_options['cat_page_banner_button_bg_color'] ) ? $term_options['cat_page_banner_button_bg_color'] : '' );
                        $cat_page_banner_button_text_color = ( isset( $term_options['cat_page_banner_button_text_color'] ) ? $term_options['cat_page_banner_button_text_color'] : '' );
                        echo  '<div class="banner_button_container ' . esc_attr( $no_main_title ) . '">' ;
                        if ( isset( $cat_page_banner_description ) && '' !== $cat_page_banner_description ) {
                            echo  '<p class="banner_button_desciption" style="color:' . esc_attr( $cat_page_banner_button_text_color ) . ';">' . esc_html( $cat_page_banner_description, 'banner-management-for-woocommerce' ) . '</p>' ;
                        }
                        if ( isset( $cat_page_banner_button_text ) && '' !== $cat_page_banner_button_text ) {
                            echo  '<a href="' . esc_url( $cat_page_banner_button_link ) . '" class="button_link" style="background-color:' . esc_attr( $cat_page_banner_button_bg_color ) . ';color:' . esc_attr( $cat_page_banner_button_text_color ) . ';" target="_blank">' . esc_html( $cat_page_banner_button_text, 'banner-management-for-woocommerce' ) . '</a>' ;
                        }
                        echo  '</div>' ;
                    }
                    
                    echo  '</div>' ;
                }
            
            } elseif ( is_page() && (!is_shop() && !is_checkout() && !is_cart()) ) {
                
                if ( !empty($wbm_other_page_stored_results_serialize_banner_enable_status) && $wbm_other_page_stored_results_serialize_banner_enable_status === 'on' ) {
                    $wbm_other_page_stored_results_serialize_banner_src = '';
                    $wbm_other_page_stored_results_serialize_banner_link = '';
                    $alt_tag_value = '';
                    $wbm_other_page_stored_results_serialize_banner_target = '';
                    $wbm_other_page_stored_results_serialize_banner_relation = '';
                    $wbm_other_page_stored_results = ( function_exists( 'wcbm_get_page_banner_data' ) ? wcbm_get_page_banner_data( 'other_pages' ) : '' );
                    if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
                        $wbm_shop_page_stored_results = wcbm_get_page_banner_data( 'shop' );
                    }
                    $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
                    $wbm_shop_page_stored_results_serialize_banner_image_size = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_size'] : '' );
                    $banner_global_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $wbm_shop_page_stored_results_serialize_banner_image_size ) : '' );
                    
                    if ( isset( $wbm_other_page_stored_results ) && !empty($wbm_other_page_stored_results) ) {
                        $wbm_other_page_stored_results_serialize = $wbm_other_page_stored_results;
                        
                        if ( !empty($wbm_other_page_stored_results_serialize) ) {
                            $wbm_other_page_stored_results_serialize_banner_src = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_image_src']) ? $wbm_other_page_stored_results_serialize['other_page_banner_image_src'] : '' );
                            $wbm_other_page_stored_results_serialize_banner_link = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_link_src']) ? $wbm_other_page_stored_results_serialize['other_page_banner_link_src'] : '' );
                            $wbm_other_page_stored_results_serialize_banner_target = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_select_target']) ? $wbm_other_page_stored_results_serialize['other_page_banner_select_target'] : '' );
                            $wbm_other_page_stored_results_serialize_banner_relation = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_select_relation']) ? $wbm_other_page_stored_results_serialize['other_page_banner_select_relation'] : '' );
                        }
                    
                    }
                    
                    ?>
                        <div class="wbm_banner_image <?php 
                    echo  esc_attr( $banner_global_select_size_class ) ;
                    ?>">
                            <?php 
                    
                    if ( '' === $wbm_other_page_stored_results_serialize_banner_link ) {
                        $alt_tag_css_other_page_fornt = '';
                    } else {
                        
                        if ( !preg_match( "~^(?:f|ht)tps?://~i", $wbm_other_page_stored_results_serialize_banner_link ) ) {
                            $image_link = "http://" . $wbm_other_page_stored_results_serialize_banner_link;
                        } else {
                            $image_link = $wbm_other_page_stored_results_serialize_banner_link;
                        }
                        
                        
                        if ( 'self' === $wbm_other_page_stored_results_serialize_banner_target ) {
                            $target_attr = "_self";
                        } else {
                            $target_attr = "_blank";
                        }
                        
                        
                        if ( 'nofollow' === $wbm_other_page_stored_results_serialize_banner_relation ) {
                            $rel_attr = "noopener noreferrer nofollow";
                        } else {
                            $rel_attr = "noopener noreferrer  follow";
                        }
                        
                        $alt_tag_css_other_page_fornt = 'href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '"';
                    }
                    
                    ?>
                            <a <?php 
                    echo  wp_kses_post( $alt_tag_css_other_page_fornt ) ;
                    ?>>
                                <p>
                                    <img src="<?php 
                    echo  esc_url( $wbm_other_page_stored_results_serialize_banner_src ) ;
                    ?>"
                                        class="category_banner_image" title="<?php 
                    echo  esc_attr( $alt_tag_value ) ;
                    ?>"
                                        alt="<?php 
                    echo  esc_attr( $alt_tag_value ) ;
                    ?>">
                                </p>
                            </a>
                        </div>
                    <?php 
                }
            
            }
        
        }
    
    }
}