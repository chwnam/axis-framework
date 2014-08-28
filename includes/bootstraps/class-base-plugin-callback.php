<?php

namespace axis_framework\includes\bootstraps;

require_once(AXIS_INC_CORE_PATH . '/class-singleton.php');

use \axis_framework\includes\core;


abstract class Base_Plugin_Callback extends core\Singleton {

    abstract public function localize();
    abstract public function on_activated();
    abstract public function on_deactivated();
    abstract public function on_uninstall();

}