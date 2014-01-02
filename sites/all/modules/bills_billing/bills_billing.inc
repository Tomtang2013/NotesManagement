<?php


function bills_type_change_callback(){
    $type =$_POST['type'];
    $ava_no = get_ava_bills_no($type);
    $ava_type_useful = get_type_useful_by_name($type);

    $rst = array(
        'ava_no' =>$ava_no,
        'ava_type_useful' =>$ava_type_useful,
    );

    echo drupal_json_output($rst);
}

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

    $value[] = array(
        'bills_no'=> $bills_no,
        'payment_unit' => $unit,
        'payment_method' => $method,
        'amount' => $amount,
        'contact' => $contact,
        'update_date' => time(),
    );
    $fields = array('bills_no','payment_unit','payment_method', 'amount','contact','update_date');

    $query = db_insert('bills_billing')->fields($fields)->values($value);
    $update_query = db_update('bills')
      ->fields(array(
        'status' => '已开具',
        'amount' => $amount,
      ))
      ->condition('billsno', $bills_no, '=');
    $transaction = db_transaction();
    try {
        $query_usf->execute();
        $query->execute();
        $update_query->execute();
        bills_log_write_row($bills_no,$user->uid,'开具');
        drupal_set_message(t("票据开具成功"));
    } catch (Exception $e) {
        $transaction->rollback();
        watchdog_exception('billing error', $e);
        drupal_set_message('票据开具失败，请联系管理员', 'error');
    }
}

