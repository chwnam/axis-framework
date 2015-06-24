<?php
/**
 * Plugin Name: Axis Framework
 * Plugin URI: https://github.com/chwnam/axis
 * Description: Axis - A WordPress Plugin Framework.
 * Author: Changwoo Nam
 * Author URI: mailto://cs.chwnam@gmail.com
 * Version: 0.21.0000
 * Text Domain: axis_framework
 * License: LGPLv2 or later
 */

/*
Copyright (C) 2015 Changwoo Nam

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace axis_framework;

use axis_framework\context\Dispatch;
use axis_framework\core\Loader;

require_once( 'axis-define.php' );

// well, now axis framework has its menu & option
$plugin_dir = dirname( __FILE__ ) . '/plugin';

// axis uses root as its library, therefore components must be overridden
$loader_component_override = array(
	Loader::CONTEXT            => $plugin_dir . '/plugin_context',
	Loader::CONTROL            => $plugin_dir . '/plugin_control',
	Loader::DISPATCH           => $plugin_dir . '/plugin_context',
	Loader::FORM               => $plugin_dir . '/plugin_form',
	Loader::TEMPLATE           => $plugin_dir . '/plugin_template',
	Loader::MODEL              => $plugin_dir . '/plugin_model',
	Loader::VIEW               => $plugin_dir . '/plugin_view',
);

$axis_dispatch = new Dispatch();
$axis_dispatch->setup( __FILE__, 'axis_framework\plugin\plugin_context', $loader_component_override );
