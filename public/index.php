<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

session_start();

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();




// API routes

$router = new Core\Router();
$router->add('api/products', ['controller' => 'Api', 'action' => 'productsall']);
$router->add('api/product/{id:\d+}', ['controller' => 'Api', 'action' => 'product']);
$router->add('api/city/{id:\d+}', ['controller' => 'Api', 'action' => 'city']);
$router->add('api/cities', ['controller' => 'Api', 'action' => 'citiesall']);

// Add the web routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);

// Authentification routes
$router->add('login', ['controller' => 'User', 'action' => 'login']);
$router->add('register', ['controller' => 'User', 'action' => 'register']);
$router->add('logout', ['controller' => 'User', 'action' => 'logout', 'private' => true]);
$router->add('dashboard', ['controller' => 'Home', 'action' => 'dashboard', 'private' => true]);
$router->add('account', ['controller' => 'User', 'action' => 'account', 'private' => true]);

// Features Routes
$router->add('mailsender', ['controller' => 'Home', 'action' => 'send']);
$router->add('contact/page', ['controller' => 'Home', 'action' => 'contact']);

// Documentation routes
$router->add('swagger-json', ['controller' => 'Swagger', 'action' => 'json']);

// Productions routes
$router->add('product', ['controller' => 'Product', 'action' => 'index', 'private' => true]);


$router->add('mailsender', ['controller' => 'Home', 'action' => 'send']);
$router->add('contact/page', ['controller' => 'Home', 'action' => 'contact']);

$router->add('product/{id:\d+}', ['controller' => 'Product', 'action' => 'show']);
// $router->add('product/{id:\d+}/edit', ['controller' => 'Product', 'action' => 'edit']);
// $router->add('product/{id:\d+}/delete', ['controller' => 'Product', 'action' => 'delete']);
$router->add('{controller}/{action}');

/*
 * Gestion des erreurs dans le routing
 */
try {
    $router->dispatch($_SERVER['QUERY_STRING']);
} catch (Exception $e) {
    switch ($e->getMessage()) {
        case 'vous deveriez être authentifié':
            header('Location: /login');
            break;
    }
}
