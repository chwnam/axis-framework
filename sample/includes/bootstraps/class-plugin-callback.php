<?php
namespace axis_sample; // The namespace should be equal to all callback classes!

require_once(AXIS_INC_BOOTSTRAP_PATH . '/class-base-plugin-callback.php');

use axis_framework\includes\bootstraps\Base_Plugin_Callback;

class Plugin_Callback extends Base_Plugin_Callback {

    public function __construct() {

        parent::__construct();
    }

    public function localize() {

        $rel_path = plugin_basename( AXIS_SAMPLE_MAIN_FILE ) . '/lang';

        // localization code. returns false when language file is not found.
        if( !load_plugin_textdomain( 'axis_sample', FALSE, $rel_path ) ) {

            //die( "load_plugin_textdomain() error!" );
        }
    }

    public function on_activated() {

        // callback when the plugin is activated.
    }

    public function on_deactivated() {

        // callback when the plugin is deactivated.
    }

    public function on_uninstall() {

        // callback when the plugin is about to uninstalled
    }

    public function register_hooks() {

        // register your own hooks here.
	    add_shortcode( 'axis_shortcode', $this->control_helper( 'axis_sample', 'shortcode-test', array(), TRUE ) );
    }
}