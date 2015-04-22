<?php

namespace axis_framework\includes\bootstraps;

use \axis_framework\includes\core;

/**
 * Class Base_Callback
 *
 * Root of all base-*-callbacks.
 *
 * @package axis_framework\includes\bootstraps
 */
class Base_Callback extends core\Singleton {

	/** @var  core\Loader $loader loader object */
	protected $loader;

	protected function __construct( array $args = array() ) {

		if( isset( $args['loader'] ) ) {

			$this->set_loader( $args['loader'] );
		}
	}

	/**
	 * @return core\Loader
	 */
	public function get_loader() {

		return $this->loader;
	}

	/**
	 * @param core\Loader $loader
	 */
	public function set_loader( core\Loader &$loader ) {

		$this->loader = $loader;
	}

    /**
     * helper function for add_action() callback. Get control object and make it run.
     * Also takes care of output, and finishing.
     *
     * @param string $namespace        control class namespace
     * @param string $control_name     control class fully-qualified name
     * @param array  $construct_param  construct parameter
     * @param bool   $output_buffering set TRUE when callback for shortcodes, or set FALSE
     * @param bool   $die              set TRUE when callback for ajax. If $output_buffering is TRUE, this parameter is
     *                                 unused.
     *
     * @return callable callback function for add_action()
     */
    protected function control_helper(
        $namespace,
        $control_name,
        array $construct_param = array(),
        $output_buffering = FALSE,
        $die = FALSE
    ) {
        return function ( $args ) use ( $namespace, $control_name, $construct_param, $output_buffering, $die ) {
            if ( is_array( $args ) && ! empty( $args ) ) {
                $construct_param = array_merge( $construct_param, array( 'callback_args' => $args ) );
            }
            $control = $this->loader->control( $namespace, $control_name, $construct_param );
            if ( $output_buffering ) {
                $control->enable_output_buffer();
            }
            $control->run();
            if ( $output_buffering ) {
                return $control->get_output_buffer();
            }
            if ( $die ) {
                die();
            }
        };
    }
}