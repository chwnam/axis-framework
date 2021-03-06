<?php

namespace axis_framework\models;


class Page_Model extends Post_Model {

	protected $post_type = 'page';

	public static function query() {

		/** @var \axis_framework\includes\core\Query $query */
		$query = parent::query();
		$query->where( 'post_type', 'page' );

		return $query;
	}
}