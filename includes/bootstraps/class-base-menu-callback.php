<?php

namespace axis_framework\includes\bootstraps;

require_once(AXIS_INC_CORE_PATH . '/class-singleton.php');

use \axis_framework\includes\core;


abstract class Base_Menu_Callback extends core\Singleton {

    public abstract function add_admin_menu();
}