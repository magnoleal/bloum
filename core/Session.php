<?php
namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

/**
 * Classe Para Manipulacao de valores na sessao<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 08 de Maio de 2012
 */
class Session {

  /**
   * Constante para identificar um prefixo para os valores <br/>
   * (evitar conflito com outros sites/sistemas no msm host)
   * @var KEY
   **/ 
  const KEY = "bloum";

  /**
   * Constante para identificar usuario na sessao
   * @var USER
   **/ 
  const USER = "user";

  /**
   * Constante para identificar parametros na sessao
   * @var SESSION_PARAMS
   **/ 
  const SESSION_PARAMS = "session_params";

  /**
   * Constante para identificar a url atual
   * @var CURRENT_URL
   **/ 
  const CURRENT_URL = "current_url";

  /**
   * Constante para identificar a url anterior
   * @var PREVIOUS_URL
   **/ 
  const PREVIOUS_URL = "previous_url";

  /**
   * Instancia da classe (Singleton)
   * @var $instance
   **/
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

  /**
   * Seta as urls atual e anterior
   * @param String $url - url anterior
   */
  public function setUrls($url){
        
    if(isset ($_SESSION[Session::KEY . '_' . Session::CURRENT_URL]) &&
        strlen($_SESSION[Session::KEY . '_' . Session::CURRENT_URL]) > 0){

      //Evitando de perder a anterior se ocorrer um Refresh
      if(strcmp($_SESSION[Session::KEY . '_' . Session::CURRENT_URL], $url) != 0)
          $_SESSION[Session::KEY . '_' . Session::PREVIOUS_URL] = 
                  $_SESSION[Session::KEY . '_' . Session::CURRENT_URL];

    }

    $_SESSION[Session::KEY . '_' . Session::CURRENT_URL] = $url;
     
  }

  public function getCurrentUrl(){
    return $this->getValue(Session::CURRENT_URL);
  }

  public function getPreviousUrl(){
    return $this->getValue(Session::PREVIOUS_URL);
  }

  public function clear(){
    session_destroy();
  }

}