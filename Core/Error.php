<?php

namespace Core;

class Error {

 /**
 * Converts error to exception
 */ 
  public static function errorHandler($level, $message, $file, $line) {
    if(error_reporting() !== 0) {
      throw new \ErrorException($message, 0, $level, $file, $line);
    }
  }

 /**
 * Handles all exceptions
 * Writes exception to log file & renders exception template if 'SHOW_ERRORS' is set to false in the Config file
 * If 'SHOW_ERRORS' is set to true, echoes the exception
 */ 
  public static function exceptionHandler($exception) {
    $code = $exception->getCode();
    if($code != 404) {
      $code = 500;
    }

    http_response_code($code);

    if(\App\Config::SHOW_ERRORS) {
      echo "<h1>Fatal Error</h1>";
      echo "<p>Uncaught exception: '". get_class($exception) ."'</p>";
      echo "<p>Message: '". $exception->getMessage() ."'</p>";
      echo "<p>Stack trace:<pre>". $exception->getTraceAsString() ."</pre></p>";
      echo "<p>Thrown in: '". $exception->getFile() ."' on line ". $exception->getLine() ."</p>";
    }
    else {
      $log = dirname(__DIR__) . '/logs/' . date('Y-m-d-') . '.txt';
      ini_set('error_log', $log);

      $message = "Uncaught exception: '". get_class($exception) ."'";
      $message .= " with message '". $exception->getMessage() ."'";
      $message .= "\nStack trace: ". $exception->getTraceAsString();
      $message .= "\nThrown in '". $exception->getFile() ."' on line ". $exception->getLine();

      error_log($message);

      View::renderTemplate("$code.html");
    }
  }

}
