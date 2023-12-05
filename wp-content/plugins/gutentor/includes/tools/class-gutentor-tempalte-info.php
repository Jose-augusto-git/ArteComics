<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Gutentor_Template_Info' ) ) {
	/**
	 * Gutentor Template Info
	 * Copy of wp-includes\template-loader.php, wp-includes\template.php files and locate_block_template function
	 * to retrive template info
	 * If we can directly get currently template info, we may delete this file
	 *
	 * @package Gutentor
	 *  @since    3.1.9
	 */
	class Gutentor_Template_Info {

		/*
		wp-includes\template-loader.php
		*/
		public function get_template_info() {

			$tag_templates = array(
				'is_embed'             => 'get_embed_template',
				'is_404'               => 'get_404_template',
				'is_search'            => 'get_search_template',
				'is_front_page'        => 'get_front_page_template',
				'is_home'              => 'get_home_template',
				'is_privacy_policy'    => 'get_privacy_policy_template',
				'is_post_type_archive' => 'get_post_type_archive_template',
				'is_tax'               => 'get_taxonomy_template',
				'is_attachment'        => 'get_attachment_template',
				'is_single'            => 'get_single_template',
				'is_page'              => 'get_page_template',
				'is_singular'          => 'get_singular_template',
				'is_category'          => 'get_category_template',
				'is_tag'               => 'get_tag_template',
				'is_author'            => 'get_author_template',
				'is_date'              => 'get_date_template',
				'is_archive'           => 'get_archive_template',
			);
			$template      = false;

			// Loop through each of the template conditionals, and find the appropriate template file.
			foreach ( $tag_templates as $tag => $template_getter ) {
				if ( call_user_func( $tag ) ) {
					if ( method_exists( $this, $template_getter ) ) {
						$template = $this->$template_getter();
					}
				}
				if ( $template ) {
					break;
				}
			}

			if ( ! $template ) {
				$template = $this->get_index_template();
			}
			return $template;

		}

		/**
		 * Find a block template with equal or higher specificity than a given PHP template file.
		 *
		 * Internally, this communicates the block content that needs to be used by the template canvas through a global variable.
		 *
		 * @since 5.8.0
		 *
		 * @global string $_wp_current_template_content
		 *
		 * @param string   $template  Path to the template. See locate_template().
		 * @param string   $type      Sanitized filename without extension.
		 * @param string[] $templates A list of template candidates, in descending order of priority.
		 * @return WP_Block_Template|null template A template object, or null if none could be found.
		 */
		function locate_block_template( $template, $type, array $templates ) {

			if ( $template ) {
				/*
				 * locate_template() has found a PHP template at the path specified by $template.
				 * That means that we have a fallback candidate if we cannot find a block template
				 * with higher specificity.
				 *
				 * Thus, before looking for matching block themes, we shorten our list of candidate
				 * templates accordingly.
				 */

				// Locate the index of $template (without the theme directory path) in $templates.
				$relative_template_path = str_replace(
					array( get_stylesheet_directory() . '/', get_template_directory() . '/' ),
					'',
					$template
				);
				$index                  = array_search( $relative_template_path, $templates, true );

				// If the template hiearchy algorithm has successfully located a PHP template file,
				// we will only consider block templates with higher or equal specificity.
				$templates = array_slice( $templates, 0, $index + 1 );
			}

			return resolve_block_template( $type, $templates, $template );
		}

		/**
		 * Retrieve path to a template
		 *
		 * Used to quickly retrieve the path of a template without including the file
		 * extension. It will also check the parent theme, if the file exists, with
		 * the use of locate_template(). Allows for more generic template location
		 * without the use of the other get_*_template() functions.
		 *
		 * @since 1.5.0
		 *
		 * @param string   $type      Filename without extension.
		 * @param string[] $templates An optional list of template candidates.
         * @return WP_Block_Template|null template A template object, or null if none could be found.
         */
		function get_query_template( $type, $templates = array() ) {
			$type = preg_replace( '|[^a-z0-9-]+|', '', $type );

			if ( empty( $templates ) ) {
				$templates = array( "{$type}.php" );
			}

			/**
			 * Filters the list of template filenames that are searched for when retrieving a template to use.
			 *
			 * The dynamic portion of the hook name, `$type`, refers to the filename -- minus the file
			 * extension and any non-alphanumeric characters delimiting words -- of the file to load.
			 * The last element in the array should always be the fallback template for this query type.
			 *
			 * Possible hook names include:
			 *
			 *  - `404_template_hierarchy`
			 *  - `archive_template_hierarchy`
			 *  - `attachment_template_hierarchy`
			 *  - `author_template_hierarchy`
			 *  - `category_template_hierarchy`
			 *  - `date_template_hierarchy`
			 *  - `embed_template_hierarchy`
			 *  - `frontpage_template_hierarchy`
			 *  - `home_template_hierarchy`
			 *  - `index_template_hierarchy`
			 *  - `page_template_hierarchy`
			 *  - `paged_template_hierarchy`
			 *  - `privacypolicy_template_hierarchy`
			 *  - `search_template_hierarchy`
			 *  - `single_template_hierarchy`
			 *  - `singular_template_hierarchy`
			 *  - `tag_template_hierarchy`
			 *  - `taxonomy_template_hierarchy`
			 *
			 * @since 4.7.0
			 *
			 * @param string[] $templates A list of template candidates, in descending order of priority.
			 */
			$templates = apply_filters( "{$type}_template_hierarchy", $templates );

			$template = locate_template( $templates );

			return $this->locate_block_template( $template, $type, $templates );
		}

		/**
		 * Retrieve path of index template in current or parent template.
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'index'.
		 *
		 * @since 3.0.0
		 *
		 * @see $this->get_query_template()
		 *
         * @return WP_Block_Template|null template A template object, or null if none could be found.
         */
		function get_index_template() {
			return $this->get_query_template( 'index' );
		}

		/**
		 * Retrieve path of 404 template in current or parent template.
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is '404'.
		 *
		 * @since 1.5.0
		 *
		 * @see $this->get_query_template()
		 *
         * @return WP_Block_Template|null template A template object, or null if none could be found.
         */
		function get_404_template() {
			return $this->get_query_template( '404' );
		}

		/**
		 * Retrieve path of archive template in current or parent template.
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'archive'.
		 *
		 * @since 1.5.0
		 *
		 * @see $this->get_query_template()
		 *
         * @return WP_Block_Template|null template A template object, or null if none could be found.
         */
		function get_archive_template() {
			$post_types = array_filter( (array) get_query_var( 'post_type' ) );

			$templates = array();

			if ( count( $post_types ) == 1 ) {
				$post_type   = reset( $post_types );
				$templates[] = "archive-{$post_type}.php";
			}
			$templates[] = 'archive.php';

			return $this->get_query_template( 'archive', $templates );
		}

		/**
		 * Retrieve path of post type archive template in current or parent template.
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'archive'.
		 *
		 * @since 3.7.0
		 *
		 * @see get_archive_template()
		 *
		 * @return string Full path to archive template file.
		 */
		function get_post_type_archive_template() {
			$post_type = get_query_var( 'post_type' );
			if ( is_array( $post_type ) ) {
				$post_type = reset( $post_type );
			}

			$obj = get_post_type_object( $post_type );
			if ( ! ( $obj instanceof WP_Post_Type ) || ! $obj->has_archive ) {
				return '';
			}

			return get_archive_template();
		}

		/**
		 * Retrieve path of author template in current or parent template.
		 *
		 * The hierarchy for this template looks like:
		 *
		 * 1. author-{nicename}.php
		 * 2. author-{id}.php
		 * 3. author.php
		 *
		 * An example of this is:
		 *
		 * 1. author-john.php
		 * 2. author-1.php
		 * 3. author.php
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'author'.
		 *
		 * @since 1.5.0
		 *
		 * @see $this->get_query_template()
		 *
         * @return WP_Block_Template|null template A template object, or null if none could be found.
         */
		function get_author_template() {
			$author = get_queried_object();

			$templates = array();

			if ( $author instanceof WP_User ) {
				$templates[] = "author-{$author->user_nicename}.php";
				$templates[] = "author-{$author->ID}.php";
			}
			$templates[] = 'author.php';

			return $this->get_query_template( 'author', $templates );
		}

		/**
		 * Retrieve path of category template in current or parent template.
		 *
		 * The hierarchy for this template looks like:
		 *
		 * 1. category-{slug}.php
		 * 2. category-{id}.php
		 * 3. category.php
		 *
		 * An example of this is:
		 *
		 * 1. category-news.php
		 * 2. category-2.php
		 * 3. category.php
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'category'.
		 *
		 * @since 1.5.0
		 * @since 4.7.0 The decoded form of `category-{slug}.php` was added to the top of the
		 *              template hierarchy when the category slug contains multibyte characters.
		 *
		 * @see $this->get_query_template()
		 *
		 * @return object Full path to category template file.
		 */
		function get_category_template() {
			$category = get_queried_object();

			$templates = array();

			if ( ! empty( $category->slug ) ) {

				$slug_decoded = urldecode( $category->slug );
				if ( $slug_decoded !== $category->slug ) {
					$templates[] = "category-{$slug_decoded}.php";
				}

				$templates[] = "category-{$category->slug}.php";
				$templates[] = "category-{$category->term_id}.php";
			}
			$templates[] = 'category.php';

			return $this->get_query_template( 'category', $templates );
		}

		/**
		 * Retrieve path of tag template in current or parent template.
		 *
		 * The hierarchy for this template looks like:
		 *
		 * 1. tag-{slug}.php
		 * 2. tag-{id}.php
		 * 3. tag.php
		 *
		 * An example of this is:
		 *
		 * 1. tag-wordpress.php
		 * 2. tag-3.php
		 * 3. tag.php
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'tag'.
		 *
		 * @since 2.3.0
		 * @since 4.7.0 The decoded form of `tag-{slug}.php` was added to the top of the
		 *              template hierarchy when the tag slug contains multibyte characters.
		 *
		 * @see $this->get_query_template()
		 *
		 * @return object Full path to tag template file.
		 */
		function get_tag_template() {
			$tag = get_queried_object();

			$templates = array();

			if ( ! empty( $tag->slug ) ) {

				$slug_decoded = urldecode( $tag->slug );
				if ( $slug_decoded !== $tag->slug ) {
					$templates[] = "tag-{$slug_decoded}.php";
				}

				$templates[] = "tag-{$tag->slug}.php";
				$templates[] = "tag-{$tag->term_id}.php";
			}
			$templates[] = 'tag.php';

			return $this->get_query_template( 'tag', $templates );
		}

		/**
		 * Retrieve path of custom taxonomy term template in current or parent template.
		 *
		 * The hierarchy for this template looks like:
		 *
		 * 1. taxonomy-{taxonomy_slug}-{term_slug}.php
		 * 2. taxonomy-{taxonomy_slug}.php
		 * 3. taxonomy.php
		 *
		 * An example of this is:
		 *
		 * 1. taxonomy-location-texas.php
		 * 2. taxonomy-location.php
		 * 3. taxonomy.php
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'taxonomy'.
		 *
		 * @since 2.5.0
		 * @since 4.7.0 The decoded form of `taxonomy-{taxonomy_slug}-{term_slug}.php` was added to the top of the
		 *              template hierarchy when the term slug contains multibyte characters.
		 *
		 * @see $this->get_query_template()
		 *
		 * @return object Full path to custom taxonomy term template file.
		 */
		function get_taxonomy_template() {
			$term = get_queried_object();

			$templates = array();

			if ( ! empty( $term->slug ) ) {
				$taxonomy = $term->taxonomy;

				$slug_decoded = urldecode( $term->slug );
				if ( $slug_decoded !== $term->slug ) {
					$templates[] = "taxonomy-$taxonomy-{$slug_decoded}.php";
				}

				$templates[] = "taxonomy-$taxonomy-{$term->slug}.php";
				$templates[] = "taxonomy-$taxonomy.php";
			}
			$templates[] = 'taxonomy.php';

			return $this->get_query_template( 'taxonomy', $templates );
		}

		/**
		 * Retrieve path of date template in current or parent template.
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'date'.
		 *
		 * @since 1.5.0
		 *
		 * @see $this->get_query_template()
		 *
		 * @return object Full path to date template file.
		 */
		function get_date_template() {
			return $this->get_query_template( 'date' );
		}

		/**
		 * Retrieve path of home template in current or parent template.
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'home'.
		 *
		 * @since 1.5.0
		 *
		 * @see $this->get_query_template()
		 *
		 * @return object Full path to home template file.
		 */
		function get_home_template() {
			$templates = array( 'home.php', 'index.php' );

			return $this->get_query_template( 'home', $templates );
		}

		/**
		 * Retrieve path of front page template in current or parent template.
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'frontpage'.
		 *
		 * @since 3.0.0
		 *
		 * @see $this->get_query_template()
		 *
		 * @return object Full path to front page template file.
		 */
		function get_front_page_template() {
			$templates = array( 'front-page.php' );

			return $this->get_query_template( 'frontpage', $templates );
		}

		/**
		 * Retrieve path of Privacy Policy page template in current or parent template.
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'privacypolicy'.
		 *
		 * @since 5.2.0
		 *
		 * @see $this->get_query_template()
		 *
		 * @return object Full path to privacy policy template file.
		 */
		function get_privacy_policy_template() {
			$templates = array( 'privacy-policy.php' );

			return $this->get_query_template( 'privacypolicy', $templates );
		}

		/**
		 * Retrieve path of page template in current or parent template.
		 *
		 * The hierarchy for this template looks like:
		 *
		 * 1. {Page Template}.php
		 * 2. page-{page_name}.php
		 * 3. page-{id}.php
		 * 4. page.php
		 *
		 * An example of this is:
		 *
		 * 1. page-templates/full-width.php
		 * 2. page-about.php
		 * 3. page-4.php
		 * 4. page.php
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'page'.
		 *
		 * @since 1.5.0
		 * @since 4.7.0 The decoded form of `page-{page_name}.php` was added to the top of the
		 *              template hierarchy when the page name contains multibyte characters.
		 *
		 * @see $this->get_query_template()
		 *
		 * @return object Full path to page template file.
		 */
		function get_page_template() {
			$id       = get_queried_object_id();
			$template = get_page_template_slug();
			$pagename = get_query_var( 'pagename' );

			if ( ! $pagename && $id ) {
				// If a static page is set as the front page, $pagename will not be set.
				// Retrieve it from the queried object.
				$post = get_queried_object();
				if ( $post ) {
					$pagename = $post->post_name;
				}
			}

			$templates = array();
			if ( $template && 0 === validate_file( $template ) ) {
				$templates[] = $template;
			}
			if ( $pagename ) {
				$pagename_decoded = urldecode( $pagename );
				if ( $pagename_decoded !== $pagename ) {
					$templates[] = "page-{$pagename_decoded}.php";
				}
				$templates[] = "page-{$pagename}.php";
			}
			if ( $id ) {
				$templates[] = "page-{$id}.php";
			}
			$templates[] = 'page.php';

			return $this->get_query_template( 'page', $templates );
		}

		/**
		 * Retrieve path of search template in current or parent template.
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'search'.
		 *
		 * @since 1.5.0
		 *
		 * @see $this->get_query_template()
		 *
		 * @return object Full path to search template file.
		 */
		function get_search_template() {
			return $this->get_query_template( 'search' );
		}

		/**
		 * Retrieve path of single template in current or parent template. Applies to single Posts,
		 * single Attachments, and single custom post types.
		 *
		 * The hierarchy for this template looks like:
		 *
		 * 1. {Post Type Template}.php
		 * 2. single-{post_type}-{post_name}.php
		 * 3. single-{post_type}.php
		 * 4. single.php
		 *
		 * An example of this is:
		 *
		 * 1. templates/full-width.php
		 * 2. single-post-hello-world.php
		 * 3. single-post.php
		 * 4. single.php
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'single'.
		 *
		 * @since 1.5.0
		 * @since 4.4.0 `single-{post_type}-{post_name}.php` was added to the top of the template hierarchy.
		 * @since 4.7.0 The decoded form of `single-{post_type}-{post_name}.php` was added to the top of the
		 *              template hierarchy when the post name contains multibyte characters.
		 * @since 4.7.0 `{Post Type Template}.php` was added to the top of the template hierarchy.
		 *
		 * @see $this->get_query_template()
		 *
		 * @return object Full path to single template file.
		 */
		function get_single_template() {
			$object = get_queried_object();

			$templates = array();

			if ( ! empty( $object->post_type ) ) {
				$template = get_page_template_slug( $object );
				if ( $template && 0 === validate_file( $template ) ) {
					$templates[] = $template;
				}

				$name_decoded = urldecode( $object->post_name );
				if ( $name_decoded !== $object->post_name ) {
					$templates[] = "single-{$object->post_type}-{$name_decoded}.php";
				}

				$templates[] = "single-{$object->post_type}-{$object->post_name}.php";
				$templates[] = "single-{$object->post_type}.php";
			}

			$templates[] = 'single.php';

			return $this->get_query_template( 'single', $templates );
		}

		/**
		 * Retrieves an embed template path in the current or parent template.
		 *
		 * The hierarchy for this template looks like:
		 *
		 * 1. embed-{post_type}-{post_format}.php
		 * 2. embed-{post_type}.php
		 * 3. embed.php
		 *
		 * An example of this is:
		 *
		 * 1. embed-post-audio.php
		 * 2. embed-post.php
		 * 3. embed.php
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'embed'.
		 *
		 * @since 4.5.0
		 *
		 * @see $this->get_query_template()
		 *
		 * @return object Full path to embed template file.
		 */
		function get_embed_template() {
			$object = get_queried_object();

			$templates = array();

			if ( ! empty( $object->post_type ) ) {
				$post_format = get_post_format( $object );
				if ( $post_format ) {
					$templates[] = "embed-{$object->post_type}-{$post_format}.php";
				}
				$templates[] = "embed-{$object->post_type}.php";
			}

			$templates[] = 'embed.php';

			return $this->get_query_template( 'embed', $templates );
		}

		/**
		 * Retrieves the path of the singular template in current or parent template.
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'singular'.
		 *
		 * @since 4.3.0
		 *
		 * @see $this->get_query_template()
		 *
		 * @return object Full path to singular template file
		 */
		function get_singular_template() {
			return $this->get_query_template( 'singular' );
		}

		/**
		 * Retrieve path of attachment template in current or parent template.
		 *
		 * The hierarchy for this template looks like:
		 *
		 * 1. {mime_type}-{sub_type}.php
		 * 2. {sub_type}.php
		 * 3. {mime_type}.php
		 * 4. attachment.php
		 *
		 * An example of this is:
		 *
		 * 1. image-jpeg.php
		 * 2. jpeg.php
		 * 3. image.php
		 * 4. attachment.php
		 *
		 * The template hierarchy and template path are filterable via the {@see '$type_template_hierarchy'}
		 * and {@see '$type_template'} dynamic hooks, where `$type` is 'attachment'.
		 *
		 * @since 2.0.0
		 * @since 4.3.0 The order of the mime type logic was reversed so the hierarchy is more logical.
		 *
		 * @see $this->get_query_template()
		 *
		 * @global array $posts
		 *
		 * @return object Full path to attachment template file.
		 */
		function get_attachment_template() {
			$attachment = get_queried_object();

			$templates = array();

			if ( $attachment ) {
				if ( false !== strpos( $attachment->post_mime_type, '/' ) ) {
					list( $type, $subtype ) = explode( '/', $attachment->post_mime_type );
				} else {
					list( $type, $subtype ) = array( $attachment->post_mime_type, '' );
				}

				if ( ! empty( $subtype ) ) {
					$templates[] = "{$type}-{$subtype}.php";
					$templates[] = "{$subtype}.php";
				}
				$templates[] = "{$type}.php";
			}
			$templates[] = 'attachment.php';

			return $this->get_query_template( 'attachment', $templates );
		}

        /*
        wp-includes\template-loader.php
        */
        public function get_template_part_info($attributes) {

            $template_part_query = new WP_Query(
                array(
                    'post_type'      => 'wp_template_part',
                    'post_status'    => 'publish',
                    'post_name__in'  => array( $attributes['slug'] ),
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'wp_theme',
                            'field'    => 'slug',
                            'terms'    => $attributes['theme'],
                        ),
                    ),
                    'posts_per_page' => 1,
                    'no_found_rows'  => true,
                )
            );
            return $template_part_query->have_posts() ? $template_part_query->next_post() : null;

        }
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
		 * @since 1.0.0
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'gutentor' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'gutentor' ), '1.0.0' );
		}
	}

}