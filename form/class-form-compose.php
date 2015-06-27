<?php
namespace axis_framework\form\tag;

require_once( 'class-form-tag.php' );


class Form_Compose {

	public function __construct() {

	}

	public function compose( array $structures ) {

		$comment = $this->get_value( $structures, 'comment' );
		$method  = $this->get_value( $structures, 'method' );
		$action  = $this->get_value( $structures, 'action' );

		$form = new Form_Tag( '', '', '',
			array(
				'method' => $method,
				'action' => $action,
			)
		);

		if ( ! empty( $comment ) ) {
			$form->insert_child( new Comment_Node( $comment ) );
		}

		foreach ( $structures as &$structure ) {
			if ( is_array( $structure ) ) {
				$form->insert_child( $this->_compose( $structure ) );
			}
		}

		return $form;
	}

	private function get_value( array &$structure, $key, $default = '' ) {

		return isset( $structure[ $key ] ) ? $structure[ $key ] : $default;
	}

	private function _compose( array &$structure ) {

		$type = $this->get_value( $structure, 'type', '' );

		switch ( $type ) {
			case 'text':
				$container = $this->_compose_text( $structure );

				return $container;

			case 'submit':
				return $this->_compose_submit( $structure );
		}

		return new Text_Node( '' );
	}

	private function _compose_text( array &$structure ) {

		$name        = $this->get_value( $structure, 'name' );
		$id          = $this->get_value( $structure, 'id', $name );
		$label       = $this->get_value( $structure, 'label' );
		$attributes  = $this->get_value( $structure, 'attributes', array() );
		$description = $this->get_value( $structure, 'description' );
		$required    = $this->get_value( $structure, 'required', FALSE );

		if ( $name ) {

			$container = new Base_Tag( 'div', '', '' );

			if ( $label ) {
				$container->insert_child(
					new Label_Tag(
						'', $id,
						array(),
						array( new Text_Node( $label ), )
					)
				);
			}

			if ( $required ) {
				$attributes['required'] = 'required';
			}

			$container->insert_child(
				new Input_tag( $id, $name, 'text', $attributes )
			);

			if ( $description ) {
				$container->insert_child(
					new Base_Tag(
						'span', '', '',
						array( 'class' => 'description' ),
						array( new Text_Node( $description ), )
					)
				);
			}

			return $container;
		}

		return new Text_Node( '' );
	}

	private function _compose_submit( array &$structure ) {

		$id         = $this->get_value( $structure, 'id' );
		$attributes = $this->get_value( $structure, 'attributes', array() );

		return new Input_Tag( $id, '', 'submit', $attributes );
	}
}