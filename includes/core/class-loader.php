<?php

namespace axis_framework\includes\core;

class Loader extends Singleton {

    private $plugin_root;

    public function set_plugin_root( $root_path ) {
        $this->plugin_root = $root_path;
    }

    public function control( $namespace, $control_name ) {

        // $control_path  = $this->get_control_path( $control_name );
        // $control_class = $this->get_control_class( $namespace, $control_name );
        $control_path  = $this->get_component_path( $control_name, 'control');
        $control_class = $this->get_component_class( $namespace, $control_name, 'control' );

        // dynamic instance creation
        require_once( $control_path );
        return new $control_class();
    }

    public function view( $view_name, $data = NULL ) {

        if ( $data ) {
            $keys = array_keys( $data );
            foreach ( $keys as $key ) {
                $$key = $data[ $key ];
            }
        }

        require_once( $this->get_view_path( $view_name ) );
    }

    public function model( $namespace, $model_name ) {

        //$model_path = $this->get_model_path( $model_name );
        //$model_class = $this->get_model_class( $namespace, $model_name );
        $model_path = $this->get_component_path( $model_name, 'model' );
        $model_class = $this->get_component_class( $namespace, $model_name, 'model' );

        require_once( $model_path );
        return new $model_class;
    }

    private function get_view_path( $view_name ) {
        return sprintf(
            '%s/%s.php',
            $this->plugin_root . '/includes/views',
            $view_name );
    }

    private function get_control_class( $namespace, $component_name ) {

        $fq_class_name = $namespace . '\\';
        $component_name = str_replace('_', '-', $component_name );
        foreach ( explode( '-', $component_name ) as $element ) {
            $fq_class_name .= ucfirst( $element );
            $fq_class_name .= '_';
        }
        $fq_class_name .= 'Control';

        return $fq_class_name;

    }

    private function get_control_path( $component_name ) {
        return sprintf(
            '%s/class-%s-control.php',
            $this->plugin_root . '/includes/controls',
            str_replace( '_', '-', $component_name )
        );
    }

    private function get_model_class( $namespace, $component_name ) {

        $fq_class_name = $namespace . '\\';
        $component_name = str_replace('_', '-', $component_name );
        foreach ( explode( '-', $component_name ) as $element ) {
            $fq_class_name .= ucfirst( $element );
            $fq_class_name .= '_';
        }
        $fq_class_name .= 'Model';

        return $fq_class_name;

    }

    private function get_model_path( $component_name ) {
        return sprintf(
            '%s/class-%s-control.php',
            $this->plugin_root . '/includes/models',
            str_replace( '_', '-', $component_name )
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
        return sprintf(
            '%s/includes/%ss/class-%s-%s.php',
            $this->plugin_root,
            $component_criteria,
            str_replace( '_', '-', $component_name ),
            $component_criteria
        );
    }

}