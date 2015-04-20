<?php

namespace axis_sample;

use axis_framework\includes\controls;
use axis_framework\includes\core;


class Settings_Control extends controls\Base_Control {

    public function run() {

        $settings_callback = Settings_Callback::get_instance();

        $this->loader->simple_view(
            'settings',
            array(
                'group_name'  => $settings_callback->group_general->name,
                'page_name'   => $settings_callback->page->name,
            )
        );
    }

    protected function register_scripts() {

    }

    protected function register_css() {

    }
}