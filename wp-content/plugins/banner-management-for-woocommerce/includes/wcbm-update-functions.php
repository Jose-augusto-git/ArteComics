<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 *
 * Function returns the page banner data with updation of the all version data.
 *
 * @param $page
 *
 * @return array|mixed|object|string|void
 */
function wcbm_get_page_banner_data( $page ) {
	if ( empty( $page ) ) {
		return '';
	}
	$stored_results = '';

	if ( 'thankyou' === $page ) {
		$stored_results = get_option( 'wbm_thankyou_page_stored_data', '' );
	} elseif ( 'shop' === $page ) {
		$stored_results = get_option( 'wbm_shop_page_stored_data', '' );
	} elseif ( 'cart' === $page ) {
		$stored_results = get_option( 'wbm_cart_page_stored_data', '' );
	} elseif ( 'checkout' === $page ) {
		$stored_results = get_option( 'wbm_checkout_page_stored_data', '' );
	} elseif ( 'banner_detail' === $page ) {
		$stored_results = get_option( 'wbm_banner_detail_page_stored_data', '' );
	} elseif ( 'other_pages' === $page ) {
		$stored_results = get_option( 'wbm_other_page_stored_data', '' );
	} elseif ( 'product_sliders' === $page ) {
		$stored_results = get_option( 'wbm_prod_slider_settings_stored_data', '' );
	}

	//check serialized for old version of the plugin.
	if ( is_serialized( $stored_results, true ) ) {
		$page_banner_data = maybe_unserialize( $stored_results );

		return $page_banner_data;
	} else if ( wcbm_isJson( $stored_results ) ) {
		$page_banner_data = json_decode( $stored_results, true );

		return $page_banner_data;
	} else {
		return $stored_results;
	}
}

/**
 * This function update the page banner data.
 *
 * @param $page
 * @param $data
 */
function wcbm_save_page_banner_data( $page, $data ) {
	if ( 'thankyou' === $page && ! empty( $data ) ) {
		$temp_data = wp_json_encode( $data );
		update_option( 'wbm_thankyou_page_stored_data', $temp_data );
	} elseif ( 'shop' === $page && ! empty( $data ) ) {
		$temp_data = wp_json_encode( $data );
		update_option( 'wbm_shop_page_stored_data', $temp_data );
	} elseif ( 'cart' === $page && ! empty( $data ) ) {
		$temp_data = wp_json_encode( $data );
		update_option( 'wbm_cart_page_stored_data', $temp_data );
	} elseif ( 'checkout' === $page && ! empty( $data ) ) {
		$temp_data = wp_json_encode( $data );
		update_option( 'wbm_checkout_page_stored_data', $temp_data );
	} elseif ( 'banner_detail' === $page && ! empty( $data ) ) {
		$temp_data = wp_json_encode( $data );
		update_option( 'wbm_banner_detail_page_stored_data', $temp_data );
	} elseif ( 'other_pages' === $page && ! empty( $data ) ) {
		$temp_data = wp_json_encode( $data );
		update_option( 'wbm_other_page_stored_data', $temp_data );
	}
	elseif ( 'product_sliders' === $page && ! empty( $data ) ) {
		$temp_data = wp_json_encode( $data );
		update_option( 'wbm_prod_slider_settings_stored_data', $temp_data );
	}
}

/**
 * This plugin returns the category banner data with the updated version of data.
 *
 * @param $p_catid
 *
 * @return array|mixed|object|string|void
 */
function wcbm_get_category_banner_data( $p_catid ) {
	if ( empty( $p_catid ) ) {
		return '';
	}

	$cat_stored_results = get_term_meta( $p_catid, "taxonomy_term_$p_catid", true );
	//check data from the option table in old version plugin.
	$cat_stored_results = empty( $cat_stored_results ) ? get_option( "taxonomy_term_$p_catid" ) : $cat_stored_results; //phpcs:ignore

	//check serialized for old version of the plugin.
	if ( is_serialized( $cat_stored_results, true ) ) {
		$cat_banner_data = maybe_unserialize( $cat_stored_results );

		return $cat_banner_data;
	} else if ( wcbm_isJson( $cat_stored_results ) ) {
		$cat_banner_data = json_decode( $cat_stored_results, true );

		return $cat_banner_data;
	} else {

		wcbm_save_cat_banner_data( $p_catid, $cat_stored_results );

		return $cat_stored_results;
	}
}

/**
 * This function update the category banner data.
 *
 * @param $p_catid
 * @param $data
 */
function wcbm_save_cat_banner_data( $p_catid, $data ) {
	if ( ! empty( $p_catid ) && ! empty( $data ) ) {
		$data = wp_json_encode( $data );
		if ( update_term_meta( $p_catid, "taxonomy_term_$p_catid", $data ) ) {
			// Removed older version plugin data from option table.
			delete_option( "taxonomy_term_$p_catid" ); //phpcs:ignore
		}
	}
}

/**
 * This function check weather data is in json format or not.
 *
 * @param $string
 *
 * @return bool
 */
function wcbm_isJson( $string ) {
	if ( ! empty( $string ) && is_array( $string ) ) {
		return false;
	}

	json_decode( $string, true );
	if ( json_last_error() === JSON_ERROR_NONE ) {
		return true;
	}

	return false;
}

function get_banner_class($class_value){
	switch ($class_value) {
		case "25":
			$cat_page_select_size_class = "small_banner";
		  break;
		case "50":
			$cat_page_select_size_class = "medium_banner";
		  break;
		case "75":
			$cat_page_select_size_class = "large_banner";
		  break;
		case "100":
			$cat_page_select_size_class = "actual_banner";
			break;
		case "1080":
			$cat_page_select_size_class = "container_banner";
			break;	
		default:
			$cat_page_select_size_class = "banner";
	}
	return $cat_page_select_size_class;
}