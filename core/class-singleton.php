<?php

namespace axis_framework\core;

\axis_framework\core\utils\check_abspath(); // check abspath or inclusion fatal error.


/**
 * Class Singleton
 *
 * For creating unique objects.
 *
 * @package axis_framework\includes\core
 * @author Changwoo Nam (cs.chwnam@gmail.com)
 * @see    \axis_framework\includes\bootstraps\Base_Callback
 */
class Singleton {

	/**
	 * @return static unique instance
	 */
    public static function get_instance() {

        static $instance = NULL;

        if ( NULL === $instance ) {
            $instance = new static();
        }

        return $instance;

    }

    /**
     * protect creating new instance
     */
    protected function __construct() {
    }

    /**
     * prevent cloning
     */
    private function __clone() {
    }

    /**
     * prevent un-serializing
     */
	/** @noinspection PhpUnusedPrivateMethodInspection */
	private function __wakeup() {
    }

}