<?php

/** @var Router $router */

use Laravel\Lumen\Routing\Router;

$router->group(['prefix' => 'api/v1', 'namespace' => 'Api\v1'], function () use ($router) {
    //Rutas con Middleware Auth0
    $router->group(['prefix' => 'payment'], function () use ($router) {
        $router->post('/', 'PaymentController@store');
    });

    $router->group(['prefix' => 'marketplace'], function () use ($router) {
        $router->post('/assoc', 'MarketPlaceController@assoc');
    });

    $router->group(['prefix' => 'billingparameters'], function () use ($router){
        $router->post('/', 'BillingParameterController@store');
        $router->put('/', 'BillingParameterController@update');
    });

    $router->group(['prefix' => 'invoice'], function () use ($router) {
        $router->post('/', 'MercadoPagoTest@api_test');
    });
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('/info', 'UsersController@show');
    });

    $router->group(['prefix' => 'marketplace-test'], function () use ($router) {
        $router->get('/', 'MercadoPagoTest@marketplace');
    });
    $router->group(['prefix' => 'mercadopago-ipn'], function () use ($router) {
        $router->post('/', 'MercadoPagoTest@ipn_notification');
    });
    $router->group(['prefix' => 'api_test'], function () use ($router) {
        $router->post('/', 'MercadoPagoTest@api_test');
    });
    $router->group(['prefix' => 'mercadopago'], function() use ($router){
        $router->get('/', 'MercadoPagoTest@test');
    });

    $router->group(['prefix' => 'test'], function() use ($router){
        $router->post('/', 'MercadoPagoTest@testPayment');
        $router->get('/', 'TestEmailController@mail');
    });

    $router->group(['prefix' => 'pdf'], function() use ($router){
        $router->get('/', 'PdfController@index');
    });




});


