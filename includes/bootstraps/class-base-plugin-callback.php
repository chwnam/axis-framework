<?php

namespace axis_framework\includes\bootstraps;
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-callback.php');
use \axis_framework\includes\core;


abstract class Base_Plugin_Callback extends Base_Callback {

    protected function __construct() {

        parent::__construct();
    }

    abstract public function localize();
    abstract public function on_activated();
    abstract public function on_deactivated();
    abstract public function on_uninstall();

}