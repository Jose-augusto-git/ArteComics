<?php

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
global  $wcbm_fs ;
$version_label = '';
$version_label = 'Free';
$plugin_slug = 'basic_woo_banner';
$plugin_name = 'Banner Management';
$wcbm_admin_object = new woocommerce_category_banner_management_Admin( '', '' );
?>

<div id="dotsstoremain">
    <div class="all-pad">
        <?php 
$wcbm_admin_object->wcbm_get_promotional_bar( $plugin_slug );
?>
        <header class="dots-header">
            <div class="dots-plugin-details">
                <div class="dots-header-left">
                    <div class="dots-logo-main">
                        <img  src="<?php 
echo  esc_url( plugins_url( 'images/wcbm-logo.png', dirname( dirname( __FILE__ ) ) ) ) ;
?>">
                    </div>
                    <div class="plugin-name">
                        <div class="title"><?php 
esc_html_e( $plugin_name, 'banner-management-for-woocommerce' );
?></div>
                    </div>
                    <span class="version-label"><?php 
echo  esc_html_e( $version_label, 'banner-management-for-woocommerce' ) ;
?></span>
                    <span class="version-number"><?php 
echo  esc_html( 'v2.4.3' ) ;
?></span>
                </div>
                <div class="dots-header-right">
                    <div class="button-dots">
                        <a target="_blank" href="<?php 
echo  esc_url( 'http://www.thedotstore.com/support/' ) ;
?>">
                            <?php 
esc_html_e( 'Support', 'banner-management-for-woocommerce' );
?>
                        </a>
                    </div>
                    <div class="button-dots">
                        <a target="_blank" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/feature-requests/' ) ;
?>">
                            <?php 
esc_html_e( 'Suggest', 'banner-management-for-woocommerce' );
?>
                        </a>
                    </div>
                    <div class="button-dots <?php 
echo  ( wcbm_fs()->is__premium_only() && wcbm_fs()->can_use_premium_code() ? '' : 'last-link-button' ) ;
?>">
                        <a target="_blank" href="<?php 
echo  esc_url( 'https://docs.thedotstore.com/collection/252-woocommerce-category-banner-management' ) ;
?>">
                            <?php 
esc_html_e( 'Help', 'banner-management-for-woocommerce' );
?>
                        </a>
                    </div>

                    <?php 
?>
                        <div class="button-dots">
                            <a class="dots-upgrade-btn" target="_blank" href="<?php 
echo  esc_url( $wcbm_fs->get_upgrade_url() ) ;
?>">
                                <?php 
esc_html_e( 'Upgrade', 'banner-management-for-woocommerce' );
?>
                            </a>
                        </div>
                    <?php 
?>
                </div>
            </div>
            <?php 
$wcbm_setting = '';
$wcbm_slider_setting = '';
$wcbm_glob_setting = '';
$wcbm_banner_account = '';
$active_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$active_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$wcbm_getting_started = ( !empty($active_tab) && 'wcbm-plugin-get-started' === $active_tab ? 'active' : '' );
$wcbm_information = ( !empty($active_tab) && 'wcbm-plugin-information' === $active_tab ? 'active' : '' );
$wbm_category_sliders = ( !empty($active_tab) && 'wcbm-category-sliders' === $active_tab ? 'active' : '' );
$wbm_product_sliders = ( !empty($active_tab) && 'wcbm-product-sliders' === $active_tab ? 'active' : '' );
$wbm_settings_menu = ( isset( $active_page ) && 'wcbm-banner-setting' === $active_page || 'wcbm-plugin-get-started' === $active_page || 'wcbm-plugin-information' === $active_page || !(wcbm_fs()->is__premium_only() && wcbm_fs()->can_use_premium_code()) && 'wcbm-banner-setting-account' === $active_page ? 'active' : '' );
$whsm_free_dashboard = ( isset( $active_page ) && 'wcbm-upgrade-dashboard' === $active_page ? 'active' : '' );
$wbm_display_submenu = ( !empty($wbm_settings_menu) && 'active' === $wbm_settings_menu ? 'display:inline-block' : 'display:none' );
$wbm_slider_menu = ( isset( $active_page ) && 'wcbm-sliders-settings' === $active_page ? 'display:inline-block' : 'display:none' );
if ( 'wcbm-banner-setting' === $active_page || 'wcbm-plugin-get-started' === $active_page || 'wcbm-plugin-information' === $active_page || !(wcbm_fs()->is__premium_only() && wcbm_fs()->can_use_premium_code()) && 'wcbm-banner-setting-account' === $active_page ) {
    $wcbm_setting = 'active';
}
if ( 'wcbm-banner-setting' === $active_page ) {
    $wcbm_glob_setting = 'active';
}
if ( empty($active_tab) && 'wcbm-sliders-settings' === $active_page ) {
    $wcbm_slider_setting = 'active';
}
if ( empty($active_tab) && 'wcbm-banner-setting-account' === $active_page ) {
    $wcbm_banner_account = 'active';
}

if ( 'wcbm-plugin-get-started' === $active_page ) {
    $banner_active_tab = 'active';
} else {
    $banner_active_tab = '';
}


if ( 'wcbm-plugin-information' === $active_page ) {
    $plugin_info_sts = 'active';
} else {
    $plugin_info_sts = '';
}


if ( !empty($active_tab) && 'wcbm-product-sliders' === $active_tab ) {
    $wcbm_product_slider_cls = 'active';
} else {
    $wcbm_product_slider_cls = '';
}


if ( !empty($active_tab) && 'wcbm-category-sliders' === $active_tab ) {
    $wcbm_category_slider_cls = 'active';
} else {
    $wcbm_category_slider_cls = '';
}


if ( !empty($active_tab) && ('wcbm-category-sliders' === $active_tab || 'wcbm-product-sliders' === $active_tab) ) {
    $slider_active_tab = 'active';
} else {
    $slider_active_tab = '';
}

?>
            <div class="dots-menu-main">
                <nav>
                    <ul>
                        <?php 
?>
                                <li>
                                    <a class="dotstore_plugin <?php 
echo  esc_attr( $whsm_free_dashboard ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'wcbm-upgrade-dashboard',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Dashboard', 'banner-management-for-woocommerce' );
?></a>
                                </li>
                                <?php 
?>
                        <li>
                            <a class="dotstore_plugin <?php 
echo  esc_attr( $slider_active_tab ) ;
?>" href="<?php 
echo  esc_url( admin_url( '/admin.php?page=wcbm-sliders-settings&tab=wcbm-category-sliders' ) ) ;
?>"><?php 
esc_html_e( 'Slider Settings', 'banner-management-for-woocommerce' );
?></a>
                        </li>
                        <li>
                            <a class="dotstore_plugin <?php 
echo  esc_attr( $wcbm_setting ) ;
?>" href="<?php 
echo  esc_url( admin_url( '/admin.php?page=wcbm-banner-setting' ) ) ;
?>"><?php 
esc_html_e( 'Settings', 'banner-management-for-woocommerce' );
?></a>
                        </li>
                        <?php 

if ( wcbm_fs()->is__premium_only() && wcbm_fs()->can_use_premium_code() ) {
    ?>
                                <li>
                                    <a class="dotstore_plugin <?php 
    echo  esc_attr( $wcbm_banner_account ) ;
    ?>" href="<?php 
    echo  esc_url( $wcbm_fs->get_account_url() ) ;
    ?>"><?php 
    esc_html_e( 'License', 'banner-management-for-woocommerce' );
    ?></a>
                                </li>
                            <?php 
}

?>
                    </ul>
                </nav>
            </div>
        </header>
        <div class="dots-settings-inner-main">
            <div class="dots-settings-left-side">
                <div class="dotstore-submenu-items" style="<?php 
echo  esc_attr( $wbm_display_submenu ) ;
?>">
                    <ul>
                        <li><a class="<?php 
echo  esc_attr( $wcbm_glob_setting ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'wcbm-banner-setting',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Global Settings', 'banner-management-for-woocommerce' );
?></a></li>
                        <li><a class="<?php 
echo  esc_attr( $banner_active_tab ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'wcbm-plugin-get-started',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'About', 'banner-management-for-woocommerce' );
?></a></li>
                        <li><a class="<?php 
echo  esc_attr( $plugin_info_sts ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'wcbm-plugin-information',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Quick info', 'banner-management-for-woocommerce' );
?></a></li>
                        <?php 

if ( !(wcbm_fs()->is__premium_only() && wcbm_fs()->can_use_premium_code()) ) {
    $check_account_page_exist = menu_page_url( 'wcbm-banner-setting-account', false );
    
    if ( isset( $check_account_page_exist ) && !empty($check_account_page_exist) ) {
        ?>
                                <li>
                                    <a class="<?php 
        echo  esc_attr( $wcbm_banner_account ) ;
        ?>" href="<?php 
        echo  esc_url( wcbm_fs()->get_account_url() ) ;
        ?>"><?php 
        esc_html_e( 'Account', 'banner-management-for-woocommerce' );
        ?></a>
                                </li>
                                <?php 
    }

}

?>
                        <li><a href="<?php 
echo  esc_url( 'https://www.thedotstore.com/plugins/' ) ;
?>" target="_blank"><?php 
esc_html_e( 'Shop Plugins', 'banner-management-for-woocommerce' );
?></a></li>
                    </ul>
                </div>
                <div class="dotstore-submenu-items" style="<?php 
echo  esc_attr( $wbm_slider_menu ) ;
?>">
                    <ul>
                        <li><a class="<?php 
echo  esc_attr( $wcbm_category_slider_cls ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'wcbm-sliders-settings',
    'tab'  => 'wcbm-category-sliders',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Category Slider', 'banner-management-for-woocommerce' );
?></a></li>
                        <li><a class="<?php 
echo  esc_attr( $wcbm_product_slider_cls ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'wcbm-sliders-settings',
    'tab'  => 'wcbm-product-sliders',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Product Slider', 'banner-management-for-woocommerce' );
?></a></li>
                    </ul>
                    <input type="hidden" name="dpb_api_url" id="dpb_api_url" value="<?php 
echo  esc_url( WCBM_STORE_URL ) ;
?>">
                </div>
                <hr class="wp-header-end" />


