<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used to
 * add/remove/edit the functionality of the Gutentor Plugin
 *
 * @link       https://www.gutentor.com/
 * @since      1.0.0
 *
 * @package    Gutentor
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * functionality of the plugin
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Gutentor
 * @author     Gutentor <info@gutentor.com>
 */
class Gutentor {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Gutentor_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Full Name of plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_full_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_full_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Main Instance
	 *
	 * Insures that only one instance of Gutentor exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return object
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null === $instance ) {
			$instance = new Gutentor();

			do_action( 'gutentor_loaded' );
		}

		// Always return the instance
		return $instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		if ( defined( 'GUTENTOR_VERSION' ) ) {
			$this->version = GUTENTOR_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name      = GUTENTOR_PLUGIN_NAME;
		$this->plugin_full_name = esc_html__( 'Gutentor', 'gutentor' );

		if ( function_exists( 'register_block_type' ) ) {
			$this->load_dependencies();
			$this->set_locale();

			$this->define_hooks();
			$this->load_hooks();
		}
	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Gutentor_Loader. Orchestrates the hooks of the plugin.
	 * - Gutentor_i18n. Defines internationalization functionality.
	 * - Gutentor_Admin. Defines all hooks for the admin area.
	 * - Gutentor_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once GUTENTOR_PATH . 'includes/loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once GUTENTOR_PATH . 'includes/i18n.php';

		/*Cron*/
		require_once GUTENTOR_PATH . 'includes/class-gutentor-cron.php';

		/*Pro*/
		require_once GUTENTOR_PATH . 'includes/pro/gutentor-pro-init.php';

		/*User data*/
		require_once GUTENTOR_PATH . 'includes/admin/class-gutentor-tracking.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once GUTENTOR_PATH . 'includes/functions/sanitize.php';

		require_once GUTENTOR_PATH . 'includes/functions/functions.php';
		require_once GUTENTOR_PATH . 'includes/hooks.php';

		/* admin */
		require_once GUTENTOR_PATH . 'includes/admin/class-gutentor-helper.php';
		require_once GUTENTOR_PATH . 'includes/admin/class-gutentor-admin.php';
		require_once GUTENTOR_PATH . 'includes/admin/settings/class-admin-settings.php';

		/*Widget*/
		require_once GUTENTOR_PATH . 'includes/sidebar-widget/class-gutentor-wp-block-widget.php';

		/*Meta*/
		require_once GUTENTOR_PATH . 'includes/metabox/meta-box.php';

		/*
		Blocks*/
		/*block-base*/
		require_once GUTENTOR_PATH . 'includes/block-base/class-gutentor-block-base.php';
		require_once GUTENTOR_PATH . 'includes/block-base/class-gutentor-query-elements.php';
		require_once GUTENTOR_PATH . 'includes/block-base/class-gutentor-block-hooks.php';
		require_once GUTENTOR_PATH . 'includes/block-base/class-gutentor-post-modules-hooks.php';
		require_once GUTENTOR_PATH . 'includes/block-base/class-gutentor-term-modules-hooks.php';

		/*Block Templates*/
		require_once GUTENTOR_PATH . 'includes/block-templates/featured/featured.php';

		/*Elements*/
		require_once GUTENTOR_PATH . 'includes/blocks/elements/class-gutentor-e2.php';/* ***Do not remove required for PHP BLOCK*/
		require_once GUTENTOR_PATH . 'includes/blocks/elements/class-gutentor-e3.php';/* ***Do not remove required for Enquee*/
		require_once GUTENTOR_PATH . 'includes/blocks/elements/class-gutentor-e4.php';/* ***Do not remove required for PHP BLOCK*/
		require_once GUTENTOR_PATH . 'includes/blocks/elements/class-gutentor-e9.php';/* ***Do not remove required for PHP BLOCK*/
		require_once GUTENTOR_PATH . 'includes/blocks/elements/class-gutentor-e11.php';/* ***Do not remove required for PHP BLOCK*/

		/*Widgets*/
		require_once GUTENTOR_PATH . 'includes/blocks/widgets/class-gutentor-blog-post.php';/* ***Do not remove required for PHP BLOCK*/
		require_once GUTENTOR_PATH . 'includes/blocks/widgets/class-gutentor-google-map.php';/* ***Do not remove required for PHP BLOCK*/
		require_once GUTENTOR_PATH . 'includes/blocks/widgets/class-gutentor-counter-box.php';/* ***Do not remove required for Enquee*/
		require_once GUTENTOR_PATH . 'includes/blocks/widgets/class-gutentor-progress-bar.php';/* ***Do not remove required for Enquee*/
		require_once GUTENTOR_PATH . 'includes/blocks/widgets/class-gutentor-video-popup.php';/* ***Do not remove required for Enquee*/
		require_once GUTENTOR_PATH . 'includes/blocks/widgets/class-gutentor-gallery.php';/* ***Do not remove required for Enquee*/
		require_once GUTENTOR_PATH . 'includes/blocks/widgets/class-gutentor-image-slider.php';/* ***Do not remove required for Enquee*/

		/*Post Modules*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-p1.php';/* ***Do not remove required for PHP BLOCK*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-p3.php';/* ***Do not remove required for PHP BLOCK*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-p2.php';/* ***Do not remove required for PHP BLOCK*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-p4.php';/* ***Do not remove required for Enquee*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-p5.php';/* ***Do not remove required for PHP BLOCK*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-p6.php';/* ***Do not remove required for PHP BLOCK*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-m0.php';/* ***Do not remove required for Enquee*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-m4.php';/* ***Do not remove required for Enquee*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-m5.php';/* ***Do not remove required for Enquee*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-m10.php';/* ***Do not remove required for Enquee*/

		/*Term Modules*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-t1.php';/* ***Do not remove required for PHP BLOCK*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-t2.php';/* ***Do not remove required for PHP BLOCK*/
		require_once GUTENTOR_PATH . 'includes/blocks/modules/class-gutentor-t3.php';/* ***Do not remove required for PHP BLOCK*/

		/*Rest API*/
		require_once GUTENTOR_PATH . 'includes/tools/class-gutentor-self-api-handler.php';

		require_once GUTENTOR_PATH . 'includes/tools/class-gutentor-extend-api.php';

		/*Advanced Import*/
		require_once GUTENTOR_PATH . 'includes/tools/class-gutentor-advanced-import.php';

		/*Tb Notice*/
		require_once GUTENTOR_PATH . 'includes/tools/class-gutentor-templateberg.php';

		/*Dynamic CSS*/
		require_once GUTENTOR_PATH . 'includes/dynamic-css.php';

		$this->loader = new Gutentor_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Gutentor_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Gutentor_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_hooks() {

		$plugin_hooks = gutentor_hooks();

		/*Hook: Register Scripts*/
		$this->loader->add_action( 'init', $plugin_hooks, 'register_script_style' );

		/*Hook: Both Frontend and Backend assets.*/
		$this->loader->add_action( 'enqueue_block_assets', $plugin_hooks, 'block_assets' );

		/*
		equal or greater than 20 doesnot work
		Changed on 3.2.6
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_hooks, 'load_last_scripts', 999 );
		*/
		$this->loader->add_action( 'wp_footer', $plugin_hooks, 'load_last_scripts', 10 );

		/*Hook: Editor assets.*/
		$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_hooks, 'block_editor_assets', 999 );
		$this->loader->add_action( 'customize_preview_init', $plugin_hooks, 'customize_preview_init', 999 );

		/*Hook: Adding Gutentor Color palatte.*/
		$this->loader->add_action( 'after_setup_theme', $plugin_hooks, 'add_color_palette', 99999 );

		/*Hook Adding Block Categories*/

		if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
			$this->loader->add_filter( 'block_categories_all', $plugin_hooks, 'add_block_categories', 99999 );
		} else {
			$this->loader->add_filter( 'block_categories', $plugin_hooks, 'add_block_categories', 99999 );
		}

		/*Adding Body Class*/
		$this->loader->add_filter( 'body_class', $plugin_hooks, 'add_body_class' );

		/*Adding Admin Body Class*/
		$this->loader->add_filter( 'admin_body_class', $plugin_hooks, 'add_admin_body_class' );

		/*
		Adding page templates
		From 3.0.3 replaced by gutentor\includes\admin\settings\class-admin-settings.php*/
		// $this->loader->add_filter( 'theme_page_templates', $plugin_hooks, 'gutentor_add_page_template' );
		// $this->loader->add_filter( 'page_template', $plugin_hooks, 'gutentor_redirect_page_template' );

		/*Adding style*/
		$this->loader->add_filter( 'wp_kses_allowed_html', $plugin_hooks, 'allow_style_tags' );

		/*Quick fix for acmethemes fontawesome*/
		$this->loader->add_filter( 'gutentor_default_options', $plugin_hooks, 'acmethemes_alter_default_options' );

		$this->loader->add_action( 'widgets_init', $plugin_hooks, 'register_gutentor_reusable_block_selector_widget' );

	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function load_hooks() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Gutentor_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
