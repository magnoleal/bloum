<?php
namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

require DIR_BLOUM.'lib/smarty/Smarty.class.php';

/**
 * Controller Base<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 15 de Maio de 2012
 **/
class Controller
{

  protected $input;
  protected $session;

  function __construct() {
    $this->input = Input::getInstance();
    $this->session = Session::getInstance();
  }


  /**
   * Seta um Usuario na Sessão
   * @param UsuarioBean $user - objeto Usuario
   */
  public function setUserSession($user){                      
    $this->session->setValue(Session::USER, $user);
  }

  public function getUserSession(){
    return $this->session->getValue(Session::USER);
  }

  public function isLogged(){

    $this->user = $this->getUserSession();

    if( $this->user && $this->user instanceof Model\Usuario &&  $this->user->id() > 0 )
        return true;
      
    return false;
  }

}