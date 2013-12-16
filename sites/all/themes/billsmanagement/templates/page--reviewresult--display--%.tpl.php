<?php $module_path = 'http://' . $_SERVER['HTTP_HOST'] . base_path() . '/' . drupal_get_path('module', 'working_stage') ?>

<div id="pr_mywokingstage_page" class="pr_page">
    <?php require_once 'header.tpl.php';?>
    <div id="pr_mywokingstage_content" class="pr_content">
        <div id="pr_right_content" >
             <?php print render($page['content']) ?>
        </div>
    </div>
    <div id="pr_mywokingstage_footer" class="pr_footer">
        <?php require_once 'footer.tpl.php';?>
    </div>
</div>
