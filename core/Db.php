<?php

namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

require DIR_BLOUM.'lib/activerecord/ActiveRecord.php';    

class Db {

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

  }

}