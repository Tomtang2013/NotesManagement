<?php
global $base_path;
$ava_no = get_ava_bills_no('支票');
$submit_path = $base_path.'billsbillingcheck/bills_billing_submit';
$check_auto_path = $base_path.'billsbillingcheck/bills_billing_auto';
if(empty($ava_no)){
    $ava_no = "暂无支票可用";
}
//    $ava_no = get_available_bill_no($type);
?>

<script type="text/javascript">
    jQuery(function(){
       limit_money_input();
       jQuery('.messages').hide();
       jQuery('#edit-submit').bind('click',function(){
            var data = validate();
            var url = '<?php print $submit_path;?>';
            console.log(data);
            if(data!=null){
                jQuery.post(url, {'data':data}, function(re){
                    location.reload();
                }, 'json');
            }
        });

        var auto_list = null;
        jQuery( "#edit-check-account" ).autocomplete({
            source: function(request,response ) {
                      var auto_path = '<?php print $check_auto_path;?>';
                      jQuery.post(
                             auto_path,
                            {data: {
                                max_row: 12,
                                str: request.term
                            }},
                            function( data ) {
                                response( jQuery.map( data, function( item ) {
                                    auto_list = data;
                                    return {
                                        label: item.account + "("+ item.org+","+ item.bank+")",
                                        value: item.account
                                    }
                                }));
                                
                                jQuery('.ui-corner-all a').one('click',function(){
                                   var select_item = auto_list[jQuery(this).parent().index()];
                                   jQuery('#edit-check-org').val(select_item['org']);
                                   jQuery('#edit-check-bank').val(select_item['bank']);
                                });
                            },
                            "json"
                        );
            }
        });
   });

    var error = null;
    function validate(){
        var no = jQuery('#edit-bills-no');
        var account = jQuery('#edit-check-account');
        var org = jQuery('#edit-check-org');
        var usefor = jQuery('#edit-check-usefor');
        var bank = jQuery('#edit-check-bank');
        var amount = jQuery('#edit-bills-money');
        var is_filter = jQuery('#edit-check-is-filter');
        
        error = new Array();
        if(amount.val() == '' || amount.val() == null || amount.val() == 0){
            amount.addClass('error');
            error.push( "必须填写金额。");
         }else{
            amount.removeClass('error');
         }
         if(no.val() ==  "暂无支票可用"){
            no.addClass('error');
            error.push( "暂无支票可用,请联系管理员。");
         }else{
            no.removeClass('error');
         }

      
        if(error.length>0){
            jQuery('.messages').show();
            jQuery('.messages ul').empty();
            for(var i =0;i<error.length;i++){
                jQuery('.messages ul').append("<li>"+error[i]+"</li>");
            }
            return null;
        } else {
            return {'no':no.val(),'org':org.val()
                    ,'account':account.val(),'usefor':usefor.val()
                    ,'bank':bank.val(),'amount':amount.val()
                    ,'is_filter':is_filter.is(':checked')};
        }
    }


    function limit_money_input() {
        jQuery("input.money").bind("contextmenu", function(){
            return false;
        });

        jQuery("input.money").css('ime-mode', 'disabled');

        jQuery("input.money").bind("keydown", function(e) {
            var key = window.event ? e.keyCode : e.which;
            if (isFullStop(key)) {
                return jQuery(this).val().indexOf('.') < 0;
            }
            return (isSpecialKey(key)) || ((isNumber(key) && !e.shiftKey));
        });
    }

    function isNumber(key) {
        return key >= 48 && key <= 57
    }

    function isSpecialKey(key) {
        //8:backspace; 46:delete; 37-40:arrows; 36:home; 35:end; 9:tab; 13:enter
        return key == 8 || key == 46 || (key >= 37 && key <= 40) || key == 35 || key == 36 || key == 9 || key == 13
    }

    function isFullStop(key) {
        return key == 190 || key == 110;
    }
</script>
<div class="messages error" >
 <ul>
 </ul>
</div>
<div id="edit-description" class="form-item form-type-item">
    <label for="edit-description">支票开具 </label>
</div>

<div class="bills-billing-form-item">
    <div class="billing-item">
        <label for="edit-bills-type">票据类型 <span class="form-required" title="此项必填。">*</span></label>
       <input type="text" id="edit-bills-type" name="bills_type" value="支票"  autocomplete="off"
               size="20" maxlength="20" class="form-text required" readonly="true"/>
    </div>
    <div class="billing-item">
        <label for="edit-bills-no">可用编号 <span class="form-required" title="此项必填。">*</span></label>
        <input type="text" id="edit-bills-no" name="bills_no" value="<?php print $ava_no?>"
               size="20" maxlength="20" autocomplete="off" class="form-text required" readonly="true"/>
    </div>
    <div style="clear:both;"></div>
</div>

<div class="bills-billing-form-item">
    <div class="billing-item">
        <label for="edit-check-account">收款人账号 </label>
        <input type="text" id="edit-check-account" name="check_account" value=""
               onKeyUp="this.value=this.value.replace(/\D/g,'')" autocomplete="off"
               onafterpaste="this.value=this.value.replace(/\D/g,'')"
               size="40" maxlength="40" class="form-text " />
    </div>
    <div class="billing-item">
        <label for="edit-check-org">收款单位 </label>
        <input type="text" id="edit-check-org" name="check_org" value="" autocomplete="off"
               size="40" maxlength="40" class="form-text " />
    </div>
</div>
<div class="bills-billing-form-item">
     <div class="billing-item">
        <label for="edit-check-bank">开户银行 </label>
        <input type="text" id="edit-check-bank" name="check_bank" value="" autocomplete="off"
               size="40" maxlength="40" class="form-text " />
     </div>
    <div class="billing-item">
        <label for="edit-check-is-filter">是否保存 </label>
        <input type="checkbox" id="edit-check-is-filter" name="check_is_filter" value=""
               class="form-text " >
    </div
    <div style="clear:both;"></div>
</div>


<div class="bills-billing-form-item">
     <div class="billing-item">
        <label for="edit-check-usefor">用途 </label>
        <input type="text" id="edit-check-usefor" name="check_usefor" value="" autocomplete="off"
               size="40" maxlength="40" class="form-text required" />
    </div>
     <div class="billing-item">
        <label for="edit-bills-money">金额 <span class="form-required" title="此项必填。">*</span></label>
        <input class="money form-text"  type="text" id="edit-bills-money" autocomplete="off"
                           name="edit_bills_money" value="" size="40" maxlength="40" />
    </div>

    <div style="clear:both;"></div>
</div>
   

<div class="bills-billing-form-item" style="padding-top:30px;">
<input type="button" id="edit-submit" name="op" value="提交" class="form-submit" />
</div>
