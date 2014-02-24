<?php

?>

<script type="text/javascript">
    jQuery(function(){
        jQuery('.print-link').click(function(){
           var url = jQuery(this).attr('href');
           jQuery.post(url, null, function(re){
                if(re.type == "check"){
                     var t={ 'year': re.year,
                            'month': re.month,
                            'day': re.day,
                            'year1': changeDateYear(re.year),
                            'month1': changeDateMonth(re.month),
                            'day1': dayToBig(re.day),
                            'name': re.name,
                            'amount1': re.amount,
                            'amount2':digit_uppercase(re.amount),
                            'amount3':prepareAmount(re.amount)};
                    myCheckPreview(t);
                } else  if(re.type == "bills"){
                   
                    var item = new Array();
                    for(var idx in re.items){
                        item.push({'item':re.items[idx].content,'amount' : re.items[idx].amount});
                    }
                    
                    item.push({'item':'合计','amount' : re.amount});
                    var t={'today': re.today,
                            'name': re.name,
                            'item':item
                           };
                    myInvoicePreview(t);
                }

           }, 'json');
            return false;
        });
       
    });

</script>

<object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0
           pluginspage="<?php print drupal_get_path('module', 'bills_billing').'/print_js/';?>install_lodop32.exe"></embed>
</object> 