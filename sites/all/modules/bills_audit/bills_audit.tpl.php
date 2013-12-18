<?php
    global $base_path;
    $ajax_url = $base_path.'billsauditsubmit';
    $messages = drupal_get_messages();
?>

<script type="text/javascript">
    jQuery(function(){
        jQuery('.audit_operating').click(function(){
            var url = jQuery(this).attr('href');
            jQuery.post( url, function( html ) {
                jQuery('#alloc_detail').html(html);
                var apply_id = jQuery('#alloc_detail').find('#apply_id').val();
                var submit_url = "<?php print $ajax_url; ?>/"+apply_id+'/';
                jQuery('#alloc_detail').find('#bills_approve').click(function(){
                       jQuery.post( submit_url+"approve", function(  ) {
                           location.reload();
                       });
                });
                jQuery('#alloc_detail').find('#bills_disapprove').click(function(){
                       jQuery.post( submit_url+"disapprove", function(  ) {
                            location.reload();
                       });
                });
            });
            jQuery('.odd').css('background-color','#ffffff');
            jQuery('.even').css('background-color','#eeeeee');
            jQuery(this).parent().parent().css('background-color','#D9FCFC');
            return false;
        });
    });
</script>


<div id="alloc_detail">
</div>