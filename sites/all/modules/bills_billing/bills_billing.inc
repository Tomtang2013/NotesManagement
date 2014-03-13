<?php

function bills_billing_callback(){
    $page_content = '';
    $page_content .= theme('bills_billing_main');
    return $page_content;
}

function bills_billing_submit(){
    $data = $_POST['data'];
    $bills_no = $data['no'];
    $unit = $data['unit'];
    $method = $data['method'];
    $contact = $data['contact'];
    $usfs = $data['usf'];

    global $user;
    $amount = 0;
    $usf_fields = array('bills_no','payment_content','number','unit_price');
    $query_usf = db_insert('bills_billing_detail')->fields($usf_fields);

    foreach ($usfs as $usf) {
        $query_usf->values(array(
            'bills_no' => $bills_no,
            'payment_content' => $usf['content'],
            'number' => $usf['number'],
            'unit_price' => $usf['unit_price'],
        ));
        $amount+=$usf['number'] * $usf['unit_price'];
    }

    $return_status = '';
    if($method == '暂借'){
        $return_status = '未归还';
    }
    $value[] = array(
        'bills_no'=> $bills_no,
        'payment_unit' => $unit,
        'payment_method' => $method,
        'return_status' => $return_status,
        'amount' => $amount,
        'contact' => $contact,
        'update_date' => time(),
    );
    $fields = array('bills_no','payment_unit','payment_method','return_status', 'amount','contact','update_date');

    $query = db_insert('bills_billing')->fields($fields)->values($value);
    $update_query = db_update('bills')
      ->fields(array(
        'status' => '已开具',
        'amount' => $amount,
      ))
      ->condition('billsno', $bills_no, '=');

    $return = array();
    $return['message'] = 'error';
    $transaction = db_transaction();
    try {
        $query_usf->execute();
        $query->execute();
        $update_query->execute();
        bills_log_write_row($bills_no,$user->uid,'开具',$unit);
        drupal_set_message(t("票据开具成功"));

        global $user;
        $return['message'] = 'success';
        $return['today'] = date('Y/m/d', time());
        $return['name'] = $user->name;
    } catch (Exception $e) {
        $transaction->rollback();
        watchdog_exception('billing error', $e);
        drupal_set_message('票据开具失败，请联系管理员', 'error');
    }
    
    drupal_json_output($return);
}

