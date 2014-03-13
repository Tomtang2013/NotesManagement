<?php

function bills_disable_form($form, &$form_state) {
    $form = array();

    $form['description'] = array(
        '#type' => 'item',
        '#title' => t("错票处理"),
    );

     $form['bills_no'] = array(
        '#type' => 'textfield',
        '#title' => t("废票号"),
        '#required' => TRUE,
        '#autocomplete_path' => 'bills/billsno_disable_autocomplete',
    );



     $form['submit'] = array(
        '#type' => 'submit',
        '#value' => '提交',
    );


    return $form;
}


function bills_disable_form_validate($form, &$form_state) {
    $bills_no = $form_state['values']['bills_no'] ;
    if( is_no_exist($bills_no,'已分配')){
        form_set_error('bills_no', t('该编号不存在,或并未“已分配”状态。'));
    }

}

function bills_disable_form_submit($form, &$form_state) {
    $bill_no = $form_state['values']['bills_no'] ;

    $query = db_update('bills')
      ->fields(array(
        'report_date' => time(),
        'status' => '已作废',
      ))
      ->condition('billsno', $bill_no, '=');
    global $user;
    try {
        $result = $query->execute();
        bills_log_write_row($bill_no,$user->uid,'作废');
        drupal_set_message(t("票据作废成功"));
    } catch (Exception $e) {
        watchdog_exception('bills disable error', $e);
        drupal_set_message('票据作废失败，请联系管理员', 'error');
    }
}