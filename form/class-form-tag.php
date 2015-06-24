<?php

namespace axis_framework\form\tag;


/**
 * Class Form_Tag
 *
 * @since 0.21.0000
 */
class Base_Html_Tag {

	/** @var string $tag */
	private $tag = '';

	/** @var string $id */
	private $id = '';

	/** @var string $name */
	private $name = '';

	/** @var array $properties */
	private $properties = array();

	/** @var null|Base_Html_Tag $parent */
	private $parent   = NULL;

	/** @var array $children */
	private $children = array();

	/**
	 * @param        $tag
	 * @param string $id
	 * @param string $name
	 * @param array  $other_attributes
	 */
	public function __construct( $tag, $id = '', $name = '', array $other_attributes = array() ) {

		$this->set_tag( $tag );
		$this->set_id( $id );
		$this->set_name( $name );

		$this->properties = array();
		foreach ( $other_attributes as $key => &$attribute ) {
			if ( $key !== 'name' && $key !== 'id' && $this->is_valid_symbol( $key ) ) {
				$this->properties[ $key ] = esc_attr( $attribute );
			}
		}
	}

	/**
	 * @param Base_Html_Tag $tag_object
	 */
	public function set_parent( Base_Html_Tag $tag_object ) {
		$this->parent = $tag_object;
	}

	/**
	 * @return Base_Html_Tag|null
	 */
	public function get_parent() {
		return $this->parent;
	}

	/**
	 * @param Base_Html_Tag $tag_object
	 */
	public function insert_child( Base_Html_Tag $tag_object ) {
		$tag_object->set_parent( $this );
		$this->children[] = $tag_object;
	}

	/**
	 * @param integer $child_index
	 *
	 * @return Base_Html_Tag
	 */
	public function remove_child( $child_index ) {

		if( 0 < $child_index && $child_index < count( $this->children ) ) {

			/** @var \axis_framework\form\tag\Base_Html_Tag $child */
			$child = $this->children[ $child_index ];
			unset( $this->children[ $child_index ] );

			$child->set_parent( NULL );
			$grandchildren = &$child->get_children();

			foreach( $grandchildren as &$grandchild ) {

				/** @var \axis_framework\form\tag\Base_Html_Tag $grandchild */
				$grandchild->set_parent( $this );
			}

			return $child;
		}

		throw new \OutOfRangeException(
			sprintf(
				"tag %s has no child at index %d",
				$this->get_tag(),
				$child_index
			)
		);
	}

	/**
	 * @param integer $child_index
	 *
	 * @return mixed
	 */
	public function &get_child( $child_index ) {

		if( 0 < $child_index && $child_index < count( $this->children ) ) {
			return $this->children[ $child_index ];
		}

		throw new \OutOfRangeException(
			sprintf(
				"tag %s has no child at index %d",
				$this->get_tag(),
				$child_index
			)
		);
	}

	/**
	 * @return array array of Base_Html_Tag
	 */
	public function &get_children() {
		return $this->children;
	}

	/**
	 * @return string
	 */
	public function get_name() {

		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_id() {

		return $this->id;
	}

	/**
	 * @return string
	 */
	public function get_tag() {

		return $this->tag;
	}

	/**
	 * @param string $name
	 */
	private function set_tag( $name ) {

		if ( ! $this->is_valid_symbol( $name ) ) {
			throw new \LogicException( "tag name '{$name}' is not allowed" );
		}
		$this->tag = $name;
	}

	/**
	 * @param string $id
	 */
	public function set_id( $id ) {

		$this->id = esc_attr( $id );
	}

	/**
	 * @param string $name
	 */
	public function set_name( $name ) {

		$this->name = esc_attr( $name );
	}

	/**
	 * @param string $property
	 * @param string $default
	 *
	 * @return string
	 */
	public function get_attribute( $property, $default = '' ) {

		if ( isset( $this->properties[ $property ] ) ) {
			return $this->properties[ $property ];
		}

		return $default;
	}

	/**
	 * @param string $property
	 * @param string $value
	 */
	public function set_attribute( $property, $value ) {

		if ( $this->is_valid_symbol( $property ) ) {
			$this->properties[ $property ] = esc_attr( $value );
		}
	}

	/**
	 * @param string $property
	 *
	 * @return bool
	 */
	public function has_attribute( $property ) {

		return array_key_exists( $property, $this->properties );
	}

	/**
	 * @param $method
	 * @param $arguments
	 *
	 * @return string
	 */
	public function __call( $method, $arguments ) {

		if ( strpos( $method, 'get_' ) === 0 ) {

			$property = substr( $method, 4 );
			if ( $this->has_attribute( $property ) ) {
				return $this->get_attribute( $property );
			} else {
				throw new \LogicException(
					sprintf(
						'form tag class "%s" does not have property "%s"',
						get_called_class(),
						$property
					)
				);
			}
		} else if ( strpos( $method, 'set_' ) === 0 ) {

			$property = substr( $method, 4 );
			if ( $this->has_attribute( $property ) ) {
				$this->set_attribute( $property, $arguments );
			} else {
				throw new \LogicException(
					sprintf(
						'form tag class "%s" does not have property "%s"',
						get_called_class(),
						$property
					)
				);
			}
		}

		throw new \BadMethodCallException(
			sprintf(
				'form tag class "%s" does not have method "%s"',
				get_called_class(),
				$method
			)
		);
	}

	/**
	 * @param $tag_or_attribute
	 *
	 * @return bool
	 */
	protected function is_valid_symbol( $tag_or_attribute ) {

		return preg_match( '/^[A-Za-z][A-Za-z0-9_\-]+$/', $tag_or_attribute ) === 1;
	}

	/**
	 * @return bool
	 */
	public function is_text_node() {

		return $this instanceof Text_Node;
	}
}


class Text_Node extends Base_Html_Tag {

	private $text;

	public function __construct( $text = '' ) {

		$this->set_text( $text );
	}

	public function get_text() {
		return $this->text;
	}

	public function set_text( $text ) {
		$this->text = sanitize_text_field( $text );
	}
}


/**
 * Class Button_Tag
 *
 * @package axis_framework\form\tag
 */
class Button_Tag extends Base_Html_Tag {

	public function __construct( $id = '', $name = '', $type = 'button', array $other_attributes = array() ) {

		$other_attributes['type'] = $type;
		
		parent::__construct( 'input', $id, $name, $other_attributes );
	}
}


/**
 * Class Fieldset_Tag
 *
 * @package axis_framework\form\tag
 */
class Fieldset_Tag extends Base_Html_Tag {

	public function __construct( $id = '', $name = '', array $other_attributes = array() ) {

		parent::__construct( 'fieldset', $id, $name, $other_attributes );
	}
}


/**
 * Class Form_Tag
 *
 * @package axis_framework\form\tag
 */
class Form_Tag extends Base_Html_Tag {

	const URL_ENCODED = 'application/x-www-form-urlencoded';
	const MULTIPART   = 'multipart/form-data';
	const TEXT_PLAIN  = 'text/plain';

	public function __construct( $id = '', $name = '', $enctype = self::URL_ENCODED, array $other_attributes = array() ) {

		$other_attributes['enctype'] = $enctype;

		parent::__construct( 'form', $id, $name, $other_attributes );
	}
}


/**
 * Class Input_Tag
 *
 * @package axis_framework\form\tag
 */
class Input_Tag extends Base_Html_Tag {

	public function __construct( $id = '', $name = '', $type = 'text', array $other_attributes = array() ) {

		$other_attributes['type'] = $type;

		parent::__construct( 'input', $id, $name, $other_attributes );
	}
}


/**
 * Class Label_Tag
 *
 * @package axis_framework\form\tag
 */
class Label_Tag extends Base_Html_Tag {

	public function __construct( $id = '', $name = '', $for = '', array $other_attributes = array() ) {

		$other_attributes['for'] = $for;

		parent::__construct( 'label', $id, $name, $other_attributes );
	}
}


/**
 * Class Legend_Tag
 *
 * @package axis_framework\form\tag
 */
class Legend_Tag extends Base_Html_Tag {

	// legend tag only has align attribute, but it is not supported in HTML5
	public function __construct( $id = '', array $other_attributes = array() ) {

		parent::__construct( 'legend', $id, '', $other_attributes );
	}
}


/**
 * Class Select_Tag
 *
 * @package axis_framework\form\tag
 */
class Select_Tag extends Base_Html_Tag {

	public function __construct( $id = '', $name = '', array $other_attributes = array() ) {

		parent::__construct( 'select', $id, $name, $other_attributes );
	}
}


/**
 * Class Optgroup_Tag
 *
 * @package axis_framework\form\tag
 */
class Optgroup_Tag extends Base_Html_Tag {

	// optgroup tag does not have name attribute
	public function __construct( $id = '', $label = '', array $other_attributes = array() ) {

		$other_attributes['label'] = $label;

		parent::__construct( 'optgroup', $id, '', $other_attributes );
	}
}


/**
 * Class Option_Tag
 *
 * @package axis_framework\form\tag
 */
class Option_Tag extends Base_Html_Tag {

	// option tag does not have 'name' attribute
	public function __construct( $id = '', $value = '', $selected = FALSE, array $other_attributes = array() ) {

		$other_attributes['value'] = $value;
		$other_attributes['selected'] = $selected;

		parent::__construct( 'option', $id, '', $other_attributes );
	}
}


/**
 * Class Textarea_Tag
 *
 * @package axis_framework\form\tag
 */
class Textarea_Tag extends Base_Html_Tag {

	public function __construct( $id, $name, array $other_attributes = array() ) {

		parent::__construct( 'textarea', $id, $name, $other_attributes );
	}
}
