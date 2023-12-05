<?php
/**
 *  Class for handling Block Render
 *
 * @package conditional-blocks
 */
// phpcs:disable  WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class for handling Block Render
 */
class Conditional_Blocks_Render_Block {

	/**
	 * Set the current block content which can be modified by CB.
	 *
	 * @var string
	 */
	private $current_block_content = '';
	/**
	 * Set the results of condition sets for debugging.
	 *
	 * @var array
	 */
	private $logged_results = array();

	/**
	 * Fire off the render block functions.
	 */
	public function init() {
		// Hook in to each block before it's rendered.
		add_filter( 'render_block', array( $this, 'render_block' ), 999, 2 );

		// Register each condition check.
		add_filter( 'conditional_blocks_register_check_lockdown', array( $this, 'lockdown' ), 10, 2 );
		add_filter( 'conditional_blocks_register_check_userLoggedIn', array( $this, 'userLoggedIn' ), 10, 2 );
		add_filter( 'conditional_blocks_register_check_userLoggedOut', array( $this, 'userLoggedOut' ), 10, 2 );

			}

	/**
	 * Filter block content before displaying.
	 *
	 * @param string $block_content the block content.
	 * @param array  $block the whole Gutenberg block object including attributes.
	 * @return string $block_content the new block content.
	 */
	public function render_block( $block_content, $block ) {

		/**
		 * Prevent loading on admin & REST. Otherwise Gutenberg freaks out.
		 */
		if ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return $block_content;
		}

		// Skip empty block.
		if ( empty( $block_content ) ) {
			return $block_content;
		}

		// Reset the current block content and reset logs.
		$this->current_block_content = $block_content;
		$this->logged_results = array();

		$condition_sets = $this->get_condition_sets_from_block( $block );

		if ( empty( $condition_sets ) ) {
			return $block_content;
		}

		$should_render = $this->has_valid_sets( $condition_sets );

		if ( $should_render ) {
			return $this->current_block_content; // modified block.
		}

		return ''; // Don't render block.
	}

	/**
	 * Get the condition sets from the block attributes with backwards compat.
	 *
	 * @param object $block object.
	 * @return array $condition_sets sets of conditions.
	 */
	public function get_condition_sets_from_block( $block ) {

		// Reorder to test first if v3, then v2, then v1.
		$v3_sets = ! empty( $block['attrs']['conditionalBlocks']['sets'] ) ? $block['attrs']['conditionalBlocks']['sets'] : false;

		$v2_conditions = ! empty( $block['attrs']['conditionalBlocks']['conditions'] ) ? $block['attrs']['conditionalBlocks']['conditions'] : false;

		$v1_conditions = ! empty( $block['attrs']['conditionalBlocksAttributes'] ) && ! empty( $block['attrs']['conditionalBlocksAttributes'] ) ? $block['attrs']['conditionalBlocksAttributes'] : false;

		// Check for v3 conditions first.
		if ( $v3_sets !== false ) {
			$condition_sets = $v3_sets;
		} elseif ( $v2_conditions !== false ) { // Then check for v2 conditions.
			$condition_sets = $this->convert_v2_to_v3_condition_sets( $v2_conditions );
		} elseif ( $v1_conditions !== false ) { // Finally, check for v1 conditions.
			$condition_sets = $this->convert_v2_to_v3_condition_sets( $this->convert_v1_to_v2_conditions( $v1_conditions ) );
		} else {
			$condition_sets = false;
		}

		return $condition_sets;
	}

	/**
	 * Check if any of the Condition Sets passes all criteria.
	 *
	 * @param array $condition_sets an array of sets containing their own conditions.
	 * @return boolean true if there is atleast one valid set of conditions.
	 */
	public function has_valid_sets( $condition_sets ) {

		$has_valid_set = false;

		foreach ( $condition_sets as $index => $set ) {

			$conditions = $set['conditions'];

			if ( empty( $conditions ) ) {
				continue;
			}

			$should_render = $this->check_conditions( $conditions );

			if ( $should_render === true ) {
				$has_valid_set = true;
			}
		}

		return $has_valid_set;
	}

	/**
	 * Determine if the current block should be rendered based on applied conditions.
	 *
	 * @param array $conditions all conditions applied to the block.
	 * @return mixed $block_content could be an empty string.
	 */
	public function check_conditions( $conditions ) {

		$results = array(
			'single' => array(), // Default checking of a single condition.
			'stacked' => array(), // Stacked Condition Types with OR logic.
			'aggregated' => array(), // Log the result of each type for debugging later.
			'should_render' => false, // The final decider if the the conditions allow the block to be rendered.
		);

		$results = array();

		foreach ( $conditions as $index => $condition ) {

			$type = ! empty( $condition['type'] ) ? $condition['type'] : false;

			if ( ! $type ) {
				continue;
			}

			// responsiveScreenSizes will modify the existing html. Handle this early.
			if ( $type === 'responsiveScreenSizes' && is_array( $condition['showOn'] ) ) {
				$this->current_block_content = $this->apply_responsive_screensizes( $this->current_block_content, $condition['showOn'] );

				// Early for modified markup.
				$results['single'][] = true;
				continue;
			}

			/**
			 * Trigger the registered check for the condition type.
			 *
			 * Defaults to false.
			 */
			$should_render = apply_filters( 'conditional_blocks_register_check_' . $type, false, $condition );

			/**
			 * Stacked Types will make specific condition types act as 'OR" logic.
			 *
			 * Depreciated: We'll treat this function as depreciated since we have built-in OR support.
			 * Keeping for now for backward compatibility when blocks multiple date related conditions.
			 */
			$stacked_checks = apply_filters( 'conditional_blocks_register_stacked_types', array( 'dateRange', 'dateRecurring' ) );

			$is_stackable = in_array( $type, $stacked_checks, true );

			if ( $is_stackable ) {
				$results['stacked'][ $type ][] = $should_render;
			} else {
				$results['single'][] = $should_render;
			}

			$results['aggregated'][] = array(
				'type' => $type,
				'should_render' => $should_render,
			);
		}

		$should_render = $this->verify_conditions_are_met( $results ); // The outcome for all conditions.
		$results['should_render'] = $should_render;

		// Got it.
		$this->logged_results[] = $results;

		return $should_render;
	}

	/**
	 * Determine if the block should be rendered based on Condition Sets.
	 *
	 * @param array $results results from checking all conditions in a set.
	 * @return bool true or false if results of a set are met.
	 */
	public function verify_conditions_are_met( $results ) {

		// All single checks need to be TRUE otherwise the block content will be hidden.
		if ( ! empty( $results['single'] ) && in_array( false, $results['single'], true ) ) {
			return false;
		}

		// A stacked condition type requires AT LEAST one to be TRUE otherwise the block content will be hidden.
		if ( ! empty( $results['stacked'] ) ) {
			foreach ( $results['stacked'] as $stack_index => $results_array ) {
				if ( ! empty( $results_array ) && ! in_array( true, $results_array, true ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Condition checks.
	 */

	/**
	 * Add device visibility per block.
	 *
	 * @param string $block_content the whole block object.
	 * @param array  $show_on screensizes the block should appear on.
	 * @return string $block_content
	 */
	public function apply_responsive_screensizes( $block_content, $show_on ) {

		$html_classes = '';

		if ( ! in_array( 'showMobileScreen', $show_on, true ) ) {
			$html_classes .= 'conblock-hide-mobile ';
		}

		if ( ! in_array( 'showTabletScreen', $show_on, true ) ) {
			$html_classes .= 'conblock-hide-tablet ';
		}

		if ( ! in_array( 'showDesktopScreen', $show_on, true ) ) {
			$html_classes .= 'conblock-hide-desktop ';
		}

		if ( ! empty( $html_classes ) ) {

			// Replace the first occurrence of class=" without classes.
			// We need the classes to be added directly to the blocks. Wrapping classes can sometimes block full width content.
			$needle = 'class="';

			// Find the first occurrence.
			$find_class_tag = strpos( $block_content, $needle );

			if ( $find_class_tag !== false ) {
				// Our classes.
				$replacement = 'class="' . $html_classes . ' ';
				// Replace it.
				$new_block = substr_replace( $block_content, $replacement, $find_class_tag, strlen( $needle ) );
			} else {
				// Fallback to wrapping classes when block has no existing classes.
				$new_block = '<div class="' . $html_classes . '">' . $block_content . '</div>';
			}

			// Make sure to add frontend CSS to handle the responsive blocks.
			do_action( 'conditional_blocks_enqueue_frontend_responsive_css' );

			return $new_block;
		} else {
			return $block_content;
		}

	}

	/**
	 * Lockdown, this block has been isolated from everyone.
	 *
	 * @param bool  $should_render if condition passed validation.
	 * @param array $condition condition config.
	 * @return bool $should_render.
	 */
	public function lockdown( $should_render, $condition ) {
		return false;
	}

	/**
	 * Check if the user us logged in.
	 *
	 * @param bool  $should_render if condition passed validation.
	 * @param array $condition condition config.
	 * @return bool $should_render.
	 */
	public function userLoggedIn( $should_render, $condition ) {

		$should_render = is_user_logged_in();

		return $should_render;
	}

	/**
	 * Check if the user is logged out.
	 *
	 * @param bool  $should_render if condition passed validation.
	 * @param array $condition condition config.
	 * @return bool $should_render.
	 */
	public function userLoggedOut( $should_render, $condition ) {

		$should_render = ! is_user_logged_in();

		return $should_render;
	}

	
	/**
	 * Convert v2 conditions to v3 sets of conditions.
	 *
	 * Conditional Blocks now uses sets of conditions. V2 is converted to a single set for compat.
	 *
	 * @param array $v2_conditions an array of conditions.
	 * @return array sets of conditions.
	 */
	public function convert_v2_to_v3_condition_sets( $v2_conditions ) {

		if ( empty( $v2_conditions ) ) {
			return false;
		}

		$sets = array();

		// Add our v2 conditions as a new set.
		$sets[] = array(
			'id' => false,
			'type' => 'set',
			'conditions' => $v2_conditions,
		);

		return $sets;
	}

	/**
	 * Convert legacy blocks to match the new condition layout.
	 *
	 * @param [type] $block
	 * @return void
	 */
	public function convert_v1_to_v2_conditions( $legacy_conditions ) {

		$conditions = array();

		$legacy_conditions['userState'] === 'logged-in' ? array_push(
			$conditions,
			array(
				'id' => wp_generate_uuid4(),
				'type' => 'userLoggedIn',
			)
		) : false;

		$legacy_conditions['userState'] === 'logged-out' ? array_push(
			$conditions,
			array(
				'id' => wp_generate_uuid4(),
				'type' => 'userLoggedOut',
			)
		) : false;

		$has_screensize = false;

		$show_on = array(
			'showMobileScreen',
			'showTabletScreen',
			'showDesktopScreen',
		);

		if ( isset( $legacy_conditions['hideOnMobile'] ) && $legacy_conditions['hideOnMobile'] === true ) {
			unset( $show_on[0] );
			$has_screensize = true;
		}

		if ( isset( $legacy_conditions['hideOnTablet'] ) && $legacy_conditions['hideOnTablet'] === true ) {
			unset( $show_on[1] );
			$has_screensize = true;
		}

		if ( isset( $legacy_conditions['hideOnDesktop'] ) && $legacy_conditions['hideOnDesktop'] === true ) {
			unset( $show_on[2] );
			$has_screensize = true;
		}

		if ( $has_screensize ) {
			array_push(
				$conditions,
				array(
					'id' => wp_generate_uuid4(),
					'type' => 'responsiveScreenSizes',
					'showOn' => array_values( $show_on ), // Make sure we only have the values.
				)
			);
		}

		if ( ! empty( $legacy_conditions['userRoles'] ) && is_array( $legacy_conditions['userRoles'] ) ) {
			array_push(
				$conditions,
				array(
					'id' => wp_generate_uuid4(),
					'type' => 'userRoles',
					'allowedRoles' => $legacy_conditions['userRoles'],
				)
			);
		}

		if ( ! empty( $legacy_conditions['httpUserAgent'] ) && is_array( $legacy_conditions['httpUserAgent'] ) ) {
			array_push(
				$conditions,
				array(
					'id' => wp_generate_uuid4(),
					'type' => 'userAgents',
					'allowedAgents' => $legacy_conditions['httpUserAgent'],
				)
			);
		}

		if ( ! empty( $legacy_conditions['httpReferer'] ) ) {
			array_push(
				$conditions,
				array(
					'id' => wp_generate_uuid4(),
					'type' => 'domainReferrers',
					'domainReferrers' => $legacy_conditions['httpReferer'],
				)
			);
		}

		if ( ! empty( $legacy_conditions['dates'] ) && is_array( $legacy_conditions['dates'] ) ) {

			foreach ( $legacy_conditions['dates'] as $date_range ) {

				if ( ! empty( $date_range['start'] ) && ! empty( $date_range['end'] ) ) {
					array_push(
						$conditions,
						array(
							'id' => wp_generate_uuid4(),
							'type' => 'dateRange',
							'startTime' => $date_range['start'],
							'endTime' => $date_range['end'],
							'hasEndDate' => true,
						)
					);
				}
			}
		}

		if ( isset( $legacy_conditions['postMeta']['key'] ) && ! empty( $legacy_conditions['postMeta']['key'] ) ) {

			array_push(
				$conditions,
				array(
					'id' => wp_generate_uuid4(),
					'type' => 'postMeta',
					'metaKey' => isset( $legacy_conditions['postMeta']['key'] ) ? $legacy_conditions['postMeta']['key'] : false,
					'metaOperator' => isset( $legacy_conditions['postMeta']['operator'] ) ? $legacy_conditions['postMeta']['operator'] : false,
					'metaValue' => isset( $legacy_conditions['postMeta']['value'] ) ? $legacy_conditions['postMeta']['value'] : false,
				)
			);
		}

		return $conditions;
	}
}

$class = new Conditional_Blocks_Render_Block();
$class->init();
