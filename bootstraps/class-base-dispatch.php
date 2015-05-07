<?php
/**
 * Base dispatch
 */

namespace axis_framework\bootstraps\dispatch;

use axis_framework\bootstraps\context\Base_Context;
use axis_framework\core;

abstract class Base_Dispatch extends core\Singleton {

	use core\Loader_Trait;

	private $callback_contexts = array();

	protected function __construct( array $args = array() ) {

		if( isset( $args['loader'] ) && $args['loader'] instanceof core\Loader ) {
			$this->set_loader( $args['loader'] );
		}
	}

	public function reset_callback_context() {

		$this->callback_contexts = array();
	}

	public function get_context( $context_name ) {

		if( !isset( $this->callback_contexts[ $context_name ] ) ) {

			return NULL;
		}

		return $this->callback_contexts[ $context_name ];
	}

	public function set_context( $context_name, $context ) {

		$this->callback_contexts[ $context_name ] = $context;
	}

	public abstract function dispatch();
}