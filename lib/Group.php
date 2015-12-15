<?php

namespace logoscon\ACF;

/**
* Abstract class that defines common Advanced Custom Fields
* registration functionality.
*
* @since  0.1.0
* @author log.OSCON, Lda. <engenharia@log.pt>
*/
abstract class Group {

	/**
	 * Group key.
	 *
	 * @since 	0.1.0
	 * @access  protected
	 * @var     string
	 */
	protected $key = '';

	/**
	 * Post types associated with the group.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @var 	array
	 */
	protected $post_types = array();

	/**
	 * User roles allowed to interact with the group.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @var 	array
	 */
	protected $user_roles = array();

	/**
	 * Options page associated with the group.
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    string
	 */
	protected $options_page = '';

	/**
	 * ACF group registration handler constructor.
	 */
	function __construct( $post_types = array() ) {
		$this->post_types = $post_types;
	}

	/**
	 * Group registration wrapper.
	 *
	 * Checks that `acf_add_local_field_group()` exists prior to invoking the
	 * protected subclass `_register()` method.
	 *
	 * @since   0.1.0
	 */
	final public function register() {
		if ( function_exists( '\acf_add_local_field_group' ) ) {
			$this->_register();
		}
	}

	/**
	 * Register the ACF group.
	 *
	 * @since    0.1.0
	 */
	abstract protected function _register();

	/**
	 * Get the ACF group key.
	 *
	 * @return  string The ACF group key.
	 *
	 * @since   0.1.0
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * Get the associated post types.
	 *
	 * @return  array The post types associated with this field group.
	 *
	 * @since   0.1.0
	 */
	public function get_post_types() {
		return $this->post_types;
	}

	/**
	 * Set the associated post types.
	 *
	 * @param  string The post types associated with this field group.
	 *
	 * @since  0.1.0
	 */
	public function set_post_types( $post_types ) {
		$this->post_types = $post_types;
	}

	/**
	 * Get the associated option page.
	 *
	 * @return  array The option page associated with this field group.
	 *
	 * @since   0.1.0
	 */
	public function get_options_page() {
		return $this->options_page;
	}

	/**
	 * Set the associated option page.
	 *
	 * @param  string The option page associated with this field group.
	 *
	 * @since  0.1.0
	 */
	public function set_options_page( $options_page ) {
		$this->options_page = $options_page;
	}

	/**
	 * Get the allowed user roles.
	 *
	 * @return  array The user roles allowed to interact with this field group.
	 *
	 * @since   0.1.0
	 */
	public function get_user_roles() {
		return $this->user_roles;
	}

	/**
	 * Set the associated post types.
	 *
	 * @param  string The user roles allowed to interact with this field group.
	 *
	 * @since  0.1.0
	 */
	public function set_user_roles( $user_roles ) {
		$this->user_roles = $user_roles;
	}

	/**
	 * Field builder for tabs.
	 *
	 * There's nothing wrong with slapping ACF's export code straight
	 * into the `register()` method, this method only aims to make things
	 * a little more readable and maintainable.
	 *
	 * @param  string $key   Unique tab key.
	 * @param  string $label Tab label.
	 *
	 * @return array         Field configuration.
	 *
	 * @since   0.1.0
	 */
	protected function _field_tab( $key, $label ) {
		return array(
			'key'               => $key,
			'label'             => $label,
			'type'              => 'tab',
			'conditional_logic' => 0,
		);
	}

	/**
	 * Location rule builder for equality checks.
	 *
	 * @param  string $param Parameter.
	 * @param  mixed  $value Value required for validation.
	 *
	 * @return array         Location rule.
	 *
	 * @since   0.1.0
	 */
	protected function _location_is( $param, $value ) {
		return array(
			'param'    => $param,
			'operator' => '==',
			'value'    => $value,
		);
	}

	/**
	 * Location rule builder for inclusion checks.
	 *
	 * Please note that this is a custom operator implemented by the
	 * `Rule\Operator\In` class and is not default behavior supported by ACF on
	 * the dashboard.
	 *
	 * Invoke `Rule\Operator\In::register()` to ensure ACF honours this rule.
	 *
	 * @param  string $param Parameter.
	 * @param  array  $value Set of allowed values.
	 *
	 * @return array         Location rule.
	 *
	 * @since   0.1.0
	 */
	protected function _location_in( $param, $value ) {
		return array(
			'param'    => $param,
			'operator' => 'IN',
			'value'    => $value,
		);
	}


	/**
	 * Taxonomy term for a specific language.
	 *
	 * @param  string $default Default taxonomy term.
	 *
	 * @return string          Default or translated taxonomy term.
	 *
	 * @since   0.2.0
	 */
	protected function _get_taxonomy( $default ) {

		if ( ! function_exists( 'icl_object_id' ) ) {
			return $default;
		}

		$data     = explode( ':', $default );
		$taxonomy = 'category';
		$term     = '';

		// Check data
		if ( isset( $data[1] ) ) {
			$taxonomy = $data[0];
			$term     = $data[1];
		}

		// Get registered taxonomy term data
		$term = \get_term_by( 'slug', $term, $taxonomy );

		// Checks if term has translation for the current language
		if ( $term && $term_id = \icl_object_id( $term->term_id, $taxonomy, false, ICL_LANGUAGE_CODE ) ) {

			$icl_term = \get_term_by( 'id', $term_id, $taxonomy );

			if ( $icl_term ) {
				return "{$taxonomy}:{$icl_term->slug}";
			}
		}

		return $default;
	}

}
