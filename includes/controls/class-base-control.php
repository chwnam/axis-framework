<?php

namespace axis_framework\includes\controls;

use axis_framework\includes\core;

abstract class Base_Control {

    protected $loader;

    public function __construct() {
        $this->loader = core\Loader::get_instance();

    }

    public abstract function run();
    protected abstract function register_scripts();
    protected abstract function register_css();

}

/**
 * Class Script_Item
 * reference: http://codex.wordpress.org/Function_Reference/wp_register_script
 *            http://codex.wordpress.org/Function_Reference/wp_localize_script
 */
class Script_Item {

    public static $ajax_url;
    public static $ajax_object;

    public $handle;           // unique name. default: none
    public $src;              // script path. default: none
    public $deps;             // dependency.  default: array()
    public $ver;              // version.     default: false
    public $in_footer;        //              default: false

    public $name;             // name of the variable which will contain the data. default: none
    public $data;             // array.                                            default: None

    public function __construct(
        $handle,
        $src,
        $name,
        array $data,
        array $deps = array(),
        $ver = FALSE,
        $in_footer = FALSE
    ) {
        $this->handle    = $handle;
        $this->src       = $src;
        $this->deps      = $deps;
        $this->ver       = $ver;
        $this->in_footer = $in_footer;

        $this->name = $name;
        $this->data = $data;
    }

    public function enqueue() {

        wp_register_script(
            $this->handle,
            $this->src,
            $this->deps,
            $this->ver,
            $this->in_footer
        );

        wp_localize_script(
            $this->handle,
            $this->name,
            $this->data
        );

        wp_enqueue_script( $this->handle );

    }

}

Script_Item::$ajax_object = 'ajax_object';
Script_Item::$ajax_url    = array( 'ajax_url' => admin_url( 'admin-ajax.php' ) );


/**
 * Class Css_Item
 *
 * reference: http://codex.wordpress.org/Function_Reference/wp_register_style
 */
class Css_Item {

    public $handle;   //
    public $src;      //
    public $deps;     // default: array()
    public $ver;      // default: false
    public $media;    // default: all

    public function __construct(
        $handle,
        $src,
        $deps = array(),
        $ver = FALSE,
        $media = 'all'
    ) {

        $this->handle = $handle;
        $this->src    = $src;
        $this->deps   = $deps;
        $this->ver    = $ver;
        $this->media  = $media;

    }

    public function enqueue() {

        wp_register_style(
            $this->handle,
            $this->src,
            $this->deps,
            $this->ver,
            $this->media
        );

        wp_enqueue_style(
            $this->handle
        );

    }

}