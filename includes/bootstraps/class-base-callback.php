<?php

namespace axis_framework\includes\bootstraps;

use \axis_framework\includes\core;

class Base_Callback extends core\Singleton {

    protected $loader;

    protected function __construct() {
    }

    public function get_loader() {

        return $this->loader;
    }

    public function set_loader(  core\Loader &$loader ) {

        $this->loader = $loader;
    }
}