<?php

namespace axis_framework\bootstraps;

require_once( AXIS_FRAMEWORK_PATH . '/bootstraps/settings/settings.php' );

use \axis_framework\core;
use \axis_framework\bootstraps\settings;


/**
 * Class Base_Settings_Callback
 *
 * @package axis_framework\includes\bootstraps
 */
abstract class Base_Settings_Callback extends Base_Callback {

	protected function __construct( array $args = array() ) {

		parent::__construct( $args );
	}

	public abstract function add_option_page();

	public abstract function register_settings();

	public abstract function add_settings_sections();

	public abstract function add_settings_fields();
}