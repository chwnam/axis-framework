<?php

namespace axis_framework\includes\views;

require_once( 'class-view-block.php' );

use axis_framework\includes\core;


abstract class Base_View {

	/** @var  core\Loader loader */
	protected $loader;

    protected $block;

    protected $current_template_path = '';

    protected $parent_template_paths = array();

    protected $_template_stack = array();

	public function __construct( $params = array() ) {

		if( isset( $params['loader'] ) ) {

			$this->set_loader( $params['loader'] );
		}

        $this->block = new View_Block();
	}

	public function set_loader( core\Loader &$loader) {

		$this->loader = &$loader;
	}

    public function extend( $template_name ) {

        $template_path = $this->loader->get_template_path( $template_name );

        // parent must be different from current template
        if( $template_path == $this->current_template_path ) {
            throw new \LogicException();
        }

        // the parent's parent: current, and the current template's parent: the parent
        // cyclic condition.
        if( isset( $this->parent_template_paths[ $template_path ] ) &&
            $this->$template_path[ $template_path ] == $this->current_template_path ) {
            throw new \LogicException();
        }

        $this->parent_template_paths[ $this->current_template_path ] = $template_name;
        $this->current_template_path = $template_path;
    }

    public function assign( $name, $value ) {

        $this->block->set( $name, $value );
    }

    public function fetch( $name, $default = '' ) {

        return $this->block->get( $name, $default );
    }

    public function begin_block( $name ) {

        $this->block->begin( $name );
    }

    public function end_block() {

        $this->block->end();
    }

    public function prepend( $name, $value ) {

        $this->block->concat( $name, $value, View_Block::PREPEND );
    }

    public function append( $name, $value ) {

        $this->block->concat( $name, $value, View_Block::APPEND );
    }

    public function block_exists( $name ) {

        return $this->block->exists( $name );
    }

    public function render( $template, array $context = array() ) {

        $this->current_template_path = $this->loader->get_template_path( $template );

        $initial_blocks = count( $this->block->unfinished() );

        ob_start();
        $this->loader->template( $template, $this, $context );
        $content = ob_get_clean();

        if( isset( $this->parent_template_paths[ $this->current_template_path ] ) ) {

            $this->_template_stack[] = $this->fetch( 'content' );
            $this->assign( 'content', $content );

            $content = $this->render( $this->parent_template_paths[ $this->current_template_path ], $context );
            $this->assign( 'content', array_pop( $this->_template_stack ) );
        }

        $remaining_blocks = count( $this->block->unfinished() );
        if( $initial_blocks !== $remaining_blocks ) {
            throw new \LogicException(
                sprintf( "%s block left open. Blocks are not allowed to cross files.", $this->block->active() )
            );
        }

        return $content;
    }
}