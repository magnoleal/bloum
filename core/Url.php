<?php

namespace Bloum;

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
 
  function __construct($url = null) {

    if ($url == null) {
      $this->url = $_SERVER['PHP_SELF'];
      $paramGet = explode($this->url, $_SERVER ['REQUEST_URI']);
      if (count($paramGet) > 1)
        $this->url .= $paramGet[1];
    }else {
      $this->url = $url;
    }



    $this->url = substr($this->url, stripos($this->url, Config::ROOT_SCRIPT . "/") + strlen(Config::ROOT_SCRIPT . "/"));

    echo $this->url;

    $this->explode();
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
    $arrayUrl = explode(Config::SEP_URL, $this->url);

    $count = count($arrayUrl);

    if ($count != 2)
      throw new BabUrlException("Bad Url Format!");

    $ind = 0;
    $this->controller = $arrayUrl[0];

    //Quebrando caso existam parametros
    $arrayUrl = explode("?", $arrayUrl[$ind]);

    $this->action = $arrayUrl[0];
  }

}

?>