<?php

namespace App\Models;

use PDO;

class Post extends \Core\Model {

 /**
 * Fetches all posts from the database
 *
 * @return array  all posts
 */ 
  public static function getAll() {
    try {
	  // Get static database instance
      $db = static::getDB();

	  // Execute query, fetch results and return them
      $stmt = $db->query("SELECT id, title, content FROM posts ORDER BY created_at");
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $results;
    }
    catch (PDOexception $e) {
      throw new \Exception("Database query failed.");
    }
  }

}
