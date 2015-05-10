<?php

namespace axis_framework\core;

\axis_framework\core\utils\check_abspath(); // check abspath or inclusion fatal error.


class Query {

	const ORDER_ASC  = 'ASC';
	const ORDER_DESC = 'DESC';

	protected $limit  = 0;
	protected $offset = 0;

	protected $where     = array();
	protected $_order_by = array();

	protected $search_term = NULL;

	protected $search_fields = array();

	/** @var string $model Model's class name */
	protected $model;

	protected $primary_key;

	public function __construct( $model ) {

		$this->model = $model;
	}

	public function set_searchable_fields( array &$fields ) {

		$this->search_fields = $fields;
	}

	public function set_primary_key( $primary_key ) {

		$this->primary_key = $primary_key;
		$this->_order_by   = array( array( 'field' => $primary_key, 'order' => self::ORDER_ASC ) );
	}

	public function reset() {

		$this->limit     = 0;
		$this->offset    = 0;
		$this->where     = array();
		$this->_order_by = array();
		$search_term     = NULL;

		return $this;
	}

	public function limit( $limit ) {

		$this->limit = (int) $limit;

		return $this;
	}

	public function offset( $offset ) {

		$this->offset = (int) $offset;

		return $this;
	}

	public function order_by( $field, $order = self::ORDER_ASC ) {

		$this->_order_by[] = array( 'field' => $field, 'order' => $order );

		return $this;
	}

	public function clear_order_by() {

		$this->_order_by = array();

		return $this;
	}

	public function where( $column, $value ) {

		$this->where[] = array( 'type' => 'where', 'column' => $column, 'value' => $value );

		return $this;
	}

	public function where_not( $column, $value ) {

		$this->where[] = array( 'type' => 'not', 'column' => $column, 'value' => $value );

		return $this;
	}

	public function where_like( $column, $value ) {

		$this->where[] = array( 'type' => 'like', 'column' => $column, 'value' => $value );

		return $this;
	}

	public function where_not_like( $column, $value ) {

		$this->where[] = array( 'type' => 'not_like', 'column' => $column, 'value' => $value );

		return $this;
	}

	public function where_lt( $column, $value ) {

		$this->where[] = array( 'type' => 'lt', 'column' => $column, 'value' => $value );

		return $this;
	}

	public function where_lte( $column, $value ) {

		$this->where[] = array( 'type' => 'lte', 'column' => $column, 'value' => $value );

		return $this;
	}

	public function where_gt( $column, $value ) {

		$this->where[] = array( 'type' => 'gt', 'column' => $column, 'value' => $value );

		return $this;
	}

	public function where_gte( $column, $value ) {

		$this->where[] = array( 'type' => 'gte', 'column' => $column, 'value' => $value );

		return $this;
	}

	public function where_in( $column, array $in ) {

		$this->where[] = array( 'type' => 'in', 'column' => $column, 'value' => $in );

		return $this;
	}

	public function where_not_in( $column, array $not_in ) {

		$this->where[] = array( 'type' => 'not_in', 'column' => $column, 'value' => $not_in );

		return $this;
	}

	public function where_any( array $where ) {

		$this->where[] = array( 'type' => 'any', 'where' => $where );

		return $this;
	}

	public function where_all( array $where ) {

		$this->where[] = array( 'type' => 'all', 'where' => $where );

		return $this;
	}

	public function search( $search_term ) {

		$this->search_term = $search_term;

		return $this;
	}

	public function find( $for_row_counting = FALSE ) {

		/** @var \wpdb $wpdb */
		global $wpdb;

		$query = $this->compose_query( $for_row_counting );

		if( $for_row_counting ) {

			return (int) $wpdb->get_var( $query );
		}

		$results = $wpdb->get_results( $query );
		if( $results ) {

			/** @var \axis_framework\models\Base_Entity_Model $model */
			$model = $this->model;

			foreach( $results as $index => &$result ) {
				$results[ $index ] = $model::create( (array) $result );
			}
		}

		return $results;
	}

	/**
	 * @param bool $query_for_row_counting
	 *
	 * @return string
	 */
	public function compose_query( $query_for_row_counting = FALSE ) {

		/** @var \axis_framework\models\Base_Entity_Model $model */
		$model = $this->model;
		$table = $model::get_table();

		$where  = '';
		$order  = '';
		$limit  = '';
		$offset = '';

		// Search
		if ( ! empty( $this->search_term ) ) {

			$where .= ' AND (';

			foreach ( $this->search_fields as $field ) {

				$where .= '`' . $field . '` LIKE "%' . esc_sql( $this->search_term ) . '%" OR ';
			}

			$where = substr( $where, 0, - 4 ) . ')';
		}

		// Where
		foreach ( $this->where as $q ) {

			switch ( $q['type'] ) {

				case 'where':
					$where .= ' AND `' . $q['column'] . '` = "' . esc_sql($q['value']) . '"';
					break;
				case 'not':
					$where .= ' AND `' . $q['column'] . '` != "' . esc_sql($q['value']) . '"';
					break;
				case 'like':
					$where .= ' AND `' . $q['column'] . '` LIKE "' . esc_sql($q['value']) . '"';
					break;
				case 'not_like':
					$where .= ' AND `' . $q['column'] . '` NOT LIKE "' . esc_sql($q['value']) . '"';
					break;
				case 'lt':
					$where .= ' AND `' . $q['column'] . '` < "' . esc_sql($q['value']) . '"';
					break;
				case 'lte':
					$where .= ' AND `' . $q['column'] . '` <= "' . esc_sql($q['value']) . '"';
					break;
				case 'gt':
					$where .= ' AND `' . $q['column'] . '` > "' . esc_sql($q['value']) . '"';
					break;
				case 'gte':
					$where .= ' AND `' . $q['column'] . '` >= "' . esc_sql($q['value']) . '"';
					break;

				case 'in':
					$where .= ' AND `' . $q['column'] . '` IN (';
					foreach ( $q['value'] as $value ) {
						$where .= '"' . esc_sql( $value ) . '",';
					}
					$where = substr( $where, 0, - 1 ) . ')';
					break;

				case 'not in':
					$where .= ' AND `' . $q['column'] . '` NOT IN (';
					foreach ($q['value'] as $value) {
						$where .= '"' . esc_sql($value) . '",';
					}
					$where = substr($where, 0, -1) . ')';
					break;

				case 'all':
					$where .= ' AND (';
					foreach ( $q['where'] as $column => $value ) {
						$where .= '`' . $column . '` = "' . esc_sql( $value ) . '" AND ';
					}
					$where = substr( $where, 0, - 5 ) . ')';
					break;

                case 'any':
	                $where .= ' AND (';

	                foreach ( $q['where'] as $column => $value ) {
		                $where .= '`' . $column . '` = "' . esc_sql( $value ) . '" OR ';
	                }
	                $where = substr( $where, 0, - 5 ) . ')';
					break;
			}
		}

		// Finish where clause
		if ( ! empty( $where ) ) {
			$where = ' WHERE ' . substr( $where, 5 );
		}

		// Order
		foreach( $this->_order_by as $order_by ) {

			$field = $order_by['field'];
			$ord   = isset( $order_by['order'] ) ? $order_by['order'] : self::ORDER_ASC;

			if( strstr( $field, '(' ) !== FALSE && strstr( $field, ')' ) !== FALSE ) {
				$order .= ' ' . $field . ' ' . $ord . ', ';     // $field is function
			} else {
				$order .= ' `' . $field . '`` ' . $ord . ', ';
			}
		}

		if( !empty( $order ) ) {
			$order = ' ORDER BY ' . substr( $order, 0, -2 );
		}

		// Limit
		if ( $this->limit > 0 ) {
			$limit = ' LIMIT ' . $this->limit;
		}

		// Offset
		if ( $this->offset > 0 ) {
			$offset = ' OFFSET ' . $this->offset;
		}

		// Query
		if ( $query_for_row_counting ) {
			return "SELECT COUNT(*) FROM `{$table}`{$where}";
		}

		return "SELECT * FROM `{$table}`{$where}{$order}{$limit}{$offset}";
	}

	public function total_count() {

		return $this->find( TRUE );
	}
}