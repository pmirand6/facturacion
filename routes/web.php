<?php
/** @var Router $router */
use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/mercadopago', function() use ($router){
   return view('client.mercadopago');
});

$router->get('/checkout-pro', function() use ($router){
    return view('client.checkout-pro');
});
$router->group(['namespace' => 'Api\v1'], function() use ($router) {
    $router->post('/create_preference', 'MercadoPagoTestController@testCreatePreference');
    $router->get('/feedback', 'MercadoPagoTestController@testCreatePreference');
});


$router->group(['namespace' => '\Rap2hpoutre\LaravelLogViewer'], function() use ($router) {
    $router->get('logs', 'LogViewerController@index');
});
