<div id="pr_dashboard_page" class="pr_page">
    <?php require_once 'header.tpl.php'; ?>
    <div id="pr_dashboard_content" class="pr_content">
         <?php if ($messages): ?>
            <div id="messages"><div class="section clearfix">
              <?php print $messages; ?>
            </div></div> <!-- /.section, /#messages -->
          <?php endif; ?>
        <?php print render($title_prefix); ?>
        <?php if ($title): ?><h1 class="page-title"><?php print $title; ?></h1><?php endif; ?>
        <?php print render($title_suffix); ?>
        <?php if (!empty($tabs['#primary'])): ?><div class="tabs-wrapper clearfix"><?php print render($tabs); ?></div><?php endif; ?>
        <?php print render($page['help']); ?>
        <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
        <?php print render($page['content']); ?>
    </div>
    <div id="pr_dashboard_footer" class="pr_footer">
        <?php require_once 'footer.tpl.php'; ?>
    </div>
</div>