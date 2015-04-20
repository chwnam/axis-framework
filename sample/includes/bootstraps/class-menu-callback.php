<?php
namespace axis_sample; // The namespace should be equal to all callback classes!

require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-menu-callback.php' );

use axis_framework\includes\bootstraps;


class Menu_Callback extends bootstraps\Base_Menu_Callback {

	public function __construct() {

		parent::__construct();
	}

	public function add_admin_menu() {

		$menu_slug  = 'axis_sample_menu';
		$capability = 'manage_options';

		// main menu. If the item is not shown, check add_menu_page: $position value.
		$menu_item = new bootstraps\Menu_Page_Item(
			AXIS_SAMPLE_FULL_NAME,            // page_title
			AXIS_SAMPLE_SHORT_NAME,           // menu title
			$capability,
			$menu_slug
		    // callback, icon_url, position
		);
		$menu_item->add();

		// sub-menus. Itemized and sequentially added.
		$submenu_page_wish_list = array(

			new bootstraps\Submenu_Page_Item(
				TRUE,                                                    // show
				$menu_slug,                                              // parent_slug
				'Sample Test - ' . AXIS_SAMPLE_FULL_NAME,                // page title (top side of web browser)
				__( 'Axis Sample Test ', 'axis_sample' ),                // menu title
				$capability,                                             // capability
				'axis_sample_menu_sample_test',                          // menu slug
				array( $this, 'axis_sample_menu_sample_test' )           // callback
			),

            new bootstraps\Submenu_Page_Item(
                TRUE,
                $menu_slug,
                'View Class Test - ' . AXIS_SAMPLE_FULL_NAME,
                'View Class Test',
                $capability,
                'axis_sample_menu_view_class_test',
                $this->control_helper( 'axis_sample', 'view-class-test' ) // using helper function
            ),
		);

		foreach ( $submenu_page_wish_list as &$item ) {

			/** @var bootstraps\Submenu_Page_Item $item */
			$item->add();
		}

		// removes the first menu item.
		remove_submenu_page( $menu_slug, $menu_slug );
	}

	public function axis_sample_menu_sample_test() {

		$control = $this->loader->control( 'axis_sample', 'sample-test' );
		$control->run();
	}
}