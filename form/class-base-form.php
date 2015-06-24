<?php

namespace axis_framework\model\forms;

require_once 'class-form-renderer.php';


/**
 * Class Base_Form
 *
 * @package axis_framework\forms
 * @since   0.20.10000
 */
abstract class Base_Form {

	protected $_nonce_action;
	protected $_nonce_name;
	protected $data;
	protected $structure;

	public function __construct( $nonce_action, $nonce_name ) {

		$this->_nonce_action = $nonce_action;
		$this->_nonce_name = $nonce_name;

		$this->apply_structure();
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

	public function get_structure() {
		return $this->structure;
	}

	public function apply_structure() {
		$this->structure = $this->build_structure();
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
	abstract protected function build_structure();

	/**
	 * @return boolean
	 */
	abstract protected function validate();
}