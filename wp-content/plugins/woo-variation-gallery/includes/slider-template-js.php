<?php
	defined( 'ABSPATH' ) or die( 'Keep Quit' );
?>

<script type="text/html" id="tmpl-woo-variation-gallery-slider-template">
	<?php ob_start() ?>
    <div class="wvg-gallery-image">
        <div>
            <# if( data.srcset ){ #>
            <div class="wvg-single-gallery-image-container">
                <img loading="lazy" class="{{data.class}}" width="{{data.src_w}}" height="{{data.src_h}}" src="{{data.src}}" alt="{{data.alt}}" title="{{data.title}}" data-caption="{{data.caption}}" data-src="{{data.full_src}}" data-large_image="{{data.full_src}}" data-large_image_width="{{data.full_src_w}}" data-large_image_height="{{data.full_src_h}}" srcset="{{data.srcset}}" sizes="{{data.sizes}}" {{data.extra_params}}/>
            </div>
            <# } #>

            <# if( !data.srcset ){ #>
            <div class="wvg-single-gallery-image-container">
                <img loading="lazy" class="{{data.class}}" width="{{data.src_w}}" height="{{data.src_h}}" src="{{data.src}}" alt="{{data.alt}}" title="{{data.title}}" data-caption="{{data.caption}}" data-src="{{data.full_src}}" data-large_image="{{data.full_src}}" data-large_image_width="{{data.full_src_w}}" data-large_image_height="{{data.full_src_h}}" sizes="{{data.sizes}}" {{data.extra_params}}/>
            </div>
            <# } #>
        </div>
    </div>
	<?php echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', ob_get_clean(), 0 ); ?>
</script>

<script type="text/html" id="tmpl-woo-variation-gallery-thumbnail-template">
	<div class="wvg-gallery-thumbnail-image">
		<div>
			<img class="{{data.gallery_thumbnail_class}}" width="{{data.gallery_thumbnail_src_w}}" height="{{data.gallery_thumbnail_src_h}}" src="{{data.gallery_thumbnail_src}}" alt="{{data.alt}}" title="{{data.title}}" />
		</div>
	</div>
</script>