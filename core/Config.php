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
    * Constante para identificar um prefixo para os valores <br/>
    * (evitar conflito com outros sites/sistemas no msm host)
    * @var KEY
    **/ 
    const KEY = "time_cms";  
    
    /**
    * Constante que define a separacao na url, Ex.: controller.action    
    **/  
    const SEP_URL = '/';

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
    * Constante que define a separacao das actions no filtro, Ex.: index|save|edit
    **/  
    const SEP_ACTION_FILTERS = '|';

    /**
    * Constante que define a separacao na url dos parametros, Ex.: controller.action?param1=43    
    **/  
    const TEMPLATE_EXT = '.tpl';
    
    /**
    * Constante que define a localizacao dos arquivos css    
    **/  
    const DIR_CSS = 'app/assets/css/';
    
    /**
    * Constante que define a localizacao dos arquivos js    
    **/  
    const DIR_JS = 'app/assets/js/';
    
    /**
    * Constante que define a localizacao dos arquivos de imagens
    **/  
    const DIR_IMAGES = 'app/assets/img/';
    
    /**
    * Ativar Minify
    **/  
    const MINIFY_ENABLE = true;
    
    /**
    * Slug Action
    **/  
    const SLUG_ACTION = 'slug';

    /**
    * Define se a url vem no padrao underscore (ex:categoria_blog)
    **/  
    const URL_UNDESCORE = true;
    
}