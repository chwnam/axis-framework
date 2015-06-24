<?php
namespace axis_framework\forms;


class Form_Renderer {

	public function __construct() {
	}

	/**
	 * @param $code_text
	 * @param $output
	 *
	 * @return string|NULL
	 */
	private function output_or_return( $code_text, $output ) {

		if( !$output ) {
			return $code_text;
		}

		echo $code_text;
		return NULL;
	}

	/**
	 * @param       $tag
	 * @param array $properties
	 * @param bool  $output
	 *
	 * @return NULL|string
	 */
	public function start_tag( $tag, array &$properties = array(), $output = TRUE ) {

		if( empty( $tag ) || is_null( $tag ) ) {
			return '';
		}

		$code = '<' . $tag . ' ';
		foreach( $properties as $key => $value ) {
			$code .= "{$key}=\"{$value}\" ";
		}
		$code .= '>';

		return $this->output_or_return( $code, $output );
	}

	/**
	 * @param      $tag
	 * @param bool $output
	 *
	 * @return NULL|string
	 */
	public function end_tag( $tag, $output = TRUE ) {

		if( empty( $tag ) || is_null( $tag ) ) {
			return '';
		}

		return $this->output_or_return( '</' . $tag . '>', $output );
	}

	/**
	 * @param       $tag
	 * @param array $properties
	 * @param bool  $output
	 *
	 * @return NULL|string
	 */
	public function start_end_tag( $tag, array &$properties = array(), $output = TRUE ) {

		if( empty( $tag ) || is_null( $tag ) ) {
			return '';
		}

		$code = $this->start_tag( $tag, $properties, FALSE );
		$length = strlen( $code );

		if( $length > 2 ) {
			return $this->output_or_return( substr( $code, 0, -1 ) . '/>', $output );
		}

		return NULL;
	}
}