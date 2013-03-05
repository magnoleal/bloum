<?php
/**
 * RETORNA UM ARQUIVO JS
 */
function smarty_function_js_file($params, $smarty)
{  
  $file = isset($params['file']) ? $params['file'] : '';
  
  if(!empty ($file)){
    
    if(stripos($file, ".js") === FALSE)
      $file .= ".js";
    
    $path = HOST_PATH.\Bloum\Config::DIR_JS.$file;
    $params_s = "";
    
    unset ($params['file']);
    
    foreach ($params as $key => $value)
      $params_s = $key.' = '.'"'.$value.'" ';   
    
    return '<script src="'.$path.'" type="text/javascript" '.$params_s.' ></script>';
  }
  
}