<?php

namespace axis_framework\model;

use axis_framework\control;
use axis_framework\core;


core\util\check_abspath(); // check abspath or inclusion fatal error.


abstract class Base_Model {

	use core\Loader_Trait;

	/** @var \axis_framework\control\Base_Control */
	protected $control = NULL;

	public function __construct( $args = array() ) {

		if( isset( $args['control'] ) ) {

			$this->set_control( $args['control'] );
		}

		if( isset( $args['loader'] ) ) {

			$this->set_loader( $args['loader'] );
		}
	}

	public function set_control( control\Base_Control $control ) {

		$this->control = $control;
	}
}