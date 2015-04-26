<?php

namespace axis_framework\includes\models;

interface Entity {

	public static function get_table();

	public static function get_searchable_fields();
}