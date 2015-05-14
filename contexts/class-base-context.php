<?php
namespace axis_framework\contexts;

use axis_framework\core\Loader_trait;


abstract class Base_Context {

	use Loader_Trait;

	/** @var  string $context_name */
	private $context_name;

	/** @var  string $context_identifier */
	private $context_identifier;

	/** @var  \axis_framework\contexts\Dispatch $dispatch */
	private $dispatch;

	public function __construct( array $args = array() ) {

		if( isset( $args['loader'] ) ) {

			$this->set_loader( $args['loader'] );
		}

		if( isset( $args['dispatch'] ) ) {

			$this->set_dispatch( $args['dispatch'] );
		}

		if( isset( $args['context_name'] ) && is_string( $args['context_name'] ) && !empty( $args['context_name'] ) ) {

			$this->set_context_name( $args['context_name'] );
			$this->set_context_id();
		} else {

			throw new \LogicException( 'Every context must have name. set \'context_name\' key and value.' );
		}
	}

	public function get_dispatch() {

		return $this->dispatch;
	}

	public function get_context( $context_name ) {

		return $this->dispatch->get_context( $context_name );
	}

	public function set_dispatch( Dispatch $dispatch ) {

		$this->dispatch = $dispatch;
	}

	public function set_context_name( $context_name ) {

		$this->context_name = $context_name;
	}

	public function get_context_name() {

		return $this->context_name;
	}

	public function get_context_id() {

		return $this->context_identifier;
	}

	private function set_context_id() {

		$this->context_identifier = $this->context_name . '-' . spl_object_hash( $this );
	}

	/**
	 * helper function for add_action() callback. Get control object and make it run.
	 * Also takes care of output, and finishing.
	 *
	 * @param string $namespace        control class namespace
	 * @param string $control_name     control name for loader
	 * @param string $control_function target function. Defaults to 'run'
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
		$control_function = 'run',
		array $construct_param = array(),
		$output_buffering = FALSE,
		$die = FALSE
	) {
		return function ( $args ) use ( $namespace, $control_name, $control_function, $construct_param, $output_buffering, $die ) {

			if ( ! empty( $args ) ) {

				$construct_param = array_merge( $construct_param, array( 'callback_args' => $args ) );
			}

			$control = $this->loader->control( $namespace, $control_name, $construct_param );

			if ( $output_buffering ) {

				$control->enable_output_buffer();
			}

			call_user_func( array( &$control, $control_function ) );

			if ( $output_buffering ) {

				return $control->get_output_buffer();
			}

			if ( $die ) {
				die();
			}
		};
	}

	public abstract function init_context();
}