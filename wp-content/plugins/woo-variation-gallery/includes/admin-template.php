<?php
defined( 'ABSPATH' ) or die( 'Keep Quit' );


/**
 * @var $gallery_images
 * @var $variation_id
 */
//print_r( $gallery_images);

foreach ( $gallery_images as $image_id ):

	$image = wp_get_attachment_image_src( $image_id );
	$input_name = sprintf( 'woo_variation_gallery[%d][]', $variation_id );
	?>
	<li class="image">
		<input class="wvg_variation_id_input" type="hidden" name="<?php echo esc_attr( $input_name ) ?>" value="<?php echo absint( $image_id ) ?>">
		<img data-id="<?php echo absint( $image_id ) ?>" src="<?php echo esc_url( $image[0] ) ?>">
		<a href="#" class="delete remove-woo-variation-gallery-image"><span class="dashicons dashicons-dismiss"></span></a>
	</li>

<?php endforeach; ?>
