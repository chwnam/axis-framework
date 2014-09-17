<?php
?>

<div class="wrap">
    <h2><?php echo __( AXIS_SAMPLE_FULL_NAME . ' Settings', AXIS_SAMPLE_LANG_CONTEXT ); ?></h2>

    <form action="options.php" method="post" id="general_settings_form">
        <?php
            settings_fields( $group_name );
            do_settings_sections( $page_name );
            submit_button();
        ?>
    </form>
</div>