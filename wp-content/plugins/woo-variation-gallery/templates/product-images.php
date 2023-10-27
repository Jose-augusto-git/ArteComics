<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$product_id = $product->get_id();

$default_attributes = woo_variation_gallery()->get_frontend()->get_product_default_attributes( $product_id );

$default_variation_id = woo_variation_gallery()->get_frontend()->get_product_default_variation_id( $product, $default_attributes );

$product_type = $product->get_type();

$columns = absint( woo_variation_gallery()->get_option( 'thumbnails_columns', apply_filters( 'woo_variation_gallery_default_thumbnails_columns', 4 ) ) );

$post_thumbnail_id = $product->get_image_id();

$attachment_ids = $product->get_gallery_image_ids();

$has_post_thumbnail = has_post_thumbnail();

// No main image but gallery
if ( ! $has_post_thumbnail && count( $attachment_ids ) > 0 ) {
	$post_thumbnail_id = $attachment_ids[0];
	array_shift( $attachment_ids );
	$has_post_thumbnail = true;
}

if ( 'variable' === $product_type && $default_variation_id > 0 ) {

	$product_variation = woo_variation_gallery()->get_frontend()->get_available_variation( $product_id, $default_variation_id );

	if ( isset( $product_variation['image_id'] ) ) {
		$post_thumbnail_id  = $product_variation['image_id'];
		$has_post_thumbnail = true;
	}

	if ( isset( $product_variation['variation_gallery_images'] ) ) {
		$attachment_ids = wp_list_pluck( $product_variation['variation_gallery_images'], 'image_id' );
		array_shift( $attachment_ids );
	}
}

$has_gallery_thumbnail = ( $has_post_thumbnail && ( count( $attachment_ids ) > 0 ) );

$only_has_post_thumbnail = ( $has_post_thumbnail && ( count( $attachment_ids ) === 0 ) );

// $wrapper = sanitize_text_field( get_option( 'woo_variation_gallery_and_variation_wrapper', apply_filters( 'woo_variation_gallery_and_variation_default_wrapper', '.product' ) ) )

$slider_js_options = array(
	'slidesToShow'   => 1,
	'slidesToScroll' => 1,
	'arrows'         => wc_string_to_bool( woo_variation_gallery()->get_option( 'slider_arrow', 'yes', 'woo_variation_gallery_slider_arrow' ) ),
	'adaptiveHeight' => true,
	// 'lazyLoad'       => 'progressive',
	'rtl'            => is_rtl(),
	'prevArrow'      => '<i class="wvg-slider-prev-arrow dashicons dashicons-arrow-left-alt2"></i>',
	'nextArrow'      => '<i class="wvg-slider-next-arrow dashicons dashicons-arrow-right-alt2"></i>',
	'speed'          => absint( woo_variation_gallery()->get_option( 'slide_speed', 300 ) )
);

if ( wc_string_to_bool( woo_variation_gallery()->get_option( 'thumbnail_slide', 'yes', 'woo_variation_gallery_thumbnail_slide' ) ) ) {
	$slider_js_options['asNavFor'] = '.woo-variation-gallery-thumbnail-slider';
}

if ( wc_string_to_bool( woo_variation_gallery()->get_option( 'slider_autoplay', 'no', 'woo_variation_gallery_slider_autoplay' ) ) ) {
	$slider_js_options['autoplay']      = true;
	$slider_js_options['autoplaySpeed'] = absint( woo_variation_gallery()->get_option( 'slider_autoplay_speed', 5000, 'woo_variation_gallery_slider_autoplay_speed' ) );
}

if ( wc_string_to_bool( woo_variation_gallery()->get_option( 'slider_fade', 'no', 'woo_variation_gallery_slider_fade' ) ) ) {
	$slider_js_options['fade'] = true;
}

$gallery_slider_js_options = apply_filters( 'woo_variation_gallery_slider_js_options', $slider_js_options );

$gallery_thumbnail_position              = sanitize_textarea_field( woo_variation_gallery()->get_option( 'thumbnail_position', 'bottom', 'woo_variation_gallery_thumbnail_position' ) );
$gallery_thumbnail_position_small_device = sanitize_textarea_field( woo_variation_gallery()->get_option( 'thumbnail_position_small_device', 'bottom' ) );


//
$thumbnail_js_options = array(
	'slidesToShow'   => $columns,
	'slidesToScroll' => $columns,
	'focusOnSelect'  => true,
	// 'dots'=>true,
	'arrows'         => wc_string_to_bool( woo_variation_gallery()->get_option( 'thumbnail_arrow', 'yes' ) ),
	'asNavFor'       => '.woo-variation-gallery-slider',
	'centerMode'     => true,
	'infinite'       => true,
	'centerPadding'  => '0px',
	'vertical'       => in_array( $gallery_thumbnail_position, array( 'left', 'right' ) ),
	'rtl'            => woo_variation_gallery()->set_rtl_by_position( $gallery_thumbnail_position ),
	'prevArrow'      => '<i class="wvg-thumbnail-prev-arrow dashicons dashicons-arrow-left-alt2"></i>',
	'nextArrow'      => '<i class="wvg-thumbnail-next-arrow dashicons dashicons-arrow-right-alt2"></i>',
	'responsive'     => array(
		array(
			'breakpoint' => 768,
			'settings'   => array(
				'vertical' => in_array( $gallery_thumbnail_position_small_device, array( 'left', 'right' ) ),
				'rtl'      => woo_variation_gallery()->set_rtl_by_position( $gallery_thumbnail_position_small_device )
			),
		),
	)
);

$thumbnail_slider_js_options = apply_filters( 'woo_variation_gallery_thumbnail_slider_js_options', $thumbnail_js_options );

$gallery_width = absint( woo_variation_gallery()->get_option( 'width', apply_filters( 'woo_variation_gallery_default_width', 30 ), 'woo_variation_gallery_width' ) );

$inline_style = apply_filters( 'woo_variation_product_gallery_inline_style', array() );

$wrapper_classes = apply_filters( 'woo_variation_gallery_product_wrapper_classes', array(
	'woo-variation-product-gallery',
	'woo-variation-product-gallery-thumbnail-columns-' . absint( $columns ),
	$has_gallery_thumbnail ? 'woo-variation-gallery-has-product-thumbnail' : '',
	$has_gallery_thumbnail ? '' : 'woo-variation-gallery-no-product-thumbnail',
	wc_string_to_bool( woo_variation_gallery()->get_option( 'thumbnail_slide', 'yes' ) ) ? 'woo-variation-gallery-enabled-thumbnail-slider' : ''
) );

$post_thumbnail_id = (int) apply_filters( 'woo_variation_gallery_post_thumbnail_id', $post_thumbnail_id, $attachment_ids, $product );
$attachment_ids    = (array) apply_filters( 'woo_variation_gallery_attachment_ids', $attachment_ids, $post_thumbnail_id, $product );

$loading_gallery_class = wc_string_to_bool( woo_variation_gallery()->get_option( 'preloader_disable', 'no' ) ) ? '' : 'loading-gallery';
?>

<?php do_action( 'woo_variation_product_gallery_start', $product ); ?>
	<div data-product_id="<?php echo esc_attr( $product_id ) ?>" data-variation_id="<?php echo esc_attr( $default_variation_id ) ?>" style="<?php echo esc_attr( woo_variation_gallery()->get_inline_style( $inline_style ) ) ?>" class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', array_unique( $wrapper_classes ) ) ) ); ?>">
		<div class="<?php echo esc_attr( $loading_gallery_class ) ?> woo-variation-gallery-wrapper woo-variation-gallery-thumbnail-position-<?php echo esc_attr( $gallery_thumbnail_position ) ?>-<?php echo esc_attr( $gallery_thumbnail_position_small_device ) ?> woo-variation-gallery-product-type-<?php echo esc_attr( $product_type ) ?>">

			<div class="woo-variation-gallery-container preload-style-<?php echo trim( woo_variation_gallery()->get_option( 'preload_style', 'blur', 'woo_variation_gallery_preload_style' ) ) ?>">

				<div class="woo-variation-gallery-slider-wrapper">

					<?php do_action( 'woo_variation_product_gallery_slider_start', $product ); ?>

					<?php if ( $has_post_thumbnail && wc_string_to_bool( woo_variation_gallery()->get_option( 'lightbox', 'yes' ) ) ): ?>
						<a href="#" class="woo-variation-gallery-trigger woo-variation-gallery-trigger-position-<?php echo woo_variation_gallery()->get_option( 'zoom_position', 'top-right', 'woo_variation_gallery_zoom_position' ) ?>">
							<span class="dashicons dashicons-search"></span>
						</a>
					<?php endif; ?>

					<div class="woo-variation-gallery-slider" data-slick='<?php echo wc_esc_json( wp_json_encode( $gallery_slider_js_options ) ); // WPCS: XSS ok. ?>'>
						<?php
						// Main  Image
						if ( $has_post_thumbnail ) {
							echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', woo_variation_gallery()->get_frontend()->get_gallery_image_html( $product, $post_thumbnail_id, array(
								'is_main_thumbnail'  => true,
								'has_only_thumbnail' => $only_has_post_thumbnail
							) ), $post_thumbnail_id );
						} else {
							echo sprintf( '<div class="wvg-gallery-image wvg-gallery-image-placeholder"><div><div class="wvg-single-gallery-image-container"><img src="%s" alt="%s" class="wp-post-image" /></div></div></div>', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
						}


						// Gallery Image
						if ( $has_gallery_thumbnail ) {
							foreach ( $attachment_ids as $attachment_id ) :
								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', woo_variation_gallery()->get_frontend()->get_gallery_image_html( $product, $attachment_id, array(
									'is_main_thumbnail'  => true,
									'has_only_thumbnail' => $only_has_post_thumbnail
								) ), $attachment_id );
							endforeach;
						}
						?>
					</div>

					<?php do_action( 'woo_variation_product_gallery_slider_end', $product ); ?>
				</div> <!-- .woo-variation-gallery-slider-wrapper -->

				<div class="woo-variation-gallery-thumbnail-wrapper">
					<div class="woo-variation-gallery-thumbnail-slider woo-variation-gallery-thumbnail-columns-<?php echo esc_attr( $columns ) ?>" data-slick='<?php echo wc_esc_json( wp_json_encode( $thumbnail_slider_js_options ) ); // WPCS: XSS ok. ?>'>
						<?php
						if ( $has_gallery_thumbnail ) {
							// Main Image

							echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', woo_variation_gallery()->get_frontend()->get_gallery_image_html( $product, $post_thumbnail_id, array( 'is_main_thumbnail' => false ) ), $post_thumbnail_id );

							// Gallery Image
							foreach ( $attachment_ids as $key => $attachment_id ) :
								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', woo_variation_gallery()->get_frontend()->get_gallery_image_html( $product, $attachment_id, array( 'is_main_thumbnail' => false ) ), $attachment_id );
							endforeach;
						}
						?>
					</div>
				</div> <!-- .woo-variation-gallery-thumbnail-wrapper -->
			</div> <!-- .woo-variation-gallery-container -->
		</div> <!-- .woo-variation-gallery-wrapper -->
	</div> <!-- .woo-variation-product-gallery -->
<?php do_action( 'woo_variation_product_gallery_end', $product ); ?>