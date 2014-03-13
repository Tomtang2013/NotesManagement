<?php


function bills_wrong_form($form, &$form_state) {
    $form = array();

    $form['description'] = array(
        '#type' => 'item',
        '#title' => t("错票处理"),
    );

     $form['bills_no'] = array(
        '#type' => 'textfield',
        '#title' => t("错票号"),
        '#required' => TRUE,
        '#autocomplete_path' => 'bills/billsno_wrong_autocomplete',
    );



     $form['submit'] = array(
        '#type' => 'submit',
        '#value' => '提交',
    );


    return $form;
}


function bills_wrong_form_validate($form, &$form_state) {
    $bills_no = $form_state['values']['bills_no'] ;
    if( is_no_exist($bills_no,'已开具')){
        form_set_error('bills_no', t('该编号不存在,或并未“已开具”状态。'));
    }

}

function bills_wrong_form_submit($form, &$form_state) {
   $bills_no = $form_state['values']['bills_no'] ;

    $query = db_update('bills')
      ->fields(array(
        'report_date' => time(),
        'status' => '已报错',
      ))
      ->condition('billsno', $bills_no, '=');
    global $user;
    try {
        $result = $query->execute();
        bills_log_write_row($bills_no,$user->uid,'报错');
        drupal_set_message(t("票据报错成功"));
    } catch (Exception $e) {
        drupal_set_message('票据报错失败，请联系管理员', 'error');
    }
}