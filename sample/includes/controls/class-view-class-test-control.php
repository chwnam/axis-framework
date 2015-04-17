<?php

namespace axis_sample;

use axis_framework\includes\controls;


class View_Class_Test_Control extends controls\Base_Control {

    public function __construct( $args ) {

        parent::__construct( $args );
    }

    public function run() {

        $v = new \stdClass();
        $v->var1 = 'a';
        $v->var2 = 'b';

        $context = array(
            'val'      => 1,
            'arr'      => array( 1, 2, 3, 4, 5 ),
            'cls'      => &$v,
        );

        /** @var View_Class_Test_View $view */
        $view = $this->loader->view_class( 'axis_sample', 'view-class-test' );
        $view->render( $context );
    }
}