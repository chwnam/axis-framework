<?php
/**
 * Base dispatch
 */

namespace axis_framework\contexts;

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

		// scandir spits warning when the input is not a directory
		if( !is_dir( $context_directory ) ) {
			return;
		}

		$context_files = scandir( $context_directory );
		if( is_array( $context_files ) ) {
			foreach( $context_files as $context_file ) {
				$path = $context_directory . '/' . $context_file;
				$matches = array();
				if( file_exists( $path ) && is_file( $path ) && preg_match( '/^class-(.+)-context\.php$/', $context_file, $matches ) && count( $matches ) == 2 ) {
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
			/** @var \axis_framework\contexts\Base_Context $context */
			$context->init_context();
		}
	}
}