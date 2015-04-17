<?php

namespace axis_framework\includes\core;

class Loader{

    private $plugin_root;
    private $component_path = array(

        'view'     => '',
        'control'  => '',
        'model'    => '',
        'template' => '',
    );

    public function __construct( $plugin_root_path, $default_component_path = NULL ) {

        $this->set_plugin_root( $plugin_root_path );
        $this->init_component_path();
    }

    public function set_plugin_root( $root_path ) {

        $this->plugin_root = $root_path;
    }

    public function init_component_path() {

        $this->component_path = array(
            'view'     => $this->plugin_root . '/includes/views',
            'control'  => $this->plugin_root . '/includes/controls',
            'model'    => $this->plugin_root . '/includes/models',
            'template' => $this->plugin_root . '/includes/templates',
        );
    }

    public function update_component_path( $criteria, $path ) {

        $p = realpath( $path );
        if( FALSE === $p ) {
            throw new \RuntimeException( sprintf( "path '%s' does not exist, or permission denied.", $p ) );
        }
        $this->component_path[$criteria] = $p;
    }

    public function remove_component_path( $criteria ) {

        unset( $this->component_path[ $criteria ] );
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

		if( !isset( $construct_param['loader'] ) ) {

			$construct_param['loader'] = &$this;
		}

        // dynamic instance creation
        require_once( $control_path );
        $instance = new $control_class( $construct_param );

        return $instance;
    }

    public function view( $view_name, $context = array() ) {

        if ( !empty( $context ) ) {
            $keys = array_keys( $context );
            foreach ( $keys as $key ) {
                $$key = $context[ $key ];
            }
        }

        require_once( $this->get_view_path( $view_name ) );
    }

	public function view_class( $namespace, $view_name, $construct_param = array() ) {

		$view_path = $this->get_component_path( $view_name, 'view' );
		$view_class = $this->get_component_class( $namespace, $view_name, 'view' );

		if( !isset( $construct_param['loader'] ) ) {

			$construct_param['loader'] = &$this;
		}

		require_once( $view_path );
		$instance = new $view_class( $construct_param );

		return $instance;
	}

    public function model( $namespace, $model_name, $construct_param = array() ) {

        $model_path = $this->get_component_path( $model_name, 'model' );
        $model_class = $this->get_component_class( $namespace, $model_name, 'model' );

	    if( !isset( $construct_param['loader'] ) ) {

		    $construct_param['loader'] = &$this;
	    }

        require_once( $model_path );
        $instance = new $model_class( $construct_param );

        return $instance;
    }

    public function template( $template_name, &$view_class, array $context = array() ) {

        foreach ( $context as $key => &$value ) {
            $$key = &$value;
        }

        $view = &$view_class;

        require_once( $this->get_template_path( $template_name ) );
    }

    private function get_view_path( $view_name ) {
        return sprintf(
            '%s/%s.php',
            $this->plugin_root . '/includes/views',
            $view_name );
    }

    private function get_template_path( $template_name ) {
        return sprintf(
            '%s/%s.php',
            $this->component_path['template'],
            $template_name
        );
    }

    private function get_component_class( $namespace, $component_name, $component_criteria ) {

        $fq_class_name = $namespace . '\\';
        $component_name = str_replace('_', '-', $component_name );
        foreach ( explode( '-', $component_name ) as $element ) {
            $fq_class_name .= ucfirst( $element );
            $fq_class_name .= '_';
        }
        $fq_class_name .= ucfirst( strtolower( $component_criteria ) ) ;

        return $fq_class_name;
    }

    private function get_component_path( $component_name, $component_criteria ) {

        $path_to_component = &$this->component_path[ $component_criteria ];

        if( !file_exists( $path_to_component ) ) {

            throw new \RuntimeException(
                sprintf(
                    "component path '%s' by criteria '%s' does not exist!",
                    $path_to_component,
                    $component_criteria
                )
            );
        }

        return sprintf(
            '%s/class-%s-%s.php',
            $path_to_component,
            str_replace( '_', '-', $component_name),
            $component_criteria
        );
    }

}