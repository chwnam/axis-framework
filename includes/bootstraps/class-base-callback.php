<?php

namespace axis_framework\includes\bootstraps;

use \axis_framework\includes\core;

/**
 * Class Base_Callback
 *
 * Root of all base-*-callbacks.
 *
 * @package axis_framework\includes\bootstraps
 */
class Base_Callback extends core\Singleton {

	/** @var  core\Loader $loader loader object */
	protected $loader;

	protected function __construct( array $args = array() ) {

		if( isset( $args['loader'] ) ) {

			$this->set_loader( $args['loader'] );
		}
	}

	/**
	 * @return core\Loader
	 */
	public function get_loader() {

		return $this->loader;
	}

	/**
	 * @param core\Loader $loader
	 */
	public function set_loader( core\Loader &$loader ) {

		$this->loader = $loader;
	}
}