<?php

namespace axis_framework\forms;


abstract class Base_Form {

	protected $_nonce_action;
	protected $_nonce_name;
	protected $data;
	protected $scheme;

	public function __construct() {
		$this->build_scheme();
	}

	public function nonce_action() {
		return $this->_nonce_action;
	}

	public function nonce_name() {
		return $this->_nonce_name;
	}

	public function nonce_field( $referer = TRUE, $echo = TRUE ) {
		return wp_nonce_field( $this->nonce_action(), $this->nonce_name(), $referer, $echo );
	}

	public function get_scheme() {
		return $this->scheme;
	}

	public function execute( array $data ) {

		if( !wp_verify_nonce( $data[ $this->nonce_name() ], $this->nonce_action() ) ) {
			return new \WP_Error( '', 'nonce verification failure' );
		}

		$this->data = $data;

		return $this->validate();
	}

	/**
	 * @return mixed
	 */
	abstract protected function build_scheme();

	/**
	 * @return boolean
	 */
	abstract protected function validate();
}