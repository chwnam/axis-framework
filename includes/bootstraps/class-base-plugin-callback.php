<?php

namespace axis_framework\includes\bootstraps;

use \axis_framework\includes\core;


abstract class Base_Plugin_Callback extends core\Singleton {

    protected $loader;

    public function __construct() {
        $this->loader = core\Loader::get_instance();
    }

    abstract public function localize();
    abstract public function on_activated();
    abstract public function on_deactivated();
    abstract public function on_uninstall();

}