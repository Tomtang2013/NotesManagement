<?php

function bills_type_update_form($form, &$form_state, $type_id) {
    $type_name = get_type_name_by_id($type_id);

    $form['description'] = array(
        '#type' => 'item',
        '#title' => t("票据类型修改"),
    );

    $form['bills_type_name'] = array(
        '#type' => 'textfield',
        '#size' => '20',
        '#required' => TRUE,
        '#maxlength' => '10',
        '#default_value'=> $type_name,
        '#title' => t('票据类型'),
    );


  $form['names_fieldset'] = array(
    '#type' => 'fieldset',
    '#title' => t('增加用途'),
    '#prefix' => '<div id="names-fieldset-wrapper">',
    '#suffix' => '</div>',
  );

  

  if (empty($form_state['num_names'])) {
    $form_state['num_names'] = get_type_useful($type_id);
  }

  $length = count($form_state['num_names']);
  
  foreach ($form_state['num_names'] as $key => $usf) {
        $form['names_fieldset']['name'][$key] = array(
            '#type' => 'textfield',
            '#title' => t('用途' . $key),
            '#default_value' => $usf
        );
        if ($length > 1) {
            $form['names_fieldset']['remove_useful_name'][$key] = array(
                '#type' => 'submit',
                '#value' => t('删除用途' . $key),
                '#submit' => array('remove_one_method'),
                '#ajax' => array(
                    'callback' => 'add_more_method',
                    'wrapper' => 'names-fieldset-wrapper',
                ),
            );
        }
    }
  
  $form['names_fieldset']['add_name'] = array(
    '#type' => 'submit',
    '#value' => t('增加用途'),
    '#submit' => array('add_one_method'),
    '#ajax' => array(
      'callback' => 'add_more_method',
      'wrapper' => 'names-fieldset-wrapper',
    ),
  );

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => '提交',
    );

     $form_state['type_id'] = $type_id;

    return $form;

}

/**
 * Callback for both ajax-enabled buttons.
 *
 * Selects and returns the fieldset with the names in it.
 */
function add_more_method($form, $form_state) {
  return $form['names_fieldset'];
}

/**
 * Submit handler for the "add-one-more" button.
 *
 * Increments the max counter and causes a rebuild.
 */
function add_one_method($form, &$form_state) {
   $usf_names = $form_state['num_names'];
   $key = key( array_slice( $usf_names, -1, 1, TRUE ) );
   $key = $key + 1;
   $form_state['num_names'][$key] = '';

   $form_state['rebuild'] = TRUE;
}

/**
 * Submit handler for the "remove one" button.
 *
 * Decrements the max counter and causes a form rebuild.
 */
function remove_one_method($form, &$form_state) {
    $op = $form_state['values']['op'];
    $idx = str_replace ('删除用途','',$op);
    $usf_names = $form_state['num_names'];
    unset($usf_names[$idx]);
    $form_state['num_names'] = $usf_names;

    $form_state['rebuild'] = TRUE;
}

function bills_type_update_form_submit($form, &$form_state) {
    $type_name = $form_state['values']['bills_type_name'];
    $type_id = $form_state['type_id'];
    $names = array();
 
    foreach ($form_state['num_names'] as $key=>$usf_name) {
        $v = trim($form_state['values'][$key]);
        if(empty($v)) continue;
        $names[] = $v;
    }
    $names =  array_unique ($names);

    $usf_values = array();
    foreach ($names as $name) {
        $usf_values[] = array(
            'type_id' => $type_id,
            'userf_name' => $name,
        );
    }
    $transaction = db_transaction();
    /*****************/
    try {

        $query = db_update('bills_type')->fields(array(
            'type_name' => $type_name,
          ))
          ->condition('type_id', $type_id);

        $query->execute();

        db_delete('bills_type_useful')->condition('type_id', $type_id)->execute();

        $fields = array('type_id', 'userf_name');

        $usf_query = db_insert('bills_type_useful')->fields($fields);
        foreach ($usf_values as $record) {
            $usf_query->values($record);
        }

        $usf_query->execute();

        drupal_set_message(t("票据类型修改成功"));
    } catch (Exception $e) {
        $transaction->rollback();
        drupal_set_message('票据类型修改失败，请联系管理员', 'error');
    }
}

function bills_type_update_form_validate($form, &$form_state) {
    $op = $form_state['values']['op'];
    if($op!='提交') return;
    $type_id = $form_state['type_id'];
    if(empty($type_id)){
         drupal_set_message('请重新选择票需要被修改的据类型。', 'error');
         drupal_goto('billstype');
    }

    $type_name = get_type_name_by_id($type_id);
    if(empty($type_name)){
         drupal_set_message('该票据类型已不存在。', 'error');
         drupal_goto('billstype');
    }


    $type_name = type_name_is_exist($type_name,$type_id);
    if(!empty($type_name)){
         form_set_error('bills_type_name', t('该票据类型已存在。'));
    }

    $type = is_have_bills($type_name);
    if(!empty($type)){
         drupal_set_message("票据类型 $type_name 已有票据存在，无法被修改。", 'error');
         drupal_goto('billstype');
    }

}
