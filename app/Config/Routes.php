<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get("/", 'Login::index');

//Ações de login
$routes->get("login", 'Login::index');
$routes->get("registrar", 'Login::registrar');
$routes->get("confirmar/(:any)", 'Login::confirmar/$1');
$routes->get("recuperar", 'Login::recuperar');
$routes->get("logout", 'Login::logout');

$routes->get("logout", 'Login::logout');

$routes->post("login", 'Admin\V1\Login::login');
$routes->post("registrar", 'Admin\V1\Login::registrar');
$routes->post("confirmar/(:any)", 'Admin\V1\Login::confirmar/$1');


$routes->group("admin", static function ($routes) {
   $routes->get("/", 'Admin::index');
});


