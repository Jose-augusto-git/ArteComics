<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Widget_Blog_Post_Templates' ) ) {

	/**
	 * Blog_Post_Templates Class For Gutentor
	 *
	 * @package Gutentor
	 * @since 2.0.0
	 */
	class Gutentor_Widget_Blog_Post_Templates {

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
		 * Run Block
		 *
		 * @access public
		 * @since 2.0.0
		 * @return void
		 */
		public function run() {
			add_filter( 'gutentor_save_blog_post_block_template_data', array( $this, 'load_blog_post_template' ), 10, 3 );
		}

		/**
		 * Load Template 1
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */
		public function template_1( $data, $post, $attributes ) {

			$output               = '';
			$overlay              = ( $attributes['blockImageBoxImageOverlayColor']['enable'] ) ? "<div class='overlay'></div>" : '';
			$enable_image_display = $attributes['blockEnableImageBoxDisplayOptions'] ? $attributes['blockEnableImageBoxDisplayOptions'] : false;
			if ( $attributes['enablePostImage'] ) {
				$image_output = '';
				$output      .= '<div class="gutentor-single-item-image-box">';
				if ( 'bg-image' == $attributes['blockImageBoxDisplayOptions'] && $enable_image_display ) {
					$url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
					if ( $url ) {
						$image_output .= '<div class="gutentor-bg-image" style="background-image:url(' . $url . ')">';
						$image_output .= $overlay;
						$image_output .= '</div>';
					}
				} else {
					if ( has_post_thumbnail() ) {
						$image_output .= '<div class="gutentor-image-thumb">';
						$image_output .= get_the_post_thumbnail( '', '', '' );
						$image_output .= $overlay;
						$image_output .= '</div>';
					}
				}
				$output .= apply_filters( 'gutentor_save_item_image_display_data', $image_output, get_permalink(), $attributes );
				$output .= '</div>';/*.gutentor-single-item-image-box*/
			}
			$output .= '<div class="gutentor-post-content">';
			if ( $attributes['blockSingleItemTitleEnable'] ) {
				$title_tag = $attributes['blockSingleItemTitleTag'];
				$output   .= '<' . $title_tag . ' class="gutentor-single-item-title">';
				$output   .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
				$output   .= get_the_title();
				$output   .= '</a>';
				$output   .= '</' . $title_tag . '>';
			}
			$output .= '<div class="entry-meta">';
			if ( $attributes['enablePostDate'] ) {
				$dateFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-calendar' : 'far fa-calendar-alt';
				$output              .= '<div class="posted-on"><i class="' . $dateFontAwesomeClass . '"></i>';
				$output              .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . get_the_date() . '</a>';
				$output              .= '</div>';

			}
			if ( $attributes['enablePostAuthor'] ) {
				$authorFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-user' : 'far fa-user';
				$output                .= '<div class="author vcard"><i class="' . $authorFontAwesomeClass . '"></i>';
				$output                .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . get_the_author() . '</a>';
				$output                .= '</div>';
			}
			if ( $attributes['enablePostCategory'] ) {
				$categories_list = get_the_category_list( esc_html__( ', ', 'gutentor' ) );
				if ( $categories_list ) {
					$catFontAwesomeClass = (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-tags' : 'fas fa-tags';
					$output             .= '<div class="cat-links"><i class="' . $catFontAwesomeClass . '"></i>' . $categories_list . '</div>';
				}
			}
			$output .= '</div>';/*.entry-meta*/
			if ( $attributes['excerptLength'] > 0 && $attributes['blockSingleItemDescriptionEnable'] ) {
				$desc_tag = $attributes['blockSingleItemDescriptionTag'];
				$output  .= '<div class="gutentor-post-excerpt gutentor-single-item-desc">';
				$output  .= "<$desc_tag class='gutentor-single-item-desc'>" . gutentor_get_excerpt_by_id( $post->ID, $attributes['excerptLength'] ) . "</$desc_tag>";
				$output  .= '</div>';
			}
			if ( $attributes['blockSingleItemButtonEnable'] ) {
				$default_class = gutentor_concat_space( 'gutentor-button', 'gutentor-single-item-button' );
				$icon          = ( isset( $attributes['buttonIcon'] ) && $attributes['buttonIcon']['value'] ) ? '<i class="gutentor-button-icon ' . $attributes['buttonIcon']['value'] . '" ></i>' : '';
				$icon_options  = ( isset( $attributes['blockSingleItemButtonIconOptions'] ) ) ? $attributes['blockSingleItemButtonIconOptions'] : '';
				$link_options  = ( isset( $attributes['blockSingleItemButtonLinkOptions'] ) ) ? $attributes['blockSingleItemButtonLinkOptions'] : '';
				$output       .= '<a class="' . gutentor_concat_space( $default_class, GutentorButtonOptionsClasses( $icon_options ) ) . '" ' . apply_filters( 'gutentor_save_link_attr', '', esc_url( get_permalink() ), $link_options ) . '>' . $icon . '<span>' . esc_html( $attributes['buttonText'] ) . '</span></a>';
			}
			$output .= '</div>';/*.gutentor-post-content*/
			return $output;
		}

		/**
		 * Blog Post Templates
		 *
		 * @param {string} $data
		 * @param {array}  $post
		 * @param {array}  $attributes
		 * @return {mix}
		 */

		public function load_blog_post_template( $data, $post, $attributes ) {

			$output = $data;

			if ( 'blog-template1' === $attributes['blockBlogTemplate'] ) {
				$output = $this->template_1( $data, $post, $attributes );
			}
			return $output;
		}

	}
}
Gutentor_Widget_Blog_Post_Templates::get_instance()->run();
