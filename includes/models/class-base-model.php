<?php

namespace axis_framework\includes\models;

use axis_framework\includes\core;

abstract class Base_Model {

	use core\Loader_Trait;

	/** @noinspection PhpUndefinedClassInspection */
	/** @noinspection PhpUndefinedNamespaceInspection */
	/** @var axis_framework\includes\controls\Base_Control */
	protected $control = NULL;

	public function __construct( $args = array() ) {

		if( isset( $args['control'] ) ) {

			$this->control = &$args['control'];
		}

		if( isset( $args['loader'] ) ) {

			$this->set_loader( $args['loader'] );
		}
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