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

   $routes->group("eventos", static function ($routes) {
      $routes->get("/", 'Admin\Eventos::index');
      $routes->get("novo", 'Admin\Eventos::novo');
      $routes->get("lista", 'Admin\Eventos::lista');
      $routes->get("participantes", 'Admin\Eventos::participantes');
      $routes->get("deletar/(:num)", 'Admin\V1\Eventos::delete/$1');
   });
});

//ROUTS BACKEND API PROTECTED
$routes->group("api/v1", ['filter' => 'admin-auth'], static function ($routes) {
   $routes->get("me", 'Admin\V1\Login::me');

   $routes->group("eventos", static function ($routes) {
      $routes->get("", 'Admin\V1\Eventos::index');
      $routes->get("(:num)", 'Admin\V1\Eventos::show/$1');
      $routes->post("", 'Admin\V1\Eventos::create');
      $routes->post("(:num)", 'Admin\V1\Eventos::update/$1');
      $routes->delete("(:num)", 'Admin\V1\Eventos::delete/$1');
   });
});

//ROUTS BACKEND API OPEN
$routes->group("api/v1", static function ($routes) {
   $routes->get("sicred", 'Admin\V1\Sicred::index');
});




$routes->get("evento/(:any)", 'Eventos::index/$1');
$routes->post("participantes/(:any)", 'Eventos::participantes/$1');
$routes->post("confirmar-participantes/(:segment)", 'Eventos::confirmParticipante/$1');

$routes->get("checkout/(:segment)", 'Eventos::checkout/$1');

$routes->post("checkout/processar", 'Eventos::teste');

$routes->post('remover-item', 'Eventos::removerItem');

$routes->get("carrinho", 'Eventos::carrinho');
$routes->post("carrinho", 'Eventos::carrinho');