<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/', 'Home::index');
$routes->get('/app', '\App\Controllers\App::index', ['namespace' => 'app']);
$routes->post('/app', '\App\Controllers\App::index');
$routes->get('/logout', 'Home::logout' );
$routes->post('/spreadsheet', 'Home::spreadsheet');
$routes->get('/spreadsheet', 'Home::spreadsheet');