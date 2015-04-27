<?php

namespace axis_framework\includes\models;

require_once AXIS_INC_MODEL_PATH . '/class-base-entity-model.php';


class Page_Model extends Post_Model {

	protected $post_type = 'page';

	public static function query() {

		/** @var \axis_framework\includes\core\Query $query */
		$query = parent::query();
		$query->where( 'post_type', 'page' );

		return $query;
	}
}