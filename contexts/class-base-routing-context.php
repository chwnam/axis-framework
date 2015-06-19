<?php
namespace axis_framework\contexts;

/**
 * Class Base_Routing_Context
 *
 * Base routing context.
 * Please override __construct() method in your inherited class.
 * Use 'delivery' parameter to handle response automatically by the specified control class.
 *
 * @package axis_framework\contexts
 */
class Base_Routing_Context extends Base_Context {

	protected $query_vars         = array();
	protected $rewrite_rules      = array();
	protected $matched_query_vars = array();
	protected $delivery           = array();

	/**
	 * @param array $args {
	 *    $query_vars     array  An array of string. Query variables to add.
	 *                           WordPress requires these terms to parse externally defined custom query variables
	 *                           correctly.
	 *    $rewrite_rules  array  Array of array. Each rewrite rule argument is used for add_rewrite_rule()
	 *    $delivery       array  Key is a query var defined in $query_vars.
	 *                           Value is an array its elements are namespace, object name, function name,
	 *                           and fallback function name, respectively. If fallback function name is omit or empty,
	 *                           then 404 not found default template is used..
	 * }
	 */
	public function __construct( $args = [ ] ) {

		parent::__construct( $args );

		if( isset( $args['query_vars'] ) && is_array( $args['query_vars'] ) && !empty( $args['query_vars'] ) ) {
			$this->query_vars = $args['query_vars'];
		}

		if( isset( $args['delivery'] ) && is_array( $args['delivery'] ) && !empty( $args['delivery'] ) ) {
			foreach( $args['delivery'] as $key => &$delivery ) {
				if( is_array( $delivery ) ) {
					$len = count( $delivery );
					if( $len == 3 ) {
						$this->delivery[ $key ] = $delivery;
					} else if ( $len == 2 ) {
						$this->delivery[ $key ] = array( $delivery[0], $delivery[1], '' );
					} else {
						throw new \LogicException( 'Bad delivery parameter: ' . implode( ', ', $delivery ) );
					}
				}
			}
		}

		$this->init_rewrite_rules( $args['rewrite_rules'] );
	}

	public function init_rewrite_rules( $rules ) {

		if ( isset( $rules ) && is_array( $rules ) && ! empty( $rules ) ) {
			foreach ( $rules as $rule ) {
				$len = count( $rule );
				if ( $len == 3 ) {
					$this->rewrite_rules[] = $rule;
				} else if ( $len == 2 ) {
					$this->rewrite_rules[] = array( $rule[0], $rule[1], 'top' );
				} else {
					throw new \LogicException( 'Bad rewrite rule: ' . implode( ', ', $rule ) );
				}
			}
		}
	}

	/**
	 * action: init
	 * @used-by init_context()
	 */
	public function add_rewrite_rules() {
		foreach ( $this->rewrite_rules as $rule ) {
			add_rewrite_rule( $rule[0], $rule[1], $rule[2] );
		}
	}

	public function init_context() {

		// add query vars so that core can understand our custom variables.
		add_action(
			'query_vars',
			function ( $vars ) {
				return array_merge( $vars, $this->query_vars );
			}
		);

		// add response to our custom variables then our template callback can play its role.
		add_action(
			'parse_request',
			function () {
				global $wp;

				$this->matched_query_vars = array();
				foreach ( $this->query_vars as $qv ) {
					if ( isset( $wp->query_vars[ $qv ] ) && !empty( $wp->query_vars[ $qv ] ) ) {
						$this->matched_query_vars[ $qv ] = $wp->query_vars[ $qv ];
					}
				}

				if ( !empty( $this->matched_query_vars ) ) {
					reset( $this->matched_query_vars );
					$key = key( $this->matched_query_vars );
					if( isset( $this->delivery[ $key ] ) ) {
						add_action( 'template_redirect', array( $this, 'control_delivery' ) );
					} else {
						add_action( 'template_redirect', array( &$this, 'template_redirect_callback' ) );
					}
				}
			}
		);

		add_action( 'init', array( &$this, 'add_rewrite_rules' ) );

		$main_file = $this->get_dispatch()->get_main_file();
		register_activation_hook( $main_file, array( &$this, 'activation_hook_callback') );
		register_deactivation_hook( $main_file, array( &$this, 'deactivation_hook_callback') );
	}

	/**
	 * caller: register_activation_hook
	 *
	 * @used-by init_context()
	 */
	public function activation_hook_callback() {
		$this->add_rewrite_rules();
		flush_rewrite_rules();
	}

	/**
	 * caller: register_deactivation_hook
	 *
	 * @used-by init_context()
	 */
	public function deactivation_hook_callback() {
		flush_rewrite_rules();
	}

	/**
	 * default redirect callback.
	 */
	public function template_redirect_callback() {
		die();
	}

	/**
	 * action: template_redirect
	 * When the first matched query variable is in the delivery list.
	 *
	 * @used-by: init_context()
	 */
	public function control_delivery() {

		reset( $this->matched_query_vars );
		$key = key( $this->matched_query_vars );

		$namespace = $this->delivery[ $key ][0];
		$classname = $this->delivery[ $key ][1];
		$fallback  = $this->delivery[ $key ][2];
		$func      = $this->matched_query_vars[ $key ];

		$args = array_slice( $this->matched_query_vars, 1 );

		$control = $this->loader->control( $namespace, $classname );
		if( method_exists( $control, $func ) ) {
			$control->{$func}( $args );
		} else {
			if( method_exists( $control, $fallback ) ) {
				$control->{$fallback}( $args );
			} else {
				include( get_404_template() );
			}
		}
		die();
	}
}
