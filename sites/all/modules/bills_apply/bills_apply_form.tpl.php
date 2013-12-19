<?php
    global $base_path;
    $ajax_url = $base_path.'billsapplysubmit';
    $messages = drupal_get_messages();
?>

<script type="text/javascript">
    jQuery(function(){
        jQuery("#add_alloc").fancybox({
            'titlePosition'		: 'inside',
            'width'                     : 'auto',
            'height'                     : 'auto',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none'
        });

        jQuery('#add_alloc_submit').click(function(){
            var number = jQuery('#number').val();
            var usefor = jQuery('#usefor').val();
            jQuery('#bill_apply_form_alloc').append('<div class="alloc_row"><div class="alloc_item alloc_usefor">'+usefor+'</div>\n\
            <div class="alloc_item alloc_number">'+number+'</div>\n\
            <div class="alloc_action_item">\n\
                <a id="add_alloc" href="#" onclick="remove_item(this)" class="form-submit">删除</a>\n\
            </div></div>');
            jQuery.fancybox.close();
            jQuery('#number').val('')
            jQuery('#usefor').val('')
        });

        jQuery('#bills_apply_submit').click(function(){
            var unit = jQuery('#unit').val();
            var amount = jQuery('#amount').val();
            var bills_type =  jQuery('#bills_type').val();
            var alloc_data = prepare_alloc_data();
            if(alloc_data.length ==0) alloc_data = 'noData';
            var url = '<?php print $ajax_url;?>';
            var data = { 'unit': unit,'amount': amount,'bills_type':bills_type,'alloc_data': alloc_data };
            jQuery.post( url,data, function( respo ) {
                location.reload();
            });
        });
    });

    function prepare_alloc_data(){
        var data = new Array();
        jQuery('#bill_apply_form_alloc .alloc_row').each( function(){
            var alloc = new Array();
            var usefor = jQuery(this).find('.alloc_usefor').text();
            var mumber = jQuery(this).find('.alloc_number').text();
            alloc.push(usefor);
            alloc.push(mumber);
            data.push(alloc);
        });
        return data;
    }

    function remove_item(t){
        jQuery(t).parent().parent().remove();
    }

</script>
<?php if ($messages): ?>
    <?php foreach ($messages as $status => $msgs): ?>
            <div class="messages <?php print $status ?>">
                <ul>
            <?php foreach ($msgs as $msg): ?>
                <li><?php print $msg ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div id="bills_apply_form">
    <div id="edit-description" class="" >
        <h1><strong>票据申请</strong> </h1>
    </div>
    <hr style="clear:none;margin-top:20px;margin-bottom:20px;height:2px;"/>
    <div style="width:600px;">
        <div id="bill_apply_form_alloc">
            <div>
                <div class="bills_apply_form_alloc_title">用途</div>
                <div class="bills_apply_form_alloc_title">数量</div>
                <div class="bills_apply_form_alloc_action">
                    <a id="add_alloc" href="#fancy_box_use_div"  class="form-submit">增加</a>
                </div>
            </div>
        </div>
    </div>
    <hr style="clear:none;margin-top:20px;margin-bottom:20px;height:1px;"/>
    <div>
        <div style="float:left;">
            <span ><strong>往来单位:</strong> </span>
            <input type="text" id="unit" value="" size="20" maxlength="10" class="form-text">
        </div>
        <div style="float:left;padding-left: 50px;">
            <span ><strong>金额:</strong> </span>
            <input type="text" id="amount" value="" size="20" maxlength="10" class="form-text">
        </div>
        <div style="float:left;clear:left;padding-top: 10px;">
            <span ><strong>票据类型:</strong> </span>
            <select id="bills_type" class="bills_type_select" >
                <option value="NA">- 选择 -</option>
                <option value="发票">发票</option>
                <option value="地税">地税</option>
                <option value="国税">国税</option>
            </select>
        </div>
    </div>
    <input type="button" style="clear:both;margin-left: 40px; clear:left;"
       id="bills_apply_submit" name="op" value="提交" class="form-submit">
</div>



<div style="display:none;">
    <div id="fancy_box_use_div" style="width:270px;height:200px;" >
        <h2 ><strong>追加用途</strong></h2>
        <div >
            <label for="usefor"> 用途: </label>
            <input type="text" id="usefor" value="" size="40" maxlength="10" class="form-text">
        </div>
        <div>
            <label for="unit"> 数量: </label>
            <input type="text" id="number" value="" size="40" maxlength="10" class="form-text">
        </div>
        <div style="float:right;padding-top: 10px;">
            <input  type="button" style="" id="add_alloc_submit" name="op"
                    value="增加" class="form-submit">
        </div>
    </div>
</div>