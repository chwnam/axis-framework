<?php

namespace axis_sample;

use axis_framework\includes\controls\Base_Control;


class Ajax_Return_Control extends Base_Control {

	public function __construct( $args ) {

		parent::__construct( $args );
	}

	public function run() {

		echo "good!";
	}
}