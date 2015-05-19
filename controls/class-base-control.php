<?php

namespace axis_framework\controls;

use axis_framework\core;


abstract class Base_Control {

	use core\Loader_Trait;

    /** @var bool using output buffer. */
    protected $output_buffer_used = FALSE;

    public function __construct( $params = array() ) {
        if ( isset( $params['loader'] ) ) {
            $this->set_loader( $params['loader'] );
        }
    }

    /**
     * Enable output buffer. Useful when callback for shortcodes.
     */
    public function enable_output_buffer() {

        if ( $this->output_buffer_used == FALSE ) {

            if ( ! ob_start() ) {

                throw new \RuntimeException( 'Enabling output buffer failed!' );
            }

            $this->output_buffer_used = TRUE;

        } else {

            throw new \LogicException( 'Output buffer already opened!' );
        }
    }

    /**
     * @return string cleanup buffer and get the content
     */
    public function get_output_buffer() {

        if ( ! $this->output_buffer_used ) {

            throw new \LogicException( 'Output buffer not enabled!' );
        }

        $output                   = ob_get_clean();
        $this->output_buffer_used = FALSE;

        return $output;
    }

    /**
     * Simple view helper function
     *
     * @param string $namespace
     * @param string $view_name
     * @param string $template_name
     * @param array  $construct_param
     * @param array  $context
     */
    public function view_helper(
	    $namespace,
	    $view_name,
        $template_name,
	    array $construct_param = array(),
	    array $context = array()
    ) {
        $view = $this->loader->view( $namespace, $view_name, $construct_param );
        echo $view->render( $template_name, $context );
    }

    /**
     * @return void keep the return value of control::run() as void, and use "echo" inside.
     *              To return output, use enable_output_buffer() before run(),
     *              and call get_output_buffer() after run().
     */
    // public abstract function run();
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
        $name = NULL,
        array $data = array(),
        array $deps = array(),
        $ver = NULL,
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

		if( $this->name ) {

			$this->localize_enqueue();

		} else {

			wp_enqueue_script( $this->handle, $this->src, $this->deps, $this->ver, $this->in_footer );
		}
	}

    public function localize_enqueue() {

        wp_register_script(
            $this->handle,
            $this->src,
            $this->deps,
            $this->ver,
            $this->in_footer
        );

        if( NULL !== $this->name ) {

            wp_localize_script(
                $this->handle,
                $this->name,
                $this->data
            );
        }

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