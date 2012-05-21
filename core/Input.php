<?php
namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

/**
 * Classe Para Manipulação de Parametros Passados Para o Servidor<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 08 de Maio de 2012
 */
class Input
{
  private $params;
  private static $instance = null;

  private function __construct()
  {
    if($this->params == null)
      $this->params = array();
    $this->extract();
  }

  public static function getInstance()
  {
    if(Input::$instance == null)
      Input::$instance = new Input();
    return Input::$instance;
  }

  public function getParams(){
    return $this->params;
  }

  private function extract(){            
    $this->extractFromArray($_REQUEST);                    
  }

  private function extractFromArray($array)
  {
    $keys = array_keys($array);

    for ($i = 0; $i < count($keys); $i++)
      $this->params[addslashes(trim($keys[$i]))] = addslashes(trim($array[$keys[$i]]));
  }  

  /**
   * Pega um Valor (objeto ou nao) da Sessão
   * @param String $chave - identificação do valor
   * @return Mixed Valor da Sesssao
   */
  public function getValue($key){
    return isset ($this->params[$key]) ? $this->params[$key] : null;
  }

  /**
   * Pega um Valor (objeto ou nao) da Sessão
   * @param String $chave - identificação do valor
   * @return Mixed Valor da Sesssao
   */
  public function exist($key){
    return isset ($this->params[$key]);
  }

  /**     
   * Pega um objeto do input:
   * Popula o objeto de acordo com suas propriedades e os nomes encontrados no INPUT
   * @param String $className - Nome da Classe do Objeto que deseja Popular
   * @return Retorna o objeto populado
   */
  public function getObject($className){
    $reflec = new \ReflectionClass($className);
    return $reflec->newInstance($this->params);
  }

}