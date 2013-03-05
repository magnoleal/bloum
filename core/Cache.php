<?php
namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

/**
 * Classe Para Manipulacao de valores na sessao<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 08 de Maio de 2012
 */
class Cache {

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
  private $enable = false;

  private function __construct($exception = false){
    $this->enable = extension_loaded('apc');
    if($exception && !$this->enable)
      throw new NotFoundException('Apc Extension Not Found!!!');
  }

  public static function getInstance()
  {
    if(Cache::$instance == null)
      Cache::$instance = new Cache();
    return Cache::$instance;
  }

  /**
   * Seta um Valor (objeto ou nao) no Cache
   * @param String $key - identificação do valor
   * @param Mixed $value - valor
   */
  function setValue($key, $value, $override = true)
  {
    if($this->enable){
    
      if($override)
        apc_store(Config::KEY . '_' . $key, $value);
      else
        apc_add(Config::KEY . '_' . $key, $value);
    
    }
  }

  /**
   * Pega um Valor (objeto ou nao) do Cache
   * @param String $key - identificação do valor
   * @return Mixed Valor da Sesssao
   */
  public function getValue($key){    
    return $this->enable ? apc_fetch(Config::KEY . '_' . $key) : false;
  }

  /**
   * Pega um Valor (objeto ou nao) do Cache
   * @param String $key - identificação do valor
   * @return Mixed Valor da Sesssao
   */
  public function exist($key){
    return $this->enable ? apc_fetch(Config::KEY . '_' . $key, true) : false;
  }

  /**
   * Deletar um Valor (objeto ou nao) do Cache
   * @param String $key - identificação do valor
   * @return Mixed Valor da Sesssao
   */
  public function delete($key){
    if($this->enable)
      apc_delete(Config::KEY . '_' . $key);
  }
  /**
   * Limpar o Cache 
   */
  public function clear(){
    if($this->enable)
      apc_clear_cache();
  }

}