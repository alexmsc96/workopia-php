<?php

require __DIR__ . '/../vendor/autoload.php';


use Framework\Router;
use Framework\Session;

Session::start();

require '../helpers.php';

/**
 * This file is the entry point of the application.
 * It initializes the router, retrieves the routes from the routes.php file,
 * and handles the incoming request by routing it to the appropriate controller.
 */

$router = new Router;
$routes = require basePath('routes.php');
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->route($uri);