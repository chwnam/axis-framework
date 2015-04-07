<?php

namespace axis_framework\includes\bootstraps;

require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-callback.php' );

use \axis_framework\includes\core;


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