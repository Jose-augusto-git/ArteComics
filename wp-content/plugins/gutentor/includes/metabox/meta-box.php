<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Gutentor Custom Meta Boxes
 *
 * @package Gutentor
 */
if ( ! class_exists( 'Gutentor_Custom_Meta_Box' ) ) :

	class Gutentor_Custom_Meta_Box {


		/**
		 * Main Instance
		 *
		 * Insures that only one instance of Gutentor_Custom_Meta_Box exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since    3.0.0
		 * @access   public
		 *
		 * @return object
		 */
		public static function instance() {

			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new Gutentor_Custom_Meta_Box();
			}

			// Always return the instance
			return $instance;
		}

		/**
		 *  Run functionality with hooks
		 *
		 * @since    3.0.0
		 * @access   public
		 *
		 * @return void
		 */
		public function run() {

			if ( is_admin() ) {
				add_action( 'load-post.php', array( $this, 'init_metabox' ) );
				add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_meta_box_script' ) );

		}

		/**
		 * Enqueue scripts
		 * return void
		 */
		public function enqueue_meta_box_script() {
			if ( gutentor_is_edit_page() && 'download' === get_post_type() ) {
				wp_enqueue_style( 'gutentor-meta-box', GUTENTOR_URL . '/includes/metabox/meta-box.css', array(), '1.0.0' );
			}
		}

		/**
		 * Meta box initialization.
		 */
		public function init_metabox() {
            $options = gutentor_get_options();
            $value   = false;
            if ( isset( $options['edd-demo-url'] ) && ! empty( $options['edd-demo-url'] ) ) {
                $value = $options['edd-demo-url'];
            }
            if( $value){
                add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
                add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );
            }

		}

		/**
		 * Adds the meta box.
		 */
		public function add_metabox() {
			add_meta_box(
				'gutentor_edd_demo_url',
				__( 'Gutentor', 'gutentor' ),
				array( $this, 'render_metabox' ),
				'download',
				'side',
				'low'
			);
		}

		/**
		 * Renders the meta box.
		 */
		public function render_metabox( $post ) {

			$gutentor_edd_demo_url_value = get_post_meta( $post->ID, 'gutentor_edd_demo_url', true );
			// true ensures you get just one value instead of an array
			wp_nonce_field( basename( __FILE__ ), 'gutentor_meta_nonce' );
			?>  
			<div class="gutentor-custom-meta components-base-control">
				<div class="components-base-control__field">
					<label class="components-base-control__label" for="gutentor_edd_demo_url"><?php echo esc_html__( 'Demo URL', 'gutentor' ); ?></label>
					<input name="gutentor_edd_demo_url" value="<?php echo esc_attr( $gutentor_edd_demo_url_value ); ?>" id="gutentor_edd_demo_url" class="components-select-control__input" />
				</div>
			</div>
			<?php
		}

		/**
		 * Handles saving the meta box.
		 *
		 * @param int     $post_id Post ID.
		 * @param WP_Post $post    Post object.
		 * @return null
		 */
		public function save_metabox( $post_id, $post ) {

			/*
			  * A Guide to Writing Secure Themes – Part 4: Securing Post Meta
			  *https://make.wordpress.org/themes/2015/06/09/a-guide-to-writing-secure-themes-part-4-securing-post-meta/
			  * */
			if (
				! isset( $_POST['gutentor_meta_nonce'] ) ||
				! wp_verify_nonce( $_POST['gutentor_meta_nonce'], basename( __FILE__ ) ) || /*Protecting against unwanted requests*/
				( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || /*Dealing with autosaves*/
				! current_user_can( 'edit_post', $post_id )/*Verifying access rights*/
			) {
				return;
			}

			if ( 'download' != $_POST['post_type'] ) {
				return $post_id;
			}
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}

			// Execute this saving function
			// site layout
			if ( isset( $_POST['gutentor_edd_demo_url'] ) ) {
				$old = get_post_meta( $post_id, 'gutentor_edd_demo_url', true );
				$new = esc_attr( $_POST['gutentor_edd_demo_url'] );
				if ( $new && $new != $old ) {
					update_post_meta( $post_id, 'gutentor_edd_demo_url', $new );
				} elseif ( '' == $new && $old ) {
					delete_post_meta( $post_id, 'gutentor_edd_demo_url', $old );
				}
			}
		}
	}

endif;

/**
 * Create Instance for Gutentor_Custom_Meta_Box
 *
 * @since    3.0.0
 * @access   public
 *
 * @param
 *
 * @return object
 */
if ( ! function_exists( 'gutentor_custom_meta_box' ) ) {

	function gutentor_custom_meta_box() {

		return Gutentor_Custom_Meta_Box::instance();
	}

	gutentor_custom_meta_box()->run();
}
