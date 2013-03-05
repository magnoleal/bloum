<?php
namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

/**
 * Classe Para Controle de data e Acesso<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 15 de Janeiro de 2013
 */
abstract class Authorization {

  protected $data;
  protected static $FREE = "F";
  
  public static $OK = 0;
  public static $NO_LOGGED = 1;
  public static $NO_ACCESS = 2;
  
  protected $superAdminGroup = '';

  /**
   * Instancia da classe (Singleton)
   * @var $instance
   **/
  private static $instance = null;

  private function __construct(){}

  public static function getInstance()
  {
    if(Authorization::$instance == null)
      Authorization::$instance = new Authorization();
    return Authorization::$instance;
  }

  /**
   * Retorna o array montado com as autorizacoes
   * @return array
   */
  protected function getdata() {
    return $this->data;
  }

  /**
   * Adicionar todos os controllers de um namespace para
   * um determinado grupo de usuarios
   * @param String $namespace Nome do Namespace
   * @param int $group Grupo de Usuario
   */
  protected function addNamespace($namespace, $group = "L") {        
    $this->data[$group][$namespace]['all']['all'] = true;
  }

  /**
   * Adicionar todos as actions de um controller para
   * um determinado grupo de usuarios
   * @param String $namespace Nome do Namespace
   * @param String $controller Nome do Controller
   * @param int $group Grupo de Usuario
   */
  protected function addController($namespace = '', $controller = '', $group = "L") {
    $this->data[$group][$namespace][$controller]['all'] = true;
  }

  /**
   * Adicionar uma action especifico de um controller para
   * um determinado grupo de usuarios
   * @param String $namespace Nome do Namespace
   * @param String $controller Nome da Action
   * @param String $action Nome do Action
   * @param int $group Grupo de Usuario
   */
  protected function addAction($namespace = '', $controller = '', $action = '', $group = "L") {
    $this->data[$group][$namespace][$controller][$action] = true;
  }

  /**
   * Verifica se o usuario pode acessar o action da controller
   * @param int $group Grupo de Usuario
   * @param String $controller Nome da Action
   * @param String $action Nome do Action     
   * @return true|false
   */
  public function isAuthorized($url, $group) {

    if(strlen($this->superAdminGroup) > 0 && $this->superAdminGroup == $group)
      return true;

    if(isset($this->data[$group])){

      $n = $url->getNamespace();
      $c = isset($this->data[$group][$n]['all']) ? 'all' : $url->getController();
      $a = isset($this->data[$group][$n][$c]['all']) ? 'all' : $url->getAction();

      if(isset($this->data[$group][$n][$c][$a]))
        return $this->data[$group][$n][$c][$a];
      return false;

    }

    return false;        
  }

   /**
   * Metodo que deve ser implementado com as autorizacoes iniciais
   *
   * @return void
   * @author 
   **/
  protected abstract function init();

}

?>
