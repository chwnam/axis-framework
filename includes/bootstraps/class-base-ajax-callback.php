<?php

namespace axis_framework\includes\bootstraps;
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-callback.php');
use \axis_framework\includes\core;


abstract class Base_Ajax_Callback extends Base_Callback {

    protected function __construct() {

        parent::__construct();
    }

    public abstract function add_ajax_actions();

    protected function accept_wish_list($wish_list) {

        foreach ( $wish_list as $w ) {
            if ( $w->has_priv() ) {
                add_action( $w->get_hook(), $w->get_callback() );
            }

            if ( $w->has_nopriv() ) {
                add_action( $w->get_hook_nopriv(), $w->get_callback() );
            }
        }

    }

}


/**
 * Class Ajax_Action
 */
class Ajax_Action {

    const PRIV   = 0x01;
    const NOPRIV = 0x02;

    private $hook;
    private $callback;
    private $option;

    function __construct( $hook, $callback, $option = Ajax_Action::PRIV ) {

        $this->hook     = $hook;
        $this->callback = $callback;
        $this->option   = $option;
    }

    public function get_hook() {
        return 'wp_ajax_' . $this->hook;
    }

    public function get_hook_nopriv() {
        return 'wp_ajax_nopriv_' . $this->hook;
    }

    public function get_callback() {
        return $this->callback;
    }

    public function has_priv() {
        return $this->option & Ajax_Action::PRIV;
    }

    public function has_nopriv() {
        return $this->option & Ajax_Action::NOPRIV;
    }
}