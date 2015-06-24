<?php

namespace axis_framework;

if ( !defined( 'AXIS_FRAMEWORK_PATH' ) ) {

	/** Axis Framework's current path */
	define( 'AXIS_FRAMEWORK_PATH', dirname( __FILE__ ) );
}

if ( !defined( 'AXIS_FRAMEWORK_MAIN_FILE' ) ) {

	/** Axis Framework's main file */
	define( 'AXIS_FRAMEWORK_MAIN_FILE', AXIS_FRAMEWORK_PATH . '/axis-framework.php' );
}

// all fundamental files are required.
require_once( AXIS_FRAMEWORK_PATH . '/core/util.php' );
require_once( AXIS_FRAMEWORK_PATH . '/core/class-query.php' );
require_once( AXIS_FRAMEWORK_PATH . '/core/class-singleton.php' );
require_once( AXIS_FRAMEWORK_PATH . '/core/class-loader.php' );
require_once( AXIS_FRAMEWORK_PATH . '/control/class-base-control.php' );
require_once( AXIS_FRAMEWORK_PATH . '/model/class-base-model.php' );
require_once( AXIS_FRAMEWORK_PATH . '/view/class-base-view.php' );
require_once( AXIS_FRAMEWORK_PATH . '/form/class-base-form.php' );
require_once( AXIS_FRAMEWORK_PATH . '/context/class-base-context.php' );
require_once( AXIS_FRAMEWORK_PATH . '/context/class-dispatch.php' );

/**
 * any other axis framework's classes inherited from above will be auto-loaded by this callback function
 * @since 0.20.1000
 */
spl_autoload_register(
	function( $class_name ) {
		// only think about axis_framework namespace
		if( 0 !== strpos( $class_name , 'axis_framework' ) ) {
			return;
		}
		$cls     = substr( $class_name, strrpos( $class_name, '\\' ) + 1 );
		$matches = NULL;
		if( preg_match( '/^(.+)_(.+)$/', $cls, $matches ) ) {
			$part      = str_replace( '_', '-', strtolower( $matches[1] ) );
			$component = strtolower( $matches[2] );
			switch( $component ) {
				case 'context':
				case 'control':
				case 'form':
				case 'model':
				case 'view':
					$file_name   = "class-{$part}-{$component}.php";
					/** @noinspection PhpIncludeInspection */
					require_once( AXIS_FRAMEWORK_PATH . "/{$component}/{$file_name}" );
				break;
			}
		}
	}
);

/**
 * Version of Axis Framework.
 */
define( 'AXIS_FRAMEWORK_VERSION', \axis_framework\core\util\axis_version() ); // after util.php