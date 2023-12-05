<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check Isset
 *
 * @param  [mix] $var
 * @return [boolean]
 */
if ( ! function_exists( 'gutentor_isset' ) ) {
	function gutentor_isset( $var ) {
		if ( isset( $var ) ) {
			return $var;
		} else {
			return '';
		}
	}
}

/**
 * Convert into RBG Color
 * gutentor_rgb_string
 *
 * @param  [mix] $rgba
 * @return boolean | string
 */
if ( ! function_exists( 'gutentor_rgb_string' ) ) {
	function gutentor_rgb_string( $rgba ) {
		if ( ! is_array( $rgba ) ) {
			return null;
		}
		$roundA = round( 100 * $rgba['a'] ) / 100;
		return 'rgba(' . round( $rgba['r'] ) . ', ' . round( $rgba['g'] ) . ', ' . round( $rgba['b'] ) . ', ' . $roundA . ')';
	}
}

/**
 * Gutentor String Concat with space
 * gutentor_rgb_string
 *
 * @param  [mix] $rgba
 * @return boolean | string
 */
if ( ! function_exists( 'gutentor_concat_space' ) ) {
	function gutentor_concat_space( $class1,
									$class2 = '',
									$class3 = '',
									$class4 = '',
									$class5 = '',
									$class6 = '',
									$class7 = '',
									$class8 = '',
									$class9 = '',
									$class10 = '',
									$class11 = '',
									$class12 = '',
									$class13 = '',
									$class14 = '',
									$class15 = ''
	) {
		$output = $class1;
		if ( $class2 ) {
			$output = $output . ' ' . $class2;
		}
		if ( $class3 ) {
			$output = $output . ' ' . $class3;
		}
		if ( $class4 ) {
			$output = $output . ' ' . $class4;
		}
		if ( $class5 ) {
			$output = $output . ' ' . $class5;
		}
		if ( $class6 ) {
			$output = $output . ' ' . $class6;
		}
		if ( $class7 ) {
			$output = $output . ' ' . $class7;
		}
		if ( $class8 ) {
			$output = $output . ' ' . $class8;
		}
		if ( $class9 ) {
			$output = $output . ' ' . $class9;
		}
		if ( $class10 ) {
			$output = $output . ' ' . $class10;
		}
		if ( $class11 ) {
			$output = $output . ' ' . $class11;
		}
		if ( $class12 ) {
			$output = $output . ' ' . $class12;
		}
		if ( $class13 ) {
			$output = $output . ' ' . $class13;
		}
		if ( $class14 ) {
			$output = $output . ' ' . $class14;
		}
		if ( $class15 ) {
			$output = $output . ' ' . $class15;
		}
		return $output;
	}
}


/**
 * Check Empty
 * gutentor_not_empty
 *
 * @param  [mix] $var
 * @return [boolean]
 */
if ( ! function_exists( 'gutentor_not_empty' ) ) {

	function gutentor_not_empty( $var ) {
		if ( trim( $var ) === '' ) {
			return false;
		}
		return true;
	}
}

/**
 * Gutentor Unit Type
 * gutentor_unit_type
 *
 * @param  [mix] $type
 * @return string
 */
if ( ! function_exists( 'gutentor_unit_type' ) ) {

	function gutentor_unit_type( $type ) {
		if ( $type === 'px' ) {
			return 'px';
		} elseif ( $type === 'vh' ) {
			return 'vh';
		} else {
			return '%';
		}
	}
}

/**
 * Generate Css
 * gutentor_generate_css
 *
 * @param  [mix] $prop
 * @param  [mix] $value
 *
 * @return [string]
 */
if ( ! function_exists( 'gutentor_generate_css' ) ) {

	function gutentor_generate_css( $prop, $value ) {
		if ( ! is_string( $prop ) || ! is_string( $value ) ) {
			return '';
		}
		if ( $value ) {
			return '' . $prop . ': ' . $value . ';';
		}
		return '';
	}
}

/**
 * Get term excerpt
 *
 * @since 3.0.0
 *
 * @return string
 */
if ( ! function_exists( 'gutentor_text_length' ) ) {
	function gutentor_text_length( $original_excerpt, $excerpt_length = 200, $in_words = false ) {
		if ( ! $original_excerpt ) {
			return '';
		}
		/*remove style tags*/
		$the_excerpt = preg_replace( '`\[[^\]]*\]`', '', $original_excerpt );
		$the_excerpt = strip_shortcodes( $the_excerpt );
		$the_excerpt = wp_strip_all_tags( $the_excerpt );
		if ( $in_words ) {
			$the_excerpt = wp_trim_words( $the_excerpt, $excerpt_length );
		} else {

			if ( function_exists( 'mb_substr' ) ) {
				$the_excerpt = $the_excerpt ? mb_substr( $the_excerpt, 0, (int) $excerpt_length ) . '&hellip;' : '';
			} else {
				$the_excerpt = $the_excerpt ? substr( $the_excerpt, 0, (int) $excerpt_length ) . '&hellip;' : '';
			}
		}
		return apply_filters( 'gutentor_text_length', $the_excerpt, $original_excerpt, $excerpt_length, $in_words );
	}
}
/**
 * Get post excerpt
 *
 * @return string
 */
if ( ! function_exists( 'gutentor_get_excerpt_by_id' ) ) {
	function gutentor_get_excerpt_by_id( $post_id, $excerpt_length = 200, $in_words = false ) {
		$the_post     = get_post( $post_id );
		$the_excerpt  = $the_post->post_excerpt;
		$in_words_con = ( $in_words ) ? $in_words : false;
		$length       = ( $excerpt_length ) ? $excerpt_length : 200;
		if ( ! $the_excerpt ) {
			$the_excerpt = $the_post->post_content;
		}
		$the_excerpt = gutentor_text_length( $the_excerpt, $length, $in_words_con );
		return apply_filters( 'gutentor_get_excerpt_by_id', $the_excerpt, $post_id, $excerpt_length, $in_words_con );
	}
}

/**
 * Get term excerpt
 *
 * @since 3.0.0
 *
 * @return string
 */
if ( ! function_exists( 'gutentor_get_term_description' ) ) {
	function gutentor_get_term_description( $term, $length = 200, $in_words = false ) {
		$the_excerpt    = $term->description;
		$in_words_con   = ( $in_words ) ? $in_words : false;
		$excerpt_length = ( $length ) ? $length : 200;
		$the_excerpt    = gutentor_text_length( $the_excerpt, $excerpt_length, $in_words_con );
		return apply_filters( 'gutentor_get_term_description', $the_excerpt, $term, $excerpt_length, $in_words_con );
	}
}

/**
 * Gutentor dynamic CSS
 *
 * @param array $dynamic_css
 *    $dynamic_css = array(
 * 'all'=>'css',
 * '768'=>'css',
 * );
 * @return mixed
 * @since    1.0.0
 */
if ( ! function_exists( 'gutentor_get_dynamic_css' ) ) {

	function gutentor_get_dynamic_css( $dynamic_css = array() ) {
		$getCSS      = '';
		$dynamic_css = apply_filters( 'gutentor_get_dynamic_css', $dynamic_css );

		if ( is_array( $dynamic_css ) ) {
			foreach ( $dynamic_css as $screen => $css ) {
				if ( $screen === 'all' ) {

					if ( is_array( $css ) ) {
						$getCSS .= implode( ' ', $css );
					} else {
						$getCSS .= $css;
					}
				} elseif ( $screen === 'tablet' ) {

					$getCSS .= '@media (min-width: 720px) {';
					if ( is_array( $css ) ) {
						$getCSS .= implode( ' ', $css );
					} else {
						$getCSS .= $css;
					}
					$getCSS .= '}';
				} elseif ( $screen === 'desktop' ) {

					$getCSS .= '@media (min-width: 992px) {';
					if ( is_array( $css ) ) {
						$getCSS .= implode( ' ', $css );
					} else {
						$getCSS .= $css;
					}
					$getCSS .= '}';
				}
			}
		}
		$output = $getCSS;

		return $output;
	}
}

/**
 *  GutentorButtonOptionsClasses
 *
 * @param null
 * @return string
 *
 * @since Gutentor 1.0.0
 */
if ( ! function_exists( 'GutentorButtonOptionsClasses' ) ) {

	function GutentorButtonOptionsClasses( $button ) {
		if ( $button === null || empty( $button ) ) {
			return false;
		}
		$output   = '';
		$position = isset( $button['position'] ) ? $button['position'] : '';
		if ( $position ) {
			$output = 'gutentor-icon-' . $position;
		}
		return $output;
	}
}

/**
 *  GutentorBackgroundOptionsCSSClasses
 *
 * @param null
 * @return string
 *
 * @since Gutentor 1.0.0
 */
if ( ! function_exists( 'GutentorBackgroundOptionsCSSClasses' ) ) {
	function GutentorBackgroundOptionsCSSClasses( $backgroundType ) {
		if ( $backgroundType === null || empty( $backgroundType ) ) {
			return false;
		}
		if ( 'image' === $backgroundType ) {
			return 'has-image-bg has-custom-bg';
		} elseif ( 'color' === $backgroundType ) {
			return 'has-color-bg has-custom-bg';
		} elseif ( 'video' === $backgroundType ) {
			return 'has-video-bg has-custom-bg';
		} elseif ( 'image-normal' === $backgroundType ) {
			return 'has-image-normal has-custom-bg';
		} elseif ( 'image-bg-and-color' === $backgroundType ) {
			return 'has-image-bg has-color-bg has-custom-bg';
		}

	}
}

/**
 *  gutentor_get_youtube_id_from_url
 *
 * @param $url
 * @return string id
 *
 * @since Gutentor 3.0.1
 */
function gutentor_get_youtube_id_from_url( $url ) {
	preg_match( '/(http(s|):|)\/\/(www\.|)yout(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $results );
	if ( isset( $results[6] ) ) {
		return $results[6];
	}
	return false;
}

/**
 *  gutentor_get_vimeo_id_from_url
 *
 * @param $url
 * @return string id
 *
 * @since Gutentor 3.0.1
 */
function gutentor_get_vimeo_id_from_url( $url ) {
	if ( preg_match( '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url, $output_array ) ) {
		return $output_array[5];
	}
	return false;
}
/**
 * Set Updated video output.
 *
 * @param {string} backgroundType
 * @param {string} backgroundVideoSrc
 * @param {object} backgroundVideo
 * @param {object} backgroundVideoUrl
 * @param {boolean} backgroundVideoLoop
 * @param {boolean} backgroundVideoMuted
 * @return {string} The video output container.
 */
if ( ! function_exists( 'GutentorUpdatedBackgroundVideoOutput' ) ) {
	function GutentorUpdatedBackgroundVideoOutput( $backgroundType, $backgroundVideoSrc, $backgroundVideo, $backgroundVideoUrl, $backgroundVideoLoop, $backgroundVideoMuted ) {
		$video_container     = '';
		$backgroundVideo_src = ( is_array( $backgroundVideo ) && array_key_exists( 'url', $backgroundVideo ) ) ? $backgroundVideo['url'] : false;
		if ( 'video' === $backgroundType && $backgroundVideoSrc === 'self-hosted-local' && $backgroundVideo ) {
			$muted           = $backgroundVideoMuted ? 'muted' : '';
			$loop            = $backgroundVideoLoop ? 'loop' : '';
			$video_container = '<video 
			playsinline
            autoplay
            ' . $loop . '
            ' . $muted . '
            class="gutentor-bg-video"
            >
				<source
					type="video/mp4"
					src="' . $backgroundVideo_src . '"
				/>
			</video>';
		}
		if ( 'video' === $backgroundType && $backgroundVideoSrc === 'external-url' && $backgroundVideoUrl ) {
			$controlsData   = '?controls=0&autoplay=1';
			$videoUrl       = $backgroundVideoUrl;
			$youtubeVideoID = gutentor_get_youtube_id_from_url( $backgroundVideoUrl );
			$vimeoVideoID   = gutentor_get_vimeo_id_from_url( $backgroundVideoUrl );
			if ( $youtubeVideoID ) {
				$videoUrl     = 'https://www.youtube.com/embed/' . $youtubeVideoID;
				$controlsData = $controlsData . '&showinfo=0';
				$controlsData = $backgroundVideoLoop ? $controlsData . '&playlist=' . $youtubeVideoID . '&loop=1' : $controlsData;
				$controlsData = $backgroundVideoMuted ? $controlsData . '&mute=1' : $controlsData;
			} else {
				$videoUrl     = 'https://player.vimeo.com/video/' . $vimeoVideoID;
				$controlsData = $controlsData . '&background=1';
				$controlsData = $backgroundVideoLoop ? $controlsData . '&loop=1' : $controlsData;
				$controlsData = $backgroundVideoMuted ? $controlsData . '&muted=1' : $controlsData;
			}
			$videoUrl        = $videoUrl . $controlsData;
			$video_container = '<iframe
				height="100%"
				width="100%"
			    class="gutentor-bg-video"
				src="' . $videoUrl . '"
				frameborder="0"
				allowfullscreen
			>
			</iframe>';
		}
		return $video_container;

	}
}

/**
 *  GutentorButtonOptionsClasses
 *
 * @param null
 * @return string
 *
 * @since Gutentor 1.0.0
 */
if ( ! function_exists( 'GutentorAnimationOptionsDataAttr' ) ) {

	/**
	 * Background Classes
	 *
	 * @param {string} backgroundType - The Background type
	 * @return {array} The inline CSS class.
	 */
	function GutentorAnimationOptionsDataAttr( $valueAnimation ) {
		if ( $valueAnimation === null || empty( $valueAnimation ) ) {
			return false;
		}
		$animation_attr = '';

		$animation = ( isset( $valueAnimation['Animation'] ) && $valueAnimation['Animation'] ) ? $valueAnimation['Animation'] : '';
		if ( 'none' !== $animation ) {
			if ( ! empty( $animation ) ) {
				$animation_class = 'data-wow-animation = "' . $animation . '"';
				$animation_attr  = gutentor_concat_space( $animation_attr, $animation_class );
			}
			$delay = ( isset( $valueAnimation['Delay'] ) && $valueAnimation['Delay'] ) ? $valueAnimation['Delay'] : '';
			if ( ! empty( $delay ) ) {
				$delay_class    = 'data-wow-delay = "' . $delay . 's"';
				$animation_attr = gutentor_concat_space( $animation_attr, $delay_class );
			}
			/*speed changed to duration*/
			$speed = ( isset( $valueAnimation['Speed'] ) && $valueAnimation['Speed'] ) ? $valueAnimation['Speed'] : '';
			if ( ! empty( $speed ) ) {
				$speed_class    = 'data-wow-duration = "' . $speed . 's"';
				$animation_attr = gutentor_concat_space( $animation_attr, $speed_class );
			}

			$iteration = ( isset( $valueAnimation['Iteration'] ) && $valueAnimation['Iteration'] ) ? $valueAnimation['Iteration'] : '';
			if ( ! empty( $iteration ) ) {
				$iteration_class = 'data-wow-iteration = "' . $iteration . '"';
				$animation_attr  = gutentor_concat_space( $animation_attr, $iteration_class );
			}
		}
		return $animation_attr;
	}
}

/**
 *  Customize Default Options
 *
 * @param null
 * @return array $gutentor_default_options
 *
 * @since Gutentor 1.0.0
 */
if ( ! function_exists( 'gutentor_get_default_options' ) ) :
	function gutentor_get_default_options( $old = false ) {
		if ( $old ) {
			$default_theme_options = array(
				'gutentor_map_api'                   => 'AIzaSyAq-PUmXMM3M2aQnwUslzap0TXaGyZlqZE',
				'gutentor_disable_wide_width_editor' => false,
				'gutentor_tax_term_color'            => false,
				'gutentor_tax_term_image'            => false,
				'gutentor_dynamic_style_location'    => 'head',
				'gutentor_gt_apply_options'          => 'global',
				'gutentor_font_awesome_version'      => '5',
				'gutentor_color_palatte_options'     => 'both',
			);
		} else {
			$default_theme_options = array(
				'color-palette-options' => 'both',
				'color-palettes'        => '',
				'dynamic-res-location'  => 'head',
				'enable-export-block'   => 'true',
				'enable-import-block'   => 'true',
				'fa-version'            => '5',
				'map-api'               => 'AIzaSyAq-PUmXMM3M2aQnwUslzap0TXaGyZlqZE',
				'off-blocks'            => '',
				'ptel'                  => '', /*Post type elements*/
				'ttel'                  => '', /*term elements*/
				'tax-in-color'          => '',
				'tax-in-image'          => '',
				'typo-apply-options'    => 'global',
				'wide-width-editor'     => false,
				'page-templates-in-pt'  => '',
				'videos-in-pt'          => '',
				'maintenance-mode'      => 'disabled',
				'maintenance-access'    => 'none',
				'maintenance-template'  => '-1',
				'allow-tracking'        => false,
				'on-popup'              => false,
				'popup-load'            => 'ajax',
			);

			/*Global Color*/
			$global_color = array(
				'btn-txt',
				'btn-bg',
				'heading',
				'body',
				'link',
				'cat-txt-default',
				'cat-bg-default',
			);
			foreach ( $global_color as $gc ) {
				$key                           = 'gc-' . esc_attr( $gc );
				$default_theme_options[ $key ] = '{}';
			}

			$global_typography = array(
				'h1',
				'h2',
				'h3',
				'h4',
				'h5',
				'h6',
				'body',
				'button',
			);
			foreach ( $global_typography as $gt ) {
				$key                           = 'gt-' . esc_attr( $gt );
				$default_theme_options[ $key ] = '';
			}

			/*Global Width*/
			$global_width = array(
				'mobile',
				'tablet',
				'desktop',
				'large',
			);
			foreach ( $global_width as $gw ) {
				$key                           = 'gw-' . esc_attr( $gw );
				$default_theme_options[ $key ] = 0;
			}

			/*post format*/
			$post_formats = gutentor_get_post_formats();
			if ( $post_formats && is_array( $post_formats ) ) {
				foreach ( $post_formats as $post_format ) {
					$key                           = 'pf-' . esc_attr( $post_format );
					$default_theme_options[ $key ] = '{}';
				}
			}
		}

		return apply_filters( 'gutentor_default_options', $default_theme_options );
	}
endif;

/**
 * Get post formats
 *
 * @return array || boolean
 * @since    2.2.1
 * @access   public
 */
if ( ! function_exists( 'gutentor_get_post_formats' ) ) {
	function gutentor_get_post_formats() {
		$post_formats = get_theme_support( 'post-formats' );
		if ( is_array( $post_formats ) && ! empty( $post_formats ) && isset( $post_formats[0] ) ) {
			$post_formats = $post_formats[0];
			array_unshift( $post_formats, 'standard' );
			return $post_formats;
		}
		return false;
	}
}

/**
 * Get options
 *
 * @param null
 * @return mixed gutentor_get_options
 *
 * @since Gutentor 1.0.0
 */
if ( ! function_exists( 'gutentor_get_options' ) ) :

	function gutentor_get_options( $key = '' ) {
		$g_options                = get_option( 'gutentor_settings_options' );
		$gutentor_default_options = gutentor_get_default_options();
		if ( ! empty( $key ) ) {
			if ( isset( $g_options[ $key ] ) ) {
				return $g_options[ $key ];
			}
			if ( get_option( $key ) ) {/*backward compatibility*/
				return get_option( $key );
			}
			$value = isset( $gutentor_default_options[ $key ] ) ? $gutentor_default_options[ $key ] : '';
			if ( empty( $value ) ) {
				$gutentor_od = gutentor_get_default_options( true );
				$value       = isset( $gutentor_od[ $key ] ) ? $gutentor_od[ $key ] : '';
			}
			return $value;
		} else {
			if ( ! is_array( $g_options ) ) {
				$g_options = array();
			}
			return array_merge( $gutentor_default_options, $g_options );
		}
	}
endif;


/**
 * Delete options
 *
 * @param null
 * @return mixed gutentor_delete_options
 *
 * @since Gutentor 3.0.2
 */
if ( ! function_exists( 'gutentor_delete_options' ) ) :

	function gutentor_delete_options( $key = '' ) {
		if ( ! empty( $key ) ) {
			delete_option( $key );
			$g_options = gutentor_get_options();
			if ( isset( $g_options[ $key ] ) ) {
				unset( $g_options[ $key ] );
				update_option( 'gutentor_settings_options', $g_options );
			}
		} else {
			delete_option( 'gutentor_settings_options' );
		}
		return false;
	}
endif;

/**
 * Return "theme support" values from the current theme, if set.
 *
 * @return boolean
 * @since Gutentor 1.0.0
 */
if ( ! function_exists( 'gutentor_get_theme_support' ) ) :

	function gutentor_get_theme_support() {
		return get_theme_support( 'gutentor' );
	}
endif;

/**
 * Default color palettes
 *
 * @param null
 * @return array $gutentor_default_color_palettes
 *
 * @since Gutentor 1.0.0
 */
if ( ! function_exists( 'gutentor_default_color_palettes' ) ) {

	function gutentor_default_color_palettes() {
		$palettes = array(
			'#000000',
			'#ffffff',
			'#dd3333',
			'#dd9933',
			'#eeee22',
			'#81d742',
			'#1e73be',
			'#8224e3',
		);
		return apply_filters( 'gutentor_default_color_palettes', $palettes );
	}
}

/**
 * Add  Dynamic Category Css
 *
 * @param array $data
 * @param array $attributes
 * @return array | boolean
 * @since    1.0.0
 * @access   public
 */
if ( ! function_exists( 'gutentor_pm_post_dynamic_categories_color' ) ) {
	function gutentor_pm_post_dynamic_categories_color( $terms = array() ) {
		$important = ' !important;';

		/* device type */
		$local_dynamic_css = '';
		if ( $terms && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$category_list = get_term( $term );

				/*get customize id*/
				$cat_color = 'gutentor-cat-' . esc_attr( $category_list->term_id );
				/* get category Color options */
				$text_color       = '#1974d2';
				$bg               = '#ffffff';
				$hover_bg         = '#ffffff';
				$hover_text_color = '#1974d2';
				$gutentor_meta    = get_term_meta( $category_list->term_id, 'gutentor_meta', true );
				if ( $gutentor_meta ) {
					$bg               = isset( $gutentor_meta['bg-color'] ) ? $gutentor_meta['bg-color'] : '';
					$hover_bg         = isset( $gutentor_meta['bg-hover-color'] ) ? $gutentor_meta['bg-hover-color'] : '';
					$text_color       = isset( $gutentor_meta['text-color'] ) ? $gutentor_meta['text-color'] : '';
					$hover_text_color = isset( $gutentor_meta['text-hover-color'] ) ? $gutentor_meta['text-hover-color'] : '';
				} elseif ( get_option( $cat_color ) ) {/*backward compatibility*/
					$gutentor_cat_options = get_option( $cat_color );
					$gutentor_cat_options = json_decode( $gutentor_cat_options, true );

					$bg               = isset( $gutentor_cat_options['background-color'] ) && ! empty( $gutentor_cat_options['background-color'] ) ? $gutentor_cat_options['background-color'] : '';
					$hover_bg         = isset( $gutentor_cat_options['background-hover-color'] ) && ! empty( $gutentor_cat_options['background-hover-color'] ) ? $gutentor_cat_options['background-hover-color'] : '';
					$text_color       = isset( $gutentor_cat_options['text-color'] ) && ! empty( $gutentor_cat_options['text-color'] ) ? $gutentor_cat_options['text-color'] : '';
					$hover_text_color = isset( $gutentor_cat_options['text-hover-color'] ) && ! empty( $gutentor_cat_options['text-hover-color'] ) ? $gutentor_cat_options['text-hover-color'] : '';
				}

				/*Cat normal color */
				$cat_color_css = '';
				if ( $text_color ) {
					$cat_color_css .= 'color:' . $text_color . $important;
				}
				/*Cat bg color */
				if ( $bg ) {
					$cat_color_css .= 'background:' . $bg . $important;
				}
				/*Add cat color css */
				if ( ! empty( $cat_color_css ) ) {
					$local_dynamic_css .= ".gutentor-categories .gutentor-cat-{$category_list->slug}{
                       " . $cat_color_css . '
                    }';
				}

				/* cat hover color */
				$cat_color_hover_css = '';
				if ( $hover_bg ) {
					$cat_color_hover_css .= 'color:' . $hover_bg . $important;
				}
				/* cat hover  bg color */
				if ( $hover_text_color ) {
					$cat_color_hover_css .= 'background:' . $hover_text_color . $important;
				}
				/*add hover css*/
				if ( ! empty( $cat_color_hover_css ) ) {
					$local_dynamic_css .= ".gutentor-categories .gutentor-cat-{$category_list->slug}:hover{
                        " . $cat_color_hover_css . '
                    }';
				}
			}
		}
		return $local_dynamic_css;
	}
}

/**
 * Add Category Dynamic Css
 *
 * @param array $data
 * @param array $attributes
 * @return array | boolean
 * @since    1.0.0
 * @access   public
 */
if ( ! function_exists( 'gutentor_pm_post_categories_color' ) ) {
	function gutentor_pm_post_categories_color( $imp = false ) {
		$important = $imp ? ' !important;' : ';';

		/* device type */
		$local_dynamic_css = '';
		/*category color options*/
		$args       = array(
			'orderby'    => 'id',
			'hide_empty' => 0,
		);
		$categories = get_categories( $args );
		if ( $categories ) {
			foreach ( $categories as $category_list ) {
				/*get customize id*/
				$cat_color = 'gutentor-cat-' . esc_attr( $category_list->term_id );
				/* get category Color options */
				$text_color       = '#1974d2';
				$bg               = '#ffffff';
				$hover_bg         = '#ffffff';
				$hover_text_color = '#1974d2';
				$gutentor_meta    = get_term_meta( $category_list->term_id, 'gutentor_meta', true );
				if ( $gutentor_meta ) {
					$bg               = isset( $gutentor_meta['bg-color'] ) ? $gutentor_meta['bg-color'] : '';
					$hover_bg         = isset( $gutentor_meta['bg-hover-color'] ) ? $gutentor_meta['bg-hover-color'] : '';
					$text_color       = isset( $gutentor_meta['text-color'] ) ? $gutentor_meta['text-color'] : '';
					$hover_text_color = isset( $gutentor_meta['text-hover-color'] ) ? $gutentor_meta['text-hover-color'] : '';
				} elseif ( get_option( $cat_color ) ) {/*backward compatibility*/
					$gutentor_cat_options = get_option( $cat_color );
					$gutentor_cat_options = json_decode( $gutentor_cat_options, true );

					$bg               = isset( $gutentor_cat_options['background-color'] ) && ! empty( $gutentor_cat_options['background-color'] ) ? $gutentor_cat_options['background-color'] : '';
					$hover_bg         = isset( $gutentor_cat_options['background-hover-color'] ) && ! empty( $gutentor_cat_options['background-hover-color'] ) ? $gutentor_cat_options['background-hover-color'] : '';
					$text_color       = isset( $gutentor_cat_options['text-color'] ) && ! empty( $gutentor_cat_options['text-color'] ) ? $gutentor_cat_options['text-color'] : '';
					$hover_text_color = isset( $gutentor_cat_options['text-hover-color'] ) && ! empty( $gutentor_cat_options['text-hover-color'] ) ? $gutentor_cat_options['text-hover-color'] : '';
				}

				/*Cat normal color */
				$cat_color_css = '';
				if ( $text_color ) {
					$cat_color_css .= 'color:' . $text_color . $important;
				}
				/*Cat bg color */
				if ( $bg ) {
					$cat_color_css .= 'background:' . $bg . $important;
				}
				/*Add cat color css */
				if ( ! empty( $cat_color_css ) ) {
					$local_dynamic_css .= ".gutentor-categories .gutentor-cat-{$category_list->slug}{
                       " . $cat_color_css . '
                    }';
				}

				/* cat hover color */
				$cat_color_hover_css = '';
				if ( $hover_bg ) {
					$cat_color_hover_css .= 'color:' . $hover_bg . $important;
				}
				/* cat hover  bg color */
				if ( $hover_text_color ) {
					$cat_color_hover_css .= 'background:' . $hover_text_color . $important;
				}
				/*add hover css*/
				if ( ! empty( $cat_color_hover_css ) ) {
					$local_dynamic_css .= ".gutentor-categories .gutentor-cat-{$category_list->slug}:hover{
                        " . $cat_color_hover_css . '
                    }';
				}
			}
		}
		return $local_dynamic_css;
	}
}

/**
 * Get post format colors
 *
 * @param boolean $imp
 * @return array
 * @since    2.2.1
 * @access   public
 */
if ( ! function_exists( 'gutentor_get_post_format_colors' ) ) {

	function gutentor_get_post_format_colors( $imp = false ) {
		$important  = $imp ? ' !important;' : ';';
		$color_data = array();
		/*Post Format color options*/
		$post_formats = gutentor_get_post_formats();
		if ( $post_formats && is_array( $post_formats ) ) {
			foreach ( $post_formats as $post_format ) {
				$post_format_color_css = $post_format_hover_color_css = '';
				$post_format_data      = gutentor_get_options( 'pf-' . esc_attr( $post_format ) );
				$post_format_data      = is_string( $post_format_data ) ? json_decode( $post_format_data, true ) : $post_format_data;

				$bg_color = $icon_color = array(
					'normal' => '',
					'hover'  => '',
				);
				if ( ! is_null( $post_format_data ) ) {
					if ( isset( $post_format_data['bg_color'] ) &&
						! empty( $post_format_data['bg_color'] ) ) {
						if ( is_string( $post_format_data['bg_color'] ) ) {
							$bg_color['normal'] = $post_format_data['bg_color'];
							$bg_color['hover']  = '';
						} else {
							$bg_color['normal'] = isset( $post_format_data['bg_color']['normal'] ) ? $post_format_data['bg_color']['normal'] : '';
							$bg_color['hover']  = isset( $post_format_data['bg_color']['hover'] ) ? $post_format_data['bg_color']['hover'] : '';
						}
					}
					if ( isset( $post_format_data['icon_color'] ) &&
						! empty( $post_format_data['icon_color'] ) ) {
						if ( is_string( $post_format_data['icon_color'] ) ) {
							$icon_color['normal'] = $post_format_data['icon_color'];
							$icon_color['hover']  = '';
						} else {
							$icon_color['normal'] = isset( $post_format_data['icon_color']['normal'] ) ? $post_format_data['icon_color']['normal'] : '';
							$icon_color['hover']  = isset( $post_format_data['icon_color']['hover'] ) ? $post_format_data['icon_color']['hover'] : '';
						}
					}
				}
				if ( ! empty( $icon_color['normal'] ) ) {
					$post_format_color_css .= 'color:' . $icon_color['normal'] . $important;
				} else {
					$post_format_color_css .= 'color:' . '#ffffff' . $important;
				}
				if ( ! empty( $bg_color['normal'] ) ) {
					$post_format_color_css .= 'background:' . $bg_color['normal'] . $important;
				} else {
					$post_format_color_css .= 'background:' . 'rgba(24,111,201,1)' . $important;
				}
				if ( ! empty( $icon_color['hover'] ) ) {
					$post_format_hover_color_css .= 'color:' . $icon_color['hover'] . $important;
				}
				if ( ! empty( $bg_color['hover'] ) ) {
					$post_format_hover_color_css .= 'background:' . $bg_color['hover'] . $important;
				}

				/* add post format css */
				if ( ! empty( $post_format_color_css ) ) {
					$color_data[ $post_format ]['normal'] = $post_format_color_css;
				}
				if ( ! empty( $post_format_hover_color_css ) ) {
					$color_data[ $post_format ]['hover'] = $post_format_hover_color_css;
				}
			}
		}

		return $color_data;
	}
}
/**
 * Add Post Format Dynamic Css
 *
 * @param array $data
 * @param array $attributes
 * @return array | boolean
 * @since    1.0.0
 * modified on    2.2.1
 * @access   public
 */
if ( ! function_exists( 'gutentor_post_format_colors' ) ) {

	function gutentor_post_format_colors( $imp = false ) {
		$local_dynamic_css  = '';
		$post_format_colors = gutentor_get_post_format_colors( $imp );
		foreach ( $post_format_colors as $post_format => $colors ) {
			/* add post format css */
			if ( isset( $colors['normal'] ) && ! empty( $colors['normal'] ) ) {
				$local_dynamic_css .= ".gutentor-post-format.gutentor-post-format-{$post_format}{
                       " . $colors['normal'] . '
                    }';
			}
			if ( isset( $colors['hover'] ) && ! empty( $colors['hover'] ) ) {
				$local_dynamic_css .= ".gutentor-post-format.gutentor-post-format-{$post_format}:hover{
                       " . $colors['hover'] . '
                    }';
			}
		}
		return $local_dynamic_css;
	}
}

/**
 * Add Post Featured Format Dynamic Css
 *
 * @param array $data
 * @param array $attributes
 * @return array | boolean
 * @since    1.0.0
 * @access   public
 */
if ( ! function_exists( 'gutentor_post_featured_format_colors' ) ) {

	function gutentor_post_featured_format_colors( $imp = false ) {
		$local_dynamic_css  = '';
		$post_format_colors = gutentor_get_post_format_colors( $imp );

		foreach ( $post_format_colors as $post_format => $colors ) {
			/* add post format css */
			if ( isset( $colors['normal'] ) && ! empty( $colors['normal'] ) ) {
				$local_dynamic_css .= ".gutentor-post-featured-format.gutentor-post-format-{$post_format}{
                       " . $colors['normal'] . '
                    }';
			}
			if ( isset( $colors['hover'] ) && ! empty( $colors['hover'] ) ) {
				$local_dynamic_css .= ".gutentor-post-featured-format.gutentor-post-format-{$post_format}:hover{
                       " . $colors['hover'] . '
                    }';
			}
		}
		return $local_dynamic_css;
	}
}

/**
 * Get Global Typography Options
 *
 * @since    3.0.0
 * @access   public
 */
if ( ! function_exists( 'gutentor_get_global_typography' ) ) {
	function gutentor_get_global_typography() {
		$typography        = array();
		$global_typography = array(
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'body',
			'button',
		);
		foreach ( $global_typography as $gt ) {
			$key                = 'gt-' . esc_attr( $gt );
			$typography[ $key ] = gutentor_get_options( $key );
		}
		return $typography;
	}
}

/**
 * Get Global Color Options
 *
 * @since    3.0.0
 * @access   public
 */
if ( ! function_exists( 'gutentor_get_global_color' ) ) {
	function gutentor_get_global_color() {
		$color        = array();
		$global_color = array(
			'btn-txt',
			'btn-bg',
			'heading',
			'body',
			'link',
			'cat-txt-default',
			'cat-bg-default',
		);
		foreach ( $global_color as $gc ) {
			$key           = 'gc-' . esc_attr( $gc );
			$color[ $key ] = gutentor_get_options( $key );
		}
		return $color;
	}
}

/**
 * Get Global Container Width
 *
 * @since    3.0.0
 * @access   public
 */
if ( ! function_exists( 'gutentor_get_global_container_width' ) ) {
	function gutentor_get_global_container_width() {
		$color     = array();
		$global_gw = array(
			'mobile',
			'tablet',
			'desktop',
			'large',
		);
		foreach ( $global_gw as $gw ) {
			$key           = 'gw-' . esc_attr( $gw );
			$color[ $key ] = gutentor_get_options( $key );
		}
		return $color;
	}
}

/**
 * gutentor_is_edit_page
 *
 * @since    1.0.0
 * @access   public
 */
if ( ! function_exists( 'gutentor_is_edit_page' ) ) {
	function gutentor_is_edit_page() {
		// make sure we are on the backend
		if ( ! is_admin() ) {
			return false;
		}
		global $pagenow;
		return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
	}
}

/**
 * Get Default Post Format
 *
 * @param array $post_format
 * @return string
 * @since    1.0.0
 * @access   public
 */
if ( ! function_exists( 'gutentor_get_post_format_default_icon' ) ) {
	function gutentor_get_post_format_default_icon( $post_format ) {
		switch ( $post_format ) :
			case 'aside':
				$icon = 'fas fa-columns';
				break;
			case 'image':
				$icon = 'fas fa-image';
				break;
			case 'video':
				$icon = 'fas fa-video';
				break;
			case 'quote':
				$icon = 'fas fa-quote-right';
				break;
			case 'link':
				$icon = 'fas fa-link';
				break;
			case 'gallery':
				$icon = 'far fa-images';
				break;
			case 'status':
				$icon = 'far fa-comment-dots';
				break;
			case 'audio':
				$icon = 'fas fa-microphone';
				break;
			case 'chat':
				$icon = 'far fa-comment-alt';
				break;
			default:
				$icon = 'fas fa-file-alt';
				break;
		endswitch;

		return $icon;

	}
}

/**
 * Get Default Post Format Icon
 *
 * @param array $post_format
 * @return string
 * @since    1.0.0
 * @access   public
 */
if ( ! function_exists( 'gutentor_get_post_format_icon' ) ) {
	function gutentor_get_post_format_icon( $post_format ) {
		$string_icon  = gutentor_get_options( 'pf-' . esc_attr( $post_format ) );
		$decoded_icon = is_string( $string_icon ) ? json_decode( $string_icon, true ) : $string_icon;
		$icon         = is_null( $decoded_icon ) ? $string_icon : $decoded_icon;
		if ( isset( $icon['icon']['value'] ) ) {
			$icon = $icon['icon']['value'];
		} elseif ( isset( $icon['icon'] ) ) {
			$icon = $icon['icon'];
		} else {
			$icon = false;
		}
		if ( ! $icon ) {
			$icon = gutentor_get_post_format_default_icon( $post_format );
		}
		return $icon;
	}
}

/**
 * Get All Post Format Icon
 *
 * @param array $post_format
 * @return string
 * @since    1.0.0
 * @access   public
 */
if ( ! function_exists( 'gutentor_get_all_post_format_icons' ) ) {
	function gutentor_get_all_post_format_icons() {
		$icons        = array();
		$post_formats = gutentor_get_post_formats();
		if ( $post_formats && is_array( $post_formats ) ) {
			foreach ( $post_formats as $post_format ) {
				$post_format_icon         = gutentor_get_post_format_icon( $post_format );
				$decoded_post_format_icon = json_decode( gutentor_get_post_format_icon( $post_format ) );
				$icons[ $post_format ]    = is_null( $decoded_post_format_icon ) ? $post_format_icon : $decoded_post_format_icon;
			}
		}
		return $icons;
	}
}

/**
 * Check Post Format Enable
 *
 * @return boolean
 * @since    1.0.0
 * @access   public
 */
if ( ! function_exists( 'gutentor_check_post_format_support_enable' ) ) {
	function gutentor_check_post_format_support_enable() {
		$post_formats = gutentor_get_post_formats();
		if ( ! $post_formats ) {
			return false;
		}
		return true;
	}
}

/**
 * Convert boolean to string
 * gutentor_boolean_to_string
 *
 * @param  [mix] $var
 * @return [boolean]
 */
if ( ! function_exists( 'gutentor_boolean_to_string' ) ) {
	function gutentor_boolean_to_string( $var ) {
		if ( $var ) {
			return 'true';
		} else {
			return 'false';
		}
	}
}

/**
 * Convert array to html attr
 *
 * @param  [array] $attr_list
 * @return [string]
 */
if ( ! function_exists( 'gutentor_get_html_attr' ) ) {
	function gutentor_get_html_attr( $attr_list ) {
		if ( ! is_array( $attr_list ) ) {
			return '';
		}
		$attr = '';
		foreach ( $attr_list as $key => $value ) {
			if ( $value ) {
				$attr .= esc_attr( $key ) . '="' . esc_attr( $value ) . '" ';
			}
		}
		return $attr;
	}
}

/**
 * set order and order by.
 *
 * @since 2.1.3
 *
 * @param string $orderby
 * @param string $order.
 * @param array  $args.
 * @return array $args.
 */
function gutentor_set_product_order_order_by( $orderby, $order, $args ) {

	switch ( $orderby ) {
		case 'price':
			$args['orderby']  = 'meta_value_num';
			$args['order']    = $order;
			$args['meta_key'] = '_price';
			break;
		case 'popularity':
			$args['orderby']  = 'meta_value_num';
			$args['order']    = $order;
			$args['meta_key'] = 'total_sales';
			break;
		case 'rating':
			$args['orderby']  = 'meta_value_num';
			$args['order']    = $order;
			$args['meta_key'] = '_wc_average_rating';
			break;
	}
	return $args;
}

/**
 * Function to create query args
 *
 * @param  [array] $attr
 * @return array
 */
function gutentor_get_query( $attr ) {

	$query_args = array(
		'posts_per_page'      => isset( $attr['posts_per_page'] ) ? $attr['posts_per_page'] : 3,
		'post_type'           => isset( $attr['post_type'] ) ? $attr['post_type'] : 'post',
		'orderby'             => isset( $attr['orderby'] ) ? $attr['orderby'] : 'date',
		'order'               => isset( $attr['order'] ) ? $attr['order'] : 'desc',
		'paged'               => isset( $attr['paged'] ) ? $attr['paged'] : 1,
		'ignore_sticky_posts' => true,
		'post_status'         => 'publish',
	);
	/*general*/
	if ( isset( $attr['post_status'] ) && $attr['post_status'] ) {
		$query_args['post_status'] = $attr['post_status'];
	}
	if ( isset( $attr['offset'] ) && $attr['offset'] && ! ( $query_args['paged'] > 1 ) ) {
		$query_args['offset'] = $attr['offset'];
	}
	if ( isset( $attr['post__in'] ) && $attr['post__in'] && is_string( $attr['post__in'] ) ) {
		$query_args['post__in'] = explode( ',', $attr['post__in'] );
	}
	if ( isset( $attr['post__not_in'] ) && $attr['post__not_in'] && is_string( $attr['post__not_in'] ) ) {
		$query_args['post__not_in'] = explode( ',', $attr['post__not_in'] );
	}

	$post_type = isset( $attr['post_type'] ) ? $attr['post_type'] : 'post';
	if ( $post_type === 'product' ) {
		$product_order_by = isset( $attr['orderby'] ) ? $attr['orderby'] : 'date';
		$product_order    = isset( $attr['order'] ) ? $attr['order'] : 'DESC';
		$query_args       = gutentor_set_product_order_order_by( $product_order_by, $product_order, $query_args );

	} else {
		$query_args['orderby'] = isset( $attr['orderby'] ) ? $attr['orderby'] : 'date';
		$query_args['order']   = isset( $attr['order'] ) ? $attr['order'] : 'desc';
	}
	if ( isset( $attr['taxonomy'] ) && $attr['taxonomy'] &&
		isset( $attr['term'] ) && $attr['term'] && $attr['term'] !== 'gAll' ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => $attr['taxonomy'],
				'field'    => 'id',
				'terms'    => $attr['term'],
				'operator' => isset( $attr['taxOperator'] ) && $attr['taxOperator'] ? $attr['taxOperator'] : 'IN',
			),
		);
	}

	/*category*/
	if ( isset( $attr['cat'] ) && $attr['cat'] ) {
		$query_args['cat'] = $attr['cat'];
	}
	if ( isset( $attr['category_name'] ) && $attr['category_name'] ) {
		$query_args['category_name'] = $attr['category_name'];
	}
	if ( isset( $attr['category__in'] ) && $attr['category__in'] && is_string( $attr['category_name'] ) ) {
		$query_args['category__in'] = explode( ',', $attr['category_name'] );
	}
	if ( isset( $attr['category__and'] ) && $attr['category__and'] && is_string( $attr['category__and'] ) ) {
		$query_args['category__and'] = explode( ',', $attr['category__and'] );
	}
	if ( isset( $attr['category__not_in'] ) && $attr['category__not_in'] && is_string( $attr['category__not_in'] ) ) {
		$query_args['category__not_in'] = explode( ',', $attr['category__not_in'] );
	}

	/*Tag*/
	if ( isset( $attr['tag'] ) && $attr['tag'] ) {
		$query_args['tag'] = $attr['tag'];
	}
	if ( isset( $attr['tag_id'] ) && $attr['tag_id'] ) {
		$query_args['tag_id'] = $attr['tag_id'];
	}
	if ( isset( $attr['tag__and'] ) && $attr['tag__and'] && is_string( $attr['tag__and'] ) ) {
		$query_args['tag__and'] = explode( ',', $attr['tag__and'] );
	}
	if ( isset( $attr['tag__in'] ) && $attr['tag__in'] && is_string( $attr['tag__in'] ) ) {
		$query_args['tag__in'] = explode( ',', $attr['tag__in'] );
	}
	if ( isset( $attr['tag__not_in'] ) && $attr['tag__not_in'] && is_string( $attr['tag__not_in'] ) ) {
		$query_args['tag__not_in'] = explode( ',', $attr['tag__not_in'] );
	}

	/*author*/
	if ( isset( $attr['author'] ) && $attr['author'] ) {
		$query_args['author'] = $attr['author'];
	}
	if ( isset( $attr['author_name'] ) && $attr['author_name'] ) {
		$query_args['author_name'] = $attr['author_name'];
	}
	if ( isset( $attr['author__in'] ) && $attr['author__in'] ) {
		if ( is_array( $attr['author__in'] ) ) {
			$query_args['author__in'] = $attr['author__in'];
		} elseif ( is_string( $attr['author__in'] ) ) {
			$query_args['author__in'] = explode( ',', $attr['author__in'] );
		}
	}
	if ( isset( $attr['author__not_in'] ) && $attr['author__not_in'] && is_string( $attr['author__not_in'] ) ) {
		$query_args['author__not_in'] = explode( ',', $attr['author__not_in'] );
	}

	/*search*/
	if ( isset( $attr['s'] ) && $attr['s'] ) {
		$query_args['s'] = $attr['s'];
	}

	/*post and page*/
	if ( isset( $attr['p'] ) && $attr['p'] ) {
		$query_args['p'] = $attr['p'];
	}
	if ( isset( $attr['name'] ) && $attr['name'] ) {
		$query_args['name'] = $attr['name'];
	}
	if ( isset( $attr['page_id'] ) && $attr['page_id'] ) {
		$query_args['page_id'] = $attr['page_id'];
	}
	if ( isset( $attr['pagename'] ) && $attr['pagename'] ) {
		$query_args['pagename'] = $attr['pagename'];
	}
	if ( isset( $attr['post_parent'] ) && $attr['post_parent'] ) {
		$query_args['post_parent'] = $attr['post_parent'];
	}
	if ( isset( $attr['post_parent__in'] ) && $attr['post_parent__in'] && is_string( $attr['post_parent__in'] ) ) {
		$query_args['post_parent__in'] = explode( ',', $attr['post_parent__in'] );
	}

	/*permission*/
	if ( isset( $attr['has_password'] ) && $attr['has_password'] ) {
		$query_args['has_password'] = $attr['has_password'];
	}
	if ( isset( $attr['post_password'] ) && $attr['post_password'] ) {
		$query_args['post_password'] = $attr['post_password'];
	}

	/*comment*/
	if ( isset( $attr['comment_count'] ) && $attr['comment_count'] ) {
		$query_args['comment_count'] = $attr['comment_count'];
	}

	/*pagination*/
	if ( isset( $attr['nopaging'] ) && $attr['nopaging'] ) {
		$query_args['nopaging'] = $attr['nopaging'];
	}
	if ( isset( $attr['posts_per_archive_page'] ) && $attr['posts_per_archive_page'] ) {
		$query_args['posts_per_archive_page'] = $attr['posts_per_archive_page'];
	}
	if ( isset( $attr['paged'] ) && $attr['paged'] ) {
		$query_args['paged'] = $attr['paged'];
	}
	if ( isset( $attr['page'] ) && $attr['page'] ) {
		$query_args['page'] = $attr['page'];
	}
	if ( isset( $attr['ignore_sticky_posts'] ) && $attr['ignore_sticky_posts'] ) {
		$query_args['ignore_sticky_posts'] = $attr['ignore_sticky_posts'];
	}

	/*permission , mimetype, cache*/
	if ( isset( $attr['perm'] ) && $attr['perm'] ) {
		$query_args['perm'] = $attr['perm'];
	}
	if ( isset( $attr['post_mime_type'] ) && $attr['post_mime_type'] && is_string( $attr['post_mime_type'] ) ) {
		$query_args['post_mime_type'] = explode( ',', $attr['post_mime_type'] );
	}
	if ( isset( $attr['cache_results'] ) && $attr['cache_results'] ) {
		$query_args['cache_results'] = $attr['cache_results'];
	}
	if ( isset( $attr['update_post_meta_cache'] ) && $attr['update_post_meta_cache'] ) {
		$query_args['update_post_meta_cache'] = $attr['update_post_meta_cache'];
	}
	if ( isset( $attr['update_post_term_cache'] ) && $attr['update_post_term_cache'] ) {
		$query_args['update_post_term_cache'] = $attr['update_post_term_cache'];
	}

	/*tax query*/
	if ( isset( $attr['tax_query'] ) && $attr['tax_query'] ) {
		if ( is_array( $attr['tax_query'] ) ) {
			$query_args['tax_query'] = $attr['tax_query'];
			foreach ( $attr['tax_query'] as $number => $number_array ) {
				foreach ( $number_array as $data => $data_val ) {
					if ( isset( $number_array['terms'] ) ) {
						$query_args['tax_query'][ $number ]['terms'] = explode( ',', $number_array['terms'] );
					}
				}
			}
		}
	}
	if ( isset( $attr['tax_query_relation'] ) && $attr['tax_query_relation'] ) {
		$query_args['tax_query']['relation'] = $attr['tax_query_relation'];
	}
	/*meta query*/
	if ( isset( $attr['meta_query'] ) && $attr['meta_query'] ) {
		if ( is_array( $attr['meta_query'] ) ) {
			$query_args['meta_query'] = $attr['meta_query'];
		}
	}
	if ( isset( $attr['meta_query_relation'] ) && $attr['meta_query_relation'] ) {
			$query_args['meta_query']['relation'] = $attr['meta_query_relation'];
	}
	/*date query*/
	if ( isset( $attr['date_query'] ) && $attr['date_query'] ) {
		if ( is_array( $attr['date_query'] ) ) {
			$query_args['date_query'] = $attr['date_query'];
		}
	}
	if ( isset( $attr['date_query_relation'] ) && $attr['date_query_relation'] ) {
		$query_args['date_query']['relation'] = $attr['date_query_relation'];
	}

	return $query_args;
}

/**
 * Function to create terms query args
 *
 * @param  [array] $attr
 * @return array
 */
function gutentor_get_term_query( $attr ) {

	$query_args = $attr;
	if ( isset( $attr['taxonomy'] ) && $attr['taxonomy'] ) {
		$query_args['taxonomy'] = explode( ',', $attr['taxonomy'] );
	}
	if ( isset( $attr['number'] ) && $attr['number'] ) {
		$query_args['number'] = $attr['number'];
	} else {
		$query_args['number'] = 6;
	}
	if ( isset( $attr['term_ids'] ) && $attr['term_ids'] ) {
		$query_args['term_ids'] = explode( ',', $attr['term_ids'] );
	}
	if ( isset( $attr['include'] ) && $attr['include'] ) {
		$query_args['include'] = explode( ',', $attr['include'] );
	}
	if ( isset( $attr['exclude'] ) && $attr['exclude'] ) {
		$query_args['exclude'] = explode( ',', $attr['exclude'] );
	}
	if ( isset( $attr['exclude_tree'] ) && $attr['exclude_tree'] ) {
		$query_args['exclude_tree'] = explode( ',', $attr['exclude_tree'] );
	}
	if ( isset( $attr['name'] ) && $attr['name'] ) {
		$query_args['name'] = explode( ',', $attr['name'] );
	}
	if ( isset( $attr['slug'] ) && $attr['slug'] ) {
		$query_args['slug'] = explode( ',', $attr['slug'] );
	}
	if ( isset( $attr['term_taxonomy_id'] ) && $attr['term_taxonomy_id'] ) {
		$query_args['term_taxonomy_id'] = explode( ',', $attr['term_taxonomy_id'] );
	}
	if ( isset( $attr['term_taxonomy_id'] ) && $attr['term_taxonomy_id'] ) {
		$query_args['term_taxonomy_id'] = explode( ',', $attr['term_taxonomy_id'] );
	}
	/*meta query*/
	if ( isset( $attr['meta_query'] ) && $attr['meta_query'] ) {
		if ( is_array( $attr['meta_query'] ) ) {
			$query_args['meta_query'] = $attr['meta_query'];
		}
	}
	if ( isset( $attr['meta_query_relation'] ) && $attr['meta_query_relation'] ) {
		$query_args['meta_query']['relation'] = $attr['meta_query_relation'];
	}
	return $query_args;
}

/**
 * Function to create query args
 *
 * @param  [array] $attr
 * @return array
 */
function gutentor_get_block_by_id( $blocks, $blockId ) {
	if ( is_array( $blocks ) && ! empty( $blocks ) ) {
		foreach ( $blocks as $block ) {

			if ( isset( $block['attrs']['gID'] ) && $block['attrs']['gID'] === $blockId ) {
				return $block;
			}
			if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
				if ( gutentor_get_block_by_id( $block['innerBlocks'], $blockId ) ) {
					return gutentor_get_block_by_id( $block['innerBlocks'], $blockId );
				}
			}
		}
	}
	return array();
}

/**
 * Function create pagination
 *
 * @param  [array] $attr
 * @return String
 */
function gutentor_pagination( $paged = false, $max_num_pages = false ) {
	$phtml = '';
	if ( ! $paged ) {
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	}
	if ( ! $max_num_pages ) {
		global $wp_query;
		$max_num_pages = $wp_query->max_num_pages;
		if ( ! $max_num_pages ) {
			$max_num_pages = 1;
		}
	}
	$mid_pages = $paged >= 3 ? array( $paged - 1, $paged, $paged + 1 ) : array( 1, 2, 3 );
	if ( $max_num_pages > 1 ) {
		if ( ! in_array( 1, $mid_pages ) ) {
			$phtml .= '<li class="gutentor-pagination-item">
                    <a class="gutentor-pagination-link" href="#" data-gpage="1">' . __( '1', 'gutentor' ) . '</a>
                </li>';
		}
		if ( $paged > 3 ) {
			$phtml .= '<li class="gutentor-pagination-item gutentor-pagination-dots"><a class="gutentor-pagination-link" href="#">...</a></li>';
		}
		foreach ( $mid_pages as $i ) {
			if ( $max_num_pages >= $i ) {
				$is_active = $paged === $i ? ' gutentor-pagination-active' : '';
				$phtml    .= '<li class="gutentor-pagination-item' . $is_active . '">
                    <a class="gutentor-pagination-link" href="#" data-gpage="' . $i . '">' . __( $i, 'gutentor' ) . '</a>
                </li>';
			}
		}
		if ( $max_num_pages > $paged + 1 ) {
			if ( $max_num_pages > 3 ) {
				$phtml .= '<li class="gutentor-pagination-item gutentor-pagination-dots"><a class="gutentor-pagination-link" href="#">...</a></li>';
			}
			if ( $max_num_pages > 3 ) {
				$phtml .= '<li class="gutentor-pagination-item">
                    <a class="gutentor-pagination-link" href="#" data-gpage="' . $max_num_pages . '">' . __( $max_num_pages, 'gutentor' ) . '</a>
                </li>';
			}
		}
	}
	return $phtml;
}

/**
 * Get Post Types.
 *
 * @since 2.1.0
 */
function gutentor_admin_get_post_types( $args = array( 'public' => true ), $excludes = array(), $includes = array() ) {

	$post_types = get_post_types(
		$args,
		'objects'
	);
	$exclude_pt = array(
		'edd_wish_list',
		'elementor_library',
		'page',
		'post',
		'attachment',
		'gutentor-template',
	);
	$exclude_pt = array_unique( array_merge( $exclude_pt, $excludes ) );
	$exclude_pt = array_diff( $exclude_pt, $includes );
	$exclude_pt = apply_filters( 'gutentor_admin_get_post_types', $exclude_pt, $excludes, $includes );

	$options = array();
	foreach ( $post_types as $post_type ) {
		if ( in_array( $post_type->name, $exclude_pt ) ) {
			continue;
		}
		$options[] = array(
			'value' => $post_type->name,
			'label' => $post_type->label,
		);
	}
	return $options;
}


function gutentor_is_array_empty( $array ) {
	foreach ( $array as $key => $val ) {
		if ( trim( $val ) !== '' ) {
			return false;
		}
	}
	return true;
}

/**
 * check if Gutentor Pro activated
 */
if ( ! function_exists( 'gutentor_pro_active' ) ) {

	function gutentor_pro_active() {
		return class_exists( 'Gutentor_Pro' );
	}
}

/**
 * check if WooCommerce activated
 */
if ( ! function_exists( 'gutentor_is_woocommerce_active' ) ) {

	function gutentor_is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}
}

/**
 * check if Edd activated
 */
if ( ! function_exists( 'gutentor_is_edd_active' ) ) {

	function gutentor_is_edd_active() {
		return class_exists( 'Easy_Digital_Downloads' );
	}
}

/**
 * check if Edd Rating activated
 */
if ( ! function_exists( 'gutentor_is_edd_review_active' ) ) {

	function gutentor_is_edd_review_active() {
		return ( class_exists( 'Easy_Digital_Downloads' ) && class_exists( 'EDD_Reviews' ) && function_exists( 'edd_reviews' ) );
	}
}

/**
 * check if Edd Whishlist activated
 */
if ( ! function_exists( 'gutentor_is_edd_wishlist_active' ) ) {

	function gutentor_is_edd_wishlist_active() {
		return ( class_exists( 'Easy_Digital_Downloads' ) && class_exists( 'EDD_Wish_Lists' ) );
	}
}
/**
 * check if Edd Whishlist activated
 */
if ( ! function_exists( 'gutentor_is_edd_favorites_active' ) ) {

	function gutentor_is_edd_favorites_active() {
		return ( class_exists( 'Easy_Digital_Downloads' ) && class_exists( 'EDD_Wish_Lists' ) && class_exists( 'EDD_Favorites' ) );
	}
}

/**
 * check if Templateberg activated
 */
if ( ! function_exists( 'gutentor_is_templateberg_active' ) ) {

	function gutentor_is_templateberg_active() {
		return class_exists( 'Templateberg' );
	}
}

/**
 * check if Templateberg connect status
 *
 * @retun boolean
 */
if ( ! function_exists( 'gutentor_templateberg_has_account' ) ) {

	function gutentor_templateberg_has_account() {
		if ( ! gutentor_is_templateberg_active() ) {
			return false;
		}
		return templateberg_connect()->has_account();
	}
}


/**
 * Custom Edd Review
 */
if ( ! function_exists( 'gutentor_custom_edd_review' ) ) {

	function gutentor_custom_edd_review( $id ) {

		// make sure edd reviews is active
		if ( ! function_exists( 'edd_reviews' ) ) {
			return '';
		}

		$edd_reviews = edd_reviews();
		if ( ! $edd_reviews ) {
			return '';
		}
		// get the average rating for this download
		$average_rating = $edd_reviews->average_rating( false, $id );
		if ( ! $average_rating ) {
			return '';
		}
		$rating = round( $average_rating * 2 ) / 2;
		if ( ! $rating ) {
			return '';
		}
		$output = '';

		$output .= '<div class="edd-review-meta-rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';
		for ( $i = 1; $i <= 5;  $i++ ) {
			if ( $i <= $rating ) {
				$output .= '<span class="dashicons dashicons-star-filled"></span>';
			} elseif ( $rating < $i && ( strpos( $rating, '.' ) !== false ) ) {
				$output .= '<span class="dashicons dashicons-star-half"></span>';
				$rating  = absint( $rating );

			} elseif ( $rating < $i ) {
				$output .= '<span class="dashicons dashicons-star-empty"></span>';
			}
		}
		$output .= '<div style="display:none" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';
		$output .= '<meta itemprop="worstRating" content="1" />';
		$output .= '<span itemprop="ratingValue">' . esc_html( $rating ) . '</span>';
		$output .= '<span itemprop="bestRating">5</span>';
		$output .= '</div>';
		$output .= '</div>';
		return $output;
	}
}

/**
 * Just to fix this
 *  ERROR | Extension 'mysql_' is deprecated since PHP 5.5 and removed since PHP 7.0; Use mysqli instead
 *  ERROR | Extension 'mysql_' is deprecated since PHP 5.5 and removed since PHP 7.0; Use mysqli instead
 *
 * function mysql_to_rfc3339 Copied from wp-includes\functions.php.
 *
 * @param string $date_string Date string to parse and format.
 * @return string Date formatted for ISO8601 without time zone.
 */
if ( ! function_exists( 'gutentor_mysql_to_rfc3339' ) ) {
	function gutentor_mysql_to_rfc3339( $date_string ) {
		return mysql2date( 'Y-m-d\TH:i:s', $date_string, false );
	}
}


/**
 * Gutentor has Gutentor blocks
 *
 * @param int|string|WP_Post|null $post Optional. Post content, post ID, or post object. Defaults to global $post.
 *
 * @return bool Whether the post content contains the specified block.
 * @since 3.0.9
 */
if ( ! function_exists( 'gutentor_has_gutentor_block' ) ) {
	function gutentor_has_gutentor_block( $post = null ) {
		$has_block = false;
		if ( ! is_string( $post ) ) {
			$wp_post = get_post( $post );
			if ( $wp_post instanceof WP_Post ) {
				$post = $wp_post->post_content;
			}
		}

		$blocks = parse_blocks( $post );

		if ( ! is_array( $blocks ) || empty( $blocks ) ) {
			$has_block = false;
		} else {
			foreach ( $blocks as $block ) {
				if ( $block['blockName'] ) {
					if ( stripos( $block['blockName'], 'gutentor' ) !== false ) {
						$has_block = true;
						break;
					} elseif ( $block['blockName'] === 'core/block' && ! empty( $block['attrs']['ref'] ) ) {
						if ( gutentor_has_gutentor_block( $block['attrs']['ref'] ) ) {
							$has_block = true;
							break;
						}
					}
				}
			}
		}

		return $has_block;

	}
}

/**
 * Just to fix this
 * has_block doesn't return true when a block is inside a reusable block #18272
 * https://github.com/WordPress/gutenberg/issues/18272
 *
 * Alternative of wp-includes\blocks.php has_block line 296
 *
 * @param string                  $block_name Full Block type to look for.
 * @param int|string|WP_Post|null $post Optional. Post content, post ID, or post object. Defaults to global $post.
 *
 * @return bool Whether the post content contains the specified block.
 * @since 3.0.3
 */
if ( ! function_exists( 'gutentor_has_block' ) ) {
	function gutentor_has_block( $block_name, $post = null ) {
		$has_block = false;
		if ( has_block( $block_name, $post ) ) {
			$has_block = true;
		} else {
			if ( ! is_string( $post ) ) {
				$wp_post = get_post( $post );
				if ( $wp_post instanceof WP_Post ) {
					$post = $wp_post->post_content;
				}
			}
			$blocks = parse_blocks( $post );
			if ( ! is_array( $blocks ) || empty( $blocks ) ) {
				$has_block = false;
			} else {
				foreach ( $blocks as $block ) {
					if ( $block['blockName'] === 'core/block' && ! empty( $block['attrs']['ref'] ) ) {
						if ( has_block( $block_name, $block['attrs']['ref'] ) ) {
							$has_block = true;
							break;
						}
					}
				}
			}
		}
		return $has_block;
	}
}

/**
 * Get all reusable blocks IDs by the post id
 *
 * @param int|string|WP_Post|null $post Optional. Post content, post ID, or post object. Defaults to global $post.
 *
 * @return bool Whether the post content contains the specified block.
 * @since 3.0.9
 */
if ( ! function_exists( 'gutentor_get_reusable_block_ids' ) ) {
	function gutentor_get_reusable_block_ids( $post = null ) {
		$reusable_blocks = array();
		if ( ! is_string( $post ) ) {
			$wp_post = get_post( $post );
			if ( $wp_post instanceof WP_Post ) {
				$post = $wp_post->post_content;
			}
		}

		$blocks = parse_blocks( $post );
		if ( is_array( $blocks ) || ! empty( $blocks ) ) {
			foreach ( $blocks as $block ) {
				if ( $block['blockName'] === 'core/block' && ! empty( $block['attrs']['ref'] ) ) {
					$reusable_blocks[] = $block['attrs']['ref'];
				}
			}
		}
		return $reusable_blocks;
	}
}
/**
 * check if Edd Whishlist activated
 */
if ( ! function_exists( 'gutentor_is_edd_has_price' ) ) {

	function gutentor_is_edd_has_price( $id ) {
		if ( edd_has_variable_prices( $id ) ) {
			return 'price-not-empty';
		} else {
			if ( edd_get_download_price( $id ) == 0 ) {
				return 'price-empty';
			} else {
				return 'price-not-empty';
			}
		}
	}
}


/**
 * Enabled Import button.
 *
 * @since 2.1.0
 */
if ( ! function_exists( 'gutentor_setting_enable_template_library' ) ) {
	function gutentor_setting_enable_template_library() {
		return gutentor_get_options( 'enable-import-block' );
	}
}

/**
 * Enabled Export button.
 *
 * @since 2.1.0
 */
if ( ! function_exists( 'gutentor_setting_enable_export_template_button' ) ) {
	function gutentor_setting_enable_export_template_button() {
		return gutentor_get_options( 'enable-export-block' );
	}
}



/**
 * Get Gutentor Block Full Name.
 *
 * @since 3.1.0
 */
if ( ! function_exists( 'gutentor_block_name_short_full_form' ) ) {
	function gutentor_block_name_short_full_form() {
		$all_blocks = array();
		$elements   = Gutentor_Admin::elements();
		foreach ( $elements as $block_id => $element ) {
			$all_blocks[ $block_id ] = $element['title'];
		}
		$modules = Gutentor_Admin::modules();
		foreach ( $modules as $block_id => $module ) {
			$all_blocks[ $block_id ] = $module['title'];
		}
		$posts = Gutentor_Admin::posts();
		foreach ( $posts as $block_id => $post ) {
			$all_blocks[ $block_id ] = $post['title'];
		}
		$terms = Gutentor_Admin::terms();
		foreach ( $terms as $block_id => $term ) {
			$all_blocks[ $block_id ] = $term['title'];
		}
		$widgets = Gutentor_Admin::content();
		foreach ( $widgets as $block_id => $widget ) {
			$all_blocks[ $block_id ] = $widget['title'];
		}
		return $all_blocks;
	}
}

/**
 * Add Default class to PHP block
 *
 * @since 3.1.0
 */
if ( ! function_exists( 'gutentor_block_add_default_classes' ) ) {
	function gutentor_block_add_default_classes( $name, $attributes = array() ) {
		$classes = 'wp-block-' . $name;

		if ( isset( $attributes['className'] ) && $attributes['className'] ) {
			$classes .= ' ' . esc_attr( $attributes['className'] );
		}
		return $classes;
	}
}

/**
 *Get dynamic Element
 *
 * @since 3.1.0
 */
if ( ! function_exists( 'gutentor_get_dynamic_element' ) ) {
	function gutentor_get_dynamic_element( $post ) {
		$response  = array();
		$g_options = gutentor_get_options();
		if ( isset( $g_options['ptel'] ) ) {
			if ( isset( $g_options['ptel'][ $post->post_type ] ) ) {
				if ( isset( $g_options['ptel'][ $post->post_type ]['elem'] ) ) {
					$response = $g_options['ptel'][ $post->post_type ]['elem'];
				}
			}
		}
		$dynamic_element = array();
		if ( $response ) {
			if ( isset( $response['tax'] ) ) {
				foreach ( $response['tax'] as $key => $value ) {
					$terms = get_the_terms( $post, $key );
					if ( $terms && ! is_wp_error( $terms ) ) :
						foreach ( $terms as $term_key => $term_value ) {
							$term_value->link = esc_url_raw( get_term_link( $term_value->slug, $key ) );
						}
					endif;
					$dynamic_element[ 'tax/' . $key ] = $terms ? $terms : array();
				}
			}
			if ( isset( $response['meta'] ) ) {
				foreach ( $response['meta'] as $key => $value ) {
					$meta = get_post_meta( $post->ID, $key, true );
					if ( is_string( $meta ) || is_numeric( $meta ) ) {
						$dynamic_element[ 'meta/' . $key ] = $meta;
					} else {
						$dynamic_element[ 'meta/' . $key ] = false;
					}
				}
			}
		}
		return $dynamic_element;
	}
}

/**
 *Get dynamic Element
 *
 * @since 3.1.0
 */
if ( ! function_exists( 'gutentor_get_term_dynamic_element' ) ) {
	function gutentor_get_term_dynamic_element( $term ) {
		$response  = array();
		$g_options = gutentor_get_options();
		if ( isset( $g_options['ttel'] ) ) {
			if ( isset( $g_options['ttel'][ $term->taxonomy ] ) ) {
				if ( isset( $g_options['ttel'][ $term->taxonomy ]['elem'] ) ) {
					$response = $g_options['ttel'][ $term->taxonomy ]['elem'];
				}
			}
		}
		$dynamic_element = array();
		if ( $response ) {
			if ( isset( $response['meta'] ) ) {
				foreach ( $response['meta'] as $key => $value ) {
					$meta = get_term_meta( $term->term_id, $key, true );
					if ( is_string( $meta ) || is_numeric( $meta ) ) {
						$dynamic_element[ 'meta/' . $key ] = $meta;
					} else {
						$dynamic_element[ 'meta/' . $key ] = false;
					}
				}
			}
		}
		return $dynamic_element;
	}
}

/**
 * Check has Featured
 *
 * @param  [mix] $var
 * @return [boolean]
 */
if ( ! function_exists( 'gutentor_has_post_featured' ) ) {
	function gutentor_has_post_featured( $post ) {
		$featured_condition = apply_filters( 'gutentor_has_post_featured', has_post_thumbnail( $post->ID ), $post );
		if ( $featured_condition ) {
			return true;
		}
		return false;
	}
}

/**
 * Check if site editor screen
 *
 * @since    3.1.9

 * @return [boolean]
 */
if ( ! function_exists( 'gutentor_is_site_editor_screen' ) ) {
	function gutentor_is_site_editor_screen() {
		if ( get_current_screen() &&
			get_current_screen()->base === 'site-editor'
			&& get_current_screen()->is_block_editor ) {
			return true;
		}
		return false;
	}
}

/**
 * FSE Fixed
 *
 * @since    3.1.9

 * @return [boolean]
 */
if ( ! function_exists( 'gutentor_is_fse_template' ) ) {
	function gutentor_is_fse_template() {
		global $_wp_current_template_content;
		if ( current_theme_supports( 'block-templates' ) && $_wp_current_template_content ) {
			return true;
		}
		return false;

	}
}


/**
 * Simple check for validating a URL, it must start with http:// or https://.
 * and pass FILTER_VALIDATE_URL validation.
 *
 * @since    3.2.1

 * @return [boolean]
 */
if ( ! function_exists( 'gutentor_is_valid_url' ) ) {
	function gutentor_is_valid_url( $url ) {

		// Must start with http:// or https://.
		if ( 0 !== strpos( $url, 'http://' ) && 0 !== strpos( $url, 'https://' ) ) {
			return false;
		}

		// Must pass validation.
		if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
			return false;
		}

		return true;

	}
}

/**
 * Update installed time
 *
 * @since    3.2.1

 * @return void
 */
if ( ! function_exists( 'gutentor_add_installed_time' ) ) {
	function gutentor_add_installed_time() {
		$helper_options = json_decode( get_option( '__gutentor_helper_options' ), true );
		if ( ! isset( $helper_options['installed_time'] ) || ! $helper_options['installed_time'] ) {
			$helper_options['installed_time'] = time();
			update_option(
				'__gutentor_helper_options',
				wp_json_encode( $helper_options )
			);
		}
	}
}
