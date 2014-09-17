<?php

  namespace axis_framework\includes\core\utils;

  function check_abspath() {
    defined( 'ABSPATH' ) or die( 'No direct access!' );
  }


  function axis_dump_pre( $text, $tag = '' ) {

    echo "<pre>";

    if ( !empty( $tag ) ) {
      echo "$tag:\n";
    }

    print_r( $text );

    echo "</pre>";

  }