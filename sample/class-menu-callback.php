<?php

require_once(AXIS_INC_BOOTSTRAP_PATH . '/class-base-menu-callback.php');
require_once(AXIS_INC_CORE_PATH      . '/class-menu-item.php');

use axis_framework\includes\bootstraps\Base_Menu_Callback;
use axis_framework\includes\core;

class Menu_Callback extends Base_Menu_Callback {

    public function add_admin_menu() {

        $menu_slug  = 'axis_sample_menu';
        $capability = 'manage_options';

        $submenu_slug_prefix     = $menu_slug . '_';
        $submenu_callback_prefix = $menu_slug . '_';

        // main menu
        $menu_item = new core\Menu_Page_Item(
            AXIS_SAMPLE_FULL_NAME,            // page_title
            AXIS_SAMPLE_SHORT_NAME,           // menu title
            $capability,
            $menu_slug
        );
        $menu_item->add();

        // submenus
        $submenu_page_wish_list = array(

            new core\Submenu_Page_Item(
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

    }

    public function axis_sample_menu_sample_test() {
        print 'hello!';
    }
}