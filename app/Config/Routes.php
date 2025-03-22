<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */



$routes->get("/", 'Login::index');

$routes->get("some-endpoint-to-check", function (){
   echo "ok";
});

//Ações de login
$routes->get("login", 'Login::index');
$routes->get("registrar", 'Login::registrar');
$routes->get("confirmar/(:any)", 'Login::confirmar/$1');
$routes->get("recuperar", 'Login::recuperar');
$routes->get("logout", 'Login::logout');
$routes->get("novasenha/(:any)", 'Login::alterar/$1');

$routes->get("logout", 'Login::logout');

$routes->post("login", 'Admin\V1\Login::login');
$routes->post("registrar", 'Admin\V1\Login::registrar');
$routes->post("recuperar", 'Admin\V1\Login::recuperarSenha');

$routes->post("confirmar/(:any)", 'Admin\V1\Login::confirmar/$1');


$routes->group("admin", ['filter' => 'admin-auth'], static function ($routes) {
   $routes->get("/", 'Admin::index');
});