<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to diplay settings of banner settings page.
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
$wbm_shop_page_stored_results_serialize_banner_select_image = '';
$wbm_shop_page_stored_results_serialize_banner_target = '';
$wbm_cart_page_stored_results_serialize_banner_target = '';
$wbm_shop_page_stored_results_serialize_banner_relation = '';
$wbm_cart_page_stored_results_serialize_banner_relation = '';
$wbm_shop_page_stored_results_serialize_banner_src = '';
$wbm_shop_page_stored_results_serialize_banner_link = '';
$wbm_shop_page_stored_results_serialize_banner_image_size = '';
$wbm_shop_page_stored_results_serialize_banner_enable_status = '';
$wbm_cart_page_stored_results_serialize_banner_select_image = '';
$wbm_cart_page_stored_results_serialize_banner_src = '';
$wbm_cart_page_stored_results_serialize_banner_arr = '';
$wbm_cart_page_stored_results_serialize_banner_link = '';
$wbm_cart_page_stored_results_serialize_banner_enable_status = '';
$wbm_checkout_page_stored_results_serialize_banner_select_image = '';
$wbm_checkout_page_stored_results_serialize_banner_src = '';
$wbm_checkout_page_stored_results_serialize_banner_arr = '';
$wbm_checkout_page_stored_results_serialize_banner_link = '';
$wbm_checkout_page_stored_results_serialize_banner_enable_status = '';
$wbm_checkout_page_stored_results_serialize_banner_target = '';
$wbm_checkout_page_stored_results_serialize_banner_relation = '';
$wbm_thankyou_page_stored_results_serialize_banner_select_image = '';
$wbm_thankyou_page_stored_results_serialize_banner_src = '';
$wbm_thankyou_page_stored_results_serialize_banner_arr = '';
$wbm_thankyou_page_stored_results_serialize_banner_link = '';
$wbm_thankyou_page_stored_results_serialize_banner_enable_status = '';
$wbm_thankyou_page_stored_results_serialize_banner_target = '';
$wbm_thankyou_page_stored_results_serialize_banner_relation = '';
$wbm_banner_detail_page_stored_results_serialize_banner_select_image = '';
$wbm_banner_detail_page_stored_results_serialize_banner_src = '';
$wbm_banner_detail_page_stored_results_serialize_banner_arr = '';
$wbm_banner_detail_page_stored_results_serialize_banner_link = '';
$wbm_banner_global_page_stored_results_serialize_banner_target = '';
$wbm_banner_global_page_stored_results_serialize_banner_relation = '';
$wbm_banner_detail_page_stored_results_serialize_banner_enable_status = '';
$wbm_banner_detail_page_section_stored_results_serialize_banner_enable_status = '';
$wbm_other_page_stored_results_serialize_banner_select_image = '';
$wbm_other_page_stored_results_serialize_banner_target = '';
$wbm_other_page_stored_results_serialize_banner_relation = '';
$wbm_other_page_stored_results_serialize_banner_src = '';
$wbm_other_page_stored_results_serialize_banner_link = '';
$wbm_other_page_stored_results_serialize_banner_enable_status = '';
$product_slider_settings_on_sale_val = '';
$product_slider_settings_featured_prod_val = array();
$product_slider_price_range_val = '';
$product_cat_slider_settings_cat_val = array();
$product_cat_slider_settings_by_title_val = '';
$product_cat_slider_settings_feature_img_val = '';
$product_cat_slider_settings_by_desc_val = '';

if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
    $wbm_shop_page_stored_results = wcbm_get_page_banner_data( 'shop' );
    $wbm_cart_page_stored_results = wcbm_get_page_banner_data( 'cart' );
    $wbm_checkout_page_stored_results = wcbm_get_page_banner_data( 'checkout' );
    $wbm_thankyou_page_stored_results = wcbm_get_page_banner_data( 'thankyou' );
    $wbm_banner_detail_page_stored_results = wcbm_get_page_banner_data( 'banner_detail' );
    $wbm_other_page_stored_results = wcbm_get_page_banner_data( 'other_pages' );
    $wbm_prod_slider_data_stored_array = wcbm_get_page_banner_data( 'product_sliders' );
}

// get shop page stored data

if ( isset( $wbm_shop_page_stored_results ) && !empty($wbm_shop_page_stored_results) ) {
    $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
    
    if ( !empty($wbm_shop_page_stored_results_serialize) ) {
        $wbm_shop_page_stored_results_serialize_banner_select_image = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_select_image']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_select_image'] : '' );
        $wbm_shop_page_stored_results_serialize_banner_target = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_select_target']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_select_target'] : '' );
        $wbm_shop_page_stored_results_serialize_banner_relation = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_select_relation']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_select_relation'] : '' );
        $wbm_shop_page_stored_results_serialize_banner_src = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_src']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_src'] : '' );
        $wbm_shop_page_stored_results_serialize_banner_link = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_link_src']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_link_src'] : '' );
        $wbm_shop_page_stored_results_serialize_banner_image_size = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_size'] : '' );
        $wbm_shop_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_enable_status']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_enable_status'] : '' );
    }

}

//get cart setting page stored data

if ( isset( $wbm_cart_page_stored_results ) && !empty($wbm_cart_page_stored_results) ) {
    $wbm_cart_page_stored_results_serialize = $wbm_cart_page_stored_results;
    
    if ( !empty($wbm_cart_page_stored_results_serialize) ) {
        $wbm_cart_page_stored_results_serialize_banner_select_image = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_select_image']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_select_image'] : '' );
        $wbm_cart_page_stored_results_serialize_banner_src = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_image_src']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_image_src'] : '' );
        $wbm_cart_page_stored_results_serialize_banner_link = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_link_src']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_link_src'] : '' );
        $wbm_cart_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_enable_status']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_enable_status'] : '' );
        $wbm_cart_page_stored_results_serialize_banner_target = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_select_target']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_select_target'] : '' );
        $wbm_cart_page_stored_results_serialize_banner_relation = ( !empty($wbm_cart_page_stored_results_serialize['cart_page_banner_select_relation']) ? $wbm_cart_page_stored_results_serialize['cart_page_banner_select_relation'] : '' );
    }

}

//get checkout setting page stored data

if ( isset( $wbm_checkout_page_stored_results ) && !empty($wbm_checkout_page_stored_results) ) {
    $wbm_checkout_page_stored_results_serialize = $wbm_checkout_page_stored_results;
    
    if ( !empty($wbm_checkout_page_stored_results_serialize) ) {
        $wbm_checkout_page_stored_results_serialize_banner_select_image = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_select_image']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_select_image'] : '' );
        $wbm_checkout_page_stored_results_serialize_banner_src = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_image_src']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_image_src'] : '' );
        $wbm_checkout_page_stored_results_serialize_banner_link = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_link_src']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_link_src'] : '' );
        $wbm_checkout_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_enable_status']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_enable_status'] : '' );
        $wbm_checkout_page_stored_results_serialize_banner_target = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_target']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_target'] : '' );
        $wbm_checkout_page_stored_results_serialize_banner_relation = ( !empty($wbm_checkout_page_stored_results_serialize['checkout_page_banner_relation']) ? $wbm_checkout_page_stored_results_serialize['checkout_page_banner_relation'] : '' );
    }

}

//get thank you setting page stored data

if ( isset( $wbm_thankyou_page_stored_results ) && !empty($wbm_thankyou_page_stored_results) ) {
    $wbm_thankyou_page_stored_results_serialize = $wbm_thankyou_page_stored_results;
    
    if ( !empty($wbm_thankyou_page_stored_results_serialize) ) {
        $wbm_thankyou_page_stored_results_serialize_banner_select_image = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_select_image']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_select_image'] : '' );
        $wbm_thankyou_page_stored_results_serialize_banner_src = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_image_src']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_image_src'] : '' );
        $wbm_thankyou_page_stored_results_serialize_banner_link = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_link_src']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_link_src'] : '' );
        $wbm_thankyou_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_enable_status']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_enable_status'] : '' );
        $wbm_thankyou_page_stored_results_serialize_banner_target = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_target']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_target'] : '' );
        $wbm_thankyou_page_stored_results_serialize_banner_relation = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_relation']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_relation'] : '' );
    }

}

// get other page stored data

if ( isset( $wbm_other_page_stored_results ) && !empty($wbm_other_page_stored_results) ) {
    $wbm_other_page_stored_results_serialize = $wbm_other_page_stored_results;
    
    if ( !empty($wbm_other_page_stored_results_serialize) ) {
        $wbm_other_page_stored_results_serialize_banner_select_image = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_select_image']) ? $wbm_other_page_stored_results_serialize['other_page_banner_select_image'] : '' );
        $wbm_other_page_stored_results_serialize_banner_target = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_select_target']) ? $wbm_other_page_stored_results_serialize['other_page_banner_select_target'] : '' );
        $wbm_other_page_stored_results_serialize_banner_relation = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_select_relation']) ? $wbm_other_page_stored_results_serialize['other_page_banner_select_relation'] : '' );
        $wbm_other_page_stored_results_serialize_banner_src = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_image_src']) ? $wbm_other_page_stored_results_serialize['other_page_banner_image_src'] : '' );
        $wbm_other_page_stored_results_serialize_banner_link = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_link_src']) ? $wbm_other_page_stored_results_serialize['other_page_banner_link_src'] : '' );
        $wbm_other_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_other_page_stored_results_serialize['other_page_banner_enable_status']) ? $wbm_other_page_stored_results_serialize['other_page_banner_enable_status'] : '' );
    }

}

//get global banner setting stored data

if ( isset( $wbm_banner_detail_page_stored_results ) && !empty($wbm_banner_detail_page_stored_results) ) {
    $wbm_banner_detail_page_stored_results_serialize = $wbm_banner_detail_page_stored_results;
    
    if ( !empty($wbm_banner_detail_page_stored_results_serialize) ) {
        $wbm_banner_detail_page_stored_results_serialize_banner_select_image = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_select_image']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_select_image'] : '' );
        $wbm_banner_detail_page_stored_results_serialize_banner_src = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_image_src']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_image_src'] : '' );
        $wbm_banner_detail_page_stored_results_serialize_banner_link = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_link_src']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_link_src'] : '' );
        $wbm_banner_global_page_stored_results_serialize_banner_target = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_target']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_target'] : '' );
        $wbm_banner_global_page_stored_results_serialize_banner_relation = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_relation']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_relation'] : '' );
        $wbm_banner_detail_page_stored_results_serialize_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_banner_enable_status'] : '' );
        $wbm_banner_detail_page_section_stored_results_serialize_banner_enable_status = ( !empty($wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status']) ? $wbm_banner_detail_page_stored_results_serialize['banner_detail_page_section_banner_enable_status'] : '' );
    }

}

// get product sliders settings stored data

if ( isset( $wbm_prod_slider_data_stored_array ) && !empty($wbm_prod_slider_data_stored_array) ) {
    $wbm_prod_slider_data_stored_array_serialize = $wbm_prod_slider_data_stored_array;
    $product_slider_settings_on_sale_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_slider_settings_on_sale']) ? $wbm_prod_slider_data_stored_array_serialize['product_slider_settings_on_sale'] : '' );
    $product_slider_settings_featured_prod_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_slider_settings_featured_prod']) ? $wbm_prod_slider_data_stored_array_serialize['product_slider_settings_featured_prod'] : array() );
    $product_slider_price_range_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_slider_price_range']) ? $wbm_prod_slider_data_stored_array_serialize['product_slider_price_range'] : '' );
    $product_cat_slider_settings_cat_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_cat']) ? $wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_cat'] : array() );
    $product_cat_slider_settings_by_title_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_by_title']) ? $wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_by_title'] : 'off' );
    $product_cat_slider_settings_feature_img_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_feature_img']) ? $wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_feature_img'] : 'off' );
    $product_cat_slider_settings_by_desc_val = ( !empty($wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_by_desc']) ? $wbm_prod_slider_data_stored_array_serialize['product_cat_slider_settings_by_desc'] : 'off' );
}

?>
<div class="wcbm-section-left">
    <div class="notice notice-success is-dismissible" id="succesful_message_wbm">
        <p><?php 
esc_html_e( 'Banner saved succesfully', 'banner-management-for-woocommerce' );
?></p>
    </div>
    <div class="woocommerce-banner-managment-setting-content">
        <div class="top-save-button">
            <img class="banner-setting-loader"
                src="<?php 
echo  esc_url( plugin_dir_url( __FILE__ ) . 'images/ajax-loader.gif' ) ;
?>"
                alt="ajax-loader" />
            <input type="button" name="save_wbmshop" id="save_top_wbm_shop_page_setting" class="button button-primary"
                value="<?php 
esc_attr_e( 'Save Changes', 'banner-management-for-woocommerce' );
?>">
        </div>
        <fieldset class="wbm_global">
            <legend>
                <div class="wbm-setting-header">
                    <h2><?php 
esc_html_e( 'Checkout Page Specific Banner Settings', 'banner-management-for-woocommerce' );
?></h2>
                </div>
            </legend>
            <p><?php 
esc_html_e( 'You can upload custom banner on page specific. Easily update the image and redirect page URL from below setting option.', 'banner-management-for-woocommerce' );
?>
            </p>
            <div class="accordion">
                <div class="accordion-section">
                    <?php 

if ( $wbm_shop_page_stored_results_serialize_banner_enable_status === 'on' ) {
    $setting_enable_or_not = " ( Enable ) ";
    $setting_enable_or_color = "green";
} else {
    $setting_enable_or_not = " ( Disable ) ";
    $setting_enable_or_color = "red";
}

?>
                    <a class="accordion-section-title" href="#wbm-enable-banner-for-shpe-page">
                        <?php 
esc_html_e( 'Banner for shop page ', 'banner-management-for-woocommerce' );
?>
                        <span id="shop_page_status_enable_or_disable"
                            class="shop_page_status_enable_or_disable <?php 
echo  esc_attr( $setting_enable_or_color ) ;
?>">
                            <?php 
esc_html_e( $setting_enable_or_not, 'banner-management-for-woocommerce' );
?>
                        </span>
                    </a>
                    <div id="wbm-enable-banner-for-shpe-page" class="accordion-section-content">

                        <table class="form-table" id="form-table-wbm-shop-page">
                            <tbody>
                                <tr>
                                    <th scope="row"><label class="wbm_leble_setting_css"
                                            for="wbm_shop_setting_enable"><?php 
esc_html_e( 'Enable/Disable', 'banner-management-for-woocommerce' );
?>
                                        </label>
                                    </th>
                                    <td><input type="checkbox" value="on" id="wbm_shop_setting_enable"
                                            class="wbm_shop_setting_enable_or_not" <?php 
checked( $wbm_shop_page_stored_results_serialize_banner_enable_status, 'on' );
?>>
                                        </td>
                                    <?php 
$shop_page_url_results = "#";
$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
if ( !empty($shop_page_url) ) {
    $shop_page_url_results = $shop_page_url;
}

if ( $wbm_shop_page_stored_results_serialize_banner_enable_status === 'on' ) {
    $shop_page_preview_content = '<strong>' . __( 'Preview', 'banner-management-for-woocommerce' ) . ':</strong> <a href="' . $shop_page_url_results . '" target="_blank">' . __( 'Click here', 'banner-management-for-woocommerce' ) . '</a>';
} else {
    $shop_page_preview_content = '';
}

?>
                                    <input type="hidden" id="shop_page_hidden_url"
                                        value="<?php 
echo  esc_url( $shop_page_url_results ) ;
?>">
                                    <td>
                                        <span
                                            class="Preview_link_for_shop_page"><?php 
echo  wp_kses_post( $shop_page_preview_content ) ;
?></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php 
$display_option = 'block';
if ( 'on' !== $wbm_shop_page_stored_results_serialize_banner_enable_status ) {
    $display_option = 'none';
}
?>
                        <div class="wbm_shop_page_enable_open_div <?php 
echo  esc_attr( $display_option ) ;
?>">
                            <fieldset class="innerbanner">
                                <legend><?php 
esc_html_e( 'General Settings', 'banner-management-for-woocommerce' );
?></legend>
                                <?php 
?>
                                    <table class="form-table" id="form-table">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><label class="wbm_leble_setting_css"
                                                        for="wbm_shop_page_single_upload_file_button"><?php 
esc_html_e( 'Banner Image', 'banner-management-for-woocommerce' );
?></label>
                                                </th>
                                                <td><a class='wbm_shop_page_single_upload_file_button button'
                                                        id="wbm_shop_page_single_upload_file_button"
                                                        uploader_title='Select File'
                                                        uploader_button_text="<?php 
esc_attr_e( 'Include File', 'banner-management-for-woocommerce' );
?>"><?php 
esc_html_e( 'Upload File', 'banner-management-for-woocommerce' );
?></a>
                                                    <a
                                                        class='wbm_shop_page_remove_single_file button'><?php 
esc_html_e( 'Remove File', 'banner-management-for-woocommerce' );
?></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"></th>
                                                <?php 

if ( '' === $wbm_shop_page_stored_results_serialize_banner_src ) {
    $shop_page_benner_css = "no-image";
} else {
    $shop_page_benner_css = "yes-image";
}

?>
                                                <td>
                                                    <img class="wbm_shop_page_cat_banner_img_admin_single <?php 
echo  esc_attr( $shop_page_benner_css ) ;
?>"
                                                        src="<?php 
echo  esc_url( $wbm_shop_page_stored_results_serialize_banner_src ) ;
?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label class="wbm_leble_setting_css"
                                                        for="shop_page_banner_single_image_link"><?php 
esc_html_e( 'Banner Image Link', 'banner-management-for-woocommerce' );
?></label>
                                                </th>
                                                <td><input type="url" id="shop_page_banner_single_image_link"
                                                        title="Example: https://multidots.com"
                                                        name='term_meta[banner_link]'
                                                        value='<?php 
echo  esc_attr( $wbm_shop_page_stored_results_serialize_banner_link ) ;
?>' />
                                                    <p><label class="banner_link_label"
                                                            for="shop_page_banner_single_image_link"><em><?php 
esc_html_e( 'Where users will be directed if they click on the banner.', 'banner-management-for-woocommerce' );
?></em></label>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label class="wbm_leble_setting_css"
                                                        for="wbm_shop_page_select_banner_target"><?php 
esc_html_e( 'Select Link Target', 'banner-management-for-woocommerce' );
?></label>
                                                </th>
                                                <td><select name="wbm_shop_page_select_banner_target"
                                                        id="wbm_shop_page_select_banner_target" class="">
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
                                            <tr>
                                                <th scope="row"><label class="wbm_leble_setting_css"
                                                        for="wbm_shop_page_select_banner_relation"><?php 
esc_html_e( 'Select Link Relation', 'banner-management-for-woocommerce' );
?></label>
                                                </th>
                                                <td><select name="wbm_shop_page_select_banner_relation"
                                                        id="wbm_shop_page_select_banner_relation" class="">
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
                                        </tbody>
                                    </table>
                                    <?php 
?>
                            </fieldset>

                        </div>
                    </div>
                    <!--end .accordion-section-content-->
                </div>
                <!--end .accordion-section-->
                <div class="accordion-section">
                    <?php 

if ( $wbm_cart_page_stored_results_serialize_banner_enable_status === 'on' ) {
    $setting_enable_or_not_cart = " ( Enable ) ";
    $setting_enable_or_color_cart = "green";
} else {
    $setting_enable_or_not_cart = " ( Disable ) ";
    $setting_enable_or_color_cart = "red";
}

?>
                    <a class="accordion-section-title"
                        href="#wbm-enable-banner-for-cart-page"><?php 
esc_html_e( 'Banner for cart page', 'banner-management-for-woocommerce' );
?>
                        <span id="cart_page_status_enable_or_disable"
                            class="cart_page_status_enable_or_disable <?php 
echo  esc_attr( $setting_enable_or_color_cart ) ;
?>">
                            <?php 
esc_html_e( $setting_enable_or_not_cart, 'banner-management-for-woocommerce' );
?></span>
                    </a>
                    <div id="wbm-enable-banner-for-cart-page" class="accordion-section-content">
                        <div class="woocommerce-banner-managment-cart-setting-admin">

                            <table class="form-table" id="form-table-wbm-cart-page">
                                <tbody>
                                    <tr>
                                        <th scope="row"><label class="wbm_leble_setting_css"
                                                for="wbm_shop_setting_cart_enable"><?php 
esc_html_e( 'Enable/Disable', 'banner-management-for-woocommerce' );
?></label>
                                        </th>
                                        <td><input type="checkbox" value="on" id="wbm_shop_setting_cart_enable"
                                                class="wbm_shop_setting_cart_enable_or_not" <?php 
checked( $wbm_cart_page_stored_results_serialize_banner_enable_status, 'on' );
?>></td>
                                        <?php 
$cart_url_results = "#";
$cart_url = wc_get_cart_url();
if ( !empty($cart_url) ) {
    $cart_url_results = $cart_url;
}

if ( $wbm_cart_page_stored_results_serialize_banner_enable_status === 'on' ) {
    $cart_page_preview_url = '<strong>' . __( 'Preview', 'banner-management-for-woocommerce' ) . ':</strong> <a href="' . $cart_url_results . '" target="_blank">' . __( 'Click here', 'banner-management-for-woocommerce' ) . '</a>';
} else {
    $cart_page_preview_url = "";
}

?>
                                        <input type="hidden" id="cart_page_hidden_url"
                                            value="<?php 
echo  esc_url( $cart_url_results ) ;
?>">
                                        <td>
                                            <span
                                                class="Preview_link_for_cart_page"><?php 
echo  wp_kses_post( $cart_page_preview_url ) ;
?></span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                            <?php 
$display_option_cart = 'block';
if ( 'on' !== $wbm_cart_page_stored_results_serialize_banner_enable_status ) {
    $display_option_cart = 'none';
}
?>
                            <div class="wbm-cart-upload-image-html <?php 
echo  esc_attr( $display_option_cart ) ;
?>">
                                <fieldset class="innerbanner">
                                    <legend><?php 
esc_html_e( 'General Settings', 'banner-management-for-woocommerce' );
?></legend>
                                    <?php 
?>
                                        <table class="form-table" id="form-table">
                                            <tbody>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="wbm_cart_page_single_upload_file_button"><?php 
esc_html_e( 'Banner Image', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td>
                                                        <a class='wbm_cart_page_single_upload_file_button button'
                                                            id="wbm_cart_page_single_upload_file_button"
                                                            uploader_title="<?php 
esc_attr_e( 'Select File', 'banner-management-for-woocommerce' );
?>"
                                                            uploader_button_text="<?php 
esc_attr_e( 'Include File', 'banner-management-for-woocommerce' );
?>"><?php 
esc_html_e( 'Upload File', 'banner-management-for-woocommerce' );
?></a>
                                                        <a
                                                            class='wbm_cart_page_remove_single_file button'><?php 
esc_html_e( 'Remove File', 'banner-management-for-woocommerce' );
?></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"></th>
                                                    <?php 

if ( $wbm_cart_page_stored_results_serialize_banner_src === '' ) {
    $cart_page_benner_css = "no-image";
} else {
    $cart_page_benner_css = "yes-image";
}

?>
                                                    <td>
                                                        <img class="wbm_cart_page_cat_banner_img_admin_single <?php 
echo  esc_attr( $cart_page_benner_css ) ;
?>"
                                                            src="<?php 
echo  esc_url( $wbm_cart_page_stored_results_serialize_banner_src ) ;
?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="cart_page_banner_single_image_link"><?php 
esc_html_e( 'Banner Image Link', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td><input type="url" id="cart_page_banner_single_image_link"
                                                            title="<?php 
esc_attr_e( 'Example: https://multidots.com', 'banner-management-for-woocommerce' );
?>"
                                                            name='term_meta[banner_link]'
                                                            value='<?php 
echo  esc_attr( $wbm_cart_page_stored_results_serialize_banner_link ) ;
?>' />
                                                        <p><label class="banner_link_label"
                                                                for="cart_page_banner_single_image_link"><em><?php 
esc_html_e( 'Where users will be directed if they click on the banner.', 'banner-management-for-woocommerce' );
?></em></label>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                <th scope="row"><label class="wbm_leble_setting_css"
                                                        for="wbm_cart_page_select_banner_target"><?php 
esc_html_e( 'Select Link Target', 'banner-management-for-woocommerce' );
?></label>
                                                </th>
                                                <td><select name="wbm_cart_page_select_banner_target"
                                                        id="wbm_cart_page_select_banner_target" class="">
                                                        <option value="self"
                                                            <?php 
selected( $wbm_cart_page_stored_results_serialize_banner_target, 'self' );
?>>
                                                            <?php 
esc_html_e( 'Self window', 'banner-management-for-woocommerce' );
?>
                                                        </option>
                                                        <option value="blank"
                                                            <?php 
selected( $wbm_cart_page_stored_results_serialize_banner_target, 'blank' );
?>>
                                                            <?php 
esc_html_e( 'New window', 'banner-management-for-woocommerce' );
?>
                                                        </option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label class="wbm_leble_setting_css"
                                                        for="wbm_cart_page_select_banner_relation"><?php 
esc_html_e( 'Select Link Relation', 'banner-management-for-woocommerce' );
?></label>
                                                </th>
                                                <td><select name="wbm_cart_page_select_banner_relation"
                                                        id="wbm_cart_page_select_banner_relation" class="">
                                                        <option value="follow"
                                                            <?php 
selected( $wbm_cart_page_stored_results_serialize_banner_relation, 'follow' );
?>>
                                                            <?php 
esc_html_e( 'Follow', 'banner-management-for-woocommerce' );
?>
                                                        </option>
                                                        <option value="nofollow"
                                                            <?php 
selected( $wbm_cart_page_stored_results_serialize_banner_relation, 'nofollow' );
?>>
                                                            <?php 
esc_html_e( 'No follow', 'banner-management-for-woocommerce' );
?>
                                                        </option>
                                                    </select>
                                                </td>
                                            </tr>   
                                            </tbody>
                                        </table>
                                        <?php 
?>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <!--end .accordion-section-content-->
                </div>
                <!--end .accordion-section-->
                <div class="accordion-section">
                    <?php 

if ( $wbm_checkout_page_stored_results_serialize_banner_enable_status === 'on' ) {
    $setting_enable_or_not_checkout = " ( Enable ) ";
    $setting_enable_or_color_checkout = "green";
} else {
    $setting_enable_or_not_checkout = " ( Disable ) ";
    $setting_enable_or_color_checkout = "red";
}

?>
                    <a class="accordion-section-title"
                        href="#wbm-enable-banner-for-checkout-page"><?php 
esc_html_e( 'Banner for checkout page', 'banner-management-for-woocommerce' );
?>
                        <span id="checkout_page_status_enable_or_disable"
                            class="checkout_page_status_enable_or_disable <?php 
echo  esc_attr( $setting_enable_or_color_checkout ) ;
?>">
                            <?php 
esc_html_e( $setting_enable_or_not_checkout, 'banner-management-for-woocommerce' );
?>
                        </span>
                    </a>
                    <div id="wbm-enable-banner-for-checkout-page" class="accordion-section-content">
                        <div class="woocommerce-banner-managment-checkout-setting-admin">
                            <table class="form-table" id="form-table-wbm-checkout-page">

                                <tbody>
                                    <tr>
                                        <th scope="row"><label class="wbm_leble_setting_css"
                                                for="wbm_shop_setting_checkout_enable"><?php 
esc_html_e( 'Enable/Disable', 'banner-management-for-woocommerce' );
?>
                                            </label>
                                        </th>
                                        <td><input type="checkbox" value="on" id="wbm_shop_setting_checkout_enable"
                                                class="wbm_shop_setting_checkout_enable_or_not" <?php 
checked( $wbm_checkout_page_stored_results_serialize_banner_enable_status, 'on' );
?>></td>
                                        <?php 
$CheckOut_url_real = "#";
$CheckOut_url = wc_get_checkout_url();
if ( !empty($CheckOut_url) ) {
    $CheckOut_url_real = $CheckOut_url;
}

if ( $wbm_checkout_page_stored_results_serialize_banner_enable_status === 'on' ) {
    $check_out_preview_content = '<strong>' . __( "Preview :", "banner-management-for-woocommerce" ) . '</strong> <a href="' . $CheckOut_url_real . '" target="_blank">' . __( 'Click here', 'banner-management-for-woocommerce' ) . '</a>';
} else {
    $check_out_preview_content = "";
}

?>

                                        <input type="hidden" id="checkout_page_hidden_url"
                                            value="<?php 
echo  esc_url( $CheckOut_url_real ) ;
?>">
                                        <td>
                                            <span
                                                class="Preview_link_for_checkout_page"><?php 
echo  wp_kses_post( $check_out_preview_content ) ;
?></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php 
$display_option_checkout = 'block';
if ( 'on' !== $wbm_checkout_page_stored_results_serialize_banner_enable_status ) {
    $display_option_checkout = 'none';
}
?>
                            <div class="wbm-checkout-upload-image-html <?php 
echo  esc_attr( $display_option_checkout ) ;
?>">
                                <fieldset class="innerbanner">
                                    <legend><?php 
esc_html_e( 'General Settings', 'banner-management-for-woocommerce' );
?></legend>
                                    <?php 
?>
                                        <table class="form-table" id="form-table">
                                            <tbody>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="wbm_checkout_page_single_upload_file_button"><?php 
esc_html_e( 'Banner Image', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td>
                                                        <a class='wbm_checkout_page_single_upload_file_button button'
                                                            id="wbm_checkout_page_single_upload_file_button"
                                                            uploader_title="<?php 
esc_attr_e( 'Select File', 'banner-management-for-woocommerce' );
?>"
                                                            uploader_button_text="<?php 
esc_attr_e( 'Include File', 'banner-management-for-woocommerce' );
?>"><?php 
esc_html_e( 'Upload File', 'banner-management-for-woocommerce' );
?></a>
                                                        <a
                                                            class='wbm_checkout_page_remove_single_file button'><?php 
esc_html_e( 'Remove File', 'banner-management-for-woocommerce' );
?></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"></th>
                                                    <?php 

if ( $wbm_checkout_page_stored_results_serialize_banner_src === '' ) {
    $checkout_page_benner_css = "none";
} else {
    $checkout_page_benner_css = "block";
}

?>
                                                    <td>
                                                        <img class="wbm_checkout_page_cat_banner_img_admin_single <?php 
echo  esc_attr( $checkout_page_benner_css ) ;
?>"
                                                            src="<?php 
echo  esc_url( $wbm_checkout_page_stored_results_serialize_banner_src ) ;
?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="checkout_page_banner_single_image_link"><?php 
esc_html_e( 'Banner Image Link', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td><input type="url" id="checkout_page_banner_single_image_link"
                                                            title="<?php 
esc_attr_e( 'Example: https://multidots.com', 'banner-management-for-woocommerce' );
?>"
                                                            name='term_meta[banner_link]'
                                                            value='<?php 
echo  esc_attr( $wbm_checkout_page_stored_results_serialize_banner_link ) ;
?>' />
                                                        <p><label class="banner_link_label"
                                                                for="checkout_page_banner_single_image_link"><em><?php 
esc_html_e( 'Where users will be directed if they click on the banner.', 'banner-management-for-woocommerce' );
?></em></label>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="wbm_checkout_page_select_banner_target"><?php 
esc_html_e( 'Select Link Target', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td><select name="wbm_checkout_page_select_banner_target"
                                                            id="wbm_checkout_page_select_banner_target" class="">
                                                            <option value="self"
                                                                <?php 
selected( $wbm_checkout_page_stored_results_serialize_banner_target, 'self' );
?>>
                                                                <?php 
esc_html_e( 'Self window', 'banner-management-for-woocommerce' );
?>
                                                            </option>
                                                            <option value="blank"
                                                                <?php 
selected( $wbm_checkout_page_stored_results_serialize_banner_target, 'blank' );
?>>
                                                                <?php 
esc_html_e( 'New window', 'banner-management-for-woocommerce' );
?>
                                                            </option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="wbm_checkout_page_select_banner_relation"><?php 
esc_html_e( 'Select Link Relation', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td><select name="wbm_checkout_page_select_banner_relation"
                                                            id="wbm_checkout_page_select_banner_relation" class="">
                                                            <option value="follow"
                                                                <?php 
selected( $wbm_checkout_page_stored_results_serialize_banner_relation, 'follow' );
?>>
                                                                <?php 
esc_html_e( 'Follow', 'banner-management-for-woocommerce' );
?>
                                                            </option>
                                                            <option value="nofollow"
                                                                <?php 
selected( $wbm_checkout_page_stored_results_serialize_banner_relation, 'nofollow' );
?>>
                                                                <?php 
esc_html_e( 'No follow', 'banner-management-for-woocommerce' );
?>
                                                            </option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php 
?>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <!--end .accordion-section-content-->
                </div>
                <!--end .accordion-section-->
                <div class="accordion-section">
                    <?php 

if ( $wbm_thankyou_page_stored_results_serialize_banner_enable_status === 'on' ) {
    $setting_enable_or_not_thankyou = " ( Enable ) ";
    $setting_enable_or_color_thankyou = "green";
} else {
    $setting_enable_or_not_thankyou = " ( Disable ) ";
    $setting_enable_or_color_thankyou = "red";
}

?>
                    <a class="accordion-section-title"
                        href="#wbm-enable-banner-for-thankyou-page"><?php 
esc_html_e( 'Banner for thank you page', 'banner-management-for-woocommerce' );
?>
                        <span id="thankyou_page_status_enable_or_disable"
                            class="thankyou_page_status_enable_or_disable <?php 
echo  esc_attr( $setting_enable_or_color_thankyou ) ;
?>"><?php 
esc_html_e( $setting_enable_or_not_thankyou, 'banner-management-for-woocommerce' );
?></span></a>
                    <div id="wbm-enable-banner-for-thankyou-page" class="accordion-section-content">
                        <div class="woocommerce-banner-managment-thank-you-setting-admin">
                            <table class="form-table" id="form-table-wbm-thankyou-page">
                                <tbody>
                                    <tr>
                                        <th scope="row"><label class="wbm_leble_setting_css"
                                                for="wbm_shop_setting_thank_you_page_enable"><?php 
esc_html_e( 'Enable/Disable', 'banner-management-for-woocommerce' );
?></label>
                                        </th>
                                        <td><input type="checkbox" value="on"
                                                id="wbm_shop_setting_thank_you_page_enable"
                                                class="wbm_shop_setting_thank_you_page_enable_or_not" <?php 
checked( $wbm_thankyou_page_stored_results_serialize_banner_enable_status, 'on' );
?>></td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php 
$display_option_checkout = 'block';
if ( 'on' !== $wbm_thankyou_page_stored_results_serialize_banner_enable_status ) {
    $display_option_checkout = 'none';
}
?>
                            <div
                                class="wbm-thank-you-page-upload-image-html <?php 
echo  esc_attr( $display_option_checkout ) ;
?>">
                                <fieldset class="innerbanner">
                                    <legend><?php 
esc_html_e( 'General Settings', 'banner-management-for-woocommerce' );
?></legend>
                                    <?php 
?>
                                        <table class="form-table" id="form-table">
                                            <tbody>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="wbm_thankyou_page_single_upload_file_button"><?php 
esc_html_e( 'Banner Image', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td>
                                                        <a class='wbm_thankyou_page_single_upload_file_button button'
                                                            id="wbm_thankyou_page_single_upload_file_button"
                                                            uploader_title="<?php 
esc_attr_e( 'Select File', 'banner-management-for-woocommerce' );
?>"
                                                            uploader_button_text="<?php 
esc_attr_e( 'Include File', 'banner-management-for-woocommerce' );
?>"><?php 
esc_html_e( 'Upload File', 'banner-management-for-woocommerce' );
?></a>
                                                        <a
                                                            class='wbm_thankyou_page_remove_single_file button'><?php 
esc_html_e( 'Remove File', 'banner-management-for-woocommerce' );
?></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"></th>
                                                    <?php 

if ( $wbm_thankyou_page_stored_results_serialize_banner_src === '' ) {
    $thankyou_page_benner_css = "no-image";
} else {
    $thankyou_page_benner_css = "yes-image";
}

?>
                                                    <td>
                                                        <img class="wbm_thankyou_page_cat_banner_img_admin_single <?php 
echo  esc_attr( $thankyou_page_benner_css ) ;
?>"
                                                            src="<?php 
echo  esc_url( $wbm_thankyou_page_stored_results_serialize_banner_src ) ;
?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="thankyou_page_banner_single_image_link"><?php 
esc_html_e( 'Banner Image Link', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td><input type="url" id="thankyou_page_banner_single_image_link"
                                                            title="<?php 
esc_attr_e( 'Example: https://multidots.com', 'banner-management-for-woocommerce' );
?>"
                                                            name='term_meta[banner_link]'
                                                            value='<?php 
echo  esc_attr( $wbm_thankyou_page_stored_results_serialize_banner_link ) ;
?>' />
                                                        <p><label class="banner_link_label"
                                                                for="thankyou_page_banner_single_image_link"><em><?php 
esc_html_e( 'Where users will be directed if they click on the banner.', 'banner-management-for-woocommerce' );
?></em></label>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="wbm_thankyou_page_select_banner_target"><?php 
esc_html_e( 'Select Link Target', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td><select name="wbm_thankyou_page_select_banner_target"
                                                            id="wbm_thankyou_page_select_banner_target" class="">
                                                            <option value="self"
                                                                <?php 
selected( $wbm_thankyou_page_stored_results_serialize_banner_target, 'self' );
?>>
                                                                <?php 
esc_html_e( 'Self window', 'banner-management-for-woocommerce' );
?>
                                                            </option>
                                                            <option value="blank"
                                                                <?php 
selected( $wbm_thankyou_page_stored_results_serialize_banner_target, 'blank' );
?>>
                                                                <?php 
esc_html_e( 'New window', 'banner-management-for-woocommerce' );
?>
                                                            </option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="wbm_thankyou_page_select_banner_relation"><?php 
esc_html_e( 'Select Link Relation', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td><select name="wbm_thankyou_page_select_banner_relation"
                                                            id="wbm_thankyou_page_select_banner_relation" class="">
                                                            <option value="follow"
                                                                <?php 
selected( $wbm_thankyou_page_stored_results_serialize_banner_relation, 'follow' );
?>>
                                                                <?php 
esc_html_e( 'Follow', 'banner-management-for-woocommerce' );
?>
                                                            </option>
                                                            <option value="nofollow"
                                                                <?php 
selected( $wbm_thankyou_page_stored_results_serialize_banner_relation, 'nofollow' );
?>>
                                                                <?php 
esc_html_e( 'No follow', 'banner-management-for-woocommerce' );
?>
                                                            </option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php 
?>
                            </div>
                        </div>
                    </div>
                    <!--end .accordion-section-content-->
                </div>
                <!--end .accordion-section-->
                <!--end .accordion-section-->
            </div>
        </fieldset>
        <fieldset class="wbm_global common_settings wbm-old-layout-sec-main">
            <div class="wbm-new-layout-modal-main">
                <div class="wbm-modal-outer">
                    <div class="wbm-modal-inner">
                        <div class="wbm-modal-wrapper">
                            <div class="wbm-modal-header">
                                <span class="dashicons dashicons-no-alt modal-close-btn"></span>
                                <h3 class="wbm-modal-title"><?php 
esc_html_e( 'Start With New Layout', 'banner-management-for-woocommerce' );
?></h3>
                            </div>
                            <div class="wbm-modal-body">
                                <p><?php 
esc_html_e( 'This feature has been deprecated from version 2.2.3. So, please start with our new layout for creating products & categories sliders.', 'banner-management-for-woocommerce' );
?></p>
                            </div>
                            <div class="wbm-modal-footer">
                                <a href="<?php 
echo  esc_url( admin_url( '/admin.php?page=wcbm-sliders-settings&tab=wcbm-category-sliders' ) ) ;
?>"><?php 
esc_html_e( 'Click here to start', 'banner-management-for-woocommerce' );
?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <legend>
                <div class="wbm-setting-header">
                    <h2><?php 
esc_html_e( 'Banner Slider Settings', 'banner-management-for-woocommerce' );
?></h2>
                </div>
            </legend>   
            <p><?php 
esc_html_e( 'You can set slider for Woocommerce products and categories.', 'banner-management-for-woocommerce' );
?></p>
            <div class="accordion">
                <!--start .accordion-section-->
                <div class="accordion-section">
                    <a class="accordion-section-title"
                        href="#wbm-enable-for-product-slider"><?php 
esc_html_e( 'WooCommerce product slider settings ', 'banner-management-for-woocommerce' );
?>
                    </a>
                    <div id="wbm-enable-for-product-slider" class="accordion-section-content">
                        <div class="wbm-new-layout-btn">
                            <a href="<?php 
echo  esc_url( admin_url( '/admin.php?page=wcbm-sliders-settings&tab=wcbm-product-sliders' ) ) ;
?>" class="button button-primary"><?php 
esc_html_e( 'Start With New Layout', 'banner-management-for-woocommerce' );
?></a>
                        </div>
                        <div class="global_settings">
                            <div class="wbm-prod-slider-settings-html">
                                <fieldset class="innerbanner">
                                    <legend><?php 
esc_html_e( 'Select Shortcode Options', 'banner-management-for-woocommerce' );
?></legend>
                                    <div class="wbm-widgets-cols">
                                        <div class="wbm-col-left">
                                            <div class="wbm-widgets-left">
                                                <table class="form-table" id="form-table-wbm-thankyou-page">
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">
                                                                <label class="wbm_leble_setting_css" for="wbm_prod_slider_setting_on_sale"><?php 
esc_html_e( 'On Sale', 'banner-management-for-woocommerce' );
?></label>
                                                                <span class="banner-woocommerce-help-tip">
                                                                    <div class="alert-desc"><?php 
esc_html_e( 'Enable this option if you want to showcase on sale products in slider.', 'banner-management-for-woocommerce' );
?></div>
                                                            </span>
                                                            </th>
                                                            <td>
                                                                <label class="wbm_prod_slider_toggle_switch">
                                                                    <input disabled type="checkbox" value="on" id="wbm_prod_slider_setting_on_sale" class="wbm_prod_slider_setting_on_sale" <?php 
checked( $product_slider_settings_on_sale_val, 'on' );
?>>
                                                                    <span class="wbm_prod_slider_toggle_btn"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <label class="wbm_leble_setting_css" for="wbm_prod_slider_setting_on_featured_product"><?php 
esc_html_e( 'Featured Products', 'banner-management-for-woocommerce' );
?></label>
                                                                <span class="banner-woocommerce-help-tip">
                                                                    <div class="alert-desc"><?php 
esc_html_e( 'Select Featured Products which you want to display on slider.', 'banner-management-for-woocommerce' );
?></div>
                                                            </span>
                                                            </th>
                                                            <td>
                                                                <?php 
?>
                                                                <select disabled name="featured-product[]" multiple="multiple" id="featured-product" data-placeholder="<?php 
esc_attr_e( 'Select a Featured Product', 'banner-management-for-woocommerce' );
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
        echo  ( in_array( $featured_query->post->ID, $product_slider_settings_featured_prod_val ) ? 'selected' : '' ) ;
        ?>><?php 
        the_title();
        ?></option>
                                                                    <?php 
    }
}
?>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <label class="wbm_leble_setting_css" for="wbm-price-range"><?php 
esc_html_e( 'Price Range', 'banner-management-for-woocommerce' );
?></label>
                                                                <span class="banner-woocommerce-help-tip">
                                                                    <div class="alert-desc"><?php 
esc_html_e( 'Select Price range for display products by price range.', 'banner-management-for-woocommerce' );
?></div>
                                                            </span>
                                                            </th>
                                                            <td>
                                                                <?php 
//Get all products
$args = array();
if ( function_exists( 'wc_get_products' ) ) {
    $products = wc_get_products( $args );
}
//get all prices
$all_prices = array();
if ( isset( $products ) && !empty($products) ) {
    foreach ( $products as $product ) {
        $all_prices[] = $product->get_price();
    }
}
//Get maximum price value from the price array
$max_price = '';

if ( isset( $all_prices ) && !empty($all_prices) ) {
    array_values( array_filter( $all_prices ) );
    rsort( $all_prices );
    $max_price = $all_prices['0'];
}

?>
                                                                <input type="hidden" id="wbm_max_price" value="<?php 
esc_attr_e( $product_slider_price_range_val, 'banner-management-for-woocommerce' );
?>" max-price="<?php 
esc_attr_e( $max_price, 'banner-management-for-woocommerce' );
?>">
                                                                <input disabled type="text" name="wbm_prod_slider_setting_price" id="wbm-price-range" >
                                                                <div id="wbm-price-range-slider"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <?php 
?>
                                                                    <label class="wbm_leble_setting_css wcbm-in-pro"><?php 
esc_html_e( 'By Categories', 'banner-management-for-woocommerce' );
?><span><?php 
esc_html_e( ' - Available in pro', 'banner-management-for-woocommerce' );
?></span></label>
                                                                    <?php 
?>
                                                                <span class="banner-woocommerce-help-tip">
                                                                    <div class="alert-desc"><?php 
esc_html_e( 'Select category if you want to display product by categories in slider.', 'banner-management-for-woocommerce' );
?></div>
                                                            </span>
                                                            </th>
                                                            <td>
                                                                <?php 
?>
                                                                    <select name="product-categories[]" multiple="multiple" data-placeholder="<?php 
esc_attr_e( 'Select a Category', 'banner-management-for-woocommerce' );
?>" id="product-categories">
                                                                    </select>
                                                                    <?php 
?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="wbm-col-right">
                                            <div class="wbm-widgets-right">
                                                <div class="wbm-shortcode-content">
                                                    <h2 class="wbm-shortcode-title"><?php 
esc_html_e( 'Shortcode', 'banner-management-for-woocommerce' );
?></h2>
                                                    <p><?php 
esc_html_e( 'Copy and paste this shortcode into your posts or pages:', 'banner-management-for-woocommerce' );
?></p>
                                                    <div class="wbm-shortcode-wrap">
                                                        <div class="wbm-code">[wcbm_product_slider<span class="on-sale"></span><span class="featured-prod"></span><span class="price-range"></span><span class="categories"></span>]</div>
                                                        <div class="wbm-after-copy-text" style="margin-top:6px;opacity:0;"><?php 
esc_html_e( 'Shortcode  Copied to Clipboard!', 'banner-management-for-woocommerce' );
?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end .accordion-section-->
                <!--start .accordion-section-->
                <div class="accordion-section">
                    <a class="accordion-section-title"
                        href="#wbm-enable-for-category-slider"><?php 
esc_html_e( 'WooCommerce categories slider settings ', 'banner-management-for-woocommerce' );
?>
                    </a>
                    <div id="wbm-enable-for-category-slider" class="accordion-section-content">
                        <div class="wbm-new-layout-btn">
                            <a href="<?php 
echo  esc_url( admin_url( '/admin.php?page=wcbm-sliders-settings&tab=wcbm-category-sliders' ) ) ;
?>" class="button button-primary"><?php 
esc_html_e( 'Start With New Layout', 'banner-management-for-woocommerce' );
?></a>
                        </div>
                        <div class="global_settings">
                            <div class="wbm-cat-slider-settings-html">
                                <fieldset class="innerbanner">
                                    <legend><?php 
esc_html_e( 'Select Shortcode Options', 'banner-management-for-woocommerce' );
?></legend>
                                    <div class="wbm-widgets-cols">
                                        <div class="wbm-col-left">
                                            <div class="wbm-widgets-left">
                                                <table class="form-table" id="form-table-wbm-thankyou-page">
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">
                                                                <label class="wbm_leble_setting_css"><?php 
esc_html_e( 'Select Categories', 'banner-management-for-woocommerce' );
?></label>
                                                                <span class="banner-woocommerce-help-tip">
                                                                    <div class="alert-desc"><?php 
esc_html_e( 'Select categories name which you want to showcase in slider.', 'banner-management-for-woocommerce' );
?></div>
                                                            </span>
                                                            </th>
                                                            <td>
                                                                <select disabled name="categories-slider[]" multiple="multiple" data-placeholder="<?php 
esc_attr_e( 'Select a Category', 'banner-management-for-woocommerce' );
?>" id="categories-slider">
                                                                    <?php 
// get woocommerce products categories
$cat_slider_terms = get_terms( 'product_cat', array(
    'hide_empty' => false,
) );
if ( !empty($cat_slider_terms) && !is_wp_error( $cat_slider_terms ) ) {
    foreach ( $cat_slider_terms as $product_term ) {
        ?>
                                                                            <option value="<?php 
        echo  esc_attr( $product_term->term_id ) ;
        ?>" <?php 
        echo  ( in_array( $product_term->term_id, $product_cat_slider_settings_cat_val ) ? 'selected' : '' ) ;
        ?>>
                                                                                <?php 
        echo  esc_html( $product_term->name ) ;
        ?></option>
                                                                            <?php 
    }
}
?>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <label class="wbm_leble_setting_css" for="wbm_cat_slider_setting_by_title"><?php 
esc_html_e( 'Title', 'banner-management-for-woocommerce' );
?></label>
                                                                <span class="banner-woocommerce-help-tip">
                                                                    <div class="alert-desc"><?php 
esc_html_e( 'Enable this option if you want to showcase the category title in slider.', 'banner-management-for-woocommerce' );
?></div>
                                                            </span>
                                                            </th>
                                                            <td>
                                                                <label class="wbm_prod_slider_toggle_switch">
                                                                    <input type="checkbox" value="on" id="wbm_cat_slider_setting_by_title" class="wbm_cat_slider_setting_by_title" checked="checked" disabled="disabled">
                                                                    <span class="wbm_prod_slider_toggle_btn"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <label class="wbm_leble_setting_css" for="wbm_cat_slider_setting_feature_img"><?php 
esc_html_e( 'Feature Image', 'banner-management-for-woocommerce' );
?></label>
                                                                <span class="banner-woocommerce-help-tip">
                                                                    <div class="alert-desc"><?php 
esc_html_e( 'Enable this option if you want to showcase the category feature image in slider.', 'banner-management-for-woocommerce' );
?></div>
                                                            </span>
                                                            </th>
                                                            <td>
                                                                <?php 
if ( empty($product_cat_slider_settings_feature_img_val) ) {
    $product_cat_slider_settings_feature_img_val = 'on';
}
?>
                                                                <label class="wbm_prod_slider_toggle_switch">
                                                                    <input disabled type="checkbox" value="on" id="wbm_cat_slider_setting_feature_img" class="wbm_cat_slider_setting_feature_img" <?php 
echo  ( isset( $product_cat_slider_settings_feature_img_val ) && $product_cat_slider_settings_feature_img_val === 'on' ? 'checked="checked"' : '' ) ;
?>>
                                                                    <span class="wbm_prod_slider_toggle_btn"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <label class="wbm_leble_setting_css" for="wbm_cat_slider_setting_by_desc"><?php 
esc_html_e( 'Description', 'banner-management-for-woocommerce' );
?></label>
                                                                <span class="banner-woocommerce-help-tip">
                                                                    <div class="alert-desc"><?php 
esc_html_e( 'Enable this option if you want to showcase the category description in slider.', 'banner-management-for-woocommerce' );
?></div>
                                                            </span>
                                                            </th>
                                                            <td>
                                                                <?php 
if ( empty($product_cat_slider_settings_by_desc_val) ) {
    $product_cat_slider_settings_by_desc_val = 'on';
}
?>
                                                                <label class="wbm_prod_slider_toggle_switch">
                                                                    <input disabled type="checkbox" value="on" id="wbm_cat_slider_setting_by_desc" class="wbm_cat_slider_setting_by_desc" <?php 
echo  ( isset( $product_cat_slider_settings_by_desc_val ) && $product_cat_slider_settings_by_desc_val === 'on' ? 'checked="checked"' : '' ) ;
?>>
                                                                    <span class="wbm_prod_slider_toggle_btn"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <?php 
?>
                                                                    <label class="wbm_leble_setting_css wcbm-in-pro" for="wbm_cat_slider_setting_by_count"><?php 
esc_html_e( 'Total Quantity', 'banner-management-for-woocommerce' );
?><span><?php 
esc_html_e( ' - Available in pro', 'banner-management-for-woocommerce' );
?></span></label>
                                                                    <?php 
?>
                                                                <span class="banner-woocommerce-help-tip">
                                                                    <div class="alert-desc"><?php 
esc_html_e( 'Enable this option if you want to showcase the category\'s product count in slider.', 'banner-management-for-woocommerce' );
?></div>
                                                            </span>
                                                            </th>
                                                            <td>
                                                                <?php 
?>
                                                                    <label class="wbm_prod_slider_toggle_switch wbm-pro-toggle">
                                                                        <input type="checkbox" value="on" id="wbm_cat_slider_setting_by_count" class="wbm_cat_slider_setting_by_count" disabled="disabled">
                                                                        <span class="wbm_prod_slider_toggle_btn"></span>
                                                                    </label>
                                                                    <?php 
?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="wbm-col-right">
                                            <div class="wbm-widgets-right">
                                                <div class="wbm-shortcode-content">
                                                    <h2 class="wbm-shortcode-title"><?php 
esc_html_e( 'Shortcode', 'banner-management-for-woocommerce' );
?></h2>
                                                    <p><?php 
esc_html_e( 'Copy and paste this shortcode into your posts or pages:', 'banner-management-for-woocommerce' );
?></p>
                                                    <div class="wbm-shortcode-wrap">
                                                        <div class="wbm-code">[wcbm_category_slider<span class="cat-slider"></span><span class="by-title"></span><span class="by-feature-img"></span><span class="by-description"></span><span class="by-count"></span>]
                                                        </div>
                                                        <div class="wbm-after-copy-text" style="margin-top:6px;opacity:0;"><?php 
esc_html_e( 'Shortcode  Copied to Clipboard!', 'banner-management-for-woocommerce' );
?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end .accordion-section-->
            </div>  
        </fieldset>
        <fieldset class="wbm_global common_settings">
            <legend>
                <div class="wbm-setting-header">
                    <h2><?php 
esc_html_e( 'Global Settings', 'banner-management-for-woocommerce' );
?></h2>
                </div>
            </legend>   
            <p><?php 
esc_html_e( 'You can set global options for Woocommerce pages and products.', 'banner-management-for-woocommerce' );
?></p>
            <div class="accordion">
                <!--start .accordion-section-->
                <div class="accordion-section">
                    <a class="accordion-section-title"
                        href="#wbm-enable-for-banner-width"><?php 
esc_html_e( 'Default woocommerce page banner settings', 'banner-management-for-woocommerce' );
?>
                    </a>
                    <div id="wbm-enable-for-banner-width" class="accordion-section-content">
                        <div class="global_settings">
                            <table class="form-table" id="form-table">
                                <tr>
                                    <th scope="row"><label class="wbm_leble_setting_css"
                                            for="shop_page_banner_image_size"><?php 
esc_html_e( 'Select Banner Size', 'banner-management-for-woocommerce' );
?></label>
                                            <span class="banner-woocommerce-help-tip"><div class="alert-desc">
                                                <?php 
esc_html_e( 'Select the size of banner from dropdown which will apply on all the default woocommerce pages.', 'banner-management-for-woocommerce' );
?>
                                            </div></span>
                                    </th>
                                    <td><select name="term_meta[shop_page_select_size]" class="shop-select-size-type"
                                            id="shop_page_banner_image_size">
                                            <option value=""
                                                <?php 
selected( $wbm_shop_page_stored_results_serialize_banner_image_size, '' );
?>>
                                                <?php 
esc_html_e( '-- Select Banner Size --', 'banner-management-for-woocommerce' );
?>
                                            </option>
                                            <option value="25"
                                                <?php 
selected( $wbm_shop_page_stored_results_serialize_banner_image_size, '25' );
?>>
                                                <?php 
esc_html_e( '25%', 'banner-management-for-woocommerce' );
?>
                                            </option>
                                            <option value="50"
                                                <?php 
selected( $wbm_shop_page_stored_results_serialize_banner_image_size, '50' );
?>>
                                                <?php 
esc_html_e( '50%', 'banner-management-for-woocommerce' );
?>
                                            </option>
                                            <option value="75"
                                                <?php 
selected( $wbm_shop_page_stored_results_serialize_banner_image_size, '75' );
?>>
                                                <?php 
esc_html_e( '75%', 'banner-management-for-woocommerce' );
?>
                                            </option>
                                            <option value="100"
                                                <?php 
selected( $wbm_shop_page_stored_results_serialize_banner_image_size, '100' );
?>>
                                                <?php 
esc_html_e( '100%', 'banner-management-for-woocommerce' );
?>
                                            </option>
                                            <option value="1080"
                                                <?php 
selected( $wbm_shop_page_stored_results_serialize_banner_image_size, '1080' );
?>>
                                                <?php 
esc_html_e( 'Fixed to container(1080px)', 'banner-management-for-woocommerce' );
?>
                                            </option>
                                        </select></td>
                                </tr>
                                <tr>
                            </table>
                        </div>
                    </div>
                </div>  
                <!--end .accordion-section-->
                <!--start .accordion-section-->
                <div class="accordion-section">
                    <?php 

if ( $wbm_banner_detail_page_stored_results_serialize_banner_enable_status === 'on' || $wbm_banner_detail_page_section_stored_results_serialize_banner_enable_status === 'on' ) {
    $setting_enable_or_not_banner_detail = " ( Enable ) ";
    $setting_enable_or_color_banner_detail = "green";
} else {
    $setting_enable_or_not_banner_detail = " ( Disable ) ";
    $setting_enable_or_color_banner_detail = "red";
}

?>
                    <a class="accordion-section-title"
                        href="#wbm-enable-banner-for-product-detail-page"><?php 
esc_html_e( 'Product page banner settings', 'banner-management-for-woocommerce' );
?>
                        <span id="banner_detail_page_status_enable_or_disable"
                            class="banner_detail_page_status_enable_or_disable <?php 
echo  esc_attr( $setting_enable_or_color_banner_detail ) ;
?>"><?php 
esc_html_e( $setting_enable_or_not_banner_detail, 'banner-management-for-woocommerce' );
?></span></a>
                    <div id="wbm-enable-banner-for-product-detail-page" class="accordion-section-content">
                        <div class="woocommerce-banner-managment-thank-you-setting-admin">
                            <table class="form-table" id="form-table-wbm-banner_detail-page">
                                <tbody>
                                <tr>
                                        <th scope="row"><label class="wbm_leble_setting_css"
                                                for="wbm_shop_setting_banner_detail_page_enable"><?php 
esc_html_e( 'Enable product page banner settings', 'banner-management-for-woocommerce' );
?></label>
                                                <span class="banner-woocommerce-help-tip"><div class="alert-desc">
                                                <?php 
esc_html_e( 'If selected then, enable the product page banner setting meta box section for each product.', 'banner-management-for-woocommerce' );
?>
                                            </div></span>
                                        </th>
                                        <td><input type="checkbox" value="on"
                                                id="wbm_shop_setting_banner_detail_page_section_enable"
                                                class="wbm_shop_setting_banner_detail_page_section_enable_or_not" <?php 
checked( $wbm_banner_detail_page_section_stored_results_serialize_banner_enable_status, 'on' );
?>></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label class="wbm_leble_setting_css"
                                                for="wbm_shop_setting_banner_detail_page_enable"><?php 
esc_html_e( 'Set common banner for all product', 'banner-management-for-woocommerce' );
?></label>
                                                <span class="banner-woocommerce-help-tip"><div class="alert-desc">
                                                <?php 
esc_html_e( 'If selected then, override all the product page banner se
                                                ttings and set these updated settings globally for all products.', 'banner-management-for-woocommerce' );
?>
                                            </div></span>
                                        </th>
                                        <td><input type="checkbox" value="on"
                                                id="wbm_shop_setting_banner_detail_page_enable"
                                                class="wbm_shop_setting_banner_detail_page_enable_or_not" <?php 
checked( $wbm_banner_detail_page_stored_results_serialize_banner_enable_status, 'on' );
?>></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php 
$display_option_checkout = 'block';
if ( 'on' !== $wbm_banner_detail_page_stored_results_serialize_banner_enable_status ) {
    $display_option_checkout = 'none';
}
?>
                        <div
                            class="wbm-banner_detail-page-upload-image-html <?php 
echo  esc_attr( $display_option_checkout ) ;
?>">
                            <fieldset class="innerbanner">
                                <legend><?php 
esc_html_e( 'General Settings', 'banner-management-for-woocommerce' );
?></legend>
                                <?php 
?>
                                        <table class="form-table" id="form-table">
                                            <tbody>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="wbm_banner_detail_page_single_upload_file_button"><?php 
esc_html_e( 'Banner Image', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td>
                                                        <a class='wbm_banner_detail_page_single_upload_file_button button'
                                                            id="wbm_banner_detail_page_single_upload_file_button"
                                                            uploader_title="<?php 
esc_attr_e( 'Select File', 'banner-management-for-woocommerce' );
?>"
                                                            uploader_button_text="<?php 
esc_attr_e( 'Include File', 'banner-management-for-woocommerce' );
?>"><?php 
esc_html_e( 'Upload File', 'banner-management-for-woocommerce' );
?></a>
                                                        <a
                                                            class='wbm_banner_detail_page_remove_single_file button'><?php 
esc_html_e( 'Remove File', 'banner-management-for-woocommerce' );
?></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"></th>
                                                    <?php 

if ( $wbm_banner_detail_page_stored_results_serialize_banner_src === '' ) {
    $banner_detail_page_benner_css = "no-image";
} else {
    $banner_detail_page_benner_css = "yes-image";
}

?>
                                                    <td>
                                                        <img class="wbm_banner_detail_page_cat_banner_img_admin_single <?php 
echo  esc_attr( $banner_detail_page_benner_css ) ;
?>"
                                                            src="<?php 
echo  esc_url( $wbm_banner_detail_page_stored_results_serialize_banner_src ) ;
?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="banner_detail_page_banner_single_image_link"><?php 
esc_html_e( 'Banner Image Link', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td><input type="url" id="banner_detail_page_banner_single_image_link"
                                                            title="<?php 
esc_attr_e( 'Example: https://multidots.com', 'banner-management-for-woocommerce' );
?>"
                                                            name='term_meta[banner_link]'
                                                            value='<?php 
echo  esc_attr( $wbm_banner_detail_page_stored_results_serialize_banner_link ) ;
?>' />
                                                        <p><label class="banner_link_label"
                                                                for="banner_detail_page_banner_single_image_link"><em><?php 
esc_html_e( 'Where users will be directed if they click on the banner.', 'banner-management-for-woocommerce' );
?></em></label>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="wbm_banner_global_page_select_banner_target"><?php 
esc_html_e( 'Select Link Target', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td><select name="wbm_banner_global_page_select_banner_target"
                                                            id="wbm_banner_global_page_select_banner_target" class="">
                                                            <option value="self"
                                                                <?php 
selected( $wbm_banner_global_page_stored_results_serialize_banner_target, 'self' );
?>>
                                                                <?php 
esc_html_e( 'Self window', 'banner-management-for-woocommerce' );
?>
                                                            </option>
                                                            <option value="blank"
                                                                <?php 
selected( $wbm_banner_global_page_stored_results_serialize_banner_target, 'blank' );
?>>
                                                                <?php 
esc_html_e( 'New window', 'banner-management-for-woocommerce' );
?>
                                                            </option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label class="wbm_leble_setting_css"
                                                            for="wbm_banner_global_page_select_banner_relation"><?php 
esc_html_e( 'Select Link Relation', 'banner-management-for-woocommerce' );
?></label>
                                                    </th>
                                                    <td><select name="wbm_banner_global_page_select_banner_relation"
                                                            id="wbm_banner_global_page_select_banner_relation" class="">
                                                            <option value="follow"
                                                                <?php 
selected( $wbm_banner_global_page_stored_results_serialize_banner_relation, 'follow' );
?>>
                                                                <?php 
esc_html_e( 'Follow', 'banner-management-for-woocommerce' );
?>
                                                            </option>
                                                            <option value="nofollow"
                                                                <?php 
selected( $wbm_banner_global_page_stored_results_serialize_banner_relation, 'nofollow' );
?>>
                                                                <?php 
esc_html_e( 'No follow', 'banner-management-for-woocommerce' );
?>
                                                            </option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php 
?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- banner for other pages -->
                <div class="accordion-section">
                    <?php 

if ( $wbm_other_page_stored_results_serialize_banner_enable_status === 'on' || isset( $wbm_banner_other_page_section_stored_results_serialize_banner_enable_status ) && $wbm_banner_other_page_section_stored_results_serialize_banner_enable_status === 'on' ) {
    $setting_enable_or_not = " ( Enable ) ";
    $setting_enable_or_color = "green";
} else {
    $setting_enable_or_not = " ( Disable ) ";
    $setting_enable_or_color = "red";
}

?>
                    <a class="accordion-section-title" href="#wbm-enable-banner-for-other-pages">
                        <?php 
esc_html_e( 'Other new page banner settings', 'banner-management-for-woocommerce' );
?>
                        <span id="other_page_status_enable_or_disable"
                            class="other_page_status_enable_or_disable <?php 
echo  esc_attr( $setting_enable_or_color ) ;
?>">
                            <?php 
esc_html_e( $setting_enable_or_not, 'banner-management-for-woocommerce' );
?>
                        </span>
                    </a>
                    <div id="wbm-enable-banner-for-other-pages" class="accordion-section-content">

                        <table class="form-table" id="form-table-wbm-banner_detail-page">
                            <tbody>
                                <?php 
?>
                                        <tr class="wbm_other_page_setting_banner_detail_in_pro">
                                            <th scope="row"><label class="wbm_leble_setting_css"
                                                    for="wbm_other_page_setting_banner_detail_enable"><?php 
esc_html_e( 'Enable other page banner settings', 'banner-management-for-woocommerce' );
?><span><?php 
esc_html_e( ' - Available in pro', 'banner-management-for-woocommerce' );
?></span></label>
                                                    <span class="banner-woocommerce-help-tip"><div class="alert-desc">
                                                    <?php 
esc_html_e( 'If selected then, enable website other pages banner setting meta box section for each page.', 'banner-management-for-woocommerce' );
?>
                                                </div></span>
                                            </th>
                                            <td><input type="checkbox" value="on"
                                                    id="wbm_other_page_setting_banner_detail_enable"
                                                    class="wbm_other_setting_banner_detail_page_section_enable_or_not"></td>
                                        </tr>
                                        <?php 
?>
                                <tr>
                                    <th scope="row"><label class="wbm_leble_setting_css"
                                            for="wbm_other_page_setting_enable"><?php 
esc_html_e( 'Set global banner for pages', 'banner-management-for-woocommerce' );
?>
                                        </label>
                                        <span class="banner-woocommerce-help-tip">
                                            <div class="alert-desc">
                                                <?php 
esc_html_e( 'If selected then, override all the pages banner settings and set these updated settings globally for all pages.', 'banner-management-for-woocommerce' );
?>
                                            </div>
                                        </span>
                                    </th>
                                    <td><input type="checkbox" value="on" id="wbm_other_page_setting_enable"
                                            class="wbm_other_page_setting_enable_or_not" <?php 
checked( $wbm_other_page_stored_results_serialize_banner_enable_status, 'on' );
?>>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php 
$display_option = 'block';
if ( 'on' !== $wbm_other_page_stored_results_serialize_banner_enable_status ) {
    $display_option = 'none';
}
?>
                        <div class="wbm_other_page_enable_open_div <?php 
echo  esc_attr( $display_option ) ;
?>">
                            <fieldset class="innerbanner">
                                <legend><?php 
esc_html_e( 'General Settings', 'banner-management-for-woocommerce' );
?></legend>
                                <?php 
?>
                                    <table class="form-table" id="form-table">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><label class="wbm_leble_setting_css"
                                                        for="wbm_other_page_single_upload_file_button"><?php 
esc_html_e( 'Banner Image', 'banner-management-for-woocommerce' );
?></label>
                                                </th>
                                                <td><a class='wbm_other_page_single_upload_file_button button'
                                                        id="wbm_other_page_single_upload_file_button"
                                                        uploader_title='Select File'
                                                        uploader_button_text="<?php 
esc_attr_e( 'Include File', 'banner-management-for-woocommerce' );
?>"><?php 
esc_html_e( 'Upload File', 'banner-management-for-woocommerce' );
?></a>
                                                    <a
                                                        class='wbm_other_page_remove_single_file button'><?php 
esc_html_e( 'Remove File', 'banner-management-for-woocommerce' );
?></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"></th>
                                                <?php 

if ( '' === $wbm_other_page_stored_results_serialize_banner_src ) {
    $other_page_benner_css = "no-image";
} else {
    $other_page_benner_css = "yes-image";
}

?>
                                                <td>
                                                    <img class="wbm_other_page_cat_banner_img_admin_single <?php 
echo  esc_attr( $other_page_benner_css ) ;
?>"
                                                        src="<?php 
echo  esc_url( $wbm_other_page_stored_results_serialize_banner_src ) ;
?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label class="wbm_leble_setting_css"
                                                        for="other_page_banner_single_image_link"><?php 
esc_html_e( 'Banner Image Link', 'banner-management-for-woocommerce' );
?></label>
                                                </th>
                                                <td><input type="url" id="other_page_banner_single_image_link"
                                                        title="Example: https://multidots.com"
                                                        name='term_meta[banner_link]'
                                                        value='<?php 
echo  esc_attr( $wbm_other_page_stored_results_serialize_banner_link ) ;
?>' />
                                                    <p><label class="banner_link_label"
                                                            for="other_page_banner_single_image_link"><em><?php 
esc_html_e( 'Where users will be directed if they click on the banner.', 'banner-management-for-woocommerce' );
?></em></label>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label class="wbm_leble_setting_css"
                                                        for="wbm_other_page_select_banner_target"><?php 
esc_html_e( 'Select Link Target', 'banner-management-for-woocommerce' );
?></label>
                                                </th>
                                                <td><select name="wbm_other_page_select_banner_target"
                                                        id="wbm_other_page_select_banner_target" class="">
                                                        <option value="self"
                                                            <?php 
selected( $wbm_other_page_stored_results_serialize_banner_target, 'self' );
?>>
                                                            <?php 
esc_html_e( 'Self window', 'banner-management-for-woocommerce' );
?>
                                                        </option>
                                                        <option value="blank"
                                                            <?php 
selected( $wbm_other_page_stored_results_serialize_banner_target, 'blank' );
?>>
                                                            <?php 
esc_html_e( 'New window', 'banner-management-for-woocommerce' );
?>
                                                        </option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><label class="wbm_leble_setting_css"
                                                        for="wbm_other_page_select_banner_relation"><?php 
esc_html_e( 'Select Link Relation', 'banner-management-for-woocommerce' );
?></label>
                                                </th>
                                                <td><select name="wbm_other_page_select_banner_relation"
                                                        id="wbm_other_page_select_banner_relation" class="">
                                                        <option value="follow"
                                                            <?php 
selected( $wbm_other_page_stored_results_serialize_banner_relation, 'follow' );
?>>
                                                            <?php 
esc_html_e( 'Follow', 'banner-management-for-woocommerce' );
?>
                                                        </option>
                                                        <option value="nofollow"
                                                            <?php 
selected( $wbm_other_page_stored_results_serialize_banner_relation, 'nofollow' );
?>>
                                                            <?php 
esc_html_e( 'No follow', 'banner-management-for-woocommerce' );
?>
                                                        </option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php 
?>
                            </fieldset>

                        </div>
                    </div>
                    <!--end .accordion-section-content-->
                </div>
            </div>
            <!--end .accordion-section-->
        </fieldset>
        <div class="top-save-button">
            <img class="banner-setting-loader"
            src="<?php 
echo  esc_url( plugin_dir_url( __FILE__ ) . 'images/ajax-loader.gif' ) ;
?>" alt="ajax-loader" />
            <input type="button" name="save_wbmshop" id="save_wbm_shop_page_setting" class="button button-primary"
            value="<?php 
echo  esc_attr( 'Save Changes', 'banner-management-for-woocommerce' ) ;
?>">
        </div>
        <fieldset id="product_banner_previou_section" class="wbm_global">
            <legend>
                <div class="wbm-setting-header">
                    <h2><?php 
esc_html_e( 'Products, Common pages, Categories, and Tags preview and settings', 'banner-management-for-woocommerce' );
?></h2>
                </div>
            </legend>
            <div class="accordion">
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#wbm-enable-product-banner-settings-ss"><?php 
esc_html_e( 'Product specific banner settings', 'banner-management-for-woocommerce' );
?></a>
                    <div id="wbm-enable-product-banner-settings-ss" class="accordion-section-content">
                        <p><?php 
esc_html_e( 'You can upload custom banner at the top of your product pages. Easily update the image through your product edit page.', 'banner-management-for-woocommerce' );
?>
                        </p>
                        <p>
                            <a href="javascript:void(0);" class="wbm-open-popup"><?php 
esc_html_e( 'Click here', 'banner-management-for-woocommerce' );
?></a><?php 
esc_html_e( ' to preview product page banner settings.', 'banner-management-for-woocommerce' );
?>
                        </p>
                        <div class="wbm-popup-image">
                            <div class="wbm-popup-outer">
                                <div class="wbm-popup-inner">
                                    <i class="dashicons dashicons-no wbm-popup-close" title="Dismiss"></i>
                                    <img class="preview_category_page_image" src="<?php 
echo  esc_url( plugin_dir_url( __FILE__ ) . 'images/product_banner_metabox_setting_image.png' ) ;
?>">
                                </div>
                            </div>
                        </div>
                        <p>
                            <strong><?php 
esc_html_e( 'Go to product page', 'banner-management-for-woocommerce' );
?></strong>
                            <a target="_blank"
                                href="<?php 
echo  esc_url( site_url() . '/wp-admin/edit.php?post_type=product' ) ;
?>"><?php 
esc_html_e( 'click here', 'banner-management-for-woocommerce' );
?></a>
                        </p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#wbm-enable-other-page-banner-settings-ss"><?php 
esc_html_e( 'Other pages specific banner settings', 'banner-management-for-woocommerce' );
?></a>
                    <div id="wbm-enable-other-page-banner-settings-ss" class="accordion-section-content">
                        <p><?php 
esc_html_e( 'You can upload custom banner at the top of your website other pages. Easily update the image through your website other pages edit page.', 'banner-management-for-woocommerce' );
?>
                        </p>
                        <p>
                            <a href="javascript:void(0);" class="wbm-open-popup"><?php 
esc_html_e( 'Click here', 'banner-management-for-woocommerce' );
?></a><?php 
esc_html_e( ' to preview common page banner settings.', 'banner-management-for-woocommerce' );
?>
                        </p>
                        <div class="wbm-popup-image">
                            <div class="wbm-popup-outer">
                                <div class="wbm-popup-inner">
                                    <i class="dashicons dashicons-no wbm-popup-close" title="Dismiss"></i>
                                    <img class="preview_category_page_image" src="<?php 
echo  esc_url( plugin_dir_url( __FILE__ ) . 'images/page_banner_metabox_setting_image.png' ) ;
?>">
                                </div>
                            </div>
                        </div>
                        <p>
                            <strong><?php 
esc_html_e( 'Go to website other page', 'banner-management-for-woocommerce' );
?></strong>
                            <a target="_blank"
                                href="<?php 
echo  esc_url( site_url() . '/wp-admin/edit.php?post_type=page' ) ;
?>"><?php 
esc_html_e( 'click here', 'banner-management-for-woocommerce' );
?></a>
                        </p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#wbm-enable-category-banner-settings-ss"><?php 
esc_html_e( 'Category specific banner settings', 'banner-management-for-woocommerce' );
?></a>
                    <div id="wbm-enable-category-banner-settings-ss" class="accordion-section-content">
                        <p><?php 
esc_html_e( 'You can upload custom banner at the top of your product category pages. Easily update the image through your product category edit page.', 'banner-management-for-woocommerce' );
?>
                        </p>
                        <p>
                            <a href="javascript:void(0);" class="wbm-open-popup"><?php 
esc_html_e( 'Click here', 'banner-management-for-woocommerce' );
?></a><?php 
esc_html_e( ' to preview product category page banner settings.', 'banner-management-for-woocommerce' );
?>
                        </p>
                        <div class="wbm-popup-image">
                            <div class="wbm-popup-outer">
                                <div class="wbm-popup-inner">
                                    <i class="dashicons dashicons-no wbm-popup-close" title="Dismiss"></i>
                                    <?php 
?>
                                            <img class="preview_category_page_image" src="<?php 
echo  esc_url( plugin_dir_url( __FILE__ ) . 'images/category_setting_image.png' ) ;
?>">
                                    <?php 
?>
                                </div>
                            </div>
                        </div>
                        <p>
                            <strong><?php 
esc_html_e( 'Go to category page', 'banner-management-for-woocommerce' );
?></strong>
                            <a target="_blank"
                                href="<?php 
echo  esc_url( site_url() . '/wp-admin/edit-tags.php?taxonomy=product_cat&post_type=product' ) ;
?>"><?php 
esc_html_e( 'click here', 'banner-management-for-woocommerce' );
?></a>
                        </p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#wbm-enable-tag-banner-settings-ss"><?php 
esc_html_e( 'Tag specific banner settings', 'banner-management-for-woocommerce' );
?></a>
                    <div id="wbm-enable-tag-banner-settings-ss" class="accordion-section-content">
                        <p><?php 
esc_html_e( 'You can upload custom banner at the top of your product tag pages. Easily update the image through your product tag edit page.', 'banner-management-for-woocommerce' );
?>
                        </p>
                        <p>
                            <a href="javascript:void(0);" class="wbm-open-popup"><?php 
esc_html_e( 'Click here', 'banner-management-for-woocommerce' );
?></a><?php 
esc_html_e( ' to preview product tag page banner settings.', 'banner-management-for-woocommerce' );
?>
                        </p>
                        <div class="wbm-popup-image">
                            <div class="wbm-popup-outer">
                                <div class="wbm-popup-inner">
                                    <i class="dashicons dashicons-no wbm-popup-close" title="Dismiss"></i>
                                    <?php 
?>
                                            <img class="preview_category_page_image" src="<?php 
echo  esc_url( plugin_dir_url( __FILE__ ) . 'images/tag_setting_image.png' ) ;
?>">
                                    <?php 
?>
                                </div>
                            </div>
                        </div>
                        <p>
                            <strong><?php 
esc_html_e( 'Go to Tag page', 'banner-management-for-woocommerce' );
?></strong>
                            <a target="_blank"
                                href="<?php 
echo  esc_url( site_url() . '/wp-admin/edit-tags.php?taxonomy=product_tag&post_type=product' ) ;
?>"><?php 
esc_html_e( 'click here', 'banner-management-for-woocommerce' );
?></a>
                        </p>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>
</div>
