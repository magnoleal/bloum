<?php
/**
 * RETORNA UM ARQUIVO CSS
 */
function smarty_function_css_file($params, $smarty)
{  
  $file = isset($params['file']) ? $params['file'] : '';
  $out = isset($params['out']) ? $params['out'] : false;
  
  if(!empty ($file)){
    
    if(stripos($file, ".css") === FALSE)
      $file .= ".css";
    
    $path = $out ? 'app/assets/'.$file : \Bloum\Config::DIR_CSS.$file;
    $params_s = "";
    
    unset ($params['file']);
    
    foreach ($params as $key => $value)
      $params_s = $key.' = '.'"'.$value.'" ';   
    
    return '<link href="'.$path.'" rel="stylesheet" type="text/css" '.$params_s.' />';
  }
  
}