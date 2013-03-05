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
class Url {

  /**
   * Url Atual
   * @var url
   **/
  private $url;

  /**
   * Modulo Atual
   * @var namespace
   **/
  private $namespace;
  
  /**
   * Controller Atual
   * @var controller
   **/
  private $controller;

  /**
   * Action Atual
   * @var action
   **/
  private $action;
  /**
   * Host Atual
   * @var host
   **/
  private $host;

  /**
   * Instancia da classe (Singleton Opcional)
   * @var $instance
   **/
  private static $instance = null;
 
  function __construct($url = null) {

    $this->host = substr($_SERVER['PHP_SELF'], 0, stripos($_SERVER['PHP_SELF'], Config::ROOT_SCRIPT));    
    $this->url = substr($_SERVER['REQUEST_URI'], stripos($_SERVER['REQUEST_URI'], $this->host)+strlen($this->host));
    
    define('HOST_PATH', $this->host);
    
    $a_url = explode("?", $this->url);
    if(strlen($a_url[0]) <= 0)   
      $this->url = "home";    
    
    $this->explode();
  }

  public static function getInstance()
  {
    if(Url::$instance == null)
      Url::$instance = new Url();
    return Url::$instance;
  }

  public function getNamespace(){
    return $this->namespace;
  }

  public function getNamespaceDir(){

    if(strlen($this->namespace) > 0)
      return strtolower(str_replace(".", "/", $this->namespace)."/");
    return '';

  }
  
  public function getController() {
    return $this->controller;
  }

  public function getControllerNameClass() {
    return ucfirst(Config::URL_UNDESCORE ? Util::camelize($this->controller) : $this->controller);
  }

  public function getAction() {
    return $this->action;
  }
  
  public function setAction($action) {
    $this->action = $action;
  }
 
  public function getUrl() {
    return $this->url;
  }
  
  public function getHost() {
    return $this->host;
  }
  
  /**
   * Funcao que quebra a url populando os atributos da classe
   *
   * @return void
   * @author Magno Leal <magnoleal89@gmail.com>
   * @version 1.0 - 08 de Maio de 2012
   * 
   * 
   */
  private function explode() {

    $link = $this->url;

    if (file_exists(DIR_APP.'config/RoutesConfig.php')){      
      
      $routes = new \RoutesConfig();
      $route = $routes->get($link);

      if($route != null)
        $link = $route;

    }

    $arrayUrl = explode("?", $link);
    $numParts = count($arrayUrl);
    
    if($numParts > 0)
      $arrayUrl = $arrayUrl[0];
    
    $arrayUrl = explode(Config::SEP_URL, $arrayUrl);
    $numParts = count($arrayUrl);
    
    if($numParts < 1)
      throw new BadUrlException("Bad Format Url!");
      

    if($numParts >= 2 && strlen($arrayUrl[$numParts - 1]) > 0){

      //action eh a ultima parte da url
      //$parmParts = explode("?", $arrayUrl[$numParts - 1]);
      $this->action = $arrayUrl[$numParts - 1];
      
    }else{
      $this->action = 'index';
      $this->url .= $this->url[strlen($this->url)-1] == Config::SEP_URL ? 'index' : Config::SEP_URL.'index';
      $arrayUrl[] = $this->action;      
    }

    $this->controller = $arrayUrl[$numParts - 2]; //controller eh a penultima parte da url
    $this->namespace = implode(Config::SEP_URL, array_slice($arrayUrl, 0, $numParts - 2)); //namespace eh o resto

  }

}