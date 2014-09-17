<?php

require_once(AXIS_INC_BOOTSTRAP_PATH . '/class-base-menu-callback.php');

use axis_framework\includes\bootstraps;

class Menu_Callback extends bootstraps\Base_Menu_Callback {

    public function __construct() {
        parent::__construct();
    }

    public function add_admin_menu() {

        $menu_slug  = 'axis_sample_menu';
        $capability = 'manage_options';

        $submenu_slug_prefix     = $menu_slug . '_';
        $submenu_callback_prefix = $menu_slug . '_';


        // main menu
        $menu_item = new bootstraps\Menu_Page_Item(
            AXIS_SAMPLE_FULL_NAME,            // page_title
            AXIS_SAMPLE_SHORT_NAME,           // menu title
            $capability,
            $menu_slug
        );
        $menu_item->add();


        // submenus
        $submenu_page_wish_list = array(

            new bootstraps\Submenu_Page_Item(
                TRUE,                                                   // show
                $menu_slug,                                             // parent_slug
                'Sample Test - ' . AXIS_SAMPLE_FULL_NAME,               // page title
                __('Sample Test ',  AXIS_SAMPLE_LANG_CONTEXT),          // menu title
                $capability,                                            // capability
                $submenu_slug_prefix . 'sample_test',                   // menu slug
                array($this, $submenu_callback_prefix . 'sample_test' ) // callback
            ),

        );

        foreach( $submenu_page_wish_list as $item ) {
            $item->add();
        }


        //
        remove_submenu_page( $menu_slug, $menu_slug );

    }

    public function axis_sample_menu_sample_test() {
        $control = $this->loader->control('sample-test');
        $control->run();
    }
}