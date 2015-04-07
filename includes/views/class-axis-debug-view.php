<?php

namespace axis_framework\includes\views;

require_once( 'class-base-view' );

use axis_framework\includes\core;


class Axis_Debug_View extends Base_View {

	/** @var  core\Loader loader */
	protected $loader;

	public function __construct( $params = array() ) {

		parent::__construct( $params );
	}

	public function render() {


	}
}