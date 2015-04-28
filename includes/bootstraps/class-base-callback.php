<?php
/**
 * Base callback
 */

namespace axis_framework\includes\bootstraps;

use \axis_framework\includes\core;

/**
 * Class Base_Callback
 *
 * Root of all base-*-callbacks.
 *
 * @package axis_framework\includes\bootstraps
 * @author Changwoo Nam (cs.chwnam@gmail.com)
 */
class Base_Callback extends core\Singleton {

	/**
	 * loader trait
	 */
	use core\Loader_Trait;

	/**
	 * @param array $args key - value pairs to initialize the object.
	 *                    key 'loader' is reserved for loader object.
	 */
	protected function __construct( array $args = array() ) {

		if( isset( $args['loader'] ) ) {

			$this->set_loader( $args['loader'] );
		}
	}

    /**
     * helper function for add_action() callback. Get control object and make it run.
     * Also takes care of output, and finishing.
     *
     * @param string $namespace        control class namespace
     * @param string $control_name     control name for loader
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
            if ( !empty( $args ) ) {
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