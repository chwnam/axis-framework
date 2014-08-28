<?php

/**
 * Definitions
 *
 * Declare all defines here
 */

// RECOMMENDED DEFINITIONS //
define('AXIS_SAMPLE_FULL_NAME',    'Axis Sample Plugin');  // recommendation: be the same as declared in the file header.
define('AXIS_SAMPLE_SHORT_NAME',   'axis_sample');         // may be used as a prefix
define('AXIS_SAMPLE_LANG_CONTEXT', 'axis_sample');         // language context for i13n
define('AXIS_SAMPLE_VERSION',      '0.1');
define('AXIS_SAMPLE_ABSPATH',      dirname(__FILE__));
define('AXIS_SAMPLE_MAIN_FILE',    AXIS_SAMPLE_ABSPATH . '/axis-sample-main.php');  // used in hooks

// THIS IS REALLY IMPORTANT! PLEASE SET IT PROPERLY!!//
define( 'AXIS_FRAMEWORK_PATH', realpath(AXIS_SAMPLE_ABSPATH . '/..'));
require_once( AXIS_FRAMEWORK_PATH . '/defines.php');
