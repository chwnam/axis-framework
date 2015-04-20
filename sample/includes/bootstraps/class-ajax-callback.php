<?php
namespace axis_sample; // The namespace should be equal to all callback classes!

require_once(AXIS_INC_BOOTSTRAP_PATH . '/class-base-ajax-callback.php');

use axis_framework\includes\bootstraps;

class Ajax_Callback extends bootstraps\Base_Ajax_Callback {

    public function __construct() {
        parent::__construct();
    }

    public function add_ajax_actions() {

        $wish_list = array(

            new bootstraps\Ajax_Action(
                'axis_sample_test_action',
                array($this, 'axis_sample_test_action'),
                bootstraps\Ajax_Action::PRIV
            ),

	        new bootstraps\Ajax_Action(
		        'axis_sample_ajax_return',
		        $this->control_helper( 'axis_sample', 'ajax-return', array(), FALSE, TRUE )
	        )
        );

        $this->accept_wish_list($wish_list);
    }

    public function axis_sample_test_action() {

        $param = esc_attr( $_GET['param'] );
        echo "You said $param";
        die();
    }
}