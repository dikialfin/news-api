<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// User Register
$router->post('register',"RegisterController@register");

// Get News Data (jika kategori kosong maka ambil headline news)
$router->get('news[/{category}]',"NewsController@getNews");

// Menyukai postingan berita
$router->post('love',"NewsController@loveNews");
