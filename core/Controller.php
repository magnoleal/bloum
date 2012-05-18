<?php
namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

/**
 * Controller Base<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 15 de Maio de 2012
 **/
class Controller
{

  protected $url;
  protected $input;
  protected $output;
  protected $session;

  protected $beforeFilter;
  protected $afterFilter;

  function __construct() {
    
    $this->url = Url::getInstance();
    $this->input = Input::getInstance();
    $this->output = Output::getInstance();
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

  public function execute($action){
    if ( !method_exists($this, $action) ) 
       throw new NotFoundException('Action ' . $action . ' Not Found in Controller ' . $this->url->getController() . '!');

    $refMetodo = new \ReflectionMethod($this, $action);
    $metParametros = $refMetodo->getParameters();

    $rs = $refMetodo->invokeArgs($this, array());

  }

  /**
   * Redireciona para uma página
   * Detalhe: Caso, ocorra um ecadeamento de ações (ex.: de salvar, chama listar)
   * os parametros da primeira serão pertido ,caso não seja desejado use 
   * a function chain
   * @param String $url - url de redirecionamento (Ex.: UsuarioActioin.listar)
   */
  public function redirect($url){        
    header("Location: $url");
  }

  /**
   * Redirecona para uma página sem perder os parametros,
   * deve ser usada quando ocorre encadeamento de ações (ex.: de salvar, chama listar)
   * os parametros da primeira não serão pertidos, caso não seja desejado use
   * a function redirect
   * @param String $url - url de redirecionamento (Ex.: UsuarioActioin.listar)
   */
  public function chain($url){        
    $this->session->setValue(Session::SESSION_PARAMS, $this->output->getTemplateVars());
    header("Location: $url");        
  }

  /**
   * Redirecona para a úlima página visitada sem perder os parametros,
   * pode ser usada quando ocorre algum erro esperado ou não na Action
   * os parametros da primeira não serão pertidos, caso não seja desejado use
   * a function redirect
   * @param String $url - url de redirecionamento (Ex.: UsuarioActioin.listar)
   */
  public function back(){
    $values = array_merge($this->input->getParams(), $this->output->getTemplateVars());
    $this->session->setValue(Session::SESSION_PARAMS, $values);
    
    header("Location: ".$this->session->getValue(Session::SESSION_PREVIOUS_URL));       
  }

}