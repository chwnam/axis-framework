<?php

namespace axis_framework\contexts;


trait Plugin_Callback_Trait {

	public function by_trait_activation_deactivation_hook( $main_file ) {

		register_activation_hook( $main_file, array( &$this, 'on_activated' ) );
		register_deactivation_hook( $main_file, array( &$this, 'on_deactivated' ) );

		// use uninstall.php instead.
		// register_uninstall_hook( $main_file, array( __CLASS__, 'on_uninstall' ) );
	}

	public function by_trait_add_admin_menu() {

		add_action( 'admin_menu', array( &$this, 'add_admin_menu' ) );
	}

	public function by_trait_add_settings_page() {

		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		add_action( 'admin_init', array( &$this, 'add_settings_sections' ) );
		add_action( 'admin_init', array( &$this, 'add_settings_fields' ) );
		add_action( 'admin_menu', array( &$this, 'add_option_page' ) );
	}

	public function by_trait_plugin_localize() {

		add_action( 'plugins_loaded', array( &$this, 'localize' ) );
	}
}