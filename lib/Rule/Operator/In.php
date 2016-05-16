<?php

namespace logoscon\ACF\Rule\Operator;

/**
 * Implements the `IN` location rule operator on Advanced Custom Fields.
 *
 * @since  1.0.0
 */
class In {

	/**
	 * Registers the filter hooks that support the `IN` operator.
	 *
	 * @param integer $priority Hook priority (defaults to 20).
	 *
	 * @uses \add_filter()
	 *
	 * @since  1.0.0
	 */
	public static function register( $priority = 20 ) {
		\add_filter( 'acf/location/rule_match/post_type',
		    array( __CLASS__, 'rule_match_post_type' ), $priority, 3 );

		\add_filter( 'acf/location/rule_match/current_user_role',
		    array( __CLASS__, 'rule_match_user_type' ), $priority, 3 );

		\add_filter( 'acf/location/rule_match/page_template',
			array( __CLASS__, 'rule_match_page_template' ), $priority, 3 );
	}

	/**
	 * Deregisters the filter hooks that support the `IN` operator.
	 *
	 * @param integer $priority Hook priority (defaults to 20).
	 *
	 * @uses \remove_filter()
	 *
	 * @since  1.0.0
	 */
	public static function unregister( $priority = 20 ) {
		\remove_filter( 'acf/location/rule_match/post_type',
		    array( __CLASS__, 'rule_match_post_type' ), $priority );

		\remove_filter( 'acf/location/rule_match/current_user_role',
		    array( __CLASS__, 'rule_match_user_type' ), $priority );
	}

	/**
	 * Filters basic post type rule matches by ACF.
	 *
	 * Adds support to the following new operator:
	 *
	 * - `IN`: Checks whether the post type is included in a set of possible values.
	 *
	 * @param  boolean $match   Whether the post type matches the rule.
	 * @param  array   $rule    ACF location rule.
	 * @param  array   $options Options containing the value to match.
	 *
	 * @return boolean          Whether the user type matches the rule.
	 *
	 * @since  1.0.0
	 *
	 * @uses \get_post_type()
	 */
	public static function rule_match_post_type( $match, $rule, $options ) {

		$post_type = $options['post_type'];
		$post_id   = $options['post_id'];

		if ( ! $post_type && ! $post_id ) {
			return false;
		}

		if ( ! $post_type ) {
			$post_type = \get_post_type( $post_id );
		}

		if ( $rule['operator'] === 'IN' ) {
			$values = is_array( $rule['value'] ) ? $rule['value'] : array( $rule['value'] );
			$match  = in_array( $post_type, $values );
		}

		return $match;
	}

	/**
	 * Filters basic user type rule matches by ACF.
	 *
	 * Adds support to the following new operator:
	 *
	 * - `IN`: Checks whether the user type is included in a set of possible values.
	 *
	 * @param  boolean $match   Whether the post type matches the rule.
	 * @param  array   $rule    ACF location rule.
	 * @param  array   $options Options containing the value to match.
	 *
	 * @return boolean          Whether the user type matches the rule.
	 *
	 * @since  1.0.0
	 *
	 * @uses \is_super_admin()
	 * @uses \is_user_logged_in()
	 * @uses \wp_get_current_user()
	 */
	public static function rule_match_user_type( $match, $rule, $options ) {

		if ( ! \is_user_logged_in() ) {
			return false;
		}

		$user = \wp_get_current_user();

		if ( $rule['operator'] === 'IN' ) {

			$values = is_array( $rule['value'] ) ? $rule['value'] : array( $rule['value'] );

			if ( in_array( 'super_admin', $values ) && \is_super_admin( $user->ID ) ) {
				return true;
			}

			$intersection = array_intersect( $values, $user->roles );
			$match        = ! empty( $intersection );
		}

		return $match;
	}

	/**
	 * Filters basic page template rule matches by ACF.
	 *
	 * Adds support to the following new operator:
	 *
	 * - `IN`: Checks whether the page template is included in a set of possible values.
	 *
	 * @param  boolean $match   Whether the page template matches the rule.
	 * @param  array   $rule    ACF location rule.
	 * @param  array   $options Options containing the value to match.
	 *
	 * @return boolean          Whether the page template matches the rule.
	 *
	 * @since  1.1.0
	 *
	 * @uses \get_post_type()
	 */
	public static function rule_match_page_template( $match, $rule, $options ) {

		$post_type = $options['post_type'];
		$post_id   = $options['post_id'];

		if ( ! $post_type && ! $post_id ) {
			return false;
		}

		if ( ! $post_type ) {
			$post_type = \get_post_type( $post_id );
		}

		if ( $post_type !== 'page' ) {
			return false;
		}

		$template = \get_post_meta( $post_id, '_wp_page_template', true );

		if ( ! $template ) {
			return false;
		}

		if ( $rule['operator'] === 'IN' ) {
			$values = is_array( $rule['value'] ) ? $rule['value'] : array( $rule['value'] );
			$match  = in_array( $template, $values );
		}

		return $match;
	}
}
