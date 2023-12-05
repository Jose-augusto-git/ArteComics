<?php
/**
 * Gutentor Cron
 *
 * @package     Gutentor
 * @subpackage  Classes/Cron
 * @since       3.2.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gutentor_Cron Class
 *
 * This class handles scheduled events
 *
 * @since 3.2.1
 */
class Gutentor_Cron {

	/**
	 * Init WordPress hook
	 *
	 * @since 3.2.1
	 * @see Gutentor_Cron::weekly_events()
	 */
	public function __construct() {
		add_filter( 'cron_schedules', array( $this, 'add_schedules' ) );
		add_action( 'wp', array( $this, 'schedule_events' ) );
	}

	/**
	 * Registers new cron schedules
	 *
	 * @since 3.2.1
	 *
	 * @param array $schedules
	 * @return array
	 */
	public function add_schedules( $schedules = array() ) {
		/*Adds once weekly to the existing schedules*/
		$schedules['weekly'] = array(
			'interval' => 604800,
			'display'  => __( 'Once Weekly', 'gutentor' ),
		);

		return $schedules;
	}

	/**
	 * Schedules our events
	 *
	 * @since 3.2.1
	 * @return void
	 */
	public function schedule_events() {
		$this->weekly_events();
		$this->daily_events();
	}

	/**
	 * Schedule weekly events
	 *
	 * @access private
	 * @since 3.2.1
	 * @return void
	 */
	private function weekly_events() {
		if ( ! wp_next_scheduled( 'gutentor_weekly_scheduled_events' ) ) {
			wp_schedule_event( time(), 'weekly', 'gutentor_weekly_scheduled_events' );
		}
	}

	/**
	 * Schedule daily events
	 *
	 * @access private
	 * @since 3.2.1
	 * @return void
	 */
	private function daily_events() {
		if ( ! wp_next_scheduled( 'gutentor_daily_scheduled_events' ) ) {
			wp_schedule_event( time(), 'daily', 'gutentor_daily_scheduled_events' );
		}
	}

}
$gutentor_cron = new Gutentor_Cron();
