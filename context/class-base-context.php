<?php
namespace axis_framework\context;

use axis_framework\core\Loader_trait;


abstract class Base_Context {

	use Loader_Trait;

	/** @var  string $context_name */
	private $context_name;

	/** @var  string $context_identifier */
	private $context_identifier;

	/** @var  \axis_framework\context\Dispatch $dispatch */
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

	/**
	 * @return Dispatch
	 */
	public function get_dispatch() {

		return $this->dispatch;
	}

	/**
	 * @param string $context_name
	 *
	 * @return Base_Context|null
	 */
	public function get_context( $context_name ) {

		return $this->dispatch->get_context( $context_name );
	}

	/**
	 * @param Dispatch $dispatch
	 */
	public function set_dispatch( Dispatch $dispatch ) {

		$this->dispatch = $dispatch;
	}

	/**
	 * @param string $context_name
	 */
	public function set_context_name( $context_name ) {

		$this->context_name = $context_name;
	}

	/**
	 * @return string
	 */
	public function get_context_name() {

		return $this->context_name;
	}

	/**
	 * @return string
	 */
	public function get_context_id() {

		return $this->context_identifier;
	}

	/**
	 *
	 */
	private function set_context_id() {

		$this->context_identifier = $this->context_name . '-' . spl_object_hash( $this );
	}

	/**
	 * helper function for add_action() callback. Get control object and make it run.
	 * Also takes care of output, and finishing.
	 * Note - priority of parameters, when they are all true:
	 *  $output_buffering
	 *  $die
	 *  $need_return
	 *
	 * @param string $namespace        control class namespace
	 * @param string $control_name     control name for loader
	 * @param string $control_function target function. Defaults to 'run'
	 * @param array  $construct_param  construct parameter
	 * @param bool   $output_buffering set TRUE when callback for shortcodes, or set FALSE
	 * @param bool   $die              set TRUE when callback for ajax. If $output_buffering is TRUE, this parameter is
	 *                                 unused.
	 * @param bool $need_return        set TRUE when callback requires return value.
	 *
	 * @return callable callback function for add_action()
	 */
	public function control_helper(
		$namespace,
		$control_name,
		$control_function,
		array $construct_param = array(),
		$output_buffering = FALSE,
		$die = FALSE,
		$need_return = FALSE
	) {

		return
			function () use ( $namespace, $control_name, $control_function, $construct_param, $output_buffering, $die, $need_return ) {

			$args = func_get_args();

			$control = $this->loader->control( $namespace, $control_name, $construct_param );

			if ( $output_buffering ) {
				$control->enable_output_buffer();
			}

				$return = call_user_func_array( array( &$control, $control_function ), $args );

			if ( $output_buffering ) {
				return $control->get_output_buffer();
			}

			if ( $die ) {
				die();
			}

				if( $need_return ) {
					return $return;
				}
		};
	}

	/**
	 * Just a wrapper for AJAX callback
	 *
	 * @see         control_helper(), shortcode_helper()
	 * @param       $namespace
	 * @param       $control_name
	 * @param       $control_function
	 * @param array $construct_param
	 *
	 * @return callable
	 */
	public function ajax_helper(
		$namespace,
		$control_name,
		$control_function,
		array $construct_param = array()
	) {
		return $this->control_helper( $namespace, $control_name, $control_function, $construct_param, FALSE, TRUE );
	}

	/**
	 * * Just a wrapper for shortcode callback
	 *
	 * @see         control_helper(), ajax_helper()
	 * @param       $namespace
	 * @param       $control_name
	 * @param       $control_function
	 * @param array $construct_param
	 *
	 * @return callable
	 */
	public function shortcode_helper(
		$namespace,
		$control_name,
		$control_function,
		array $construct_param = array()
	) {
		return $this->control_helper( $namespace, $control_name, $control_function, $construct_param, TRUE, FALSE );
	}

	/**
	 * Wrapper for calling protected member methods
	 *
	 * @param     $method
	 * @param int $accepted_args
	 *
	 * @return callable
	 */
	protected function context_callback( $method, $accepted_args = 1  ) {

		if( !method_exists( $this, $method ) ) {
			throw new \LogicException(
				sprintf( "method '%s' not found in context %s", $method, $this->context_name )
			);
		}

		return function() use ( $method, $accepted_args ) {
			return call_user_func_array( array( $this, $method ), array_slice( func_get_args(), 0, $accepted_args ) );
		};
	}

	/**
	 * Our add_action() shorthand
	 *
	 * @param        $tag
	 * @param string $function
	 * @param int    $priority
	 * @param int    $accepted_args
	 */
	protected function add_context_action( $tag, $function = '', $priority = 10, $accepted_args = 1 ) {

		if( empty( $function ) ) {
			$function = $this->context_callback( $tag . '_callback', $accepted_args );
		}

		add_action( $tag, $function, $priority, $accepted_args );
	}

	/**
	 * Our add_filter() shorthand
	 *
	 * @param        $tag
	 * @param string $function
	 * @param int    $priority
	 * @param int    $accepted_args
	 */
	protected function add_context_filter( $tag, $function = '', $priority = 10, $accepted_args = 1 ) {

		if( empty( $function ) ) {
			$function = $this->context_callback( $tag . '_callback', $accepted_args );
		}

		add_filter( $tag, $function, $priority, $accepted_args );
	}

	/**
	 * @param       $template_name
	 *
	 * @return callable
	 */
	protected function render_template( $template_name ) {

		return function() use ( $template_name ) {
			$view = $this->loader->generic_view();
			echo $view->render( $template_name );
		};
	}

	public abstract function init_context();
}