<?php
function bills_handin_check_form($form, &$form_state) {
    
     $form = array();

     $form['description'] = array(
        '#type' => 'item',
        '#title' => t("确认缴销票据"),
     );

     $form['handin_no'] = array(
        '#type' => 'textfield',
        '#size' => '20',
        '#required' => TRUE,
        '#maxlength' => '20',
        '#title' => t('缴销编号'),
    );


     $form['submit'] = array(
        '#type' => 'submit',
        '#value' => '检索',
    );

     $form['handin_confirm'] = array(
        '#type' => 'submit',
        '#value' => '确认缴销',
    );
      $form['handin_cancel'] = array(
        '#type' => 'submit',
        '#value' => '拒绝缴销',
    );

     if (!empty($form_state['handin_no'])) {
        $header = array(
            'nos' => t('票据编号区间'),
            'distribution' => t('已开具'),
            'disable' => t('已作废'),
            'wrong' => t('已报错'),
            'number' => t('总张数'),
            'amount' => t('金额'),
        );
        $query = db_select('bills_handin', 'bh')
                        ->extend('PagerDefault');

        $fields = array('start_no', 'end_no', 'distribution', 'disable', 'wrong', 'amount');

        $query->fields('bh', $fields);
        $str_query = $query
                        ->condition('bh.handin_no', $form_state['handin_no'])
                        ->limit(10)
                        ->orderBy('bh.handin_id');
        $result = $str_query->execute()->fetchAll();

        $rows = array();
        foreach ($result as $row) {
            $rows[] = array(
                'nos' => $row->start_no.'～'.$row->end_no,
                'distribution' => $row->distribution,
                'disable' => $row->disable,
                'wrong' => $row->wrong,
                'number' =>$row->end_no - $row->start_no + 1,
                'amount' => $row->amount,
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


function bills_handin_check_form_validate($form, &$form_state) {
//   $table = $form_state['values']['table'] ;
//   $rst = 0;
//   foreach($table as $row){
//       if($row !=0){
//           $rst = $row;
//            break;
//       }
//   }
//   if($rst == 0)
//       form_set_error('', t('请选择已经收到的缴销票据编号.'));
}

function bills_handin_check_form_submit($form, &$form_state) {
    global $user;
    if ($form_state['values']['op'] == '检索') {
        $form_state['handin_no'] = $form_state['values']['handin_no'];
        $form_state['rebuild'] = TRUE;
    } else {
        $status = '已确认';
        $opt = '缴销确认';
        if ($form_state['values']['op'] == '拒绝缴销') {
            $status = '已开具';
            $opt = '缴销拒绝';
        }
        $or = get_start_end_or($form_state['values']['handin_no']);
       
        $query = db_update('bills')
                        ->fields(array(
                            'status' => $status,
                        ))
                        ->condition($or)
                        ->condition('status', '已缴销')
                        ->condition('distribution_uid', $user->uid);
        try {
            $result = $query->execute();
//            bills_log_start_end($start,$end, $user->uid, $opt);
            bills_log_write_row($form_state['values']['handin_no'], $user->uid, $opt);
            drupal_set_message(t("票据 $opt 成功" ));
        } catch (Exception $e) {
            watchdog_exception('bills handin error', $e);
            drupal_set_message("票据 $opt 失败，请联系管理员", 'error');
        }
    }
    
}

function get_start_end_or($handin_no) {
    $handin_query = db_select('bills_handin')->fields(
                            'bills_handin',array('start_no', 'end_no'))->condition('handin_no', $handin_no);

    $handins = $handin_query->execute()->fetchAll();
    $or = db_or();
    foreach ($handins as $handin) {
        $or->condition('billsno', $handin->start_no, '>=')
                ->condition('billsno', $handin->end_no, '<=');
    }
    
    return $or;
}