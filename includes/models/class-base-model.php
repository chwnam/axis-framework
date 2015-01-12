<?php

namespace axis_framework\includes\models;

use axis_framework\includes\core;

abstract class Base_Model {

	protected $loader;

	public function __construct() {
	}

	public function set_loader( &$loader) {
		$this->loader = $loader;
	}
}