<?php
/**
 * RETORNA UMA TAG IMG
 */
function smarty_function_img_tag($params, $smarty)
{  
  $file = isset($params['file']) ? $params['file'] : '';
  $assets = isset($params['assets']) ? $params['assets'] : true;  
  $returnUrl = isset($params['returnUrl']) ? $params['returnUrl'] : false;  
  
  if(!empty ($file)){
    
    $as = '';
    if($assets)
      $as = \Bloum\Config::DIR_IMAGES;
        
    $path = HOST_PATH.$as.$file;
    if($returnUrl)
      return $path;
    $params_s = "";
    
    $alt = isset ($params['alt']) ? $params['alt'] : \Bloum\Util::getFileName($file);    
    unset ($params['file']); unset ($params['alt']); unset ($params['assets']);
    
    foreach ($params as $key => $value)
      $params_s .= $key.' = '.'"'.$value.'" ';   
    
    return '<img src="'.$path.'" alt="'.$alt.'" '.$params_s.' />';
  }
  
}