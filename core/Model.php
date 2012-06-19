<?php

namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

/**
 * Classe Para Manipulação de URL<br />
 * 
 * <b>Padrao: controller.action[?param1=value1&param2=value2]</b>
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 14 de Maio de 2012
 */
class Model extends \ActiveRecord\Model {
  
  static function paginate($pg = 0, $limit = 0, $conditions = array()){
    
    $_limit = array('limit' => $limit, 'offset' => Util::numOffset($pg, $limit));
    
    $output = Output::getInstance();
    
    $output->addValue('total', Util::numPages(static::count($conditions), $limit));
    $output->addValue('pg', $pg);
    
    return static::all( array_merge($conditions, $_limit) );
    
  }
  
}

?>
