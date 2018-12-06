<?php

namespace Core;

class View {

 /**
 * Requires a view
 */ 
  public static function render($view, $args=[]) {
    extract($args, EXTR_SKIP);

    $file = "../App/Views/$view";

    if(is_readable($file)) {
      require($file);
    }
    else {
      throw new \Exception("$file not found");
    }
  }

 /**
 * Renders a Twig template and passes the arguments
 */ 
  public static function renderTemplate($template, $args=[]) {
    static $twig = null;

    if($twig === null) {
      $loader = new \Twig_Loader_Filesystem('../App/Views');
      $twig = new \Twig_Environment($loader);
    }

    echo $twig->render($template, $args);
  }

}
