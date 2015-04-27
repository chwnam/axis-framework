<?php

namespace axis_framework\includes\models;

use axis_framework\includes\controls;
use axis_framework\includes\core;


abstract class Base_Model {

	use core\Loader_Trait;

	/** @var \axis_framework\includes\controls\Base_Control */
	protected $control = NULL;

	public function __construct( $args = array() ) {

		if( isset( $args['control'] ) ) {

			$this->set_control( $args['control'] );
		}

		if( isset( $args['loader'] ) ) {

			$this->set_loader( $args['loader'] );
		}
	}

	public function set_control( controls\Base_Control $control ) {

		$this->control = $control;
	}
}