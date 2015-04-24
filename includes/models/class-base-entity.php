<?php
namespace axis_framework\includes\models;


abstract class Base_Table implements Table_Interface {

	public static function primary_key_name() {

		return 'id';
	}

	public static function create( $properties ) {

		return new static( $properties );
	}

	public function __construct( array $properties = array() ) {

		$model_properties = $this->properties();
		$properties = array_intersect( $properties, $model_properties );

		foreach( $properties as $property => &$value ) {
			$this->{$property} = maybe_unserialize( $value );
		}
	}

	public function primary_key() {

		return $this->{static::primary_key_name()};
	}

	public function properties() {

		$properties = get_object_vars( $this );
		return $properties;
	}

	public function flatten_properties( $properties ) {

		foreach( $properties as $property => $value ) {

			if( is_object( $value ) ) {

				$class_name = get_class( $value );

				if( $class_name == 'DateTime' ) {

					/** @var \DateTime $value */
					$properties[ $property ] = $value->format( 'Y-m-d H:i:s' );

				} else if( $class_name == 'stdClass' ) {

					/** @var \stdClass $value */
					$properties[ $property ] = serialize( $value );
				}

			} else if( is_array( $value ) ) {

				/** @var array $value */
				$properties[ $property ] = serialize( $value );

			} else if( $value instanceof Base_Model ) {

				/** @var Base_Table $value */
				$properties[ $property ] = $value->primary_key();
			}
		}

		return $properties;
	}

	public function save() {

		/** @var \wpdb $wpdb */
		global $wpdb;

		$properties = $this->flatten_properties( $this->properties() );
		$pk         = static::primary_key_name();

		if( is_null( $properties[ $pk ] ) ) {

			$wpdb->insert( $this->get_table(), $properties );
			$this->{$pk} = $wpdb->insert_id;

		} else {

			$wpdb->update(
				$this->get_table(),
				$properties,
				array(
					$pk => $this->{$pk}
				)
			);
		}

		return $this->{$pk};
	}
}