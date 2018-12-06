<?php

namespace App\Controllers;

use Core\View;
use App\Models\Post;

class Posts extends \Core\Controller {

  /**
  * Fetches all posts and passes them to a view
  */ 
  public function indexAction() {
    $posts = Post::getAll();

    View::renderTemplate('Posts/index.html', [
      'posts' => $posts
    ]);
  }
  
}
