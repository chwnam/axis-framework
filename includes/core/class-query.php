<?php

namespace axis_framework\includes\core;


class Query {

	const ORDER_ASC  = 'ASC';
	const ORDER_DESC = 'DESC';

	protected $limit  = 0;
	protected $offset = 0;

	protected $where    = array();
	protected $order_by = 'id';

	protected $order       = self::ORDER_ASC;
	protected $search_term = NULL;

	protected $search_fields = array();

	protected $model;

	protected $primary_key;

	public function __construct() {

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

	public function compose() {

		$model = $this->model;
		$table = $model->get_table();
		$where = '';
		$order = '';
		$limit = '';
		$offset = '';


	}
}