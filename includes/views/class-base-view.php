<?php

namespace axis_framework\includes\views;

require_once( 'class-view-block.php' );

use axis_framework\includes\core;


core\utils\check_abspath(); // check abspath or inclusion fatal error.


abstract class Base_View {

	use core\Loader_Trait;

    protected $block;

    protected $current_template = '';

    protected $parent_templates = array();

    protected $_template_stack = array();

	public function __construct( $params = array() ) {

		if( isset( $params['loader'] ) ) {

			$this->set_loader( $params['loader'] );
		}

        $this->block = new View_Block();
	}

    public function extend( $template ) {

        // parent must be different from current template
        if( $template == $this->current_template ) {
            throw new \LogicException( 'You cannot have views extend themselves.' );
        }

        // the parent's parent: current, and the current template's parent: the parent
        // cyclic condition.
        if( isset( $this->parent_templates[ $template ] ) &&
            $this->parent_templates == $this->$current_template ) {
            throw new \LogicException( 'You cannot have views extend in a loop.' );
        }

        $this->parent_templates[ $this->current_template ] = $template;
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

		$this->current_template = $template;
		$this->block->set( 'content', $this->_render( $template, $context ) );
		$this->current_template = $template;

		return $this->block->get( 'content' );
	}

    public function _render( $template, array $context = array() ) {

	    $this->current_template = $template;
        $initial_blocks = count( $this->block->unfinished() );

        ob_start();
        $this->loader->template( $template, $this, $context );
        $content = ob_get_clean();

        if( isset( $this->parent_templates[ $template ] ) ) {

            $this->_template_stack[] = $this->fetch( 'content' );
            $this->assign( 'content', $content );

            $content = $this->_render( $this->parent_templates[ $template ] );
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