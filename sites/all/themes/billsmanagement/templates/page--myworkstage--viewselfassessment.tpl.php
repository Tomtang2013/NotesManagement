<?php $module_path = 'http://' . $_SERVER['HTTP_HOST'] . base_path() . '/' . drupal_get_path('module', 'working_stage') ?>

<div id="pr_mywokingstage_page" class="pr_page">
    <?php require_once 'header.tpl.php';?>
    <div id="pr_mywokingstage_content" class="pr_content">
        <div id="pr_mywokingstage_content_left" class="pr_content_left">
            <?php $navigation_tree = menu_tree(variable_get('menu_main_links_source', 'navigation'));
            print drupal_render($navigation_tree); ?>
        </div>
        <div id="pr_mywokingstage_content_right" class="pr_content_right">
            <div class="pr_view_feedback">
                <div id="pr_right_content">
                  <?php print render($page['self_assessment']) ?>
                </div>
            </div>
        </div>
    </div>
    <div id="pr_mywokingstage_footer" class="pr_footer">
        <?php require_once 'footer.tpl.php';?>
    </div>
</div>
