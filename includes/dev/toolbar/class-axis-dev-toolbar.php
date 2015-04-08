<?php

namespace axis_framework\includes\dev;

use axis_framework\includes\controls;

class Axis_Dev_Toolbar extends controls\Base_Control {

	public function __construct( array $args = array() ) {

		parent::__construct( $args );
	}

	public function register_scripts() {

		$script = new controls\Script_Item(
			'axis_dev_toolbar_js_handle',
			content_url( 'axis/includes/dev/toolbar/axis-dev-toolbar.js' ),
			NULL,
			array(),
			array( 'jquery', 'jquery-ui-resizable' ),
			NULL,
			TRUE
		);

		$script->enqueue();
	}

	public function register_css() {

		$css = new controls\Css_Item(
			'axis_dev_toolbar_css_handle',
			content_url( 'axis/includes/dev/toolbar/axis-dev-toolbar.css' ),
			array(),
			NULL
		);


		global $wp_scripts;
		// get the jquery ui object
		/** @var \_WP_Dependency $query_ui */
		$query_ui = $wp_scripts->query('jquery-ui-core');

		// load the jquery ui theme
		$url = "http://ajax.googleapis.com/ajax/libs/jqueryui/$query_ui->ver/themes/smoothness/jquery-ui.css";

		$jquery_ui_smoothness = new controls\Css_Item(
			'jquery-ui-smoothness',
			$url,
			array(),
			NULL
		);

		$css->enqueue();
		$jquery_ui_smoothness->enqueue();
	}

	public function run( array &$context = array() ) {

		$this->register_scripts();
		$this->register_css();

		$log_array = axis_get_logger()->get_logging();
		$n_context = array_merge( $context, array( 'log_array' => $log_array ) );

		if ( !empty( $n_context ) ) {

			$keys = array_keys( $n_context );
			foreach ( $keys as &$key ) {
				$$key = &$n_context[ $key ];
			}
		}

		// this object will not have loader object reference.
		require_once( 'toolbar-view.php' );

		if ( !empty( $n_context ) ) {
			$keys = array_keys( $n_context );
			foreach ( $keys as &$key ) {
				if( isset( $$key ) ) {
					unset( $$key );
				}
			}
		}
	}
}


function init_axis_dev_toolbar() {

	$debug_control = new Axis_Dev_Toolbar();
	$debug_control->run();
}