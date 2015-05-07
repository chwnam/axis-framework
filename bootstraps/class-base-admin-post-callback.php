<?php

namespace axis_framework\bootstraps;

use \axis_framework\core;


/**
 * Class Base_Admin_Post_Callback
 *
 * @package axis_framework\includes\bootstraps
 */
abstract class Base_Admin_Post_Callback extends Base_Callback {

	protected function __construct( array $args = array() ) {

		parent::__construct( $args );
	}

	/**
	 * Complete your admin-post actions with Admin_Post_Action class
	 */
	public abstract function add_admin_post_actions();

	/**
	 * @param array $wish_list array of Admin_Post_Action
	 */
	protected function accept_wish_list( array &$wish_list ) {

		foreach ( $wish_list as &$w ) {

			/** @var Admin_Post_Action $w */
			add_action( $w->get_hook(), $w->get_callback() );
		}
	}
}


/**
 * Class Admin_Post_Action
 *
 * @package axis_framework\includes\bootstraps
 */
class Admin_Post_Action {

	private $hook;
	private $callback;

	/**
	 * @param string $hook      the name of hook
	 * @param mixed  $callback  the callback of action
	 */
	function __construct( $hook, $callback ) {

		$this->hook     = $hook;
		$this->callback = $callback;
	}

	/**
	 * @return string name of the hook
	 */
	public function get_hook() {

		return 'admin_post_' . $this->hook;
	}

	/**
	 * @return mixed callback of the action
	 */
	public function get_callback() {

		return $this->callback;
	}
}