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

require_once( 'plugin-defines.php' );
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-bootstrap.php');
require_once( 'class-plugin-callback.php');
require_once( 'class-ajax-callback.php');
require_once( 'class-menu-callback.php');
require_once( 'class-settings-callback.php');

$bootstrap = new axis_framework\includes\bootstraps\Bootstrap();
$bootstrap->set_plugin_callback(Plugin_Callback::get_instance());
$bootstrap->set_ajax_callback(Ajax_Callback::get_instance());
$bootstrap->set_menu_callback(Menu_Callback::get_instance());
$bootstrap->set_settings_callback(Settings_Callback::get_instance());
$bootstrap->set_main_file(AXIS_SAMPLE_MAIN_FILE);
$bootstrap->run();
