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
require_once( AXIS_FRAMEWORK_PATH . '/core/utils.php' );
require_once( AXIS_FRAMEWORK_PATH . '/core/class-query.php' );
require_once( AXIS_FRAMEWORK_PATH . '/core/class-singleton.php' );
require_once( AXIS_FRAMEWORK_PATH . '/core/class-loader.php' );
require_once( AXIS_FRAMEWORK_PATH . '/controls/class-base-control.php' );
require_once( AXIS_FRAMEWORK_PATH . '/models/class-base-model.php' );
require_once( AXIS_FRAMEWORK_PATH . '/views/class-base-view.php' );
require_once( AXIS_FRAMEWORK_PATH . '/forms/class-base-form.php' );
require_once( AXIS_FRAMEWORK_PATH . '/contexts/class-base-context.php' );
require_once( AXIS_FRAMEWORK_PATH . '/contexts/class-dispatch.php' );

/**
 * any others will be auto-loaded by callback function
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
					$plural_form = $component . 's';
					$file_name   = "class-{$part}-{$component}.php";
					/** @noinspection PhpIncludeInspection */
					require_once( AXIS_FRAMEWORK_PATH . "/{$plural_form}/{$file_name}" );
				break;
			}
		}
	}
);

/**
 * Version of Axis Framework.
 */
define( 'AXIS_FRAMEWORK_VERSION', \axis_framework\core\utils\axis_version() ); // after utils.php