<?php

namespace axis_framework\plugin\plugin_context;

use axis_framework\context\Base_Context;


class Axis_Plugin_Context extends Base_Context {

	public function __construct( array $args = [] ) {

		parent::__construct( $args );
	}

	public function init_context() {

		$this->add_context_action( 'admin_menu' );
	}

	protected function admin_menu_callback() {

		add_menu_page(
			__( 'Axis Framework', 'axis_framework' ),
			__( 'Axis Framework', 'axis_framework' ),
			'manage_options',
			'axis_framework_menu',
			$this->render_template( 'axis_framework_menu' )
		);
	}
}