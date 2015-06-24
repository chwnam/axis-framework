<?php
/**
 * Base dispatch
 */

namespace axis_framework\context;

use axis_framework\core\Loader;
use axis_framework\core\Loader_Trait;


class Dispatch {

	use Loader_Trait;

	private $callback_contexts = array();
	private $plugin_main_file = '';

	public function reset_context() {

		$this->callback_contexts = array();
	}

	public function get_context( $context_name ) {

		if( !isset( $this->callback_contexts[ $context_name ] ) ) {
			return NULL;
		}

		return $this->callback_contexts[ $context_name ];
	}

	public function get_main_file() {

		return $this->plugin_main_file;
	}

	public function set_context( $context_name, $context ) {

		$this->callback_contexts[ $context_name ] = $context;
	}

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
		if( is_array( $context_files ) ) {
			foreach( $context_files as $context_file ) {
				$path = $context_directory .  '/' . $context_file;
				$matches = array();
				if( file_exists( $path ) && is_file( $path ) && preg_match( '/^class-(.+)-context\.php$/', $context_file, $matches ) ) {
					$this->set_context(
						$matches[1],  // context_name
						$this->loader->context(
							$context_namespace,
							$matches[1], // context name
							array( 'loader' => $this->loader, 'dispatch' => $this )
						)
					);
				}
			}
		}

		foreach( $this->callback_contexts as $context ) {
			/** @var \axis_framework\context\Base_Context $context */
			$context->init_context();
		}
	}

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