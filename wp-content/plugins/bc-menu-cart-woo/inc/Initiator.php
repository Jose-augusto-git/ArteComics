<?php



namespace BinaryCarpenter\BC_MNC;
use BinaryCarpenter\BC_MNC\Activation as Activation;
use BinaryCarpenter\BC_MNC\BC_Options;
use BinaryCarpenter\BC_MNC\Options_Form as Options_Form;
use BinaryCarpenter\BC_MNC\Options_Name as Oname;
use BinaryCarpenter\BC_MNC\Cart_Details as Cart_Details;

/*
 * free 1.46
 * pro 2.19
 */

/**
 * Class Initiator
 * @package BinaryCarpenter\BC_MNC
 */
class Initiator {
    private static $instance;

	/**
	 * @return Initiator
	 */
    public static function get_instance()
    {
        if (self::$instance == null)
            self::$instance = new Initiator();

        return self::$instance;
    }


    /**
	 * Construct.
	 */
	public function __construct() {

		add_shortcode('bc_cart_icon', array($this, 'shortcode'));

		$this->languages();
		// load the localisation & classes
//		add_action( 'plugins_loaded', array( $this, 'languages' ), 0 ); // or use init?
		add_filter( 'load_textdomain_mofile', array( $this, 'textdomain_fallback' ), 10, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts_styles_backend') ); // Load backend script

		//this is to save settings via aja
		add_action('wp_ajax_' . Options_Form::BC_OPTION_COMMON_AJAX_ACTION, array( '\BinaryCarpenter\BC_MNC\Options_Form','handle_post_save_options') );





		// add filters to selected menus to add cart item <li>
		add_action( 'init', array( $this, 'filter_nav_menus' ) );

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );

		add_action( 'admin_menu', array( $this, 'add_menu') );

	}



	/**
	 * Add menu page
	 */
	public function add_menu() {

		$core = new Core();
		$core->admin_menu();
		add_submenu_page(
			Core::MENU_SLUG,
			__( Config::PLUGIN_NAME, 'bc-menu-cart-woo' ),
			__( '<img style="width: 14px; height: 14px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAACXBIWXMAAH6MAAB+jAH2GftsAAA4JmlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxMzggNzkuMTU5ODI0LCAyMDE2LzA5LzE0LTAxOjA5OjAxICAgICAgICAiPgogICA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogICAgICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgICAgICAgICB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iCiAgICAgICAgICAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIKICAgICAgICAgICAgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIgogICAgICAgICAgICB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIKICAgICAgICAgICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgICAgICAgICAgeG1sbnM6dGlmZj0iaHR0cDovL25zLmFkb2JlLmNvbS90aWZmLzEuMC8iCiAgICAgICAgICAgIHhtbG5zOmV4aWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20vZXhpZi8xLjAvIj4KICAgICAgICAgPHhtcDpDcmVhdG9yVG9vbD5BZG9iZSBQaG90b3Nob3AgQ0MgMjAxNyAoV2luZG93cyk8L3htcDpDcmVhdG9yVG9vbD4KICAgICAgICAgPHhtcDpDcmVhdGVEYXRlPjIwMTktMDUtMDlUMDk6MDE6MTgrMDc6MDA8L3htcDpDcmVhdGVEYXRlPgogICAgICAgICA8eG1wOk1vZGlmeURhdGU+MjAxOS0wNS0wOVQwOTowMTo0NyswNzowMDwveG1wOk1vZGlmeURhdGU+CiAgICAgICAgIDx4bXA6TWV0YWRhdGFEYXRlPjIwMTktMDUtMDlUMDk6MDE6NDcrMDc6MDA8L3htcDpNZXRhZGF0YURhdGU+CiAgICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2UvcG5nPC9kYzpmb3JtYXQ+CiAgICAgICAgIDxwaG90b3Nob3A6Q29sb3JNb2RlPjM8L3Bob3Rvc2hvcDpDb2xvck1vZGU+CiAgICAgICAgIDx4bXBNTTpJbnN0YW5jZUlEPnhtcC5paWQ6MDFkNjEwMDUtMjYyNi1kZDRiLWE5ZTMtZWZjOTg3OWE2NjU1PC94bXBNTTpJbnN0YW5jZUlEPgogICAgICAgICA8eG1wTU06RG9jdW1lbnRJRD54bXAuZGlkOjAxZDYxMDA1LTI2MjYtZGQ0Yi1hOWUzLWVmYzk4NzlhNjY1NTwveG1wTU06RG9jdW1lbnRJRD4KICAgICAgICAgPHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD54bXAuZGlkOjAxZDYxMDA1LTI2MjYtZGQ0Yi1hOWUzLWVmYzk4NzlhNjY1NTwveG1wTU06T3JpZ2luYWxEb2N1bWVudElEPgogICAgICAgICA8eG1wTU06SGlzdG9yeT4KICAgICAgICAgICAgPHJkZjpTZXE+CiAgICAgICAgICAgICAgIDxyZGY6bGkgcmRmOnBhcnNlVHlwZT0iUmVzb3VyY2UiPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6YWN0aW9uPmNyZWF0ZWQ8L3N0RXZ0OmFjdGlvbj4KICAgICAgICAgICAgICAgICAgPHN0RXZ0Omluc3RhbmNlSUQ+eG1wLmlpZDowMWQ2MTAwNS0yNjI2LWRkNGItYTllMy1lZmM5ODc5YTY2NTU8L3N0RXZ0Omluc3RhbmNlSUQ+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDp3aGVuPjIwMTktMDUtMDlUMDk6MDE6MTgrMDc6MDA8L3N0RXZ0OndoZW4+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDpzb2Z0d2FyZUFnZW50PkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE3IChXaW5kb3dzKTwvc3RFdnQ6c29mdHdhcmVBZ2VudD4KICAgICAgICAgICAgICAgPC9yZGY6bGk+CiAgICAgICAgICAgIDwvcmRmOlNlcT4KICAgICAgICAgPC94bXBNTTpIaXN0b3J5PgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICAgICA8dGlmZjpYUmVzb2x1dGlvbj44MjI4NTgwLzEwMDAwPC90aWZmOlhSZXNvbHV0aW9uPgogICAgICAgICA8dGlmZjpZUmVzb2x1dGlvbj44MjI4NTgwLzEwMDAwPC90aWZmOllSZXNvbHV0aW9uPgogICAgICAgICA8dGlmZjpSZXNvbHV0aW9uVW5pdD4yPC90aWZmOlJlc29sdXRpb25Vbml0PgogICAgICAgICA8ZXhpZjpDb2xvclNwYWNlPjY1NTM1PC9leGlmOkNvbG9yU3BhY2U+CiAgICAgICAgIDxleGlmOlBpeGVsWERpbWVuc2lvbj42NDwvZXhpZjpQaXhlbFhEaW1lbnNpb24+CiAgICAgICAgIDxleGlmOlBpeGVsWURpbWVuc2lvbj42NDwvZXhpZjpQaXhlbFlEaW1lbnNpb24+CiAgICAgIDwvcmRmOkRlc2NyaXB0aW9uPgogICA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAg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" > ' . Config::PLUGIN_MENU_NAME, 'bc-menu-cart-woo' ),
			'manage_options',
			Config::PLUGIN_SLUG,
			array('\BinaryCarpenter\BC_MNC\Main', 'ui')
		);

	}

	public function action_links($links)
	{
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=bc_menu_bar_cart' ) . '">' . __( 'Get started', 'bc-menu-cart-woo' ) . '</a>';
		$custom_links[] = '<a target="_blank" href="https://tickets.binarycarpenter.com/open.php">' . __( 'Supports', 'bc-menu-cart-woo' ) . '</a>';
		if (Config::IS_FREE)
			$custom_links[] = '<a target="_blank" href="https://www.binarycarpenter.com/app/bc-menu-cart-icon-plugin/">' . __( 'Get pro','bc-menu-cart-woo' ) . '</a>';
		return array_merge( $custom_links, $links );
	}




	public static function product_cart_change_amount()
	{
	    if (!function_exists('WC'))
	        return;


		//remove the requested product from cart
		foreach (\WC()->cart->get_cart() as $cart_item_key => $cart_item) {

			$product_id = intval($_POST['product_id']);
			$product_new_amount = intval($_POST['new_amount']);
			$passed_cart_item_key = sanitize_text_field($_POST['cart_item_key']);
			if($cart_item['product_id'] == $product_id && $cart_item_key ==  $passed_cart_item_key)
			{
				if ($product_new_amount <= 0)
					\WC()->cart->remove_cart_item($cart_item_key);
				else
				{
					\WC()->cart->set_quantity($cart_item_key, $product_new_amount, true);
				}

			}
		}

		$design_option_id = intval($_POST['cart_design_id']);


		// Fragments and mini cart are returned
		$data = array(
			'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
					'.bc-mnc__style-'.$design_option_id => Cart_Display::generate_menu_item_html($design_option_id, false),
					'div.bc-mnc__cart-total' => Cart_Display::generate_cart_total(),
//                'a.bc-mnc__cart-link.bc-mnc__style-'.$design_option_id => Cart_Display::generate_cart_icon_and_circle_item_count($design_options),
					'div.bc-mnc__cart-details--cart-total__amount' => Cart_Display::generate_cart_total(),
					'.bc-mnc__cart-details[data-option-id=\''.$design_option_id.'\'] section' => Cart_Details::generate_cart_items_list( new BC_Options(Config::OPTION_NAME, $design_option_id))
				)
			),
			'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', \WC()->cart->get_cart_for_session() ? md5( json_encode( \WC()->cart->get_cart_for_session() ) ) : '', \WC()->cart->get_cart_for_session() )
		);

		wp_send_json( $data );

		die();
	}

	public function shortcode($atts)
	{
		$atts = shortcode_atts(array(
			'id' => 0
		), $atts, 'bc_cart_icon');

		if ($atts['id'] == 0)
			return "";
        error_log('Generating shortcode on cart');
		return Cart_Display::generate_menu_item_html($atts['id'], 'div');
	}

	public static function remove_item_from_cart()
	{
		//remove the requested product from cart
		foreach (\WC()->cart->get_cart() as $cart_item_key => $cart_item)
		{

			$product_id = intval($_POST['product_id']);
			$passed_cart_item_key = sanitize_text_field($_POST['cart_item_key']);
			if($cart_item['product_id'] == $product_id && $cart_item_key ==  $passed_cart_item_key)
			{
				\WC()->cart->remove_cart_item($cart_item_key);
			}
		}

		$design_option_id = intval($_POST['cart_design_id']);


		// Fragments and mini cart are returned
		$data = array(
			'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
					'.bc-mnc__style-'.$design_option_id => Cart_Display::generate_menu_item_html($design_option_id, false),
					'div.bc-mnc__cart-total' => Cart_Display::generate_cart_total(),
//                'a.bc-mnc__cart-link.bc-mnc__style-'.$design_option_id => Cart_Display::generate_cart_icon_and_circle_item_count($design_options),
					'div.bc-mnc__cart-details--cart-total__amount' => Cart_Display::generate_cart_total(),
					'.bc-mnc__cart-details[data-option-id=\''.$design_option_id.'\'] section' => Cart_Details::generate_cart_items_list( new BC_Options(Config::OPTION_NAME, $design_option_id))
				)
			),
			'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', \WC()->cart->get_cart_for_session() ? md5( json_encode( \WC()->cart->get_cart_for_session() ) ) : '', \WC()->cart->get_cart_for_session() )
		);

		wp_send_json( $data );

		die();
	}

	/**
	 * Get all the linked menu options and print the fragments accordingly
	 * @param $fragments
	 * @return mixed
	 */
	public static function update_cart_fragment_ajax($fragments)
	{
	    error_log('CART UPDATED--->>>');
		//get the design options that has menu attached to them
//		$active_design_options = Cart_Display::get_designs_id_that_have_menu_linked();

        $all_cart_designs =  BC_Options::get_all_options(Config::OPTION_NAME)->posts;
        $active_design_options = array_map(function($post){
            return $post->ID;
        }, $all_cart_designs);
//		if (count($active_design_options) == 0) {
//            return $fragments;
//        }


		foreach ($active_design_options as $design_option_id)
		{
			$design_option = new BC_Options(Config::OPTION_NAME, $design_option_id);
			$fragments['a.bc-mnc__style-' . $design_option_id] = Cart_Display::generate_cart_a($design_option);
//            $fragments['a.bc-mnc__style-' . $design_option_id] = Cart_Display::generate_menu_item_html($design_option_id, 'li');
			$fragments['div.bc-mnc__cart-total'] = Cart_Display::generate_cart_total();
			$fragments['.bc-mnc__cart-details[data-option-id=\''.$design_option_id.'\'] section'] = Cart_Details::generate_cart_items_list( $design_option);

		}

		return $fragments;

	}



	/**
	 * Load translations.
	 */
	public function languages() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'bc-menu-cart-woo' );
		$dir    = trailingslashit( WP_LANG_DIR );

		/**
		 * Frontend/global Locale. Looks in:
		 *
		 * 		- WP_LANG_DIR/wp-menu-cart/wp-menu-cart-LOCALE.mo
		 * 	 	- WP_LANG_DIR/plugins/wp-menu-cart-LOCALE.mo
		 * 	 	- wp-menu-cart/languages/wp-menu-cart-LOCALE.mo (which if not found falls back to:)
		 * 	 	- WP_LANG_DIR/plugins/wp-menu-cart-LOCALE.mo
		 */
		load_textdomain( 'bc-menu-cart-woo', $dir . 'wp-menu-cart/wp-menu-cart-' . $locale . '.mo' );
		load_textdomain( 'bc-menu-cart-woo', $dir . 'plugins/wp-menu-cart-' . $locale . '.mo' );
		load_plugin_textdomain( 'bc-menu-cart-woo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Maintain textdomain compatibility between main plugin (wp-menu-cart) and WooCommerce version (woocommerce-menu-bar-cart)
	 * so that wordpress.org language packs can be used for both
	 */
	public function textdomain_fallback( $mofile, $textdomain ) {
		$main_domain = 'bc-menu-cart-woo';
		$wc_domain = 'woocommerce-menu-bar-cart';

		// check if this is filtering the mofile for this plugin
		if ( $textdomain === $main_domain ) {
			$wc_mofile = str_replace( "{$textdomain}-", "{$wc_domain}-", $mofile ); // with trailing dash to target file and not folder
			if ( file_exists( $wc_mofile ) ) {
				// we have a wc override - use it
				return $wc_mofile;
			}
		}

		return $mofile;
	}



	//load css,
	public function load_scripts_styles_backend()
	{
		global $current_screen;
		if (stripos($current_screen->base, 'bc_menu_bar_cart') !== false)
		{
			wp_enqueue_media();
			wp_enqueue_script( Config::PLUGIN_SLUG . '_admin_scripts', plugins_url('../bundle/js/backend-bundle.min.js', __FILE__), array('jquery', 'underscore'), false,true);
			//enque
			wp_enqueue_style( Config::PLUGIN_SLUG . '_admin_styles', plugins_url('../bundle/css/backend.css', __FILE__), array());
		}
	}

	/**
	 * Load CSS
	 */
	public static function load_scripts_styles_frontend() {
		wp_register_script(
			'bc_menu_bar_cart_frontend',
			plugins_url( '../bundle/js/frontend-bundle.min.js' , __FILE__ ),
			array( 'jquery', 'underscore' ),
      false,
  		true	
		);
		wp_enqueue_script(
			'bc_menu_bar_cart_frontend'
		);

		wp_register_style( Config::PLUGIN_COMMON_HANDLER . '-frontend', plugins_url('../bundle/css/frontend.css', __FILE__), array(), '', 'all' );
		wp_enqueue_style( Config::PLUGIN_COMMON_HANDLER . '-frontend');


		$theme_cart_options = BC_Options::get_all_options('bc_menu_cart_theme_cart_icon')->posts;
		if (count($theme_cart_options) > 0)
		{
			$theme_cart_option = new BC_Options('bc_menu_cart_theme_cart_icon', $theme_cart_options[0]->ID);

			//check if hiding theme cart is checked
			if ($theme_cart_option->get_bool(Oname::HIDE_THEME_CART))
			{

				$theme_cart_css_selector = $theme_cart_option->get_string(Oname::THEME_CART_CSS_SELECTOR, '', true);

				if ($theme_cart_css_selector !== '')
					wp_add_inline_style( Config::PLUGIN_COMMON_HANDLER . '-frontend' , '.et-cart-info ,.site-header-cart ,' . $theme_cart_css_selector . ' { display:none !important; }' );
				else
					wp_add_inline_style( Config::PLUGIN_COMMON_HANDLER . '-frontend' , '.et-cart-info ,.site-header-cart { display:none !important; }' );
			}

		}



		//Load Stylesheet if twentytwelve is active
		if ( wp_get_theme() == 'Twenty Twelve' ) {
			wp_register_style( 'bc_menu_bar_cart-twentytwelve', plugins_url( '/css/bc_menu_bar_cart-twentytwelve.css', __FILE__ ), array(), '', 'all' );
			wp_enqueue_style( 'bc_menu_bar_cart-twentytwelve' );
		}

		//Load Stylesheet if twentyfourteen is active
		if ( wp_get_theme() == 'Twenty Fourteen' ) {
			wp_register_style( 'bc_menu_bar_cart-twentyfourteen', plugins_url( '/css/bc_menu_bar_cart-twentyfourteen.css', __FILE__ ), array(), '', 'all' );
			wp_enqueue_style( 'bc_menu_bar_cart-twentyfourteen' );
		}


	}




	/**
	 * Add Menu Cart to menu
	 * This is a filter function that hooked into wp_nav_menu_
	 *
	 * @return string menu items + Menu Cart item
	 */
	public function add_cart_icon_to_menu($items , $args) {
		$menu_slug = $args->menu->slug;


		$linked_options = BC_Options::get_all_options('bc_menu_cart_linked_menu')->posts;

		if (count($linked_options) == 0 || $linked_options[0]->ID ===   0)
			return $items;



		//Only get the first option, since there is only one item linked to one menu
		$options = new BC_Options(Oname::MENU_CART_LINK_MENUS, $linked_options[0]->ID);

		//now, check if this menu has an option attached to it. The id of the design option is the meta_value of
		//a meta which has $menu_slug as key

		if (!($options->get_int($menu_slug) > 0))
			return $items;


		$item_html = Cart_Display::generate_menu_item_html($options->get_int($menu_slug));

//        $item_html = '';
		$all_html = $items . $item_html;


		return $all_html;
	}



	/**
	 * Add filters to selected menus to add cart item <li>
	 */
	public function filter_nav_menus() {

		//get the linked menu option
		$linked_options = BC_Options::get_all_options('bc_menu_cart_linked_menu')->posts;

		if (count($linked_options) == 0)
			return;


		$menus = Helpers::get_menu_array();

		if(count($menus) == 0)
			return;

		//get the BC_Options object
		//get only the first item since we only store the options in one post
		$linked_menu_options = new BC_Options('bc_menu_cart_linked_menu', $linked_options[0]->ID);


		//add filter to menu that has design attached to it
		foreach ($menus as $menu)
		{
			if ($linked_menu_options->get_int($menu['slug']) > 0) {
				add_filter( 'wp_nav_menu_' . $menu['slug'] . '_items', array( $this, 'add_cart_icon_to_menu' ) , PHP_INT_MAX, 2 );
			}
				
		}
	}



}


add_action('plugin_loaded', function(){
    Initiator::get_instance();
});
add_filter( 'woocommerce_add_to_cart_fragments', ['BinaryCarpenter\BC_MNC\Initiator','update_cart_fragment_ajax'] );
//remove one item from cart
add_action('wp_ajax_bc_menu_cart_product_change_amount', ['BinaryCarpenter\BC_MNC\Initiator','product_cart_change_amount']);
add_action('wp_ajax_nopriv_bc_menu_cart_product_change_amount', ['BinaryCarpenter\BC_MNC\Initiator','product_cart_change_amount']);
// enqueue scripts & ajax
add_action( 'wp_enqueue_scripts', ['BinaryCarpenter\BC_MNC\Initiator','load_scripts_styles_frontend']); // Load scripts

add_action('wp_ajax_bc_menu_cart_remove_product', ['BinaryCarpenter\BC_MNC\Initiator','remove_item_from_cart']);
add_action('wp_ajax_nopriv_bc_menu_cart_remove_product', ['BinaryCarpenter\BC_MNC\Initiator','remove_item_from_cart']);

add_action('wp_ajax_bc_menu_cart_activate_license', array(Activation::class, 'activation_callback'));