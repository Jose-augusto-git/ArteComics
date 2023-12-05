<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to diplay the product sliders settings.
 *
 * @link       http://www.multidots.com/
 * @since      2.3.0
 *
 * @package    woocommerce_category_banner_management
 * @subpackage woocommerce_category_banner_management/admin/partials
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    exit;
}
$get_action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$get_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
/*
 * edit all posted data method define in class-woo-banner-management-admin
 */

if ( $get_action === 'edit' ) {
    
    if ( !empty($get_id) && $get_id !== "" ) {
        $get_post_id = ( isset( $get_id ) ? sanitize_text_field( wp_unslash( $get_id ) ) : '' );
        $dswbm_slider_status = get_post_status( $get_post_id );
        $dswbm_slider_rule_name = __( get_the_title( $get_post_id ), 'banner-management-for-woocommerce' );
        $wbm_product_slider_general_meta = get_post_meta( $get_post_id, 'wbm_product_slider_general_meta', true );
        
        if ( is_serialized( $wbm_product_slider_general_meta ) ) {
            $wbm_product_slider_general_meta = maybe_unserialize( $wbm_product_slider_general_meta );
        } else {
            $wbm_product_slider_general_meta = $wbm_product_slider_general_meta;
        }
        
        $wbm_product_slider_display_meta = get_post_meta( $get_post_id, 'wbm_product_slider_display_meta', true );
        
        if ( is_serialized( $wbm_product_slider_display_meta ) ) {
            $wbm_product_slider_display_meta = maybe_unserialize( $wbm_product_slider_display_meta );
        } else {
            $wbm_product_slider_display_meta = $wbm_product_slider_display_meta;
        }
        
        $wbm_product_slider_sliders_meta = get_post_meta( $get_post_id, 'wbm_product_slider_sliders_meta', true );
        
        if ( is_serialized( $wbm_product_slider_sliders_meta ) ) {
            $wbm_product_slider_sliders_meta = maybe_unserialize( $wbm_product_slider_sliders_meta );
        } else {
            $wbm_product_slider_sliders_meta = $wbm_product_slider_sliders_meta;
        }
        
        $wbm_product_slider_typo_meta = get_post_meta( $get_post_id, 'wbm_product_slider_typo', true );
        
        if ( is_serialized( $wbm_product_slider_typo_meta ) ) {
            $wbm_product_slider_typo_meta = maybe_unserialize( $wbm_product_slider_typo_meta );
        } else {
            $wbm_product_slider_typo_meta = $wbm_product_slider_typo_meta;
        }
        
        $title_text = esc_html__( 'Edit Slider', 'banner-management-for-woocommerce' );
    }

} else {
    $get_post_id = '';
    $dswbm_slider_status = '';
    $dswbm_slider_rule_name = '';
    $dswbm_slider_rule_name = ( !empty($dswbm_slider_rule_name) ? esc_attr( stripslashes( $dswbm_slider_rule_name ) ) : '' );
    $title_text = esc_html__( 'Add New Slider', 'banner-management-for-woocommerce' );
}

$dswbm_slider_status = ( !empty($dswbm_slider_status) && 'publish' === $dswbm_slider_status || empty($dswbm_slider_status) ? 'checked' : '' );
$dswbm_admin_object = new woocommerce_category_banner_management_Admin( '', '' );
?>
<div class="dswbm-slider-section-main">

	<h1 class="wp-heading-inline"><?php 
echo  esc_html__( $title_text, 'banner-management-for-woocommerce' ) ;
?></h1>
	<div class="dswbm_slider_rule_name">
		<input type="text" name="dswbm_prod_slider_rule_name" placeholder="<?php 
echo  esc_attr( 'Add slider title' ) ;
?>" value="<?php 
echo  esc_attr( $dswbm_slider_rule_name ) ;
?>" required>
	</div>
	<div class="wbm-sliders-settings">
		<div class="sliders-settings-tabs">
			<ul>
				<li class="wbm-slider-tab active-tab"><a href="#wbm-slider-section-1"><?php 
echo  esc_html__( "General Settings", 'banner-management-for-woocommerce' ) ;
?></a></li>
				<li class="wbm-slider-tab"><a href="#wbm-slider-section-2"><?php 
echo  esc_html__( "Display Options", 'banner-management-for-woocommerce' ) ;
?></a></li>
				<li class="wbm-slider-tab"><a href="#wbm-slider-section-4"><?php 
echo  esc_html__( "Thumbnail Settings", 'banner-management-for-woocommerce' ) ;
?></a></li>
				<li class="wbm-slider-tab"><a href="#wbm-slider-section-3"><?php 
echo  esc_html__( "Slider Settings", 'banner-management-for-woocommerce' ) ;
?></a></li>
				<?php 
?>
			</ul>
		</div>
		<div class="wbm-sliders-table">
			<div id="wbm-slider-section-1" class="wbm-slider-section">
				<table class="form-table table-outer genral-settings-tbl">
					<tbody>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="dswbm_prod_slider_status"><?php 
esc_html_e( 'Status', 'banner-management-for-woocommerce' );
?></label>
								<span class="banner-woocommerce-help-tip">
									<div class="alert-desc">
										<?php 
esc_html_e( 'Enable or Disable this slider using this button (This slider will work only if it is enabled).', 'banner-management-for-woocommerce' );
?>
									</div>
								</span>
							</th>
							<td class="forminp">
								<label class="dswbm_toggle_switch">
									<input type="checkbox" id="dswbm_prod_slider_status" name="dswbm_prod_slider_status" value="on" <?php 
echo  esc_attr( $dswbm_slider_status ) ;
?>>
									<span class="dswbm_toggle_btn"></span>
								</label>
							</td>
						</tr>
						<?php 

if ( isset( $wbm_product_slider_general_meta ) && !empty($wbm_product_slider_general_meta) ) {
    foreach ( $wbm_product_slider_general_meta as $key => $general_settings ) {
        $wbm_filter_products = ( isset( $general_settings['wbm_filter_products'] ) ? $general_settings['wbm_filter_products'] : '' );
        $wbm_choose_products = ( isset( $general_settings['wbm_choose_products'] ) ? $general_settings['wbm_choose_products'] : array() );
        $wbm_choose_by_cat = ( isset( $general_settings['wbm_choose_by_cat'] ) ? $general_settings['wbm_choose_by_cat'] : array() );
        $wbm_featured_products = ( isset( $general_settings['wbm_featured_products'] ) ? $general_settings['wbm_featured_products'] : array() );
        $wbm_exclude_products = ( isset( $general_settings['wbm_exclude_products'] ) ? $general_settings['wbm_exclude_products'] : array() );
        $wbm_total_prod_show = ( isset( $general_settings['wbm_total_prod_show'] ) ? $general_settings['wbm_total_prod_show'] : '' );
        $wbm_prod_order_by = ( isset( $general_settings['wbm_prod_order_by'] ) ? $general_settings['wbm_prod_order_by'] : '' );
        $wbm_prod_order = ( isset( $general_settings['wbm_prod_order'] ) ? $general_settings['wbm_prod_order'] : '' );
        $no_cols_large_desktop = ( isset( $general_settings['wbm_prod_no_of_cols']['large_desktop'] ) ? $general_settings['wbm_prod_no_of_cols']['large_desktop'] : '' );
        $no_cols_desktop = ( isset( $general_settings['wbm_prod_no_of_cols']['desktop'] ) ? $general_settings['wbm_prod_no_of_cols']['desktop'] : '' );
        $no_cols_laptop = ( isset( $general_settings['wbm_prod_no_of_cols']['laptop'] ) ? $general_settings['wbm_prod_no_of_cols']['laptop'] : '' );
        $no_cols_tablet = ( isset( $general_settings['wbm_prod_no_of_cols']['tablet'] ) ? $general_settings['wbm_prod_no_of_cols']['tablet'] : '' );
        $no_cols_mobile = ( isset( $general_settings['wbm_prod_no_of_cols']['mobile'] ) ? $general_settings['wbm_prod_no_of_cols']['mobile'] : '' );
        $dswbm_slider_mode = ( isset( $general_settings['dswbm_slider_mode'] ) && !empty($general_settings['dswbm_slider_mode']) ? $general_settings['dswbm_slider_mode'] : 'standard' );
        ?>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="dswbm_cat_slider_status"><?php 
        esc_html_e( 'Slider Mode', 'banner-management-for-woocommerce' );
        ?></label>
									</th>
									<td class="forminp">
										<div class="dswbm-siblings dswbm--button-group" data-multiple="">
											<div class="dswbm--button <?php 
        echo  ( 'standard' === $dswbm_slider_mode ? 'dswbm--active' : '' ) ;
        ?>">
												<input type="radio" name="wbm_product_slider_general[dswbm_slider_mode]" value="standard" data-depend-id="wcsp_slider_mode" <?php 
        echo  ( 'standard' === $dswbm_slider_mode ? 'checked' : '' ) ;
        ?>>
												<label><?php 
        esc_html_e( 'Standard', 'banner-management-for-woocommerce' );
        ?></label>
											</div>
											<div class="dswbm--button <?php 
        echo  ( 'ticker' === $dswbm_slider_mode ? 'dswbm--active' : '' ) ;
        ?>">
												<input type="radio" name="wbm_product_slider_general[dswbm_slider_mode]" value="ticker" data-depend-id="wcsp_slider_mode" <?php 
        echo  ( 'ticker' === $dswbm_slider_mode ? 'checked' : '' ) ;
        ?>>
												<label><?php 
        esc_html_e( 'Ticker', 'banner-management-for-woocommerce' );
        ?></label>
											</div>
										</div>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_filter_products"><?php 
        esc_html_e( 'Filter Products', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Select an option to filter the products.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<select name="wbm_product_slider_general[wbm_filter_products]" id="wbm_filter_products">
											<option value="wbm_all_products" <?php 
        echo  ( esc_attr( 'wbm_all_products' === $wbm_filter_products ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'All', 'banner-management-for-woocommerce' );
        ?></option>
											<option value="wbm_specific_products" <?php 
        echo  ( esc_attr( 'wbm_specific_products' === $wbm_filter_products ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Specific Product(s)', 'banner-management-for-woocommerce' );
        ?></option>
											<option value="wbm_featured_products" <?php 
        echo  ( esc_attr( 'wbm_featured_products' === $wbm_filter_products ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Featured Product(s)', 'banner-management-for-woocommerce' );
        ?></option>
											<?php 
        ?>
												<option value="wbm_by_categories" disabled><?php 
        esc_html_e( 'Specific Category(s) - (Pro)', 'banner-management-for-woocommerce' );
        ?></option>
												<option value="wbm_exclude_product" disabled><?php 
        esc_html_e( 'Exclude Product(s) - (Pro)', 'banner-management-for-woocommerce' );
        ?></option>
												<?php 
        ?>
										</select>
									</td>
								</tr>
								<tr class="wbm-select-featured-products">
									<th class="titledesc" scope="row">
										<label for="wbm_featured_products"><?php 
        esc_html_e( 'Choose Featured Product(s)', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Choose the featured specific product(s) to show.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<?php 
        ?>
										<select name="wbm_product_slider_general[wbm_featured_products][]" multiple="multiple" id="wbm_featured_products" class="wbm-multiselect" data-placeholder="<?php 
        esc_attr_e( 'Select a featured product', 'banner-management-for-woocommerce' );
        ?>">
											<?php 
        // query for get featured products list
        $meta_query = WC()->query->get_meta_query();
        $tax_query = WC()->query->get_tax_query();
        $tax_query[] = array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN',
        );
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => $meta_query,
            'tax_query'      => $tax_query,
        );
        $featured_query = new WP_Query( $args );
        if ( $featured_query->have_posts() ) {
            while ( $featured_query->have_posts() ) {
                $featured_query->the_post();
                ?>
												<option value="<?php 
                esc_attr_e( $featured_query->post->ID, 'banner-management-for-woocommerce' );
                ?>" <?php 
                echo  ( in_array( $featured_query->post->ID, $wbm_featured_products ) ? 'selected' : '' ) ;
                ?>><?php 
                esc_html_e( '#' . $featured_query->post->ID . ' ' . get_the_title(), 'banner-management-for-woocommerce' );
                ?></option>
											<?php 
            }
        }
        wp_reset_postdata();
        ?>
										</select>
									</td>
								</tr>
								<?php 
        ?>
								<tr valign="top" class="wbm-select-products">
									<th class="titledesc" scope="row">
										<label for="wbm_choose_products"><?php 
        esc_html_e( 'Choose Product(s)', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Choose the specific product(s) to show.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<select name="wbm_product_slider_general[wbm_choose_products][]" multiple="multiple" id="wbm_choose_products" class="wbm-multiselect" data-placeholder="<?php 
        esc_attr_e( 'Select a Product', 'banner-management-for-woocommerce' );
        ?>">
											<?php 
        // get woocommerce products list
        $get_prod_args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );
        $product_query = new WP_Query( $get_prod_args );
        if ( $product_query->have_posts() ) {
            while ( $product_query->have_posts() ) {
                $product_query->the_post();
                ?>
													<option value="<?php 
                esc_attr_e( $product_query->post->ID, 'banner-management-for-woocommerce' );
                ?>" <?php 
                echo  ( in_array( $product_query->post->ID, $wbm_choose_products ) ? 'selected' : '' ) ;
                ?>><?php 
                esc_html_e( '#' . $product_query->post->ID . ' ' . get_the_title(), 'banner-management-for-woocommerce' );
                ?></option>
													<?php 
            }
        }
        wp_reset_postdata();
        ?>
										</select>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_total_prod_show"><?php 
        esc_html_e( 'Total Products to Show', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Total number of products to display.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<input type="number" id="wbm_total_prod_show" min="1" name="wbm_product_slider_general[wbm_total_prod_show]" value="<?php 
        echo  esc_attr( $wbm_total_prod_show ) ;
        ?>">
									</td>
								</tr>
								<tr valign="top" class="wbm-multi-option-field">
									<th class="titledesc" scope="row">
										<label><?php 
        esc_html_e( 'Product Column(s)', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        $alert_desc = __( 'Set number of column(s) in different devices for responsive view.<p><span><i class="fa fa-television"></i>LARGE DESKTOP - Screens larger than 1280px.</span><span><i class="fa fa-desktop"></i>DESKTOP - Screens smaller than 1280px.</span><span><i class="fa fa-laptop"></i>LAPTOP - Screens smaller than 980px.</span><span><i class="fa fa-tablet-screen-button"></i>TABLET - Screens smaller than 736px.</span><span><i class="fa fa-mobile-screen-button"></i>MOBILE - Screens smaller than 480px.</span></p>', 'banner-management-for-woocommerce' );
        ?>
												<?php 
        echo  wp_kses( $alert_desc, $dswbm_admin_object->wcbm_allowed_html_tags() ) ;
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<div class="wbm-mo-parent">
											<div class="wbm-multi-option-input">
												<span class="wbm-multi-option-icon">
													<i class="fa fa-television"></i>
												</span>
												<input type="number" class="wbm_prod_no_of_cols" min="1" name="wbm_product_slider_general[wbm_prod_no_of_cols][large_desktop]" value="<?php 
        echo  esc_attr( $no_cols_large_desktop ) ;
        ?>">
											</div>
											<div class="wbm-multi-option-input">
												<span class="wbm-multi-option-icon">
													<i class="fa fa-desktop"></i>
												</span>
												<input type="number" class="wbm_prod_no_of_cols" min="1" name="wbm_product_slider_general[wbm_prod_no_of_cols][desktop]" value="<?php 
        echo  esc_attr( $no_cols_desktop ) ;
        ?>">
											</div>
											<div class="wbm-multi-option-input">
												<span class="wbm-multi-option-icon">
													<i class="fa fa-laptop"></i>
												</span>
												<input type="number" class="wbm_prod_no_of_cols" min="1" name="wbm_product_slider_general[wbm_prod_no_of_cols][laptop]" value="<?php 
        echo  esc_attr( $no_cols_laptop ) ;
        ?>">
											</div>
											<div class="wbm-multi-option-input">
												<span class="wbm-multi-option-icon">
													<i class="fa fa-tablet-screen-button"></i>
												</span>
												<input type="number" class="wbm_prod_no_of_cols" min="1" name="wbm_product_slider_general[wbm_prod_no_of_cols][tablet]" value="<?php 
        echo  esc_attr( $no_cols_tablet ) ;
        ?>">
											</div>
											<div class="wbm-multi-option-input">
												<span class="wbm-multi-option-icon">
													<i class="fa fa-mobile-screen-button"></i>
												</span>
												<input type="number" class="wbm_prod_no_of_cols" min="1" name="wbm_product_slider_general[wbm_prod_no_of_cols][mobile]" value="<?php 
        echo  esc_attr( $no_cols_mobile ) ;
        ?>">
											</div>
										</div>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_prod_order_by"><?php 
        esc_html_e( 'Order by', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Select an order by option.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<select name="wbm_product_slider_general[wbm_prod_order_by]" id="wbm_prod_order_by">
											<option value="ID" <?php 
        echo  ( esc_attr( 'ID' === $wbm_prod_order_by ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'ID', 'banner-management-for-woocommerce' );
        ?></option>
											<option value="title" <?php 
        echo  ( esc_attr( 'title' === $wbm_prod_order_by ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Name', 'banner-management-for-woocommerce' );
        ?></option>
											<option value="date" <?php 
        echo  ( esc_attr( 'date' === $wbm_prod_order_by ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Date', 'banner-management-for-woocommerce' );
        ?></option>
											<option value="modified" <?php 
        echo  ( esc_attr( 'modified' === $wbm_prod_order_by ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Modified', 'banner-management-for-woocommerce' );
        ?></option>
											<option value="rand" <?php 
        echo  ( esc_attr( 'rand' === $wbm_prod_order_by ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Random', 'banner-management-for-woocommerce' );
        ?></option>
											<option value="none" <?php 
        echo  ( esc_attr( 'none' === $wbm_prod_order_by ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'None', 'banner-management-for-woocommerce' );
        ?></option>
										</select>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_prod_order"><?php 
        esc_html_e( 'Order', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Select an order option.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<select name="wbm_product_slider_general[wbm_prod_order]" id="wbm_prod_order">
											<option value="ASC" <?php 
        echo  ( esc_attr( 'ASC' === $wbm_prod_order ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Ascending', 'banner-management-for-woocommerce' );
        ?></option>
											<option value="DESC" <?php 
        echo  ( esc_attr( 'DESC' === $wbm_prod_order ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Descending', 'banner-management-for-woocommerce' );
        ?></option>
										</select>
									</td>
								</tr>
								<?php 
    }
} else {
    ?>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="dswbm_cat_slider_status"><?php 
    esc_html_e( 'Slider Mode', 'banner-management-for-woocommerce' );
    ?></label>
								</th>
								<td class="forminp">
									<div class="dswbm-siblings dswbm--button-group" data-multiple="">
										<div class="dswbm--button dswbm--active">
											<input type="radio" name="wbm_product_slider_general[dswbm_slider_mode]" value="standard" data-depend-id="wcsp_slider_mode">
											<label><?php 
    esc_html_e( 'Standard', 'banner-management-for-woocommerce' );
    ?></label>
										</div>
										<div class="dswbm--button">
											<input type="radio" name="wbm_product_slider_general[dswbm_slider_mode]" value="ticker" data-depend-id="wcsp_slider_mode">
											<label><?php 
    esc_html_e( 'Ticker', 'banner-management-for-woocommerce' );
    ?></label>
										</div>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_filter_products"><?php 
    esc_html_e( 'Filter Products', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Select an option to filter the products.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<select name="wbm_product_slider_general[wbm_filter_products]" id="wbm_filter_products">
										<option value="wbm_all_products"><?php 
    esc_html_e( 'All', 'banner-management-for-woocommerce' );
    ?></option>
										<option value="wbm_specific_products"><?php 
    esc_html_e( 'Specific Product(s)', 'banner-management-for-woocommerce' );
    ?></option>
										<option value="wbm_featured_products"><?php 
    esc_html_e( 'Featured Product(s)', 'banner-management-for-woocommerce' );
    ?></option>
										<?php 
    ?>
											<option value="wbm_by_categories" disabled><?php 
    esc_html_e( 'Specific Category(s) - (Pro)', 'banner-management-for-woocommerce' );
    ?></option>
											<option value="wbm_exclude_product" disabled><?php 
    esc_html_e( 'Exclude Product(s) - (Pro)', 'banner-management-for-woocommerce' );
    ?></option>
											<?php 
    ?>
									</select>
								</td>
							</tr>
							<tr class="wbm-select-featured-products">
								<th class="titledesc" scope="row">
									<label for="wbm_featured_products"><?php 
    esc_html_e( 'Choose Featured Product(s)', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Choose the featured specific product(s) to show.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<?php 
    ?>
									<select name="wbm_product_slider_general[wbm_featured_products][]" multiple="multiple" id="wbm_featured_products" class="wbm-multiselect" data-placeholder="<?php 
    esc_attr_e( 'Select a featured product', 'banner-management-for-woocommerce' );
    ?>">
										<?php 
    // query for get featured products list
    $meta_query = WC()->query->get_meta_query();
    $tax_query = WC()->query->get_tax_query();
    $tax_query[] = array(
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => 'featured',
        'operator' => 'IN',
    );
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => $meta_query,
        'tax_query'      => $tax_query,
    );
    $featured_query = new WP_Query( $args );
    if ( $featured_query->have_posts() ) {
        while ( $featured_query->have_posts() ) {
            $featured_query->the_post();
            ?>
											<option value="<?php 
            esc_attr_e( $featured_query->post->ID, 'banner-management-for-woocommerce' );
            ?>"><?php 
            esc_html_e( '#' . $featured_query->post->ID . ' ' . get_the_title(), 'banner-management-for-woocommerce' );
            ?></option>
										<?php 
        }
    }
    wp_reset_postdata();
    ?>
									</select>
								</td>
							</tr>
							<?php 
    ?>
							<tr valign="top" class="wbm-select-products">
								<th class="titledesc" scope="row">
									<label for="wbm_choose_products"><?php 
    esc_html_e( 'Choose Product(s)', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Choose the specific product(s) to show.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<select name="wbm_product_slider_general[wbm_choose_products][]" multiple="multiple" id="wbm_choose_products" class="wbm-multiselect" data-placeholder="<?php 
    esc_attr_e( 'Select a Product', 'banner-management-for-woocommerce' );
    ?>">
										<?php 
    // get woocommerce products list
    $get_prod_args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    );
    $product_query = new WP_Query( $get_prod_args );
    if ( $product_query->have_posts() ) {
        while ( $product_query->have_posts() ) {
            $product_query->the_post();
            ?>
												<option value="<?php 
            esc_attr_e( $product_query->post->ID, 'banner-management-for-woocommerce' );
            ?>"><?php 
            esc_html_e( '#' . $product_query->post->ID . ' ' . get_the_title(), 'banner-management-for-woocommerce' );
            ?></option>
												<?php 
        }
    }
    wp_reset_postdata();
    ?>
									</select>
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_total_prod_show"><?php 
    esc_html_e( 'Total Products to Show', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Total number of products to display.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<input type="number" id="wbm_total_prod_show" min="1" name="wbm_product_slider_general[wbm_total_prod_show]" value="15">
								</td>
							</tr>
							<tr valign="top" class="wbm-multi-option-field">
								<th class="titledesc" scope="row">
									<label><?php 
    esc_html_e( 'Product Column(s)', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    $alert_desc = __( 'Set number of column(s) in different devices for responsive view.<p><span><i class="fa fa-television"></i>LARGE DESKTOP - Screens larger than 1280px.</span><span><i class="fa fa-desktop"></i>DESKTOP - Screens smaller than 1280px.</span><span><i class="fa fa-laptop"></i>LAPTOP - Screens smaller than 980px.</span><span><i class="fa fa-tablet-screen-button"></i>TABLET - Screens smaller than 736px.</span><span><i class="fa fa-mobile-screen-button"></i>MOBILE - Screens smaller than 480px.</span></p>', 'banner-management-for-woocommerce' );
    ?>
											<?php 
    echo  wp_kses( $alert_desc, $dswbm_admin_object->wcbm_allowed_html_tags() ) ;
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<div class="wbm-mo-parent">
										<div class="wbm-multi-option-input">
											<span class="wbm-multi-option-icon">
												<i class="fa fa-television"></i>
											</span>
											<input type="number" class="wbm_prod_no_of_cols" min="1" name="wbm_product_slider_general[wbm_prod_no_of_cols][large_desktop]" value="4">
										</div>
										<div class="wbm-multi-option-input">
											<span class="wbm-multi-option-icon">
												<i class="fa fa-desktop"></i>
											</span>
											<input type="number" class="wbm_prod_no_of_cols" min="1" name="wbm_product_slider_general[wbm_prod_no_of_cols][desktop]" value="3">
										</div>
										<div class="wbm-multi-option-input">
											<span class="wbm-multi-option-icon">
												<i class="fa fa-laptop"></i>
											</span>
											<input type="number" class="wbm_prod_no_of_cols" min="1" name="wbm_product_slider_general[wbm_prod_no_of_cols][laptop]" value="2">
										</div>
										<div class="wbm-multi-option-input">
											<span class="wbm-multi-option-icon">
												<i class="fa fa-tablet-screen-button"></i>
											</span>
											<input type="number" class="wbm_prod_no_of_cols" min="1" name="wbm_product_slider_general[wbm_prod_no_of_cols][tablet]" value="2">
										</div>
										<div class="wbm-multi-option-input">
											<span class="wbm-multi-option-icon">
												<i class="fa fa-mobile-screen-button"></i>
											</span>
											<input type="number" class="wbm_prod_no_of_cols" min="1" name="wbm_product_slider_general[wbm_prod_no_of_cols][mobile]" value="1">
										</div>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_prod_order_by"><?php 
    esc_html_e( 'Order by', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Select an order by option.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<select name="wbm_product_slider_general[wbm_prod_order_by]" id="wbm_prod_order_by">
										<option value="ID"><?php 
    esc_html_e( 'ID', 'banner-management-for-woocommerce' );
    ?></option>
										<option value="title"><?php 
    esc_html_e( 'Name', 'banner-management-for-woocommerce' );
    ?></option>
										<option value="date"><?php 
    esc_html_e( 'Date', 'banner-management-for-woocommerce' );
    ?></option>
										<option value="modified"><?php 
    esc_html_e( 'Modified', 'banner-management-for-woocommerce' );
    ?></option>
										<option value="rand"><?php 
    esc_html_e( 'Random', 'banner-management-for-woocommerce' );
    ?></option>
										<option value="none"><?php 
    esc_html_e( 'None', 'banner-management-for-woocommerce' );
    ?></option>
									</select>
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_prod_order"><?php 
    esc_html_e( 'Order', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Select an order option.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<select name="wbm_product_slider_general[wbm_prod_order]" id="wbm_prod_order">
										<option value="ASC"><?php 
    esc_html_e( 'Ascending', 'banner-management-for-woocommerce' );
    ?></option>
										<option value="DESC"><?php 
    esc_html_e( 'Descending', 'banner-management-for-woocommerce' );
    ?></option>
									</select>
								</td>
							</tr>
							<?php 
}

?>
					</tbody>
				</table>
			</div>
			<div id="wbm-slider-section-2" class="wbm-slider-section">
				<table class="form-table table-outer genral-settings-tbl">
					<tbody>
						<?php 

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
        ?>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_prod_title"><?php 
        esc_html_e( 'Section Title', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Show/Hide product slider\'s rule/section title.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<label class="dswbm_toggle_switch">
											<input type="checkbox" id="wbm_prod_title" name="wbm_product_slider_display[wbm_prod_title]" value="on" <?php 
        checked( $wbm_prod_title, 'on' );
        ?>>
											<span class="dswbm_toggle_btn"></span>
										</label>
									</td>
								</tr>
								<tr valign="top" class="wbm-prod-sec-title-field">
									<th class="titledesc" scope="row">
										<label for="wbm_prod_title_color"><?php 
        esc_html_e( 'Section Title Color', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Set color for product slider\'s rule/section title.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<input type="text" id="wbm_prod_title_color" class="wbm_sliders_colorpick" name="wbm_product_slider_display[wbm_prod_title_color]" value="<?php 
        esc_attr_e( $wbm_prod_title_color, 'banner-management-for-woocommerce' );
        ?>">
									</td>
								</tr>
								<tr valign="top" class="wbm-multi-option-field wbm-prod-sec-title-field">
									<th class="titledesc" scope="row">
										<label><?php 
        esc_html_e( 'Margin from Section Title', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Set margin for product slider\'s rule/section title.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<div class="wbm-mo-parent">
											<div class="wbm-multi-option-input">
												<span class="wbm-multi-option-icon">
													<i class="fa fa-long-arrow-up"></i>
												</span>
												<input type="number" class="wbm_prod_title_margin" min="0" name="wbm_product_slider_display[wbm_prod_title_margin][top]" value="<?php 
        echo  esc_attr( $sec_title_top_margin ) ;
        ?>">
											</div>
											<div class="wbm-multi-option-input">
												<span class="wbm-multi-option-icon">
													<i class="fa fa-long-arrow-right"></i>
												</span>
												<input type="number" class="wbm_prod_title_margin" min="0" name="wbm_product_slider_display[wbm_prod_title_margin][right]" value="<?php 
        echo  esc_attr( $sec_title_right_margin ) ;
        ?>">
											</div>
											<div class="wbm-multi-option-input">
												<span class="wbm-multi-option-icon">
													<i class="fa fa-long-arrow-down"></i>
												</span>
												<input type="number" class="wbm_prod_title_margin" min="0" name="wbm_product_slider_display[wbm_prod_title_margin][bottom]" value="<?php 
        echo  esc_attr( $sec_title_bottom_margin ) ;
        ?>">
											</div>
											<div class="wbm-multi-option-input">
												<span class="wbm-multi-option-icon">
													<i class="fa fa-long-arrow-left"></i>
												</span>
												<input type="number" class="wbm_prod_title_margin" min="0" name="wbm_product_slider_display[wbm_prod_title_margin][left]" value="<?php 
        echo  esc_attr( $sec_title_left_margin ) ;
        ?>">
											</div>
											<div class="wbm-multi-option-input">
												<select name="wbm_product_slider_display[wbm_prod_title_margin][unit]" id="pro_sec_title_margin_unit">
													<option value="px" <?php 
        echo  ( esc_attr( 'px' === $sec_title_margin_unit ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'px', 'banner-management-for-woocommerce' );
        ?></option>
													<option value="em" <?php 
        echo  ( esc_attr( 'em' === $sec_title_margin_unit ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'em', 'banner-management-for-woocommerce' );
        ?></option>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_prod_space_between"><?php 
        esc_html_e( 'Space Between Products (px)', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Set space between each slide.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<input type="number" id="wbm_prod_space_between" min="0" name="wbm_product_slider_display[wbm_prod_space_between]" value="<?php 
        echo  esc_attr( $wbm_prod_space_between ) ;
        ?>">
									</td>
								</tr>
								<?php 
    }
} else {
    ?>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_prod_title"><?php 
    esc_html_e( 'Section Title', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Show/Hide product slider\'s rule/section title.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<label class="dswbm_toggle_switch">
										<input type="checkbox" id="wbm_prod_title" name="wbm_product_slider_display[wbm_prod_title]" value="on">
										<span class="dswbm_toggle_btn"></span>
									</label>
								</td>
							</tr>
							<tr valign="top" class="wbm-prod-sec-title-field">
								<th class="titledesc" scope="row">
									<label for="wbm_prod_title_color"><?php 
    esc_html_e( 'Section Title Color', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Set color for product slider\'s rule/section title.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<input type="text" id="wbm_prod_title_color" class="wbm_sliders_colorpick" name="wbm_product_slider_display[wbm_prod_title_color]" value="">
								</td>
							</tr>
							<tr valign="top" class="wbm-multi-option-field wbm-prod-sec-title-field">
								<th class="titledesc" scope="row">
									<label><?php 
    esc_html_e( 'Margin from Section Title', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Set margin for product slider\'s rule/section title.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<div class="wbm-mo-parent">
										<div class="wbm-multi-option-input">
											<span class="wbm-multi-option-icon">
												<i class="fa fa-long-arrow-up"></i>
											</span>
											<input type="number" class="wbm_prod_title_margin" min="0" name="wbm_product_slider_display[wbm_prod_title_margin][top]" value="0">
										</div>
										<div class="wbm-multi-option-input">
											<span class="wbm-multi-option-icon">
												<i class="fa fa-long-arrow-right"></i>
											</span>
											<input type="number" class="wbm_prod_title_margin" min="0" name="wbm_product_slider_display[wbm_prod_title_margin][right]" value="0">
										</div>
										<div class="wbm-multi-option-input">
											<span class="wbm-multi-option-icon">
												<i class="fa fa-long-arrow-down"></i>
											</span>
											<input type="number" class="wbm_prod_title_margin" min="0" name="wbm_product_slider_display[wbm_prod_title_margin][bottom]" value="30">
										</div>
										<div class="wbm-multi-option-input">
											<span class="wbm-multi-option-icon">
												<i class="fa fa-long-arrow-left"></i>
											</span>
											<input type="number" class="wbm_prod_title_margin" min="0" name="wbm_product_slider_display[wbm_prod_title_margin][left]" value="0">
										</div>
										<div class="wbm-multi-option-input">
											<select name="wbm_product_slider_display[wbm_prod_title_margin][unit]" id="pro_sec_title_margin_unit">
												<option value="px"><?php 
    esc_html_e( 'px', 'banner-management-for-woocommerce' );
    ?></option>
												<option value="em"><?php 
    esc_html_e( 'em', 'banner-management-for-woocommerce' );
    ?></option>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_prod_space_between"><?php 
    esc_html_e( 'Space Between Products (px)', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Set space between each slide.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<input type="number" id="wbm_prod_space_between" min="0" name="wbm_product_slider_display[wbm_prod_space_between]" value="20">
								</td>
							</tr>
							<?php 
}

?>
					</tbody>
				</table>
			</div>
			<div id="wbm-slider-section-3" class="wbm-slider-section">
				<table class="form-table table-outer sliders-settings-tbl">
					<tbody>
						<?php 

if ( isset( $wbm_product_slider_sliders_meta ) && !empty($wbm_product_slider_sliders_meta) ) {
    foreach ( $wbm_product_slider_sliders_meta as $key => $sliders_settings ) {
        $wbm_prod_autoplay = ( isset( $sliders_settings['wbm_prod_autoplay'] ) ? $sliders_settings['wbm_prod_autoplay'] : '' );
        $wbm_prod_autoplay_speed = ( isset( $sliders_settings['wbm_prod_autoplay_speed'] ) ? $sliders_settings['wbm_prod_autoplay_speed'] : '' );
        $wbm_prod_scroll_speed = ( isset( $sliders_settings['wbm_prod_scroll_speed'] ) ? $sliders_settings['wbm_prod_scroll_speed'] : '' );
        $wbm_prod_pause_on_hov = ( isset( $sliders_settings['wbm_prod_pause_on_hov'] ) ? $sliders_settings['wbm_prod_pause_on_hov'] : '' );
        $wbm_prod_infinite_loop = ( isset( $sliders_settings['wbm_prod_infinite_loop'] ) ? $sliders_settings['wbm_prod_infinite_loop'] : '' );
        $wbm_prod_auto_height = ( isset( $sliders_settings['wbm_prod_auto_height'] ) ? $sliders_settings['wbm_prod_auto_height'] : '' );
        $wbm_cat_slide_to_scroll_large_desktop = ( isset( $sliders_settings['wbm_prod_slide_to_scroll']['large_desktop'] ) ? $sliders_settings['wbm_prod_slide_to_scroll']['large_desktop'] : '' );
        $wbm_cat_slide_to_scroll_desktop = ( isset( $sliders_settings['wbm_prod_slide_to_scroll']['desktop'] ) ? $sliders_settings['wbm_prod_slide_to_scroll']['desktop'] : '' );
        $wbm_cat_slide_to_scroll_laptop = ( isset( $sliders_settings['wbm_prod_slide_to_scroll']['laptop'] ) ? $sliders_settings['wbm_prod_slide_to_scroll']['laptop'] : '' );
        $wbm_cat_slide_to_scroll_tablet = ( isset( $sliders_settings['wbm_prod_slide_to_scroll']['tablet'] ) ? $sliders_settings['wbm_prod_slide_to_scroll']['tablet'] : '' );
        $wbm_cat_slide_to_scroll_mobile = ( isset( $sliders_settings['wbm_prod_slide_to_scroll']['mobile'] ) ? $sliders_settings['wbm_prod_slide_to_scroll']['mobile'] : '' );
        ?>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_prod_autoplay"><?php 
        esc_html_e( 'AutoPlay', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Enable/Disable auto play.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<label class="dswbm_toggle_switch">
											<input type="checkbox" id="wbm_prod_autoplay" name="wbm_product_slider_sliders[wbm_prod_autoplay]" value="true" <?php 
        checked( $wbm_prod_autoplay, 'true' );
        ?>>
											<span class="dswbm_toggle_btn"></span>
										</label>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_prod_autoplay_speed"><?php 
        esc_html_e( 'AutoPlay Speed (ms)', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Set auto play speed. Default value is 2000 milliseconds.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<input type="number" id="wbm_prod_autoplay_speed" min="1" name="wbm_product_slider_sliders[wbm_prod_autoplay_speed]" value="<?php 
        echo  esc_attr( $wbm_prod_autoplay_speed ) ;
        ?>">
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_prod_scroll_speed"><?php 
        esc_html_e( 'Scroll Speed (ms)', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Set pagination/slide scroll speed. Default value is 600 milliseconds.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<input type="number" id="wbm_prod_scroll_speed" min="1" name="wbm_product_slider_sliders[wbm_prod_scroll_speed]" value="<?php 
        echo  esc_attr( $wbm_prod_scroll_speed ) ;
        ?>">
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_prod_pause_on_hov"><?php 
        esc_html_e( 'Pause on Hover', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Enable/Disable slider pause on hover.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<label class="dswbm_toggle_switch">
											<input type="checkbox" id="wbm_prod_pause_on_hov" name="wbm_product_slider_sliders[wbm_prod_pause_on_hov]" value="true" <?php 
        checked( $wbm_prod_pause_on_hov, 'true' );
        ?>>
											<span class="dswbm_toggle_btn"></span>
										</label>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_prod_infinite_loop"><?php 
        esc_html_e( 'Infinite Loop', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Enable/Disable infinite loop mode.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<label class="dswbm_toggle_switch">
											<input type="checkbox" id="wbm_prod_infinite_loop" name="wbm_product_slider_sliders[wbm_prod_infinite_loop]" value="true" <?php 
        checked( $wbm_prod_infinite_loop, 'true' );
        ?>>
											<span class="dswbm_toggle_btn"></span>
										</label>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_prod_auto_height"><?php 
        esc_html_e( 'Auto Height', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Enable/Disable auto height.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<label class="dswbm_toggle_switch">
											<input type="checkbox" id="wbm_prod_auto_height" name="wbm_product_slider_sliders[wbm_prod_auto_height]" value="true" <?php 
        checked( $wbm_prod_auto_height, 'true' );
        ?>>
											<span class="dswbm_toggle_btn"></span>
										</label>
									</td>
								</tr>
								<tr valign="top" class="wbm-multi-option-field">
								<th class="titledesc" scope="row">
									<label><?php 
        esc_html_e( 'Slide To Scroll', 'banner-management-for-woocommerce' );
        ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
        esc_html_e( 'Set slide to scroll in different devices.', 'banner-management-for-woocommerce' );
        ?>
										</div>
									</span>
									<p class="description" style="display:none;"></p>
								</th>
								<td class="forminp">
								<div class="wbm-mo-parent">
									<div class="wbm-multi-option-input">
										<span class="wbm-multi-option-icon">
											<i class="fa fa-television"></i>
										</span>
										<input type="number" class="wbm_prod_slide_to_scroll" min="1" name="wbm_product_slider_sliders[wbm_prod_slide_to_scroll][large_desktop]" value="<?php 
        echo  esc_html( $wbm_cat_slide_to_scroll_large_desktop ) ;
        ?>">
									</div>
									<div class="wbm-multi-option-input">
										<span class="wbm-multi-option-icon">
											<i class="fa fa-desktop"></i>
										</span>
										<input type="number" class="wbm_prod_slide_to_scroll" min="1" name="wbm_product_slider_sliders[wbm_prod_slide_to_scroll][desktop]" value="<?php 
        echo  esc_html( $wbm_cat_slide_to_scroll_desktop ) ;
        ?>">
									</div>
									<div class="wbm-multi-option-input">
										<span class="wbm-multi-option-icon">
											<i class="fa fa-laptop"></i>
										</span>
										<input type="number" class="wbm_prod_slide_to_scroll" min="1" name="wbm_product_slider_sliders[wbm_prod_slide_to_scroll][laptop]" value="<?php 
        echo  esc_html( $wbm_cat_slide_to_scroll_laptop ) ;
        ?>">
									</div>
									<div class="wbm-multi-option-input">
										<span class="wbm-multi-option-icon">
											<i class="fa fa-tablet-screen-button"></i>
										</span>
										<input type="number" class="wbm_prod_slide_to_scroll" min="1" name="wbm_product_slider_sliders[wbm_prod_slide_to_scroll][tablet]" value="<?php 
        echo  esc_html( $wbm_cat_slide_to_scroll_tablet ) ;
        ?>">
									</div>
									<div class="wbm-multi-option-input">
										<span class="wbm-multi-option-icon">
											<i class="fa fa-mobile-screen-button"></i>
										</span>
										<input type="number" class="wbm_prod_slide_to_scroll" min="1" name="wbm_product_slider_sliders[wbm_prod_slide_to_scroll][mobile]" value="<?php 
        echo  esc_html( $wbm_cat_slide_to_scroll_mobile ) ;
        ?>">
									</div>
								</div>
								</td>
							</tr>
								<?php 
    }
} else {
    ?>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_prod_autoplay"><?php 
    esc_html_e( 'AutoPlay', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Enable/Disable auto play.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<label class="dswbm_toggle_switch">
										<input type="checkbox" id="wbm_prod_autoplay" name="wbm_product_slider_sliders[wbm_prod_autoplay]" value="true" checked>
										<span class="dswbm_toggle_btn"></span>
									</label>
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_prod_autoplay_speed"><?php 
    esc_html_e( 'AutoPlay Speed (ms)', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Set auto play speed. Default value is 2000 milliseconds.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<input type="number" id="wbm_prod_autoplay_speed" min="1" name="wbm_product_slider_sliders[wbm_prod_autoplay_speed]" value="2000">
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_prod_scroll_speed"><?php 
    esc_html_e( 'Scroll Speed (ms)', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Set pagination/slide scroll speed. Default value is 600 milliseconds.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<input type="number" id="wbm_prod_scroll_speed" min="1" name="wbm_product_slider_sliders[wbm_prod_scroll_speed]" value="600">
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_prod_pause_on_hov"><?php 
    esc_html_e( 'Pause on Hover', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Enable/Disable slider pause on hover.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<label class="dswbm_toggle_switch">
										<input type="checkbox" id="wbm_prod_pause_on_hov" name="wbm_product_slider_sliders[wbm_prod_pause_on_hov]" value="true" checked>
										<span class="dswbm_toggle_btn"></span>
									</label>
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_prod_infinite_loop"><?php 
    esc_html_e( 'Infinite Loop', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Enable/Disable infinite loop mode.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<label class="dswbm_toggle_switch">
										<input type="checkbox" id="wbm_prod_infinite_loop" name="wbm_product_slider_sliders[wbm_prod_infinite_loop]" value="true" checked>
										<span class="dswbm_toggle_btn"></span>
									</label>
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_prod_auto_height"><?php 
    esc_html_e( 'Auto Height', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Enable/Disable auto height.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<label class="dswbm_toggle_switch">
										<input type="checkbox" id="wbm_prod_auto_height" name="wbm_product_slider_sliders[wbm_prod_auto_height]" value="true" checked>
										<span class="dswbm_toggle_btn"></span>
									</label>
								</td>
							</tr>
							<tr valign="top" class="wbm-multi-option-field">
								<th class="titledesc" scope="row">
									<label><?php 
    esc_html_e( 'Slide To Scroll', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Set slide to scroll in different devices.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
									<p class="description" style="display:none;"></p>
								</th>
								<td class="forminp">
								<div class="wbm-mo-parent">
									<div class="wbm-multi-option-input">
										<span class="wbm-multi-option-icon">
											<i class="fa fa-television"></i>
										</span>
										<input type="number" class="wbm_prod_slide_to_scroll" min="1" name="wbm_product_slider_sliders[wbm_prod_slide_to_scroll][large_desktop]" value="1">
									</div>
									<div class="wbm-multi-option-input">
										<span class="wbm-multi-option-icon">
											<i class="fa fa-desktop"></i>
										</span>
										<input type="number" class="wbm_prod_slide_to_scroll" min="1" name="wbm_product_slider_sliders[wbm_prod_slide_to_scroll][desktop]" value="1">
									</div>
									<div class="wbm-multi-option-input">
										<span class="wbm-multi-option-icon">
											<i class="fa fa-laptop"></i>
										</span>
										<input type="number" class="wbm_prod_slide_to_scroll" min="1" name="wbm_product_slider_sliders[wbm_prod_slide_to_scroll][laptop]" value="1">
									</div>
									<div class="wbm-multi-option-input">
										<span class="wbm-multi-option-icon">
											<i class="fa fa-tablet-screen-button"></i>
										</span>
										<input type="number" class="wbm_prod_slide_to_scroll" min="1" name="wbm_product_slider_sliders[wbm_prod_slide_to_scroll][tablet]" value="1">
									</div>
									<div class="wbm-multi-option-input">
										<span class="wbm-multi-option-icon">
											<i class="fa fa-mobile-screen-button"></i>
										</span>
										<input type="number" class="wbm_prod_slide_to_scroll" min="1" name="wbm_product_slider_sliders[wbm_prod_slide_to_scroll][mobile]" value="1">
									</div>
								</div>
								</td>
							</tr>
							<?php 
}

?>
					</tbody>
				</table>
				<div class="wbm-slider-sub-field">
					<span><?php 
esc_html_e( 'Navigation Settings', 'banner-management-for-woocommerce' );
?></span>
				</div>
				<table class="form-table table-outer sliders-settings-tbl">
					<tbody>
						<?php 

if ( isset( $wbm_product_slider_sliders_meta ) && !empty($wbm_product_slider_sliders_meta) ) {
    foreach ( $wbm_product_slider_sliders_meta as $key => $sliders_settings ) {
        $wbm_prod_nav_status = ( isset( $sliders_settings['wbm_prod_nav_status'] ) ? $sliders_settings['wbm_prod_nav_status'] : '' );
        $wbm_prod_nav_color = ( isset( $sliders_settings['wbm_prod_nav_color']['color'] ) ? $sliders_settings['wbm_prod_nav_color']['color'] : '' );
        $wbm_prod_nav_hov_color = ( isset( $sliders_settings['wbm_prod_nav_color']['hover_color'] ) ? $sliders_settings['wbm_prod_nav_color']['hover_color'] : '' );
        $wbm_prod_nav_bg_color = ( isset( $sliders_settings['wbm_prod_nav_color']['bg_color'] ) ? $sliders_settings['wbm_prod_nav_color']['bg_color'] : '' );
        $wbm_prod_nav_hov_bg_color = ( isset( $sliders_settings['wbm_prod_nav_color']['bg_hover_color'] ) ? $sliders_settings['wbm_prod_nav_color']['bg_hover_color'] : '' );
        $wbm_prod_nav_all_border = ( isset( $sliders_settings['wbm_prod_nav_border']['all'] ) ? $sliders_settings['wbm_prod_nav_border']['all'] : '' );
        $wbm_prod_nav_border_style = ( isset( $sliders_settings['wbm_prod_nav_border']['style'] ) ? $sliders_settings['wbm_prod_nav_border']['style'] : '' );
        $wbm_prod_nav_border_color = ( isset( $sliders_settings['wbm_prod_nav_border']['color'] ) ? $sliders_settings['wbm_prod_nav_border']['color'] : '' );
        $wbm_prod_nav_border_hov_color = ( isset( $sliders_settings['wbm_prod_nav_border']['hover_color'] ) ? $sliders_settings['wbm_prod_nav_border']['hover_color'] : '' );
        ?>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label><?php 
        esc_html_e( 'Navigation', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Show/Hide slider navigation.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<span class="wbm_radio_toggle_btn">
											<label for="wbm_prod_nav_show">
												<input type="radio" id="wbm_prod_nav_show" name="wbm_product_slider_sliders[wbm_prod_nav_status]" value="true" <?php 
        checked( $wbm_prod_nav_status, 'true' );
        ?>><?php 
        esc_html_e( 'Show', 'banner-management-for-woocommerce' );
        ?>
											</label>
											<label for="wbm_prod_nav_hide">
												<input type="radio" id="wbm_prod_nav_hide" name="wbm_product_slider_sliders[wbm_prod_nav_status]" value="false" <?php 
        checked( $wbm_prod_nav_status, 'false' );
        ?>><?php 
        esc_html_e( 'Hide', 'banner-management-for-woocommerce' );
        ?>
											</label>
											<label for="wbm_prod_nav_hide_on_mobile">
												<input type="radio" id="wbm_prod_nav_hide_on_mobile" name="wbm_product_slider_sliders[wbm_prod_nav_status]" value="false-mobile" <?php 
        checked( $wbm_prod_nav_status, 'false-mobile' );
        ?>><?php 
        esc_html_e( 'Hide on Mobile', 'banner-management-for-woocommerce' );
        ?>
											</label>
										</span>
									</td>
								</tr>
								<tr valign="top" class="wbm-multi-option-field wbm-prod-nav-field">
									<th class="titledesc" scope="row">
										<label><?php 
        esc_html_e( 'Navigation Color', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Set color for the slider navigation.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
									<div class="wbm-mo-parent">
										<div class="wbm-multi-option-input">
											<p><?php 
        esc_html_e( 'Color', 'banner-management-for-woocommerce' );
        ?></p>
											<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_nav_color][color]" value="<?php 
        echo  esc_attr( $wbm_prod_nav_color ) ;
        ?>">
										</div>
										<div class="wbm-multi-option-input">
											<p><?php 
        esc_html_e( 'Hover Color', 'banner-management-for-woocommerce' );
        ?></p>
											<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_nav_color][hover_color]" value="<?php 
        echo  esc_attr( $wbm_prod_nav_hov_color ) ;
        ?>">
										</div>
										<div class="wbm-multi-option-input">
											<p><?php 
        esc_html_e( 'Background', 'banner-management-for-woocommerce' );
        ?></p>
											<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_nav_color][bg_color]" value="<?php 
        echo  esc_attr( $wbm_prod_nav_bg_color ) ;
        ?>">
										</div>
										<div class="wbm-multi-option-input">
											<p><?php 
        esc_html_e( 'Hover Background', 'banner-management-for-woocommerce' );
        ?></p>
											<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_nav_color][bg_hover_color]" value="<?php 
        echo  esc_attr( $wbm_prod_nav_hov_bg_color ) ;
        ?>">
										</div>
									</div>
									</td>
								</tr>
								<tr valign="top" class="wbm-multi-option-field wbm-prod-nav-field">
									<th class="titledesc" scope="row">
										<label><?php 
        esc_html_e( 'Navigation Border', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Set border for the slider navigation.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
									<div class="wbm-mo-parent">
										<div class="wbm-multi-option-input">
											<span class="wbm-multi-option-icon">
												<i class="fa fa-arrows"></i>
											</span>
											<input type="number" class="wbm_prod_nav_border" min="0" name="wbm_product_slider_sliders[wbm_prod_nav_border][all]" value="<?php 
        echo  esc_attr( $wbm_prod_nav_all_border ) ;
        ?>">
										</div>
										<div class="wbm-multi-option-input">
											<select name="wbm_product_slider_sliders[wbm_prod_nav_border][style]">
												<option value="solid" <?php 
        echo  ( esc_attr( 'solid' === $wbm_prod_nav_border_style ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Solid', 'banner-management-for-woocommerce' );
        ?></option>
												<option value="dashed" <?php 
        echo  ( esc_attr( 'dashed' === $wbm_prod_nav_border_style ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Dashed', 'banner-management-for-woocommerce' );
        ?></option>
												<option value="dotted" <?php 
        echo  ( esc_attr( 'dotted' === $wbm_prod_nav_border_style ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Dotted', 'banner-management-for-woocommerce' );
        ?></option>
												<option value="double" <?php 
        echo  ( esc_attr( 'double' === $wbm_prod_nav_border_style ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Double', 'banner-management-for-woocommerce' );
        ?></option>
												<option value="inset" <?php 
        echo  ( esc_attr( 'inset' === $wbm_prod_nav_border_style ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Inset', 'banner-management-for-woocommerce' );
        ?></option>
												<option value="outset" <?php 
        echo  ( esc_attr( 'outset' === $wbm_prod_nav_border_style ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Outset', 'banner-management-for-woocommerce' );
        ?></option>
												<option value="groove" <?php 
        echo  ( esc_attr( 'groove' === $wbm_prod_nav_border_style ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Groove', 'banner-management-for-woocommerce' );
        ?></option>
												<option value="ridge" <?php 
        echo  ( esc_attr( 'ridge' === $wbm_prod_nav_border_style ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Ridge', 'banner-management-for-woocommerce' );
        ?></option>
												<option value="none" <?php 
        echo  ( esc_attr( 'none' === $wbm_prod_nav_border_style ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'None', 'banner-management-for-woocommerce' );
        ?></option>
											</select>
										</div>
										<div class="wbm-multi-option-input">
											<p><?php 
        esc_html_e( 'Color', 'banner-management-for-woocommerce' );
        ?></p>
											<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_nav_border][color]" value="<?php 
        echo  esc_attr( $wbm_prod_nav_border_color ) ;
        ?>">
										</div>
										<div class="wbm-multi-option-input">
											<p><?php 
        esc_html_e( 'Hover Color', 'banner-management-for-woocommerce' );
        ?></p>
											<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_nav_border][hover_color]" value="<?php 
        echo  esc_attr( $wbm_prod_nav_border_hov_color ) ;
        ?>">
										</div>
										</div>
									</td>
								</tr>
								<?php 
    }
} else {
    ?>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label><?php 
    esc_html_e( 'Navigation', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Show/Hide slider navigation.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<span class="wbm_radio_toggle_btn">
										<label for="wbm_prod_nav_show">
											<input type="radio" id="wbm_prod_nav_show" name="wbm_product_slider_sliders[wbm_prod_nav_status]" value="true" checked><?php 
    esc_html_e( 'Show', 'banner-management-for-woocommerce' );
    ?>
										</label>
										<label for="wbm_prod_nav_hide">
											<input type="radio" id="wbm_prod_nav_hide" name="wbm_product_slider_sliders[wbm_prod_nav_status]" value="false"><?php 
    esc_html_e( 'Hide', 'banner-management-for-woocommerce' );
    ?>
										</label>
										<label for="wbm_prod_nav_hide_on_mobile">
											<input type="radio" id="wbm_prod_nav_hide_on_mobile" name="wbm_product_slider_sliders[wbm_prod_nav_status]" value="false-mobile"><?php 
    esc_html_e( 'Hide on Mobile', 'banner-management-for-woocommerce' );
    ?>
										</label>
									</span>
								</td>
							</tr>
							<tr valign="top" class="wbm-multi-option-field wbm-prod-nav-field">
								<th class="titledesc" scope="row">
									<label><?php 
    esc_html_e( 'Navigation Color', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Set color for the slider navigation.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
								<div class="wbm-mo-parent">
									<div class="wbm-multi-option-input">
										<p><?php 
    esc_html_e( 'Color', 'banner-management-for-woocommerce' );
    ?></p>
										<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_nav_color][color]" value="#aaaaaa">
									</div>
									<div class="wbm-multi-option-input">
										<p><?php 
    esc_html_e( 'Hover Color', 'banner-management-for-woocommerce' );
    ?></p>
										<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_nav_color][hover_color]" value="">
									</div>
									<div class="wbm-multi-option-input">
										<p><?php 
    esc_html_e( 'Background', 'banner-management-for-woocommerce' );
    ?></p>
										<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_nav_color][bg_color]" value="transparent">
									</div>
									<div class="wbm-multi-option-input">
										<p><?php 
    esc_html_e( 'Hover Background', 'banner-management-for-woocommerce' );
    ?></p>
										<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_nav_color][bg_hover_color]" value="">
									</div>
								</div>
								</td>
							</tr>
							<tr valign="top" class="wbm-multi-option-field wbm-prod-nav-field">
								<th class="titledesc" scope="row">
									<label><?php 
    esc_html_e( 'Navigation Border', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Set border for the slider navigation.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
								<div class="wbm-mo-parent">
									<div class="wbm-multi-option-input">
										<span class="wbm-multi-option-icon">
											<i class="fa fa-arrows"></i>
										</span>
										<input type="number" class="wbm_prod_nav_border" min="0" name="wbm_product_slider_sliders[wbm_prod_nav_border][all]" value="1">
									</div>
									<div class="wbm-multi-option-input">
										<select name="wbm_product_slider_sliders[wbm_prod_nav_border][style]">
											<option value="solid"><?php 
    esc_html_e( 'Solid', 'banner-management-for-woocommerce' );
    ?></option>
											<option value="dashed"><?php 
    esc_html_e( 'Dashed', 'banner-management-for-woocommerce' );
    ?></option>
											<option value="dotted"><?php 
    esc_html_e( 'Dotted', 'banner-management-for-woocommerce' );
    ?></option>
											<option value="double"><?php 
    esc_html_e( 'Double', 'banner-management-for-woocommerce' );
    ?></option>
											<option value="inset"><?php 
    esc_html_e( 'Inset', 'banner-management-for-woocommerce' );
    ?></option>
											<option value="outset"><?php 
    esc_html_e( 'Outset', 'banner-management-for-woocommerce' );
    ?></option>
											<option value="groove"><?php 
    esc_html_e( 'Groove', 'banner-management-for-woocommerce' );
    ?></option>
											<option value="ridge"><?php 
    esc_html_e( 'Ridge', 'banner-management-for-woocommerce' );
    ?></option>
											<option value="none"><?php 
    esc_html_e( 'None', 'banner-management-for-woocommerce' );
    ?></option>
										</select>
									</div>
									<div class="wbm-multi-option-input">
										<p><?php 
    esc_html_e( 'Color', 'banner-management-for-woocommerce' );
    ?></p>
										<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_nav_border][color]" value="#aaaaaa">
									</div>
									<div class="wbm-multi-option-input">
										<p><?php 
    esc_html_e( 'Hover Color', 'banner-management-for-woocommerce' );
    ?></p>
										<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_nav_border][hover_color]" value="">
									</div>
									</div>
								</td>
							</tr>
							<?php 
}

?>
					</tbody>
				</table>
				<div class="wbm-slider-sub-field">
					<span><?php 
esc_html_e( 'Pagination Settings', 'banner-management-for-woocommerce' );
?></span>
				</div>
				<table class="form-table table-outer sliders-settings-tbl">
					<tbody>
						<?php 

if ( isset( $wbm_product_slider_sliders_meta ) && !empty($wbm_product_slider_sliders_meta) ) {
    foreach ( $wbm_product_slider_sliders_meta as $key => $sliders_settings ) {
        $wbm_prod_pager_status = ( isset( $sliders_settings['wbm_prod_pager_status'] ) ? $sliders_settings['wbm_prod_pager_status'] : '' );
        $wbm_prod_pager_color = ( isset( $sliders_settings['wbm_prod_pager_color']['color'] ) ? $sliders_settings['wbm_prod_pager_color']['color'] : '' );
        $wbm_prod_pager_active_color = ( isset( $sliders_settings['wbm_prod_pager_color']['active_color'] ) ? $sliders_settings['wbm_prod_pager_color']['active_color'] : '' );
        $wbm_prod_pager_hov_color = ( isset( $sliders_settings['wbm_prod_pager_color']['hover_color'] ) ? $sliders_settings['wbm_prod_pager_color']['hover_color'] : '' );
        ?>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label><?php 
        esc_html_e( 'Pagination', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Show/Hide slider pagination.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
										<p class="description" style="display:none;">
											
										</p>
									</th>
									<td class="forminp">
										<span class="wbm_radio_toggle_btn">
											<label for="wbm_prod_pager_show">
												<input type="radio" id="wbm_prod_pager_show" name="wbm_product_slider_sliders[wbm_prod_pager_status]" value="true" <?php 
        checked( $wbm_prod_pager_status, 'true' );
        ?>><?php 
        esc_html_e( 'Show', 'banner-management-for-woocommerce' );
        ?>
											</label>
											<label for="wbm_prod_pager_hide">
												<input type="radio" id="wbm_prod_pager_hide" name="wbm_product_slider_sliders[wbm_prod_pager_status]" value="false" <?php 
        checked( $wbm_prod_pager_status, 'false' );
        ?>><?php 
        esc_html_e( 'Hide', 'banner-management-for-woocommerce' );
        ?>
											</label>
											<label for="wbm_prod_pager_hide_on_mobile">
												<input type="radio" id="wbm_prod_pager_hide_on_mobile" name="wbm_product_slider_sliders[wbm_prod_pager_status]" value="false-mobile" <?php 
        checked( $wbm_prod_pager_status, 'false-mobile' );
        ?>><?php 
        esc_html_e( 'Hide on Mobile', 'banner-management-for-woocommerce' );
        ?>
											</label>
										</span>
									</td>
								</tr>
								<tr valign="top" class="wbm-multi-option-field wbm-prod-pager-field">
									<th class="titledesc" scope="row">
										<label><?php 
        esc_html_e( 'Pagination Color', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Set color for the slider pagination.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
										<p class="description" style="display:none;">
											
										</p>
									</th>
									<td class="forminp">
									<div class="wbm-mo-parent">
										<div class="wbm-multi-option-input">
											<p><?php 
        esc_html_e( 'Color', 'banner-management-for-woocommerce' );
        ?></p>
											<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_pager_color][color]" value="<?php 
        echo  esc_attr( $wbm_prod_pager_color ) ;
        ?>">
										</div>
										<div class="wbm-multi-option-input">
											<p><?php 
        esc_html_e( 'Active Color', 'banner-management-for-woocommerce' );
        ?></p>
											<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_pager_color][active_color]" value="<?php 
        echo  esc_attr( $wbm_prod_pager_active_color ) ;
        ?>">
										</div>
										<div class="wbm-multi-option-input">
											<p><?php 
        esc_html_e( 'Hover Color', 'banner-management-for-woocommerce' );
        ?></p>
											<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_pager_color][hover_color]" value="<?php 
        echo  esc_attr( $wbm_prod_pager_hov_color ) ;
        ?>">
										</div>
									</div>
									</td>
								</tr>
								<?php 
    }
} else {
    ?>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label><?php 
    esc_html_e( 'Pagination', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Show/Hide slider pagination.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<span class="wbm_radio_toggle_btn">
										<label for="wbm_prod_pager_show">
											<input type="radio" id="wbm_prod_pager_show" name="wbm_product_slider_sliders[wbm_prod_pager_status]" value="true" checked><?php 
    esc_html_e( 'Show', 'banner-management-for-woocommerce' );
    ?>
										</label>
										<label for="wbm_prod_pager_hide">
											<input type="radio" id="wbm_prod_pager_hide" name="wbm_product_slider_sliders[wbm_prod_pager_status]" value="false"><?php 
    esc_html_e( 'Hide', 'banner-management-for-woocommerce' );
    ?>
										</label>
										<label for="wbm_prod_pager_hide_on_mobile">
											<input type="radio" id="wbm_prod_pager_hide_on_mobile" name="wbm_product_slider_sliders[wbm_prod_pager_status]" value="false-mobile"><?php 
    esc_html_e( 'Hide on Mobile', 'banner-management-for-woocommerce' );
    ?>
										</label>
									</span>
								</td>
							</tr>
							<tr valign="top" class="wbm-multi-option-field wbm-prod-pager-field">
								<th class="titledesc" scope="row">
									<label><?php 
    esc_html_e( 'Pagination Color', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Set color for the slider pagination.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
								<div class="wbm-mo-parent">
									<div class="wbm-multi-option-input">
										<p><?php 
    esc_html_e( 'Color', 'banner-management-for-woocommerce' );
    ?></p>
										<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_pager_color][color]" value="#666">
									</div>
									<div class="wbm-multi-option-input">
										<p><?php 
    esc_html_e( 'Active Color', 'banner-management-for-woocommerce' );
    ?></p>
										<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_pager_color][active_color]" value="#000">
									</div>
									<div class="wbm-multi-option-input">
										<p><?php 
    esc_html_e( 'Hover Color', 'banner-management-for-woocommerce' );
    ?></p>
										<input type="text" class="wbm_sliders_colorpick" name="wbm_product_slider_sliders[wbm_prod_pager_color][hover_color]" value="#000">
									</div>
									</div>
								</td>
							</tr>
							<?php 
}

?>
					</tbody>
				</table>
				<div class="wbm-slider-sub-field">
					<span><?php 
esc_html_e( 'Miscellaneous', 'banner-management-for-woocommerce' );
?></span>
				</div>
				<table class="form-table table-outer sliders-settings-tbl">
					<tbody>
						<?php 

if ( isset( $wbm_product_slider_sliders_meta ) && !empty($wbm_product_slider_sliders_meta) ) {
    foreach ( $wbm_product_slider_sliders_meta as $key => $sliders_settings ) {
        $wbm_prod_touch_swipe_status = ( isset( $sliders_settings['wbm_prod_touch_swipe_status'] ) ? $sliders_settings['wbm_prod_touch_swipe_status'] : '' );
        $wbm_prod_mousewheel_control_status = ( isset( $sliders_settings['wbm_prod_mousewheel_control_status'] ) ? $sliders_settings['wbm_prod_mousewheel_control_status'] : '' );
        $wbm_prod_mouse_draggable_status = ( isset( $sliders_settings['wbm_prod_mouse_draggable_status'] ) ? $sliders_settings['wbm_prod_mouse_draggable_status'] : '' );
        ?>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label><?php 
        esc_html_e( 'Touch Swipe', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Enable/Disable touch swipe.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
										<p class="description" style="display:none;"></p>
									</th>
									<td class="forminp">
										<label class="dswbm_toggle_switch">
											<input type="checkbox" id="wbm_prod_touch_swipe" name="wbm_product_slider_sliders[wbm_prod_touch_swipe_status]" value="true" <?php 
        checked( $wbm_prod_touch_swipe_status, 'true' );
        ?>>
											<span class="dswbm_toggle_btn"></span>
										</label>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label><?php 
        esc_html_e( 'Mousewheel Control', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Enable/Disable mousewheel control.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
										<p class="description" style="display:none;"></p>
									</th>
									<td class="forminp">
										<label class="dswbm_toggle_switch">
											<input type="checkbox" id="wbm_prod_mousewheel_control" name="wbm_product_slider_sliders[wbm_prod_mousewheel_control_status]" value="true" <?php 
        checked( $wbm_prod_mousewheel_control_status, 'true' );
        ?>>
											<span class="dswbm_toggle_btn"></span>
										</label>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label><?php 
        esc_html_e( 'Mouse Draggable', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Enable/Disable mouse draggable.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
										<p class="description" style="display:none;"></p>
									</th>
									<td class="forminp">
										<label class="dswbm_toggle_switch">
											<input type="checkbox" id="wbm_prod_mouse_draggable" name="wbm_product_slider_sliders[wbm_prod_mouse_draggable_status]" value="true" <?php 
        checked( $wbm_prod_mouse_draggable_status, 'true' );
        ?>>
											<span class="dswbm_toggle_btn"></span>
										</label>
									</td>
								</tr>
								<?php 
    }
} else {
    ?>
							<tr valign="top">
									<th class="titledesc" scope="row">
										<label><?php 
    esc_html_e( 'Touch Swipe', 'banner-management-for-woocommerce' );
    ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
    esc_html_e( 'Enable/Disable touch swipe.', 'banner-management-for-woocommerce' );
    ?>
											</div>
										</span>
										<p class="description" style="display:none;"></p>
									</th>
									<td class="forminp">
										<label class="dswbm_toggle_switch">
											<input type="checkbox" id="wbm_prod_touch_swipe" name="wbm_product_slider_sliders[wbm_prod_touch_swipe_status]" value="true" >
											<span class="dswbm_toggle_btn"></span>
										</label>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label><?php 
    esc_html_e( 'Mousewheel Control', 'banner-management-for-woocommerce' );
    ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
    esc_html_e( 'Enable/Disable mousewheel control.', 'banner-management-for-woocommerce' );
    ?>
											</div>
										</span>
										<p class="description" style="display:none;"></p>
									</th>
									<td class="forminp">
										<label class="dswbm_toggle_switch">
											<input type="checkbox" id="wbm_prod_mousewheel_control" name="wbm_product_slider_sliders[wbm_prod_mousewheel_control_status]" value="true">
											<span class="dswbm_toggle_btn"></span>
										</label>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label><?php 
    esc_html_e( 'Mouse Draggable', 'banner-management-for-woocommerce' );
    ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
    esc_html_e( 'Enable/Disable mouse draggable.', 'banner-management-for-woocommerce' );
    ?>
											</div>
										</span>
										<p class="description" style="display:none;"></p>
									</th>
									<td class="forminp">
										<label class="dswbm_toggle_switch">
											<input type="checkbox" id="wbm_prod_mouse_draggable" name="wbm_product_slider_sliders[wbm_prod_mouse_draggable_status]" value="true">
											<span class="dswbm_toggle_btn"></span>
										</label>
									</td>
								</tr>
							<?php 
}

?>
					</tbody>
				</table>
			</div>
			<div id="wbm-slider-section-4" class="wbm-slider-section">
				<table class="form-table table-outer sliders-settings-tbl">
					<tbody>
						<?php 

if ( isset( $wbm_product_slider_sliders_meta ) && !empty($wbm_product_slider_sliders_meta) ) {
    foreach ( $wbm_product_slider_sliders_meta as $key => $sliders_settings ) {
        $wbm_prod_thumb_zoom = ( isset( $sliders_settings['wbm_prod_thumb_zoom']['zoom'] ) ? $sliders_settings['wbm_prod_thumb_zoom']['zoom'] : '' );
        $wbm_thumb_shadow_v_offset = '';
        $wbm_thumb_shadow_h_offset = '';
        $wbm_thumb_shadow_blur = '';
        $wbm_thumb_shadow_spread = '';
        $wbm_thumb_shadow_style = '';
        $wbm_thumb_shadow_color = '';
        $wbm_thumb_shadow_hov_color = '';
        $wbm_prod_thumb_image_mode = '';
        ?>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wbm_prod_thumb_zoom"><?php 
        esc_html_e( 'Zoom', 'banner-management-for-woocommerce' );
        ?></label>
										<span class="banner-woocommerce-help-tip">
											<div class="alert-desc">
												<?php 
        esc_html_e( 'Set a zoom effect for thumbnail.', 'banner-management-for-woocommerce' );
        ?>
											</div>
										</span>
									</th>
									<td class="forminp">
										<select name="wbm_product_slider_sliders[wbm_prod_thumb_zoom]" id="wbm_prod_thumb_zoom">
											<option value="none" <?php 
        echo  ( esc_attr( 'none' === $wbm_prod_thumb_zoom ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'None', 'banner-management-for-woocommerce' );
        ?></option>
											<option value="zoom-in" <?php 
        echo  ( esc_attr( 'zoom-in' === $wbm_prod_thumb_zoom ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Zoom In', 'banner-management-for-woocommerce' );
        ?></option>
											<option value="zoom-out" <?php 
        echo  ( esc_attr( 'zoom-out' === $wbm_prod_thumb_zoom ) ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Zoom Out', 'banner-management-for-woocommerce' );
        ?></option>
										</select>
									</td>
								</tr>
								<?php 
    }
} else {
    ?>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wbm_prod_thumb_zoom"><?php 
    esc_html_e( 'Zoom', 'banner-management-for-woocommerce' );
    ?></label>
									<span class="banner-woocommerce-help-tip">
										<div class="alert-desc">
											<?php 
    esc_html_e( 'Enable/Disable auto play.', 'banner-management-for-woocommerce' );
    ?>
										</div>
									</span>
								</th>
								<td class="forminp">
									<select name="wbm_product_slider_sliders[wbm_prod_thumb_zoom]" id="wbm_prod_thumb_zoom">
										<option value="none" ><?php 
    esc_html_e( 'None', 'banner-management-for-woocommerce' );
    ?></option>
										<option value="zoom-in" ><?php 
    esc_html_e( 'Zoom In', 'banner-management-for-woocommerce' );
    ?></option>
										<option value="zoom-out" ><?php 
    esc_html_e( 'Zoom Out', 'banner-management-for-woocommerce' );
    ?></option>
									</select>
								</td>
							</tr>
							<?php 
}

?>
					</tbody>
				</table>
			</div>
			<?php 
?>
		</div>
		<div class="sliders-save-preview-btn">
			<p>
				<input type="submit" class="button button-primary" name="wbm_product_sliders_save"
			       value="<?php 
esc_attr_e( 'Save Slider', 'banner-management-for-woocommerce' );
?>">
			</p>
			<p>
				<button id="wbm-product-slider-preview-btn" class="button button-primary"><?php 
esc_html_e( 'Show Preview', 'banner-management-for-woocommerce' );
?></button>
			</p>
		</div>
		<?php 
wp_nonce_field( 'woocommerce_save_method', 'wbm_product_sliders_save_method_nonce' );
?>
		<div class="wbm_slider_live_preview">
			<div class="slider-preview-header">
				<h3><?php 
esc_html_e( 'Live Preview', 'banner-management-for-woocommerce' );
?></h3>
				<p><?php 
esc_html_e( 'Save the slider and apply the same settings to your slider.', 'banner-management-for-woocommerce' );
?></p>
			</div>
			<div class="slider-preview-body">
				
			</div>
		</div>
	</div>
	<div class="wbm-slider-shortcode-box">
		<div class="shortcode-box-head">
			<h2><?php 
echo  esc_html__( 'Copy Shortcode', 'banner-management-for-woocommerce' ) ;
?></h2>
		</div>
		<div class="shortcode-box-body">
			<div class="wbm-scode-content">
				<h3 class="wbm-scode-title"><?php 
echo  esc_html__( 'Page or Post Include', 'banner-management-for-woocommerce' ) ;
?></h3>
				<p><?php 
echo  esc_html__( 'Copy and paste this shortcode into your posts or pages:', 'banner-management-for-woocommerce' ) ;
?></p>
				<?php 
$copy_page_shortcode = '<div class="wbm-after-copy-text"><span class="dashicons dashicons-yes-alt"></span> Shortcode  Copied to Clipboard! </div><div class="wbm-code wbm-copy-shortcode">[wcbm_product id=&quot;' . $get_post_id . '&quot;]</div>';
echo  wp_kses( $copy_page_shortcode, $dswbm_admin_object->wcbm_allowed_html_tags() ) ;
?>
			</div>
			<div class="wbm-scode-content">
				<h3 class="wbm-scode-title"><?php 
echo  esc_html__( 'Template Include', 'banner-management-for-woocommerce' ) ;
?></h3>
				<p><?php 
echo  esc_html__( 'Paste the PHP code into your template file:', 'banner-management-for-woocommerce' ) ;
?></p>
				<?php 
$copy_template_shortcode = '<div class="wbm-after-copy-text"><span class="dashicons dashicons-yes-alt"></span> Shortcode  Copied to Clipboard! </div><div class="wbm-code wbm-copy-shortcode">&lt;?php echo do_shortcode(\'[wcbm_product id=&quot;' . $get_post_id . '&quot;]\'); ?&gt;</div>';
echo  wp_kses( $copy_template_shortcode, $dswbm_admin_object->wcbm_allowed_html_tags() ) ;
?>
			</div>
		</div>
	</div>
</div>
