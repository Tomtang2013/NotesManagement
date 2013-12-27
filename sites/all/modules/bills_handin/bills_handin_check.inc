<?php
function bills_handin_check_form($form, &$form_state) {
    $header = array(
        'billsno' => t('票据编号'),
        'type' => t('票据类型'),
        'status' => t('票据状态'),
        'amount' => t('票据金额'),
    );
    $query = db_select('bills', 'b')
                    ->extend('PagerDefault');

    $fields = array('billsno', 'type', 'status', 'amount');

    $query->fields('b', $fields);
    $str_query = $query
                    ->condition('b.status', '已缴销', '=')
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
        '#title' => t("确认缴销票据"),
    );

    $form['table'] = array(
        '#type' => 'tableselect',
        '#header' => $header,
        '#options' => $rows,
        '#multiple' => TRUE,
    );

    $form['pager'] = array('#markup' => theme('pager'));


     $form['submit'] = array(
        '#type' => 'submit',
        '#value' => '提交',
    );


    return $form;
}


function bills_handin_check_form_validate($form, &$form_state) {
   $table = $form_state['values']['table'] ;
   $rst = 0;
   foreach($table as $row){
       if($row !=0){
           $rst = $row;
            break;
       }
   }
   if($rst == 0)
       form_set_error('', t('请选择已经收到的缴销票据编号.'));
}

function bills_handin_check_form_submit($form, &$form_state) {
    $table = $form_state['values']['table'] ;

   $bill_nos = array();
   foreach($table as $row){
       if($row > 0){
           $bill_nos[] =$row;
       }
   }
   global $user;
    $query = db_update('bills')
      ->fields(array(
        'report_date' => time(),
        'status' => '已确认',
      ))
      ->condition('billsno', $bill_nos, 'in');
    try {
        $result = $query->execute();
        bills_log_write_array($bill_nos,$user->uid,'缴销确认');
        drupal_set_message(t("票据缴销确认成功"));
    } catch (Exception $e) {
        drupal_set_message('票据缴销确认失败，请联系管理员', 'error');
    }
}