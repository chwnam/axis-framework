<?php

namespace axis_framework\includes\views;

use axis_framework\includes\core;

abstract class Base_View {

	/** @var  core\Loader loader */
	protected $loader;

	public function __construct( $params = array() ) {

		if( isset( $params['loader'] ) ) {

			$this->set_loader( $params['loader'] );
		}
	}

	public function set_loader( core\Loader &$loader) {

		$this->loader = &$loader;
	}

    public abstract function render( array $context = array() );
}