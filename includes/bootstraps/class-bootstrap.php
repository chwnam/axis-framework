<?php

namespace axis_framework\includes\bootstraps;

class Bootstrap {

    private $plugin_callback        = NULL;
    private $ajax_callback          = NULL;
    private $menu_callback          = NULL;
    private $settings_callback      = NULL;
    private $main_file              = NULL;

    public function __construct() {

    }

    public function set_plugin_callback( $plugin_callback ) {
        $this->plugin_callback = $plugin_callback;
    }

    public function set_ajax_callback($ajax_callback) {
        $this->ajax_callback = $ajax_callback;
    }

    public function set_menu_callback($menu_callback) {
        $this->menu_callback = $menu_callback;
    }

    public function set_settings_callback($settings_callback) {
        $this->settings_callback = $settings_callback;
    }

    public function set_main_file( $main_file ) {
        $this->main_file = $main_file;
    }

    public function run() {

        $this->register_hooks();
        $this->add_admin_menus();
        $this->add_settings_pages();
        $this->localize();
        $this->add_ajax_actions();

    }

    /**
     * Activation, deactivation, uninstall hook
     */
    private function register_hooks() {

        if ($this->plugin_callback && $this->main_file) {
            register_activation_hook( $this->main_file,   array( $this->plugin_callback, 'on_activated' ) );
            register_deactivation_hook( $this->main_file, array( $this->plugin_callback, 'on_deactivated' ) );
            register_uninstall_hook( $this->main_file,    array( $this->plugin_callback, 'on_uninstall' ) );
        }

    }

    /**
     * Menu hook
     */
    private function add_admin_menus() {

        add_action( 'admin_menu', array( $this->menu_callback, 'add_admin_menu' ) );
    }

    /**
     * Create option page by using WordPress Option API
     */
    private function add_settings_pages() {

    }

    /**
     * Localization hook
     */
    private function localize() {

    }

    /**
     * Prepare all ajax callbacks
     */
    private function add_ajax_actions() {

    }
}