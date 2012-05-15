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

}