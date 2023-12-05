<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_E4' ) ) {

	/**
	 * Functions related to Google Map
	 *
	 * @package Gutentor
	 * @since 1.0.1
	 */

	class Gutentor_E4 extends Gutentor_Block_Base {

		/**
		 * Name of the block.
		 *
		 * @access protected
		 * @since 1.0.1
		 * @var string
		 */
		protected $block_name = 'e4';

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
				'id'               => '',
				'blockID'          => '',
				'gID'              => '',
				'e4Loc'            => 'La Sagrada Familia, Barcelona, Spain',
				'e4Lat'            => '41.4036299',
				'e4Lon'            => '2.1743558000000576',
				'e4Type'           => 'roadmap',
				'e4Zoom'           => 15,
				'e4Height'         => array(
					'type'    => 'px',
					'desktop' => '250',
					'tablet'  => '250',
					'mobile'  => '150',
				),
				'e4Draggable'      => true,
				'e4TypeCtrl'       => true,
				'e4ZoomCtrl'       => true,
				'e4FullScrCtrl'    => true,
				'e4StreetViewCtrl' => true,
				'e4Markers'        => array(),
			);
			$google_map_attr = apply_filters( 'gutentor_element_google_map_get_default_values', $google_map_attr );
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
				'id'               => array(
					'type' => 'string',
				),
				'blockID'          => array(
					'type' => 'string',
				),
				'gID'              => array(
					'type' => 'string',
				),
				'gName'            => array(
					'type'    => 'string',
					'default' => 'gutentor/e4',
				),
				'e4Loc'            => array(
					'type'    => 'string',
					'default' => 'La Sagrada Familia, Barcelona, Spain',
				),
				'e4Lat'            => array(
					'type'    => 'string',
					'default' => '41.4036299',
				),
				'e4Lon'            => array(
					'type'    => 'string',
					'default' => '2.1743558000000576',
				),
				'e4Type'           => array(
					'type'    => 'string',
					'default' => 'roadmap',
				),
				'e4Zoom'           => array(
					'type'    => 'number',
					'default' => 15,
				),
				'e4Height'         => array(
					'type'    => 'object',
					'default' => array(
						'type'    => 'px',
						'desktop' => '250',
						'tablet'  => '250',
						'mobile'  => '150',
					),
				),
				'e4Draggable'      => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'e4TypeCtrl'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'e4ZoomCtrl'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'e4FullScrCtrl'    => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'e4StreetViewCtrl' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'e4Markers'        => array(
					'type'    => 'object',
					'default' => array(),
				),
			);
			return array_merge_recursive( $google_map_attr, $this->get_element_common_attrs() );
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
			$blockID = isset( $attributes['gID'] ) ? $attributes['gID'] : '';
			$class   = 'gutentor-google-map';

			$default_class = gutentor_block_add_default_classes( 'gutentor-e4', $attributes );

			if ( isset( $attributes['className'] ) ) {
				$class .= ' ' . $default_class;
			}

			$align = isset( $attributes['align'] ) ? 'align' . $attributes['align'] : '';
			$tag   = 'div';

			$local_attr                      = array();
			$local_attr['id']                = $attributes['id'];
			$local_attr['location']          = $attributes['e4Loc'];
			$local_attr['latitude']          = $attributes['e4Lat'];
			$local_attr['longitude']         = $attributes['e4Lon'];
			$local_attr['zoom']              = $attributes['e4Zoom'];
			$local_attr['type']              = $attributes['e4Type'];
			$local_attr['draggable']         = $attributes['e4Draggable'];
			$local_attr['mapTypeControl']    = $attributes['e4TypeCtrl'];
			$local_attr['zoomControl']       = $attributes['e4ZoomCtrl'];
			$local_attr['fullscreenControl'] = $attributes['e4FullScrCtrl'];
			$local_attr['streetViewControl'] = $attributes['e4StreetViewCtrl'];
			$local_attr['markers']           = $attributes['e4Markers'];

			$block_animation_attrs = isset( $attributes['eAnimation'] ) ? $attributes['eAnimation'] : '';

			$map_section_class = gutentor_concat_space( 'gutentor-element g-el-gmap', $align );
			$map_section_id    = 'section-' . $blockID;
			$map_section_class = gutentor_concat_space( $map_section_class, $map_section_id );
			$class             = gutentor_concat_space( $class, $id );

			$output  = '<' . $tag . ' class="' . apply_filters( 'gutentor_save_element_class', $map_section_class, $attributes ) . '" id="section-' . esc_attr( $blockID ) . '"   ' . GutentorAnimationOptionsDataAttr( $block_animation_attrs ) . '>' . "\n";
			$output .= '<div class="' . apply_filters( 'gutentor_save_grid_row_class', gutentor_concat_space( esc_attr( $class ), 'gutentor-grid-item-wrap' ), $attributes ) . '" id="' . esc_attr( $id ) . '"></div>' . "\n";
			$output .= '</' . $tag . '>' . "\n";
			$output .= '<script type="text/javascript">' . "\n";
			$output .= '	/* <![CDATA[ */' . "\n";
			$output .= '		if ( ! window.gutentorGoogleMaps ) window.gutentorGoogleMaps =[];' . "\n";
			$output .= '		window.gutentorGoogleMaps.push( { container: "' . $id . '", attributes: ' . wp_json_encode( $local_attr ) . ' } );' . "\n";
			$output .= '	/* ]]> */' . "\n";
			$output .= '</script>' . "\n";

			return $output;
		}
	}
}
Gutentor_E4::get_instance()->run();
