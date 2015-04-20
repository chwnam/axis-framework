<?php

namespace axis_sample;

use axis_framework\includes\controls;
use axis_framework\includes\core;


class Sample_Test_Control extends controls\Base_Control {

	public function __construct( array $args = array() ) {

		parent::__construct( $args );
	}

	private function prepare_data() {

		/** @var Sample_Test_Model $model */
		$model = $this->loader->model( 'axis_sample', 'sample-test' );
		$data  = $model->prepare_data();

		return $data;
	}

	public function run() {

		$this->register_scripts();
		$this->register_css();

		$data = array(
			'output_text' => $this->prepare_data(),    // 항상 key-value 쌍이어야 하며 key는 변수 이름으로 쓸 수 있어야 합니다.
		);

		$this->loader->simple_view( 'sample-test', $data );
	}

	public function register_scripts() {

		$wish_list = array(

			new controls\Script_Item(
				'sample_test_script_handle',
				AXIS_SAMPLE_JS_URL . '/sample_test.js',
				controls\Script_Item::$ajax_object,
				controls\Script_Item::$ajax_url,
				array( 'jquery' ),
				NULL
			),
		);

		foreach ( $wish_list as &$w ) {

			/** @var controls\Script_Item $w */
			$w->enqueue();
		}
	}

	public function register_css() {

		$wish_list = array(

			new controls\Css_Item(
				'test_css_handle',
				AXIS_SAMPLE_CSS_URL . '/sample_test.css'
			)
		);

		foreach ( $wish_list as &$w ) {

			/** @var controls\Css_Item $w */
			$w->enqueue();
		}
	}
}