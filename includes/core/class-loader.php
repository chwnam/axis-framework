<?php

namespace axis_framework\includes\core;


/**
 * Class Loader
 *
 * @package axis_framework\includes\core
 * @author  Changwoo Nam (cs.chwnam@gmail.com)
 */
class Loader {

	const BOOTSTRAP_CALLBACK = 'callback';
	const CONTROL            = 'control';
	const MODEL              = 'model';
	const TEMPLATE           = 'template';
	const VIEW               = 'view';

	const RULE_SIMPLE   = 'simple';
	const RULE_COMPLETE = 'complete';
	const RULE_CUSTOM   = 'custom';

	private $plugin_root;

	private $component_path = array(
		self::BOOTSTRAP_CALLBACK => '',
		self::CONTROL            => '',
		self::MODEL              => '',
		self::TEMPLATE           => '',
		self::VIEW               => '',
	);

	public function __construct( $plugin_root_path, $default_component_path = NULL ) {

		$this->set_plugin_root( $plugin_root_path );
		$this->init_component_path();
	}

	public function set_plugin_root( $root_path ) {

		$this->plugin_root = $root_path;
	}

	/**
	 * initialize default component path.
	 *
	 * @param array $component_to_override rule to override.
	 */
	public function init_component_path( array $component_to_override = array() ) {

		$default = array(
			self::BOOTSTRAP_CALLBACK => $this->plugin_root . '/includes/bootstraps',
			self::CONTROL            => $this->plugin_root . '/includes/controls',
			self::TEMPLATE           => $this->plugin_root . '/includes/templates',
			self::MODEL              => $this->plugin_root . '/includes/models',
			self::VIEW               => $this->plugin_root . '/includes/views',
		);

		$this->component_path = $default;

		if ( !empty( $component_to_override ) ) {

			foreach( $component_to_override as $c => $p ) {
				$this->update_component_path( $c, $p );
			}
		}
	}

	/**
	 * @return array current component path list
	 */
	public function component_paths() {

		return $this->component_path;
	}

	/**
	 * @param string $criteria criteria name. e.g. view, control, model, template, ...
	 * @param string $path     path to component root. Must exist.
	 */
	public function update_component_path( $criteria, $path ) {

		$p = realpath( $path );
		if ( FALSE === $p ) {
			throw new \RuntimeException( sprintf( "path '%s' does not exist, or permission denied.", $p ) );
		}
		$this->component_path[ $criteria ] = $p;
	}

	/**
	 * @param string $criteria criteria name to remove
	 */
	public function remove_component_path( $criteria ) {

		unset( $this->component_path[ $criteria ] );
	}

	public function bootstrap_callback( $namespace, $callback_name ) {

		$callback_path  = $this->get_component_path(
			$callback_name,
			self::BOOTSTRAP_CALLBACK
		);

		$callback_class = $this->get_component_class(
			$namespace,
			$callback_name,
			self::BOOTSTRAP_CALLBACK
		);

		if( !file_exists( $callback_path ) ) {
			return NULL;
		}

		/** @noinspection PhpIncludeInspection */
		require_once( $callback_path );

		return $callback_class;
	}

	/**
	 * @param string $namespace
	 * @param string $control_name
	 * @param array  $construct_param
	 *
	 * @return mixed control class
	 */
	public function control( $namespace, $control_name, $construct_param = array() ) {

		$control_path  = $this->get_component_path( $control_name, 'control' );
		$control_class = $this->get_component_class( $namespace, $control_name, 'control' );

		if ( ! isset( $construct_param['loader'] ) ) {

			$construct_param['loader'] = &$this;
		}

		// dynamic instance creation
		/** @noinspection PhpIncludeInspection */
		require_once( $control_path );
		$instance = new $control_class( $construct_param );

		return $instance;
	}

	/**
	 * Includes a simple view file.
	 *
	 * @param string $view_name view name
	 * @param array  $context   context to deliver
	 */
	public function simple_view( $view_name, array $context = array() ) {

		extract( $context );

		/** @noinspection PhpIncludeInspection */
		require_once( $this->get_component_path( $view_name, 'view', self::RULE_SIMPLE ) );
	}

	/**
	 * Returns "View Class"
	 *
	 * @param string $namespace view class's namespace
	 * @param string $view_name vie  class name
	 * @param array  $construct_param
	 *
	 * @return mixed View Class instance
	 */
	public function view( $namespace, $view_name, $construct_param = array() ) {

		$view_path  = $this->get_component_path( $view_name, 'view' );
		$view_class = $this->get_component_class( $namespace, $view_name, 'view' );

		if ( ! isset( $construct_param['loader'] ) ) {

			$construct_param['loader'] = &$this;
		}

		/** @noinspection PhpIncludeInspection */
		require_once( $view_path );
		$instance = new $view_class( $construct_param );

		return $instance;
	}

	/**
	 * @param string $namespace
	 * @param string $model_name
	 * @param array  $construct_param
	 *
	 * @return mixed model class instance
	 */
	public function model( $namespace, $model_name, $construct_param = array() ) {

		$model_path  = $this->get_component_path( $model_name, 'model' );
		$model_class = $this->get_component_class( $namespace, $model_name, 'model' );

		if ( ! isset( $construct_param['loader'] ) ) {

			$construct_param['loader'] = &$this;
		}

		/** @noinspection PhpIncludeInspection */
		require_once( $model_path );
		$instance = new $model_class( $construct_param );

		return $instance;
	}

	/**
	 * @param string $template_name
	 * @param mixed  $view view class to be used in the template
	 * @param array  $context
	 */
	public function template(
		$template_name,
		/** @noinspection PhpUnusedParameterInspection */
		&$view,
		array $context = array()
	) {

		extract( $context );

		/** @noinspection PhpIncludeInspection */
		require( $this->get_component_path( $template_name, 'template', self::RULE_SIMPLE ) );
	}

	/**
	 * @param string $table WordPress table name. e.g. comment, user, post, page
	 *
	 * @return string fully-qualified class name of model
	 */
	public function wp_table( $table ) {

		$path  = AXIS_INC_MODEL_PATH . '/wp-tables' . '/class-' . $table . '-model.php';
		$model = '\\axis_framework\\includes\\models\\' . ucfirst( $table ) . '_Model';

		/** @noinspection PhpIncludeInspection */
		require_once( $path );
		return $model;
	}

	/**
	 * @param string   $namespace
	 * @param string   $component_name
	 * @param string   $component_criteria
	 * @param string   $component_rule
	 * @param callable $naming_override
	 *
	 * @return string fully-qualified class name.
	 */
	public function get_component_class(
		$namespace,
		$component_name,
		$component_criteria,
		$component_rule = self::RULE_COMPLETE,
		$naming_override = NULL
	) {

		switch ( $component_rule ) {

			case self::RULE_COMPLETE:
				$fq_class_name = $namespace === '\\' ? $namespace : $namespace . '\\';
				$element_array = explode( '-', str_replace( '_', '-', $component_name ) );
				foreach ( $element_array as &$element ) {
					$fq_class_name .= ucfirst( $element ) . '_';
				}
				$fq_class_name .= ucfirst( strtolower( $component_criteria ) );
				break;

			case self::RULE_CUSTOM:
				$fq_class_name = call_user_func(
					$naming_override,
					array( $namespace, $component_name, $component_criteria )
				);
				break;

			default:
				throw new \LogicException(
					sprintf(
						'component name %s of namespace \'%s\' not found in criteria %s',
						$component_name, $namespace, $component_criteria
					)
				);
		}

		return $fq_class_name;
	}

	/**
	 * @param          $component_name
	 * @param          $component_criteria
	 * @param string   $component_rule
	 * @param callable $naming_override
	 *
	 * @return string component path
	 */
	public function get_component_path(
		$component_name,
		$component_criteria,
		$component_rule = self::RULE_COMPLETE,
		$naming_override = NULL
	) {

		$path_to_component = &$this->component_path[ $component_criteria ];

		if ( ! file_exists( $path_to_component ) ) {

			throw new \RuntimeException(
				sprintf(
					"component path '%s' by criteria '%s' does not exist!",
					$path_to_component,
					$component_criteria
				)
			);
		}

		switch ( $component_rule ) {

			case self::RULE_COMPLETE:
				$path = sprintf(
					'%s/class-%s-%s.php',
					$path_to_component,
					str_replace( '_', '-', $component_name ),
					$component_criteria
				);
				break;

			case self::RULE_SIMPLE:
				$path = sprintf( '%s/%s.php', $this->component_path[ $component_criteria ], $component_name );
				break;

			case self::RULE_CUSTOM:
				$path = call_user_func_array( $naming_override, array( $component_name, $component_criteria, ) );
				break;

			default:
				throw new \LogicException(
					sprintf(
						'component name %s not found in criteria %s',
						$component_name, $component_criteria
					)
				);
		}

		return $path;
	}
}


/**
 * Class Loader_Trait
 *
 * @package axis_framework\includes\core
 * @authro  Changwoo Nam (cs.chwnam@gmail.com)
 */
trait Loader_Trait {

	/** @var \axis_framework\includes\core\Loader */
	protected $loader = NULL;

	public function set_loader( \axis_framework\includes\core\Loader $loader ) {

		$this->loader = $loader;
	}

	public function get_loader() {

		return $this->loader;
	}
}