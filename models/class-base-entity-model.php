<?php

namespace axis_framework\models;

use axis_framework\core;

require_once( 'interface-entity.php' );  // entity interface must be earlier than entity model


abstract class Base_Entity_Model implements Entity {

	public function __construct( $args ) {

		$model_properties = $this->properties();
		$properties       = array_intersect_key( $args, $model_properties );

		foreach ( $properties as $property => &$value ) {
			$this->{$property} = maybe_unserialize( $value );
		}
	}

	public function __call( $function, $arguments ) {

		// Getters following the pattern 'get_{$property}'
		if ( strpos( $function, 'get_' ) === 0 ) {

			$property = substr( $function, 4 );
			if( isset( $this->{$property} ) ) {
				return $this->{$property};
			} else {
				throw new \LogicException(
					sprintf(
						'class "%s" does not have property "%s"',
						get_called_class(),
						$property
					)
				);
			}
		}

		// Setters following the pattern 'set_{$property}'
		else if ( strpos( $function, 'set_' ) === 0 ) {

			$property = substr( $function, 4 );

			if ( isset( $this->{$property} ) ) {

				$this->{$property} = $arguments[0];

			} else {
				throw new \LogicException(
					sprintf(
						'class "%s" does not have property "%s"',
						get_called_class(),
						$property
					)
				);
			}
		}

		return NULL;
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

		if ( is_null( $properties[ $pk ] ) ) {

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

		$query = new core\Query( get_called_class() );
		$query->set_searchable_fields( static::get_searchable_fields() );
		$query->set_primary_key( static::primary_key_name() );

		return $query;
	}

	public static function flatten_properties( $properties ) {

		foreach ( $properties as $property => $value ) {

			if ( is_object( $value ) ) {

				$class_name = get_class( $value );

				if ( $class_name == 'DateTime' ) {

					/** @var \DateTime $value */
					$properties[ $property ] = $value->format( 'Y-m-d H:i:s' );

				} else if ( $class_name == 'stdClass' ) {

					/** @var \stdClass $value */
					$properties[ $property ] = serialize( $value );
				}

			} else if ( is_array( $value ) ) {

				/** @var array $value */
				$properties[ $property ] = serialize( $value );

			} else if ( $value instanceof Base_Entity_Model ) {

				/** @var Base_Entity_Model $value */
				$properties[ $property ] = $value->primary_key();
			}
		}

		return $properties;
	}

	public static function find_one( $id ) {

		return static::find_one_by( static::primary_key_name(), (int) $id );
	}

	public function find_one_by( $field, $value ) {

		/** @var \wpdb $wpdb */
		global $wpdb;

		$value = esc_sql( $value );
		$table = static::get_table();

		$obj = $wpdb->get_row( "SELECT * FROM `{$table}` WHERE `{$field}` = '{$value}'", ARRAY_A );

		// Return false if no item was found, or a new model
		return ( $obj ? static::create( $obj ) : FALSE );
	}

	public static function all() {

		/** @var \wpdb $wpdb */
		global $wpdb;

		$table   = static::get_table();
		$results = $wpdb->get_results( "SELECT * FROM `{$table}`" );

		foreach ( $results as $index => &$result ) {
			$results[ $index ] = static::create( (array) $result );
		}

		return $results;
	}

	public static function get_first() {

		/** @var \wpdb $wpdb */
		global $wpdb;

		$table       = static::get_table();
		$primary_key = static::primary_key_name();

		$obj = $wpdb->get_row( "SELECT * FROM `{$table}` ORDER BY {$primary_key} ASC LIMIT 1", ARRAY_A );

		return ( $obj ? static::create( $obj ) : FALSE );
	}

	public static function get_last() {

		/** @var \wpdb $wpdb */
		global $wpdb;

		$table       = static::get_table();
		$primary_key = static::primary_key_name();

		$obj = $wpdb->get_row( "SELECT * FROM `{$table}` ORDER BY {$primary_key} DESC LIMIT 1", ARRAY_A );

		return ( $obj ? static::create( $obj ) : FALSE );
	}

	public function to_array() {

		return $this->properties();
	}

	public static function get_table_prefix() {

		/** $var \wpdb $wpdb */
		global $wpdb;

		return $wpdb->prefix;
	}
}