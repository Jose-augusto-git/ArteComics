<?php
/**
 * Plugin Name: Content Slider Block
 * Description: Display your goal to your visitor in bountiful way with content slider block.
 * Version: 3.1.3
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: content-slider-block
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'CSB_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '3.1.3' );
define( 'CSB_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'CSB_DIR_PATH', plugin_dir_path( __FILE__ ) );

if( !function_exists( 'csb_init' ) ) {
	function csb_init() {
		global $csb_bs;
		require_once( CSB_DIR_PATH . 'bplugins_sdk/init.php' );
		$csb_bs = new BPlugins_SDK( __FILE__ );
	}
	csb_init();
}else {
	global $csb_bs;
	$csb_bs->uninstall_plugin( __FILE__ );
}

// Content Slider
class CSBContentSliderBlock{
	function __construct(){
		add_action( 'init', [$this, 'onInit'] );
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		register_activation_hook( __FILE__, [$this, 'onPluginActivate'] );

		add_filter( 'block_categories_all', [$this, 'blockCategories'] );
	}

	function onPluginActivate(){
		if ( is_plugin_active( 'content-slider-block-pro/plugin.php' ) ){
			deactivate_plugins( 'content-slider-block-pro/plugin.php' );
		}
	}

	function enqueueBlockAssets() {
		wp_enqueue_script( 'swiperJS', CSB_DIR_URL . 'assets/js/swiper.min.js', [], '8.0.7', true );
	}

	function blockCategories( $categories ){
		return array_merge( [[
			'slug'	=> 'CSBlock',
			'title'	=> 'Content Slider Block',
		] ], $categories );
	} // Categories
	
	function onInit() {
		wp_register_style( 'csb-content-slider-block-style', plugins_url( 'dist/style.css', __FILE__ ), [], CSB_VERSION ); // Style
		wp_register_style( 'csb-content-slider-block-editor-style', plugins_url( 'dist/editor.css', __FILE__ ), [ 'csb-content-slider-block-style' ], CSB_VERSION ); // Backend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'csb-content-slider-block-editor-style',
			'style'				=> 'csb-content-slider-block-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'csb-content-slider-block-editor-script', 'content-slider-block', CSB_DIR_PATH . 'languages' );
	}
	
	// Render
	function render( $attributes ){
		extract( $attributes );
		global $csb_bs;

		wp_set_script_translations( 'csb-content-slider-block-script', 'content-slider-block', CSB_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$extraClass = $csb_bs->can_use_premium_feature() ? 'premium' : 'free';
		$blockClassName = "wp-block-csb-content-slider-block $extraClass $className align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='csbContentSlider-<?php echo esc_attr( $cId ); ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'>
			<div class='csbContentSliderStyle'></div>

			<div class='csbSliderWrapper'></div>
		</div>

		<?php return ob_get_clean();
	}
}
new CSBContentSliderBlock;

global $csb_bs;
if( $csb_bs ){
	if( $csb_bs->can_use_premium_feature() ){
		require_once CSB_DIR_PATH . 'inc/pattern.php';
		require_once CSB_DIR_PATH . 'inc/custom-post.php';
	}
}