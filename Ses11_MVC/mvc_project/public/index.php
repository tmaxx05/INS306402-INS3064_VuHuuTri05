<?php
// public/index.php

// 1. Autoloader Configuration
spl_autoload_register(function ($className) {
    // Define the base directories where classes might live
    $directories =[
        '../core/',
        '../app/Controllers/',
        '../app/Models/'
    ];

    foreach ($directories as $dir) {
        $file = $dir . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return; // Stop searching once found
        }
    }
});

// 2. Initialize Router
$router = new Router();

// 3. Define Routes (Exercise 2 Requirement)
// Mapping URLs to Controller@Method
$router->get('/products', 'ProductController@index');
$router->get('/products/create', 'ProductController@create');
$router->post('/products/store', 'ProductController@store');
$router->get('/products/edit', 'ProductController@edit');
$router->post('/products/update', 'ProductController@update');
$router->post('/products/delete', 'ProductController@delete');

// 4. Dispatch the Request (with Error Handling Homework Requirement)
try {
    // Get the current URL path. Default to '/' if not set.
    // Assuming URL format like: localhost/mvc_project/public/index.php?url=/products
    $url = isset($_GET['url']) ? '/' . rtrim($_GET['url'], '/') : '/';
    $method = $_SERVER['REQUEST_METHOD'];

    $router->dispatch($url, $method);

} catch (Exception $e) {
    // Graceful Error Handling
    http_response_code(500);
    echo "<h1>Application Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}