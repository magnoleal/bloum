<?php
namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

require DIR_BLOUM.'lib/smarty/Smarty.class.php';

/**
 * Classe Para Manipulação de Parametros Passados do Controller para View<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 18 de Maio de 2012
 */
class Output extends \Smarty
{
  private $callShow = false;
  private static $instance = null; 

  function __construct() {
    parent::__construct();
    $this->init();
  }

  public static function getInstance()
  {
    if(Output::$instance == null)
      Output::$instance = new Output();
    return Output::$instance;
  }

  public function getParams(){
    return $this->params;
  }

  public function isCallShow() {
    return $this->callShow;
  }

  public function setCallShow($callShow) {
    $this->callShow = $callShow;
  }  

  public function init(){
    $this->cache_dir = DIR_BLOUM."lib/smarty/cache";
    $this->config_dir = DIR_BLOUM."lib/smarty/configs";
    $this->compile_dir = DIR_BLOUM."lib/smarty/templates_c";
    $this->plugins_dir = DIR_BLOUM."lib/smarty/plugins";        
    $this->template_dir = DIR_APP."views/";
  }

  /**
   * Seta um objeto no output:
   * Percorre as propriedades do objeto e joga no OUTPUT
   * @param Object $objeto - Objeto a ser injetado
   * @param String $prefix - Caso queria identificar as propriedades com algum prefixo (Ex.: usuarioEmail, usuarioLogin)
   * @param String $sufix - Caso queria identificar as propriedades com algum sufixo (Ex.: emailUsuario, loginUsuario)
   */
  public function addObject($objeto = null, $prefix = "", $sufix = ""){

    $reflec = new ReflectionClass( $objeto );        

    foreach($reflec->getProperties() as $att){

      $key = $att->getName();

      if(strlen($prefix) > 0)
        $key = $prefix.$key;
      if(strlen($sufix) > 0)
        $key = $key.$sufix;
      
      $this->assign($key, $att->getValue());

    }
  }

  /**
   * Joga um value no output
   * @param String $key - nome de idenficação do value
   * @param Mixed $value - value a ser injetado (pode ser qualquer tipo de dado: String, int, array, etc)
   */
  public function addValue($key, $value){                
    $this->assign($key,$value);        
  }

  /**
   * Abre uma pagina (template)
   * Usa-se no final da execução dos metodos nas actions     
   * @param String $tpl - Pagina (template) a ser aberta
   */
  public function render($tpl){
    $this->callShow = true;
    $this->display($tpl);
  }

  /**
   * Retorna o conteudo de uma pagina (template)
   * @param String $tpl - Pagina (template) a ser retornada
   */
  public function getHtml($tpl){
    return $this->fetch($tpl);
  }

}