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

	    $this->prepare_script();
	    $this->view_helper( 'axis_sample', 'view-class-test', array(), $context );
    }

	public function prepare_script() {

		$script = new controls\Script_Item(
			'ajax_return_script_handle',
			AXIS_SAMPLE_JS_URL . '/ajax_return.js',
			controls\Script_Item::$ajax_object,
			controls\Script_Item::$ajax_url
		);
		$script->enqueue();
	}
}