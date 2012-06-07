<?php
/**
 * RETORNA UM ARQUIVO CSS
 */
function smarty_function_css_file($params, $smarty)
{  
  $file = isset($params['file']) ? $params['file'] : '';
  
  if(!empty ($file)){
    
    if(stripos($file, ".css") === FALSE)
      $file .= ".css";
    
    $path = \Bloum\Config::DIR_CSS.$file;
    $params_s = "";
    
    unset ($params['file']);
    
    foreach ($params as $key => $value)
      $params_s = $key.' = '.'"'.$value.'" ';   
    
    return '<link href="'.$path.'" rel="stylesheet" type="text/css" '.$params_s.' />';
  }
  
}