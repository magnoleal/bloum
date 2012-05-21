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

  static protected $beforeFilter = array();
  static protected $afterFilter = array();

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

    //inicializando configuracoes de banco de dados
    Db::init();
    
    $this->executeFilter(static::$beforeFilter, $action);

    $refMethod = new \ReflectionMethod($this, $action);

    $rs = $refMethod->invokeArgs($this, $this->mountParams($refMethod));

    $this->executeFilter(static::$afterFilter, $action);


    if(!$this->output->isCallShow())
      $this->output->show($action.Config::TEMPLATE_EXT);

    $this->output->render();

  }

  public function executeFilter($arrayFilter, $action){
    $filters = array_keys($arrayFilter);

    foreach ($filters as $filter) {

      $filterParams = $arrayFilter[$filter];

      if ( !method_exists($this, $filter) ) 
        throw new NotFoundException('Filter ' . $filter . ' Not Found in Controller ' . $this->url->getController() . '!');
      
      $skip = isset($filterParams['skip']) ? $filterParams['skip'] : '';
      $skip = explode(Config::SEP_ACTION_FILTERS, $skip);

      if( array_search($action, $skip) !== FALSE )
        continue;

      $only = isset($filterParams['only']) ? $filterParams['only'] : '';
      $only = explode(Config::SEP_ACTION_FILTERS, $only);

      if( strlen($only[0]) > 0 && $only[0] != 'all' && array_search($action, $only) === FALSE )
        continue;

      $refFilter = new \ReflectionMethod($this, $filter);
      $refFilter->invokeArgs($this, $this->mountParams($refFilter));
      
    }
  }

  private function mountParams($refMethod){
    $metParametros = $refMethod->getParameters();

    $arrayParams = array();

    foreach ($metParametros as $param) {
      //se for um model, ja passa o objeto populado
      if (file_exists(DIR_APP.'models/'.ucfirst($param->name.'.php'))){
        $arrayParams[$param->name] = $this->input->getObject( ucfirst($param->name) );
      }        
      elseif($this->input->exist($param->name)){
        $arrayParams[$param->name] = $this->input->getValue($param->name);
      }
    }

    return $arrayParams;
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