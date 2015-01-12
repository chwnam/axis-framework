<?php
/**
 * Plugin Name: Axis Sample Plugin
 * Plugin URI: https://github.com/chwnam/axis
 * Description: Axis Sample Plugin.
 * Author: Axis Author
 * Author URI: mailto://cs.chwnam@gmail.com
 * Domain Path:
 * Network:
 * Text Domain:
 * Version:
 */

require_once('plugin-defines.php');
require_once(AXIS_INC_BOOTSTRAP_PATH . '/class-bootstrap.php');

$bootstrap = new axis_framework\includes\bootstraps\Bootstrap();
$bootstrap->auto_discover_and_run('axis_sample', AXIS_SAMPLE_MAIN_FILE);
