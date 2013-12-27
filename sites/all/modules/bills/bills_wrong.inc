<?php


function bills_wrong_form($form, &$form_state) {
    $header = array(
        'billsno' => t('票据编号'),
        'type' => t('票据类型'),
        'status' => t('票据状态'),
        'amount' => t('票据金额'),
    );
    $query = db_select('bills', 'b')
                    ->extend('PagerDefault');

    $fields = array('billsno', 'type', 'status', 'amount');

    global $user;

    $query->fields('b', $fields);
    $str_query = $query
                    ->condition('b.status', '已开具', '=')
                    ->condition('b.distribution_uid', $user->uid, '=')
                    ->limit(10)
                    ->orderBy('b.billsno');
    $result = $str_query->execute();

    $rows = array();
    foreach ($result as $row) {
        $rows[$row->billsno] = array(
            'billsno' => $row->billsno,
            'type' => $row->type,
            'status' => $row->status,
            'amount' => $row->amount,
        );
    }
    $form = array();

    $form['description'] = array(
        '#type' => 'item',
        '#title' => t("错票处理"),
    );

    $form['table'] = array(
        '#type' => 'tableselect',
        '#header' => $header,
        '#options' => $rows,
        '#multiple' => FALSE,
    );

    $form['pager'] = array('#markup' => theme('pager'));


     $form['submit'] = array(
        '#type' => 'submit',
        '#value' => '提交',
    );


    return $form;
}


function bills_wrong_form_validate($form, &$form_state) {
    $value = $form_state['values']['table'] ;

   if(empty($value))
       form_set_error('', t('请选择需报错的票据编号.'));
}

function bills_wrong_form_submit($form, &$form_state) {
   $bills_no = $form_state['values']['table'] ;

    $query = db_update('bills')
      ->fields(array(
        'report_date' => time(),
        'status' => '已报错',
      ))
      ->condition('billsno', $bills_no, '=');
    global $user;
    try {
        $result = $query->execute();
        bills_log_write_one($bill_no,$user->uid,'报错');
        drupal_set_message(t("票据报错成功"));
    } catch (Exception $e) {
        drupal_set_message('票据报错失败，请联系管理员', 'error');
    }
}