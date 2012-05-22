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
  /**
   * Objeto Bloum\Url
   * @var url
   **/
  protected $url;

  /**
   * Objeto Bloum\Input
   * @var input
   **/
  protected $input;

  /**
   * Objeto Bloum\Ouput
   * @var output
   **/
  protected $output;

  /**
   * Objeto Bloum\Session
   * @var session
   **/
  protected $session;

  /**
   * Array com os filtros que sao executados antes da action nos controllers: <br/>
   * Como Usar: static $beforeFilter = array(
   *              'filter1' => array('only' => 'action1|action2'),
   *              'filter2' => array('skip' => 'action1|action2'),
   *              'filter3' => array('only' => 'all')
   *            );
   *
   *
   * @var beforeFilter
   **/
  static protected $beforeFilter = array();

  /**
   * Array com os filtros que sao executados depois da action nos controllers: <br/>
   * Como Usar: static $afterFilter = array(
   *              'filter1' => array('only' => 'action1|action2'),
   *              'filter2' => array('skip' => 'action1|action2'),
   *              'filter3' => array('only' => 'all')
   *            );
   *
   *
   * @var afterFilter
   **/
  static protected $afterFilter = array();


  /**
   * Construtor da classe, instancia as referencias
   **/  
  function __construct() {
    
    $this->url = Url::getInstance();
    $this->input = Input::getInstance();
    $this->output = Output::getInstance();
    $this->session = Session::getInstance();

  }

  /**
   * Seta um Usuario na Sessao
   * @param $user Mixed Valor que represente um Usuario
   */
  public function setUserSession($user){                      
    $this->session->setValue(Session::USER, $user);
  }

  /**
   * Pega o Usuario da Sessao
   * @return Mixed Valor que represente um Usuario
   */
  public function getUserSession(){
    return $this->session->getValue(Session::USER);
  }

  /**
   * Verifica se o usuario esta logado
   * @return true|false
   */
  public function isLogged(){

    $this->user = $this->getUserSession();

    if( $this->user && $this->user instanceof Model\Usuario &&  $this->user->id() > 0 )
        return true;
      
    return false;
  }

  /**
   * Executa a chamada da action e filtros caso exista   
   * @param $action String action que sera executada
   */
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

  /**
   * Executa a chamada de um filtro 
   * @param $arrayFilter Array Configuracao do filtro
   * @param $action String action que sera executada
   */
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

  /**
   * Monta os parametros para chamada de um metodo 
   * @param $refMethod ReflectionMethod Metodo instanciado por Reflection
   * @return Array retorna o array com os parametros do metodo e seus valores
   */
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
    
    header("Location: ".$this->session->getValue(Session::PREVIOUS_URL));       
  }

}