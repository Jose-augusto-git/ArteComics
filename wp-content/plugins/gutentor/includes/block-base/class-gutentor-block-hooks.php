<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Block_Hooks' ) ) {

	/**
	 * Block Specific Hooks Class For Gutentor
	 *
	 * @package Gutentor
	 * @since 2.0.0
	 */
	class Gutentor_Block_Hooks {

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @return object
		 * @since 2.0.0
		 */
		public static function get_instance() {
			 // Store the instance locally to avoid private static replication.
			static $instance = null;

			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new self();
			}

			// Always return the instance
			return $instance;

		}

		/**
		 * Add Shape Array
		 *
		 * @access public
		 * @return array
		 * @since 2.0.0
		 */
		public function blockShapeDividerSvgArray() {
			$shape = array(
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,6V0H1000V100Z" transform="translate(0 0)"></path></svg>',
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,22.3V0H1000V100Z" transform="translate(0 0)" style="opacity:0.66"></path><path d="M0,6V0H1000V100Z" transform="translate(0 0)"></path></svg>',
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 84.94" preserveAspectRatio="none"><path d="M0,0V72.94c14.46,5.89,32.38,10.5,54.52.26,110.25-51,120.51,23.71,192.6-4.3,144.73-56.23,154.37,49.44,246.71,4.64C637,4.05,622.19,124.16,757.29,66.21c93-39.91,108.38,54.92,242.71-8.25V0Z" style="fill-rule:evenodd;opacity:0.33"></path><path d="M0,0V52.83c131.11,59.9,147-32.91,239.24,6.65,135.09,58,120.24-62.16,263.46,7.34,92.33,44.8,102-60.88,246.71-4.64,72.1,28,82.35-46.71,192.6,4.3,23.95,11.08,43,4.78,58-1.72V0Z" style="fill-rule:evenodd;opacity:0.66"></path><path d="M0,0V24.26c15.6,6.95,35.77,15.41,61.78,3.38,110.25-51,120.51,23.71,192.6-4.3C399.11-32.89,408.75,72.79,501.08,28,644.3-41.51,629.45,78.6,764.54,20.65,855.87-18.53,872.34,72.12,1000,15.7V0Z" style="fill-rule:evenodd"></path></svg>',
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 99" preserveAspectRatio="none"><path d="M526.35,17.11C607.41,28.38,687,48.17,768.06,59.5A1149.19,1149.19,0,0,0,1000,68.07V0H0V99C155.18,13.84,347.42-7.77,526.35,17.11Z" transform="translate(0 0.04)"></path></svg>',
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path class="st0" d="M499.9,51"></path><path class="st1" d="M0,1v94.2c256.7-171,917.3-15.6,1000,4.8V1H0z"></path><path d="M0,100h1.3C273.1-106.1,1000,77.4,1000,77.4V0H0V100z"></path></svg>',
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><polygon points="0,0 0,46.7 40.7,23.4 82.5,47.2 124.2,23.4 166,47.2 207.7,23.4 249.4,47.2 291.2,23.4 332.9,47.2 374.7,23.4 416.4,47.2 458.1,23.4 499.9,47.2 541.6,23.4 583.4,47.2 625.1,23.4 666.9,47.2 708.6,23.4 750.3,47.2 792.1,23.4 833.8,47.2 875.6,23.4 917.3,47.2 959,23.4 1000,46.8 1000,0 "></polygon><path class="st0" d="M499.9,51"></path></svg>',
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 435" preserveAspectRatio="none"><path class="path1" d="M1920,435.1H0V49c32.8,32,92.7,82.1,180,108.3C486.8,249.6,554.4-28.5,918,9.1C1152.9,33.4,1328.5,180,1602,176 c137.7-2,248.9-43,318-75C1920,229.7,1920,306.4,1920,435.1z" style="fill-rule:evenodd;opacity:0.33; transform: rotate(180deg); transform-origin:50% 50%;" ></path><path class="path2" d="M1920,288.1c-228,42-357.8,100.5-489,54c-254.1-90-325.1-324.6-603-315C619.8,34.3,532.8,150,280.5,228.8    c-136.7,42.7-178-42.7-280.5-48.6v255h1920V288.1z" style="fill-rule:evenodd;opacity:0.66; transform: rotate(180deg); transform-origin:50% 50%;"></path><path class="path3" d="M1920,435.1H0v-215c81,5,135,77,243,41c199.3-66.4,294.5-143.1,405-162c315-54,384.2,131.1,585,207 c165,62.4,385,129,687-120C1920,236.1,1920,385.1,1920,435.1z" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"></path></svg>',
				'<svg width="100%" viewBox="0 0 1280 86" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M1280 66.1c-3.8 0-7.6.3-11.4.8-18.3-32.6-59.6-44.2-92.2-25.9-3.5 2-6.9 4.3-10 6.9-22.7-41.7-74.9-57.2-116.6-34.5-14.2 7.7-25.9 19.3-33.8 33.3-.2.3-.3.6-.5.8-12.2-1.4-23.7 5.9-27.7 17.5-11.9-6.1-25.9-6.3-37.9-.6-21.7-30.4-64-37.5-94.4-15.7-12.1 8.6-21 21-25.4 35.2-10.8-9.3-24.3-15-38.5-16.2-8.1-24.6-34.6-38-59.2-29.9-14.3 4.7-25.5 16-30 30.3-4.3-1.9-8.9-3.2-13.6-3.8-13.6-45.5-61.5-71.4-107-57.8a86.38 86.38 0 0 0-43.2 29.4c-8.7-3.6-18.7-1.8-25.4 4.8-23.1-24.8-61.9-26.2-86.7-3.1-7.1 6.6-12.5 14.8-15.9 24-26.7-10.1-56.9-.4-72.8 23.3-2.6-2.7-5.6-5.1-8.9-6.9-.4-.2-.8-.4-1.2-.7-.6-25.9-22-46.4-47.9-45.8-11.5.3-22.5 4.7-30.9 12.5-16.5-33.5-57.1-47.3-90.6-30.8-21.9 11-36.3 32.7-37.6 57.1-7-2.3-14.5-2.8-21.8-1.6C84.8 47 55.7 40.7 34 54.8c-5.6 3.6-10.3 8.4-13.9 14-6.6-1.7-13.3-2.6-20.1-2.6-.1 0 0 19.8 0 19.8h1280V66.1z" fill-opacity=".5" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/><path d="M15.6 86H1280V48.5c-3.6 1.1-7.1 2.5-10.4 4.4-6.3 3.6-11.8 8.5-16 14.5-8.1-1.5-16.4-.9-24.2 1.7-3.2-39-37.3-68.1-76.4-64.9-24.8 2-46.8 16.9-57.9 39.3-19.9-18.5-51-17.3-69.4 2.6-8.2 8.8-12.8 20.3-13.1 32.3-.4.2-.9.4-1.3.7-3.5 1.9-6.6 4.4-9.4 7.2-16.6-24.9-48.2-35-76.2-24.4-12.2-33.4-49.1-50.6-82.5-38.4-9.5 3.5-18.1 9.1-25 16.5-7.1-6.9-17.5-8.8-26.6-5-30.4-39.3-87-46.3-126.2-15.8-14.8 11.5-25.6 27.4-31 45.4-4.9.6-9.7 1.9-14.2 3.9-8.2-25.9-35.8-40.2-61.7-32-15 4.8-26.9 16.5-31.8 31.5-14.9 1.3-29 7.2-40.3 17-11.5-37.4-51.2-58.4-88.7-46.8-14.8 4.6-27.7 13.9-36.7 26.5-12.6-6-27.3-5.7-39.7.6-4.1-12.2-16.2-19.8-29-18.4-.2-.3-.3-.6-.5-.9-24.4-43.3-79.4-58.6-122.7-34.2-13.3 7.5-24.4 18.2-32.4 31.2C99.8 18.5 50 28.5 25.4 65.4c-4.3 6.4-7.5 13.3-9.8 20.6z" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/></g></svg>',
				'<svg width="100%" height="300px" viewBox="0 0 1280 140" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M853.893,86.998c-38.859,0-58.811-16.455-77.956-35.051c18.295-10.536,40.891-18.276,73.378-18.276 c38.685,0,64.132,12.564,85.489,28.347C916.192,72.012,900.8,86.998,853.893,86.998z M526.265,80.945 c-6.517-0.562-13.599-0.879-21.41-0.879c-70.799,0-91.337,27.229-134.433,35.662c14.901,3.72,32.118,6.07,52.898,6.07 C470.171,121.797,500.34,103.421,526.265,80.945z" fill-opacity=".3" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/><path d="M663.458,109.671c-67.137,0-80.345-23.824-137.193-28.726C567.086,45.555,597.381,0,665.691,0 c61.857,0,85.369,27.782,110.246,51.947C736.888,74.434,717.459,109.671,663.458,109.671z M217.68,94.163 c55.971,0,62.526,24.026,126.337,24.026c9.858,0,18.508-0.916,26.404-2.461c-57.186-14.278-80.177-48.808-138.659-48.808 c-77.063,0-99.96,48.569-151.751,48.569c-40.006,0-60.008-12.206-80.011-29.506v16.806c20.003,10.891,40.005,21.782,80.011,21.782 C160.014,124.57,158.608,94.163,217.68,94.163z M1200.112,46.292c-57.493,0-56.935,46.595-115.015,46.595 c-53.612,0-59.755-39.618-115.602-39.618c-15.267,0-25.381,3.751-34.69,8.749c36.096,26.675,60.503,62.552,117.342,62.552 c69.249,0,75.951-43.559,147.964-43.559c39.804,0,59.986,10.943,79.888,21.777V85.982 C1260.097,68.771,1239.916,46.292,1200.112,46.292z" fill-opacity=".5" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/><path d="M1052.147,124.57c-56.84,0-81.247-35.876-117.342-62.552c-18.613,9.994-34.005,24.98-80.912,24.98 c-38.859,0-58.811-16.455-77.956-35.051c-39.05,22.487-58.479,57.724-112.48,57.724c-67.137,0-80.345-23.824-137.193-28.726 c-25.925,22.475-56.093,40.852-102.946,40.852c-20.779,0-37.996-2.349-52.898-6.07c-7.895,1.545-16.546,2.461-26.404,2.461 c-63.811,0-70.366-24.026-126.337-24.026c-59.072,0-57.665,30.407-137.669,30.407c-40.006,0-60.008-10.891-80.011-21.782V140h1280 v-37.212c-19.903-10.835-40.084-21.777-79.888-21.777C1128.098,81.011,1121.397,124.57,1052.147,124.57z" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/></g></svg>',
				'<svg viewBox="0 0 1280 140" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M0 140h1280C573.08 140 0 0 0 0z" style="transform: rotate(180deg); transform-origin:50% 50%;"/></g></svg>',
				'<svg viewBox="0 0 1280 140" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M0 51.76c36.21-2.25 77.57-3.58 126.42-3.58 320 0 320 57 640 57 271.15 0 312.58-40.91 513.58-53.4V0H0z" fill-opacity=".3" style="fill-rule:evenodd;"/><path d="M0 24.31c43.46-5.69 94.56-9.25 158.42-9.25 320 0 320 89.24 640 89.24 256.13 0 307.28-57.16 481.58-80V0H0z" fill-opacity=".5" style="fill-rule:evenodd; "/><path d="M0 0v3.4C28.2 1.6 59.4.59 94.42.59c320 0 320 84.3 640 84.3 285 0 316.17-66.85 545.58-81.49V0z" style="fill-rule:evenodd; "/></g></svg>',
				'<svg version="1.1" x="0px" y="0px" viewBox="0 0 240 24" xml:space="preserve" preserveAspectRatio="none"><path d="M138.75,22.41c-5.43,0.619-12.363-0.09-18.125-1.27c-5.654-1.19-17.017-5.711-24.116-8.642 C78.34,4.689,64.438,1.708,50.312,0.429c-5.664-0.5-11.685-0.84-24.814,0.62C12.358,2.499,0,7.249,0,7.249V24h240v-7.34 c0,0-17.285-7.921-38.018-7.381c-9.404,0.17-22.275,1.61-32.158,5.442c-5.098,1.959-12.539,4.409-19.121,5.879 C144.17,22.061,141.191,22.17,138.75,22.41z" style="transform: rotate(180deg); transform-origin:50% 50%;"></path></svg>',
				'<svg viewBox="0 0 1280 140" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M0 47.44L170 0l626.48 94.89L1110 87.11l170-39.67V140H0V47.44z" fill-opacity=".5" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/><path d="M0 90.72l140-28.28 315.52 24.14L796.48 65.8 1140 104.89l140-14.17V140H0V90.72z" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/></g></svg>',
				'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="100" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 100 C40 0 60 0 100 100 Z" style="transform: rotate(180deg); transform-origin:50% 50%;"></path></svg>',
				'<svg xmlns="http://www.w3.org/2000/svg" version="1.1"  viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 100 C 20 0 50 0 100 100 Z" style="transform: rotate(180deg); transform-origin:50% 50%;"></path></svg>',
				'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="100" viewBox="0 0 100 102" preserveAspectRatio="none"><path d="M0 0 L50 100 L100 0 Z" style="transform: rotate(180deg); transform-origin:50% 50%;"></path></svg>',
				'<svg viewBox="0 0 1280 140" xmlns="http://www.w3.org/2000/svg"><path d="M0 0l64.8 30.95 91.2-2.54 95.46 27.87 120.04.2L443 83.15l90.09-3.12L640 110.12l102.39-29.73 85.55 8.51 88.11-5.75L992 52.22l73.21 4.26L1132 38.79l77-.33L1280 0v140H0V0z" fill-opacity=".5" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/><path d="M0 0l64.8 38.69 91.2-3.18 95.46 34.84 120.04.24 71.5 33.35 90.09-3.91L640 137.65l102.39-37.17 85.55 10.65 88.11-7.19L992 65.28l73.21 5.31 66.79-22.1 77-.41L1280 0v140H0V0z" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/></svg>',
				'<svg width="100%" height="250px" viewBox="0 0 1280 140" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M1280 0l-266 91.52a72.59 72.59 0 0 1-30.76 3.71L0 0v140h1280z" fill-opacity=".5" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/><path d="M1280 0l-262.1 116.26a73.29 73.29 0 0 1-39.09 6L0 0v140h1280z" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/></svg>',
				'<svg width="100%" height="100px" viewBox="0 0 1280 140" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 140h1280C573.08 140 0 0 0 0z" fill-opacity=".3" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/><path d="M0 140h1280C573.08 140 0 30 0 30z" fill-opacity=".5" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/><path d="M0 140h1280C573.08 140 0 60 0 60z" style="fill-rule:evenodd; transform: rotate(180deg); transform-origin:50% 50%;"/></svg>',
			);

			return apply_filters( 'gutentor_shape_list', $shape );
		}

		/**
		 * Add Filter
		 *
		 * @access public
		 * @return void
		 * @since 2.0.0
		 */
		public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
			add_filter( $hook, array( $component, $callback ), $priority, $accepted_args );
		}

		/**
		 * Add Action
		 *
		 * @access public
		 * @return void
		 * @since 2.0.0
		 */
		public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
			add_action( $hook, array( $component, $callback ), $priority, $accepted_args );
		}


		/**
		 * Run Block
		 *
		 * @access public
		 * @return void
		 * @since 2.0.0
		 */
		public function run() {
			/*Block Specific PHP hooks*/
			$this->add_filter( 'gutentor_save_element_class', $this, 'add_element_google_map_section_classes', 10, 2 );
			$this->add_filter( 'gutentor_save_section_class', $this, 'add_section_classes', 10, 2 );
			$this->add_filter( 'gutentor_save_section_class', $this, 'added_content_align_classes', 10, 2 );
			$this->add_filter( 'gutentor_save_before_container', $this, 'addAdvancedVideoOutput', 10, 2 );
			$this->add_filter( 'gutentor_save_section_class', $this, 'add_animation_class', 15, 2 );
			$this->add_filter( 'gutentor_save_container_class', $this, 'add_container_remove_space_classes', 15, 2 );
			$this->add_filter( 'gutentor_save_grid_row_class', $this, 'add_row_remove_space_classes', 15, 2 );
			$this->add_filter( 'gutentor_save_grid_column_class', $this, 'add_column_remove_space_classes', 15, 2 );
			$this->add_filter( 'gutentor_save_before_container', $this, 'addAdvancedBlockShapeTop', 15, 2 );
			$this->add_filter( 'gutentor_save_after_container', $this, 'addAdvancedBlockShapeBottom', 15, 2 );
			$this->add_filter( 'gutentor_save_grid_row_class', $this, 'add_Item_wrap_animation_class', 15, 2 );
			$this->add_filter( 'gutentor_save_item_image_display_data', $this, 'add_link_to_post_thumbnails', 15, 3 );
			$this->add_filter( 'gutentor_save_grid_column_class', $this, 'add_column_class', 10, 2 );
			$this->add_filter( 'gutentor_save_before_block_items', $this, 'add_block_save_header', 10, 2 );
			$this->add_filter( 'gutentor_save_link_attr', $this, 'addButtonLinkAttr', 10, 3 );
			$this->add_filter( 'gutentor_edit_post_module_readmore_button_data', $this, 'addButtonWrap', 10, 3 );
			$this->add_filter( 'gutentor_save_block_header_data', $this, 'gutentor_heading_title', 10, 2 );
			$this->add_filter( 'gutentor_save_grid_column_class', $this, 'addingBlogStyleOptionsClass', 15, 2 );
            $this->add_filter( 'gutentor_post_module_product_button_class', $this, 'addingProductButtonWrap', 10, 3 );
            $this->add_filter( 'gutentor_post_module_download_button_class', $this, 'addingProductButtonWrap', 10, 3 );
			$this->add_filter( 'gutentor_edit_enable_column', $this, 'remove_column_class_blog_post', 8, 2 );

			/*Get dynamic CSS location*/
			$this->add_filter( 'gutentor_dynamic_style_location', $this, 'get_dynamic_style_location' );
		}

		/**
		 * Adding Google Map Section Classes
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return {array}
		 */
		public function add_element_google_map_section_classes( $output, $attributes ) {
			if ( $attributes['gName'] != 'gutentor/e4' ) {
				return $output;
			}

			$local_data       = '';
			$elementAnimation = isset( $attributes['eAnimation'] ) ? $attributes['eAnimation'] : '';
			$animation_class  = ( $elementAnimation && array_key_exists( 'Animation', $attributes['eAnimation'] ) && $attributes['eAnimation']['Animation'] && 'none' != $attributes['eAnimation']['Animation'] ) ? gutentor_concat_space( 'wow animated ', $attributes['eAnimation']['Animation'] ) : '';
			$local_data       = gutentor_concat_space( $local_data, $animation_class );

			/*Section Enable Position*/
			$enable_position = ( $attributes['eOnPos'] ) ? $attributes['eOnPos'] : false;
			if ( $enable_position ) {
				/*Section Desktop Position*/
				$position_desktop = ( $attributes['ePosTypeD'] ) ? $attributes['ePosTypeD'] . '-desktop' : false;
				$local_data       = gutentor_concat_space( $local_data, $position_desktop );

				/*Section Tablet Position*/
				$position_tablet = ( $attributes['ePosTypeT'] ) ? $attributes['ePosTypeT'] . '-tablet' : false;
				$local_data      = gutentor_concat_space( $local_data, $position_tablet );

				/*Section Mobile Position*/
				$position_mobile = ( $attributes['ePosTypeM'] ) ? $attributes['ePosTypeM'] . '-mobile' : false;
				$local_data      = gutentor_concat_space( $local_data, $position_mobile );

			}

			/*Section Display*/
			if ( isset( $attributes['eHideMode'] ) ) {
				$is_d_hide = array_key_exists( 'desktop', $attributes['eHideMode'] ) && $attributes['eHideMode']['desktop'];

				/*Section Tablet Display*/
				$is_t_hide = array_key_exists( 'tablet', $attributes['eHideMode'] ) && $attributes['eHideMode']['tablet'];

				/*Section Mobile Display*/
				$is_m_hide = array_key_exists( 'mobile', $attributes['eHideMode'] ) && $attributes['eHideMode']['mobile'];

				$device_class = '';
				if ( $is_d_hide ) {
					$device_class = 'd-lg-none';
				}
				if ( $is_t_hide ) {
					$device_class .= ' d-md-none';
					if ( ! $is_d_hide ) {
						$device_class .= ' d-lg-block';
					}
				}
				if ( $is_m_hide ) {
					$device_class .= ' d-none';
					if ( ! $is_t_hide ) {
						//$device_class .= ' d-sm-block';
						$device_class .= ' d-md-block';
					}
					if ( ! $device_class && ! strpos( $device_class, 'd-lg-block' ) ) {
						$device_class .= ' d-lg-block';
					}
				}
				$local_data = gutentor_concat_space( $local_data, $device_class );
			}

			/*Concat Output with local data*/
			$local_data = gutentor_concat_space( $output, $local_data );

			return $local_data;

		}

		/**
		 * Adding Section Classes
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return {array}
		 */
		public function add_section_classes( $output, $attributes ) {
			$local_data                  = '';
			$blockComponentBGType        = ( isset( $attributes['blockComponentBGType'] ) ) ? $attributes['blockComponentBGType'] : '';
			$blockComponentEnableOverlay = ( isset( $attributes['blockComponentEnableOverlay'] ) ) ? $attributes['blockComponentEnableOverlay'] : '';

			/* Bg classes */
			$bg_class   = GutentorBackgroundOptionsCSSClasses( $blockComponentBGType );
			$local_data = gutentor_concat_space( $local_data, $bg_class );

			/*Overlay classes*/
			$overlay    = $blockComponentEnableOverlay ? 'has-gutentor-overlay' : '';
			$local_data = gutentor_concat_space( $local_data, $overlay );

			/*Shape Top select classes*/
			$blockShapeTopSelect = ( isset( $attributes['blockShapeTopSelect'] ) ) ? $attributes['blockShapeTopSelect'] : false;
			if ( $blockShapeTopSelect ) {
				$blockShapeTopSelectClass = $blockShapeTopSelect ? 'has-gutentor-block-shape-top' : '';
				$local_data               = gutentor_concat_space( $local_data, $blockShapeTopSelectClass );
			}
			/*mTShapePos select classes*/
			$blockShapeTopPosition = ( isset( $attributes['blockShapeTopPosition'] ) ) ? $attributes['blockShapeTopPosition'] : false;
			if ( $blockShapeTopPosition ) {
				$blockShapeTopPosition = $blockShapeTopPosition ? 'gutentor-block-shape-top-bring-front' : '';
				$local_data            = gutentor_concat_space( $local_data, $blockShapeTopPosition );
			}

			/*mBShapePos select classes*/
			$blockShapeBottomPosition = ( isset( $attributes['blockShapeBottomPosition'] ) ) ? $attributes['blockShapeBottomPosition'] : false;
			if ( $blockShapeBottomPosition ) {
				$blockShapeBottomPosition = $blockShapeBottomPosition ? 'gutentor-block-shape-bottom-bring-front' : '';
				$local_data               = gutentor_concat_space( $local_data, $blockShapeBottomPosition );
			}

			/*mTShapeVFlip select classes*/
			$blockShapeTopFlipVertically = ( isset( $attributes['blockShapeTopFlipVertically'] ) ) ? $attributes['blockShapeTopFlipVertically'] : false;
			if ( $blockShapeTopFlipVertically ) {
				$local_data = gutentor_concat_space( $local_data, 'gutentor-tshape-fv' );
			}

			/*mTShapeHFlip select classes*/
			$blockShapeTopFlipHorizontally = ( isset( $attributes['blockShapeTopFlipHorizontally'] ) ) ? $attributes['blockShapeTopFlipHorizontally'] : false;
			if ( $blockShapeTopFlipHorizontally ) {
				$local_data = gutentor_concat_space( $local_data, 'gutentor-tshape-hv' );
			}

			/*mTShapeHFlip select classes*/
			$blockShapeBottomFlipVertically = ( isset( $attributes['blockShapeBottomFlipVertically'] ) ) ? $attributes['blockShapeBottomFlipVertically'] : false;
			if ( $blockShapeBottomFlipVertically ) {
				$local_data = gutentor_concat_space( $local_data, 'gutentor-bshape-fv' );
			}

			/*mTShapeHFlip select classes*/
			$blockShapeBottomFlipHorizontally = ( isset( $attributes['blockShapeBottomFlipHorizontally'] ) ) ? $attributes['blockShapeBottomFlipHorizontally'] : false;
			if ( $blockShapeBottomFlipHorizontally ) {
				$local_data = gutentor_concat_space( $local_data, 'gutentor-bshape-hv' );
			}

			/*Shape Bottom select classes*/
			$blockShapeBottomSelect = ( isset( $attributes['blockShapeBottomSelect'] ) ) ? $attributes['blockShapeBottomSelect'] : false;
			if ( $blockShapeBottomSelect ) {
				$blockShapeBottomSelectClass = $blockShapeBottomSelect ? 'has-gutentor-block-shape-bottom' : '';
				$local_data                  = gutentor_concat_space( $local_data, $blockShapeBottomSelectClass );
			}

			/*Section Desktop Display*/
			$is_d_hide = ( $attributes['blockComponentDesktopDisplayMode'] ) ? $attributes['blockComponentDesktopDisplayMode'] : false;

			/*Section Tablet Display*/
			$is_t_hide = ( $attributes['blockComponentTabletDisplayMode'] ) ? $attributes['blockComponentTabletDisplayMode'] : false;

			/*Section Mobile Display*/
			$is_m_hide = ( $attributes['blockComponentMobileDisplayMode'] ) ? $attributes['blockComponentMobileDisplayMode'] : false;

			$device_class = '';
			if ( $is_d_hide ) {
				$device_class = 'd-lg-none';
			}
			if ( $is_t_hide ) {
				$device_class .= ' d-md-none';
				if ( ! $is_d_hide ) {
					$device_class .= ' d-lg-block';
				}
			}
			if ( $is_m_hide ) {
				$device_class .= ' d-none';
				if ( ! $is_t_hide ) {
					//$device_class .= ' d-sm-block';
                    $device_class .= ' d-md-block';
				}
				if ( ! $device_class && ! strpos( $device_class, 'd-lg-block' ) ) {
					$device_class .= ' d-lg-block';
				}
			}
			$local_data = gutentor_concat_space( $local_data, $device_class );

			/*Section Enable Position*/
			$enable_position = ( $attributes['blockComponentEnablePosition'] ) ? $attributes['blockComponentEnablePosition'] : false;

			/*Section Desktop Position*/
			$position_desktop = ( $enable_position && $attributes['blockComponentPositionTypeDesktop'] ) ? $attributes['blockComponentPositionTypeDesktop'] . '-desktop' : false;
			$local_data       = gutentor_concat_space( $local_data, $position_desktop );

			/*Section Tablet Position*/
			$position_tablet = ( $enable_position && $attributes['blockComponentPositionTypeTablet'] ) ? $attributes['blockComponentPositionTypeTablet'] . '-tablet' : false;
			$local_data      = gutentor_concat_space( $local_data, $position_tablet );

			/*Section Mobile Position*/
			$position_mobile = ( $enable_position && $attributes['blockComponentPositionTypeMobile'] ) ? $attributes['blockComponentPositionTypeMobile'] . '-mobile' : false;
			$local_data      = gutentor_concat_space( $local_data, $position_mobile );

			/*Concat Output with local data*/
			$local_data = gutentor_concat_space( $output, $local_data );

			return $local_data;

		}

		/**
		 * Adding Content align Class
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return string
		 */
		public function added_content_align_classes( $output, $attributes ) {
			$gutentorBlockName = ( isset( $attributes['gutentorBlockName'] ) ) ? $attributes['gutentorBlockName'] : '';
			$block_list        = array( 'gutentor/blog-post', 'gutentor/google-map' );
			$block_list        = apply_filters( 'gutentor_block_access_content_align_class', $block_list );
			if ( ! in_array( $gutentorBlockName, $block_list ) ) {
				return $output;
			}

			$height_condition       = false;
			$enable_height          = ( isset( $attributes['blockComponentEnableHeight'] ) ) ? $attributes['blockComponentEnableHeight'] : false;
			$has_height             = ( isset( $attributes['blockComponentHeight'] ) ) ? $attributes['blockComponentHeight'] : false;
			$desktop_height         = ( array_key_exists( 'desktop', $has_height ) ) ? $attributes['blockComponentHeight']['desktop'] : false;
			$tablet_height          = ( array_key_exists( 'tablet', $has_height ) ) ? $attributes['blockComponentHeight']['tablet'] : false;
			$mobile_height          = ( array_key_exists( 'mobile', $has_height ) ) ? $attributes['blockComponentHeight']['mobile'] : false;
			$enable_content_v_align = ( isset( $attributes['blockComponentEnableContentVerticalAlign'] ) ) ? $attributes['blockComponentEnableContentVerticalAlign'] : false;
			$desktop_v_align        = ( isset( $attributes['blockComponentContentVerticalAlignDesktop'] ) ) ? $attributes['blockComponentContentVerticalAlignDesktop'] : false;
			$tablet_v_align         = ( isset( $attributes['blockComponentContentVerticalAlignTablet'] ) ) ? $attributes['blockComponentContentVerticalAlignTablet'] : false;
			$mobile_v_align         = ( isset( $attributes['blockComponentContentVerticalAlignMobile'] ) ) ? $attributes['blockComponentContentVerticalAlignMobile'] : false;
			if ( $enable_height && $has_height && $enable_content_v_align && ( $desktop_height || $tablet_height || $mobile_height ) ) {
				$height_condition = true;

			}
			if ( $height_condition && $desktop_v_align ) {
				$desktop_align_data = $desktop_v_align . '-desktop';
				$output             = gutentor_concat_space( $output, $desktop_align_data );

			}
			if ( $height_condition && $tablet_v_align ) {
				$tablet_align_data = $tablet_v_align . '-tablet';
				$output            = gutentor_concat_space( $output, $tablet_align_data );
			}
			if ( $height_condition && $mobile_v_align ) {
				$mobile_align_data = $mobile_v_align . '-mobile';
				$output            = gutentor_concat_space( $output, $mobile_align_data );
			}
			return $output;

		}

		/**
		 * Advanced Options Video Output
		 *
		 * @param {object} output
		 * @param {object} props
		 * @return {object} Inline CSS
		 */
		public function addAdvancedVideoOutput( $output, $attributes ) {
			$gutentorBlockName = ( isset( $attributes['gutentorBlockName'] ) ) ? $attributes['gutentorBlockName'] : '';
			$block_list        = array( 'gutentor/blog-post', 'gutentor/google-map' );
			$block_list        = apply_filters( 'gutentor_widget_block_access_bg_video_html', $block_list );
			if ( ! in_array( $gutentorBlockName, $block_list ) ) {
				return $output;
			}
			$blockComponentBGType = ( isset( $attributes['blockComponentBGType'] ) ) ? $attributes['blockComponentBGType'] : '';
			if ( 'video' !== $blockComponentBGType ) {
				return $output;
			}
			$blockComponentBGVideo      = ( isset( $attributes['blockComponentBGVideo'] ) ) ? $attributes['blockComponentBGVideo'] : '';
			$blockComponentBGVideoLoop  = ( isset( $attributes['blockComponentBGVideoLoop'] ) ) ? $attributes['blockComponentBGVideoLoop'] : '';
			$blockComponentBGVideoMuted = ( isset( $attributes['blockComponentBGVideoMuted'] ) ) ? $attributes['blockComponentBGVideoMuted'] : '';
			$backgroundVideoSrc         = ( isset( $attributes['mBGVideoSrc'] ) ) ? $attributes['mBGVideoSrc'] : '';
			$backgroundVideoUrl         = ( isset( $attributes['mBGVideoUrl'] ) ) ? $attributes['mBGVideoUrl'] : '';
			$videoOutput                = GutentorUpdatedBackgroundVideoOutput( $blockComponentBGType, $backgroundVideoSrc, $blockComponentBGVideo, $backgroundVideoUrl, $blockComponentBGVideoLoop, $blockComponentBGVideoMuted );
			if ( $videoOutput ) {
				$output = gutentor_concat_space( $output, $videoOutput );
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
		public function add_container_remove_space_classes( $output, $attributes ) {
			$gutentorBlockName = ( isset( $attributes['gutentorBlockName'] ) ) ? $attributes['gutentorBlockName'] : '';
			$block_list        = array( 'gutentor/blog-post', 'gutentor/google-map' );
			$block_list        = apply_filters( 'gutentor_block_access_container_remove_space', $block_list );
			if ( ! in_array( $gutentorBlockName, $block_list ) ) {
				return $output;
			}

			$local_data        = '';
			$container_object  = ( isset( $attributes['blockComponentRemoveContainerSpace'] ) ) ? $attributes['blockComponentRemoveContainerSpace'] : false;
			$container_desktop = ( $container_object && isset( $attributes['blockComponentRemoveContainerSpace']['desktop'] ) ) ? $attributes['blockComponentRemoveContainerSpace']['desktop'] : false;
			$container_desktop = ( $container_desktop ) ? 'gutentor-rm-ct-space-d' : '';
			$container_tablet  = ( $container_object && isset( $attributes['blockComponentRemoveContainerSpace']['tablet'] ) ) ? $attributes['blockComponentRemoveContainerSpace']['tablet'] : false;
			$container_tablet  = ( $container_tablet ) ? 'gutentor-rm-ct-space-t' : '';
			$container_mobile  = ( $container_object && isset( $attributes['blockComponentRemoveContainerSpace']['mobile'] ) ) ? $attributes['blockComponentRemoveContainerSpace']['mobile'] : false;
			$container_mobile  = ( $container_mobile ) ? 'gutentor-rm-ct-space' : '';

			/* Remove Desktop Container Class*/
			$local_data = gutentor_concat_space( $local_data, $container_desktop );
			$local_data = gutentor_concat_space( $local_data, $container_tablet );
			$local_data = gutentor_concat_space( $local_data, $container_mobile );

			$local_data = gutentor_concat_space( $output, $local_data );
			return $local_data;

		}


		/**
		 * Adding Row Remove Classes
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return string
		 */
		public function add_row_remove_space_classes( $output, $attributes ) {
			$gutentorBlockName = ( isset( $attributes['gutentorBlockName'] ) ) ? $attributes['gutentorBlockName'] : '';
			$block_list        = array( 'gutentor/blog-post' );
			$block_list        = apply_filters( 'gutentor_block_access_row_remove_space', $block_list );
			if ( ! in_array( $gutentorBlockName, $block_list ) ) {
				return $output;
			}

			$local_data  = '';
			$row_object  = ( isset( $attributes['blockComponentRemoveRowSpace'] ) ) ? $attributes['blockComponentRemoveRowSpace'] : false;
			$row_desktop = ( $row_object && isset( $attributes['blockComponentRemoveRowSpace']['desktop'] ) ) ? $attributes['blockComponentRemoveRowSpace']['desktop'] : false;
			$row_desktop = ( $row_desktop ) ? 'gutentor-rm-row-space-d' : '';
			$row_tablet  = ( $row_object && isset( $attributes['blockComponentRemoveRowSpace']['tablet'] ) ) ? $attributes['blockComponentRemoveRowSpace']['tablet'] : false;
			$row_tablet  = ( $row_tablet ) ? 'gutentor-rm-row-space-t' : '';
			$row_mobile  = ( $row_object && isset( $attributes['blockComponentRemoveRowSpace']['mobile'] ) ) ? $attributes['blockComponentRemoveRowSpace']['mobile'] : false;
			$row_mobile  = ( $row_mobile ) ? 'gutentor-rm-row-space' : '';

			/* Remove Row Class*/
			$local_data = gutentor_concat_space( $local_data, $row_desktop );
			$local_data = gutentor_concat_space( $local_data, $row_tablet );
			$local_data = gutentor_concat_space( $local_data, $row_mobile );

			$local_data = gutentor_concat_space( $output, $local_data );
			return $local_data;

		}

		/**
		 * Adding Column Remove Classes
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return string
		 */
		public function add_column_remove_space_classes( $output, $attributes ) {
			$gutentorBlockName = ( isset( $attributes['gutentorBlockName'] ) ) ? $attributes['gutentorBlockName'] : '';
			$block_list        = array( 'gutentor/blog-post' );
			$block_list        = apply_filters( 'gutentor_block_access_column_remove_space', $block_list );
			if ( ! in_array( $gutentorBlockName, $block_list ) ) {
				return $output;
			}

			$local_data     = '';
			$column_object  = ( isset( $attributes['blockComponentRemoveColumnSpace'] ) ) ? $attributes['blockComponentRemoveColumnSpace'] : false;
			$column_desktop = ( $column_object && isset( $attributes['blockComponentRemoveColumnSpace']['desktop'] ) ) ? $attributes['blockComponentRemoveColumnSpace']['desktop'] : false;
			$column_desktop = ( $column_desktop ) ? 'gutentor-rm-col-space-d' : '';
			$column_tablet  = ( $column_object && isset( $attributes['blockComponentRemoveColumnSpace']['tablet'] ) ) ? $attributes['blockComponentRemoveColumnSpace']['tablet'] : false;
			$column_tablet  = ( $column_tablet ) ? 'gutentor-rm-col-space-t' : '';
			$column_mobile  = ( $column_object && isset( $attributes['blockComponentRemoveColumnSpace']['mobile'] ) ) ? $attributes['blockComponentRemoveColumnSpace']['mobile'] : false;
			$column_mobile  = ( $column_mobile ) ? 'gutentor-rm-col-space' : '';

			/* Remove Column Class*/
			$local_data = gutentor_concat_space( $local_data, $column_desktop );
			$local_data = gutentor_concat_space( $local_data, $column_tablet );
			$local_data = gutentor_concat_space( $local_data, $column_mobile );

			$local_data = gutentor_concat_space( $output, $local_data );
			return $local_data;

		}

		/**
		 * Adding Section Animation Class
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return string
		 */
		public function add_animation_class( $output, $attributes ) {
			$blockComponentAnimation = isset( $attributes['blockComponentAnimation'] ) ? $attributes['blockComponentAnimation'] : '';
			$animation_class         = ( $blockComponentAnimation && $attributes['blockComponentAnimation']['Animation'] && 'none' != $attributes['blockComponentAnimation']['Animation'] ) ? gutentor_concat_space( 'wow animated ', $attributes['blockComponentAnimation']['Animation'] ) : '';
			return gutentor_concat_space( $output, $animation_class );
		}

		/**
		 * Advanced Block Shape Before Container
		 *
		 * @param {string} $output
		 * @param {array}  $attributes
		 * @return string $attributes
		 */
		public function addAdvancedBlockShapeTop( $output, $attributes ) {
			if ( ! array_key_exists( 'blockShapeTopSelect', $attributes ) ) {
				return $output;
			}
			if ( ! isset( $attributes['blockShapeTopSelect'] ) || is_null( $attributes['blockShapeTopSelect'] ) ) {
				return $output;
			}
			$shape = $this->blockShapeDividerSvgArray();
			if ( ! array_key_exists( $attributes['blockShapeTopSelect'], $shape ) ) {
				return $output;
			}
			$shape_data = '<div class="gutentor-block-shape-top"><span>' . $shape[ $attributes['blockShapeTopSelect'] ] . '</span></div>';
			return $output . $shape_data;
		}

		/**
		 * Advanced Block Shape Before Container
		 *
		 * @param {string} $output
		 * @param {array}  $attributes
		 * @return string $attributes
		 */
		public function addAdvancedBlockShapeBottom( $output, $attributes ) {
			if ( ! array_key_exists( 'blockShapeBottomSelect', $attributes ) ) {
				return $output;
			}
			if ( ! isset( $attributes['blockShapeBottomSelect'] ) || is_null( $attributes['blockShapeBottomSelect'] ) ) {
				return $output;
			}
			$shape = $this->blockShapeDividerSvgArray();
			if ( ! array_key_exists( $attributes['blockShapeBottomSelect'], $shape ) ) {
				return $output;
			}
			$shape_data = '<div class="gutentor-block-shape-bottom"><span>' . $shape[ $attributes['blockShapeBottomSelect'] ] . '</span></div>';
			return $output . $shape_data;
		}

		/**
		 * Adding Item Wrap Animation Class
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return string
		 */
		public function add_Item_wrap_animation_class( $output, $attributes ) {
			 $blockItemsWrapAnimation = isset( $attributes['blockItemsWrapAnimation'] ) ? $attributes['blockItemsWrapAnimation'] : '';
			$animation_class          = ( $blockItemsWrapAnimation && $attributes['blockItemsWrapAnimation']['Animation'] && 'none' != $attributes['blockItemsWrapAnimation']['Animation'] ) ? gutentor_concat_space( 'wow animated ', $attributes['blockItemsWrapAnimation']['Animation'] ) : '';
			return gutentor_concat_space( $output, $animation_class );
		}

		/**
		 * Adding Link to Post Thumbnails
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return string
		 */
		public function add_link_to_post_thumbnails( $output, $url, $attributes ) {
			$output_wrap = '';
			$target      = '';
			$class      = '';
			$rel         = '';
			if ( empty( $output ) || $output == null ) {
				return $output;
			}
			if ( ! array_key_exists( 'blockImageBoxLinkOptionsEnable', $attributes ) ) {
				return $output;
			}
			if ( ! $attributes['blockImageBoxLinkOptionsEnable'] ) {
				return $output;
			}
			if ( array_key_exists( 'blockImageBoxLinkOpenNewTab', $attributes ) ) {
				$target = $attributes['blockImageBoxLinkOpenNewTab'] ? 'target="_blank"' : '';
			}
            if ( array_key_exists( 'blockImageBoxLinkClass', $attributes ) ) {
                $class = $attributes['blockImageBoxLinkClass'] ? $attributes['blockImageBoxLinkClass'] : '';
            }
			if ( array_key_exists( 'blockImageBoxLinkRel', $attributes ) ) {
				$rel = ( $attributes['blockImageBoxLinkRel'] ) ? 'rel="' . $attributes['blockImageBoxLinkRel'] . '"' : '';

			}

			$output_wrap .= '<a class="gutentor-single-item-image-link ' . $class . '" href="' . $url . '" ' . $target . ' ' . $rel . '>';
			$output_wrap .= $output;
			$output_wrap .= '</a>';
			return $output_wrap;

		}

		/**
		 * Adding Block Header
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return string
		 */
		public function add_column_class( $output, $attributes ) {
			if ( ! apply_filters( 'gutentor_edit_enable_column', true, $attributes ) ) {
				return $output;
			}
			$local_data               = '';
			$blockItemsColumn_desktop = ( isset( $attributes['blockItemsColumn']['desktop'] ) ) ? $attributes['blockItemsColumn']['desktop'] : '';
			$local_data               = gutentor_concat_space( $local_data, $blockItemsColumn_desktop );
			$blockItemsColumn_tablet  = ( isset( $attributes['blockItemsColumn']['tablet'] ) ) ? $attributes['blockItemsColumn']['tablet'] : '';
			$local_data               = gutentor_concat_space( $local_data, $blockItemsColumn_tablet );
			$blockItemsColumn_mobile  = ( isset( $attributes['blockItemsColumn']['mobile'] ) ) ? $attributes['blockItemsColumn']['mobile'] : '';
			$local_data               = gutentor_concat_space( $local_data, $blockItemsColumn_mobile );
			return gutentor_concat_space( $output, $local_data );
		}

		/**
		 * Adding Block Header
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return string
		 */
		public function add_block_save_header( $output, $attributes ) {
			if ( ! apply_filters( 'gutentor_save_block_header_data_enable', true, $attributes ) ) {
				return $output;
			}
			$blockHeader  = '<div class="' . apply_filters( 'gutentor_save_block_header_class', 'gutentor-block-header', $attributes ) . '">';
			$blockHeader .= apply_filters( 'gutentor_save_block_header_data', '', $attributes );
			$blockHeader .= '</div>';
			return $output . $blockHeader;
		}

		/**
		 * Add Button Attributes
		 *
		 * @param {object} output
		 * @param {string} buttonLink
		 * @param {object} buttonLinkOptions
		 * @return string
		 */
		public function addButtonLinkAttr( $output, $buttonLink, $buttonLinkOptions ) {
			$target        = $rel = '';
			$buttonOptions = ( $buttonLinkOptions && array_key_exists( 'openInNewTab', $buttonLinkOptions ) ) ? $buttonLinkOptions['openInNewTab'] : false;
			if ( $buttonOptions ) {
				$target = $buttonOptions ? '_blank' : '';
			}
			$relOptions = ( $buttonLinkOptions && array_key_exists( 'rel', $buttonLinkOptions ) ) ? $buttonLinkOptions['rel'] : false;
			if ( $relOptions ) {
				$rel = ( $buttonLinkOptions['rel'] ) ? $buttonLinkOptions['rel'] : '';
			}
			$a_href     = ( $buttonLink ) ? 'href="' . $buttonLink . '"' : '';
			$a_target   = ( $target ) ? 'target="' . $target . '" ' : '';
			$local_data = gutentor_concat_space( $a_href, $a_target );
			$a_rel      = ( $rel ) ? 'rel="' . $rel . '" ' : '';
			$local_data = gutentor_concat_space( $local_data, $a_rel );
			return gutentor_concat_space( $output, $local_data );
		}

        /**
         * Add Button Wrap Attributes
         *
         * @param {object} $output
         * @param {string} $post
         * @param {object} buttonLinkOptions
         * @return string
         */
        public function addButtonWrap( $output, $post, $attributes ) {

            if ( $attributes['gName'] != 'gutentor/p1' ) {
                return $output;
            }
            $outPutData        = '';
            $enable_height = ( $attributes['pEqlHeight'] ) ? $attributes['pEqlHeight'] : false;
            if ( !$enable_height ) {
                return $output;
            }
            $outPutData .= '<div class="gutentor-button-wrap">';
            $outPutData .= $output;
            $outPutData .= '</div>';
            return $outPutData;
        }

		/**
		 * Callback functions for body_class,
		 * Adding Admin Body Class.
		 *
		 * @return string
		 * @since    1.0.0
		 * @access   public
		 */
		public function gutentor_heading_title( $data, $attributes ) {
			$output                        = '';
			$block_title_tag               = '';
			$block_title                   = '';
			$section_title_align           = '';
			$section_title_animation       = '';
			$section_title_animation_class = '';
			$block_enable_design_title     = '';
			$block_design_title            = '';
			if ( isset( $attributes['blockComponentTitleAlign'] ) ) {
				$section_title_align = ( $attributes['blockComponentTitleAlign'] ) ? $attributes['blockComponentTitleAlign'] : '';
			}
			if ( isset( $attributes['blockComponentTitleAnimation'] ) ) {
				$section_title_animation = ( $attributes['blockComponentTitleAnimation'] ) ? $attributes['blockComponentTitleAnimation'] : '';
				if ( isset( $attributes['blockComponentTitleAnimation']['Animation'] ) ) {
					$section_title_animation_class = ( $attributes['blockComponentTitleAnimation']['Animation'] && 'none' != $attributes['blockComponentTitleAnimation']['Animation'] ) ? 'wow animated ' . $attributes['blockComponentTitleAnimation']['Animation'] : '';
				}
			}
			if ( isset( $attributes['blockComponentTitleTag'] ) ) {
				$block_title_tag = ( $attributes['blockComponentTitleTag'] ) ? $attributes['blockComponentTitleTag'] : '';
			}
			if ( isset( $attributes['blockComponentTitle'] ) ) {
				$block_title = ( $attributes['blockComponentTitle'] ) ? $attributes['blockComponentTitle'] : '';
			}
			if ( isset( $attributes['blockComponentTitleDesignEnable'] ) ) {
				$block_enable_design_title = ( $attributes['blockComponentTitleDesignEnable'] ) ? 'enable-title-design' : '';
			}
			if ( isset( $attributes['blockComponentTitleDesignEnable'] ) && isset( $attributes['blockComponentTitleSeperatorPosition'] ) ) {
				$block_design_title = ( $attributes['blockComponentTitleDesignEnable'] && $attributes['blockComponentTitleSeperatorPosition'] ) ? $attributes['blockComponentTitleSeperatorPosition'] : 'seperator-bottom';
			}

			$blockComponentTitleEnable = isset( $attributes['blockComponentTitleEnable'] ) ? $attributes['blockComponentTitleEnable'] : false;
			if ( $blockComponentTitleEnable ) {
				$output .= '<div class="gutentor-section-title ' . gutentor_concat_space( $block_enable_design_title, $block_design_title ) . ' ' . gutentor_concat_space( $section_title_align, $section_title_animation_class ) . ' "  ' . GutentorAnimationOptionsDataAttr( $section_title_animation ) . '>' . "\n";
				$output .= '<' . $block_title_tag . ' class="gutentor-title">' . "\n";
				$output .= $block_title;
				$output .= '</' . $block_title_tag . '>' . "\n";
				$output .= '</div>' . "\n";
			}
			return $data . $output;
		}

		/**
		 * Adding Class
		 *
		 * @param {array} output
		 * @param {object} props
		 * @return {array}
		 */
		public function addingBlogStyleOptionsClass( $output, $attributes ) {
			if ( 'gutentor/blog-post' !== $attributes['gutentorBlockName'] ) {
				return $output;
			}
			$blog_style_class = $attributes['blockBlogStyle'] ? $attributes['blockBlogStyle'] : '';
			return gutentor_concat_space( $output, $blog_style_class );
		}

        /**
         * Adding Product
         *
         * @param {array} output
         * @param {object} props
         * @return {array}
         */
        public function addingProductButtonWrap( $output,$post, $attributes ) {
            if ( $attributes['gName'] != 'gutentor/p1' ) {
                return $output;
            }
            $enable_height = ( $attributes['pEqlHeight'] ) ? $attributes['pEqlHeight'] : false;
            if ( !$enable_height ) {
                return $output;
            }
            return gutentor_concat_space( $output, 'gutentor-button-wrap' );
        }

		/**
		 * Remove Column Class in Blog post
		 *
		 * @param {array} output
		 * @param {object} attributes
		 * @return string
		 */
		public function remove_column_class_blog_post( $output, $attributes ) {
			if ( 'gutentor/blog-post' !== $attributes['gutentorBlockName'] ) {
				return $output;
			}
			if ( $attributes['blockBlogStyle'] === 'blog-list' ) {
				return false;
			}
			return $output;
		}

		/**
		 * Get value of gutentor_dynamic_style_location
		 *
		 * @param {string} $gutentor_dynamic_style_location
		 * @return string
		 */
		public function get_dynamic_style_location( $gutentor_dynamic_style_location ) {
			if ( gutentor_get_options( 'dynamic-res-location' ) ) {
				$gutentor_dynamic_style_location = gutentor_get_options( 'dynamic-res-location' );
			}
			return $gutentor_dynamic_style_location;
		}
	}
}

/**
 * Return instance of  Gutentor_Block_Hooks class
 *
 * @since    1.0.0
 */
if ( ! function_exists( 'gutentor_block_hooks' ) ) {

	function gutentor_block_hooks() {
		return Gutentor_Block_Hooks::get_instance();
	}
}
gutentor_block_hooks()->run();
