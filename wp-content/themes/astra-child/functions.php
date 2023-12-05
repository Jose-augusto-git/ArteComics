<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

// Minha conta - Nome do usuário
function my_user_name() {
  $current_user = wp_get_current_user();
  return trim( 'Bem-vindo ' . $current_user->user_firstname . ' ' . $current_user->user_lastname );
}
add_shortcode( 'my-user-name', 'my_user_name' );

// URL Logout
function my_url_logout() {
  return wp_logout_url();
}
add_shortcode( 'my-url-logout', 'my_url_logout' );