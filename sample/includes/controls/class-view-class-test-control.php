<?php

namespace axis_sample;

use axis_framework\includes\controls;


class View_Class_Test_Control extends controls\Base_Control {

    public function __construct( $args ) {

        parent::__construct( $args );
    }

    public function run() {

        $post = new \stdClass();
        $post->title = 'post title';
        $post->body  = 'post body!';

        $context = array(
            'val'      => 1,
            'arr'      => array( 1, 2, 3, 4, 5 ),
            'post'     => $post,
        );

        /** @var View_Class_Test_View $view */
        $view = $this->loader->view_class( 'axis_sample', 'view-class-test' );
        echo $view->render( 'test-template', $context );
    }
}