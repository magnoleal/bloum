<?php

namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

class Db {

  private static $db = null;

  public static function getDb() {
    if (Db::$db == null)
      Db::$db = new PDO(Config::$DRIVER_DB, Config::$USER_DB, Config::$PASS_DB);
    return Db::$db;
  }

}