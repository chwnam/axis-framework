<?php

namespace axis_framework\includes\bootstraps;

use \axis_framework\includes\core;


abstract class Base_Menu_Callback extends core\Singleton {

    protected $loader;

    protected function __construct() {
        $this->loader = core\Loader::get_instance();
    }

    public abstract function add_admin_menu();
}

class Menu_Page_Item {

    public $page_title;     // title that displayed in the top window area of a web browser.
    public $menu_title;     // title thaa displayed in the wordpress admin dashboard menu.
    public $capability;     // access capability
    public $menu_slug;      // must be unique.
    public $callback;
    public $icon_url;
    public $position;

    function __construct(
        $page_title,
        $menu_title,
        $capability,
        $menu_slug,
        $callback = '',
        $icon_url = '',
        $position = NULL
    ) {
        $this->page_title = $page_title;
        $this->menu_title = $menu_title;
        $this->capability = $capability;
        $this->menu_slug  = $menu_slug;
        $this->callback   = $callback;
        $this->icon_url   = $icon_url;
        $this->position   = $position;
    }

    function add() {
        return add_menu_page(
            $this->page_title,
            $this->menu_title,
            $this->capability,
            $this->menu_slug,
            $this->callback,
            $this->icon_url,
            $this->position
        );
    }
}

class Submenu_Page_Item {

    public $show;           // to be displayed or hidden.
    public $parent_slug;    // menu_page_item slug
    public $page_title;     // title that displayed in the top window area of a web browser.
    public $menu_title;     // title that displayed in the wordpress admin dashboard menu.
    public $capability;     // access capability
    public $menu_slug;      // must be unique.
    public $callback;

    function __construct(
        $show,
        $parent_slug,
        $page_title,
        $menu_title,
        $capability,
        $menu_slug,
        $callback
    ) {
        $this->show           = $show;
        $this->parent_slug    = $parent_slug;
        $this->page_title     = $page_title;
        $this->menu_title     = $menu_title;
        $this->capability     = $capability;
        $this->menu_slug      = $menu_slug;
        $this->callback       = $callback;
    }

    public function add() {
        if ( $this->show) {
            return add_submenu_page(
                $this->parent_slug,
                $this->page_title,
                $this->menu_title,
                $this->capability,
                $this->menu_slug,
                $this->callback
            );
        }
    }
}