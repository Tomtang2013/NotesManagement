<?php

function bills_billing_check_callback() {
    $page_content = '';
    $page_content .= theme('bills_billing_check');
    return $page_content;
}

function bills_billing_check_submit() {
    $data = $_POST['data'];
    $bills_no = $data['no'];
    $org = $data['org'];
    $account = $data['account'];
    $bank = $data['bank'];
    $usefor = $data['usefor'];
    $amount = $data['amount'];
    $is_filter = $data['is_filter'];
    global $user;
    $check_fields = array('bills_no', 'account', 'org', 'bank', 'usefor', 'is_filter');
    $query_check = db_insert('bills_billing_check')->fields($check_fields);

    $query_check->values(array(
        'bills_no' => $bills_no,
        'account' => $account,
        'org' => $org,
        'bank' => $bank,
        'usefor' => $usefor,
        'is_filter' => $is_filter ? 1 : 0,
    ));

    $value[] = array(
        'bills_no' => $bills_no,
        'payment_unit' => $org,
        'payment_method' => '支票',
        'amount' => $amount,
        'update_date' => time(),
    );
    $fields = array('bills_no', 'payment_unit', 'payment_method', 'amount', 'update_date');

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

        $query_check->execute();
        $query->execute();
        $update_query->execute();
        bills_log_write_row($bills_no, $user->uid, '开具');
        drupal_set_message(t("票据开具成功"));
        
        global $user;
        $return['message'] = 'success';
        $return['name'] = $user->name;
        $return['year'] = date("Y");
        $return['month'] = date("m");
        $return['day'] = date("d");
        $return['amount'] = $amount;
        $return['pop'] = $usefor;
    } catch (Exception $e) {
        $transaction->rollback();
        watchdog_exception('billing error', $e);
        drupal_set_message('票据开具失败，请联系管理员', 'error');
    }
    drupal_json_output($return);
}

function bills_billing_check_auto() {
    $data = $_POST["data"];
    $str = $data['str'];
    $max_row = $data['max_row'];

    $matches = array();
    if ($str) {
        global $user;
        $query = db_select('bills_billing_check')->distinct()
                        ->fields('bills_billing_check', array('account', 'org', 'bank'))
                        ->condition('account', db_like($str) . '%', 'LIKE')
                        ->condition('is_filter', 1 )
                        ->range(0, $max_row);

        $result = $query->execute();

        foreach ($result as $check) {
            $matches[] = array(
                'account' => $check->account,
                'org' => $check->org,
                'bank' => $check->bank,
            );
        }
    }
    echo drupal_json_output($matches);
}