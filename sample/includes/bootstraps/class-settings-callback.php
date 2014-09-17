<?php

require_once(AXIS_INC_BOOTSTRAP_PATH . '/class-base-settings-callback.php');

use \axis_framework\includes\bootstraps\Base_Settings_Callback;
use \axis_framework\includes\bootstraps\settings;

class Settings_Callback extends Base_Settings_Callback {

    public $group_general;
    public $option_value_1;

    public $field_value_1;
    public $section_general;
    public $page;

    public function __construct() {
        parent::__construct();

        $this->build_option_model_hierarchy();
        $this->build_control_hierarchy();
    }

    private function build_option_model_hierarchy() {

        $this->group_general = new settings\Option_Group('axis_sample_option_general');
        $this->option_value_1 = new settings\Option_Name('axis_sample_value_1', $this->group_general);
    }

    private function build_control_hierarchy() {

        $this->page = new settings\Settings_Page('axis_sample_setting_page');

        $this->section_general = new settings\Settings_Section(
            'axis_sample_section_general', // id
            __('General Section', AXIS_SAMPLE_LANG_CONTEXT), // title
            function( $args ) { // callback
                $args['description'] = __('General settings sample description', AXIS_SAMPLE_LANG_CONTEXT);
                settings\Settings_Helper::generic_section_callback($args);
            },
            $this->page // page
        );

        $this->field_value_1 = new settings\Settings_Field(
            'axis_sample_field_1', // id
            __('Field Value 1', AXIS_SAMPLE_LANG_CONTEXT), // title
            function( $args ) { // callback
                settings\Settings_Helper::generic_text_input_callback( $args );
            },
            $this->section_general, // section
            array( // args
                'id'            => 'axis_sample_field_1', // be the same as the first argument
                'name'          => $this->option_value_1->name,
                'value'         => esc_attr( get_option( $this->option_value_1->name ) ),
                'description'   => __('Sample value 1 description', AXIS_SAMPLE_LANG_CONTEXT),
                'autocompelte'  => TRUE,
            )
        );

    }

    public function add_option_page() {

        $capability = 'manage_options';

        add_options_page(
            __( 'axis sample options', AXIS_SAMPLE_LANG_CONTEXT ) . ' - ' . AXIS_SAMPLE_FULL_NAME,     // page_title
            __( 'axis sample options', AXIS_SAMPLE_LANG_CONTEXT ),                                     // menu_title
            $capability,                                                                               // capability
            $this->page->name,                                            // menu_slug
            array( $this, $this->page->name )                             // callback_function
        );

    }

    public function register_settings() {

        settings\Settings_Helper::register_settings(
            [
                $this->option_value_1,
            ]
        );

    }

    public function add_settings_sections() {

        settings\Settings_Helper::add_settings_sections(
            [
                $this->section_general,
            ]
        );

    }

    public function add_settings_fields() {

        settings\Settings_Helper::add_settings_fields(
            [
                $this->field_value_1,
            ]
        );

    }

    // menu callback
    public function axis_sample_setting_page() {

        $control = $this->loader->control('settings');
        $control->run();

    }
}