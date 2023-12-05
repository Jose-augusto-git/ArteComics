<?php
/**
 * Template Name: Gutentor Canvas
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package Gutentor
 */
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
do_action( 'wp_body_open' );
/* Start the Loop */
while ( have_posts() ) :
	the_post();
	the_content();
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile; // End of the loop.
wp_footer();
?>
</body>
</html>