<?php

namespace axis_framework\bootstraps\settings;

/**
 * Class Option_Name
 *
 * Option_Name is directly related to wp_options.option_name.
 * In terms of MVC pattern, it is, loosely speaking, like "MODEL" part.
 *
 */
class Option_Name {

	public $name;
	public $group;
	public $sanitize_callback;

	public function __construct( $name, $group, $sanitize_callback = NULL ) {

		$this->name              = $name;
		$this->group             = $group;
		$this->sanitize_callback = $sanitize_callback;
	}

}


/**
 * Class Option_Group
 *
 * A group of Option_Name objects.
 *
 */
class Option_Group {

	public $name;

	public function __construct( $name ) {

		$this->name = $name;
	}

}


/**
 * Class Settings_Field
 *
 * In terms of MVC pattern, it is "CONTROL" part.
 *
 * @package sms4wp\includes\bootstraps
 */
class Settings_Field {

	public $id;
	public $title;
	public $callback;
	public $section;
	public $args;

	public function __construct( $id, $title, $callback, $section, $args ) {

		$this->id       = $id;
		$this->title    = $title;
		$this->callback = $callback;
		$this->section  = $section;
		$this->args     = $args;
	}
}


/**
 * Class Settings_Section
 *
 * A Group of Settings_Field objects.
 */
class Settings_Section {

	public $id;
	public $title;
	public $callback;
	public $page;

	public function __construct( $id, $title, $callback, $page ) {

		$this->id       = $id;
		$this->title    = $title;
		$this->callback = $callback;
		$this->page     = $page;
	}
}


/**
 * Class Settings_Page
 *
 * Option sub-page names of the WordPres settings menu.: general, read, write, ....
 * However, it is just a slug to identify a page.
 *
 */
class Settings_Page {

	public $name;

	public function __construct( $name ) {

		$this->name = $name;
	}
}


class Settings_Helper {

	static public function register_settings( $option_names ) {

		foreach ( $option_names as $on ) {
			register_setting(
				$on->group->name,
				$on->name,
				$on->sanitize_callback
			);
		}
	}

	static public function add_settings_sections( $sections ) {

		foreach ( $sections as $s ) {
			add_settings_section(
				$s->id,
				$s->title,
				$s->callback,
				$s->page->name
			);
		}
	}

	static public function add_settings_fields( $fields ) {

		foreach ( $fields as $f ) {
			add_settings_field(
				$f->id,
				$f->title,
				$f->callback,
				$f->section->page->name,
				$f->section->id,
				$f->args
			);
		}
	}

	static public function generic_text_input_callback( $args ) {

		$fmt = '<input type="text" id="%s" name="%s" value="%s" autocomplete="%s"/> <span class="description">%s</span>';
		printf( $fmt, $args['id'], $args['name'], $args['value'], $args['autocomplete'] ? "on" : "off", $args['description'] );
	}

	static public function generic_section_callback( $args ) {

		$fmt = '<p id="%s">%s</p>';
		printf( $fmt, $args['id'], $args['description'] );
	}

}