<?php

namespace axis_framework\includes\bootstraps;

use \axis_framework\includes\core;


abstract class Base_Settings_Callback extends core\Singleton {

    protected $loader;

    public function __construct() {
        $this->loader = core\Loader::get_instance();
    }

    public abstract function register_settings();
    public abstract function add_settings_sections();
    public abstract function add_settings_fields();

}