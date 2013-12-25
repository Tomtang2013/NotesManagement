<?php


function bills_distribution_form($form, &$form_state) {
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
                    ->condition('b.status', '可用', '=')
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
        '#title' => t("票据分配"),
    );

    $form['table'] = array(
        '#type' => 'tableselect',
        '#header' => $header,
        '#options' => $rows,
        '#multiple' => TRUE,
    );

    $form['pager'] = array('#markup' => theme('pager'));

    $form['distribution_uid'] = array(
        '#type' => 'select',
        '#required' => TRUE,
        '#options' => get_billing_users(),
        '#title' => t('分发给开票员：'),
    );

     $form['submit'] = array(
        '#type' => 'submit',
        '#value' => '提交',
    );


    return $form;
}

function get_billing_users(){
    $result = db_query('select u.uid,u.name from users u
    join users_roles ur on u.uid = ur.uid
    join role r on ur.rid = r.rid and r.name = :rolename',
    array(':rolename' => '开票员'))->fetchAll();

    $users = array();

    foreach($result as $user){
        $users[$user->uid] = $user->name;
    }
    return $users;
}


function bills_distribution_form_validate($form, &$form_state) {
   $table = $form_state['values']['table'] ;
   $rst = 0;
   foreach($table as $row){
       if($row !=0){
           $rst = $row;
            break;
       }
   }
   if($rst == 0)
       form_set_error('', t('请选择需要分配的票据编号.'));
}

function bills_distribution_form_submit($form, &$form_state) {
    $uid = $form_state['values']['distribution_uid'];
    $table = $form_state['values']['table'] ;

   $bill_nos = array();
   foreach($table as $row){
       if($row > 0){
           $bill_nos[] =$row;
       }
   }

    $query = db_update('bills')
      ->fields(array(
        'distribution_uid' => $uid,
        'status' => '已分配',
      ))
      ->condition('billsno', $bill_nos, 'in');
    try {
        $result = $query->execute();
        drupal_set_message(t("票据分配成功"));
    } catch (Exception $e) {
        drupal_set_message('票据分配失败，请联系管理员', 'error');
    }
}