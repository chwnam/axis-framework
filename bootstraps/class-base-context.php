<?php
namespace axis_framework\bootstraps\context;


class Base_Context {

	private $context_name;
	private $context_identifier;

	public function __construct( $context_name = '' ) {

		$this->set_context_name( $context_name );
		$this->set_context_id();
	}

	public function set_context_name( $context_name ) {
		$this->context_name = $context_name;
	}

	public function get_context_name() {
		return $this->context_name;
	}

	public function get_context_id() {
		return $this->context_identifier;
	}

	private function set_context_id() {
		$this->context_identifier = $this->context_name . '-' . spl_object_hash( $this );
	}

	public function add_action( $tag, $method_to_add, $priority = 10, $accepted_args = 1 ) {

		add_action( $tag, array( &$this, $method_to_add ), $priority, $accepted_args );
	}

	public function add_filter( $tag, $method_to_add, $priority = 10, $accepted_args = 1 ) {

		add_filter( $tag, array( &$this, $method_to_add, $priority, $accepted_args ) );
	}
}