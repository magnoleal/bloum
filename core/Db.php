<?php

namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

require_once DIR_BLOUM.'lib/activerecord/ActiveRecord.php';    

/**
 * Classe Para Configurações gerais do Banco e Active Record<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 21 de Maio de 2012
 */
class Db {

  /**
   * Inicializa as configurações
   **/
  public static function init(){

    if(!file_exists(DIR_APP.'config/DbConfig.php'))
      throw new NotFoundException("Class DbConfig Not Found, Check Your App Config Folder!");
    
    $default = defined('ENVIRONMENT') ? ENVIRONMENT : 'development';


    $connections = \DbConfig::$connections;
 
    \ActiveRecord\Config::initialize(function($cfg) use ($connections, $default)
    {

      $cfg->set_model_directory(DIR_APP.'models');
      $cfg->set_connections($connections);
      $cfg->set_default_connection($default);

    });

    \ActiveRecord\DateTime::$FORMATS['br'] = 'd/m/Y H:i:s';
    \ActiveRecord\DateTime::$DEFAULT_FORMAT = 'br';

  }

}

//inicializando configuracoes de banco de dados
Db::init();