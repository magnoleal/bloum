<?php
/**
 * RETORNA UMA URL COMPLETA
 */
function smarty_function_url_to($params, $smarty)
{  
  $link = isset($params['link']) ? $params['link'] : '';
  $namespace = isset($params['n']) ? $params['n'] : '';
  $controller = isset($params['c']) ? $params['c'] : '';
  $action = isset($params['a']) ? $params['a'] : '';
  
  if(!empty ($link)){    
    return HOST_PATH.$link;    
  }
  
  if(!empty ($controller) && !empty ($action)){    
    
    $link = '';
    if(!empty($namespace))
      $link .= $namespace.Bloum\Config::SEP_URL;
    
    return HOST_PATH.$link.$controller.Bloum\Config::SEP_URL.$action;    
  }
  
}