<?php
/**
 * @filesource
 */

namespace axis_framework;

if ( ! defined( 'AXIS_FRAMEWORK_PATH' ) ) {

	/** Axis Framework's current path */
	define( 'AXIS_FRAMEWORK_PATH', dirname( __FILE__ ) );
}

/**
 * Version of Axis Framework. Must be identical to the one in the WordPress plugin header comment.
 */
define( 'AXIS_VERSION', '0.10.2000' );

/**
 * first-depth directory.
 */
define( 'AXIS_INC_PATH', AXIS_FRAMEWORK_PATH . '/includes' );

/**
 * second-depth directory: bootstrap path
 */
define( 'AXIS_INC_BOOTSTRAP_PATH', AXIS_INC_PATH . '/bootstraps' );

/**
 * second-depth directory: control path
 */
define( 'AXIS_INC_CONTROL_PATH', AXIS_INC_PATH . '/controls' );

/**
 * second-depth directory: core path
 */
define( 'AXIS_INC_CORE_PATH', AXIS_INC_PATH . '/core' );

/**
 * second-depth directory: model path
 */
define( 'AXIS_INC_MODEL_PATH', AXIS_INC_PATH . '/models' );

/**
 * second-depth directory: view
 */
define( 'AXIS_INC_VIEW_PATH', AXIS_INC_PATH . '/views' );