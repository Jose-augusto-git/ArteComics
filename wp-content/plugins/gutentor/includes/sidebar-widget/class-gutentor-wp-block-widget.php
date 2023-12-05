<?php
/**
 * Class for adding Reusable Block Widget
 *
 * @package AddonsPress
 * @subpackage Gutentor
 * @since 2.0.3
 */
if ( ! class_exists( 'Gutentor_WP_Block_Widget' ) ) {

	class Gutentor_WP_Block_Widget extends WP_Widget {
		/*defaults values for fields*/
		private $defaults = array(
			'title'       => '',
			'wp_block_id' => '',
		);

		/*Used Blocks*/
		private $used_blocks = array();

		/**
		 * All blocks on the widgets
		 *
		 * @var array
		 * @access public
		 * @since 1.0.0
		 */
		public static $unique_blocks = array();

		function __construct() {
			parent::__construct(
			/*Base ID of your widget*/
				'gutentor_wp_block_widget',
				/*Widget name will appear in UI*/
				esc_html__( 'Addons Gutentor Block', 'gutentor' ),
				/*Widget description*/
				array(
					'description' => esc_html__( 'Feel free to add Gutenberg Block on the Widgets', 'gutentor' ),
				)
			);
			/*Add Scripts*/
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
			add_action( 'wp_footer', array( $this, 'add_missing_assets' ) );
			add_action( 'wp_footer', array( $this, 'add_used_blocks_css' ), 20 );
		}

		/*Widget Backend*/
		public function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, $this->defaults );
			/*default values*/
			$wp_block_id = absint( $instance['wp_block_id'] );

			printf(
				'<h3><a href="%1$s" target="_blank">%2$s</a></h3>',
				admin_url( 'edit.php?post_type=wp_block' ),
				__( 'Go to here to add Block', 'gutentor' )
			);
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title (Optional)', 'gutentor' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<?php
			$item_arg   = array(
				'post_type'      => 'wp_block',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'order'          => 'DESC',
			);
			$item_query = new WP_Query( $item_arg );
			if ( $item_query->have_posts() ) :
				printf(
					'<p><label for="%1$s">%2$s</label><br/><small>%4$s</small>' .
					'<select class="widefat" id="%1$s" name="%3$s">',
					$this->get_field_id( 'wp_block_id' ),
					__( 'Select Block:', 'gutentor' ),
					$this->get_field_name( 'wp_block_id' ),
					esc_html__( 'Select block and its content will display in the frontend.', 'gutentor' )
				);
				printf(
					'<option value="%1$s" %2$s>%3$s</option>',
					0,
					selected( 0, $wp_block_id, false ),
					__( 'Select Block', 'gutentor' )
				);
				while ( $item_query->have_posts() ) :
					$item_query->the_post();
					printf(
						'<option value="%1$s" %2$s>%3$s</option>',
						absint( get_the_ID() ),
						selected( get_the_ID(), $wp_block_id, false ),
						get_the_title()
					);
				endwhile;
				wp_reset_postdata();
				echo '</select></p>';
			endif;
		}

		/**
		 * Function to Updating widget replacing old instances with new
		 *
		 * @access public
		 * @since 1.0.0
		 *
		 * @param array $new_instance new arrays value
		 * @param array $old_instance old arrays value
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance                = $old_instance;
			$instance['title']       = sanitize_text_field( $new_instance['title'] );
			$instance['wp_block_id'] = absint( $new_instance['wp_block_id'] );

			return $instance;
		}

		/**
		 * Function to Creating widget front-end. This is where the action happens
		 *
		 * @access public
		 * @since 1.0
		 *
		 * @param array $args widget setting
		 * @param array $instance saved values
		 * @return void
		 */
		public function widget( $args, $instance ) {

			$instance = wp_parse_args( (array) $instance, $this->defaults );

			/*default values*/
			$title       = apply_filters( 'widget_title', ! empty( $instance['title'] ) ? $instance['title'] : '', $instance, $this->id_base );
			$wp_block_id = absint( $instance['wp_block_id'] );

			echo $args['before_widget'];
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
			}
			if ( ! empty( $wp_block_id ) ) :
				$g_args  = array(
					'p'         => $wp_block_id,
					'post_type' => 'wp_block',
				);
				$g_query = new WP_Query( $g_args );
				/*The Loop*/
				if ( $g_query->have_posts() ) :
					echo '<div class="gutentor-widget gutentor-wp-block-widget">';
					while ( $g_query->have_posts() ) :
						$g_query->the_post();

						$already_used_blocks = false;
						if ( isset( $GLOBALS['GUTENTOR_GLOBAL']['reusable_block'] ) ) {
							$reusable_blocks = $GLOBALS['GUTENTOR_GLOBAL']['reusable_block'];
							if ( in_array( get_the_ID(), $reusable_blocks ) ) {
								$already_used_blocks = true;
							} else {
								array_push( $reusable_blocks, get_the_ID() );
								$GLOBALS['GUTENTOR_GLOBAL']['reusable_block'] = $reusable_blocks;
							}
						} else {
							$GLOBALS['GUTENTOR_GLOBAL']['reusable_block'] = array(
								get_the_ID(),
							);
						}

						if ( ! $already_used_blocks ) {
							array_push( $this->used_blocks, get_the_ID() );

							$css_info = get_post_meta( get_the_ID(), 'gutentor_css_info', true );
							if ( isset( $css_info['blocks'] ) && is_array( $css_info['blocks'] ) ) {
								self::$unique_blocks = array_unique( array_merge( self::$unique_blocks, $css_info['blocks'] ) );
							}
						}

						the_content();
					endwhile;
					echo '</div>';
				endif;
				wp_reset_postdata();
			endif;
			echo $args['after_widget'];
		}

		/**
		 * Load scripts and styles
		 *
		 * @since    2.1.2
		 * @access   public
		 *
		 * @param null
		 * @return void
		 */
		function scripts() {
			if ( ! is_active_widget( false, false, $this->id_base, true ) ) {
				return;
			}
			$gwb_options = $this->get_settings();
			if ( is_array( $gwb_options ) && ! empty( $gwb_options ) ) {
				foreach ( $gwb_options as $gwb_option ) {
					if ( isset( $gwb_option['wp_block_id'] ) && $gwb_option['wp_block_id'] != 0 ) {
						$wp_block_id = absint( $gwb_option['wp_block_id'] );
						$g_args      = array(
							'p'         => $wp_block_id,
							'post_type' => 'wp_block',
						);
						$g_query     = new WP_Query( $g_args );
						/*The Loop*/
						if ( $g_query->have_posts() ) :
							while ( $g_query->have_posts() ) :
								$g_query->the_post();
								gutentor_hooks()->load_lib_assets();
							endwhile;
						endif;
						wp_reset_postdata();
					}
				}
			}
		}

		/**
		 * Add missing assets if any
		 *
		 * @since    3.0.0
		 * @access   public
		 *
		 * @param array $block_css_files url of blocks css files.
		 * @return void
		 */
		private function enqueue_missing_assets( $block_css_files ) {
			if ( is_array( $block_css_files ) && ! empty( $block_css_files ) ) {
				foreach ( $block_css_files as $file_url ) {
					if ( $file_url ) {
						$exploded = explode( '/', $file_url );
						wp_enqueue_style(
							'gutentor-missing-' . str_replace( '.css', '', end( $exploded ) ), // Handle.
							$file_url,
							false, // Dependency to include the CSS after it.
							GUTENTOR_VERSION // Version: File modification time.
						);
					}
				}
			}
		}

		/**
		 * Add missing assets if any
		 * Reference from Gutentor_Dynamic_CSS -> get_blocks_css
		 *
		 * @since    3.0.0
		 * @access   public
		 *
		 * @param null
		 * @return void
		 */
		public function add_missing_assets() {

			$diff_blocks     = array_diff( self::$unique_blocks, gutentor_dynamic_css()->get_unique_blocks() );
			$block_css_files = array();
			if ( is_array( $diff_blocks ) && ! empty( $diff_blocks ) ) {

				/*global*/
				if ( count( $diff_blocks ) === count( self::$unique_blocks ) ) {
					$block_css_files[] = gutentor_dynamic_css()->get_static_css( 'global', true );
				}

				/*Slick*/
				$slick = array(
					'gutentor/image-slider',
					'gutentor/m5',
					'gutentor/m0',
					'gutentor/p3',
					'gutentor/t3',
				);
				if ( ! empty( array_intersect( $diff_blocks, $slick ) ) ) {
					$block_css_files[] = gutentor_dynamic_css()->get_static_css( 'slick', true );
				}

				/*featured*/
				$featured = array(
					'gutentor/t1',
					'gutentor/t2',
					'gutentor/p2',
				);
				if ( ! empty( array_intersect( $diff_blocks, $featured ) ) ) {
					$block_css_files[] = gutentor_dynamic_css()->get_static_css( 'featured', true );
				}

				/*Post/Tax Type*/
				$types = array(
					'gutentor/p1',
					'gutentor/p2',
					'gutentor/p3',
					'gutentor/p4',
					'gutentor/p5',
					'gutentor/p6',
					'gutentor/t1',
					'gutentor/t2',
					'gutentor/t3',
				);
				if ( ! empty( array_intersect( $diff_blocks, $types ) ) ) {
					$block_css_files[] .= gutentor_dynamic_css()->get_static_css( 'global-type', true );
				}

				/*Widget*/
				$widgets = array(
					'gutentor/about-block',
					'gutentor/accordion',
					'gutentor/author-profile',
					'gutentor/blog-post',
					'gutentor/call-to-action',
					'gutentor/content-box',
					'gutentor/count-down',
					'gutentor/counter-box',
					'gutentor/divider',
					'gutentor/featured-block',
					'gutentor/gallery',
					'gutentor/google-map',
					'gutentor/icon-box',
					'gutentor/image-box',
					'gutentor/image-slider',
					'gutentor/list',
					'gutentor/notification',
					'gutentor/opening-hours',
					'gutentor/pricing',
					'gutentor/progress-bar',
					'gutentor/restaurant-menu',
					'gutentor/show-more',
					'gutentor/social',
					'gutentor/tabs',
					'gutentor/team',
					'gutentor/testimonial',
					'gutentor/timeline',
					'gutentor/video-popup',
				);
				if ( ! empty( array_intersect( $diff_blocks, $widgets ) ) ) {
					$block_css_files[] = gutentor_dynamic_css()->get_static_css( 'global-widget', true );
				}

				foreach ( $diff_blocks as $block ) {
					$block_css_files[] = gutentor_dynamic_css()->get_static_css( $block, true );
				}
				$this->enqueue_missing_assets( $block_css_files );
			}
		}

		public function add_used_blocks_css() {
			if ( ! empty( $this->used_blocks ) ) {
				foreach ( $this->used_blocks as $used_block ) {
					$style = get_post_meta( $used_block, 'gutentor_dynamic_css', true );

					if ( get_post_meta( $used_block, 'gutentor_gfont_url', true ) ) {
						$fonts_url = get_post_meta( $used_block, 'gutentor_gfont_url', true );
						echo '<link id="gutentor-google-fonts-' . esc_attr( $used_block ) . '" href="' . esc_url( $fonts_url ) . '" rel="stylesheet" />';
					}
					echo "<!-- Dynamic CSS -->\n<style type=\"text/css\" id='gutentor-used-block-$used_block'>\n" . wp_strip_all_tags( $style ) . "\n</style>";

				}
			}

		}
	} // Class Gutentor_WP_Block_Widget ends here
}
