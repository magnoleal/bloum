<?php
namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

require_once DIR_BLOUM.'core/Exceptions.php';

class Main {

  private $url;  
  private $session;

  function __construct() {
    
    $this->url = new Url();

    @session_start();

  }

  private function setSessionFromFlash() {
    if (isset($_REQUEST[$this->keySessionID]) && strlen($_REQUEST[$this->keySessionID]) > 0) {
      session_id($_REQUEST[$this->keySessionID]);
      @session_start();
    }
  }

  /**
   * undocumented function
   *
   * @return void
   * @author 
   **/
  public function run(){
    $isAjax = false;

    if (Util::isRequestFlash()){
      $isAjax = true;
      $this->setSessionFromFlash();
    }

    #if ( !file_exists(DIR_APP . "controllers/" . $this->url->getController() . ".php") )
     # throw new NotFoundException('Controller ' . $this->url->getController() . ' Not Found!');

    $session = Session::getInstance();
    
    $session->setUrls($this->url->getUrl());


    if (!$isAjax)            
      $isAjax = Util::isRequestAjax();                                

  }

}

?>