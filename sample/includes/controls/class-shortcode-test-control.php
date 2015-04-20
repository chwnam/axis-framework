<?php

namespace axis_sample;

use axis_framework\includes\controls;


class Shortcode_Test_Control extends controls\Base_Control {


	public function __construct( array $args = array() ) {

		parent::__construct( $args );
	}

	public function run() {

		echo 'shortcode-success!';
	}
}