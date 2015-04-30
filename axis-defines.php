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

// all fundamental files are required.
require_once( AXIS_INC_CORE_PATH . '/utils.php' );
require_once( AXIS_INC_CORE_PATH . '/class-query.php' );
require_once( AXIS_INC_CORE_PATH . '/class-singleton.php' );
require_once( AXIS_INC_CORE_PATH . '/class-loader.php' );
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-admin-post-callback.php' );
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-ajax-callback.php' );
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-menu-callback.php' );
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-plugin-callback.php' );
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-settings-callback.php' );
require_once( AXIS_INC_CONTROL_PATH . '/class-base-control.php' );
require_once( AXIS_INC_MODEL_PATH . '/class-base-model.php' );
require_once( AXIS_INC_MODEL_PATH . '/interface-entity.php' );  // entity interface must be earlier than entity model
require_once( AXIS_INC_MODEL_PATH . '/class-base-entity-model.php' );
require_once( AXIS_INC_VIEW_PATH . '/class-base-view.php' );

/**
 * Version of Axis Framework.
 */
define( 'AXIS_FRAMEWORK_VERSION', \axis_framework\includes\core\utils\axis_version() ); // after utils.php