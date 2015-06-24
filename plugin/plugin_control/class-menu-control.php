<?php

namespace axis_framework\plugin\plugin_controls;

use axis_framework\control\Base_Control;


class Menu_Control extends Base_Control {

	public function __construct( array $args = array() ) {

		parent::__construct( $args );
	}

	public function axis_framework_menu() {

		$this->render_template( 'axis_framework_menu' );
	}
}