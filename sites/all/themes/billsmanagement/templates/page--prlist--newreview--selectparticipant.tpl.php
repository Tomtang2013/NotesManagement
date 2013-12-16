<?php $module_path = 'http://' . $_SERVER['HTTP_HOST'] . base_path() . '/' . drupal_get_path('module', 'working_stage') ?>
<?php $base_path = 'http://' . $_SERVER['HTTP_HOST'] . base_path() ?>
<link rel="stylesheet" href="<?php echo $module_path ?>/css/common.css" type="text/css" />
<link type="text/css" href="<?php echo $module_path ?>/css/ui.multiselect.css" rel="stylesheet" />
<link type="text/css" href="<?php echo $module_path ?>/css/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $module_path ?>/js/plugins/localisation/jquery.localisation-min.js"></script>
<script type="text/javascript" src="<?php echo $module_path ?>/js/plugins/scrollTo/jquery.scrollTo-min.js"></script>
<script type="text/javascript" src="<?php echo $module_path ?>/js/ui.multiselect.js"></script>
<script type="text/javascript" src="<?php echo $module_path ?>/js/woking-stage-multiple-select.js"></script>
<script type="text/javascript">        
    function submitParticipant(){
        $('#loading_data_participant').css("display", 'block');
        var val = "";
        var name = "";
        var count = 0;
<?php
if ($_SESSION['pr_list_id'] != null) {
    $prid = $_SESSION['pr_list_id'];
} else {
    $prid = -1;
}
?>
        $("#users option:selected").each(function() {
            val += $(this).val()+ ",";
            count++;
        });
        if(count == 0){
            alert("You should choose at least 1 people!");
            $('#loading_data_participant').css("display", 'none');
            return false;
        }
        $("#users option:selected").each(function() {
            name += $(this).text()+ "";
        });
    
        $.ajax({
            type: "POST",
            url: '<?php echo $base_path ?>myworkstage/submitparticipant/<?php echo $prid ?>/'+val,
            success: function(msg){
                $('#loading_data_participant').css("display", 'none');
                //                $('#participant_message').attr('innerHTML', 'Your changes have been saved.');
                //                $('#participant_message').css("display", "block");
                window.location.href = "<?php print base_path() . 'prlist/newreview/mailsettings' ?>";
            }
        });
    }
</script>
<div id="pr_select_participant_page" class="pr_page">
    <?php require_once 'header.tpl.php';?>
    <div id="pr_select_participant_content" class="pr_content">
        <div id="pr_select_participant_content_left" class="pr_content_left">
            <?php
            $navigation_tree = menu_tree(variable_get('menu_main_links_source', 'navigation'));
            print drupal_render($navigation_tree);
            $review = load_editing_review();
            ?>
        </div>
        <div id="pr_select_participant_content_right" class="pr_content_right">
            <div class="pr_workingstage_connent">
                <?php if (!is_null($review)): ?>
                    <div>
                        <label>Description:</label>
                        <input id="edit_review_description" style="width: 250px;" name="<?php print $review->active ?>" value="<?php print $review->description ?>" />
    <!--                        <input type="button" id="update_description" name="<?php print $review->prid ?>" class="form-submit" onClick="updateStatus()" value ="Change Description" />-->
                        <input type="button" id="change_status" style="display: none" class="form-submit" onClick="changeStatus()" value ="Start Review" />
                        <img class="loading_img" id="status_loading_img_desc" title="loading..." style="width: 25px; height: 25px" src="<?php print base_path() . drupal_get_path('theme', 'performancereview') . '/images/loading.gif' ?>" />
                        <input type="hidden" id="base_path" value="<?php print base_path(); ?>" />
                    </div>
                <?php endif; ?>
                <?php
                $tabs = menu_local_tasks();
                if ($tabs['tabs']['count'] >= 1):
                    ?><ul class="pr360-tabs"><?php print render($tabs['tabs']['output']); ?></ul><?php endif; ?>

                <div>
                    <?php
//                $review_id = isset($_SESSION['pr_list_id']) ? $_SESSION['pr_list_id'] : (variable_get('editing_review_id') ? variable_get('editing_review_id') : 0);
//                $flag = is_preparation_done($review_id);
//                if ($flag):
                    ?>
    <!--                    <input type="hidden" id="base_path" value="<?php print base_path(); ?>" />
                        <input type="button" id="change_status" style="display: none" class="form-submit" onClick="changeStatus()" value ="Start Review">-->
                    <?php // endif; ?>
                </div>
                <div id="participant_message" style="display: none" class="messages status"></div>
                <?php print_r($messages); ?>
                <?php print render($page['select_review_participant']) ?>
            </div>
        </div>
    </div>
    <div class="hidden_box">
        <div id="start_review">
            <div id="error_message" class="messages error"></div>
            <div id="status_message" class="messages status"></div>
            <div>
                <label>Do you want to start this review? Once the review has been started, you cannot configure the timeslot and participants any more.</label>
                <div style="text-align: center"><input type="button" id="start_btn" class="form-submit" onclick="start_review()" value="Yes" />
                    <input type="button" id="cancel_btn" class="form-submit" onclick="start_review_cancel()" value="No" />
                    <img class="loading_img" id="status_loading_img" title="loading..." style="width: 25px; height: 25px" src="<?php print base_path() . drupal_get_path('theme', 'performancereview') . '/images/loading.gif' ?>">
                    <input type="hidden" id="base_path" value="<?php print base_path(); ?>" />
                </div>
            </div>
        </div>
    </div>
    <div id="pr_select_participant_footer" class="pr_footer">
        <?php require_once 'footer.tpl.php';?>
    </div>
</div>
