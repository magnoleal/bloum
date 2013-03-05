<?php

namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

require_once DIR_BLOUM.'core/Db.php';    

/**
 * Classe Para Manipulação de URL<br />
 * 
 * <b>Padrao: controller.action[?param1=value1&param2=value2]</b>
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 14 de Maio de 2012
 */
class Model extends \ActiveRecord\Model {
  
  public function populate($attributes){
    
    if( !isset($attributes) || count($attributes) <= 0 )
      return;
    
    foreach ($attributes as $key => $value) {
      
      if(isset($this->$key))
        $this->$key = $value;
      
    }
    
    if(isset($this->id) && intval($this->id) > 0)
      $this->setNewRecord(false);
    else
      $this->setNewRecord(true);
    
  }
  
  static function paginate($pg = 0, $limit = 0, $conditions = array(), $order = ''){
    
    $_limit = array('limit' => $limit, 'offset' => Util::numOffset($pg, $limit));
    
    if(strlen($order) > 0)    
      $_limit = array_merge($_limit, array('order' => $order));
    
    $output = Output::getInstance();
    
    $output->addValue('total', Util::numPages(static::count($conditions), $limit));
    $output->addValue('pg', $pg);
    
    return static::all( array_merge($conditions, $_limit) );
    
  }
  
  static function simplePaginate($pg = 0, $limit = 0, $conditions = array(), $order = '', $joins = array()){
    
    $pg = intval($pg);
    if($pg < 0)
      $pg = $pg * -1;
    
    $offset = Util::numOffset($pg, $limit);
    
    $_limit = array('limit' => $limit, 'offset' => $offset);
    
    if(strlen($order) > 0)    
      $_limit = array_merge($_limit, array('order' => $order));
    
    if(count($joins))
      $_limit = array_merge($_limit, array('joins' => $joins, 'select' => 'distinct '.static::$table.'.*'));
    
    $output = Output::getInstance();
    
    $row_fim = $limit * $pg;
    
    $row_count = static::count(array_merge($conditions, array('joins' => $joins)));
    $row_fim = $row_count >= $row_fim ? $row_fim : $row_count;
    $total = Util::numPages($row_count, $limit);
    
    if($offset == 0)
      $offset = 1;
    else if($offset > $row_fim)
      $offset = $row_fim = 0;
    
    $output->addValue('row_ini', $offset);
    $output->addValue('row_fim', $row_fim);
    $output->addValue('row_count', $row_count);
    $output->addValue('total', $total);
    $output->addValue('pg', $pg);
    $output->addValue('order', $order);

    return static::all( array_merge($conditions, $_limit) );
    
  }

  public static function fullPaginate($pg = 0, $params = array()){
    $pg = intval($pg);
    if($pg < 0)
      $pg = $pg * -1;
    
    $limit = $params['limit'];
    $offset = Util::numOffset($pg, $limit);
    $params['offset'] = $offset;
    
    $output = Output::getInstance();
    
    $row_fim = $limit * $pg;

    $count_params = $params;
    unset($count_params['limit']);
    unset($count_params['offset']);
    unset($count_params['order']);
    
    $row_count = static::count($count_params);
    $row_fim = $row_count >= $row_fim ? $row_fim : $row_count;
    $total = Util::numPages($row_count, $limit);
    
    if($offset == 0)
      $offset = 1;
    else if($offset > $row_fim)
      $offset = $row_fim = 0;
    
    $output->addValue('row_ini', $offset);
    $output->addValue('row_fim', $row_fim);
    $output->addValue('row_count', $row_count);
    $output->addValue('total', $total);
    $output->addValue('pg', $pg);
    $output->addValue('order', $params['order']);

    return static::all( $params );
  }
  
  public function copy(){
    $attrs = $this->attributes();
    $attrs[$this->get_primary_key(true)] = null;
    return $this->create($attrs);
  }
  
}

?>
