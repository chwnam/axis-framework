<?php

namespace axis_framework\includes\core;

class Loader extends Singleton {

    private $plugin_namespace;
    private $plugin_root;

    public function set_plugin_namespace( $namespace ) {
        $this->plugin_namespace = $namespace;
    }

    public function set_plugin_root( $root_path ) {
        $this->plugin_root = $root_path;
    }


    /**
     * Dynamically create instance by control name.
     *
     * @param string $control_name
     *
     * @return mixed control instance
     */
    public function control( $control_name ) {

        $control_path  = $this->get_control_path( $control_name );
        $control_class = $this->get_control_class( $control_name );

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

    private function get_view_path( $view_name ) {
        return sprintf(
            '%s/%s.php',
            $this->plugin_root . '/includes/views',
            $view_name );
    }

    /**
     * get class name by fixed rule.
     *
     * @param string $component_name
     *
     * @return string fully qualified control class name
     */
    private function get_control_class( $component_name ) {

        $fq_class_name = $this->plugin_namespace . '\\';
        $component_name = str_replace('_', '-', $component_name );
        foreach ( explode( '-', $component_name ) as $element ) {
            $fq_class_name .= ucfirst( $element );
            $fq_class_name .= '_';
        }
        $fq_class_name .= 'Control';

        return $fq_class_name;

    }

    /**
     * get class file name by fixed rule.
     *
     * @param string $component_name
     *
     * @return string full path of control
     */
    private function get_control_path( $component_name ) {
        return sprintf(
            '%s/class-%s-control.php',
            $this->plugin_root . '/includes/controls',
            str_replace( '_', '-', $component_name )
        );
    }

}