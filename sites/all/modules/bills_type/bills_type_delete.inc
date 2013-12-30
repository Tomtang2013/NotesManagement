<?php

function bills_type_delete_form($form, &$form_state, $type_id) {
    
    $form['description'] = array(
        '#type' => 'item',
        '#title' => t("票据类型删除确认"),
        '#markup' => '<div style="padding:20px 20px 20px 0px;">' . t('删除票据类型后，从属该票据的用途也会被一并删除。')
        . '</div>',
    );
    $form['delete'] = array(
        '#type' => 'submit',
        '#value' => t('确认删除'),
        '#submit' => array('bills_type_delete_form_delete'),
        '#weight' => 100,
    );
    
    $form_state['type_id'] = $type_id;

    $form['cancel'] = array(
        '#type' => 'submit',
        '#value' => t('取消删除'),
        '#submit' => array('bills_type_delete_form_cancel'),
         '#weight' => 200,
    );
    return $form;
}


function bills_type_delete_form_validate($form, &$form_state) {
    $type_id = $form_state['type_id'];
    if(empty($type_id)){
         drupal_set_message('请重新选择票需要被删除的据类型。', 'error');
         drupal_goto('billstype');
    }
    
    $type_name = get_type_name_by_id($type_id);
    if(empty($type_name)){
         drupal_set_message('该票据类型已不存在。', 'error');
         drupal_goto('billstype');
    }

    $type = is_have_bills($type_name);
    if(!empty($type)){
         drupal_set_message("票据类型 $type_name 已有票据存在，无法被删除。", 'error');
         drupal_goto('billstype');
    }
  
}


function bills_type_delete_form_delete($form, &$form_state) {
    $type_id = $form_state['type_id'];
    $transaction = db_transaction();
    try {
        db_delete('bills_type_useful')
        ->condition('type_id', $type_id)
        ->execute();
        db_delete('bills_type')
        ->condition('type_id', $type_id)
        ->execute();
        drupal_set_message(t("票据类型删除成功"));
    } catch (Exception $e) {
        $transaction->rollback();
        watchdog_exception('bills type delete error', $e);
        drupal_set_message('票据类型删除失败，请联系管理员', 'error');
    }
    drupal_goto('billstype');
}

function bills_type_delete_form_cancel($form, &$form_state) {
    drupal_goto('billstype');
}