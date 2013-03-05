<?php
namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

/**
 * Classe Para Manipulacao de cache de valores<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 08 de Maio de 2012
 */
class Cookie {

  const SEP = '#.!';

  /**
   * Instancia da classe (Singleton)
   * @var $instance
   **/
  private static $instance = null;

  private function __construct(){}

  public static function getInstance()
  {
    if(Cookie::$instance == null)
      Cookie::$instance = new Cookie();
    return Cookie::$instance;
  }

  private static function encode($value){
    $value = strrev($value);    
    return Security::randomKey(4) . Cookie::SEP . base64_encode($value);
    
  }
  
  private static function decode($value){
    $encode = explode(Cookie::SEP, $value);
    return count($encode) == 2 ? strrev(base64_decode($encode[1])) : '' ;
  }
  
  /**
   * Seta um Valor (objeto ou nao) no Cookie
   * @param String $key - identificação do valor
   * @param Mixed $value - valor
   */
  public function setValue($key, $value, $expire = 0, $host = '/', $encode = true)
  {
    if($encode)
      $value = Cookie::encode($value);
    if(setcookie(Config::KEY . '_' . $key, $value, $expire, $host))   
      return $value;
    return false;
  }
  
  
  /**
   * Remove um Valor (objeto ou nao) do Cookie
   * @param String $key - identificação do valor
   * @param Mixed $value - valor
   */
  public function rmValue($key){
    setcookie(Config::KEY . '_' . $key, '', -1);
  }

  /**
   * Pega um Valor (objeto ou nao) do Cookie
   * @param String $chave - identificação do valor
   * @return Mixed Valor da Sesssao
   */
  public function getValue($key, $decode = true){
    if(isset ($_COOKIE[Config::KEY . '_' . $key])){
      if ($decode)
        return Cookie::decode($_COOKIE[Config::KEY . '_' . $key]);
      return $_COOKIE[Config::KEY . '_' . $key];
    }
    return null;
  }

  /**
   * Pega um Valor (objeto ou nao) do Cookie
   * @param String $chave - identificação do valor
   * @return Mixed Valor da Sesssao
   */
  public function exist($key){
    return isset ($_COOKIE[Config::KEY . '_' . $key]);
  }

}