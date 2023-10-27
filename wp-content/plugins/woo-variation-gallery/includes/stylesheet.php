<?php
defined( 'ABSPATH' ) or die( 'Keep Quit' );
/**
 * @var $gallery_thumbnails_columns
 * @var $gallery_thumbnails_gap
 * @var $single_image_width
 * @var $gallery_width
 * @var $gallery_margin
 * @var $gallery_medium_device_width
 * @var $gallery_small_device_width
 * @var $gallery_small_device_clear_float
 * @var $gallery_extra_small_device_width
 * @var $gallery_extra_small_device_clear_float
 */
?>
<style type="text/css">
	:root {
		--wvg-thumbnail-item: <?php echo $gallery_thumbnails_columns ?>;
		--wvg-thumbnail-item-gap: <?php echo $gallery_thumbnails_gap ?>px;
		--wvg-single-image-size: <?php echo $single_image_width ?>px;
		--wvg-gallery-width: <?php echo $gallery_width ?>%;
		--wvg-gallery-margin: <?php echo $gallery_margin ?>px;
	}

	/* Default Width */
	.woo-variation-product-gallery {
		max-width: <?php echo $gallery_width ?>% !important;
		width: 100%;
	}

	/* Medium Devices, Desktops */
	<?php if( $gallery_medium_device_width > 0 ): ?>
	@media only screen and (max-width: 992px) {
		.woo-variation-product-gallery {
			width: <?php echo $gallery_medium_device_width ?>px;
			max-width: 100% !important;
		}
	}

	<?php endif; ?>

	/* Small Devices, Tablets */
	<?php if( $gallery_small_device_width > 0 ): ?>
	@media only screen and (max-width: 768px) {
		.woo-variation-product-gallery {
			width: <?php echo $gallery_small_device_width ?>px;
			max-width: 100% !important;
		<?php if( $gallery_small_device_clear_float ): ?> float: none;
		<?php endif; ?>
		}
	}

	<?php endif; ?>

	/* Extra Small Devices, Phones */
	<?php if( $gallery_extra_small_device_width > 0 ): ?>
	@media only screen and (max-width: 480px) {
		.woo-variation-product-gallery {
			width: <?php echo $gallery_extra_small_device_width ?>px;
			max-width: 100% !important;
		<?php if( $gallery_extra_small_device_clear_float ): ?> float: none;
		<?php endif; ?>
		}
	}

	<?php endif; ?>
</style>
