<?php
    global $base_path;
    $submit_url = $base_path.'billsticketsubmit';
    $available_number_url = $base_path.'billsticketavailablenumber';
?>

<script type="text/javascript">
    jQuery(function(){
        jQuery('#bills_type').change(function(){
            if(this.value == 'N/A'){
                jQuery('#available_billsno')
                    .html('<span  class="unavailableno">请先选择票据类型</span>');
                return;
            }

            var available_number_url = '<?php print $available_number_url; ?>';
            var data = { type:this.value };
            jQuery.post(available_number_url,data, function( respo ) {
                jQuery('#available_billsno').html(respo);
            });
        });
    });
</script>

<div style="width:600px;" id="bills_ticket_main">
    <div class="bills_form_row">
        <label for="available_billsno">可用编号:</label>
        <span id="available_billsno"><span  class="unavailableno">请先选择票据类型</span></span>
    </div>
    <div class="bills_form_row">
        <label for="available_billsno">票据类型:</label>
        <select id="bills_type" class="bills_type_select">
            <option value="NA">- 选择 -</option>
            <option value="发票">发票</option>
            <option value="地税">地税</option>
            <option value="国税">国税</option>
        </select>
    </div>
</div>