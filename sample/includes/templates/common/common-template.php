<?php /** @var axis_framework\includes\views\Base_View $view */ ?>
<h1><?= $view->fetch( 'title' ) ?></h1>
<?= $view->fetch( 'content' ) ?>

<div class="actions">
    <h3>Related actions</h3>
    <ul>
        <?= $view->fetch( 'sidebar' ) ?>
    </ul>
</div>