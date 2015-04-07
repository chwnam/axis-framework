<?php

namespace axis_framework\includes\core\utils;

function check_abspath() {

	defined( 'ABSPATH' ) or die( 'No direct access!' );
}


/**
 * Small but pretty useful debug and dump function.
 *
 * @param mixed  $any_object any type of object you want to print.
 * @param string $tag        additional tag.
 */
if( defined( 'AXIS_ENABLE_DUMP_PRE' ) ) {

	function axis_dump_pre( $any_object, $tag = '' ) {

		echo "<pre>";

		if ( ! empty( $tag ) ) {
			echo "$tag:\n";
		}

		print_r( $any_object );

		echo "</pre>";
	}

}  else {

	function axis_dump_pre( $any_object, $tag = '' ) { }
}