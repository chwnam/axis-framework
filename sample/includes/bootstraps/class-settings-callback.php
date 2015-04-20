<?php
namespace axis_sample; // The namespace should be equal to all callback classes!

require_once(AXIS_INC_BOOTSTRAP_PATH . '/class-base-settings-callback.php');

use \axis_framework\includes\bootstraps\Base_Settings_Callback;
use \axis_framework\includes\bootstraps\settings;

class Settings_Callback extends Base_Settings_Callback {

    public $group_general;
    public $option_value_1;

    public $field_value_1;
    public $section_general;
    public $page;

    protected function __construct() {
        parent::__construct();

        $this->build_option_model_hierarchy();
        $this->build_control_hierarchy();
    }

    /**
     * 데이터베이스에 저장될 모델에 대한 정의를 수행합니다.
     * 세팅된 값을 저장할 option 들을 정의하고, 각각의 option은 어떤 option group에 속할지를 알려줍니다.
     */
    private function build_option_model_hierarchy() {

        // Group Name: General
        $this->group_general  = new settings\Option_Group('axis_sample_option_general');

        // General > axis_sample_value_1
        // open wp_options table and find this key after you save the settings.
        $this->option_value_1 = new settings\Option_Name('axis_sample_value_1', $this->group_general);
    }

    /**
     * 세팅 화면이 나왔을 때 UI 위젯들을 어떻게 보여주고 조작할지에 대해 설정합니다.
     */
    private function build_control_hierarchy() {

        // 페이지는 가장 상위 개념입니다.
        $this->page = new settings\Settings_Page('axis_sample_setting_page');

        // 섹션은 페이지 아래 존재합니다.
        $this->section_general = new settings\Settings_Section(
            'axis_sample_section_general',                     // id. html 코드를 확인하세요.
            __('General Section', 'axis_sample'),   // title
            // callback
            function( $args ) {
                // $args는 UI 위젯으로 전달되는 값입니다. generic_section_callback()을 참고하세요.
                $args['description'] = __('General settings sample description', 'axis_sample');
                settings\Settings_Helper::generic_section_callback($args);
            },
            $this->page                                        // page. 여기서 이 섹션의 상위 페이지가 누구인지 지정합니다.
        );

        // 각 섹션은 필드들을 가지고 있습니다.
        $this->field_value_1 = new settings\Settings_Field(
            'axis_sample_field_1',                           // id. html 코드를 확인하세요.
            __('Field Value 1', 'axis_sample'),   // title
            function( $args ) { // callback
                settings\Settings_Helper::generic_text_input_callback( $args );
            },
            $this->section_general,                               // section. 여기서 이 필드의 상위 섹션이 누구인지 지정합니다.
            array(                                                // extra-args. 위젯으로 전달되는 별도의 인자들.
                'id'            => 'axis_sample_field_1',         // be the same as the first argument
                'name'          => $this->option_value_1->name,
                'value'         => esc_attr( get_option( $this->option_value_1->name ) ),
                'description'   => __('Sample value 1 description', 'axis_sample'),
                'autocompelte'  => TRUE,
            )
        );
    }

    /**
     * 플러그인의 메뉴처럼 옵션 페이지를 생성하는 부분입니다.
     * 플러그인과는 달리 설정 메뉴의 하위에 출력된다는 점이 다릅니다.
     */
    public function add_option_page() {

        $capability = 'manage_options';

        add_options_page(
            __( 'axis sample options', 'axis_sample' ) . ' - ' . AXIS_SAMPLE_FULL_NAME,     // page_title
            __( 'axis sample options', 'axis_sample' ),                                     // menu_title
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

        $control = $this->loader->control('axis_sample', 'settings');
        $control->run();
    }
}