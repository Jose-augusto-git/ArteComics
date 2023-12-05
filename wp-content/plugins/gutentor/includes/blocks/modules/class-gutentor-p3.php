<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_P3_Hooks' ) ) {

	/**
	 * Block Specific Hooks Class For Gutentor
	 *
	 * @package Gutentor
	 * @since 2.0.0
	 */
	class Gutentor_P3_Hooks extends Gutentor_Block_Base {

		/**
		 * Name of the block.
		 *
		 * @access protected
		 * @since 1.0.1
		 * @var string
		 */
		protected $block_name = 'p3';

		/**
		 * Prevent some functions to called many times
		 *
		 * @access private
		 * @since 2.0.0
		 * @var integer
		 */
		private static $counter = 0;

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @since 2.0.0
		 * @return object
		 */
		public static function get_instance() {

			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new self();
			}

			// Always return the instance
			return $instance;

		}

		/**
		 * Set register_block_type_args variable on parent
		 * Used for blog template loading
		 *
		 * @since      3.0.6
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 */
		public function register_block_type_args() {
			$this->register_block_type_args = array(
				'view_script_handles' => array( 'slick' ),
				'style_handles'       => array( 'slick' ),
			);
		}

		/**
		 * Add Filter
		 *
		 * @access public
		 * @since 2.0.0
		 * @return void
		 */
		public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
			add_filter( $hook, array( $component, $callback ), $priority, $accepted_args );
		}

		/**
		 * Add Action
		 *
		 * @access public
		 * @since 2.0.0
		 * @return void
		 */
		public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
			add_action( $hook, array( $component, $callback ), $priority, $accepted_args );
		}


		/**
		 * Run Block
		 *
		 * @access public
		 * @since 2.0.0
		 * @return void
		 */
		public function run() {
			parent::run();
			/*Block Specific PHP hooks*/
			$this->add_filter( 'gutentor_post_module_main_wrap_class', $this, 'add_carousel_arrow_class', 15, 2 );
			$this->add_filter( 'gutentor_post_module_grid_row_class', $this, 'add_carousel_row', 15, 2 );
			$this->add_filter( 'gutentor_post_module_attr', $this, 'add_carousel_data', 15, 2 );
			$this->add_filter( 'gutentor_post_module_grid_column_class', $this, 'add_carousel_class', 15, 2 );
			$this->add_filter( 'gutentor_post_module_before_block_items', $this, 'add_carousel_arrow', 15, 2 );
		}

		/**
		 * Adding Carousel Class
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return {array}
		 */
		public function add_carousel_arrow_class( $output, $attributes ) {

			$gutentorBlockName = ( isset( $attributes['gName'] ) ) ? $attributes['gName'] : '';
			$block_list        = array( 'gutentor/p1' );
			if ( ! in_array( $gutentorBlockName, $block_list ) ) {
				return $output;
			}
			if ( ! isset( $attributes['p1CarouselOpt'] ) || ! $attributes['p1CarouselOpt']['enable'] ) {
				return $output;
			}
			if ( ! isset( $attributes['p1CarouselOpt']['arrowsPosition'] ) ) {
				return $output;
			}

			$arrow_position         = $attributes['p1CarouselOpt']['arrowsPosition'];
			$enable_desktop_arrow   = isset( $attributes['p1CarouselOpt']['arrows'] ) && $attributes['p1CarouselOpt']['arrows'];
			$enable_tablet_arrow    = isset( $attributes['p1CarouselOpt']['arrowsT'] ) && $attributes['p1CarouselOpt']['arrowsT'];
			$enable_mobile_arrow    = isset( $attributes['p1CarouselOpt']['arrowsM'] ) && $attributes['p1CarouselOpt']['arrowsM'];
			$arrow_position_desktop = array_key_exists( 'desktop', $arrow_position ) ? $arrow_position['desktop'] : false;
			if ( $enable_desktop_arrow && $arrow_position_desktop ) {
				$output = gutentor_concat_space( $output, $arrow_position_desktop . '-desktop' );

			}
			$arrow_position_tablet = array_key_exists( 'tablet', $arrow_position ) ? $arrow_position['tablet'] : false;
			if ( $enable_tablet_arrow && $arrow_position_tablet ) {
				$output = gutentor_concat_space( $output, $arrow_position_tablet . '-tablet' );

			}
			$arrow_position_mobile = array_key_exists( 'mobile', $arrow_position ) ? $arrow_position['mobile'] : false;
			if ( $enable_mobile_arrow && $arrow_position_mobile ) {
				$output = gutentor_concat_space( $output, $arrow_position_mobile . '-mobile' );
			}
			return $output;
		}

		/**
		 * Adding Container Remove Classes
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return string
		 */
		public function add_carousel_row( $output, $attributes ) {
			if ( ! isset( $attributes['p1CarouselOpt'] ) || ! $attributes['p1CarouselOpt']['enable'] ) {
				return $output;
			}

			$local_data = str_replace( 'grid-row', '', $output );
			if ( isset( $attributes['p1CarouselOpt']['carouselID'] ) ) {
				$local_data = gutentor_concat_space( $local_data, $attributes['p1CarouselOpt']['carouselID'] );
			}
			return gutentor_concat_space( $local_data, 'gutentor-module-carousel-row' );
		}

		/**
		 * Adding Carousel Data
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return {array}
		 */
		public function add_carousel_data( $output, $attributes ) {
			if ( ! isset( $attributes['p1CarouselOpt'] ) || ! $attributes['p1CarouselOpt']['enable'] ) {
				return $output;
			}
			$p1CarouselOpt = $attributes['p1CarouselOpt'];
			$local_data    = array();
			if ( isset( $p1CarouselOpt['dots'] ) ) {
				$local_data['data-dots'] = ( $p1CarouselOpt['dots'] ) ? 'true' : 'false';
			}
			if ( isset( $p1CarouselOpt['dotsT'] ) ) {
				$local_data['data-dotstablet'] = ( $p1CarouselOpt['dotsT'] ) ? 'true' : 'false';
			}
			if ( isset( $p1CarouselOpt['dotsM'] ) ) {
				$local_data['data-dotsmobile'] = ( $p1CarouselOpt['dotsM'] ) ? 'true' : 'false';
			}
			if ( isset( $p1CarouselOpt['arrowNext'] ) ) {
				$local_data['data-nextarrow'] = ( $p1CarouselOpt['arrowNext'] ) ? $p1CarouselOpt['arrowNext'] : '';
			}
			if ( isset( $p1CarouselOpt['arrowsPrev'] ) ) {
				$local_data['data-prevarrow'] = ( $p1CarouselOpt['arrowsPrev'] ) ? $p1CarouselOpt['arrowsPrev'] : '';
			}
			if ( isset( $p1CarouselOpt['arrows'] ) ) {
				$local_data['data-arrows'] = ( $p1CarouselOpt['arrows'] ) ? 'true' : 'false';
			}
			if ( isset( $p1CarouselOpt['arrowsT'] ) ) {
				$local_data['data-arrowstablet'] = ( $p1CarouselOpt['arrowsT'] ) ? 'true' : 'false';
			}
			if ( isset( $p1CarouselOpt['arrowsM'] ) ) {
				$local_data['data-arrowsmobile'] = ( $p1CarouselOpt['arrowsM'] ) ? 'true' : 'false';
			}
			if ( isset( $p1CarouselOpt['arrowsPosition']['desktop'] ) ) {
				$local_data['data-arrowsPositionDesktop'] = ( $p1CarouselOpt['arrowsPosition']['desktop'] . '-desktop' );
			}
			if ( isset( $p1CarouselOpt['arrowsPosition']['tablet'] ) ) {
				$local_data['data-arrowsPositionTablet'] = ( $p1CarouselOpt['arrowsPosition']['tablet'] . '-tablet' );
			}
			if ( isset( $p1CarouselOpt['arrowsPosition']['mobile'] ) ) {
				$local_data['data-arrowsPositionMobile'] = ( $p1CarouselOpt['arrowsPosition']['mobile'] . '-mobile' );
			}
			if ( isset( $p1CarouselOpt['infinite'] ) ) {
				$local_data['data-infinite'] = ( $p1CarouselOpt['infinite'] ) ? 'true' : 'false';

			}
			if ( isset( $p1CarouselOpt['speed'] ) ) {
				$local_data['data-speed'] = $p1CarouselOpt['speed'];
			}
			if ( isset( $p1CarouselOpt['autoplay'] ) ) {
				$local_data['data-autoplay'] = ( $p1CarouselOpt['autoplay'] ) ? 'true' : 'false';
				if ( isset( $p1CarouselOpt['autoplaySpeed'] ) ) {
					$local_data['data-autoplayspeed'] = $p1CarouselOpt['autoplaySpeed'];
				}
				if ( isset( $p1CarouselOpt['pauseOnFocus'] ) ) {
					$local_data['data-pauseonfocus'] = ( $p1CarouselOpt['pauseOnFocus'] ) ? 'true' : 'false';
				}
				if ( isset( $p1CarouselOpt['pauseOnHover'] ) ) {
					$local_data['data-pauseonhover'] = ( $p1CarouselOpt['pauseOnHover'] ) ? 'true' : 'false';
				}
			}
			if ( isset( $p1CarouselOpt['draggable'] ) ) {
				$local_data['data-draggable'] = ( $p1CarouselOpt['draggable'] ) ? 'true' : 'false';
			}
			/*center mode*/
			if ( isset( $p1CarouselOpt['cmondesktop'] ) ) {
				$local_data['data-cmondesktop'] = ( $p1CarouselOpt['cmondesktop'] ) ? 'true' : 'false';
				if ( isset( $p1CarouselOpt['cmpaddingdesktop'] ) ) {
					$local_data['data-cmpaddingdesktop'] = ( $p1CarouselOpt['cmpaddingdesktop'] ) ? $p1CarouselOpt['cmpaddingdesktop'] : '';
				}
			}
			if ( isset( $p1CarouselOpt['cmontablet'] ) ) {
				$local_data['data-cmontablet'] = ( $p1CarouselOpt['cmontablet'] ) ? 'true' : 'false';
				if ( isset( $p1CarouselOpt['cmpaddingtablet'] ) ) {
					$local_data['data-cmpaddingtablet'] = ( $p1CarouselOpt['cmpaddingtablet'] ) ? $p1CarouselOpt['cmpaddingtablet'] : '';
				}
			}
			if ( isset( $p1CarouselOpt['cmonmobile'] ) ) {
				$local_data['data-cmonmobile'] = ( $p1CarouselOpt['cmonmobile'] ) ? 'true' : 'false';
				if ( isset( $p1CarouselOpt['cmpaddingmobile'] ) ) {
					$local_data['data-cmpaddingmobile'] = ( $p1CarouselOpt['cmpaddingmobile'] ) ? $p1CarouselOpt['cmpaddingmobile'] : '';
				}
			}
			if ( isset( $p1CarouselOpt['slideitem']['desktop'] ) ) {
				$local_data['data-slideitemdesktop'] = $p1CarouselOpt['slideitem']['desktop'];
			}
			if ( isset( $p1CarouselOpt['slideitem']['tablet'] ) ) {
				$local_data['data-slideitemtablet'] = $p1CarouselOpt['slideitem']['tablet'];
			}
			if ( isset( $p1CarouselOpt['slideitem']['mobile'] ) ) {
				$local_data['data-slideitemmobile'] = $p1CarouselOpt['slideitem']['mobile'];
			}
			if ( isset( $p1CarouselOpt['slidescroll']['desktop'] ) ) {
				$local_data['data-slidescroll-desktop'] = $p1CarouselOpt['slidescroll']['desktop'];
			}
			if ( isset( $p1CarouselOpt['slidescroll']['tablet'] ) ) {
				$local_data['data-slidescroll-tablet'] = $p1CarouselOpt['slidescroll']['tablet'];
			}
			if ( isset( $p1CarouselOpt['slidescroll']['mobile'] ) ) {
				$local_data['data-slidescroll-mobile'] = $p1CarouselOpt['slidescroll']['mobile'];
			}
			return $local_data;
		}

		/**
		 * Adding carousel class
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return {array}
		 */
		public function add_carousel_class( $output, $attributes ) {

			if ( ! isset( $attributes['p1CarouselOpt'] ) || ! $attributes['p1CarouselOpt']['enable'] ) {
				return $output;
			}
			return gutentor_concat_space( $output, 'gutentor-carousel-item' );
		}

		/**
		 * Adding carousel class
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return {array}
		 */
		public function add_carousel_arrow( $output, $attributes ) {

			$gutentorBlockName = ( isset( $attributes['gName'] ) ) ? $attributes['gName'] : '';
			$block_list        = array( 'gutentor/p1' );
			if ( ! in_array( $gutentorBlockName, $block_list ) ) {
				return $output;
			}
			if ( ! isset( $attributes['p1CarouselOpt'] ) || ! $attributes['p1CarouselOpt']['enable'] ) {
				return $output;
			}
			$p1CarouselOpt        = ( isset( $attributes['p1CarouselOpt'] ) && $attributes['p1CarouselOpt']['enable'] ) ? $attributes['p1CarouselOpt'] : false;
			$desktop_row_position = ( $p1CarouselOpt && $p1CarouselOpt['arrowsPosition']['desktop'] ) ? $p1CarouselOpt['arrowsPosition']['desktop'] . '-desktop' : false;
			if ( $desktop_row_position != 'gutentor-slick-a-default-desktop' ) {
				$output .= '<div class="gutentor-slick-arrows"></div>';
			}
			return $output;
		}
	}
}

/**
 * Return instance of  Gutentor_P3_Hooks class
 *
 * @since    1.0.0
 */
if ( ! function_exists( 'gutentor_p3_hooks' ) ) {

	function gutentor_p3_hooks() {

		return Gutentor_P3_Hooks::get_instance();
	}
}
gutentor_p3_hooks()->run();
