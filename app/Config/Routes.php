<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */



$routes->get("/", 'Login::index');

$routes->get("teste", 'Home::teste');

//Ações de login
$routes->get("login", 'Login::index');
$routes->get("registrar", 'Login::registrar');
$routes->get("confirmar/(:any)", 'Login::confirmar/$1');
$routes->get("recuperar", 'Login::recuperar');
$routes->get("logout", 'Login::logout');
$routes->get("novasenha/(:any)", 'Login::alterar/$1');


$routes->get("logout", 'Login::logout');

// CONTROLLERS DE API
$routes->get("google", 'Admin\V1\Login::google');
$routes->get("auth/callback", "Admin\V1\Login::callbackGoogle");

$routes->post("login", 'Admin\V1\Login::login');
$routes->post("registrar", 'Admin\V1\Login::registrar');
$routes->post("recuperar", 'Admin\V1\Login::recuperarSenha');

$routes->post("novasenha", 'Admin\V1\Login::novaSenha');

$routes->post("confirmar/(:any)", 'Admin\V1\Login::confirmar/$1');


//ROUTS FRONT ADMIN
$routes->group("admin", ['filter' => 'admin-auth'], static function ($routes) {
   $routes->get("/", 'Admin::index');

   $routes->group("cursos", static function ($routes) {
      $routes->get("/", 'Admin\Cursos::index');
      $routes->get("novo", 'Admin\Cursos::novo');
      $routes->get("lista", 'Admin\Cursos::lista');
      $routes->get("participantes", 'Admin\Cursos::participantes');
   });
});

$routes->group("api/v1", ['filter' => 'admin-auth'], static function ($routes) {
   $routes->get("me", 'Admin\V1\Login::me');
});