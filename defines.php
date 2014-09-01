<?php

namespace axis_framework;

if (!defined('AXIS_FRAMEWORK_PATH')) {
    define('AXIS_FRAMEWORK_PATH', dirname(__FILE__));
}

/* first-depth directories */
define('AXIS_INC_PATH',  AXIS_FRAMEWORK_PATH . '/includes');

/* second-depth directories */
define( 'AXIS_INC_BOOTSTRAP_PATH', AXIS_INC_PATH . '/bootstraps' );
define( 'AXIS_INC_CONTROL_PATH',   AXIS_INC_PATH . '/controls' );
define( 'AXIS_INC_CORE_PATH',      AXIS_INC_PATH . '/core' );
define( 'AXIS_INC_MODEL_PATH',     AXIS_INC_PATH . '/models' );
define( 'AXIS_INC_VIEW_PATH',      AXIS_INC_PATH . '/views' );

require_once(AXIS_INC_BOOTSTRAP_PATH . '/class-bootstrap.php');