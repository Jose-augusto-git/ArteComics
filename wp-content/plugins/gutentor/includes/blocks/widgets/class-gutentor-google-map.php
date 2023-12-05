<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Google_Map' ) ) {

	/**
	 * Functions related to Google Map
	 *
	 * @package Gutentor
	 * @since 1.0.1
	 */

	class Gutentor_Google_Map extends Gutentor_Block_Base {

		/**
		 * Name of the block.
		 *
		 * @access protected
		 * @since 1.0.1
		 * @var string
		 */
		protected $block_name = 'google-map';

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @since 1.0.1
		 * @return object
		 */
		public static function get_instance() {

			// Store the instance locally to avoid private static replication.
			static $instance = null;

			// Only run these methods if they haven't been ran previously.
			if ( null === $instance ) {
				$instance = new self();
			}

			// Always return the instance.
			return $instance;

		}

		/**
		 * Set register_block_type_args variable on parent
		 * Used for blog template loading
		 *
		 * @since      3.2.6
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 */
		public function register_block_type_args() {
			$this->register_block_type_args = array(
				'view_script_handles' => array( 'gutentor-google-maps', 'google-maps' ),
			);
		}

		/**
		 * Google Map Attributes Default Value
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 */
		public function get_default_values() {
			$google_map_attr = array(
				'id'                => '',
				'location'          => 'La Sagrada Familia, Barcelona, Spain',
				'containerWidth'    => 'grid-container',
				'latitude'          => '41.4036299',
				'longitude'         => '2.1743558000000576',
				'type'              => 'roadmap',
				'zoom'              => 15,
				'mapHeight'         => array(
					'type'    => 'px',
					'desktop' => '250',
					'tablet'  => '250',
					'mobile'  => '150',
				),
				'draggable'         => true,
				'mapTypeControl'    => true,
				'zoomControl'       => true,
				'fullscreenControl' => true,
				'streetViewControl' => true,
				'markers'           => array(),
			);
			$google_map_attr = apply_filters( 'gutentor_google_map_get_default_values', $google_map_attr );
			return $google_map_attr;
		}

		/**
		 * Returns attributes for this Block
		 *
		 * @static
		 * @access public
		 * @since 1.0.0
		 * @return array
		 */
		protected function get_attrs() {
			$google_map_attr = array(
				'id'                => array(
					'type' => 'string',
				),
				'blockID'           => array(
					'type' => 'string',
				),
				'gutentorBlockName' => array(
					'type' => 'string',
				),
				'containerWidth'    => array(
					'type'    => 'string',
					'default' => 'grid-container',
				),
				'location'          => array(
					'type'    => 'string',
					'default' => 'La Sagrada Familia, Barcelona, Spain',
				),
				'latitude'          => array(
					'type'    => 'string',
					'default' => '41.4036299',
				),
				'longitude'         => array(
					'type'    => 'string',
					'default' => '2.1743558000000576',
				),
				'type'              => array(
					'type'    => 'string',
					'default' => 'roadmap',
				),
				'zoom'              => array(
					'type'    => 'number',
					'default' => 15,
				),
				'mapHeight'         => array(
					'type'    => 'object',
					'default' => array(
						'type'    => 'px',
						'desktop' => '250',
						'tablet'  => '250',
						'mobile'  => '150',
					),
				),
				'draggable'         => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'mapTypeControl'    => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'zoomControl'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'fullscreenControl' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'streetViewControl' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'markers'           => array(
					'type'    => 'object',
					'default' => array(),
				),
				'mBGImageSrc'       => array(
					'type'    => 'string',
					'default' => 'self-hosted-local',
				),
				'mBGVideoSrc'       => array(
					'type'    => 'string',
					'default' => 'self-hosted-local',
				),
				'mBGVideoUrl'       => array(
					'type'    => 'string',
					'default' => 'https://www.youtube.com/watch?v=bGMi7L78hVk',
				),
			);
			return $google_map_attr;
		}

		/**
		 * Render Google Map Data
		 *
		 * @since    1.0.1
		 * @access   public
		 *
		 * @param array  $attributes
		 * @param string $content
		 * @return string
		 */
		public function render_callback( $attributes, $content ) {

			$id      = isset( $attributes['id'] ) ? $attributes['id'] : 'gutentor-google-map-' . wp_rand( 10, 100 );
			$blockID = isset( $attributes['blockID'] ) ? $attributes['blockID'] : '';

			$default_class = gutentor_block_add_default_classes( 'gutentor-google-map', $attributes );

			$align = isset( $attributes['align'] ) ? 'align' . $attributes['align'] : '';
			$tag   = $attributes['blockSectionHtmlTag'] ? $attributes['blockSectionHtmlTag'] : 'section';

			$blockComponentAnimation = isset( $attributes['blockComponentAnimation'] ) ? $attributes['blockComponentAnimation'] : '';
			$blockItemsWrapAnimation = isset( $attributes['blockItemsWrapAnimation'] ) ? $attributes['blockItemsWrapAnimation'] : '';

			$output  = '<' . $tag . ' class="' . apply_filters( 'gutentor_save_section_class', gutentor_concat_space( 'gutentor-section gutentor-google-map', $align, $default_class ), $attributes ) . '" id="section-' . esc_attr( $blockID ) . '"   ' . GutentorAnimationOptionsDataAttr( $blockComponentAnimation ) . '>' . "\n";
			$output .= apply_filters( 'gutentor_save_before_container', '', $attributes );
			$output .= "<div class='" . apply_filters( 'gutentor_save_container_class', 'grid-container', $attributes ) . "'>";
			$output .= apply_filters( 'gutentor_save_before_block_items', '', $attributes );
			$output .= '<div class="' . apply_filters( 'gutentor_save_grid_row_class', esc_attr( 'gutentor-grid-item-wrap' ), $attributes ) . '" id="' . esc_attr( $id ) . '" ' . GutentorAnimationOptionsDataAttr( $blockItemsWrapAnimation ) . '></div>' . "\n";
			$output .= apply_filters( 'gutentor_save_after_block_items', '', $attributes );
			$output .= '</div>' . "\n";
			$output .= apply_filters( 'gutentor_save_after_container', '', $attributes );
			$output .= '</' . $tag . '>' . "\n";
			$output .= '<script type="text/javascript">' . "\n";
			$output .= '	/* <![CDATA[ */' . "\n";
			$output .= '		if ( ! window.gutentorGoogleMaps ) window.gutentorGoogleMaps =[];' . "\n";
			$output .= '		window.gutentorGoogleMaps.push( { container: "' . $id . '", attributes: ' . wp_json_encode( $attributes ) . ' } );' . "\n";
			$output .= '	/* ]]> */' . "\n";
			$output .= '</script>' . "\n";

			return $output;
		}
	}
}
Gutentor_Google_Map::get_instance()->run();
