<?php
?>
%%%%%%%%%%%%%%%%
<div id="pr_dashboard_page" class="pr_page">
    <?php require_once 'header.tpl.php';?>
    <div id="pr_dashboard_content" class="pr_content">
        <div id="pr_dashboard_content_left" class="pr_content_left">
           <?php $navigation_tree = menu_tree(variable_get('menu_main_links_source', 'navigation')); 
           print drupal_render($navigation_tree); ?>
        </div>
        <div id="pr_dashboard_content_right" class="pr_content_right">
             <?php if ($messages): ?>
                  <?php print $messages; ?>
              <?php endif; ?>
            <?php  print drupal_render($page['content']); ?>
        </div>
    </div>
    <div id="pr_dashboard_footer" class="pr_footer">
        <?php require_once 'footer.tpl.php';?>
    </div>
</div>
