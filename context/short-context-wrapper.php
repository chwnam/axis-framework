<?php
use axis_framework\context\Empty_Context;


\axis_framework\core\util\check_abspath();


/**
 * Class Short_Context_Wrapper
 */
class Short_Context_Wrapper {

	/** @var array $empty_context \axis_framework\context\Empty_Context container */
	private static $empty_context = array();

	/**
	 * @param \axis_framework\context\Empty_Context $empty_context
	 * @param string                                $tag
	 */
	public static function set_empty_context( Empty_Context $empty_context, $tag ) {

		if( $empty_context instanceof Empty_Context && !empty( $tag ) && is_string( $tag ) ) {
			static::$empty_context[ $tag ] = $empty_context;
		}
	}

	/**
	 * @param string $tag
	 *
	 * @return \axis_framework\context\Empty_Context
	 */
	public static function get_empty_context( $tag ) {

		if( !isset( static::$empty_context[ $tag ] ) ) {
			throw new \BadFunctionCallException( 'empty context is not set!' );
		}

		return static::$empty_context[ $tag ];
	}

	/**
	 * @param string $tag
	 *
	 * @return bool
	 */
	public static function has_empty_context( $tag ) {

		return isset( static::$empty_context[ $tag ] );
	}
}


/**
 * @param string $plugin_main_file
 * @param        $namespace
 * @param        $control_name
 * @param        $control_function
 * @param array  $construct_param
 *
 * @return callable
 */
function axis_control(
	$plugin_main_file,
	$namespace,
	$control_name,
	$control_function,
	array $construct_param = array()
) {

	$context = Short_Context_Wrapper::get_empty_context( $plugin_main_file );

	return $context->control_helper(
		$namespace,
		$control_name,
		$control_function,
		$construct_param,
		FALSE,
		FALSE,
		TRUE
	);
}


/**
 * @param string $plugin_main_file ,
 * @param        $namespace
 * @param        $control_name
 * @param        $control_function
 * @param array  $construct_param
 *
 * @return callable
 */
function axis_ajax_control(
	$plugin_main_file,
	$namespace,
	$control_name,
	$control_function,
	array $construct_param = array()
) {

	$context = Short_Context_Wrapper::get_empty_context( $plugin_main_file );

	return $context->ajax_helper(
		$namespace,
		$control_name,
		$control_function,
		$construct_param
	);
}


/**
 * @param string $plugin_main_file
 * @param        $namespace
 * @param        $control_name
 * @param        $control_function
 * @param array  $construct_param
 *
 * @return callable
 */
function axis_shortcode_control(
	$plugin_main_file,
	$namespace,
	$control_name,
	$control_function,
	array $construct_param = array()
) {

	$context = Short_Context_Wrapper::get_empty_context( $plugin_main_file );

	return $context->shortcode_helper(
		$namespace,
		$control_name,
		$control_function,
		$construct_param
	);
}