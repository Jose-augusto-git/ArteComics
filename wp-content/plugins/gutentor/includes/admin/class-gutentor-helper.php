<?php
/**
 * A helper class for plugin Admin Settings
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Gutentor_Helper' ) ) {
	/**
	 * Class Gutentor_Helper.
	 */
	class Gutentor_Helper {

		public static $block_list = array(
			'about-block'        => 'gutentor/about-block',
			'accordion'          => 'gutentor/accordion',
			'advanced-columns'   => 'gutentor/m4',
			'author-profile'     => 'gutentor/author-profile',
			'blog-post'          => 'gutentor/blog-post',
			'call-to-action'     => 'gutentor/call-to-action',
			'count-down'         => 'gutentor/count-down',
			'counter-box'        => 'gutentor/counter-box',
			'divider'            => 'gutentor/divider',
			'featured-block'     => 'gutentor/featured-block',
			'gallery'            => 'gutentor/gallery',
			'google-map'         => 'gutentor/google-map',
			'google-map-element' => 'gutentor/e4',
			'icon-box'           => 'gutentor/icon-box',
			'image-box'          => 'gutentor/image-box',
			'image-slider'       => 'gutentor/image-slider',
			'list'              => 'gutentor/list',
			'notification'      => 'gutentor/notification',
			'opening-hours'      => 'gutentor/opening-hours',
			'pricing'            => 'gutentor/pricing',
			'progress-bar'       => 'gutentor/progress-bar',
			'restaurant-menu'    => 'gutentor/restaurant-menu',
			'show-more'             => 'gutentor/show-more',
			'social'             => 'gutentor/social',
			'tabs'               => 'gutentor/tabs',
			'team'               => 'gutentor/team',
			'testimonial'        => 'gutentor/testimonial',
			'timeline'           => 'gutentor/timeline',
			'video-popup'        => 'gutentor/video-popup',
		);
		public static function enqueue( $scripts ) {

			// Do not enqueue anything if no array is supplied.
			if ( ! is_array( $scripts ) ) {
				return;
			}

			$scripts = apply_filters( 'gutentor_block_scripts', $scripts );

			foreach ( $scripts as $script ) {

				// Do not try to enqueue anything if handler is not supplied.
				if ( ! isset( $script['handler'] ) ) {
					continue;
				}

				$version = GUTENTOR_VERSION;
				if ( isset( $script['version'] ) ) {
					$version = $script['version'];
				}

				// Enqueue each vendor's style
				if ( isset( $script['style'] ) ) {

					$path = GUTENTOR_URL . $script['style'];
					if ( isset( $script['absolute'] ) ) {
						$path = $script['style'];
					}

					$dependency = array();
					if ( isset( $script['dependency'] ) ) {
						$dependency = $script['dependency'];
					}
					wp_enqueue_style( $script['handler'], $path, $dependency, $version );
				}

				// Enqueue each vendor's script
				if ( isset( $script['script'] ) ) {

					if ( $script['script'] === true || $script['script'] === 1 ) {
						wp_enqueue_script( $script['handler'] );
					} else {

						$prefix = '';
						if ( isset( $script['prefix'] ) ) {
							$prefix = $script['prefix'];
						}

						$path = '';
						if ( isset( $script['script'] ) ) {
							$path = GUTENTOR_URL . $script['script'];
						}

						if ( isset( $script['absolute'] ) ) {
							$path = $script['script'];
						}

						$dependency = array( 'jquery' );
						if ( isset( $script['dependency'] ) ) {
							$dependency = $script['dependency'];
						}

						$in_footer = true;

						if ( isset( $script['in_footer'] ) ) {
							$in_footer = $script['in_footer'];
						}

						wp_enqueue_script( $prefix . $script['handler'], $path, $dependency, $version, $in_footer );
					}
				}
			}
		}

		/**
		 * Returns an option from the database for
		 * the admin settings page.
		 *
		 * @param  string  $key     The option key.
		 * @param  mixed   $default Option default value if option is not available.
		 * @param  boolean $network_override Whether to allow the network admin setting to be overridden on subsites.
		 * @return string           Return the option value
		 */
		public static function get_option( $key, $default = false, $network_override = false ) {
			return gutentor_get_options( $key );
		}

		/**
		 * Updates an option from the admin settings page.
		 *
		 * @param string $key       The option key.
		 * @param mixed  $value     The value to update.
		 * @param bool   $network   Whether to allow the network admin setting to be overridden on subsites.
		 * @return void
		 */
		public static function update_option( $key, $blocks, $network_override = false ) {

			$g_options         = gutentor_get_options();
			$g_options[ $key ] = $blocks;

			update_option( 'gutentor_settings_options', $g_options );

		}
	}
}
