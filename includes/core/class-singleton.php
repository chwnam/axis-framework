<?php

namespace axis_framework\includes\core;


class Singleton {

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
     * prevent unserializing
     */
    private function __wakeup() {
    }

}