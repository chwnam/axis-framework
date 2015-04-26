<?php

namespace axis_framework\includes\models;

require_once( AXIS_INC_CORE_PATH . '/class-query.php' );

use axis_framework\includes\core;


abstract class Base_Entity_Model implements Entity {

	public function __construct( $args ) {

		$model_properties = $this->properties();
		$properties = array_intersect( $args, $model_properties );

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

	public function delete() {

		/** @var \wpdb $wpdb */
		global $wpdb;

		return $wpdb->delete(
			static::get_table(),
			array( static::primary_key_name() => $this->{static::primary_key_name()} )
		);
	}

	public static function primary_key_name() {

		return 'id';
	}

	public static function create( $properties ) {

		return new static( $properties );
	}

	public static function query() {

		static $query = NULL;

		if( is_null( $query ) ) {

			$query = new core\Query( get_called_class() );
			$query->set_searchable_fields( static::get_searchable_fields() );
			$query->set_primary_key( static::primary_key_name() );
		}

		return $query->reset();
	}

	public static function flatten_properties( $properties ) {

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

			} else if( $value instanceof Base_Entity_Model ) {

				/** @var Base_Entity_Model $value */
				$properties[ $property ] = $value->primary_key();
			}
		}

		return $properties;
	}

	public static function get_table_prefix() {

		/** $var \wpdb $wpdb */
		global $wpdb;
		return $wpdb->prefix;
	}
}