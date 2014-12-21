<?php

use axis_framework\includes\controls;
use axis_framework\includes\core;

class Sample_Test_Control extends controls\Base_Control {

    public function __construct() {
        parent::__construct();
    }

    public function run() {

        $this->register_scripts();
        $this->register_css();

        $view = $this->loader->view( '', 'sample-test' );

    }

    public function register_scripts() {

        $wish_list = array(

            new controls\Script_Item(
                'axis_js_handle',
                AXIS_SAMPLE_JS_URL . '/axis_js.js',
                controls\Script_Item::$ajax_object,
                controls\Script_Item::$ajax_url,
                array('jquery'),
                NULL
            ),

            new controls\Script_Item(
                'sample_test_script_handle',
                AXIS_SAMPLE_JS_URL . '/sample_test.js',
                controls\Script_Item::$ajax_object,
                controls\Script_Item::$ajax_url,
                array('jquery', 'axis_js_handle'),
                NULL
            ),

        );

        foreach($wish_list as $w) {
            $w->enqueue();
        }
    }

    public function register_css() {

        $wish_list = array(

            new controls\Css_Item(
                'test_css_handle',
                AXIS_SAMPLE_CSS_URL . '/sample_test.css'
            )

        );

        foreach($wish_list as $w) {
            $w->enqueue();
        }

    }

}