<?php

namespace axis_framework\views;

\axis_framework\core\utils\check_abspath(); // check abspath or inclusion fatal error.


class View_Block {

    const APPEND = 'append';
    const PREPEND = 'prepend';

    protected $blocks = array();
    protected $active = array();

    public function begin( $name ) {

        if( in_array( $name, $this->active) ) {

            throw new \RuntimeException( sprintf( "%s is now active.", $name ) );
        }

        $this->active[] = $name;
        ob_start();
    }

    public function end() {

        if( !empty( $this->active ) ) {

            $active = end( $this->active );
            $content = ob_get_clean();
            $this->blocks[ $active ] = $content;
            array_pop( $this->active );
        }
    }

    public function concat( $name, $value, $mode = View_Block::APPEND ) {

        if( !isset( $this->blocks[ $name ] ) ) {
            $this->blocks[ $name ] = '';
        }

        if( $mode == View_Block::PREPEND ) {
            $this->blocks[ $name ] = $value . $this->blocks[ $name ];
        } else {
            $this->blocks[ $name ] .= $value;
        }
    }

    public function set( $name, $value ) {

        $this->blocks[ $name ] = $value;
    }

    public function get( $name, $default = '' ) {

        return isset( $this->blocks[ $name ] ) ? $this->blocks[ $name ] : $default;
    }

    public function exists( $name ) {

        return isset( $this->blocks[ $name ] );
    }

    public function keys() {

        return array_keys( $this->blocks );
    }

    public function active() {

        return end( $this->active );
    }

    public function unfinished() {

        return $this->active;
    }
}