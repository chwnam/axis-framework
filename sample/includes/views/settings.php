<?php
// see the context. 컨트롤로부터 전송된 콘텍스트 값들입니다. 자동으로 변수화시켜 줍니다.
\axis_framework\includes\core\utils\axis_dump_pre( $group_name, 'group_name' );
\axis_framework\includes\core\utils\axis_dump_pre( $page_name, 'page_name' );

/**
 * setting page view
 */
?>

<div class="wrap">
    <h2><?php echo __( AXIS_SAMPLE_FULL_NAME . ' Settings', 'axis_sample' ); ?></h2>

    <!--suppress HtmlUnknownTarget -->
	<form action="options.php" method="post" id="general_settings_form">
        <?php
            // http://codex.wordpress.org/Function_Reference/settings_fields
            // none 필드와 옵션 폼을 구성하기 위한 필드들을 출력하는 곳으로 반드시 불려야 합니다.
            settings_fields( $group_name );

            // http://codex.wordpress.org/Function_Reference/do_settings_sections
            // 페이지 이름을 주면 그 페이지 하위의 모든 "섹션"들이 출력됩니다.
            // 섹션은 또 하위의 모든 "필드"들을 정해진 방식으로 출력합니다.
            do_settings_sections( $page_name );

            // 전송 버튼을 명시적으로 출력합니다.
            submit_button();
        ?>
    </form>

    <!--
     이렇게 세팅 페이지를 구성한 후, 변경 사항을 저장하면 데이터베이스에 옵션 값이 기록됩니다.
     옵션 테이블은 기본으로 wp_options이며 'option_name' 필드로 'axis_sample_value_1'인 레코드를 찾아 보면
     한 레코드가 기록될 것이고 반드시 이 페이지 폼에서 전송된 값으로 기록될 것입니다.

     또한 반대로 언제나 옵션 화면에 접속하면
     반드시 Field Value 1에는 wp_options['option_name'] 값이 기본적으로 입력되어 있어야 합니다.
    -->
</div>