<?php

namespace Bloum;

class Db {

  private static $db = null;

  public static function getDb() {
    if (Db::$db == null)
      Db::$db = new PDO(Config::$DRIVER_DB, Config::$USER_DB, Config::$PASS_DB);
    return Db::$db;
  }

}