<?php

namespace axis_sample;

use axis_framework\includes\models;

class Sample_Test_Model extends models\Base_Model {

    public function prepare_data() {

	    // 모델에서도 loader 객체를 사용할 수 있어요.
	    // $control = $this->loader->control('axis_sample', 'inner-control');

	    return "data prepared!";
    }
}