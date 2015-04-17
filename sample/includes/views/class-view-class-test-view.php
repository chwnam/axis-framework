<?php

namespace axis_sample;

use axis_framework\includes\views;


class View_Class_Test_View extends views\Base_View {

    public function __construct( $args ) {

        parent::__construct( $args );
    }

    public function render( array $context = array() ) {

        $this->loader->template( 'test-template', $this, $context );
    }
}