<?php

	namespace axis_framework\includes\bootstraps;

	require_once( AXIS_INC_CORE_PATH . '/class-singleton.php' );
	require_once( AXIS_INC_CORE_PATH . '/class-loader.php' );
	require_once( AXIS_INC_CORE_PATH . '/utils.php' );
	require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-ajax-callback.php' );
	require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-menu-callback.php' );
	require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-plugin-callback.php' );
	require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-settings-callback.php' );
	require_once( AXIS_INC_CONTROL_PATH . '/class-base-control.php' );

	use axis_framework\includes\core;

	core\utils\check_abspath();


	class Bootstrap {

		protected $plugin_callback = NULL;
		protected $ajax_callback = NULL;
		protected $menu_callback = NULL;
		protected $settings_callback = NULL;
		protected $main_file = NULL;
		protected $loader = NULL;

		public function __construct() {
			$this->loader = new core\Loader();
		}

		public function auto_discover_and_run( $main_file_namespace, $main_file ) {

			$this->auto_discover( $main_file_namespace, $main_file );
			$this->run();
		}

		/**
		 *  Automatic setting when files are arranged under the rule.
		 *  TODO: Hardcoded parts should be replaceable.
		 */
		public function auto_discover( $main_file_namespace, $main_file ) {

			$this->set_main_file( $main_file );

			$plugin_root_path         = realpath( dirname( $main_file ) );
			$plugin_include_bootstrap = $plugin_root_path . '/includes/bootstraps';

			// initialize loader.
			if( $this->loader === NULL ) {
				$this->loader = new core\Loader();
			}
			$this->loader->set_plugin_root( $plugin_root_path );

			// all callback objects initialization
			$ajax_callback_path     = $plugin_include_bootstrap . '/class-ajax-callback.php';
			$menu_callback_path     = $plugin_include_bootstrap . '/class-menu-callback.php';
			$plugin_callback_path   = $plugin_include_bootstrap . '/class-plugin-callback.php';
			$settings_callback_path = $plugin_include_bootstrap . '/class-settings-callback.php';

			if ( file_exists( $ajax_callback_path ) ) {
				require_once( $ajax_callback_path );
				$fqn = $main_file_namespace . '\\' . 'Ajax_Callback';
				$this->set_ajax_callback( $fqn::get_instance() );
			}

			if ( file_exists( $menu_callback_path ) ) {
				require_once( $menu_callback_path );
				$fqn = $main_file_namespace . '\\' . 'Menu_Callback';
				$this->set_menu_callback( $fqn::get_instance() );
			}

			if ( file_exists( $plugin_callback_path ) ) {
				require_once( $plugin_callback_path );
				$fqn = $main_file_namespace . '\\' . 'Plugin_Callback';
				$this->set_plugin_callback( $fqn::get_instance() );
			}

			if ( file_exists( $settings_callback_path ) ) {
				require_once( $settings_callback_path );
				$fqn = $main_file_namespace . '\\' . 'Settings_Callback';
				$this->set_settings_callback( $fqn::get_instance() );
			}
		}

		public function set_plugin_callback( $plugin_callback ) {

			$this->plugin_callback = $plugin_callback;
			$this->plugin_callback->set_loader( $this->loader );
		}

		public function set_ajax_callback( $ajax_callback ) {

			$this->ajax_callback = $ajax_callback;
			$this->ajax_callback->set_loader( $this->loader );
		}

		public function set_menu_callback( $menu_callback ) {

			$this->menu_callback = $menu_callback;
			$this->menu_callback->set_loader( $this->loader );
		}

		public function set_settings_callback( $settings_callback ) {

			$this->settings_callback = $settings_callback;
			$this->plugin_callback->set_loader( $this->loader );
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
		protected function register_hooks() {

			if ( $this->plugin_callback && $this->main_file ) {

				register_activation_hook( $this->main_file, array( $this->plugin_callback, 'on_activated' ) );
				register_deactivation_hook( $this->main_file, array( $this->plugin_callback, 'on_deactivated' ) );
				register_uninstall_hook( $this->main_file, array( $this->plugin_callback, 'on_uninstall' ) );
			}

		}

		/**
		 * Menu hook
		 */
		protected function add_admin_menus() {

			if( $this->menu_callback ) {
				add_action( 'admin_menu', array( $this->menu_callback, 'add_admin_menu' ) );
			}
		}

		/**
		 * Create option page by using WordPress Option API
		 */
		protected function add_settings_pages() {

			if ( $this->settings_callback ) {

				add_action( 'admin_init', array( $this->settings_callback, 'register_settings' ) );
				add_action( 'admin_init', array( $this->settings_callback, 'add_settings_sections' ) );
				add_action( 'admin_init', array( $this->settings_callback, 'add_settings_fields' ) );

				add_action( 'admin_menu', array( $this->settings_callback, 'add_option_page' ) );
			}
		}

		/**
		 * Localization hook
		 */
		protected function localize() {

			if ( $this->plugin_callback ) {

				add_action( 'init', array( $this->plugin_callback, 'localize' ) );
			}
		}

		/**
		 * Prepare all ajax callbacks
		 */
		protected function add_ajax_actions() {

			if ( $this->ajax_callback ) {

				$this->ajax_callback->add_ajax_actions();
			}
		}
	}

