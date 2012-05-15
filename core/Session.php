<?php
namespace Bloum;

class Session {

  const KEY = "bloum";
  const USER = "user";
  const SESSION_PARAMS = "session_params";
  const SESSION_CURRENT_URL = "current_url";
  const SESSION_PREVIOUS_URL = "previous_url";

  private static $instance = null;

  private function __construct(){}

  public static function getInstance()
  {
    if(Session::$instance == null)
      Session::$instance = new Session();
    return Session::$instance;
  }

  /**
   * Seta um Valor (objeto ou nao) na Sessão
   * @param String $key - identificação do valor
   * @param Mixed $value - valor
   */
  function setValue($key, $value)
  {
    $_SESSION[Session::KEY . '_' . $key] = $value;
  }

  /**
   * Pega um Valor (objeto ou nao) da Sessão
   * @param String $chave - identificação do valor
   * @return Mixed Valor da Sesssao
   */
  public function getValue($key){
    return isset ($_SESSION[Session::KEY . '_' . $key]) ? $_SESSION[Session::KEY . '_' . $key] : null;
  }

  /**
   * Pega um Valor (objeto ou nao) da Sessão
   * @param String $chave - identificação do valor
   * @return Mixed Valor da Sesssao
   */
  public function exist($key){
    return isset ($_SESSION[Session::KEY . '_' . $key]);
  }

  public function setUrls($url){
        
    if(isset ($_SESSION[Session::KEY . '_' . Session::SESSION_CURRENT_URL]) &&
        strlen($_SESSION[Session::KEY . '_' . Session::SESSION_CURRENT_URL]) > 0){

      //Evitando de perder a anterior se ocorrer um Refresh
      if(strcmp($_SESSION[Session::KEY . '_' . Session::SESSION_CURRENT_URL], $url) != 0)
          $_SESSION[Session::KEY . '_' . Session::SESSION_PREVIOUS_URL] = 
                  $_SESSION[Session::KEY . '_' . Session::SESSION_CURRENT_URL];

    }

    $_SESSION[Session::KEY . '_' . Session::SESSION_CURRENT_URL] = $url;
     
  }

  public function getCurrentUrl(){
    return $this->getValue(Session::SESSION_CURRENT_URL);
  }

  public function getPreviousUrl(){
    return $this->getValue(Session::SESSION_PREVIOUS_URL);
  }

  public function clear(){
    session_destroy();
  }

}