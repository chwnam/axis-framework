<?php

namespace axis_framework\includes\bootstraps;
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-callback.php');
require_once( AXIS_INC_BOOTSTRAP_PATH . '/settings/settings.php');

use \axis_framework\includes\core;
use \axis_framework\includes\bootstraps\settings;

abstract class Base_Settings_Callback extends Base_Callback {

    protected function __construct() {

        parent::__construct();
    }

    public abstract function add_option_page();
    public abstract function register_settings();
    public abstract function add_settings_sections();
    public abstract function add_settings_fields();

}