<?php

namespace axis_framework\form;

require_once( 'class-form-compose.php' );

use axis_framework\form\tag;


/**
 * Class Base_Form
 * A ModelView-like form object
 *
 * @package axis_framework\forms
 * @since   0.20.10000
 */
abstract class Base_Form {

	protected $_nonce_action;
	protected $_nonce_name;

	public function __construct( $nonce_action, $nonce_name ) {

		$this->_nonce_action = $nonce_action;
		$this->_nonce_name   = $nonce_name;
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

		return $this->build_structure();
	}

	public function execute( array &$data ) {

		if ( ! wp_verify_nonce( $data[ $this->nonce_name() ], $this->nonce_action() ) ) {
			return new \WP_Error( '', 'nonce verification failure' );
		}

		return $this->validate( $data );
	}

	public function do_form( $add_nonce_field = TRUE ) {

		$structure = $this->get_structure();
		$compose   = new tag\Form_Compose();
		$form      = $compose->compose( $structure );

		$output  = isset( $structure['output'] ) ? $structure['output'] : TRUE;
		$referer = isset( $structure['referer'] ) ? $structure['referer'] : TRUE;

		$output_code   = $form->start_tag( $output );
		$form_children = &$form->get_children();
		foreach ( $form_children as &$child ) {
			/** @var \axis_framework\form\tag\Base_Tag $child */
			$output_code .= $child->render( TRUE, $output );
		}
		if( $add_nonce_field ) {
			$output_code .= $this->nonce_field( $referer, $output );
		}
		$output_code .= $form->end_tag( $output );

		return $output ? $output_code : NULL;
	}

	/**
	 * @return mixed
	 */
	abstract protected function build_structure();

	/**
	 * @param  array $data
	 *
*@return boolean
	 */
	abstract protected function validate( array &$data );
}