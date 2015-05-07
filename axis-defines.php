<?php

namespace axis_framework;

if ( ! defined( 'AXIS_FRAMEWORK_PATH' ) ) {

	/** Axis Framework's current path */
	define( 'AXIS_FRAMEWORK_PATH', dirname( __FILE__ ) );
}

if ( ! defined( 'AXIS_FRAMEWORK_MAIN_FILE' ) ) {

	/** Axis Framework's main file */
	define( 'AXIS_FRAMEWORK_MAIN_FILE', AXIS_FRAMEWORK_PATH . '/axis-framework.php' );
}

/**
 * bootstrap path
 */
define( 'AXIS_BOOTSTRAP_PATH', AXIS_FRAMEWORK_PATH . '/bootstraps' );

/**
 * control path
 */
define( 'AXIS_CONTROL_PATH', AXIS_FRAMEWORK_PATH . '/controls' );

/**
 * core path
 */
define( 'AXIS_CORE_PATH', AXIS_FRAMEWORK_PATH . '/core' );

/**
 * model path
 */
define( 'AXIS_MODEL_PATH', AXIS_FRAMEWORK_PATH . '/models' );

/**
 * view path
 */
define( 'AXIS_VIEW_PATH', AXIS_FRAMEWORK_PATH . '/views' );

/**
 * context and dispatch path
 */
define( 'AXIS_CONTEXT_PATH', AXIS_FRAMEWORK_PATH . '/contexts' );

// all fundamental files are required.
require_once( AXIS_CORE_PATH . '/utils.php' );
require_once( AXIS_CORE_PATH . '/class-query.php' );
require_once( AXIS_CORE_PATH . '/class-singleton.php' );
require_once( AXIS_CORE_PATH . '/class-loader.php' );
require_once( AXIS_CONTROL_PATH . '/class-base-control.php' );
require_once( AXIS_MODEL_PATH . '/class-base-model.php' );
require_once( AXIS_MODEL_PATH . '/interface-entity.php' );  // entity interface must be earlier than entity model
require_once( AXIS_MODEL_PATH . '/class-base-entity-model.php' );
require_once( AXIS_VIEW_PATH . '/class-base-view.php' );

if( defined('AXIS_USE_BOOTSTRAP') && AXIS_USE_BOOTSTRAP ) {
	require_once( AXIS_BOOTSTRAP_PATH . '/class-base-callback.php' );
	require_once( AXIS_BOOTSTRAP_PATH . '/class-base-admin-post-callback.php' );
	require_once( AXIS_BOOTSTRAP_PATH . '/class-base-ajax-callback.php' );
	require_once( AXIS_BOOTSTRAP_PATH . '/class-base-menu-callback.php' );
	require_once( AXIS_BOOTSTRAP_PATH . '/class-base-plugin-callback.php' );
	require_once( AXIS_BOOTSTRAP_PATH . '/class-base-settings-callback.php' );
	require_once( AXIS_BOOTSTRAP_PATH . '/class-bootstrap.php' );
} else {
	require_once( AXIS_CONTEXT_PATH . '/class-base-context.php' );
	require_once( AXIS_CONTEXT_PATH . '/class-dispatch.php' );
}

/**
 * Version of Axis Framework.
 */
define( 'AXIS_FRAMEWORK_VERSION', \axis_framework\core\utils\axis_version() ); // after utils.php