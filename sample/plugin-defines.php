<?php

/**
 * Definitions
 *
 * Declare all defines here!
 */

// RECOMMENDED DEFINITIONS //
define( 'AXIS_SAMPLE_FULL_NAME',  'Axis Sample Plugin' );  // recommendation: be the same as declared in the file header.
define( 'AXIS_SAMPLE_SHORT_NAME', 'axis_sample' );         // may be used as a prefix
define( 'AXIS_SAMPLE_VERSION',    '0.1' );
define( 'AXIS_SAMPLE_ABSPATH',    dirname( __FILE__ ) );
define( 'AXIS_SAMPLE_MAIN_FILE',  AXIS_SAMPLE_ABSPATH . '/axis-sample-main.php' );  // used in hooks

define( 'AXIS_SAMPLE_URL',      plugin_dir_url( __FILE__ ) );
define( 'AXIS_SAMPLE_INC_URL',  AXIS_SAMPLE_URL . 'includes' );
define( 'AXIS_SAMPLE_VIEW_URL', AXIS_SAMPLE_INC_URL . '/views' );
define( 'AXIS_SAMPLE_CSS_URL',  AXIS_SAMPLE_VIEW_URL . '/css' );
define( 'AXIS_SAMPLE_IMG_URL',  AXIS_SAMPLE_VIEW_URL . '/img' );
define( 'AXIS_SAMPLE_JS_URL',   AXIS_SAMPLE_VIEW_URL . '/js' );

// define this constant to enable axis_dump_pre() function.
// value not considered.
define( 'AXIS_ENABLE_DUMP_PRE', NULL );

// THIS IS REALLY IMPORTANT! PLEASE SET IT PROPERLY!!//
define( 'AXIS_FRAMEWORK_PATH', realpath( AXIS_SAMPLE_ABSPATH . '/..' ) );
require_once( AXIS_FRAMEWORK_PATH . '/defines.php' );
