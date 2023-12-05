<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Gutentor_Self_Api_Handler' ) ) {
	/**
	 * Advanced Import
	 *
	 * @package Gutentor
	 * @since 1.0.1
	 */
	class Gutentor_Self_Api_Handler extends WP_Rest_Controller {


		/**
		 * Rest route namespace.
		 *
		 * @var Gutentor_Self_Api_Handler
		 */
		public $namespace = 'gutentor-self-api/';

		/**
		 * Rest route version.
		 *
		 * @var Gutentor_Self_Api_Handler
		 */
		public $version = 'v1';

		/**
		 * Initialize the class
		 */
		public function run() {
			 add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		/**
		 * Register REST API route
		 */
		public function register_routes() {
			 $namespace = $this->namespace . $this->version;

			register_rest_route(
				$namespace,
				'/max_num_pages',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'max_num_pages' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
					),
				)
			);

			register_rest_route(
				$namespace,
				'/gadvancedb',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'gadvancedb' ),
						'args'                => array(
							'paged'   => array(
								'type'              => 'number',
								'required'          => true,
								'description'       => __( 'Page Number (Paged) ', 'gutentor' ),
								'sanitize_callback' => 'absint',
							),
							'blockId' => array(
								'type'              => 'string',
								'required'          => true,
								'description'       => __( 'Block ID', 'gutentor' ),
								'sanitize_callback' => 'sanitize_text_field',
							),
							'postId'  => array(
								'type'              => 'number',
								'required'          => true,
								'description'       => __( 'Block ID', 'gutentor' ),
								'sanitize_callback' => 'sanitize_text_field',
							),
						),
						'permission_callback' => '__return_true',
					),
				)
			);

			register_rest_route(
				$namespace,
				'/get_authors',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_authors' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
					),
				)
			);

			register_rest_route(
				$namespace,
				'/get_all_author',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_all_author' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
					),
				)
			);

			register_rest_route(
				$namespace,
				'/tax',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'tax' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
					),
				)
			);

			register_rest_route(
				$namespace,
				'/get_taxonomies',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_taxonomies' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
					),
				)
			);

			register_rest_route(
				$namespace,
				'/get_post_type_posts',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_post_type_posts' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
					),
				)
			);

			register_rest_route(
				$namespace,
				'/tex_terms',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'tex_terms' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
						'args'                => array(
							'tax' => array(
								'type'              => 'string',
								'required'          => true,
								'description'       => __( 'Taxonomy', 'gutentor' ),
								'sanitize_callback' => 'sanitize_text_field',
							),
						),

					),
				)
			);

			register_rest_route(
				$namespace,
				'/get_posts',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_posts' ),
						'permission_callback' => array( $this, 'get_posts_permissions_check' ),
					),
				)
			);
			register_rest_route(
				$namespace,
				'/additional_elements',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'additional_elements' ),
						'permission_callback' => array( $this, 'get_posts_permissions_check' ),
					),
				)
			);

			register_rest_route(
				$namespace,
				'/additional_term_elements',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'additional_term_elements' ),
						'permission_callback' => array( $this, 'get_posts_permissions_check' ),
					),
				)
			);

			register_rest_route(
				$namespace,
				'/get_terms',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_terms' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
					),
				)
			);

			register_rest_route(
				$namespace,
				'/set_gutentor_settings',
				array(
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'set_gutentor_settings' ),
						'permission_callback' => function () {
							return ( current_user_can( 'edit_posts' ) && current_user_can( 'manage_options' ) );
						},
						'args'                => array(),
					),
				)
			);

			register_rest_route(
				$namespace,
				'/get_gutentor_settings',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_gutentor_settings' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},

					),
				)
			);

			register_rest_route(
				$namespace,
				'/get_post_types',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_post_types' ),
						'permission_callback' => function () {
							return ( current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) );
						},
					),
				)
			);

			register_rest_route(
				$namespace,
				'/get_all_metas',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_all_metas' ),
						'permission_callback' => function () {
							return ( current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) );
						},
					),
				)
			);

			register_rest_route(
				$namespace,
				'/get_all_term_metas',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_all_term_metas' ),
						'permission_callback' => function () {
							return ( current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) );
						},
					),
				)
			);

			register_rest_route(
				$namespace,
				'/popup',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'popup' ),
						'permission_callback' => '__return_true',
					),
				)
			);
		}

		/**
		 * Function to fetch templates.
		 *
		 * @return array|bool|\WP_Error
		 */
		public function max_num_pages( \WP_REST_Request $request ) {
			$query_args = array(
				'posts_per_page'      => $request->get_param( 'posts_per_page' ) ? $request->get_param( 'posts_per_page' ) : 6,
				'post_type'           => $request->get_param( 'post_type' ) ? $request->get_param( 'post_type' ) : 'post',
				'orderby'             => $request->get_param( 'orderby' ) ? $request->get_param( 'orderby' ) : 'date',
				'order'               => $request->get_param( 'order' ) ? $request->get_param( 'order' ) : 'desc',
				'paged'               => $request->get_param( 'paged' ) ? $request->get_param( 'paged' ) : 1,
				'ignore_sticky_posts' => true,
				'post_status'         => 'publish',
			);
			if ( $request->get_param( 'taxonomy' ) &&
				$request->get_param( 'term' ) ) {
				$query_args['tax_query'] = array(
					array(
						'taxonomy' => $request->get_param( 'taxonomy' ),
						'field'    => 'id',
						'terms'    => explode( ',', $request->get_param( 'term' ) ),
					),
				);
			}

			if ( $request->get_param( 'offset' ) ) {
				$query_args['offset'] = $request->get_param( 'offset' ) ? $request->get_param( 'offset' ) : 0;
			}
			if ( $request->get_param( 'post__in' ) ) {
				$query_args['post__in'] = explode( ',', $request->get_param( 'post__in' ) );
			}
			if ( $request->get_param( 'post__not_in' ) ) {
				$query_args['post__not_in'] = explode( ',', $request->get_param( 'post__not_in' ) );
			}
			// the query
			$the_query = new WP_Query( $query_args );
			wp_reset_postdata();
			return rest_ensure_response( $the_query->max_num_pages );
		}

		/**
		 * Function to fetch templates.
		 *
		 * @return array|bool|\WP_Error
		 */
		public function gadvancedb( \WP_REST_Request $request ) {
			$paged          = $request->get_param( 'paged' );
			$blockId        = $request->get_param( 'blockId' );
			$postId         = $request->get_param( 'postId' );
			$taxonomy       = $request->get_param( 'gTax' );
			$term           = $request->get_param( 'gTerm' );
			$innerBlockType = $request->get_param( 'innerBlockType' );

			if ( $paged ) {
				$post = get_post( $postId );
				if ( $post ) {
					$content = $post->post_content;
				} else {
					/*For Widgets*/
					$content       = '';
					$widget_blocks = get_option( 'widget_block' );
					if ( is_array( $widget_blocks ) && ! empty( $widget_blocks ) ) {
						foreach ( $widget_blocks as $w_block ) {
							$blocks = parse_blocks( $w_block['content'] );
							if ( gutentor_get_block_by_id( $blocks, $blockId ) ) {
								$content = $w_block['content'];
								break;
							}
						}
					}
				}

				if ( $content && has_blocks( $content ) ) {
					$blocks = parse_blocks( $content );
					$pBlock = gutentor_get_block_by_id( $blocks, $blockId );

					/*Get Default Attributes*/
					if ( 'gutentor/p6' === $innerBlockType ) {
						$p_attr = Gutentor_P6::get_instance()->get_attrs();
					} else {
						$p_attr = Gutentor_P1::get_instance()->get_attrs();
					}
					$common_attr      = gutentor_block_base()->get_common_attrs();
					$default_pre_attr = array_merge( $p_attr, $common_attr );
					$default_attr     = array();
					foreach ( $default_pre_attr as $key => $value ) {
						if ( isset( $value['default'] ) ) {
							$default_attr[ $key ] = $value['default'];
						}
					}
					$final_attrs          = array_merge( $default_attr, $pBlock['attrs'] );
					$final_attrs['paged'] = $paged;
					if ( $term && $term !== 'default' ) {
						if ( $term !== 'gAll' ) {
							$final_attrs['pTaxType'] = $taxonomy;
							$final_attrs['pTaxTerm'] = $term;
						} else {
							if ( $request->get_param( 'allOpt' ) && 'inherit' != $request->get_param( 'allOpt' ) ) {
								$final_attrs['pTaxType'] = '';
								$final_attrs['pTaxTerm'] = '';
							}
						}
					}

					/*Search Query*/
					if ( $request->get_param( 's' ) ) {
						$final_attrs['s'] = $request->get_param( 's' );
					}

					$response = array();

					if ( 'gutentor/p6' === $innerBlockType ) {
						$response['pBlog'] = Gutentor_P6::get_instance()->render_callback( $final_attrs, 'ajax' );
					} else {
						$response['pBlog'] = Gutentor_P1::get_instance()->render_callback( $final_attrs, 'ajax' );
					}

					/*
					max num of pages
					taken from class p1
					*/
					// the query
					$query_args = array(
						'posts_per_page'      => isset( $final_attrs['postsToShow'] ) ? $final_attrs['postsToShow'] : 6,
						'post_type'           => isset( $final_attrs['pPostType'] ) ? $final_attrs['pPostType'] : 'post',
						'orderby'             => isset( $final_attrs['orderBy'] ) ? $final_attrs['orderBy'] : 'date',
						'order'               => isset( $final_attrs['order'] ) ? $final_attrs['order'] : 'desc',
						'paged'               => isset( $final_attrs['paged'] ) ? $final_attrs['paged'] : 1,
						'ignore_sticky_posts' => true,
						'post_status'         => 'publish',
					);

					if ( isset( $final_attrs['offset'] ) ) {
						$query_args['offset'] = $final_attrs['offset'];
					}
					if ( isset( $final_attrs['pIncludePosts'] ) && ! empty( $final_attrs['pIncludePosts'] ) ) {
						$query_args['post__in'] = $final_attrs['pIncludePosts'];
					}
					if ( isset( $final_attrs['pExcludePosts'] ) && ! empty( $final_attrs['pExcludePosts'] ) ) {
						$query_args['post__not_in'] = $final_attrs['pExcludePosts'];
					}

					if ( isset( $final_attrs['pTaxType'] ) && ! empty( $final_attrs['pTaxType'] ) &&
						isset( $final_attrs['pTaxTerm'] ) && ! empty( $final_attrs['pTaxTerm'] ) ) {

						$query_args['taxonomy'] = $final_attrs['pTaxType'];

						if ( is_array( $final_attrs['pTaxTerm'] ) ) {
							$p_terms = array();
							foreach ( $final_attrs['pTaxTerm'] as $p_term ) {
								$p_terms [] = $p_term['value'];
							}
							$query_args['term'] = $p_terms;
						} elseif ( is_string( $final_attrs['pTaxTerm'] ) || is_numeric( $final_attrs['pTaxTerm'] ) ) {
							$query_args['term'] = $final_attrs['pTaxTerm'];
						}
					}
					/*Search query*/
					if ( isset( $final_attrs['s'] ) && ! empty( $final_attrs['s'] ) ) {
						$query_args['s'] = $final_attrs['s'];
					}

					$the_query = new WP_Query( gutentor_get_query( $query_args ) );

					wp_reset_postdata();
					$max_num_pages             = $the_query->max_num_pages;
					$response['max_num_pages'] = $max_num_pages;
					$response['pagination']    = gutentor_pagination( $paged, $max_num_pages );

					return rest_ensure_response( $response );
				}
			}
		}

		/**
		 * Function to fetch authors.
		 *
		 * T
		 */
		public function get_authors( \WP_REST_Request $request ) {
			$post_type = $request->get_param( 'post_type' );
			global $wpdb;

			$all_authors = $wpdb->get_results(
				"
                select
    A.*, COUNT(*) as post_count
from
    $wpdb->users A
inner join $wpdb->posts B
    on A.ID = B.post_author
WHERE ( ( B.post_type = '$post_type' AND ( B.post_status = 'publish' OR B.post_status = 'private' ) ) )
GROUP BY A.ID
ORDER BY post_count DESC"
			);

			$final_data = array();
			if ( $all_authors ) {
				foreach ( $all_authors as $data ) {
					$final_data[] = array(
						'label' => ucwords( $data->display_name ),
						'value' => $data->ID,
					);
				}
			}

			return rest_ensure_response( $final_data );
		}


		/**
		 * Function to fetch authors.
		 *
		 * T
		 */
		public function get_all_author( \WP_REST_Request $request ) {
			global $wpdb;

			$all_authors = $wpdb->get_results(
				"
                select
    A.*, COUNT(*) as post_count
from
    $wpdb->users A
inner join $wpdb->posts B
    on A.ID = B.post_author
WHERE (  ( B.post_status = 'publish' OR B.post_status = 'private'  ) )
GROUP BY A.ID
ORDER BY post_count DESC"
			);

			$final_data = array();
			if ( $all_authors ) {
				foreach ( $all_authors as $data ) {
					$final_data[] = array(
						'label' => ucwords( $data->display_name ),
						'value' => $data->ID,
					);
				}
			}

			return rest_ensure_response( $final_data );
		}

		/**
		 * Function to fetch tax terms.
		 */
		public function tax( \WP_REST_Request $request ) {
			$post_type  = $request->get_param( 'post_type' );
			$taxonomies = get_object_taxonomies( $post_type, 'objects' );
			$items      = array();
			if ( $taxonomies ) {
				foreach ( $taxonomies as $key => $tax ) {
					$items[ $key ]['label'] = $tax->label;
					$items[ $key ]['value'] = $tax->name;
				}
			}
			return rest_ensure_response( $items );
		}

		/**
		 * Function to fetch tax terms.
		 */
		public function get_taxonomies( \WP_REST_Request $request ) {
			$args       = array(
				'public' => true,
			);
			$output     = 'objects';
			$taxonomies = get_taxonomies( $args, $output );
			$items      = array();
			if ( $taxonomies ) {
				foreach ( $taxonomies as $key => $tax ) {
					$items[ $key ]['label'] = $tax->label;
					$items[ $key ]['value'] = $tax->name;
				}
			}
			return rest_ensure_response( $items );
		}

		/**
		 * Function to fetch tax terms.
		 */
		public function tex_terms( \WP_REST_Request $request ) {
			$tax         = $request->get_param( 'tax' );
			$search_text = $request->get_param( 'searchTxt' );
			$tax_array   = array(
				'taxonomy' => $tax,
			);
			if ( $search_text ) {
				$tax_array['name__like'] = $search_text;
			}
			$tex_terms = get_terms( $tax_array );
			if ( ! empty( $tex_terms ) ) :
				return rest_ensure_response( $tex_terms );
			endif;
			return rest_ensure_response( false );
		}

		/**
		 * Function to fetch tax terms.
		 */
		public function get_terms( \WP_REST_Request $request ) {
			$taxonomy     = $request->get_param( 'taxonomy' );
			$hide_empty   = $request->get_param( 'hide_empty' );
			$count        = $request->get_param( 'count' );
			$hierarchical = $request->get_param( 'hierarchical' );
			$childless    = $request->get_param( 'childless' );
			$term_args    = array(
				'taxonomy'     => $taxonomy,
				'hide_empty'   => ( $hide_empty == 'true' ),
				'count'        => ( $count == 'true' ),
				'hierarchical' => ( $hierarchical == 'true' ),
				'childless'    => ( $childless == 'true' ),
			);
			if ( $request->get_param( 'taxonomy' ) ) {
				$term_args['taxonomy'] = explode( ',', $request->get_param( 'taxonomy' ) );
			}
			if ( $request->get_param( 'term_ids' ) ) {
				$term_args['term_ids'] = explode( ',', $request->get_param( 'term_ids' ) );
			}
			if ( $request->get_param( 'orderby' ) ) {
				$term_args['orderby'] = $request->get_param( 'orderby' );
			}
			if ( $request->get_param( 'order' ) ) {
				$term_args['order'] = $request->get_param( 'order' );
			}
			if ( $request->get_param( 'include' ) ) {
				$term_args['include'] = explode( ',', $request->get_param( 'include' ) );
			}
			if ( $request->get_param( 'exclude' ) ) {
				$term_args['exclude'] = explode( ',', $request->get_param( 'exclude' ) );
			}
			if ( $request->get_param( 'exclude_tree' ) ) {
				$term_args['exclude_tree'] = explode( ',', $request->get_param( 'exclude_tree' ) );
			}
			if ( $request->get_param( 'number' ) ) {
				$term_args['number'] = $request->get_param( 'number' );
			} else {
				$term_args['number'] = 6;
			}
			if ( $request->get_param( 'offset' ) ) {
				$term_args['offset'] = intval( $request->get_param( 'offset' ) );
			}
			if ( $request->get_param( 'name' ) ) {
				$term_args['name'] = explode( ',', $request->get_param( 'name' ) );
			}
			if ( $request->get_param( 'slug' ) ) {
				$term_args['slug'] = explode( ',', $request->get_param( 'slug' ) );
			}
			if ( $request->get_param( 'term_taxonomy_id' ) ) {
				$term_args['term_taxonomy_id'] = explode( ',', $request->get_param( 'term_taxonomy_id' ) );
			}
			if ( $request->get_param( 'search' ) ) {
				$term_args['search'] = $request->get_param( 'search' );
			}
			if ( $request->get_param( 'name__like' ) ) {
				$term_args['name__like'] = $request->get_param( 'name__like' );
			}
			if ( $request->get_param( 'description__like' ) ) {
				$term_args['description__like'] = $request->get_param( 'description__like' );
			}
			if ( $request->get_param( 'child_of' ) ) {
				$term_args['child_of'] = $request->get_param( 'child_of' );
			}
			if ( $request->get_param( 'parent' ) ) {
				$term_args['parent'] = $request->get_param( 'parent' );
			}

			/*meta query*/
			if ( $request->get_param( 'meta_query' ) ) {
				$meta_query = $request->get_param( 'meta_query' ) ? $request->get_param( 'meta_query' ) : false;
				$meta_query = json_decode( $meta_query, true );
				if ( is_array( $meta_query['meta_query'] ) ) {
					$term_args['meta_query'] = $meta_query['meta_query'];
				}
			}
			if ( $request->get_param( 'meta_query_relation' ) ) {
				$meta_query_relation = $request->get_param( 'meta_query_relation' ) ? $request->get_param( 'meta_query_relation' ) : false;
				if ( $meta_query_relation ) {
					$term_args['meta_query']['relation'] = $meta_query_relation;
				}
			}
			// return $term_args;
			$term_obj = get_terms( $term_args );
			$terms    = array();
			foreach ( $term_obj as $term ) {
				$data    = $this->prepare_term_for_response( $term, $request );
				$terms[] = $this->prepare_response_for_collection( $data );
			}
			$term_obj = $terms;
			$term_obj = rest_ensure_response( $term_obj );
			if ( ! empty( $term_obj ) ) :
				return rest_ensure_response( $term_obj );
			endif;
			return rest_ensure_response( false );

		}


		/**
		 * Function to fetch tax terms.
		 */
		public function get_post_type_posts( \WP_REST_Request $request ) {
			$post_type   = $request->get_param( 'postType' );
			$search_text = $request->get_param( 'searchTxt' );
			$args        = array(
				'post_type' => $post_type,
			);
			if ( $search_text ) {
				$args['s'] = $search_text;
			}
			$post_data = get_posts( $args );
			$items     = array();
			if ( $post_data ) {
				foreach ( $post_data as $key => $data ) {
					$items[ $key ]['label'] = $data->post_title;
					$items[ $key ]['value'] = $data->ID;
				}
			}
			return rest_ensure_response( $items );
		}

		/**
		 * Checks if a given request has access to read posts.
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
		 * @since 2.1.3
		 */
		public function get_posts_permissions_check( $request ) {
			$post_type = get_post_type_object( $request->get_param( 'post_type' ) );

			if ( 'edit' === $request['context'] && ! current_user_can( $post_type->cap->edit_posts ) ) {
				return new WP_Error(
					'rest_forbidden_context',
					__( 'Sorry, you are not allowed to edit posts in this post type.' ),
					array( 'status' => rest_authorization_required_code() )
				);
			}

			return true;
		}

		/**
		 * Prepares links for the request.
		 *
		 * @param WP_Post $post Post object.
		 * @return array Links for the given post.
		 * @since 4.7.0
		 */
		public function prepare_links( $post ) {
			$base = sprintf( '%s/%s', $this->namespace, $this->rest_base );

			// Entity meta.
			$links = array(
				'self'       => array(
					'href' => rest_url( trailingslashit( $base ) . $post->ID ),
				),
				'collection' => array(
					'href' => rest_url( $base ),
				),
				'about'      => array(
					'href' => rest_url( 'wp/v2/types/' . $post->post_type ),
				),
			);

			if ( ( in_array( $post->post_type, array( 'post', 'page' ), true ) || post_type_supports( $post->post_type, 'author' ) )
				&& ! empty( $post->post_author ) ) {
				$links['author'] = array(
					'href'       => rest_url( 'wp/v2/users/' . $post->post_author ),
					'embeddable' => true,
				);
			}

			if ( in_array( $post->post_type, array( 'post', 'page' ), true ) || post_type_supports( $post->post_type, 'comments' ) ) {
				$replies_url = rest_url( 'wp/v2/comments' );
				$replies_url = add_query_arg( 'post', $post->ID, $replies_url );

				$links['replies'] = array(
					'href'       => $replies_url,
					'embeddable' => true,
				);
			}

			if ( in_array( $post->post_type, array( 'post', 'page' ), true ) || post_type_supports( $post->post_type, 'revisions' ) ) {
				$revisions       = wp_get_post_revisions( $post->ID, array( 'fields' => 'ids' ) );
				$revisions_count = count( $revisions );

				$links['version-history'] = array(
					'href'  => rest_url( trailingslashit( $base ) . $post->ID . '/revisions' ),
					'count' => $revisions_count,
				);

				if ( $revisions_count > 0 ) {
					$last_revision = array_shift( $revisions );

					$links['predecessor-version'] = array(
						'href' => rest_url( trailingslashit( $base ) . $post->ID . '/revisions/' . $last_revision ),
						'id'   => $last_revision,
					);
				}
			}

			$post_type_obj = get_post_type_object( $post->post_type );

			if ( $post_type_obj->hierarchical && ! empty( $post->post_parent ) ) {
				$links['up'] = array(
					'href'       => rest_url( trailingslashit( $base ) . (int) $post->post_parent ),
					'embeddable' => true,
				);
			}

			// If we have a featured media, add that.
			$featured_media = get_post_thumbnail_id( $post->ID );
			if ( $featured_media ) {
				$image_url = rest_url( 'wp/v2/media/' . $featured_media );

				$links['https://api.w.org/featuredmedia'] = array(
					'href'       => $image_url,
					'embeddable' => true,
				);
			}

			if ( ! in_array( $post->post_type, array( 'attachment', 'nav_menu_item', 'revision' ), true ) ) {
				$attachments_url = rest_url( 'wp/v2/media' );
				$attachments_url = add_query_arg( 'parent', $post->ID, $attachments_url );

				$links['https://api.w.org/attachment'] = array(
					'href' => $attachments_url,
				);
			}

			$taxonomies = get_object_taxonomies( $post->post_type );

			if ( ! empty( $taxonomies ) ) {
				$links['https://api.w.org/term'] = array();

				foreach ( $taxonomies as $tax ) {
					$taxonomy_obj = get_taxonomy( $tax );

					// Skip taxonomies that are not public.
					if ( empty( $taxonomy_obj->show_in_rest ) ) {
						continue;
					}

					$tax_base = ! empty( $taxonomy_obj->rest_base ) ? $taxonomy_obj->rest_base : $tax;

					$terms_url = add_query_arg(
						'post',
						$post->ID,
						rest_url( 'wp/v2/' . $tax_base )
					);

					$links['https://api.w.org/term'][] = array(
						'href'       => $terms_url,
						'taxonomy'   => $tax,
						'embeddable' => true,
					);
				}
			}

			return $links;
		}

		/**
		 * Checks the post_date_gmt or modified_gmt and prepare any post or
		 * modified date for single post output.
		 *
		 * @param string      $date_gmt GMT publication time.
		 * @param string|null $date Optional. Local publication time. Default null.
		 * @return string|null ISO8601/RFC3339 formatted datetime.
		 * @since 4.7.0
		 */
		protected function prepare_date_response( $date_gmt, $date = null ) {
			// Use the date if passed.
			if ( isset( $date ) ) {
				return gutentor_mysql_to_rfc3339( $date );
			}

			// Return null if $date_gmt is empty/zeros.
			if ( '0000-00-00 00:00:00' === $date_gmt ) {
				return null;
			}

			// Return the formatted datetime.
			return gutentor_mysql_to_rfc3339( $date_gmt );
		}

		/**
		 * Prepares a single post output for response.
		 * Copied from WP_REST_Posts_Controller->prepare_item_for_response
		 *
		 * @param WP_Post         $post Post object.
		 * @param WP_REST_Request $request Request object.
		 * @return WP_REST_Response Response object.
		 * @since 2.1.3
		 */
		public function prepare_item_for_response( $post, $request ) {
			$GLOBALS['post'] = $post;

			setup_postdata( $post );

			// Base fields for every post.
			$data = array();
			/*ID*/
			$data['id'] = $post->ID;

			/*date*/
			$data['date'] = $this->prepare_date_response( $post->post_date_gmt, $post->post_date );

			/*Date GMT*/
			if ( '0000-00-00 00:00:00' === $post->post_date_gmt ) {
				$post_date_gmt = get_gmt_from_date( $post->post_date );
			} else {
				$post_date_gmt = $post->post_date_gmt;
			}
			$data['date_gmt'] = $this->prepare_date_response( $post_date_gmt );

			/*Date guid*/
			$data['guid'] = array(
				/** This filter is documented in wp-includes/post-template.php */
				'rendered' => apply_filters( 'get_the_guid', $post->guid, $post->ID ),
				'raw'      => $post->guid,
			);

			/*Date modified*/
			$data['modified'] = $this->prepare_date_response( $post->post_modified_gmt, $post->post_modified );

			/*Modified GMT*/
			if ( '0000-00-00 00:00:00' === $post->post_modified_gmt ) {
				$post_modified_gmt = gmdate( 'Y-m-d H:i:s', strtotime( $post->post_modified ) - ( get_option( 'gmt_offset' ) * 3600 ) );
			} else {
				$post_modified_gmt = $post->post_modified_gmt;
			}
			$data['modified_gmt'] = $this->prepare_date_response( $post_modified_gmt );

			/*Passeord*/
			$data['password'] = $post->post_password;

			/*Slug*/
			$data['slug'] = $post->post_name;

			/*Status*/
			$data['status'] = $post->post_status;

			/*Post type*/
			$data['type'] = $post->post_type;

			/*Permalink*/
			$data['link'] = get_permalink( $post->ID );

			/*title*/
			$data['title']        = array();
			$data['title']['raw'] = $post->post_title;

			add_filter( 'protected_title_format', array( $this, 'protected_title_format' ) );
			$data['title']['rendered'] = get_the_title( $post->ID );
			remove_filter( 'protected_title_format', array( $this, 'protected_title_format' ) );

			/*Content*/
			$data['content']                  = array();
			$data['content']['raw']           = $post->post_content;
			$data['content']['rendered']      = post_password_required( $post ) ? '' : apply_filters( 'the_content', $post->post_content );
			$data['content']['protected']     = (bool) $post->post_password;
			$data['content']['block_version'] = block_version( $post->post_content );

			/*Excerpt*/
			$excerpt         = gutentor_get_excerpt_by_id( $post->ID, 1000 );
			$data['excerpt'] = array(
				'raw'       => $post->post_excerpt,
				'rendered'  => post_password_required( $post ) ? '' : $excerpt,
				'protected' => (bool) $post->post_password,
			);

			/*Author*/
			$data['author'] = (int) $post->post_author;

			/*Feature Media*/
			$data['featured_media'] = (int) get_post_thumbnail_id( $post->ID );

			/*Parent*/
			$data['parent'] = (int) $post->post_parent;

			/*Menu Order*/
			$data['menu_order'] = (int) $post->menu_order;

			/*Comment Status*/
			$data['comment_status'] = $post->comment_status;

			/*Ping Status*/
			$data['ping_status'] = $post->ping_status;

			/*Is sticky*/
			$data['sticky'] = is_sticky( $post->ID );

			/*Tempalate*/
			$template = get_page_template_slug( $post->ID );
			if ( $template ) {
				$data['template'] = $template;
			} else {
				$data['template'] = '';
			}

			/*Post format*/
			$data['format'] = get_post_format( $post->ID );

			// Fill in blank post format.
			if ( empty( $data['format'] ) ) {
				$data['format'] = 'standard';
			}

			/*Taxonomies*/
			$post_type  = $request->get_param( 'post_type' ) ? $request->get_param( 'post_type' ) : 'post';
			$taxonomies = wp_list_filter( get_object_taxonomies( $post_type, 'objects' ) );
			foreach ( $taxonomies as $taxonomy ) {
				$base  = ! empty( $taxonomy->rest_base ) ? $taxonomy->rest_base : $taxonomy->name;
				$terms = get_the_terms( $post, $taxonomy->name );
				if ( $terms && ! is_wp_error( $terms ) ) :
					foreach ( $terms as $term_key => $term_value ) {
						$term_value->link = esc_url_raw( get_term_link( $term_value->slug, $taxonomy->name ) );
					}
				endif;
				$data[ $base ] = $terms ? $terms : array();
			}
			/*Permalink template*/
			$post_type_obj = get_post_type_object( $post->post_type );
			if ( is_post_type_viewable( $post_type_obj ) && $post_type_obj->public ) {
				if ( ! function_exists( 'get_sample_permalink' ) ) {
					require_once ABSPATH . 'wp-admin/includes/post.php';
				}

				$sample_permalink = get_sample_permalink( $post->ID, $post->post_title, '' );

				$data['permalink_template'] = $sample_permalink[0];
				$data['generated_slug']     = $sample_permalink[1];
			}

			$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
			$data    = $this->add_additional_fields_to_object( $data, $request );
			$data    = $this->filter_response_by_context( $data, $context );
			$data    = apply_filters( "gutentor_rest_prepare_data_{$post_type}", $data, $post, $request );
			$data    = apply_filters( 'gutentor_rest_prepare_data', $data, $post, $request );
			// Wrap the data in a response object.
			$response = rest_ensure_response( $data );
			$links    = $this->prepare_links( $post );
			$response->add_links( $links );

			/**
			 * Filters the post data for a response.
			 *
			 * The dynamic portion of the hook name, `$post->post_type`, refers to the post type slug.
			 *
			 * @param WP_REST_Response $response The response object.
			 * @param WP_Post $post Post object.
			 * @param WP_REST_Request $request Request object.
			 * @since 4.7.0
			 */
			return apply_filters( "gutentor_rest_prepare_{$post_type}", $response, $post, $request );
		}

		/**
		 * set order and order by.
		 *
		 * @param string $orderby
		 * @param string $order .
		 * @param array  $args .
		 * @return array $args.
		 * @since 2.1.3
		 */
		public function set_product_order_order_by( $orderby, $order, $args ) {
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
		 * Prepares a single post output for response.
		 * Copied from WP_REST_Posts_Controller->prepare_item_for_response
		 *
		 * @param object          $term Post object.
		 * @param WP_REST_Request $request Request object.
		 * @return WP_REST_Response Response object.
		 * @since 2.1.3
		 */
		public function prepare_term_for_response( $term, $request ) {
			/*for thumbnail*/
			$thumbnail_id = false;

			// Base fields for every post.
			$data = array();
			/*ID*/
			$data['count']            = $term->count;
			$data['description']      = $term->description;
			$data['filter']           = $term->filter;
			$data['name']             = $term->name;
			$data['parent']           = $term->parent;
			$data['slug']             = $term->slug;
			$data['taxonomy']         = $term->taxonomy;
			$data['term_group']       = $term->term_group;
			$data['term_id']          = $term->term_id;
			$data['term_taxonomy_id'] = $term->term_taxonomy_id;

			$taxonomy = $request->get_param( 'taxonomy' ) ? $request->get_param( 'taxonomy' ) : 'category';
			/*custom thumbnail*/
			$tax_in_image = gutentor_get_options( 'tax-in-image' );
			if ( $taxonomy === 'product_cat' ) {
				$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
			} elseif ( is_array( $tax_in_image ) &&
				in_array( $term->taxonomy, $tax_in_image )
			) {
				$gutentor_meta = get_term_meta( $term->term_id, 'gutentor_meta', true );
				$thumbnail_id  = isset( $gutentor_meta['featured-image'] ) ? $gutentor_meta['featured-image'] : '';
			}
			$term_link        = get_term_link( $term->term_id, $term->taxonomy );
			$data['term_url'] = ( $term_link ) ? $term_link : '#';
			$context          = ! empty( $request['context'] ) ? $request['context'] : 'view';
			$data             = $this->add_additional_fields_to_object( $data, $request );
			$data             = $this->filter_response_by_context( $data, $context );

			/*Featured Image*/
			if ( $thumbnail_id ) {
				$attach_data = wp_get_attachment_metadata( $thumbnail_id );

				// Ensure empty details is an empty object.
				if ( ! empty( $attach_data['sizes'] ) ) {

					foreach ( $attach_data['sizes'] as $size => &$size_data ) {

						if ( isset( $size_data['mime-type'] ) ) {
							$size_data['mime_type'] = $size_data['mime-type'];
							unset( $size_data['mime-type'] );
						}

						// Use the same method image_downsize() does.
						$image_src = wp_get_attachment_image_src( $thumbnail_id, $size );
						if ( ! $image_src ) {
							continue;
						}

						$size_data['source_url'] = $image_src[0];
					}

					$full_src = wp_get_attachment_image_src( $thumbnail_id, 'full' );

					if ( ! empty( $full_src ) ) {
						$attach_data['sizes']['full'] = array(
							'file'       => wp_basename( $full_src[0] ),
							'width'      => $full_src[1],
							'height'     => $full_src[2],
							'mime_type'  => get_post_mime_type( $thumbnail_id ),
							'source_url' => $full_src[0],
						);
					}

					// Use the same method image_downsize() does.
					$image_src = wp_get_attachment_url( $thumbnail_id );
					if ( $image_src ) {
						$attach_data['source_url'] = $image_src;
					}

					$data['_embedded']['wp:featuredmedia'][] = $attach_data;

				} else {
					// Use the same method image_downsize() does.
					$image_src = wp_get_attachment_url( $thumbnail_id );
					if ( $image_src ) {
						$attach_data['source_url'] = $image_src;
					}

					$data['_embedded']['wp:featuredmedia'][] = $attach_data;
				}
			}

			$data = apply_filters( "gutentor_rest_prepare_categories_data_{$term->taxonomy}", $data, $term, $request );
			$data = apply_filters( 'gutentor_rest_prepare_term_data', $data, $term, $request );

			// Wrap the data in a response object.
			$response = rest_ensure_response( $data );

			/**
			 * Filters the post data for a response.
			 *
			 * @param WP_REST_Response $response The response object.
			 * @param WP_Post $term Post object.
			 * @param WP_REST_Request $request Request object.
			 * @since 4.7.0
			 */
			return apply_filters( "gutentor_rest_prepare_term_{$term->taxonomy}", $response, $term, $request );
		}

		/**
		 * Function to fetch posts.
		 * Copied from WP_REST_Posts_Controller get_items and modified
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 * @since 2.1.3
		 */
		public function get_posts( \WP_REST_Request $request ) {
			$post_type  = $request->get_param( 'post_type' ) ? $request->get_param( 'post_type' ) : 'post';
			$author__in = $request->get_param( 'author__in' ) && $request->get_param( 'author__in' );
			$query_args = array(
				'posts_per_page'      => $request->get_param( 'per_page' ) ? $request->get_param( 'per_page' ) : 6,
				'post_type'           => $request->get_param( 'post_type' ) ? $request->get_param( 'post_type' ) : 'post',
				'paged'               => $request->get_param( 'paged' ) ? $request->get_param( 'paged' ) : 1,
				'ignore_sticky_posts' => true,
				'post_status'         => 'publish',
			);
			/*general*/
			if ( $request->get_param( 'post_status' ) ) {
				$query_args['post_status'] = $request->get_param( 'post_status' );
			}
			if ( $request->get_param( 'offset' ) ) {
				$query_args['offset'] = $request->get_param( 'offset' ) ? $request->get_param( 'offset' ) : 0;
			}
			if ( $request->get_param( 'post__in' ) ) {
				$query_args['post__in'] = explode( ',', $request->get_param( 'post__in' ) );
			}
			if ( $request->get_param( 'post__not_in' ) ) {
				$query_args['post__not_in'] = explode( ',', $request->get_param( 'post__not_in' ) );
			}
			if ( $post_type === 'product' ) {
				$product_order_by    = $request->get_param( 'orderby' ) ? $request->get_param( 'orderby' ) : 'date';
				$product_order       = $request->get_param( 'order' ) ? $request->get_param( 'order' ) : 'desc';
				$query_args['order'] = $request->get_param( 'order' ) ? $request->get_param( 'order' ) : 'desc';
				$query_args          = $this->set_product_order_order_by( $product_order_by, $product_order, $query_args );
			} else {
				$query_args['orderby'] = $request->get_param( 'orderby' ) ? $request->get_param( 'orderby' ) : 'date';
				$query_args['order']   = $request->get_param( 'order' ) ? $request->get_param( 'order' ) : 'desc';
			}
			if ( $request->get_param( 'taxonomy' ) &&
				$request->get_param( 'term' ) ) {
				$query_args['tax_query'] = array(
					array(
						'taxonomy' => $request->get_param( 'taxonomy' ),
						'field'    => 'id',
						'terms'    => explode( ',', $request->get_param( 'term' ) ),
						'operator' => $request->get_param( 'taxOperator' ) ? $request->get_param( 'taxOperator' ) : 'IN',
					),
				);
			}

			/*category*/
			if ( $request->get_param( 'cat' ) ) {
				$query_args['cat'] = $request->get_param( 'cat' );
			}
			if ( $request->get_param( 'category_name' ) ) {
				$query_args['category_name'] = $request->get_param( 'category_name' );
			}
			if ( $request->get_param( 'category__in' ) ) {
				$query_args['category__in'] = explode( ',', $request->get_param( 'category__in' ) );
			}
			if ( $request->get_param( 'category__and' ) ) {
				$query_args['category__and'] = explode( ',', $request->get_param( 'category__and' ) );
			}
			if ( $request->get_param( 'category__not_in' ) ) {
				$query_args['category__not_in'] = explode( ',', $request->get_param( 'category__not_in' ) );
			}

			/*Tag*/
			if ( $request->get_param( 'tag' ) ) {
				$query_args['tag'] = $request->get_param( 'tag' );
			}
			if ( $request->get_param( 'tag_id' ) ) {
				$query_args['tag_id'] = $request->get_param( 'tag_id' );
			}
			if ( $request->get_param( 'tag__and' ) ) {
				$query_args['tag__and'] = explode( ',', $request->get_param( 'tag__and' ) );
			}
			if ( $request->get_param( 'tag__in' ) ) {
				$query_args['tag__in'] = explode( ',', $request->get_param( 'tag__in' ) );
			}
			if ( $request->get_param( 'tag__not_in' ) ) {
				$query_args['tag__not_in'] = explode( ',', $request->get_param( 'tag__not_in' ) );
			}

			/*author*/
			if ( $request->get_param( 'author' ) ) {
				$query_args['author'] = $request->get_param( 'author' );
			}
			if ( $request->get_param( 'author_name' ) ) {
				$query_args['author_name'] = $request->get_param( 'author_name' );
			}
			if ( $author__in ) {
				$query_args['author__in'] = explode( ',', $request->get_param( 'author__in' ) );
			}
			if ( $request->get_param( 'author__not_in' ) ) {
				$query_args['author__not_in'] = explode( ',', $request->get_param( 'author__not_in' ) );
			}

			/*search*/
			if ( $request->get_param( 's' ) ) {
				$query_args['s'] = $request->get_param( 's' );
			}

			/*post and page*/
			if ( $request->get_param( 'p' ) ) {
				$query_args['p'] = $request->get_param( 'p' );
			}
			if ( $request->get_param( 'name' ) ) {
				$query_args['name'] = $request->get_param( 'name' );
			}
			if ( $request->get_param( 'page_id' ) ) {
				$query_args['page_id'] = $request->get_param( 'page_id' );
			}
			if ( $request->get_param( 'pagename' ) ) {
				$query_args['pagename'] = $request->get_param( 'pagename' );
			}
			if ( $request->get_param( 'post_parent' ) ) {
				$query_args['post_parent'] = $request->get_param( 'post_parent' );
			}
			if ( $request->get_param( 'post_parent__in' ) ) {
				$query_args['post_parent__in'] = $request->get_param( 'post_parent__in' );
			}

			/*permission*/
			if ( $request->get_param( 'has_password' ) ) {
				$query_args['has_password'] = $request->get_param( 'has_password' );
			}
			if ( $request->get_param( 'post_password' ) ) {
				$query_args['post_password'] = $request->get_param( 'post_password' );
			}

			/*comment*/
			if ( $request->get_param( 'comment_count' ) ) {
				$query_args['comment_count'] = $request->get_param( 'comment_count' );
			}

			/*pagination*/
			if ( $request->get_param( 'nopaging' ) ) {
				$query_args['nopaging'] = $request->get_param( 'nopaging' );
			}
			if ( $request->get_param( 'posts_per_archive_page' ) ) {
				$query_args['posts_per_archive_page'] = $request->get_param( 'posts_per_archive_page' );
			}
			if ( $request->get_param( 'paged' ) ) {
				$query_args['paged'] = $request->get_param( 'paged' );
			}
			if ( $request->get_param( 'page' ) ) {
				$query_args['page'] = $request->get_param( 'page' );
			}
			if ( $request->get_param( 'ignore_sticky_posts' ) ) {
				$query_args['ignore_sticky_posts'] = $request->get_param( 'ignore_sticky_posts' );
			}

			/*permission , mimetype, cache*/
			if ( $request->get_param( 'perm' ) ) {
				$query_args['perm'] = $request->get_param( 'perm' );
			}
			if ( $request->get_param( 'post_mime_type' ) ) {
				$query_args['post_mime_type'] = explode( ',', $request->get_param( 'post_mime_type' ) );
			}
			if ( $request->get_param( 'cache_results' ) ) {
				$query_args['cache_results'] = $request->get_param( 'cache_results' );
			}
			if ( $request->get_param( 'update_post_meta_cache' ) ) {
				$query_args['update_post_meta_cache'] = $request->get_param( 'update_post_meta_cache' );
			}
			if ( $request->get_param( 'update_post_term_cache' ) ) {
				$query_args['update_post_term_cache'] = $request->get_param( 'update_post_term_cache' );
			}

			/*tax query*/
			if ( $request->get_param( 'tax_query' ) ) {
				$tax_query = $request->get_param( 'tax_query' ) ? $request->get_param( 'tax_query' ) : false;
				$tax_query = json_decode( $tax_query, true );
				if ( is_array( $tax_query['tax_query'] ) ) {
					$query_args['tax_query'] = $tax_query['tax_query'];
					foreach ( $tax_query['tax_query'] as $number => $number_array ) {
						foreach ( $number_array as $data => $data_val ) {
							if ( isset( $number_array['terms'] ) ) {
								$query_args['tax_query'][ $number ]['terms'] = explode( ',', $number_array['terms'] );
							}
						}
					}
				}
			}
			if ( $request->get_param( 'tax_query_relation' ) ) {
				$tax_query_relation = $request->get_param( 'tax_query_relation' ) ? $request->get_param( 'tax_query_relation' ) : false;
				if ( $tax_query_relation ) {
					$query_args['tax_query']['relation'] = $tax_query_relation;
				}
			}
			/*meta query*/
			if ( $request->get_param( 'meta_query' ) ) {
				$meta_query = $request->get_param( 'meta_query' ) ? $request->get_param( 'meta_query' ) : false;
				$meta_query = json_decode( $meta_query, true );
				if ( is_array( $meta_query['meta_query'] ) ) {
					$query_args['meta_query'] = $meta_query['meta_query'];
				}
			}
			if ( $request->get_param( 'meta_query_relation' ) ) {
				$meta_query_relation = $request->get_param( 'meta_query_relation' ) ? $request->get_param( 'meta_query_relation' ) : false;
				if ( $meta_query_relation ) {
					$query_args['meta_query']['relation'] = $meta_query_relation;
				}
			}
			/*date query*/
			if ( $request->get_param( 'date_query' ) ) {
				$date_query = $request->get_param( 'date_query' ) ? $request->get_param( 'date_query' ) : false;
				$date_query = json_decode( $date_query, true );
				if ( is_array( $date_query['date_query'] ) ) {
					$query_args['date_query'] = $date_query['date_query'];
				}
			}
			if ( $request->get_param( 'date_query_relation' ) ) {
				$date_query_relation = $request->get_param( 'date_query_relation' ) ? $request->get_param( 'date_query_relation' ) : false;
				if ( $date_query_relation ) {
					$query_args['date_query']['relation'] = $date_query_relation;
				}
			}
			$posts_query  = new WP_Query();
			$query_result = $posts_query->query( $query_args );

			$posts = array();

			foreach ( $query_result as $post ) {

				$data    = $this->prepare_item_for_response( $post, $request );
				$posts[] = $this->prepare_response_for_collection( $data );
			}

			$page        = (int) $query_args['paged'];
			$total_posts = $posts_query->found_posts;

			if ( $total_posts < 1 ) {
				// Out-of-bounds, run the query again without LIMIT for total count.
				unset( $query_args['paged'] );

				$count_query = new WP_Query();
				$count_query->query( $query_args );
				$total_posts = $count_query->found_posts;
			}

			$max_pages = ceil( $total_posts / (int) $posts_query->query_vars['posts_per_page'] );

			if ( $page > $max_pages && $total_posts > 0 ) {
				return new WP_Error(
					'rest_post_invalid_page_number',
					__( 'The page number requested is larger than the number of pages available.' ),
					array( 'status' => 400 )
				);
			}

			$response = rest_ensure_response( $posts );

			$response->header( 'X-WP-Total', (int) $total_posts );
			$response->header( 'X-WP-TotalPages', (int) $max_pages );

			$request_params = $request->get_query_params();
			$base           = add_query_arg( urlencode_deep( $request_params ), rest_url( sprintf( '%s/%s', $this->namespace, $this->rest_base ) ) );

			if ( $page > 1 ) {
				$prev_page = $page - 1;

				if ( $prev_page > $max_pages ) {
					$prev_page = $max_pages;
				}

				$prev_link = add_query_arg( 'page', $prev_page, $base );
				$response->link_header( 'prev', $prev_link );
			}
			if ( $max_pages > $page ) {
				$next_page = $page + 1;
				$next_link = add_query_arg( 'page', $next_page, $base );

				$response->link_header( 'next', $next_link );
			}

			return $response;
		}

		/**
		 * Function to additional elements on Post Types.
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 * @since 3.1.4
		 */
		public function additional_elements( \WP_REST_Request $request ) {
			$post_type = $request->get_param( 'post_type' );
			$type      = $request->get_param( 'type' );

			$response = false;

			$g_options = gutentor_get_options();
			if ( isset( $g_options['ptel'] ) ) {
				if ( isset( $g_options['ptel'][ $post_type ] ) ) {
					if ( isset( $g_options['ptel'][ $post_type ][ $type ] ) ) {
						$response = $g_options['ptel'][ $post_type ][ $type ];
					}
				}
			}

			return rest_ensure_response( $response );

		}

		/**
		 * Function to additional elements on Term.
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 * @since 3.1.4
		 */
		public function additional_term_elements( \WP_REST_Request $request ) {
			$term = $request->get_param( 'term' );
			$type = $request->get_param( 'type' );

			$response = false;

			$g_options = gutentor_get_options();
			if ( isset( $g_options['ttel'] ) ) {
				if ( isset( $g_options['ttel'][ $term ] ) ) {
					if ( isset( $g_options['ttel'][ $term ][ $type ] ) ) {
						$response = $g_options['ttel'][ $term ][ $type ];
					}
				}
			}

			return rest_ensure_response( $response );

		}

		/**
		 * Function to fetch gutentor all options.
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 * @since 3.0.2
		 */
		public function set_gutentor_settings( \WP_REST_Request $request ) {
			$params = $request->get_params();
			if ( isset( $params['settings'] ) ) {
				$settings = $params['settings'];
				gutentor_delete_options();
				$g_options = gutentor_get_options();
				foreach ( $settings as $key => $value ) {

					if (
						'map-api' === $key ||
						'dynamic-res-location' === $key ||
						'fa-version' === $key ||
						'typo-apply-options' === $key ||
						'color-palette-options' === $key

					) {
						$value = sanitize_text_field( $value );
					} elseif (
						'wide-width-editor' === $key ||
						'enable-export-block' === $key ||
						'enable-import-block' === $key ||
						'on-popup' === $key ||
						'allow-tracking' === $key ||
						'edd-demo-url' === $key
					) {
						$value = gutentor_sanitize_checkbox( $value );
					} elseif (
						'resource-load' === $key
					) {
						$value = $value;
					} elseif (
						'color-palettes' === $key ||
						'editor-in-pt' === $key ||
						'page-templates-in-pt' === $key ||
						'tax-in-color' === $key ||
						'tax-in-image' === $key ||
						'videos-in-pt' === $key
					) {
						$value = gutentor_sanitize_array( $value );
					} elseif ( 'ptel' === $key || 'ttel' === $key ) {
						if ( is_array( $value ) ) {
							foreach ( $value as $k1 => $v1 ) {
								$value[ $k1 ] = $v1;
								foreach ( $v1 as $k2 => $v2 ) {
									foreach ( $v2 as $k3 => $v3 ) {

										foreach ( $v3 as $k4 => $v4 ) {
											if ( isset( $v4['label'] ) && isset( $v4['value'] ) ) {
												$value[ $k1 ][ $k2 ][ $k3 ][ $k4 ]['label'] = sanitize_text_field( $v4['label'] );
												$value[ $k1 ][ $k2 ][ $k3 ][ $k4 ]['value'] = sanitize_text_field( $v4['value'] );
											} else {
												unset( $value[ $k1 ][ $k2 ][ $k3 ][ $k4 ] );
											}
										}
									}
								}
							}
						} else {
							$value = array();
						}
					} elseif ( 'archive-templates' === $key ||
						'singular-templates' === $key ||
						'others-templates' === $key
					) {
						if ( is_array( $value ) ) {
							foreach ( $value as $k1 => $v1 ) {
								$value[ $k1 ] = $v1;
								if ( isset( $v1['condition'] ) && isset( $v1['template'] ) ) {
									$value[ $k1 ]['condition'] = sanitize_text_field( $v1['condition'] );
									$value[ $k1 ]['template']  = sanitize_text_field( $v1['template'] );
								} else {
									unset( $value[ $k1 ] );
								}
							}
						} else {
							$value = array();
						}
					} elseif ( strpos( $key, 'pf-' ) !== false ) {
						$value = $value;
					} elseif ( strpos( $key, 'gt-' ) !== false ) {
						$value = sanitize_text_field( $value );
					} elseif ( strpos( $key, 'gw-' ) !== false ) {
						$value = absint( $value );
					} elseif ( strpos( $key, 'gc-' ) !== false ) {
						$value = sanitize_text_field( $value );
					} else {
						$value = sanitize_text_field( $value );
					}
					$g_options[ $key ] = $value;
				}
				do_action( 'set_gutentor_settings_options', $g_options );
				update_option( 'gutentor_settings_options', $g_options );
				return rest_ensure_response( gutentor_get_options() );
			}
			return rest_ensure_response( gutentor_get_options() );

		}

		/**
		 * Function to fetch gutentor all options.
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 * @since 3.0.2
		 */
		public function get_gutentor_settings( \WP_REST_Request $request ) {
			return rest_ensure_response( gutentor_get_options() );
		}

		/**
		 * Function to get post types
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 * @since 3.1.2
		 */
		public function get_post_types( \WP_REST_Request $request ) {
			if ( $request->get_param( 'args' ) ) {
				return rest_ensure_response( gutentor_admin_get_post_types( $request->get_param( 'args' ) ) );
			} else {
				return rest_ensure_response( gutentor_admin_get_post_types() );
			}
		}

		/**
		 * Function to get post meta
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 * @since 3.1.4
		 */
		public function get_all_metas( \WP_REST_Request $request ) {
			$post_type = $request->get_param( 'post_type' );

			$meta_keys = array();
			global $wpdb;
			$query = "
            SELECT DISTINCT($wpdb->postmeta.meta_key) 
            FROM $wpdb->posts 
            LEFT JOIN $wpdb->postmeta 
            ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
            WHERE $wpdb->posts.post_type = '%s' 
            AND $wpdb->postmeta.meta_key != '' 
            AND $wpdb->postmeta.meta_key != 'enclosure' 
            AND $wpdb->postmeta.meta_key != 'gutentor_gfont_url' 
            AND $wpdb->postmeta.meta_key != 'gutentor_dynamic_css' 
            AND $wpdb->postmeta.meta_key != 'gutentor_css_info' 
            AND $wpdb->postmeta.meta_key != 'gutentor_meta_video_src_option' 
            AND $wpdb->postmeta.meta_key != 'gutentor_meta_video_url' 
            AND $wpdb->postmeta.meta_key != 'gutentor_meta_video_id' 
            AND $wpdb->postmeta.meta_key != 'cosmoswp_site_layout' 
            AND $wpdb->postmeta.meta_key != 'cosmoswp_sidebar_options' 
            AND $wpdb->postmeta.meta_key != 'cosmoswp_header_layout' 
            AND $wpdb->postmeta.meta_key != 'cosmoswp_footer_layout' 
            AND $wpdb->postmeta.meta_key != 'cosmoswp_banner_options_layout' 
            AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)' 
            AND $wpdb->postmeta.meta_key NOT RegExp '(^[0-9]+$)'";

			$normal_meta = $wpdb->get_col( $wpdb->prepare( $query, $post_type ) );

			if ( function_exists( 'gutentor_get_acf_fields_by_location' ) ) {
				$acf_fields       = gutentor_get_acf_fields_by_location( $post_type );
				$meta_keys['acf'] = $acf_fields;
				$names            = array_column( $acf_fields, 'name' );
				if ( is_array( $normal_meta ) && is_array( $names ) ) {
					foreach ( $normal_meta as $key => $val ) {
						if ( in_array( $val, $names ) ) {
							unset( $normal_meta[ $key ] );
						}
					}
				}
			}

			$meta_keys['normal'] = $normal_meta;

			/* create 1 Day Expiration TODO*/
			set_transient( 'gutentor_meta_keys_' . $post_type, $meta_keys, 60 * 60 * 24 );

			return rest_ensure_response( $meta_keys );

		}


		/**
		 * Function to get all term meta
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 * @since 3.1.4
		 */
		public function get_all_term_metas( \WP_REST_Request $request ) {
			$post_type = $request->get_param( 'tax' );

			$meta_keys = array();
			global $wpdb;
			$query = "
            SELECT DISTINCT($wpdb->termmeta.meta_key) 
            FROM $wpdb->termmeta
            LEFT JOIN $wpdb->terms 
                ON $wpdb->terms.term_id = $wpdb->termmeta.term_id 
            LEFT JOIN $wpdb->term_taxonomy
                ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id 
            WHERE $wpdb->term_taxonomy.taxonomy = '%s' 
            AND $wpdb->termmeta.meta_key != '' 
            AND $wpdb->termmeta.meta_key != 'enclosure' 
            AND $wpdb->termmeta.meta_key != 'gutentor_dynamic_css' 
            AND $wpdb->termmeta.meta_key != 'gutentor_css_info' 
            AND $wpdb->termmeta.meta_key != 'gutentor_meta_video_src_option' 
            AND $wpdb->termmeta.meta_key != 'gutentor_meta_video_id' 
            AND $wpdb->termmeta.meta_key != 'cosmoswp_site_layout' 
            AND $wpdb->termmeta.meta_key != 'cosmoswp_sidebar_options' 
            AND $wpdb->termmeta.meta_key != 'cosmoswp_header_layout' 
            AND $wpdb->termmeta.meta_key != 'cosmoswp_footer_layout' 
            AND $wpdb->termmeta.meta_key != 'cosmoswp_banner_options_layout' 
            AND $wpdb->termmeta.meta_key NOT RegExp '(^[_0-9].+$)' 
            AND $wpdb->termmeta.meta_key NOT RegExp '(^[0-9]+$)'";

			$normal_meta = $wpdb->get_col( $wpdb->prepare( $query, $post_type ) );

			if ( function_exists( 'gutentor_get_acf_fields_by_location' ) ) {
				$acf_fields       = gutentor_get_acf_fields_by_location( $post_type );
				$meta_keys['acf'] = $acf_fields;
				$names            = array_column( $acf_fields, 'name' );
				if ( is_array( $normal_meta ) && is_array( $names ) ) {
					foreach ( $normal_meta as $key => $val ) {
						if ( in_array( $val, $names ) ) {
							unset( $normal_meta[ $key ] );
						}
					}
				}
			}

			$meta_keys['normal'] = $normal_meta;

			/* create 1 Day Expiration TODO*/
			set_transient( 'gutentor_meta_keys_' . $post_type, $meta_keys, 60 * 60 * 24 );

			return rest_ensure_response( $meta_keys );

		}
		/**
		 * Function to popup.
		 *
		 * @return array|bool|\WP_Error
		 */
		public function popup( \WP_REST_Request $request ) {
			if ( ! function_exists( 'gutentor_template' ) ) {
				return false;
			}
			if ( ! gutentor_get_options( 'on-popup' ) ) {
				return false;
			}
			$page_conditions = $request->get_param( 'condition' );

			$popup_data = gutentor_template()->get_popup_data( $page_conditions );
			return rest_ensure_response( $popup_data );
		}

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @return object
		 * @since 1.0.1
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
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @access public
		 * @return void
		 * @since 1.0.0
		 */
		public function __clone() {
			 // Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'gutentor' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @access public
		 * @return void
		 * @since 1.0.0
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'gutentor' ), '1.0.0' );
		}
	}

}
Gutentor_Self_Api_Handler::get_instance()->run();
