<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.gutentor.com/
 * @since      1.0.0
 *
 * @package    Gutentor
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Gutentor
 * @author     Gutentor <info@gutentor.com>
 */
class Gutentor_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		update_option( '__gutentor_do_redirect', true );

        gutentor_add_installed_time();
	}
}
