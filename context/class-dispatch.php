<?php
namespace axis_framework\context;

use axis_framework\core\Loader;
use axis_framework\core\Loader_Trait;


/**
 * Class Dispatch
 *
 * Plugin starter, and class based context manager.
 *
 * @package axis_framework\context
 */
class Dispatch {

	use Loader_Trait;

	/**
	 * @var array
	 */
	private $callback_contexts = array();

	/**
	 * @var string
	 */
	private $plugin_main_file = '';

	/**
	 * @return void
	 */
	public function reset_context() {

		$this->callback_contexts = array();
	}

	/**
	 * @param string $context_name
	 *
	 * @return \axis_framework\context\Base_Context|null
	 */
	public function get_context( $context_name ) {

		if( !isset( $this->callback_contexts[ $context_name ] ) ) {
			return NULL;
		}

		return $this->callback_contexts[ $context_name ];
	}

	/**
	 * @return string
	 */
	public function get_main_file() {

		return $this->plugin_main_file;
	}

	/**
	 * @param \axis_framework\context\Base_Context $context
	 */
	public function set_context( Base_Context $context ) {

		if( $context instanceof Base_Context ) {
			$this->callback_contexts[ $context->get_context_name() ] = $context;
		}
	}

	/**
	 * @param       $main_file_path
	 * @param       $context_namespace
	 * @param array $loader_component_override
	 */
	public function setup(
		$main_file_path,
		$context_namespace,
		array $loader_component_override = array()
	) {

		$this->loader = new Loader( realpath( dirname( $main_file_path ) ), $loader_component_override );
		$this->plugin_main_file = $main_file_path;

		// traversing the 'contexts' directory and instantiating all context classes.
		$context_directory = $this->loader->get_component_directory( 'context' );

		// scandir() spits warning when the input is not a directory
		if( !is_dir( $context_directory ) ) {
			return;
		}

		$context_files = scandir( $context_directory );
		if( !is_array( $context_files ) ) {
			return;
		}

		// compact-style contexts
		$compact_contexts = array();

		foreach( $context_files as $context_file ) {

			$path    = $context_directory . '/' . $context_file;
			$matches = array();

			if( !file_exists( $path ) || !is_file( $path ) ) {
				continue;
			}

			if( preg_match( '/^(class-)?(.+)-context\.php$/', $context_file, $matches ) ) {

				if( !empty( $matches[1] ) ) {
					$this->set_context(
						$this->loader->context(
							$context_namespace,
							$matches[2], // context name
							array( 'loader' => $this->loader, 'dispatch' => $this )
						)
					);
				} else {
					$compact_contexts[] = $path;
				}
			}
		}

		// compact style context initialization
		if( !empty( $compact_contexts ) ) {
			require_once 'short-context-wrapper.php';
			if( !\Short_Context_Wrapper::has_empty_context( $this->get_main_file() ) ) {
				\Short_Context_Wrapper::set_empty_context(
					new Empty_Context(
						array(
							'loader'       => $this->loader,
							'dispatch'     => $this,
							'context_name' => 'empty-context',
						)
					),
					$this->get_main_file()
				);
			}
			foreach( $compact_contexts as $compact_context ) {

				/** @noinspection PhpIncludeInspection */
				include_once( $compact_context );
			}
		}

		// class-based context initialization
		foreach( $this->callback_contexts as $context ) {
			/** @var \axis_framework\context\Base_Context $context */
			$context->init_context();
		}
	}

	/**
	 * Disable deactivation of Axis Framework
	 */
	public static function lock_axis_framework() {

		$axis_main_file   = AXIS_FRAMEWORK_MAIN_FILE;
		$plugin_root_base = basename( WP_PLUGIN_DIR );
		$plugin_file      = substr(
			$axis_main_file,
			strpos( $axis_main_file, $plugin_root_base ) + strlen( $plugin_root_base ) + 1
		);

		$disable_func = function( $actions ) {
			$actions['deactivate'] = '<span class="locked">Axis framework is requested to be locked.</span>';
			return $actions;
		};

		add_filter(
			"plugin_action_links_$plugin_file",
			$disable_func,
			10, 1
		);

		add_filter(
			"network_admin_plugin_action_links_$plugin_file",
			$disable_func,
			10, 1
		);
	}
}