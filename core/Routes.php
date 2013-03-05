<?php 

namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

/**
 * Classe Para Configurações de Rotas<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 08 de Maio de 2012
 */
abstract class Routes{

  private $routes;

  function __construct() {
    $this->init();
  }

  /**
   * Metodo que deve ser implementado com as rotas iniciais
   *
   * @return void
   * @author 
   **/
  protected abstract function init();

  /**
   * Adiciona uma rota em tempo de execução
   *
   * @return void
   * @author 
   **/
  public function add($url, $namespace, $controller, $action)
  {
    $this->routes[$url] = $namespace.Config::SEP_URL.$controller.Config::SEP_URL.$action;
  }

  /**
   * Remove uma rota em tempo de execução
   *
   * @return void
   * @author 
   **/
  public function remove($url)
  {
    unset($this->routes[$url]);
  }

  /**
   * Pega uma rota do array
   *
   * @return void
   * @author 
   **/
  public function get($url){
    return isset($this->routes[$url]) ? $this->routes[$url] : null;
  }

}