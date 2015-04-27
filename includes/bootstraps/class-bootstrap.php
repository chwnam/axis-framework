<?php
/**
 * Bootstrap
 */

namespace axis_framework\includes\bootstraps;

require_once( AXIS_INC_CORE_PATH . '/class-query.php' );
require_once( AXIS_INC_CORE_PATH . '/class-singleton.php' );
require_once( AXIS_INC_CORE_PATH . '/class-loader.php' );
require_once( AXIS_INC_CORE_PATH . '/utils.php' );
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-admin-post-callback.php' );
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-ajax-callback.php' );
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-menu-callback.php' );
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-plugin-callback.php' );
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-settings-callback.php' );
require_once( AXIS_INC_CONTROL_PATH . '/class-base-control.php' );
require_once( AXIS_INC_MODEL_PATH . '/class-base-model.php' );
require_once( AXIS_INC_MODEL_PATH . '/interface-entity.php' );  // entity interface must be earlier than entity model
require_once( AXIS_INC_MODEL_PATH . '/class-base-entity-model.php' );
require_once( AXIS_INC_VIEW_PATH . '/class-base-view.php' );

use axis_framework\includes\core;


core\utils\check_abspath();


/**
 * Class Bootstrap: Plugin initializer.
 *
 * Bootstrap is the first object to be instantiated for Axis Framework based plugins.
 * It initializes all callback objects, so that every callback function of your plugin can work for you.
 *
 * @package axis_framework\includes\bootstraps
 * @see     \axis_framework\includes\Base_Callback
 */
class Bootstrap {

	/**
	 * constant string for admin post callback object.
	 */
	const CALLBACK_ADMIN_POST = 'admin-post';

	/**
	 * constant string for ajax callback object.
	 */
	const CALLBACK_AJAX = 'ajax';

	/**
	 * constant string for menu callback object.
	 */
	const CALLBACK_MENU = 'menu';

	/**
	 * constant string for plugin callback object.
	 */
	const CALLBACK_PLUGIN = 'plugin';

	/**
	 * constant string for setting callback object.
	 */
	const CALLBACK_SETTINGS = 'settings';

	/**
	 * @var array $callback_objects all callback objects are stored here.
	 */
	protected $callback_objects;

	/**
	 * @var string $main_file Full path of your plug-in's path.
	 */
	protected $main_file;

	/**
	 * protected loader variable, and loader setter/getter trait.
	 */
	use core\Loader_Trait;

	/**
	 * @param array $arg argument to initialize.
	 */
	public function __construct( array $arg = array() ) {

		/** Default callback objects. Of course, you can override them. */
		$this->callback_objects = array(
			self::CALLBACK_ADMIN_POST => NULL,
			self::CALLBACK_AJAX       => NULL,
			self::CALLBACK_MENU       => NULL,
			self::CALLBACK_PLUGIN     => NULL,
			self::CALLBACK_SETTINGS   => NULL,
		);
	}

	/**
	 * Automatically discover files and run this bootstrap. Wrapper for auto_discover(), and run().
	 *
	 * @param string $main_file_namespace       namespace of callback objects.
	 * @param string $main_file                 full path of your plug-in's main file.
	 * @param mixed  $custom_discover           additional discover function
	 * @param array  $loader_component_override loader component array to override.
	 * @param mixed  $custom_run_callback       callback function finally called in run() method.
	 *
	 * @see \axis_framework\includes\bootstraps\Bootstrap::auto_discover()
	 * @see \axis_framework\includes\bootstraps\Bootstrap::run()
	 */
	public function auto_discover_and_run(
		$main_file_namespace,
		$main_file,
		$custom_discover = NULL,
		array $loader_component_override = array(),
		$custom_run_callback = NULL
	) {

		$this->auto_discover( $main_file_namespace, $main_file, $custom_discover, $loader_component_override );
		$this->run( $custom_run_callback );
	}

	/**
	 * Automatic setting when files are arranged under the rule.
	 *
	 * @param string $callback_namespace        the namespace of callback objects.
	 * @param string $main_file                 full path of your plug-in's main file.
	 * @param mixed  $custom_discover           additional discover function
	 * @param array  $loader_component_override loader component array to override.
	 */
	public function auto_discover(
		$callback_namespace,
		$main_file,
		$custom_discover = NULL,
		array $loader_component_override = array()
	) {

		// Main file set. It will be used when adding actions for activation, deactivation of plugin later.
		$this->set_main_file( $main_file );

		// initialize loader with default settings.
		if ( $this->loader == NULL ) {

			$plugin_root_path = realpath( dirname( $main_file ) );

			// your loader is created.
			$this->loader = new core\Loader( $plugin_root_path, $loader_component_override );
		}


		// temporary array for batch operation.
		$callback_targets = array(
			self::CALLBACK_ADMIN_POST,
			self::CALLBACK_AJAX,
			self::CALLBACK_MENU,
			self::CALLBACK_PLUGIN,
			self::CALLBACK_SETTINGS
		);

		// callback objects initialization
		foreach ( $callback_targets as $callback_target ) {

			/**
			 * @var Base_Callback $callback fully-qualified name of the callback class.
			 */
			$callback = $this->loader->bootstrap_callback( $callback_namespace, $callback_target );

			if ( $callback ) {

				$this->set_callback_object( $callback_target, $callback::get_instance() );
			}
		}

		// User-side discover operation. You can add your own extra callback objects.
		if ( $custom_discover ) {

			call_user_func_array( $custom_discover, array( &$this ) );
		}
	}

	/**
	 * Set a callback object.
	 *
	 * @param string $callback_category callback category. e.g. admin-post, ajax, menu, plugin, setting, ...
	 * @param object $plugin_callback
	 */
	public function set_callback_object( $callback_category, $plugin_callback ) {

		$this->callback_objects[ $callback_category ] = $plugin_callback;
		/** @noinspection PhpUndefinedMethodInspection */
		$this->callback_objects[ $callback_category ]->set_loader( $this->get_loader() );
	}

	/**
	 * Get a callback object.
	 *
	 * @param string $callback_category callback category. e.g. admin-post, ajax, menu, plugin, setting, ...
	 *
	 * @return object callback object's instance.
	 */
	public function get_callback_object( $callback_category ) {

		return $this->callback_objects[ $callback_category ];
	}

	/**
	 * Sets the main file. The main file is used for activation, deactivation, and uninstall hooks.
	 *
	 * @param string $main_file the full path of the plug-in's main file.
	 */
	public function set_main_file( $main_file ) {

		$this->main_file = $main_file;
	}

	/**
	 * main run procedure.
	 *
	 * @param mixed $custom_run_callback callback function finally called in run() method.
	 */
	public function run( $custom_run_callback = NULL ) {

		$this->register_hooks();
		$this->add_admin_menus();
		$this->add_settings_pages();
		$this->localize();
		$this->add_admin_post_actions();
		$this->add_ajax_actions();

		if ( $custom_run_callback ) {

			call_user_func_array( $custom_run_callback, array( &$this ) );
		}
	}

	/**
	 * Activation, deactivation, uninstall hook
	 */
	protected function register_hooks() {

		$plugin_callback = &$this->callback_objects[ self::CALLBACK_PLUGIN ];

		if ( $plugin_callback && $this->main_file ) {

			register_activation_hook( $this->main_file, array( $plugin_callback, 'on_activated' ) );
			register_deactivation_hook( $this->main_file, array( $plugin_callback, 'on_deactivated' ) );
			register_uninstall_hook( $this->main_file, array( $plugin_callback, 'on_uninstall' ) );

			// register other hooks
			/** @noinspection PhpUndefinedMethodInspection */
			$plugin_callback->register_hooks();
		}
	}

	/**
	 * Menu hook
	 */
	protected function add_admin_menus() {

		$menu_callback = &$this->callback_objects[ self::CALLBACK_MENU ];

		if ( $menu_callback ) {

			add_action( 'admin_menu', array( $menu_callback, 'add_admin_menu' ) );
		}
	}

	/**
	 * Create option page by using WordPress Option API
	 */
	protected function add_settings_pages() {

		$settings_callback = &$this->callback_objects[ self::CALLBACK_SETTINGS ];

		if ( $settings_callback ) {

			add_action( 'admin_init', array( $settings_callback, 'register_settings' ) );
			add_action( 'admin_init', array( $settings_callback, 'add_settings_sections' ) );
			add_action( 'admin_init', array( $settings_callback, 'add_settings_fields' ) );
			add_action( 'admin_menu', array( $settings_callback, 'add_option_page' ) );
		}
	}

	/**
	 * Localization hook
	 */
	protected function localize() {

		$plugin_callback = &$this->callback_objects[ self::CALLBACK_PLUGIN ];

		if ( $plugin_callback ) {

			add_action( 'plugins_loaded', array( $plugin_callback, 'localize' ) );
		}
	}

	/**
	 * Prepare all ajax callbacks
	 */
	protected function add_ajax_actions() {

		$ajax_callback = &$this->callback_objects[ self::CALLBACK_AJAX ];

		if ( $ajax_callback ) {

			/** @noinspection PhpUndefinedMethodInspection */
			$ajax_callback->add_ajax_actions();
		}
	}

	/**
	 * Prepare all admin post callbacks
	 */
	protected function add_admin_post_actions() {

		$admin_post_callback = &$this->callback_objects[ self::CALLBACK_ADMIN_POST ];

		if ( $admin_post_callback ) {

			/** @noinspection PhpUndefinedMethodInspection */
			$admin_post_callback->add_admin_post_actions();
		}
	}
}

