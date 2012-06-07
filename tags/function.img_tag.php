<?php
/**
 * RETORNA UMA TAG IMG
 */
function smarty_function_img_tag($params, $smarty)
{  
  $file = isset($params['file']) ? $params['file'] : '';
  
  if(!empty ($file)){
    
    $path = \Bloum\Config::DIR_IMAGES.$file;
    $params_s = "";
    
    $alt = isset ($params['alt']) ? $params['alt'] : \Bloum\Util::getFileName($file);    
    unset ($params['file']); unset ($params['alt']);
    
    foreach ($params as $key => $value)
      $params_s = $key.' = '.'"'.$value.'" ';   
    
    return '<img src="'.$path.'" alt="'.$alt.'" '.$params_s.' />';
  }
  
}