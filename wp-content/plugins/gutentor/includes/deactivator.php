<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.gutentor.com/
 * @since      1.0.0
 *
 * @package    Gutentor
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Gutentor
 * @author     Gutentor <info@gutentor.com>
 */
class Gutentor_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		update_option( '__gutentor_do_redirect', false );

		global $current_user;
		$user_id                  = $current_user->ID;
		$ignored_notice_partially = get_user_meta( $user_id, 'gutentor_templateberg_notice_calendar', true );

		// Delete partial notice remove data.
		if ( $ignored_notice_partially ) {
			delete_user_meta( $user_id, 'gutentor_templateberg_notice_calendar' );
		}
	}
}
