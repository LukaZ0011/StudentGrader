<?php

if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = '/';
}

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$uri = preg_replace('#^/StudentGrader#', '', $uri);
if ($uri === '' || $uri === false) $uri = '/';


$routes = [
    '/' => __DIR__ . '/controllers/index.php',
    '/about' => __DIR__ . '/controllers/about.php',
    '/contact' => __DIR__ . '/controllers/contact.php'
];

function routeToController($uri, $routes)
{
    if (array_key_exists($uri, $routes)) {
        require $routes[$uri];
    } else {
        abort();
    }
}

function abort($code = 404)
{
    http_response_code($code);

    require __DIR__ . "/views/{$code}.php";

    die();
}

routeToController($uri, $routes);
