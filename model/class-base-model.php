<?php

namespace axis_framework\model;

use axis_framework\control\Base_Control;
use axis_framework\core\Loader_Trait;
use axis_framework\core\util;


util\check_abspath(); // check abspath or inclusion fatal error.


abstract class Base_Model {

	use Loader_Trait;

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

	public function set_control( Base_Control $control ) {

		$this->control = $control;
	}
}