<?php
function build_table_rows(){
    $query = db_select('bills_type', 'b')
                    ->extend('PagerDefault');

    $fields = array('type_id', 'type_name');

    $query->fields('b', $fields);
    $result = $query
        ->limit(10)
        ->orderBy('b.type_id')
        ->execute();

    $rows = array();
    foreach ($result as $row) {
         $rows[$row->type_id] = array(
            'type_name' => $row->type_name,
             'opt_update'=> l('修改','billstype/'.$row->type_id.'/update'),
             'opt_delete' => l('删除','billstype/'.$row->type_id.'/delete'),
        );
    }
    return $rows;
}


function bills_type_create_form($form, &$form_state) {

    $header = array(
        'type_name' => t('票据类型名称'),
        'opt_update' => t('修改'),
        'opt_delete' => t('删除'),
    );


    $form = array();
//    $form['#tree'] = TRUE;
    $form['table'] = array(
        '#theme' => 'table',
        '#header' => $header,
        '#rows' => build_table_rows(),
    );

    $form['pager'] = array('#markup' => theme('pager'));

    
    $form['description'] = array(
        '#type' => 'item',
        '#title' => t("票据类型录入"),
    );
    // This is the first form element. It's a textfield with a label, "Name"
    $form['bills_type_name'] = array(
        '#type' => 'textfield',
        '#size' => '20',
        '#maxlength' => '10',
        '#title' => t('票据类型'),
    );

  $form['names_fieldset'] = array(
    '#type' => 'fieldset',
    '#title' => t('增加用途'),
    '#prefix' => '<div id="names-fieldset-wrapper">',
    '#suffix' => '</div>',
  );

  if (empty($form_state['num_names'])) {
    $form_state['num_names'] = 1;
  }

  for ($i = 0; $i < $form_state['num_names']; $i++) {
        $form['names_fieldset']['name'][$i] = array(
            '#type' => 'textfield',
            '#title' => t('用途'.($i+1)),
        );
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

  if($form_state['num_names']>1){
       $form['names_fieldset']['remove_useful_name'] = array(
            '#type' => 'submit',
            '#value' => t('删除用途'),
            '#submit' => array('remove_one_method'),
            '#ajax' => array(
                'callback' => 'add_more_method',
                'wrapper' => 'names-fieldset-wrapper',
            ),
        );
  }
   
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => '提交',
    );

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
  $form_state['num_names']++;
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
    $form_state['num_names'] =  array_splice($form_state['num_names'],$idx,1);

    $form_state['rebuild'] = TRUE;
}


function bills_type_create_form_validate($form, &$form_state) {
    $op = $form_state['values']['op'];
    if($op!='提交') return;

    $type_name = $form_state['values']['bills_type_name'];
    if(empty($type_name)){
         form_set_error('bills_type_name', t('请输入票据类型。'));
         return;
    }
    $type_name = type_name_is_exist($type_name);
    if(!empty($type_name)){
         form_set_error('bills_type_name', t('该票据类型已存在。'));
    }
}

function bills_type_create_form_submit($form, &$form_state) {
    $type_name = $form_state['values']['bills_type_name'];
    $usf_names = $form_state['num_names'];
    $names = array();
    for ($i = 0; $i < $usf_names; $i++) {
        $v = trim($form_state['values'][$i]);
        if(empty($v)) continue;
        $names[] = $v;
    }
    $names =  array_unique ($names);
    $transaction = db_transaction();
    /*****************/
    try {

        $value[] = array(
            'type_name' => $type_name,
        );
        $fields = array('type_name');
        $query = db_insert('bills_type')->fields($fields)->values($value);
        $type_id = $query->execute();

        $usf_values = array();
        for ($i = 0; $i < count($names); $i++) {
            $usf_values[] = array(
                'type_id' => $type_id,
                'userf_name' => $names[$i],
            );
        }

        $fields = array('type_id', 'userf_name');

        $usf_query = db_insert('bills_type_useful')->fields($fields);
        foreach ($usf_values as $record) {
            $usf_query->values($record);
        }

        $usf_query->execute();

        drupal_set_message(t("票据类型录入成功"));
    } catch (Exception $e) {
        $transaction->rollback();
        drupal_set_message('票据类型录入失败，请联系管理员', 'error');
    }
}

