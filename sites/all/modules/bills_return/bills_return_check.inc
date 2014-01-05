<?php
function bills_return_check_form($form, &$form_state) {

     $form = array();

     $form['description'] = array(
        '#type' => 'item',
        '#title' => t("确认退回票据"),
     );

     $form['return_no'] = array(
        '#type' => 'textfield',
        '#size' => '20',
        '#required' => TRUE,
        '#maxlength' => '20',
        '#title' => t('退回编号'),
    );


     $form['submit'] = array(
        '#type' => 'submit',
        '#value' => '检索',
    );

     $form['return_confirm'] = array(
        '#type' => 'submit',
        '#value' => '确认退回',
    );
      $form['return_cancel'] = array(
        '#type' => 'submit',
        '#value' => '拒绝退回',
    );

     if (!empty($form_state['return_no'])) {
        $header = array(
            'nos' => t('票据编号区间'),
            'number' => t('张数'),
//            'disable' => t('已作废'),
//            'wrong' => t('已报错'),
//            'amount' => t('金额'),
        );
        $query = db_select('bills_return', 'bh')
                        ->extend('PagerDefault');

        $fields = array('start_no', 'end_no');

        $query->fields('bh', $fields);
        $str_query = $query
                        ->condition('bh.return_no', $form_state['return_no'])
                        ->limit(10)
                        ->orderBy('bh.return_id');
        $result = $str_query->execute()->fetchAll();

        $rows = array();
        foreach ($result as $row) {
            $rows[] = array(
                'nos' => $row->start_no.'～'.$row->end_no,
                'number' => $row->end_no - $row->start_no + 1,
//                'disable' => $row->disable,
//                'wrong' => $row->wrong,
//                'amount' => $row->amount,
                );
        }

        $form['table'] = array(
            '#theme' => 'table',
            '#header' => $header,
            '#rows' => $rows,
        );

        $form['pager'] = array('#markup' => theme('pager'));
    }
    return $form;
}


function bills_return_check_form_validate($form, &$form_state) {
//   $table = $form_state['values']['table'] ;
//   $rst = 0;
//   foreach($table as $row){
//       if($row !=0){
//           $rst = $row;
//            break;
//       }
//   }
//   if($rst == 0)
//       form_set_error('', t('请选择已经收到的退回票据编号.'));
}

function bills_return_check_form_submit($form, &$form_state) {
    global $user;
    if ($form_state['values']['op'] == '检索') {
        $form_state['return_no'] = $form_state['values']['return_no'];
        $form_state['rebuild'] = TRUE;
    } else {
        $status = '可用';
        $opt = '退回确认';

        if ($form_state['values']['op'] == '拒绝退回') {
            $status = '已分配';
            $opt = '退回拒绝';
            $fields =array('status' => $status);
        } else {
             $fields =array(
                 'status' => $status,
                 'distribution_uid' =>0,
             );
        }
        $or = get_start_end_or($form_state['values']['return_no']);



        $query = db_update('bills')
                        ->fields($fields)
                        ->condition($or)
                        ->condition('status', '已退回')
                        ->condition('distribution_uid', $user->uid);
        try {

            $result = $query->execute();
//            bills_log_start_end($start,$end, $user->uid, $opt);
            bills_log_write_row($form_state['values']['return_no'], $user->uid, $opt);
            drupal_set_message(t("票据 $opt 成功" ));
        } catch (Exception $e) {
            watchdog_exception('bills return error', $e);
            drupal_set_message("票据 $opt 失败，请联系管理员", 'error');
        }
    }

}

function get_start_end_or($return_no) {
    $return_query = db_select('bills_return')->fields(
                            'bills_return',array('start_no', 'end_no'))->condition('return_no', $return_no);

    $returns = $return_query->execute()->fetchAll();
    $or = db_or();
    foreach ($returns as $return) {
        $or->condition('billsno', $return->start_no, '>=')
                ->condition('billsno', $return->end_no, '<=');
    }

    return $or;
}