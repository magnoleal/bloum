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
  
}