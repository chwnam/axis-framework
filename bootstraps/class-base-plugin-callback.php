<?php

namespace axis_framework\bootstraps;

use axis_framework\core;


/**
 * Class Base_Plugin_Callback
 *
 * @package axis_framework\includes\bootstraps
 */
abstract class Base_Plugin_Callback extends Base_Callback {

	protected function __construct( array $args = array() ) {

		parent::__construct( $args );
	}

	abstract public function localize();

	abstract public function on_activated();

	abstract public function on_deactivated();

	abstract public function on_uninstall();

	abstract public function register_hooks();

}