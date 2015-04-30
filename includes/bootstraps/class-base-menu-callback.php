<?php

namespace axis_framework\includes\bootstraps;

use \axis_framework\includes\core;


/**
 * Class Base_Menu_Callback
 *
 * @package axis_framework\includes\bootstraps
 */
abstract class Base_Menu_Callback extends Base_Callback {

	protected function __construct( array $args = array() ) {

		parent::__construct( $args );
	}

	public abstract function add_admin_menu();
}


/**
 * Class Menu_Page_Item
 *
 * @package axis_framework\includes\bootstraps
 */
class Menu_Page_Item {

	/**
	 * @var string       $page_title title that displayed in the top window area of a web browser.
	 * @var string       $menu_title title that displayed in the wordpress admin dashboard menu.
	 * @var string       $capability access capability
	 * @var string       $menu_slug  must be unique.
	 * @var string|array $callback
	 * @var string       $icon_url
	 * @var null|integer $position
	 */
	public $page_title;
	public $menu_title;
	public $capability;
	public $menu_slug;
	public $callback;
	public $icon_url;
	public $position;

	/**
	 * @param string       $page_title title that displayed in the top window area of a web browser.
	 * @param string       $menu_title title that displayed in the wordpress admin dashboard menu.
	 * @param string       $capability access capability
	 * @param string       $menu_slug  must be unique.
	 * @param string|array $callback
	 * @param string       $icon_url
	 * @param null|integer $position
	 */
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


/**
 * Class Submenu_Page_Item
 *
 * @package axis_framework\includes\bootstraps
 */
class Submenu_Page_Item {

	/**
	 * @var bool         $show        to be displayed or hidden.
	 * @var string       $parent_slug menu_page_item slug.
	 * @var string       $page_title  title that displayed in the top window area of a web browser.
	 * @var string       $menu_title  title that displayed in the wordpress admin dashboard menu.
	 * @var string       $capability  access capability
	 * @var string       $menu_slug   must be unique.
	 * @var string|array $callback    string or array.
	 */
	public $show;
	public $parent_slug;
	public $page_title;
	public $menu_title;
	public $capability;
	public $menu_slug;
	public $callback;

	/**
	 * @param bool         $show        to be displayed or hidden.
	 * @param string       $parent_slug menu_page_item slug.
	 * @param string       $page_title  title that displayed in the top window area of a web browser.
	 * @param string       $menu_title  title that displayed in the wordpress admin dashboard menu.
	 * @param string       $capability  access capability
	 * @param string       $menu_slug   must be unique.
	 * @param string|array $callback    string or array.
	 */
	function __construct(
		$show,
		$parent_slug,
		$page_title,
		$menu_title,
		$capability,
		$menu_slug,
		$callback
	) {

		$this->show        = $show;
		$this->parent_slug = $parent_slug;
		$this->page_title  = $page_title;
		$this->menu_title  = $menu_title;
		$this->capability  = $capability;
		$this->menu_slug   = $menu_slug;
		$this->callback    = $callback;
	}

	/**
	 * add this submenu.
	 *
	 * @return false|string the resulting page's hook, or false if the user does not have the capability required.
	 *                      false if show is set to false.
	 */
	public function add() {

		if ( $this->show ) {

			return add_submenu_page(
				$this->parent_slug,
				$this->page_title,
				$this->menu_title,
				$this->capability,
				$this->menu_slug,
				$this->callback
			);
		}

		return FALSE;
	}
}