<?php
namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

require_once DIR_BLOUM.'core/Exceptions.php';

/**
 * Classe Principal do Framework<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 11 de Maio de 2012
 **/
class Main {

  /**
   * Objeto Bloum\Url
   * @var url
   **/
  private $url;    
  private $keySessionID = "ksi";
 
  function __construct() {

    date_default_timezone_set('America/Sao_Paulo');
    spl_autoload_register(array($this, 'loader'));

    if (!defined('DIR_APP')) 
      throw new NotFoundException('Constant DIR_APP Not Found!');
    
    $this->url = Url::getInstance();
    
  }

  /**
   * Seta o id da sessao em casos de requisicao flash
   **/
  private function setSessionFromFlash() {
    
    if (isset($_REQUEST[$this->keySessionID]) && strlen($_REQUEST[$this->keySessionID]) > 0) {
      session_id($_REQUEST[$this->keySessionID]);
      @session_start();
    }
  }
  
  private function setSession(){
    if (!Util::isRequestFlash()){
      if(!session_start())
        throw new Exception('Error on start Session!');      
    }
    else{
      $this->setSessionFromFlash();      
    }
  }

  /**
   * Metodo principal, executa a chamada dos controllers
   **/
  public function run(){
    
    $isAjax = false;

    if (Util::isRequestFlash())
      $isAjax = true;    
    
    $this->setSession();
    
    $controllerName = $this->url->getControllerNameClass()."Controller";

    if ( !file_exists(DIR_APP . "controllers/" .   $this->url->getNamespaceDir().$controllerName . ".php") )
      throw new NotFoundException('Controller ' .  $this->url->getNamespaceDir().$controllerName . ' Not Found!');

    if (!$isAjax)            
      $isAjax = Util::isRequestAjax(); 
    

    try {

      if(file_exists(DIR_APP."controllers/ApplicationController.php"))
        require_once DIR_APP."controllers/ApplicationController.php";    

      require_once DIR_APP."controllers/".$this->url->getNamespaceDir().$controllerName.".php";
      
      $controller = new $controllerName();   
      $controller->setAjax($isAjax);
      $controller->execute($this->url->getAction());
      
    } catch (\Exception $exc) {
      
      if ($isAjax){      
        
        header('HTTP/1.1 500 Internal Server');
        header('Content-Type: application/json');
        
        die( json_encode(array('message' => $exc->getMessage(), 'code' => 333)) );
      }else{
        var_dump($exc->getMessage());
      }
      
    }
    
  }

  public static function loader($class){
    $namespace = '';
    $parts = explode('\\', $class);

    $count = count($parts);
    $class = $parts[$count-1];

    if($count > 1){        
      unset($parts[$count - 1]);
      $namespace = strtolower( implode("/", $parts) );
    }  

    if ($namespace == 'bloum')
      require_once DIR_BLOUM."core/$class.php";
    elseif ($namespace == 'gen')
      require_once DIR_BLOUM."gen/$class.php";

    elseif(file_exists(DIR_APP."models/$class.php"))
      require_once DIR_APP."models/$class.php";    
    
    elseif (strpos($class,'Model'))
      require_once DIR_APP."models/$class.php";

    elseif (strpos($class,'Helper'))
      require_once DIR_APP."helpers/$class.php";
    
    elseif (strpos($class,'Config'))
      require_once DIR_APP."config/$class.php";     
    
    elseif (strpos($class,'Lib'))
      require_once DIR_APP."lib/$class.php";     
  }

}