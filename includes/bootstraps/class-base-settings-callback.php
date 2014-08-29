<?php

namespace axis_framework\includes\bootstraps;

require_once( AXIS_INC_BOOTSTRAP_PATH . '/settings/settings.php');

use \axis_framework\includes\core;
use \axis_framework\includes\bootstraps\settings;

abstract class Base_Settings_Callback extends core\Singleton {

    protected $loader;

    public function __construct() {
        $this->loader = core\Loader::get_instance();
    }

    public abstract function add_option_page();
    public abstract function register_settings();
    public abstract function add_settings_sections();
    public abstract function add_settings_fields();

}