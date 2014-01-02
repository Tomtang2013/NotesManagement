<?php

function bills_distribution_form($form, &$form_state) {
    $query = db_query("select count(billsno) billscount,type from bills
                    where status = '可用'
                    group by type ");

    $result = $query->fetchAll();

    $rows = array();
    foreach ($result as $row) {
        $rows[] = array(
            'type' => $row->type,
            'billscount' => $row->billscount,
        );
    }

    $header = array(
        'type' => t('票据类型'),
        'bills_number' => t('可用数量(张)'),
    );

    $form = array();
//    $form['#tree'] = TRUE;
    $form['table'] = array(
        '#theme' => 'table',
        '#header' => $header,
        '#rows' => $rows,
    );
    $form['pager'] = array('#markup' => theme('pager'));

    $form_state['rows'] = $rows;

    $form['description'] = array(
        '#type' => 'item',
        '#title' => t("票据分配"),
    );

    $form['bills_type'] = array(
        '#type' => 'select',
        '#required' => TRUE,
        '#options' => get_bills_type(),
        '#title' => t('请选择票据类型'),
    );

    $form['distribution_uid'] = array(
        '#type' => 'select',
        '#required' => TRUE,
        '#options' => get_billing_users(),
        '#title' => t('请选择开票员'),
    );

    $form['bills_number'] = array(
        '#type' => 'textfield',
        '#required' => TRUE,
        '#title' => t('请输入需分配的张数'),
    );

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => '提交',
    );


    return $form;
}

function get_billing_users() {
    $result = db_query('select u.uid,u.name from users u
    join users_roles ur on u.uid = ur.uid
    join role r on ur.rid = r.rid and r.name = :rolename',
                    array(':rolename' => '开票员'))->fetchAll();

    $users = array();

    foreach ($result as $user) {
        $users[$user->uid] = $user->name;
    }
    return $users;
}

function bills_distribution_form_validate($form, &$form_state) {
    $bills_number = $form_state['values']['bills_number'] + 0;
    $type = $form_state['values']['bills_type'];
    if (!is_int($bills_number) || $bills_number <= 0)
        form_set_error('bills_number', t('请输入正确的票据张数。'));
    if (!empty($type)) {
        $rows = $form_state['rows'];
        $type_num = 0;
        foreach ($rows as $row) {
            if ($type == $row['type']) {
                $type_num = $row['billscount'];
            }
        }
        if ($bills_number > $type_num) {
            form_set_error('bills_number', t("$type 不足,请修改分配票据张数。"));
        }
    }
}

function bills_distribution_form_submit($form, &$form_state) {
    $uid = $form_state['values']['distribution_uid'];
    $type = $form_state['values']['bills_type'];
    $bills_number = $form_state['values']['bills_number'] + 0;
    $transaction = db_transaction();
  
    global $user;
    try {

        $bill_nos = array();

        $query = db_select('bills', 'b')
                        ->fields('b', array('billsno'))
                        ->condition('b.status', '可用')
                        ->condition('b.type', $type)
                        ->orderBy('b.billsno')
                        ->range(0, $bills_number);

        $nos = $query->execute()->fetchAll();
        foreach ($nos as $no) {
            if ($no->billsno > 0) {
                $bill_nos[] = $no->billsno;
            }
        }

        $query = db_update('bills')
                        ->fields(array(
                            'distribution_uid' => $uid,
                            'status' => '已分配',
                        ))
                        ->condition('billsno', $bill_nos, 'in');
        $result = $query->execute();
        bills_log_write_array($bill_nos, $user->uid, '分配', $uid);
        drupal_set_message(t("票据分配成功"));
    } catch (Exception $e) {
        $transaction->rollback();
        watchdog_exception('bills distribution error', $e);
        drupal_set_message('票据分配失败，请联系管理员', 'error');
    }
}