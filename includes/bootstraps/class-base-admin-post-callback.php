<?php

namespace axis_framework\includes\bootstraps;
require_once( AXIS_INC_BOOTSTRAP_PATH . '/class-base-callback.php' );
use \axis_framework\includes\core;


abstract class Base_Admin_Post_Callback extends Base_Callback {

	protected function __construct() {

		parent::__construct();
	}

	/**
	 * Complete your admin-post actions with Admin_Post_Action class
	 *
	 * @return void
	 */
	public abstract function add_admin_post_actions();

	/**
	 * @param array $wish_list array of Admin_Post_Action
	 */
	protected function accept_wish_list( array $wish_list ) {

		foreach ( $wish_list as $w ) {

			/** @var Admin_Post_Action $w */
			add_action( $w->get_hook(), $w->get_callback() );
		}
	}
}


/**
 * Class Admin_Post_Action
 */
class Admin_Post_Action {

	private $hook;
	private $callback;

	function __construct( $hook, $callback ) {

		$this->hook     = $hook;
		$this->callback = $callback;
	}

	public function get_hook() {

		return 'admin_post_' . $this->hook;
	}

	public function get_callback() {

		return $this->callback;
	}
}