<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * wcbm_banner_management_product_slider_Table class.
 *
 * @extends WP_List_Table
 */
if ( ! class_exists( 'wcbm_banner_management_product_slider_Table' ) ) {

	class wcbm_banner_management_product_slider_Table extends WP_List_Table {

		const post_type = 'wcbm_product_slider';
		private static $wc_wbm_found_items = 0;
		private static $admin_object = null;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			parent::__construct( array(
				'singular' => 'post',
				'plural'   => 'posts',
				'ajax'     => false
			) );
			
			self::$admin_object = new woocommerce_category_banner_management_Admin( "", "" );
		}

		/**
		 * get_columns function.
		 *
		 * @return  array
		 * @since 1.0.0
		 *
		 */
		public function get_columns() {
			return array(
				'cb'                => '<input type="checkbox" />',
				'title'             => esc_html__( 'Title', 'banner-management-for-woocommerce' ),
				'shortcode'         => esc_html__( 'Shortcode', 'banner-management-for-woocommerce' ),
				'status'            => esc_html__( 'Status', 'banner-management-for-woocommerce' ),
				'date'       		=> esc_html__( 'Date', 'banner-management-for-woocommerce' ),
			);
		}

		/**
		 * get_sortable_columns function.
		 *
		 * @return array
		 * @since 1.0.0
		 *
		 */
		protected function get_sortable_columns() {
			$columns = array(
				'title'  => array( 'title', true ),
				'date'   => array( 'date', false ),
			);

			return $columns;
		}

		/**
		 * Get Methods to display
		 *
		 * @since 1.0.0
		 */
		public function prepare_items() {
			$this->prepare_column_headers();
			$per_page = $this->get_items_per_page( 'whsm_per_page' );

			$get_search  = filter_input( INPUT_POST, 's', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_orderby = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_order   = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

			$args = array(
				'posts_per_page' => $per_page,
				'orderby'        => 'ID',
				'order'          => 'DESC',
				'offset'         => ( $this->get_pagenum() - 1 ) * $per_page,
			);

			if ( isset( $get_search ) && ! empty( $get_search ) ) {
				$args['s'] = trim( wp_unslash( $get_search ) );
			}

			if ( isset( $get_orderby ) && ! empty( $get_orderby ) ) {
				if ( 'title' === $get_orderby ) {
					$args['orderby'] = 'title';
				} elseif ( 'amount' === $get_orderby ) {
					$args['meta_key'] = 'sm_product_cost';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$args['orderby']  = 'meta_value_num';
				} elseif ( 'date' === $get_orderby ) {
					$args['orderby'] = 'date';
				}
			}

			if ( isset( $get_order ) && ! empty( $get_order ) ) {
				if ( 'asc' === strtolower( $get_order ) ) {
					$args['order'] = 'ASC';
				} elseif ( 'desc' === strtolower( $get_order ) ) {
					$args['order'] = 'DESC';
				}
			}

			$this->items = $this->whsm_find( $args, $get_orderby);

			$total_items = $this->whsm_count();

			$total_pages = ceil( $total_items / $per_page );

			$this->set_pagination_args( array(
				'total_items' => $total_items,
				'total_pages' => $total_pages,
				'per_page'    => $per_page,
			) );
		}

		/**
		 */
		public function no_items() {
			if ( isset( $this->error ) ) {
				echo esc_html($this->error->get_error_message());
			} else {
				esc_html_e( 'No Sliders Found.', 'banner-management-for-woocommerce' );
			}
		}

		/**
		 * Checkbox column
		 *
		 * @param string
		 *
		 * @return mixed
		 * @since 1.0.0
		 *
		 */
		public function column_cb( $item ) {
			if ( ! $item->ID ) {
				return;
			}

			return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', 'method_id_cb', esc_attr( $item->ID ) );
		}

		/**
		 * Output the slider rule name column.
		 *
		 * @param object $item
		 *
		 * @return string
		 * @since 1.0.0
		 *
		 */
		public function column_title( $item ) {
			$edit_method_url = add_query_arg( array(
				'page'   => 'wcbm-sliders-settings',
				'tab' 	 => 'wcbm-product-sliders',
				'action' => 'edit',
				'post'   => $item->ID
			), admin_url( 'admin.php' ) );
			$editurl         = $edit_method_url;

			$method_name = '<strong>
                            <a href="' . wp_nonce_url( $editurl, 'edit_' . $item->ID, 'cust_nonce' ) . '" class="row-title">' . esc_html( $item->post_title ) . '</a>
                        </strong>';

			echo wp_kses( $method_name, self::$admin_object->wcbm_allowed_html_tags() );
		}

		/**
		 * Output the slider shortcode column.
		 *
		 * @param object $item
		 *
		 * @return string
		 * @since 1.0.0
		 *
		 */
		public function column_shortcode( $item ) {
			$method_name = '<div class="wbm-after-copy-text"><span class="dashicons dashicons-yes-alt"></span> Shortcode  Copied to Clipboard! </div><input class="wbm-copy-shortcode" type="text" value="[wcbm_product id=&quot;'.$item->ID.'&quot;]" readonly="readonly">';

			echo wp_kses( $method_name, self::$admin_object->wcbm_allowed_html_tags() );
		}

		/**
		 * Generates and displays row action links.
		 *
		 * @param object $item Link being acted upon.
		 * @param string $column_name Current column name.
		 * @param string $primary Primary column name.
		 *
		 * @return string Row action output for links.
		 * @since 1.0.0
		 *
		 */
		protected function handle_row_actions( $item, $column_name, $primary ) {
			if ( $primary !== $column_name ) {
				return '';
			}

			$edit_method_url = add_query_arg( array(
				'page'   => 'wcbm-sliders-settings',
				'tab' 	 => 'wcbm-product-sliders',
				'action' => 'edit',
				'post'   => $item->ID
			), admin_url( 'admin.php' ) );
			$editurl         = $edit_method_url;

			$delete_method_url = add_query_arg( array(
				'page'   => 'wcbm-sliders-settings',
				'tab' 	 => 'wcbm-product-sliders',
				'action' => 'delete',
				'post'   => $item->ID
			), admin_url( 'admin.php' ) );
			$delurl            = $delete_method_url;

			$actions              = array();
			$actions['edit']      = '<a href="' . wp_nonce_url( $editurl, 'edit_' . $item->ID, 'cust_nonce' ) . '">' . __( 'Edit', 'banner-management-for-woocommerce' ) . '</a>';
			$actions['delete']    = '<a href="' . wp_nonce_url( $delurl, 'del_' . $item->ID, 'cust_nonce' ) . '">' . __( 'Delete', 'banner-management-for-woocommerce' ) . '</a>';

			return $this->row_actions( $actions );
		}

		/**
		 * Output the method enabled column.
		 *
		 * @param object $item
		 *
		 * @return string
		 */
		public function column_status( $item ) {
			if ( 0 === $item->ID ) {
				return esc_html__( 'Everywhere', 'banner-management-for-woocommerce' );
			}

			if ( 'publish' === $item->post_status ) {
				$status = 'Enable';
			} else {
				$status = 'Disable';
			}

			return $status;
		}

		/**
		 * Output the method amount column.
		 *
		 * @param object $item
		 *
		 * @return mixed $item->post_date;
		 * @since 1.0.0
		 *
		 */
		public function column_date( $item ) {
			if ( 0 === $item->ID ) {
				return esc_html__( 'Everywhere', 'banner-management-for-woocommerce' );
			}

			return $item->post_date;
		}

		/**
		 * Display bulk action in filter
		 *
		 * @return array $actions
		 * @since 1.0.0
		 *
		 */
		public function get_bulk_actions() {
			$actions = array(
				'disable' => esc_html__( 'Disable', 'banner-management-for-woocommerce' ),
				'enable'  => esc_html__( 'Enable', 'banner-management-for-woocommerce' ),
				'delete'  => esc_html__( 'Delete', 'banner-management-for-woocommerce' )
			);

			return $actions;
		}

		/**
		 * Process bulk actions
		 *
		 * @since 1.0.0
		 */
		public function process_bulk_action() {
			$delete_nonce     = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_method_id_cb = filter_input( INPUT_POST, 'method_id_cb', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY );
			$method_id_cb     = ! empty( $get_method_id_cb ) ? array_map( 'sanitize_text_field', wp_unslash( $get_method_id_cb ) ) : array();
			$get_page 	  = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			
			$action = $this->current_action();

			if ( ! isset( $method_id_cb ) ) {
				return;
			}

			$deletenonce = wp_verify_nonce( $delete_nonce, 'bulk-wbmSliders' );

			if ( ! isset( $deletenonce ) && 1 !== $deletenonce ) {
				return;
			}

			$items = array_filter( array_map( 'absint', $method_id_cb ) );

			if ( ! $items ) {
				return;
			}

			if ( 'delete' === $action ) {
				foreach ( $items as $id ) {
					wp_delete_post( $id );
				}
				self::$admin_object->wcbm_updated_message( 'deleted', $get_page, '' );

				// Clear cache
				delete_transient( 'dscpw_name_address_fields' );
			} elseif ( 'enable' === $action ) {

				foreach ( $items as $id ) {
					$enable_post = array(
						'post_type'   => self::post_type,
						'ID'          => $id,
						'post_status' => 'publish'
					);
					wp_update_post( $enable_post );
				}
				self::$admin_object->wcbm_updated_message( 'enabled', $get_page, '' );
			} elseif ( 'disable' === $action ) {
				foreach ( $items as $id ) {
					$disable_post = array(
						'post_type'   => self::post_type,
						'ID'          => $id,
						'post_status' => 'draft'
					);

					wp_update_post( $disable_post );
				}
				self::$admin_object->wcbm_updated_message( 'disabled', $get_page, '' );
			}
		}

		/**
		 * Find post data
		 *
		 * @param mixed $args
		 * @param string $get_orderby
		 *
		 * @return array $posts
		 * @since 1.0.0
		 *
		 */
		public static function whsm_find( $args = '' ) {
			$defaults = array(
				'post_status'    => 'any',
				'posts_per_page' => - 1,
				'offset'         => 0,
				'orderby'        => 'ID',
				'order'          => 'ASC',
			);

			$args = wp_parse_args( $args, $defaults );

			$args['post_type'] = self::post_type;

			$wc_wbm_query = new WP_Query( $args );
			$posts          = $wc_wbm_query->query( $args );

			self::$wc_wbm_found_items = $wc_wbm_query->found_posts;

			return $posts;
		}

		/**
		 * Count post data
		 *
		 * @return string
		 * @since 1.0.0
		 *
		 */
		public static function whsm_count() {
			return self::$wc_wbm_found_items;
		}

		/**
	     * Display the search box.
	     *
	     * @since 3.1.0
	     * @access public
	     *
	     * @param string $text    The 'submit' button label.
	     * @param string $input_id The input id.
	     */
	    public function search_box( $text, $input_id ) {
			$input_id = $input_id . '-search-input';
			?>
				<p class="search-box">
					<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php esc_html_e( $text, 'banner-management-for-woocommerce' ); ?>:</label>
					<input type="search" id="<?php echo esc_attr( $input_id ); ?>" placeholder="<?php esc_attr_e( 'Slider title', 'banner-management-for-woocommerce' ) ?>" name="s" value="<?php _admin_search_query(); ?>" />
						<?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>
				</p>
			<?php
			}

		/**
		 * Set column_headers property for table list
		 *
		 * @since 1.0.0
		 */
		protected function prepare_column_headers() {
			$this->_column_headers = array(
				$this->get_columns(),
				array(),
				$this->get_sortable_columns(),
			);
		}
	}
}