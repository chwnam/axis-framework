<?php
//\axis_framework\includes\core\utils\axis_dump_pre( $val, '$val' );
//\axis_framework\includes\core\utils\axis_dump_pre( $arr, '$arr' );
//\axis_framework\includes\core\utils\axis_dump_pre( $cls, '$cls' );
//\axis_framework\includes\core\utils\axis_dump_pre( $view, '$view' );
//\axis_framework\includes\core\utils\axis_dump_pre( $post, '$post' );

/** @var \axis_sample\View_Class_Test_View $view */
/** @var \stdClass $post */

$view->extend( '/common/common-template' );
$view->assign( 'title', $post->title );
$view->begin_block( 'sidebar' );
?>
    <li>
        this is sidebar!
    </li>
    <li>
        do you like template?
    </li>
	<button id="ajax-return">AJAX Return Test</button>
	<div id="output"></div>
<?php $view->end_block(); ?>

<?= $post->body ?><br>
