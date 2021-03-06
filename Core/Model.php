<?php

namespace Core;

use PDO;
use App\Config;

abstract class Model {

 /**
 * If database is not yet set up, make a new instance
 * 
 * @return database
 */ 
  protected static function getDB() {
    static $db = null;

    if($db === null) {
      $db = new PDO("mysql:host=".Config::DB_HOST.";dbname=".Config::DB_NAME.";charset=utf8",
      Config::DB_USER, Config::DB_PASSWORD);

      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $db;
  }

}
