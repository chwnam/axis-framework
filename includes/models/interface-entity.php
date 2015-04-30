<?php

namespace axis_framework\includes\models;

\axis_framework\includes\core\utils\check_abspath(); // check abspath or inclusion fatal error.


interface Entity {

	public static function get_table();

	public static function get_searchable_fields();
}