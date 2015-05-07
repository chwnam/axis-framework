<?php

namespace axis_framework\models;

\axis_framework\core\utils\check_abspath(); // check abspath or inclusion fatal error.


interface Entity {

	public static function get_table();

	public static function get_searchable_fields();
}