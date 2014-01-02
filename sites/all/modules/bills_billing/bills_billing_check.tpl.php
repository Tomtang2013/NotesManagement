<?php
global $base_path;
$ava_no = get_ava_bills_no('支票');
$submit_path = $base_path.'billsbillingcheck/bills_billing_submit';
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
            if(data!=""){
                jQuery.post(url, {'data':data}, function(re){
//                    location.reload();
                }, 'json');
            }
        });
        
    });

    var error = null;
    function validate(){
        var no = jQuery('#edit-bills-no');
        var money = jQuery('#edit-bills-money');
        var account = jQuery('#edit-check-account');
        var org = jQuery('#edit-check-org');
        var usefor = jQuery('#edit-check-usefor');

        error = new Array();
        if(money.val() == '' || money.val() == null || money.val() == 0){
            money.addClass('error');
            error.push( "必须填写金额。");
         }else{
            money.removeClass('error');
         }
         if(no.val() ==  "暂无支票可用"){
            no.addClass('error');
            error.push( "暂无支票可用,请联系管理员。");
         }else{
            money.removeClass('error');
         }

        error = new Array();
        if(error.length>0){
            jQuery('.messages').show();
            jQuery('.messages ul').empty();
            for(var i =0;i<error.length;i++){
                jQuery('.messages ul').append("<li>"+error[i]+"</li>");
            }
            return null;
        } else {
            return {'money':money.val(),'no':no.val(),'org':org.val()
                    ,'account':account.val(),'usefor':usefor.val()};
        }
    }

    function check_empty(value,tarrget,title){
         if(value == null || value == '') {
            tarrget.addClass('error');
            error.push( "必须填写"+title+"。");
         } else if((title == '数量' || title == '单价')&&value == 0 ){
            tarrget.addClass('error');
            error.push( "必须填写"+title+"。");
         }else{
            tarrget.removeClass('error');
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
       <input type="text" id="edit-bills-type" name="bills_type" value="支票"
               size="20" maxlength="20" class="form-text required" readonly="true">
    </div>
    <div class="billing-item">
        <label for="edit-bills-no">可用编号 <span class="form-required" title="此项必填。">*</span></label>
        <input type="text" id="edit-bills-no" name="bills_no" value="<?php print $ava_no?>"
               size="20" maxlength="20" class="form-text required" readonly="true">
    </div>
    <div style="clear:both;"></div>
</div>

<div class="bills-billing-form-item">
    <div class="billing-item">
        <label for="edit-check-account">收款人账号 </label>
        <input type="text" id="edit-check-account" name="check_account" value=""
               onKeyUp="this.value=this.value.replace(/\D/g,'')"
               onafterpaste="this.value=this.value.replace(/\D/g,'')"
               size="40" maxlength="40" class="form-text required" >
    </div>
    <div class="billing-item">
        <label for="edit-check-org">收款单位 </label>
        <input type="text" id="edit-check-org" name="check_org" value=""
               size="40" maxlength="40" class="form-text required" >
    </div>
</div>
<div class="bills-billing-form-item">
     <div class="billing-item">
        <label for="edit-check-bank">开户银行 </label>
        <input type="text" id="edit-check-bank" name="check_bank" value=""
               size="40" maxlength="40" class="form-text required" >
    </div
    <div style="clear:both;"></div>
</div>


<div class="bills-billing-form-item">
     <div class="billing-item">
        <label for="edit-check-usefor">用途 </label>
        <input type="text" id="edit-check-usefor" name="check_usefor" value=""
               size="40" maxlength="40" class="form-text required" >
    </div>
     <div class="billing-item">
        <label for="edit-bills-money">金额 <span class="form-required" title="此项必填。">*</span></label>
        <input class="money form-text"  type="text" id="edit-bills-money"
                           name="edit_bills_money" value="" size="40" maxlength="40" />
    </div>

    <div style="clear:both;"></div>
</div>
   

<div class="bills-billing-form-item" style="padding-top:30px;">
<input type="button" id="edit-submit" name="op" value="提交" class="form-submit" />
</div>
