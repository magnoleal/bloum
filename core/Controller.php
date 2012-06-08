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
   * Layout default
   * @var layout
   **/
  static protected $layout = "application";
  
  /**
   * Tipos de mensagens - error|success|info|warning
   * @var array 
   */
  protected $messages = array();
  
  /**
   * Variavel para definir se foi chamado metodos de redirecionamento
   * @var boolean 
   */
  private $callShow = false;

  /**
   * Construtor da classe, instancia as referencias
   **/  
  function __construct() {
    
    $this->url = Url::getInstance();
    $this->input = Input::getInstance();
    $this->output = Output::getInstance();
    $this->session = Session::getInstance();
    
    $this->setOutputFromSession();

  }

  public function setCallShow($callShow) {
    $this->callShow = $callShow;
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

    $existAppContr = file_exists(DIR_APP.'controllers/ApplicationController.php');

    try {

      //executando globalBeforeFilter do ApplicationController
      if($existAppContr && isset(\ApplicationController::$globalBeforeFilter))
        $this->executeGlobalFilter(\ApplicationController::$globalBeforeFilter);  

      //executando beforeFilter do Controller corrente
      $this->executeFilter(static::$beforeFilter, $action);

      //executando a action chamada na url
      $refMethod = new \ReflectionMethod($this, $action);
      $rs = $refMethod->invokeArgs($this, $this->mountParams($refMethod));

      //executando afterFilter do Controller corrente
      $this->executeFilter(static::$afterFilter, $action);

      //executando globalAfterFilter do ApplicationController
      if($existAppContr && isset(\ApplicationController::$globalAfterFilter))
        $this->executeGlobalFilter(\ApplicationController::$globalAfterFilter);  

      //caso nao passe o template, chama um com o mesmo nome da action
      if(!$this->callShow)
        $this->show($action);
    
    } catch (\Exception $exc) {      
      $this->messages['error'] = $exc->getMessage();
      $this->output->addValue('messages', $this->messages);
      $this->back();
    }

  }

  /**
   * Executa a chamada de um filtro 
   * @param $arrayFilter Array Configuracao do filtro
   * @param $action String action que sera executada
   */
  public function executeGlobalFilter($arrayFilter){

    if(count($arrayFilter) > 0){

      foreach ($arrayFilter as $filter) {

        if ( !method_exists($this, $filter) ) 
          throw new NotFoundException('GlobalFilter ' . $filter . ' Not Found in ApplicationController!');
                
        $refFilter = new \ReflectionMethod($this, $filter);
        $refFilter->invokeArgs($this, $this->mountParams($refFilter));
        
      }

    }
  }

  /**
   * Executa a chamada de um filtro 
   * @param $arrayFilter Array Configuracao do filtro
   * @param $action String action que sera executada
   */
  public function executeFilter($arrayFilter, $action){

    if(count($arrayFilter) > 0){

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
    $this->callShow = true;
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
    $this->callShow = true;
    $this->setMessagesOutput();
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
    $this->callShow = true;
    
    $this->setMessagesOutput();
    $values = array_merge($this->input->getParams(), $this->output->getTemplateVars());
    $this->session->setValue(Session::SESSION_PARAMS, $values);
    
    header("Location: ".$this->session->getValue(Session::PREVIOUS_URL));       
  }

  public function getLayoutPath(){
    $ct = $this->url->getController();
      
    $ctLayout = "extends:";

    //1ª opcao: layout na classe filha
    //2ª opcao: layout com o nome do controller
    //Ultima opcao: layout default
    if (static::$layout != "application" ){
      
      if(strlen(static::$layout) > 0)
        $ctLayout .= static::$layout;
      else
        $ctLayout = '';

    } else if(file_exists(DIR_APP.'views/layouts/'.$ct.Config::TEMPLATE_EXT)){
      $ctLayout .= DIR_APP.'views/layouts/'.$ct;
    } else{
      $ctLayout .= 'layouts/application';
    }         

    if(strlen($ctLayout) > 0)
      $ctLayout .= Config::TEMPLATE_EXT."|";

    return $ctLayout;
  }

  public function getTemplatePath($tpl = ""){
    $tpl = strlen($tpl) > 0 ? $tpl : $this->url->getAction();
    return $this->getLayoutPath().$this->url->getController()."/".$tpl.Config::TEMPLATE_EXT;
  }

  public function show($tpl){
    $this->callShow = true;
    $this->setMessagesOutput();
    $this->output->show($this->getTemplatePath($tpl));
  }

  /**
   * Retorna o conteudo de uma pagina (template)
   * @param $tpl String - Pagina (template) a ser retornada
   */
  public function getHtml($tpl){
    $this->callShow = true;
    $this->setMessagesOutput();
    return $this->output->fetch($this->getTemplatePath($tpl));     
  }    

  /**
   * Seta no Output os parametros existentes na sessão,
   * metodo utilizado quando ocorrer um Encadeamento de Actions,
   * ou seja o valor de output de uma action e passado para outra
   */
  private function setOutputFromSession(){
    $sessionParams = $this->session->getValue(Session::SESSION_PARAMS);
    
    

    if(isset ($sessionParams) && count($sessionParams) > 0){

      $keys = array_keys($sessionParams);
      
      for($i = 0; $i < count($keys); $i++){
        $this->output->addValue($keys[$i], $sessionParams[$keys[$i]]);
      }

      $this->session->setValue(Session::SESSION_PARAMS, null);

    }
  }
  
  private function setMessagesOutput(){
    if( $this->output->getTemplateVars('messages') == null )
        $this->output->addValue('messages', $this->messages);
  }
  
}