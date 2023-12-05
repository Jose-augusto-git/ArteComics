<?php

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';
$plugin_title = "Banner Management for Woocommerce";
$plugin_version_text = "Free Version";
?>
    <div class="wcbm-section-left">
         <div class="wcbm-main-table res-cl">
        <h2><?php 
esc_html_e( 'Quick info', 'banner-management-for-woocommerce' );
?></h2>
        <table class="table-outer">
            <tbody>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Product Type', 'banner-management-for-woocommerce' );
?></td>
                <td class="fr-2"><?php 
esc_html_e( 'WooCommerce Plugin', 'banner-management-for-woocommerce' );
?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Product Name', 'banner-management-for-woocommerce' );
?></td>
                <td class="fr-2"><?php 
esc_html_e( $plugin_title, 'banner-management-for-woocommerce' );
?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Installed Version', 'banner-management-for-woocommerce' );
?></td>
                <td class="fr-2"><?php 
esc_html_e( $plugin_version_text, 'banner-management-for-woocommerce' );
?> <?php 
echo  esc_html( WCBM_PLUGIN_VERSION ) ;
?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'License & Terms of use', 'banner-management-for-woocommerce' );
?></td>
                <td class="fr-2"><a target="_blank"  href="<?php 
echo  esc_url( 'https://www.thedotstore.com/terms-and-conditions/' ) ;
?>">
                        <?php 
esc_html_e( 'Click here', 'banner-management-for-woocommerce' );
?></a>
                    <?php 
esc_html_e( 'to view license and terms of use.', 'banner-management-for-woocommerce' );
?>
                </td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Help & Support', 'banner-management-for-woocommerce' );
?></td>
                <td class="fr-2 wcbm-information">
                    <ul>
                        <li><a target="_blank" href="<?php 
echo  esc_url( site_url( 'wp-admin/admin.php?page=wcbm-plugin-get-started' ) ) ;
?>"><?php 
esc_html_e( 'Quick Start', 'banner-management-for-woocommerce' );
?></a></li>
                        <li><a target="_blank" href="<?php 
echo  esc_url( 'https://docs.thedotstore.com/collection/252-woocommerce-category-banner-management' ) ;
?>"><?php 
esc_html_e( 'Guide Documentation', 'banner-management-for-woocommerce' );
?></a></li>
                        <li><a target="_blank" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/support/' ) ;
?>"><?php 
esc_html_e( 'Support Forum', 'banner-management-for-woocommerce' );
?></a></li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Localization', 'banner-management-for-woocommerce' );
?></td>
                <td class="fr-2"><?php 
esc_html_e( 'English, Spanish, French', 'banner-management-for-woocommerce' );
?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Category page banner', 'banner-management-for-woocommerce' );
?></td>
                <td class="fr-2"><?php 
esc_html_e( '[display_category_banner]', 'banner-management-for-woocommerce' );
?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Product page banner', 'banner-management-for-woocommerce' );
?></td>
                <td class="fr-2"><?php 
esc_html_e( '[display_product_banner]', 'banner-management-for-woocommerce' );
?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Website other page banner', 'banner-management-for-woocommerce' );
?></td>
                <td class="fr-2"><?php 
esc_html_e( '[display_page_banner]', 'banner-management-for-woocommerce' );
?></td>
            </tr>
            </tbody>
        </table>
    </div>
    </div>

    </div>
    </div>
    </div>
    </div>
<?php 
//require_once(plugin_dir_path(__FILE__) .'header/plugin-sidebar.php');