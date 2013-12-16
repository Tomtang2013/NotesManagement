<?php $module_path = 'http://' . $_SERVER['HTTP_HOST'] . base_path() . '/' . drupal_get_path('module', 'working_stage') ?>
<script type="text/javascript" >
    function acceptFeedback(prid, employeeName){
        $.ajax({
            type: "POST",
            url: '<?php echo $base_path ?>myworkstage/acceptfeedback/'+prid+'/'+employeeName,
            success: function(msg){
                $('#accept-text').css('display', 'block');
                $('#accept-text').fadeOut(4000);
                $('#accept-button').removeClass('form-submit');
                $('#accept-button').addClass('form-submit-disabled');
                $('#reject-button').removeClass('form-submit');
                $('#reject-button').addClass('form-submit-disabled');
                $('#accept-button').attr('disabled', 'disabled');
                $('#reject-button').attr('disabled', 'disabled');
            }
        });
    }
    
    function rejectFeedback(prid, employeeName){
        $.ajax({
            type: "POST",
            url: '<?php echo $base_path ?>myworkstage/rejectfeedback/'+prid+'/'+employeeName,
            success: function(msg){
                $('#reject-text').css('display', 'block');
                $('#reject-text').fadeOut(4000);
                $('#accept-button').removeClass('form-submit');
                $('#accept-button').addClass('form-submit-disabled');
                $('#reject-button').removeClass('form-submit');
                $('#reject-button').addClass('form-submit-disabled');
                $('#accept-button').attr('disabled', 'disabled');
                $('#reject-button').attr('disabled', 'disabled');
            }
        });
    }
</script>
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
                    <?php print render($page['summary_feedback']) ?>
                </div>
            </div>
        </div>
    </div>
    <div id="pr_mywokingstage_footer" class="pr_footer">
        <?php require_once 'footer.tpl.php';?>
    </div>
</div>
