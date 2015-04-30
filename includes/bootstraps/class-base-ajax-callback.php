<?php

namespace axis_framework\includes\bootstraps;

use \axis_framework\includes\core;


/**
 * Class Base_Ajax_Callback
 *
 * @package axis_framework\includes\bootstraps
 */
abstract class Base_Ajax_Callback extends Base_Callback {

	protected function __construct( array $args = array() ) {

		parent::__construct( $args );
	}

	public abstract function add_ajax_actions();

	/**
	 * @param array $wish_list array of Ajax_Action
	 */
	protected function accept_wish_list( array &$wish_list ) {

		foreach ( $wish_list as &$w ) {

			/** @var Ajax_Action $w */
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

	/**
	 * @return string the name of hook
	 */
	public function get_hook() {

		return 'wp_ajax_' . $this->hook;
	}

	/**
	 * @return string the name of hook, no-privileged.
	 */
	public function get_hook_nopriv() {

		return 'wp_ajax_nopriv_' . $this->hook;
	}

	/**
	 * @return mixed the callback
	 */
	public function get_callback() {

		return $this->callback;
	}

	/**
	 * @return bool Does the object have priv (privileged) hook?
	 */
	public function has_priv() {

		return (bool)( $this->option & Ajax_Action::PRIV );
	}

	/**
	 * @return bool Does the object have no-priv (unprivileged) hook?
	 */
	public function has_nopriv() {

		return (bool)( $this->option & Ajax_Action::NOPRIV );
	}
}