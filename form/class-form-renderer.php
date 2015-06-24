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

	public function render_tag( $tag, array &$properties = array(), $output = TRUE ) {

		if( isset( $properties['content'] ) ) {
			$code = $this->start_tag( $tag, $properties, FALSE );
			if( is_array( $properties['content'] ) ) {
				if( isset( $properties['content']['tag'] ) ) {
					$code .= $this->render_tag( $properties['content']['tag'], $properties['content'], FALSE );
				} else {
					foreach( $properties['content'] as &$content ) {
						if ( is_array( $content ) &&  isset( $content['tag'] ) ) {
							$code .= $this->render_tag( $content['tag'], $content, FALSE );
						}
					}
				}
			} else {
				$code .= $properties['content'];
			}
			$code .= $this->end_tag( $tag, FALSE );
			return $this->output_or_return( $code, $output );
		}

		return $this->start_end_tag( $tag, $properties );
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

		$code = '<' . $tag;
		foreach( $properties as $key => $value ) {
			if( $key != 'tag' && $key != 'content' ) {
				$code .=  ' ' . $key . '="' . esc_attr( $value ) . '" ';
			}
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

		// like <i>, the minimum length must be 3
		if( $length < 3 ) {
			return NULL;
		}

		return $this->output_or_return( substr( $code, 0, -1 ) . '/>', $output );
	}
}