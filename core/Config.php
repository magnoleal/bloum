<?php 

namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

/**
 * Classe Para Configurações gerais do CORE<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 08 de Maio de 2012
 */
class Config{  
    /**
    * Constante que define a separacao na url, Ex.: controller.action    
    **/  
    const SEP_URL = '.';

    /**
    * Constante que define a separacao na url dos parametros, Ex.: controller.action?param1=43    
    **/  
    const SEP_PARAMS = '?';

    /**
    * Constante que define se o log esta ou nao ativado
    **/  
    const LOG_ENABLE = true;

    /**
    * Constante que define o diretorio que ficarao os logs da frame
    **/  
    const DIR_LOG = 'logs/';

    /**
    * Constante que define o script que o .htaccess redireciona
    **/  
    const ROOT_SCRIPT = 'index.php';

    /**
    * Constante que define a separacao na url dos parametros, Ex.: controller.action?param1=43    
    **/  
    const SEP_ACTION_FILTERS = '|';
}