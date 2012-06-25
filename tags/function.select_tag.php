<?php

require DIR_BLOUM . 'lib/smarty/plugins/function.html_options.php';

/**
 * RETORNA UM COMBO BOX
 */
function smarty_function_select_tag($params, $smarty) {

  $model = $params['model'];

  if (!isset($params['blank_option']))
    $params['blank_option'] = true;

  $name_object = ucfirst($model);

  $key = $params['key'];
  $value = $params['value'];

  unset($params['model']);
  unset($params['key']);
  unset($params['value']);
  
  \Bloum\Util::underscore($model);
  
  $under_name = \Bloum\Util::underscore($model);

  $params['name'] = isset($params['name']) ? $params['name'] : $under_name. '_id';
  $params['id'] = isset($params['id']) ? $params['id'] : $under_name . '_id';
  $options = isset($params['options']) ? $params['options'] : $name_object::all();

  $array_options = array();

  if ($params['blank_option'])
    $array_options[''] = 'Selecione um(a) ' . $name_object;

  unset($params['blank_option']);

  foreach ($options as $op)
    $array_options[$op->$key] = $op->$value;

  $params['options'] = $array_options;
  $params['class'] = @$params['class'] . ' chzn-select';
  $params['class'] .= isset($params['required']) && $params['required'] ? ' required' : '';

  $vars = $smarty->getTemplateVars();

  $name = $params['name'];
    
  if (isset($vars[$name]))
    $params['selected'] = $vars[$name];
  else if(isset($vars['model']) && isset($vars['model']->$name))
    $params['selected'] = $vars['model']->$name;

  unset($params['required']);

  return smarty_function_html_options($params, $smarty);
}