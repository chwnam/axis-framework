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

// all fundamental files are required.
require_once( AXIS_FRAMEWORK_PATH . '/core/utils.php' );
require_once( AXIS_FRAMEWORK_PATH . '/core/class-query.php' );
require_once( AXIS_FRAMEWORK_PATH . '/core/class-singleton.php' );
require_once( AXIS_FRAMEWORK_PATH . '/core/class-loader.php' );
require_once( AXIS_FRAMEWORK_PATH . '/controls/class-base-control.php' );
require_once( AXIS_FRAMEWORK_PATH . '/models/class-base-model.php' );
require_once( AXIS_FRAMEWORK_PATH . '/models/interface-entity.php' );  // entity interface must be earlier than entity model
require_once( AXIS_FRAMEWORK_PATH . '/models/class-base-entity-model.php' );
require_once( AXIS_FRAMEWORK_PATH . '/views/class-base-view.php' );

require_once( AXIS_FRAMEWORK_PATH . '/bootstraps/class-base-callback.php' );
require_once( AXIS_FRAMEWORK_PATH . '/bootstraps/class-base-admin-post-callback.php' );
require_once( AXIS_FRAMEWORK_PATH . '/bootstraps/class-base-ajax-callback.php' );
require_once( AXIS_FRAMEWORK_PATH . '/bootstraps/class-base-menu-callback.php' );
require_once( AXIS_FRAMEWORK_PATH . '/bootstraps/class-base-plugin-callback.php' );
require_once( AXIS_FRAMEWORK_PATH . '/bootstraps/class-base-settings-callback.php' );
require_once( AXIS_FRAMEWORK_PATH . '/bootstraps/class-bootstrap.php' );

require_once( AXIS_FRAMEWORK_PATH . '/contexts/class-base-context.php' );
require_once( AXIS_FRAMEWORK_PATH . '/contexts/class-dispatch.php' );

/**
 * Version of Axis Framework.
 */
define( 'AXIS_FRAMEWORK_VERSION', \axis_framework\core\utils\axis_version() ); // after utils.php