<?php

namespace App\Controllers;

use \Core\View;

class Home extends \Core\Controller {

  /**
  * Executes before the action gets executed.
  * This could be used for checking for authentication
  */ 
  protected function before() {
  }

  /**
  * Executes after the action gets executed.
  */ 
  protected function after() {
  }

  /**
  * Renders the homepage template
  */ 
  public function indexAction() {
    View::renderTemplate('Home/index.html', [
      'name' => 'Jelle',
      'stack' => ['PHP', 'MySQL', 'Twig']
    ]);
  }

}
