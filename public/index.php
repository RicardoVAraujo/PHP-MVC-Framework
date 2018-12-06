<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

// Setup custom error handling
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


$router = new Core\Router();

// Add routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
$router->add('{controller}/{id:\d+}/{action}');

// Dispatch current query string
$router->dispatch($_SERVER['QUERY_STRING']);
