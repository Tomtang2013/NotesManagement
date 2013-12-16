<?php $module_path = 'http://' . $_SERVER['HTTP_HOST'] . base_path() . drupal_get_path('module', 'working_stage') ?>
<?php $base_path = 'http://' . $_SERVER['HTTP_HOST'] . base_path() ?>
<?php
if ($_POST['prid'] != null) {
    $prid = $_POST['prid'];
} else {
    $prid = -1;
}
$review = load_review($prid);
$min_number = !is_null($review) ? $review->min_provider_number : MIN_FEEDBACK_NUMBER;
$max_number = !is_null($review) ? $review->max_provider_number : MAX_FEEDBACK_NUMBER;
global $user;
$cur_name = $user->name;
$completed_count = get_completed_providers_count_db($prid);
$selected_count = get_completed_providers_count_db($prid, FALSE);
?>
<link rel="stylesheet" href="<?php echo $module_path ?>/css/common.css" type="text/css" />
<link type="text/css" href="<?php echo $module_path ?>/css/ui.multiselect.css" rel="stylesheet" />
<link type="text/css" href="<?php echo $module_path ?>/css/jquery-ui.css" rel="stylesheet" />

<script type="text/javascript" src="<?php echo $module_path ?>/js/plugins/localisation/jquery.localisation-min.js"></script>
<script type="text/javascript" src="<?php echo $module_path ?>/js/plugins/scrollTo/jquery.scrollTo-min.js"></script>
<script type="text/javascript" src="<?php echo $module_path ?>/js/ui.multiselect.js"></script>
<script type="text/javascript" src="<?php echo $module_path ?>/js/woking-stage-multiple-select.js"></script>

<script type="text/javascript" src="<?php echo $module_path ?>/js/jquery.fancybox.js"></script>
<link type="text/css" href="<?php echo $module_path ?>/css/jquery.fancybox.css" rel="stylesheet" />

<script type="text/javascript">
    $(function(){
        $('#AddExternalProvider').fancybox({
            helpers : {
                overlay : {
                    css : {
                        'background' : 'rgba(0,0,0,0.5)'
                    }
                }
            }
        });
    });
</script>
<script type="text/javascript">
    function submitFeedbackProvider(){
        $('#loading_data').css("display", 'block');
        var val = "";
        var name = "";
        var count = 0;
        $("#users option:selected").each(function() {
            val += $(this).val()+ ",";
            count++;
        });
        if(count+<?php echo $completed_count ?> > <?php echo $max_number ?>){
            alert("You should not choose more than <?php echo $max_number ?> people!");
            $('#loading_data').css("display", 'none');
            return false;
        }
        if(count+<?php echo $completed_count ?> < <?php echo $min_number; ?>){
            alert("You should choose at least <?php echo $min_number; ?> people!");
            $('#loading_data').css("display", 'none');
            return false;
        }
        //        $("#users option:selected").each(function() {
        //            name += $(this).text()+ ",";
        //        });
        $.ajax({
            type: "POST",
            url: '<?php echo $base_path ?>myworkstage/submitfeedbackprovider/<?php echo $prid ?>/<?php echo $cur_name ?>/'+val+'<?php echo $cur_name ?>',
            success: function(msg){
                window.location.href="<?php echo $base_path ?>myworkstage";
                $('#loading_data').css("display", 'none');
            }
        });
    }
        
    function AddFeedbackProvider(){
        var firstName = $('#addtionFirstName').val();
        var lastName = $('#addtionLastName').val();
        var email = $('#addtionEmail').val();
    
        var re = /^[A-Za-zd=#$%...-]+$/;
        if (!re.test(firstName)) {
            alert('The first name you entry contains illegal characters');
            return false;
        }
        
        if (!re.test(lastName)) {
            alert('The last name you entry contains illegal characters');
            return false;
        }
        
        if(!email.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)){
            alert('The email you entry is not correct.');
            return false;
        }
    
        if(firstName == "" || lastName== ""  || email == ""){
            alert("Need name and email!");
            $('#addtion_loading_data').css("display", 'none');
            return false;
        }

        var multiselectObj = $('#users');
        multiselectObj.append("<option selected='selected' value='"+firstName+"."+lastName+"$"+email+"'>"+firstName+" "+lastName+" (external)</option>");
        destoryMultiselect();
        renderMultiselect();
    
        $.fancybox.close();
    
    }
</script>
<div id="pr_select_feedback_provider_page" class="pr_page">
    <?php require_once 'header.tpl.php'; ?>
    <div id="pr_select_feedback_provider_content" class="pr_content">
        <div id="pr_select_feedback_provider_content_left" class="pr_content_left">
            <?php $navigation_tree = menu_tree(variable_get('menu_main_links_source', 'navigation'));
            print drupal_render($navigation_tree); ?>
        </div>
        <div id="pr_select_feedback_provider_content_right" class="pr_content_right">
            <div class="pr_workingstage_connent">
                <div id="pr_right_content">
                    <?php print render($page['my_woking_stage_select_feedback_provider']) ?>
                </div>
            </div>
        </div>
    </div>
    <div id="pr_select_feedback_provider_footer" class="pr_footer">
        <?php require_once 'footer.tpl.php'; ?>
    </div>
</div>
