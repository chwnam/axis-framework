<?php

namespace axis_framework\model;

\axis_framework\core\util\check_abspath(); // check abspath or inclusion fatal error.


interface Entity {

	public static function get_table();

	public static function get_searchable_fields();
}