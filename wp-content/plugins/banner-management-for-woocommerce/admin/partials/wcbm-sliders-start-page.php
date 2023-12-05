<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to diplay the sliders settings.
 *
 * @link       http://www.multidots.com/
 * @since      2.3.0
 *
 * @package    woocommerce_category_banner_management
 * @subpackage woocommerce_category_banner_management/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit;
}

$get_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$get_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

if ( 'wcbm-sliders-settings' === $get_page ) {
	
	require_once( plugin_dir_path(__FILE__) .'header/plugin-header.php' );
	if ( 'wcbm-category-sliders' === $get_tab ) {
		require_once( dirname( __FILE__ ) . '/wcbm-category-sliders-page.php' );
		?>
		<div class="wcbm-section-left">
			<div class="wrap woocommerce">
				<form method="post" enctype="multipart/form-data">
					<?php
					$wbm_sliders_page_obj = new wbm_banner_management_category_slider_Page();
					$wbm_sliders_page_obj->wcbm_category_sliders_output();
					?>
				</form>
			</div>
		</div>
		<?php
	} else {
		require_once( dirname( __FILE__ ) . '/wcbm-product-sliders-page.php' );
		?>
		<div class="wcbm-section-left">
			<div class="wrap woocommerce">
				<form method="post" enctype="multipart/form-data">
					<?php
					$wbm_sliders_page_obj = new wbm_banner_management_product_slider_Page();
					$wbm_sliders_page_obj->wcbm_product_sliders_output();
					?>
				</form>
			</div>
		</div>
		<?php
	}
}