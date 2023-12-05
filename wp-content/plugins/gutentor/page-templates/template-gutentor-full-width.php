<?php
/**
 * Template Name: Gutentor Full Width
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package Gutentor
 */
if( gutentor_is_fse_template()){
 ?><!doctype html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="profile" href="https://gmpg.org/xfn/11"/>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
    <?php
}
else{
    get_header();
}
do_action( 'gutentor_template_before_loop' );
/* Start the Loop */
while ( have_posts() ) :
	the_post();
	the_content();

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}

endwhile; // End of the loop.
do_action( 'gutentor_template_after_loop' );
if( gutentor_is_fse_template()){
    wp_footer();
    ?>
    </body>
    </html>
<?php
}
else{
    get_footer();
}