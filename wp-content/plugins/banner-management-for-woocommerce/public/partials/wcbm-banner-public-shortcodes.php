<?php

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/** Added new shortcode for product sliders */
add_shortcode( 'wcbm_product_slider', 'wcbm_product_slider_shortcode_callback' );
/**
 * Function For display the product sliders
 *
 */
function wcbm_product_slider_shortcode_callback( $atts )
{
    $atts = shortcode_atts( array(
        'on_sale'             => '',
        'featured_product_id' => '',
        'price_range'         => '',
        'category_id'         => '',
    ), $atts, 'wcbm_product_slider' );
    // setup query
    $args = array(
        'post_type'   => 'product',
        'post_status' => 'publish',
    );
    // query for on sale products
    
    if ( isset( $atts['on_sale'] ) && !empty($atts['on_sale']) && $atts['on_sale'] === 'yes' ) {
        $product_ids_on_sale = wc_get_product_ids_on_sale();
        $args['post__in'] = $product_ids_on_sale;
    }
    
    // query for featured products
    
    if ( isset( $atts['featured_product_id'] ) && !empty($atts['featured_product_id']) ) {
        $f_product_arr = explode( ',', $atts['featured_product_id'] );
        $args['post__in'] = $f_product_arr;
    }
    
    // query for on sale and featured product
    
    if ( isset( $atts['on_sale'] ) && !empty($atts['on_sale']) && $atts['on_sale'] === 'yes' && isset( $atts['featured_product_id'] ) && !empty($atts['featured_product_id']) ) {
        $f_product_arr = explode( ',', $atts['featured_product_id'] );
        $product_ids_on_sale = wc_get_product_ids_on_sale();
        $product_arr = array_intersect( $product_ids_on_sale, $f_product_arr );
        $args['post__in'] = $product_arr;
    }
    
    // query for product price range
    
    if ( isset( $atts['price_range'] ) && !empty($atts['price_range']) ) {
        $price_range = explode( '-', $atts['price_range'] );
        $current_min_price = $price_range[0];
        $current_max_price = $price_range[1];
        $args['meta_query'] = array( array(
            'key'     => '_price',
            'value'   => array( $current_min_price, $current_max_price ),
            'compare' => 'BETWEEN',
            'type'    => 'NUMERIC',
        ) );
    }
    
    // query database
    $products = new WP_Query( $args );
    ob_start();
    
    if ( $products->have_posts() ) {
        ?>
	<div class="wbm_banner_random_image">
		<div class="banner-content">
			<div class="wbm-product-bxslider">
				<?php 
        woocommerce_product_loop_start();
        while ( $products->have_posts() ) {
            $products->the_post();
            wc_get_template_part( 'content', 'product' );
        }
        // end of the loop.
        woocommerce_product_loop_end();
        ?>
			</div>
		</div>
	</div>
	<?php 
    }
    
    return ob_get_clean();
}

/** Added new shortcode for category sliders */
add_shortcode( 'wcbm_category_slider', 'wcbm_category_slider_shortcode_callback' );
/**
 * Function For display the product sliders
 *
 */
function wcbm_category_slider_shortcode_callback( $atts )
{
    $atts = shortcode_atts( array(
        'category_id'     => '',
        'cat_title'       => '',
        'cat_feature_img' => '',
        'cat_description' => '',
        'cat_count'       => '',
    ), $atts, 'wcbm_category_slider' );
    // setup query
    $args = array(
        'post_type'  => 'product',
        'hide_empty' => false,
    );
    $terms = get_terms( 'product_cat', $args );
    ob_start();
    ?>
	<div class="wbm_banner_random_image">
		<div class="banner-content">
			<div class="wbm-product-bxslider">
				<ul class="wbm-category-slider">
					<?php 
    // query for selected categories
    
    if ( isset( $atts['category_id'] ) && !empty($atts['category_id']) ) {
        $prod_cat_arrs = explode( ',', $atts['category_id'] );
        foreach ( $prod_cat_arrs as $prod_cat_arr ) {
            foreach ( $terms as $term ) {
                
                if ( intval( $prod_cat_arr ) === $term->term_id ) {
                    ?>
									<li class="term-list">
										<?php 
                    
                    if ( isset( $atts['cat_feature_img'] ) && !empty($atts['cat_feature_img']) && $atts['cat_feature_img'] === 'yes' ) {
                        ?>
											<div class="term-img">
												<?php 
                        $thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                        
                        if ( intval( $thumb_id ) !== 0 ) {
                            $term_img = wp_get_attachment_url( $thumb_id );
                            ?>
													<img src="<?php 
                            echo  esc_url( $term_img ) ;
                            ?>" alt="<?php 
                            echo  esc_attr( $term->name ) ;
                            ?>">	
												<?php 
                        }
                        
                        ?>
											</div>
											<?php 
                    }
                    
                    ?>
										<div class="term-content">
											<?php 
                    
                    if ( isset( $atts['cat_title'] ) && !empty($atts['cat_title']) && $atts['cat_title'] === 'yes' ) {
                        $term_link = get_term_link( $term );
                        // If there was an error, continue to the next term.
                        if ( is_wp_error( $term_link ) ) {
                            continue;
                        }
                        ?>
												<h3><a href="<?php 
                        echo  esc_url( $term_link ) ;
                        ?>"><?php 
                        echo  esc_html( $term->name, 'banner-management-for-woocommerce' ) ;
                        ?></a></h3>
												<?php 
                    }
                    
                    // strip tags to avoid breaking any html
                    $desString = wp_strip_all_tags( $term->description );
                    
                    if ( strlen( $desString ) > 110 ) {
                        // truncate string
                        $desStringCut = substr( $desString, 0, 110 );
                        $endPoint = strrpos( $desStringCut, ' ' );
                        //if the string doesn't contain any space then it will cut without word basis.
                        $desString = ( $endPoint ? substr( $desStringCut, 0, $endPoint ) : substr( $desStringCut, 0 ) );
                        $desString .= '...';
                    }
                    
                    
                    if ( isset( $atts['cat_description'] ) && !empty($atts['cat_description']) && $atts['cat_description'] === 'yes' ) {
                        ?>
												<p><?php 
                        echo  esc_html( $desString, 'banner-management-for-woocommerce' ) ;
                        ?></p>
												<?php 
                    }
                    
                    ?>
										</div>
									</li>
									<?php 
                }
            
            }
        }
    } elseif ( isset( $terms ) && !empty($terms) ) {
        foreach ( $terms as $term ) {
            ?>
							<li class="term-list">
								<?php 
            
            if ( isset( $atts['cat_feature_img'] ) && !empty($atts['cat_feature_img']) && $atts['cat_feature_img'] === 'yes' ) {
                ?>
									<div class="term-img">
										<?php 
                $thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                
                if ( intval( $thumb_id ) !== 0 ) {
                    $term_img = wp_get_attachment_url( $thumb_id );
                    ?>
											<img src="<?php 
                    echo  esc_url( $term_img ) ;
                    ?>" alt="<?php 
                    echo  esc_attr( $term->name ) ;
                    ?>">	
										<?php 
                }
                
                ?>
									</div>
									<?php 
            }
            
            ?>
								<div class="term-content">
									<?php 
            
            if ( isset( $atts['cat_title'] ) && !empty($atts['cat_title']) && $atts['cat_title'] === 'yes' ) {
                $term_link = get_term_link( $term );
                // If there was an error, continue to the next term.
                if ( is_wp_error( $term_link ) ) {
                    continue;
                }
                ?>
										<h3><a href="<?php 
                echo  esc_url( $term_link ) ;
                ?>"><?php 
                echo  esc_html( $term->name, 'banner-management-for-woocommerce' ) ;
                ?></a></h3>
										<?php 
            }
            
            // strip tags to avoid breaking any html
            $desString = wp_strip_all_tags( $term->description );
            
            if ( strlen( $desString ) > 110 ) {
                // truncate string
                $desStringCut = substr( $desString, 0, 110 );
                $endPoint = strrpos( $desStringCut, ' ' );
                //if the string doesn't contain any space then it will cut without word basis.
                $desString = ( $endPoint ? substr( $desStringCut, 0, $endPoint ) : substr( $desStringCut, 0 ) );
                $desString .= '...';
            }
            
            
            if ( isset( $atts['cat_description'] ) && !empty($atts['cat_description']) && $atts['cat_description'] === 'yes' ) {
                ?>
										<p><?php 
                echo  esc_html( $desString, 'banner-management-for-woocommerce' ) ;
                ?></p>
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
			</div>
		</div>
	</div>
	<?php 
    return ob_get_clean();
}

/** Added new shortcode for category list banner page */
add_shortcode( 'display_category_banner', 'wcbm_display_category_banner_callback' );
/**
 * Get the category banner html
 *
 */
function wcbm_display_category_banner_callback()
{
    $banner_methods_obj = new WCBM_banners_display_methods( '', '' );
    ob_start();
    echo  wp_kses_post( $banner_methods_obj->wcbm_display_category_banner_html() ) ;
    return ob_get_clean();
}

/** Added new shortcode for product detail page banner */
add_shortcode( 'display_product_banner', 'wcbm_display_product_banner_callback' );
/**
 * Get the product page banner
 *
 */
function wcbm_display_product_banner_callback()
{
    $banner_methods_obj = new WCBM_banners_display_methods( '', '' );
    ob_start();
    echo  wp_kses_post( $banner_methods_obj->wcbm_display_product_banner_html() ) ;
    return ob_get_clean();
}

/** Added new shortcode for website other page banner */
add_shortcode( 'display_page_banner', 'wcbm_display_page_banner_callback' );
/**
 * Get the website other page banner
 *
 */
function wcbm_display_page_banner_callback()
{
    $banner_methods_obj = new WCBM_banners_display_methods( '', '' );
    ob_start();
    echo  wp_kses_post( $banner_methods_obj->wcbm_show_other_page_banner_method() ) ;
    return ob_get_clean();
}

/** Added new shortcode for category list banner page */
add_shortcode( 'wcbm_category', 'wcbm_category_sliders_shortcode_callback_method' );
/**
 * Function For display the category sliders
 *
 * @since    2.3.0
 */
function wcbm_category_sliders_shortcode_callback_method( $atts )
{
    $atts = shortcode_atts( array(
        'id' => '',
    ), $atts, 'wcbm_category' );
    $wbm_slider_status = '';
    $wbm_slider_rule_name = '';
    $wbm_category_slider_general_meta = '';
    $wbm_category_slider_display_meta = '';
    $wbm_category_slider_thumbnail_meta = '';
    $wbm_category_slider_sliders_meta = '';
    
    if ( isset( $atts['id'] ) && !empty($atts['id']) ) {
        $post_id = $atts['id'];
        $wbm_slider_status = get_post_status( $post_id );
        $wbm_slider_rule_name = __( get_the_title( $post_id ), 'banner-management-for-woocommerce' );
        $wbm_category_slider_general_meta = get_post_meta( $post_id, 'wbm_category_slider_general_meta', true );
        
        if ( is_serialized( $wbm_category_slider_general_meta ) ) {
            $wbm_category_slider_general_meta = maybe_unserialize( $wbm_category_slider_general_meta );
        } else {
            $wbm_category_slider_general_meta = $wbm_category_slider_general_meta;
        }
        
        $wbm_category_slider_display_meta = get_post_meta( $post_id, 'wbm_category_slider_display_meta', true );
        
        if ( is_serialized( $wbm_category_slider_display_meta ) ) {
            $wbm_category_slider_display_meta = maybe_unserialize( $wbm_category_slider_display_meta );
        } else {
            $wbm_category_slider_display_meta = $wbm_category_slider_display_meta;
        }
        
        $wbm_category_slider_thumbnail_meta = get_post_meta( $post_id, 'wbm_category_slider_thumbnail_meta', true );
        
        if ( is_serialized( $wbm_category_slider_thumbnail_meta ) ) {
            $wbm_category_slider_thumbnail_meta = maybe_unserialize( $wbm_category_slider_thumbnail_meta );
        } else {
            $wbm_category_slider_thumbnail_meta = $wbm_category_slider_thumbnail_meta;
        }
        
        $wbm_category_slider_sliders_meta = get_post_meta( $post_id, 'wbm_category_slider_sliders_meta', true );
        
        if ( is_serialized( $wbm_category_slider_sliders_meta ) ) {
            $wbm_category_slider_sliders_meta = maybe_unserialize( $wbm_category_slider_sliders_meta );
        } else {
            $wbm_category_slider_sliders_meta = $wbm_category_slider_sliders_meta;
        }
        
        $wbm_category_slider_typo_meta = get_post_meta( $post_id, 'wbm_category_slider_typo_meta', true );
        
        if ( is_serialized( $wbm_category_slider_typo_meta ) ) {
            $wbm_category_slider_typo_meta = maybe_unserialize( $wbm_category_slider_typo_meta );
        } else {
            $wbm_category_slider_typo_meta = $wbm_category_slider_typo_meta;
        }
    
    }
    
    
    if ( isset( $wbm_slider_status ) && 'publish' === $wbm_slider_status ) {
        if ( isset( $wbm_category_slider_general_meta ) && !empty($wbm_category_slider_general_meta) ) {
            foreach ( $wbm_category_slider_general_meta as $key => $general_settings ) {
                $wbm_category_type = ( isset( $general_settings['wbm_category_type'] ) ? $general_settings['wbm_category_type'] : '' );
                $wbm_filter_categories = ( isset( $general_settings['wbm_filter_categories'] ) ? $general_settings['wbm_filter_categories'] : '' );
                $wbm_choose_categories = ( isset( $general_settings['wbm_choose_categories'] ) ? $general_settings['wbm_choose_categories'] : array() );
                $wbm_total_cat_show = ( isset( $general_settings['wbm_total_cat_show'] ) ? $general_settings['wbm_total_cat_show'] : '' );
                $wbm_cat_order_by = ( isset( $general_settings['wbm_cat_order_by'] ) ? $general_settings['wbm_cat_order_by'] : '' );
                $wbm_cat_order = ( isset( $general_settings['wbm_cat_order'] ) ? $general_settings['wbm_cat_order'] : '' );
                $no_cols_large_desktop = ( isset( $general_settings['wbm_cat_no_of_cols']['large_desktop'] ) ? $general_settings['wbm_cat_no_of_cols']['large_desktop'] : '' );
                $no_cols_desktop = ( isset( $general_settings['wbm_cat_no_of_cols']['desktop'] ) ? $general_settings['wbm_cat_no_of_cols']['desktop'] : '' );
                $no_cols_laptop = ( isset( $general_settings['wbm_cat_no_of_cols']['laptop'] ) ? $general_settings['wbm_cat_no_of_cols']['laptop'] : '' );
                $no_cols_tablet = ( isset( $general_settings['wbm_cat_no_of_cols']['tablet'] ) ? $general_settings['wbm_cat_no_of_cols']['tablet'] : '' );
                $no_cols_mobile = ( isset( $general_settings['wbm_cat_no_of_cols']['mobile'] ) ? $general_settings['wbm_cat_no_of_cols']['mobile'] : '' );
                $dswbm_slider_mode = ( isset( $general_settings['dswbm_slider_mode'] ) && !empty($general_settings['dswbm_slider_mode']) ? $general_settings['dswbm_slider_mode'] : '' );
                $wbm_layout_presets = ( isset( $general_settings['wbm_layout_presets'] ) ? $general_settings['wbm_layout_presets'] : 'slider' );
                $wbm_exclude_categories = ( isset( $general_settings['wbm_exclude_categories'] ) ? $general_settings['wbm_exclude_categories'] : array() );
                $dswbm_child_categories = ( isset( $general_settings['dswbm_child_categories'] ) ? $general_settings['dswbm_child_categories'] : 'parent' );
                $wbm_choose_categories_pnc_cat = ( isset( $general_settings['wbm_choose_categories_pnc_cat'] ) ? $general_settings['wbm_choose_categories_pnc_cat'] : array() );
                $wbm_parent_child_display_type = ( isset( $general_settings['wbm_parent_child_display_type'] ) ? $general_settings['wbm_parent_child_display_type'] : '' );
            }
        }
        if ( isset( $wbm_category_slider_display_meta ) && !empty($wbm_category_slider_display_meta) ) {
            foreach ( $wbm_category_slider_display_meta as $key => $display_settings ) {
                $wbm_cat_title = ( isset( $display_settings['wbm_cat_title'] ) ? $display_settings['wbm_cat_title'] : '' );
                $wbm_cat_title_color = ( isset( $display_settings['wbm_cat_title_color'] ) ? $display_settings['wbm_cat_title_color'] : '' );
                $sec_title_top_margin = ( isset( $display_settings['wbm_cat_title_margin']['top'] ) ? $display_settings['wbm_cat_title_margin']['top'] : '' );
                $sec_title_right_margin = ( isset( $display_settings['wbm_cat_title_margin']['right'] ) ? $display_settings['wbm_cat_title_margin']['right'] : '' );
                $sec_title_bottom_margin = ( isset( $display_settings['wbm_cat_title_margin']['bottom'] ) ? $display_settings['wbm_cat_title_margin']['bottom'] : '' );
                $sec_title_left_margin = ( isset( $display_settings['wbm_cat_title_margin']['left'] ) ? $display_settings['wbm_cat_title_margin']['left'] : '' );
                $sec_title_margin_unit = ( isset( $display_settings['wbm_cat_title_margin']['unit'] ) ? $display_settings['wbm_cat_title_margin']['unit'] : '' );
                $wbm_cat_space_between = ( isset( $display_settings['wbm_cat_space_between'] ) ? $display_settings['wbm_cat_space_between'] : '' );
                $wbm_cat_make_card_style = ( isset( $display_settings['wbm_cat_make_card_style'] ) ? $display_settings['wbm_cat_make_card_style'] : '' );
                $wbm_cat_card_top_border = ( isset( $display_settings['wbm_cat_card_border']['top'] ) ? $display_settings['wbm_cat_card_border']['top'] : '' );
                $wbm_cat_card_right_border = ( isset( $display_settings['wbm_cat_card_border']['right'] ) ? $display_settings['wbm_cat_card_border']['right'] : '' );
                $wbm_cat_card_bottom_border = ( isset( $display_settings['wbm_cat_card_border']['bottom'] ) ? $display_settings['wbm_cat_card_border']['bottom'] : '' );
                $wbm_cat_card_left_border = ( isset( $display_settings['wbm_cat_card_border']['left'] ) ? $display_settings['wbm_cat_card_border']['left'] : '' );
                $wbm_cat_card_style_border = ( isset( $display_settings['wbm_cat_card_border']['style'] ) ? $display_settings['wbm_cat_card_border']['style'] : '' );
                $wbm_cat_card_border_color = ( isset( $display_settings['wbm_cat_card_border']['color'] ) ? $display_settings['wbm_cat_card_border']['color'] : '' );
                $wbm_cat_card_border_hov_color = ( isset( $display_settings['wbm_cat_card_border']['hover_color'] ) ? $display_settings['wbm_cat_card_border']['hover_color'] : '' );
                $wbm_cat_card_bg_color = ( isset( $display_settings['wbm_cat_card_background']['bg_color'] ) ? $display_settings['wbm_cat_card_background']['bg_color'] : '' );
                $wbm_cat_card_bg_hov_color = ( isset( $display_settings['wbm_cat_card_background']['bg_hover_color'] ) ? $display_settings['wbm_cat_card_background']['bg_hover_color'] : '' );
                $wbm_cat_inner_top_padding = ( isset( $display_settings['wbm_cat_inner_padding']['top'] ) ? $display_settings['wbm_cat_inner_padding']['top'] : '' );
                $wbm_cat_inner_right_padding = ( isset( $display_settings['wbm_cat_inner_padding']['right'] ) ? $display_settings['wbm_cat_inner_padding']['right'] : '' );
                $wbm_cat_inner_bottom_padding = ( isset( $display_settings['wbm_cat_inner_padding']['bottom'] ) ? $display_settings['wbm_cat_inner_padding']['bottom'] : '' );
                $wbm_cat_inner_left_padding = ( isset( $display_settings['wbm_cat_inner_padding']['left'] ) ? $display_settings['wbm_cat_inner_padding']['left'] : '' );
                $wbm_cat_inner_padding_unit = ( isset( $display_settings['wbm_cat_inner_padding']['unit'] ) ? $display_settings['wbm_cat_inner_padding']['unit'] : '' );
                $wbm_cat_name_status = ( isset( $display_settings['wbm_cat_name_status'] ) ? $display_settings['wbm_cat_name_status'] : '' );
                $wbm_cat_name_color = ( isset( $display_settings['wbm_cat_name_color'] ) ? $display_settings['wbm_cat_name_color'] : '' );
                $wbm_cat_name_top_margin = ( isset( $display_settings['wbm_cat_name_margin']['top'] ) ? $display_settings['wbm_cat_name_margin']['top'] : '' );
                $wbm_cat_name_right_margin = ( isset( $display_settings['wbm_cat_name_margin']['right'] ) ? $display_settings['wbm_cat_name_margin']['right'] : '' );
                $wbm_cat_name_bottom_margin = ( isset( $display_settings['wbm_cat_name_margin']['bottom'] ) ? $display_settings['wbm_cat_name_margin']['bottom'] : '' );
                $wbm_cat_name_left_margin = ( isset( $display_settings['wbm_cat_name_margin']['left'] ) ? $display_settings['wbm_cat_name_margin']['left'] : '' );
                $wbm_cat_name_margin_unit = ( isset( $display_settings['wbm_cat_name_margin']['unit'] ) ? $display_settings['wbm_cat_name_margin']['unit'] : '' );
                $wbm_cat_prod_count = ( isset( $display_settings['wbm_cat_prod_count'] ) ? $display_settings['wbm_cat_prod_count'] : '' );
                $wbm_cat_count_position = ( isset( $display_settings['wbm_cat_count_position'] ) ? $display_settings['wbm_cat_count_position'] : '' );
                $wbm_prod_count_before = ( isset( $display_settings['wbm_prod_count_before'] ) ? $display_settings['wbm_prod_count_before'] : '' );
                $wbm_prod_count_after = ( isset( $display_settings['wbm_prod_count_after'] ) ? $display_settings['wbm_prod_count_after'] : '' );
                $wbm_prod_count_color = ( isset( $display_settings['wbm_prod_count_color'] ) ? $display_settings['wbm_prod_count_color'] : '' );
                $wbm_cat_desc_status = ( isset( $display_settings['wbm_cat_desc_status'] ) ? $display_settings['wbm_cat_desc_status'] : '' );
                $wbm_cat_desc_color = ( isset( $display_settings['wbm_cat_desc_color'] ) ? $display_settings['wbm_cat_desc_color'] : '' );
                $wbm_cat_desc_top_margin = ( isset( $display_settings['wbm_cat_desc_margin']['top'] ) ? $display_settings['wbm_cat_desc_margin']['top'] : '' );
                $wbm_cat_desc_right_margin = ( isset( $display_settings['wbm_cat_desc_margin']['right'] ) ? $display_settings['wbm_cat_desc_margin']['right'] : '' );
                $wbm_cat_desc_bottom_margin = ( isset( $display_settings['wbm_cat_desc_margin']['bottom'] ) ? $display_settings['wbm_cat_desc_margin']['bottom'] : '' );
                $wbm_cat_desc_left_margin = ( isset( $display_settings['wbm_cat_desc_margin']['left'] ) ? $display_settings['wbm_cat_desc_margin']['left'] : '' );
                $wbm_cat_desc_margin_unit = ( isset( $display_settings['wbm_cat_desc_margin']['unit'] ) ? $display_settings['wbm_cat_desc_margin']['unit'] : '' );
                $wbm_shop_now_button = ( isset( $display_settings['wbm_shop_now_button'] ) ? $display_settings['wbm_shop_now_button'] : '' );
                $wbm_shop_now_label = ( isset( $display_settings['wbm_shop_now_label'] ) ? $display_settings['wbm_shop_now_label'] : '' );
                $wbm_shop_now_color = ( isset( $display_settings['wbm_shop_now_color']['color'] ) ? $display_settings['wbm_shop_now_color']['color'] : '' );
                $wbm_shop_now_hov_color = ( isset( $display_settings['wbm_shop_now_color']['hover_color'] ) ? $display_settings['wbm_shop_now_color']['hover_color'] : '' );
                $wbm_shop_now_bg_color = ( isset( $display_settings['wbm_shop_now_color']['bg_color'] ) ? $display_settings['wbm_shop_now_color']['bg_color'] : '' );
                $wbm_shop_now_hov_bg_color = ( isset( $display_settings['wbm_shop_now_color']['bg_hover_color'] ) ? $display_settings['wbm_shop_now_color']['bg_hover_color'] : '' );
                $wbm_shop_now_all_border = ( isset( $display_settings['wbm_shop_now_border']['all'] ) ? $display_settings['wbm_shop_now_border']['all'] : '' );
                $wbm_shop_now_border_style = ( isset( $display_settings['wbm_shop_now_border']['style'] ) ? $display_settings['wbm_shop_now_border']['style'] : '' );
                $wbm_shop_now_border_color = ( isset( $display_settings['wbm_shop_now_border']['color'] ) ? $display_settings['wbm_shop_now_border']['color'] : '' );
                $wbm_shop_now_border_hov_color = ( isset( $display_settings['wbm_shop_now_border']['hover_color'] ) ? $display_settings['wbm_shop_now_border']['hover_color'] : '' );
                $wbm_shop_now_top_margin = ( isset( $display_settings['wbm_shop_now_margin']['top'] ) ? $display_settings['wbm_shop_now_margin']['top'] : '' );
                $wbm_shop_now_right_margin = ( isset( $display_settings['wbm_shop_now_margin']['right'] ) ? $display_settings['wbm_shop_now_margin']['right'] : '' );
                $wbm_shop_now_bottom_margin = ( isset( $display_settings['wbm_shop_now_margin']['bottom'] ) ? $display_settings['wbm_shop_now_margin']['bottom'] : '' );
                $wbm_shop_now_left_margin = ( isset( $display_settings['wbm_shop_now_margin']['left'] ) ? $display_settings['wbm_shop_now_margin']['left'] : '' );
                $wbm_shop_now_margin_unit = ( isset( $display_settings['wbm_shop_now_margin']['unit'] ) ? $display_settings['wbm_shop_now_margin']['unit'] : '' );
                $wbm_shop_now_link_target = ( isset( $display_settings['wbm_shop_now_link_target'] ) ? $display_settings['wbm_shop_now_link_target'] : '' );
                $wbm_cat_content_position = '';
            }
        }
        if ( isset( $wbm_category_slider_thumbnail_meta ) && !empty($wbm_category_slider_thumbnail_meta) ) {
            foreach ( $wbm_category_slider_thumbnail_meta as $key => $thumbnail_settings ) {
                $wbm_cat_thumbnail = ( isset( $thumbnail_settings['wbm_cat_thumbnail'] ) ? $thumbnail_settings['wbm_cat_thumbnail'] : '' );
                $wbm_cat_thumbnail_size = ( isset( $thumbnail_settings['wbm_cat_thumbnail_size'] ) ? $thumbnail_settings['wbm_cat_thumbnail_size'] : '' );
                $wbm_cat_thumb_style = ( isset( $thumbnail_settings['wbm_cat_thumb_style'] ) ? $thumbnail_settings['wbm_cat_thumb_style'] : '' );
                $wbm_thumb_all_border = ( isset( $thumbnail_settings['wbm_thumb_border']['all'] ) ? $thumbnail_settings['wbm_thumb_border']['all'] : '' );
                $wbm_thumb_border_style = ( isset( $thumbnail_settings['wbm_thumb_border']['style'] ) ? $thumbnail_settings['wbm_thumb_border']['style'] : '' );
                $wbm_thumb_border_color = ( isset( $thumbnail_settings['wbm_thumb_border']['color'] ) ? $thumbnail_settings['wbm_thumb_border']['color'] : '' );
                $wbm_thumb_border_hov_color = ( isset( $thumbnail_settings['wbm_thumb_border']['hover_color'] ) ? $thumbnail_settings['wbm_thumb_border']['hover_color'] : '' );
                $wbm_thumb_top_margin = ( isset( $thumbnail_settings['wbm_thumb_margin']['top'] ) ? $thumbnail_settings['wbm_thumb_margin']['top'] : '' );
                $wbm_thumb_right_margin = ( isset( $thumbnail_settings['wbm_thumb_margin']['right'] ) ? $thumbnail_settings['wbm_thumb_margin']['right'] : '' );
                $wbm_thumb_bottom_margin = ( isset( $thumbnail_settings['wbm_thumb_margin']['bottom'] ) ? $thumbnail_settings['wbm_thumb_margin']['bottom'] : '' );
                $wbm_thumb_left_margin = ( isset( $thumbnail_settings['wbm_thumb_margin']['left'] ) ? $thumbnail_settings['wbm_thumb_margin']['left'] : '' );
                $wbm_thumb_margin_unit = ( isset( $thumbnail_settings['wbm_thumb_margin']['unit'] ) ? $thumbnail_settings['wbm_thumb_margin']['unit'] : '' );
                $wbm_cat_thumb_mode = ( isset( $thumbnail_settings['wbm_cat_thumb_mode'] ) ? $thumbnail_settings['wbm_cat_thumb_mode'] : '' );
                $wbm_cat_thumb_zoom = ( isset( $thumbnail_settings['wbm_cat_thumb_zoom'] ) ? $thumbnail_settings['wbm_cat_thumb_zoom'] : '' );
                $wbm_cat_thumbnail_shape = '';
                $wbm_cat_thumb_border_radius = '';
                $wbm_thumb_shadow_v_offset = '';
                $wbm_thumb_shadow_h_offset = '';
                $wbm_thumb_shadow_blur = '';
                $wbm_thumb_shadow_spread = '';
                $wbm_thumb_shadow_shadow = '';
                $wbm_thumb_shadow_color = '';
                $wbm_thumb_shadow_hover_color = '';
            }
        }
        if ( isset( $wbm_category_slider_sliders_meta ) && !empty($wbm_category_slider_sliders_meta) ) {
            foreach ( $wbm_category_slider_sliders_meta as $key => $sliders_settings ) {
                $wbm_cat_autoplay = ( isset( $sliders_settings['wbm_cat_autoplay'] ) ? $sliders_settings['wbm_cat_autoplay'] : '' );
                $wbm_cat_autoplay_speed = ( isset( $sliders_settings['wbm_cat_autoplay_speed'] ) ? $sliders_settings['wbm_cat_autoplay_speed'] : '' );
                $wbm_cat_scroll_speed = ( isset( $sliders_settings['wbm_cat_scroll_speed'] ) ? $sliders_settings['wbm_cat_scroll_speed'] : '' );
                $wbm_cat_pause_on_hov = ( isset( $sliders_settings['wbm_cat_pause_on_hov'] ) ? $sliders_settings['wbm_cat_pause_on_hov'] : '' );
                $wbm_cat_infinite_loop = ( isset( $sliders_settings['wbm_cat_infinite_loop'] ) ? $sliders_settings['wbm_cat_infinite_loop'] : '' );
                $wbm_cat_auto_height = ( isset( $sliders_settings['wbm_cat_auto_height'] ) ? $sliders_settings['wbm_cat_auto_height'] : '' );
                $wbm_cat_nav_status = ( isset( $sliders_settings['wbm_cat_nav_status'] ) ? $sliders_settings['wbm_cat_nav_status'] : '' );
                $wbm_cat_nav_color = ( isset( $sliders_settings['wbm_cat_nav_color']['color'] ) ? $sliders_settings['wbm_cat_nav_color']['color'] : '' );
                $wbm_cat_nav_hov_color = ( isset( $sliders_settings['wbm_cat_nav_color']['hover_color'] ) ? $sliders_settings['wbm_cat_nav_color']['hover_color'] : '' );
                $wbm_cat_nav_bg_color = ( isset( $sliders_settings['wbm_cat_nav_color']['bg_color'] ) ? $sliders_settings['wbm_cat_nav_color']['bg_color'] : '' );
                $wbm_cat_nav_hov_bg_color = ( isset( $sliders_settings['wbm_cat_nav_color']['bg_hover_color'] ) ? $sliders_settings['wbm_cat_nav_color']['bg_hover_color'] : '' );
                $wbm_cat_nav_all_border = ( isset( $sliders_settings['wbm_cat_nav_border']['all'] ) ? $sliders_settings['wbm_cat_nav_border']['all'] : '' );
                $wbm_cat_nav_border_style = ( isset( $sliders_settings['wbm_cat_nav_border']['style'] ) ? $sliders_settings['wbm_cat_nav_border']['style'] : '' );
                $wbm_cat_nav_border_color = ( isset( $sliders_settings['wbm_cat_nav_border']['color'] ) ? $sliders_settings['wbm_cat_nav_border']['color'] : '' );
                $wbm_cat_nav_border_hov_color = ( isset( $sliders_settings['wbm_cat_nav_border']['hover_color'] ) ? $sliders_settings['wbm_cat_nav_border']['hover_color'] : '' );
                $wbm_cat_pager_status = ( isset( $sliders_settings['wbm_cat_pager_status'] ) ? $sliders_settings['wbm_cat_pager_status'] : '' );
                $wbm_cat_pager_color = ( isset( $sliders_settings['wbm_cat_pager_color']['color'] ) ? $sliders_settings['wbm_cat_pager_color']['color'] : '' );
                $wbm_cat_pager_active_color = ( isset( $sliders_settings['wbm_cat_pager_color']['active_color'] ) ? $sliders_settings['wbm_cat_pager_color']['active_color'] : '' );
                $wbm_cat_pager_hov_color = ( isset( $sliders_settings['wbm_cat_pager_color']['hover_color'] ) ? $sliders_settings['wbm_cat_pager_color']['hover_color'] : '' );
                $wbm_cat_slide_to_scroll_large_desktop = ( isset( $sliders_settings['wbm_cat_slide_to_scroll']['large_desktop'] ) ? $sliders_settings['wbm_cat_slide_to_scroll']['large_desktop'] : '' );
                $wbm_cat_slide_to_scroll_desktop = ( isset( $sliders_settings['wbm_cat_slide_to_scroll']['desktop'] ) ? $sliders_settings['wbm_cat_slide_to_scroll']['desktop'] : '' );
                $wbm_cat_slide_to_scroll_laptop = ( isset( $sliders_settings['wbm_cat_slide_to_scroll']['laptop'] ) ? $sliders_settings['wbm_cat_slide_to_scroll']['laptop'] : '' );
                $wbm_cat_slide_to_scroll_tablet = ( isset( $sliders_settings['wbm_cat_slide_to_scroll']['tablet'] ) ? $sliders_settings['wbm_cat_slide_to_scroll']['tablet'] : '' );
                $wbm_cat_slide_to_scroll_mobile = ( isset( $sliders_settings['wbm_cat_slide_to_scroll']['mobile'] ) ? $sliders_settings['wbm_cat_slide_to_scroll']['mobile'] : '' );
                $wbm_cat_touch_swipe = ( isset( $sliders_settings['wbm_cat_touch_swipe'] ) ? $sliders_settings['wbm_cat_touch_swipe'] : 'false' );
                $wbm_cat_mousewheel = ( isset( $sliders_settings['wbm_cat_mousewheel'] ) ? $sliders_settings['wbm_cat_mousewheel'] : 'false' );
                $wbm_cat_mouse_draggable = ( isset( $sliders_settings['wbm_cat_mouse_draggable'] ) ? $sliders_settings['wbm_cat_mouse_draggable'] : 'false' );
            }
        }
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
                $choos_pnc_cat = array_reverse( $wbm_choose_categories_pnc_cat );
                $removed_categories = array_splice( $choos_pnc_cat, count( $choos_pnc_cat ) - $wbm_total_cat_show, $wbm_total_cat_show );
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
        ob_start();
        ?>
		<div id="dswbm-sliders-<?php 
        esc_attr_e( $post_id, 'banner-management-for-woocommerce' );
        ?>" class="wbm-category-slider-section dswbm-sliders-main <?php 
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
        ?>" show-controls="<?php 
        esc_attr_e( $wbm_cat_nav_status, 'banner-management-for-woocommerce' );
        ?>" show-pager="<?php 
        esc_attr_e( $wbm_cat_pager_status, 'banner-management-for-woocommerce' );
        ?>" slide-space="<?php 
        esc_attr_e( $wbm_cat_space_between, 'banner-management-for-woocommerce' );
        ?>" slider-large-desk="<?php 
        esc_attr_e( $no_cols_large_desktop, 'banner-management-for-woocommerce' );
        ?>" slider-desk="<?php 
        esc_attr_e( $no_cols_desktop, 'banner-management-for-woocommerce' );
        ?>" slider-laptop="<?php 
        esc_attr_e( $no_cols_laptop, 'banner-management-for-woocommerce' );
        ?>" slider-tablet="<?php 
        esc_attr_e( $no_cols_tablet, 'banner-management-for-woocommerce' );
        ?>" slider-mobile="<?php 
        esc_attr_e( $no_cols_mobile, 'banner-management-for-woocommerce' );
        ?>" slider-mode="<?php 
        esc_attr_e( $dswbm_slider_mode, 'banner-management-for-woocommerce' );
        ?>" slider-layout-preset="<?php 
        esc_attr_e( $wbm_layout_presets, 'banner-management-for-woocommerce' );
        ?>"
		slider-to-scroll-large-desktop = "<?php 
        esc_attr_e( $wbm_cat_slide_to_scroll_large_desktop, 'banner-management-for-woocommerce' );
        ?>" 
		slider-to-scroll-desktop = "<?php 
        esc_attr_e( $wbm_cat_slide_to_scroll_desktop, 'banner-management-for-woocommerce' );
        ?>" 
		slider-to-scroll-lapto = "<?php 
        esc_attr_e( $wbm_cat_slide_to_scroll_laptop, 'banner-management-for-woocommerce' );
        ?>" 
		slider-to-scroll-tablet = "<?php 
        esc_attr_e( $wbm_cat_slide_to_scroll_tablet, 'banner-management-for-woocommerce' );
        ?>" 
		slider-to-scroll-mobile = "<?php 
        esc_attr_e( $wbm_cat_slide_to_scroll_mobile, 'banner-management-for-woocommerce' );
        ?>"
		slider-touch-status = "<?php 
        esc_attr_e( $wbm_cat_touch_swipe, 'banner-management-for-woocommerce' );
        ?>"
		slider-mousewheel-status = "<?php 
        esc_attr_e( $wbm_cat_mousewheel, 'banner-management-for-woocommerce' );
        ?>"
		slider-mouse_draggable-status = "<?php 
        esc_attr_e( $wbm_cat_mouse_draggable, 'banner-management-for-woocommerce' );
        ?>">
		

			<?php 
        
        if ( isset( $wbm_cat_title_typo_font_family ) && !empty($wbm_cat_title_typo_font_family) && 'none' !== $wbm_cat_title_typo_font_family ) {
            ?>
				<link href='https://fonts.googleapis.com/css?family=<?php 
            echo  esc_attr( $wbm_cat_title_typo_font_family ) ;
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
            echo  esc_attr( $wbm_cat_title_typo_shopbtn_font_family ) ;
            ?>' rel='stylesheet' type="text/css"> <?php 
            // phpcs:ignore
            ?>
			<?php 
        }
        
        ?>
			<style>
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .bx-wrapper .bx-controls-direction a i {
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
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .bx-wrapper .bx-controls-direction a i:hover {
        			color: <?php 
        esc_html_e( $wbm_cat_nav_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        			background-color: <?php 
        esc_html_e( $wbm_cat_nav_hov_bg_color, 'banner-management-for-woocommerce' );
        ?>;
        			border-color: <?php 
        esc_html_e( $wbm_cat_nav_border_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .bx-wrapper .bx-pager .bx-pager-item a {
        			background-color: <?php 
        esc_html_e( $wbm_cat_pager_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .bx-wrapper .bx-pager .bx-pager-item a:hover {
        			background-color: <?php 
        esc_html_e( $wbm_cat_pager_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .bx-wrapper .bx-pager .bx-pager-item a.active {
        			background-color: <?php 
        esc_html_e( $wbm_cat_pager_active_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .slider-section-title {
        			margin-top: <?php 
        esc_html_e( $sec_title_top_margin . $sec_title_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-bottom: <?php 
        esc_html_e( $sec_title_bottom_margin . $sec_title_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-right: <?php 
        esc_html_e( $sec_title_right_margin . $sec_title_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-left: <?php 
        esc_html_e( $sec_title_left_margin . $sec_title_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .slider-section-title h3 {
        			color: <?php 
        esc_html_e( $wbm_cat_title_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .term-content h3 {
        			margin-top: <?php 
        esc_html_e( $wbm_cat_name_top_margin . $wbm_cat_name_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-bottom: <?php 
        esc_html_e( $wbm_cat_name_bottom_margin . $wbm_cat_name_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-right: <?php 
        esc_html_e( $wbm_cat_name_right_margin . $wbm_cat_name_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-left: <?php 
        esc_html_e( $wbm_cat_name_left_margin . $wbm_cat_name_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .term-content h3 a {
        			color: <?php 
        esc_html_e( $wbm_cat_name_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .term-content h3 .prod-count {
        			color: <?php 
        esc_html_e( $wbm_prod_count_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .term-content .cat-desc {
    				color: <?php 
        esc_html_e( $wbm_cat_desc_color, 'banner-management-for-woocommerce' );
        ?>;
    				margin-top: <?php 
        esc_html_e( $wbm_cat_desc_top_margin . $wbm_cat_desc_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-bottom: <?php 
        esc_html_e( $wbm_cat_desc_bottom_margin . $wbm_cat_desc_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-right: <?php 
        esc_html_e( $wbm_cat_desc_right_margin . $wbm_cat_desc_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-left: <?php 
        esc_html_e( $wbm_cat_desc_left_margin . $wbm_cat_desc_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .term-content .shop-now-btn {
        			margin-top: <?php 
        esc_html_e( $wbm_shop_now_top_margin . $wbm_shop_now_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-bottom: <?php 
        esc_html_e( $wbm_shop_now_bottom_margin . $wbm_shop_now_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-right: <?php 
        esc_html_e( $wbm_shop_now_right_margin . $wbm_shop_now_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-left: <?php 
        esc_html_e( $wbm_shop_now_left_margin . $wbm_shop_now_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .term-content .shop-now-btn a {
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
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .term-content .shop-now-btn a:hover {
        			color: <?php 
        esc_html_e( $wbm_shop_now_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        			background-color: <?php 
        esc_html_e( $wbm_shop_now_hov_bg_color, 'banner-management-for-woocommerce' );
        ?>;
        			border-color: <?php 
        esc_html_e( $wbm_shop_now_border_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .term-list .term-img {
        			margin-top: <?php 
        esc_html_e( $wbm_thumb_top_margin . $wbm_thumb_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-bottom: <?php 
        esc_html_e( $wbm_thumb_bottom_margin . $wbm_thumb_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-right: <?php 
        esc_html_e( $wbm_thumb_right_margin . $wbm_thumb_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-left: <?php 
        esc_html_e( $wbm_thumb_left_margin . $wbm_thumb_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			<?php 
        
        if ( isset( $wbm_cat_thumb_style ) && 'thumb_border' === $wbm_cat_thumb_style ) {
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
        ?>
				<?php 
        
        if ( 'slider' !== $wbm_layout_presets ) {
            ?>
					@media only screen and (min-width:1280px){
						#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?>.layout-present-grid ul.wbm-slider li {
							margin: 0 10px;
							width: calc(80% / <?php 
            echo  esc_attr( $no_cols_large_desktop ) ;
            ?>);
						}
					}
					@media only screen and (min-width:981px) and (max-width:1279px){
						#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?>.layout-present-grid ul.wbm-slider li {
							margin: 0 10px;
							width: calc(80% / <?php 
            echo  esc_attr( $no_cols_desktop ) ;
            ?>);
						}
					}
					@media only screen and (min-width:737px) and (max-width:980px){
						#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?>.layout-present-grid ul.wbm-slider li {
							margin: 0 10px;
							width: calc(80% / <?php 
            echo  esc_attr( $no_cols_laptop ) ;
            ?>);
						}
					}
					@media only screen and (min-width:481px) and (max-width:737px){
						#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?>.layout-present-grid ul.wbm-slider li {
							margin: 0 10px;
							width: calc(80% / <?php 
            echo  esc_attr( $no_cols_tablet ) ;
            ?>);
						}
					}
					@media only screen and (max-width:480px){
						#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?>.layout-present-grid ul.wbm-slider li {
							margin: 0 10px;
							width: calc(80% / <?php 
            echo  esc_attr( $no_cols_mobile ) ;
            ?>);
						}
					}
					<?php 
        }
        
        ?>
        		<?php 
        
        if ( isset( $wbm_cat_thumb_style ) && 'thumb_border' === $wbm_cat_thumb_style ) {
            ?>
    				#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?> .term-list .term-img:hover {
	        			border-color: <?php 
            esc_html_e( $wbm_thumb_border_hov_color, 'banner-management-for-woocommerce' );
            ?>;
	        		}
        			<?php 
        }
        
        
        if ( 'on-hover' === $wbm_cat_thumb_mode ) {
            ?>
    				#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?> .term-list .term-img img:hover {
        			    filter: grayscale(1);
	        		}
        			<?php 
        }
        
        
        if ( 'normal-to-grayscale' === $wbm_cat_thumb_mode ) {
            ?>
    				#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?> .term-list .term-img img {
        			    filter: grayscale(1);
	        		}
					#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?> .term-list .term-img img:hover {
        			    filter: none;
	        		}
        			<?php 
        }
        
        
        if ( 'always-grayscale' === $wbm_cat_thumb_mode ) {
            ?>
    				#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?> .term-list .term-img img {
        			    filter: grayscale(1);
	        		}
        			<?php 
        }
        
        
        if ( isset( $wbm_cat_make_card_style ) && 'on' === $wbm_cat_make_card_style ) {
            ?>
    				#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?> .term-content {
    					border-top-width: <?php 
            esc_html_e( $wbm_cat_card_top_border . 'px', 'banner-management-for-woocommerce' );
            ?>;
        				border-bottom-width: <?php 
            esc_html_e( $wbm_cat_card_bottom_border . 'px', 'banner-management-for-woocommerce' );
            ?>;
        				border-left-width: <?php 
            esc_html_e( $wbm_cat_card_left_border . 'px', 'banner-management-for-woocommerce' );
            ?>;
        				border-right-width: <?php 
            esc_html_e( $wbm_cat_card_right_border . 'px', 'banner-management-for-woocommerce' );
            ?>;
	        			border-style: <?php 
            esc_html_e( $wbm_cat_card_style_border, 'banner-management-for-woocommerce' );
            ?>;
	        			border-color: <?php 
            esc_html_e( $wbm_cat_card_border_color, 'banner-management-for-woocommerce' );
            ?>;
	        			padding-top: <?php 
            esc_html_e( $wbm_cat_inner_top_padding . $wbm_cat_inner_padding_unit, 'banner-management-for-woocommerce' );
            ?>;
	        			padding-bottom: <?php 
            esc_html_e( $wbm_cat_inner_bottom_padding . $wbm_cat_inner_padding_unit, 'banner-management-for-woocommerce' );
            ?>;
	        			padding-right: <?php 
            esc_html_e( $wbm_cat_inner_right_padding . $wbm_cat_inner_padding_unit, 'banner-management-for-woocommerce' );
            ?>;
	        			padding-left: <?php 
            esc_html_e( $wbm_cat_inner_left_padding . $wbm_cat_inner_padding_unit, 'banner-management-for-woocommerce' );
            ?>;
	        			background-color: <?php 
            esc_html_e( $wbm_cat_card_bg_color, 'banner-management-for-woocommerce' );
            ?>;
    				}
    				<?php 
        }
        
        
        if ( isset( $wbm_cat_make_card_style ) && 'on' === $wbm_cat_make_card_style ) {
            ?>
	        		#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?> .term-content:hover {
	        			border-color: <?php 
            esc_html_e( $wbm_cat_card_border_hov_color, 'banner-management-for-woocommerce' );
            ?>;
	        			background-color: <?php 
            esc_html_e( $wbm_cat_card_bg_hov_color, 'banner-management-for-woocommerce' );
            ?>;
	        		}
	        		<?php 
        }
        
        
        if ( isset( $wbm_cat_thumb_zoom ) && 'zoom-in' === $wbm_cat_thumb_zoom ) {
            
            if ( $wbm_cat_content_position === 'cont-over-thumb' ) {
                ?>
						#dswbm-sliders-<?php 
                esc_html_e( $post_id, 'banner-management-for-woocommerce' );
                ?> .term-list:hover .term-img img{
							-webkit-transform: scale(1.2);
							-moz-transform: scale(1.2);
							transform: scale(1.2);
						}
						<?php 
            } else {
                ?>
						#dswbm-sliders-<?php 
                esc_html_e( $post_id, 'banner-management-for-woocommerce' );
                ?> .term-list .term-img:hover img{
							-webkit-transform: scale(1.2);
							-moz-transform: scale(1.2);
							transform: scale(1.2);
						}
						<?php 
            }
        
        } elseif ( isset( $wbm_cat_thumb_zoom ) && 'zoom-out' === $wbm_cat_thumb_zoom ) {
            ?>
					#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?>  .term-list .term-img img{
						-webkit-transform: scale3d(1.2,1.2,1);
						-moz-transform: scale3d(1.2,1.2,1);
						transform: scale3d(1.2,1.2,1);
					}
					<?php 
            
            if ( $wbm_cat_content_position === 'cont-over-thumb' ) {
                ?>
						#dswbm-sliders-<?php 
                esc_html_e( $post_id, 'banner-management-for-woocommerce' );
                ?>  .term-list:hover .term-img img{
							-webkit-transform: none;
							-moz-transform: none;
							transform: none;
						}
						<?php 
            } else {
                ?>
						#dswbm-sliders-<?php 
                esc_html_e( $post_id, 'banner-management-for-woocommerce' );
                ?>  .term-list .term-img:hover img{
							-webkit-transform: none;
							-moz-transform: none;
							transform: none;
						}
						<?php 
            }
        
        }
        
        ?>
        	</style>
        	<?php 
        
        if ( isset( $wbm_cat_title ) && 'on' === $wbm_cat_title ) {
            ?>
				<div class="slider-section-title">
					<h3><?php 
            esc_html_e( $wbm_slider_rule_name, 'banner-management-for-woocommerce' );
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
		</div>
		<?php 
        return ob_get_clean();
    }

}

/** Added new shortcode for category list banner page */
add_shortcode( 'wcbm_product', 'wcbm_product_sliders_shortcode_callback_method' );
/**
 * Function For display the category sliders
 *
 * @since    2.3.0
 */
function wcbm_product_sliders_shortcode_callback_method( $atts )
{
    $atts = shortcode_atts( array(
        'id' => '',
    ), $atts, 'wcbm_product' );
    $wbm_slider_status = '';
    $wbm_slider_rule_name = '';
    $wbm_product_slider_general_meta = '';
    $wbm_product_slider_display_meta = '';
    $wbm_product_slider_sliders_meta = '';
    
    if ( isset( $atts['id'] ) && !empty($atts['id']) ) {
        $post_id = $atts['id'];
        $wbm_slider_status = get_post_status( $post_id );
        $wbm_slider_rule_name = __( get_the_title( $post_id ), 'banner-management-for-woocommerce' );
        $wbm_product_slider_general_meta = get_post_meta( $post_id, 'wbm_product_slider_general_meta', true );
        
        if ( is_serialized( $wbm_product_slider_general_meta ) ) {
            $wbm_product_slider_general_meta = maybe_unserialize( $wbm_product_slider_general_meta );
        } else {
            $wbm_product_slider_general_meta = $wbm_product_slider_general_meta;
        }
        
        $wbm_product_slider_display_meta = get_post_meta( $post_id, 'wbm_product_slider_display_meta', true );
        
        if ( is_serialized( $wbm_product_slider_display_meta ) ) {
            $wbm_product_slider_display_meta = maybe_unserialize( $wbm_product_slider_display_meta );
        } else {
            $wbm_product_slider_display_meta = $wbm_product_slider_display_meta;
        }
        
        $wbm_product_slider_sliders_meta = get_post_meta( $post_id, 'wbm_product_slider_sliders_meta', true );
        
        if ( is_serialized( $wbm_product_slider_sliders_meta ) ) {
            $wbm_product_slider_sliders_meta = maybe_unserialize( $wbm_product_slider_sliders_meta );
        } else {
            $wbm_product_slider_sliders_meta = $wbm_product_slider_sliders_meta;
        }
        
        $wbm_product_slider_typo_meta = get_post_meta( $post_id, 'wbm_product_slider_typo', true );
        
        if ( is_serialized( $wbm_product_slider_typo_meta ) ) {
            $wbm_product_slider_typo_meta = maybe_unserialize( $wbm_product_slider_typo_meta );
        } else {
            $wbm_product_slider_typo_meta = $wbm_product_slider_typo_meta;
        }
    
    }
    
    
    if ( isset( $wbm_slider_status ) && 'publish' === $wbm_slider_status ) {
        if ( isset( $wbm_product_slider_general_meta ) && !empty($wbm_product_slider_general_meta) ) {
            foreach ( $wbm_product_slider_general_meta as $key => $general_settings ) {
                $wbm_filter_products = ( isset( $general_settings['wbm_filter_products'] ) ? $general_settings['wbm_filter_products'] : '' );
                $wbm_choose_products = ( isset( $general_settings['wbm_choose_products'] ) ? $general_settings['wbm_choose_products'] : array() );
                $wbm_choose_by_cat = ( isset( $general_settings['wbm_choose_by_cat'] ) ? $general_settings['wbm_choose_by_cat'] : array() );
                $wbm_featured_products = ( isset( $general_settings['wbm_featured_products'] ) ? $general_settings['wbm_featured_products'] : array() );
                $wbm_total_prod_show = ( isset( $general_settings['wbm_total_prod_show'] ) ? $general_settings['wbm_total_prod_show'] : '' );
                $wbm_prod_order_by = ( isset( $general_settings['wbm_prod_order_by'] ) ? $general_settings['wbm_prod_order_by'] : '' );
                $wbm_prod_order = ( isset( $general_settings['wbm_prod_order'] ) ? $general_settings['wbm_prod_order'] : '' );
                $no_cols_large_desktop = ( isset( $general_settings['wbm_prod_no_of_cols']['large_desktop'] ) ? $general_settings['wbm_prod_no_of_cols']['large_desktop'] : '' );
                $no_cols_desktop = ( isset( $general_settings['wbm_prod_no_of_cols']['desktop'] ) ? $general_settings['wbm_prod_no_of_cols']['desktop'] : '' );
                $no_cols_laptop = ( isset( $general_settings['wbm_prod_no_of_cols']['laptop'] ) ? $general_settings['wbm_prod_no_of_cols']['laptop'] : '' );
                $no_cols_tablet = ( isset( $general_settings['wbm_prod_no_of_cols']['tablet'] ) ? $general_settings['wbm_prod_no_of_cols']['tablet'] : '' );
                $no_cols_mobile = ( isset( $general_settings['wbm_prod_no_of_cols']['mobile'] ) ? $general_settings['wbm_prod_no_of_cols']['mobile'] : '' );
                $dswbm_slider_mode = ( isset( $general_settings['dswbm_slider_mode'] ) ? $general_settings['dswbm_slider_mode'] : '' );
                $wbm_exclude_products = '';
            }
        }
        if ( isset( $wbm_product_slider_display_meta ) && !empty($wbm_product_slider_display_meta) ) {
            foreach ( $wbm_product_slider_display_meta as $key => $display_settings ) {
                $wbm_prod_title = ( isset( $display_settings['wbm_prod_title'] ) ? $display_settings['wbm_prod_title'] : '' );
                $wbm_prod_title_color = ( isset( $display_settings['wbm_prod_title_color'] ) ? $display_settings['wbm_prod_title_color'] : '' );
                $sec_title_top_margin = ( isset( $display_settings['wbm_prod_title_margin']['top'] ) ? $display_settings['wbm_prod_title_margin']['top'] : '' );
                $sec_title_right_margin = ( isset( $display_settings['wbm_prod_title_margin']['right'] ) ? $display_settings['wbm_prod_title_margin']['right'] : '' );
                $sec_title_bottom_margin = ( isset( $display_settings['wbm_prod_title_margin']['bottom'] ) ? $display_settings['wbm_prod_title_margin']['bottom'] : '' );
                $sec_title_left_margin = ( isset( $display_settings['wbm_prod_title_margin']['left'] ) ? $display_settings['wbm_prod_title_margin']['left'] : '' );
                $sec_title_margin_unit = ( isset( $display_settings['wbm_prod_title_margin']['unit'] ) ? $display_settings['wbm_prod_title_margin']['unit'] : '' );
                $wbm_prod_space_between = ( isset( $display_settings['wbm_prod_space_between'] ) ? $display_settings['wbm_prod_space_between'] : '' );
            }
        }
        if ( isset( $wbm_product_slider_sliders_meta ) && !empty($wbm_product_slider_sliders_meta) ) {
            foreach ( $wbm_product_slider_sliders_meta as $key => $sliders_settings ) {
                $wbm_prod_autoplay = ( isset( $sliders_settings['wbm_prod_autoplay'] ) ? $sliders_settings['wbm_prod_autoplay'] : '' );
                $wbm_prod_autoplay_speed = ( isset( $sliders_settings['wbm_prod_autoplay_speed'] ) ? $sliders_settings['wbm_prod_autoplay_speed'] : '' );
                $wbm_prod_scroll_speed = ( isset( $sliders_settings['wbm_prod_scroll_speed'] ) ? $sliders_settings['wbm_prod_scroll_speed'] : '' );
                $wbm_prod_pause_on_hov = ( isset( $sliders_settings['wbm_prod_pause_on_hov'] ) ? $sliders_settings['wbm_prod_pause_on_hov'] : '' );
                $wbm_prod_infinite_loop = ( isset( $sliders_settings['wbm_prod_infinite_loop'] ) ? $sliders_settings['wbm_prod_infinite_loop'] : '' );
                $wbm_prod_auto_height = ( isset( $sliders_settings['wbm_prod_auto_height'] ) ? $sliders_settings['wbm_prod_auto_height'] : '' );
                $wbm_prod_nav_status = ( isset( $sliders_settings['wbm_prod_nav_status'] ) ? $sliders_settings['wbm_prod_nav_status'] : '' );
                $wbm_prod_nav_color = ( isset( $sliders_settings['wbm_prod_nav_color']['color'] ) ? $sliders_settings['wbm_prod_nav_color']['color'] : '' );
                $wbm_prod_nav_hov_color = ( isset( $sliders_settings['wbm_prod_nav_color']['hover_color'] ) ? $sliders_settings['wbm_prod_nav_color']['hover_color'] : '' );
                $wbm_prod_nav_bg_color = ( isset( $sliders_settings['wbm_prod_nav_color']['bg_color'] ) ? $sliders_settings['wbm_prod_nav_color']['bg_color'] : '' );
                $wbm_prod_nav_hov_bg_color = ( isset( $sliders_settings['wbm_prod_nav_color']['bg_hover_color'] ) ? $sliders_settings['wbm_prod_nav_color']['bg_hover_color'] : '' );
                $wbm_prod_nav_all_border = ( isset( $sliders_settings['wbm_prod_nav_border']['all'] ) ? $sliders_settings['wbm_prod_nav_border']['all'] : '' );
                $wbm_prod_nav_border_style = ( isset( $sliders_settings['wbm_prod_nav_border']['style'] ) ? $sliders_settings['wbm_prod_nav_border']['style'] : '' );
                $wbm_prod_nav_border_color = ( isset( $sliders_settings['wbm_prod_nav_border']['color'] ) ? $sliders_settings['wbm_prod_nav_border']['color'] : '' );
                $wbm_prod_nav_border_hov_color = ( isset( $sliders_settings['wbm_prod_nav_border']['hover_color'] ) ? $sliders_settings['wbm_prod_nav_border']['hover_color'] : '' );
                $wbm_prod_pager_status = ( isset( $sliders_settings['wbm_prod_pager_status'] ) ? $sliders_settings['wbm_prod_pager_status'] : '' );
                $wbm_prod_pager_color = ( isset( $sliders_settings['wbm_prod_pager_color']['color'] ) ? $sliders_settings['wbm_prod_pager_color']['color'] : '' );
                $wbm_prod_pager_active_color = ( isset( $sliders_settings['wbm_prod_pager_color']['active_color'] ) ? $sliders_settings['wbm_prod_pager_color']['active_color'] : '' );
                $wbm_prod_pager_hov_color = ( isset( $sliders_settings['wbm_prod_pager_color']['hover_color'] ) ? $sliders_settings['wbm_prod_pager_color']['hover_color'] : '' );
                $wbm_prod_thumb_zoom = ( isset( $sliders_settings['wbm_prod_thumb_zoom']['zoom'] ) ? $sliders_settings['wbm_prod_thumb_zoom']['zoom'] : '' );
                $wbm_thumb_shadow_v_offset = ( isset( $sliders_settings['wbm_prod_thum_shadow']['v-offset'] ) ? $sliders_settings['wbm_prod_thum_shadow']['v-offset'] : 0 );
                $wbm_thumb_shadow_h_offset = ( isset( $sliders_settings['wbm_prod_thum_shadow']['h-offset'] ) ? $sliders_settings['wbm_prod_thum_shadow']['h-offset'] : 0 );
                $wbm_thumb_shadow_blur = ( isset( $sliders_settings['wbm_prod_thum_shadow']['blur'] ) ? $sliders_settings['wbm_prod_thum_shadow']['blur'] : 0 );
                $wbm_thumb_shadow_spread = ( isset( $sliders_settings['wbm_prod_thum_shadow']['spread'] ) ? $sliders_settings['wbm_prod_thum_shadow']['spread'] : 0 );
                $wbm_thumb_shadow_style = ( isset( $sliders_settings['wbm_prod_thum_shadow']['shadow'] ) && 'outset' !== $sliders_settings['wbm_prod_thum_shadow']['shadow'] ? $sliders_settings['wbm_prod_thum_shadow']['shadow'] : '' );
                $wbm_thumb_shadow_color = ( isset( $sliders_settings['wbm_prod_thum_shadow']['color'] ) ? $sliders_settings['wbm_prod_thum_shadow']['color'] : '' );
                $wbm_thumb_shadow_hov_color = ( isset( $sliders_settings['wbm_prod_thum_shadow']['hover-color'] ) ? $sliders_settings['wbm_prod_thum_shadow']['hover-color'] : '' );
                $wbm_prod_large_desktop = ( isset( $sliders_settings['wbm_prod_slide_to_scroll']['large_desktop'] ) ? $sliders_settings['wbm_prod_slide_to_scroll']['large_desktop'] : '' );
                $wbm_prod_desktop = ( isset( $sliders_settings['wbm_prod_slide_to_scroll']['desktop'] ) ? $sliders_settings['wbm_prod_slide_to_scroll']['desktop'] : '' );
                $wbm_prod_laptop = ( isset( $sliders_settings['wbm_prod_slide_to_scroll']['laptop'] ) ? $sliders_settings['wbm_prod_slide_to_scroll']['laptop'] : '' );
                $wbm_prod_tablet = ( isset( $sliders_settings['wbm_prod_slide_to_scroll']['tablet'] ) ? $sliders_settings['wbm_prod_slide_to_scroll']['tablet'] : '' );
                $wbm_prod_mobile = ( isset( $sliders_settings['wbm_prod_slide_to_scroll']['mobile'] ) ? $sliders_settings['wbm_prod_slide_to_scroll']['mobile'] : '' );
                $wbm_prod_touch_swipe_status = ( isset( $sliders_settings['wbm_prod_touch_swipe_status'] ) ? $sliders_settings['wbm_prod_touch_swipe_status'] : '' );
                $wbm_prod_mousewheel_control_status = ( isset( $sliders_settings['wbm_prod_mousewheel_control_status'] ) ? $sliders_settings['wbm_prod_mousewheel_control_status'] : '' );
                $wbm_prod_mouse_draggable_status = ( isset( $sliders_settings['wbm_prod_mouse_draggable_status'] ) ? $sliders_settings['wbm_prod_mouse_draggable_status'] : '' );
                $wbm_prod_thumb_image_mode = '';
            }
        }
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
        if ( isset( $wbm_filter_products ) && 'wbm_featured_products' === $wbm_filter_products ) {
            if ( isset( $wbm_choose_by_cat ) ) {
                $args['post__in'] = $wbm_featured_products;
            }
        }
        if ( isset( $wbm_filter_products ) && 'wbm_exclude_product' === $wbm_filter_products ) {
            if ( isset( $wbm_exclude_products ) ) {
                $args['post__not_in'] = $wbm_exclude_products;
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
        ob_start();
        ?>
		<div id="dswbm-sliders-<?php 
        esc_attr_e( $post_id, 'banner-management-for-woocommerce' );
        ?>" class="wbm-product-slider-section dswbm-sliders-main woocommerce" auto-play="<?php 
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
        ?>" show-controls="<?php 
        esc_attr_e( $wbm_prod_nav_status, 'banner-management-for-woocommerce' );
        ?>" show-pager="<?php 
        esc_attr_e( $wbm_prod_pager_status, 'banner-management-for-woocommerce' );
        ?>" slide-space="<?php 
        esc_attr_e( $wbm_prod_space_between, 'banner-management-for-woocommerce' );
        ?>" slider-large-desk="<?php 
        esc_attr_e( $no_cols_large_desktop, 'banner-management-for-woocommerce' );
        ?>" slider-desk="<?php 
        esc_attr_e( $no_cols_desktop, 'banner-management-for-woocommerce' );
        ?>" slider-laptop="<?php 
        esc_attr_e( $no_cols_laptop, 'banner-management-for-woocommerce' );
        ?>" slider-tablet="<?php 
        esc_attr_e( $no_cols_tablet, 'banner-management-for-woocommerce' );
        ?>" slider-mobile="<?php 
        esc_attr_e( $no_cols_mobile, 'banner-management-for-woocommerce' );
        ?>" 
		slider-mode="<?php 
        esc_attr_e( $dswbm_slider_mode, 'banner-management-for-woocommerce' );
        ?>"
		slider-to-large-desktop="<?php 
        esc_attr_e( $wbm_prod_large_desktop, 'banner-management-for-woocommerce' );
        ?>"
		slider-to-desktop="<?php 
        esc_attr_e( $wbm_prod_desktop, 'banner-management-for-woocommerce' );
        ?>"
		slider-to-laptop="<?php 
        esc_attr_e( $wbm_prod_laptop, 'banner-management-for-woocommerce' );
        ?>"
		slider-to-tablet="<?php 
        esc_attr_e( $wbm_prod_tablet, 'banner-management-for-woocommerce' );
        ?>"
		slider-to-mobile="<?php 
        esc_attr_e( $wbm_prod_mobile, 'banner-management-for-woocommerce' );
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
			<style>
				#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .slider-section-title {
        			margin-top: <?php 
        esc_html_e( $sec_title_top_margin . $sec_title_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-bottom: <?php 
        esc_html_e( $sec_title_bottom_margin . $sec_title_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-right: <?php 
        esc_html_e( $sec_title_right_margin . $sec_title_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        			margin-left: <?php 
        esc_html_e( $sec_title_left_margin . $sec_title_margin_unit, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .slider-section-title h3 {
        			color: <?php 
        esc_html_e( $wbm_prod_title_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .bx-wrapper .bx-controls-direction a i {
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
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .bx-wrapper .bx-controls-direction a i:hover {
        			color: <?php 
        esc_html_e( $wbm_prod_nav_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        			background-color: <?php 
        esc_html_e( $wbm_prod_nav_hov_bg_color, 'banner-management-for-woocommerce' );
        ?>;
        			border-color: <?php 
        esc_html_e( $wbm_prod_nav_border_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .bx-wrapper .bx-pager .bx-pager-item a {
        			background-color: <?php 
        esc_html_e( $wbm_prod_pager_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .bx-wrapper .bx-pager .bx-pager-item a:hover {
        			background-color: <?php 
        esc_html_e( $wbm_prod_pager_hov_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
        		#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .bx-wrapper .bx-pager .bx-pager-item a.active {
        			background-color: <?php 
        esc_html_e( $wbm_prod_pager_active_color, 'banner-management-for-woocommerce' );
        ?>;
        		}
				<?php 
        
        if ( isset( $wbm_prod_thumb_zoom ) && 'zoom-in' === $wbm_prod_thumb_zoom ) {
            ?>
					#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?> .product a img:hover{
						-webkit-transform: scale(1.2);
						-moz-transform: scale(1.2);
						transform: scale(1.2);
					}
				<?php 
        } elseif ( isset( $wbm_prod_thumb_zoom ) && 'zoom-out' === $wbm_prod_thumb_zoom ) {
            ?>
					#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?> .product a img{
						-webkit-transform: scale3d(1.2,1.2,1);
						-moz-transform: scale3d(1.2,1.2,1);
						transform: scale3d(1.2,1.2,1);
					}
					#dswbm-sliders-<?php 
            esc_html_e( $post_id, 'banner-management-for-woocommerce' );
            ?> .product a img:hover{
						-webkit-transform: none;
						-moz-transform: none;
						transform: none;
					}
				<?php 
        }
        
        ?>
				#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .product{
					-webkit-box-shadow: <?php 
        echo  esc_attr( $wbm_thumb_shadow_style ) ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_v_offset ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_h_offset ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_blur ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_spread ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_color ) ;
        ?>;
					-moz-box-shadow: <?php 
        echo  esc_attr( $wbm_thumb_shadow_style ) ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_v_offset ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_h_offset ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_blur ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_spread ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_color ) ;
        ?>;
					box-shadow: <?php 
        echo  esc_attr( $wbm_thumb_shadow_style ) ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_v_offset ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_h_offset ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_blur ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_spread ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_color ) ;
        ?>;
				}

				#dswbm-sliders-<?php 
        esc_html_e( $post_id, 'banner-management-for-woocommerce' );
        ?> .product:hover{
					-webkit-box-shadow: <?php 
        echo  esc_attr( $wbm_thumb_shadow_style ) ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_v_offset ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_h_offset ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_blur ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_spread ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_hov_color ) ;
        ?>;
					-moz-box-shadow: <?php 
        echo  esc_attr( $wbm_thumb_shadow_style ) ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_v_offset ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_h_offset ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_blur ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_spread ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_hov_color ) ;
        ?>;
					box-shadow: <?php 
        echo  esc_attr( $wbm_thumb_shadow_style ) ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_v_offset ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_h_offset ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_blur ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_spread ) . 'px' ;
        ?> <?php 
        echo  esc_attr( $wbm_thumb_shadow_hov_color ) ;
        ?>;
				}
				<?php 
        ?>
			</style>
			<?php 
        
        if ( isset( $wbm_prod_title ) && 'on' === $wbm_prod_title ) {
            ?>
				<div class="slider-section-title">
					<h3><?php 
            esc_html_e( $wbm_slider_rule_name, 'banner-management-for-woocommerce' );
            ?></h3>
				</div>
				<?php 
        }
        
        
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
                    ?>
								<a href="<?php 
                    echo  esc_url( get_permalink( $product->get_id() ), 'banner-management-for-woocommerce' ) ;
                    ?>" data-quantity="1" class="button product_type_variable add_to_cart_button" data-product_id="<?php 
                    echo  esc_attr( $product->get_id(), 'banner-management-for-woocommerce' ) ;
                    ?>" data-product_sku="<?php 
                    echo  esc_attr( $product->get_sku(), 'banner-management-for-woocommerce' ) ;
                    ?>" rel="nofollow"><?php 
                    esc_html_e( 'Read more', 'banner-management-for-woocommerce' );
                    ?></a>
								<?php 
                } elseif ( $product->is_type( 'variable' ) ) {
                    ?>
								<a href="<?php 
                    echo  esc_url( get_permalink( $product->get_id() ), 'banner-management-for-woocommerce' ) ;
                    ?>" data-quantity="1" class="button product_type_variable add_to_cart_button" data-product_id="<?php 
                    echo  esc_attr( $product->get_id(), 'banner-management-for-woocommerce' ) ;
                    ?>" data-product_sku="<?php 
                    echo  esc_attr( $product->get_sku(), 'banner-management-for-woocommerce' ) ;
                    ?>" rel="nofollow"><?php 
                    esc_html_e( 'Select options', 'banner-management-for-woocommerce' );
                    ?></a>
								<?php 
                } elseif ( $product->is_type( 'simple' ) ) {
                    ?>
								<a href="<?php 
                    echo  esc_url( '?add-to-cart=' . $product->get_id(), 'banner-management-for-woocommerce' ) ;
                    ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php 
                    echo  esc_attr( $product->get_id(), 'banner-management-for-woocommerce' ) ;
                    ?>" data-product_sku="<?php 
                    echo  esc_attr( $product->get_sku(), 'banner-management-for-woocommerce' ) ;
                    ?>" rel="nofollow"><?php 
                    esc_html_e( 'Add to cart', 'banner-management-for-woocommerce' );
                    ?></a>
								<?php 
                } else {
                    ?>
								<a href="<?php 
                    echo  esc_url( get_permalink( $product->get_id() ), 'banner-management-for-woocommerce' ) ;
                    ?>" data-quantity="1" class="button add_to_cart_button" data-product_id="<?php 
                    echo  esc_attr( $product->get_id(), 'banner-management-for-woocommerce' ) ;
                    ?>" data-product_sku="<?php 
                    echo  esc_attr( $product->get_sku(), 'banner-management-for-woocommerce' ) ;
                    ?>" rel="nofollow"><?php 
                    esc_html_e( 'View products', 'banner-management-for-woocommerce' );
                    ?></a>
								<?php 
                }
                
                ?>
						</li>
						<?php 
            }
            ?>
        		</ul>
    			<?php 
        }
        
        ?>
		</div>
		<?php 
        return ob_get_clean();
    }

}
