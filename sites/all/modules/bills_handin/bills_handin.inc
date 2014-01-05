<?php
function bills_handin_form($form, &$form_state) {
     $form['description'] = array(
        '#type' => 'item',
        '#title' => t("票据缴销"),
    );
    $form['billsno_start'] = array(
        '#type' => 'textfield',
        '#size' => '20',
        '#required' => TRUE,
        '#maxlength' => '10',
        '#title' => t('起始票据编号'),
    );

    $form['billsno_end'] = array(
        '#type' => 'textfield',
        '#size' => '20',
        '#required' => TRUE,
        '#maxlength' => '10',
        '#title' => t('结束票据编号'),
    );

     $form['submit'] = array(
        '#type' => 'submit',
        '#value' => '提交',
    );


    return $form;
}


function bills_handin_form_validate($form, &$form_state) {
    $start = $form_state['values']['billsno_start'] + 0;
    $end = $form_state['values']['billsno_end'] + 0;
    if (!is_int($start) || $start <= 0)
        form_set_error('billsno_start', t('起始票据编号必须是数字.'));
    if (!is_int($end) || $end <= 0)
        form_set_error('billsno_end', t('结束票据编号必须是数字.'));
    if ($start > $end) {
        form_set_error('billsno_end', t('结束票据编号不能小于起始票据编号。'));
    }

    $count = bills_have_no($start,$end);

    if($count==0){
         form_set_error('billsno_start', t('该票据段未含有可缴销票据。'));
    } else if($count !=$end - $start+1){
         form_set_error('billsno_start', t('该票据段含有他人票据。'));
    }
    
}

function bills_have_no($start,$end){
     global $user;
      $query = db_select('bills', 'b')
      ->fields( 'b',array('billsno'))
      ->condition('billsno', $start, '>=')
      ->condition('billsno', $end, '<=')
      ->condition(db_or()->condition('status', '已开具')
                       ->condition('status', '已作废')
                       ->condition('status', '已报错')
                        ->condition('status', '已分配'))
      ->condition('distribution_uid', $user->uid);
      $no = $query->execute()->fetchAll();
      return count($no);
}


function bills_handin_form_submit($form, &$form_state) {
    $start = $form_state['values']['billsno_start'] + 0;
    $end = $form_state['values']['billsno_end'] + 0;

    global $user;
    $query = db_update('bills')
                    ->fields(array(
                        'status' => '已缴销',
                    ))
                    ->condition('billsno', $start, '>=')
                    ->condition('billsno', $end, '<=')
                    ->condition(db_or()->condition('status', '已开具')
                                       ->condition('status', '已作废')
                                       ->condition('status', '已报错'))
                    ->condition('distribution_uid', $user->uid);

    $black_nos = db_select('bills','b')
                     ->fields( 'b',array('billsno'))
                    ->condition('billsno', $start, '>=')
                    ->condition('billsno', $end, '<=')
                    ->condition('status', '已分配')
                    ->condition('distribution_uid', $user->uid)
                    ->execute()->fetchAll();
    
    $values = array();
    $time = time();
    $no = $user->uid .''. $time;
    
    $values = get_handin_start_end($no,$time,$start,$end,$user->uid,$black_nos);

    $insert_query = db_insert('bills_handin')->fields(
                array('handin_no','handin_uid','start_no', 'end_no', 'createdate',
                      'wrong', 'disable', 'distribution', 'amount'));
    foreach ($values as $record) {
        $insert_query->values($record);
    }
    
    $transaction = db_transaction();
    try {
        $result = $query->execute();
        $insert_query->execute();
        bills_log_start_end($start,$end, $user->uid, '缴销');
        drupal_set_message(t("票据缴销成功,缴销编号为:".$no));
    } catch (Exception $e) {
        $transaction->rollback();
        watchdog_exception('bills handin error', $e);
        drupal_set_message('票据缴销失败，请联系管理员', 'error');
    }
}



function get_type_number($start,$end,$type){
    global $user;
      $query = db_select('bills', 'b')
      ->fields( 'b',array('billsno'))
      ->condition('billsno', $start, '>=')
      ->condition('billsno', $end, '<=')
      ->condition('status', $type)
      ->condition('distribution_uid', $user->uid);
      $no = $query->execute()->fetchAll();
      return count($no);
}

function get_sum_amount($start,$end){
    global $user;
      $query = db_select('bills', 'b')
      ->fields( 'b',array('amount'))
      ->condition('billsno', $start, '>=')
      ->condition('billsno', $end, '<=')
      ->condition('status', '已开具')
      ->condition('distribution_uid', $user->uid);
      $amounts = $query->execute()->fetchAll();
      $sum  = 0;
      foreach($amounts as $amount){
          $sum+=$amount->amount;
      }
      return $sum;
}



function get_handin_start_end($no,$time,$start,$end,$uid,$blocks){
    $values = array();
    $start_no = $start;
    $end_no = $end;

    for ($j = 0;$j<count($blocks);$j++ ) {
        $blocknoClass = $blocks[$j];
        $blockno = $blocknoClass->billsno + 0;
        if($start_no == $blockno){
            $start_no++;
        } else if($start_no < $blockno){
            $values[] = array(
                'handin_no' => $no,
                'handin_uid' => $uid,
                'start_no' => $start_no,
                'end_no' => $blockno - 1,
                'createdate' => $time,
                'wrong' => get_type_number($start_no,$blockno - 1,'已报错'),
                'disable' => get_type_number($start_no,$blockno - 1,'已作废'),
                'distribution' => get_type_number($start_no,$blockno - 1,'已开具'),
                'amount' => get_sum_amount($start_no,$blockno - 1),
            );

            $start_no = $blockno + 1;
        }
    }
    
    if($start_no<=$end_no){
        $values[] = array(
            'handin_no' => $no,
            'handin_uid' => $uid,
            'start_no' => $start_no,
            'end_no' => $end_no,
            'createdate' => $time,
            'wrong' => get_type_number($start_no, $end_no, '已报错'),
            'disable' => get_type_number($start_no, $end_no, '已作废'),
            'distribution' => get_type_number($start_no, $end_no, '已开具'),
            'amount' => get_sum_amount($start_no, $end_no),
        );
    }
    

    return $values;
}
