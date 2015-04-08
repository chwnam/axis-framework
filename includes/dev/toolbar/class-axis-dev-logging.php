<?php

namespace axis_framework\includes\dev;

use axis_framework\includes\core;

if( defined( 'AXIS_DEV_TOOLBAR' ) && AXIS_DEV_TOOLBAR ) {


	class Axis_Dev_Logging extends core\Singleton {

		const CRITICAL = 60;
		const ERROR    = 50;
		const WARNING  = 40;
		const INFO     = 30;
		const VERBOSE  = 20;
		const DEBUG    = 10;
		const NOT_SET  = 0;

		private $logging = array();

		public function &get_logging() {

			return $this->logging;
		}


		public function log( $log_tag, $message ) {

			$bt   = debug_backtrace();

			// skip the trace called from this object.
			$caller_info = $bt[0]['file'] == __FILE__ ? next( $bt ) : current( $bt );

			$item          = new Log_Item();
			$item->tag     = $log_tag;
			$item->time    = time();
			$item->path    = $caller_info['file'];
			$item->line_no = $caller_info['line'];
			$item->message = $message;

			$this->logging[] = $item;
		}

		public function critical( $message ) {

			$this->log( 'CRITICAL', $message );
		}

		public function error( $message ) {

			$this->log( 'ERROR', $message );
		}

		public function warning( $message ) {

			$this->log( 'WARNING', $message );
		}

		public function info( $message ) {

			$this->log( 'INFO', $message );
		}

		public function verbose( $message ) {

			$this->log( 'VERBOSE', $message );
		}

		public function debug( $message ) {

			$this->log( 'DEBUG', $message );
		}
	}


	class Log_Item {

		public $tag;
		public $time;
		public $path;
		public $line_no;
		public $message;
	}

} else {

	class Axis_Dev_Logging extends core\Singleton {

		public function log( $log_tag, $message ) {}
		public function critical( $message ) {}
		public function error( $message ) {}
		public function warning( $message ) {}
		public function info( $message ) {}
		public function verbose( $message ) {}
		public function debug( $message ) {}
	}
}


/** @noinspection PhpUndefinedClassInspection */
/** @return \axis_framework\includes\dev\Axis_Dev_Logging */
function axis_get_logger() {
	/** @noinspection PhpUndefinedClassInspection */
	return Axis_Dev_Logging::get_instance();
}