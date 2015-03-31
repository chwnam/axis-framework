<?php

namespace axis_framework\includes\models;

use axis_framework\includes\core;

abstract class Base_Model {

	protected $loader;
	protected $control = NULL;

	public function __construct( $params = array() ) {

		if( isset( $params['control'] ) ) {

			$this->control = &$params['control'];
		}

		if( isset( $params['loader'] ) ) {

			$this->set_loader( $params['loader'] );
		}
	}

	public function set_loader( core\Loader &$loader) {

		$this->loader = &$loader;
	}

	public function set_control( &$control ) {

		$this->control = &$control;
	}

	public function notify_to_control( $signal, $param = array() ) {

		if( $this->control && is_string( $signal ) && is_array( $param ) ) {

			call_user_func_array( array( &$this->control, &$signal ), $param );
		}
	}
}