<?php
namespace Piclou\Ikcms\Helpers;
use Illuminate\Support\Facades\Route;

class CustomRoute
{

    public static function crud(string $uri, string $controller, string $name)
    {
        /* Liste */
        Route::get("/{$uri}", "{$controller}@index")
            ->name($name . ".index");
        /* Création */
        Route::get("/{$uri}/create","{$controller}@create")
            ->name($name . ".create");
        Route::post("/{$uri}/create","{$controller}@store")
            ->name($name . ".store");
        /* Edition */
        Route::get("/{$uri}/edit/{uuid}","{$controller}@edit")
            ->name($name . ".edit")
            ->where(['uuid' => '[a-z-0-9\-]+']);
        /* Edition - POST */
        Route::put("/{$uri}/update/{uuid}","{$controller}@update")
            ->name($name . ".update")
            ->where(['uuid' => '[a-z-0-9\-]+']);
        /* Suppression */
        Route::get("/{$uri}/delete/{uuid}","{$controller}@destroy")
            ->name($name . ".delete")
            ->where(['uuid' => '[a-z-0-9\-]+']);

    }
}
