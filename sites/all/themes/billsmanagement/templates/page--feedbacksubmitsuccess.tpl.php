<?php $theme_path = 'http://' . $_SERVER['HTTP_HOST'] . base_path() . drupal_get_path('theme', 'performancereview') ?>
<?php $base_path = 'http://' . $_SERVER['HTTP_HOST'] . base_path() ?>
<div id="wrap" style="min-height: 480px; padding-top: 20px;">
    <?php if ($title): ?><h1 class="page-title" style="margin-top: 20px;"><?php print $title; ?></h1><?php endif; ?>
    <div style="float: left; margin-left: 100px; margin-top: 120px;"><img src="<?php echo $theme_path ?>/images/submitsuccess.png" /></div>
    <div style="float: left; margin-top: 150px; margin-left: 30px; font-size: 24px;">Thank you for taking time to complete the feedback!</div>
    <?php global $user;?>
    <?php if ($user->uid != 0): ?>
    <div style="float: right; margin-top: 100px;"><input type="button" value="Go to my working stage"class="form-submit" onclick='window.location.href="<?php echo $base_path?>myworkstage"'/></div>
    <?php endif; ?>
    <?php if ($user->uid == 0): ?>
    <div style="float: right; margin-top: 100px;">If you want to login 360 degree system, please click -->&nbsp;&nbsp;<input type="button" value="Login"class="form-submit" onclick='window.location.href="<?php echo $base_path?>"'/></div>
    <?php endif; ?>
</div>
