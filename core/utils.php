<?php

namespace axis_framework\core\utils;

function check_abspath() {

	defined( 'ABSPATH' ) or die( 'No direct access!' );
}

check_abspath();

/**
 * Small but pretty useful debug and dump function.
 *
 * @param mixed  $any_object any type of object you want to print.
 * @param string $tag        additional tag.
 */
function axis_dump_pre( $any_object, $tag = '' ) {

	echo "<pre>";

	if ( ! empty( $tag ) ) {
		echo "$tag:\n";
	}

	print_r( $any_object );

	echo "</pre>";
}

/**
 * Get the version number of Axis Framework
 *
 * @return string version number string. e.g. 0.10.2500.
 */
function axis_version() {

	// Assume that the only one axis framework is installed on the server.
	static $axis_ver = '';

	if ( empty( $axis_ver ) ) {

		$plugin_data = \get_file_data( AXIS_FRAMEWORK_MAIN_FILE, array( 'Version' => 'Version' ) );
		$axis_ver    = $plugin_data['Version'];
	}

	return $axis_ver;
}

/**
 * Get admin menu notification bubble.
 * This bubble is displayed when WordPress has something to update, or so.
 *
 * @param $count
 * @since v.0.20.1000
 *
 * @return string
 */
function admin_menu_notification_bubble( $count ) {

	$bubble = '';

	if( preg_match( '/^\d+$/', $count ) ) {
		$bubble = sprintf(
			'<span class="update-plugins count-%d"><span class="plugin-count">%s</span></span>',
			$count,
			number_format_i18n( $count )
		);
	}

	return $bubble;
}