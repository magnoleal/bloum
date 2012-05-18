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

  private $url;
  private $controller;
  private $action;

  private static $instance = null;
 
  function __construct($url = null) {

    $this->url = isset($url) ? $url : $_SERVER['PHP_SELF'];

    $this->url = substr($this->url, stripos($this->url, Config::ROOT_SCRIPT) + strlen(Config::ROOT_SCRIPT));      

    if(strlen($this->url) <= 0)
      $this->url = "index.index";
    elseif($this->url[0] = '/')
      $this->url = substr($this->url, 1);

    $this->explode();
  }

  public static function getInstance()
  {
    if(Url::$instance == null)
      Url::$instance = new Url();
    return Url::$instance;
  }


  public function getController() {
    return $this->controller;
  }

  public function getAction() {
    return $this->action;
  }

  public function getUrl() {
    return $this->url;
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

    $arrayUrl = explode(Config::SEP_URL, $link);

    if(count($arrayUrl) > 2)
      throw new BadUrlException("Bad Format Url!");
      
    $this->controller = $arrayUrl[0];
    
    if(count($arrayUrl) == 1 || strlen($arrayUrl[1]) < 1){

      $this->action = 'index';

    }else{
      
      //Quebrando caso existam parametros
      $arrayUrl = explode("?", $arrayUrl[1]);
      $this->action = $arrayUrl[0];

    }

  }

}