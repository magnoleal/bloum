<?php
namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

/**
 * Classe Com Metodos Uteis e Genericos<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 08 de Maio de 2012
 */
class Util {

  /**
   * Verifica se a requisicao eh ajax
   * @return true|false
   **/
  public static function isRequestAjax() {
    return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strcmp(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']), 'xmlhttprequest') == 0);
  }

  /**
   * Verifica se a requisicao eh flash
   * @return true|false
   **/
  public static function isRequestFlash() {
    return (!empty($_SERVER['HTTP_USER_AGENT']) &&
      (stripos($_SERVER['HTTP_USER_AGENT'], 'shockwave') > 0 || stripos($_SERVER['HTTP_USER_AGENT'], 'flash') > 0));
  }
  
  public static function getFileName($file, $withExtension = false){
    $ext = explode("/", $file);

    if(count($ext) > 1)
      $name = $ext[count($ext)-1];
    else
      $name = $file;
    
    if(!$withExtension){
      $name = explode (".", $name);
      unset ($name[count($name) - 1]);
      $name = implode(".", $name);
    }

    return $name;
  }
  
  public static function numPages($total, $size) {
    return ceil($total / $size);
  }

  public static function numOffset($page, $size) {

    if (isset($page) && $page > 0)
      return ($page * $size) - $size;

    return 0;
  }
  
  public static function camelize($name){
    $name = str_replace(array('-', '_'), ' ', $name);
    $name = ucwords($name);
    $name = str_replace(' ', '', $name); 
    return lcfirst($name); 
  }
  
  public static function underscore($name) {
    $name[0] = strtolower($name[0]);
    $func = create_function('$c', 'return "_" . strtolower($c[1]);');
    return preg_replace_callback('/([A-Z])/', $func, $name);
  }
  
}