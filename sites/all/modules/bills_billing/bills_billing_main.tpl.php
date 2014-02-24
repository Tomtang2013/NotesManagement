<?php
global $base_path;
$ava_types = get_billingable_bills_type();
$get_ava_no_path = $base_path . 'billing/bills_type_change_callback';
$submit_path = $base_path . 'billing/bills_billing_submit';

//    $ava_no = get_available_bill_no($type);
?>

<script type="text/javascript">
    jQuery(function(){
        init();
        jQuery('#edit-add').bind('click',function(){
            if(current_row==3){
                jQuery(this).attr('disable','true');
                return;
            }
            addoneline();
            limit_money_input();
            bind_count_event();
            count_summary();
            current_row ++;
        });

        jQuery('#edit-submit').bind('click',function(){
            var data = validate();
            var url = '<?php print $submit_path;?>';
            if(data!=null){
                jQuery.post(url, {'data':data}, function(re){
                    if('success' == re.message){
                        var item =  new Array();
                        for(var idx in data.usf){
                            var row = data.usf[idx];
                            item.push({'item':row.content,'amount':row.number * row.unit_price});
                        }
                        var total = 0;
                        for(var it in item){
                            total = total + item[it].amount;
                        }
                        total = total+ " " + digit_uppercase(total);
                        item.push({'item':'合计','amount' : total});
                        var t={'today': re.today,
                                'name': re.name,
                                'item':item};

                        myInvoicePreview(t);
                    }
                    location.reload();
                }, 'json');
            }
            return false;
        });

        jQuery('.messages').hide();
        limit_money_input();
        bind_count_event();
    });
    var error = null;
    function validate(){
        var type = jQuery('#edit-bills-type');
        var no = jQuery('#edit-bills-number');
        var unit = jQuery('#edit-bills-unit');
        var method = jQuery('#edit-payment-method');
        var contact = jQuery('#edit-bills-contact');
        
        error = new Array();
        check_empty(type.val(),type,'票据类型');
        check_empty(no.val(),no,'票据编号');
        check_empty(unit.val(),unit,'缴款单位/个人');
        check_empty(method.val(),method,'缴款方式');

        if(method.val() =="暂借"){
            check_empty(contact.val(),contact,'联系方式');
        } else {
            contact.removeClass('error');
        }

        var usf = new Array();
        jQuery('.tableline').each(function(){
            var content = jQuery(this).find('#edit-bills-payment-content');
            var number = jQuery(this).find('#edit-bills-number');
            var unit_price = jQuery(this).find('#edit-bills-unit-price');
            check_empty(content.val(),content,'缴款用途');
            check_empty(number.val(),number,'数量');
            check_empty(unit_price.val(),unit_price,'单价');

            usf.push({'content':content.val(),'number':number.val(),'unit_price':unit_price.val()});
        });
      
        if(error.length>0){
            jQuery('.messages').show();
            jQuery('.messages ul').empty();
            for(var i =0;i<error.length;i++){
                jQuery('.messages ul').append("<li>"+error[i]+"</li>");
            }
            return null;
        } else {
            return {'type':type.val(),'no':no.val(),'unit':unit.val()
                    ,'method':method.val(),'contact':contact.val(),'usf':usf};
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

    function bind_count_event(){
         jQuery('input.number').bind('change',function(){
            var number = jQuery(this).val();
            var price = jQuery(this).parent().parent().find('.money').val();
            var count = jQuery(this).parent().parent().find('.line-count');
            set_line_count_value(count,number*price);
        });

        jQuery('input.money').bind('change',function(){
            var price= jQuery(this).val();
            var number = jQuery(this).parent().parent().find('.number').val();
            var count = jQuery(this).parent().parent().find('.line-count');
            set_line_count_value(count,number*price);
        });
    }
    var  useful_list = null;
    var  current_row = 1;
//
    function set_line_count_value(count,value){
        jQuery(count).text(value.toFixed(2));
        count_summary();
    }

    function count_summary(){
        var summary_count = 0;
        jQuery('.line-count').each(function(){
            summary_count+=new Number(jQuery(this).text());
        });
        jQuery('#summary-count').text(summary_count.toFixed(2));
    }

    function get_ava_bills_no(){
        var type =  jQuery('#edit-bills-type').val();
        var url = '<?php print $get_ava_no_path ?>';
        var data = {'type':type};
        jQuery.post(url, data, function(re){
            if(re.ava_no){
                jQuery('#edit-bills-number').val(re.ava_no);
            } else {
                jQuery('#edit-bills-number').val('');
            }
            useful_list = re.ava_type_useful;
            set_useful_option();
        }, 'json');
    }

    function set_useful_option(){
        var objs = document.getElementsByName('bills_payment_content');
        for (i=0; i<objs.length; i++) {
            var obj = objs[i];
            obj.options.length = 0;
            obj.add(new Option("- 选择 -",""));
            for(var usf in useful_list){
                obj.add(new Option(useful_list[usf],usf));
            }
        }
    }

    function addoneline(){
        var first = jQuery('#tableline');
        var more = first.clone();
        jQuery(more).find('#edit-add').val('删除').bind('click',function(){
            jQuery(this).parent().parent().remove();
            current_row--;
            jQuery(this).attr('disable','');
            count_summary();
            return false;
        });
        first.parent().append(more);
       
    }

    function init(){
        jQuery('edit-bills-type').attr('selected', 'selected');
        jQuery('edit-bills-number').val("");
        jQuery('edit-bills-unit').val('');
        jQuery('.tableline').each(function(){
            jQuery('edit-bills-number').val('');
            jQuery('edit-bills-unit-price').val('');
        });
        
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
    <label for="edit-description">票据开具 </label>
</div>

<div class="bills-billing-form-item">
    <div class="billing-item">
        <label for="edit-bills-type">请选择票据类型 <span class="form-required" title="此项必填。">*</span></label>
        <select id="edit-bills-type" name="bills_type"
                autocomplete="off"
                class="form-select required" onchange="get_ava_bills_no()">
            <option value="" selected="selected">- 选择 -</option>
            <?php
            foreach ($ava_types as $type) {
                print '<option value="' . $type . '">' . $type . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="billing-item">
        <label for="edit-bills-no">可用编号 <span class="form-required" title="此项必填。">*</span></label>
        <input type="text" id="edit-bills-number" name="bills_no" value=""
               autocomplete="off"
               size="20" maxlength="20" class="form-text required" readonly="true"/>
    </div>
    <div class="billing-item">
        <label for="edit-bills-unit">缴款单位/个人 <span class="form-required" title="此项必填。">*</span></label>
        <input  type="text" id="edit-bills-unit" name="edit_bills_unit" value=""
                autocomplete="off"
                size="30" maxlength="60" class="form-text required"/>
     </div>
    <div style="clear:both;"></div>
</div>
<br />
<div class="bills-billing-form-item">
    <table class="bills-billing-table">
        <thead><tr ><th>款项内容 </th> <th>数量</th> <th>金额(单价)</th><th>合计</th><th>操作</th> </tr></thead>
        <tbody>
            <tr id="tableline" class="tableline">
               
                <td> <select style="width:100px;" id="edit-bills-payment-content" name="bills_payment_content" class="form-select required" >
                        <option value="" selected="selected">- 选择 -</option>
                    </select></td>
                <td><input class="number form-text" style="width:120px;" type="text" id="edit-bills-number"
                           onKeyUp="this.value=this.value.replace(/\D/g,'')"
                           autocomplete="off"
                           onafterpaste="this.value=this.value.replace(/\D/g,'')"
                           name="bills_number" value="1" size="11" maxlength="11" /></td>
                <td><input class="money form-text" style="width:120px;" type="text" id="edit-bills-unit-price"
                           autocomplete="off"
                           name="edit_bills_unit_price" value="" size="11" maxlength="11" /></td>
                <td style="width:120px;"><span class="line-count">0</span></td>
                <td><input type="button" id="edit-add" name="op" value="增加" class="form-submit" /></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="bills-billing-form-item">
    <div class="billing-item" style="padding-left:488px;">
        <span ><strong>总计:</strong> </span>
        <span id="summary-count">0</span>
    </div>
</div>
<div class="bills-billing-form-item">
    <div class="billing-item">
        <label for="edit-payment-method">缴款方式 <span class="form-required" title="此项必填。">*</span></label>
        <select id="edit-payment-method" name="bills_payment_method" class="form-select" >
            <option value="" selected="selected">- 选择 -</option>
            <option value="现金">现金</option>
            <option value="暂借">暂借</option>
        </select>
    </div>
    <div class="billing-item">
        <label for="edit-bills-contact">联系方式 </label>
        <input type="text" id="edit-bills-contact" name="bills_contact"
               autocomplete="off" value="" size="20" maxlength="20"
               class="form-text " >
    </div>
</div>
<div class="bills-billing-form-item" style="padding-top:30px;">
<input type="button" id="edit-submit" name="op" value="提交" class="form-submit" />
</div>

<object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0
           pluginspage="<?php print drupal_get_path('module', 'bills_billing').'/print_js/';?>install_lodop32.exe"></embed>
</object> 