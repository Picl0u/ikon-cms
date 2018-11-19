<?php
use Piclou\Ikcms\Helpers\CustomRoute;
Route::group([
    'middleware' => ['web','ikcms.admin'],
    'prefix' => config("ikcms.adminUrl"),
    'namespace' => 'Piclou\Ikcms\Controllers\Admin'
], function() {
    // Dashboard
    Route::get('', 'AdminController@dashboard')->name('ikcms.admin.dashboard');

    //Administrateurs
    CustomRoute::crud('admins','UsersController','ikcms.admin.users');

    // Slider
    CustomRoute::crud('sliders','SlidersController','ikcms.admin.sliders');
    Route::post("/sliders/updateImage/{uuid}","SlidersController@updateImage")
        ->name("ikcms.admin.sliders.image.update")
        ->where(['uuid' => '[a-z-0-9\-]+']);
    Route::get("/sliders/deleteImage/{uuid}","SlidersController@deleteImage")
        ->name("ikcms.admin.sliders.image.delete")
        ->where(['uuid' => '[a-z-0-9\-]+']);
    Route::get("/sliders/positions","SlidersController@positions")
        ->name("ikcms.admin.sliders.position");
    Route::post("/sliders/positions/store","SlidersController@positionsStore")
        ->name("ikcms.admin.sliders.positions.store");

    //Pages - Catégories
    CustomRoute::crud('pages/categories','PageCategoriesController','ikcms.admin.pagecategories');
    //Pages - Contenus
    CustomRoute::crud('pages/contents','PagesController','ikcms.admin.pages');
    Route::post("/pages/contents/updateImage/{uuid}","PagesController@updateImage")
        ->name("ikcms.admin.pages.image.update")
        ->where(['uuid' => '[a-z-0-9\-]+']);
    Route::get("/pages/contents/deleteImage/{uuid}","PagesController@deleteImage")
        ->name("ikcms.admin.pages.image.delete")
        ->where(['uuid' => '[a-z-0-9\-]+']);
    Route::get("/pages/positions","PagesController@positions")
        ->name("ikcms.admin.pages.position");
    Route::post("/pages/positions/store","PagesController@positionsStore")
        ->name("ikcms.admin.pages.positions.store");

    //Logs - Liste des logs
    Route::get('logs', 'LogsController@log_list')->name('ikcms.admin.logs.list');
    Route::get('log/detail/{id}', 'LogsController@log_show')->name('ikcms.admin.logs.show')->where(['id' => '[0-9\-]+']);
    //Logs - Liste des requêtes
    Route::get('requests', 'LogsController@request_list')->name('ikcms.admin.requests.list');
    Route::get('requests/detail/{id}', 'LogsController@request_show')->name('ikcms.admin.requests.show')->where(['id' => '[0-9\-]+']);
    //Logs - Modèles
    Route::get('models', 'LogsController@model_list')->name('ikcms.admin.models.list');
    Route::get('models/detail/{model}', 'LogsController@model_show')->name('ikcms.admin.models.show');
    Route::get('models/show/{id}', 'LogsController@model_revert')->name('ikcms.admin.models.revert')->where(['id' => '[0-9\-]+']);

    // Paramètres
    Route::get('settings', 'SettingController@index')->name('ikcms.admin.settings.index');
    Route::post('settings/save', 'SettingController@store')->name('ikcms.admin.settings.store');

});

Route::group([
    'middleware' => 'web',
    'prefix' => config("ikcms.adminUrl"),
    'namespace' => 'Piclou\Ikcms\Controllers\Admin'
], function() {
    // Login
    Route::get('login', 'AdminController@login')->name('ikcms.admin.login');
});