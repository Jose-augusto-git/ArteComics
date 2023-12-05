<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'gutentor_sanitize_color' ) ) :
	/**
	 * Color sanitization callback
	 * https://wordpress.stackexchange.com/questions/257581/escape-hexadecimals-rgba-values
	 *
	 * @since 1.0.0
	 */
	function gutentor_sanitize_color( $color ) {
		if ( empty( $color ) || is_array( $color ) ) {
			return '';
		}

		// If string does not start with 'rgba', then treat as hex.
		// sanitize the hex color and finally convert hex to rgba
		if ( false === strpos( $color, 'rgba' ) ) {
			return sanitize_hex_color( $color );
		}

		// By now we know the string is formatted as an rgba color so we need to further sanitize it.
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );

		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
	}
endif;

if ( ! function_exists( 'gutentor_sanitize_field_background' ) ) :

	/**
	 * Sanitize Field Background
	 *
	 * @since Gutentor 1.0.0
	 *
	 * @param $input
	 * @return array
	 */
	function gutentor_sanitize_field_background( $input, $gutentor_setting ) {

		$input_decoded = json_decode( $input, true );
		$output        = array();

		if ( ! empty( $input_decoded ) ) {
			foreach ( $input_decoded as $key => $value ) {

				switch ( $key ) :
					case 'background-size':
					case 'background-position':
					case 'background-repeat':
					case 'background-attachment':
						$output[ $key ] = sanitize_key( $value );
						break;

					case 'background-image':
						$output[ $key ] = esc_url_raw( $value );
						break;
					case 'background-color':
					case 'background-hover-color':
					case 'background-color-title':
					case 'title-font-color':
					case 'background-color-post':
					case 'site-title-color':
					case 'site-tagline-color':
					case 'post-font-color':
					case 'text-color':
					case 'text-hover-color':
					case 'title-color':
					case 'link-color':
					case 'link-hover-color':
					case 'on-sale-bg':
					case 'on-sale-color':
					case 'out-of-stock-bg':
					case 'out-of-stock-color':
					case 'rating-color':
					case 'grid-list-color':
					case 'grid-list-hover-color':
					case 'categories-color':
					case 'categories-hover-color':
					case 'deleted-price-color':
					case 'deleted-price-hover-color':
					case 'price-color':
					case 'price-hover-color':
					case 'content-color':
					case 'content-hover-color':
					case 'tab-list-color':
					case 'tab-content-color':
					case 'tab-list-border-color':
					case 'tab-content-border-color':
					case 'background-stripped-color':
					case 'button-color':
					case 'button-hover-color':
					case 'icon-color':
					case 'icon-hover-color':
					case 'meta-color':
					case 'next-prev-color':
					case 'next-prev-hover-color':
					case 'button-bg-color':
					case 'button-bg-hover-color':
						$output[ $key ] = gutentor_sanitize_color( $value );
						break;
					default:
						$output[ $key ] = sanitize_text_field( $value );
						break;
				endswitch;
			}
			return wp_json_encode( $output );
		}
		return $input;
	}
endif;

if ( ! function_exists( 'gutentor_sanitize_checkbox' ) ) :
	/*
	 * Boolean check.
	 * @since 2.1.0
	 */
	function gutentor_sanitize_checkbox( $checked ) {
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}
endif;

if ( ! function_exists( 'gutentor_sanitize_array' ) ) :
	/*
	 * Array check.
	 * @since 2.1.0
	 */
	function gutentor_sanitize_array( $checked ) {
		return is_array( $checked ) ? $checked : array();
	}
endif;

